<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Box\Spout\Common\Type;
use Box\Spout\Writer\WriterFactory;

class Receivable extends MY_Controller
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
        $this->load->common('reports/receivable', $data);
    }

    private function set_data()
    {
        $beuser = $this->func_model->verify_login(); 
    	$this->load->model('product_model');
        $this->load->model('report_model');

        $data ['csrf'] = array (
            'name' => $this->security->get_csrf_token_name (),
            'value' => $this->security->get_csrf_hash ()
        );
        $data['title_txt'] = 'Receivable Report';
        $data['top_menu'] = $this->menu_model->load_top_menu();
        $data['menu'] = $this->menu_model->load_meun();
        $data['action_url'] = current_url();

        $data['agent_id'] = $this->input->post('agent_id');
        $data['region_id'] = $this->input->post('region_id');
        if (empty($data['region_id']) && $beuser['region_id']) {
        	$data['region_id'] = $beuser['region_id'];
        }
        $data['product_short'] = $this->input->post('product_short');
        $data['policy_status'] = $this->input->post('policy_status');
        $data['payment_added_from'] = $this->input->post('payment_added_from');
        $data['payment_added_to'] = $this->input->post('payment_added_to');
        $data['payment_date_from'] = $this->input->post('payment_date_from');
        $data['payment_date_to'] = $this->input->post('payment_date_to');
        
        $data['product_list'] = $this->product_model->get_available_product_list();
        $data['user_list'] = $this->user_model->get_available_user_list();
        $data['report_data'] = empty($_POST) ? array() : $this->report_model->get_receivable($data);
        if (($beuser['user_group_id'] > 100) && ($beuser['user_group_id'] != 104) && empty($data['agent_id'])) {
        	$data['report_data'] = array();
        }
        
        $data['export_list'] = base_url ( "reports/receivable/export_list" );
        $data['export_form'] = $this->load->view ( 'reports/agent_export', $data, true);
        return $data;
    }

     function export_list() {
        $beuser = $this->func_model->verify_login(); 
        $this->load->model('product_model');
        $this->load->model('report_model');
        $data['agent_id'] = empty($this->input->get_post('agent_id')) ? 0 : (int)$this->input->get_post('agent_id');
        $data['region_id'] = $this->input->get_post('region_id');
        if (empty($data['region_id']) && $beuser['region_id']) {
        	$data['region_id'] = $beuser['region_id'];
        }
        $data['product_short'] = $this->input->get_post('product_short');
        $data['policy_status'] = $this->input->get_post('policy_status');
        $data['payment_added_from'] = $this->input->get_post('payment_added_from');
        $data['payment_added_to'] = $this->input->get_post('payment_added_to');
        $data['payment_date_from'] = $this->input->get_post('payment_date_from');
        $data['payment_date_to'] = $this->input->get_post('payment_date_to');
        $data['product_list'] = $this->product_model->get_available_product_list();
        $data['user_list'] = $this->user_model->get_available_user_list();
        $data['report_data'] = $this->report_model->get_receivable($data);
        if (($beuser['user_group_id'] > 100) && ($beuser['user_group_id'] != 104) && empty($data['agent_id'])) {
        	$data['report_data'] = array();
        }

        $w = WriterFactory::create(Type::XLSX); // for XLSX files
        $kArr = array(
                'order_date' => 'Pruchase Date',
                'policy' => 'Policy Number',
                'insured_name' => 'Customer Name',
                'student_id' => 'Student ID',
                'effective_date' => 'Effective Date',
                'expiry_date' => 'Expiry Date',
                'total_days' => 'Trip Length',
                'policy_premium' => 'Premium',
                'net_premium' => 'Net',
        		'commission_amount' => 'Commission',
        		'cal_comm_rate' => 'Ratio',
        );

        $tmpfname = "/tmp/jf_test.xlsx";
        
        $w->openToBrowser("Receivable_Report_" . date('Ymd') . ".xlsx");
        //$w->openToFile($tmpfname);
        //foreach ($data['report_data'] as $datas) {
            $arr = array('JF Insurance Agency Group Inc.');
            $w->addRow($arr);
            $arr = array('15 Wertheim court, Suite 501, Richmond Hill, ON, L4B 3H7');
            $w->addRow($arr);
            $arr = array('Tel: 905-707-1512 Fax: 905-707-1513 Toll free: 1-877-832-5541');
            $w->addRow($arr);
            $arr = array('');
            $w->addRow($arr);

            $arr = array('Invoice Statement');
            $w->addRow($arr);

            $arr = array('For Policy of: ', 'From ' . $data['report_data']['period']['from'] , 'To ' . $data['report_data']['period']['to']);
            $w->addRow($arr);

            $arr = array('', '','','','','','','');
            $w->addRow($arr);


            foreach ($data['report_data']['data'] as $datas) {
                
                $arr = array('Bill to: ' . $datas['agency']['agent_name'] .', ' . $datas['agency']['address'] . ', ' . $datas['agency']['province'] . ', ' . $datas['agency']['postal_code']);
                $w->addRow($arr);

                $arr = array('', '','','','','','','');
                $w->addRow($arr);

                $arr = array('Total Premium ' . $datas['agency']['outstanding']);
                $w->addRow($arr);
                $arr = array('Net Payable Amount: ' . $datas['agency']['payable_to_jf']);
                $w->addRow($arr);

                $arr = array('', '','','','','','','');
                $w->addRow($arr);

                $arr = array();
                foreach ($kArr as $k => $v) { $arr[] = $v; } 
                $w->addRow($arr);
                  
                foreach ($datas['records'] as $record) {
                    $arr = array();
                    foreach ($kArr as $k => $v) { $arr[] = $record[$k]; } 
                    $w->addRow($arr);
                }
                   
            }
          
           
            $arr = array('', '','','','','','','');
            $w->addRow($arr);
        //}
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
