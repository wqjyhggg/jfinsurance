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
		$this->load_>model('user_model');
		if ($isbeuser) {
			if ($this->session->userdata ( 'beuser' )) {
				$user = $this->session->userdata ( 'beuser' );
				if ($this_user = $this->user_model->get_user_by_id ( $user ['id'] )) {
					if ($this_user ['status'] == 1) {
						return $user;
					}
				}
			}
			if ($isvsuser && $this->session->userdata ( 'vsuser' )) {
				$user = $this->session->userdata ( 'vsuser' );
				if ($this_user = $this->user_model->get_user_by_id ( $user ['id'] )) {
					if ($this_user ['status'] == 1) {
						return $user;
					}
				}
			}
		} else {
			if ($this->session->userdata ( 'user' ) && $this->session->userdata ( 'beuser' )) {
				$user = $this->session->userdata ( 'user' );
				if ($this_user = $this->user_model->get_user_by_id ( $user ['id'] )) {
					if ($this_user ['status'] == 1) {
						return $user;
					}
				}
			}
		}
		redirect ( base_url ('user/login') );
	}
}
