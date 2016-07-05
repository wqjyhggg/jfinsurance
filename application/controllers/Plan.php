<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Plan extends MY_Controller {
	private $merchentID = "300203256";
	private $apikey = "EA368D97B29E474DB7E02B02CA679523";
	public $error;
	
	/**
	 * Index Page for this controller.
	 */
	public function index()
	{
		$beuser = $this->func_model->verify_login(); 
		$this->load->model('status_model');
		$this->load->model('product_model');
		$this->load->model('plan_model');
		
		$data = array();
		
		$data['beuser'] = $beuser;
		$data['firstname'] = $this->input->get_post('firstname');
		$data['lastname'] = $this->input->get_post('lastname');
		$data['birthday'] = $this->input->get_post('birthday');
		$data['birthday2'] = $this->input->get_post('birthday2');
		$data['policy'] = $this->input->get_post('policy');
		$data['apply_date'] = $this->input->get_post('apply_date');
		$data['apply_date2'] = $this->input->get_post('apply_date2');
		$data['arrival_date'] = $this->input->get_post('arrival_date');
		$data['arrival_date2'] = $this->input->get_post('arrival_date2');
		$data['effective_date'] = $this->input->get_post('effective_date');
		$data['effective_date2'] = $this->input->get_post('effective_date2');
		$data['expiry_date'] = $this->input->get_post('expiry_date');
		$data['expiry_date2'] = $this->input->get_post('expiry_date2');
		$data['uname'] = $this->input->get_post('uname');
		$data['batch_number'] = $this->input->get_post('batch_number');
		$data['status_id'] = $this->input->get_post('status_id');
		$data['product_short'] = $this->input->get_post('product_short');
		$data['province2'] = $this->input->get_post('province2');
		$data['country2'] = $this->input->get_post('country2');

		$data['product_list'] = $this->product_model->product_list();
		$prod2 = array();
		foreach($data['product_list'] as $key => $value) {
			if ($value['calculate']) {
				$prod2[$key] = $value;
			}
		}
		$data['product_list_a'] = $prod2;
		$data['status_list'] = $this->status_model->status_list();

		if ($this->input->post('search')) {
			/* plan_id, policy, batch_number, full_name, status_id, effective_date, firstname(customer), lastname(customer), agent_firstname, agent_lastname */
			$data['plan_list'] = $this->plan_model->plan_search($this->input->post(), 100);
		} else if ($this->input->get('q')) {
			$data['plan_list'] = $data['plan_list'] = $this->plan_model->plan_search(array('policy' => $this->input->get('q')), 100);
		} else {
			$data['plan_list'] = array();
		}

		$data['search_url'] = current_url ();
		$data['add_url'] = base_url ( "plan/add" );
		$data['edit_url'] = base_url ( "plan/edit" ) . "/";
		$data['copy_url'] = base_url ( "plan/copy" ) . "/";
		$data['province_url'] = base_url ( "geo/province/" );
		$data['country_url'] = base_url ( "geo/country/" );
		if (!empty($data['country2'])) {
			$data['province_url'] .= "/" . $data['country2'];
			$data['country_url'] .= "/" . $data['country2'];
			if (!empty($data['province2'])) {
				$data['province_url'] .= "/" . $data['province2'];
			}
		}
		$data['title_txt'] = 'Policy';
		$data['top_menu'] = $this->menu_model->load_top_menu();
		$data['menu'] = $this->menu_model->load_meun();

		$data['csrf'] = array (
				'name' => $this->security->get_csrf_token_name (),
				'value' => $this->security->get_csrf_hash ()
		);
		
		$this->load->common('plan/list', $data);
	}
	
	function form_valid() {
		$this->error = array();

		$product_short = $this->input->post('product_short');
		if (empty($product_short) || empty($this->product_model->get_product($product_short))) {
			redirect(base_url('plan'));
		}
		
		$nowtm = strtotime(date('Y-m-d'));
		$arrival_date = $this->input->post('arrival_date');
		$arrivaltm = strtotime($arrival_date);
		if (empty($arrival_date) || ($arrivaltm < 1466555500)) {
			$this->error['error_arrival_date'] = 'Confirm Arrival Date';
		}
		$effective_date = $this->input->post('effective_date');
		$effectivetm = strtotime($effective_date);
		if (empty($effective_date) || ($effectivetm < 1466555500)) {
			$this->error['error_effective_date'] = 'Confirm Effective Date';
		}
		$expiry_date = $this->input->post('expiry_date');
		$expirytm = strtotime($expiry_date);
		if (empty($expiry_date) || ($expirytm < 1466555500) || ($expirytm < $effectivetm)) {
			$this->error['error_expiry_date'] = 'Confirm Expiry Date';
		}
		if (empty($this->input->post('beneficiary'))) {
			$this->error['error_beneficiary'] = 'Beneficiary is Required';
		}
		if (empty($this->input->post('firstname'))) {
			$this->error['error_firstname'] = 'Firstname is Required';
		}
		if (empty($this->input->post('lastname'))) {
			$this->error['error_lastname'] = 'Lastname is Required';
		}
		if (empty($this->input->post('birthday'))) {
			$this->error['error_birthday'] = 'Birthday is Required';
		}
		if (empty($this->input->post('street_number'))) {
			$this->error['error_street_number'] = 'Street number is Required';
		}
		if (empty($this->input->post('street_name'))) {
			$this->error['error_street_name'] = 'Street name is Required';
		}
		if (empty($this->input->post('city'))) {
			$this->error['error_city'] = 'City Required';
		}
		if (empty($this->input->post('postcode'))) {
			$this->error['error_postcode'] = 'Postcode is Required';
		}
		if (empty($this->input->post('phone1'))) {
			$this->error['error_phone1'] = 'Phone1 is Required';
		}
		if (empty($this->input->post('contact_email'))) {
			$this->error['error_contact_email'] = 'Contact email is Required';
		}
		return empty($this->error);
	}
	
	function form($plan=array()) {
		$beuser = $this->func_model->verify_login();
		$this->load->model('customer_model');
		
		$this->load->model('status_model');
		$this->load->model('product_model');
		$this->load->model('plan_model');

		$this->error = array();
	
		if ($this->input->post('submit') && $this->form_valid()) {
			$plan_id = $this->input->post('plan_id');
		
			if (empty($plan_id)) {
				$plan_id = $this->plan_model->add($this->input->post());
				if ($plan_id) {
					$plan = $this->plan_model->get_plan_by_id($plan_id);
					$para = array(
							'plan_id' => $plan_id, 
							'customer_id' => $plan['customer_id'], 
							'payment_id' => 0, 
							'message' => $this->plan_model->logstr, 
							'systemlog' => $this->plan_model->sqlstr
					);
					$this->log_model->activity('plan', $para);
				}
			} else {
				$plan_id = $this->plan_model->update($plan_id, $this->input->post());
				if ($plan_id) {
					$plan = $this->plan_model->get_plan_by_id($plan_id);
					$para = array(
							'plan_id' => $plan_id, 
							'customer_id' => $plan['customer_id'], 
							'payment_id' => 0, 
							'message' => $this->plan_model->logstr, 
							'systemlog' => $this->plan_model->sqlstr
					);
					$this->log_model->activity('plan', $para);
				}
			}
			if ($plan_id) {
				redirect("plan/term/" . $plan_id);
			}
		} else if (isset($plan['plan_id'])) {
			$plan_id = $plan['plan_id'];
			$customer = array();
			$customers = array();
			$plan = $this->plan_model->get_plan_by_id($plan['plan_id']);
			if ($plan) {
				$customer = $this->customer_model->get_customer_by_id($plan['customer_id']);
				if ($customer) {
					$customers = $this->customer_model->get_customer_by_parent_id($plan['customer_id']);
				}
			}
		} else {
			$plan_id = 0;
		}
		
		$data = $this->error;
		
		if ($this->input->post('plan_id')) {
			$data['plan_id'] = $this->input->post('plan_id'); 
		} else if (isset($plan['plan_id'])) {
			$data['plan_id'] = $plan['plan_id'];
		} else {
			$data['plan_id'] = 0;
		}
		if ($this->input->post('product_short')) {
			$data['product_short'] = $this->input->post('product_short'); 
		} else if (isset($plan['product_short'])) {
			$data['product_short'] = $plan['product_short'];
		} else {
			redirect(base_url('plan'));
		}
		if ($this->input->post('status_id')) {
			$data['status_id'] = $this->input->post('status_id'); 
		} else if (isset($plan['status_id'])) {
			$data['status_id'] = $plan['status_id'];
		} else {
			$data['status_id'] = 0;
		}
		if ($this->input->post('apply_date')) {
			$data['apply_date'] = $this->input->post('apply_date'); 
		} else if (isset($plan['apply_date'])) {
			$data['apply_date'] = $plan['apply_date'];
		} else {
			$data['apply_date'] = date("Y-m-d");
		}
		if ($this->input->post('arrival_date')) {
			$data['arrival_date'] = $this->input->post('arrival_date'); 
		} else if (isset($plan['arrival_date'])) {
			$data['arrival_date'] = $plan['arrival_date'];
		} else {
			$data['arrival_date'] = '';
		}
		if ($this->input->post('effective_date')) {
			$data['effective_date'] = $this->input->post('effective_date'); 
		} else if (isset($plan['effective_date'])) {
			$data['effective_date'] = $plan['effective_date'];
		} else {
			$data['effective_date'] = '';
		}
		if ($this->input->post('expiry_date')) {
			$data['expiry_date'] = $this->input->post('expiry_date'); 
		} else if (isset($plan['expiry_date'])) {
			$data['expiry_date'] = $plan['expiry_date'];
		} else {
			$data['expiry_date'] = '';
		}
		if ($this->input->post('beneficiary')) {
			$data['beneficiary'] = $this->input->post('beneficiary'); 
		} else if (isset($plan['beneficiary'])) {
			$data['beneficiary'] = $plan['beneficiary'];
		} else {
			$data['beneficiary'] = '';
		}
		if ($this->input->post('isfamilyplan')) {
			$data['isfamilyplan'] = $this->input->post('isfamilyplan'); 
		} else if (isset($plan['isfamilyplan'])) {
			$data['isfamilyplan'] = $plan['isfamilyplan'];
		} else {
			$data['isfamilyplan'] = 0;
		}
		if ($this->input->post('sum_insured')) {
			$data['sum_insured'] = $this->input->post('sum_insured'); 
		} else if (isset($plan['sum_insured'])) {
			$data['sum_insured'] = $plan['sum_insured'];
		} else {
			$data['sum_insured'] = '';
		}
		if ($this->input->post('premium')) {
			$data['premium'] = $this->input->post('premium'); 
		} else if (isset($plan['premium'])) {
			$data['premium'] = $plan['premium'];
		} else {
			$data['premium'] = 0;
		}
		if ($this->input->post('deductible_amount')) {
			$data['deductible_amount'] = $this->input->post('deductible_amount'); 
		} else if (isset($plan['deductible_amount'])) {
			$data['deductible_amount'] = $plan['deductible_amount'];
		} else {
			$data['deductible_amount'] = '';
		}
		if ($this->input->post('customer_id')) {
			$data['customer_id'] = $this->input->post('customer_id');
		} else if (isset($plan['customer_id'])) {
			$data['customer_id'] = $plan['customer_id'];
		} else {
			$data['customer_id'] = 0;
		}
		if ($this->input->post('firstname')) {
			$data['firstname'] = $this->input->post('firstname');
		} else if (isset($customer['firstname'])) {
			$data['firstname'] = $customer['firstname'];
		} else {
			$data['firstname'] = '';
		}
		if ($this->input->post('lastname')) {
			$data['lastname'] = $this->input->post('lastname');
		} else if (isset($customer['lastname'])) {
			$data['lastname'] = $customer['lastname'];
		} else {
			$data['lastname'] = '';
		}
		if ($this->input->post('birthday')) {
			$data['birthday'] = $this->input->post('birthday');
		} else if (isset($customer['birthday'])) {
			$data['birthday'] = $customer['birthday'];
		} else {
			$data['birthday'] = '';
		}
		if ($this->input->post('gender')) {
			$data['gender'] = $this->input->post('gender');
		} else if (isset($customer['gender'])) {
			$data['gender'] = $customer['gender'];
		} else {
			$data['gender'] = 'M';
		}
		for ($i = 1; $i < 9; $i++) {
			if ($this->input->post('customer_id_'.$i)) {
				$data['customer_id_'.$i] = $this->input->post('customer_id_'.$i);
			} else if (isset($customers[$i - 1]) && isset($customers[$i - 1]['customer_id'])) {
				$data['customer_id_'.$i] = $customers[$i - 1]['customer_id'];
			} else {
				$data['customer_id_'.$i] = 0;
			}
			if ($this->input->post('firstname_'.$i)) {
				$data['firstname_'.$i] = $this->input->post('firstname_'.$i);
			} else if (isset($customers[$i - 1]) && isset($customers[$i - 1]['firstname'])) {
				$data['firstname_'.$i] = $customers[$i - 1]['firstname'];
			} else {
				$data['firstname_'.$i] = '';
			}
			if ($this->input->post('lastname_'.$i)) {
				$data['lastname_'.$i] = $this->input->post('lastname_'.$i);
			} else if (isset($customers[$i - 1]) && isset($customers[$i - 1]['lastname'])) {
				$data['lastname_'.$i] = $customers[$i - 1]['lastname'];
			} else {
				$data['lastname_'.$i] = '';
			}
			if ($this->input->post('birthday_'.$i)) {
				$data['birthday_'.$i] = $this->input->post('birthday_'.$i);
			} else if (isset($customers[$i - 1]) && isset($customers[$i - 1]['birthday'])) {
				$data['birthday_'.$i] = $customers[$i - 1]['birthday'];
			} else {
				$data['birthday_'.$i] = '';
			}
			if ($this->input->post('gender_'.$i)) {
				$data['gender_'.$i] = $this->input->post('gender_'.$i);
			} else if (isset($customers[$i - 1]) && isset($customers[$i - 1]['gender'])) {
				$data['gender_'.$i] = $customers[$i - 1]['gender'];
			} else {
				$data['gender_'.$i] = 'M';
			}
		}
		if ($this->input->post('street_number')) {
			$data['street_number'] = $this->input->post('street_number');
		} else if (isset($plan['street_number'])) {
			$data['street_number'] = $plan['street_number'];
		} else {
			$data['street_number'] = '';
		}
		if ($this->input->post('street_name')) {
			$data['street_name'] = $this->input->post('street_name');
		} else if (isset($plan['street_name'])) {
			$data['street_name'] = $plan['street_name'];
		} else {
			$data['street_name'] = '';
		}
		if ($this->input->post('suite_number')) {
			$data['suite_number'] = $this->input->post('suite_number');
		} else if (isset($plan['suite_number'])) {
			$data['suite_number'] = $plan['suite_number'];
		} else {
			$data['suite_number'] = '';
		}
		if ($this->input->post('city')) {
			$data['city'] = $this->input->post('city');
		} else if (isset($plan['city'])) {
			$data['city'] = $plan['city'];
		} else {
			$data['city'] = '';
		}
		if ($this->input->post('province2')) {
			$data['province2'] = $this->input->post('province2');
		} else if (isset($plan['province2'])) {
			$data['province2'] = $plan['province2'];
		} else {
			$data['province2'] = '';
		}
		if ($this->input->post('country2')) {
			$data['country2'] = $this->input->post('country2');
		} else if (isset($plan['country2'])) {
			$data['country2'] = $plan['country2'];
		} else {
			$data['country2'] = '';
		}
		if ($this->input->post('postcode')) {
			$data['postcode'] = $this->input->post('postcode');
		} else if (isset($plan['postcode'])) {
			$data['postcode'] = $plan['postcode'];
		} else {
			$data['postcode'] = '';
		}
		if ($this->input->post('phone1')) {
			$data['phone1'] = $this->input->post('phone1');
		} else if (isset($plan['phone1'])) {
			$data['phone1'] = $plan['phone1'];
		} else {
			$data['phone1'] = '';
		}
		if ($this->input->post('phone2')) {
			$data['phone2'] = $this->input->post('phone2');
		} else if (isset($plan['phone2'])) {
			$data['phone2'] = $plan['phone2'];
		} else {
			$data['phone2'] = '';
		}
		if ($this->input->post('contact_email')) {
			$data['contact_email'] = $this->input->post('contact_email');
		} else if (isset($plan['contact_email'])) {
			$data['contact_email'] = $plan['contact_email'];
		} else {
			$data['contact_email'] = '';
		}
		if ($this->input->post('contact_phone')) {
			$data['contact_phone'] = $this->input->post('contact_phone');
		} else if (isset($plan['contact_phone'])) {
			$data['contact_phone'] = $plan['contact_phone'];
		} else {
			$data['contact_phone'] = '';
		}
		if ($this->input->post('residence')) {
			$data['residence'] = $this->input->post('residence');
		} else if (isset($plan['residence'])) {
			$data['residence'] = $plan['residence'];
		} else {
			$data['residence'] = '';
		}
		if ($this->input->post('note')) {
			$data['note'] = $this->input->post('note');
		} else if (isset($plan['note'])) {
			$data['note'] = $plan['note'];
		} else {
			$data['note'] = '';
		}

		$data['show_history'] = 0;
		if (empty($data['plan_id'])) {
			$data['submit'] = 'Add New Policy';
			$data['p_header'] = 'Create Policy';
		} else {
			$data['p_header'] = 'Edit Policy';
			$data['submit'] = 'Update Policy';
			if ($beuser['user_group_id'] <= 2) {
				$this->load->model('trans_model');
				$data['show_history'] = 1;
				$data['activelogs'] = $this->log_model->get_activity_by_plan_id($data['plan_id']);
				$data['payments'] = $this->trans_model->get_payment_by_plan_id($data['plan_id']);
			}
		}
		$data['sum_insured_url'] = base_url ( "product/insured/" . $data['product_short'] );
		if (!empty($data['sum_insured'])) $data['sum_insured_url'] .= "/" . $data['sum_insured']; 
		$data['deductible_amount_url'] = base_url ( "product/deductible/" . $data['product_short'] );
		if (!empty($data['deductible_amount'])) $data['deductible_amount_url'] .= "/" . $data['deductible_amount'];
		$data['province_url'] = base_url ( "geo/province" );
		$data['country_url'] = base_url ( "geo/country" );
		if (!empty($data['country2'])) {
			$data['province_url'] .= "/" . $data['country2'];
			$data['country_url'] .= "/" . $data['country2'];
			if (!empty($data['province2'])) {
				$data['province_url'] .= "/" . $data['province2'];
			}
		}

		$data['user_group_id'] = $beuser['user_group_id'];
		$data['status_list'] = $this->status_model->status_list();
		if ((int)$data['plan_id'] > 0) {
			$data['copy_url'] = base_url ( "plan/copy/" . (int)$data['plan_id'] );
			if ((int)$data['status_id'] == 1) {
				$data['pay_url'] = base_url ( "plan/pay/" . (int)$data['plan_id'] );
			} else {
				$data['pay_url'] = '';
			}
		} else {
			$data['copy_url'] = '';
			$data['pay_url'] = '';
		}
		
		$data['premium_url'] = base_url ( "product/premium" );
		$data['action_url'] = base_url ( "plan/form" );
		$data['title_txt'] = 'Policy';
		$data['top_menu'] = $this->menu_model->load_top_menu();
		$data['menu'] = $this->menu_model->load_meun();
		$data['csrf'] = array (
				'name' => $this->security->get_csrf_token_name (),
				'value' => $this->security->get_csrf_hash ()
		);
		
		if ($data['product_short'] == 'OPL') {
			$data['insurable_options'] = $this->load->view('plan/form_opl', $data, TRUE);
		} else if ($data['product_short'] == 'JFR') {
			$data['insurable_options'] = $this->load->view('plan/form_opl', $data, TRUE);
		} else if ($data['product_short'] == 'JUS') {
			$data['insurable_options'] = $this->load->view('plan/form_jus', $data, TRUE);
		} else if ($data['product_short'] == 'NUS') {
			$data['insurable_options'] = $this->load->view('plan/form_jus', $data, TRUE);
		} else if ($data['product_short'] == 'JES') {
			$data['insurable_options'] = $this->load->view('plan/form_jes', $data, TRUE);
		} else /* if ($plan['product_short'] == 'JFC') */ {
			$data['insurable_options'] = $this->load->view('plan/form_jes', $data, TRUE);
		}

		//$this->load->common('plan/form', $data);
		$this->load->common('plan/form', $data);
	}
	
	function add() {
		$beuser = $this->func_model->verify_login(); 
		$product_short = $this->input->post_get('product_short');
		if (empty($product_short)) {
			redirect(base_url('production'));
		}
		$plan = array('product_short' => $product_short);
		return ($this->form($plan));
	}

	function term($plan_id=0) {
		$beuser = $this->func_model->verify_login(); 
		if (empty($plan_id)) {
			$plan_id = $this->input->post('plan_id');
		}
		if (empty($plan_id)) {
			redirect(base_url('production'));
		}

		$data['message'] = '';
		if ($this->input->post('submit')) {
			$agree = $this->input->post('agree');
			if ($agree) {
				$this->load->model('plan_model');
				$this->load->model('product_model');
				
				$para = array('agree' => 1);
				$this->plan_model->update($plan_id, $para);
				$plan = $this->plan_model->get_plan_by_id($plan_id);
				$para = array(
						'plan_id' => $plan_id, 
						'customer_id' => $plan['customer_id'], 
						'payment_id' => 0, 
						'message' => $this->plan_model->logstr, 
						'systemlog' => $this->plan_model->sqlstr
				);
				$this->log_model->activity('plan', $para);
				
				redirect('plan/detail/'.$plan_id);
			} else {
				$data['message'] = 'You need agree term and conditions to continue';
			}
		}
		$data['plan_id'] = $plan_id;
		
		$data['action_url'] = base_url('plan/term');
		$data['title_txt'] = 'Policy';
		$data['top_menu'] = $this->menu_model->load_top_menu();
		$data['menu'] = $this->menu_model->load_meun();
		$data['csrf'] = array (
				'name' => $this->security->get_csrf_token_name (),
				'value' => $this->security->get_csrf_hash ()
		);
		
		$this->load->common('plan/term', $data);
	}

	private function credit_card() {
		$this->load->model('plan_model');
		$this->load->model('product_model');
		$this->load->model('trans_model');
		
		$plan_id = $this->input->post('plan_id');
		$premium = $this->input->post('premium');
		
		if (empty( $this->request->post ['card_number'] ) ) {
			$this->error = 'Please input Card Number';
		} else if (empty( $this->request->post ['card_name'] ) ) {
			$this->error = 'Please input Card Name';
		} else if (empty( $this->request->post ['expiry_month'] ) ) {
			$this->error = 'Please select Expiry Month';
		} else if (empty( $this->request->post ['expiry_year'] ) ) {
			$this->error = 'Please select Expiry Year';
		} else if (empty( $this->request->post ['card_cvv'] ) ) {
			$this->error = 'Please Card CVV';
		} else {
			$card_number = $this->request->post ['card_number'];
			$card_name = $this->request->post ['card_name'];
			$expiry_month = $this->request->post ['expiry_month'];
			$expiry_year = $this->request->post ['expiry_year'];
			$card_cvv = $this->request->post ['card_cvv'];

			$plan = $this->plan_model->get_plan_by_id($plan_id);
			if ($plan['status_id'] != 2) {
				$dt = array();
				$dt['plan_id'] = $plan_id;
				$dt['amount'] = $premium;
				$dt['pay_type'] = 'premium';
				$dt['pay_mothed'] = 'Credit Card';
				$dt['name'] = $card_name;
				$dt['added'] = date('c');
				$dt['first5'] = substr($card_number, 0, 5);
				$dt['last4'] = substr($card_number, -4);
				$dt['expiry_month'] = $expiry_month;
				$dt['expiry_year'] = $expiry_year;
				$dt['expiry_year'] = $expiry_year;
				$dt['ispaid'] = 0;
				$payment_id = $this->trans_model->add($dt);
				$para = array(
						'plan_id' => $plan_id,
						'customer_id' => $plan['customer_id'],
						'payment_id' => $payment_id,
						'message' => $this->trans_model->logstr,
						'systemlog' => $this->trans_model->sqlstr
				);
				$this->log_model->activity('payment', $para);
				
				$para = array('payinfo' => $payinfo, 'status_id' => 2, 'policy' => $this->plan_model->get_policy_number($plan_id, 2));
				$this->plan_model->update($plan_id, $para);
				$para = array(
						'plan_id' => $plan_id,
						'customer_id' => $plan['customer_id'],
						'payment_id' => $payment_id,
						'message' => $this->plan_model->logstr,
						'systemlog' => $this->plan_model->sqlstr
				);
				$this->log_model->activity('plan', $para);

			} else /* if ($plan['status_id'] == 2) */ {
				$payment = $this->trans_model->get_payment($plan_id, 'premium', 0);
				if (!empty($premium)) {
					$dt = array();
					$dt['plan_id'] = $plan_id;
					$dt['amount'] = $premium;
					$dt['pay_type'] = 'premium';
					$dt['pay_mothed'] = 'Credit Card';
					$dt['name'] = $card_name;
					$dt['added'] = date('c');
					$dt['first5'] = substr($card_number, 0, 5);
					$dt['last4'] = substr($card_number, -4);
					$dt['expiry_month'] = $expiry_month;
					$dt['expiry_year'] = $expiry_year;
					$dt['expiry_year'] = $expiry_year;
					$dt['ispaid'] = 0;
					$payment_id = $this->trans_model->update($payment['payment_id'], $dt);
					$para = array(
							'plan_id' => $plan_id,
							'customer_id' => $plan['customer_id'],
							'payment_id' => $payment_id,
							'message' => $this->trans_model->logstr,
							'systemlog' => $this->trans_model->sqlstr
					);
					$this->log_model->activity('payment', $para);
				} else {
					$payment_id = $payment['payment_id'];
				}
				
				$para = array('payinfo' => $payinfo, 'status_id' => 2);
				$this->plan_model->update($plan_id, $para);
				$para = array(
						'plan_id' => $plan_id,
						'customer_id' => $plan['customer_id'],
						'payment_id' => $payment_id,
						'message' => $this->plan_model->logstr,
						'systemlog' => $this->plan_model->sqlstr
				);
				$this->log_model->activity('plan', $para);
			}

			$beanstream = new \Beanstream\Gateway ( $this->merchentID, $this->apikey, 'www', 'v1' );
			$payment_data = array (
					'order_number' => $plan_id,
					'amount' => $premium,
					'payment_method' => 'card',
					'card' => array (
							'name' => $card_name,
							'number' => $card_number,
							'expiry_month' => $expiry_month,
							'expiry_year' => $expiry_year,
							'cvd' => $card_cvv 
					) 
			);
			try {
				$result = $beanstream->payments ()->makeCardPayment ( $payment_data, TRUE ); // set to FALSE for Pre-Auth
				if (isset($result['approved'])) {
					$dt['ispaid'] = 1;
					$dt['note'] = "Success: Raw Data=> " . json_encode($result);
					$payment_id = $this->trans_model->update($payment_id, $dt);
					$para = array(
							'plan_id' => $plan_id,
							'customer_id' => $plan['customer_id'],
							'payment_id' => $payment_id,
							'message' => $this->trans_model->logstr,
							'systemlog' => $this->trans_model->sqlstr
					);
					$this->log_model->activity('payment', $para);

					$para = array('payinfo' => $payinfo, 'status_id' => 3);
					$this->plan_model->update($plan_id, $para);
					$para = array(
							'plan_id' => $plan_id,
							'customer_id' => $plan['customer_id'],
							'payment_id' => $payment_id,
							'message' => $this->plan_model->logstr,
							'systemlog' => $this->plan_model->sqlstr
					);
					$this->log_model->activity('payment', $para);
				} else {
					$dt['ispaid'] = 0;
					$dt['note'] = "Failur: Raw Data=> " . json_encode($result);
					$payment_id = $this->trans_model->update($payment_id, $dt);
					$para = array(
							'plan_id' => $plan_id,
							'customer_id' => $plan['customer_id'],
							'payment_id' => $payment_id,
							'message' => $this->trans_model->logstr,
							'systemlog' => $this->trans_model->sqlstr
					);
					$this->log_model->activity('payment', $para);
				}
			} catch ( \Beanstream\Exception $e ) {
				// print_r ( $e->getMessage() );
				$dt['ispaid'] = 0;
				$dt['note'] = "Failur: (libraray) Raw Data=> " . $e->getMessage() . " : " . json_encode($e);
				$payment_id = $this->trans_model->update($payment_id, $dt);
				$para = array(
						'plan_id' => $plan_id,
						'customer_id' => $plan['customer_id'],
						'payment_id' => $payment_id,
						'message' => $this->trans_model->logstr,
						'systemlog' => $this->trans_model->sqlstr
				);
				$this->log_model->activity('payment', $para);
			}
		}
	}

	private function cash() {
		$this->load->model('plan_model');
		$this->load->model('product_model');
		
		$plan_id = $this->input->post('plan_id');
		$premium = $this->input->post('premium');
		$payinfo = "Pay Cash: " . 'Premium: $' . $premium . "; ";
		
		$plan = $this->plan_model->get_plan_by_id($plan_id);
		if ($plan['status_id'] != 2) {
			$dt = array();
			$dt['plan_id'] = $plan_id;
			$dt['amount'] = $premium;
			$dt['pay_type'] = 'premium';
			$dt['pay_mothed'] = 'Cash';
			$dt['added'] = date('c');
			$dt['note'] = $payinfo;
			$dt['ispaid'] = 0;
			$payment_id = $this->trans_model->add($dt);
			$para = array(
					'plan_id' => $plan_id,
					'customer_id' => $plan['customer_id'],
					'payment_id' => $payment_id,
					'message' => $this->trans_model->logstr,
					'systemlog' => $this->trans_model->sqlstr
			);
			$this->log_model->activity('payment', $para);
			
			$para = array('payinfo' => $payinfo, 'status_id' => 2, 'policy' => $this->plan_model->get_policy_number($plan_id, 2));
			$this->plan_model->update($plan_id, $para);
			$para = array(
					'plan_id' => $plan_id,
					'customer_id' => $plan['customer_id'],
					'payment_id' => $payment_id,
					'message' => $this->plan_model->logstr,
					'systemlog' => $this->plan_model->sqlstr
			);
			$this->log_model->activity('plan', $para);
		
		} else /* if ($plan['status_id'] == 2) */ {
			// Change pay_type
			$payment = $this->trans_model->get_payment($plan_id, 'premium', 0);
			if (!empty($premium)) {
				$dt = array();
				$dt['plan_id'] = $plan_id;
				$dt['amount'] = $premium;
				$dt['pay_type'] = 'premium';
				$dt['pay_mothed'] = 'Cash';
				$dt['added'] = date('c');
				$dt['note'] = $payinfo;
				$dt['ispaid'] = 0;
				$payment_id = $this->trans_model->update($payment['payment_id'], $dt);
				$para = array(
						'plan_id' => $plan_id,
						'customer_id' => $plan['customer_id'],
						'payment_id' => $payment_id,
						'message' => $this->trans_model->logstr,
						'systemlog' => $this->trans_model->sqlstr
				);
				$this->log_model->activity('payment', $para);
			} else {
				$payment_id = $payment['payment_id'];
			}
					
			$para = array('payinfo' => $payinfo);
			$this->plan_model->update($plan_id, $para);
			$para = array(
					'plan_id' => $plan_id,
					'customer_id' => $plan['customer_id'],
					'payment_id' => $payment_id,
					'message' => $this->plan_model->logstr,
					'systemlog' => $this->plan_model->sqlstr
			);
			$this->log_model->activity('plan', $para);
		
		}
	}

	private function cheque() {
		$this->load->model('plan_model');
		$this->load->model('product_model');
		
		$plan_id = $this->input->post('plan_id');
		$premium = $this->input->post('premium');
		$payinfo  = 'Bank Name: ' . $this->input->post('bank_name') . "; ";
		$payinfo .= 'Payor Name: ' . $this->input->post('payor_name') . "; ";
		$payinfo .= 'Checque#: ' . $this->input->post('cheque_number') . "; ";
		$payinfo .= 'Premium: $' . $this->input->post('premium') . "; ";

		$plan = $this->plan_model->get_plan_by_id($plan_id);
		if ($plan['status_id'] != 2) {
			$dt = array();
			$dt['plan_id'] = $plan_id;
			$dt['amount'] = $premium;
			$dt['pay_type'] = 'premium';
			$dt['pay_mothed'] = 'Checque';
			$dt['added'] = date('c');
			$dt['note'] = $payinfo;
			$dt['ispaid'] = 0;
			$payment_id = $this->trans_model->add($dt);
				
			$para = array('payinfo' => $payinfo, 'status_id' => 2, 'policy' => $this->plan_model->get_policy_number($plan_id, 2));
			$this->plan_model->update($plan_id, $para);
			$para = array(
					'plan_id' => $plan_id,
					'customer_id' => $plan['customer_id'],
					'payment_id' => 0,
					'message' => $this->plan_model->logstr,
					'systemlog' => $this->plan_model->sqlstr
			);
			$this->log_model->activity('plan', $para);
		} else {
			$payment = $this->trans_model->get_payment($plan_id, 'premium', 0);
			if (!empty($premium)) {
				$dt = array();
				$dt['plan_id'] = $plan_id;
				$dt['amount'] = $premium;
				$dt['pay_type'] = 'premium';
				$dt['pay_mothed'] = 'Checque';
				$dt['added'] = date('c');
				$dt['note'] = $payinfo;
				$dt['ispaid'] = 0;
				$payment_id = $this->trans_model->update($dt);
			} else {
				$payment_id = $payment['payment_id'];
			}
			
			$para = array('payinfo' => $payinfo, 'status_id' => 2);
			$this->plan_model->update($plan_id, $para);
			$para = array(
					'plan_id' => $plan_id,
					'customer_id' => $plan['customer_id'],
					'payment_id' => 0,
					'message' => $this->plan_model->logstr,
					'systemlog' => $this->plan_model->sqlstr
			);
			$this->log_model->activity('plan', $para);
		}
	}

	function detail($plan_id=0, $sekey='') {
		$this->error = '';
		if ($play_type = $this->input->post('play_type')) {
			$plan_id = $this->input->post('plan_id');
			$sekey = $this->input->post('sekey');
			if ($play_type == 'Credit Card') {
				$this->credit_card();
			} else if ($play_type == 'Cash') {
				$this->cash();
			} else if ($play_type == 'Cheque') {
				$this->cheque();
			}
		}
		if (empty($plan_id)) {
			redirect(base_url('production'));
		}
		
		$this->load->model('customer_model');
		$this->load->model('plan_model');
		$this->load->model('product_model');
		$this->load->model('paytype_model');
		$plan = $this->plan_model->get_plan_by_id($plan_id);
		if (empty($plan)) {
			redirect('user/login');
		}
		if (empty($sekey)) {
			$beuser = $this->func_model->verify_login();
		} else {
			$beuser = $this->user_model->get_user_by_id($plan['user_id']);
			$key = $this->plan_model->get_plan_key($plan_id);
			if ($key != $sekey) {
				redirect('user/login');
			}
		}
		
		$data['errorms'] = $beuser;
		$data['beuser'] = $beuser;
		$data['sekey'] = $sekey;
		$data['customer'] = $this->customer_model->get_customer_by_id($plan['customer_id']);
		$data['customers'] = $this->customer_model->get_customer_by_parent_id($plan['customer_id']);
		$data['apply_date'] = date('Y-m-d');
		
		$para = array();
		$para['product_short'] = $plan['product_short'];
		$para['apply_date'] = date('Y-m-d');
		$para['effective_date'] = $plan['effective_date'];
		$para['expiry_date'] = $plan['expiry_date'];
		$para['isfamilyplan'] = $plan['isfamilyplan'];
		$para['sum_insured'] = $plan['sum_insured'];
		$para['deductible_amount'] = $plan['deductible_amount'];
		$para['stable_condition'] = $plan['stable_condition'];
		$para['birthday'] = $this->customer_model->get_max_birthday($plan['customer_id'], $plan['isfamilyplan']);
		$para['number_customer'] = $this->customer_model->get_number_customer($plan['customer_id'], $plan['isfamilyplan']);
		$premiumarr = $this->product_model->get_premium($para);
		if (!empty($premiumarr) && !empty($premiumarr['premium']) && ((float)$premiumarr['premium'] != (float)$plan['premium'])) {
			$para = array('premium' => $premiumarr['premium']);
			$this->plan_model->update($plan['plan_id'], $para);
			$plan = $this->plan_model->get_plan_by_id($plan['plan_id']);
			$para = array(
					'plan_id' => $plan_id, 
					'customer_id' => $plan['customer_id'], 
					'payment_id' => 0, 
					'message' => $this->plan_model->logstr, 
					'systemlog' => $this->plan_model->sqlstr
			);
			$this->log_model->activity('plan', $para);
		}
		$data['premiumarr'] = $premiumarr;
		$data['plan'] = $plan;
		$data['customer'] = $this->customer_model->get_customer_by_id($plan['customer_id']);
		$data['customers'] = $this->customer_model->get_customer_by_parent_id($plan['customer_id']);
		
		if ($beuser['user_group_id'] <= 2) {
			$data['paytype_list'] = $this->paytype_model->paytype_list();
		} else {
			$data['paytype_list'] = split(",", $beuser['pay_type']);
		}
		$data['payurl'] = base_url('plan/detail/' . $plan_id . '/' . $this->plan_model->get_plan_key($plan_id));
		$data['active_url'] = current_url();
		if ($plan['status_id'] <= 2) {
			$display = 1;
			if (in_array('Credit Card', $data['paytype_list'])) {
				$data['credit_dis'] = $display;
				$data['pay_type'] = 'Credit Card';
				$display = 0;
			}
			if (in_array('Cheque', $data['paytype_list'])) {
				$data['cheque_dis'] = $display;
				$data['pay_type'] = 'Cheque';
				$display = 0;
			}
			if (in_array('Cash', $data['paytype_list'])) {
				$data['cash_dis'] = $display;
				$data['pay_type'] = 'Cash';
				$display = 0;
			}
		}
		
		$data['title_txt'] = 'Policy';
		$data['top_menu'] = $this->menu_model->load_top_menu();
		$data['menu'] = $this->menu_model->load_meun();
		$data['csrf'] = array (
				'name' => $this->security->get_csrf_token_name (),
				'value' => $this->security->get_csrf_hash ()
		);
		
		$this->load->common('plan/detail', $data);
	}

	function copy($plan_id) {
		$this->load->model('plan_model');
		$plan = $this->plan_model->get_plan_by_id($plan_id);
		if ($plan) {
			unset($plan['plan_id']);
			unset($plan['customer_id']);
			unset($plan['user_id']);
			unset($plan['status_id']);
			unset($plan['policy']);
			unset($plan['agree']);
			unset($plan['batch_number']);
		}
		$this->form($plan);
	}

	function edit($plan_id=0) {
		$this->load->model('plan_model');
		$plan = $this->plan_model->get_plan_by_id($plan_id);
		$this->form($plan);
	}
}
