<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Func_model extends CI_Model {
    /**
     *  Check matched username and passwrd in user table
     *  
     *  @param	string	$level
     *  @param	string	$password
     *  @return null / array on find.     
     */
	public function verify_level($level) {
		$user = $this->session->userdata('user');
	   	if (!$user) {
    		redirect('user/login');
    	}
		if ($user['user_group_id'] <= $level) {
    		return TRUE;
    	}
    	return FALSE;
	}
	
	public function verify_login($isbeuser=FALSE, $isvsuser=FALSE) {
		if ($isbeuser) {
			if ($this->session->userdata ( 'beuser' )) {
				return $this->session->userdata ( 'beuser' );
			}
			if ($isvsuser && $this->session->userdata ( 'vsuser' )) {
				return $this->session->userdata ( 'vsuser' );
			}
			redirect ( base_url ('user/login') );
		} else {
			if ($this->session->userdata ( 'user' ) && $this->session->userdata ( 'beuser' )) {
				return $this->session->userdata ( 'beuser' );
			}
			redirect ( base_url ('user/login') );
		}
	}
}
