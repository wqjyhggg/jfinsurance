<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Box\Spout\Common\Type;
use Box\Spout\Writer\WriterFactory;

class Plan extends MY_Controller {
	private $merchentID = "300203256";
	private $apikey = "634E4AFd7Eda4dcEaA2976207A7C92bb";
	public $error;
	public $page_limit = 20;
	public $toppackagename = array(
			'all_inclusive' => "All Inclusive plan",
			'single_medical_plan' => "Single medical plan",
			'annual_plan' => "Annual plan",
			'optional_plan' => "Optional plan",
	);
	
	
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
		$data['student_id'] = $this->input->get_post('student_id');
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
								&& empty($data['student_id']) 
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

		if ($this->input->get_post('search')) {
			/* plan_id, policy, batch_number, full_name, status_id, effective_date, firstname(customer), lastname(customer), agent_firstname, agent_lastname */
			$sArr = $this->input->post();
			if (empty($sArr)) {
				$sArr = $this->input->get();
			}
			$data['plan_list'] = $this->plan_model->plan_search($sArr, $this->page_limit, $this->input->get('per_page'));
			$data['plan_total'] = $this->plan_model->plan_search_count($sArr);
			$this->session->set_userdata('policy_search', json_encode($sArr));
		} else if ($this->input->get('q')) {
			$para = array('policy_match' => $this->input->get('q'));
			$data['plan_list'] = $this->plan_model->plan_search($para, $this->page_limit, $this->input->get('per_page'));
			$data['plan_total'] = $this->plan_model->plan_search_count($para);
			$this->session->set_userdata('policy_search', json_encode($para));
		} else {
			$data['plan_list'] = array();
			$data['plan_total'] = 0;
			$this->session->unset_userdata ( 'policy_search' );
		}
		$data['search_url'] = current_url ();
		$data['add_url'] = base_url ( "plan/add" );
		$data['export_list'] = base_url ( "plan/export_list" );
		$data['edit_url'] = base_url ( "plan/edit" ) . "/";
		$data['copy_url'] = base_url ( "plan/copy" ) . "/";
		$data['renewal_url'] = base_url ( "plan/renewal" ) . "/";
		$data['pdf_url'] = base_url ( "plan/pdf" ) . "/";
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
		

		$data['export_logo_url'] = base_url('plan/exportlogo') . "/";
		$data['export_price_url'] = base_url('plan/exportprice') . "/";
		$data['export_logo_price_option'] = FALSE;
		if (($beuser['user_group_id'] < 100) || ($beuser['user_id'] == 3744)) $data['export_logo_price_option'] = TRUE;
			
		$this->session->set_userdata ( 'withlogo', 1);
		if ($beuser['user_group_id'] != 103) {
			$this->session->set_userdata ( 'withprice', 1);
		} else {
			$this->session->set_userdata ( 'withprice', 0);
		}

		$this->load->library('pagination');
		$config['base_url'] = site_url('plan');
		$config['enable_query_strings'] = TRUE;
		$config['page_query_string'] = TRUE;
		$config['per_page'] = $this->page_limit;
		$config['total_rows'] = $data['plan_total'];
		$config['first_tag_open'] = "<li class='cpagination'>";
		$config['first_tag_close'] = "</li>";
		$config['last_tag_open'] = "<li class='cpagination'>";
		$config['last_tag_close'] = "</li>";
		$config['next_tag_open'] = "<li class='cpagination'>";
		$config['next_tag_close'] = "</li>";
		$config['prev_tag_open'] = "<li class='cpagination'>";
		$config['prev_tag_close'] = "</li>";
		$config['cur_tag_open'] = "<li class='cpagination' style='background-color:#ddd'>";
		$config['cur_tag_close'] = "</li>";
		$config['num_tag_open'] = "<li class='cpagination'>";
		$config['num_tag_close'] = "</li>";
		if (count($this->input->get()) > 0) {
			$getArr = $this->input->get();
			if (isset($getArr['per_page'])) {
				unset($getArr['per_page']);
			}
			$config['suffix'] = '&' . http_build_query($getArr, '', "&");
		}
		
		$this->pagination->initialize($config); // initiaze pagination config
		
		$data ['pagination'] = $this->pagination->create_links(); // create pagination links


