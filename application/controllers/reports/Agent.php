<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Agent extends MY_Controller {

	/**
	 * Index Page for this controller.
	 */
	public function index()
	{
		$data ['csrf'] = array (
				'name' => $this->security->get_csrf_token_name (),
				'value' => $this->security->get_csrf_hash () 
		);
		
		$data['title_txt'] = 'Sales Report to Agent';
		$data['top_menu'] = $this->menu_model->load_top_menu();
		$data['menu'] = $this->menu_model->load_meun();
		$this->load->common('reports/agent', $data);
	}
}
