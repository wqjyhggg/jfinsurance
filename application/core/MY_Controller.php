<?php
/**
 * Base class for All API interface
 * 
 */
class MY_Controller extends CI_Controller {
	public $json_input;
	public $data = array();
	
	public function __construct() {
		parent::__construct();
		$this->load->database();
		$this->load->library('session');
		$this->session->sess_expiration = 7200; // 2 hours
		$this->session->sess_time_to_update = 300; // Update session ID every 5 minutes
		$this->load->helper('url');
		$this->load->model('log_model');
		$this->load->model('user_model');
		$this->load->model('func_model');
		$this->load->model('menu_model');
		
    // Set db time zone
    // $this->db->query("SET time_zone = 'America/Toronto'");
    
		// Set language 
		$language = $this->session->userdata('language');
		$segment = $this->uri->segment(1);
		if (empty($language) || (($language != "englist") && ($language != "french"))) {
			$language = $this->config->item('language');
			$this->session->set_userdata('language', $language);
		}

		$currentUrl = empty($_SERVER['QUERY_STRING']) ? current_url() : current_url() . '?' . $_SERVER['QUERY_STRING'];
    	$this->session->set_userdata('curr_url', $currentUrl);
        $this->lang->load('message');
    	$this->lang->load('message', $language);
        $this->data['lang'] = $this->lang->language;
        $this->data['language'] = $language;
        
        $this->load->model('html_model');
        
		$post = $this->input->post();
        $get = $this->input->get();
		log_message('info', "GET: " . json_encode($get) . "; POST: " . json_encode($post));
	}
}
