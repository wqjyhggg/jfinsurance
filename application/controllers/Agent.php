<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Agent extends MY_Controller {
	
	public function _remap($method, $params = array())
	{
		if (method_exists($this, $method)) {
			return call_user_func_array(array($this, $method), $params);
		} else {
			$this->index($method);
		}
		// show_404();
	}

	/**
	 * Index Page for this controller.
	 */
	public function index($name='')
	{
		if (empty($name)) {
			redirect("/");
		}
		$this->load->model('myhome_model');
		// echo $this->uri->segment(1); echo $this->uri->segment(2);
		$data['title_txt'] = 'Welcome';
		$myhome = $this->myhome_model->get_myhome_by_name($name);
		if (empty($myhome)) {
			$user = $this->myhome_model->get_user_by_name($name);
			$myhome = array();
		} else {
			$user = $this->user_model->get_user_by_id($myhome['user_id']);
		}
		if ($user) {
			$this->session->set_userdata ( 'vsuser', $user );
		} else {
			redirect("/");
		}
		if (empty($myhome['logo'])) {
			$myhome['logo'] = 'logo_thumb.png';
		}
		
		if (empty($myhome['qr'])) {
			$myhome['qr'] = 'noqr.png';
		}
		
		if (empty($myhome['image'])) {
			$myhome['image'] = 'homepic.png';
		}
		
		if (!isset($myhome['about_title'])) {
			$myhome['about_title'] = 'ABOUT US';
		}
		
		if (!isset($myhome['about_short'])) {
			$myhome['about_short'] = 'We take care of you';
		}
		
		if (!isset($myhome['top_desc'])) {
			$myhome['top_desc'] = "We don't like to think about it, but sudden, unexpected accidents or illnesses do happen, and trying to find an pay for adequate medical attention can be difficult when you are abroad.<br>Health car costs around the world can be bery expensive. Hospital can charge thousands of dollars per day. Your health plan may or may not cover a minute protion of these cost. Without adequate insurance coverage you could be responsible from dollar one, which could create a massive impact on your personal finances. Why take the risk?";
		}
		
		if (!isset($myhome['top_title'])) {
			$myhome['top_title'] = 'WHY BUY INSURANCE';
		}
		
		if (!isset($myhome['about_desc'])) {
			$myhome['about_desc'] = 'JF Insurance Agency Group Inc. (JF) is a licensed brokerage firm incorporated in 1992. We are the leading private firm in providing Emergency Hospital and Medical coverage for Canadians, visitors across Canada and International students. We are recognized for our dedication to serve our clients on both an individual basis and association groups.';
		}
		
		if (!isset($myhome['foot_title'])) {
			$myhome['foot_title'] = 'Toronto Office';
		}
		
		if (!isset($myhome['address'])) {
			$myhome['address'] = '15 Wertheim Court, Suite 501';
		}
		
		if (!isset($myhome['city_province'])) {
			$myhome['city_province'] = 'Richmond Hill, ON';
		}
		
		if (!isset($myhome['post_code'])) {
			$myhome['post_code'] = 'L4B 3H7 CANADA';
		}
		
		if (!isset($myhome['phone'])) {
			$myhome['phone'] = 'Phone: 905-707-1512';
		}
		
		if (!isset($myhome['fax'])) {
			$myhome['fax'] = 'Fax: 905-707-1513';
		}
		
		if (!isset($myhome['toll_free'])) {
			$myhome['toll_free'] = 'Toll Free: 1-877-832-5541';
		}
		
		if (!isset($myhome['toll_free_fax'])) {
			$myhome['toll_free_fax'] = 'Toll Free Fax: 1-888-988-3268';
		}
		
		if (!isset($myhome['email'])) {
			$myhome['email'] = $user['email'];
		}
		
		$this->load->model('product_model');
		$this->load->model('user_model');
		
		$downloads_url = base_url('pdf/download') . "/";
		$file_url = array();
		$product_list = $this->product_model->product_list(1);
		ksort($product_list);
		$fileName = array('_Brochure', '_ChineseBrochure', '_Benefit_Summary', '_Claim_Form', '_Claim_Procedure', '_Consent_Form', '_Policy');
		
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
		
		$myhome['file_url'] = $file_url;
		$myhome['buy_url'] = base_url('plan/add?product_short=');

		$data['top_menu'] = $this->menu_model->load_top_menu();
		$data['menu'] = $this->menu_model->load_meun();
		$this->load->view('agent/home', $myhome);
	}

	public function img($filename='') {
		$this->load->model('myhome_model');
		if (empty($filename) || ! file_exists(AGENTINFODIR . $filename)) {
			die();
		}

		$fileinfo = pathinfo($filename);
	    $mimeType = '';
	    switch ($fileinfo['extension']) {
	        case 'gif':
	            $mimeType = 'application/gif';
	            break;
	        case 'png':
	            $mimeType = 'application/png';
	            break;
	        case 'jpeg':
	        case 'jpg':
	            $mimeType = 'application/jpeg';
	            break;
	        default:
	        	die();
	    }       
	    header('Content-type: ' . $mimeType);
	    //header('Content-Disposition: attachment; filename="' . $filename . '"');
	    header('Content-Disposition: inline; filename="' . $filename . '"');
	    header('Content-Transfer-Encoding: binary');
	    header('Expires: 0');
	    header('Pragma: no-cache');
	    readfile(AGENTINFODIR . $filename);
	}
}
