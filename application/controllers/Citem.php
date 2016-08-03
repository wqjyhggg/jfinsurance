<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Citem extends MY_Controller {
	public $data;

	/**
	 * Index Page for this controller.
	 */
	public function index()
	{
	}
	
	/**
	 * Index Page for this controller.
	 */
	public function itemlist($claim_id)
	{
		$beuser = $this->func_model->verify_login();
		$this->load->model('claim_model');
		
		if (isset($this->data) && !empty($this->data['error_message'])) {
			$data['error_message'] = $this->data['error_message'];
		}
		
		$data['claim'] = $this->claim_model->get_claim_by_id($claim_id);
		$data['lists'] = $this->claim_model->get_item_list($claim_id);
		$data['add_url'] = base_url('citem/add/'.$claim_id);
		$data['edit_url'] = base_url('citem/edit');
		$data['title_txt'] = 'Claim Item';
		$data['top_menu'] = $this->menu_model->load_top_menu();
		$data['menu'] = $this->menu_model->load_meun();
		$data['csrf'] = array (
				'name' => $this->security->get_csrf_token_name (),
				'value' => $this->security->get_csrf_hash ()
		);
		
		$this->load->common('citem/list', $data);
	}
	
	public function add($claim_id) {
		$beuser = $this->func_model->verify_login();

		$this->load->model('customer_model');
		$this->load->model('claim_model');
		$this->load->model('plan_model');

		$claim = $this->claim_model->get_claim_by_id($claim_id);
		if ($claim) {
			$this->data = array();
			$para = array();
			$para['claim_id'] = $claim_id;
			$para['plan_id'] = $claim['plan_id'];
			$para['user_id'] = $claim['user_id'];
			$para['customer_id'] = $claim['customer_id'];
			$para['product_short'] = $claim['product_short'];
			$para['policy_number'] = $claim['policy_number'];
			$para['lastname'] = $claim['lastname'];
			$para['firstname'] = $claim['firstname'];
			$para['birthday'] = $claim['birthday'];
			$para['gender'] = $claim['gender'];
			$para['claim_date'] = date('Y-m-d');
			$citem_id = $this->claim_model->additem($para);
			$log = array(
					'plan_id' => $claim['plan_id'], 
					'customer_id' => $claim['customer_id'], 
					'payment_id' => 0, 
					'message' => $this->claim_model->logstr, 
					'systemlog' => $this->claim_model->sqlstr
			);
			$this->log_model->activity('claim_item', $para);
			$citem = $this->claim_model->get_claim_item_by_id($citem_id);
			$this->form($citem);
		} else {
			$this->data['error_message'] = "Can't find Claim";
		}
	}
	
	public function edit($citem_id) {
		$beuser = $this->func_model->verify_login();
		
		$this->load->model('claim_model');
		
		$this->data = array();
		$citem = $this->claim_model->get_claim_item_by_id($citem_id);
		$this->form($citem);
	}
	
	public function form_valid() {
		$r = TRUE;
		// $this->input->post('claim_id')
		if (empty($this->input->post('plan_id'))) {
			$this->data['error_message'] = 'Unknown Policy';
			return FALSE;
		}
		if (empty($this->input->post('customer_id'))) {
			$this->data['error_message'] = 'Unknown Customer';
			return FALSE;
		}
		// $this->input->post('done')
		if (empty($this->input->post('product_short'))) {
			$this->data['error_message'] = 'Unknown Product';
			$r = FALSE;
		}
		if (empty($this->input->post('policy_number'))) {
			$this->data['error_message'] = 'Unknown Policy Number';
			$r = FALSE;
		}
		$dt = date_create($this->input->post('birthday'));
		if (empty($this->input->post('birthday')) && !$dt) {
			$this->data['error_birthday'] = 'Birthday is Required';
			$r = FALSE;
		}
		return $r;
	}
	
	public function form($citem=array()) {
		$beuser = $this->func_model->verify_login();

		if (empty($citem) && $this->input->post('submit') && $this->form_valid()) {
			$this->load->model('claim_model');
			$citem_id = $this->input->post('citem_id');
			$this->claim_model->updateitem($citem_id, $this->input->post());
			$citem = $this->claim_model->get_claim_item_by_id($citem_id);
			redirect('citem/itemlist/' . $citem['claim_id']);
		}
		
		if ($this->input->post('citem_id')) {
			$this->data['citem_id'] = $this->input->post('citem_id'); 
		} else if (isset($citem['citem_id'])) {
			$this->data['citem_id'] = $citem['citem_id'];
		} else {
		}
		if ($this->input->post('claim_id')) {
			$this->data['claim_id'] = $this->input->post('claim_id'); 
		} else if (isset($citem['claim_id'])) {
			$this->data['claim_id'] = $citem['claim_id'];
		} else {
			$this->data['error_message'] = 'Unknown Claim';
		}
		if ($this->input->post('plan_id')) {
			$this->data['plan_id'] = $this->input->post('plan_id'); 
		} else if (isset($citem['plan_id'])) {
			$this->data['plan_id'] = $citem['plan_id'];
		} else {
			$this->data['error_message'] = 'Unknown Policy';
		}
		$this->data['user_id'] = $beuser['user_id'];
		if ($this->input->post('customer_id')) {
			$this->data['customer_id'] = $this->input->post('customer_id'); 
		} else if (isset($citem['customer_id'])) {
			$this->data['customer_id'] = $citem['customer_id'];
		} else {
			$this->data['error_message'] = 'Unknown Customer';
		}
		if ($this->input->post('done')) {
			$this->data['done'] = $this->input->post('done'); 
		} else if (isset($citem['done'])) {
			$this->data['done'] = $citem['done'];
		} else {
			$this->data['done'] = 2;
		}
		if ($this->input->post('product_short')) {
			$this->data['product_short'] = $this->input->post('product_short'); 
		} else if (isset($citem['product_short'])) {
			$this->data['product_short'] = $citem['product_short'];
		} else {
			$this->data['error_message'] = 'Unknown Product';
		}
		if ($this->input->post('policy_number')) {
			$this->data['policy_number'] = $this->input->post('policy_number'); 
		} else if (isset($citem['policy_number'])) {
			$this->data['policy_number'] = $citem['policy_number'];
		} else {
			$this->data['error_message'] = 'Unknown Policy';
		}
		if ($this->input->post('claim_number')) {
			$this->data['claim_number'] = $this->input->post('claim_number'); 
		} else if (isset($citem['claim_number'])) {
			$this->data['claim_number'] = $citem['claim_number'];
		} else {
			$this->data['claim_number'] = '';
		}
		if ($this->input->post('lastname')) {
			$this->data['lastname'] = $this->input->post('lastname'); 
		} else if (isset($citem['lastname'])) {
			$this->data['lastname'] = $citem['lastname'];
		} else {
			$this->data['lastname'] = '';
		}
		if ($this->input->post('firstname')) {
			$this->data['firstname'] = $this->input->post('firstname'); 
		} else if (isset($citem['firstname'])) {
			$this->data['firstname'] = $citem['firstname'];
		} else {
			$this->data['firstname'] = '';
		}
		if ($this->input->post('birthday')) {
			$this->data['birthday'] = $this->input->post('birthday'); 
		} else if (isset($citem['birthday'])) {
			$this->data['birthday'] = $citem['birthday'];
		} else {
			$this->data['birthday'] = '';
		}
		if ($this->input->post('gender')) {
			$this->data['gender'] = $this->input->post('gender'); 
		} else if (isset($citem['gender'])) {
			$this->data['gender'] = $citem['gender'];
		} else {
			$this->data['gender'] = 'M';
		}
		if ($this->input->post('claim_date')) {
			$this->data['claim_date'] = $this->input->post('claim_date'); 
		} else if (isset($citem['claim_date'])) {
			$this->data['claim_date'] = $citem['claim_date'];
		} else {
			$this->data['claim_date'] = '';
		}
		if ($this->input->post('claimed')) {
			$this->data['claimed'] = $this->input->post('claimed'); 
		} else if (isset($citem['claimed'])) {
			$this->data['claimed'] = $citem['claimed'];
		} else {
			$this->data['claimed'] = 0;
		}
		if ($this->input->post('paid')) {
			$this->data['paid'] = $this->input->post('paid'); 
		} else if (isset($citem['paid'])) {
			$this->data['paid'] = $citem['paid'];
		} else {
			$this->data['paid'] = 0;
		}
		if ($this->input->post('pay_to')) {
			$this->data['pay_to'] = $this->input->post('pay_to'); 
		} else if (isset($citem['pay_to'])) {
			$this->data['pay_to'] = $citem['pay_to'];
		} else {
			$this->data['pay_to'] = '';
		}
		if ($this->input->post('service_date')) {
			$this->data['service_date'] = $this->input->post('service_date'); 
		} else if (isset($citem['service_date'])) {
			$this->data['service_date'] = $citem['service_date'];
		} else {
			$this->data['service_date'] = '';
		}
		if ($this->input->post('eob_date')) {
			$this->data['eob_date'] = $this->input->post('eob_date'); 
		} else if (isset($citem['eob_date'])) {
			$this->data['eob_date'] = $citem['eob_date'];
		} else {
			$this->data['eob_date'] = '';
		}
		if ($this->input->post('received')) {
			$this->data['received'] = $this->input->post('received'); 
		} else if (isset($citem['received'])) {
			$this->data['received'] = $citem['received'];
		} else {
			$this->data['received'] = '';
		}
		if ($this->input->post('cashed_date')) {
			$this->data['cashed_date'] = $this->input->post('cashed_date'); 
		} else if (isset($citem['cashed_date'])) {
			$this->data['cashed_date'] = $citem['cashed_date'];
		} else {
			$this->data['cashed_date'] = '';
		}
		if ($this->input->post('eob_cheque_no')) {
			$this->data['eob_cheque_no'] = $this->input->post('eob_cheque_no'); 
		} else if (isset($citem['eob_cheque_no'])) {
			$this->data['eob_cheque_no'] = $citem['eob_cheque_no'];
		} else {
			$this->data['eob_cheque_no'] = '';
		}
		if ($this->input->post('address')) {
			$this->data['address'] = $this->input->post('address'); 
		} else if (isset($citem['address'])) {
			$this->data['address'] = $citem['address'];
		} else {
			$this->data['address'] = '';
		}
		if ($this->input->post('city')) {
			$this->data['invoice_num'] = $this->input->post('city'); 
		} else if (isset($citem['city'])) {
			$this->data['city'] = $citem['city'];
		} else {
			$this->data['city'] = '';
		}
		if ($this->input->post('invoice_num')) {
			$this->data['invoice_num'] = $this->input->post('invoice_num'); 
		} else if (isset($citem['invoice_num'])) {
			$this->data['invoice_num'] = $citem['invoice_num'];
		} else {
			$this->data['invoice_num'] = '';
		}
		if ($this->input->post('province2')) {
			$this->data['province2'] = $this->input->post('province2'); 
		} else if (isset($citem['province2'])) {
			$this->data['province2'] = $citem['province2'];
		}
		if (empty($this->data['province2'])) {
			$this->data['province2'] = 'ON';
		}
		if ($this->input->post('country2')) {
			$this->data['country2'] = $this->input->post('country2');
		} else if (isset($citem['country2'])) {
			$this->data['country2'] = $citem['country2'];
		}
		if (empty($this->data['country2'])) {
			$this->data['country2'] = 'CA';
		}
		if ($this->input->post('postcode')) {
			$this->data['postcode'] = $this->input->post('postcode');
		} else if (isset($citem['postcode'])) {
			$this->data['postcode'] = $citem['postcode'];
		} else {
			$this->data['postcode'] = '';
		}
		if ($this->input->post('cheque_number')) {
			$this->data['cheque_number'] = $this->input->post('cheque_number'); 
		} else if (isset($citem['cheque_number'])) {
			$this->data['cheque_number'] = $citem['cheque_number'];
		} else {
			$this->data['cheque_number'] = '';
		}
		if ($this->input->post('coverage_code_id')) {
			$this->data['coverage_code_id'] = $this->input->post('coverage_code_id'); 
		} else if (isset($citem['coverage_code_id'])) {
			$this->data['coverage_code_id'] = $citem['coverage_code_id'];
		} else {
			$this->data['coverage_code_id'] = 0;
		}
		if ($this->input->post('service_date')) {
			$this->data['service_date'] = $this->input->post('service_date'); 
		} else if (isset($citem['service_date'])) {
			$this->data['service_date'] = $citem['service_date'];
		} else {
			$this->data['service_date'] = '';
		}
		if ($this->input->post('paid_date')) {
			$this->data['paid_date'] = $this->input->post('paid_date'); 
		} else if (isset($citem['paid_date'])) {
			$this->data['paid_date'] = $citem['paid_date'];
		} else {
			$this->data['paid_date'] = '';
		}
		if ($this->input->post('diagnosis')) {
			$this->data['diagnosis'] = $this->input->post('diagnosis'); 
		} else if (isset($citem['diagnosis'])) {
			$this->data['diagnosis'] = $citem['diagnosis'];
		} else {
			$this->data['diagnosis'] = '';
		}
		if ($this->input->post('internal_note')) {
			$this->data['internal_note'] = $this->input->post('internal_note'); 
		} else if (isset($citem['internal_note'])) {
			$this->data['internal_note'] = $citem['internal_note'];
		} else {
			$this->data['internal_note'] = '';
		}
		if ($this->input->post('external_note')) {
			$this->data['external_note'] = $this->input->post('external_note'); 
		} else if (isset($citem['internal_note'])) {
			$this->data['external_note'] = $citem['external_note'];
		} else {
			$this->data['external_note'] = '';
		}
		if (!empty($this->data['error_message'])) {
			// How to show error_message?
			redirect('citem/itemlist/' . $citem['claim_id']);
		}
		
		$this->data['province_url'] = base_url ( "geo/province/" . $this->data['country2'] . "/" . $this->data['province2'] );
		$this->data['country_url'] = base_url ( "geo/country/" . $this->data['country2'] );
		
		$this->load->model('coverage_model');
		$this->data['coverage_codes'] = $this->coverage_model->get_coverage_codes();
		$this->data['edit_url'] = base_url('citem/form');
		
		$this->data['title_txt'] = 'Claim';
		$this->data['top_menu'] = $this->menu_model->load_top_menu();
		$this->data['menu'] = $this->menu_model->load_meun();
		$this->data['csrf'] = array (
				'name' => $this->security->get_csrf_token_name (),
				'value' => $this->security->get_csrf_hash ()
		);
		$this->load->common('citem/form', $this->data);
	}
}
