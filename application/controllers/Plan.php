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
		$data['morefilter'] = (    empty($data['firstname']) 
								&& empty($data['lastname']) 
								&& empty($data['birthday']) 
								&& empty($data['birthday2']) 
								&& empty($data['apply_date']) 
								&& empty($data['apply_date2']) 
								&& empty($data['arrival_date']) 
								&& empty($data['arrival_date2']) 
								&& empty($data['effective_date']) 
								&& empty($data['effective_date2']) 
								&& empty($data['expiry_date']) 
								&& empty($data['expiry_date2']) 
								&& empty($data['uname']) 
								&& empty($data['status_id']) 
								&& empty($data['product_short']) 
								&& empty($data['country2']) 
								&& empty($data['batch_number']) ) ? 1 : 0;
		
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
		$data['pdf_url'] = base_url ( "plan/pdf" ) . "/";
		$data['province_url'] = base_url ( "geo/province/" );
		$data['country_url'] = base_url ( "geo/country/" );
		$data['sendpackage_url'] = base_url ( "plan/sendpackage" ) . "/";
		if (empty($data['province2'])) {
			$data['province2'] = 'ON';
			$data['country2'] = 'CA';
		}
		if (empty($data['country2'])) {
			$data['country2'] = 'CA';
		}
		if (!empty($data['country2'])) {
			$data['province_url'] .= "/" . $data['country2'];
			$data['country_url'] .= "/" . $data['country2'];
			if (!empty($data['province2'])) {
				$data['province_url'] .= "/" . $data['province2'];
			}
		}
		

		$data['export_logo_url'] = base_url('plan/exportlogo') . "/";
		$data['export_price_url'] = base_url('plan/exportprice') . "/";
		$data['export_logo_price_option'] = FALSE;
		if ($beuser['user_group_id'] < 100) $data['export_logo_price_option'] = TRUE;
			
		$this->session->set_userdata ( 'withlogo', 1);
		$this->session->set_userdata ( 'withprice', 1);


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
			$policies = $this->plan_model->plan_export_search(json_decode($policy_search, TRUE));
		} else {
			$policies = array();
		}
		
		$w = WriterFactory::create(Type::XLSX); // for XLSX files
		$kArr = array(
				'plan_id',
				'customer_id',
				'user_id',
				'status_id',
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
				'holiday_rate',
				'spouse',
				'sum_insured',
				'deductible_amount',
				'dailyrate',
				'totaldays',
				'totalyears',
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
	
	function from_valid_family_member() {
		$older_than_21 = 0;
		$today = date("Y-m-d");
		for ($i = 1 ; $i < 9; $i++) {
			if (empty($this->input->post('gender_' . $i)) || empty($this->input->post('firstname_' . $i)) || empty($this->input->post('lastname_' . $i)) || empty($this->input->post('birthday_' . $i))) {
				continue;
			}
			$years = $this->product_model->getYears($today, $this->input->post('birthday_' . $i));
			if ($years > 61) {
				$this->error['error_birthday_' . $i] = 'Member older than 61';
			}
			if ($years > 21) {
				$older_than_21++;
				if ($older_than_21 >= 2) {
					$this->error['error_birthday_' . $i] = 'Multiple member older than 21';
				}
			}
		}
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
		if (empty($arrival_date)) {	// 2015-01-01
			$this->error['error_arrival_date'] = 'Confirm Arrival Date';
		} else if (($product_short == 'JFR') && ($arrivaltm < 1420070400)) {	// 2015-01-01
			$this->error['error_arrival_date'] = "Arrival Date is too early";
		}
		$effective_date = $this->input->post('effective_date');
		$effectivetm = strtotime($effective_date);
		if (empty($effective_date) || ($effectivetm < 1466555500)) {
			$this->error['error_effective_date'] = 'Confirm Effective Date';
		}
		if ($arrivaltm > $effectivetm) {
			$this->error['error_effective_date'] = 'Arrival Date cannot be later than Effective Date';
		}
		$expiry_date = $this->input->post('expiry_date');
		$expirytm = strtotime($expiry_date);
		if (empty($expiry_date) || ($expirytm < 1466555500) || ($expirytm < $effectivetm)) {
			$this->error['error_expiry_date'] = 'Confirm Expiry Date';
		}
		if ((float)$this->input->post('premium') < 0.1) {
			$this->error['error_premium'] = "Please check all your input fields. Premium amount can't be 0!";
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
		if (($product_short == 'OPL') || ($product_short == 'JFR')) {
			if (empty($this->input->post('stable_condition'))) {
				$this->error['error_stable_condition'] = 'Please select pre-existion condition coverage';
			}
			if ($product_short == 'JFR') {
				$years = $this->product_model->getYears($arrival_date, $effective_date);
				if ($years >= 2) {
					$this->error['error_effective_date'] = 'Effective Date must less than 2 Years for Arrival Date';
				}
			}
				
			$this->from_valid_family_member();
		} else if (($product_short == 'JUS') || ($product_short == 'NUS')) {
			if (empty($this->input->post('rate_options'))) {
				$this->error['error_rate_options'] = 'Please select rate options';
			}
			$skip_cnt = (empty($this->input->post('spouse'))) ? 0 : 1;
			$apply_date = $this->input->post('apply_date');
			$birthday = $this->input->post('birthday');
			$years = $this->product_model->getYears($apply_date, $birthday);
			if ($years > 69) {
				$this->error['error_message'] = "Customer age must less 69 years old";
			} else {
				for ($i = 1; $i < 9; $i++) {
					$birthday = $this->input->post('birthday_'.$i);
					if (empty($birthday)) break;
					$years = $this->product_model->getYears($apply_date, $birthday);
					if ($years > 26) {
						if ($skip_cnt <= 0) {
							$this->error['error_message'] = 'Dependent children must under age 26';
						}
						$skip_cnt--;
					}
				}
			}
		} else if (($product_short == 'JES') || ($product_short == 'JFC')) {
			$apply_date = $this->input->post('apply_date');
			$birthday = $this->input->post('birthday');
			$years = $this->product_model->getYears($apply_date, $birthday);
			if ($years > 69) {
				$this->error['error_message'] = "Customer age must less 69 years old";
			} else {
				for ($i = 1; $i < 9; $i++) {
					$birthday = $this->input->post('birthday_'.$i);
					if (empty($birthday)) break;
					$years = $this->product_model->getYears($apply_date, $birthday);
					if ($years > 69) {
						$this->error['error_message'] = "Customer age must less 69 years old";
						break;
					}
				}
			}
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
				$plan_id = $this->plan_model->update($plan_id, $this->input->post(), array('isfamilyplan' => 1, 'holiday_rate' => 1, 'spouse' => 1));
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
		if (empty($plan) && $data['plan_id']) {
			$plan = $this->plan_model->get_plan_by_id($data['plan_id']);
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

		if ($this->input->post('student_id')) {
			$data['student_id'] = $this->input->post('student_id');
		} else if (isset($plan['student_id'])) {
			$data['student_id'] = $plan['student_id'];
		} else {
			$data['student_id'] = '';
		}
		if ($this->input->post('institution_addr')) {
			$data['institution_addr'] = $this->input->post('institution_addr');
		} else if (isset($plan['institution_addr'])) {
			$data['institution_addr'] = $plan['institution_addr'];
		} else {
			$data['institution_addr'] = '';
		}
		if ($this->input->post('institution')) {
			$data['institution'] = $this->input->post('institution');
		} else if (isset($plan['institution'])) {
			$data['institution'] = $plan['institution'];
		} else {
			$data['institution'] = '';
		}
		if ($this->input->post('institution_phone')) {
			$data['institution_phone'] = $this->input->post('institution_phone');
		} else if (isset($plan['institution_phone'])) {
			$data['institution_phone'] = $plan['institution_phone'];
		} else {
			$data['institution_phone'] = '';
		}
		
		if ($this->input->post('stable_condition')) {
			$data['stable_condition'] = $this->input->post('stable_condition'); 
		} else if (isset($plan['stable_condition'])) {
			$data['stable_condition'] = $plan['stable_condition'];
		} else {
			$data['stable_condition'] = 0;
		}
		if ($this->input->post('rate_options')) {
			$data['rate_options'] = $this->input->post('rate_options'); 
		} else if (isset($plan['rate_options'])) {
			$data['rate_options'] = $plan['rate_options'];
		} else {
			$data['rate_options'] = 0;
		}
		if ($this->input->post('holiday_rate')) {
			$data['holiday_rate'] = $this->input->post('holiday_rate'); 
		} else if (isset($plan['holiday_rate'])) {
			$data['holiday_rate'] = $plan['holiday_rate'];
		} else {
			$data['holiday_rate'] = 0;
		}
		if ($this->input->post('spouse')) {
			$data['spouse'] = $this->input->post('spouse');; 
		} else if (isset($plan['spouse'])) {
			$data['spouse'] = $plan['spouse'];
		} else {
			$data['spouse'] = 0;
		}
		if ($this->input->post('deductible_amount')) {
			$data['deductible_amount'] = $this->input->post('deductible_amount'); 
		} else if (isset($plan['deductible_amount'])) {
			$data['deductible_amount'] = $plan['deductible_amount'];
		} else {
			if (($data['product_short'] == 'NUS') || ($data['product_short'] == 'JUS')) {
				$data['deductible_amount'] = 100;
			} else {
				$data['deductible_amount'] = '';
			}
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
			$data['province2'] = 'ON';
		}
		if ($this->input->post('country2')) {
			$data['country2'] = $this->input->post('country2');
		} else if (isset($plan['country2'])) {
			$data['country2'] = $plan['country2'];
		} else {
			$data['country2'] = 'CA';
		}
		if (empty($data['province2'])) {
			$data['province2'] = 'ON';
			$data['country2'] = 'CA';
		}
		if (empty($data['country2'])) {
			$data['country2'] = 'CA';
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
		$data['payhistory_url'] = base_url ( "plan/payhistory/" . $data['plan_id'] );
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
		
		$data['policy_user'] = (isset($plan) && !empty($plan['user_id'])) ? $this->user_model->get_user_by_id($plan['user_id']) : $this->user_model->get_user_by_id($beuser['user_id']);
		$data['premium_url'] = base_url ( "product/getpremium" );
		$data['action_url'] = base_url ( "plan/form" );
		$data['claimurl'] = base_url ( "claim/add" ) . "/";
		$data['sendpackage_url'] = base_url ( "plan/sendpackage" ) . "/";
		$data['cancel_url'] = base_url ( "plan/cancel" ) . "/";
		$data['refund_url'] = base_url ( "plan/refund" ) . "/";
		$data['cancelprint_url'] = base_url ( "plan/cancelprint" ) . "/";
		$data['refundprint_url'] = base_url ( "plan/refundprint" ) . "/" . (int)$data['plan_id'];
		$data['revert_url'] = base_url ( "payment/revert" ) . "/";
		$data['makepay_url'] = base_url ( "payment/makepay" );

		$data['print_card_url'] = '';
		$data['print_receipt_url'] = '';
		$data['cancel_letter_url'] = '';
		$data['refund_letter_url'] = '';
		$data['pdf_url'] = base_url('plan/pdf/' . $plan['plan_id']);
		$data['export_logo_url'] = base_url('plan/exportlogo') . "/";
		$data['export_price_url'] = base_url('plan/exportprice') . "/";
		$data['export_logo_price_option'] = FALSE;
		if ($plan['product_short'] == 'JES') {
			if ($beuser['user_group_id'] < 100) $data['export_logo_price_option'] = TRUE;
		} else if ($plan['product_short'] == 'JFC') {
			if ($beuser['user_group_id'] < 100) $data['export_logo_price_option'] = TRUE;
		}
		$this->session->set_userdata ( 'withlogo', 1);
		$this->session->set_userdata ( 'withprice', 1);
		
		if (!empty($plan) && !empty($plan['status_id']) && !empty($plan['plan_id']) && ($plan['status_id'] >= 2)) {
			if ($plan['status_id'] == 5) {
				// Cancel
				$data['cancel_letter_url'] = base_url('plan/cancelprint/' . $plan['plan_id']);
			} else if ($plan['status_id'] == 6) {
				// Refund
				$data['refund_letter_url'] = base_url('plan/refundprint/' . $plan['plan_id']);
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

		$data['isprocessplan'] = 1;
		if (!empty($plan) && !empty($plan['status_id']) && ($plan['status_id'] > 1) && ($beuser['user_group_id'] > 100)) {
			if ($data['product_short'] == 'OPL') {
				$data['insurable_options'] = $this->load->view('plan/form_opl_agent', $data, TRUE);
			} else if ($data['product_short'] == 'JFR') {
				$data['insurable_options'] = $this->load->view('plan/form_opl_agent', $data, TRUE);
			} else if ($data['product_short'] == 'JUS') {
				$data['insurable_options'] = $this->load->view('plan/form_jus_agent', $data, TRUE);
			} else if ($data['product_short'] == 'NUS') {
				$data['insurable_options'] = $this->load->view('plan/form_jus_agent', $data, TRUE);
			} else if ($data['product_short'] == 'JES') {
				$data['insurable_options'] = $this->load->view('plan/form_jes_agent', $data, TRUE);
			} else if ($data['product_short'] == 'JFC') {
				$data['insurable_options'] = $this->load->view('plan/form_jfc_agent', $data, TRUE);
			} else {
				$data['insurable_options'] = $this->load->view('plan/form_other_agent', $data, TRUE);
				$data['isprocessplan'] = 0;
			}
			
			$this->load->common('plan/form_agent', $data);
		} else {
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
			} else if ($data['product_short'] == 'JFC') {
				$data['insurable_options'] = $this->load->view('plan/form_jfc', $data, TRUE);
			} else {
				$data['insurable_options'] = $this->load->view('plan/form_other', $data, TRUE);
				$data['isprocessplan'] = 0;
			}
	
			$data['popRefund'] = $this->load->view('plan/refund_addr', $data, TRUE);
			
			//$this->load->common('plan/form', $data);
			$this->load->common('plan/form', $data);
		}
	}
	
	function payhistory($plan_id) {
		$beuser = $this->func_model->verify_login();
	
		$this->load->model('plan_model');
		$this->load->model('payment_model');
		$data['show_history'] = 1;
		$sort = $this->input->get('s');
		$data['payments'] = $this->payment_model->get_payment_by_plan_id($plan_id, $sort);
		$data['total_payment'] = array();
		if ($sort == 'type') {
			foreach ($data['payments'] as $pay) {
				if (isset($data['total_payment'][$pay['pay_type']])) {
					$data['total_payment'][$pay['pay_type']] += $pay['amount'];
				} else {
					$data['total_payment'][$pay['pay_type']] = $pay['amount'];
				}
			}
		}
		
		$data['payhistory_url'] = base_url ( "plan/payhistory/" . $plan_id );
		$data['user_group_id'] = $beuser['user_group_id'];
		$data['revert_url'] = base_url ( "payment/revert" ) . "/";
		$data['makepay_url'] = base_url ( "payment/makepay" );
	
		$data['csrf'] = array (
				'name' => $this->security->get_csrf_token_name (),
				'value' => $this->security->get_csrf_hash ()
		);
	
		$this->load->view('plan/form_payment', $data);
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

	private function credit_card_negative() {
		$this->load->model('plan_model');
		$this->load->model('product_model');
		$this->load->model('payment_model');
		
		$plan_id = $this->input->post('plan_id');
		// $premium = preg_replace("/[^0-9\.-]/", "", $this->input->post('premium'));
		$premium = (float)$this->input->post('premium');
		
		$plan = $this->plan_model->get_plan_by_id($plan_id);
		$product = $this->product_model->get_product($plan['product_short']);
		$dt = array();
		$dt['plan_id'] = $plan_id;
		$dt['currency'] = $product['currency'];
		$dt['pay_mothed'] = 'Credit Card';
		$dt['name'] = 'No Card Needs';
		$dt['added'] = date('c');
		$dt['first5'] = 'XXXXX';
		$dt['last4'] = 'XXXX';
		$dt['expiry_month'] = '01';
		$dt['expiry_year'] = '01';
		$dt['ispaid'] = 0;
		$commission_rate = $this->product_model->get_commission_rate($plan['product_short'], $plan['user_id']);
		$commission_amount = $premium * $commission_rate / 100.0;
		$up_commission_rate = $this->product_model->get_up_commission_rate($plan['product_short']);
		$up_commission_amount = $premium * $up_commission_rate / 100.0;
				
		$dt['amount'] = $premium;
		$dt['rate'] = 100;
		$dt['pay_type'] = 'premium';
		$payment_id = $this->payment_model->add($dt);
		$para = array(
				'plan_id' => $plan_id,
				'customer_id' => $plan['customer_id'],
				'payment_id' => $payment_id,
				'message' => $this->payment_model->logstr,
				'systemlog' => $this->payment_model->sqlstr
		);
		$this->log_model->activity('payment', $para);

		// upstream commission
		$dt['amount'] = $up_commission_amount;
		$dt['rate'] = $up_commission_rate;
		$dt['pay_type'] = 'up_commission';
		$up_commission_payment_id = $this->payment_model->add($dt);
		$para = array(
				'plan_id' => $plan_id,
				'customer_id' => $plan['customer_id'],
				'payment_id' => $up_commission_payment_id,
				'message' => $this->payment_model->logstr,
				'systemlog' => $this->payment_model->sqlstr
		);
		$this->log_model->activity('up_commission', $para);

		// commission
		$dt['amount'] = $commission_amount;
		$dt['rate'] = $commission_rate;
		$dt['pay_type'] = 'commission';
		if (($plan['product_short'] == 'OPL') || ($plan['product_short'] == 'JFR') && ($premium > 100000)) {
			$dt['added'] = $plan['effective_date'];
		}
		$commission_payment_id = $this->payment_model->add($dt);
		$para = array(
				'plan_id' => $plan_id,
				'customer_id' => $plan['customer_id'],
				'payment_id' => $commission_payment_id,
				'message' => $this->payment_model->logstr,
				'systemlog' => $this->payment_model->sqlstr
		);
		$this->log_model->activity('commission', $para);

		$payinfo = "Credit Card: paymount is negative";
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
	
	private function credit_card() {
		$this->load->model('plan_model');
		$this->load->model('product_model');
		$this->load->model('payment_model');
		
		$plan_id = $this->input->post('plan_id');
		// $premium = preg_replace("/[^0-9\.-]/", "", $this->input->post('premium'));
		$premium = (float)$this->input->post('premium');
		if ($premium < 0) {
			return $this->credit_card_negative();
		}
		
		if (empty( $this->input->post('card_number') ) ) {
			$this->error = 'Please input Card Number.';
		} else if (empty( $this->input->post('card_name') ) ) {
			$this->error = 'Please input Card Name.';
		} else if (empty( $this->input->post('expiry_month') ) ) {
			$this->error = 'Please select Expiry Month.';
		} else if (empty( $this->input->post('expiry_year') ) ) {
			$this->error = 'Please select Expiry Year.';
		} else if (empty( $this->input->post('card_cvv') ) ) {
			$this->error = 'Please Input Card CVV';
		} else {
			$card_number = $this->input->post('card_number');
			$card_name = $this->input->post('card_name');
			$expiry_month = $this->input->post('expiry_month');
			$expiry_year = $this->input->post('expiry_year');
			$card_cvv = $this->input->post('card_cvv');

			$plan = $this->plan_model->get_plan_by_id($plan_id);
			$product = $this->product_model->get_product($plan['product_short']);
			$dt = array();
			$dt['plan_id'] = $plan_id;
			$dt['currency'] = $product['currency'];
			$dt['pay_mothed'] = 'Credit Card';
			$dt['name'] = $card_name;
			$dt['added'] = date('c');
			$dt['first5'] = substr($card_number, 0, 5);
			$dt['last4'] = substr($card_number, -4);
			$dt['expiry_month'] = $expiry_month;
			$dt['expiry_year'] = $expiry_year;
			$dt['ispaid'] = 0;
			$commission_rate = $this->product_model->get_commission_rate($plan['product_short'], $plan['user_id']);
			$commission_amount = $premium * $commission_rate / 100.0;
			$up_commission_rate = $this->product_model->get_up_commission_rate($plan['product_short']);
			$up_commission_amount = $premium * $up_commission_rate / 100.0;
					
			$dt['amount'] = $premium;
			$dt['rate'] = 100;
			$dt['pay_type'] = 'premium';
			$payment_id = $this->payment_model->add($dt);
			$para = array(
					'plan_id' => $plan_id,
					'customer_id' => $plan['customer_id'],
					'payment_id' => $payment_id,
					'message' => $this->payment_model->logstr,
					'systemlog' => $this->payment_model->sqlstr
			);
			$this->log_model->activity('payment', $para);

			// upstream commission
			$dt['amount'] = $up_commission_amount;
			$dt['rate'] = $up_commission_rate;
			$dt['pay_type'] = 'up_commission';
			$up_commission_payment_id = $this->payment_model->add($dt);
			$para = array(
					'plan_id' => $plan_id,
					'customer_id' => $plan['customer_id'],
					'payment_id' => $up_commission_payment_id,
					'message' => $this->payment_model->logstr,
					'systemlog' => $this->payment_model->sqlstr
			);
			$this->log_model->activity('up_commission', $para);

			// commission
			$dt['amount'] = $commission_amount;
			$dt['rate'] = $commission_rate;
			$dt['pay_type'] = 'commission';
			if (($plan['product_short'] == 'OPL') || ($plan['product_short'] == 'JFR') && ($premium > 100000)) {
				$dt['added'] = $plan['effective_date'];
			}
			$commission_payment_id = $this->payment_model->add($dt);
			$para = array(
					'plan_id' => $plan_id,
					'customer_id' => $plan['customer_id'],
					'payment_id' => $commission_payment_id,
					'message' => $this->payment_model->logstr,
					'systemlog' => $this->payment_model->sqlstr
			);
			$this->log_model->activity('commission', $para);
			$beanstream = new \Beanstream\Gateway ( $product['merchent_id'], $product['apikey'], 'www', 'v1' );
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
					
					$dt = array();
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
					
					$dt = array();
					$dt['ispaid'] = 0;
					$dt['amount'] = 0;
					$dt['note'] = "Failur pay (" . $premium . "): Raw Data=> " . json_encode($result);
					$payment_id = $this->payment_model->update($payment_id, $dt);
					$para = array(
							'plan_id' => $plan_id,
							'customer_id' => $plan['customer_id'],
							'payment_id' => $payment_id,
							'message' => $this->payment_model->logstr,
							'systemlog' => $this->payment_model->sqlstr
					);
					$this->log_model->activity('payment', $para);
					$commission_payment_id = $this->payment_model->update($commission_payment_id, $dt);
					$up_commission_payment_id = $this->payment_model->update($up_commission_payment_id, $dt);
					$this->error = 'Card payment failed. Incorrect card information or insufficient credit.';
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
				$dt = array();
				$dt['ispaid'] = 0;
				$dt['amount'] = 0;
				$dt['note'] = "Failur pay (" . $premium . "): (libraray) Raw Data=> " . $e->getMessage() . " : " . json_encode($e);
				$payment_id = $this->payment_model->update($payment_id, $dt);
				$para = array(
						'plan_id' => $plan_id,
						'customer_id' => $plan['customer_id'],
						'payment_id' => $payment_id,
						'message' => $this->payment_model->logstr,
						'systemlog' => $this->payment_model->sqlstr
				);
				$this->log_model->activity('payment', $para);
				$commission_payment_id = $this->payment_model->update($commission_payment_id, $dt);
				$up_commission_payment_id = $this->payment_model->update($up_commission_payment_id, $dt);
				$this->error = 'Card payment failed. Something wrong. Please contact support.';
			}
		}
	}

	private function cash() {
		$this->load->model('plan_model');
		$this->load->model('product_model');
		$this->load->model('payment_model');
		
		$plan_id = $this->input->post('plan_id');
		$premium = preg_replace("/[^0-9\.-]/", "", $this->input->post('premium'));
		$payinfo = "Pay Cash: " . 'Premium: $' . $premium . "; ";
		
		$plan = $this->plan_model->get_plan_by_id($plan_id);
		$product = $this->product_model->get_product($plan['product_short']);
		$dt = array();
		$dt['plan_id'] = $plan_id;
		$dt['amount'] = $premium;
		$dt['pay_type'] = 'premium';
		$dt['currency'] = $product['currency'];
		$dt['pay_mothed'] = 'Cash';
		$dt['added'] = date('c');
		$dt['note'] = $payinfo;
		$dt['ispaid'] = 0;

		$commission_rate = $this->product_model->get_commission_rate($plan['product_short'], $plan['user_id']);
		$commission_amount = $premium * $commission_rate / 100.0;
		$up_commission_rate = $this->product_model->get_up_commission_rate($plan['product_short']);
		$up_commission_amount = $premium * $up_commission_rate / 100.0;
		
		$dt['amount'] = $premium;
		$dt['rate'] = 100;
		$dt['pay_type'] = 'premium';
		$payment_id = $this->payment_model->add($dt);
		$para = array(
				'plan_id' => $plan_id,
				'customer_id' => $plan['customer_id'],
				'payment_id' => $payment_id,
				'message' => $this->payment_model->logstr,
				'systemlog' => $this->payment_model->sqlstr
		);
		$this->log_model->activity('payment', $para);
		
		// up commission
		$dt['amount'] = $up_commission_amount;
		$dt['rate'] = $up_commission_rate;
		$dt['pay_type'] = 'up_commission';
		$up_commission_payment_id = $this->payment_model->add($dt);
		$para = array(
				'plan_id' => $plan_id,
				'customer_id' => $plan['customer_id'],
				'payment_id' => $up_commission_payment_id,
				'message' => $this->payment_model->logstr,
				'systemlog' => $this->payment_model->sqlstr
		);
		$this->log_model->activity('up_commission', $para);

		// commission
		$dt['amount'] = $commission_amount;
		$dt['rate'] = $commission_rate;
		$dt['pay_type'] = 'commission';
		if (($plan['product_short'] == 'OPL') || ($plan['product_short'] == 'JFR') && ($premium > 100000)) {
			$dt['added'] = $plan['effective_date'];
		}
		$commission_payment_id = $this->payment_model->add($dt);
		$para = array(
				'plan_id' => $plan_id,
				'customer_id' => $plan['customer_id'],
				'payment_id' => $commission_payment_id,
				'message' => $this->payment_model->logstr,
				'systemlog' => $this->payment_model->sqlstr
		);
		$this->log_model->activity('commission', $para);

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
		$premium = preg_replace("/[^0-9\.-]/", "", $this->input->post('premium'));
		$payinfo  = 'Invoice Number: ' . $this->input->post('invoice_num') . "; ";
		$payinfo .= 'Bank Name: ' . $this->input->post('bank_name') . "; ";
		$payinfo .= 'Payor Name: ' . $this->input->post('payor_name') . "; ";
		$payinfo .= 'Cheque#: ' . $this->input->post('cheque_number') . "; ";
		$payinfo .= 'Premium: $' . $this->input->post('premium') . "; ";

		$plan = $this->plan_model->get_plan_by_id($plan_id);
		$product = $this->product_model->get_product($plan['product_short']);
		$dt = array();
		$dt['plan_id'] = $plan_id;
		$dt['amount'] = $premium;
		$dt['pay_type'] = 'premium';
		$dt['currency'] = $product['currency'];
		$dt['pay_mothed'] = 'Cheque';
		$dt['invoice_num'] = empty($this->input->post('invoice_num')) ? '' : $this->input->post('invoice_num');
		$dt['bank_name'] = $this->input->post('bank_name');
		$dt['payor_name'] = $this->input->post('payor_name');
		$dt['cheque_number'] = $this->input->post('cheque_number');
		$dt['added'] = date('c');
		$dt['note'] = $payinfo;
		$dt['ispaid'] = 0;

		$commission_rate = $this->product_model->get_commission_rate($plan['product_short'], $plan['user_id']);
		$commission_amount = $premium * $commission_rate / 100.0;
		$up_commission_rate = $this->product_model->get_up_commission_rate($plan['product_short']);
		$up_commission_amount = $premium * $up_commission_rate / 100.0;
		
		$dt['amount'] = $premium;
		$dt['rate'] = 100;
		$dt['pay_type'] = 'premium';
		$payment_id = $this->payment_model->add($dt);
		$para = array(
				'plan_id' => $plan_id,
				'customer_id' => $plan['customer_id'],
				'payment_id' => $payment_id,
				'message' => $this->payment_model->logstr,
				'systemlog' => $this->payment_model->sqlstr
		);
		$this->log_model->activity('payment', $para);

		// up commission
		$dt['amount'] = $up_commission_amount;
		$dt['rate'] = $up_commission_rate;
		$dt['pay_type'] = 'up_commission';
		$up_commission_payment_id = $this->payment_model->add($dt);
		$para = array(
				'plan_id' => $plan_id,
				'customer_id' => $plan['customer_id'],
				'payment_id' => $up_commission_payment_id,
				'message' => $this->payment_model->logstr,
				'systemlog' => $this->payment_model->sqlstr
		);
		$this->log_model->activity('up_commission', $para);

		// commission
		$dt['amount'] = $commission_amount;
		$dt['rate'] = $commission_rate;
		$dt['pay_type'] = 'commission';
		if (($plan['product_short'] == 'OPL') || ($plan['product_short'] == 'JFR') && ($premium > 100000)) {
			$dt['added'] = $plan['effective_date'];
		}
		$commission_payment_id = $this->payment_model->add($dt);
		$para = array(
				'plan_id' => $plan_id,
				'customer_id' => $plan['customer_id'],
				'payment_id' => $commission_payment_id,
				'message' => $this->payment_model->logstr,
				'systemlog' => $this->payment_model->sqlstr
		);
		$this->log_model->activity('commission', $para);
		
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

	function exportlogo($withlogo=1) {
		$this->session->set_userdata ( 'withlogo',  $withlogo);
		echo $withlog ? 'Has Logo' : 'No Logo';
	}

	function exportprice($withprice=1) {
		$this->session->set_userdata ( 'withprice',  $withprice);
		echo $withprice ? 'With Price' : 'No Price';
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
		$this->load->model('payment_model');
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
		if ($play_type = $this->input->post('play_type')) {
			// Pay Action
			if (($plan['status_id'] == 3) && empty($this->session->userdata ('user'))) {
				// Paid Status and Paied from User
				$this->load->model('mymail_model');
				$body = "Hi; \r\nYour client " . $plan['firstname'] . " " . $plan['lastname'] . " has paied for Policy " . $plan['polcy'];
				$this->mymail_model->send_mymail($beuser['email'], 'Your client paide', $body);
			}
		}
		
		if (($plan['status_id'] < 2) && ($beuser['user_group_id'] > 100)) {
			// Before Sold
			$para = array();
			$para['product_short'] = $plan['product_short'];
			$para['apply_date'] = date('Y-m-d');
			$para['effective_date'] = $plan['effective_date'];
			$para['expiry_date'] = $plan['expiry_date'];
			$para['isfamilyplan'] = $plan['isfamilyplan'];
			$para['sum_insured'] = $plan['sum_insured'];
			$para['deductible_amount'] = $plan['deductible_amount'];
			$para['rate_options'] = $plan['rate_options'];
			$para['holiday_rate'] = $plan['holiday_rate'];
			$para['spouse'] = $plan['spouse'];
			$para['stable_condition'] = $plan['stable_condition'];
			$para['holiday_rate'] = $plan['holiday_rate'];
			$para['birthday'] = $this->customer_model->get_max_birthday($plan['customer_id'], $plan['isfamilyplan'], $plan['product_short']);
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
		} else {
			$data['apply_date'] = $plan['apply_date'];
		}
		$data['plan'] = $plan;
		$product = $this->product_model->get_product($plan['product_short']);
		$data['plan_full_name'] = $product ? $product['full_name'] : '';
		
		$data['customer'] = $this->customer_model->get_customer_by_id($plan['customer_id']);
		$data['customers'] = $this->customer_model->get_customer_by_parent_id($plan['customer_id']);
		
		if (empty($sekey)) {
			if ($beuser['user_group_id'] < 100) {
				$data['paytype_list'] = $this->paytype_model->paytype_list();
			} else {
				$data['paytype_list'] = explode(",", trim($beuser['pay_type']));
			}
		} else {
			$data['paytype_list'] = $this->paytype_model->paytype_default();
		}
		$data['payurl'] = base_url('plan/detail/' . $plan_id . '/' . $this->plan_model->get_plan_key($plan_id));
		$data['active_url'] = current_url();
		$data['status_list'] = $this->status_model->status_list();
		$days = $this->product_model->getDays('today', $plan['effective_date']);
		$data['payment_total'] = $plan['premium'] - $this->payment_model->get_total_paid($plan['plan_id'], 'premium');
		/* Can't update status to PAID when payment ispaid = 0 !!!
		if (empty($data['payment_total']) && ($plan['status_id'] == Plan_model::SOLD)) {
			$this->plan_model->update($plan_id, array('status_id' => Plan_model::PAID));
			$data['plan']['status_id'] = Plan_model::PAID;
		}
		*/
		if (!empty($data['payment_total']) && ($days < 1) && ($beuser['user_group_id'] > 100)) {
			$data['error_message'] = "You have to pay before Effective date.";
			$data['payment_total'] = '';
		}
		$data['defaultpay_type'] = $defaultpay_type;
		$display = 1;
		if (empty($defaultpay_type)) {
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
			$data['pay_type'] = $defaultpay_type;
			if ($data['pay_type'] == 'Credit Card') {
				$data['credit_dis'] = 1;
			} else if ($data['pay_type'] == 'Cheque') {
				$data['cheque_dis'] = 1;
			} else if ($data['pay_type'] == 'Cash') {
				$data['cash_dis'] = 1;
			}
		}
		
		$data['policy_user'] = $this->user_model->get_user_by_id($plan['user_id']);
		$data['pdf_url'] = base_url('plan/pdf/' . $plan['plan_id']);
		$data['print_card_url'] = '';
		$data['print_receipt_url'] = '';
		$data['cancel_letter_url'] = '';
		$data['refund_letter_url'] = '';
		$data['plan_url'] = '';
		$data['sendpackage_url'] = '';
		if ($plan['status_id'] >= 2) {
			if ($plan['status_id'] == 5) {
				// Cancel
				$data['cancel_letter_url'] = base_url('plan/cancelprint/' . $plan['plan_id']);
			} else if ($plan['status_id'] == 6) {
				// Refund
				$data['refund_letter_url'] = base_url('plan/refundprint/' . $plan['plan_id']);
			} else {
				$data['print_card_url'] = base_url('plan/card/' . $plan['plan_id']);
				$data['print_receipt_url'] = base_url('plan/receipt/' . $plan['plan_id']);
				if ($beuser['user_group_id'] < 100) {
					$data['plan_url'] = base_url('plan/edit/' . $plan['plan_id']);
				}
				$data['sendpackage_url'] = base_url ( "plan/sendpackage/" . $plan['plan_id']);
			}
		} else {
			if ($this->session->userdata ( 'user' )) {
				$data['plan_url'] = base_url('plan/edit/' . $plan['plan_id']);
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
		$data['export_logo_url'] = base_url('plan/exportlogo') . "/";
		$data['export_price_url'] = base_url('plan/exportprice') . "/";
		$data['export_logo_price_option'] = FALSE;
		$data['isprocessplan'] = 1;
		if ($data['plan']['product_short'] == 'OPL') {
			$data['insurable_options'] = $this->load->view('plan/detail_opl', $data, TRUE);
		} else if ($data['plan']['product_short'] == 'JFR') {
			$data['insurable_options'] = $this->load->view('plan/detail_opl', $data, TRUE);
		} else if ($data['plan']['product_short'] == 'JUS') {
			$data['insurable_options'] = $this->load->view('plan/detail_jus', $data, TRUE);
		} else if ($data['plan']['product_short'] == 'NUS') {
			$data['insurable_options'] = $this->load->view('plan/detail_jus', $data, TRUE);
		} else if ($data['plan']['product_short'] == 'JES') {
			if ($beuser['user_group_id'] < 100) $data['export_logo_price_option'] = TRUE;
			$data['insurable_options'] = $this->load->view('plan/detail_jes', $data, TRUE);
		} else if ($data['plan']['product_short'] == 'JFC') {
			if ($beuser['user_group_id'] < 100) $data['export_logo_price_option'] = TRUE;
			$data['insurable_options'] = $this->load->view('plan/detail_jes', $data, TRUE);
		} else {
			$data['insurable_options'] = $this->load->view('plan/detail_other', $data, TRUE);
			$data['isprocessplan'] = 0;
		}
		
		$this->session->set_userdata ( 'withlogo', 1);
		$this->session->set_userdata ( 'withprice', 1);
		
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
			$data['withlogo'] = $this->input->post('withlogo');
			$data['withprice'] = $this->input->post('withprice');
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
				
				if ($plan['user_id']) {
					$data['user'] = $this->user_model->get_user_by_id($plan['user_id']);
				}
				if ($data['plan']['product_short'] == 'OPL') {
					$data['insurable_options'] = $this->load->view('plan/detail_opl', $data, TRUE);
					$data['special_note'] = $this->load->view('plan/pdf_note_opl',$data, TRUE);
					$files = array(
					'OPL_Policy.pdf' => DOWNLOADDIR . 'OPL_Policy.pdf',
					'OPL_Claim_Procedure.pdf' => DOWNLOADDIR . 'OPL_Claim_Procedure.pdf',
					'OPL_Consent_Form.pdf' => DOWNLOADDIR . 'OPL_Consent_Form.pdf',
					'OPL_Claim_Form.pdf' => DOWNLOADDIR . 'OPL_Claim_Form.pdf',
					'OPL_Brochure.pdf' => DOWNLOADDIR . 'OPL_Brochure.pdf'
					);
				} else if ($data['plan']['product_short'] == 'JFR') {
					$data['insurable_options'] = $this->load->view('plan/detail_opl', $data, TRUE);
					$data['special_note'] = $this->load->view('plan/pdf_note_jfr',$data, TRUE);
					$files = array(
					'JFR_Policy.pdf' => DOWNLOADDIR . 'JFR_Policy.pdf',
					'JFR_Claim_Procedure.pdf' => DOWNLOADDIR . 'JFR_Claim_Procedure.pdf',
					'JFR_Consent_Form.pdf' => DOWNLOADDIR . 'JFR_Consent_Form.pdf',
					'JFR_Claim_Form.pdf' => DOWNLOADDIR . 'JFR_Claim_Form.pdf',
					'JFR_Brochure.pdf' => DOWNLOADDIR . 'JFR_Brochure.pdf'
					);
				} else if ($data['plan']['product_short'] == 'JUS') {
					$data['insurable_options'] = $this->load->view('plan/detail_jus', $data, TRUE);
					$data['special_note'] = $this->load->view('plan/pdf_note_jus',$data, TRUE);
					$files = array(
					'JUS_Brochure.pdf' => DOWNLOADDIR . 'JUS_Brochure.pdf'
					);
				} else if ($data['plan']['product_short'] == 'NUS') {
					$data['insurable_options'] = $this->load->view('plan/detail_jus', $data, TRUE);
					$data['special_note'] = $this->load->view('plan/pdf_note_nus',$data, TRUE);
					$files = array(
					'NUS_Brochure.pdf' => DOWNLOADDIR . 'NUS_Brochure.pdf'
					);
				} else if ($data['plan']['product_short'] == 'JES') {
					$data['insurable_options'] = $this->load->view('plan/detail_jes', $data, TRUE);
					$data['special_note'] = $this->load->view('plan/pdf_note_jes',$data, TRUE);
					$files = array(
					'JES_Policy.pdf' => DOWNLOADDIR . 'JES_Policy.pdf',
					'JES_Claim_Form.pdf' => DOWNLOADDIR . 'JES_Claim_Form.pdf',
					'JES_Brochure.pdf' => DOWNLOADDIR . 'JES_Brochure.pdf'
					);
				} else if ($data['plan']['product_short'] == 'JFC') {
					$data['insurable_options'] = $this->load->view('plan/detail_jes', $data, TRUE);
					$data['special_note'] = $this->load->view('plan/pdf_note_jfc',$data, TRUE);
					
				} else {
					$data['insurable_options'] = $this->load->view('plan/detail_other', $data, TRUE);
				}
				
				$policy_file = tempnam("/tmp", "Policy");
				//$policy_file = "C:\Users\Administrator\AppData\Local\Temp\Policy";
				$data['title_txt'] = 'Policy';
				$data['style'] = $this->load->view('common/pdf_style',$data, TRUE);
				$mpdf = new mPDF('c');
				$html = $this->load->view('plan/pdf', $data, TRUE);
				$mpdf->writeHTML($html);
				$mpdf->Output($policy_file, 'F');
				$this->load->model('mymail_model');
				$body = $this->load->view('mail/package',$data, TRUE);
				
				$files['policy.pdf'] = $policy_file;
				$sendok = $this->mymail_model->send_mymail($emailaddr, 'Insurance Packages', $body, $files, $from='Support');
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
		$data['withlogo'] = $this->session->userdata('withlogo');
		$data['withprice'] = $this->session->userdata('withprice');
		
		if ($data['plan']['product_short'] == 'OPL') {
			$data['insurable_options'] = $this->load->view('plan/detail_opl', $data, TRUE);
			$data['special_note'] = $this->load->view('plan/pdf_note_opl',$data, TRUE);
		} else if ($data['plan']['product_short'] == 'JFR') {
			$data['insurable_options'] = $this->load->view('plan/detail_opl', $data, TRUE);
			$data['special_note'] = $this->load->view('plan/pdf_note_jfr',$data, TRUE);
		} else if ($data['plan']['product_short'] == 'JUS') {
			$data['insurable_options'] = $this->load->view('plan/detail_jus', $data, TRUE);
			$data['special_note'] = $this->load->view('plan/pdf_note_jus',$data, TRUE);
		} else if ($data['plan']['product_short'] == 'NUS') {
			$data['insurable_options'] = $this->load->view('plan/detail_jus', $data, TRUE);
			$data['special_note'] = $this->load->view('plan/pdf_note_nus',$data, TRUE);
		} else if ($data['plan']['product_short'] == 'JES') {
			$data['insurable_options'] = $this->load->view('plan/detail_jes', $data, TRUE);
			$data['special_note'] = $this->load->view('plan/pdf_note_jes',$data, TRUE);
		} else if ($data['plan']['product_short'] == 'JFC') {
			$data['insurable_options'] = $this->load->view('plan/detail_jes', $data, TRUE);
			$data['special_note'] = $this->load->view('plan/pdf_note_jfc',$data, TRUE);
		} else {
			$data['insurable_options'] = $this->load->view('plan/detail_other', $data, TRUE);
			$data['special_note'] = " ";
		}
		
		$data['title_txt'] = 'Policy';
		$data['style'] = $this->load->view('common/pdf_style',$data, TRUE);		
		$mpdf = new mPDF('c');
		if ($plan['status_id'] < 2) {
			$mpdf->SetWatermarkText ("QUOTE", 0.1);
			$mpdf->showWatermarkText = true;
		}
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
		$this->load->model('product_model');
		$product = $this->product_model->get_product($plan['product_short']);
		
		$data['beuser'] = $beuser;
		$data['plan'] = $plan;


		if ($this->input->post()) {
			$refund_amount = (float)$this->input->post('refund_amount');
			$admin_fee = (float)$this->input->post('admin_fee');

			$total_amount = $refund_amount - $admin_fee;

			if ($total_amount > 0) {
				$this->load->model('payment_model');
				$dt = array();
				$dt['plan_id'] = $plan_id;
				$dt['admin_fee'] = $admin_fee;
				$dt['currency'] = $product['currency'];
				$dt['pay_mothed'] = 'Cheque';
				$dt['added'] = date('c');
				$dt['ispaid'] = 0;
				$dt['note'] = "Cancel at " . $dt['added'] . " amount: " . $refund_amount . " admin fee: " . $admin_fee;
				
				$commission_rate = $this->product_model->get_commission_rate($plan['product_short'], $plan['user_id']);
				$commission_amount = $refund_amount * $commission_rate / 100.0;
				$up_commission_rate = $this->product_model->get_up_commission_rate($plan['product_short']);
				$up_commission_amount = $refund_amount * $up_commission_rate / 100.0;
				
				$dt['amount'] = $total_amount * (-1);
				$dt['rate'] = 100;
				$dt['pay_type'] = 'cancel';
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
				$dt['rate'] = $commission_rate;
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
	
				$dt['pay_type'] = 'cancel_up_commission';
				$dt['rate'] = $up_commission_rate;
				$dt['amount'] = $up_commission_amount * (-1);
				$up_commission_payment_id = $this->payment_model->add($dt);
				$para = array(
						'plan_id' => $plan_id,
						'customer_id' => $plan['customer_id'],
						'payment_id' => $up_commission_payment_id,
						'message' => $this->payment_model->logstr,
						'systemlog' => $this->payment_model->sqlstr
				);
				$this->log_model->activity('up_commission', $para);
	
				$note = "Cancel at " . $dt['added'] . " amount: " . $refund_amount . " admin fee: " . $admin_fee . "; " . $plan['note'];
				$para = array('status_id' => 5, 'payment_id' => $payment_id, 'commission_payment_id' => $commission_payment_id, 'note' => $note );  // Change status to cancel
				$this->plan_model->update($plan_id, $para);
				$para = array(
						'plan_id' => $plan_id,
						'customer_id' => $plan['customer_id'],
						'payment_id' => $payment_id,
						'message' => $this->plan_model->logstr,
						'systemlog' => $this->plan_model->sqlstr
				);
				$this->log_model->activity('plan', $para);
				
				redirect('plan/detail/'.$plan_id);
			} else {
				$data['error_message'] = 'Invalid refund amount';
			}
		}
		$data['action_url'] = base_url('plan/cancel');
		$data['plan_id'] = $plan['plan_id'];
		$data['url_back_to_policy'] = base_url('plan/');
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
		$this->load->model('product_model');
		$plan = $this->plan_model->get_plan_by_id($plan_id);

		//print_r($plan);die('==');
		$data['total_premium'] = $plan['premium'];
		$data['status'] = 'OK';
		$data['refund_amount'] = $this->plan_model->refund_amount($plan_id, $this->input->get('refund_date'));
		$data['refund_days'] = $this->product_model->getDays($plan['effective_date'], $this->input->get('refund_date'));
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
		$this->load->model('product_model');
		$product = $this->product_model->get_product($plan['product_short']);
		
		$data['beuser'] = $beuser;
		$data['plan'] = $plan;
		if ($this->input->post()) {
			$refund_amount = (float)$this->input->post('refund_amount');
			$admin_fee = (float)$this->input->post('admin_fee');
			$total_amount = (float)$this->input->post('total_refund');
			//die($refund_amount . '==' . $admin_fee . '++' . $total_amount);
			if ($total_amount > 0) {
				$this->load->model('payment_model');
				$dt = array();
				$dt['plan_id'] = $plan_id;
				$dt['amount'] = $total_amount * (-1);
				$dt['admin_fee'] = (float)$admin_fee;
				$dt['pay_type'] = 'refund';
				$dt['currency'] = $product['currency'];
				$dt['pay_mothed'] = 'Cheque';
				$dt['added'] = date('c');
				$dt['ispaid'] = 0;
				$dt['note'] = "Refund at " . $dt['added'] . " amount: " . $refund_amount . " admin fee: " . $admin_fee;
				
				$commission_rate = $this->product_model->get_commission_rate($plan['product_short'], $plan['user_id']);
				$commission_amount = $refund_amount * $commission_rate / 100.0;
				$up_commission_rate = $this->product_model->get_up_commission_rate($plan['product_short']);
				$up_commission_amount = $refund_amount * $up_commission_rate / 100.0;
				
				$dt['amount'] = $total_amount * (-1);
				$dt['rate'] = 100;
				$dt['pay_type'] = 'refund';
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
				$dt['rate'] = $commission_rate;
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
	
				$dt['pay_type'] = 'refund_up_commission';
				$dt['rate'] = $up_commission_rate;
				$dt['amount'] = $up_commission_amount * (-1);
				$up_commission_payment_id = $this->payment_model->add($dt);
				$para = array(
						'plan_id' => $plan_id,
						'customer_id' => $plan['customer_id'],
						'payment_id' => $up_commission_payment_id,
						'message' => $this->payment_model->logstr,
						'systemlog' => $this->payment_model->sqlstr
				);
				$this->log_model->activity('up_commission', $para);
	
				$note = "Refund at " . $dt['added'] . " amount: " . $refund_amount . " admin fee: " . $admin_fee . "; " . $plan['note'];
				$para = array('status_id' => 6, 'payment_id' => $payment_id, 'commission_payment_id' => $commission_payment_id, 'note' => $note );  // Change status to refund
				$this->plan_model->update($plan_id, $para);
				$para = array(
						'plan_id' => $plan_id,
						'customer_id' => $plan['customer_id'],
						'payment_id' => $payment_id,
						'message' => $this->plan_model->logstr,
						'systemlog' => $this->plan_model->sqlstr
				);
				$this->log_model->activity('plan', $para);
				redirect('plan/detail/'.$plan_id);
			} else {
				$data['error_message'] = 'Invalid refund amount';
			}
		}
		$data['action_url'] = base_url('plan/refund');
		$data['refund_amount_url'] = base_url('plan/refund_amount')."/".$plan['plan_id'];
		$data['plan_id'] = $plan['plan_id'];
		$data['adminfee'] = 40;
		if ($plan['product_short'] == 'JFC') $data['adminfee'] = 25; 
		$data['url_back_to_policy'] = base_url('plan/');

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
	 * Cancel Letter
	 * 
	 * @param integer $plan_id
	 */
	public function cancelprint($plan_id=0) {
		$beuser = $this->func_model->verify_login(TRUE);
		$this->load->model('plan_model');
		if (empty($plan_id)) {
			$plan_id = $this->input->post('plan_id');
		}
		if (empty($plan_id)) {
			redirect('user/login');
		}
		$plan = $this->plan_model->get_plan_by_id($plan_id);
		if (empty($plan) && ($plan['status'] != 5)) {
			redirect('plan');
		}
		$this->load->model('product_model');
		$this->load->model('payment_model');
		$product = $this->product_model->get_product($plan['product_short']);
		$payment = $this->payment_model->get_payment_by_id($plan['payment_id']);
		
		$data['beuser'] = $beuser;
		$data['plan'] = $plan;

		$total_amount = (float)$payment['amount'] * (-1);
		$admin_fee = (float)$payment['admin_fee'];
		$refund_amount = $total_amount + $admin_fee;

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
		} else if ($data['plan']['product_short'] == 'JFC') {
			$data['insurable_options'] = $this->load->view('plan/detail_jes', $data, TRUE);
		} else {
			$data['insurable_options'] = $this->load->view('plan/detail_other', $data, TRUE);
		}

		$data['style'] = $this->load->view('common/pdf_style',$data, TRUE);
		$html = $this->load->view('plan/cancel', $data, TRUE);
		$mpdf = new mPDF('c');
		$mpdf->writeHTML($html);
		$mpdf->Output();
	}
	
	/**
	 * Refund Letter
	 * 
	 * @param integer $plan_id
	 */
	public function refundprint($plan_id=0) {
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
		$this->load->model('product_model');
		$this->load->model('payment_model');
		$product = $this->product_model->get_product($plan['product_short']);
		$payment = $this->payment_model->get_payment_by_id($plan['payment_id']);

		$data['beuser'] = $beuser;
		$data['plan'] = $plan;
		$data['refundprint_url'] = base_url ( "plan/refundprint" ) . "/" . $plan_id;
		
		$total_amount = (float)$payment['amount'] * (-1);
		$admin_fee = (float)$payment['admin_fee'];
		$refund_amount = $total_amount + $admin_fee;
		
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
		} else if ($data['plan']['product_short'] == 'JFC') {
			$data['insurable_options'] = $this->load->view('plan/detail_jes', $data, TRUE);
		} else {
			$data['insurable_options'] = $this->load->view('plan/detail_other', $data, TRUE);
		}

		//print_r($this->input->post());die('====');

		if ($this->input->post('customer_full_name')) {

			$data['customer_full_name'] = $this->input->post('customer_full_name');
			$data['full_address'] = $this->input->post('full_address');
			$data['city'] = $this->input->post('city');
			$data['province2'] = $this->input->post('province2');
			$data['postcode'] = $this->input->post('postcode');
			$data['style'] = $this->load->view('common/pdf_style',$data, TRUE);
			$html = $this->load->view('plan/refund', $data, TRUE);
			$mpdf = new mPDF('c');
			$mpdf->writeHTML($html);
			$mpdf->Output();
		} else {
			$data['customer_full_name'] = $data['customer']['firstname'] . " " . $data['customer']['lastname'];
			$data['full_address'] = empty($plan['suite_number']) ? '' : $plan['suite_number'] . "- ";
			$data['full_address'] .= $plan['street_number'] . ' ' . $plan['street_name'];
			$data['city'] = $plan['city'];
			$data['province2'] = $plan['province2'];
			$data['postcode'] = $plan['postcode'];

			$this->load->view('plan/refund_addr', $data);
		}
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
			$data['cardp'] = "opl";
			$data['insurable_options'] = $this->load->view('plan/detail_opl', $data, TRUE);
		} else if ($data['plan']['product_short'] == 'JFR') {
			$data['cardp'] = "jfr";
			$data['insurable_options'] = $this->load->view('plan/detail_opl', $data, TRUE);
		} else if ($data['plan']['product_short'] == 'JUS') {
			$data['cardp'] = "jus";
			$data['insurable_options'] = $this->load->view('plan/detail_jus', $data, TRUE);
		} else if ($data['plan']['product_short'] == 'NUS') {
			$data['cardp'] = "nus";
			$data['insurable_options'] = $this->load->view('plan/detail_jus', $data, TRUE);
		} else if ($data['plan']['product_short'] == 'JES') {
			$data['cardp'] = "jes";
			$data['insurable_options'] = $this->load->view('plan/detail_jes', $data, TRUE);
		} else if ($data['plan']['product_short'] == 'JFC') {
			$data['cardp'] = "jfc";
			$data['insurable_options'] = $this->load->view('plan/detail_jes', $data, TRUE);
		} else {
			$data['cardp'] = "";
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
		} else if ($data['plan']['product_short'] == 'JFC') {
			$data['insurable_options'] = $this->load->view('plan/detail_jes', $data, TRUE);
		} else {
			$data['insurable_options'] = $this->load->view('plan/detail_other', $data, TRUE);
		}

		$this->load->model('payment_model');
		$data['payment'] = '';
		if ($plan['payment_id']) {
			$data['payment'] = $this->payment_model->get_payment_by_id($plan['payment_id']);
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
			unset($plan['spouse']);
			unset($plan['student_id']);
			unset($plan['payment_id']);
			unset($plan['commission_payment_id']);
			unset($plan['payinfo']);
			unset($plan['note']);
			unset($plan['ip']);
		}
		$this->form($plan);
	}

	public function edit($plan_id=0) {
		$this->load->model('plan_model');
		$plan = $this->plan_model->get_plan_by_id($plan_id);
		$this->form($plan);
	}
}
