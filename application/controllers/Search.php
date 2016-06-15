<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Search extends MY_Controller {

	/**
	 * Index Page for this controller.
	 */
	public function index()
	{
		$data['title_txt'] = 'Welcome';
		$data['top_menu'] = $this->menu_model->load_top_meun();
		$data['menu'] = $this->menu_model->load_meun();
		$this->load->common('home', $data);
	}
}