		$data['title_txt'] = 'Policy';
		$data['top_menu'] = $this->menu_model->load_top_menu();
		$data['menu'] = $this->menu_model->load_meun();
		$data['html_model'] = $this->html_model;

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
		$plan_total = 0;
		if ($policy_search) {
			$sArr = json_decode($policy_search, TRUE);
			$plan_total = $this->plan_model->plan_search_count($sArr);
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
		$tmpfname = "/tmp/jf_policy.xlsx";
		$w->openToBrowser("Policy" . date('Ymd') . ".xlsx");
		//$w->openToFile($tmpfname);
		$w->addRow($kArr);
		for ($i = 0; $i < $plan_total; $i += $this->page_limit) {
			$policies = $this->plan_model->plan_export_search($sArr, TRUE, $this->page_limit, $i);
			foreach($policies as $p) {
				$para = array();
				foreach($kArr as $k) {
					$para[] = empty($p[$k]) ? '' : $p[$k];
				}
				$w->addRow($para);
			}
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
		$apply_date = $this->input->post('apply_date');
		$older_than_21 = 0;
		$today = date("Y-m-d");
		for ($i = 1 ; $i < 9; $i++) {
			if (empty($this->input->post('gender_' . $i)) || empty($this->input->post('firstname_' . $i)) || empty($this->input->post('lastname_' . $i)) || empty($this->input->post('birthday_' . $i))) {
				continue;
			}
			$days = $this->product_model->getDays($this->input->post('birthday_' . $i), $apply_date);
			if ($days < 15) {
				$this->error['error_birthday_' . $i] = "Customer age must be old than 15 days";
			}
			
			$years = $this->product_model->getYears($apply_date, $this->input->post('birthday_' . $i));
			if ($years > 60) {
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
	
	function top_update_valid($plan, $beuser) {
		if ($plan['status_id'] < 2) {
			// not available yet
			return ;
		}
		$nowtm = strtotime(date('Y-m-d'). " 23:59:59");
		$effective_date = $this->input->post('effective_date');
		$effectivetm = strtotime($effective_date);
		/*
		if (($effective_date != $plan['effective_date']) && ($effective_date < $nowtm)) {
			$this->error['error_effective_date'] = "Can't back Effective Date before today";
			return ;
		}
		*/
		$nowdate = date('Y-m-d');
		if (($plan['effective_date'] <= $nowdate) && ($beuser['user_group_id'] != 1)) {
			// After effective
			$expiry_date = $this->input->post('expiry_date');
			if ($expiry_date != $plan['expiry_date']) {
				if ($plan['package'] == 'single_medical_plan') {
					if ($expiry_date < $plan['expiry_date']) {
						$this->error['error_expiry_date'] = "Expiry Date can't be back after plan effected";
					}
				} else {
					$this->error['error_expiry_date'] = "Expiry Date can't be changed";
				}
			}
			if ($this->input->post('stable_condition') != $plan['stable_condition']) {
				$this->error['error_stable_condition'] = "Stable Condition can't be changed after plan effective";
			}
			if ($this->input->post('package') != $plan['package']) {
				$this->error['error_message'] = "Package can't be changed after plan effective";
			}

			$ad_and_d_ck = $this->input->post('ad_and_d_ck');
			if ($ad_and_d_ck) $ad_and_d_ck = 1;
			$flight_accident_ck = $this->input->post('flight_accident_ck');
			if ($flight_accident_ck) $flight_accident_ck = 1;
			if ((int)($this->input->post('free_cancel') != (int)$plan['free_cancel']) ||
				($this->input->post('annual_plan_days') != $plan['annual_plan_days']) ||
				((int)$ad_and_d_ck != (int)$plan['ad_and_d_ck']) ||
				($this->input->post('ad_and_d_insured') != $plan['ad_and_d_insured']) ||
				((int)$flight_accident_ck != (int)$plan['flight_accident_ck']) ||
				($this->input->post('flight_accident_insured') != $plan['flight_accident_insured']) ||
				($this->input->post('trip_cancellation_ck') != $plan['trip_cancellation_ck']) ||
				($this->input->post('trip_cancellation_insured') != $plan['trip_cancellation_insured']) ||
				($this->input->post('questionnaire') != $plan['questionnaire']) ||
				((int)$this->input->post('question1') != (int)$plan['question1']) ||
				((int)$this->input->post('question2') != (int)$plan['question2']) ||
				((int)$this->input->post('question3') != (int)$plan['question3']) ||
				((int)$this->input->post('question4') != (int)$plan['question4']) ||
				((int)$this->input->post('question5') != (int)$plan['question5']) ) {
				$this->error['error_message'] = "Plan can't be changed after plan effective";
			}
		} else {
			// before 
			$ad_and_d_ck = $this->input->post('ad_and_d_ck');
			if ($ad_and_d_ck) $ad_and_d_ck = 1;
			if ($plan['ad_and_d_ck'] && (($ad_and_d_ck != $plan['ad_and_d_ck']) || ($this->input->post('ad_and_d_insured') < $plan['ad_and_d_insured']))) {
				$this->error['error_message'] = "AD & D Plan only be changed to add sum inusured";
			}
			
			$flight_accident_ck = $this->input->post('flight_accident_ck');
			if ($flight_accident_ck) $flight_accident_ck = 1;
			if ($plan['flight_accident_ck'] && (($flight_accident_ck != $plan['flight_accident_ck']) || ($this->input->post('flight_accident_insured') < $plan['flight_accident_insured']))) {
				$this->error['error_message'] = "Flight Accident Plan only be changed to add sum inusured";
			}

			if ($plan['trip_cancellation_ck'] && (($this->input->post('trip_cancellation_ck') != $plan['trip_cancellation_ck']) || ($this->input->post('trip_cancellation_insured') < $plan['trip_cancellation_insured']))) {
				$this->error['error_message'] = "Trip Cancellation Plan only be changed to add sum inusured";
			}

			if (($plan['package'] == 'annual_plan') && ($this->input->post('annual_plan_days') < $plan['annual_plan_days'])) {
				$this->error['error_message'] = "Annual Plan can't be changed to add more days";
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
		if (($product_short != 'TOP') && empty($arrival_date)) {	// 2015-01-01
			$this->error['error_arrival_date'] = 'Confirm Arrival Date';
		/*	
		} else if (($product_short == 'JFR') && ($arrivaltm < 1420070400)) {	// 2015-01-01
			$this->error['error_arrival_date'] = "Arrival Date is too early";
		*/
		}
		$effective_date = $this->input->post('effective_date');
		$effectivetm = strtotime($effective_date);
		if (empty($effective_date) || ($effectivetm < 1466555500)) {
			$this->error['error_effective_date'] = 'Confirm Effective Date';
		}
		
		if ($product_short == 'JESP') {
			if (empty($this->input->post('student_id'))) {
				$this->error['error_student_id'] = 'Student Name is Required';
			}
			if (empty($this->input->post('institution'))) {
				$this->error['error_institution'] = 'School Name is Required';
			}
		}
		if (($product_short == 'JES') || ($product_short == 'JFPL') || ($product_short == 'JFSL')) {
			if (empty($this->input->post('institution'))) {
				$this->error['error_institution'] = 'School Name is Required';
			}
		}
		if ($product_short == 'BHS') {
			if (empty($this->input->post('student_id'))) {
				$this->error['error_student_id'] = 'Student Name is Required';
			}
		}
		if (($product_short != 'TOP') && ($arrivaltm > $effectivetm)) {
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
		if (empty($this->input->post('firstname')) || preg_match('/[^\x20-\x7f]/', $this->input->post('firstname'))) {
			$this->error['error_firstname'] = 'Firstname is Required (No special character is allowed)';
		}
		if (empty($this->input->post('lastname')) || preg_match('/[^\x20-\x7f]/', $this->input->post('lastname'))) {
			$this->error['error_lastname'] = 'Lastname is Required (No special character is allowed)';
		}
		if (empty($this->input->post('birthday'))) {
			$this->error['error_birthday'] = 'Birthday is Required';
		}
		if (empty($this->input->post('street_number')) || preg_match('/[^\x20-\x7f]/', $this->input->post('street_number'))) {
			$this->error['error_street_number'] = 'Street number is Required (No special character is allowed)';
		}
		if (empty($this->input->post('street_name')) || preg_match('/[^\x20-\x7f]/', $this->input->post('street_name'))) {
			$this->error['error_street_name'] = 'Street name is Required (No special character is allowed)';
		}
		if (empty($this->input->post('city')) || preg_match('/[^\x20-\x7f]/', $this->input->post('city'))) {
			$this->error['error_city'] = 'City Required (No special character is allowed)';
		}
		if (empty($this->input->post('postcode')) || preg_match('/[^\x20-\x7f]/', $this->input->post('postcode'))) {
			$this->error['error_postcode'] = 'Postcode is Required (No special character is allowed)';
		}
		if (empty($this->input->post('phone1'))) {
			$this->error['error_phone1'] = 'Phone1 is Required';
		}
		if (empty($this->input->post('contact_email'))) {
			$this->error['error_contact_email'] = 'Contact email is Required';
		}
		if (!empty($this->input->post('isfamilyplan')) && (empty($this->input->post('birthday_1')) || empty($this->input->post('firstname_1')) || empty($this->input->post('lastname_1')))) {
			$this->error['error_message'] = 'Please input family / group member information';
		}
		if (($product_short == 'OPL') || ($product_short == 'JFVTC') || ($product_short == 'JFR')) {
			$apply_date = $this->input->post('apply_date');
			$birthday = $this->input->post('birthday');
			$days = $this->product_model->getDays($birthday, $apply_date);
			if ($days < 15) {
				$this->error['error_message'] = "Customer age must be old than 15 days ".$days . "[".$birthday."][".$apply_date."]";
			}
			if (empty($this->input->post('stable_condition'))) {
				$this->error['error_stable_condition'] = 'Please select pre-existing condition coverage';
			}

			//if ($product_short == 'JFR') {
				$stable_condition = $this->input->post('stable_condition');
				if ($stable_condition == 2) {
					$stable_condition_confirm = $this->input->post('stable_condition_confirm');
					if (empty($stable_condition_confirm)) {
						$this->error['error_stable_condition_confirm'] = 'You must confirm this condition for your selection.';
					}
				}
			//}

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
				$this->error['error_message'] = "Customer age must be less than 69 years old";
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
		} else if (($product_short == 'JES') || ($product_short == 'JFPL') || ($product_short == 'JFSL') || ($product_short == 'JESP') || ($product_short == 'JFE') || ($product_short == 'JFS') || ($product_short == 'BHS') || ($product_short == 'JFC') || ($product_short == 'JFP')) {
			$apply_date = $this->input->post('apply_date');
			$birthday = $this->input->post('birthday');
			$years = $this->product_model->getYears($apply_date, $birthday);
			if ($product_short == 'JFS') {
        $stable_condition_confirm = $this->input->post('stable_condition_confirm');
        if (empty($stable_condition_confirm)) {
          $this->error['error_stable_condition_confirm'] = 'You must confirm you known about NOT cover any Pre-Existing Medical Condition(s).';
        }
			}
			if ($years > 69) {
				$this->error['error_message'] = "Customer age must be less than 69 years old";
			} else {
				for ($i = 1; $i < 9; $i++) {
					$birthday = $this->input->post('birthday_'.$i);
					if (empty($birthday)) break;
					$years = $this->product_model->getYears($apply_date, $birthday);
					if (($product_short == 'JES') || ($product_short == 'JFPL') || ($product_short == 'JFSL') || ($product_short == 'JESP') || ($product_short == 'JFE') || ($product_short == 'JFS') || ($product_short == 'BHS')) {
						if ($years > 59) {
							$this->error['error_message'] = "All family member's age must be less than 59 years old";
							break;
						}
					} else {
						if ($years > 69) {
							$this->error['error_message'] = "All family member's age must be less than 69 years old";
							break;
						}
					}
				}
			}
			$years = $this->product_model->getYears($apply_date, $this->input->post('birthday'));
			if (($product_short == 'JES') || ($product_short == 'JFPL') || ($product_short == 'JFSL') || ($product_short == 'JESP') || ($product_short == 'JFE') || ($product_short == 'JFS') || ($product_short == 'BHS')) {
				if ($years < 4) {
					$this->error['error_message'] = "Customer age must be older than 4 years old";
				} else {
					for ($i = 1; $i < 9; $i++) {
						$birthday = $this->input->post('birthday_'.$i);
						if (empty($birthday)) break;
						$years = $this->product_model->getYears($apply_date, $birthday);
						if ($years < 4) {
							$this->error['error_message'] = "All family member's age must be older than 4 years old";
							break;
						}
					}
				}
			}
		} else if ($product_short == 'TOP') {
			$tdata = $this->product_model->get_top_premium($this->input->post());
			$ipremium = (float)$this->input->post('premium');
			$tpremium = (float)$tdata['premium'];
			if (empty($tdata['status']) || ($tdata['status'] != 'OK') || (abs($ipremium - $tpremium) > 0.001)) {
				$this->error['error_message'] = "Peimum has been changed, Please confirm and submit again";
			}
		}
		
		return empty($this->error);
	}
	
	function verify_claims($plan_id) {
		$plan = $this->plan_model->get_plan_by_id($plan_id);

		if ($plan['claim_flag'] >= 2) {
			if ($plan['claim_allow_by'] < 1) {
				$this->error['error_claim'] = 'The insured may have a previous claim that is affecting the policy issuance or renewal. Please contact JF staff for further assistance 905-707-1512';
			}
			return;
		}

		$customers = $this->plan_model->get_plan_customers_by_id($plan_id);
		foreach ($customers as $customer) {
			$vrecords = $this->plan_model->verify_customer($customer['firstname'], $customer['lastname'], $customer['birthday']);
			$claim_amount = 0;
			$case_amount = 0;
			if ($vrecords['status'] == 'OK') {
				foreach ($vrecords['cases'] as $case) {
					$case_amount += (float)$case['amount'];
				}
				foreach ($vrecords['claims'] as $claim) {
					$claim_amount += (float)$claim['amount'];
				}
			}
			if (empty($claim_amount) && empty($case_amount)) {
				// continue check next customer
				continue;
			} else if (($claim_amount <= 2000) && ($case_amount <= 2000)) {
				$plan = $this->plan_model->update($plan_id, array('claim_flag' => 1));
				// $this->error['error_claim'] = "Warning: The insured(s) have had previous claim(s). Please check the policy eligibility and any pre-existing conditions with insured(s). " . $customer['firstname'] . " " . $customer['lastname'] . "(" . $customer['birthday'] . ")";
			} else /* if (($claim_amount > 2000) || ($case_amount > 2000)) */ {
				$plan = $this->plan_model->update($plan_id, array('claim_flag' => 2));
				$this->error['error_claim'] = 'The insured may have a previous claim that is affecting the policy issuance or renewal. Please contact JF staff for further assistance 905-707-1512';
				break;
			}
		}
		return;
	}
	
	function form($plan=array()) {
		$beuser = $this->func_model->verify_login(TRUE, TRUE);
		$this->load->model('customer_model');
		
		$this->load->model('status_model');
		$this->load->model('product_model');
		$this->load->model('plan_model');
		$this->load->model('payment_model');
		
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
				$planold = $this->plan_model->get_plan_by_id($plan_id);
				$new_status_id = $this->input->post('status_id');
				if (($planold['status_id'] >= 2) && ($new_status_id < 2)) {
					// This is second tab or windows, no change
					redirect("plan/term/" . $plan_id);
				}
				if ($planold['product_short'] == 'TOP') {
					if (!empty($planold) && ($planold['status_id'] >= 2) && ($beuser['user_group_id'] > 100)) {
						redirect("plan/term/" . $plan_id);
					} else {
						$this->top_update_valid($planold, $beuser);
					}
				}
				if (empty($this->error)) {
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

						if ((($planold['product_short'] == 'OPL') || ($planold['product_short'] == 'JFVTC') || ($planold['product_short'] == 'JFR')) && ((($planold['sum_insured'] >= 100000) && ($planold['totaldays'] >= 365)) || (($plan['sum_insured'] >= 100000) && ($plan['totaldays'] >= 365)))) {
							if (($plan['sum_insured'] >= 100000) && ($plan['totaldays'] >= 365)) {
								if (($planold['effective_date'] != $plan['effective_date']) || ($planold['totaldays'] != $plan['totaldays']) || ($planold['premium'] != $plan['premium']) || ($planold['expiry_date'] != $plan['expiry_date']) ) {
									// Super visa changed effective date
									$this->payment_model->adjust_commission_added_date($plan_id, $plan['effective_date'], FALSE);
									$para = array(
											'plan_id' => $plan_id, 
											'customer_id' => $plan['customer_id'], 
											'payment_id' => $plan['commission_payment_id'], 
											'message' => $this->payment_model->logstr, 
											'systemlog' => $this->payment_model->sqlstr
									);
									$this->log_model->activity('plan', $para);
								}
							} else {
								// No more super visa, change payment data to today
								$this->payment_model->adjust_commission_added_date($plan_id, date('Y-m-d'), FALSE);
								$para = array(
										'plan_id' => $plan_id, 
										'customer_id' => $plan['customer_id'], 
										'payment_id' => $plan['commission_payment_id'], 
										'message' => $this->payment_model->logstr, 
										'systemlog' => $this->payment_model->sqlstr
								);
								$this->log_model->activity('plan', $para);
							}
						}
					}
				}
			}
			if (empty($this->error)) {
				if ($plan_id) {
					$this->verify_claims($plan_id);
				}
			}
			if (empty($this->error)) {
				if ($plan_id) {
					redirect("plan/term/" . $plan_id);
				}
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
			// For renewal plan
			$customer = array();
			$customers = array();
			if (!empty($plan['customer_id'])) {
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
		$data['policy'] = '';
		if (empty($plan) && $data['plan_id']) {
			$plan = $this->plan_model->get_plan_by_id($data['plan_id']);
		}
		
		if ($plan && isset($plan['status_id']) && ($plan['status_id'] > 1) && ($plan['claim_flag'] < 1)&& $this->session->userdata('vsuser')) {
			// user can't change anything after sold
			redirect('plan/detail/'.$plan['plan_id']);
		}
		
		if ($plan && isset($plan['policy'])) {
			$data['policy'] = $plan['policy']; 
		}
		
		if ($this->input->post('product_short')) {
			$data['product_short'] = $this->input->post('product_short'); 
		} else if (isset($plan['product_short'])) {
			$data['product_short'] = $plan['product_short'];
		} else {
			redirect(base_url('plan'));
		}
		
		if ($this->input->post('user_id')) {
			$data['user_id'] = $this->input->post('user_id'); 
		} else if (isset($plan['user_id'])) {
			$data['user_id'] = $plan['user_id'];
		} else {
			$data['user_id'] = $beuser['user_id'];
		}
		$data['beuser_user_id'] = $beuser['user_id'];
		
		if ($this->input->post('status_id')) {
			$data['status_id'] = $this->input->post('status_id'); 
		} else if (isset($plan['status_id'])) {
			$data['status_id'] = $plan['status_id'];
		} else {
			$data['status_id'] = 1;
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
		if ($this->input->post('batch_number')) {
			$data['batch_number'] = $this->input->post('batch_number'); 
		} else if (isset($plan['batch_number'])) {
			$data['batch_number'] = $plan['batch_number'];
		} else {
			$data['batch_number'] = 0;
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
			$data['sum_insured'] = 0;
		}
		if ($this->input->post('premium')) {
			$data['premium'] = $this->input->post('premium'); 
			if ($data['product_short'] == 'TOP') {
				$tdata = $this->product_model->get_top_premium($this->input->post());
				if (empty($tdata['status']) || ($tdata['status'] != 'OK')) {
					$data['premium'] = $tdata['premium'];
				}
			}
		} else if (isset($plan['premium'])) {
			$data['premium'] = $plan['premium'];
		} else {
			$data['premium'] = 0;
		}
		if ($this->input->post('tax')) {
			$data['tax'] = $this->input->post('tax');
		} else if (isset($plan['tax'])) {
			$data['tax'] = $plan['tax'];
		} else {
			$data['tax'] = 0;
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
		if ($this->input->post('stable_condition_confirm')) {
			$data['stable_condition_confirm'] = $this->input->post('stable_condition_confirm'); 
		} else if (isset($plan['stable_condition_confirm'])) {
			$data['stable_condition_confirm'] = $plan['stable_condition_confirm'];
		} else {
			$data['stable_condition_confirm'] = 0;
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
		if ($data['product_short'] == 'TOP') {
			$max_member = 25;
		} else {
			$max_member = 9;
		}
		$data['max_member'] = $max_member;
		$data['cur_max_member'] = 0;
		for ($i = 1; $i < $max_member; $i++) {
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
			if (!empty($data['birthday_'.$i])) {
				$data['cur_max_member'] += 1;
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
		if (isset($plan['claim_flag'])) {
			$data['claim_flag'] = $plan['claim_flag'];
		} else {
			$data['claim_flag'] = 0;
		}
		if ($this->input->post('claim_allow_by')) {
			$data['claim_allow_by'] = $this->input->post('claim_allow_by');
		} else if (isset($plan['claim_allow_by'])) {
			$data['claim_allow_by'] = $plan['claim_allow_by'];
		} else {
			$data['claim_allow_by'] = '';
		}
		if ($this->input->post('claim_allow_note')) {
			$data['claim_allow_note'] = $this->input->post('claim_allow_note');
		} else if (isset($plan['claim_allow_note'])) {
			$data['claim_allow_note'] = $plan['claim_allow_note'];
		} else {
			$data['claim_allow_note'] = '';
		}
		if (isset($plan['free_cancel'])) {
			$data['free_cancel'] = $plan['free_cancel'];
		} else {
			$data['free_cancel'] = '';
		}
		
		// For TOP plan
		if ($this->input->post('ad_and_d_ck')) {
			$data['ad_and_d_ck'] = 1;
		} else if (isset($plan['ad_and_d_ck'])) {
			$data['ad_and_d_ck'] = $plan['ad_and_d_ck'];
		} else {
			$data['ad_and_d_ck'] = 0;
		}
		if ($this->input->post('ad_and_d_insured')) {
			$data['ad_and_d_insured'] = $this->input->post('ad_and_d_insured');
		} else if (isset($plan['ad_and_d_insured'])) {
			$data['ad_and_d_insured'] = $plan['ad_and_d_insured'];
		} else {
			$data['ad_and_d_insured'] = 0;
		}
		if ($this->input->post('flight_accident_ck')) {
			$data['flight_accident_ck'] = 1;
		} else if (isset($plan['flight_accident_ck'])) {
			$data['flight_accident_ck'] = $plan['flight_accident_ck'];
		} else {
			$data['flight_accident_ck'] = 0;
		}
		if ($this->input->post('flight_accident_insured')) {
			$data['flight_accident_insured'] = $this->input->post('flight_accident_insured');
		} else if (isset($plan['flight_accident_insured'])) {
			$data['flight_accident_insured'] = $plan['flight_accident_insured'];
		} else {
			$data['flight_accident_insured'] = 0;
		}
		if ($this->input->post('trip_cancellation_ck')) {
			$data['trip_cancellation_ck'] = 1;
		} else if (isset($plan['trip_cancellation_ck'])) {
			$data['trip_cancellation_ck'] = $plan['trip_cancellation_ck'];
		} else {
			$data['trip_cancellation_ck'] = 0;
		}
		if ($this->input->post('trip_cancellation_insured')) {
			$data['trip_cancellation_insured'] = $this->input->post('trip_cancellation_insured');
		} else if (isset($plan['trip_cancellation_insured'])) {
			$data['trip_cancellation_insured'] = $plan['trip_cancellation_insured'];
		} else {
			$data['trip_cancellation_insured'] = '0';
		}
		if ($this->input->post('package')) {
			$data['package'] = $this->input->post('package');
		} else if (isset($plan['package'])) {
			$data['package'] = $plan['package'];
		} else {
			$data['package'] = '';
		}
		if ($this->input->post('annual_plan_days')) {
			$data['annual_plan_days'] = $this->input->post('annual_plan_days');
		} else if (isset($plan['annual_plan_days'])) {
			$data['annual_plan_days'] = $plan['annual_plan_days'];
		} else {
			$data['annual_plan_days'] = '0';
		}
		if ($this->input->post('free_cancel')) {
			$data['free_cancel'] = 1;
		} else if (isset($plan['free_cancel'])) {
			$data['free_cancel'] = $plan['free_cancel'];
		} else {
			$data['free_cancel'] = 0;
		}
		if ($this->input->post('questionnaire')) {
			$data['questionnaire'] = $this->input->post('questionnaire');
		} else if (isset($plan['questionnaire'])) {
			$data['questionnaire'] = $plan['questionnaire'];
		} else {
			$data['questionnaire'] = '0';
		}
		if ($this->input->post('question1')) {
			$data['question1'] = $this->input->post('question1');
		} else if (isset($plan['question1'])) {
			$data['question1'] = $plan['question1'];
		} else {
			$data['question1'] = '0';
		}
		if ($this->input->post('question1_lung')) {
			$data['question1_lung'] = $this->input->post('question1_lung');
		} else if (isset($plan['question1_lung'])) {
			$data['question1_lung'] = $plan['question1_lung'];
		} else {
			$data['question1_lung'] = '0';
		}
		if ($this->input->post('question1_diabets')) {
			$data['question1_diabets'] = $this->input->post('question1_diabets');
		} else if (isset($plan['question1_diabets'])) {
			$data['question1_diabets'] = $plan['question1_diabets'];
		} else {
			$data['question1_diabets'] = '0';
		}
			if ($this->input->post('question1_heart')) {
			$data['question1_heart'] = $this->input->post('question1_heart');
		} else if (isset($plan['question1_heart'])) {
			$data['question1_heart'] = $plan['question1_heart'];
		} else {
			$data['question1_heart'] = '0';
		}
		if ($this->input->post('question2')) {
			$data['question2'] = $this->input->post('question2');
		} else if (isset($plan['question2'])) {
			$data['question2'] = $plan['question2'];
		} else {
			$data['question2'] = '0';
		}
		if ($this->input->post('question3')) {
			$data['question3'] = $this->input->post('question3');
		} else if (isset($plan['question3'])) {
			$data['question3'] = $plan['question3'];
		} else {
			$data['question3'] = '0';
		}
		if ($this->input->post('question3_bowel')) {
			$data['question3_bowel'] = $this->input->post('question3_bowel');
		} else if (isset($plan['question3_bowel'])) {
			$data['question3_bowel'] = $plan['question3_bowel'];
		} else {
			$data['question3_bowel'] = 'N';
		}
		if ($this->input->post('question3_cancer')) {
			$data['question3_cancer'] = $this->input->post('question3_cancer');
		} else if (isset($plan['question3_cancer'])) {
			$data['question3_cancer'] = $plan['question3_cancer'];
		} else {
			$data['question3_cancer'] = 'N';
		}
		if ($this->input->post('question3_diabetes')) {
			$data['question3_diabetes'] = $this->input->post('question3_diabetes');
		} else if (isset($plan['question3_diabetes'])) {
			$data['question3_diabetes'] = $plan['question3_diabetes'];
		} else {
			$data['question3_diabetes'] = 'N';
		}
		if ($this->input->post('question3_diverticu')) {
			$data['question3_diverticu'] = $this->input->post('question3_diverticu');
		} else if (isset($plan['question3_diverticu'])) {
			$data['question3_diverticu'] = $plan['question3_diverticu'];
		} else {
			$data['question3_diverticu'] = 'N';
		}
		if ($this->input->post('question3_gerd')) {
			$data['question3_gerd'] = $this->input->post('question3_gerd');
		} else if (isset($plan['question3_gerd'])) {
			$data['question3_gerd'] = $plan['question3_gerd'];
		} else {
			$data['question3_gerd'] = 'N';
		}
		if ($this->input->post('question3_heart')) {
			$data['question3_heart'] = $this->input->post('question3_heart');
		} else if (isset($plan['question3_heart'])) {
			$data['question3_heart'] = $plan['question3_heart'];
		} else {
			$data['question3_heart'] = 'N';
		}
		if ($this->input->post('question3_hyper')) {
			$data['question3_hyper'] = $this->input->post('question3_hyper');
		} else if (isset($plan['question3_hyper'])) {
			$data['question3_hyper'] = $plan['question3_hyper'];
		} else {
			$data['question3_hyper'] = 'N';
		}
		if ($this->input->post('question3_kidney')) {
			$data['question3_kidney'] = $this->input->post('question3_kidney');
		} else if (isset($plan['question3_kidney'])) {
			$data['question3_kidney'] = $plan['question3_kidney'];
		} else {
			$data['question3_kidney'] = 'N';
		}
		if ($this->input->post('question3_lung')) {
			$data['question3_lung'] = $this->input->post('question3_lung');
		} else if (isset($plan['question3_lung'])) {
			$data['question3_lung'] = $plan['question3_lung'];
		} else {
			$data['question3_lung'] = 'N';
		}
		if ($this->input->post('question3_peptic')) {
			$data['question3_peptic'] = $this->input->post('question3_peptic');
		} else if (isset($plan['question3_peptic'])) {
			$data['question3_peptic'] = $plan['question3_peptic'];
		} else {
			$data['question3_peptic'] = 'N';
		}
		
		if ($this->input->post('question4')) {
			$data['question4'] = $this->input->post('question4');
		} else if (isset($plan['question4'])) {
			$data['question4'] = $plan['question4'];
		} else {
			$data['question4'] = '0';
		}
		if ($this->input->post('question5')) {
			$data['question5'] = $this->input->post('question5');
		} else if (isset($plan['question5'])) {
			$data['question5'] = $plan['question5'];
		} else {
			$data['question5'] = '0';
		}
		
		$data['show_history'] = 0;
		if (empty($data['plan_id'])) {
			$data['submit'] = 'Next';
			$data['p_header'] = 'New Policy';
		} else {
			$data['p_header'] = 'Edit Policy';
			$data['submit'] = 'Update Policy';
			if ($beuser['user_group_id'] < 100) {
				$this->load->model('payment_model');
				$data['show_history'] = 1;
				$data['activelogs'] = $this->log_model->get_activity_by_plan_id($data['plan_id']);
				$data['payments'] = $this->payment_model->get_payment_by_plan_id($data['plan_id']);
        if ($data['status_id'] >= 2){
          $this->load->model('claim_model');
          $claims = $this->plan_model->verify_policy($data['policy']);
          $data['claims'] = (!empty($claims) && ($claims['status'] == 'OK')) ? $claims['claims'] : '';
        }
			}
		}
		$data['payhistory_url'] = base_url ( "plan/payhistory/" . $data['plan_id'] );
		$data['sum_insured_url'] = base_url ( "product/insured/" . $data['product_short'] );
		if (!empty($data['sum_insured'])) $data['sum_insured_url'] .= "/" . $data['sum_insured']; 
		$data['deductible_amount_url'] = base_url ( "product/deductible/" . $data['product_short'] );
		$data['deductible_amount_url_now'] = $data['deductible_amount_url'];
		if (!empty($data['deductible_amount'])) {
			$data['deductible_amount_url_now'] .= "/0" . $data['deductible_amount'];
		} else {
			$data['deductible_amount_url_now'] .= "/" . $data['deductible_amount'];
		}
		if (!empty($data['plan_id'])) {
			$data['deductible_amount_url_now'] .= "/" . $data['plan_id'];
		}
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
			$data['renewal_url'] = base_url ( "plan/renewal" . "/" . (int)$data['plan_id'] );
			if ((int)$data['status_id'] == 1) {
				$data['pay_url'] = base_url ( "plan/term/" . (int)$data['plan_id'] );
			} else {
				$data['pay_url'] = '';
			}
			$data['next_url'] = base_url ( "plan/term/" . (int)$data['plan_id'] );
		} else {
			$data['copy_url'] = '';
			$data['renewal_url'] = "";
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
		$data['revert_url'] = base_url ( "payment/revert" ) . "/";
		$data['makepay_url'] = base_url ( "payment/makepay" );

		$data['print_card_url'] = '';
		$data['print_receipt_url'] = '';
		$data['cancel_letter_url'] = '';
		$data['refund_letter_url'] = '';
		$data['pdf_url'] = base_url('plan/pdf/' . (empty($plan['plan_id']) ? '' : $plan['plan_id']));
		$data['export_logo_url'] = base_url('plan/exportlogo') . "/";
		$data['export_price_url'] = base_url('plan/exportprice') . "/";
		$data['export_logo_price_option'] = FALSE;
		if (($beuser['user_group_id'] < 100) || ($beuser['user_id'] == 3744)) $data['export_logo_price_option'] = TRUE;
		$this->session->set_userdata ( 'withlogo', 1);
		if ($beuser['user_group_id'] != 103) {
			$this->session->set_userdata ( 'withprice', 1);
		} else {
			$this->session->set_userdata ( 'withprice', 0);
		}
		
		if (!empty($plan) && !empty($plan['status_id']) && !empty($plan['plan_id']) && ($plan['status_id'] >= 2)) {
			if ($plan['status_id'] == 5) {
				// Cancel
				if (($plan['product_short'] != 'JUS') && ($plan['product_short'] != 'NUS')) {
					$data['cancel_letter_url'] = base_url('plan/cancelprint/' . $plan['plan_id']);
				}
			} else if ($plan['status_id'] == 6) {
				// Refund
				if (($plan['product_short'] != 'JUS') && ($plan['product_short'] != 'NUS')) {
					$data['refund_letter_url'] = base_url('plan/refundprint/' . $plan['plan_id']);
				}
			} else {
				$data['print_card_url'] = base_url('plan/card/' . $plan['plan_id']);
				$data['print_receipt_url'] = base_url('plan/receipt/' . $plan['plan_id']);
			}
		}
		
		$data['title_txt'] = 'Policy';
		if ($vsuser = $this->session->userdata('vsuser')) {
			$this->load->model('myhome_model');
			$myhome = $this->myhome_model->get_myhome($vsuser['user_id']);
			if (!empty($myhome['logo'])) {
				$data['myhomelogo'] = base_url('agent/img') . '/' . $myhome['logo'];
			}
			
			if (!empty($myhome['qr'])) {
				$data['myqr'] = base_url('agent/img') . '/' . $myhome['qr'];
			}
				
			$data['top_menu'] = '';
			$data['menu'] = '';
		} else {
			$data['top_menu'] = $this->menu_model->load_top_menu();
			$data['menu'] = $this->menu_model->load_meun();
		}
		$data['csrf'] = array (
				'name' => $this->security->get_csrf_token_name (),
				'value' => $this->security->get_csrf_hash ()
		);

		$data['do_user_id'] = 0;
		$douser = $this->session->userdata('user');
		if ($douser) {
			$data['do_user_id'] = $douser['user_id'];
		}
		if (isset($data['error_claim'])) {
			$data['next_url'] = '';
		}
		
		$data['isprocessplan'] = 1;
		$data['plan_cancel_date'] = '';
		$data['plan_refund_date'] = '';
		if (!empty($plan) && !empty($plan['status_id'])) {
			$this->load->model('payment_model');
			if ($plan['status_id'] == Plan_model::CANCEL) {
				$data['plan_cancel_date'] = $this->payment_model->get_cancel_date($plan['plan_id']);
			}
			if ($plan['status_id'] == Plan_model::REFUND) {
				$data['plan_refund_date'] = $plan['refund_date'];
			}
		}
		$data['html_model'] = $this->html_model;
		if ($data['product_short'] == 'TOP') {
			$data['toppackagename'] = $this->toppackagename;
			$data['premium_url'] = base_url ( "plan/gettoppremium" );
			$data['no_change'] = 0;
			if (!empty($plan) && !empty($plan['status_id']) && !empty($plan['plan_id']) && ($plan['status_id'] >= 2) && ($data['user_group_id'] > 100)) {
				$data['no_change'] = 1;
			}
			$this->load->common('plan/top/form', $data);
		} else {
			if (!empty($plan) && !empty($plan['status_id']) && ($plan['status_id'] > 1) && ($beuser['user_group_id'] > 100)) {
				if ($data['product_short'] == 'OPL') {
					$data['insurable_options'] = $this->load->view('plan/form_opl_agent', $data, TRUE);
				} else if (($data['product_short'] == 'JFVTC') || ($data['product_short'] == 'JFR')) {
					$data['insurable_options'] = $this->load->view('plan/form_opl_agent', $data, TRUE);
				} else if ($data['product_short'] == 'JUS') {
					$data['insurable_options'] = $this->load->view('plan/form_jus_agent', $data, TRUE);
				} else if ($data['product_short'] == 'NUS') {
					$data['insurable_options'] = $this->load->view('plan/form_jus_agent', $data, TRUE);
				} else if (($data['product_short'] == 'JES') || ($data['product_short'] == 'JFPL') || ($data['product_short'] == 'JFSL') || ($data['product_short'] == 'JESP') || ($data['product_short'] == 'JFE') || ($data['product_short'] == 'JFS') || ($data['product_short'] == 'BHS')) {
					$data['insurable_options'] = $this->load->view('plan/form_jes_agent', $data, TRUE);
				} else if (($data['product_short'] == 'JFC') || ($data['product_short'] == 'JFP')) {
					$data['insurable_options'] = $this->load->view('plan/form_jfc_agent', $data, TRUE);
				} else {
					$data['insurable_options'] = $this->load->view('plan/form_other_agent', $data, TRUE);
					$data['isprocessplan'] = 0;
				}
				
				$this->load->common('plan/form_agent', $data);
			} else {
				if ($data['product_short'] == 'OPL') {
					$data['insurable_options'] = $this->load->view('plan/form_opl', $data, TRUE);
				} else if (($data['product_short'] == 'JFVTC') || ($data['product_short'] == 'JFR')) {
					$data['insurable_options'] = $this->load->view('plan/form_opl', $data, TRUE);
				} else if ($data['product_short'] == 'JUS') {
					$data['insurable_options'] = $this->load->view('plan/form_jus', $data, TRUE);
				} else if ($data['product_short'] == 'NUS') {
					$data['insurable_options'] = $this->load->view('plan/form_jus', $data, TRUE);
				} else if (($data['product_short'] == 'JES') || ($data['product_short'] == 'JFPL') || ($data['product_short'] == 'JFSL') || ($data['product_short'] == 'JESP') || ($data['product_short'] == 'JFE') || ($data['product_short'] == 'JFS') || ($data['product_short'] == 'BHS')) {
					$data['insurable_options'] = $this->load->view('plan/form_jes', $data, TRUE);
				} else if (($data['product_short'] == 'JFC') || ($data['product_short'] == 'JFP')) {
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
	}
	
	function gettoppremium() {
		$beuser = $this->func_model->verify_login();
		$this->load->model('product_model');
		$data = array('status' => 'Fail', 'message' => '');
		// Remove Top from Sep 1
		if (0 && $this->input->post()) {
			$data = $this->product_model->get_top_premium($this->input->post());
			/*
			if ($this->form_valid()) {
				$data = $this->product_model->get_top_premium($this->input->post());
			} else {
				$data['message'] = "Please fill in required data"; 
				$data['error'] = $this->error;
			}
			*/
		}
		
		header('Content-Type: application/json');
		echo json_encode($data);
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
		$data['html_model'] = $this->html_model;
	
		$data['csrf'] = array (
				'name' => $this->security->get_csrf_token_name (),
				'value' => $this->security->get_csrf_hash ()
		);
	
		$this->load->view('plan/form_payment', $data);
	}
	
	function add() {
		$beuser = $this->func_model->verify_login(TRUE, TRUE);
		$product_short = $this->input->post_get('product_short');
		if (empty($product_short)) {
			redirect(base_url('production'));
		}
		$plan = array('product_short' => $product_short);
		return ($this->form($plan));
	}

	function term($plan_id=0) {
		$beuser = $this->func_model->verify_login(TRUE, TRUE); 
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
				if (empty($plan['agree'])) {
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
				}
				
				redirect('plan/detail/'.$plan_id);
			} else {
				$data['message'] = 'You need agree term and conditions to continue.';
			}
		}
		$data['plan_id'] = $plan_id;
		$data['agree'] = $plan['agree'];
		
		$data['action_url'] = base_url('plan/term');
		$data['title_txt'] = 'Policy';
		if ($vsuser = $this->session->userdata('vsuser')) {
			$this->load->model('myhome_model');
			$myhome = $this->myhome_model->get_myhome($vsuser['user_id']);
			if (!empty($myhome['logo'])) {
				$data['myhomelogo'] = base_url('agent/img') . '/' . $myhome['logo'];
			}
			
			if (!empty($myhome['qr'])) {
				$data['myqr'] = base_url('agent/img') . '/' . $myhome['qr'];
			}
				
			$data['top_menu'] = '';
			$data['menu'] = '';
		} else {
			$data['top_menu'] = $this->menu_model->load_top_menu();
			$data['menu'] = $this->menu_model->load_meun();
		}
		$data['csrf'] = array (
				'name' => $this->security->get_csrf_token_name (),
				'value' => $this->security->get_csrf_hash ()
		);
		$data['html_model'] = $this->html_model;
		
		$this->load->common('plan/term', $data);
	}

	private function credit_card_negative() {
		$this->load->model('plan_model');
		$this->load->model('plan_history_model');
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
		if (($plan['product_short'] == 'TOP') && ($plan['totalyears'] > 60)) {
			if ($commission_rate > 15) {
				$commission_rate -= 15;
			} else {
				$commission_rate = 0;
			}
		}
		if ($plan['product_short'] == 'TOP') {
			$commission_amount = ($premium - ($plan['tax'] * $premium / $plan['premium'])) * $commission_rate / 100.0;
		} else {
			$commission_amount = $premium * $commission_rate / 100.0;
		}
		$up_commission_rate = $this->product_model->get_up_commission_rate($plan['product_short']);
		$up_commission_amount = $premium * $up_commission_rate / 100.0;
				
		$dt['amount'] = $premium;
		$dt['rate'] = 100;
		$dt['pay_type'] = 'premium';
		$dt['premium_payment_id'] = 0;
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
		$dt['premium_payment_id'] = $payment_id;
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
		$dt['premium_payment_id'] = $payment_id;
		if (($plan['product_short'] == 'OPL') || ($plan['product_short'] == 'JFVTC') || ($plan['product_short'] == 'JFR')) {
			$nowtm = time();
			$efftm = strtotime($plan['effective_date']);
			if ($nowtm <= $efftm) {
				if (($plan['sum_insured'] >= 100000) && ($plan['totaldays'] >= 365)) {
					if ($this->payment_model->adjust_commission_added_date($plan_id, $plan['effective_date'])) {
						$para = array(
								'plan_id' => $plan_id,
								'customer_id' => $plan['customer_id'],
								'payment_id' => 0,
								'message' => 'adjust apply time to effective date : ' . $plan_id . ' [ ' . $plan['effective_date'] . ' ]',
								'systemlog' => $this->payment_model->sqlstr
						);
						$this->log_model->activity('commission', $para);
					}
					$dt['added'] = $plan['effective_date'];
				/* cause bug	
				} else {
					if ($this->payment_model->adjust_commission_added_back_date($plan_id, date('Y-m-d'))) {
						$para = array(
								'plan_id' => $plan_id,
								'customer_id' => $plan['customer_id'],
								'payment_id' => 0,
								'message' => 'adjust apply time to today : ' . $plan_id . ' [ ' . date('Y-m-d') . ' ]',
								'systemlog' => $this->payment_model->sqlstr
						);
						$this->log_model->activity('commission', $para);
					}
				*/
				}
			}
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

    $history_id = 0;
    if (($history = $this->plan_history_model->get_plan_history_by_plan_id($plan_id)) && ($history["actualrate"]>0)) {
      $history_id = $history["plan_history_id"];
    } else {
      // Add missing first record.
      if ($plan['status_id'] > 1) {
        $history_id = $this->plan_history_model->add($plan_id, $plan['status_id']);
      }
    }

    $payinfo = "Credit Card: paymount is negative";
		$para = array('payment_id' => $payment_id, 'payinfo' => $payinfo, 'commission_payment_id' => $commission_payment_id, 'status_id' => Plan_model::SOLD, 'policy' => $this->plan_model->get_policy_number($plan_id, 2));
    $this->plan_model->update($plan_id, $para);
    if ($history_id) {
      $this->plan_history_model->add_remove($history_id);
    }
    $this->plan_history_model->add($plan_id, Plan_model::SOLD);

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
		$this->load->model('plan_history_model');
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

			$card_number = preg_replace('#[^0-9]#', '', $card_number);
			$card_cvv = preg_replace('#[^0-9]#', '', $card_cvv);
			$card_number_len = strlen($card_number);
			$card_cvv_len = strlen($card_cvv);
			
			if (($card_number_len < 13) || ($card_number_len > 16)) {
				$this->error = 'Invalid Card Number';
			} else if (($card_cvv_len < 3) || ($card_cvv_len > 4)) {
				$this->error = 'Invalid Card Number CVV';
			} else {
				
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
			if (($plan['product_short'] == 'TOP') && ($plan['totalyears'] > 60)) {
				if ($commission_rate > 15) {
					$commission_rate -= 15;
				} else {
					$commission_rate = 0;
				}
			}
			if ($plan['product_short'] == 'TOP') {
				$commission_amount = ($premium - ($plan['tax'] * $premium / $plan['premium'])) * $commission_rate / 100.0;
			} else {
				$commission_amount = $premium * $commission_rate / 100.0;
			}
			$up_commission_rate = $this->product_model->get_up_commission_rate($plan['product_short']);
			$up_commission_amount = $premium * $up_commission_rate / 100.0;
					
			$dt['amount'] = $premium;
			$dt['rate'] = 100;
			$dt['pay_type'] = 'premium';
			$dt['premium_payment_id'] = 0;
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
			$dt['premium_payment_id'] = $payment_id;
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
			$dt['premium_payment_id'] = $payment_id;
			if (($plan['product_short'] == 'OPL') || ($plan['product_short'] == 'JFVTC') || ($plan['product_short'] == 'JFR')) {
				$nowtm = time();
				$efftm = strtotime($plan['effective_date']);
				if ($nowtm <= $efftm) {
					if (($plan['sum_insured'] >= 100000) && ($plan['totaldays'] >= 365)) {
						if ($this->payment_model->adjust_commission_added_date($plan_id, $plan['effective_date'])) {
							$para = array(
									'plan_id' => $plan_id,
									'customer_id' => $plan['customer_id'],
									'payment_id' => 0,
									'message' => 'adjust apply time to effective date : ' . $plan_id . ' [ ' . $plan['effective_date'] . ' ]',
									'systemlog' => $this->payment_model->sqlstr
							);
							$this->log_model->activity('commission', $para);
						}
						$dt['added'] = $plan['effective_date'];
					} else {
						if ($this->payment_model->adjust_commission_added_back_date($plan_id, date('Y-m-d'))) {
							$para = array(
									'plan_id' => $plan_id,
									'customer_id' => $plan['customer_id'],
									'payment_id' => 0,
									'message' => 'adjust apply time to today : ' . $plan_id . ' [ ' . date('Y-m-d') . ' ]',
									'systemlog' => $this->payment_model->sqlstr
							);
							$this->log_model->activity('commission', $para);
						}
					}
				}
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
          $history_id = 0;
          if (($history = $this->plan_history_model->get_plan_history_by_plan_id($plan_id)) && ($history["actualrate"]>0)) {
            $history_id = $history["plan_history_id"];
          } else {
            // Add missing first record.
            if ($plan['status_id'] > 1) {
              $history_id = $this->plan_history_model->add($plan_id, $plan['status_id']);
            }
          }
        
          $payinfo = "Credit Card: " . substr($card_number, 0, 5) . "xxx" . substr($card_number, -4) . " " . $card_name .  " " . $expiry_month . "/" . $expiry_year;
						
					$para = array('payment_id' => $payment_id, 'payinfo' => $payinfo, 'commission_payment_id' => $commission_payment_id, 'status_id' => Plan_model::PAID, 'policy' => $this->plan_model->get_policy_number($plan_id, 2));
					$this->plan_model->update($plan_id, $para);
					$para = array(
							'plan_id' => $plan_id,
							'customer_id' => $plan['customer_id'],
							'payment_id' => $payment_id,
							'message' => $this->plan_model->logstr,
							'systemlog' => $this->plan_model->sqlstr
					);
					$this->log_model->activity('plan', $para);
          if ($history_id) {
            $this->plan_history_model->add_remove($history_id);
          }
          $this->plan_history_model->add($plan_id, Plan_model::PAID);
					
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
				$this->error = 'Payment failed. Please verify your credit card info.';
			}
			} // number length check
		}
	}

	private function cash() {
		$this->load->model('plan_model');
		$this->load->model('plan_history_model');
		$this->load->model('product_model');
		$this->load->model('payment_model');
		
		$plan_id = $this->input->post('plan_id');
		$premium = preg_replace("/[^0-9\.-]/", "", $this->input->post('premium'));
		$premium = (float)$premium;
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
		if (($plan['product_short'] == 'TOP') && ($plan['totalyears'] > 60)) {
			if ($commission_rate > 15) {
				$commission_rate -= 15;
			} else {
				$commission_rate = 0;
			}
		}
		if ($plan['product_short'] == 'TOP') {
			$commission_amount = ($premium - ($plan['tax'] * $premium / $plan['premium'])) * $commission_rate / 100.0;
		} else {
			$commission_amount = $premium * $commission_rate / 100.0;
		}
		$up_commission_rate = $this->product_model->get_up_commission_rate($plan['product_short']);
		$up_commission_amount = $premium * $up_commission_rate / 100.0;
		
		$dt['amount'] = $premium;
		$dt['rate'] = 100;
		$dt['pay_type'] = 'premium';
		$dt['premium_payment_id'] = 0;
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
		$dt['premium_payment_id'] = $payment_id;
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
		$dt['premium_payment_id'] = $payment_id;
		if (($plan['product_short'] == 'OPL') || ($plan['product_short'] == 'JFVTC') || ($plan['product_short'] == 'JFR')) {
			$nowtm = time();
			$efftm = strtotime($plan['effective_date']);
			if ($nowtm <= $efftm) {
				if (($plan['sum_insured'] >= 100000) && ($plan['totaldays'] >= 365)) {
					if ($this->payment_model->adjust_commission_added_date($plan_id, $plan['effective_date'])) {
						$para = array(
								'plan_id' => $plan_id,
								'customer_id' => $plan['customer_id'],
								'payment_id' => 0,
								'message' => 'adjust apply time to effective date : ' . $plan_id . ' [ ' . $plan['effective_date'] . ' ]',
								'systemlog' => $this->payment_model->sqlstr
						);
						$this->log_model->activity('commission', $para);
					}
					$dt['added'] = $plan['effective_date'];
				} else {
					if ($this->payment_model->adjust_commission_added_back_date($plan_id, date('Y-m-d'))) {
						$para = array(
								'plan_id' => $plan_id,
								'customer_id' => $plan['customer_id'],
								'payment_id' => 0,
								'message' => 'adjust apply time to today : ' . $plan_id . ' [ ' . date('Y-m-d') . ' ]',
								'systemlog' => $this->payment_model->sqlstr
						);
						$this->log_model->activity('commission', $para);
					}
				}
			}
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

    $history_id = 0;
    if (($history = $this->plan_history_model->get_plan_history_by_plan_id($plan_id)) && ($history["actualrate"]>0)) {
      $history_id = $history["plan_history_id"];
    } else {
      // Add missing first record.
      if ($plan['status_id'] > 1) {
        $history_id = $this->plan_history_model->add($plan_id, $plan['status_id']);
      }
    }

		$para = array('payment_id' => $payment_id, 'payinfo' => $payinfo, 'commission_payment_id' => $commission_payment_id, 'status_id' => Plan_model::SOLD, 'policy' => $this->plan_model->get_policy_number($plan_id, 2));
		$this->plan_model->update($plan_id, $para);
    if ($history_id) {
      $this->plan_history_model->add_remove($history_id);
    }
    $this->plan_history_model->add($plan_id, Plan_model::SOLD);

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
		$this->load->model('plan_history_model');
		$this->load->model('product_model');
		$this->load->model('payment_model');
		
		$plan_id = $this->input->post('plan_id');
		$premium = preg_replace("/[^0-9\.-]/", "", $this->input->post('premium'));
		$premium = (float)$premium;
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
		if (($plan['product_short'] == 'TOP') && ($plan['totalyears'] > 60)) {
			if ($commission_rate > 15) {
				$commission_rate -= 15;
			} else {
				$commission_rate = 0;
			}
		}
		if ($plan['product_short'] == 'TOP') {
			$commission_amount = ($premium - ($plan['tax'] * $premium / $plan['premium'])) * $commission_rate / 100.0;
		} else {
			$commission_amount = $premium * $commission_rate / 100.0;
		}
		$up_commission_rate = $this->product_model->get_up_commission_rate($plan['product_short']);
		$up_commission_amount = $premium * $up_commission_rate / 100.0;
		
		$dt['amount'] = $premium;
		$dt['rate'] = 100;
		$dt['pay_type'] = 'premium';
		$dt['premium_payment_id'] = 0;
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
		$dt['premium_payment_id'] = $payment_id;
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
		$dt['premium_payment_id'] = $payment_id;
		if (($plan['product_short'] == 'OPL') || ($plan['product_short'] == 'JFVTC') || ($plan['product_short'] == 'JFR')) {
			$nowtm = time();
			$efftm = strtotime($plan['effective_date']);
			if ($nowtm <= $efftm) {
				if (($plan['sum_insured'] >= 100000) && ($plan['totaldays'] >= 365)) {
					if ($this->payment_model->adjust_commission_added_date($plan_id, $plan['effective_date'])) {
						$para = array(
								'plan_id' => $plan_id,
								'customer_id' => $plan['customer_id'],
								'payment_id' => 0,
								'message' => 'adjust apply time to effective date : ' . $plan_id . ' [ ' . $plan['effective_date'] . ' ]',
								'systemlog' => $this->payment_model->sqlstr
						);
						$this->log_model->activity('commission', $para);
					}
					$dt['added'] = $plan['effective_date'];
				} else {
					if ($this->payment_model->adjust_commission_added_back_date($plan_id, date('Y-m-d'))) {
						$para = array(
								'plan_id' => $plan_id,
								'customer_id' => $plan['customer_id'],
								'payment_id' => 0,
								'message' => 'adjust apply time to today : ' . $plan_id . ' [ ' . date('Y-m-d') . ' ]',
								'systemlog' => $this->payment_model->sqlstr
						);
						$this->log_model->activity('commission', $para);
					}
				}
			}
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
		
    $history_id = 0;
    if (($history = $this->plan_history_model->get_plan_history_by_plan_id($plan_id)) && ($history["actualrate"]>0)) {
      $history_id = $history["plan_history_id"];
    } else {
      // Add missing first record.
      if ($plan['status_id'] > 1) {
        $history_id = $this->plan_history_model->add($plan_id, $plan['status_id']);
      }
    }
    
    $para = array('payment_id' => $payment_id, 'payinfo' => $payinfo, 'commission_payment_id' => $commission_payment_id, 'status_id' => Plan_model::SOLD, 'policy' => $this->plan_model->get_policy_number($plan_id, 2));
		$this->plan_model->update($plan_id, $para);
    if ($history_id) {
      $this->plan_history_model->add_remove($history_id);
    }
    $this->plan_history_model->add($plan_id, Plan_model::SOLD);

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
		echo $withlogo ? 'Has Logo' : 'No Logo';
	}

	function exportprice($withprice=1) {
		$this->session->set_userdata ( 'withprice',  $withprice);
		echo $withprice ? 'With Price' : 'No Price';
	}
	
	private function psireturncommon($plan_id) {
		$this->load->model('psigate_model');
		$this->load->model('plan_model');
		if (!$plan_id) {
			die("Unknown");
		}
		$plan = $this->plan_model->get_plan_by_id($plan_id);
		if (!$plan) {
			die("Unknown data");
		}

		$para = array();
		$para['plan_id'] = $plan_id;
		$para['TransTime'] = $this->input->get('TransTime') ? $this->input->get('TransTime') : '';
		$para['OrderID'] = $this->input->get('OrderID') ? $this->input->get('OrderID') : '';
		$para['Approved'] = $this->input->get('Approved') ? $this->input->get('Approved') : '';
		$para['ReturnCode'] = $this->input->get('ReturnCode') ? $this->input->get('ReturnCode') : '';
		$para['ErrMsg'] = $this->input->get('ErrMsg') ? $this->input->get('ErrMsg') : '';
		$para['TaxTotal'] = $this->input->get('TaxTotal') ? $this->input->get('TaxTotal') : 0;
		$para['ShipTotal'] = $this->input->get('ShipTotal') ? $this->input->get('ShipTotal') : 0;
		$para['SubTotal'] = $this->input->get('SubTotal') ? $this->input->get('SubTotal') : 0;
		$para['FullTotal'] = $this->input->get('FullTotal') ? $this->input->get('FullTotal') : 0;
		$para['PaymentType'] = $this->input->get('PaymentType') ? $this->input->get('PaymentType') : '';
		$para['DebitType'] = $this->input->get('DebitType') ? $this->input->get('DebitType') : '';
		$para['CardNumber'] = $this->input->get('CardNumber') ? $this->input->get('CardNumber') : '';
		$para['CardExpMonth'] = $this->input->get('CardExpMonth') ? $this->input->get('CardExpMonth') : '';
		$para['CardExpYear'] = $this->input->get('CardExpYear') ? $this->input->get('CardExpYear') : '';
		$para['TransRefNumber'] = $this->input->get('TransRefNumber') ? $this->input->get('TransRefNumber') : '';
		$para['CardIDResult'] = $this->input->get('CardIDResult') ? $this->input->get('CardIDResult') : '';
		$para['IPResult'] = $this->input->get('IPResult') ? $this->input->get('IPResult') : '';
		$para['IPCity'] = $this->input->get('IPCity') ? $this->input->get('IPCity') : '';
		$para['IPRegion'] = $this->input->get('IPRegion') ? $this->input->get('IPRegion') : '';
		$para['IPCountry'] = $this->input->get('IPCountry') ? $this->input->get('IPCountry') : '';
		$para['AVSResult'] = $this->input->get('AVSResult') ? $this->input->get('AVSResult') : '';
		$para['IssuerName'] = $this->input->get('IssuerName') ? $this->input->get('IssuerName') : '';
		$para['IssuerConfCode'] = $this->input->get('IssuerConfCode') ? $this->input->get('IssuerConfCode') : '';
		$para['AcquirerCode'] = $this->input->get('AcquirerCode') ? $this->input->get('AcquirerCode') : '';
		$para['CustomerIssLang'] = $this->input->get('CustomerIssLang') ? $this->input->get('CustomerIssLang') : '';
		$para['TransType'] = $this->input->get('TransType') ? $this->input->get('TransType') : '';
		$para['rowdata'] = json_encode($this->input->get());
		
		$para['psigate_id'] = $this->psigate_model->add($para);
		
		return $para;
	}

	function psiok($plan_id=0) {
		$psipara = $this->psireturncommon($plan_id);
		$this->load->model('product_model');
		$this->load->model('payment_model');
		
		$premium = (float)$psipara['FullTotal'];
		
		$plan = $this->plan_model->get_plan_by_id($plan_id);
		$product = $this->product_model->get_product($plan['product_short']);
		$dt = array();
		$dt['plan_id'] = $plan_id;
		$dt['currency'] = $product['currency'];
		$dt['pay_mothed'] = 'Credit Card';
		$dt['name'] = '';
		$dt['added'] = date('c');
		$dt['first5'] = '';
		$dt['last4'] = substr($psipara['CardNumber'], -4);
		$dt['expiry_month'] = $psipara['CardExpMonth'];
		$dt['expiry_year'] = $psipara['CardExpYear'];
		$dt['ispaid'] = 1;
		$commission_rate = $this->product_model->get_commission_rate($plan['product_short'], $plan['user_id']);
		if (($plan['product_short'] == 'TOP') && $plan['questionnaire']) {
			if ($commission_rate > 0.15) {
				$commission_rate -= 0.15;
			} else {
				$commission_rate = 0;
			}
		}
		if ($plan['product_short'] == 'TOP') {
			$commission_amount = ($premium - ($plan['tax'] * $premium / $plan['premium'])) * $commission_rate / 100.0;
		} else {
			$commission_amount = $premium * $commission_rate / 100.0;
		}
					
		$dt['amount'] = $premium;
		$dt['rate'] = 100;
		$dt['pay_type'] = 'premium';
		$dt['premium_payment_id'] = 0;
		$payment_id = $this->payment_model->add($dt);
		$para = array(
				'plan_id' => $plan_id,
				'customer_id' => $plan['customer_id'],
				'payment_id' => $payment_id,
				'message' => $this->payment_model->logstr,
				'systemlog' => $this->payment_model->sqlstr
		);
		$this->log_model->activity('payment', $para);

		// commission
		$dt['amount'] = $commission_amount;
		$dt['rate'] = $commission_rate;
		$dt['pay_type'] = 'commission';
		$dt['ispaid'] = 0;
		$dt['premium_payment_id'] = $payment_id;
		if (($plan['product_short'] == 'OPL') || ($plan['product_short'] == 'JFVTC') || ($plan['product_short'] == 'JFR')) {
			$nowtm = time();
			$efftm = strtotime($plan['effective_date']);
			if ($nowtm <= $efftm) {
				if (($plan['sum_insured'] >= 100000) && ($plan['totaldays'] >= 365)) {
					if ($this->payment_model->adjust_commission_added_date($plan_id, $plan['effective_date'])) {
						$para = array(
								'plan_id' => $plan_id,
								'customer_id' => $plan['customer_id'],
								'payment_id' => 0,
								'message' => 'adjust apply time to effective date : ' . $plan_id . ' [ ' . $plan['effective_date'] . ' ]',
								'systemlog' => $this->payment_model->sqlstr
						);
						$this->log_model->activity('commission', $para);
					}
					$dt['added'] = $plan['effective_date'];
				} else {
					if ($this->payment_model->adjust_commission_added_back_date($plan_id, date('Y-m-d'))) {
						$para = array(
								'plan_id' => $plan_id,
								'customer_id' => $plan['customer_id'],
								'payment_id' => 0,
								'message' => 'adjust apply time to today : ' . $plan_id . ' [ ' . date('Y-m-d') . ' ]',
								'systemlog' => $this->payment_model->sqlstr
						);
						$this->log_model->activity('commission', $para);
					}
				}
			}
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

		$payinfo = "PSiGate Card: (" . $psipara['psigate_id'] . ") " . $psipara['CardNumber'] . " " . $psipara['CardExpMonth'] . "/" . $psipara['CardExpYear'];
		$para = array('payment_id' => $payment_id, 'payinfo' => $payinfo, 'commission_payment_id' => $commission_payment_id, 'status_id' => Plan_model::PAID, 'policy' => $this->plan_model->get_policy_number($plan_id, 2));
		$this->plan_model->update($plan_id, $para);
		$para = array(
				'plan_id' => $plan_id,
				'customer_id' => $plan['customer_id'],
				'payment_id' => $payment_id,
				'commission_payment_id' => $commission_payment_id,
				'message' => $this->plan_model->logstr,
				'systemlog' => $this->plan_model->sqlstr
		);
		$this->log_model->activity('plan', $para);
		redirect(base_url('plan/detail/'.$plan_id));
	}

	function psifail($plan_id=0) {
		$para = $this->psireturncommon($plan_id);
		$this->load->model('product_model');
		$this->load->model('payment_model');
		
		$plan = $this->plan_model->get_plan_by_id($plan_id);
		if ($plan) {
			$para = array(
					'plan_id' => $plan_id,
					'customer_id' => $plan['customer_id'],
					'payment_id' => 0,
					'message' => 'payment fail',
					'systemlog' => json_encode($para)
			);
			$this->log_model->activity('payment', $para);
		}
		return $this->detail($plan_id, '', 'Payment Failed');
	}
	
	function detail($plan_id=0, $sekey='', $passerr='') {
		$this->error = '';
		$defaultpay_type = '';
		if ($play_type = $this->input->post('play_type')) {
			$plan_id = $this->input->post('plan_id');
			$sekey = $this->input->post('sekey');
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
		$this->load->model('plan_history_model');
		$plan = $this->plan_model->get_plan_by_id($plan_id);
		if (empty($plan)) {
			redirect('user/login');
		}
		if (($plan['claim_flag'] > 1) && ($plan['claim_allow_by'] < 1)) {
			redirect('plan/form');
		}
		
		if ($play_type = $this->input->post('play_type')) {
			if (!empty($sekey)) {
				if (empty($this->session->userdata ( 'beuser' )) && empty($this->session->userdata ( 'vsuser' ))) {
					redirect('user/login');
				}
			}
		
			$totalpaid = $this->payment_model->get_total_paid($plan_id, $pay_type='premium');
			$premium = preg_replace("/[^0-9\.-]/", "", $this->input->post('premium'));
			$premium = $totalpaid + (float)$premium;
      if (empty($this->input->post('premium')) && ($plan["status_id"] == Plan_model::CHANGED) && (($premium - floatval($plan['premium'])) < 0.01)) {
        // Confirm change
        $history_id = 0;
        if (($history = $this->plan_history_model->get_plan_history_by_plan_id($plan_id)) && ($history["actualrate"]>0)) {
          $history_id = $history["plan_history_id"];
        } else {
          // Add missing first record.
          if ($plan['status_id'] > 1) {
            $history_id = $this->plan_history_model->add($plan_id, $plan['status_id']);
          }
        }

        $this->plan_model->update($plan_id, array("status_id"=>Plan_model::PAID));
        $para = array(
          'plan_id' => $plan_id, 
          'customer_id' => $plan['customer_id'], 
          'payment_id' => 0, 
          'message' => $this->plan_model->logstr, 
          'systemlog' => $this->plan_model->sqlstr
        );
        $this->log_model->activity('plan', $para);
        if ($history_id) {
          $this->plan_history_model->add_remove($history_id);
        }
        if ($nid = $this->plan_history_model->add($plan_id, Plan_model::PAID)) {
          // Remove payment_id, it should be no payment
          $this->plan_history_model->update($nid, array("note"=>"plan condition change only"));
        }
      } else if (($premium - (float)$plan['premium']) > 0.001) {
				$this->error = "Pay amount has problem plase try again.";
			} else if ($play_type == 'Credit Card') {
				$this->credit_card();
				$defaultpay_type = 'Credit Card';
			} else if ($play_type == 'Cash') {
				$this->cash();
				$defaultpay_type = 'Cash';
			} else if ($play_type == 'Cheque') {
				$this->cheque();
				$defaultpay_type = 'Cheque';
			}
			if (empty($this->error)) {
				redirect(base_url('plan/detail/' . $plan_id));
			}
		}
		if (empty($sekey)) {
			$beuser = $this->func_model->verify_login(TRUE, TRUE);
		} else {
			$beuser = $this->user_model->get_user_by_id($plan['user_id']);
			$key = $this->plan_model->get_plan_key($plan_id);
			if ($key != $sekey) {
				redirect('user/login');
			}
			if ($plan['status_id'] != Plan_model::CHANGED) {
				if (((time() - strtotime($plan['last_update'])) > (48 * 3600)) || ($plan['effective_date'] <= date("Y-m-d"))) {
					show_error("This pay link is expired. Please contact your agent to Pay");
				}
			}
			
			$this->session->set_userdata ( 'beuser',  $beuser);
		}
		
		if (empty($passerr)) {
			$data['errormsg'] = $this->error;
		} else {
			$data['errormsg'] = $passerr;
		}
		$data['beuser'] = $beuser;
		$data['sekey'] = $sekey;
		$data['apply_date'] = date('Y-m-d');
		if ($play_type = $this->input->post('play_type')) {
			// Pay Action
			if (($plan['status_id'] == 3) && empty($this->session->userdata ('user'))) {
				// Paid Status and Paied from User
				$this->load->model('mymail_model');
				$body  = "Dear " . $beuser['firstname'] . ",\r\n\r\n";
				$body .= "Your client has paid for the policy " . $plan['polcy'] . ". Please follow up with your client if necessary.\r\n\r\n";
				$body .= "Best Regards,\r\n";
				$body .= "JF Insurance Agency Group Inc.\r\n";
				$body .= "15 Wertheim Court, Suite #501\r\n";
				$body .= "Richmond Hill, ON L4B 3H7\r\n";
				$body .= "Tel: 905-707-1512  Fax: 905-707-1513\r\n";
				$body .= "Website: www.jfgroup.ca\r\n";
				$this->mymail_model->send_mymail($beuser['email'], 'Your client has paid for the policy '.$plan['policy'], $body);
			}
		}
		
		if (($plan['status_id'] < 2) && ($beuser['user_group_id'] > 100)) {
			// Before Sold
			$para = array();
			$para['plan_id'] = $plan['plan_id'];
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
		$data['plan_cancel_date'] = '';
		$data['plan_refund_date'] = '';

		if ($plan['status_id'] == Plan_model::CANCEL) {
			$data['plan_cancel_date'] = $this->payment_model->get_cancel_date($plan['plan_id']);
		}
		if ($plan['status_id'] == Plan_model::REFUND) {
			$data['plan_refund_date'] = $plan['refund_date'];
		}
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
		$data['payurltm'] = date("Y-m-d H:i", strtotime($plan['last_update']) + 48 * 3600);
		$data['active_url'] = current_url();
		//$data['psi_active_url'] = 'https://stagingcheckout.psigate.com/HTMLPost/HTMLMessenger';
		$data['psi_active_url'] = 'https://checkout.psigate.com/HTMLPost/HTMLMessenger';
		$data['psi_thanks_url'] = base_url('plan/psiok/' . $plan_id);
		$data['psi_nothanks_url'] = base_url('plan/psifail/' . $plan_id);
		$data['StoreKey'] = 'JohnsonFuIns2017081018581'; //  'merchantcardcapture200024';
		$data['CustomerIP'] = $this->input->ip_address();
		
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
		
		// Refund special
		$data['used_days'] = 0;
		if ($data['plan']['status_id'] == 6) {
			$data['used_days'] = $this->product_model->getDays($data['plan']['effective_date'],$data['plan']['refund_date']);
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
				if (($plan['product_short'] != 'JUS') && ($plan['product_short'] != 'NUS')) {
					$data['cancel_letter_url'] = base_url('plan/cancelprint/' . $plan['plan_id']);
				}
			} else if ($plan['status_id'] == 6) {
				// Refund
				if (($plan['product_short'] != 'JUS') && ($plan['product_short'] != 'NUS')) {
					$data['refund_letter_url'] = base_url('plan/refundprint/' . $plan['plan_id']);
				}
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
		$data['isvsuser'] = 0;
		if ($vsuser = $this->session->userdata('vsuser')) {
			$this->load->model('myhome_model');
			$myhome = $this->myhome_model->get_myhome($vsuser['user_id']);
			if (!empty($myhome['logo'])) {
				$data['myhomelogo'] = base_url('agent/img') . '/' . $myhome['logo'];
			}
			
			if (!empty($myhome['qr'])) {
				$data['myqr'] = base_url('agent/img') . '/' . $myhome['qr'];
			}
			
			$data['paytype_list'] = $this->paytype_model->paytype_default();
			$data['isvsuser'] = 1;
				
			$data['top_menu'] = '';
			$data['menu'] = '';
		} else {
			$data['top_menu'] = $this->menu_model->load_top_menu();
			$data['menu'] = $this->menu_model->load_meun();
		}
		$data['csrf'] = array (
				'name' => $this->security->get_csrf_token_name (),
				'value' => $this->security->get_csrf_hash ()
		);
		
		$data['defaultpay_type'] = $defaultpay_type;
		$data['export_logo_url'] = base_url('plan/exportlogo') . "/";
		$data['export_price_url'] = base_url('plan/exportprice') . "/";
		$data['export_logo_price_option'] = FALSE;
    if (($beuser['user_group_id'] < 100) || ($beuser['user_id'] == 3744)) $data['export_logo_price_option'] = TRUE;
		$data['isprocessplan'] = 1;
		$data['html_model'] = $this->html_model;
		if ($data['plan']['product_short'] == 'OPL') {
			$data['insurable_options'] = $this->load->view('plan/detail_opl', $data, TRUE);
		} else if (($data['plan']['product_short'] == 'JFVTC') || ($data['plan']['product_short'] == 'JFR')) {
			$data['insurable_options'] = $this->load->view('plan/detail_opl', $data, TRUE);
		} else if ($data['plan']['product_short'] == 'JUS') {
			$data['insurable_options'] = $this->load->view('plan/detail_jus', $data, TRUE);
		} else if ($data['plan']['product_short'] == 'NUS') {
			$data['insurable_options'] = $this->load->view('plan/detail_jus', $data, TRUE);
		} else if (($data['plan']['product_short'] == 'JES') || ($data['plan']['product_short'] == 'JFPL') || ($data['plan']['product_short'] == 'JFSL') || ($data['plan']['product_short'] == 'JESP') || ($data['plan']['product_short'] == 'JFE') || ($data['plan']['product_short'] == 'JFS') || ($data['plan']['product_short'] == 'BHS')) {
			$data['insurable_options'] = $this->load->view('plan/detail_jes', $data, TRUE);
		} else if (($data['plan']['product_short'] == 'JFC') || ($data['plan']['product_short'] == 'JFP')) {
			$data['insurable_options'] = $this->load->view('plan/detail_jes', $data, TRUE);
		} else {
			$data['insurable_options'] = $this->load->view('plan/detail_other', $data, TRUE);
			$data['isprocessplan'] = 0;
		}
		
		$data['card_number'] = $this->input->post('card_number');
		$data['card_name'] = $this->input->post('card_name');
		$data['expiry_month'] = $this->input->post('expiry_month');
		$data['expiry_year'] = $this->input->post('expiry_year');
		$data['card_cvv'] = $this->input->post('card_cvv');
		
		$data['show_history'] = 0;
		if ($this->session->userdata ( 'user') && ($beuser['user_group_id'] < 100)) {
			$this->load->model('payment_model');
			$data['show_history'] = 1;
//			$data['activelogs'] = $this->log_model->get_activity_by_plan_id($plan['plan_id']);
			$data['payments'] = $this->payment_model->get_payment_by_plan_id($plan['plan_id']);
		}
		$data['payhistory_url'] = base_url ( "plan/payhistory/" . $plan['plan_id'] );
		$data['makepay_url'] = base_url ( "payment/makepay" );
		$data['revert_url'] = base_url ( "payment/revert" ) . "/";
		if ($plan['claim_flag'] == 1) {
			$data['error_message'] = '<strong>Warning: The insured(s) have had previous claim(s). Please check the policy eligibility and any pre-existing conditions with insured(s).</strong>';
		}
		
		$this->session->set_userdata ( 'withlogo', 1);
		if ($beuser['user_group_id'] != 103) {
			$this->session->set_userdata ( 'withprice', 1);
		} else {
			$this->session->set_userdata ( 'withprice', 0);
		}
		$data['html_model'] = $this->html_model;
		
		if ($data['plan']['product_short'] == 'TOP') {
			$data['toppackagename'] = $this->toppackagename;
			$this->load->common('plan/top/detail', $data);
		} else {
			$this->load->common('plan/detail', $data);
		}
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
		$data['pdf_enable'] = empty($beuser['pdf_product']) ? array() : json_decode($beuser['pdf_product']);
		$data['emailaddr'] = $plan['contact_email'];
		if ($this->input->post()) {
			$emailaddr = $this->input->post('emailaddr');
			$withbatch = 0;
			if ($beuser['user_group_id'] < 100) {
				$data['withlogo'] = $this->input->post('withlogo');
				$data['withprice'] = $this->input->post('withprice');
				$withbatch = $this->input->post('withbatch');
			} else {
				$data['withlogo'] = 1;
				if ($beuser['user_group_id'] != 103) {
					$data['withprice'] = 1;
				} else {
					$data['withprice'] = 0;
				}
			}
			if (!empty($emailaddr)) {
				$data['emailaddr'] = $emailaddr;
			}
			$this->load->model('verify_model');
			if ($this->verify_model->isEmail($data['emailaddr'])) {
				$this->load->model('customer_model');
				$this->load->model('product_model');
				$this->load->model('paytype_model');
				$this->load->model('status_model');
				$this->load->model('payment_model');
				$product = $this->product_model->get_product($plan['product_short']);
				$data['payment'] = '';
				if ($plan['payment_id']) {
					$data['payment'] = $this->payment_model->get_payment_by_id($plan['payment_id']);
				}
				$data['plan_full_name'] = $product ? $product['full_name'] : '';
				$data['customer'] = $this->customer_model->get_customer_by_id($data['plan']['customer_id']);
				$data['customers'] = $this->customer_model->get_customer_by_parent_id($data['plan']['customer_id']);
				$data['paytype_list'] = $this->paytype_model->paytype_list();
				$data['status_list'] = $this->status_model->status_list();
				$data['html_model'] = $this->html_model;
				
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
				} else if ($data['plan']['product_short'] == 'JFVTC') {
					$data['insurable_options'] = $this->load->view('plan/detail_opl', $data, TRUE);
					$data['special_note'] = $this->load->view('plan/pdf_note_jfr',$data, TRUE);
					$files = array(
					'JFVTC_Policy.pdf' => DOWNLOADDIR . 'JFVTC_Policy.pdf',
					'JFVTC_Claim_Procedure.pdf' => DOWNLOADDIR . 'JFVTC_Claim_Procedure.pdf',
					'JFVTC_Claim_Form.pdf' => DOWNLOADDIR . 'JFVTC_Claim_Form.pdf',
					'JFVTC_Brochure.pdf' => DOWNLOADDIR . 'JFVTC_Brochure.pdf'
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
				} else if ($data['plan']['product_short'] == 'JFS') {
					$data['insurable_options'] = $this->load->view('plan/detail_jes', $data, TRUE);
					$data['special_note'] = $this->load->view('plan/pdf_note_jes',$data, TRUE);
					$files = array(
					'JFS_Policy.pdf' => DOWNLOADDIR . 'JFS_Policy.pdf',
					'JFS_Claim_Form.pdf' => DOWNLOADDIR . 'JFS_Claim_Form.pdf',
					'JFS_Clinic_Map.pdf' => DOWNLOADDIR . 'JFS_Clinic_Map.pdf',
					);
				} else if ($data['plan']['product_short'] == 'JFE') {
					$data['insurable_options'] = $this->load->view('plan/detail_jes', $data, TRUE);
					$data['special_note'] = $this->load->view('plan/pdf_note_jes',$data, TRUE);
					$files = array(
					'JFE_Policy.pdf' => DOWNLOADDIR . 'JFE_Policy.pdf',
					'JFE_Claim_Form.pdf' => DOWNLOADDIR . 'JFE_Claim_Form.pdf',
					'JFE_Clinic_Map.pdf' => DOWNLOADDIR . 'JFE_Clinic_Map.pdf',
					);
				} else if ($data['plan']['product_short'] == 'BHS') {
					$data['insurable_options'] = $this->load->view('plan/detail_jes', $data, TRUE);
					$data['special_note'] = $this->load->view('plan/pdf_note_jes',$data, TRUE);
					$files = array(
					'BHS_Policy.pdf' => DOWNLOADDIR . 'BHS_Policy.pdf',
					'BHS_Claim_Form.pdf' => DOWNLOADDIR . 'BHS_Claim_Form.pdf',
					'BHS_Clinic_Map.pdf' => DOWNLOADDIR . 'BHS_Clinic_Map.pdf',
					);
				} else if ($data['plan']['product_short'] == 'JES') {
					$data['insurable_options'] = $this->load->view('plan/detail_jes', $data, TRUE);
					$data['special_note'] = $this->load->view('plan/pdf_note_jes',$data, TRUE);
					$files = array(
					'JES_Policy.pdf' => DOWNLOADDIR . 'JES_Policy.pdf',
					'JES_Claim_Form.pdf' => DOWNLOADDIR . 'JES_Claim_Form.pdf',
					'JES_Clinic_Map.pdf' => DOWNLOADDIR . 'JES_Clinic_Map.pdf',
					'JES_Brochure.pdf' => DOWNLOADDIR . 'JES_Brochure.pdf'
					);
				} else if ($data['plan']['product_short'] == 'JFPL') {
					$data['insurable_options'] = $this->load->view('plan/detail_jes', $data, TRUE);
					$data['special_note'] = $this->load->view('plan/pdf_note_jes',$data, TRUE);
					$files = array(
					'JFPL_Policy.pdf' => DOWNLOADDIR . 'JFPL_Policy.pdf',
					'JFPL_Claim_Form.pdf' => DOWNLOADDIR . 'JFPL_Claim_Form.pdf',
					'JFPL_Clinic_Map.pdf' => DOWNLOADDIR . 'JFPL_Clinic_Map.pdf',
					'JFPL_Benefit_Summary.pdf' => DOWNLOADDIR . 'JFPL_Benefit_Summary.pdf'
					);
				} else if ($data['plan']['product_short'] == 'JFSL') {
					$data['insurable_options'] = $this->load->view('plan/detail_jes', $data, TRUE);
					$data['special_note'] = $this->load->view('plan/pdf_note_jes',$data, TRUE);
					$files = array(
					'JFSL_Policy.pdf' => DOWNLOADDIR . 'JFSL_Policy.pdf',
					'JFSL_Claim_Form.pdf' => DOWNLOADDIR . 'JFSL_Claim_Form.pdf',
					'JFSL_Clinic_Map.pdf' => DOWNLOADDIR . 'JFSL_Clinic_Map.pdf',
					'JFSL_Benefit_Summary.pdf' => DOWNLOADDIR . 'JFSL_Benefit_Summary.pdf'
					);
				} else if ($data['plan']['product_short'] == 'JESP') {
					$data['insurable_options'] = $this->load->view('plan/detail_jes', $data, TRUE);
					$data['special_note'] = $this->load->view('plan/pdf_note_jes',$data, TRUE);
					$files = array(
					'JESP_Policy.pdf' => DOWNLOADDIR . 'JESP_Policy.pdf',
					'JESP_Claim_Form.pdf' => DOWNLOADDIR . 'JESP_Claim_Form.pdf',
					'JESP_Brochure.pdf' => DOWNLOADDIR . 'JESP_Brochure.pdf'
					);
				} else if ($data['plan']['product_short'] == 'JFC') {
					$data['insurable_options'] = $this->load->view('plan/detail_jes', $data, TRUE);
					$data['special_note'] = $this->load->view('plan/pdf_note_jfc',$data, TRUE);
					$files = array(
					'JFC_Policy.pdf' => DOWNLOADDIR . 'JFC_Policy.pdf',
					'JFC_Claim_Form.pdf' => DOWNLOADDIR . 'JFC_Claim_Form.pdf',
					'JFC_Brochure.pdf' => DOWNLOADDIR . 'JFC_Brochure.pdf'
					);
				} else if ($data['plan']['product_short'] == 'JFP') {
					$data['insurable_options'] = $this->load->view('plan/detail_jes', $data, TRUE);
					$data['special_note'] = $this->load->view('plan/pdf_note_jfc',$data, TRUE);
					$files = array(
					'JFP_Policy.pdf' => DOWNLOADDIR . 'JFP_Policy.pdf',
					'JFP_Claim_Form.pdf' => DOWNLOADDIR . 'JFP_Claim_Form.pdf',
					'JFP_Brochure.pdf' => DOWNLOADDIR . 'JFP_Brochure.pdf'
					);
					
				} else if ($data['plan']['product_short'] == 'TOP') {
					$data['insurable_options'] = '';
					$data['special_note'] = $this->load->view('plan/top/pdf_note_top',$data, TRUE);
					$files = array(
					'TOP_Policy.pdf' => DOWNLOADDIR . 'TOP_Policy.pdf',
					'TOP_Baggage_Claim_Form.pdf' => DOWNLOADDIR . 'TOP_Baggage_Claim_Form.pdf',
					'TOP_Cancellation_Claim_Form.pdf' => DOWNLOADDIR . 'TOP_Cancellation_Claim_Form.pdf',
					'TOP_Medical_Claim_Form.pdf' => DOWNLOADDIR . 'TOP_Medical_Claim_Form.pdf',
					'TOP_Benefit_Summary.pdf' => DOWNLOADDIR . 'TOP_Benefit_Summary.pdf'
					);
				} else {
					$data['insurable_options'] = $this->load->view('plan/detail_other', $data, TRUE);
				}
				
				$policy_file = tempnam("/tmp", "Policy");
				//$policy_file = "C:\Users\Administrator\AppData\Local\Temp\Policy";
				$data['title_txt'] = 'Policy';
				$data['style'] = $this->load->view('common/pdf_style',$data, TRUE);
        $data['hadheaderfooter'] = 0;
        if ($data['plan']['product_short'] == 'JFVTC') {
          $mpdf = new mPDF('c', 'A4', 0, '', $mgl = 0, $mgr = 0, $mgt = 15, $mgb = 0, $mgh = 0, $mgf = 0, $orientation = 'P');
          if ($data['withlogo']) {
            $mpdf->SetHTMLHeader('<img style="width:100%;" src="'.base_url().'image/pdf_header.png" />');
          }
          $data['hadheaderfooter'] = 1;
          $html = $this->load->view('plan/pdf_jfvtc', $data, TRUE);
        } else if ($data['plan']['product_short'] == 'JFSL') {
          $mpdf = new mPDF('c', 'A4', 0, '', $mgl = 0, $mgr = 0, $mgt = 15, $mgb = 0, $mgh = 0, $mgf = 0, $orientation = 'P');
          if ($data['withlogo']) {
            $mpdf->SetHTMLHeader('<img style="width:100%;" src="'.base_url().'image/pdf_header.png" />');
          }
          // $mpdf->SetHTMLFooter('<img style="width:100%;" src="'.base_url().'image/pdf_footer.png" />');
          $data['hadheaderfooter'] = 1;
          $html = $this->load->view('plan/pdf_jfsl', $data, TRUE);
        } else if ($data['plan']['product_short'] == 'JFPL') {
          $mpdf = new mPDF('c', 'A4', 0, '', $mgl = 0, $mgr = 0, $mgt = 15, $mgb = 0, $mgh = 0, $mgf = 0, $orientation = 'P');
          if ($data['withlogo']) {
            $mpdf->SetHTMLHeader('<img style="width:100%;" src="'.base_url().'image/pdf_header.png" />');
          }
          // $mpdf->SetHTMLFooter('<img style="width:100%;" src="'.base_url().'image/pdf_footer.png" />');
          $data['hadheaderfooter'] = 1;
          $html = $this->load->view('plan/pdf', $data, TRUE);
        } else {
  				$mpdf = new mPDF('c');
          $html = $this->load->view('plan/pdf', $data, TRUE);
        }
				$mpdf->writeHTML($html);
				$mpdf->Output($policy_file, 'F');
				$this->load->model('mymail_model');
				$body = $this->load->view('mail/package',$data, TRUE);
				$title = "Confirmation of Insurance - " . $plan['policy'] . " - " . $data['customer']['firstname'] . " " . $data['customer']['lastname'];
				
				$files['policy_confirmation.pdf'] = $policy_file;
				$sendok = $this->mymail_model->send_mymail($data['emailaddr'], $title, $body, $files, $from='JF Insurance');
				unlink($policy_file);
				if ($sendok) {
					if ($withbatch) {
						die("OK");
					}
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
		$data['plan_batch'] = array();
		if ($plan['batch_number'] > 0) {
			$data['plan_batch'] = $this->plan_model->plan_search(array('batch_number' => $plan['batch_number']), 550);
		}
		
		$data['title_txt'] = 'Policy';
		$data['top_menu'] = $this->menu_model->load_top_menu();
		$data['menu'] = $this->menu_model->load_meun();
		$data['csrf'] = array (
				'name' => $this->security->get_csrf_token_name (),
				'value' => $this->security->get_csrf_hash ()
		);
		$data['html_model'] = $this->html_model;
		
		$this->load->common('plan/sendpackage', $data);
	}

	public function pdf($plan_id)
	{
		$beuser = $this->func_model->verify_login(TRUE, TRUE);
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
		$data['pdf_enable'] = empty($beuser['pdf_product']) ? array() : json_decode($beuser['pdf_product']);
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
		$data['html_model'] = $this->html_model;
		
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
		} else if (($data['plan']['product_short'] == 'JFS') || ($data['plan']['product_short'] == 'JFE') || ($data['plan']['product_short'] == 'BHS')) {
			$data['insurable_options'] = $this->load->view('plan/detail_jes', $data, TRUE);
			$data['special_note'] = $this->load->view('plan/pdf_note_jes',$data, TRUE);
		} else if ($data['plan']['product_short'] == 'JES') {
			$data['insurable_options'] = $this->load->view('plan/detail_jes', $data, TRUE);
			$data['special_note'] = $this->load->view('plan/pdf_note_jes',$data, TRUE);
		} else if ($data['plan']['product_short'] == 'JFPL') {
			$data['insurable_options'] = $this->load->view('plan/detail_jfpl', $data, TRUE);
			$data['special_note'] = $this->load->view('plan/pdf_note_jfpl',$data, TRUE);
		} else if ($data['plan']['product_short'] == 'JFSL') {
			$data['insurable_options'] = $this->load->view('plan/detail_jfpl', $data, TRUE);
			$data['special_note'] = $this->load->view('plan/pdf_note_jfpl',$data, TRUE);
		} else if ($data['plan']['product_short'] == 'JESP') {
			$data['insurable_options'] = $this->load->view('plan/detail_jes', $data, TRUE);
			$data['special_note'] = $this->load->view('plan/pdf_note_jes',$data, TRUE);
		} else if ($data['plan']['product_short'] == 'JFC') {
			$data['insurable_options'] = $this->load->view('plan/detail_jes', $data, TRUE);
			$data['special_note'] = $this->load->view('plan/pdf_note_jfc',$data, TRUE);
		} else if ($data['plan']['product_short'] == 'JFP') {
			$data['insurable_options'] = $this->load->view('plan/detail_jes', $data, TRUE);
			$data['special_note'] = $this->load->view('plan/pdf_note_jes',$data, TRUE);
		} else if ($data['plan']['product_short'] == 'TOP') {
			$data['insurable_options'] = '';
			$data['toppackagename'] = $this->toppackagename;
			$data['special_note'] = $this->load->view('plan/top/pdf_note_top',$data, TRUE);
		} else {
			$data['insurable_options'] = $this->load->view('plan/detail_other', $data, TRUE);
			$data['special_note'] = " ";
		}
		
		$data['title_txt'] = 'Policy';
		$data['customer_product_name'] = $this->product_model->get_product_customize_name($beuser['user_id'], $data['plan']['product_short']);
		$data['style'] = $this->load->view('common/pdf_style',$data, TRUE);		
		$data['hadheaderfooter'] = 0;
    if ($data['plan']['product_short'] == 'JFVTC') {
      $mpdf = new mPDF('c', 'A4', 0, '', $mgl = 0, $mgr = 0, $mgt = 15, $mgb = 0, $mgh = 0, $mgf = 0, $orientation = 'P');
      if ($plan['status_id'] < 2) {
        $mpdf->SetWatermarkText ("QUOTE", 0.1);
        $mpdf->showWatermarkText = true;
      }
      $data['hadheaderfooter'] = 1;
      $html = $this->load->view('plan/pdf_jfvtc', $data, TRUE);
      if ($data['withlogo']) {
        $mpdf->SetHTMLHeader('<img style="width:100%;" src="'.base_url().'image/pdf_header.png" />');
      }
    // $mpdf->SetHTMLFooter('<img style="width:100%;" src="'.base_url().'image/pdf_footer.png" />');
    } else if ($data['plan']['product_short'] == 'JFSL') {
      $mpdf = new mPDF('c', 'A4', 0, '', $mgl = 0, $mgr = 0, $mgt = 15, $mgb = 0, $mgh = 0, $mgf = 0, $orientation = 'P');
      if ($plan['status_id'] < 2) {
        $mpdf->SetWatermarkText ("QUOTE", 0.1);
        $mpdf->showWatermarkText = true;
      }
      $data['hadheaderfooter'] = 1;
      $html = $this->load->view('plan/pdf_jfsl', $data, TRUE);
      if ($data['withlogo']) {
        $mpdf->SetHTMLHeader('<img style="width:100%;" src="'.base_url().'image/pdf_header.png" />');
      }
    // $mpdf->SetHTMLFooter('<img style="width:100%;" src="'.base_url().'image/pdf_footer.png" />');
    } else if ($data['plan']['product_short'] == 'JFPL') {
      $mpdf = new mPDF('c', 'A4', 0, '', $mgl = 0, $mgr = 0, $mgt = 15, $mgb = 0, $mgh = 0, $mgf = 0, $orientation = 'P');
      if ($plan['status_id'] < 2) {
        $mpdf->SetWatermarkText ("QUOTE", 0.1);
        $mpdf->showWatermarkText = true;
      }
      $data['hadheaderfooter'] = 1;
      $html = $this->load->view('plan/pdf', $data, TRUE);
      if ($data['withlogo']) {
        $mpdf->SetHTMLHeader('<img style="width:100%;" src="'.base_url().'image/pdf_header.png" />');
      }
    // $mpdf->SetHTMLFooter('<img style="width:100%;" src="'.base_url().'image/pdf_footer.png" />');
    } else {
      $mpdf = new mPDF('c');
      if ($plan['status_id'] < 2) {
        $mpdf->SetWatermarkText ("QUOTE", 0.1);
        $mpdf->showWatermarkText = true;
      }
      $html = $this->load->view('plan/pdf', $data, TRUE);
    }
		$mpdf->writeHTML($html);
		$mpdf->Output("Policy.pdf","I");
	}

	/**
	 * Cancel Letter
	 * 
	 * @param integer $plan_id
	 */
	public function cancel($plan_id=0) {
		$beuser = $this->func_model->verify_login(TRUE);
		$this->load->model('plan_model');
		$this->load->model('plan_history_model');

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
		if (($plan['status_id'] != Plan_model::SOLD) && ($plan['status_id'] != Plan_model::PAID)) {
			redirect('plan/detail/'.$plan_id);
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
				if (($plan['product_short'] == 'TOP') && ($plan['totalyears'] > 60)) {
					if ($commission_rate > 15) {
						$commission_rate -= 15;
					} else {
						$commission_rate = 0;
					}
				}
				
				$commission_amount = $refund_amount * $commission_rate / 100.0;
				$up_commission_rate = $this->product_model->get_up_commission_rate($plan['product_short']);
				$up_commission_amount = $refund_amount * $up_commission_rate / 100.0;
				
				$dt['amount'] = $total_amount * (-1);
				$dt['rate'] = 100;
				$dt['pay_type'] = 'cancel';
				$dt['premium_payment_id'] = 0;
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
				$dt['premium_payment_id'] = $payment_id;
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
				$dt['premium_payment_id'] = $payment_id;
				$up_commission_payment_id = $this->payment_model->add($dt);
				$para = array(
						'plan_id' => $plan_id,
						'customer_id' => $plan['customer_id'],
						'payment_id' => $up_commission_payment_id,
						'message' => $this->payment_model->logstr,
						'systemlog' => $this->payment_model->sqlstr
				);
				$this->log_model->activity('up_commission', $para);

        $history_id = 0;
        if (($history = $this->plan_history_model->get_plan_history_by_plan_id($plan_id)) && ($history["actualrate"]>0)) {
          $history_id = $history["plan_history_id"];
        } else {
          // Add missing first record.
          if ($plan['status_id'] > 1) {
            $history_id = $this->plan_history_model->add($plan_id, $plan['status_id']);
          }
        }

				$note = "Cancel at " . $dt['added'] . " amount: " . $refund_amount . " admin fee: " . $admin_fee . "; " . $plan['note'];
				$para = array('status_id' => 5, 'payment_id' => $payment_id, 'commission_payment_id' => $commission_payment_id, 'note' => $note );  // Change status to cancel
				$this->plan_model->update($plan_id, $para);
        if ($id = $this->plan_history_model->add_remove($history_id)) {
          $this->plan_history_model->update($id, array("payment_id"=>$payment_id, "note"=>"Canceled Recode"));
        }
        $para = array(
						'plan_id' => $plan_id,
						'customer_id' => $plan['customer_id'],
						'payment_id' => $payment_id,
						'message' => $this->plan_model->logstr,
						'systemlog' => $this->plan_model->sqlstr
				);
				$this->log_model->activity('plan', $para);

				if ((($plan['product_short'] == 'OPL') || ($plan['product_short'] == 'JFVTC') || ($plan['product_short'] == 'JFR')) && ($plan['sum_insured'] >= 100000) && ($plan['totaldays'] >= 365)) {
					// No more super visa, change payment data to today
					$this->payment_model->adjust_commission_added_date($plan_id, $dt['added'], FALSE);
					$para = array(
							'plan_id' => $plan_id,
							'customer_id' => $plan['customer_id'],
							'payment_id' => $plan['commission_payment_id'],
							'message' => $this->payment_model->logstr,
							'systemlog' => $this->payment_model->sqlstr
					);
					$this->log_model->activity('plan', $para);
				}
				
				redirect('plan/detail/'.$plan_id);
			} else {
				$data['error_message'] = 'Invalid refund amount';
			}
		}

		$claims = $this->plan_model->verify_policy($plan['policy']);
		$data['claims'] = (!empty($claims) && ($claims['status'] == 'OK')) ? $claims['claims'] : '';

		$data['action_url'] = base_url('plan/cancel');
		$data['plan_id'] = $plan['plan_id'];
		$data['admin_fee'] = 0;
		$data['url_back_to_policy'] = base_url('plan/');
		$data['title_txt'] = 'Policy';
		$data['top_menu'] = $this->menu_model->load_top_menu();
		$data['menu'] = $this->menu_model->load_meun();
		$data['csrf'] = array (
				'name' => $this->security->get_csrf_token_name (),
				'value' => $this->security->get_csrf_hash ()
		);
		$data['html_model'] = $this->html_model;
		
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
		$data['refund_days'] = $this->product_model->getDays($plan['effective_date'], $this->input->get('refund_date'));
		if ($plan['product_short'] == 'TOP') {
			$this->load->model('top_model');
			$data['refund_amount'] = $this->top_model->refund_amount($plan, $data['refund_days']);
		} else {
			$data['refund_amount'] = $this->plan_model->refund_amount($plan_id, $this->input->get('refund_date'));
		}
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
		$this->load->model('plan_history_model');

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
		if (($plan['status_id'] != Plan_model::SOLD) && ($plan['status_id'] != Plan_model::PAID)) {
			redirect('plan/detail/'.$plan_id);
		}
		$this->load->model('product_model');
		$product = $this->product_model->get_product($plan['product_short']);
		
		$data['beuser'] = $beuser;
		$data['plan'] = $plan;
		if ($this->input->post()) {
			$refund_amount = (float)$this->input->post('refund_amount');
			$admin_fee = (float)$this->input->post('admin_fee');
			$total_amount = (float)$this->input->post('total_refund');
			$refund_date = $this->input->post('refund_date');
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
				if (($plan['product_short'] == 'TOP') && ($plan['totalyears'] > 60)) {
					if ($commission_rate > 15) {
						$commission_rate -= 15;
					} else {
						$commission_rate = 0;
					}
				}
				$commission_amount = $refund_amount * $commission_rate / 100.0;
				$up_commission_rate = $this->product_model->get_up_commission_rate($plan['product_short']);
				$up_commission_amount = $refund_amount * $up_commission_rate / 100.0;
				
				$dt['amount'] = $total_amount * (-1);
				$dt['rate'] = 100;
				$dt['pay_type'] = 'refund';
				$dt['premium_payment_id'] = 0;
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
				$dt['premium_payment_id'] = $payment_id;
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
				$dt['premium_payment_id'] = $payment_id;
				$up_commission_payment_id = $this->payment_model->add($dt);
				$para = array(
						'plan_id' => $plan_id,
						'customer_id' => $plan['customer_id'],
						'payment_id' => $up_commission_payment_id,
						'message' => $this->payment_model->logstr,
						'systemlog' => $this->payment_model->sqlstr
				);
				$this->log_model->activity('up_commission', $para);
	
        // Add history
        $history_id = 0;
        if (($history = $this->plan_history_model->get_plan_history_by_plan_id($plan_id)) && ($history["actualrate"]>0)) {
          $history_id = $history["plan_history_id"];
        } else {
          // Add missing first record
          if ($plan['status_id'] > 1) {
            $history_id = $this->plan_history_model->add($plan_id, $plan['status_id']);
          }
        }
        if ($history_id) {
          $this->plan_history_model->add_remove($history_id);
        }

        $note = "Refund at " . $dt['added'] . " amount: " . $refund_amount . " admin fee: " . $admin_fee . "; " . $plan['note'];
				$para = array('status_id' => Plan_model::REFUND, 'payment_id' => $payment_id, 'commission_payment_id' => $commission_payment_id, 'refund_date' => $refund_date, 'note' => $note );  // Change status to refund
				$this->plan_model->update($plan_id, $para);
        if ($id = $this->plan_history_model->add($plan_id, Plan_model::REFUND)) {
          $this->plan_history_model->update($id, array("payment_id"=>$payment_id, "premium"=>($plan["premium"] - $refund_amount),"expiry_date"=>$refund_date, "note"=>"Refunded Recode"));
        }

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
		$claims = $this->plan_model->verify_policy($plan['policy']);
		$data['claims'] = (!empty($claims) && ($claims['status'] == 'OK')) ? $claims['claims'] : '';
		$data['adminfee'] = 40;
		$data['refund_enable'] = 1;
		if ($plan['product_short'] == 'TOP') {
			$data['adminfee'] = 20;
			$data['top_refund_notes'] = "Only Single Medical Plan can do refund.";
			if ($plan['package'] != 'single_medical_plan') {
				$data['top_refund_notes'] .= " This plan can't be refunded.";
				$data['refund_enable'] = 0;
			}
		}
		// if ($plan['product_short'] == 'JFC') $data['adminfee'] = 25; 
		$data['url_back_to_policy'] = base_url('plan/');

		$data['title_txt'] = 'Policy';
		$data['top_menu'] = $this->menu_model->load_top_menu();
		$data['menu'] = $this->menu_model->load_meun();
		$data['csrf'] = array (
				'name' => $this->security->get_csrf_token_name (),
				'value' => $this->security->get_csrf_hash ()
		);
		$data['html_model'] = $this->html_model;
		
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
		$data['html_model'] = $this->html_model;
		if ($data['plan']['product_short'] == 'OPL') {
			$data['insurable_options'] = $this->load->view('plan/detail_opl', $data, TRUE);
		} else if (($data['plan']['product_short'] == 'JFVTC') || ($data['plan']['product_short'] == 'JFR')) {
			$data['insurable_options'] = $this->load->view('plan/detail_opl', $data, TRUE);
		} else if ($data['plan']['product_short'] == 'JUS') {
			$data['insurable_options'] = $this->load->view('plan/detail_jus', $data, TRUE);
		} else if ($data['plan']['product_short'] == 'NUS') {
			$data['insurable_options'] = $this->load->view('plan/detail_jus', $data, TRUE);
		} else if (($data['plan']['product_short'] == 'JFS') || ($data['plan']['product_short'] == 'JFE') || ($data['plan']['product_short'] == 'BHS')) {
			$data['insurable_options'] = $this->load->view('plan/detail_jes', $data, TRUE);
		} else if (($data['plan']['product_short'] == 'JES') || ($data['plan']['product_short'] == 'JFPL') || ($data['plan']['product_short'] == 'JFSL')) {
			$data['insurable_options'] = $this->load->view('plan/detail_jes', $data, TRUE);
		} else if ($data['plan']['product_short'] == 'JESP') {
			$data['insurable_options'] = $this->load->view('plan/detail_jes', $data, TRUE);
		} else if ($data['plan']['product_short'] == 'JFC') {
			$data['insurable_options'] = $this->load->view('plan/detail_jes', $data, TRUE);
		} else if ($data['plan']['product_short'] == 'JFP') {
			$data['insurable_options'] = $this->load->view('plan/detail_jes', $data, TRUE);
		} else {
			$data['insurable_options'] = $this->load->view('plan/detail_other', $data, TRUE);
		}

		$data['insure_co'] = 'Allianz Travel Insurance Coordinators Ltd';
		if (($plan['product_short'] == 'JFR') || ($plan['product_short'] == 'JES') || ($plan['product_short'] == 'JESP') || ($plan['product_short'] == 'JFS') || ($plan['product_short'] == 'JFE') || ($plan['product_short'] == 'BHS')) {
			$data['insure_co'] = "Berkley Canada";
		} else if ($plan['product_short'] == 'JFVTC') {
      $data['insure_co'] = "Old Republic";
    }
		$data['style'] = $this->load->view('common/pdf_style',$data, TRUE);
		$html = $this->load->view('plan/cancel', $data, TRUE);
		$mpdf = new mPDF('c');
		$mpdf->writeHTML($html);
		$mpdf->Output("policy_cancel.pdf","I");
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
		$data['html_model'] = $this->html_model;
		if ($data['plan']['product_short'] == 'OPL') {
			$data['insurable_options'] = $this->load->view('plan/detail_opl', $data, TRUE);
		} else if (($data['plan']['product_short'] == 'JFVTC') || ($data['plan']['product_short'] == 'JFR')) {
			$data['insurable_options'] = $this->load->view('plan/detail_opl', $data, TRUE);
		} else if ($data['plan']['product_short'] == 'JUS') {
			$data['insurable_options'] = $this->load->view('plan/detail_jus', $data, TRUE);
		} else if ($data['plan']['product_short'] == 'NUS') {
			$data['insurable_options'] = $this->load->view('plan/detail_jus', $data, TRUE);
		} else if (($data['plan']['product_short'] == 'JFS') || ($data['plan']['product_short'] == 'JFE') || ($data['plan']['product_short'] == 'BHS')) {
			$data['insurable_options'] = $this->load->view('plan/detail_jes', $data, TRUE);
		} else if (($data['plan']['product_short'] == 'JES') || ($data['plan']['product_short'] == 'JFPL') || ($data['plan']['product_short'] == 'JFSL')) {
			$data['insurable_options'] = $this->load->view('plan/detail_jes', $data, TRUE);
		} else if ($data['plan']['product_short'] == 'JESP') {
			$data['insurable_options'] = $this->load->view('plan/detail_jes', $data, TRUE);
		} else if ($data['plan']['product_short'] == 'JFC') {
			$data['insurable_options'] = $this->load->view('plan/detail_jes', $data, TRUE);
		} else if ($data['plan']['product_short'] == 'JFP') {
			$data['insurable_options'] = $this->load->view('plan/detail_jes', $data, TRUE);
		} else {
			$data['insurable_options'] = $this->load->view('plan/detail_other', $data, TRUE);
		}

		//print_r($this->input->post());die('====');

		if ($this->input->post('customer_full_name')) {
			$data['insure_co'] = 'Allianz Travel Insurance Coordinators Ltd';
			if (($plan['product_short'] == 'JFR') || ($plan['product_short'] == 'JES') || ($plan['product_short'] == 'JFPL') || ($plan['product_short'] == 'JESP') || ($plan['product_short'] == 'JFS') || ($plan['product_short'] == 'JFE') || ($plan['product_short'] == 'BHS')) {
				$data['insure_co'] = "Berkley Canada";
			} else if (($plan['product_short'] == 'JFVTC') || ($data['plan']['product_short'] == 'JFSL')) {
        $data['insure_co'] = "Old Republic";
      }
			$data['customer_full_name'] = $this->input->post('customer_full_name');
			$data['full_address'] = $this->input->post('full_address');
			$data['city'] = $this->input->post('city');
			$data['province2'] = $this->input->post('province2');
			$data['postcode'] = $this->input->post('postcode');
			$data['style'] = $this->load->view('common/pdf_style',$data, TRUE);
			$html = $this->load->view('plan/refund', $data, TRUE);
			$mpdf = new mPDF('c');
			$mpdf->writeHTML($html);
			$mpdf->Output("policy_refund.pdf","I");
		} else {
			$data['customer_full_name'] = $data['customer']['firstname'] . " " . $data['customer']['lastname'];
			$data['full_address'] = empty($plan['suite_number']) ? '' : "Suite " . $plan['suite_number'] . " ";
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
		$data['html_model'] = $this->html_model;
		if ($data['plan']['product_short'] == 'OPL') {
			$data['cardp'] = "opl";
			$data['insurable_options'] = $this->load->view('plan/detail_opl', $data, TRUE);
		} else if (($data['plan']['product_short'] == 'JFVTC') || ($data['plan']['product_short'] == 'JFR')) {
			$data['cardp'] = "jfr";
			$data['insurable_options'] = $this->load->view('plan/detail_opl', $data, TRUE);
		} else if ($data['plan']['product_short'] == 'JUS') {
			$data['cardp'] = "jus";
			$data['insurable_options'] = $this->load->view('plan/detail_jus', $data, TRUE);
		} else if ($data['plan']['product_short'] == 'NUS') {
			$data['cardp'] = "nus";
			$data['insurable_options'] = $this->load->view('plan/detail_jus', $data, TRUE);
		} else if (($data['plan']['product_short'] == 'JFS') || ($data['plan']['product_short'] == 'JFE') || ($data['plan']['product_short'] == 'BHS')) {
			$data['cardp'] = "jes";
			$data['insurable_options'] = $this->load->view('plan/detail_jes', $data, TRUE);
		} else if (($data['plan']['product_short'] == 'JES') || ($data['plan']['product_short'] == 'JFPL') || ($data['plan']['product_short'] == 'JFSL')) {
			$data['cardp'] = "jes";
			$data['insurable_options'] = $this->load->view('plan/detail_jes', $data, TRUE);
		} else if ($data['plan']['product_short'] == 'JESP') {
			$data['cardp'] = "jes";
			$data['insurable_options'] = $this->load->view('plan/detail_jes', $data, TRUE);
		} else if ($data['plan']['product_short'] == 'JFC') {
			$data['cardp'] = "jfc";
			$data['insurable_options'] = $this->load->view('plan/detail_jes', $data, TRUE);
		} else if ($data['plan']['product_short'] == 'JFP') {
			$data['cardp'] = "jfp";
			$data['insurable_options'] = $this->load->view('plan/detail_jes', $data, TRUE);
		} else if ($data['plan']['product_short'] == 'TOP') {
			$data['cardp'] = "top";
			$data['insurable_options'] = $this->load->view('plan/top/card', $data, TRUE);
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
		$mpdf->Output("policy_card.pdf","I");
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
		$data['html_model'] = $this->html_model;
		if ($data['plan']['product_short'] == 'OPL') {
			$data['insurable_options'] = $this->load->view('plan/detail_opl', $data, TRUE);
		} else if (($data['plan']['product_short'] == 'JFVTC') || ($data['plan']['product_short'] == 'JFR')) {
			$data['insurable_options'] = $this->load->view('plan/detail_opl', $data, TRUE);
		} else if ($data['plan']['product_short'] == 'JUS') {
			$data['insurable_options'] = $this->load->view('plan/detail_jus', $data, TRUE);
		} else if ($data['plan']['product_short'] == 'NUS') {
			$data['insurable_options'] = $this->load->view('plan/detail_jus', $data, TRUE);
		} else if (($data['plan']['product_short'] == 'JFS') || ($data['plan']['product_short'] == 'JFE') || ($data['plan']['product_short'] == 'BHS')) {
			$data['insurable_options'] = $this->load->view('plan/detail_jes', $data, TRUE);
		} else if (($data['plan']['product_short'] == 'JES') || ($data['plan']['product_short'] == 'JES')) {
			$data['insurable_options'] = $this->load->view('plan/detail_jes', $data, TRUE);
		} else if ($data['plan']['product_short'] == 'JESP') {
			$data['insurable_options'] = $this->load->view('plan/detail_jes', $data, TRUE);
		} else if ($data['plan']['product_short'] == 'JFC') {
			$data['insurable_options'] = $this->load->view('plan/detail_jes', $data, TRUE);
		} else if ($data['plan']['product_short'] == 'JFP') {
			$data['insurable_options'] = $this->load->view('plan/detail_jes', $data, TRUE);
		} else if ($data['plan']['product_short'] == 'TOP') {
			$data['insurable_options'] = $this->load->view('plan/top/card', $data, TRUE);
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
		$data['agent'] = $this->user_model->get_user_by_id($plan['user_id']);
		
		$mpdf = new mPDF('c');
		$data['title_txt'] = 'Receipt';
		$data['style'] = $this->load->view('common/pdf_style',$data, TRUE);
		$html = $this->load->view('plan/receipt', $data, TRUE);
		$mpdf->writeHTML($html);
		$mpdf->Output("receipt.pdf","I");
	}
		
	public function Renewal($plan_id) {
		$beuser = $this->func_model->verify_login(TRUE);
		$this->load->model('plan_model');
		$plan = $this->plan_model->get_plan_by_id($plan_id);
		if ($plan) {
			unset($plan['plan_id']);
			// unset($plan['customer_id']);
			unset($plan['user_id']);
			unset($plan['status_id']);
			// unset($plan['policy']);
			// unset($plan['agree']);
			unset($plan['batch_number']);
			// unset($plan['spouse']);
			// unset($plan['student_id']);
			unset($plan['payment_id']);
			unset($plan['commission_payment_id']);
			// unset($plan['payinfo']);
			// unset($plan['note']);
			// unset($plan['premium']);
			// unset($plan['ip']);
			unset($plan['effective_date']);
			unset($plan['expiry_date']);
			unset($plan['totaldays']);
			unset($plan['premium']);
			unset($plan['note']);
			$plan['apply_date'] = date('Y-m-d');
		}
		$this->form($plan);
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
			unset($plan['premium']);
			unset($plan['ip']);
			$plan['apply_date'] = date('Y-m-d');
		}
		$this->form($plan);
	}

	public function edit($plan_id=0) {
		$this->load->model('plan_model');
		$plan = $this->plan_model->get_plan_by_id($plan_id);
		$this->form($plan);
	}
}
