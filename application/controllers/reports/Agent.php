<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Box\Spout\Common\Type;
use Box\Spout\Writer\WriterFactory;

class Agent extends MY_Controller
{
    /**
     * Index Page for this controller.
     */
    public function index()
    {
    	$beuser = $this->func_model->verify_login();
        $data = $this->set_data();
        $data['beuser'] = $beuser;
        $this->load->model('region_model');
        $data['regions'] = $this->region_model->get_regions();
        $this->load->common('reports/agent', $data);
    }

    private function set_data()
    {
    	$beuser = $this->session->userdata ( 'beuser' );
        $this->load->model('product_model');
        $this->load->model('report_model');
        $this->load->model('backrun_model');

        $data ['csrf'] = array (
            'name' => $this->security->get_csrf_token_name (),
            'value' => $this->security->get_csrf_hash ()
        );
        $data['title_txt'] = 'Sales Report to Agent';
        $data['top_menu'] = $this->menu_model->load_top_menu();
        $data['menu'] = $this->menu_model->load_meun();
        $data['action_url'] = current_url();

        $data['agent_id'] = empty($this->input->post('agent_id')) ? 0 : (int)$this->input->post('agent_id');
        $data['region_id'] = empty($this->input->post('region_id')) ? $beuser['region_id'] : $this->input->post('region_id');

        $data['product_short'] = $this->input->post('product_short');
        $data['payment_added_from'] = $this->input->post('payment_added_from');
        $data['payment_added_to'] = $this->input->post('payment_added_to');
        $data['payment_date_from'] = $this->input->post('payment_date_from');
        $data['payment_date_to'] = $this->input->post('payment_date_to');
        
        $data['product_list'] = $this->product_model->get_available_product_list();
        $data['user_list'] = $this->user_model->get_available_user_list();
        if ($this->input->post('submit')) {
          $data['report_data'] = empty($_POST) ? array() : $this->report_model->get_sales_report_agent($data);
        } else {
          $para_data = array();
          $para_data['agent_id'] = empty($this->input->post('agent_id')) ? 0 : (int)$this->input->post('agent_id');
          $para_data['region_id'] = empty($this->input->post('region_id')) ? $beuser['region_id'] : $this->input->post('region_id');
  
          $para_data['product_short'] = $this->input->post('product_short');
          $para_data['payment_added_from'] = $this->input->post('payment_added_from');
          $para_data['payment_added_to'] = $this->input->post('payment_added_to');
          $para_data['payment_date_from'] = $this->input->post('payment_date_from');
          $para_data['payment_date_to'] = $this->input->post('payment_date_to');
          $this->backrun_model->add_run(Backrun_model::SalesReportToAgent, json_encode($para_data));
          $data['report_data'] = array();
        }
        $data['download_request'] = $this->backrun_model->get_job_list(Backrun_model::SalesReportToAgent);
        $data['export_list'] = base_url ( "reports/agent/export_list" );
        return $data;
    }

    function export_list() {
        $beuser = $this->func_model->verify_login(); 
        $this->load->model('product_model');
        $this->load->model('report_model');
        $data['agent_id'] = empty($this->input->get_post('agent_id')) ? 0 : (int)$this->input->get_post('agent_id');

        $data['product_short'] = $this->input->get_post('product_short');
        $data['region_id'] = empty($this->input->get_post('region_id')) ? $beuser['region_id'] : $this->input->get_post('region_id');

        $data['product_short'] = $this->input->get_post('product_short');
        $data['payment_added_from'] = $this->input->get_post('payment_added_from');
        $data['payment_added_to'] = $this->input->get_post('payment_added_to');
        $data['payment_date_from'] = $this->input->get_post('payment_date_from');
        $data['payment_date_to'] = $this->input->get_post('payment_date_to');
        
        $data['product_list'] = $this->product_model->get_available_product_list();
        $data['user_list'] = $this->user_model->get_available_user_list();
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

        $tmpfname = "/tmp/jf_test.xlsx";
        
        $w->openToBrowser("Sales_Report_to_Agent_" . date('Ymd') . ".xlsx");
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
        /*
        header('Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="Policy' . date('Ymd') . '.xlsx"');
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Pragma: no-cache');
        readfile($tmpfname);
        */
        //unlink($tmpfname);
    }
    
    public function report()
    {
      if ((php_sapi_name() !== 'cli')) {
        show_404();
        return ;
      }
      $this->load->model('backrun_model');

      $data['agent_id'] = 0;
      $data['region_id'] = 0;

      $data['product_short'] = '';
      $data['payment_added_from'] = '2023-11-01';
      $data['payment_added_to'] = '2024-11-30';
      $data['payment_date_from'] = '';
      $data['payment_date_to'] = '';

      $data["run_type"] = Backrun_model::SalesReportToAgent;
  
      $this->backrun_model->SalesReportToAgent(0, $data);
    }
  }
