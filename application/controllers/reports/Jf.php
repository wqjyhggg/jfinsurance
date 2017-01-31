<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Box\Spout\Common\Type;
use Box\Spout\Writer\WriterFactory;
class Jf extends MY_Controller
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
        $this->load->common('reports/jf', $data);
    }

    private function set_data()
    {
    	$beuser = $this->session->userdata ( 'beuser' );
        $this->load->model('product_model');
        $this->load->model('report_model');

        $data ['csrf'] = array (
            'name' => $this->security->get_csrf_token_name (),
            'value' => $this->security->get_csrf_hash ()
        );
        $data['title_txt'] = 'Sales Report to Jf';
        $data['top_menu'] = $this->menu_model->load_top_menu();
        $data['menu'] = $this->menu_model->load_meun();
        $data['action_url'] = current_url();

        $data['agent_id'] = $this->input->post('agent_id');
        $data['region_id'] = empty($this->input->post('region_id')) ? $beuser['region_id'] : $this->input->post('region_id');
        $data['product_short'] = $this->input->post('product_short');
        $data['payment_added_from'] = $this->input->post('payment_added_from');
        $data['payment_added_to'] = $this->input->post('payment_added_to');
        $data['payment_date_from'] = $this->input->post('payment_date_from');
        $data['payment_date_to'] = $this->input->post('payment_date_to');
        
        $data['product_list'] = $this->product_model->get_available_product_list();
        $data['user_list'] = $this->user_model->get_available_user_list();
        $data['report_data'] = empty($_POST) ? array() : $this->report_model->get_sales_report_jf($data);

        $data['export_list'] = base_url ( "reports/jf/export_list" );
        return $data;
	}

     function export_list() {
        $beuser = $this->func_model->verify_login(); 
        $this->load->model('product_model');
        $this->load->model('report_model');
        
        $data['agent_id'] = $this->input->get_post('agent_id');
        $data['region_id'] = empty($this->input->get_post('region_id')) ? $beuser['region_id'] : $this->input->post('region_id');
        $data['product_short'] = $this->input->get_post('product_short');
        $data['payment_added_from'] = $this->input->get_post('payment_added_from');
        $data['payment_added_to'] = $this->input->get_post('payment_added_to');
        $data['payment_date_from'] = $this->input->get_post('payment_date_from');
        $data['payment_date_to'] = $this->input->get_post('payment_date_to');
        
        $data['user_list'] = $this->user_model->get_available_user_list();
        $data['report_data'] = $this->report_model->get_sales_report_jf($data);

        $w = WriterFactory::create(Type::XLSX); // for XLSX files
        $kArr = array(
                'policy' => 'Policy No.',
        		'apply_date' => 'Apply Date',
                'pay_date' => 'Pay Date',
                'invoice_num' => 'Invoice Num',
                'up_insuer' => 'InsurerCoName',
                'product_short' => 'Product',
                'insured' => 'Insured Name',
                'effective_date' => 'Effective Date',
                'expiry_date' => 'Expiry Date',
                'totaldays' => 'Number of Days',
                'dailyrate' => 'Daily Rate',
        		'premium' => 'Policy Premium',
                'amount' => 'Pay Amount',
                'ispaid' => 'Paied',
        		'commission_rate' => 'Commission Rate',
                'net_premium' => 'Net Amount',
                'commission' => 'Commission Amount');
                
        $tmpfname = "/tmp/jf_test.xlsx";
        
		if (!empty($data['report_data'])) {
			$w->openToBrowser("Sales_Report_to_JF_" . date('Ymd') . ".xlsx");
			$arr = array('Total Payment: $' . $data['report_data']['payment'] . '   Total Commission: $' . $data['report_data']['commission']);
			unset($data['report_data']['payment']);
			unset($data['report_data']['commission']);
			$w->addRow($arr);
			
			//$w->openToFile($tmpfname);
			foreach ($data['report_data'] as $datas) {
				$arr = array('');
				$w->addRow($arr);
				$w->addRow(array_values($arr));
				
				foreach ($datas['results'] as $record) {
                    $arr = array();
                    foreach ($kArr as $k => $v) {
                    	if ($k == 'ispaid') {
                    		$arr[] = ($record[$k] == 1) ? 'Y' : '-';
                    	} else if ($k == 'commission_rate') {
                            if (abs($record['amount']) > 0.009) {
                            	$arr[] = sprintf("%0.2f", $record['commission'] * 100 / $record['amount']);
                            } else {
                            	$arr[] = '';
                            }
                    	} else if ($k == 'net_premium') {
                    		$arr[] = $record['amount'] - $record['commission'];
                    	} else {
                    		$arr[] = $record[$k];
                    	}
                    } 
                    $w->addRow($arr);
                }
                
                $arr = array('AgentID: ' . $datas['agent']['user_id'] . ' ( ' . $datas['agent']['lastname'] . ', ' . $datas['agent']['lastname'] . ' )   Total Payment: $' . $datas['payment'] . '   Total Net Payment: $' . ($datas['payment'] - $datas['commission']) . '   Total Commission: $' . $datas['commission']);
                $w->addRow($arr);     
            }
           
            $arr = array('');
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
}
