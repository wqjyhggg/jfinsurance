<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Snappay extends CI_Controller {
  function _remap($param) {
    $this->index($param);
  }

	/**
	 * Index Page for this controller.
	 */
	public function index($plan_id)
	{
    $recv = trim(file_get_contents('php://input'));
    if (empty($recv) || empty($plan_id)) {
      log_message('debug', "snappay: plan_id[".$plan_id."];recv[".$recv."]");
      return;
    }
    $this->load->model('plan_model');
    $this->load->model('snappay_model');
    $plan = $this->plan_model->find_by_plan_id($plan_id);
    if (empty($plan)) {
      log_message('debug', "plan_id?[".$plan_id."];recv[".$recv."]");
      return;
    }

    $data = $this->snappay_model->get_postback($plan_id, $recv, $plan);
    /*
    $data = [
      'type' => "snappay-ali",
      'amount' => $trans_amount
    ];
    */
		$this->load->model('plan_history_model');
		$this->load->model('product_model');
		$this->load->model('payment_model');
		$this->load->model('log_model');
		
		$premium = (float)$data["amount"];
		$payinfo = "Pay Ali: " . 'Premium: $' . $premium . "; ";
		$product = $this->product_model->get_product($plan['product_short']);
		$dt = array();
		$dt['plan_id'] = $plan_id;
		$dt['amount'] = $premium;
		$dt['pay_type'] = 'premium';
		$dt['currency'] = $product['currency'];
		$dt['pay_mothed'] = 'Ali';
		$dt['added'] = date('c');
		$dt['note'] = $payinfo;
		$dt['ispaid'] = 0;

		$commission_rate = $this->product_model->get_commission_rate($plan['product_short'], $plan['user_id']);
		if (($plan['product_short'] == 'TOP') && ($plan['totalyears'] > 60)) {
			if ($commission_rate > 15) {
				$commission_rate -= 15;
			} else {
				$commission_rate = 0;
			}
		}
		if ($plan['product_short'] == 'TOP') {
			$commission_amount = ($premium - ($plan['tax'] * $premium / $plan['premium'])) * $commission_rate / 100.0;
		} else {
			$commission_amount = $premium * $commission_rate / 100.0;
		}
		// $up_commission_rate = $this->product_model->get_up_commission_rate($plan['product_short']);
		// $up_commission_amount = $premium * $up_commission_rate / 100.0;
		
		$dt['amount'] = $premium;
		$dt['rate'] = 100;
		$dt['pay_type'] = 'premium';
		$dt['premium_payment_id'] = 0;
		$payment_id = $this->payment_model->add($dt);
		$para = array(
				'plan_id' => $plan_id,
				'customer_id' => $plan['customer_id'],
				'payment_id' => $payment_id,
				'message' => $this->payment_model->logstr,
				'systemlog' => $this->payment_model->sqlstr
		);
		$this->log_model->activity('payment', $para);

		// // up commission
		// $dt['amount'] = $up_commission_amount;
		// $dt['rate'] = $up_commission_rate;
		// $dt['pay_type'] = 'up_commission';
		// $dt['premium_payment_id'] = $payment_id;
		// $up_commission_payment_id = $this->payment_model->add($dt);
		// $para = array(
		// 		'plan_id' => $plan_id,
		// 		'customer_id' => $plan['customer_id'],
		// 		'payment_id' => $up_commission_payment_id,
		// 		'message' => $this->payment_model->logstr,
		// 		'systemlog' => $this->payment_model->sqlstr
		// );
		// $this->log_model->activity('up_commission', $para);

		// commission
		$dt['amount'] = $commission_amount;
		$dt['rate'] = $commission_rate;
		$dt['pay_type'] = 'commission';
		$dt['premium_payment_id'] = $payment_id;
		if (($plan['product_short'] == 'OPL') || ($plan['product_short'] == 'JFVTC') || ($plan['product_short'] == 'JFR')) {
			$nowtm = time();
			$efftm = strtotime($plan['effective_date']);
			if ($nowtm <= $efftm) {
				if (($plan['sum_insured'] >= 100000) && ($plan['totaldays'] >= 365)) {
					if ($this->payment_model->adjust_commission_added_date($plan_id, $plan['effective_date'])) {
						$para = array(
								'plan_id' => $plan_id,
								'customer_id' => $plan['customer_id'],
								'payment_id' => 0,
								'message' => 'adjust apply time to effective date : ' . $plan_id . ' [ ' . $plan['effective_date'] . ' ]',
								'systemlog' => $this->payment_model->sqlstr
						);
						$this->log_model->activity('commission', $para);
					}
					$dt['added'] = $plan['effective_date'];
				} else {
					if ($this->payment_model->adjust_commission_added_back_date($plan_id, date('Y-m-d'))) {
						$para = array(
								'plan_id' => $plan_id,
								'customer_id' => $plan['customer_id'],
								'payment_id' => 0,
								'message' => 'adjust apply time to today : ' . $plan_id . ' [ ' . date('Y-m-d') . ' ]',
								'systemlog' => $this->payment_model->sqlstr
						);
						$this->log_model->activity('commission', $para);
					}
				}
			}
		}
		$commission_payment_id = $this->payment_model->add($dt);
		$para = array(
				'plan_id' => $plan_id,
				'customer_id' => $plan['customer_id'],
				'payment_id' => $commission_payment_id,
				'message' => $this->payment_model->logstr,
				'systemlog' => $this->payment_model->sqlstr
		);
		$this->log_model->activity('commission', $para);

    $history_id = 0;
    if (($history = $this->plan_history_model->get_plan_history_by_plan_id($plan_id)) && ($history["actualrate"]>0)) {
      $history_id = $history["plan_history_id"];
    } else {
      // Add missing first record.
      if ($plan['status_id'] > 1) {
        $history_id = $this->plan_history_model->add($plan_id, $plan['status_id']);
      }
    }

		$para = array('payment_id' => $payment_id, 'payinfo' => $payinfo, 'commission_payment_id' => $commission_payment_id, 'status_id' => Plan_model::SOLD, 'policy' => $this->plan_model->get_policy_number($plan_id, 2));
		$this->plan_model->update($plan_id, $para);
    if ($history_id) {
      $this->plan_history_model->add_remove($history_id);
    }
    $this->plan_history_model->add($plan_id, Plan_model::SOLD);

		$para = array(
				'plan_id' => $plan_id,
				'customer_id' => $plan['customer_id'],
				'payment_id' => $payment_id,
				'message' => $this->plan_model->logstr,
				'systemlog' => $this->plan_model->sqlstr
		);
		$this->log_model->activity('plan', $para);
	}
}
