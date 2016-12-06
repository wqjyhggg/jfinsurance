<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Claim extends MY_Controller {
	public $data;

	/**
	 * Index Page for this controller.
	 */
	public function index()
	{
		$beuser = $this->func_model->verify_login();
		$this->load->model('product_model');
		
		if ($this->input->get()) {
			$this->load->model('claim_model');
			$para = array();
			$para['product_short'] = $this->input->get('product_short');
			$para['policy_number'] = $this->input->get('policy_number');
			$para['claim_number'] = $this->input->get('claim_number');
			$para['lastname'] = $this->input->get('lastname');
			$para['firstname'] = $this->input->get('firstname');
			$para['claim_date'] = $this->input->get('claim_date');
			$para['claim_date2'] = $this->input->get('claim_date2');
			$para['cheque_number'] = $this->input->get('cheque_number');
			$para['invoice_num'] = $this->input->get('invoice_num');
			$data['lists'] = $this->claim_model->search($para);
		} else {
			$data['lists'] = array();
		}

		if ($this->input->get("lastname")) {
			$data['lastname'] = $this->input->get("lastname");
		} else {
			$data['lastname'] = '';
		}
		if ($this->input->get("firstname")) {
			$data['firstname'] = $this->input->get("firstname");
		} else {
			$data['firstname'] = '';
		}
		if ($this->input->get("policy_number")) {
			$data['policy_number'] = $this->input->get("policy_number");
		} else {
			$data['policy_number'] = '';
		}
		if ($this->input->get("claim_number")) {
			$data['claim_number'] = $this->input->get("claim_number");
		} else {
			$data['claim_number'] = '';
		}
		if ($this->input->get("product_short")) {
			$data['product_short'] = $this->input->get("product_short");
		} else {
			$data['product_short'] = '';
		}
		if ($this->input->get("claim_date")) {
			$data['claim_date'] = $this->input->get("claim_date");
		} else {
			$data['claim_date'] = '';
		}
		if ($this->input->get("claim_date2")) {
			$data['claim_date2'] = $this->input->get("claim_date2");
		} else {
			$data['claim_date2'] = '';
		}
				
		$data['action_url'] = current_url();
		$data['edit_url'] = base_url('claim/edit');
		$data['citem_url'] = base_url('citem/itemlist');
		$data['products'] = $this->product_model->product_array();
		$data['title_txt'] = 'Claim';
		$data['top_menu'] = $this->menu_model->load_top_menu();
		$data['menu'] = $this->menu_model->load_meun();
		$data['csrf'] = array (
				'name' => $this->security->get_csrf_token_name (),
				'value' => $this->security->get_csrf_hash ()
		);
		
		$this->load->common('claim/list', $data);
	}
	
	public function add($customer_id) {

		$beuser = $this->func_model->verify_login();

		$this->load->model('customer_model');
		$this->load->model('claim_model');
		$this->load->model('plan_model');

		if ($this->input->post() && ($customer_id == $this->input->post('customer_id'))) {
			$this->data = array();
			$claim = array();
			if (!empty($customer_id)) {
				$customer = $this->customer_model->get_customer_by_id($customer_id);
				if ($customer) {
					$plan = $this->plan_model->get_plan_by_id($customer['plan_id']);
					if ($plan && ($plan['status_id'] >= 2)) {
						$para = array();
						$para['plan_id'] = $plan['plan_id'];
						$para['user_id'] = $beuser['user_id'];
						$para['customer_id'] = $customer['customer_id'];
						$para['done'] = 2;
						$para['product_short'] = $plan['product_short'];
						$para['policy_number'] = $plan['policy'];
						$para['lastname'] = $customer['lastname'];
						$para['firstname'] = $customer['firstname'];
						$para['birthday'] = $customer['birthday'];
						$para['gender'] = $customer['gender'];
						$para['claim_date'] = date('Y-m-d');
						$claim_id = $this->claim_model->add($para);
						$log = array(
								'plan_id' => $plan['plan_id'], 
								'customer_id' => $customer['customer_id'], 
								'payment_id' => 0, 
								'message' => $this->claim_model->logstr, 
								'systemlog' => $this->claim_model->sqlstr
						);
						$this->log_model->activity('claim', $para);
						$claim = $this->claim_model->get_claim_by_id($claim_id);
						if ($claim) {
							$para = array('status_id' => 4, 'note' => $plan['note'] . ";\n" . "Add Claim(" . $claim['claim_id'] .")");
							$this->plan_model->update($plan['plan_id'], $para);
							$log = array(
									'plan_id' => $plan['plan_id'], 
									'customer_id' => $customer['customer_id'], 
									'payment_id' => 0, 
									'message' => $this->claim_model->logstr, 
									'systemlog' => $this->claim_model->sqlstr
							);
							$this->log_model->activity('plan', $para);
						}
					} else {
						$this->data['error_message'] = "Can't find customer";
					}
				} else {
					$this->data['error_message'] = "Can't find customer";
				}
			}
			
			$this->data['button'] = 'Submit';
			$this->form($claim);
		} else {
			$this->load->model('product_model');
			$customer = $this->customer_model->get_customer_by_id($customer_id);
			$plan = $this->plan_model->get_plan_by_id($customer['plan_id']);
			$para['plan_id'] = $customer['plan_id'];
			$data['lists'] = $this->claim_model->search($para);
			$data['firstname'] = $customer["firstname"];
			$data['lastname'] = $customer["lastname"];
			$data['birthday'] = $customer["birthday"];
			$data['gender'] = $customer["gender"];
			$data['policy_number'] = '';
			$data['claim_number'] = '';
			$data['product_short'] = '';
			$data['claim_date'] = '';
			$data['claim_date2'] = '';
			$data['customer'] = $customer;
			$data['plan'] = $plan;
				
			$data['add_url'] = current_url();
			$data['action_url'] = base_url('claim');
			$data['edit_url'] = base_url('claim/edit');
			$data['citem_url'] = base_url('citem/itemlist');
			$data['products'] = $this->product_model->product_array();
			$data['title_txt'] = 'Claim';
			$data['top_menu'] = $this->menu_model->load_top_menu();
			$data['menu'] = $this->menu_model->load_meun();
			$data['csrf'] = array (
					'name' => $this->security->get_csrf_token_name (),
					'value' => $this->security->get_csrf_hash ()
			);
			
			$this->load->common('claim/list', $data);
		}
	}
	
	public function edit($claim_id) {
		$beuser = $this->func_model->verify_login();
		
		$this->load->model('claim_model');
		
		$this->data = array();
		$claim = $this->claim_model->get_claim_by_id($claim_id);
		
		$this->form($claim);
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
		if (empty($this->input->post('claim_number'))) {
			$this->data['error_claim_number'] = 'Claim Number is Required';
			$r = FALSE;
		}
		if (empty($this->input->post('lastname'))) {
			$this->data['error_lastname'] = 'Lastname is Required';
			$r = FALSE;
		}
		if (empty($this->input->post('firstname'))) {
			$this->data['error_firstname'] = 'Firstname is Required';
			$r = FALSE;
		}
		$dt = date_create($this->input->post('birthday'));
		if (empty($this->input->post('birthday')) && !$dt) {
			$this->data['error_birthday'] = 'Birthday is Required';
			$r = FALSE;
		}
		// $this->input->post('gender')
		// $this->input->post('claim_date')
		// $this->input->post('note')
		return $r;
	}
	
	public function form($claim=array()) {
		$beuser = $this->func_model->verify_login();

		if (empty($claim) && $this->input->post() && $this->form_valid()) {
			$this->load->model('claim_model');
			$claim_id = $this->input->post('claim_id');
			$this->claim_model->update($claim_id, $this->input->post());
			$claim = $this->claim_model->get_claim_by_id($claim_id);
			redirect('claim?lastname='.$this->input->post('lastname').'&firstname='.$this->input->post('firstname').'&policy_number='.$this->input->post('policy_number').'&claim_number='.$this->input->post('claim_number').'&product_short='.$this->input->post('product_short').'&claim_date=&claim_date2=');
		}
		if ($this->input->post('claim_id')) {
			$this->data['claim_id'] = $this->input->post('claim_id'); 
		} else if (isset($claim['claim_id'])) {
			$this->data['claim_id'] = $claim['claim_id'];
		} else {
			$this->data['claim_id'] = 0;
		}
		if ($this->input->post('plan_id')) {
			$this->data['plan_id'] = $this->input->post('plan_id'); 
		} else if (isset($claim['plan_id'])) {
			$this->data['plan_id'] = $claim['plan_id'];
		} else {
			$this->data['error_message'] = 'Unknown Policy';
		}
		$this->data['user_id'] = $beuser['user_id'];
		if ($this->input->post('customer_id')) {
			$this->data['customer_id'] = $this->input->post('customer_id'); 
		} else if (isset($claim['customer_id'])) {
			$this->data['customer_id'] = $claim['customer_id'];
		} else {
			$this->data['error_message'] = 'Unknown Customer';
		}
		/* if ($this->input->post('done')) {
			$this->data['done'] = $this->input->post('done'); 
		} else*/ if (isset($claim['done'])) {
			$this->data['done'] = $claim['done'];
		} else {
			$this->data['done'] = 2;
		}
		if ($this->input->post('product_short')) {
			$this->data['product_short'] = $this->input->post('product_short'); 
		} else if (isset($claim['product_short'])) {
			$this->data['product_short'] = $claim['product_short'];
		} else {
			$this->data['error_message'] = 'Unknown Product';
		}
		if ($this->input->post('policy_number')) {
			$this->data['policy_number'] = $this->input->post('policy_number'); 
		} else if (isset($claim['policy_number'])) {
			$this->data['policy_number'] = $claim['policy_number'];
		} else {
			$this->data['error_message'] = 'Unknown Policy';
		}
		if ($this->input->post('claim_number')) {
			$this->data['claim_number'] = $this->input->post('claim_number'); 
		} else if (isset($claim['claim_number'])) {
			$this->data['claim_number'] = $claim['claim_number'];
		} else {
			$this->data['claim_number'] = '';
		}
		if ($this->input->post('lastname')) {
			$this->data['lastname'] = $this->input->post('lastname'); 
		} else if (isset($claim['lastname'])) {
			$this->data['lastname'] = $claim['lastname'];
		} else {
			$this->data['lastname'] = '';
		}
		if ($this->input->post('firstname')) {
			$this->data['firstname'] = $this->input->post('firstname'); 
		} else if (isset($claim['firstname'])) {
			$this->data['firstname'] = $claim['firstname'];
		} else {
			$this->data['firstname'] = '';
		}
		if ($this->input->post('birthday')) {
			$this->data['birthday'] = $this->input->post('birthday'); 
		} else if (isset($claim['birthday'])) {
			$this->data['birthday'] = $claim['birthday'];
		} else {
			$this->data['birthday'] = '';
		}
		if ($this->input->post('gender')) {
			$this->data['gender'] = $this->input->post('gender'); 
		} else if (isset($claim['gender'])) {
			$this->data['gender'] = $claim['gender'];
		} else {
			$this->data['gender'] = 'M';
		}
		if ($this->input->post('claim_date')) {
			$this->data['claim_date'] = $this->input->post('claim_date'); 
		} else if (isset($claim['claim_date'])) {
			$this->data['claim_date'] = $claim['claim_date'];
		} else {
			$this->data['claim_date'] = date("Y-m-d");
		}
		if ($this->input->post('note')) {
			$this->data['note'] = $this->input->post('note'); 
		} else if (isset($claim['note'])) {
			$this->data['note'] = $claim['note'];
		} else {
			$this->data['note'] = '';
		}
		
		if (!empty($this->data['error_message'])) {
			// How to show error_message?
			redirect('claim');
		}

		$this->load->model('plan_model');
		$this->data['plan'] = $this->plan_model->get_plan_by_id($this->data['plan_id']);

		$this->data['agent'] = $this->user_model->get_user_by_id($this->data['plan']['user_id']);
		
		if (!isset($this->data['button']) || ($this->data['button'] != 'Submit' )) {
			$this->data['button'] = 'Update';
		}

		$this->data['edit_url'] = base_url('claim/form');
		if ($this->data['claim_id']) {
			$this->data['claimed_amount'] = $this->claim_model->getClaimTotal($this->data['claim_id']);
		} else {
			$this->data['claimed_amount'] = 0;
		}
		
		$this->data['title_txt'] = 'Claim';
		$this->data['top_menu'] = $this->menu_model->load_top_menu();
		$this->data['menu'] = $this->menu_model->load_meun();
		$this->data['csrf'] = array (
				'name' => $this->security->get_csrf_token_name (),
				'value' => $this->security->get_csrf_hash ()
		);
		
		// for items
		$this->load->model('claim_model');
		$data['claimed_amount'] = $this->claim_model->getClaimTotal($this->data['claim_id']);
		$data['claim'] = $this->claim_model->get_claim_by_id($this->data['claim_id']);
		$data['lists'] = $this->claim_model->get_item_list($this->data['claim_id']);
		$data['add_url'] = base_url('citem/add/'.$this->data['claim_id']);
		$data['edit_url'] = base_url('citem/edit');
		$data['letter_url'] = base_url('citem/letter');
		$data['csrf'] = $this->data['csrf'];
		$data['combined'] = 1;
		
		$this->data['items'] = $this->load->view('citem/list', $data, true);

		$this->load->common('claim/form', $this->data);
	}
}
