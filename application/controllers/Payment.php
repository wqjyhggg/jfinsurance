<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Payment extends MY_Controller {
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
		$pay_submit = $this->input->post('pay_submit');
		if ($this->input->post() && !empty($payment) && is_array($payment)) {
			$this->load->model('payment_model');
			$this->load->model('plan_model');
			$payarr = array(
				'bank_name' => $this->input->post('bank_name'),
				'payor_name' => $this->input->post('payor_name'),
				'cheque_number' => $this->input->post('cheque_number'),
				'pay_to' => $this->input->post('pay_to'),
				'ispaid' => 1,
				'pay_mothed' => 'Checque',
				'pay_date' => date('Y-m-d'),
			);
			
			foreach ($payment as $payment_id) {
				$pay = $this->payment_model->get_payment_by_id($payment_id);
				if ($pay) {
					$payarr['note'] = $pay['note'] . "; Make pay by " . $this->session->userdata ( 'user' )['username'];
					if ($pay_submit && !$pay['ispaid']) {
						// Submit pay
						$this->payment_model->update($payment_id, $payarr);
					} else {
						$plan = $this->plan_model->get_plan_by_id($pay['plan_id']);
						if ($plan) {
							$pay['policy'] = $plan['policy'];
						} else {
							$pay['policy'] = "Unknown";
						}
					}
				}
				if (!$pay['ispaid']) {
					$data['payments'][] = $pay;
				}
			}
		} else {
			$data['error_message'] = "Don't understand which payment to pay";
		}
		if ($pay_submit) {
			redirect('plan');
		}
		
		$data['pay_url'] = current_url();
		
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
