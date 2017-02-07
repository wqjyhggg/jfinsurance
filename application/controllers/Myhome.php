<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

use Endroid\QrCode\QrCode;

class Myhome extends MY_Controller {
	const NAME_MIN = 2;
	const NAME_MAX = 32;
	private $data;
	private $logofile;
	private $imagefile;
	private $qrfile;
	
	/**
	 * Login verify
	 *
	 * @param
	 *        	$this->input->post.....
	 * @return boolean
	 */
	private function verify() {
		$rt = TRUE;
		if (empty($this->input->post('myname'))) {
			$this->data['error_myname'] = $this->lang->line ( 'error_myname_empty' );
			$rt = FALSE;
		} else if (!preg_match('/^([a-z0-9]+)_([a-z0-9])*$/', $this->input->post('myname'))) {
			$this->data['error_myname'] = $this->lang->line ( 'error_myname_rule' );
			$rt = FALSE;
		}
		
		if (!empty($this->input->post('email')) && filter_var($this->input->post('email'), FILTER_VALIDATE_EMAIL)) {
			$this->data['error_myname'] = $this->lang->line ( 'error_myname_email' );
			$rt = FALSE;
		}

		$this->logofile = '';
		$this->imagefile = '';
		if (!empty($_FILES)) {
			$this->load->library('upload');
			$this->load->library('image_lib');
			if (!empty($_FILES['logo_src']) && !empty($_FILES['logo_src']['tmp_name'])) {
				$logo = $this->myhome_model->get_logo_filename();
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
							'width' => $this->myhome_model->get_logo_width(),
					);
					$this->image_lib->initialize($imgpara);
					$this->image_lib->resize();
					$this->image_lib->clear();
					$this->logofile = $disfile;
				}
			}
				
