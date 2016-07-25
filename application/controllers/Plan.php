<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Box\Spout\Common\Type;
use Box\Spout\Writer\WriterFactory;

class Plan extends MY_Controller {
	private $merchentID = "300203256";
	private $apikey = "634E4AFd7Eda4dcEaA2976207A7C92bb";
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
			$data['plan_list'] = $this->plan_model->plan_search($this->input->post());
			$this->session->set_userdata('policy_search', json_encode($this->input->post()));
		} else if ($this->input->get('q')) {
			$para = array('policy' => $this->input->get('q'));
			$data['plan_list'] = $this->plan_model->plan_search($para);
			$this->session->set_userdata('policy_search', json_encode($para));
		} else {
			$data['plan_list'] = array();
			$this->session->unset_userdata ( 'policy_search' );
		}
		$data['search_url'] = current_url ();
		$data['add_url'] = base_url ( "plan/add" );
		$data['export_list'] = base_url ( "plan/export_list" );
		$data['edit_url'] = base_url ( "plan/edit" ) . "/";
		$data['copy_url'] = base_url ( "plan/copy" ) . "/";
		$data['province_url'] = base_url ( "geo/province/" );
		$data['country_url'] = base_url ( "geo/country/" );
		$data['sendpackage_url'] = base_url ( "plan/sendpackage" ) . "/";
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
	
	function export_list() {
		$beuser = $this->func_model->verify_login(); 
		$this->load->model('status_model');
		$this->load->model('product_model');
		$this->load->model('plan_model');
		
	
		$data['product_list'] = $this->product_model->product_list();
		$prod2 = array();
		foreach($data['product_list'] as $key => $value) {
			if ($value['calculate']) {
				$prod2[$key] = $value;
			}
		}
		$data['product_list_a'] = $prod2;
		$data['status_list'] = $this->status_model->status_list();

		$policy_search = $this->session->userdata('policy_search');
		if ($policy_search) {
			$policies = $this->plan_model->plan_export_search($policy_search);
		} else {
			$policies = array();
		}
		
		$w = WriterFactory::create(Type::XLSX); // for XLSX files
		$kArr = array(
				'plan_id',
				'customer_id',
				'user_id',
				'policy',
				'product_short',
				'batch_number',
				'isfamilyplan',
				'apply_date',
				'arrival_date',
				'effective_date',
				'expiry_date',
				'beneficiary',
				'stable_condition',
				'rate_options',
				'sum_insured',
				'deductible_amount',
				'premium',
				'commission_amount',
				'street_number',
				'street_name',
				'suite_number',
				'city',
				'province2',
				'country2',
				'postcode',
				'phone1',
				'phone2',
				'institution',
				'institution_addr',
				'student_id',
				'institution_phone',
				'institution_fax',
				'contact_email',
				'contact_phone',
				'residence',
				'payinfo',
				'firstname',
				'lastname',
				'gende',
				'birthday',
				'firstname_1',
				'lastname_1',
				'gende_1',
				'birthday_1',
				'firstname_2',
				'lastname_2',
				'gende_2',
				'birthday_2',
				'firstname_3',
				'lastname_3',
				'gende_3',
				'birthday_3',
				'firstname_4',
				'lastname_4',
				'gende_4',
				'birthday_4',
				'firstname_5',
				'lastname_5',
				'gende_5',
				'birthday_5',
				'firstname_6',
				'lastname_6',
				'gende_6',
				'birthday_6',
				'firstname_7',
				'lastname_7',
				'gende_7',
				'birthday_7',
				'firstname_8',
				'lastname_8',
				'gende_8',
				'birthday_8');
		$tmpfname = "/tmp/jf_test.xlsx";
		$w->openToBrowser("Policy" . date('Ymd') . ".xlsx");
		//$w->openToFile($tmpfname);
		$w->addRow($kArr);
		foreach($policies as $p) {
			$para = array();
			foreach($kArr as $k) {
				$para[] = empty($p[$k]) ? '' : $p[$k];
			}
			$w->addRow($para);
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
		if ($this->input->post('totaldays')) {
			$data['totaldays'] = $this->input->post('totaldays'); 
		} else if (isset($plan['totaldays'])) {
			$data['totaldays'] = $plan['totaldays'];
		} else {
			$data['totaldays'] = '';
		}
		if ($this->input->post('dailyrate')) {
			$data['dailyrate'] = $this->input->post('dailyrate'); 
		} else if (isset($plan['dailyrate'])) {
			$data['dailyrate'] = $plan['dailyrate'];
		} else {
			$data['dailyrate'] = '';
		}
		if ($this->input->post('totalyears')) {
			$data['totalyears'] = $this->input->post('totalyears'); 
		} else if (isset($plan['totalyears'])) {
			$data['totalyears'] = $plan['totalyears'];
		} else {
			$data['totalyears'] = '';
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
		if (isset($plan['policy'])) {
			$data['policy'] = $plan['policy'];
		} else {
			$data['policy'] = '';
		}
		
		$data['show_history'] = 0;
		if (empty($data['plan_id'])) {
			$data['submit'] = 'Add New Policy';
			$data['p_header'] = 'Create Policy';
		} else {
			$data['p_header'] = 'Edit Policy';
			$data['submit'] = 'Update Policy';
			if ($beuser['user_group_id'] < 100) {
				$this->load->model('payment_model');
				$data['show_history'] = 1;
				$data['activelogs'] = $this->log_model->get_activity_by_plan_id($data['plan_id']);
				$data['payments'] = $this->payment_model->get_payment_by_plan_id($data['plan_id']);
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
		$product = $this->product_model->get_product($data['product_short']);
		$data['plan_full_name'] = $product ? $product['full_name'] : '';
		$data['status_list'] = $this->status_model->status_list();
		if ((int)$data['plan_id'] > 0) {
			$data['copy_url'] = base_url ( "plan/copy/" . (int)$data['plan_id'] );
			if ((int)$data['status_id'] == 1) {
				$data['pay_url'] = base_url ( "plan/term/" . (int)$data['plan_id'] );
			} else {
				$data['pay_url'] = '';
			}
			$data['next_url'] = base_url ( "plan/term/" . (int)$data['plan_id'] );
		} else {
			$data['copy_url'] = '';
			$data['pay_url'] = '';
			$data['next_url'] = '';
		}
		
		$data['premium_url'] = base_url ( "product/premium" );
		$data['action_url'] = base_url ( "plan/form" );
		$data['claimurl'] = base_url ( "claim/add" ) . "/";
		$data['sendpackage_url'] = base_url ( "plan/sendpackage" ) . "/";
		$data['cancel_url'] = base_url ( "plan/cancel" ) . "/";
		$data['refund_url'] = base_url ( "plan/refund" ) . "/";
		$data['makepay_url'] = base_url ( "payment/makepay" );
		
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
		} else if ($plan['product_short'] == 'JFC') {
			$data['insurable_options'] = $this->load->view('plan/form_jes', $data, TRUE);
		} else {
			$data['insurable_options'] = $this->load->view('plan/form_other', $data, TRUE);
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
		$beuser = $this->func_model->verify_login(TRUE); 
		$this->load->model('plan_model');
		if (empty($plan_id)) {
			$plan_id = $this->input->post('plan_id');
		}
		if (empty($plan_id)) {
			redirect(base_url('production'));
		}
		$plan = $this->plan_model->get_plan_by_id($plan_id);
		if (empty($plan)) {
			redirect(base_url('production'));
		}
		
		$data['message'] = '';
		if ($this->input->post('submit')) {
			$agree = $this->input->post('agree');
			if ($agree) {
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
				$data['message'] = 'You need agree term and conditions to continue.';
			}
		}
		$data['plan_id'] = $plan_id;
		$data['agree'] = $plan['agree'];
		
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
		$this->load->model('payment_model');
		
		$plan_id = $this->input->post('plan_id');
		$premium = $this->input->post('premium');
		
		if (empty( $this->input->post('card_number') ) ) {
			$this->error = 'Please input Card Number.';
		} else if (empty( $this->input->post('card_name') ) ) {
			$this->error = 'Please input Card Name.';
		} else if (empty( $this->input->post('expiry_month') ) ) {
			$this->error = 'Please select Expiry Month.';
		} else if (empty( $this->input->post('expiry_year') ) ) {
			$this->error = 'Please select Expiry Year.';
		} else if (empty( $this->input->post('card_cvv') ) ) {
			$this->error = 'Please Card CVV';
		} else {
			$card_number = $this->input->post('card_number');
			$card_name = $this->input->post('card_name');
			$expiry_month = $this->input->post('expiry_month');
			$expiry_year = $this->input->post('expiry_year');
			$card_cvv = $this->input->post('card_cvv');

			$plan = $this->plan_model->get_plan_by_id($plan_id);
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
			$dt['ispaid'] = 0;
			$commission_amount = $this->plan_model->get_commission($plan_id);
			if ($plan['payment_id']) {
				$payment = $this->payment_model->get_payment_by_plan_id($plan['payment_id']);
				if (isset($payment['ispaid']) && ($payment['ispaid'] == 1)) {
					// Add adjust amount
					$dt['amount'] -= $payment['amount'];
					$payment_id = $this->payment_model->add($dt);
					$para = array(
							'plan_id' => $plan_id,
							'customer_id' => $plan['customer_id'],
							'payment_id' => $payment_id,
							'message' => $this->payment_model->logstr,
							'systemlog' => $this->payment_model->sqlstr
					);
					$this->log_model->activity('payment', $para);
				} else {
					// Adjust directally
					$payment_id = $this->payment_model->update($plan['payment_id'], $dt);
					$para = array(
							'plan_id' => $plan_id,
							'customer_id' => $plan['customer_id'],
							'payment_id' => $payment_id,
							'message' => $this->payment_model->logstr,
							'systemlog' => $this->payment_model->sqlstr
					);
					$this->log_model->activity('payment', $para);
				}
			} else {
				$payment_id = $this->payment_model->add($dt);
				$para = array(
						'plan_id' => $plan_id,
						'customer_id' => $plan['customer_id'],
						'payment_id' => $payment_id,
						'message' => $this->payment_model->logstr,
						'systemlog' => $this->payment_model->sqlstr
				);
				$this->log_model->activity('payment', $para);
			}
			$premium = $dt['amount'];	// Adjust amount if it was paid 

			// Adjust commission
			$dt['pay_type'] = 'commission';
			if ($plan['commission_payment_id']) {
				$commission_payment = $this->payment_model->get_payment_by_plan_id($plan['commission_payment_id']);
				if (isset($commission_payment['ispaid']) && ($commission_payment['ispaid'] == 1)) {
					// Add adjust amount
					$dt['amount'] = $commission_amount - $pcommission_ayment['amount'];
					$commission_payment_id = $this->payment_model->add($dt);
					$para = array(
							'plan_id' => $plan_id,
							'customer_id' => $plan['customer_id'],
							'payment_id' => $commission_payment_id,
							'message' => $this->payment_model->logstr,
							'systemlog' => $this->payment_model->sqlstr
					);
					$this->log_model->activity('commission', $para);
				} else {
					// Adjust directally
					$dt['amount'] = $commission_amount;
					$commission_payment_id = $this->payment_model->update($plan['commission_payment_id'], $dt);
					$para = array(
							'plan_id' => $plan_id,
							'customer_id' => $plan['customer_id'],
							'payment_id' => $commission_payment_id,
							'message' => $this->payment_model->logstr,
							'systemlog' => $this->payment_model->sqlstr
					);
					$this->log_model->activity('commission', $para);
				}
			} else {
				$dt['amount'] = $commission_amount;
				$commission_payment_id = $this->payment_model->add($dt);
				$para = array(
						'plan_id' => $plan_id,
						'customer_id' => $plan['customer_id'],
						'payment_id' => $commission_payment_id,
						'message' => $this->payment_model->logstr,
						'systemlog' => $this->payment_model->sqlstr
				);
				$this->log_model->activity('commission', $para);
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
					$payinfo = "Credit Card: " . substr($card_number, 0, 5) . "xxx" . substr($card_number, -4) . " " . $card_name .  " " . $expiry_month . "/" . $expiry_year;
						
					$para = array('payment_id' => $payment_id, 'payinfo' => $payinfo, 'commission_payment_id' => $commission_payment_id, 'status_id' => 3, 'policy' => $this->plan_model->get_policy_number($plan_id, 2));
					$this->plan_model->update($plan_id, $para);
					$para = array(
							'plan_id' => $plan_id,
							'customer_id' => $plan['customer_id'],
							'payment_id' => $payment_id,
							'message' => $this->plan_model->logstr,
							'systemlog' => $this->plan_model->sqlstr
					);
					$this->log_model->activity('plan', $para);
					
					$dt['ispaid'] = 1;
					$dt['note'] = "Success: Raw Data=> " . json_encode($result);
					$payment_id = $this->payment_model->update($payment_id, $dt);
					$para = array(
							'plan_id' => $plan_id,
							'customer_id' => $plan['customer_id'],
							'payment_id' => $payment_id,
							'message' => $this->payment_model->logstr,
							'systemlog' => $this->payment_model->sqlstr
					);
					$this->log_model->activity('payment', $para);
				} else {
					$payinfo = "Credit Card: " . substr($card_number, 0, 5) . "xxx" . substr($card_number, -4) . " " . $card_name .  " " . $expiry_month . "/" . $expiry_year;
						
					$para = array('payment_id' => $payment_id, 'payinfo' => $payinfo, 'commission_payment_id' => $commission_payment_id );
					$this->plan_model->update($plan_id, $para);
					$para = array(
							'plan_id' => $plan_id,
							'customer_id' => $plan['customer_id'],
							'payment_id' => $payment_id,
							'message' => $this->plan_model->logstr,
							'systemlog' => $this->plan_model->sqlstr
					);
					$this->log_model->activity('plan', $para);
					
					$dt['ispaid'] = 0;
					$dt['note'] = "Failur: Raw Data=> " . json_encode($result);
					$payment_id = $this->payment_model->update($payment_id, $dt);
					$para = array(
							'plan_id' => $plan_id,
							'customer_id' => $plan['customer_id'],
							'payment_id' => $payment_id,
							'message' => $this->payment_model->logstr,
							'systemlog' => $this->payment_model->sqlstr
					);
					$this->log_model->activity('payment', $para);
					$this->error = 'Payment Failed. Please confirm card information.';
				}
			} catch ( \Beanstream\Exception $e ) {
				$payinfo = "Credit Card: " . substr($card_number, 0, 5) . "xxx" . substr($card_number, -4) . " " . $card_name .  " " . $expiry_month . "/" . $expiry_year;
					
				$para = array('payment_id' => $payment_id, 'payinfo' => $payinfo, 'commission_payment_id' => $commission_payment_id);
				$this->plan_model->update($plan_id, $para);
				$para = array(
						'plan_id' => $plan_id,
						'customer_id' => $plan['customer_id'],
						'payment_id' => $payment_id,
						'message' => $this->plan_model->logstr,
						'systemlog' => $this->plan_model->sqlstr
				);
				$this->log_model->activity('plan', $para);
				
				// print_r ( $e->getMessage() );
				$dt['ispaid'] = 0;
				$dt['note'] = "Failur: (libraray) Raw Data=> " . $e->getMessage() . " : " . json_encode($e);
				$payment_id = $this->payment_model->update($payment_id, $dt);
				$para = array(
						'plan_id' => $plan_id,
						'customer_id' => $plan['customer_id'],
						'payment_id' => $payment_id,
						'message' => $this->payment_model->logstr,
						'systemlog' => $this->payment_model->sqlstr
				);
				$this->log_model->activity('payment', $para);
				$this->error = 'Payment Failed. Please pay it later.';
			}
		}
	}

	private function cash() {
		$this->load->model('plan_model');
		$this->load->model('product_model');
		$this->load->model('payment_model');
		
		$plan_id = $this->input->post('plan_id');
		$premium = $this->input->post('premium');
		$payinfo = "Pay Cash: " . 'Premium: $' . $premium . "; ";
		
		$plan = $this->plan_model->get_plan_by_id($plan_id);
		$dt = array();
		$dt['plan_id'] = $plan_id;
		$dt['amount'] = $premium;
		$dt['pay_type'] = 'premium';
		$dt['pay_mothed'] = 'Cash';
		$dt['added'] = date('c');
		$dt['note'] = $payinfo;
		$dt['ispaid'] = 0;
		$commission_amount = $this->plan_model->get_commission($plan_id);
		
		if ($plan['payment_id']) {
			$payment = $this->payment_model->get_payment_by_plan_id($plan['payment_id']);
			if (isset($payment['ispaid']) && ($payment['ispaid'] == 1)) {
				// Add adjust amount
				$dt['amount'] -= $payment['amount'];
				$payment_id = $this->payment_model->add($dt);
				$para = array(
						'plan_id' => $plan_id,
						'customer_id' => $plan['customer_id'],
						'payment_id' => $payment_id,
						'message' => $this->payment_model->logstr,
						'systemlog' => $this->payment_model->sqlstr
				);
				$this->log_model->activity('payment', $para);
			} else {
				// Adjust directally
				$payment_id = $this->payment_model->update($plan['payment_id'], $dt);
				$para = array(
						'plan_id' => $plan_id,
						'customer_id' => $plan['customer_id'],
						'payment_id' => $payment_id,
						'message' => $this->payment_model->logstr,
						'systemlog' => $this->payment_model->sqlstr
				);
				$this->log_model->activity('payment', $para);
			}
		} else {
			$payment_id = $this->payment_model->add($dt);
			$para = array(
					'plan_id' => $plan_id,
					'customer_id' => $plan['customer_id'],
					'payment_id' => $payment_id,
					'message' => $this->payment_model->logstr,
					'systemlog' => $this->payment_model->sqlstr
			);
			$this->log_model->activity('payment', $para);
		}
		$premium = $dt['amount'];	// Adjust amount if it was paid
		
		// Adjust commission
		$dt['pay_type'] = 'commission';
		if ($plan['commission_payment_id']) {
			$commission_payment = $this->payment_model->get_payment_by_plan_id($plan['commission_payment_id']);
			if (isset($commission_payment['ispaid']) && ($commission_payment['ispaid'] == 1)) {
				// Add adjust amount
				$dt['amount'] = $commission_amount - $commission_payment['amount'];
				$commission_payment_id = $this->payment_model->add($dt);
				$para = array(
						'plan_id' => $plan_id,
						'customer_id' => $plan['customer_id'],
						'payment_id' => $commission_payment_id,
						'message' => $this->payment_model->logstr,
						'systemlog' => $this->payment_model->sqlstr
				);
				$this->log_model->activity('commission', $para);
			} else {
				// Adjust directally
				$dt['amount'] = $commission_amount;
				$commission_payment_id = $this->payment_model->update($plan['commission_payment_id'], $dt);
				$para = array(
						'plan_id' => $plan_id,
						'customer_id' => $plan['customer_id'],
						'payment_id' => $commission_payment_id,
						'message' => $this->payment_model->logstr,
						'systemlog' => $this->payment_model->sqlstr
				);
				$this->log_model->activity('commission', $para);
			}
		} else {
			$dt['amount'] = $commission_amount;
			$commission_payment_id = $this->payment_model->add($dt);
			$para = array(
					'plan_id' => $plan_id,
					'customer_id' => $plan['customer_id'],
					'payment_id' => $commission_payment_id,
					'message' => $this->payment_model->logstr,
					'systemlog' => $this->payment_model->sqlstr
			);
			$this->log_model->activity('commission', $para);
		}
		$para = array('payment_id' => $payment_id, 'payinfo' => $payinfo, 'commission_payment_id' => $commission_payment_id, 'status_id' => 2, 'policy' => $this->plan_model->get_policy_number($plan_id, 2));
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

	private function cheque() {
		$this->load->model('plan_model');
		$this->load->model('product_model');
		$this->load->model('payment_model');
		
		$plan_id = $this->input->post('plan_id');
		$premium = $this->input->post('premium');
		$payinfo  = 'Invoice Number: ' . $this->input->post('invoice_num') . "; ";
		$payinfo .= 'Bank Name: ' . $this->input->post('bank_name') . "; ";
		$payinfo .= 'Payor Name: ' . $this->input->post('payor_name') . "; ";
		$payinfo .= 'Checque#: ' . $this->input->post('cheque_number') . "; ";
		$payinfo .= 'Premium: $' . $this->input->post('premium') . "; ";

		$plan = $this->plan_model->get_plan_by_id($plan_id);
		$dt = array();
		$dt['plan_id'] = $plan_id;
		$dt['amount'] = $premium;
		$dt['pay_type'] = 'premium';
		$dt['pay_mothed'] = 'Cheque';
		$dt['added'] = date('c');
		$dt['note'] = $payinfo;
		$dt['ispaid'] = 0;
		$commission_amount = $this->plan_model->get_commission($plan_id);
		
		if ($plan['payment_id']) {
			$payment = $this->payment_model->get_payment_by_plan_id($plan['payment_id']);
			if (isset($payment['ispaid']) && ($payment['ispaid'] == 1)) {
				// Add adjust amount
				$dt['amount'] -= $payment['amount'];
				$payment_id = $this->payment_model->add($dt);
				$para = array(
						'plan_id' => $plan_id,
						'customer_id' => $plan['customer_id'],
						'payment_id' => $payment_id,
						'message' => $this->payment_model->logstr,
						'systemlog' => $this->payment_model->sqlstr
				);
				$this->log_model->activity('payment', $para);
			} else {
				// Adjust directally
				$payment_id = $this->payment_model->update($plan['payment_id'], $dt);
				$para = array(
						'plan_id' => $plan_id,
						'customer_id' => $plan['customer_id'],
						'payment_id' => $payment_id,
						'message' => $this->payment_model->logstr,
						'systemlog' => $this->payment_model->sqlstr
				);
				$this->log_model->activity('payment', $para);
			}
		} else {
			$payment_id = $this->payment_model->add($dt);
			$para = array(
					'plan_id' => $plan_id,
					'customer_id' => $plan['customer_id'],
					'payment_id' => $payment_id,
					'message' => $this->payment_model->logstr,
					'systemlog' => $this->payment_model->sqlstr
			);
			$this->log_model->activity('payment', $para);
		}
		$premium = $dt['amount'];	// Adjust amount if it was paid
		
		// Adjust commission
		$dt['pay_type'] = 'commission';
		if ($plan['commission_payment_id']) {
			$commission_payment = $this->payment_model->get_payment_by_plan_id($plan['commission_payment_id']);
			if (isset($commission_payment['ispaid']) && ($commission_payment['ispaid'] == 1)) {
				// Add adjust amount
				$dt['amount'] = $commission_amount - $commission_payment['amount'];
				$commission_payment_id = $this->payment_model->add($dt);
				$para = array(
						'plan_id' => $plan_id,
						'customer_id' => $plan['customer_id'],
						'payment_id' => $commission_payment_id,
						'message' => $this->payment_model->logstr,
						'systemlog' => $this->payment_model->sqlstr
				);
				$this->log_model->activity('commission', $para);
			} else {
				// Adjust directally
				$dt['amount'] = $commission_amount;
				$commission_payment_id = $this->payment_model->update($plan['commission_payment_id'], $dt);
				$para = array(
						'plan_id' => $plan_id,
						'customer_id' => $plan['customer_id'],
						'payment_id' => $commission_payment_id,
						'message' => $this->payment_model->logstr,
						'systemlog' => $this->payment_model->sqlstr
				);
				$this->log_model->activity('commission', $para);
			}
		} else {
			$dt['amount'] = $commission_amount;
			$commission_payment_id = $this->payment_model->add($dt);
			$para = array(
					'plan_id' => $plan_id,
					'customer_id' => $plan['customer_id'],
					'payment_id' => $commission_payment_id,
					'message' => $this->payment_model->logstr,
					'systemlog' => $this->payment_model->sqlstr
			);
			$this->log_model->activity('commission', $para);
		}
		$para = array('payment_id' => $payment_id, 'payinfo' => $payinfo, 'commission_payment_id' => $commission_payment_id, 'status_id' => 2, 'policy' => $this->plan_model->get_policy_number($plan_id, 2));
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

	function detail($plan_id=0, $sekey='') {
		$this->error = '';
		$defaultpay_type = '';
		if ($play_type = $this->input->post('play_type')) {
			$plan_id = $this->input->post('plan_id');
			$sekey = $this->input->post('sekey');
			if ($play_type == 'Credit Card') {
				$this->credit_card();
				$defaultpay_type = 'Credit Card';
			} else if ($play_type == 'Cash') {
				$this->cash();
				$defaultpay_type = 'Cash';
			} else if ($play_type == 'Cheque') {
				$this->cheque();
				$defaultpay_type = 'Cheque';
			}
		}
		if (empty($plan_id)) {
			redirect(base_url('production'));
		}
		
		$this->load->model('customer_model');
		$this->load->model('plan_model');
		$this->load->model('product_model');
		$this->load->model('paytype_model');
		$this->load->model('status_model');
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
			$this->session->set_userdata ( 'beuser',  $beuser);
		}
		
		$data['errormsg'] = $this->error;
		$data['beuser'] = $beuser;
		$data['sekey'] = $sekey;
		$data['apply_date'] = date('Y-m-d');
	
		if ($plan['status_id'] < 3) {
			// Before Paid
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
				$para = array('premium' => $premiumarr['premium'],'dailyrate' => $premiumarr['dailyrate'],'totaldays' => $premiumarr['totaldays'],'totalyears' => $premiumarr['totalyears']);
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
		}
		$data['plan'] = $plan;
		$product = $this->product_model->get_product($plan['product_short']);
		$data['plan_full_name'] = $product ? $product['full_name'] : '';
		
		$data['customer'] = $this->customer_model->get_customer_by_id($plan['customer_id']);
		$data['customers'] = $this->customer_model->get_customer_by_parent_id($plan['customer_id']);
		
		if ($beuser['user_group_id'] < 100) {
			$data['paytype_list'] = $this->paytype_model->paytype_list();
		} else {
			$data['paytype_list'] = split(",", $beuser['pay_type']);
		}
		$data['payurl'] = base_url('plan/detail/' . $plan_id . '/' . $this->plan_model->get_plan_key($plan_id));
		$data['active_url'] = current_url();
		$data['status_list'] = $this->status_model->status_list();
		if ($plan['status_id'] <= 2) {
			$display = 1;
			if (empty($data['defaultpay_type'])) {
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
			} else {
				$data['credit_dis'] = 0;
				$data['cheque_dis'] = 0;
				$data['cash_dis'] = 0;
				$data['pay_type'] = $data['defaultpay_type'];
				if ($data['pay_type'] == 'Credit Card') {
					$data['credit_dis'] = 1;
				} else if ($data['pay_type'] == 'Cheque') {
					$data['cheque_dis'] = 1;
				} else /* if ($data['pay_type'] == 'Cash') */ {
					$data['cash_dis'] = 1;
				}
			}
		}

		$data['pdf_url'] = base_url('plan/pdf/' . $plan['plan_id']);
		$data['print_card_url'] = '';
		$data['print_receipt_url'] = '';
		$data['cancel_letter_url'] = '';
		$data['refund_letter_url'] = '';
		if ($plan['status_id'] >= 2) {
			if ($plan['status_id'] == 5) {
				// Cancel
				$data['cancel_letter_url'] = base_url('plan/cancel/' . $plan['plan_id']);
			} else if ($plan['status_id'] == 6) {
				// Refund
				$data['refund_letter_url'] = base_url('plan/refund/' . $plan['plan_id']);
			} else {
				$data['print_card_url'] = base_url('plan/card/' . $plan['plan_id']);
				$data['print_receipt_url'] = base_url('plan/receipt/' . $plan['plan_id']);
			}
		}
		
		$data['title_txt'] = 'Policy';
		$data['top_menu'] = $this->menu_model->load_top_menu();
		$data['menu'] = $this->menu_model->load_meun();
		$data['csrf'] = array (
				'name' => $this->security->get_csrf_token_name (),
				'value' => $this->security->get_csrf_hash ()
		);
		
		$data['defaultpay_type'] = $defaultpay_type;
		
		if ($data['plan']['product_short'] == 'OPL') {
			$data['insurable_options'] = $this->load->view('plan/detail_opl', $data, TRUE);
		} else if ($data['plan']['product_short'] == 'JFR') {
			$data['insurable_options'] = $this->load->view('plan/detail_opl', $data, TRUE);
		} else if ($data['plan']['product_short'] == 'JUS') {
			$data['insurable_options'] = $this->load->view('plan/detail_jus', $data, TRUE);
		} else if ($data['plan']['product_short'] == 'NUS') {
			$data['insurable_options'] = $this->load->view('plan/detail_jus', $data, TRUE);
		} else if ($data['plan']['product_short'] == 'JES') {
			$data['insurable_options'] = $this->load->view('plan/detail_jes', $data, TRUE);
		} else if ($plan['plan']['product_short'] == 'JFC') {
			$data['insurable_options'] = $this->load->view('plan/detail_jes', $data, TRUE);
		} else {
			$data['insurable_options'] = $this->load->view('plan/detail_other', $data, TRUE);
		}
		
		$this->load->common('plan/detail', $data);
	}

	public function sendpackage($plan_id=0)
	{
		$beuser = $this->func_model->verify_login(TRUE);
		$this->load->model('plan_model');
		if (empty($plan_id)) {
			$plan_id = $this->input->post('plan_id');
		}
		if (empty($plan_id)) {
			redirect('user/login');
		}
		$plan = $this->plan_model->get_plan_by_id($plan_id);
		if (empty($plan)) {
			redirect('user/login');
		}
		
		$data['beuser'] = $beuser;
		$data['plan'] = $plan;
		$data['emailaddr'] = $plan['contact_email'];
		if ($this->input->post()) {
			$emailaddr = $this->input->post('emailaddr');
			if (!empty($emailaddr)) {
				$data['emailaddr'] = $emailaddr;
			}
			$this->load->model('verify_model');
			if ($this->verify_model->isEmail($emailaddr)) {
				$this->load->model('customer_model');
				$this->load->model('product_model');
				$this->load->model('paytype_model');
				$this->load->model('status_model');
				$product = $this->product_model->get_product($plan['product_short']);
				$data['plan_full_name'] = $product ? $product['full_name'] : '';
				$data['customer'] = $this->customer_model->get_customer_by_id($data['plan']['customer_id']);
				$data['customers'] = $this->customer_model->get_customer_by_parent_id($data['plan']['customer_id']);
				$data['paytype_list'] = $this->paytype_model->paytype_list();
				$data['status_list'] = $this->status_model->status_list();
		
				if ($data['plan']['product_short'] == 'OPL') {
					$data['insurable_options'] = $this->load->view('plan/detail_opl', $data, TRUE);
				} else if ($data['plan']['product_short'] == 'JFR') {
					$data['insurable_options'] = $this->load->view('plan/detail_opl', $data, TRUE);
				} else if ($data['plan']['product_short'] == 'JUS') {
					$data['insurable_options'] = $this->load->view('plan/detail_jus', $data, TRUE);
				} else if ($data['plan']['product_short'] == 'NUS') {
					$data['insurable_options'] = $this->load->view('plan/detail_jus', $data, TRUE);
				} else if ($data['plan']['product_short'] == 'JES') {
					$data['insurable_options'] = $this->load->view('plan/detail_jes', $data, TRUE);
				} else if ($plan['plan']['product_short'] == 'JFC') {
					$data['insurable_options'] = $this->load->view('plan/detail_jes', $data, TRUE);
				} else {
					$data['insurable_options'] = $this->load->view('plan/detail_other', $data, TRUE);
				}
				
				$policy_file = tempnam("/tmp", "Policy");
				
				$data['title_txt'] = 'Policy';
				$data['style'] = $this->load->view('common/pdf_style',$data, TRUE);
				$mpdf = new mPDF('c');
				$html = $this->load->view('plan/pdf', $data, TRUE);
				//die($html);
				$mpdf->writeHTML($html);
				$mpdf->Output($policy_file, 'F');
				
				$this->load->model('mymail_model');
				$body = $this->load->view('mail/package',$data, TRUE);
				$files = array(
					'policy' => $policy_file,
				);
				$sendok = $this->mymail_model->send_mymail($emailaddr, 'Insure Packages', $body, $files, $from='Support');
				unlink($policy_file);
				if ($sendok) {
					redirect('plan');
				} else {
					$data['error_message'] = "Something wrong with send email";
				}
			} else {
				$data['error_message'] = "Please input valid email address";
			}
		}
		
		$data['action_url'] = base_url('plan/sendpackage');
		$data['plan_id'] = $plan['plan_id'];
		
		$data['title_txt'] = 'Policy';
		$data['top_menu'] = $this->menu_model->load_top_menu();
		$data['menu'] = $this->menu_model->load_meun();
		$data['csrf'] = array (
				'name' => $this->security->get_csrf_token_name (),
				'value' => $this->security->get_csrf_hash ()
		);
		
		$this->load->common('plan/sendpackage', $data);
	}

	public function pdf($plan_id)
	{
		$beuser = $this->func_model->verify_login(TRUE);
		$this->load->model('customer_model');
		$this->load->model('plan_model');
		$this->load->model('product_model');
		$this->load->model('paytype_model');
		$this->load->model('status_model');
		$this->load->model('payment_model');
		$plan = $this->plan_model->get_plan_by_id($plan_id);
		if (empty($plan)) {
			redirect('user/login');
		}
		
		$data['beuser'] = $beuser;
		$data['plan'] = $plan;
		$data['payment'] = '';
		if ($plan['payment_id']) {
			$data['payment'] = $this->payment_model->get_payment_by_id($plan['payment_id']);
		}
		$data['user'] = '';
		if ($plan['user_id']) {
			$data['user'] = $this->user_model->get_user_by_id($plan['user_id']);
		}
		$product = $this->product_model->get_product($plan['product_short']);
		$data['plan_full_name'] = $product ? $product['full_name'] : '';
		$data['customer'] = $this->customer_model->get_customer_by_id($data['plan']['customer_id']);
		$data['customers'] = $this->customer_model->get_customer_by_parent_id($data['plan']['customer_id']);
		$data['paytype_list'] = $this->paytype_model->paytype_list();
		$data['status_list'] = $this->status_model->status_list();

		if ($data['plan']['product_short'] == 'OPL') {
			$data['insurable_options'] = $this->load->view('plan/detail_opl', $data, TRUE);
			$data['special_note'] = $this->load->view('plan/pdf_note_opl',$data, TRUE);
		} else if ($data['plan']['product_short'] == 'JFR') {
			$data['insurable_options'] = $this->load->view('plan/detail_opl', $data, TRUE);
			$data['special_note'] = $this->load->view('plan/pdf_note_jfr',$data, TRUE);
		} else if ($data['plan']['product_short'] == 'JUS') {
			$data['insurable_options'] = $this->load->view('plan/detail_jus', $data, TRUE);
			$data['special_note'] = $this->load->view('plan/pdf_note_opl',$data, TRUE);
		} else if ($data['plan']['product_short'] == 'NUS') {
			$data['insurable_options'] = $this->load->view('plan/detail_jus', $data, TRUE);
			$data['special_note'] = $this->load->view('plan/pdf_note_opl',$data, TRUE);
		} else if ($data['plan']['product_short'] == 'JES') {
			$data['insurable_options'] = $this->load->view('plan/detail_jes', $data, TRUE);
			$data['special_note'] = $this->load->view('plan/pdf_note_jes',$data, TRUE);
		} else if ($plan['plan']['product_short'] == 'JFC') {
			$data['insurable_options'] = $this->load->view('plan/detail_jes', $data, TRUE);
			$data['special_note'] = $this->load->view('plan/pdf_note_opl',$data, TRUE);
		} else {
			$data['insurable_options'] = $this->load->view('plan/detail_other', $data, TRUE);
			$data['special_note'] = $this->load->view('plan/pdf_note_opl',$data, TRUE);
		}
		
		$data['title_txt'] = 'Policy';
		$data['style'] = $this->load->view('common/pdf_style',$data, TRUE);		
		
		$mpdf = new mPDF('c');
		$html = $this->load->view('plan/pdf', $data, TRUE);
		
		$mpdf->writeHTML($html);
		$mpdf->Output();
	}

	/**
	 * Cancel Letter
	 * 
	 * @param integer $plan_id
	 */
	public function cancel($plan_id=0) {
		$beuser = $this->func_model->verify_login(TRUE);
		$this->load->model('plan_model');
		if (empty($plan_id)) {
			$plan_id = $this->input->post('plan_id');
		}
		if (empty($plan_id)) {
			redirect('user/login');
		}
		$plan = $this->plan_model->get_plan_by_id($plan_id);
		if (empty($plan)) {
			redirect('user/login');
		}
		
		$data['beuser'] = $beuser;
		$data['plan'] = $plan;
		if ($this->input->post()) {
			$refund_amount = number_format((float)$this->input->post('refund_amount'), 2);
			$admin_fee = number_format((float)$this->input->post('admin_fee'), 2);

			$total_amount = number_format((float)$refund_amount - (float)$admin_fee, 2);
			if ($total_amount > 0) {
				$this->load->model('payment_model');
				$dt = array();
				$dt['plan_id'] = $plan_id;
				$dt['amount'] = $total_amount * (-1);
				$dt['pay_type'] = 'cancel';
				$dt['pay_mothed'] = 'Checque';
				$dt['added'] = date('c');
				$dt['ispaid'] = 0;
				$dt['note'] = "Cancel at " . $dt['added'] . " amount: " . $refund_amount . " admin fee: " . $admin_fee;
				$commission_amount = $this->plan_model->get_commission($plan_id, $refund_amount);
				$payment_id = $this->payment_model->add($dt);
				$para = array(
						'plan_id' => $plan_id,
						'customer_id' => $plan['customer_id'],
						'payment_id' => $payment_id,
						'message' => $this->payment_model->logstr,
						'systemlog' => $this->payment_model->sqlstr
				);
				$this->log_model->activity('payment', $para);
				
				$dt['pay_type'] = 'cancel_commission';
				$dt['amount'] = $commission_amount * (-1);
				$commission_payment_id = $this->payment_model->add($dt);
				$para = array(
						'plan_id' => $plan_id,
						'customer_id' => $plan['customer_id'],
						'payment_id' => $commission_payment_id,
						'message' => $this->payment_model->logstr,
						'systemlog' => $this->payment_model->sqlstr
				);
				$this->log_model->activity('commission', $para);
	
				$note = $plan['note'] . "; Refund at " . $dt['added'] . " amount: " . $refund_amount . " admin fee: " . $admin_fee;
				$para = array('status_id' => 6, 'note' => $note );  // Change status to refund
				$this->plan_model->update($plan_id, $para);
				$para = array(
						'plan_id' => $plan_id,
						'customer_id' => $plan['customer_id'],
						'payment_id' => $payment_id,
						'message' => $this->plan_model->logstr,
						'systemlog' => $this->plan_model->sqlstr
				);
				$this->log_model->activity('plan', $para);
				
				$this->load->model('status_model');
				$data['status_list'] = $this->status_model->status_list();
				$data['refund_amount'] = $refund_amount;
				$data['admin_fee'] = $admin_fee;
				$data['total_amount'] = $total_amount;
					
				$this->load->model('customer_model');
				$data['customer'] = $this->customer_model->get_customer_by_id($data['plan']['customer_id']);
				$data['customers'] = $this->customer_model->get_customer_by_parent_id($data['plan']['customer_id']);
				if ($data['plan']['product_short'] == 'OPL') {
					$data['insurable_options'] = $this->load->view('plan/detail_opl', $data, TRUE);
				} else if ($data['plan']['product_short'] == 'JFR') {
					$data['insurable_options'] = $this->load->view('plan/detail_opl', $data, TRUE);
				} else if ($data['plan']['product_short'] == 'JUS') {
					$data['insurable_options'] = $this->load->view('plan/detail_jus', $data, TRUE);
				} else if ($data['plan']['product_short'] == 'NUS') {
					$data['insurable_options'] = $this->load->view('plan/detail_jus', $data, TRUE);
				} else if ($data['plan']['product_short'] == 'JES') {
					$data['insurable_options'] = $this->load->view('plan/detail_jes', $data, TRUE);
				} else if ($plan['plan']['product_short'] == 'JFC') {
					$data['insurable_options'] = $this->load->view('plan/detail_jes', $data, TRUE);
				} else {
					$data['insurable_options'] = $this->load->view('plan/detail_other', $data, TRUE);
				}
				$html = $this->load->view('plan/cancel', $data, TRUE);

				$mpdf = new mPDF('c');
				$mpdf->writeHTML($html);
				$mpdf->Output();
				exit;
			} else {
				$data['error_message'] = 'Invalid refund amount';
			}
		}
		$data['action_url'] = base_url('plan/cancel');
		$data['plan_id'] = $plan['plan_id'];
		
		$data['title_txt'] = 'Policy';
		$data['top_menu'] = $this->menu_model->load_top_menu();
		$data['menu'] = $this->menu_model->load_meun();
		$data['csrf'] = array (
				'name' => $this->security->get_csrf_token_name (),
				'value' => $this->security->get_csrf_hash ()
		);
		
		$this->load->common('plan/cancel_input', $data);
	}
	
	/**
	 * Refund Letter
	 * 
	 * @param integer $plan_id
	 */
	public function refund_amount($plan_id) {
		$beuser = $this->func_model->verify_login(TRUE);
		$this->load->model('plan_model');
		$plan = $this->get_plan_by_id($plan_id);
		$data['status'] = 'OK';
		$data['refund_amount'] = $this->plan_model->refund_amount($plan_id, $this->input->get('refund_date'));
		$data['used_amount'] = $plan['premium'] - $data['refund_amount']; 
		header('Content-Type: application/json');
		echo json_encode($data);
	}
	
	/**
	 * Refund Letter
	 * 
	 * @param integer $plan_id
	 */
	public function refund($plan_id=0) {
		$beuser = $this->func_model->verify_login(TRUE);
		$this->load->model('plan_model');
		if (empty($plan_id)) {
			$plan_id = $this->input->post('plan_id');
		}
		if (empty($plan_id)) {
			redirect('user/login');
		}
		$plan = $this->plan_model->get_plan_by_id($plan_id);
		if (empty($plan)) {
			redirect('user/login');
		}
		
		$data['beuser'] = $beuser;
		$data['plan'] = $plan;
		if ($this->input->post()) {
			$refund_amount = number_format((float)$this->input->post('refund_amount'), 2);
			$admin_fee = number_format((float)$this->input->post('admin_fee'), 2);

			$total_amount = number_format((float)$refund_amount - (float)$admin_fee, 2);
			if ($total_amount > 0) {
				$this->load->model('payment_model');
				$dt = array();
				$dt['plan_id'] = $plan_id;
				$dt['amount'] = $total_amount * (-1);
				$dt['pay_type'] = 'refund';
				$dt['pay_mothed'] = 'Checque';
				$dt['added'] = date('c');
				$dt['ispaid'] = 0;
				$dt['note'] = "Refund at " . $dt['added'] . " amount: " . $refund_amount . " admin fee: " . $admin_fee;
				$commission_amount = $this->plan_model->get_commission($plan_id, $refund_amount);
				$payment_id = $this->payment_model->add($dt);
				$para = array(
						'plan_id' => $plan_id,
						'customer_id' => $plan['customer_id'],
						'payment_id' => $payment_id,
						'message' => $this->payment_model->logstr,
						'systemlog' => $this->payment_model->sqlstr
				);
				$this->log_model->activity('payment', $para);
				
				$dt['pay_type'] = 'refund_commission';
				$dt['amount'] = $commission_amount * (-1);
				$commission_payment_id = $this->payment_model->add($dt);
				$para = array(
						'plan_id' => $plan_id,
						'customer_id' => $plan['customer_id'],
						'payment_id' => $commission_payment_id,
						'message' => $this->payment_model->logstr,
						'systemlog' => $this->payment_model->sqlstr
				);
				$this->log_model->activity('commission', $para);

				$note = $plan['note'] . "; Refund at " . $dt['added'] . " amount: " . $refund_amount . " admin fee: " . $admin_fee;
				$para = array('status_id' => 6, 'note' => $note );  // Change status to refund
				$this->plan_model->update($plan_id, $para);
				$para = array(
						'plan_id' => $plan_id,
						'customer_id' => $plan['customer_id'],
						'payment_id' => $payment_id,
						'message' => $this->plan_model->logstr,
						'systemlog' => $this->plan_model->sqlstr
				);
				$this->log_model->activity('plan', $para);
				
				$this->load->model('status_model');
				$data['status_list'] = $this->status_model->status_list();
				$data['refund_amount'] = $refund_amount;
				$data['admin_fee'] = $admin_fee;
				$data['total_amount'] = $total_amount;
				
				$this->load->model('customer_model');
				$data['customer'] = $this->customer_model->get_customer_by_id($data['plan']['customer_id']);
				$data['customers'] = $this->customer_model->get_customer_by_parent_id($data['plan']['customer_id']);
				if ($data['plan']['product_short'] == 'OPL') {
					$data['insurable_options'] = $this->load->view('plan/detail_opl', $data, TRUE);
				} else if ($data['plan']['product_short'] == 'JFR') {
					$data['insurable_options'] = $this->load->view('plan/detail_opl', $data, TRUE);
				} else if ($data['plan']['product_short'] == 'JUS') {
					$data['insurable_options'] = $this->load->view('plan/detail_jus', $data, TRUE);
				} else if ($data['plan']['product_short'] == 'NUS') {
					$data['insurable_options'] = $this->load->view('plan/detail_jus', $data, TRUE);
				} else if ($data['plan']['product_short'] == 'JES') {
					$data['insurable_options'] = $this->load->view('plan/detail_jes', $data, TRUE);
				} else if ($plan['plan']['product_short'] == 'JFC') {
					$data['insurable_options'] = $this->load->view('plan/detail_jes', $data, TRUE);
				} else {
					$data['insurable_options'] = $this->load->view('plan/detail_other', $data, TRUE);
				}
				
				$html = $this->load->view('plan/refund', $data, TRUE);
				$mpdf = new mPDF('c');
				$mpdf->writeHTML($html);
				$mpdf->Output();
			} else {
				$data['error_message'] = 'Invalid refund amount';
			}
		}
		$data['action_url'] = base_url('plan/refund');
		$data['refund_amount_url'] = base_url('plan/refund_amount')."/".$plan['plan_id'];
		$data['plan_id'] = $plan['plan_id'];
		
		$data['title_txt'] = 'Policy';
		$data['top_menu'] = $this->menu_model->load_top_menu();
		$data['menu'] = $this->menu_model->load_meun();
		$data['csrf'] = array (
				'name' => $this->security->get_csrf_token_name (),
				'value' => $this->security->get_csrf_hash ()
		);
		
		$this->load->common('plan/refund_input', $data);
	}
	
	/**
	 * Print Travel Card
	 * 
	 * @param integer $plan_id
	 */
	public function card($plan_id) {
		$beuser = $this->func_model->verify_login(TRUE);
		$this->load->model('plan_model');
		$this->load->model('product_model');
		$plan = $this->plan_model->get_plan_by_id($plan_id);
		if (empty($plan)) {
			redirect ( base_url ('user/login') );
			return;
		}
		$data = array('plan' => $plan);
		$this->load->model('customer_model');
		$data['customer'] = $this->customer_model->get_customer_by_id($data['plan']['customer_id']);
		$data['customers'] = $this->customer_model->get_customer_by_parent_id($data['plan']['customer_id']);
		if ($data['plan']['product_short'] == 'OPL') {
			$data['insurable_options'] = $this->load->view('plan/detail_opl', $data, TRUE);
		} else if ($data['plan']['product_short'] == 'JFR') {
			$data['insurable_options'] = $this->load->view('plan/detail_opl', $data, TRUE);
		} else if ($data['plan']['product_short'] == 'JUS') {
			$data['insurable_options'] = $this->load->view('plan/detail_jus', $data, TRUE);
		} else if ($data['plan']['product_short'] == 'NUS') {
			$data['insurable_options'] = $this->load->view('plan/detail_jus', $data, TRUE);
		} else if ($data['plan']['product_short'] == 'JES') {
			$data['insurable_options'] = $this->load->view('plan/detail_jes', $data, TRUE);
		} else if ($plan['plan']['product_short'] == 'JFC') {
			$data['insurable_options'] = $this->load->view('plan/detail_jes', $data, TRUE);
		} else {
			$data['insurable_options'] = $this->load->view('plan/detail_other', $data, TRUE);
		}

		$product = $this->product_model->get_product($plan['product_short']);
		$data['plan_full_name'] = $product ? $product['full_name'] : '';

		$mpdf = new mPDF('c');
		$data['title_txt'] = 'Card';
		$data['style'] = $this->load->view('common/pdf_style',$data, TRUE);
		$html = $this->load->view('plan/card', $data, TRUE);
		$mpdf->writeHTML($html);
		$mpdf->Output();
	}
	
	/**
	 * Print Receipt
	 * 
	 * @param integer $plan_id
	 */
	public function receipt($plan_id) {
		$beuser = $this->func_model->verify_login(TRUE);
		$this->load->model('plan_model');
		$this->load->model('product_model');
		$plan = $this->plan_model->get_plan_by_id($plan_id);
		if (empty($plan)) {
			redirect ( base_url ('user/login') );
			return;
		}
		$data = array('plan' => $plan);
		$this->load->model('customer_model');
		$data['customer'] = $this->customer_model->get_customer_by_id($data['plan']['customer_id']);
		$data['customers'] = $this->customer_model->get_customer_by_parent_id($data['plan']['customer_id']);
		if ($data['plan']['product_short'] == 'OPL') {
			$data['insurable_options'] = $this->load->view('plan/detail_opl', $data, TRUE);
		} else if ($data['plan']['product_short'] == 'JFR') {
			$data['insurable_options'] = $this->load->view('plan/detail_opl', $data, TRUE);
		} else if ($data['plan']['product_short'] == 'JUS') {
			$data['insurable_options'] = $this->load->view('plan/detail_jus', $data, TRUE);
		} else if ($data['plan']['product_short'] == 'NUS') {
			$data['insurable_options'] = $this->load->view('plan/detail_jus', $data, TRUE);
		} else if ($data['plan']['product_short'] == 'JES') {
			$data['insurable_options'] = $this->load->view('plan/detail_jes', $data, TRUE);
		} else if ($plan['plan']['product_short'] == 'JFC') {
			$data['insurable_options'] = $this->load->view('plan/detail_jes', $data, TRUE);
		} else {
			$data['insurable_options'] = $this->load->view('plan/detail_other', $data, TRUE);
		}

		$product = $this->product_model->get_product($plan['product_short']);
		$data['plan_full_name'] = $product ? $product['full_name'] : '';
		
		$mpdf = new mPDF('c');
		$data['title_txt'] = 'Receipt';
		$data['style'] = $this->load->view('common/pdf_style',$data, TRUE);
		$html = $this->load->view('plan/receipt', $data, TRUE);
		$mpdf->writeHTML($html);
		$mpdf->Output();
	}
	
	public function copy($plan_id) {
		$beuser = $this->func_model->verify_login(TRUE);
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

	public function edit($plan_id=0) {
		$this->load->model('plan_model');
		$plan = $this->plan_model->get_plan_by_id($plan_id);
		$this->form($plan);
	}
}
