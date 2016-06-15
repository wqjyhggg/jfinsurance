<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Menu_model extends CI_Model {
    /**
     *  Check matched username and passwrd in user table
     *  
     *  @param	string	$level
     *  @param	string	$password
     *  @return null / array on find.     
     */
	public function load_meun() {
		$meunArr = array(
			0 =>array(
					"<a href='" . base_url() . "' class='leftmeun'>" . $this->lang->line ( 'text_home' ) . "</a>",
					"<a href='" . base_url('user/login') . "' class='leftmeun'>" . $this->lang->line ( 'text_login' ) . "</a>",
				),
			1 => array(
					"<a href='" . base_url() . "' class='leftmeun'>" . $this->lang->line ( 'text_home' ) . "</a>",
					"<a href='" . base_url('pdf/pdflist') . "' class='leftmeun'>" . $this->lang->line ( 'text_list_download_file' ) . "</a>",
					"<a href='" . base_url('pdf/upload') . "' class='leftmeun'>" . $this->lang->line ( 'text_upload_policy' ) . "</a>",
					"<a href='" . base_url('report') . "' class='leftmeun'>" . $this->lang->line ( 'text_report' ) . "</a>",
					"<a href='" . base_url('policy') . "' class='leftmeun'>" . $this->lang->line ( 'text_policy' ) . "</a>",
					"<a href='" . base_url('search') . "' class='leftmeun'>" . $this->lang->line ( 'text_search' ) . "</a>",
					"<a href='" . base_url('claim') . "' class='leftmeun'>" . $this->lang->line ( 'text_claim' ) . "</a>",
					"<a href='" . base_url('agent') . "' class='leftmeun'>" . $this->lang->line ( 'text_agent' ) . "</a>",
				),
			2 => array(
					"<a href='" . base_url() . "' class='leftmeun'>" . $this->lang->line ( 'text_home' ) . "</a>",
					"<a href='" . base_url('pdf/pdflist') . "' class='leftmeun'>" . $this->lang->line ( 'text_list_download_file' ) . "</a>",
					"<a href='" . base_url('pdf/upload') . "' class='leftmeun'>" . $this->lang->line ( 'text_upload_policy' ) . "</a>",
					"<a href='" . base_url('report') . "' class='leftmeun'>" . $this->lang->line ( 'text_report' ) . "</a>",
					"<a href='" . base_url('policy') . "' class='leftmeun'>" . $this->lang->line ( 'text_policy' ) . "</a>",
					"<a href='" . base_url('search') . "' class='leftmeun'>" . $this->lang->line ( 'text_search' ) . "</a>",
					"<a href='" . base_url('claim') . "' class='leftmeun'>" . $this->lang->line ( 'text_claim' ) . "</a>",
					"<a href='" . base_url('agent') . "' class='leftmeun'>" . $this->lang->line ( 'text_agent' ) . "</a>",
				),
			3 =>array(
					"<a href='" . base_url() . "' class='leftmeun'>" . $this->lang->line ( 'text_home' ) . "</a>",
					"<a href='" . base_url('user/login') . "' class='leftmeun'>" . $this->lang->line ( 'text_login' ) . "</a>",
				),
			4 =>array(
					"<a href='" . base_url() . "' class='leftmeun'>" . $this->lang->line ( 'text_home' ) . "</a>",
					"<a href='" . base_url('user/login') . "' class='leftmeun'>" . $this->lang->line ( 'text_login' ) . "</a>",
				),
			5 =>array(
					"<a href='" . base_url() . "' class='leftmeun'>" . $this->lang->line ( 'text_home' ) . "</a>",
					"<a href='" . base_url('user/login') . "' class='leftmeun'>" . $this->lang->line ( 'text_login' ) . "</a>",
				),
		);
		$behalf = "<a href='" . base_url('behalf') . "' class='leftmeun'>" . $this->lang->line ( 'text_behalf' ) . "</a>";
		$unbehalf = "<a href='" . base_url('behalf/undo') . "' class='leftmeun'>" . $this->lang->line ( 'text_unbehalf' ) . "</a>";
	
		$user = $this->session->userdata('user');
		$beuser = $this->session->userdata('beuser');
		$onbehalf = 0;
	   	if (!$user) {
    		$group = 0;
    	} else {
    		$group = $user['user_group_id'];
    		if ($user['user_id'] != $beuser['user_id']) {
    			$onbehalf = 1;
    		}
    	}
		$menu = $meunArr[$group];
		if (($group == 1) || ($group == 2) ||($group == 4)) {
			if ($onbehalf) {
				$menu = array_push($menu, $this->unbehalf);
			} else {
				$menu = array_push($menu, $this->behalf);
			}
		}
    	return FALSE;
	}
}
