<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

use Box\Spout\Common\Type;
use Box\Spout\Writer\WriterFactory;
    
// CREATE TABLE backrun(
// 	backrun_id INT NOT NULL AUTO_INCREMENT,
//  is_done TINYINT NOT NULL DEFAULT 0,
//  run_type VARCHAR(16) NOT NULL DEFAULT '',
//  filepath VARCHAR(128) NOT NULL DEFAULT '',
//  para_data TEXT,
// 	require_time DATETIME NOT NULL DEFAULT now(),
// 	done_time DATETIME NOT NULL DEFAULT now(),
//  PRIMARY KEY (backrun_id) )ENGINE=MyISAM DEFAULT CHARSET=utf8;

class Backrun_model extends CI_Model {
	public $error;
	const ORPremium="ORPremium";
	const ORMonthlyPremium="ORMonthlyPremium";
	const SalesReportToAgent="ReportToAgent";
	const SalesReportToInsurer="ReportToInsurer";
	const KEEP_DAYS=7;
	
	/**
	 * Add run job
	 * 
	 * @param string	$run_type   job type
	 * @param string	$para_data  job parameter (json data)
	 * @return int  record id
	 */
	public function add_run($run_type, $para_data) {
    $data = array(
      'run_type' => $run_type, 
      'para_data' => $para_data
    );
		$this->db->insert('backrun', $data);
		$id = $this->db->insert_id();
		return $id;
	}
	
	/**
	 * Get waiting run job
	 * 
	 * @param string	$run_type   job type
	 * @return array
	 */
	public function get_job_list($run_type) {
		$this->db->where('run_type', $run_type);
    $this->db->where("DATE(require_time) > (NOW() - INTERVAL ".SELF::KEEP_DAYS." DAY)");
		return $this->db->get('backrun')->result_array();
	}
	
	/**
	 * Get waiting run job
	 * 
	 * @return array
	 */
	public function get_run() {
		$this->db->where('is_done', 0);
		return $this->db->get('backrun')->result_array();
	}

  public function set_file_done($backrun_id, $filename) {
    $this->db->set('is_done', 1);
    $this->db->set('filepath', $filename);
    $this->db->set('done_time', 'current_time()', false);
    $this->db->where('backrun_id', $backrun_id);
    $this->db->update('backrun');
  }


