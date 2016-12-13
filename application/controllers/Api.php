<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

class Api extends MY_Controller {
	public $error;

	private function valid() {
		$this->error = '';

		$ip = $this->input->ip_address();
		$key = $this->input->post('key');
		$this->load->model('setting_model');

		$st = $this->setting_model->get_setting_by_name('api', $ip);
		if (empty($st) || ($st['value'] != $key)) {
			$this->error = "Can't access. Your IP is ". $ip;
		}
		return empty($this->error);
	}
	
	public function index() {
		if ($this->valid()) {
			$data['success'] = 'OK';
		} else {
			$data['errormsg'] = $this->error;
		}
		header('Content-Type: application/json');
		header('Cache-Control: no-store, no-cache, must-revalidate');
		echo json_encode($data);
	}
	
	public function search() {
		if ($this->valid()) {
			$this->load->model('setting_model');
			$this->load->model('status_model');
			$this->load->model('plan_model');

			$this->setting_model->set_default_user();
			$data = array();
			if (!empty($this->input->post('plan_id'))) $data['plan_id'] = $this->input->post('plan_id');
			if (!empty($this->input->post('firstname'))) $data['firstname'] = $this->input->post('firstname');
			if (!empty($this->input->post('lastname'))) $data['lastname'] = $this->input->post('lastname');
			if (!empty($this->input->post('birthday'))) {
				$data['birthday'] = $this->input->post('birthday');
				if (!empty($this->input->post('birthday2'))) {
					$data['birthday2'] = $this->input->post('birthday2');
				} else {
					$data['birthday2'] = $data['birthday'];
				}
			}
			if (!empty($this->input->post('policy'))) $data['policy'] = $this->input->post('policy');
			if (!empty($this->input->post('apply_date'))) {
				$data['apply_date'] = $this->input->post('apply_date');
				if (!empty($this->input->post('apply_date2'))) {
					$data['apply_date2'] = $this->input->post('apply_date2');
				} else {
					$data['apply_date2'] = $data['apply_date'];
				}
			}
			if (!empty($this->input->post('arrival_date'))) {
				$data['arrival_date'] = $this->input->post('arrival_date');
				if (!empty($this->input->post('arrival_date2'))) {
					$data['arrival_date2'] = $this->input->post('arrival_date2');
				} else {
					$data['arrival_date2'] = $data['arrival_date'];
				}
			}
			if (!empty($this->input->post('effective_date'))) {
				$data['effective_date'] = $this->input->post('effective_date');
				if (!empty($this->input->post('effective_date2'))) {
					$data['effective_date2'] = $this->input->post('effective_date2');
				} else {
					$data['effective_date2'] = $data['effective_date'];
				}
			}
			if (!empty($this->input->post('expiry_date'))) {
				$data['expiry_date'] = $this->input->post('expiry_date');
				if (!empty($this->input->post('expiry_date2'))) {
					$data['expiry_date2'] = $this->input->post('expiry_date');
				} else {
					$data['expiry_date2'] = $data['expiry_date'];
				}
			}
			// $data['uname'] = $this->input->post('uname'); // Agent 
			// $data['batch_number'] = $this->input->post('batch_number');
			// $data['status_id'] = $this->input->post('status_id');
			// $data['product_short'] = $this->input->post('product_short');
			// $data['province2'] = $this->input->post('province2');
			// $data['country2'] = $this->input->post('country2');
			if (empty($data)) {
				$json['errormsg'] = 'Empty query condition';
			} else {
				$plan_list = $this->plan_model->plan_search($data);
				foreach ($plan_list as $plan) {
					$p = array();
					$p['plan_id'] = $plan['plan_id'];
					$p['customer_id'] = $plan['customer_id'];
					$p['status_id'] = $plan['status_id'];
					$p['policy'] = $plan['policy'];
					$p['product_short'] = $plan['product_short'];
					$p['apply_date'] = $plan['apply_date'];
					$p['isfamilyplan'] = $plan['isfamilyplan'];
					$p['arrival_date'] = $plan['arrival_date'];
					$p['effective_date'] = $plan['effective_date'];
					$p['expiry_date'] = $plan['expiry_date'];
					$p['beneficiary'] = $plan['beneficiary'];
					$p['stable_condition'] = $plan['stable_condition'];
					$p['spouse'] = $plan['spouse'];
					$p['sum_insured'] = $plan['sum_insured'];
					$p['deductible_amount'] = $plan['deductible_amount'];
					$p['totaldays'] = $plan['totaldays'];
					$p['street_number'] = $plan['street_number'];
					$p['street_name'] = $plan['street_name'];
					$p['suite_number'] = $plan['suite_number'];
					$p['city'] = $plan['city'];
					$p['province2'] = $plan['province2'];
					$p['country2'] = $plan['country2'];
					$p['postcode'] = $plan['postcode'];
					$p['phone1'] = $plan['phone1'];
					$p['phone2'] = $plan['phone2'];
					$p['institution'] = $plan['institution'];
					$p['institution_addr'] = $plan['institution_addr'];
					$p['student_id'] = $plan['student_id'];
					$p['institution_phone'] = $plan['institution_phone'];
					$p['institution_fax'] = $plan['institution_fax'];
					$p['contact_email'] = $plan['contact_email'];
					$p['contact_phone'] = $plan['contact_phone'];
					$p['residence'] = $plan['residence'];
					$p['firstname'] = $plan['firstname'];
					$p['lastname'] = $plan['lastname'];
					$p['gender'] = $plan['gender'];
					$p['birthday'] = $plan['birthday'];
					$p['agent_firstname'] = $plan['agent_firstname'];
					$p['agent_lastname'] = $plan['agent_lastname'];
					$json['plan_list'][] = $p;
				}
	
				$json['status_list'] = $this->status_model->status_list();
	
				$json['success'] = 'OK';
			}
		} else {
			$json['errormsg'] = $this->error;
		}
		header('Content-Type: application/json');
		header('Cache-Control: no-store, no-cache, must-revalidate');
		echo json_encode($json);
	}
}
