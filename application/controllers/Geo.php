<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Geo extends MY_Controller {
	/**
	 * Get country select with defualt setting
	 * 
	 */
	public function country($c='CA')
	{
		$this->load->model('geo_model');
		$countries = $this->geo_model->get_country();
		$clist = array();
		if (empty($c)) $c = 'CA';	// double safe for country code
		foreach($countries as $ct) {
			$k = $ct['iso_code_2'];
			if ($k == $c) {
				$clist[$k] = array("name" => $ct['name'], "selected" => "selected");
			} else {
				$clist[$k] = array("name" => $ct['name'], "selected" => "");
			}
		}
		$data = array('country' => $clist);
		$data['country_url'] = base_url ( "geo/province" ) . "/";
		$this->load->view('geo/country', $data);
	}
	
	/**
	 * Get country select with defualt setting
	 * 
	 */
	public function province($c='CA',$p='ON')
	{
		$this->load->model('geo_model');
		if (empty($c)) $c = 'CA';	// double safe for country code
		$provinces = $this->geo_model->get_province($c);
		$plist = array();
		foreach($provinces as $pv) {
			$k = $pv['province2'];
			if ($k == $p) {
				$plist[$k] = array("name" => $pv['name'], "selected" => "selected");
			} else {
				$plist[$k] = array("name" => $pv['name'], "selected" => "");
			}
		}
		$this->load->view('geo/province', array('province' => $plist));
	}
	
	public function index()
	{
		return $this->country();
	}
}
