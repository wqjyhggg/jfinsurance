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
		if (isset($para['trans_id'])) {
			$this->db->set('trans_id', $para['trans_id']);
		}
		if (isset($para['retry'])) {
			$this->db->set('retry', $para['retry']);
		}
		if (isset($para['retry_date'])) {
			$this->db->set('retry_date', $para['retry_date']);
		}
		if (isset($para['refund_amount'])) {
			$this->db->set('refund_amount', $para['refund_amount']);
		}
		if (isset($para['admin_fee'])) {
			$this->db->set('admin_fee', $para['admin_fee']);
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
		if (isset($para['trans_id'])) {
			$this->db->set('trans_id', $para['trans_id']);
		}
		if (isset($para['retry'])) {
			$this->db->set('retry', $para['retry']);
		}
		if (isset($para['retry_date'])) {
			$this->db->set('retry_date', $para['retry_date']);
		}
		if (isset($para['refund_amount'])) {
			$this->db->set('refund_amount', $para['refund_amount']);
		}
		if (isset($para['admin_fee'])) {
			$this->db->set('admin_fee', $para['admin_fee']);
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

	public function create_payment_records($plan_id, $first_amount, $month_pay, $mountly_number, $effective_date, $admin_fee) {
		if ($this->db->where("plan_id", $plan_id)->where("paid>", 0)->get("monthly_payment")->row_array()) {
			// Payment already edited build. Can't rebuild
			return "Payment already edited. Can not rebuild";
		}
		$this->db->where("plan_id", $plan_id)->delete("monthly_payment");		// Remove if it is existed
		$precord = [
			"plan_id" => $plan_id,
			"pay_type" => 0,
			"amount" => $first_amount,
			"admin_fee" => $admin_fee,
			"pay_date" => date("Y-m-d"),
			"retry_date" => date("Y-m-d")
		];
		if ($record_id = $this->add($precord)) {
			$recurrdate = new DateTime($effective_date);
			if ($effective_date == $precord["pay_date"]) {
				// Init changed 3 month
				$recurrdate->modify('+1 month');
				// $recurrdate->modify('+1 days');
			}
			$precord["pay_type"] = 1;
			$precord["admin_fee"] = 0;
			$precord["amount"] = $month_pay;
			for ($i = 0; $i < $mountly_number; $i++) {
				$precord["pay_date"] = $recurrdate->format('Y-m-d');
				$precord["retry_date"] = $precord["pay_date"];
				$precord["retry"] = 0;
				$this->add($precord);
				$recurrdate->modify('+1 month');
				// $recurrdate->modify('+1 days');
			}
			return $record_id;
		}
		$error = $this->db->error(); // Returns an array with 'code' and 'message'
    // echo "Database Error Code: " . $error['code'] . "<br>";
    // echo "Database Error Message: " . $error['message'];
		return "Can not create payment recodes: ".$this->db->last_query()."; ".$error['message'];
	}

	public function get_monthlypay_data($plan_id) {
		// $sql = "SELECT
		// 					SUM(amount) AS premium,
		// 					SUM(CASE WHEN paid = 1 THEN amount ELSE 0 END) AS paid_amount,
		// 					SUM(admin_fee) AS admit_fee,
		// 					( SELECT amount FROM monthly_payment mp4 WHERE mp4.plan_id = mp.plan_id ORDER BY id ASC LIMIT 1 ) AS init_pay,
		// 					( SELECT amount FROM monthly_payment mp2 WHERE mp2.plan_id = mp.plan_id ORDER BY id DESC LIMIT 1 ) AS monthly_pay,
		// 					( SELECT pay_date FROM monthly_payment mp3 WHERE mp3.plan_id = mp.plan_id AND paid = 1 ORDER BY id DESC LIMIT 1 ) AS paid_date
		// 				FROM monthly_payment mp WHERE plan_id = ".intval($plan_id);
		// $rt = $this->db->query($sql)->row_array();
		$rt = [
			"premium" => 0,
			"admin_fee" => 0,
			"init_pay" => 0,
			"monthly_pay" => 0,
			"total_paid" => 0,
			"total_refund" => 0,
			"recurrent_times" => 0,
			"init_pay_date" => "N/A",
			"last_pay_date" => "N/A",
		];
		if ($rts = $this->get_by_plan_id($plan_id)) {
			foreach ($rts as $rc) {
				$rt["premium"] += $rc["amount"];
				$rt["admin_fee"] += $rc["admin_fee"];
				if ($rc["paid"] == 1) {
					$rt["total_paid"] += $rc["amount"];
					$rt["last_pay_date"] = $rc["pay_date"];
					$rt["total_refund"] += $rc["refund_amount"];
				}
				if ($rc["pay_type"]) {
					$rt["recurrent_times"]++;
					$rt["monthly_pay"] = $rc["amount"];
				} else {
					$rt["init_pay"] = $rc["amount"];
					$rt["init_pay_date"] = $rc["pay_date"];
				}
			}
			$rt["premium"] -= $rt["admin_fee"];
		}
		return $rt;
	}

	public function get_by_plan_id($plan_id) {
		return $this->db->where("plan_id", $plan_id)->get("monthly_payment")->result_array();
	}
	
	public function get_by_payment_id($plan_id, $payment_id) {
		return $this->db->where("plan_id", $plan_id)->where("payment_id", $payment_id)->get("monthly_payment")->row_array();
	}

	public function today_payments($dt) {
		$recurArr = $this->db->where("pay_type", 1)->where("paid", 0)->where("pay_date", $dt)->get("monthly_payment")->result_array();
		$retryArr = $this->db->where("pay_type", 1)->where("paid", -2)->where("retry<", 3)->where("retry_date", $dt)->get("monthly_payment")->result_array();
		if ($recurArr) {
			if ($retryArr) {
				return array_merge($recurArr, $retryArr);
			}
			return $recurArr;
		}
		return $retryArr;
	}

	public function plan_today_payments($plan_id) {
		$today = date("Y-m-d");
		$rc = $this->db->where("pay_type", 1)->where("paid", 0)->where("pay_date", $today)->get("monthly_payment")->row_array();
		return $rc;
	}

	public function set_profile_id($plan_id, $profile_id) {
		$this->db->where("plan_id", $plan_id)->set('profile_id', $profile_id)->update("monthly_payment");
	}

	public function void_unpaid_record($plan_id, $paid=-1) {
		$now = date("Y-m-d H:i:s");
		return $this->db->where("plan_id", $plan_id)->where("paid<=", 0)->set("paid", $paid)->set("pay_time", $now)->update("monthly_payment");
	}

	public function do_cancel($plan_id, $total_amount) {
		$this->void_unpaid_record($plan_id);
		$initRc = $this->db->where("plan_id", $plan_id)->where("pay_type", 0)->get("monthly_payment")->row_array();
		if ($initRc) {
			$this->db->where("monthly_payment_id", $initRc["monthly_payment_id"])->set("refund_amount", $total_amount)->update("monthly_payment");
		}
		return $refund_amount;
	}

	public function do_terminate($plan_id, $refund_date) {
		$this->void_unpaid_record($plan_id, -3);
		$refund_amount = 0;
		$lastRc = $this->db->where("plan_id", $plan_id)->order_by("monthly_payment_id", "ASC")->limit(1)->get("monthly_payment")->row_array();
		if (empty($lastRc)) {
			return $refund_amount;
		}
		$monthly_amount = $lastRc["amount"];
		$admin_fee = $monthly_amount * 2 + 50;
		$paid_amount = 0;

		$paidRc = $this->db->where("plan_id", $plan_id)->where("paid", 1)->get("monthly_payment")->result_array();
		if ($paidRc) {
			foreach ($paidRc as $rc) {
				$paid_amount += $rc["amount"];
			}
		}
		return $paid_amount - $admin_fee;
	}

	public function do_refund($plan_id, $refund_date, $effective_date) {
		$this->void_unpaid_record($plan_id);
		$refund_amount = 0;
		$lastRc = $this->db->where("plan_id", $plan_id)->order_by("monthly_payment_id", "ASC")->limit(1)->get("monthly_payment")->row_array();
		if (empty($lastRc)) {
			return $refund_amount;
		}
		$monthly_amount = $lastRc["amount"];
		$admin_fee = $monthly_amount * 2;

		$paidRc = $this->db->where("plan_id", $plan_id)->where("paid", 1)->get("monthly_payment")->result_array();
		if ($paidRc) {
			foreach ($paidRc as $rc) {
				if ($rc["pay_type"] == 0) {
					// Init charge
					if ($refund_date < $effective_date) {
						// Should be cancel??? do as refund anyway
						if (($admin_fee + 50) < $rc["amount"]) {
							// Amount should be 3 monthly fee + 50
							$r_amount = $rc["amount"] - ($admin_fee + 50);
							$refund_amount += $r_amount;
							$this->db->where("monthly_payment_id", $rc["monthly_payment_id"])->set("refund_amount", $r_amount)->update("monthly_payment");
						}
					}
				} else {
					if ($refund_date < $rc["pay_date"]) {
						$refund_amount += $monthly_amount;
						$this->db->where("monthly_payment_id", $rc["monthly_payment_id"])->set("refund_amount", $monthly_amount)->update("monthly_payment");
					}
				}
			}
		}
		return $refund_amount;
	}

	public function change_effective_date($plan_id, $effective_date) {
		if ($records = $this->db->where("plan_id", $plan_id)->where("pay_type", 1)->where("paid", 0)->get("monthly_payment")->result_array()) {
			$recurrdate = new DateTime($effective_date);
			foreach ($records as $rc) {
				$pay_date = $recurrdate->format('Y-m-d');
				$this->db->where("monthly_payment_id", $rc["monthly_payment_id"])->set('pay_date', $pay_date)->set('retry_date', $pay_date)->update("monthly_payment");
				$recurrdate->modify('+1 month');
				// $recurrdate->modify('+1 days');
			}
		}
	}

	public function clear_old($plan_id) {
		$this->db->where("plan_id", $plan_id)->delete("monthly_payment");
	}
}
