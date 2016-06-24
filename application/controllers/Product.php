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
		$plans = $this->product_model->product_insured($product_short);
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
		$plans = $this->product_model->product_deductiable($product_short);
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
	
	function premium() {
		$beuser = $this->func_model->verify_login(); 
		$this->load->model('product_model');
		$para = array(
			'product_short' => $this->input->post_get('product_short'),
			'apply_date' => $this->input->post_get('apply_date'),
			'effective_date' => $this->input->post_get('effective_date'),
			'expiry_date' => $this->input->post_get('expiry_date'),
			'isfamilyplan' => $this->input->post_get('isfamilyplan'),
			'sum_insured' => $this->input->post_get('sum_insured'),
			'deductiable_amount' => $this->input->post_get('deductiable_amount'),
			'stable_condition' => $this->input->post_get('stable_condition'),
			'birthday' => $this->input->post_get('birthday'));

		$premium = $this->product_model->get_premium($para);
		if (empty($premium)) {
			$data = array('status' => 'Error', 'message' => "Can't caculate premium");
		} else {
			$data = array('status' => 'OK', 'premium' => number_format($premium, 2, '.', ','));
		}
		header('Content-Type: application/json');
		echo json_encode($data);
	}
}
