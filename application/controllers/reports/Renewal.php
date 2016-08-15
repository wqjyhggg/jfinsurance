<?php
defined('BASEPATH') OR exit('No direct script access allowed');

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
        $data['create_date_from'] = $this->input->post('create_date_from');
        $data['create_date_to'] = $this->input->post('create_date_to');
        $data['effective_date_from'] = $this->input->post('effective_date_from');
        $data['effective_date_to'] = $this->input->post('effective_date_to');
        $data['expiry_date_from'] = empty($this->input->post('expiry_date_from')) ? date('Y-m-01') : $this->input->post('expiry_date_from', true);
        $data['expiry_date_to'] = empty($this->input->post('expiry_date_to')) ? date("Y-m-d") : $this->input->post('expiry_date_to', true);

        $data['product_list'] = $this->product_model->get_available_product_list();
        $data['user_list'] = $this->user_model->get_available_user_list();
        $data['report_data'] = empty($_POST) ? array() : $this->report_model->get_renewal_report($data);
        return $data;
	}

	public function sendemail() {
        $beuser = $this->func_model->verify_login();
		
        if (empty($_POST)) show_error('Unsupport Function', 404);
        
        $data['agent_id'] = $this->input->post_get('agent_id');
		$data['product_short'] = $this->input->post_get('product_short');
		$data['application_date_from'] = $this->input->post_get('application_date_from');
		$data['application_date_to'] = $this->input->post_get('application_date_to');
		$data['create_date_from'] = $this->input->post_get('create_date_from');
		$data['create_date_to'] = $this->input->post_get('create_date_to');
		$data['effective_date_from'] = $this->input->post_get('effective_date_from');
		$data['effective_date_to'] = $this->input->post_get('effective_date_to');
		$data['expiry_date_from'] = empty($this->input->post_get('expiry_date_from')) ? date('Y-m-01') : $this->input->post_get('expiry_date_from', true);
		$data['expiry_date_to'] = empty($this->input->post_get('expiry_date_to')) ? date("Y-m-d") : $this->input->post_get('expiry_date_to', true);
    
		if ($report_data = $this->report_model->get_renewal_report($data)) {
			$this->load->model('mymail_model');
			foreach ($report_data['data'] as $agent_id => $renewal_data) {
				$data['period'] = $report_data['period']['from'] . " to " . $report_data['period']['to'];
				$data['name'] = $renewal_data['agency'];
				$data['email'] = $renewal_data['agency_email'];
				$data['records'] = $renewal_data['records'];
				$body = $this->load-view('reports/renewal_email', $data, TRUE);
				$this->mymail_model->send_mymail($data['email'], 'Renewal Report', $body);
			}
		}
		rediret('reports/renewal');
	}
}
