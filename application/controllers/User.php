<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
class User extends MY_Controller {
	const PASSWORD_MIN = 6;
	const PASSWORD_MAX = 16;
	const PERPAGE = 15;
	private $data;
	
	/**
	 * default for this controller. it is login page.
	 */
	public function index() {
		return $this->dtlist();
	}

	public function logout() {
		$this->session->unset_userdata ( 'user' );
		$this->session->unset_userdata ( 'beuser' );
		redirect ( base_url ( "user" ) );
	}
	
	/**
	 * Login verify
	 *
	 * @param
	 *        	$this->input->post.....
	 * @return boolean
	 */
	private function login_verify() {
		$rt = TRUE;
		if (empty ( $this->input->post ( 'username' ) )) {
			$this->data ['username_error'] = $this->lang->line ( 'error_username_input' );
			;
			$rt = FALSE;
		}
		
		$pw = $this->input->post ( 'password' );
		if (empty ( trim($pw) ) || (strlen ( $pw ) > self::PASSWORD_MAX)) {
			$this->data ['password_error'] = $this->lang->line ( 'error_password_input' );
			$rt = FALSE;
		}
		
		return $rt;
	}
	
	/**
	 *
	 * User information verification
	 *
	 * @param
	 *        	$this->input->post.....
	 * @return boolean
	 */
	private function verify() {
		$rt = TRUE;
		$group_id = (int)$this->input->post('user_group_id');
		if ($group_id < 1) {
			$this->data['error_user_group'] = $this->lang->line('error_user_group');
			$rt = FALSE;
		}
		// $this->input->post('parent_user_id');
		if (empty(trim($this->input->post('username')))) {
			$this->data['error_username'] = $this->lang->line('error_username');
			$rt = FALSE;
		}
		$password = trim($this->input->post('password'));
		if (!empty($password) && ((strlen($password) < self::PASSWORD_MIN) || (strlen($password) > self::PASSWORD_MAX))) {
			$this->data['error_password'] = $this->lang->line('error_password');
			$rt = FALSE;
		}
		// $this->data['region'] = $this->input->post('region');
		// $this->data['business'] = $this->input->post('business');
		// $this->data['gender'] = $this->input->post('gender');
		if (empty(trim($this->input->post('business')))) {
			$this->data['error_business'] = $this->lang->line('error_business');
			$rt = FALSE;
		}
		if (empty(trim($this->input->post('firstname')))) {
			$this->data['error_firstname'] = $this->lang->line('error_firstname');
			$rt = FALSE;
		}
		if (empty(trim($this->input->post('lastname')))) {
			$this->data['error_lastname'] = $this->lang->line('error_lastname');
			$rt = FALSE;
		}
		if (empty(trim($this->input->post('email'))) || ( ! $this->verify_model->isEmail($this->input->post('email')))) {
			$this->data['error_email'] = $this->lang->line('error_email');
			$rt = FALSE;
		}
		if (empty(trim($this->input->post('address')))) {
			$this->data['error_address'] = $this->lang->line('error_address');
			$rt = FALSE;
		}
		if (empty(trim($this->input->post('city')))) {
			$this->data['error_city'] = $this->lang->line('error_city');
			$rt = FALSE;
		}
		if (empty(trim($this->input->post('province2')))) {
			$this->data['error_province'] = $this->lang->line('error_province');
			$rt = FALSE;
		}
		if (empty(trim($this->input->post('postcode')))) {
			$this->data['error_postcode'] = $this->lang->line('error_postcode');
			$rt = FALSE;
		}
		if (empty(trim($this->input->post('mail_address')))) {
			$this->data['error_mail_address'] = $this->lang->line('error_mail_address');
			$rt = FALSE;
		}
		if (empty(trim($this->input->post('mail_city')))) {
			$this->data['error_mail_city'] = $this->lang->line('error_mail_city');
			$rt = FALSE;
		}
		if (empty(trim($this->input->post('mail_province2')))) {
			$this->data['error_mail_province'] = $this->lang->line('error_mail_province');
			$rt = FALSE;
		}
		if (empty(trim($this->input->post('mail_postcode')))) {
			$this->data['error_mail_postcode'] = $this->lang->line('error_mail_postcode');
			$rt = FALSE;
		}
		if (empty($this->input->post('paytype_list'))) {
			$this->data['error_paytype_list'] = $this->lang->line('error_paytype_list');
			$rt = FALSE;
		}
		// $this->data['website'] = $this->input->post('website');
		if (empty(trim($this->input->post('licence_number')))) {
			$this->data['error_licence_number'] = $this->lang->line('error_licence_number');
			$rt = FALSE;
		}
		if (empty(trim($this->input->post('licence_expire'))) ) {
			$this->data['error_licence_expire'] = $this->lang->line('error_licence_expire');
			$rt = FALSE;
		} else {
			$tm = strtotime($this->input->post('licence_expire'));
			if ($tm < time()) {
				$this->data['error_licence_expire'] = $this->lang->line('error_licence_expire');
				$rt = FALSE;
			}
		}
		//$this->data['business_phone'] = $this->input->post('business_phone');
		//$this->data['mobile_phone'] = $this->input->post('mobile_phone');
		//$this->data['fax_number'] = $this->input->post('fax_number');
		//$this->data['toll_free'] = $this->input->post('toll_free');
		//$this->data['product_list'] = $this->input->post('product_list');
		//$this->data['pay_type'] = $this->input->post('pay_type');
		//$this->data['ip'] = $this->input->post('ip');
		//$this->data['status'] = $this->input->post('status');
		//$this->data['date_added'] = $this->input->post('date_added');
		//$this->data['note'] = $this->input->post('note');
		// user porduction
		return $rt;
	}
	
