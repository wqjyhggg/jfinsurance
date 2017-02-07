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
		
		$myhome['file_url'] = $file_url;
		

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
