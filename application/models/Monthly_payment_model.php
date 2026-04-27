<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Monthly_payment_model extends CI_Model {
	const PAID = 1;
	const WAITING = 0;
	const VOID = -1;
	const PAYERROR = -2;
	const TERMINATED = -3;

	public $error;
	public $admin_fee = 80;

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

	public function build_new_plan_list_onepass($plan_list) {
    $new = [];
    $currentPlanId = null;
    $candidate = null;
    $candidateHasNegative = false;

    foreach ($plan_list as $item) {
			if (!isset($item['plan_id']) || !isset($item['paid'])) {
				// Skip error
				continue;
			}
			$pid = $item['plan_id'];
			$paid = $item['paid'];

			if ($currentPlanId === null) {
				$currentPlanId = $pid;
				$candidate = $item;
				$candidateHasNegative = ($paid < 0);
				continue;
			}

			if ($pid !== $currentPlanId) {
					// push the candidate for previous group
					$new[] = $candidate;
					// reset for new group
					$currentPlanId = $pid;
					$candidate = $item;
					$candidateHasNegative = ($paid < 0);
					continue;
			}

			// same plan_id group:
			if ($candidateHasNegative) {
					// we already found a negative and per rule we keep the first negative, so do nothing
					continue;
			}

			if ($paid < 0) {
					// first negative in this group -> choose it
					$candidate = $item;
					$candidateHasNegative = true;
					continue;
			}

			// no negative yet and current item has non-negative paid
			// rule: if all are >=0 we want last record, so update candidate to current (last seen)
			$candidate = $item;
    }

    // push last group's candidate
    if ($candidate !== null) {
        $new[] = $candidate;
    }

    return $new;
	}

	public function plan_search($para, $plan, $limit=0, $start=0) {
		$this->db->select('monthly_payment.*, plan.status_id, plan.policy, plan.effective_date, plan.expiry_date');
		$this->db->from('monthly_payment');
		$this->db->join('plan', 'monthly_payment.plan_id=plan.plan_id', 'left');
		if ($plan) {
			$this->db->where('plan_id', $plan['plan_id']);
		}
		if (!empty($para['user_id'])) {
			$this->db->where('plan.user_id=', $para['user_id']);
		}
		if (!empty($para['date_start'])) {
			$this->db->where('pay_date>=', $para['date_start']);
		}
		if (!empty($para['date_end'])) {
			$this->db->where('pay_date<=', $para['date_end']);
		}
		if (!empty($para['paid'])) {
			$this->db->where('paid', $para['paid']);
		}
		$this->db->order_by('plan_id', "ASC");
		$this->db->order_by('monthly_payment_id', "ASC");
		if ($limit) {
			$this->db->limit($limit, $start);
		}
		return $this->db->get()->result_array();
	}

	public function plan_search_count($para, $plan) {
		if ($plan) {
			$this->db->where('plan_id', $plan['plan_id']);
		}
		if (!empty($para['date_start'])) {
			$this->db->where('pay_date>=', $para['date_start']);
		}
		if (!empty($para['date_end'])) {
			$this->db->where('pay_date<=', $para['date_end']);
		}
		if (!empty($para['paid'])) {
			$this->db->where('paid', $para['paid']);
		}
		return $this->db->get("monthly_payment")->num_rows();
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
			"paid_premium" => 0,
			"total_refund" => 0,
			"recurrent_times" => 0,
			"init_pay_date" => "N/A",
			"last_pay_date" => "N/A",
			"last_available_date" => "N/A",
		];
		if ($rts = $this->get_by_plan_id($plan_id)) {
			foreach ($rts as $rc) {
				$rt["premium"] += $rc["amount"];
				if ($rc["paid"] == 1) {
					$rt["total_paid"] += $rc["amount"];
					$rt["last_pay_date"] = $rc["pay_date"];
					$rt["total_refund"] += $rc["refund_amount"];
				}
				if ($rc["pay_type"]) {
					$rt["recurrent_times"]++;
					$rt["monthly_pay"] = $rc["amount"];
				} else {
					$rt["admin_fee"] += $rc["admin_fee"];
					$rt["init_pay"] = $rc["amount"];
					$rt["init_pay_date"] = $rc["pay_date"];
				}
			}
			$rt["paid_premium"] = $rt["total_paid"] - $rt["admin_fee"];
			$rt["premium"] -= $rt["admin_fee"];
			if ($rt["last_pay_date"] != "N/A") {
				$date = new DateTime($rt["last_pay_date"]);
				$date->modify('+3 month');
				$date->modify('-1 day');
				$rt["last_available_date"] = $date->format('Y-m-d');
			} else {
				$this->load->model('plan_model');
				if ($plan = $this->plan_model->get_plan_by_id($plan_id)) {
					$date = new DateTime($plan["effective_date"]);
					$date->modify('+2 month');
					$date->modify('-1 day');
					$rt["last_available_date"] = $date->format('Y-m-d');
				}
			}
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

	public function do_cancel($plan_id) {
		$this->void_unpaid_record($plan_id);
		return $this->db->where("plan_id", $plan_id)->where("paid", 1)->set("refund_amount", "amount", false)->update("monthly_payment");
	}

	public function do_terminate($plan_id, $refund_date, $plan) {
		$this->load->model('product_model');
		$this->void_unpaid_record($plan_id, -3);
		$refund_amount = 0;
		$charged_amount = 0;
		$lastRc = $this->db->where("plan_id", $plan_id)->order_by("monthly_payment_id", "DESC")->limit(1)->get("monthly_payment")->row_array();
		if (empty($lastRc)) {
			return $refund_amount;
		}
		$monthly_amount = $lastRc["amount"];
		$min_admin_fee = $this->admin_fee;	// First time paid

		$paidRc = $this->db->where("plan_id", $plan_id)->where("paid", 1)->order_by("monthly_payment_id", "ASC")->get("monthly_payment")->result_array();
		$expiry_date = $plan["effective_date"];
		$refund_tm = strtotime($refund_date);
		if ($paidRc) {
			foreach ($paidRc as $rc) {
				$charged_amount += $rc["amount"];
				$pay_tm = strtotime($rc["pay_date"]);
				if ($pay_tm <= $refund_tm) {
					$expiry_date = $rc["pay_date"];
				} else {
					$refund_amount += $rc["amount"];
				}
			}
		}
		$date = new DateTime($expiry_date);
		$date->modify('+2 month');
		$date->modify('-1 day');
		$expiry_date = $date->format('Y-m-d');
		$totaldays = $this->product_model->getDays($plan["expiry_date"], $refund_date);
		return ["refund_amount" => $refund_amount, "totaldays" => $totaldays, "charged_amount" => $charged_amount, "admin_fee" => $min_admin_fee];
	}

	public function do_refund($plan_id, $refund_date, $effective_date) {
		$this->load->model('product_model');
		$this->void_unpaid_record($plan_id);
		$refund_amount = 0;
		$charged_amount = 0;
		$lastRc = $this->db->where("plan_id", $plan_id)->order_by("monthly_payment_id", "DESC")->limit(1)->get("monthly_payment")->row_array();
		if (empty($lastRc)) {
			return ["refund_amount" => $refund_amount, "charged_amount" => $charged_amount, "admin_fee" => 0];
		}
		$monthly_amount = $lastRc["amount"];
		$min_admin_fee = $this->admin_fee;	// First time paid

		$paidRc = $this->db->where("plan_id", $plan_id)->where("paid", 1)->order_by("monthly_payment_id", "ASC")->get("monthly_payment")->result_array();
		if ($paidRc) {
			foreach ($paidRc as $rc) {
				$charged_amount += $rc["amount"];
				if ($rc["pay_type"] == 0) {
					// Init charge
					if ($refund_date < $effective_date) {
						// Should be cancel??? do as refund anyway
						if ($min_admin_fee < $rc["amount"]) {
							// Amount should be 3 monthly fee + $this->admin_fee
							$r_amount = $rc["amount"] - $min_admin_fee;
							$refund_amount += $r_amount;
							$this->db->where("monthly_payment_id", $rc["monthly_payment_id"])->set("refund_amount", $r_amount)->update("monthly_payment");
						} else {
							$this->db->where("monthly_payment_id", $rc["monthly_payment_id"])->set("refund_amount", $rc["amount"])->set("postdata", "refund admin fee error:".$min_admin_fee)->update("monthly_payment");
						}
					}
				} else {
					if ($refund_date < $rc["pay_date"]) {
						$refund_amount += $rc["amount"];
						$this->db->where("monthly_payment_id", $rc["monthly_payment_id"])->set("refund_amount", $rc["amount"])->update("monthly_payment");
					}
				}
			}
		}
		$totaldays = $this->product_model->getDays($effective_date, $refund_date);
		return ["refund_amount" => $refund_amount, "charged_amount" => $charged_amount, "admin_fee" => $min_admin_fee, 'totaldays' => $totaldays];
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

	public function get_monthly_status($plan) {
		$this->load->model('plan_model');
		$lastRc = $this->db->where("plan_id", $plan["plan_id"])->order_by("monthly_payment_id", "DESC")->limit(1)->get("monthly_payment")->row_array();
		if (empty($lastRc)) {
			return "";
		}
		if ($lastRc["paid"] == SELF::VOID) {
			if ($plan["status_id"] == Plan_model::CANCEL) {
				return "Canceled";
			} else if ($plan["status_id"] == Plan_model::REFUND) {
				return "Refunded";
			}
			return "Stopped";
		} else if ($lastRc["paid"] == SELF::TERMINATED) {
			return "Terminated";
		} else if ($lastRc["paid"] == SELF::PAYERROR) {
			return "Payment Error";
		}
		return "Active";
	}

	public function clear_old($plan_id) {
		$this->db->where("plan_id", $plan_id)->delete("monthly_payment");
	}
}
