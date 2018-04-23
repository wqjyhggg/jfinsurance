<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lang extends MY_Controller {
	/**
	 * Index Page for this controller.
	 */
	public function index()
	{
		$lang = 'english';
		$this->session->set_userdata('language', $lang);
		$currentUrl = $this->session->set_userdata('curr_url');
		redirect($currentUrl, 'refresh');
	}

	public function english()
	{
		$lang = 'english';
		$this->session->set_userdata('language', $lang);
		$currentUrl = $this->session->set_userdata('curr_url');
		redirect($currentUrl, 'refresh');
	}

	public function chinese()
	{
		$lang = 'chinese';
		$this->session->set_userdata('language', $lang);
		$currentUrl = $this->session->set_userdata('curr_url');
		redirect($currentUrl, 'refresh');
	}

	public function japanese()
	{
		$lang = 'japanese';
		$this->session->set_userdata('language', $lang);
		$currentUrl = $this->session->set_userdata('curr_url');
		redirect($currentUrl, 'refresh');
	}

	public function korean()
	{
		$lang = 'korean';
		$this->session->set_userdata('language', $lang);
		$currentUrl = $this->session->set_userdata('curr_url');
		redirect($currentUrl, 'refresh');
	}
}
