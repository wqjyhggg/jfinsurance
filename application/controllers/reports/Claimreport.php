<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Box\Spout\Common\Type;
use Box\Spout\Writer\WriterFactory;

class Claimreport extends MY_Controller
{
	/**
	 * Index Page for this controller.
	 */
	public function index()
	{
        $beuser = $this->func_model->verify_login();
        $data = $this->set_data();
        $this->load->common('reports/claim', $data);
    }

    private function set_data()
    {
        $this->load->model('product_model');
        $this->load->model('report_model');

        $data ['csrf'] = array (
            'name' => $this->security->get_csrf_token_name (),
            'value' => $this->security->get_csrf_hash ()
        );
        $data['title_txt'] = 'Claim Report';
        $data['top_menu'] = $this->menu_model->load_top_menu();
        $data['menu'] = $this->menu_model->load_meun();
        $data['action_url'] = current_url();

        $data['agent_id'] = $this->input->post('agent_id');
        $data['product_short'] = $this->input->post('product_short');
        $data['application_date_from'] = $this->input->post('application_date_from');
        $data['application_date_to'] = $this->input->post('application_date_to');
        //$data['create_date_from'] = empty($this->input->post('create_date_from')) ? date('Y-m-01') : $this->input->post('create_date_from', true);
        //$data['create_date_to'] = empty($this->input->post('create_date_to')) ? date("Y-m-d") : $this->input->post('create_date_to', true);
        $data['effective_date_from'] = $this->input->post('effective_date_from');
        $data['effective_date_to'] = $this->input->post('effective_date_to');
        $data['payment_update_date_from'] = $this->input->post('payment_update_date_from');
        $data['payment_update_date_to'] = $this->input->post('payment_update_date_to');

        $data['product_list'] = $this->product_model->get_available_product_list();
        $data['user_list'] = $this->user_model->get_available_user_list();
        $data['report_data'] = empty($_POST) ? array() : $this->report_model->get_claim_report($data);
        
        $data['export_list'] = base_url ( "reports/claimreport/export_list" );
        $data['export_form'] = $this->load->view ( 'reports/agent_export', $data, true);
        return $data;
	}

    function export_list() {
        $beuser = $this->func_model->verify_login(); 
        $this->load->model('product_model');
        $this->load->model('report_model');
        $data['agent_id'] = empty($this->input->get_post('agent_id')) ? 0 : (int)$this->input->get_post('agent_id');
        $data['product_short'] = $this->input->get_post('product_short');

        $data['application_date_from'] = $this->input->get_post('application_date_from');
        $data['application_date_to'] = $this->input->get_post('application_date_to');
        //$data['create_date_from'] = $this->input->get_post('create_date_from');
        //$data['create_date_to'] = $this->input->get_post('create_date_to');
        $data['effective_date_from'] = $this->input->get_post('effective_date_from');
        $data['effective_date_to'] = $this->input->get_post('effective_date_to');
        $data['payment_update_date_from'] = $this->input->get_post('payment_update_date_from');
        $data['payment_update_date_to'] = $this->input->get_post('payment_update_date_to');
        $data['product_list'] = $this->product_model->get_available_product_list();
        $data['user_list'] = $this->user_model->get_available_user_list();
        $data['report_data'] = $this->report_model->get_claim_report($data);
        
        //echo "<pre>";
       // print_r($data['report_data']['data']);die('============');

        $w = WriterFactory::create(Type::XLSX); // for XLSX files
        $kArr = array(
                'policy_number' => 'Policy Number',
                'deductible_amount' => 'Deductible',
                'customer_name' => 'Customer Name',
                'birthday' => 'Date of Birth',
                'address' => 'Address',
                'city' => 'City',
                'province' => 'Province',
                'postcode' => 'Postal Code',
                'gender' => 'Gender',
                'agent_name' => 'Agent Name',
                'claim_number' => 'Claim Number',
                'claim_date' => 'Claim Date',
                'service_date' => 'Service Date',
                'diagnosis' => 'Diagnosis',
                'name' => 'Coverage Code',
                'claimed' => 'Claim Amount',
                'paid' => 'Amount Paid',
                'amount_received' => 'Amount Received',
                'cheque_number' => 'Cheque Number',
                'cashed_date' => 'Cheque Cash Day',
                'pay_to' => 'Payee Name',
                'external_note' => 'External Notes');

        $tmpfname = "/tmp/jf_test.xlsx";
        
        $w->openToBrowser("Claim_Report_" . date('Ymd') . ".xlsx");
        //$w->openToFile($tmpfname);

        
        $date_from = $data['report_data']['period']['from'];
        $date_to = $data['report_data']['period']['to'];

        foreach ($data['report_data']['data'] as $datas) {
            $arr = array('Date From: ', $date_from, 'To: ', $date_to);
            $w->addRow($arr);
            $arr = array('Agent: ', $datas['agency']);
            $w->addRow($arr);
            $arr = array('','');
            $w->addRow($arr);
            $arr = array();
            foreach ($kArr as $k => $v) { $arr[] = $v; } 
            $w->addRow($arr);
            foreach ($datas['records'] as $data) {
                $w->addRow($data);
            }
            $arr = array('','');
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
