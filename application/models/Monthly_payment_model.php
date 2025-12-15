<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Monthly_payment_model extends CI_Model {
	public $error;

	/**
	 * Get payment by id
	 *
	 * @param integer $payment_id
	 * @return array
	 */
	public function get_by_id($monthly_payment_id) {
		$this->db->where('monthly_payment_id', $monthly_payment_id);
		return $this->db->get('monthly_payment')->row_array();
	}
	
	/**
	 * Add payment data
	 * 
	 * @param	array	$para	Add payment record
	 * @return	integer	record ID
	 */
	public function add($para) {
		if (empty($para['plan_id'])) {
			$this->error = "Missing plan id";
			return 0;
		} else {
			$this->db->set('plan_id', $para['plan_id']);
		}
		if (isset($para['payment_id'])) {
			$this->db->set('payment_id', $para['payment_id']);
		}
		if (isset($para['profile_id'])) {
			$this->db->set('profile_id', $para['profile_id']);
		}
		if (isset($para['pay_type'])) {
			$this->db->set('pay_type', $para['pay_type']);
		}
		if (isset($para['paid'])) {
			$this->db->set('paid', $para['paid']);
		}
		if (isset($para['retry'])) {
			$this->db->set('retry', $para['retry']);
		}
		if (isset($para['amount'])) {
			$this->db->set('amount', $para['amount']);
		}
		if (isset($para['pay_date'])) {
			$this->db->set('pay_date', $para['pay_date']);
		}
		if (isset($para['pay_time'])) {
			$this->db->set('pay_time', $para['pay_time']);
		}
		if (isset($para['postdata'])) {
			$this->db->set('postdata', $para['postdata']);
		}
		if (isset($para['rawdata'])) {
			$this->db->set('rawdata', $para['rawdata']);
		}
		if ($this->db->insert('monthly_payment')) {
			$monthly_payment_id = $this->db->insert_id();
			return $monthly_payment_id;
		}
		$this->error = "Can not add record";
		return 0;
	}

	/**
	 * Update payment data
	 * 
	 * @param	array	$para	Add payment record
	 * @return	integer	record ID
	 */
	public function update($monthly_payment_id, $para) {
		$this->db->where('monthly_payment_id', $monthly_payment_id);
		if (isset($para['plan_id'])) {
			$this->db->set('plan_id', $para['plan_id']);
		}
		if (isset($para['payment_id'])) {
			$this->db->set('payment_id', $para['payment_id']);
		}
		if (isset($para['profile_id'])) {
			$this->db->set('profile_id', $para['profile_id']);
		}
		if (isset($para['pay_type'])) {
			$this->db->set('pay_type', $para['pay_type']);
		}
		if (isset($para['paid'])) {
			$this->db->set('paid', $para['paid']);
		}
		if (isset($para['retry'])) {
			$this->db->set('retry', $para['retry']);
		}
		if (isset($para['amount'])) {
			$this->db->set('amount', $para['amount']);
		}
		if (isset($para['pay_date'])) {
			$this->db->set('pay_date', $para['pay_date']);
		}
		if (isset($para['pay_time'])) {
			$this->db->set('pay_time', $para['pay_time']);
		}
		if (isset($para['postdata'])) {
			$this->db->set('postdata', $para['postdata']);
		}
		if (isset($para['rawdata'])) {
			$this->db->set('rawdata', $para['rawdata']);
		}
		if ($this->db->update('monthly_payment')) {
			return $monthly_payment_id;
		}
		return 0;
	}

	public function create_payment_records($plan_id, $first_amount, $month_pay, $mountly_number, $effective_date) {
		if ($this->db->where("plan_id", $plan_id)->where("paid!=", 0)->get("monthly_payment")->row_array()) {
			// Payment already edited build. Can't rebuild
			return "Payment already edited. Can not rebuild";
		}
		$this->db->where("plan_id", $plan_id)->delete("monthly_payment");		// Remove if it is existed
		$precord = [
			"plan_id" => $plan_id,
			"pay_type" => 0,
			"amount" => $first_amount,
			"pay_date" => date("Y-m-d")
		];
		if ($record_id = $this->add($precord)) {
			$recurrdate = new DateTime($effective_date);
			$precord["pay_type"] = 1;
			$precord["amount"] = $month_pay;
			for ($i = 0; $i < $mountly_number; $i++) {
				$recurrdate->modify('+1 month');
				$precord["pay_date"] = $recurrdate->format('Y-m-d');
				$this->add($precord);
			}
			return $record_id;
		}
		$error = $this->db->error(); // Returns an array with 'code' and 'message'
    // echo "Database Error Code: " . $error['code'] . "<br>";
    // echo "Database Error Message: " . $error['message'];
		return "Can not create payment recodes: ".$this->db->last_query()."; ".$error['message'];
	}

	public function get_by_plan_id($plan_id) {
		return $this->db->where("plan_id", $plan_id)->get("monthly_payment")->result_array();
	}
	
	public function get_by_payment_id($payment_id) {
		return $this->db->where("payment_id", $payment_id)->get("monthly_payment")->row_array();
	}

	public function set_profile_id($plan_id, $profile_id) {
		$this->db->where("plan_id", $plan_id)->set('profile_id', $profile_id)->update("monthly_payment");
	}

	public function clear_old($plan_id) {
		$this->db->where("plan_id", $plan_id)->delete("monthly_payment");
	}
}