	/**
	 * User list current user list
	 */
	public function install() {
		die($this->user_model->installadmin());
	}
	
	/**
	 * User list current user list
	 */
	public function dtlist() {
		if (! $this->func_model->verify_level ( 104 )) {
			// Login user
			$this->session->set_userdata ( "error_message", $this->lang->line ( 'error_no_permission' ) );
			redirect ( base_url ( 'errorpage' ) );
		}
		
		// Get all text depend language
		//$data = $this->lang->language;
		$this->load->model('user_group_model');
		$this->load->model('region_model');
		$this->load->library('pagination');
		
		$data['user_group_list'] = $this->user_group_model->get_user_group_list(1);	// Get full list
		$data['user_list'] = $this->user_model->get_user_list($this->session->beuser['user_group_id'], $this->session->beuser['user_id'], $this->input->get(), self::PERPAGE, ($this->input->get('per_page') * self::PERPAGE) );
		$data['user_list_total'] = $this->user_model->get_user_list_total($this->session->beuser['user_group_id'], $this->session->beuser['user_id'], $this->input->get());
		$data['action_url'] = current_url();
		$data['edit_url'] = base_url('user/edit')."?user_id=";
		$data['user_group_id'] = $this->input->post ( 'user_group_id' );
		$data['region_id'] = $this->input->post ( 'region_id' );
		$data['username'] = $this->input->post ( 'username' );
		$data['firstname'] = $this->input->post ( 'firstname' );
		$data['lastname'] = $this->input->post ( 'lastname' );
		$data['email'] = $this->input->post ( 'email' );
		$data['business'] = $this->input->post ( 'business' );
		$data['broker_list'] = $this->user_model->get_broker_id_list();
		
		$data['beuser_group_id'] = $this->session->beuser['user_group_id'];
		$data['beuser_region_id'] = $this->session->beuser['region_id'];
		$data['regions'] = $this->region_model->get_regions();
		if ($this->session->beuser == $this->session->user) {
			$data['behalf_url'] = base_url('behalf/to') . "/";
		} else {
			$data['behalf_url'] = '';
		}
		
		$searchURL = current_url();
		$para = $this->input->get();
		if (!empty($para)) {
			if (key_exists('per_page', $para)) {
				unset($para['per_page']);
			}
			$searchURL .= "?" . http_build_query($para);
		}
		$pgArr = array(
				'base_url' => $searchURL,
				'total_rows' => $data['user_list_total'],
				'per_page' => self::PERPAGE,
				'page_query_string' => TRUE,
				'use_page_numbers' => TRUE,
				'uri_segment' => 3,
		);
		$this->pagination->initialize($pgArr);
		
		$data['pagination'] = $this->pagination->create_links();
		
		$data ['csrf'] = array (
				'name' => $this->security->get_csrf_token_name (),
				'value' => $this->security->get_csrf_hash () 
		);

		$data['top_menu'] = $this->menu_model->load_top_menu();
		$data['menu'] = $this->menu_model->load_meun();
		$this->load->common ( 'user/dtlist', $data );
	}
	
