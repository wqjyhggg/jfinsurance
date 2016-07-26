<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Claimreport extends MY_Controller {

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
        $data['create_date_from'] = empty($this->input->post('create_date_from')) ? date('Y-m-01') : $this->input->post('create_date_from', true);
        $data['create_date_to'] = empty($this->input->post('create_date_to')) ? date("Y-m-d") : $this->input->post('create_date_to', true);
        $data['effective_date_from'] = $this->input->post('effective_date_from');
        $data['effective_date_to'] = $this->input->post('effective_date_to');
        $data['payment_update_date_from'] = $this->input->post('payment_update_date_from');
        $data['payment_update_date_to'] = $this->input->post('payment_update_date_to');

        $data['product_list'] = $this->product_model->get_available_product_list();
        $data['user_list'] = $this->user_model->get_available_user_list();
        $data['report_data'] = empty($_POST) ? array() : $this->report_model->get_claim_report($data);
        return $data;
	}
}
