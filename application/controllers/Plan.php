<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Plan extends MY_Controller {
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
		$data['brithday'] = $this->input->get_post('brithday');
		$data['brithday2'] = $this->input->get_post('brithday2');
		$data['policy'] = $this->input->get_post('policy');
		$data['apply_time'] = $this->input->get_post('apply_time');
		$data['apply_time2'] = $this->input->get_post('apply_time2');
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
		$data['status_list'] = $this->status_model->status_list();

		if ($this->input->post('search')) {
			/* plan_id, policy, batch_number, full_name, status_id, effective_date, firstname(customer), lastname(customer), agent_firstname, agent_lastname */
			$data['plan_list'] = $this->plan_model->plan_search($this->input->post(), 100);
			$data['plan_list'] = array();
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
		
		$nowtm = time();
		$arrival_date = $this->input->post('arrival_date');
		$arrivaltm = strtotime($arrival_date);
		if (empty($arrival_date) || ($arrivaltm < $nowtm)) {
			$this->error['error_arrival_date'] = 'Confirm Arrival Date';
		}
		$effective_date = $this->input->post('effective_date');
		$effectivetm = strtotime($effective_date);
		if (empty($effective_date) || ($effectivetm < $nowtm)) {
			$this->error['error_effective_date'] = 'Confirm Effective Date';
		}
		$expiry_date = $this->input->post('expiry_date');
		$expirytm = strtotime($expiry_date);
		if (empty($expiry_date) || ($expirytm < $nowtm) || ($expirytm < $effectivetm)) {
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
		if (empty($this->input->post('brithday'))) {
			$this->error['error_brithday'] = 'Brithday is Required';
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
					$this->log_model->activity('user', array('message' => $this->plan_model->logstr, 'systemlog' => $this->plan_model->sqlstr));
				}
			} else {
				$plan_id = $this->plan_model->update($plan_id, $this->input->post());
				if ($plan_id) {
					$this->log_model->activity('user', array('message' => $this->plan_model->logstr, 'systemlog' => $this->plan_model->sqlstr));
				}
			}
			if ($plan_id) {
				redirect("plan/term");
			}
		} else if (isset($plan['plan_id'])) {
			$customer = array();
			$customers = array();
			$plan = $this->plan_model->get_plan_by_id($plan['plan_id']);
			if ($plan) {
				$customer = $this->customer_model->get_customer_by_id($plan['customer_id']);
				if ($customer) {
					$customers = $this->customer_model->get_customer_by_parent_id($plan['customer_id']);
				}
			}
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
		} else if (isset($plan['plan_id'])) {
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
		if ($this->input->post('deductiable_amount')) {
			$data['deductiable_amount'] = $this->input->post('deductiable_amount'); 
		} else if (isset($plan['deductiable_amount'])) {
			$data['deductiable_amount'] = $plan['deductiable_amount'];
		} else {
			$data['deductiable_amount'] = '';
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
		if ($this->input->post('brithday')) {
			$data['brithday'] = $this->input->post('brithday');
		} else if (isset($customer['brithday'])) {
			$data['brithday'] = $customer['brithday'];
		} else {
			$data['brithday'] = '';
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
			if ($this->input->post('brithday_'.$i)) {
				$data['brithday_'.$i] = $this->input->post('brithday_'.$i);
			} else if (isset($customers[$i - 1]) && isset($customers[$i - 1]['brithday'])) {
				$data['brithday_'.$i] = $customers[$i - 1]['brithday'];
			} else {
				$data['brithday_'.$i] = '';
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
		if (empty($plan_id)) {
			$data['submit'] = 'Add';
		} else {
			$data['submit'] = 'Update';
		}
		$data['sum_insured_url'] = base_url ( "product/insured/" . $data['product_short'] );
		$data['deductiable_amount_url'] = base_url ( "product/deductiable/" . $data['product_short'] );
		$data['province_url'] = base_url ( "geo/province/" . $data['country2'] . "/" . $data['province2'] );
		$data['country_url'] = base_url ( "geo/country/" . $this->data['country2'] );
		if (!empty($data['country2'])) {
			$data['province_url'] .= "/" . $data['country2'];
			$data['country_url'] .= "/" . $data['country2'];
			if (!empty($data['province2'])) {
				$data['province_url'] .= "/" . $data['province2'];
			}
		}

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
		$data['action_url'] = base_url ( "plan/form" );
		$data['title_txt'] = 'Policy';
		$data['top_menu'] = $this->menu_model->load_top_menu();
		$data['menu'] = $this->menu_model->load_meun();
		$data['csrf'] = array (
				'name' => $this->security->get_csrf_token_name (),
				'value' => $this->security->get_csrf_hash ()
		);

		$this->load->common('plan/form', $data);
	}
	
	function add() {
		$beuser = $this->func_model->verify_login(); 
		$product_short = $this->input->post('product_short');
		if (empty($product_short)) {
			redirect(base_url('production'));
		}
		$plan = array('product_short' => $product_short);
		return ($this->form($plan));
	}

	function term($plan_id) {
	}

	function copy($plan_id) {
	}

	function edit($plan_id) {
	}
}