	/**
	 * User information edit / add page
	 */
	public function edit() {
		if (! $this->func_model->verify_level ( 2 )) {
			// Login user
			$this->session->set_userdata ( "error_message", $this->lang->line ( 'error_no_permission' ) );
			redirect ( base_url ( 'errorpage' ) );
		}
		
		$this->load->model('province_model');
		$this->load->model('product_model');
		$this->load->model('paytype_model');
		$this->load->model('user_group_model');
		$this->load->model('region_model');
		$this->load->model('verify_model');
		
		//$this->data = $this->lang->language;
		$user_id = $this->input->get_post('user_id');
		$this->data['user_id'] = $user_id;
		if ($this->input->post () && $this->verify ()) {
			if ($this->user_model->check_username( $user_id, $this->input->post('username'))) {
				$this->data['error_message'] = "username existed, please select other username";
			} else {
				$this->user_model->update ( $user_id, $this->input->post () );
				$this->log_model->activity('user', array('message' => $this->user_model->logstr, 'systemlog' => $this->user_model->sqlstr));
				redirect ( base_url ('user') );
			}
		}
		
		// Get all text depend language
		$this->data['user_group_list'] = $this->user_group_model->get_user_group_list($this->session->user['user_group_id']);
		$this->data['broker_list'] = $this->user_model->get_broker_id_list();
		$this->data['province_list'] = $this->province_model->province_list();
		
		$this->data['op_user_group_id'] = $this->session->beuser['user_group_id'];
		$this->data['user_group_id'] = '';
		$this->data['parent_user_id'] = ($this->session->beuser['user_group_id'] < 100) ? 0 : $this->session->beuser['user_id'];
		$this->data['username'] = '';
		$this->data['password'] = '';
		$this->data['region_id'] = $this->session->beuser['region_id'];
		$this->data['business'] = ($this->session->beuser['user_group_id'] < 100) ? '' : $this->session->beuser['business'];
		$this->data['gender'] = '';
		$this->data['firstname'] = '';
		$this->data['lastname'] = '';
		$this->data['email'] = '';
		$this->data['address'] = '';
		$this->data['city'] = '';
		$this->data['province2'] = 'ON';
		$this->data['country2'] = 'CA';
		$this->data['postcode'] = '';
		$this->data['mail_address'] = '';
		$this->data['mail_city'] = '';
		$this->data['mail_province2'] = 'ON';
		$this->data['mail_country2'] = 'CA';
		$this->data['mail_postcode'] = '';
		$this->data['website'] = '';
		$this->data['licence_number'] = '';
		$this->data['licence_expire'] = '';
		$this->data['business_phone'] = '';
		$this->data['mobile_phone'] = '';
		$this->data['fax_number'] = '';
		$this->data['toll_free'] = '';
		$this->data['pay_type'] = '';
		$this->data['receive_type'] = 'Cheque';
		$this->data['ip'] = '';
		$this->data['status'] = '';
		$this->data['date_added'] = '';
		$this->data['note'] = '';

		$product_list = $this->product_model->product_list(1);
		$plist = array();
		foreach ($product_list as $p) {
			$p['checked'] = '';
			if (empty($user_id) && ($p['product_short'] != 'JFC')) {
				$p['checked'] = 'checked';
			} 
			$plist[$p['product_short']] = $p;
		}
		$this->data['product_list'] = $plist;
		$this->data['paytype_list'] = $this->paytype_model->paytype_list();
		
		if ($this->input->post()) {
			$this->data['user_group_id'] = $this->input->post('user_group_id');
			$this->data['parent_user_id'] = $this->input->post('parent_user_id');
			$this->data['username'] = $this->input->post('username');
			$this->data['password'] = $this->input->post('password');
			$this->data['region_id'] = $this->input->post('region_id');
			$this->data['business'] = $this->input->post('business');
			$this->data['gender'] = $this->input->post('gender');
			$this->data['firstname'] = $this->input->post('firstname');
			$this->data['lastname'] = $this->input->post('lastname');
			$this->data['email'] = $this->input->post('email');
			$this->data['address'] = $this->input->post('address');
			$this->data['city'] = $this->input->post('city');
			$this->data['province2'] = $this->input->post('province2');
			$this->data['country2'] = $this->input->post('country2');
			$this->data['postcode'] = $this->input->post('postcode');
			$this->data['mail_address'] = $this->input->post('mail_address');
			$this->data['mail_city'] = $this->input->post('mail_city');
			$this->data['mail_province2'] = $this->input->post('mail_province2');
			$this->data['mail_country2'] = $this->input->post('mail_country2');
			$this->data['mail_postcode'] = $this->input->post('mail_postcode');
			$this->data['website'] = $this->input->post('website');
			$this->data['licence_number'] = $this->input->post('licence_number');
			$this->data['licence_expire'] = $this->input->post('licence_expire');
			$this->data['business_phone'] = $this->input->post('business_phone');
			$this->data['mobile_phone'] = $this->input->post('mobile_phone');
			$this->data['fax_number'] = $this->input->post('fax_number');
			$this->data['toll_free'] = $this->input->post('toll_free');
			$this->data['ip'] = $this->input->post('ip');
			$this->data['status'] = $this->input->post('status');
			$this->data['date_added'] = $this->input->post('date_added');
			$this->data['note'] = $this->input->post('note');
			foreach ($this->data['product_list'] as $k => $p) {
				if (isset($_POST['product_list'][$k])) {
					$this->data['product_list'][$k]['checked'] = 'checked';
				}
				$this->data['product_list'][$k]['commission'] = $this->input->post('product_commission_'.$k);
			}
			$pt = ' ';
			foreach ($this->data['paytype_list'] as $k => $p) {
				if (isset($_POST['paytype_list']) && in_array($p, $_POST['paytype_list'])) {
					$pt .= "," . $p;
				}
			}
			$this->data['pay_type'] = $pt;
			$this->data['receive_type'] = $this->input->post('receive_type');
		} else if ($user_id) {
			$user = $this->user_model->get_user_by_id($user_id);
			$user_product_list = $this->user_model->get_user_product_list($user_id);
			if ($user && empty($this->input->post())) {
				$this->data = array_merge($this->data, $user);
			}
			foreach ($this->data['product_list'] as $k => $p) {
				foreach($user_product_list as $up) {
					if ($p['product_short'] == $up['product_short']) {
						$p['checked'] = 'checked';
						$p['commission'] = $up['commission'];
					}
				}
				$this->data['product_list'][$k] = $p;
			}
		}
		if (empty($this->data['province2'])) $this->data['province2'] = 'ON';
		if (empty($this->data['country2'])) $this->data['country2'] = 'CA';
		$this->data['regions'] = $this->region_model->get_regions();
		$this->data['province_url'] = base_url ( "geo/province/" . $this->data['country2'] . "/" . $this->data['province2'] );
		$this->data['country_url'] = base_url ( "geo/country/" . $this->data['country2'] );
		$this->data['mail_province_url'] = base_url ( "geo/province/" . $this->data['mail_country2'] . "/" . $this->data['mail_province2'] );
		$this->data['mail_country_url'] = base_url ( "geo/country/" . $this->data['mail_country2'] );
		$this->data['action_url'] = base_url('user/edit');
		$this->data ['csrf'] = array (
				'name' => $this->security->get_csrf_token_name (),
				'value' => $this->security->get_csrf_hash () 
		);

		$this->data['top_menu'] = $this->menu_model->load_top_menu();
		$this->data['menu'] = $this->menu_model->load_meun();
		$this->load->common ( 'user/edit', $this->data );
	}
	