  public function ORPremium($backrun_id, $data)
  {
		if ((php_sapi_name() !== 'cli')) {
      show_404();
			return ;
		}
    $beuser = $this->user_model->get_user_by_id(1);
    $data['beuser'] = $beuser;

    $this->load->model('product_model');
    $this->load->model('report_model');

    echo "Start run\n";

    $data['report_data'] = $this->report_model->get_premium_report2($data);
    echo "got data\n";

    $status_list = array(
                1 => "Quote",
                2 => "Sold",
                3 => "Paid",
                4 => "Claimed",
                5 => "Cancel",
                6 => "Refund",
                7 => "Changed",
                8 => "Adjust",
              );

    $w = WriterFactory::create(Type::XLSX); // for XLSX files
    $kArr = array(
      'policy' => 'Policy Number',
      'firstname' => 'First Name',
      'lastname' => 'Last Name',
      'province2' => 'Province',
      'status_id' => 'Status',
      'sold_date' => 'Sold Date',
      'add_time' => 'Payment Date',
      'effective_date' => 'Effective Date',
      'expiry_date' => 'Expire Date',
      'totaldays' => 'Number of Days',
      'days_used' => 'Days of Used',
      'sum_insured' => 'Sum Insured',
      'customer_cnt' => 'Quantity',
      'deductible_amount' => 'Deductible Amount',
      'dailyrate' => 'Daily Rate',
      'discount' => 'Discounted Amount',
      'premium' => 'Total',
      'earned' => 'Earned',
      'unearned' => 'Unearned',
      'product_short' => 'Product',
    );

    $filename = "tmppdf/Premium_Report_" . date('Ymd') . ".xlsx";
    if (!empty($backrun_id)) {
      $filename = "tmppdf/Premium_Report_" . $backrun_id . ".xlsx";
    }

    $w->openToFile(DOWNLOADDIR.$filename);
    $w->addRow(array($data['payment_added_from']. " to " . $data['payment_added_to']));
    if (!empty($data['product_short'])) {
      $w->addRow($data['product_short']);
    }

    $arr = array();
    foreach ($kArr as $k => $v) {
      $arr[] = $v;
    }
    $w->addRow($arr);

    $total = $tearned = 0;  $solddate = '';
    foreach ($data['report_data'] as $record) {
      if ($record['ishead']==1) { 
        $solddate = substr($record['add_time'],0,10);
      }
      if ($record['status_id'] == 6) {
        $dte = strtotime($record['expiry_date']);
        $dts = strtotime($record['effective_date']);
        $record['totaldays'] = round(($dte-$dts)/(60 * 60 * 24)) + 1; 
      }
      if (abs($record['premium']) <= 25) {
        $record['premium'] = floatval($record['dailyrate'] * $record['totaldays']);
      }
      if ($record['days_used'] >= $record['totaldays']) {
        $earned = $record['premium'];
        $unearned = 0;
      } else if ($record['days_used'] > 0) {
        $earned = $record['premium']*$record['days_used']/$record['totaldays'];
        $unearned = $record['premium'] - $earned;
      } else {
        $earned = 0;
        $unearned = $record['premium'];
      }
      $total += $record['premium'];
      $tearned += $earned;
      $discount = 0;
      if ($record['totaldays'] >= 365) {
        $discount = $record['totaldays'] * $record['dailyrate'] - $record['premium'];
        if ($discount < 5) {
          $discount = 0;
        }
      }

      $arr = array();

      foreach ($kArr as $k => $v) {
        if ($k == "earned") {
          $arr[] = $earned;
        } else if ($k == "sold_date") {
          $arr[] = $solddate;
        } else if ($k == "status_id") {
          $arr[] = empty($status_list[$record["status_id"]])?(empty($record["status_id"])?"X":$record["status_id"]):$status_list[$record["status_id"]];
        } else if ($k == "add_time") {
          $arr[] = substr($record[$k],0,10);
        } else if ($k == "days_used") {
          $arr[] = ($record[$k]>0)?$record[$k]:0;
        } else if ($k == "discount") {
          $arr[] = number_format($discount,2);
        } else if ($k == "premium") {
          $arr[] = number_format($record['premium'],2);
        } else if ($k == "unearned") {
          $arr[] = number_format($unearned,2);
        } else if ($k == "earned") {
          $arr[] = number_format($earned,2);
        } else if ($k == "deductible_amount") {
          $arr[] = number_format($record[$k], 2);
        } else if ($k == "dailyrate") {
          $arr[] = number_format($record[$k], 2);
        } else {
          $arr[] = $record[$k];
        }
      }
      $w->addRow($arr);
    }
    $arr = array();
    foreach ($kArr as $k => $v) {
      if ($k == "earned") {
        $arr[] = number_format($tearned,2);
      } else if ($k == "unearned") {
        $arr[] = number_format($total - $tearned,2);
      } else if ($k == "premium") {
        $arr[] = number_format($total,2);
      } else if ($k == "deductible_amount") {
        $arr[] = 'Total:';
      } else {
        $arr[] = '';
      }
    }
    $w->addRow($arr);
    $w->close();
    if ($backrun_id) {
      $this->set_file_done($backrun_id, $filename);
    } else {
      echo "to file: ".DOWNLOADDIR.$filename."\n";
    }
  }

