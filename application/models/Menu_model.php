<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Menu_model extends CI_Model {
	public $nologin = array(
		"<a href='" . base_url() . "' class='leftmeun'>" . $this->lang->line ( 'text_home' ) . "</a>",
		"<a href='" . base_url('user/login') . "' class='leftmeun'>" . $this->lang->line ( 'text_login' ) . "</a>",
	);
	public $admin = array(
			"<a href='" . base_url() . "' class='leftmeun'>" . $this->lang->line ( 'text_home' ) . "</a>",
			"<a href='" . base_url('pdf/pdflist') . "' class='leftmeun'>" . $this->lang->line ( 'text_list_download_file' ) . "</a>",
			"<a href='" . base_url('pdf/upload') . "' class='leftmeun'>" . $this->lang->line ( 'text_upload_policy' ) . "</a>",
			"<a href='" . base_url('report') . "' class='leftmeun'>" . $this->lang->line ( 'text_report' ) . "</a>",
			"<a href='" . base_url('policy') . "' class='leftmeun'>" . $this->lang->line ( 'text_policy' ) . "</a>",
	);
	
    /**
     *  Check matched username and passwrd in user table
     *  
     *  @param	string	$level
     *  @param	string	$password
     *  @return null / array on find.     
     */
	public function load_meun() {
		$user = $this->session->userdata('user');
		$beuser = $this->session->userdata('beuser');
	   	if (!$user) {
    		redirect('user/login');
    	}
		if ($user['user_group_id'] <= $level) {
    		return TRUE;
    	}
    	return FALSE;
	}
}