	/**
	 * User reset password page
	 */
	public function resetpassword() {
		if (! $this->func_model->verify_login()) {
			// Login user
			$this->session->set_userdata ( "error_message", $this->lang->line ( 'error_no_permission' ) );
			redirect ( base_url ( 'errorpage' ) );
		}
		
		if ($this->input->post ()) {
			$password = $this->input->post ('password');
			$password2 = $this->input->post ('password2');
			if (empty($password) || empty($password2)) {
				$this->data['error_message'] = "Please input your new password";
			} else if ($password != $password2){
				$this->data['error_message'] = "New password and verify password are different, please reinput";
			} else if ((strlen ( $password ) < self::PASSWORD_MIN) || (strlen ( $password ) > self::PASSWORD_MAX)) {
				$this->data ['error_message'] = $this->lang->line ( 'error_password_input' );
			} else {
				$this->user_model->update ( $this->session->beuser['user_id'], array('password' => $password, 'status' => 1), 0);
				$this->log_model->activity('user', array('message' => "Reset Password: ".$this->user_model->logstr, 'systemlog' => "Reset Password: ".$this->user_model->sqlstr));
				redirect ( base_url ('product') );
			}
		}
		
		$this->data['action_url'] = base_url('user/resetpassword');
		$this->data ['csrf'] = array (
				'name' => $this->security->get_csrf_token_name (),
				'value' => $this->security->get_csrf_hash () 
		);

		$this->data['top_menu'] = $this->menu_model->load_top_menu();
		$this->data['menu'] = $this->menu_model->load_meun();
		$this->load->common ( 'user/resetpassword', $this->data );
	}
	