  public function ORMonthlyPremium($backrun_id, $data)
  {
		if ((php_sapi_name() !== 'cli')) {
      show_404();
			return ;
		}
    $beuser = $this->user_model->get_user_by_id(1);
    $data['beuser'] = $beuser;

    $this->load->model('product_model');
    $this->load->model('report_model');

    echo "Start run\n";

    $data['report_data'] = $this->report_model->get_premium_report3($data);
    echo "got data\n";

    $status_list = array(
                1 => "Quote",
                2 => "Sold",
                3 => "Paid",
                4 => "Claimed",
                5 => "Cancel",
                6 => "Refund",
                7 => "Changed",
                8 => "Adjust",
              );

    $w = WriterFactory::create(Type::XLSX); // for XLSX files
    $kArr = array(
      'policy' => 'Policy Number',
      'firstname' => 'First Name',
      'lastname' => 'Last Name',
      'province2' => 'Province',
      'status_id' => 'Status',
      'sold_date' => 'Sold Date',
      'add_time' => 'Payment Date',
      'effective_date' => 'Effective Date',
      'expiry_date' => 'Expire Date',
      'totaldays' => 'Number of Days',
      'days_used' => 'Days of Used',
      'sum_insured' => 'Sum Insured',
      'customer_cnt' => 'Quantity',
      'deductible_amount' => 'Deductible Amount',
      'dailyrate' => 'Daily Rate',
      'discount' => 'Discounted Amount',
      'premium' => 'Total',
      'earned' => 'Earned',
      'unearned' => 'Unearned',
      'product_short' => 'Product',
      'plan_type' => 'Plan Type',
      'total_premium' => 'Total Premium',
    );

    $filename = "tmppdf/Monthly_Premium_Report_" . date('Ymd') . ".xlsx";
    if (!empty($backrun_id)) {
      $filename = "tmppdf/Monthly_Premium_Report_" . $backrun_id . ".xlsx";
    }

    $w->openToFile(DOWNLOADDIR.$filename);
    $w->addRow(array($data['payment_added_from']. " to " . $data['payment_added_to']));
    if (!empty($data['product_short'])) {
      $w->addRow($data['product_short']);
    }

    $arr = array();
    foreach ($kArr as $k => $v) {
      $arr[] = $v;
    }
    $w->addRow($arr);

    $total = $tearned = 0;  $solddate = '';
    foreach ($data['report_data'] as $record) {
      if ($record['ishead']>0) { 
        $solddate = substr($record['add_time'],0,10);
      }
      if ($record['status_id'] == 6) {
        $dte = strtotime($record['expiry_date']);
        $dts = strtotime($record['effective_date']);
        $record['totaldays'] = round(($dte-$dts)/(60 * 60 * 24)) + 1; 
      }
      if (abs($record['premium']) <= 25) {
        $record['premium'] = floatval($record['dailyrate'] * $record['totaldays']);
      }
			if ($record['ishead']==2) {
				$earned = 0;
				$unearned = 0;
			} else if ($record['days_used'] >= $record['totaldays']) {
        $earned = $record['premium'];
        $unearned = 0;
      } else if ($record['days_used'] > 0) {
        $earned = $record['premium']*$record['days_used']/$record['totaldays'];
        $unearned = $record['premium'] - $earned;
      } else {
        $earned = 0;
        $unearned = $record['premium'];
      }
      $total += $record['premium'];
      $tearned += $earned;
      $discount = 0;
      // if ($record['totaldays'] >= 365) {
      //   $discount = $record['totaldays'] * $record['dailyrate'] - $record['premium'];
      //   if ($discount < 5) {
      //     $discount = 0;
      //   }
      // }

      $arr = array();

      foreach ($kArr as $k => $v) {
        if ($k == "earned") {
          $arr[] = $earned;
        } else if ($k == "sold_date") {
          $arr[] = $solddate;
        } else if ($k == "status_id") {
          $arr[] = empty($status_list[$record["status_id"]])?(empty($record["status_id"])?"X":$record["status_id"]):$status_list[$record["status_id"]];
        } else if ($k == "add_time") {
          $arr[] = substr($record[$k],0,10);
        } else if ($k == "days_used") {
          $arr[] = ($record[$k]>0)?$record[$k]:0;
        } else if ($k == "discount") {
          $arr[] = number_format($discount,2);
        } else if ($k == "premium") {
          $arr[] = number_format($record['premium'],2);
        } else if ($k == "unearned") {
          $arr[] = number_format($unearned,2);
        } else if ($k == "earned") {
          $arr[] = number_format($earned,2);
        } else if ($k == "deductible_amount") {
          $arr[] = number_format($record[$k], 2);
        } else if ($k == "dailyrate") {
          $arr[] = number_format($record[$k], 2);
        } else if ($k == "plan_type") {
          $arr[] = "Monthly";
        } else if ($k == "total_premium") {
          $arr[] = number_format($record[$k], 2);
        } else {
          $arr[] = $record[$k];
        }
      }
      $w->addRow($arr);
    }
    $arr = array();
    foreach ($kArr as $k => $v) {
      if ($k == "earned") {
        $arr[] = number_format($tearned,2);
      } else if ($k == "unearned") {
        $arr[] = number_format($total - $tearned,2);
      } else if ($k == "premium") {
        $arr[] = number_format($total,2);
      } else if ($k == "deductible_amount") {
        $arr[] = 'Total:';
      } else {
        $arr[] = '';
      }
    }
    $w->addRow($arr);
    $w->close();
    if ($backrun_id) {
      $this->set_file_done($backrun_id, $filename);
    } else {
      echo "to file: ".DOWNLOADDIR.$filename."\n";
    }
  }