			if (!empty($_FILES['image_src']) && !empty($_FILES['image_src']['tmp_name'])) {
				$image = $this->myhome_model->get_image_filename();
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
				if ( ! $this->upload->do_upload('image_src')) {
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
							'width' => $this->myhome_model->get_image_width(),
					);
					$this->image_lib->initialize($imgpara);
					$this->image_lib->resize();
					$this->image_lib->clear();
					$this->imagefile = $disfile;
				}
			}
						
			if (!empty($_FILES['qr_src']) && !empty($_FILES['qr_src']['tmp_name'])) {
				$image = $this->myhome_model->get_image_filename();
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
							'width' => $this->myhome_model->get_image_width(),
					);
					$this->image_lib->initialize($imgpara);
					$this->image_lib->resize();
					$this->image_lib->clear();
					$this->qrfile = $disfile;
				}
			}
		}
		
		return $rt;
	}
	
	/**
	 * default for this controller. it is login page.
	 */
	public function index() {
		if (! $this->func_model->verify_login()) {
			redirect ( base_url ( 'user' ) );
		}
		
		$this->load->model('myhome_model');
		$this->data = array();
		if ($this->input->post() && $this->verify()) {
			$this->myhome_model->update(array_merge($this->input->post(), array('logo' => $this->logofile, 'image' => $this->imagefile, 'qr' => $this->qrfile)));
			$this->data['success_message'] = 'Your My Home information has been saved';
		}
		$beuser = $this->session->userdata('beuser');
		$myhome = $this->myhome_model->get_myhome($beuser['user_id']);
		$mynameArr = array();
		
		if ($myhome) {
			$this->data['myname'] = $myhome['myname'];
			$mynameArr = preg_split('/_/', $myhome['myname']);
		}
		echo "<pre>"; print_r($this->qrfile); die("XX"); //XXXXXXXXXXXXXXXXXXXXXXXX
		
		$this->data['user_id'] = $beuser['user_id'];
		if ($this->input->post('firstname')) {
			$this->data['firstname'] = $this->input->post('firstname');
		} else if ($myhome) {
			$this->data['firstname'] = $mynameArr[0];
		} else {
			$this->data['firstname'] = $beuser['firstname'];
		}
		if ($this->input->post('lastname')) {
			$this->data['lastname'] = $this->input->post('lastname');
		} else if ($myhome) {
			$this->data['lastname'] = $mynameArr[1];
		} else {
			$this->data['lastname'] = $beuser['lastname'];
		}
		
		if ($this->input->post('logo_src')) {
			$this->data['logo_src'] = $this->input->post('logo_src');
		} else if ($myhome && !empty($myhome['logo'])) {
			$this->data['logo_src'] = $myhome['logo'];
		} else {
			$this->data['logo_src'] = 'logo_thumb.png';
		}
		if ($this->input->post('qr_src')) {
			$this->data['qr_src'] = $this->input->post('qr_src');
		} else if ($myhome && !empty($myhome['qr'])) {
			$this->data['qr_src'] = $myhome['qr'];
		} else {
			$this->data['qr_src'] = 'noqr.png';
		}
		if ($this->input->post('image_src')) {
			$this->data['image_src'] = $this->input->post('image_src');
		} else if ($myhome && !empty($myhome['image'])) {
			$this->data['image_src'] = $myhome['image'];
		} else {
			$this->data['image_src'] = 'homepic.png';
		}
		
		if ($this->input->post('top_title')) {
			$this->data['top_title'] = $this->input->post('top_title');
		} else if ($myhome) {
			$this->data['top_title'] = $myhome['top_title'];
		} else {
			$this->data['top_title'] = 'WHY BUY INSURANCE';
		}
		if ($this->input->post('top_desc')) {
			$this->data['top_desc'] = $this->input->post('top_desc');
		} else if ($myhome) {
			$this->data['top_desc'] = $myhome['top_desc'];
		} else {
			$this->data['top_desc'] = "We don't like to think about it, but sudden, unexpected accidents or illnesses do happen, and trying to find an pay for adequate medical attention can be difficult when you are abroad.<br>Health car costs around the world can be bery expensive. Hospital can charge thousands of dollars per day. Your health plan may or may not cover a minute protion of these cost. Without adequate insurance coverage you could be responsible from dollar one, which could create a massive impact on your personal finances. Why take the risk?";
		}
		
		
		if ($this->input->post('about_title')) {
			$this->data['about_title'] = $this->input->post('about_title');
		} else if ($myhome) {
			$this->data['about_title'] = $myhome['about_title'];
		} else {
			$this->data['about_title'] = "ABOUT US";
		}
		if ($this->input->post('about_short')) {
			$this->data['about_short'] = $this->input->post('about_short');
		} else if ($myhome) {
			$this->data['about_short'] = $myhome['about_short'];
		} else {
			$this->data['about_short'] = "We take care of you";
		}
		if ($this->input->post('about_desc')) {
			$this->data['about_desc'] = $this->input->post('about_desc');
		} else if ($myhome) {
			$this->data['about_desc'] = $myhome['about_desc'];
		} else {
			$this->data['about_desc'] = "JF Insurance Agency Group Inc. (JF) is a licensed brokerage firm incorporated in 1992. We are the leading private firm in providing Emergency Hospital and Medical coverage for Canadians, visitors across Canada and International students. We are recognized for our dedication to serve our clients on both an individual basis and association groups.";
		}
		
		
		if ($this->input->post('foot_title')) {
			$this->data['foot_title'] = $this->input->post('foot_title');
		} else if ($myhome) {
			$this->data['foot_title'] = $myhome['foot_title'];
		} else {
			$this->data['foot_title'] = 'Toronto Office';
		}
		if ($this->input->post('address')) {
			$this->data['address'] = $this->input->post('address');
		} else if ($myhome) {
			$this->data['address'] = $myhome['address'];
		} else {
			$this->data['address'] = '15 Wertheim Court, Suite 501';
		}
		if ($this->input->post('city_province')) {
			$this->data['city_province'] = $this->input->post('city_province');
		} else if ($myhome) {
			$this->data['city_province'] = $myhome['city_province'];
		} else {
			$this->data['city_province'] = 'Richmond Hill, ON';
		}
		if ($this->input->post('post_code')) {
			$this->data['post_code'] = $this->input->post('post_code');
		} else if ($myhome) {
			$this->data['post_code'] = $myhome['post_code'];
		} else {
			$this->data['post_code'] = 'L4B 3H7 CANADA';
		}
		if ($this->input->post('phone')) {
			$this->data['phone'] = $this->input->post('phone');
		} else if ($myhome) {
			$this->data['phone'] = $myhome['phone'];
		} else {
			$this->data['phone'] = 'Phone: 905-707-1512';
		}
		if ($this->input->post('fax')) {
			$this->data['fax'] = $this->input->post('fax');
		} else if ($myhome) {
			$this->data['fax'] = $myhome['fax'];
		} else {
			$this->data['fax'] = 'Fax: 905-707-1513';
		}
		if ($this->input->post('toll_free')) {
			$this->data['toll_free'] = $this->input->post('toll_free');
		} else if ($myhome) {
			$this->data['toll_free'] = $myhome['toll_free'];
		} else {
			$this->data['toll_free'] = 'Toll Free: 1-877-832-5541';
		}
		if ($this->input->post('toll_free_fax')) {
			$this->data['toll_free_fax'] = $this->input->post('toll_free_fax');
		} else if ($myhome) {
			$this->data['toll_free_fax'] = $myhome['toll_free_fax'];
		} else {
			$this->data['toll_free_fax'] = 'Toll Free Fax: 1-888-988-3268';
		}
		
		if ($this->input->post('email')) {
			$this->data['email'] = $this->input->post('email');
		} else if ($myhome) {
			$this->data['email'] = $myhome['email'];
		} else {
			$this->data['email'] = 'E-mail: info@jfgroup.ca';
		}

		$this->load->model('product_model');
		
		$downloads_url = base_url('pdf/download') . "/";
		$file_url = array();
		$product_list = $this->product_model->product_list(1);
		ksort($product_list);
		$fileName = array('_Brochure', '_Benefit_Summary', '_Claim_Form', '_Claim_Procedure', '_Consent_Form', '_Policy');
		
		foreach ($product_list as $product_short => $p) {
			$file_url[$product_short] = array('fullname' => $p['full_name'], 'files' => array());
			foreach ($fileName as $fn) {
				$name = str_replace('_', ' ', $fn);
				$fname = $product_short . $fn . ".pdf";
				if (file_exists(DOWNLOADDIR . $fname)) {
					$file_url[$product_short]['files'][] = array('url' => $downloads_url . $fname, 'name' => $name);
				}
			}
		}
		
		$this->data['file_url'] = $file_url;
		
		$this->data['action_url'] = current_url();
		$this->data['myname_url'] = base_url('myhome/myname');
		$this->data['myhome_url'] = base_url('agent');
		
		$this->data ['csrf'] = array (
				'name' => $this->security->get_csrf_token_name (),
				'value' => $this->security->get_csrf_hash () 
		);

		$this->data['top_menu'] = $this->menu_model->load_top_menu();
		$this->data['menu'] = $this->menu_model->load_meun();
		$this->load->common ( 'myhome/home', $this->data );
	}
	
	/**
	 * User information edit / add page
	 */
	public function myname() {
		if ($this->func_model->verify_login()) {
		
			$this->load->model('myhome_model');
			$beuser = $this->session->userdata('beuser');
			
			$firstname = $this->input->get_post('firstname');
			$lastname = $this->input->get_post('lastname');
			$name = $this->myhome_model->get_myname($firstname, $lastname);
			$myhome = $this->myhome_model->get_myhome_by_name($name);
			if ($myhome && ($myhome['user_id'] != $beuser['user_id'])) {
				$data = array('status' => 0, 'message' => "[" . $name . "] is taken by other, please try to use other name or add numbers to your firstname or lastname");
			} else {
				$data = array('status' => 1, 'name' => $name);
			}
			header('Content-Type: application/json');
			header('Cache-Control: no-store, no-cache, must-revalidate');
			echo json_encode($data);
		}
	}
}
