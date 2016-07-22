<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Claim extends MY_Controller {
	public $data;

	/**
	 * Index Page for this controller.
	 */
	public function index()
	{
	}
	
	public function makepay() {
		$beuser = $this->func_model->verify_login();

		$data = array();
		$data['payments'] = array();
		$payment = $this->input->post('payment');
		if ($this->input->post() && !empty($payment) && is_array($payment)) {
			$this->load->model('payment_model');
			foreach ($payment as $pay) {
				$data['payments'][] = 
				$customer = $this->customer_model->get_customer_by_id($customer_id);
				if ($customer) {
					$plan = $this->plan_model->get_plan_by_id($customer['plan_id']);
					if ($plan && ($plan['status_id'] >= 2)) {
						$para = array();
						$para['plan_id'] = $plan['plan_id'];
						$para['user_id'] = $beuser['user_id'];
						$para['customer_id'] = $customer['customer_id'];
						$para['done'] = 2;
						$para['product_short'] = $plan['product_short'];
						$para['policy_number'] = $plan['policy'];
						$para['lastname'] = $customer['lastname'];
						$para['firstname'] = $customer['firstname'];
						$para['birthday'] = $customer['birthday'];
						$para['gender'] = $customer['gender'];
						$para['claim_date'] = date('Y-m-d');
						$claim_id = $this->claim_model->add($para);
						$log = array(
								'plan_id' => $plan['plan_id'], 
								'customer_id' => $customer['customer_id'], 
								'payment_id' => 0, 
								'message' => $this->claim_model->logstr, 
								'systemlog' => $this->claim_model->sqlstr
						);
						$this->log_model->activity('claim', $para);
						$claim = $this->claim_model->get_claim_by_id($claim_id);
						if ($claim) {
							$para = array('status_id' => 4, 'note' => $plan['note'] . ";\n" . "Add Claim(" . $claim['claim_id'] .")");
							$this->plan_model->update($plan['plan_id'], $para);
							$log = array(
									'plan_id' => $plan['plan_id'], 
									'customer_id' => $customer['customer_id'], 
									'payment_id' => 0, 
									'message' => $this->claim_model->logstr, 
									'systemlog' => $this->claim_model->sqlstr
							);
							$this->log_model->activity('plan', $para);
						}
					} else {
						$this->data['error_message'] = "Can't find customer";
					}
				} else {
					$this->data['error_message'] = "Can't find customer";
				}
			}
		} else {
			$data['error_message'] = "Don't understand which payment to pay";
		}

		$data['title_txt'] = 'Claim';
		$data['top_menu'] = $this->menu_model->load_top_menu();
		$data['menu'] = $this->menu_model->load_meun();
		$data['csrf'] = array (
				'name' => $this->security->get_csrf_token_name (),
				'value' => $this->security->get_csrf_hash ()
		);
		
		$this->load->common('payment/makepay', $data);
	}
}
