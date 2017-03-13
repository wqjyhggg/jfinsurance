<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Box\Spout\Common\Type;
use Box\Spout\Writer\WriterFactory;

class Refund extends MY_Controller
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
        $this->load->common('reports/refund', $data);
/*
        //todo when do we need pdf, when we send out email? the logic is not clear yet
        $data['style'] = $this->load->view('common/pdf_style',$data, TRUE);
        $mpdf = new mPDF('c');
        $html = $this->load->view('reports/refund', $data, TRUE);
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
        $data['title_txt'] = 'Refund Report';
        $data['top_menu'] = $this->menu_model->load_top_menu();
        $data['menu'] = $this->menu_model->load_meun();
        $data['action_url'] = current_url();
        
        $data['ispaid'] = empty($this->input->post('ispaid')) ? 0 : 1;
        $data['region_id'] = empty($this->input->post('region_id')) ? $beuser['region_id'] : $this->input->post('region_id');
        $data['product_short'] = $this->input->post('product_short');
        $data['pay_date_from'] = $this->input->post('pay_date_from');
        $data['pay_date_to'] = $this->input->post('pay_date_to');
        $data['create_date_from'] = (empty($_POST) && empty($this->input->post('create_date_from'))) ? date('Y-m-01') : $this->input->post('create_date_from', true);
        $data['create_date_to'] = (empty($_POST) && empty($this->input->post('create_date_to'))) ? date("Y-m-d") : $this->input->post('create_date_to', true);

        $data['product_list'] = $this->product_model->get_available_product_list();
        // $data['user_list'] = $this->user_model->get_available_user_list();
        $data['report_data'] = empty($_POST) ? array() : $this->report_model->get_refund_report($data);
        //echo "<pre>"; print_r($data['report_data']);

        $data['export_list'] = base_url ( "reports/refund/export_list" );
        $data['export_form'] = $this->load->view ( 'reports/agent_export', $data, true);
        return $data;
	}

    function export_list() {
        $beuser = $this->func_model->verify_login(); 
        $this->load->model('product_model');
        $this->load->model('report_model');
        $data['ispaid'] = $this->input->get_post('ispaid', true);
        $data['region_id'] = empty($this->input->get_post('region_id')) ? $beuser['region_id'] : $this->input->get_post('region_id');
        $data['product_short'] = $this->input->get_post('product_short', true);
        $data['pay_date_from'] = $this->input->get_post('pay_date_from', true);
        $data['pay_date_to'] = $this->input->get_post('pay_date_to', true);
        $data['create_date_from'] = $this->input->get_post('create_date_from', true);
        $data['create_date_to'] = $this->input->get_post('create_date_to', true);
        
        $data['product_list'] = $this->product_model->get_available_product_list();
        // $data['user_list'] = $this->user_model->get_available_user_list();
        $data['report_data'] = $this->report_model->get_refund_report($data);

        $w = WriterFactory::create(Type::XLSX); // for XLSX files
        $kArr = array(
                'policy' => 'Policy #',
                'customer_name' => 'Customer Name',
                'birthday' => 'DOB',
        		'refund_date' => 'Refund Date',
        		'agent_name' => 'Agent Name',
                'premium' => 'Original Premium',
                'commission' => 'Original Net Premium',
        		'net_amount' => 'Refund Amount',
                'admin_fee' => 'Admin Fee<',
                'amount' => 'Total Refund',
        		'added' => 'Refund Process Date'
        );

        $w->openToBrowser("refund_Report_" . date('Ymd') . ".xlsx");
        //$w->openToFile("/tmp/jf_test.xlsx");

        foreach ($kArr as $k => $v) { $arr[] = $v; } 
        $w->addRow($arr);
        
        $total = 0;
        foreach ($data['report_data'] as $record) {
        	$arr = array();
        	$total += $record['amount'];
            foreach ($kArr as $k => $v) {
            	if ($k == 'commission') {
            		$arr[] = (double)($record['premium'] - $record['commission']);
            	} else if ($k == 'premium') {
            		$arr[] = (double)$record['premium'];
            	} else if ($k == 'net_amount') {
            		$arr[] = (double)$record['net_amount'];
            	} else if ($k == 'admin_fee') {
            		$arr[] = (double)$record['admin_fee'];
            	} else if ($k == 'amount') {
            		$arr[] = (double)$record['amount'];
            	} else if ($k == 'added') {
            		$arr[] = substr($record['added'], 0 , 10);
            	} else {
            		$arr[] = $record[$k];
            	}
			} 
            $w->addRow($arr);
        }
            
        $arr = array('Total', '', '', '', '', '', '', '', '', $total);
        $w->addRow($arr);

        $arr = array('','');
        $w->addRow($arr);

        $w->close();
    }
}
