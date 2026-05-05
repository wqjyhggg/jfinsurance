<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bambora extends CI_Controller {
	// public $profile_url="https://api.bambora.com/v1/profiles";
	// public $payment_url="https://api.bambora.com/v1/payments";

	public $profile_url="https://api.na.bambora.com/v1/profiles";
	public $payment_url="https://api.na.bambora.com/v1/payments";

  // function _remap($param) {
  //   $this->index($param);
  // }

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
		$requestMethod = $_SERVER['REQUEST_METHOD'];
    $getData = $_GET ? json_encode($_GET) : 'No GET data';
    $postData = $_POST ? json_encode($_POST) : 'No POST data';
		$rawInput = file_get_contents('php://input') ?: 'No raw input';
		$headers = json_encode(getallheaders());
		$logEntry = date('Y-m-d H:i:s') . PHP_EOL .
				" - Method: $requestMethod" . PHP_EOL .
				" - Headers: $headers" . PHP_EOL .
				" - GET: $getData" . PHP_EOL .
				" - POST: $postData" . PHP_EOL .
				" - RAW: $rawInput" . PHP_EOL . PHP_EOL;
		file_put_contents("paymentcall.txt", $logEntry, FILE_APPEND);

		$rawInput = file_get_contents('php://input');

    $this->load->database();
    $this->load->helper('url');
		$this->load->model('log_model');
    $this->load->model('plan_model');
    $this->load->model('user_model');
    $this->load->model('product_model');
		$this->load->model('payment_model');
    $this->load->model('monthly_payment_model');
		$this->load->model('plan_history_model');
		$this->load->model('bambora_model');

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
			'payment_id' => isset($post['ref3'])?$post['ref3']:0,
			'message' => json_encode($post)
		);
		$activity_id = $this->log_model->activity("bb-p", $para, $user);

		$errormsg = "";
		if (empty($post["hashValue"])) {
			$errormsg = "Unknown hashValue";
			if ($activity_id) {
				$this->log_model->update($activity_id, ["systemlog" => $errormsg]);
			}
			die($errormsg);
		}

		if (empty($post["ref1"]) || (($post["ref1"] != "monthly") && ($post["ref1"] != "recurrent") && ($post["ref1"] != "payoff"))) {
			$errormsg = "Unknown ref1";
			if ($activity_id) {
				$this->log_model->update($activity_id, ["systemlog" => $errormsg]);
			}
			// die($errormsg);
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
		} else if ($monthly_payment["paid"] == 1) {
			$errormsg = "Already Processed";
			if ($activity_id) {
				$this->log_model->update($activity_id, ["systemlog" => $errormsg, "payment_id"=>$monthly_payment["payment_id"]]);
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
			$errormsg = "Unknown user";
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
			if ($post["ref1"] == "payoff") {
				$mpArr["amount"] = $post["trnAmount"];
				$mpArr["pay_date"] = date('Y-m-d');
				$monthly_payment["amount"] = $mpArr["amount"];
			}
		} else {
			$mpArr["paid"] = -2;
			$retry = $monthly_payment["retry"] + 1;
			if ($retry == 1) {
				$this->send_charge_fail_email($plan);
				$mpArr["retry_date"] = date('Y-m-d', strtotime('+2 days'));
			} else if ($retry == 2) {
				$mpArr["retry_date"] = date('Y-m-d', strtotime('+5 days'));
			}
			$mpArr["retry"] = $retry;
		}
		$this->monthly_payment_model->update($monthly_payment_id, $mpArr);

		$premium = $monthly_payment["amount"];
		$admin_fee = $monthly_payment["admin_fee"];
		$commission_premium = $premium - $admin_fee;
		if ($post["trnApproved"] == "1") {
			$commission_amount = 0;
			$commission_rate = $this->product_model->get_commission_rate($plan['product_short'], $plan['user_id']);
			if ((($plan['product_short'] == 'TOP') || ($plan['product_short'] == 'TOPN')) && ($plan['totalyears'] > 60)) {
				if ($commission_rate > 15) {
					$commission_rate -= 15;
				} else {
					$commission_rate = 0;
				}
			}
			if ($plan['product_short'] == 'TOP') {
				$commission_amount = ($commission_premium - ($plan['tax'] * $commission_premium / $plan['premium'])) * $commission_rate / 100.0;
			} else {
				$commission_amount = $commission_premium * $commission_rate / 100.0;
			}
			$dt = [];
			$dt['amount'] = $premium;
			$dt['admin_fee'] = $admin_fee;
			$dt['plan_id'] = $plan_id;
			$dt['added'] = date("Y-m-d H:i:s");
			$dt['pay_date'] = date("Y-m-d");
			$dt['pay_mothed'] = "Credit Card";
			$dt['rate'] = 100;
			$dt['ispaid'] = 1;
			$dt['pay_type'] = 'premium';
			$dt['premium_payment_id'] = 0;
			$dt['note'] = "CC Success: Raw Data=> " . json_encode($post);
			$payment_id = $this->payment_model->add($dt, $user);
			$this->monthly_payment_model->update($monthly_payment_id, ["payment_id" => $payment_id]);
			if ($post["ref1"] == "payoff") {
				$this->monthly_payment_model->void_unpaid_record($plan_id);
			}
			if ($activity_id) {
				$this->log_model->update($activity_id, ["payment_id" => $payment_id]);
			}
			$para = array(
				'plan_id' => $plan_id,
				'customer_id' => $plan['customer_id'],
				'payment_id' => $payment_id,
				'message' => $this->payment_model->logstr,
				'systemlog' => $this->payment_model->sqlstr
			);
			$this->log_model->activity('payment', $para, $user);

			$dt['amount'] = $commission_amount;
			$dt['admin_fee'] = 0;
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
			$commission_payment_id = $this->payment_model->add($dt, $user);
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
				$planpara['payinfo'] = "First pay by Bambora: ".$monthly_payment_id;
				$planpara['status_id'] = Plan_model::PAID;
				$planpara['monthlypay'] = 1;
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

				// Create Profile
				$postdata = '{"language":"eng","comments":"First Payment","create_from_id":"'.$post["trnId"].'"}';
		    $headers = array(
					'Authorization: Passcode '.$product["profile_key"],
					'Content-Type: application/json',
					'Content-Length: ' . strlen($postdata)
				);
				$para = array(
					'plan_id' => $plan_id,
					'user_id' => $plan["user_id"],
					'payment_id' => 0,
					'message' => $postdata,
					'systemlog' => json_encode($headers)
				);
				$this->log_model->activity("bb-rcur", $para, $user);
		
				$url = $this->profile_url;
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
				curl_setopt($ch, CURLOPT_POST, true);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				$response = curl_exec($ch);
				$responseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
				curl_close($ch);

				$para = array(
					'plan_id' => $plan_id,
					'user_id' => $plan["user_id"],
					'payment_id' => $responseCode,
					'message' => $response,
					'systemlog' => $url
				);
				$this->log_model->activity("bb-profile", $para, $user);
		
				if ($responseCode === 200) {
					$rt = json_decode($response, true);
					/* 
					{	"code": 1,
						"message": "Operation_successful",
						"customer_code": "B154823851c14212a2baCb1dAD1d8401",
						"limited_use_card_token": "BABD7B9F-D31B-4203-BC1A-EECDDC5D12EB" } 
					*/
					if (isset($rt["code"]) && ($rt["code"] == 1) && isset($rt["customer_code"])) {
						$this->monthly_payment_model->set_profile_id($plan_id, $rt["customer_code"]);
						if ($activity_id) {
							$this->log_model->update($activity_id, ["systemlog" => "OK"]);
						}
						die("OK");
					} else {
						$this->load->model('mymail_model');
						$message = $plan["policy"] . " Monthly Payment Profile creation failed.";
						$this->mymail_model->send_mymail("wqjyhggg@gmail.com", 'JF Profile Error', $message, $attach=array(), $from='', 'text');
						$message = "Profile creation unknown return";
						if ($activity_id) {
							$this->log_model->update($activity_id, ["systemlog" => $message]);
						}
						die($message);
					}
				} else {
					$this->load->model('mymail_model');
					$message  = $plan["policy"] . " Monthly Payment Profile Return Error.\r\n";
					$message .= "monthly_payment_id: ".$monthly_payment_id.".\r\n";
					$message .= "responseCode: ".$responseCode.".\r\n";
					$message .= "response: ".$response.".\r\n";
					$this->mymail_model->send_mymail("wqjyhggg@gmail.com", 'JF Profile Error', $message, $attach=array(), $from='', 'text');
					$message = "Profile creation return error";
					if ($activity_id) {
						$this->log_model->update($activity_id, ["systemlog" => $message]);
					}
					die($message);
				}
			} else {
				$planpara['payinfo'] = $post["ref1"]. " pay by Bambora: ".$monthly_payment_id;
				$this->plan_model->update($plan_id, $planpara, array(), $user);
				$para = array(
					'plan_id' => $plan_id,
					'customer_id' => $plan['customer_id'],
					'payment_id' => $payment_id,
					'message' => $this->plan_model->logstr,
					'systemlog' => $this->plan_model->sqlstr
				);
				$this->log_model->activity('plan', $para, $user);
				$message = $post["ref1"]. " Payment OK";
				if ($activity_id) {
					$this->log_model->update($activity_id, ["systemlog" => $message]);
				}
				die("OK");
			}
		} else {
			$dt = [];
			$dt['plan_id'] = $plan_id;
			$dt['pay_mothed'] = "Credit Card";
			$dt['added'] = date("Y-m-d H:i:s");
			$dt['pay_date'] = date("Y-m-d");
			$dt['amount'] = 0;
			$dt['rate'] = 100;
			$dt['ispaid'] = 0;
			$dt['pay_type'] = 'premium';
			$dt['premium_payment_id'] = 0;
			$dt['note'] = "CC Failure: Raw Data=> " . json_encode($post);
			$payment_id = $this->payment_model->add($dt, $user);
			$this->monthly_payment_model->update($monthly_payment_id, ["payment_id" => $payment_id]);
			$para = array(
				'plan_id' => $plan_id,
				'customer_id' => $plan['customer_id'],
				'payment_id' => $payment_id,
				'message' => $this->payment_model->logstr,
				'systemlog' => $this->payment_model->sqlstr
			);
			$this->log_model->activity('payment', $para, $user);
		}
		die("processed OK");
	}

	public function send_charge_fail_email($plan) {
		$this->load->model('mymail_model');
		$this->load->model('user_model');
		$this->load->model('customer_model');
		$this->load->model("verify_model");

		if ($this->verify_model->isEmail($plan["contact_email"]) && ($customer = $this->customer_model->get_customer_by_id($plan["customer_id"]))) {
			$subject = "Urgent Action Required: Recurring Payment Failure for Your Insurance Policy";
			$body  = "Dear ".$customer["firstname"]." ".$customer["lastname"].",\r\n\r\n";
			$body .= "We are reaching out regarding your recent insurance policy ".$plan["policy"]." purchased on ".$plan["apply_date"].".\r\n";
			$body .= "Our records show that the payment for this policy was unsuccessful. Please be aware that your policy remains active; however, prompt payment is necessary to maintain coverage.\r\n";
			$body .= "If you believe this payment failure is an error or if you have any questions, we encourage you to contact us immediately to resolve the issue.\r\n\r\n";
			$body .= "Important Notice:\r\n";
			$body .= "Failure to address this payment within 30 calendar days will result in suspension of your policy.\r\n";
			$body .= "To avoid any interruption in your coverage, please respond to this message or call us directly at (905) 707-1512. You may also email us at info@jfgroup.ca.\r\n\r\n";
			$body .= "Thank you for your prompt attention to this matter.\r\n\r\n";
			$body .= "Sincerely,\r\n\r\n";
			$body .= "JF Insurance";
			$this->mymail_model->send_mymail($plan["contact_email"], $subject, $body, $attach=array(), $from='', 'text');
		}
		if (($agent = $this->user_model->get_user_by_id($plan["user_id"])) && $this->verify_model->isEmail($agent["email"])) {
			sleep(10);
			$subject = "Urgent Notification: Payment Failure for Client's Insurance Policy";
			$body  = "Dear ".$agent["firstname"]." ".$agent["lastname"].",\r\n\r\n";
			$body .= "We are notifying you regarding a payment failure for your client's insurance policy (Policy Name: ".$plan["policy"]." purchased on ".$plan["apply_date"].".\r\n";
			$body .= "Our records indicate that the payment for this policy was unsuccessful. While the policy remains active currently, prompt payment from the client is essential to maintain coverage.\r\n";
			$body .= "Please reach out to your client promptly to address this issue. If you require any assistance or have questions, do not hesitate to contact us.\r\n\r\n";
			$body .= "Important Notice:\r\n";
			$body .= "Failure to resolve the payment within 30 calendar days will lead to suspension of the policy.\r\n";
			$body .= "To prevent any disruption in coverage, please encourage your client to respond to this message or contact us directly at (905) 707-1512. You may also direct them to email us at info@jfgroup.ca.\r\n\r\n";
			$body .= "Thank you for your immediate attention to this matter.\r\n\r\n";
			$body .= "Sincerely,\r\n\r\n";
			$body .= "JF Insurance";
			$this->mymail_model->send_mymail($agent["email"], $subject, $body, $attach=array(), $from='', 'text');
		}
	}

	public function recurren() {
		if ((php_sapi_name() !== 'cli')) {
			show_error("ERROR", 404);
		}

    $this->load->database();
    $this->load->helper('url');
		$this->load->model('log_model');
    $this->load->model('plan_model');
    $this->load->model('user_model');
    $this->load->model('product_model');
		$this->load->model('payment_model');
    $this->load->model('monthly_payment_model');
		$this->load->model('plan_history_model');
		$this->load->model('bambora_model');

		$user = $this->user_model->get_user_by_id(1);		// For log only

		$dt = date("Y-m-d");
		echo "Do Recurring start at ".date("Y-m-d H:i:s")."\r\n";
		$monthly_payments = $this->monthly_payment_model->today_payments($dt);
		if ($monthly_payments) {
			foreach ($monthly_payments as $pay) {
				if ($plan = $this->plan_model->get_by_id($pay["plan_id"])) {
					if ($plan["status_id"] != Plan_model::PAID) {
						continue;
					}
				} else {
					echo "Unknown plan_id:".$pay["plan_id"]."\r\n";
					continue;
				}
				if ($message = $this->bambora_model->do_payment($pay["monthly_payment_id"])) {
					echo $message;
				}
			}
		}
		echo "Done for All at ".date("Y-m-d H:i:s")."\r\n";
	}
}
