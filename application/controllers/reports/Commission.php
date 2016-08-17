<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Box\Spout\Common\Type;
use Box\Spout\Writer\WriterFactory;
class Commission extends MY_Controller
{
	/**
	 * Index Page for this controller.
	 */
	public function index()
	{
        $beuser = $this->func_model->verify_login();
        $data = $this->set_data();
		$this->load->common('reports/commission', $data);
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
        $this->load->model('product_model');
        $this->load->model('report_model');

        $data ['csrf'] = array (
            'name' => $this->security->get_csrf_token_name (),
            'value' => $this->security->get_csrf_hash ()
        );
        $data['title_txt'] = 'Commission Report';
        $data['top_menu'] = $this->menu_model->load_top_menu();
        $data['menu'] = $this->menu_model->load_meun();
        $data['action_url'] = current_url();

        $data['agent_id'] = $this->input->post('agent_id');
        $data['product_short'] = $this->input->post('product_short');
        $data['application_date_from'] = $this->input->post('application_date_from', true);
        $data['application_date_to'] = $this->input->post('application_date_to', true);
        $data['create_date_from'] = $this->input->post('create_date_from');
        $data['create_date_to'] = $this->input->post('create_date_to');
        $data['effective_date_from'] = $this->input->post('effective_date_from');
        $data['effective_date_to'] = $this->input->post('effective_date_to');
        $data['payment_update_date_from'] = $this->input->post('payment_update_date_from');
        $data['payment_update_date_to'] = $this->input->post('payment_update_date_to');

        $data['product_list'] = $this->product_model->get_available_product_list();
        $data['user_list'] = $this->user_model->get_available_user_list();
        $data['report_data'] = empty($_POST) ? array() : $this->report_model->get_commission_report($data);
       
        $data['export_list'] = base_url ( "reports/commission/export_list" );
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
        $data['create_date_from'] = $this->input->get_post('create_date_from');
        $data['create_date_to'] = $this->input->get_post('create_date_to');
        $data['effective_date_from'] = $this->input->get_post('effective_date_from');
        $data['effective_date_to'] = $this->input->get_post('effective_date_to');
        $data['payment_update_date_from'] = $this->input->get_post('payment_update_date_from');
        $data['payment_update_date_to'] = $this->input->get_post('payment_update_date_to');
        $data['product_list'] = $this->product_model->get_available_product_list();
        $data['user_list'] = $this->user_model->get_available_user_list();
        $data['report_data'] = $this->report_model->get_commission_report($data);
        
        //echo "<pre>";
        //print_r($data['report_data']);die('============');

        $w = WriterFactory::create(Type::XLSX); // for XLSX files
        $kArr = array(
                'apply_date' => 'Payment Date',
                'policy' => 'Policy Number',
                'paid_status' => 'Paid Status',
                'insurerCoName' => 'Insurer',
                'insured_name' => 'Customer Name',
                'effective_date' => 'Effective Date',
                'expiry_date' => 'Expiry Date',
                'total_days' => 'Trip Length',
                'policy_premium' => 'Total Premium',
                'payment_status' => 'Payment Status',
                'commission_rate' => 'Commission Rate(%)',
                'commission_amount' => 'Commission Amount',
                'commission_status' => 'Commission Status');

        $tmpfname = "/tmp/jf_test.xlsx";
        
        $w->openToBrowser("Commission_Report_" . date('Ymd') . ".xlsx");
        //$w->openToFile($tmpfname);
        
        $date_from = $data['report_data']['period']['from'];
        $date_to = $data['report_data']['period']['to'];

        foreach ($data['report_data']['data'] as $datas) {
            $arr = array('Agent Name:' , $datas['agency']['agent_name'], '','', 'Payment Method: ', $datas['agency']['payment_method']);
            $w->addRow($arr);

            $arr = array('','','','','Mailling Addrerss: ', $datas['agency']['address'] . ',' . $datas['agency']['province'] . ',' . $datas['agency']['postal_code']);
            $w->addRow($arr);
           
            $arr = array('Commission Cheque Title: ', $datas['agency']['cheque_title']);
            $w->addRow($arr);

            $arr = array('', '');
            $w->addRow($arr);

            $arr = array();
            foreach ($kArr as $k => $v) { $arr[] = $v; } 
            $w->addRow($arr);

            foreach ($datas['records'] as $record) {
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
