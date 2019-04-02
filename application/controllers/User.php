<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

use Box\Spout\Common\Type;
use Box\Spout\Writer\WriterFactory;

class User extends MY_Controller {
	const PASSWORD_MIN = 6;
	const PASSWORD_MAX = 16;
	const PERPAGE = 15;
	
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
		
		if (!empty($_FILES)) {
			$this->load->library('upload');
			$this->load->library('image_lib');
			$this->load->model('myhome_model');
			if (!empty($_FILES['logo_src']) && !empty($_FILES['logo_src']['tmp_name'])) {
				$logo = $this->myhome_model->get_pdf_logo_filename();
				foreach (glob(AGENTINFODIR . $logo ."*") as $filename) {
					unlink($filename);
				}
			
				$para = array(
						'allowed_types' => 'gif|jpg|png', 
						'file_name' => $logo,
						'upload_path' => AGENTINFODIR,
						'file_ext_tolower' => TRUE
				);
				$this->upload->initialize($para);
				if ( ! $this->upload->do_upload('logo_src')) {
					$this->data['error_myname_logo'] = $this->lang->line ( 'error_myname_logo' );
					$rt = FALSE;
				} else {
					$filedata = $this->upload->data();
					/*
					[file_name] => logeccbc87e4b5ce2fe28308fd9f2a7baf33.png
					[file_type] => image/png
					[file_path] => /home/jackw/Public/jfgroup/agentinfo/
					[full_path] => /home/jackw/Public/jfgroup/agentinfo/logeccbc87e4b5ce2fe28308fd9f2a7baf33.png
					[raw_name] => logeccbc87e4b5ce2fe28308fd9f2a7baf33
					[orig_name] => logeccbc87e4b5ce2fe28308fd9f2a7baf3.png
					[client_name] => 2.PNG
					[file_ext] => .png
					[file_size] => 296.71
					[is_image] => 1
					[image_width] => 1409
					[image_height] => 974
					[image_type] => png
					[image_size_str] => width="1409" height="974"
					*/	
					$disfile = $filedata['raw_name'] . "_thumb" . $filedata['file_ext'];
					if (file_exists($disfile)) {
						unlink($disfile);
					}
					$imgpara = array(
							'image_library' => 'gd2',
							'source_image' => $filedata['full_path'],
							'maintain_ratio' => TRUE,
							'create_thumb' => TRUE,
							'width' => $this->myhome_model->get_pdf_logo_width(),
					);
					$this->image_lib->initialize($imgpara);
					$this->image_lib->resize();
					$this->image_lib->clear();
					$this->data['pdf_logo'] = $disfile;
				}
			}

			if (!empty($_FILES['qr_src']) && !empty($_FILES['qr_src']['tmp_name'])) {
				$image = $this->myhome_model->get_pdf_filename('qr');
				foreach (glob(AGENTINFODIR . $image ."*") as $filename) {
					unlink($filename);
				}
			
				$para = array(
						'allowed_types' => 'gif|jpg|png',
						'file_name' => $image,
						'upload_path' => AGENTINFODIR,
						'file_ext_tolower' => TRUE
				);
				$this->upload->initialize($para);
				if ( ! $this->upload->do_upload('qr_src')) {
					$this->data['error_myname_image'] = $this->lang->line ( 'error_myname_image' );
					$rt = FALSE;
				} else {
					$filedata = $this->upload->data();
					$disfile = $filedata['raw_name'] . "_thumb" . $filedata['file_ext'];
					if (file_exists($disfile)) {
						unlink($disfile);
					}
					$imgpara = array(
							'image_library' => 'gd2',
							'source_image' => $filedata['full_path'],
							'maintain_ratio' => TRUE,
							'create_thumb' => TRUE,
							'width' => $this->myhome_model->get_pdf_width('qr'),
					);
					$this->image_lib->initialize($imgpara);
					$this->image_lib->resize();
					$this->image_lib->clear();
					$this->data['pdf_qr'] = $disfile;
				}
			}
				
			if (!empty($_FILES['qr2_src']) && !empty($_FILES['qr2_src']['tmp_name'])) {
				$image = $this->myhome_model->get_pdf_filename('qr2');
				foreach (glob(AGENTINFODIR . $image ."*") as $filename) {
					unlink($filename);
				}
				
				$para = array(
						'allowed_types' => 'gif|jpg|png', 
						'file_name' => $image,
						'upload_path' => AGENTINFODIR,
						'file_ext_tolower' => TRUE
				);
				$this->upload->initialize($para);
				if ( ! $this->upload->do_upload('qr2_src')) {
					$this->data['error_myname_image'] = $this->lang->line ( 'error_myname_image' );
					$rt = FALSE;
				} else {
					$filedata = $this->upload->data();
					$disfile = $filedata['raw_name'] . "_thumb" . $filedata['file_ext'];
					if (file_exists($disfile)) {
						unlink($disfile);
					}
					$imgpara = array(
							'image_library' => 'gd2',
							'source_image' => $filedata['full_path'],
							'maintain_ratio' => TRUE,
							'create_thumb' => TRUE,
							'width' => $this->myhome_model->get_pdf_width('qr2'),
					);
					$this->image_lib->initialize($imgpara);
					$this->image_lib->resize();
					$this->image_lib->clear();
					$this->data['pdf_qr2'] = $disfile;
				}
			}
		}
		
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
		} else if ($this->session->beuser['user_group_id'] > 100) {
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
	public function export() {
		$beuser = $this->func_model->verify_login();
		if (($beuser['user_id'] == '1') || ($beuser['user_id'] == '2762')) {
			$user_list = $this->user_model->get_user_list($this->session->beuser['user_group_id'], $this->session->beuser['user_id'] );
			
			$w = WriterFactory::create(Type::XLSX); // for XLSX files
			$w->openToBrowser("user_" . date('Ymd') . ".xlsx");
			//$w->openToFile($tmpfname);
			$w->addRow(array('User id', 'username', 'first name', 'last name', 'email', 'province', 'license number', 'license expire'));
			
			foreach($user_list as $agent) {
				$w->addRow(array(
						$agent['user_id'], 
						$agent['username'],
						$agent['firstname'],
						$agent['lastname'],
						$agent['email'],
						$agent['province2'],
						$agent['licence_number'],
						$agent['licence_expire']
						));
			}
			$w->close();
		}
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
		
		$per_page = empty($this->input->get('per_page')) ? 1 : $this->input->get('per_page');
		if ($per_page < 1) $per_page = 1;

		$data['user_group_list'] = $this->user_group_model->get_user_group_list(1);	// Get full list
		$data['user_list'] = $this->user_model->get_user_list($this->session->beuser['user_group_id'], $this->session->beuser['user_id'], $this->input->get(), self::PERPAGE, (($per_page - 1) * self::PERPAGE) );
		$data['user_list_total'] = $this->user_model->get_user_list_total($this->session->beuser['user_group_id'], $this->session->beuser['user_id'], $this->input->get());
		$data['action_url'] = current_url();
		$data['edit_url'] = base_url('user/edit')."?user_id=";
		$data['user_group_id'] = $this->input->post ( 'user_group_id' );
		$data['region_id'] = $this->input->post ( 'region_id' );
		$data['user_id'] = $this->input->post ( 'user_id' );
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
		$data['beuser_user_id'] = $this->session->beuser['user_id'];
		$data['export_url'] = base_url('user/export');
		
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
				$post = $this->input->post();
				if (!empty($this->data['pdf_logo'])) $post['pdf_logo'] = $this->data['pdf_logo'];
				if (!empty($this->data['pdf_qr'])) $post['pdf_qr'] = $this->data['pdf_qr'];
				if (!empty($this->data['pdf_qr2'])) $post['pdf_qr2'] = $this->data['pdf_qr2'];
				$this->user_model->update ( $user_id, $post, 1, array('product_list' => 1));
				$this->log_model->activity('user', array('message' => $this->user_model->logstr, 'systemlog' => $this->user_model->sqlstr));
				$this->product_model->set_product_customize($user_id, $this->input->post('product_customize'));
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
		$this->data['mail_name'] = '';
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
		$this->data['note2'] = '';
		$this->data['date_added'] = '';
		$this->data['enable_pdf'] = 0;
		$this->data['pdf_logo'] = '';
		$this->data['pdf_qr'] = '';
		$this->data['pdf_qr2'] = '';
		$this->data['pdf_f_left1'] = '';
		$this->data['pdf_f_left2'] = '';
		$this->data['pdf_f_left3'] = '';
		$this->data['pdf_f_left4'] = '';
		$this->data['pdf_f_left5'] = '';
		$this->data['pdf_f_left6'] = '';
		$this->data['pdf_f_right1'] = '';
		$this->data['pdf_f_right2'] = '';
		$this->data['pdf_f_right3'] = '';
		$this->data['pdf_f_right4'] = '';
		$this->data['pdf_f_right5'] = '';
		$this->data['pdf_f_right6'] = '';
		
		$product_list = $this->product_model->product_list(1);
		$plist = array();
		$pdf_plist = array();
		$product_customize = array();
		foreach ($product_list as $p) {
			$p['checked'] = '';
			if (empty($user_id) && ($p['product_short'] != 'JFC') && ($p['product_short'] != 'NUS') && ($p['product_short'] != 'JUS')) {
				$p['checked'] = 'checked';
			} 
			$plist[$p['product_short']] = $p;
			$pdf_plist[$p['product_short']] = '';
			$product_customize[$p['product_short']] = '';
		}
		$this->data['product_list'] = $plist;
		$this->data['pdf_product_list'] = $pdf_plist;
		$this->data['product_customize'] = $product_customize;
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
			$this->data['mail_name'] = $this->input->post('mail_name');
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
			$this->data['note2'] = $this->input->post('note2');
			$this->data['pdf_f_left1'] = $this->input->post('pdf_f_left1');
			$this->data['pdf_f_left2'] = $this->input->post('pdf_f_left2');
			$this->data['pdf_f_left3'] = $this->input->post('pdf_f_left3');
			$this->data['pdf_f_left4'] = $this->input->post('pdf_f_left4');
			$this->data['pdf_f_left5'] = $this->input->post('pdf_f_left5');
			$this->data['pdf_f_left6'] = $this->input->post('pdf_f_left6');
			$this->data['pdf_f_right1'] = $this->input->post('pdf_f_right1');
			$this->data['pdf_f_right2'] = $this->input->post('pdf_f_right2');
			$this->data['pdf_f_right3'] = $this->input->post('pdf_f_right3');
			$this->data['pdf_f_right4'] = $this->input->post('pdf_f_right4');
			$this->data['pdf_f_right5'] = $this->input->post('pdf_f_right5');
			$this->data['pdf_f_right6'] = $this->input->post('pdf_f_right6');
			foreach ($this->data['product_list'] as $k => $p) {
				if (isset($_POST['product_list'][$k])) {
					$this->data['product_list'][$k]['checked'] = 'checked';
				}
				$this->data['product_list'][$k]['commission'] = $this->input->post('product_commission_'.$k);
			}
			foreach ($this->data['pdf_product_list'] as $k => $p) {
				if (isset($_POST['pdf_product_list']) && in_array($k, $_POST['pdf_product_list'])) {
					$this->data['pdf_product_list'][$k] = 'checked';
				}
			}
			$pt = ' ';
			foreach ($this->data['paytype_list'] as $k => $p) {
				if (isset($_POST['paytype_list']) && in_array($p, $_POST['paytype_list'])) {
					$pt .= "," . $p;
				}
			}
			$this->data['product_customize'] = $this->input->post('product_customize');
			$this->data['pay_type'] = $pt;
			$this->data['receive_type'] = $this->input->post('receive_type');
		} else if ($user_id) {
			$user = $this->user_model->get_user_by_id($user_id);
			$user_product_list = $this->user_model->get_user_product_list($user_id);
			$user_pdf_list = array();
			if (!empty($user['pdf_product'])) $user_pdf_list = json_decode($user['pdf_product']);
			foreach ($this->data['pdf_product_list'] as $k => $p) {
				if (in_array($k, $user_pdf_list)) {
					$this->data['pdf_product_list'][$k] = 'checked';
				}
			}
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
			
			$product_customize = $this->product_model->get_product_customize($user_id);
			foreach ($product_customize as $pdc) {
				$this->data['product_customize'][$pdc['product_short']] = $pdc['name'];
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
					// redirect ( base_url ('product') );
					$this->data['top_menu'] = $this->menu_model->load_top_menu();
					//$this->data['menu'] = $this->menu_model->load_meun();
					$this->load->model('report_model');
					$this->data['html'] = $this->report_model->get_pophome();
						
					$this->load->common ( 'user/announcement', $this->data );
					return ;
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
