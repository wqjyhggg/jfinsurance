<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Geo extends MY_Controller {
	/**
	 * Get country select with defualt setting
	 * 
	 */
	public function country($c=NULL)
	{
		$this->load->model('geo_model');
		$countries = $this->geo_model->get_country();
		$neednull = $this->input->get_post('neednull');
		$myid = $this->input->get_post('myid');
		if ($neednull) {
			$clist = array('' => array("name" => ' -- '.$this->lang->line("select country").' --', "selected" => ""));
		} else {
			if (empty($c)) $c = 'CA';	// double safe for country code
			$clist = array();
		}
		foreach($countries as $ct) {
			$k = $ct['iso_code_2'];
			if ($k == $c) {
				$clist[$k] = array("name" => $ct['name'], "selected" => "selected");
			} else {
				$clist[$k] = array("name" => $ct['name'], "selected" => "");
			}
		}
		$data = array('country' => $clist);
		$data['myid'] = $myid;
		$data['country_url'] = base_url ( "geo/province" ) . "/";
		$this->load->view('geo/country', $data);
	}
	
	/**
	 * Get country select with defualt setting
	 * 
	 */
	public function province($c=NULL,$p=NULL)
	{
		$this->load->model('geo_model');
		if (empty($c)) $c = 'CA';	// double safe for country code
		$provinces = $this->geo_model->get_province($c);
		$neednull = $this->input->get_post('neednull');
		$myid = $this->input->get_post('myid');
		if ($neednull) {
			$plist = array('' => array("name" => ' -- '.$this->lang->line("select province").' --', "selected" => ""));
		} else {
			$plist = array();
		}
		foreach($provinces as $pv) {
			$k = $pv['province2'];
			if ($k == $p) {
				$plist[$k] = array("name" => $pv['name'], "selected" => "selected");
			} else {
				$plist[$k] = array("name" => $pv['name'], "selected" => "");
			}
		}
		$data['province'] = $plist;
		$data['myid'] = $myid;
		$this->load->view('geo/province', $data);
	}
	
	public function index()
	{
		return $this->country();
	}
}
