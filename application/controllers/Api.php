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
		} else {
			$this->setting_model->set_default_user();
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
	
	public function premium() {
		if ($this->valid()) {
			$post = $this->input->post();
			$data['errormsg'] = 'Unknown Remote Server Error';
			$data['success'] = 'Fail';
			$this->load->model('product_model');
			if ($post['product_short'] == 'TOP') {
				$post['totaldays'] = $post['total_days'];
				if ($premium = $this->product_model->get_top_quote($post)) {
					$data['success'] = 'OK';
					$data['premiumArr'] = $premium;
					$data['errormsg'] = '';
				}
			} else {
				if ($premium = $this->product_model->get_premium_sub($post)) {
					$data['success'] = 'OK';
					$data['premiumArr'] = $premium;
					$data['errormsg'] = '';
				}
			}
		} else {
			$data['errormsg'] = $this->error;
		}
		header('Content-Type: application/json');
		header('Cache-Control: no-store, no-cache, must-revalidate');
		echo json_encode($data);
	}
	
	public function agent($agent_id=0) {
		if ($this->valid()) {
			$data['errormsg'] = 'Unknown agent';
			$data['success'] = 'Fail';
			$this->load->model('user_model');
			if ($agent = $this->user_model->get_user_by_id($agent_id)) {
				$data['success'] = 'OK';
				$data['agent']['agent_id'] = $agent['user_id'];
				$data['agent']['group_id'] = $agent['user_group_id'];
				$data['agent']['username'] = $agent['username'];
				$data['agent']['firstname'] = $agent['firstname'];
				$data['agent']['lastname'] = $agent['lastname'];
				$data['agent']['email'] = $agent['email'];
				$data['agent']['mobile_phone'] = $agent['mobile_phone'];
				$products = $this->user_model->get_user_product_list($agent_id);
				$data['agent']['products'] = array();
				foreach ($products as $p) {
					$data['agent']['products'][] = $p['product_short'];
				}
				$data['errormsg'] = '';
			}
		} else {
			$data['errormsg'] = $this->error;
		}
		header('Content-Type: application/json');
		header('Cache-Control: no-store, no-cache, must-revalidate');
		echo json_encode($data);
	}
	
	public function login() {
		if ($this->valid()) {
			$data['errormsg'] = 'Unknown agent';
			$data['success'] = 'Fail';
			$this->load->model('user_model');
			if ($agent = $this->user_model->login( $this->input->post('username'), $this->input->post('password') )) {
				$data['success'] = 'OK';
				$data['agent']['agent_id'] = $agent['user_id'];
				$data['agent']['group_id'] = $agent['user_group_id'];
				$data['agent']['username'] = $agent['username'];
				$data['agent']['firstname'] = $agent['firstname'];
				$data['agent']['lastname'] = $agent['lastname'];
				$data['agent']['email'] = $agent['email'];
				$data['agent']['mobile_phone'] = $agent['mobile_phone'];
				$products = $this->user_model->get_user_product_list($agent['user_id']);
				$data['agent']['products'] = array();
				foreach ($products as $p) {
					$data['agent']['products'][] = $p['product_short'];
				}
				$data['errormsg'] = '';
			}
		} else {
			$data['errormsg'] = $this->error;
		}
		header('Content-Type: application/json');
		header('Cache-Control: no-store, no-cache, must-revalidate');
		echo json_encode($data);
	}
	
	public function products() {
		if ($this->valid()) {
			$data['success'] = 'OK';
			$this->load->model('product_model');
			$plan = $this->product_model->product_list(1);
			$data['plan'] = array();
			foreach ($plan as $key => $p) {
				$data['plan'][$key] = array(
						'product_short' => $p['product_short'],
						'min_premium' => $p['min_premium'],
						'full_name' => $p['full_name'],
						'up_insuer' => $p['up_insuer'],
						'prepare_rate' => $p['prepare_rate'],
						'currency' => $p['currency'],
				);
			}
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
			$this->load->model('customer_model');
				
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
			else if (!empty($this->input->post('policy_match'))) $data['policy_match'] = $this->input->post('policy_match');
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
					if ($plan['status_id'] <= Plan_model::QUOTE) continue;
					
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
					$p['rate_options'] = $plan['rate_options'];
					$p['refund_date'] = $plan['refund_date'];
					$p['holiday_rate'] = $plan['holiday_rate'];
					$p['premium'] = $plan['premium'];
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
					$p['note'] = $plan['note'];
					$p['package'] = $plan['package'];
					$p['free_cancel'] = $plan['free_cancel'];
					$p['annual_plan_days'] = $plan['annual_plan_days'];
					$p['ad_and_d_ck'] = $plan['ad_and_d_ck'];
					$p['ad_and_d_insured'] = $plan['ad_and_d_insured'];
					$p['flight_accident_ck'] = $plan['flight_accident_ck'];
					$p['flight_accident_insured'] = $plan['flight_accident_insured'];
					$p['trip_cancellation_ck'] = $plan['trip_cancellation_ck'];
					$p['trip_cancellation_insured'] = $plan['trip_cancellation_insured'];
					$p['questionnaire'] = $plan['questionnaire'];
					$p['question1'] = $plan['question1'];
					$p['question2'] = $plan['question2'];
					$p['question3'] = $plan['question3'];
					$p['question4'] = $plan['question4'];
					$p['question5'] = $plan['question5'];
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
					$p['agent_id'] = $plan['user_id'];
					$p['agent_firstname'] = $plan['agent_firstname'];
					$p['agent_lastname'] = $plan['agent_lastname'];
					$p['family'] = array();
					if ($p['isfamilyplan']) {
						$family = $this->customer_model->get_customer_by_parent_id($p['customer_id']);
						if ($family) {
							foreach ($family as $fm) {
								$p['family'][] = array('firstname' => $fm['firstname'], 'lastname' => $fm['lastname'], 'birthday' => $fm['birthday'], 'gender' => $fm['gender'], );
							}
						}
					}
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

	public function claim_summary() {
		if ($this->valid()) {
			$this->load->model('plan_model');
				
			$data = array();
			if (!empty($this->input->post('start_dt'))) $data['start_dt'] = $this->input->post('start_dt');
			if (!empty($this->input->post('end_dt'))) $data['end_dt'] = $this->input->post('end_dt');
			if (!empty($this->input->post('product_short'))) $data['product_short'] = $this->input->post('product_short');
			if (!empty($this->input->post('agent_id'))) $data['agent_id'] = $this->input->post('agent_id');
			if (empty($data['start_dt']) || empty($data['end_dt'])) {
				$json['errormsg'] = "Parameter has something wrong";
			} else {
				$json['data'] = $this->plan_model->claim_summary($data);
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
