<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Box\Spout\Common\Type;
use Box\Spout\Writer\WriterFactory;

class Agent_Commission extends MY_Controller
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
        $this->load->common('reports/agent_commission', $data);
/*
        //todo when we need pdf, when we send out email? the logic is not clear yet
        $data['style'] = $this->load->view('common/pdf_style',$data, TRUE);
        $mpdf = new mPDF('c');
        //todo may need separate commission pdf view
        $html = $this->load->view('reports/commission', $data, TRUE);
        $mpdf->writeHTML($html);
        $mpdf->Output();
*/
    }

    private function set_data()
    {
    	$beuser = $this->session->userdata ( 'beuser' );
    	$this->load->model('product_model');
        $this->load->model('report_model');
        $this->load->model('paytype_model');
        
        $data ['csrf'] = array (
            'name' => $this->security->get_csrf_token_name (),
            'value' => $this->security->get_csrf_hash ()
        );
        $data['title_txt'] = 'Agent Commission Report';
        $data['top_menu'] = $this->menu_model->load_top_menu();
        $data['menu'] = $this->menu_model->load_meun();
        $data['action_url'] = current_url();

        $data['agent_id'] = $this->input->post('agent_id');
        $data['region_id'] = empty($this->input->post('region_id')) ? $beuser['region_id'] : $this->input->post('region_id');
        $data['payment_date_from'] = $this->input->post('payment_date_from');
        $data['payment_date_to'] = $this->input->post('payment_date_to');
        $data['paied'] = $this->input->post('paied');
        $data['minvalue'] = $this->input->post('minvalue');
        $data['pay_mothed'] = $this->input->post('pay_mothed');
        
        $data['user_list'] = $this->user_model->get_available_user_list();
        $data['paytype_list'] = $this->paytype_model->paytype_list();
        $data['report_data'] = empty($_POST) ? array() : $this->report_model->get_agent_commission_report($data);
        
        $data['export_list'] = base_url ( "reports/agent_commission/export_list" );
        return $data;
	}

    function export_list() {
        $beuser = $this->func_model->verify_login(); 
        $this->load->model('product_model');
        $this->load->model('report_model');
        $data['agent_id'] = empty($this->input->get_post('agent_id')) ? 0 : (int)$this->input->get_post('agent_id');
        $data['region_id'] = empty($this->input->get_post('region_id')) ? $beuser['region_id'] : $this->input->get_post('region_id');
        $data['payment_date_from'] = $this->input->get_post('payment_date_from');
        $data['payment_date_to'] = $this->input->get_post('payment_date_to');
        $data['paied'] = $this->input->get_post('paied');
        $data['minvalue'] = $this->input->get_post('minvalue');
        $data['pay_mothed'] = $this->input->get_post('pay_mothed');
        
        $data['user_list'] = $this->user_model->get_available_user_list();
        $data['report_data'] = $this->report_model->get_agent_commission_report($data);
        //echo "<pre>";print_r($data['report_data']);die('============');

        $w = WriterFactory::create(Type::XLSX); // for XLSX files 
        $kArr = array(
                'username' => 'Username',
        		'agent_name' => 'Agent Name',
                'total_balance' => 'Total Balance',
                'unpaid_premium' => 'Unpaid Premium',
        		'last_paid' => 'Last Paid Date',
        		'receive_type' => 'Pay Method',
                'note' => 'Note');

        $tmpfname = "/tmp/jf_test.xlsx";
        
        $w->openToBrowser("Agent_Commission_Summary_" . date('Ymd') . ".xlsx");
        //$w->openToFile($tmpfname);
        $arr = array();
        foreach ($kArr as $k => $v) { $arr[] = $v; }
        $w->addRow($arr);
        
		$arr = array('Period : ' . $payment_date_from . " - " . $payment_date_to,);
		$w->addRow($arr);
        foreach ($data['report_data'] as $record) {
			$arr = array();
			foreach ($kArr as $k => $v) {
				if ($k == 'last_paid') {
					$arr[] = substr($record[$k], 0, 10);
				} else {
					$arr[] = $record[$k];
				}
			}
			$w->addRow($arr);
		}
		$arr = array('', '','','','','','','');
		$w->addRow($arr);
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
