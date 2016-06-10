<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Errorpage extends MY_Controller {

	/**
	 * Index Page for this controller.
	 *
	 */
	public function index()
	{
		$data['errmsg'] = $this->session->userdata("error_message");
		if (empty($data['errmsg'])) {
			$data['errmsg'] = $this->lang->line("error_unknown");
		}
		$this->load->view('errorpage', $data);
	}
}
