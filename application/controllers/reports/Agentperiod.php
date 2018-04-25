<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Box\Spout\Common\Type;
use Box\Spout\Writer\WriterFactory;
class Agentperiod extends MY_Controller
{
	/**
	 * Index Page for this controller.
	 */
	public function index()
	{
        $beuser = $this->func_model->verify_login();
        $data = $this->set_data();
        $data['beuser'] = $beuser;

        $this->load->common('reports/agentperiod', $data);
    }

    private function set_data()
    {
    	$beuser = $this->session->userdata ( 'beuser' );
        $this->load->model('report_model');

        $data ['csrf'] = array (
            'name' => $this->security->get_csrf_token_name (),
            'value' => $this->security->get_csrf_hash ()
        );
        $data['title_txt'] = 'Sales Report in period';
        $data['top_menu'] = $this->menu_model->load_top_menu();
        $data['menu'] = $this->menu_model->load_meun();
        $data['action_url'] = current_url();

        $data['payment_added_from'] = $this->input->post('payment_added_from');
        $data['payment_added_to'] = $this->input->post('payment_added_to');
        $data['payment_date_from'] = $this->input->post('payment_date_from');
        $data['payment_date_to'] = $this->input->post('payment_date_to');
        $data['report_data'] = empty($_POST) ? array() : $this->report_model->get_report_in_period($data);
        
        $data['export_list'] = base_url ( "reports/agentperiod/export_list" );
        return $data;
	}

     function export_list() {
        $beuser = $this->func_model->verify_login(); 
        $this->load->model('product_model');
        $this->load->model('report_model');
        
        $data['payment_added_from'] = $this->input->get_post('payment_added_from');
        $data['payment_added_to'] = $this->input->get_post('payment_added_to');
        $data['payment_date_from'] = $this->input->get_post('payment_date_from');
        $data['payment_date_to'] = $this->input->get_post('payment_date_to');
        
        $data['report_data'] = $this->report_model->get_report_in_period($data);

        $w = WriterFactory::create(Type::XLSX); // for XLSX files
        $kArr = array(
                'user_id' => 'ID',
        		'username' => 'Username',
                'email' => 'Email',
                'firstname' => 'Firstname',
                'lastname' => 'Lastname',
                'receive_type' => 'Pay Type',
                'pay_type' => 'Allowed Pay method');
                
        $tmpfname = "/tmp/period_report.xlsx";
        
		if (!empty($data['report_data'])) {
			$w->openToBrowser("Sales_in_Peroid_" . date('Ymd') . ".xlsx");
			
			$w->addRow(array_values($kArr));
			foreach ($data['report_data'] as $record) {
                $arr = array();
                foreach ($kArr as $k => $v) {
               		$arr[] = $record[$k];
                } 
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
