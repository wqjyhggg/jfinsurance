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
		if ($myhome) {
			$this->load->model('user_model');
			$beuser = $this->user_model->get_user_by_id($myhome['user_id']);
			if ($beuser && ($beuser['status'] == 1)) {
				$this->session->set_userdata('beuser', $beuser);
				$data['title_txt'] = 'Agent ' . $beuser['firstname'] . ' ' . $beuser['lastname'];
				$data['myhome'] = $myhome;
				$data['myhomelogo'] = empty($myhome['logo']) ? '' : base_url('agent/img') . "/" . $myhome['logo'];
				$data['myhomeimage'] = empty($myhome['image']) ? '' : base_url('agent/img') . "/" . $myhome['image'];
			}
		}

		$data['top_menu'] = $this->menu_model->load_top_menu();
		$data['menu'] = $this->menu_model->load_meun();
		$this->load->common('home', $data);
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
