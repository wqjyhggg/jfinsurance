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
        $data['beuser'] = $beuser;
        $this->load->model('region_model');
        $data['regions'] = $this->region_model->get_regions();
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
    	$beuser = $this->session->userdata ( 'beuser' );
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
        $data['region_id'] = empty($this->input->post('region_id')) ? $beuser['region_id'] : $this->input->post('region_id');
        $data['product_short'] = $this->input->post('product_short');
        $data['payment_added_from'] = $this->input->post('payment_added_from');
        $data['payment_added_to'] = $this->input->post('payment_added_to');
        $data['payment_date_from'] = $this->input->post('payment_date_from');
        $data['payment_date_to'] = $this->input->post('payment_date_to');
        
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
        $data['region_id'] = empty($this->input->get_post('region_id')) ? $beuser['region_id'] : $this->input->get_post('region_id');
        $data['product_short'] = $this->input->get_post('product_short');
        $data['payment_added_from'] = $this->input->get_post('payment_added_from');
        $data['payment_added_to'] = $this->input->get_post('payment_added_to');
        $data['payment_date_from'] = $this->input->get_post('payment_date_from');
        $data['payment_date_to'] = $this->input->get_post('payment_date_to');
        
        $data['product_list'] = $this->product_model->get_available_product_list();
        $data['user_list'] = $this->user_model->get_available_user_list();
        $data['report_data'] = $this->report_model->get_commission_report($data);
        
        //echo "<pre>";
        //print_r($data['report_data']);die('============');

        $w = WriterFactory::create(Type::XLSX); // for XLSX files
        $kArr = array(
                'added' => 'Payment Date',
                'policy' => 'Policy Number',
                'status' => 'Paid Status',
                'up_insuer' => 'Insurer',
                'customer_name' => 'Customer Name',
                'effective_date' => 'Effective Date',
                'expiry_date' => 'Expiry Date',
                'total_days' => 'Trip Length',
                'premium' => 'Total Premium',
                'premiumispaid' => 'Payment Status',
                'rate' => 'Commission Rate(%)',
                'amount' => 'Commission Amount',
                'ispaid' => 'Commission Status');

        $tmpfname = "/tmp/jf_test.xlsx";
        
        $w->openToBrowser("Commission_Report_" . date('Ymd') . ".xlsx");
        //$w->openToFile($tmpfname);
        
        foreach ($data['report_data'] as $datas) {
            $arr = array('Agent Name:' , $datas['agent']['firstname'] . ' ' . $datas['agent']['lastname'], '','', 'Payment Method: ', $datas['agent']['receive_type']);
            $w->addRow($arr);

            $arr = array('','','','','Mailling Addrerss: ', $datas['agent']['mail_address'] . ' ' . $datas['agent']['mail_city'] . ',' . $datas['agent']['mail_province2'] . ',' . $datas['agent']['mail_postcode']);
            $w->addRow($arr);
           
            $arr = array('Commission Cheque Title: ', $datas['agent']['note']);
            $w->addRow($arr);

            $arr = array('', '');
            $w->addRow($arr);

            $arr = array();
            foreach ($kArr as $k => $v) { $arr[] = $v; } 
            $w->addRow($arr);

            foreach ($datas['data'] as $record) {
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
