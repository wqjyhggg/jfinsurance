<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends MY_Controller {

	/**
	 * Index Page for this controller.
	 */
	public function index()
	{
		$this->data['title_txt'] = 'Welcome';

		$this->data['top_menu'] = $this->menu_model->load_top_menu();
		$this->data['menu'] = $this->menu_model->load_meun();
		//print_r($this->data['menu']);
		if ($this->session->userdata ( 'beuser' ))  {
			$this->load->common('home', $this->data);
		} else {
			$this->load->common('home', $this->data);
			//$this->load->view('home_v1', $this->data);
		}
	}

	public function pophome()
	{
		$this->load->model('report_model');
		$html = $this->report_model->get_pophome();
		if ($html) {
			die(rawurldecode($html));
		} else {
			redirect ( base_url ('product') );
		}
	}
}