  public function SalesReportToAgent($backrun_id, $data)
  {
		if ((php_sapi_name() !== 'cli')) {
      show_404();
			return ;
		}
    $this->load->model('product_model');
    $this->load->model('report_model');

    $beuser = $this->user_model->get_user_by_id(1);
    $data['beuser'] = $beuser;

    $data['agent_id'] = empty($this->input->get_post('agent_id')) ? 0 : (int)$this->input->get_post('agent_id');

    $data['product_short'] = $this->input->get_post('product_short');
    $data['region_id'] = empty($this->input->get_post('region_id')) ? $beuser['region_id'] : $this->input->get_post('region_id');

    $data['product_short'] = $this->input->get_post('product_short');
    $data['payment_added_from'] = $this->input->get_post('payment_added_from');
    $data['payment_added_to'] = $this->input->get_post('payment_added_to');
    $data['payment_date_from'] = $this->input->get_post('payment_date_from');
    $data['payment_date_to'] = $this->input->get_post('payment_date_to');
    
    $data['product_list'] = $this->product_model->get_available_product_list($beuser);
    $data['user_list'] = $this->user_model->get_available_user_list($beuser);
    $data['report_data'] = $this->report_model->get_sales_report_agent($data);
    
    //echo "<pre>";
    //print_r($data['report_data']);die('============');

    $w = WriterFactory::create(Type::XLSX); // for XLSX files
    $kArr = array(
            'added' => 'Payment Date',
            'policy' => 'Policy No.',
            'up_insuer' => 'Insurer',
            'full_name' => 'Product',
            'insured' => 'Insured Name',
            'effective_date' => 'Effective Date',
            'expiry_date' => 'Expiry Date',
            'totaldays' => 'Number of Days',
            'dailyrate' => 'Daily Rate',
            'amount' => 'Policy Premium',
            'net_premium' => 'Net Premium',
    );
    if (($beuser['user_id'] == 2810) || ($beuser['user_id'] == 3297)) {
      $kArr['pay_mothed'] = 'Pay Mothed';
      $kArr['contact_email'] = 'Contact Email';
      $kArr['contact_phone'] = 'Contact Phone';
    }

    if (($beuser['user_id'] == 450) || ($beuser['user_id'] == 2018)) {
      $kArr['note'] = "Note";
    }

    $filename = "tmppdf/SalesReportToAgent_" . date('Ymd') . ".xlsx";
    if (!empty($backrun_id)) {
      $filename = "tmppdf/SalesReportToAgent_" . $backrun_id . ".xlsx";
    }
    
    $w->openToFile(DOWNLOADDIR.$filename);
    //$w->openToFile($tmpfname);
    foreach ($data['report_data'] as $data) {
      $arr = array();
      foreach ($kArr as $k => $v) { $arr[] = $v; } 
        $w->addRow($arr);
        foreach ($data['records'] as $record) {
          $arr = array();
          foreach ($kArr as $k => $v) {
            if ($k == 'added') {
              $arr[] = substr($record[$k], 0, 10);
            } else if ($k == 'net_premium') {
              $arr[] = $record['amount'] - $record['commission'];
            } else {
              $arr[] = $record[$k];
            }
          } 
          $w->addRow($arr);
        }
        $arr = array('Total Premium: $' . $data['data']['policy_premium'], '','','Total Net Premium: $' . $data['data']['net_premium'],'','','Username:' . $data['data']['agent_username'] . ' Email: ' . $data['data']['agent_email']);
        $w->addRow($arr);
        $arr = array('', '','','','','','','');
        $w->addRow($arr);
    }
    $w->close();
    if ($backrun_id) {
      $this->set_file_done($backrun_id, $filename);
    } else {
      echo "to file: ".DOWNLOADDIR.$filename."\n";
    }
  }

