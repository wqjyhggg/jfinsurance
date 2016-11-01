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
        $this->load->common('reports/agent', $data);
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
        $data['application_date_from'] = $this->input->post('application_date_from');
        $data['application_date_to'] = $this->input->post('application_date_to');
        $data['arrival_date_from'] = $this->input->post('arrival_date_from');
        $data['arrival_date_to'] = $this->input->post('arrival_date_to');
        $data['effective_date_from'] = $this->input->post('effective_date_from');
        $data['effective_date_to'] = $this->input->post('effective_date_to');
        $data['expiry_date_from'] = $this->input->post('expiry_date_from');
        $data['expiry_date_to'] = $this->input->post('expiry_date_to');
        $data['payment_date_from'] = $this->input->post('payment_date_from');
        $data['payment_date_to'] = $this->input->post('payment_date_to');
        
        $data['product_list'] = $this->product_model->get_available_product_list();
        $data['user_list'] = $this->user_model->get_available_user_list();
        $data['report_data'] = empty($_POST) ? array() : $this->report_model->get_sales_report_jf($data);

        $data['export_list'] = base_url ( "reports/jf/export_list" );
        $data['export_form'] = $this->load->view ( 'reports/agent_export', $data, true);
        return $data;
	}

     function export_list() {
        $beuser = $this->func_model->verify_login(); 
        $this->load->model('product_model');
        $this->load->model('report_model');
        $data['agent_id'] = empty($this->input->get_post('agent_id')) ? 0 : (int)$this->input->get_post('agent_id');
        $data['region_id'] = empty($this->input->post('region_id')) ? $beuser['region_id'] : $this->input->post('region_id');
        $data['product_short'] = $this->input->get_post('product_short');
        $data['application_date_from'] = $this->input->get_post('application_date_from');
        $data['application_date_to'] = $this->input->get_post('application_date_to');
        $data['arrival_date_from'] = $this->input->get_post('arrival_date_from');
        $data['arrival_date_to'] = $this->input->get_post('arrival_date_to');
        $data['effective_date_from'] = $this->input->get_post('effective_date_from');
        $data['effective_date_to'] = $this->input->get_post('effective_date_to');
        $data['expiry_date_from'] = $this->input->get_post('expiry_date_from');
        $data['expiry_date_to'] = $this->input->get_post('expiry_date_to');
        $data['product_list'] = $this->product_model->get_available_product_list();
        $data['user_list'] = $this->user_model->get_available_user_list();
        $data['report_data'] = $this->report_model->get_sales_report_jf($data);

        $w = WriterFactory::create(Type::XLSX); // for XLSX files
        $kArr = array(
                'plan_id' => 'Order ID',
                'order_date' => 'Order Date',
                'policy' => 'Policy No.',
                'invoice_num' => 'Invoice Num',
                'insurerCoName' => 'InsurerCoName',
                'product' => 'Product',
                'insured' => 'Insured Name',
                'agency' => 'Text177',
                'effective_date' => 'Effective Date',
                'expiry_date' => 'Expiry Date',
                'total_days' => 'Number of Days',
                'policy_premium' => 'Policy Premium',
                'commission_rate' => 'Commission Rate',
                'net_premium' => 'Net Premium',
                'commission_amount' => 'Commission Amount');

        $tmpfname = "/tmp/jf_test.xlsx";
        
        $w->openToBrowser("Sales_Report_to_JF_" . date('Ymd') . ".xlsx");
        //$w->openToFile($tmpfname);
        foreach ($data['report_data'] as $datas) {
            $arr = array('Policy Premium: ', $datas['policy_premium'], '', 'Agent: ', $datas['agency_fname'] . ' ' . $datas['agency_lname']);
            $w->addRow($arr);
            $arr = array('');
            $w->addRow($arr);

            $arr = array();
            foreach ($kArr as $k => $v) { $arr[] = $v; } 
            $w->addRow($arr);
            
            foreach ($datas['data'] as $records) {
                
                foreach ($records['records'] as $record) {
                    $arr = array();
                    foreach ($kArr as $k => $v) { $arr[] = $record[$k]; } 
                    $w->addRow($arr);
                }
                
                $arr = array('Total Premium: $' . $records['policy_premium'],'','','Total Net Premium: $' . $records['net_premium'],'','','Total Commission: $', $records['commission']);
                $w->addRow($arr);     
           
            }
           
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
