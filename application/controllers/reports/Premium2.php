<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Box\Spout\Common\Type;
use Box\Spout\Writer\WriterFactory;

class Premium2 extends MY_Controller
{
  /**
   * Index Page for this controller.
   */
  public function index()
  {
    $beuser = $this->func_model->verify_login();
    $data = array();
    $data['beuser'] = $beuser;

    $this->load->model('product_model');
    $this->load->model('report_model');
    $this->load->model('backrun_model');

    $data['csrf'] = array(
      'name' => $this->security->get_csrf_token_name(),
      'value' => $this->security->get_csrf_hash()
    );
    $data['title_txt'] = 'OR Premium Report';
    $data['top_menu'] = $this->menu_model->load_top_menu();
    $data['menu'] = $this->menu_model->load_meun();
    $data['action_url'] = current_url();

    $product_short = $this->input->post('product_short');
    $data['payment_added_from'] = empty($this->input->post('payment_added_from'))?date("Y-m-d"):$this->input->post('payment_added_from');
    $data['payment_added_to'] = empty($this->input->post('payment_added_to'))?date("Y-m-d"):$this->input->post('payment_added_to');
    $data['earned_to'] = empty($this->input->post('earned_to'))?date("Y-m-d"):$this->input->post('earned_to');
    $data['product_short'] = empty($product_short)?array():array_keys($product_short);

    $data['report_data'] = array();
    if ($this->input->post('export')) {
      $data['report_data'] = $this->report_model->get_premium_report2($data);
      return $this->export_list($data);
    } else if ($this->input->post('request')) {
      $para_data = [];
      $para_data['payment_added_from'] = $data['payment_added_from'];
      $para_data['payment_added_to'] = $data['payment_added_to'];
      $para_data['earned_to'] = $data['earned_to'];
      $para_data['product_short'] = $data['product_short'];
      $this->backrun_model->add_run(Backrun_model::ORPremium, json_encode($para_data));
    } else if ($this->input->post('submit')) {
      $data['report_data'] = $this->report_model->get_premium_report2($data);
    }
    //echo "<pre>";
    //print_r($data); //XXXXXXXXXXXXX
    //echo "</pre>";
    //die("XXXX");
    $data['download_url'] = base_url("tmppdf");
    $data['download_request'] = $this->backrun_model->get_job_list(Backrun_model::ORPremium);
    $data['product_list'] = $this->product_model->get_available_product_list();

    $this->load->common('reports/premium2', $data);
  }

  private function export_list($data)
  {
    //echo "<pre>";
    //print_r($data['report_data']);die('============');
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

    $w->openToBrowser("Premium_Report_" . date('Ymd') . ".xlsx");
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
      // if ($record['status_id'] == 6) { $dte = strtotime($record['expiry_date']); $dts = strtotime($record['effective_date']); $record['totaldays'] = round(($dte-$dts)/(60 * 60 * 24)) + 1; };
      // $premium = floatval($record['dailyrate'] * $record['totaldays']);
      // $dispremium = abs($premium) - abs($record['premium']);
      // if ($dispremium < 0) $dispremium = 0;
      // if (abs($premium) > abs($record['premium'])) { $premium = floatval($record['premium']); }
      // $earned = ($record['days_used']>0)? ($premium*floatval($record['days_used'])/floatval($record['totaldays'])) : 0;
      // $unearned = number_format($premium - $earned, 2);
      // $total += $premium;
      // $tearned += $earned;
      // if ($record['ishead']==1) { $solddate = substr($record['add_time'],0,10); }
      // $dispremium = number_format($dispremium,2);
      // $premium = number_format($premium,2);
      // $earned = number_format($earned,2);
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
  }

  public function report()
  {
		if ((php_sapi_name() !== 'cli')) {
      show_404();
			return ;
		}
    $this->load->model('backrun_model');

    $data['payment_added_from'] = '2022-04-01';
    $data['payment_added_to'] = '2024-09-30';
    $data['earned_to'] = '2024-09-30';
    $data['product_short'] = array("JFGD","JFOS","JFPL","JFSL","JFVTC");
    $data["run_type"] == Backrun_model::ORPremium;

    $this->backrun_model->ORPremium(0, $data);
  }
}
