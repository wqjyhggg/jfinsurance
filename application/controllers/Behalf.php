<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Behalf extends MY_Controller {

	/**
	 * Index Page for this controller.
	 */
	public function index()
	{
		redirect('user');
	}
	
	/**
	 * Behalf active
	 */
	public function to($user_id)
	{
		if (! $this->func_model->verify_level ( 4 )) {
			// Login user
			$this->session->set_userdata ( "error_message", $this->lang->line ( 'error_no_permission' ) );
			redirect ( base_url ( 'errorpage' ) );
		}
		$me = $this->session->userdata('user');
		if ($me['user_group_id'] > 100) {
			// School or brokerage need check permission
			if ( ! $this->user_model->check_sub_user($me['user_id'], $user_id) ) {
				$this->session->set_userdata ( "error_message", $this->lang->line ( 'error_no_permission' ) );
				redirect ( base_url ( 'errorpage' ) );
			}
		}
		$beuser = $this->user_model->get_user_by_id($user_id);
		if ($beuser && ($beuser['user_group_id'] > $me['user_group_id'])) {
			$this->session->set_userdata ( 'beuser', $beuser);
			$msg = "Behalf from " . $me['username'] . " to " . $beuser['username'];
			$this->log_model->activity('behalf', array('message' => $msg, 'systemlog' => $msg));
		}
		redirect('plan');
	}

	/**
	 * Behalf deactive
	 */
	public function undo()
	{
		if (! $this->func_model->verify_level ( 4 )) {
			// Login user
			$this->session->set_userdata ( "error_message", $this->lang->line ( 'error_no_permission' ) );
			redirect ( base_url ( 'errorpage' ) );
		}
		$this->session->set_userdata ( 'beuser', $this->session->userdata('user'));
		redirect('plan');
	}
}
