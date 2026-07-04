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
		$this->load->model('user_model');
		if ($isbeuser) {
			if ($this->session->userdata ( 'beuser' )) {
				$user = $this->session->userdata ( 'beuser' );
				if ($theuser = $this->user_model->get_user_by_id ( $user ['id'] )) {
					if ($theuser ['status'] == 1) {
						return $theuser;
					}
				}
			}
			if ($isvsuser && $this->session->userdata ( 'vsuser' )) {
				$user = $this->session->userdata ( 'vsuser' );
				if ($theuser = $this->user_model->get_user_by_id ( $user ['id'] )) {
					if ($theuser ['status'] == 1) {
						return $theuser;
					}
				}
			}
		} else {
			if ($this->session->userdata ( 'user' ) && $this->session->userdata ( 'beuser' )) {
				$user = $this->session->userdata ( 'beuser' );
				if ($theuser = $this->user_model->get_user_by_id ( $user ['id'] )) {
					if ($theuser ['status'] == 1) {
						return $theuser;
					}
				}
			}
		}
		redirect ( base_url ('user/login') );
	}
}
