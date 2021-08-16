<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product extends MY_Controller {
	public $error;
	
	/**
	 * Index Page for this controller.
	 */
	public function index()
	{
		$beuser = $this->func_model->verify_login(); 
		
		$this->load->model('product_model');
		$data['products'] = $this->product_model->product_array(1);
		
		$data['quote_url'] = base_url('plan/add');
		$data['view_url'] = base_url('product/detail') . "/";
		
		$data['title_txt'] = 'Products';
		$data['downloads_url'] = base_url('pdf/download') . "/";

		$data['top_menu'] = $this->menu_model->load_top_menu();
		$data['menu'] = $this->menu_model->load_meun();
		$data['csrf'] = array (
				'name' => $this->security->get_csrf_token_name (),
				'value' => $this->security->get_csrf_hash ()
		);
		
		$data['downloads_url'] = base_url('pdf/download') . "/";
		$data['url_benefit'] = '_Benefit_Summary';

		$this->load->common('product/product', $data);	
	}
	
	function detail($product_short='') {
		if (empty($product_short)) {
			redirect(base_url('production'));
		}
		$data['title_txt'] = 'Product : ' . $product_short;

		$data['top_menu'] = $this->menu_model->load_top_menu();
		$data['menu'] = $this->menu_model->load_meun();
		$data['csrf'] = array (
				'name' => $this->security->get_csrf_token_name (),
				'value' => $this->security->get_csrf_hash ()
		);
		$this->load->common('product/'.$product_short, $data);	
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
	
	function deductible($product_short='', $deductible_amount='', $plan_id='') {
		$this->load->model('product_model');
		$plans = $this->product_model->product_deductible($product_short, $deductible_amount);
		$plist = array();
		foreach($plans as $amount) {
			if ($amount != 'unlimit') {
				$amountname = '$'.number_format($amount, 2, '.', ',');
			} else {
				$amountname = $amount;
			}
			if ($deductible_amount == $amount) {
				$plist[$amount] = array("name" => $amountname, "selected" => "selected");
			} else {
				$plist[$amount] = array("name" => $amountname, "selected" => "");
			}
		}
		$data = array('plist' => $plist);
		$this->load->view('product/deductible', $data);
	}
	
	function getpremium() {
		$beuser = $this->func_model->verify_login(TRUE, TRUE); 
		$this->load->model('product_model');
		$para = array(
			'status_id' => $this->input->post_get('status_id'),
			'plan_id' => $this->input->post_get('plan_id'),
			'product_short' => $this->input->post_get('product_short'),
			'apply_date' => $this->input->post_get('apply_date'),
			'effective_date' => $this->input->post_get('effective_date'),
			'expiry_date' => $this->input->post_get('expiry_date'),
			'isfamilyplan' => $this->input->post_get('isfamilyplan'),
			'sum_insured' => $this->input->post_get('sum_insured'),
			'deductible_amount' => $this->input->post_get('deductible_amount'),
			'rate_options' => $this->input->post_get('rate_options'),
			'stable_condition' => $this->input->post_get('stable_condition'),
			'holiday_rate' => $this->input->post_get('holiday_rate'),
			'birthday' => $this->input->post_get('birthday'),
			'spouse' => $this->input->post_get('spouse'),
			'number_customer' => $this->input->post_get('number_customer'));
		$premiumarr = $this->product_model->get_premium($para);
		if (empty($premiumarr) || (empty($premiumarr['premium']) && empty($premiumarr['message']))) {
			$days = 0;
			if (!empty($para['effective_date']) && !empty($para['expiry_date'])) {
				$days = $this->product_model->getDays($para['effective_date'], $para['expiry_date']);
			}
			if ($days > 0) {
				$data = array('status' => 'Days', 'days' => $days);
			} else {
				$data = array('status' => 'Unknown', 'message' => '');
			}
		} else {
			//$premiumarr['premium'] = number_format($premiumarr['premium'], 2, '.', ',');
			$data = array('status' => 'OK', 'premiumarr' => $premiumarr);
		}
		header('Content-Type: application/json');
		echo json_encode($data);
	}
}
