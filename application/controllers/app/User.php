<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User extends CI_Controller
{
  const PASSWORD_MIN = 6;
  const PASSWORD_MAX = 16;
  const PERPAGE = 15;

  public $error;
  public $data;

  /**
   * default for this controller. it is login page.
   */
  public function index()
  {
    $this->load->model("app_model");
    $this->app_model->return_error("Unknown Request");
  }

  public function logout()
  {
    $this->error = "";
    $this->load->model("app_model");
    $this->app_model->remove_token($this->input->post('token'));
    $this->app_model->return_ok("Logout Success ");
  }

  /**
   * Login verify
   *
   * @param
   *        	$this->input->post.....
   * @return boolean
   */
  private function login_verify()
  {
    if (empty($this->input->post('username'))) {
      $this->error = "Missing Username";
      return FALSE;
    }

    $pw = $this->input->post('password');
    if (empty(trim($pw)) || (strlen($pw) > self::PASSWORD_MAX)) {
      $this->error = "Wrong Password";
      return FALSE;
    }

    return true;
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
   * User information edit / add page
   */
  public function update($is_update=1)
  {
    $this->error = "";
    $this->load->model("app_model");
    $this->load->model("user_model");
    $user = $this->app_model->check_token($this->input->post("token"));

    if (empty($user)) {
      if (empty($this->error)) {
        $this->error = "Timeout";
      }
      return $this->app_model->return_error($this->error);
    }

    $this->load->model('province_model');
    $this->load->model('product_model');
    $this->load->model('paytype_model');
    $this->load->model('user_group_model');
    $this->load->model('region_model');
    $this->load->model('verify_model');

    //$this->data = $this->lang->language;
    $user_id = $user['user_id'];
    if ($user["user_group_id"] < 100) {
      if ($this->input->post("user_id")) {
        $user_id = $this->input->post("user_id");
      }
    }
    if ($is_update && $this->verify()) {
      if ($this->user_model->check_username($user_id, $this->input->post('username'))) {
        $this->data['error_message'] = "username existed, please select other username";
      } else {
        $post = $this->input->post();
        if (!empty($this->data['pdf_logo'])) $post['pdf_logo'] = $this->data['pdf_logo'];
        if (!empty($this->data['pdf_qr'])) $post['pdf_qr'] = $this->data['pdf_qr'];
        if (!empty($this->data['pdf_qr2'])) $post['pdf_qr2'] = $this->data['pdf_qr2'];
        $this->user_model->update($user_id, $post, 1, array('product_list' => 1));
        $this->log_model->activity('user', array('message' => $this->user_model->logstr, 'systemlog' => $this->user_model->sqlstr));
        $this->product_model->set_product_customize($user_id, $this->input->post('product_customize'));
        redirect(base_url('user'));
      }
    }

    // Get all text depend language
    $this->data['regions'] = $this->region_model->get_regions();
    $user = $this->user_model->get_user_by_id($user_id);
    $this->data['user'] = $user;
    $this->data['user_group_list'] = $this->user_group_model->get_user_group_list($user['user_group_id']);
    $this->data['broker_list'] = $this->user_model->get_broker_id_list($user["region_id"]);
    $this->data['province_list'] = $this->province_model->province_list();
    $this->data['product_list'] = $this->product_model->product_list(1, $user);
    $user_product_list = $this->user_model->get_user_product_list($user_id);
    $pdf_product_list = array();
    $user_pdf_list = json_decode($user['pdf_product']);
    foreach ($this->data['product_list'] as $k => $p) {
			$this->data['product_list'][$k]['checked'] = '';
      foreach($user_product_list as $up) {
        if ($p['product_short'] == $up['product_short']) {
          $this->data['product_list'][$k]['checked'] = 'checked';
          break;
        }
      }
      $pdf_product_list[$p["product_short"]] = "";
      if (in_array($p["product_short"], $user_pdf_list)) {
        $pdf_product_list[$p["product_short"]] = "checked";
      }
		}
    $this->data['pdf_product_list'] = $pdf_product_list;

    $this->app_model->return_ok($this->data);
  }

	public function users() {
    $this->error = "";
    $this->load->model("app_model");
    $this->load->model("user_model");
    $user = $this->app_model->check_token($this->input->post("token"));

    if (empty($user)) {
      if (empty($this->error)) {
        $this->error = "Timeout";
      }
      return $this->app_model->return_error($this->error);
    }

    $user_list = $this->user_model->get_available_user_list($user, $this->input->post());
    // $paytype_list = $this->paytype_model->paytype_list();

    $this->app_model->return_ok([
      "agent_list" => $user_list, // For sales_agent, jf
    ]);
  }

  public function detail() {
    return $this->update(0);
  }
  /**
   * User reset password page
   */
  public function resetpassword()
  {
    $this->error = "";
    $this->load->model("app_model");
    $this->load->model("user_model");
    $user = $this->app_model->check_token($this->input->post("token"));

    if (empty($user)) {
      if (empty($this->error)) {
        $this->error = "Timeout";
      }
      return $this->app_model->return_error($this->error);
    }

    if ($this->input->post()) {
      $password = $this->input->post('password');
      $password2 = $this->input->post('password2');
      if (empty($password) || empty($password2)) {
        $this->data['error_message'] = "Please input your new password";
      } else if ($password != $password2) {
        $this->data['error_message'] = "New password and verify password are different, please reinput";
      } else if ((strlen($password) < self::PASSWORD_MIN) || (strlen($password) > self::PASSWORD_MAX)) {
        $this->data['error_message'] = $this->lang->line('error_password_input');
      } else {
        $this->user_model->update($user['user_id'], array('password' => $password, 'status' => 1), 0);
        $this->log_model->activity('user', array('message' => "Reset Password: " . $this->user_model->logstr, 'systemlog' => "Reset Password: " . $this->user_model->sqlstr));
        redirect(base_url('product'));
      }
    }

    $this->app_model->return_ok($this->data);
  }

  /**
   * User login
   */
  public function login()
  {
    $this->error = "";
    $this->load->model("app_model");
    if ($this->input->post() && $this->login_verify()) {
      $this->load->model("user_model");
      // Login post check
      $r = $this->user_model->login($this->input->post('username'), $this->input->post('password'));
      if ($r) {
        if (is_array($r)) {
          $token = $this->app_model->create_token($r);
          return $this->app_model->return_ok(array("token" => $token, "is_temp" => 0));
        } else {
          $this->error = $r;
        }
      } else {
        $this->load->model("forgetpwd_model");
        if ($tr = $this->forgetpwd_model->get_by_key($this->input->post('password'))) {
          if ($r = $this->user_model->get_user_by_id($tr["user_id"])) {
            if (is_array($r)) {
              $token = $this->app_model->create_token($r);
              return $this->app_model->return_ok(array("token" => $token, "is_temp" => 1));
            } 
          }
        }
        $this->error = "No User Found";
      }
    }
    if (empty($this->error)) {
      $this->error = "Missing data";
    }
    $this->app_model->return_error($this->error);
  }

  /**
   * User Forget Passwrod
   */
  public function forget()
  {
    $this->error = "";
    $this->load->model("app_model");
    if ($this->input->post('username')) {
      $this->data['ispost'] = 1;
      // Login post check
      $r = $this->user_model->get_user_by_username_or_email($this->input->post('username'));
      if ($r && filter_var($r["email"], FILTER_VALIDATE_EMAIL)) {
        $this->load->model("forgetpwd_model");
        if ($key = $this->forgetpwd_model->get_pwd($r["user_id"])) {
          $this->load->model("mymail_model");
          // Send link with key

          $mail = $this->forgetpwd_model->email_body_app($key);
          $this->mymail_model->send_mymail($r["email"], $mail["title"], $mail["body"], $attach = array(), $from = '', $mailtype = 'text');
        }
      }
    }
    $this->app_model->return_ok("Please check your email.");
  }

  public function statics()
  {
    $this->error = "";
    $this->load->model("app_model");
    $this->load->model("user_model");
    $user = $this->app_model->check_token($this->input->post("token"));

    if (empty($user)) {
      if (empty($this->error)) {
        $this->error = "Timeout";
      }
      return $this->app_model->return_error($this->error);
    }
    $data = array();
    $data["total_customers"] = "12345";
    $data["total_sales_year_amount"] = "234567";
    $data["total_sales_year"] = "2345";
    $data["last_update"] = "2023-07-01";
    $this->app_model->return_ok($data);
  }
}
