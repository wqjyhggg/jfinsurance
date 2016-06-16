<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Contact extends MY_Controller {

	/**
	 * Index Page for this controller.
	 */
	public function index()
	{
		$data['title_txt'] = 'Contact';

		$data['top_menu'] = $this->menu_model->load_top_menu();
		//$data['menu'] = $this->menu_model->load_meun();

		$this->load->common('about_us', $data);
	}
}
