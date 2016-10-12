<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Box\Spout\Common\Type;
use Box\Spout\Writer\WriterFactory;

class Renewal extends MY_Controller
{
	/**
	 * Index Page for this controller.
	 */
	public function index()
	{
        $beuser = $this->func_model->verify_login();
        $data = $this->set_data();
		$this->load->common('reports/renewal', $data);
/*
        //todo when do we need pdf, when we send out email? the logic is not clear yet
        $data['style'] = $this->load->view('common/pdf_style',$data, TRUE);
        $mpdf = new mPDF('c');
        $html = $this->load->view('reports/renewal', $data, TRUE);
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
        $data['title_txt'] = 'Renewal Report';
        $data['top_menu'] = $this->menu_model->load_top_menu();
        $data['menu'] = $this->menu_model->load_meun();
        $data['action_url'] = current_url();
        $data['mail_url'] = base_url('reports/renewal/sendemail');
        
        $data['agent_id'] = $this->input->post('agent_id');
        $data['product_short'] = $this->input->post('product_short');
        $data['application_date_from'] = $this->input->post('application_date_from');
        $data['application_date_to'] = $this->input->post('application_date_to');
        $data['arrival_date_from'] = $this->input->post('arrival_date_from');
        $data['arrival_date_to'] = $this->input->post('arrival_date_to');
        $data['effective_date_from'] = $this->input->post('effective_date_from');
        $data['effective_date_to'] = $this->input->post('effective_date_to');
        $data['expiry_date_from'] = (empty($_POST) && empty($this->input->post('expiry_date_from'))) ? date('Y-m-01') : $this->input->post('expiry_date_from', true);
        $data['expiry_date_to'] = (empty($_POST) && empty($this->input->post('expiry_date_to'))) ? date("Y-m-d") : $this->input->post('expiry_date_to', true);

        $data['product_list'] = $this->product_model->get_available_product_list();
        $data['user_list'] = $this->user_model->get_available_user_list();
        $data['report_data'] = empty($_POST) ? array() : $this->report_model->get_renewal_report($data);
        
        $data['export_list'] = base_url ( "reports/renewal/export_list" );
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
        $data['arrival_date_from'] = $this->input->get_post('arrival_date_from');
        $data['arrival_date_to'] = $this->input->get_post('arrival_date_to');
        $data['effective_date_from'] = $this->input->get_post('effective_date_from');
        $data['effective_date_to'] = $this->input->get_post('effective_date_to');
        
        $data['expiry_date_from'] = empty($this->input->post('expiry_date_from')) ? date('Y-m-01') : $this->input->post('expiry_date_from', true);
        $data['expiry_date_to'] = empty($this->input->post('expiry_date_to')) ? date("Y-m-d") : $this->input->post('expiry_date_to', true);

        $data['expiry_date_from'] = $this->input->get_post('expiry_date_from');
        $data['expiry_date_to'] = $this->input->get_post('expiry_date_to');
        $data['product_list'] = $this->product_model->get_available_product_list();
        $data['user_list'] = $this->user_model->get_available_user_list();
        $data['report_data'] = $this->report_model->get_renewal_report($data);

        $w = WriterFactory::create(Type::XLSX); // for XLSX files
        $kArr = array(
                'policy' => 'Policy Number',
                'effective_date' => 'Effective Date',
                'expiry_date' => 'Expiry Date',
                'customer_name' => 'Customer Name',
                'province' => 'Province',
                'phone' => 'Phone Number',
                'email' => 'Email Address'
                );

        $tmpfname = "/tmp/jf_test.xlsx";
        
        $w->openToBrowser("Renewal_Report_" . date('Ymd') . ".xlsx");
        //$w->openToFile($tmpfname);

        $date_from = $data['report_data']['period']['from'];
        $date_to = $data['report_data']['period']['to'];

        foreach ($data['report_data']['data'] as $datas) {
            $arr = array('Expire Date From: ', $date_from, 'To: ', $date_to);
            $w->addRow($arr);
            $arr = array('Agent: ', $datas['agency']);
            $w->addRow($arr);
            $arr = array('','');
            $w->addRow($arr);
            $arr = array();
            foreach ($kArr as $k => $v) { $arr[] = $v; } 
            $w->addRow($arr);
            
            foreach ($datas['records'] as $record) {
                
                    $arr = array();
                    foreach ($kArr as $k => $v) { $arr[] = $record[$k]; } 
                    $w->addRow($arr);
                
                
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

	public function sendemail() {
        $beuser = $this->func_model->verify_login();
		
        // if (empty($_POST)) show_error('Unsupport Function', 404);
        $this->load->model('product_model');
        $this->load->model('user_model');
        $this->load->model('report_model');
        
        $data['agent_id'] = $this->input->get('agent_id');
		$data['product_short'] = $this->input->get('product_short');
		$data['application_date_from'] = $this->input->get('application_date_from');
		$data['application_date_to'] = $this->input->get('application_date_to');
		$data['arrival_date_from'] = $this->input->get('arrival_date_from');
		$data['arrival_date_to'] = $this->input->get('arrival_date_to');
		$data['effective_date_from'] = $this->input->get('effective_date_from');
		$data['effective_date_to'] = $this->input->get('effective_date_to');
		$data['expiry_date_from'] = $this->input->get('expiry_date_from');
		$data['expiry_date_to'] = $this->input->get('expiry_date_to');
		$data['product_list'] = $this->product_model->get_available_product_list();
		$data['user_list'] = $this->user_model->get_available_user_list();
		$data['report_data'] = $this->report_model->get_renewal_report($data);

		if ($data['report_data']) {
			$this->load->model('mymail_model');
			foreach ($data['report_data']['data'] as $agent_id => $renewal_data) {
				$data['period'] = $data['report_data']['period']['from'] . " to " . $data['report_data']['period']['to'];
				$data['name'] = $renewal_data['agency'];
				$data['email'] = $renewal_data['agency_email'];
				$data['records'] = $renewal_data['records'];
				$body = $this->load->view('reports/renewal_email', $data, TRUE);
				$this->mymail_model->send_mymail($data['email'], 'Renewal Report', $body);
			}
		}
		redirect('reports/renewal');
	}
}
