<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bambora extends CI_Controller {
  function _remap($param) {
    $this->index($param);
  }

	private function get_hashValue($post, $hashKey) {
		$strArr = [];
		foreach ($post as $key => $val) {
			if ($key == "hashValue") break;
			$valstr = urlencode($val);
			if (!is_numeric($val)) {
				$valstr = str_replace(".", "%2E", $valstr);
			}
			$strArr[] = $key."=".$valstr;
		}
    $rawString = join("&", $strArr);
    $hashString = $rawString . $hashKey;
    return md5($hashString);
  }

	/**
	 * Index Page for this controller.
	 * ALTER TABLE `activity` ADD PRIMARY KEY (`activity_id`), ADD KEY `plan_id` (`plan_id`), ADD KEY `atype` (`activity_id`);
	 * `activity_id` int NOT NULL,
	 * `atype` varchar(16) NOT NULL,
	 * `tm` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	 * `user_id` int NOT NULL,
	 * `plan_id` int NOT NULL,
	 * `customer_id` int NOT NULL,
	 * `payment_id` int NOT NULL,
	 * `message` text NOT NULL COMMENT 'human readable message',
	 * `systemlog` text NOT NULL COMMENT 'system active usually is sql'
	 */
	public function index() {
		$rawInput = file_get_contents('php://input');

    $this->load->database();
    $this->load->helper('url');
		$this->load->model('log_model');
    $this->load->model('plan_model');
    $this->load->model('user_model');
    $this->load->model('product_model');
		$this->load->model('payment_model');
    $this->load->model('monthly_payment_model');

		$post = $this->input->post();
		/* $post = [
				"trnApproved"=>"1",
				"trnId"=>"10000211",
				"messageId"=>"1",
				"messageText"=>"Approved",
				"authCode"=>"TEST",
				"responseType"=>"T",
				"trnAmount"=>"347.48",
				"trnDate"=>"12\/12\/2025 4:53:40 PM",
				"trnOrderNumber"=>"6309-1",
				"trnLanguage"=>"eng",
				"trnCustomerName"=>"John Smith",
				"trnEmailAddress"=>"abc@def.com",
				"trnPhoneNumber"=>"4161234567",
				"avsProcessed"=>"1",
				"avsId"=>"N",
				"avsResult"=>"0",
				"avsAddrMatch"=>"0",
				"avsPostalMatch"=>"0",
				"avsMessage"=>"Street address and Postal\/ZIP do not match.",
				"cvdId"=>"1",
				"cardType"=>"VI",
				"trnType"=>"P",
				"paymentMethod"=>"CC",
				"ref1"=>"monthly",
				"ref2"=>"6309",
				"ref3"=>"1",
				"ref4"=>"",
				"ref5"=>"",
				"hashValue"=>"3dda10505201730b97d201db5967031b"]

				plan_id int NOT NULL DEFAULT 0,
				payment_id int NOT NULL DEFAULT 0,
				profile_id varchar(64) NOT NULL DEFAULT '' COMMENT 'for recurrent payment profile id',
				trans_id varchar(64) NOT NULL DEFAULT '' COMMENT 'from processor transaction ID',
				pay_type tinyint NOT NULL DEFAULT 1 COMMENT '0: for first pay. 1: for recurrent',
				paid tinyint NOT NULL DEFAULT 0 COMMENT '0: no, 1: yes, -1: void', -2: pay error',
				retry tinyint NOT NULL DEFAULT 0 COMMENT 'tried times',
				amount decimal(10,2) NOT NULL DEFAULT 0,
				pay_date date NULL COMMENT 'pay date',
				pay_time datetime NULL COMMENT 'paid real time',
				postdata text,
				rawdata text,
		*/
		$user = $this->user_model->get_user_by_id(1);		// For log only
		$para = array(
			'plan_id' => isset($post['ref2'])?$post['ref2']:0,
			'user_id' => isset($post['ref3'])?$post['ref3']:0,
			'payment_id' => isset($post['ref4'])?$post['ref4']:0,
			'message' => json_encode($post)
		);
		$activity_id = $this->log_model->activity("bambora", $para, $user);

		$errormsg = "";
		if (empty($post["hashValue"])) {
			$errormsg = "Unknown hashValue";
			if ($activity_id) {
				$this->log_model->update($activity_id, ["systemlog" => $errormsg]);
			}
			die($errormsg);
		}

		if (empty($post["ref1"]) || ($post["ref1"] != "monthly")) {
			$errormsg = "Unknown ref1";
			if ($activity_id) {
				$this->log_model->update($activity_id, ["systemlog" => $errormsg]);
			}
			die($errormsg);
		}

		if (empty($post["ref3"])) {
			$errormsg = "Unknown ref3";
			if ($activity_id) {
				$this->log_model->update($activity_id, ["systemlog" => $errormsg]);
			}
			die($errormsg);
		}
		$monthly_payment_id = $post["ref3"];
    $monthly_payment = $this->monthly_payment_model->get_by_id($monthly_payment_id);
		if (empty($monthly_payment)) {
			$errormsg = "Unknown monthly payment";
			if ($activity_id) {
				$this->log_model->update($activity_id, ["systemlog" => $errormsg]);
			}
			die($errormsg);
		}

		if (empty($post["ref2"])) {
			$errormsg = "Unknown ref2";
			if ($activity_id) {
				$this->log_model->update($activity_id, ["systemlog" => $errormsg]);
			}
			die($errormsg);
		}
		$plan_id = $post["ref2"];
		$plan = $this->plan_model->get_plan_by_id($plan_id);
		if (empty($plan)) {
			$errormsg = "Unknown Plan";
			if ($activity_id) {
				$this->log_model->update($activity_id, ["systemlog" => $errormsg]);
			}
			die($errormsg);
		}
		$product = $this->product_model->get_product($plan["product_short"]);
		if (empty($product)) {
			$errormsg = "Unknown product";
			if ($activity_id) {
				$this->log_model->update($activity_id, ["systemlog" => $errormsg]);
			}
			die($errormsg);
		}
		$user = $this->user_model->get_user_by_id($plan["user_id"]);
		if (empty($product)) {
			$errormsg = "Unknown product";
			if ($activity_id) {
				$this->log_model->update($activity_id, ["systemlog" => $errormsg]);
			}
			die($errormsg);
		}

		$hashValue = $post["hashValue"];
		$myhashValue = $this->get_hashValue($post, $product["hash_key"]);
		if ($hashValue != $myhashValue) {
			$errormsg = "Verify Error: ".$myhashValue;
			if ($activity_id) {
				$this->log_model->update($activity_id, ["systemlog" => $errormsg]);
			}
			die($errormsg);
		}

		if (!isset($post["trnApproved"])) {
			$errormsg = "Unknown trnApproved";
			if ($activity_id) {
				$this->log_model->update($activity_id, ["systemlog" => $errormsg]);
			}
			die($errormsg);
		}
		if (!isset($post["trnId"])) {
			$errormsg = "Unknown trnId";
			if ($activity_id) {
				$this->log_model->update($activity_id, ["systemlog" => $errormsg]);
			}
			die($errormsg);
		}
		$pay_time = date("Y-m-d H:i:s");
		if (isset($post["trnDate"])) {
			$pay_time = date("Y-m-d H:i:s", strtotime($post["trnDate"]));
		}

		$mpArr = [
			"trans_id" => $post["trnId"],
			"pay_time" => $pay_time,
			"rawdata" => json_encode($post),
		];
		if ($post["trnApproved"] == "1") {
			$mpArr["paid"] = 1;
		} else {
			$mpArr["paid"] = -2;
		}
		$this->monthly_payment_model->update($monthly_payment_id, $mpArr);

		$premium = $monthly_payment["amount"];
		if ($post["trnApproved"] == "1") {
			$commission_amount = 0;
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

			$dt = [];
			$dt['amount'] = $premium;
			$dt['rate'] = 100;
			$dt['ispaid'] = 1;
			$dt['pay_type'] = 'premium';
			$dt['premium_payment_id'] = 0;
			$dt['note'] = "Success: Raw Data=> " . json_encode($post);
			$payment_id = $this->payment_model->add($dt);
			$para = array(
				'plan_id' => $plan_id,
				'customer_id' => $plan['customer_id'],
				'payment_id' => $payment_id,
				'message' => $this->payment_model->logstr,
				'systemlog' => $this->payment_model->sqlstr
			);
			$this->log_model->activity('payment', $para, $user);

			$dt['amount'] = $commission_amount;
			$dt['rate'] = $commission_rate;
			$dt['ispaid'] = 0;
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
							$this->log_model->activity('commission', $para, $user);
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
							$this->log_model->activity('commission', $para, $user);
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
			$this->log_model->activity('commission', $para, $user);

			$planpara = array('payment_id' => $payment_id, 'commission_payment_id' => $commission_payment_id);
			if ($monthly_payment["pay_type"] == 0) {
				// First pay
				$planpara['payinfo'] = "First pay by Bambora";
				$planpara['status_id'] = Plan_model::PAID;
				$planpara['policy'] = $this->plan_model->get_policy_number($plan_id, 2);
				$this->plan_model->update($plan_id, $planpara, array(), $user);
				$para = array(
					'plan_id' => $plan_id,
					'customer_id' => $plan['customer_id'],
					'payment_id' => $payment_id,
					'message' => $this->plan_model->logstr,
					'systemlog' => $this->plan_model->sqlstr
				);
				$this->log_model->activity('plan', $para, $user);
				if ($nid = $this->plan_history_model->add($plan_id, Plan_model::PAID)) {
					// Remove payment_id, it should be no payment
					$this->plan_history_model->update($nid, array("note" => "plan condition change only"));
				}
			} else {
				$planpara['payinfo'] = "recurrent pay by Bambora: ".$monthly_payment_id;
				$this->plan_model->update($plan_id, $planpara, array(), $user);
				$para = array(
					'plan_id' => $plan_id,
					'customer_id' => $plan['customer_id'],
					'payment_id' => $payment_id,
					'message' => $this->plan_model->logstr,
					'systemlog' => $this->plan_model->sqlstr
				);
				$this->log_model->activity('plan', $para, $user);
			}
		} else {
			$dt = [];
			$dt['amount'] = 0;
			$dt['rate'] = 100;
			$dt['ispaid'] = 0;
			$dt['pay_type'] = 'premium';
			$dt['premium_payment_id'] = 0;
			$dt['note'] = "Success: Raw Data=> " . json_encode($post);
			$payment_id = $this->payment_model->add($dt);
			$para = array(
				'plan_id' => $plan_id,
				'customer_id' => $plan['customer_id'],
				'payment_id' => $payment_id,
				'message' => $this->payment_model->logstr,
				'systemlog' => $this->payment_model->sqlstr
			);
			$this->log_model->activity('payment', $para);
		}
		die("processed OK");
	}
}
