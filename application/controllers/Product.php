<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product extends MY_Controller {
	public $error;
	
	/**
	 * Index Page for this controller.
	 */
	public function index()
	{
		die("");
	}
	
	function insured($product_short='', $sum_insured='') {
		$this->load->model('product_model');
		$plans = $this->product_model->plan_insured($product_short);
		$plist = array();
		foreach($plans as $amount) {
			if ($amount != 'unlimit') {
				$amountname = '$'.number_format($amount, 2, '.', ',');
			} else {
				$amountname = $amount;
			}
			if ($sum_insured == $amount) {
				$plist[$amount] = array("name" => $amountname, "selected" => "selected");
			} else {
				$plist[$amount] = array("name" => $amountname, "selected" => "");
			}
		}
		$data = array('plist' => $plist);
		$this->load->view('product/insured', $data);
	}
	
	function deductiable($product_short='', $deductiable_amount='') {
		$this->load->model('product_model');
		$plans = $this->product_model->plan_deductiable($product_short);
		$plist = array();
		foreach($plans as $amount) {
			if ($amount != 'unlimit') {
				$amountname = '$'.number_format($amount, 2, '.', ',');
			} else {
				$amountname = $amount;
			}
			if ($deductiable_amount == $amount) {
				$plist[$amount] = array("name" => $amountname, "selected" => "selected");
			} else {
				$plist[$amount] = array("name" => $amountname, "selected" => "");
			}
		}
		$data = array('plist' => $plist);
		$this->load->view('product/deductiable', $data);
	}
}
