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
			$this->load->view('home_v1', $this->data);
		}
	}
}
