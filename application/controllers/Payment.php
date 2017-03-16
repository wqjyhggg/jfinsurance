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
	
	/**
	 * Make a Payment
	 */
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
				'invoice_num' => $this->input->post('invoice_num'),
				'bank_name' => $this->input->post('bank_name'),
				'payor_name' => $this->input->post('payor_name'),
				'cheque_number' => $this->input->post('cheque_number'),
				'pay_to' => $this->input->post('pay_to'),
				'note' => $this->input->post('note'),
				'ispaid' => 1,
				'pay_mothed' => 'Cheque',
				'pay_date' => date('Y-m-d'),
			);
			
			$redirect_plan_id = 0;
			foreach ($payment as $payment_id) {
				$pay = $this->payment_model->get_payment_by_id($payment_id);
				if ($pay) {
					$redirect_plan_id = empty($pay['plan_id']) ? 0 : $pay['plan_id'];
					$payarr['note'] .= " Make pay by " . $this->session->userdata ( 'user' )['username'] . "; " . $pay['note'];
					if ($pay_submit && !$pay['ispaid']) {
						// Submit pay
						if ($pay['pay_type'] == 'premium') {
							unset($payarr['pay_mothed']);
							$this->payment_model->update($payment_id, $payarr);
							$unpaied = $this->payment_model->get_payment($pay['plan_id'], 'premium', 0);
							$plan = $this->plan_model->get_plan_by_id($pay['plan_id']);
							if (empty($unpaied) && $plan && ($plan['status_id'] == 2)) {
								$note = 'Mark pay by: ' . $beuser['username'] . "; " . $plan['note'];
								$para = array('note' => $note, 'status_id' => 3);
								$this->plan_model->update($plan['plan_id'], $para);
								$para = array(
										'plan_id' => $plan['plan_id'],
										'customer_id' => $plan['customer_id'],
										'payment_id' => $payment_id,
										'message' => $this->plan_model->logstr,
										'systemlog' => $this->plan_model->sqlstr
								);
								$this->log_model->activity('plan', $para);
								if (!empty($plan['batch_number']) && !empty($this->input->post('batchpay_'.$payment_id))) {
									// Batch Upadte
									$this->load->model('batch_model');
									$this->batch_model->batch_pay($plan['batch_number'], $payarr);
								}
							}
						} else {
							$payarr['pay_mothed'] = 'Cheque';
							$this->payment_model->update($payment_id, $payarr);
						}
					} else {
						$plan = $this->plan_model->get_plan_by_id($pay['plan_id']);
						if ($plan) {
							if ($pay['pay_type'] == 'premium') {
								$pay['batch_number'] = empty($plan['batch_number']) ? '' : $plan['batch_number'];
							}
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
			if (!empty($redirect_plan_id)) {
				redirect('plan/detail/'.$redirect_plan_id);
			}
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

	/**
	 * Revert Payment
	 *
	 * @param integer $plan_id
	 */
	public function revert($payment_id=0) {
		$beuser = $this->func_model->verify_login(TRUE);
		$this->load->model('payment_model');
		$this->load->model('plan_model');

		if (empty($payment_id)) {
			$payment_id = $this->input->post('payment_id');
		}
		if (empty($payment_id)) {
			redirect('user/login');
		}
		$payment = $this->payment_model->get_payment_by_id($payment_id);
		if (empty($payment)) {
			redirect('user/login');
		}

		$plan = $this->plan_model->get_plan_by_id($payment['plan_id']);
		
		$data['beuser'] = $beuser;
		$dt = array();
		$dt['amount'] = 0;
		$dt['ispaid'] = 1;
		$dt['note'] = "Revert: " . $payment['amount'] . "; " . $payment['note'];
		$payment_id = $this->payment_model->update($payment_id, $dt);
		$para = array(
				'plan_id' => $plan['plan_id'],
				'customer_id' => $plan['customer_id'],
				'payment_id' => $payment_id,
				'message' => $this->payment_model->logstr,
				'systemlog' => $this->payment_model->sqlstr
		);
		if (($payment['pay_type'] == 'refund') || ($payment['pay_type'] == 'cancel')) {
			$this->log_model->activity('payment', $para);
			
			$note = "Revert " . $payment['pay_type'] . " : " . $payment['amount'] . "; " . $plan['note'];
			$para = array('status_id' => 3, 'note' => $note );  // Change status to Paid
			$this->plan_model->update($plan['plan_id'], $para);
			$para = array(
					'plan_id' => $plan['plan_id'],
					'customer_id' => $plan['customer_id'],
					'payment_id' => $payment_id,
					'message' => $this->plan_model->logstr,
					'systemlog' => $this->plan_model->sqlstr
			);
			$this->log_model->activity('plan', $para);
		} else {
			$this->log_model->activity('commission', $para);
		}
		redirect('plan');
	}
}
