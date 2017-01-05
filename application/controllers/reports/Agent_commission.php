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
        $data['product_short'] = $this->input->post('product_short');
        $data['application_date_from'] = $this->input->post('application_date_from', true);
        $data['application_date_to'] = $this->input->post('application_date_to', true);
        $data['arrival_date_from'] = $this->input->post('arrival_date_from');
        $data['arrival_date_to'] = $this->input->post('arrival_date_to');
        $data['effective_date_from'] = $this->input->post('effective_date_from');
        $data['effective_date_to'] = $this->input->post('effective_date_to');
        $data['expiry_date_from'] = $this->input->post('expiry_date_from');
        $data['expiry_date_to'] = $this->input->post('expiry_date_to');
        
        $data['payment_method'] = $this->input->post('payment_method');
        $data['payment_date_from'] = $this->input->post('payment_date_from');
        $data['payment_date_to'] = $this->input->post('payment_date_to');
        $data['paied'] = $this->input->post('paied');
        $data['minvalue'] = $this->input->post('minvalue');
        
        $data['user_list'] = $this->user_model->get_available_user_list();
        $data['report_data'] = empty($_POST) ? array() : $this->report_model->get_agent_commission_report($data);
       
        $data['export_list'] = base_url ( "reports/agent_commission/export_list" );
        $data['export_form'] = $this->load->view ( 'reports/agent_export', $data, true);
        return $data;
	}

    function export_list() {
        $beuser = $this->func_model->verify_login(); 
        $this->load->model('product_model');
        $this->load->model('report_model');
        $data['agent_id'] = empty($this->input->get_post('agent_id')) ? 0 : (int)$this->input->get_post('agent_id');
        $data['region_id'] = empty($this->input->get_post('region_id')) ? $beuser['region_id'] : $this->input->get_post('region_id');
        $data['payment_method'] = $this->input->get_post('payment_method');
        $data['payment_date_from'] = $this->input->get_post('payment_date_from');
        $data['payment_date_to'] = $this->input->get_post('payment_date_to');
        $data['paied'] = $this->input->get_post('paied');
        $data['minvalue'] = $this->input->get_post('minvalue');
        
        $data['user_list'] = $this->user_model->get_available_user_list();
        $data['report_data'] = $this->report_model->get_agent_commission_report($data);
        
        //echo "<pre>";
        //print_r($data['report_data']);die('============');

        $w = WriterFactory::create(Type::XLSX); // for XLSX files 
        $kArr = array(
                'agent_name' => 'Agent',
                'total_balance' => 'Total Balance',
                'receive_type' => 'Pay Method',
                'note' => 'Note');

        $tmpfname = "/tmp/jf_test.xlsx";
        
        $w->openToBrowser("Agent_Commission_Report_" . date('Ymd') . ".xlsx");
        //$w->openToFile($tmpfname);
        
        foreach ($data['report_data']['data'] as $datas) {
        	$arr = array();
            foreach ($kArr as $k => $v) { $arr[] = $v; } 
            $w->addRow($arr);

            foreach ($datas as $record) {
                $arr = array();
                foreach ($kArr as $k => $v) { $arr[] = $record[$k]; } 
                $w->addRow($arr);
            }

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
}
