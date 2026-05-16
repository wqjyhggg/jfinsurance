<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Bambora_model extends CI_Model {
	public $error;
	// public $profile_url="https://api.bambora.com/v1/profiles";
	// public $payment_url="https://api.bambora.com/v1/payments";

	public $profile_url="https://api.na.bambora.com/v1/profiles";
	public $payment_url="https://api.na.bambora.com/v1/payments";

	public function do_payment($monthly_payment_id) {
		$this->load->model('log_model');
    $this->load->model('plan_model');
    $this->load->model('user_model');
    $this->load->model('product_model');
		$this->load->model('payment_model');
    $this->load->model('monthly_payment_model');
		$this->load->model('plan_history_model');

		$user = $this->user_model->get_user_by_id(1);
		if ($pay = $this->monthly_payment_model->get_by_id($monthly_payment_id)) {
			if (($pay["paid"] != 0) && ($pay["paid"] != -2)) {
				$this->error = "Can't make payment of this record. paid value: ".$pay["paid"]."\r\n";
				return $this->error;
			}
			$plan_id = $pay["plan_id"];
			$plan = $this->plan_model->get_plan_by_id($plan_id);
			if (empty($plan)) {
				$this->error = "Unknonwn plan: ".json_encode($pay)."\r\n";
				return $this->error;
			}
			if ($plan["status_id"] != Plan_model::PAID) {
				$this->error = "Plan: is not in PAID status".json_encode($plan)."\r\n";
				// return $this->error;
			}
			$product = $this->product_model->get_product($plan["product_short"]);
			if (empty($product)) {
				$this->error = "Unknonwn product: ".json_encode($product)."\r\n";
				return $this->error;
			}

			$order_number = $plan_id."-".$pay["monthly_payment_id"];
			if ($pay["retry"]) {
				$order_number .= "-".$pay["retry"];
			}
			$postArr = [
				"order_number" => $order_number,
				"amount" => $pay["amount"],
				"payment_method" => "payment_profile",
				"custom" => [
					"ref1" => "recurrent",
					"ref2" => $plan_id,
					"ref3" => $pay["monthly_payment_id"],
				],
				"payment_profile" => [
					"complete" => true,
					"customer_code" => $pay["profile_id"],
					"card_id" => 1
				]
			];

			$postdata = json_encode($postArr);
			$headers = array(
				'Authorization: Passcode '.$product["payment_key"],
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
			$this->log_model->activity("bb-recur-c", $para, $user);
		
			$url = $this->payment_url;
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
			$activity_id = $this->log_model->activity("bb-recur-r", $para, $user);
			$rt = "";
			if (!empty($response)) {
				$rt = json_decode($response, true);
			}
		
			if (($responseCode === 200) && $rt && isset($rt["id"]) && isset($rt["approved"])) {
				/* 
					{
						"id": "10000215",
						"authorizing_merchant_id": 383613451,
						"approved": "1",
						"message_id": "1",
						"message": "Approved",
						"auth_code": "TEST",
						"created": "2025-12-17T13:08:21",
						"order_number": "101-2",
						"type": "P",
						"payment_method": "CC",
						"risk_score": 0.0,
						"amount": 50.43,
						"custom": {
								"ref1": "recurrent",
								"ref2": "101",
								"ref3": "2",
								"ref4": "",
								"ref5": ""
						},
						"card": {
								"card_type": "VI",
								"last_four": "1234",
								"card_bin": "403000",
								"address_match": 0,
								"postal_result": 0,
								"avs_result": "0",
								"cvd_result": "5",
								"avs": {
										"id": "N",
										"message": "Street address and Postal/ZIP do not match.",
										"processed": true
								}
						},
						"links": [
								{
										"rel": "void",
										"href": "https://api.na.bambora.com/v1/payments/10000215/void",
										"method": "POST"
								},
								{
										"rel": "return",
										"href": "https://api.na.bambora.com/v1/payments/10000215/returns",
										"method": "POST"
								}
						]
					}
				*/
		
				$pay_time = date("Y-m-d H:i:s");
				if (isset($rt["approved"]) && ($rt["approved"] == 1)) {
					$pay2 = $this->monthly_payment_model->get_by_id($pay["monthly_payment_id"]);
					if (empty($pay2)) {
						$para = array(
							'plan_id' => $plan_id,
							'user_id' => $activity_id,
							'payment_id' => 0,
							'message' => "Can not find record !!! monthly_payment_id: ".$pay["monthly_payment_id"]. ";",
							'systemlog' => ""
						);
						$this->log_model->activity("bb-recur-r-e", $para, $user);
					}
					if (!empty($pay2["paid"]) && ($pay2["paid"] != -2)) {
						// Done by bambora post back
						$para = array(
							'plan_id' => $plan_id,
							'user_id' => $activity_id,
							'payment_id' => 0,
							'message' => "Done by bambora post back",
							'systemlog' => ""
						);
						$this->log_model->activity("bb-recur-r", $para, $user);
						return null;
					}

					$premium = $pay["amount"];
					$admin_fee = $pay["admin_fee"];
					$premium -= $admin_fee;
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
					$dt['admin_fee'] = $admin_fee;
					$dt['plan_id'] = $plan_id;
					$dt['added'] = date("Y-m-d H:i:s");
					$dt['pay_date'] = date("Y-m-d");
					$dt['pay_mothed'] = "Credit Card";
					$dt['rate'] = 100;
					$dt['ispaid'] = 1;
					$dt['pay_type'] = 'premium';
					$dt['premium_payment_id'] = 0;
					$dt['note'] = "CC Success: Raw Data=> " . $response;
					$payment_id = $this->payment_model->add($dt, $user);
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

					$mpArr = [
						"trans_id" => $rt["id"],
						"paid" => 1,
						"pay_time" => $pay_time,
						"payment_id" => $payment_id,
						"postdata" => isset($postdata)?$postdata:"",
						"rawdata" => $response,
					];
					$this->monthly_payment_model->update($pay["monthly_payment_id"], $mpArr);

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
					return null;
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
					$dt['note'] = "CC Failure: Raw Data=> " . $response;
					$payment_id = $this->payment_model->add($dt, $user);
					$para = array(
						'plan_id' => $plan_id,
						'customer_id' => $plan['customer_id'],
						'payment_id' => $payment_id,
						'message' => $this->payment_model->logstr,
						'systemlog' => $this->payment_model->sqlstr
					);
					$this->log_model->activity('payment', $para, $user);

					$retry = $pay["retry"]++;
					$mpArr = [
						"trans_id" => $rt["id"],
						"paid" => -2,
						"retry" => $retry,
						"pay_time" => $pay_time,
						"payment_id" => $payment_id,
						"postdata" => isset($postdata)?$postdata:"",
						"rawdata" => $response,
					];
					if ($retry == 1) {
						$mpArr["retry_date"] = date('Y-m-d', strtotime('+2 days'));
					} else if ($retry == 2) {
						$mpArr["retry_date"] = date('Y-m-d', strtotime('+5 days'));
					}
					$this->monthly_payment_model->update($pay["monthly_payment_id"], $mpArr);

					// $planArr = [];	// Set plan to refunded
					// $planpara['payinfo'] = "Bambora recurrent charge fail. Monthly payment id: ".$pay["monthly_payment_id"];
					// $planpara['status_id'] = Plan_model::REFUND;
					// $this->plan_model->update($plan_id, $planpara, array(), $user);
					// $para = array(
					// 	'plan_id' => $plan_id,
					// 	'customer_id' => $plan['customer_id'],
					// 	'payment_id' => $payment_id,
					// 	'message' => $this->plan_model->logstr,
					// 	'systemlog' => $this->plan_model->sqlstr
					// );
					// $this->log_model->activity('plan', $para, $user);
					// if ($nid = $this->plan_history_model->add($plan_id, Plan_model::REFUND)) {
					// 	// Remove payment_id, it should be no payment
					// 	$this->plan_history_model->update($nid, array("note" => "recurrent charge fail."));
					// }

					$this->load->model('mymail_model');
					$message = $plan["policy"] . " Monthly Payment recurrent failed. monthly_payment_id: ".$pay["monthly_payment_id"];
					$this->mymail_model->send_mymail("wqjyhggg@gmail.com", 'JF Recur Error', $message, $attach=array(), $from='', 'text');
					$this->error = "Retry monthly payment Not approved (".$monthly_payment_id.")(".$responseCode.")(".$response.")";
					return $this->error;
				}
			} else {
				$this->load->model('mymail_model');
				$message  = $plan["policy"] . " Monthly Payment recurrent Return Error.\r\n";
				$message .= "monthly_payment_id: ".$pay["monthly_payment_id"].".\r\n";
				$message .= "responseCode: ".$responseCode.".\r\n";
				$message .= "response: ".$response.".\r\n";
				$this->mymail_model->send_mymail("wqjyhggg@gmail.com", 'JF recurrent Error', $message, $attach=array(), $from='', 'text');

				$pay2 = $this->monthly_payment_model->get_by_id($pay["monthly_payment_id"]);

				// Not done by bambora post back
				$retry = $pay["retry"]++;
				$mpArr = [
					"trans_id" => isset($rt["id"])?$rt["id"]:0,
					"paid" => -2,
					"retry" => $retry,
					"pay_time" => date("Y-m-d H:i:s"),
					"payment_id" => $responseCode?intval($responseCode):0,
					"postdata" => isset($postdata)?$postdata:"",
					"rawdata" => $response,
				];
				if ($retry == 1) {
					$mpArr["retry_date"] = date('Y-m-d', strtotime('+2 days'));
				} else if ($retry == 2) {
					$mpArr["retry_date"] = date('Y-m-d', strtotime('+5 days'));
				}
				$this->monthly_payment_model->update($pay["monthly_payment_id"], $mpArr);
				$this->error = "Retry monthly payment Failed (".$monthly_payment_id.")(".$responseCode.")(".$response.")";
				return $this->error;
			}
		}
		$this->error = "Unknown monthly_payment record (".$monthly_payment_id.")";
		return $this->error;
	}

	public function do_payoff($plan_id) {
		$this->load->model('log_model');
    $this->load->model('plan_model');
    $this->load->model('user_model');
    $this->load->model('product_model');
		$this->load->model('payment_model');
    $this->load->model('monthly_payment_model');
		$this->load->model('plan_history_model');

		$user = $this->user_model->get_user_by_id(1);
		$plan = $this->plan_model->get_plan_by_id($plan_id);
		if (empty($plan)) {
			$this->error = "No avaleable plan existed (plan id: ".$plan_id.")";
			return $this->error;
		}
		if ($payments = $this->monthly_payment_model->get_by_plan_id($plan_id)) {
			$total_pay = 0;
			$first_pay = "";
			foreach ($payments as $pay) {
				if (($pay["paid"] == 0) || ($pay["paid"] == -2)) {
					$total_pay += $pay["amount"];
					if (empty($first_pay)) {
						$first_pay = $pay;
					}
				}
			}
			if ($total_pay <= 0) {
				$this->error = "This plan is paid off already (plan id: ".$plan_id.")";
				return $this->error;
			}
			$product = $this->product_model->get_product($plan["product_short"]);
			if (empty($product)) {
				$this->error = "Unknonwn product: ".json_encode($product)."\r\n";
				return $this->error;
			}

			$order_number = $plan_id."-1-1-10";
			$postArr = [
				"order_number" => $order_number,
				"amount" => $total_pay,
				"payment_method" => "payment_profile",
				"custom" => [
					"ref1" => "payoff",
					"ref2" => $plan_id,
					"ref3" => $first_pay["monthly_payment_id"],
				],
				"payment_profile" => [
					"complete" => true,
					"customer_code" => $first_pay["profile_id"],
					"card_id" => 1
				]
			];

			$postdata = json_encode($postArr);
			$headers = array(
				'Authorization: Passcode '.$product["payment_key"],
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
			$this->log_model->activity("bb-payoff", $para, $user);
		
			$url = $this->payment_url;
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
			$activity_id = $this->log_model->activity("bb-payoff-r", $para, $user);
			$rt = "";
			if (!empty($response)) {
				$rt = json_decode($response, true);
			}
		
			if (($responseCode === 200) && $rt && isset($rt["id"]) && isset($rt["approved"])) {
				$pay_time = date("Y-m-d H:i:s");
				if (isset($rt["approved"]) && ($rt["approved"] == 1)) {
					$pay2 = $this->monthly_payment_model->get_by_id($first_pay["monthly_payment_id"]);
					if (empty($pay2)) {
						$para = array(
							'plan_id' => $plan_id,
							'user_id' => $activity_id,
							'payment_id' => 0,
							'message' => "Can not find record !!! monthly_payment_id: ".$first_pay["monthly_payment_id"]. ";",
							'systemlog' => ""
						);
						$this->log_model->activity("bb-recur-r-e", $para, $user);
					}
					if (!empty($pay2["paid"]) && ($pay2["paid"] != -2)) {
						// Done by bambora post back
						$para = array(
							'plan_id' => $plan_id,
							'user_id' => $activity_id,
							'payment_id' => 0,
							'message' => "Done by bambora post back",
							'systemlog' => ""
						);
						$this->log_model->activity("bb-recur-r", $para, $user);
						return null;
					}

					$premium = $total_pay;
					$admin_fee = 0;
					$commission_amount = 0;
					$commission_rate = $this->product_model->get_commission_rate($plan['product_short'], $plan['user_id']);
					if ((($plan['product_short'] == 'TOP') || ($plan['product_short'] == 'TOP')) && ($plan['totalyears'] > 60)) {
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
					$dt['admin_fee'] = $admin_fee;
					$dt['plan_id'] = $plan_id;
					$dt['added'] = date("Y-m-d H:i:s");
					$dt['pay_date'] = date("Y-m-d");
					$dt['pay_mothed'] = "Credit Card";
					$dt['rate'] = 100;
					$dt['ispaid'] = 1;
					$dt['pay_type'] = 'premium';
					$dt['premium_payment_id'] = 0;
					$dt['note'] = "CC Success: Raw Data=> " . $response;
					$payment_id = $this->payment_model->add($dt, $user);
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

					$mpArr = [
						"trans_id" => $rt["id"],
						"paid" => 1,
						"amount" => $total_pay,
						"pay_time" => $pay_time,
						"payment_id" => $payment_id,
						"postdata" => isset($postdata)?$postdata:"",
						"rawdata" => $response,
					];
					$this->monthly_payment_model->update($pay2["monthly_payment_id"], $mpArr);
					$this->monthly_payment_model->void_unpaid_record($plan_id);

					$dt['amount'] = $commission_amount;
					$dt['admin_fee'] = 0;
					$dt['rate'] = $commission_rate;
					$dt['ispaid'] = 0;
					$dt['pay_type'] = 'commission';
					$dt['premium_payment_id'] = $payment_id;
					$commission_payment_id = $this->payment_model->add($dt, $user);
					$para = array(
						'plan_id' => $plan_id,
						'customer_id' => $plan['customer_id'],
						'payment_id' => $commission_payment_id,
						'message' => $this->payment_model->logstr,
						'systemlog' => $this->payment_model->sqlstr
					);
					$this->log_model->activity('commission', $para, $user);
					return null;
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
					$dt['note'] = "CC Failure: Raw Data=> " . $response;
					$payment_id = $this->payment_model->add($dt, $user);
					$para = array(
						'plan_id' => $plan_id,
						'customer_id' => $plan['customer_id'],
						'payment_id' => $payment_id,
						'message' => $this->payment_model->logstr,
						'systemlog' => $this->payment_model->sqlstr
					);
					$this->log_model->activity('payment', $para, $user);
					$this->error = "Try monthly full payment Not approved (".$monthly_payment_id.")(".$responseCode.")(".$response.")";
					return $this->error;
				}
			} else {
				$this->error = "Try monthly full payment Failed (".$responseCode.")(".$response.")";
				return $this->error;
			}
		}
		$this->error = "Unknown monthly_payment records (plan id: ".$plan_id.")";
		return $this->error;
	}
}
