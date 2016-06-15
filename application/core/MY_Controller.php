<?php
/**
 * Base class for All API interface
 * 
 */
class MY_Controller extends CI_Controller {
	public $json_input;
	
	public function __construct() {
		parent::__construct();
		$this->load->database();
		$this->load->library('session');
		$this->load->helper('url');
		$this->load->model('log_model');
		$this->load->model('user_model');
		$this->load->model('func_model');
		$this->load->model('menu_model');
		
		// Set language 
		$language = $this->session->userdata('language');
		if (empty($language)) {
			$language = $this->config->item('language');
			$this->session->set_userdata('language', $language);
		}

		$currentUrl = $_SERVER['QUERY_STRING'] ? current_url() . '?' . $_SERVER['QUERY_STRING'] : current_url();
    	$this->session->set_userdata('curr_url', $currentUrl);
        $this->lang->load('message', $language);
        
		$post = $this->input->post();
        $get = $this->input->get();
		log_message('info', "GET: " . json_encode($get) . "; POST: " . json_encode($post));
	}
}