  public function SalesReportToInsurer($backrun_id, $data)
  {
		if ((php_sapi_name() !== 'cli')) {
      show_404();
			return ;
		}
    $this->load->model('product_model');
    $this->load->model('report_model');

    $beuser = $this->user_model->get_user_by_id(1);
    $data['beuser'] = $beuser;

    $data['product_list'] = $this->product_model->get_available_product_list($beuser);
    $data['user_list'] = $this->user_model->get_available_user_list($beuser);
    $data['report_data'] = $this->report_model->get_sales_report_insurer($data);
    
    //echo "<pre>";
    //print_r($data['report_data']);die('============');

    $w = WriterFactory::create(Type::XLSX); // for XLSX files
    $kArr = array(
            'added' => 'Payment Date',
            'policy' => 'Policy No.',
            'up_insuer' => 'Insurer',
            'full_name' => 'Product',
            'insured' => 'Insured Name',
            'effective_date' => 'Effective Date',
            'expiry_date' => 'Expiry Date',
            'totaldays' => 'Number of Days',
            'dailyrate' => 'Daily Rate',
            'amount' => 'Policy Premium',
            'net_premium' => 'Net Premium',
    );
    if (($beuser['user_id'] == 2810) || ($beuser['user_id'] == 3297)) {
      $kArr['pay_mothed'] = 'Pay Mothed';
      $kArr['contact_email'] = 'Contact Email';
      $kArr['contact_phone'] = 'Contact Phone';
    }

    if (($beuser['user_id'] == 450) || ($beuser['user_id'] == 2018)) {
      $kArr['note'] = "Note";
    }

    $filename = "tmppdf/SalesReportToInsurer_" . date('Ymd') . ".xlsx";
    if (!empty($backrun_id)) {
      $filename = "tmppdf/SalesReportToInsurer_" . $backrun_id . ".xlsx";
    }
    
    $w->openToFile(DOWNLOADDIR.$filename);
    //$w->openToFile($tmpfname);
    foreach ($data['report_data'] as $data) {
      $arr = array();
      foreach ($kArr as $k => $v) { $arr[] = $v; } 
        $w->addRow($arr);
        foreach ($data['records'] as $record) {
          $arr = array();
          foreach ($kArr as $k => $v) {
            if ($k == 'added') {
              $arr[] = substr($record[$k], 0, 10);
            } else if ($k == 'net_premium') {
              $arr[] = $record['amount'] - $record['commission'];
            } else {
              $arr[] = $record[$k];
            }
          } 
          $w->addRow($arr);
        }
        $arr = array('Total Premium: $' . $data['data']['policy_premium'], '','','Total Net Premium: $' . $data['data']['net_premium'],'','','Username:' . $data['data']['agent_username'] . ' Email: ' . $data['data']['agent_email']);
        $w->addRow($arr);
        $arr = array('', '','','','','','','');
        $w->addRow($arr);
    }
    $w->close();
    if ($backrun_id) {
      $this->set_file_done($backrun_id, $filename);
    } else {
      echo "to file: ".DOWNLOADDIR.$filename."\n";
    }
  }
}
