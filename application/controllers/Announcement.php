<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Announcement extends MY_Controller {

	/**
	 * Index Page for this controller.
	 */
	public function index()
	{
		$beuser = $this->func_model->verify_login();
		
		$data['title_txt'] = 'Announcement';

		$data['top_menu'] = $this->menu_model->load_top_menu();
		$data['menu'] = $this->menu_model->load_meun();
		
		$this->load->model('report_model');
		$data['save_url'] = base_url('announcement/save'); 
		
		$data['html'] = $this->report_model->get_pophome();
		$data['csrf'] = array (
				'name' => $this->security->get_csrf_token_name (),
				'value' => $this->security->get_csrf_hash ()
		);

		if ($beuser['user_group_id'] == 1) {
			$this->load->common('announcement', $data);
		} else {
			$this->load->common('user/announcement', $data);
		}
	}
	
	public function save() {
		$beuser = $this->func_model->verify_login();
		$this->load->model('report_model');
		$html = $this->input->post("textdata");
		$this->report_model->set_pophome($html);
		redirect(base_url('announcement'));
	}
}