	/**
	 * User login
	 */
	public function login() {
		if ($this->session->userdata ( 'user' )) {
			// Login user
			redirect ( base_url () );
		}
		
		// Get all text depend language
		$this->data = $this->lang->language;
		$this->data ['username'] = '';
		$this->data ['password'] = '';
		$this->data ['error_message'] = '';
		$this->data ['username_error'] = '';
		$this->data ['password_error'] = '';
		$this->data ['action_url'] = current_url ();
		if ($this->input->post () && $this->login_verify ()) {
			// Login post check
			$r = $this->user_model->login ( $this->input->post ( 'username' ), $this->input->post ( 'password' ) );
			if ($r) {
				if (is_array($r)) {
					$this->session->set_userdata ( 'user', $r );
					$this->session->set_userdata ( 'beuser', $r );
					if ($r['forcepw']) {
						redirect ( base_url ('user/resetpassword') );
					}
					redirect ( base_url ('product') );
				} else {
					$this->data['error_message'] = $r;
				}
			} else {
				$this->data['error_message'] = $this->lang->line ( 'error_no_user_found' );
			}
		}
		if ($this->input->post ( 'username' )) {
			$data ['username'] = $this->input->post ( 'username' );
		}
		
		if ($this->input->post ( 'password' )) {
			$data ['password'] = $this->input->post ( 'password' );
		}
		
		$this->data ['csrf'] = array (
				'name' => $this->security->get_csrf_token_name (),
				'value' => $this->security->get_csrf_hash () 
		);
		$this->data['top_menu'] = $this->menu_model->load_top_menu();
		$this->load->common ( 'user/login', $this->data );
	}
}
