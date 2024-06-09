<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Notify extends MY_Controller {

	/**
	 * Index Page for this controller.
	 */
	public function index()
	{
		$beuser = $this->func_model->verify_login();
		
		$data['title_txt'] = 'Notify Me';

		$data['top_menu'] = $this->menu_model->load_top_menu();
		$data['menu'] = $this->menu_model->load_meun();
		
		$this->load->model('user_notify_model');
		$data['save_url'] = base_url('notify'); 
		$data['beuser'] = $beuser;
		$user_id = $beuser["user_id"];
    if (($beuser["user_group_id"] < 100) && ($this->input->post("user_id"))) {
      $user_id = $this->input->post("user_id");
    }
		$data['notify_type'] = 0;
    if ($this->input->post("submit")) {
      $notify_type = $this->input->post("notify_type");
      $this->user_notify_model->save($user_id, $notify_type);
    }
    $data['user_id'] = $user_id;
    if ($notify = $this->user_notify_model->get_by_id($user_id)) {
      $data['notify_type'] = $notify["notify_type"];
    }
		$data['csrf'] = array (
				'name' => $this->security->get_csrf_token_name (),
				'value' => $this->security->get_csrf_hash ()
		);

		$this->load->common('notify', $data);
	}
}
