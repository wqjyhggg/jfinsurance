<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Renewal extends MY_Controller {

	/**
	 * Index Page for this controller.
	 */
	public function index()
	{
		$data['title_txt'] = 'Renewal Report';
		$data['top_menu'] = $this->menu_model->load_top_menu();
		$data['menu'] = $this->menu_model->load_meun();
		$this->load->common('reports/renewal', $data);
	}
}
