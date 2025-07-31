<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Plan extends CI_Controller
{
  public $error;
  public $data;
	public $topnpackagename = array(
		'single_medical_plan' => "Single medical plan",
		'optional_plan' => "Optional plan",
	);
	public $toppackagename = array(
		'all_inclusive' => "All Inclusive plan",
		'single_medical_plan' => "Single medical plan",
		'annual_plan' => "Annual plan",
		'optional_plan' => "Optional plan",
	);
	public $french_plan = array("JES", "JESP", "JFS", "TOP", "JFPL", "JFGD", "JFOS", "JFVTC", "JFR");

  public function index()
  {
    $this->error = "";
    $this->load->model("app_model");
    $this->load->model("user_model");
    $user = $this->app_model->check_token($this->input->post("token"));

    if (empty($user)) {
      if (empty($this->error)) {
        $this->error = "Session Expired";
      }
      return $this->app_model->return_error($this->error);
    }
    if (($user["user_group_id"] < 100) && ($bid = $this->input->post("bid"))) {
      $user = $this->user_model->get_user_by_id($bid);
      if (empty($user)) {
        return $this->app_model->return_error("Unknown agent");
      }
    }

    $data = array();
    $post = $this->input->post();
    if (($user["user_group_id"] > 100) && ($user["user_group_id"] != 104)) {
      $post["user_id"] = $user["user_id"];
    }
    $this->load->model("plan_model");
    $limit = intval($this->input->post("limit"));
    $start = intval($this->input->post("start"));
    if ($limit <= 0) $limit = 5;
    if ($start < 0) $start = 0;
    $sorder = $this->input->post("sorder");
    $desc = 1;
    if ($sorder) {
      $desc = $this->input->post("desc");
    }
    if ($plans = $this->plan_model->plan_activities($user, $post, $limit, $start, $sorder, $desc)) {
      $data["plans"] = $plans;
      $data["totals"] = $this->plan_model->plan_activitie_totals($user, $post);
      $this->app_model->return_ok($data);
    }
    return $this->app_model->return_ok(array("plans"=>array(), "totals"=>0));
  }

  function get_plan_pay() {
    $this->error = "";
    $this->load->model("app_model");
    $this->load->model("user_model");
    $this->load->helper('url');

    $user = $this->app_model->check_token($this->input->post("token"));

    if (empty($user)) {
      if (empty($this->error)) {
        $this->error = "Session Expired";
      }
      return $this->app_model->return_error($this->error);
    }
    if (($user["user_group_id"] < 100) && ($bid = $this->input->post("bid"))) {
      $user = $this->user_model->get_user_by_id($bid);
      if (empty($user)) {
        return $this->app_model->return_error("Unknown agent");
      }
    }
    $this->load->model('payment_model');
    $this->load->model('plan_model');

    $plan_id = $this->input->post("plan_id");
    $plan = $this->plan_model->get_plan_by_id($plan_id);
    if (empty($plan)) {
      return $this->app_model->return_error("Unknown Policy");
    }

    $payment_total = $plan['premium'] - $this->payment_model->get_total_paid($plan['plan_id'], 'premium', $plan['apply_date']);
    $payurl = "";
    if ($payment_total && (($plan["status_id"] == Plan_model::QUOTE) || ($plan["status_id"] == Plan_model::CHANGED))) {
      $payurl = base_url().'plan/detail/' . $plan_id . '/' . $this->plan_model->get_plan_key($plan_id);
    }
    $this->app_model->return_ok(["pay_amount"=>$payment_total, "payurl"=>$payurl]);
  }

  function get_ali_url() {
    $this->error = "";
    $this->load->model("app_model");
    $this->load->model("user_model");
    $user = $this->app_model->check_token($this->input->post("token"));

    if (empty($user)) {
      if (empty($this->error)) {
        $this->error = "Session Expired";
      }
      return $this->app_model->return_error($this->error);
    }
    $this->load->model('payment_model');
    $this->load->model('plan_model');
    $this->load->model('snappay_model');

    $plan_id = $this->input->post("plan_id");
    $plan = $this->plan_model->get_plan_by_id($plan_id);
    if (empty($plan)) {
      return $this->app_model->return_error("Unknown Policy");
    }

    $payment_total = $plan['premium'] - $this->payment_model->get_total_paid($plan['plan_id'], 'premium', $plan['apply_date']);
    if ($payment_total <= 0) {
      return $this->app_model->return_error("Policy Paid already");
    }
    $pay_url = $this->snappay_model->get_pay_url($plan, $payment_total, "", $this->input->post("returnurl"));
    if (empty($pay_url)) {
      $err = "Unknown Payment URL";
      if ($this->snappay_model->last_err) {
        $err = $this->snappay_model->last_err;
      }
      return $this->app_model->return_error($err);
    }
    $this->app_model->return_ok(["pay_url"=>$pay_url, "pay_amount"=>$payment_total]);
  }

  function set_plan_pay() {
    $this->error = "";
    $this->load->model("app_model");
    $this->load->model("user_model");
    $user = $this->app_model->check_token($this->input->post("token"));

    if (empty($user)) {
      if (empty($this->error)) {
        $this->error = "Session Expired";
      }
      return $this->app_model->return_error($this->error);
    }
    $this->load->model('payment_model');
    $this->load->model('plan_model');
    $this->load->model('plan_history_model');
		$this->load->model('product_model');
		$this->load->model('log_model');

    $plan_id = $this->input->post("plan_id");
    $premium = $this->input->post("pay_amount");
    $pay_type = $this->input->post("pay_type"); // Cash, Cheque, Ali, Credit Card
    $payinfo = '';
    if (($pay_type != "Cash") && ($pay_type != "Cheque") && ($pay_type != "Ali") && ($pay_type != "Credit Card")) {
      return $this->app_model->return_error("Unknown pay type");
    }
    $plan = $this->plan_model->get_plan_by_id($plan_id);
    if (empty($plan)) {
      return $this->app_model->return_error("Unknown Policy");
    }
		$premium = (float)$premium;
    
    if ($pay_type == 'Ali') {
      $payinfo = "Pay Ali: " . 'Premium: $' . $premium . "; ";
    } else if ($pay_type == 'Cheque') {
      $payinfo  = 'Invoice Number: ' . $this->input->post('invoice_num') . "; ";
      $payinfo .= 'Bank Name: ' . $this->input->post('bank_name') . "; ";
      $payinfo .= 'Payor Name: ' . $this->input->post('payor_name') . "; ";
      $payinfo .= 'Cheque#: ' . $this->input->post('cheque_number') . "; ";
      $payinfo .= 'Premium: $' . $this->input->post('premium') . "; ";
    } else if ($pay_type == 'Credit Card') {
      $card_number = $this->input->post('card_number');
      $card_name = $this->input->post('card_name');
      $expiry_month = $this->input->post('expiry_month');
      $expiry_year = $this->input->post('expiry_year');
      $card_cvv = $this->input->post('card_cvv');

      $card_number = preg_replace('#[^0-9]#', '', $card_number);
			$card_cvv = preg_replace('#[^0-9]#', '', $card_cvv);
			$card_number_len = strlen($card_number);
			$card_cvv_len = strlen($card_cvv);
    }

		$product = $this->product_model->get_product($plan['product_short']);
		$dt = array();
		$dt['plan_id'] = $plan_id;
		$dt['amount'] = $premium;
		$dt['pay_type'] = 'premium';
		$dt['currency'] = $product['currency'];
		$dt['pay_mothed'] = $pay_type;
		$dt['added'] = date('c');
		$dt['note'] = $payinfo;
		$dt['ispaid'] = 0; // (($pay_type == 'Cash') || ($pay_type == 'Cheque'))?0:1;
    if ($pay_type == 'Cheque') {
      // for Cheque
      $dt['invoice_num'] = empty($this->input->post('invoice_num')) ? '' : $this->input->post('invoice_num');
      $dt['bank_name'] = $this->input->post('bank_name');
      $dt['payor_name'] = $this->input->post('payor_name');
      $dt['cheque_number'] = $this->input->post('cheque_number');
    } else if ($pay_type == 'Credit Card') {
			$dt['name'] = $card_name;
			$dt['first5'] = substr($card_number, 0, 5);
			$dt['last4'] = substr($card_number, -4);
			$dt['expiry_month'] = $expiry_month;
			$dt['expiry_year'] = $expiry_year;

      $card_number = preg_replace('#[^0-9]#', '', $card_number);
			$card_cvv = preg_replace('#[^0-9]#', '', $card_cvv);
			$card_number_len = strlen($card_number);
			$card_cvv_len = strlen($card_cvv);
			
			if (($card_number_len < 13) || ($card_number_len > 16)) {
				return $this->app_model->return_error('Invalid Card Number');
			} else if (($card_cvv_len < 3) || ($card_cvv_len > 4)) {
				return $this->app_model->return_error('Invalid Card Number CVV');
			}
    }

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
		$payment_id = $this->payment_model->add($dt, $user);
		$para = array(
				'plan_id' => $plan_id,
				'customer_id' => $plan['customer_id'],
				'payment_id' => $payment_id,
				'message' => $this->payment_model->logstr,
				'systemlog' => $this->payment_model->sqlstr
		);
		$this->log_model->activity('payment', $para, $user);

		// // up commission
		// $dt['amount'] = $up_commission_amount;
		// $dt['rate'] = $up_commission_rate;
		// $dt['pay_type'] = 'up_commission';
		// $dt['premium_payment_id'] = $payment_id;
		// $up_commission_payment_id = $this->payment_model->add($dt, $user);
		// $para = array(
		// 		'plan_id' => $plan_id,
		// 		'customer_id' => $plan['customer_id'],
		// 		'payment_id' => $up_commission_payment_id,
		// 		'message' => $this->payment_model->logstr,
		// 		'systemlog' => $this->payment_model->sqlstr
		// );
		// $this->log_model->activity('up_commission', $para, $user);

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

    if (($pay_type == 'Cash') || ($pay_type == 'Cheque')) {
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
      $this->plan_model->update($plan_id, $para, array(), $user);
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
      $this->log_model->activity('plan', $para, $user);
    } else if ($pay_type == 'Credit Card') {
			$beanstream = new \Beanstream\Gateway ( $product['merchent_id'], $product['apikey'], 'www', 'v1' );
			$payment_data = array (
					'order_number' => $plan_id,
					'amount' => $premium,
					'payment_method' => 'card',
					'card' => array (
							'name' => $card_name,
							'number' => $card_number,
							'expiry_month' => $expiry_month,
							'expiry_year' => $expiry_year,
							'cvd' => $card_cvv 
					) 
			);
			try {
				$result = $beanstream->payments()->makeCardPayment( $payment_data, TRUE ); // set to FALSE for Pre-Auth
				if (isset($result['approved'])) {
          $history_id = 0;
          if (($history = $this->plan_history_model->get_plan_history_by_plan_id($plan_id)) && ($history["actualrate"]>0)) {
            $history_id = $history["plan_history_id"];
          } else {
            // Add missing first record.
            if ($plan['status_id'] > 1) {
              $history_id = $this->plan_history_model->add($plan_id, $plan['status_id']);
            }
          }
        
          $payinfo = "Credit Card: " . substr($card_number, 0, 5) . "xxx" . substr($card_number, -4) . " " . $card_name .  " " . $expiry_month . "/" . $expiry_year;
						
					$para = array('payment_id' => $payment_id, 'payinfo' => $payinfo, 'commission_payment_id' => $commission_payment_id, 'status_id' => Plan_model::PAID, 'policy' => $this->plan_model->get_policy_number($plan_id, 2));
					$this->plan_model->update($plan_id, $para);
					$para = array(
							'plan_id' => $plan_id,
							'customer_id' => $plan['customer_id'],
							'payment_id' => $payment_id,
							'message' => $this->plan_model->logstr,
							'systemlog' => $this->plan_model->sqlstr
					);
					$this->log_model->activity('plan', $para, $user);
          if ($history_id) {
            $this->plan_history_model->add_remove($history_id);
          }
          $this->plan_history_model->add($plan_id, Plan_model::PAID);
					
					$dt = array();
					$dt['ispaid'] = 1;
					$dt['note'] = "Success: Raw Data=> " . json_encode($result);
					$payment_id = $this->payment_model->update($payment_id, $dt);
					$para = array(
							'plan_id' => $plan_id,
							'customer_id' => $plan['customer_id'],
							'payment_id' => $payment_id,
							'message' => $this->payment_model->logstr,
							'systemlog' => $this->payment_model->sqlstr
					);
					$this->log_model->activity('payment', $para, $user);
          // If in Quebec, send French version package
          if (($plan["province2"] == "QC") && in_array($plan["product_short"], $this->french_plan)) {
            $this->sendpackage(1, $user, $plan_id);
          }
        } else {
					$payinfo = "Credit Card: " . substr($card_number, 0, 5) . "xxx" . substr($card_number, -4) . " " . $card_name .  " " . $expiry_month . "/" . $expiry_year;
						
					$para = array('payment_id' => $payment_id, 'payinfo' => $payinfo, 'commission_payment_id' => $commission_payment_id );
					$this->plan_model->update($plan_id, $para);
					$para = array(
							'plan_id' => $plan_id,
							'customer_id' => $plan['customer_id'],
							'payment_id' => $payment_id,
							'message' => $this->plan_model->logstr,
							'systemlog' => $this->plan_model->sqlstr
					);
					$this->log_model->activity('plan', $para, $user);
					
					$dt = array();
					$dt['ispaid'] = 0;
					$dt['amount'] = 0;
					$dt['note'] = "Failur pay (" . $premium . "): Raw Data=> " . json_encode($result);
					$payment_id = $this->payment_model->update($payment_id, $dt);
					$para = array(
							'plan_id' => $plan_id,
							'customer_id' => $plan['customer_id'],
							'payment_id' => $payment_id,
							'message' => $this->payment_model->logstr,
							'systemlog' => $this->payment_model->sqlstr
					);
					$this->log_model->activity('payment', $para, $user);
					$commission_payment_id = $this->payment_model->update($commission_payment_id, $dt);
					$up_commission_payment_id = $this->payment_model->update($up_commission_payment_id, $dt);
					$this->error = 'Card payment failed. Incorrect card information or insufficient credit.';
				}
			} catch ( \Beanstream\Exception $e ) {
				$payinfo = "Credit Card: " . substr($card_number, 0, 5) . "xxx" . substr($card_number, -4) . " " . $card_name .  " " . $expiry_month . "/" . $expiry_year;
					
				$para = array('payment_id' => $payment_id, 'payinfo' => $payinfo, 'commission_payment_id' => $commission_payment_id);
				$this->plan_model->update($plan_id, $para);
				$para = array(
						'plan_id' => $plan_id,
						'customer_id' => $plan['customer_id'],
						'payment_id' => $payment_id,
						'message' => $this->plan_model->logstr,
						'systemlog' => $this->plan_model->sqlstr
				);
				$this->log_model->activity('plan', $para, $user);
				
				// print_r ( $e->getMessage() );
				$dt = array();
				$dt['ispaid'] = 0;
				$dt['amount'] = 0;
				$dt['note'] = "Failur pay (" . $premium . "): (libraray) Raw Data=> " . $e->getMessage() . " : " . json_encode($e);
				$payment_id = $this->payment_model->update($payment_id, $dt);
				$para = array(
						'plan_id' => $plan_id,
						'customer_id' => $plan['customer_id'],
						'payment_id' => $payment_id,
						'message' => $this->payment_model->logstr,
						'systemlog' => $this->payment_model->sqlstr
				);
				$this->log_model->activity('payment', $para, $user);
				$commission_payment_id = $this->payment_model->update($commission_payment_id, $dt);
				$up_commission_payment_id = $this->payment_model->update($up_commission_payment_id, $dt);
				$this->error = 'Payment failed. Please verify your credit card info.';
			}
    }
    $this->app_model->return_ok(["status"=>"OK"]);
  }

  public function detail()
  {
    $this->error = "";
    $this->load->model("app_model");
    $this->load->model("user_model");
    $user = $this->app_model->check_token($this->input->post("token"));

    if (empty($user)) {
      if (empty($this->error)) {
        $this->error = "Session Expired";
      }
      return $this->app_model->return_error($this->error);
    }
    if (($user["user_group_id"] < 100) && ($bid = $this->input->post("bid"))) {
      $user = $this->user_model->get_user_by_id($bid);
      if (empty($user)) {
        return $this->app_model->return_error("Unknown agent");
      }
    }

    $data = array();
    $this->load->model("plan_model");
    $this->load->model("customer_model");

    if ($id = $this->input->post("plan_id")) {
      if ($plan = $this->plan_model->get_plan_by_id($id)) {
        if (($user["user_group_id"] > 100) && ($plan["user_id"] != $user["user_id"])) {
          return $this->app_model->return_error("Can't find plan");
        }
        $data["plan"] = $plan;
        $data["customer"] = $this->customer_model->get_customer_by_id($plan["customer_id"]);
        if ($plan["isfamilyplan"]) {
          $data["family"] = $this->customer_model->get_customer_by_parent_id($plan["customer_id"]);
        }
        $this->app_model->return_ok($data);
      }
    }
    return $this->app_model->return_error("Can't find plan");
  }

  public function set_status()
  {
    $this->error = "";
    $this->load->model("app_model");
    $this->load->model("user_model");
    $user = $this->app_model->check_token($this->input->post("token"));

    if (empty($user)) {
      if (empty($this->error)) {
        $this->error = "Session Expired";
      }
      return $this->app_model->return_error($this->error);
    }
    if (($user["user_group_id"] < 100) && ($bid = $this->input->post("bid"))) {
      $user = $this->user_model->get_user_by_id($bid);
      if (empty($user)) {
        return $this->app_model->return_error("Unknown agent");
      }
    }

    // if ($user["user_group_id"] > 100) {
    //   return $this->app_model->return_error($this->error);
    // }
    $this->load->model("plan_model");
    $this->load->model("payment_model");
    $data = array();
    if ($id = $this->input->post("plan_id")) {
      if ($plan = $this->plan_model->get_plan_by_id($id)) {
        if ($plan['status_id'] != Plan_model::CHANGED) {
          return $this->app_model->return_error("Policy cannot use this interface.");
        }
        $totalpaid = $this->payment_model->get_total_paid($id, $pay_type='premium', $plan["apply_date"]);
        $premium = round($plan["premium"], 2);
        $totalpaid = round($totalpaid, 2);
        if ($premium != $totalpaid) {
          return $this->app_model->return_error("Policy not full paid");
        }
        $status_id = Plan_model::PAID;
        if ($this->payment_model->get_payment($id)) {
          $status_id = Plan_model::SOLD;
        }
        $this->plan_model->update($id, ["status_id" => $status_id], array(), $user);
        $plan["status_id"] = $status_id;
        $this->app_model->return_ok(["plan"=>$plan]);
      }
    }
    return $this->app_model->return_error("Unknown Policy");
  }

  public function update()
  {
    $this->error = "";
    $this->load->model("app_model");
    $this->load->model("user_model");
    $user = $this->app_model->check_token($this->input->post("token"));

    if (empty($user)) {
      if (empty($this->error)) {
        $this->error = "Session Expired";
      }
      return $this->app_model->return_error($this->error);
    }
    if (($user["user_group_id"] < 100) && ($bid = $this->input->post("bid"))) {
      $user = $this->user_model->get_user_by_id($bid);
      if (empty($user)) {
        return $this->app_model->return_error("Unknown agent");
      }
    }

    // if ($user["user_group_id"] > 100) {
    //   return $this->app_model->return_error($this->error);
    // }
    $this->load->model("plan_model");
    $data = array();
    if ($id = $this->input->post("plan_id")) {
      if ($this->plan_model->get_plan_by_id($id)) {
        $ckArr = array(
          "holiday_rate" => empty($this->input->post('holiday_rate')) ? 0 : 1,
          "spouse" => empty($this->input->post('spouse')) ? 0 : 1,
          "free_cancel" => empty($this->input->post('free_cancel')) ? 1 : 0    
        );
        $isfamilyplan = 0;
        if (!empty($this->input->post('isfamilyplan'))) {
          if ((int)$this->input->post('isfamilyplan') >= 1) {
            $isfamilyplan = (int)$this->input->post('isfamilyplan');
          } else {
            $isfamilyplan = 1;
          }
        }
        $ckArr['isfamilyplan'] = $isfamilyplan;
        $post = $this->input->post();
        unset($post["user_id"]);
        $this->plan_model->update($id, $post, $ckArr, $user);
      }
    } else {
      $id = $this->plan_model->add($this->input->post(), $user);
    }
    $data["plan"] = $this->plan_model->get_plan_by_id($id);
    $data["claim_message"] = "";

    $post = $this->input->post();
    if ($data["plan"]['claim_flag'] >= 2) {
      if ($data["plan"]['claim_allow_by'] < 1) {
        $data["claim_message"] = "The insured may have a previous claim that is affecting the policy issuance or renewal. Please contact JF staff for further assistance 905-707-1512";
      }
    } else {
      $customers = $this->plan_model->get_plan_customers_by_id($id);
      foreach ($customers as $customer) {
        $vrecords = $this->plan_model->verify_customer($customer['firstname'], $customer['lastname'], $customer['birthday']);
        $claim_amount = 0;
        $case_amount = 0;
        if ($vrecords['status'] == 'OK') {
          foreach ($vrecords['cases'] as $case) {
            $case_amount += (float)$case['amount'];
          }
          foreach ($vrecords['claims'] as $claim) {
            $claim_amount += (float)$claim['amount'];
          }
        }
        if (empty($claim_amount) && empty($case_amount)) {
          continue;
        } else if (($claim_amount <= 2500) && ($case_amount <= 2500)) {
          $this->plan_model->update($id, array('claim_flag' => 1), array(), $user);
          $data["plan"]['claim_flag'] = 1;
          $data["claim_message"] = "Reminders: The insured(s) may have had previous claim(s). Please confirm the policy eligibility and any pre-existing conditions with insured(s). " . $customer['firstname'] . " " . $customer['lastname'] . "(" . $customer['birthday'] . ")";
        } else if (!isset($post["claim_flag"])) /* if (($claim_amount > 2000) || ($case_amount > 2000)) */ {
          $plan = $this->plan_model->update($id, array('claim_flag' => 2), array(), $user);
          $data["plan"]['claim_flag'] = 2;
          $data["claim_message"] = 'The insured may have a previous claim that is affecting the policy issuance or renewal. Please contact JF staff for further assistance 905-707-1512';
        }
      }
    }

    $this->app_model->return_ok($data);
  }

  public function quote() {
    $this->error = "";
    $this->load->model("app_model");
    $this->load->model("user_model");
    $user = $this->app_model->check_token($this->input->post("token"));

    if (empty($user)) {
      if (empty($this->error)) {
        $this->error = "Session Expired";
      }
      return $this->app_model->return_error($this->error);
    }
    if (($user["user_group_id"] < 100) && ($bid = $this->input->post("bid"))) {
      $user = $this->user_model->get_user_by_id($bid);
      if (empty($user)) {
        return $this->app_model->return_error("Unknown agent");
      }
    }

    $this->load->model("plan_model");
    $data = array();

    $post = $this->input->post();
    if (!isset($post["product_short"])) {
      return $this->app_model->return_error("Missing product_short");
    }
    if (!isset($post["total_days"])) {
      return $this->app_model->return_error("Missing total_days");
    }
    if (!isset($post["totalyears"])) {
      return $this->app_model->return_error("Missing totalyears");
    }

    $this->load->model('product_model');
    if (($post['product_short'] == 'TOP') || ($post['product_short'] == 'TOPN')) {
      if (empty($post["province2"])) {
        $post["province2"] = "ON";
      }
      $post['totaldays'] = $post['total_days'];
      if ($premium = $this->product_model->get_top_quote($post)) {
        $data['premiumArr'] = $premium;
      }
    } else {
      if ($premium = $this->product_model->get_premium_sub($post, $user)) {
        $data['premiumArr'] = $premium;
      }
    }
    if (empty($data['premiumArr'])) {
      if (empty($this->error)) {
			  $this->error = "Can't get premium";
      }
      return $this->app_model->return_error($this->error);
		}
		return $this->app_model->return_ok($data);
	}


  public function output_heads() {
    header("Access-Control-Allow-Origin: *");
    $allowedOrigins = ["*"];
    $origin = empty($_SERVER["HTTP_ORIGIN"]) ? '' : $_SERVER["HTTP_ORIGIN"];
    if (in_array($origin, $allowedOrigins)) {
      header("Access-Control-Allow-Origin: $origin");
    }
    // 设置其他CORS头
    header("Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");
  }

  // print receipt
  public function receipt() {
    $this->error = "";
    $this->load->model("app_model");
    $this->load->model("user_model");
    $this->load->helper('url');
    $this->lang->load('message');
    $this->lang->load('message', 'english');

    $user = $this->app_model->check_token($this->input->post("token"));

    if (empty($user)) {
      if (empty($this->error)) {
        $this->error = "Session Expired";
      }
      return $this->app_model->return_error($this->error);
    }
    if (($user["user_group_id"] < 100) && ($bid = $this->input->post("bid"))) {
      $user = $this->user_model->get_user_by_id($bid);
      if (empty($user)) {
        return $this->app_model->return_error("Unknown agent");
      }
    }

    $plan_id = $this->input->post("plan_id");
		if (empty($plan_id)) {
      return $this->app_model->return_error("Unknown plan");
		}

    $this->load->model("plan_model");
		$this->load->model('product_model');
		$plan = $this->plan_model->get_plan_by_id($plan_id);
		if (empty($plan)) {
      return $this->app_model->return_error("Can't find plan");
		}
		$data = array('plan' => $plan);
		$this->load->model('customer_model');
		$data['customer'] = $this->customer_model->get_customer_by_id($data['plan']['customer_id']);
		$data['customers'] = $this->customer_model->get_customer_by_parent_id($data['plan']['customer_id']);
    $this->load->model('html_model');
		$data['html_model'] = $this->html_model;
		if ($data['plan']['product_short'] == 'OPL') {
			$data['insurable_options'] = $this->load->view('plan/detail_opl', $data, TRUE);
		} else if (($data['plan']['product_short'] == 'JFVTC') || ($data['plan']['product_short'] == 'JFR')) {
			$data['insurable_options'] = $this->load->view('plan/detail_opl', $data, TRUE);
		} else if ($data['plan']['product_short'] == 'JUS') {
			$data['insurable_options'] = $this->load->view('plan/detail_jus', $data, TRUE);
		} else if ($data['plan']['product_short'] == 'NUS') {
			$data['insurable_options'] = $this->load->view('plan/detail_jus', $data, TRUE);
		} else if (($data['plan']['product_short'] == 'JFS') || ($data['plan']['product_short'] == 'JFE') || ($data['plan']['product_short'] == 'BHS')) {
			$data['insurable_options'] = $this->load->view('plan/detail_jes', $data, TRUE);
		} else if (($data['plan']['product_short'] == 'JES') || ($data['plan']['product_short'] == 'JES')) {
			$data['insurable_options'] = $this->load->view('plan/detail_jes', $data, TRUE);
		} else if ($data['plan']['product_short'] == 'JESP') {
			$data['insurable_options'] = $this->load->view('plan/detail_jes', $data, TRUE);
		} else if ($data['plan']['product_short'] == 'JFC') {
			$data['insurable_options'] = $this->load->view('plan/detail_jes', $data, TRUE);
		} else if ($data['plan']['product_short'] == 'JFP') {
			$data['insurable_options'] = $this->load->view('plan/detail_jes', $data, TRUE);
		} else if ($data['plan']['product_short'] == 'TOPN') {
			$data['insurable_options'] = $this->load->view('plan/top/card', $data, TRUE);
		} else if ($data['plan']['product_short'] == 'TOP') {
			$data['insurable_options'] = $this->load->view('plan/top/card', $data, TRUE);
		} else {
			$data['insurable_options'] = $this->load->view('plan/detail_other', $data, TRUE);
		}

		$this->load->model('payment_model');
		$data['payment'] = '';
		if ($plan['payment_id']) {
			$data['payment'] = $this->payment_model->get_payment_by_id($plan['payment_id'], $plan['apply_date']);
		}

		$product = $this->product_model->get_product($plan['product_short']);
		$data['plan_full_name'] = $product ? $product['full_name'] : '';
		$data['agent'] = $this->user_model->get_user_by_id($plan['user_id']);
		
    $this->output_heads();
		$mpdf = new mPDF('c');
		$data['title_txt'] = 'Receipt';
		$data['style'] = $this->load->view('common/pdf_style',$data, TRUE);
		$html = $this->load->view('plan/receipt', $data, TRUE);
		$mpdf->writeHTML($html);
		$mpdf->Output("receipt.pdf","I");
  }

  // print card,
  public function card() {
    $this->error = "";
    $this->load->model("app_model");
    $this->load->model("user_model");
    $this->load->helper('url');
    $this->lang->load('message');
    $this->lang->load('message', 'english');

    $user = $this->app_model->check_token($this->input->post("token"));

    if (empty($user)) {
      if (empty($this->error)) {
        $this->error = "Session Expired";
      }
      return $this->app_model->return_error($this->error);
    }
    if (($user["user_group_id"] < 100) && ($bid = $this->input->post("bid"))) {
      $user = $this->user_model->get_user_by_id($bid);
      if (empty($user)) {
        return $this->app_model->return_error("Unknown agent");
      }
    }

    $plan_id = $this->input->post("plan_id");
		if (empty($plan_id)) {
      return $this->app_model->return_error("Unknown plan");
		}

    $this->load->model("plan_model");
		$this->load->model('product_model');
		$plan = $this->plan_model->get_plan_by_id($plan_id);
		if (empty($plan)) {
      return $this->app_model->return_error("Can't find plan");
		}
		$data = array('plan' => $plan);
		$this->load->model('customer_model');
		$data['customer'] = $this->customer_model->get_customer_by_id($data['plan']['customer_id']);
		$data['customers'] = $this->customer_model->get_customer_by_parent_id($data['plan']['customer_id']);
    $this->load->model('html_model');
		$data['html_model'] = $this->html_model;
		if ($data['plan']['product_short'] == 'OPL') {
			$data['insurable_options'] = $this->load->view('plan/detail_opl', $data, TRUE);
		} else if (($data['plan']['product_short'] == 'JFVTC') || ($data['plan']['product_short'] == 'JFR')) {
			$data['insurable_options'] = $this->load->view('plan/detail_opl', $data, TRUE);
		} else if ($data['plan']['product_short'] == 'JUS') {
			$data['insurable_options'] = $this->load->view('plan/detail_jus', $data, TRUE);
		} else if ($data['plan']['product_short'] == 'NUS') {
			$data['insurable_options'] = $this->load->view('plan/detail_jus', $data, TRUE);
		} else if (($data['plan']['product_short'] == 'JFS') || ($data['plan']['product_short'] == 'JFE') || ($data['plan']['product_short'] == 'BHS')) {
			$data['insurable_options'] = $this->load->view('plan/detail_jes', $data, TRUE);
		} else if (($data['plan']['product_short'] == 'JES') || ($data['plan']['product_short'] == 'JFPL') || ($data['plan']['product_short'] == 'JFSL') || ($data['plan']['product_short'] == 'JFGD') || ($data['plan']['product_short'] == 'TCS') || ($data['plan']['product_short'] == 'JFOS')) {
			$data['insurable_options'] = $this->load->view('plan/detail_jes', $data, TRUE);
		} else if ($data['plan']['product_short'] == 'JESP') {
			$data['insurable_options'] = $this->load->view('plan/detail_jes', $data, TRUE);
		} else if ($data['plan']['product_short'] == 'JFC') {
			$data['insurable_options'] = $this->load->view('plan/detail_jes', $data, TRUE);
		} else if ($data['plan']['product_short'] == 'JFP') {
			$data['insurable_options'] = $this->load->view('plan/detail_jes', $data, TRUE);
		} else if ($data['plan']['product_short'] == 'TOPN') {
			$data['insurable_options'] = $this->load->view('plan/top/card', $data, TRUE);
		} else if ($data['plan']['product_short'] == 'TOP') {
			$data['insurable_options'] = $this->load->view('plan/top/card', $data, TRUE);
		} else {
			$data['insurable_options'] = $this->load->view('plan/detail_other', $data, TRUE);
		}

		$product = $this->product_model->get_product($plan['product_short']);
		$data['plan_full_name'] = $product ? $product['full_name'] : '';

    $this->output_heads();
    $mpdf = new mPDF('c');
		$data['title_txt'] = 'Card';
    $data['style'] = $this->load->view('common/pdf_style',$data, TRUE);
		$html = $this->load->view('plan/card', $data, TRUE);
		$mpdf->writeHTML($html);
		$mpdf->Output("policy_card.pdf","I");
  }

  // refound
  public function refund() {
    $this->error = "";
    $this->load->model("app_model");
    $this->load->model("user_model");
    $this->load->model("log_model");

    $user = $this->app_model->check_token($this->input->post("token"));

    if (empty($user)) {
      if (empty($this->error)) {
        $this->error = "Session Expired";
      }
      return $this->app_model->return_error($this->error);
    }
    if (($user["user_group_id"] < 100) && ($bid = $this->input->post("bid"))) {
      $user = $this->user_model->get_user_by_id($bid);
      if (empty($user)) {
        return $this->app_model->return_error("Unknown agent");
      }
    }

    $plan_id = $this->input->post("plan_id");
    $do_refund = $this->input->post("do_refund");
		if (empty($plan_id)) {
      return $this->app_model->return_error("Unknown plan");
		}

    $this->load->model("plan_model");
    $data = array();
		$beuser = $user;
		$this->load->model('plan_model');
		$this->load->model('plan_history_model');

		$plan = $this->plan_model->get_plan_by_id($plan_id);
		if (empty($plan)) {
      return $this->app_model->return_error("Can't find plan");
		}
		if (($plan['status_id'] != Plan_model::SOLD) && ($plan['status_id'] != Plan_model::PAID)) {
      return $this->app_model->return_error("Can't refund plan");
		}
		$this->load->model('product_model');
		$product = $this->product_model->get_product($plan['product_short']);
		
		$data['beuser'] = $beuser;
		$data['plan'] = $plan;
		if ($do_refund == 1) {
			$refund_amount = (float)$this->input->post('refund_amount');
			$admin_fee = (float)$this->input->post('admin_fee');
			$total_amount = (float)$this->input->post('total_refund');
			$refund_date = $this->input->post('refund_date');
			//die($refund_amount . '==' . $admin_fee . '++' . $total_amount);
			if ($total_amount > 0) {
				$this->load->model('payment_model');
				$dt = array();
				$dt['plan_id'] = $plan_id;
				$dt['amount'] = $total_amount * (-1);
				$dt['admin_fee'] = (float)$admin_fee;
				$dt['pay_type'] = 'refund';
				$dt['currency'] = $product['currency'];
				$dt['pay_mothed'] = 'Cheque';
				$dt['added'] = date('c');
				$dt['ispaid'] = 0;
				$dt['note'] = "Refund at " . $dt['added'] . " amount: " . $refund_amount . " admin fee: " . $admin_fee;
				
				$commission_rate = $this->product_model->get_commission_rate($plan['product_short'], $plan['user_id']);
				if (($plan['product_short'] == 'TOP') && ($plan['totalyears'] > 60)) {
					if ($commission_rate > 15) {
						$commission_rate -= 15;
					} else {
						$commission_rate = 0;
					}
				}
        if ($plan['product_short'] == 'TOP') {
          $commission_amount = ($refund_amount - ($plan['tax'] * $refund_amount / $plan['premium'])) * $commission_rate / 100.0;
        } else {
          $commission_amount = $refund_amount * $commission_rate / 100.0;
        }
				$up_commission_rate = $this->product_model->get_up_commission_rate($plan['product_short']);
				$up_commission_amount = $refund_amount * $up_commission_rate / 100.0;
				
				$dt['amount'] = $total_amount * (-1);
				$dt['rate'] = 100;
				$dt['pay_type'] = 'refund';
				$dt['premium_payment_id'] = 0;
				$payment_id = $this->payment_model->add($dt, $user);
				$para = array(
						'plan_id' => $plan_id,
						'customer_id' => $plan['customer_id'],
						'payment_id' => $payment_id,
						'message' => $this->payment_model->logstr,
						'systemlog' => $this->payment_model->sqlstr
				);
				$this->log_model->activity('payment', $para, $user);
				
				$dt['pay_type'] = 'refund_commission';
				$dt['rate'] = $commission_rate;
				$dt['amount'] = $commission_amount * (-1);
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
	
				$dt['pay_type'] = 'refund_up_commission';
				$dt['rate'] = $up_commission_rate;
				$dt['amount'] = $up_commission_amount * (-1);
				$dt['premium_payment_id'] = $payment_id;
				$up_commission_payment_id = $this->payment_model->add($dt, $user);
				$para = array(
						'plan_id' => $plan_id,
						'customer_id' => $plan['customer_id'],
						'payment_id' => $up_commission_payment_id,
						'message' => $this->payment_model->logstr,
						'systemlog' => $this->payment_model->sqlstr
				);
				$this->log_model->activity('up_commission', $para, $user);
	
        // Add history
        $history_id = 0;
        if (($history = $this->plan_history_model->get_plan_history_by_plan_id($plan_id)) && ($history["actualrate"]>0)) {
          $history_id = $history["plan_history_id"];
        } else {
          // Add missing first record
          if ($plan['status_id'] > 1) {
            $history_id = $this->plan_history_model->add($plan_id, $plan['status_id']);
          }
        }
        if ($history_id) {
          $this->plan_history_model->add_remove($history_id);
        }

        $note = "Refund at " . $dt['added'] . " amount: " . $refund_amount . " admin fee: " . $admin_fee . "; " . $plan['note'];
				$para = array('status_id' => Plan_model::REFUND, 'payment_id' => $payment_id, 'commission_payment_id' => $commission_payment_id, 'refund_date' => $refund_date, 'note' => $note );  // Change status to refund
				$this->plan_model->update($plan_id, $para, array(), $user);
        if ($id = $this->plan_history_model->add($plan_id, Plan_model::REFUND)) {
          $this->plan_history_model->update($id, array("payment_id"=>$payment_id, "premium"=>($plan["premium"] - $refund_amount),"expiry_date"=>$refund_date, "note"=>"Refunded Recode"));
        }

				$para = array(
						'plan_id' => $plan_id,
						'customer_id' => $plan['customer_id'],
						'payment_id' => $payment_id,
						'message' => $this->plan_model->logstr,
						'systemlog' => "By APP:".$this->plan_model->sqlstr
				);
				$this->log_model->activity('plan', $para, $user);
        return $this->app_model->return_ok(array('plan_id' => $plan_id, 'customer_id' => $plan['customer_id'], 'payment_id' => $payment_id));
			} else {
				return $this->app_model->return_error('Invalid refund amount');
			}
		}
		$claims = $this->plan_model->verify_policy($plan['policy']);
		$data['claims'] = (!empty($claims) && ($claims['status'] == 'OK')) ? $claims['claims'] : '';
		$data['adminfee'] = 40;
		$data['refund_enable'] = 1;
		if (($plan['product_short'] == 'TOP') || ($plan['product_short'] == 'TOPN')) {
			$data['adminfee'] = 25;
			$data['top_refund_notes'] = "Only Single Medical Plan can do refund.";
			if ($plan['package'] != 'single_medical_plan') {
				$data['top_refund_notes'] .= " This plan can't be refunded.";
				$data['refund_enable'] = 0;
			}
		}
    $data['total_premium'] = $plan['premium'];
		$data['status'] = 'OK';
		$data['refund_days'] = $this->product_model->getDays($plan['effective_date'], date("Y-m-d"));
		if ($plan['product_short'] == 'TOPN') {
			$this->load->model('topn_model');
			$data['refund_amount'] = $this->topn_model->refund_amount($plan, $data['refund_days']);
    } else if ($plan['product_short'] == 'TOP') {
			if ($plan_id>Product_model::PLANIDCHG2025_1) {
				$this->load->model('top3_model');
				$data['refund_amount'] = $this->top3_model->refund_amount($plan, $data['refund_days']);
			} else if ($plan_id>Product_model::PLANIDCHG2024_1) {
				$this->load->model('top2_model');
				$data['refund_amount'] = $this->top2_model->refund_amount($plan, $data['refund_days']);
			} else {
				$this->load->model('top_model');
				$data['refund_amount'] = $this->top_model->refund_amount($plan, $data['refund_days']);
			}
		} else {
			$data['refund_amount'] = $this->plan_model->refund_amount($plan_id, $this->input->get('refund_date'));
		}
		$data['used_amount'] = $plan['premium'] - $data['refund_amount']; 
		return $this->app_model->return_ok($data);
  }

  // cancel
  public function cancel() {
    $this->error = "";
    $this->load->model("app_model");
    $this->load->model("user_model");
    $this->load->model("log_model");

    $user = $this->app_model->check_token($this->input->post("token"));

    if (empty($user)) {
      if (empty($this->error)) {
        $this->error = "Session Expired";
      }
      return $this->app_model->return_error($this->error);
    }
    if (($user["user_group_id"] < 100) && ($bid = $this->input->post("bid"))) {
      $user = $this->user_model->get_user_by_id($bid);
      if (empty($user)) {
        return $this->app_model->return_error("Unknown agent");
      }
    }

    $plan_id = $this->input->post("plan_id");
    $do_cancel = $this->input->post("do_cancel");
		if (empty($plan_id)) {
      return $this->app_model->return_error("Unknown plan");
		}

    $data = array();
		$beuser = $user;
		$this->load->model('plan_model');
		$this->load->model('plan_history_model');

		$plan = $this->plan_model->get_plan_by_id($plan_id);
		if (empty($plan)) {
      return $this->app_model->return_error("Can't find plan");
		}
		if (($plan['status_id'] != Plan_model::SOLD) && ($plan['status_id'] != Plan_model::PAID)) {
      return $this->app_model->return_error("Can't cancel plan");
		}
		$this->load->model('product_model');
		$product = $this->product_model->get_product($plan['product_short']);
		
		$data['beuser'] = $beuser;
		$data['plan'] = $plan;


		if ($do_cancel == 1) {
			$refund_amount = (float)$this->input->post('refund_amount');
			$admin_fee = (float)$this->input->post('admin_fee');

			$total_amount = $refund_amount - $admin_fee;

			if ($total_amount > 0) {
				$this->load->model('payment_model');
				$dt = array();
				$dt['plan_id'] = $plan_id;
				$dt['admin_fee'] = $admin_fee;
				$dt['currency'] = $product['currency'];
				$dt['pay_mothed'] = 'Cheque';
				$dt['added'] = date('c');
				$dt['ispaid'] = 0;
				$dt['note'] = "Cancel at " . $dt['added'] . " amount: " . $refund_amount . " admin fee: " . $admin_fee;
				
				$commission_rate = $this->product_model->get_commission_rate($plan['product_short'], $plan['user_id']);
				if (($plan['product_short'] == 'TOP') && ($plan['totalyears'] > 60)) {
					if ($commission_rate > 15) {
						$commission_rate -= 15;
					} else {
						$commission_rate = 0;
					}
				}
				
        if ($plan['product_short'] == 'TOP') {
          $commission_amount = ($refund_amount - ($plan['tax'] * $refund_amount / $plan['premium'])) * $commission_rate / 100.0;
        } else {
          $commission_amount = $refund_amount * $commission_rate / 100.0;
        }
				$up_commission_rate = $this->product_model->get_up_commission_rate($plan['product_short']);
				$up_commission_amount = $refund_amount * $up_commission_rate / 100.0;
				
				$dt['amount'] = $total_amount * (-1);
				$dt['rate'] = 100;
				$dt['pay_type'] = 'cancel';
				$dt['premium_payment_id'] = 0;
				$payment_id = $this->payment_model->add($dt, $user);
				$para = array(
						'plan_id' => $plan_id,
						'customer_id' => $plan['customer_id'],
						'payment_id' => $payment_id,
						'message' => $this->payment_model->logstr,
						'systemlog' => $this->payment_model->sqlstr
				);
				$this->log_model->activity('payment', $para, $user);
				
				$dt['pay_type'] = 'cancel_commission';
				$dt['rate'] = $commission_rate;
				$dt['amount'] = $commission_amount * (-1);
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
	
				$dt['pay_type'] = 'cancel_up_commission';
				$dt['rate'] = $up_commission_rate;
				$dt['amount'] = $up_commission_amount * (-1);
				$dt['premium_payment_id'] = $payment_id;
				$up_commission_payment_id = $this->payment_model->add($dt, $user);
				$para = array(
						'plan_id' => $plan_id,
						'customer_id' => $plan['customer_id'],
						'payment_id' => $up_commission_payment_id,
						'message' => $this->payment_model->logstr,
						'systemlog' => $this->payment_model->sqlstr
				);
				$this->log_model->activity('up_commission', $para, $user);

        $history_id = 0;
        if (($history = $this->plan_history_model->get_plan_history_by_plan_id($plan_id)) && ($history["actualrate"]>0)) {
          $history_id = $history["plan_history_id"];
        } else {
          // Add missing first record.
          if ($plan['status_id'] > 1) {
            $history_id = $this->plan_history_model->add($plan_id, $plan['status_id']);
          }
        }

        $exnote = $this->input->post('reason_input');
        if (empty($exnote)) {
          $exnote = $this->input->post('reason');
        }
				$note = "Reason: " . $exnote . ", cancel at " . $dt['added'] . " amount: " . $refund_amount . " admin fee: " . $admin_fee . "; " . $plan['note'];
				$para = array('status_id' => 5, 'payment_id' => $payment_id, 'commission_payment_id' => $commission_payment_id, 'note' => $note );  // Change status to cancel
				$this->plan_model->update($plan_id, $para, array(), $user);
        if ($id = $this->plan_history_model->add_remove($history_id)) {
          $this->plan_history_model->update($id, array("payment_id"=>$payment_id, "note"=>"Canceled Recode"));
        }
        $para = array(
						'plan_id' => $plan_id,
						'customer_id' => $plan['customer_id'],
						'payment_id' => $payment_id,
						'message' => $this->plan_model->logstr,
						'systemlog' => $this->plan_model->sqlstr
				);
				$this->log_model->activity('plan', $para, $user);

				if ((($plan['product_short'] == 'OPL') || ($plan['product_short'] == 'JFVTC') || ($plan['product_short'] == 'JFR')) && ($plan['sum_insured'] >= 100000) && ($plan['totaldays'] >= 365)) {
					// No more super visa, change payment data to today
					$this->payment_model->adjust_commission_added_date($plan_id, $dt['added'], FALSE);
					$para = array(
							'plan_id' => $plan_id,
							'customer_id' => $plan['customer_id'],
							'payment_id' => $plan['commission_payment_id'],
							'message' => $this->payment_model->logstr,
							'systemlog' => $this->payment_model->sqlstr
					);
					$this->log_model->activity('plan', $para, $user);
				}
        return $this->app_model->return_ok(array('plan_id' => $plan_id, 'customer_id' => $plan['customer_id'], 'payment_id' => $payment_id));
			} else {
				return $this->app_model->return_error('Invalid refund amount');
			}
		}

		$claims = $this->plan_model->verify_policy($plan['policy']);
		$data['claims'] = (!empty($claims) && ($claims['status'] == 'OK')) ? $claims['claims'] : '';
		$data['admin_fee'] = 0;
		return $this->app_model->return_ok($data);
  }

  // export pdf
  public function pdf() {
    $this->error = "";
    $this->load->model("app_model");
    $this->load->model("user_model");
    $this->load->helper('url');
    $this->lang->load('message');
    $this->lang->load('message', 'english');

    $user = $this->app_model->check_token($this->input->post("token"));

    if (empty($user)) {
      if (empty($this->error)) {
        $this->error = "Session Expired";
      }
      return $this->app_model->return_error($this->error);
    }
    if (($user["user_group_id"] < 100) && ($bid = $this->input->post("bid"))) {
      $user = $this->user_model->get_user_by_id($bid);
      if (empty($user)) {
        return $this->app_model->return_error("Unknown agent");
      }
    }

    $plan_id = $this->input->post("plan_id");
		if (empty($plan_id)) {
      return $this->app_model->return_error("Unknown plan");
		}

		$beuser = $user;

		$this->load->model('customer_model');
		$this->load->model('plan_model');
		$this->load->model('product_model');
		$this->load->model('paytype_model');
		$this->load->model('status_model');
		$this->load->model('payment_model');
		$this->load->model('html_model');
		$plan = $this->plan_model->get_plan_by_id($plan_id);
		if (empty($plan)) {
      return $this->app_model->return_error("Can't find plan");
		}
		
		$data['beuser'] = $beuser;
		$data['plan'] = $plan;
		$data['pdf_enable'] = empty($beuser['pdf_product']) ? array() : json_decode($beuser['pdf_product']);
		$data['payment'] = '';
		if ($plan['payment_id']) {
			$data['payment'] = $this->payment_model->get_payment_by_id($plan['payment_id'], $plan['apply_date']);
		}
		$data['user'] = '';
		if ($plan['user_id']) {
			$data['user'] = $this->user_model->get_user_by_id($plan['user_id']);
		}
		$product = $this->product_model->get_product($plan['product_short']);
		$data['plan_full_name'] = $product ? $product['full_name'] : '';
		$data['customer'] = $this->customer_model->get_customer_by_id($data['plan']['customer_id']);
		$data['customers'] = $this->customer_model->get_customer_by_parent_id($data['plan']['customer_id']);
		$data['paytype_list'] = $this->paytype_model->paytype_list();
		$data['status_list'] = $this->status_model->status_list();
		$data['withlogo'] = $this->input->post("withlogo")?$this->input->post("withlogo"):1;
    $data['withprice'] = $this->input->post("withprice")?$this->input->post("withprice"):1;
		$disablePriceArr = array(4968,4942,2151,2626,4258,798,339,3698,3799);
    if (in_array($beuser['user_id'], $disablePriceArr)) {
      $data['withprice'] = 0;
    } else if (($beuser['user_group_id'] == 103) || ($beuser['user_group_id'] == 106)) {
      $data['withprice'] = 0;
    }
		$data['html_model'] = $this->html_model;
		
		if ($data['plan']['product_short'] == 'OPL') {
			$data['insurable_options'] = $this->load->view('plan/detail_opl', $data, TRUE);
			$data['special_note'] = $this->load->view('plan/pdf_note_opl',$data, TRUE);
		} else if ($data['plan']['product_short'] == 'JFR') {
			$data['insurable_options'] = $this->load->view('plan/detail_opl', $data, TRUE);
			$data['special_note'] = $this->load->view('plan/pdf_note_jfr',$data, TRUE);
		} else if ($data['plan']['product_short'] == 'JUS') {
			$data['insurable_options'] = $this->load->view('plan/detail_jus', $data, TRUE);
			$data['special_note'] = $this->load->view('plan/pdf_note_jus',$data, TRUE);
		} else if ($data['plan']['product_short'] == 'NUS') {
			$data['insurable_options'] = $this->load->view('plan/detail_jus', $data, TRUE);
			$data['special_note'] = $this->load->view('plan/pdf_note_nus',$data, TRUE);
		} else if (($data['plan']['product_short'] == 'JFS') || ($data['plan']['product_short'] == 'JFE') || ($data['plan']['product_short'] == 'BHS')) {
			$data['insurable_options'] = $this->load->view('plan/detail_jes', $data, TRUE);
			$data['special_note'] = $this->load->view('plan/pdf_note_jes',$data, TRUE);
		} else if ($data['plan']['product_short'] == 'JES') {
			$data['insurable_options'] = $this->load->view('plan/detail_jes', $data, TRUE);
			$data['special_note'] = $this->load->view('plan/pdf_note_jes',$data, TRUE);
		} else if ($data['plan']['product_short'] == 'JFPL') {
			$data['insurable_options'] = $this->load->view('plan/detail_jfpl', $data, TRUE);
			$data['special_note'] = $this->load->view('plan/pdf_note_jfpl',$data, TRUE);
		} else if ($data['plan']['product_short'] == 'JFSL') {
			$data['insurable_options'] = $this->load->view('plan/detail_jfpl', $data, TRUE);
			$data['special_note'] = $this->load->view('plan/pdf_note_jfpl',$data, TRUE);
		} else if (($data['plan']['product_short'] == 'JFGD') || ($data['plan']['product_short'] == 'TCS') || ($data['plan']['product_short'] == 'JFOS')) {
			$data['insurable_options'] = $this->load->view('plan/detail_jfpl', $data, TRUE);
			$data['special_note'] = $this->load->view('plan/pdf_note_jfpl',$data, TRUE);
		} else if ($data['plan']['product_short'] == 'JESP') {
			$data['insurable_options'] = $this->load->view('plan/detail_jes', $data, TRUE);
			$data['special_note'] = $this->load->view('plan/pdf_note_jes',$data, TRUE);
		} else if ($data['plan']['product_short'] == 'JFC') {
			$data['insurable_options'] = $this->load->view('plan/detail_jes', $data, TRUE);
			$data['special_note'] = $this->load->view('plan/pdf_note_jfc',$data, TRUE);
		} else if ($data['plan']['product_short'] == 'JFP') {
			$data['insurable_options'] = $this->load->view('plan/detail_jes', $data, TRUE);
			$data['special_note'] = $this->load->view('plan/pdf_note_jes',$data, TRUE);
		} else if ($data['plan']['product_short'] == 'TOPN') {
			$data['insurable_options'] = '';
			$data['toppackagename'] = $this->topnpackagename;
			$data['special_note'] = $this->load->view('plan/top/pdf_note_top',$data, TRUE);
		} else if ($data['plan']['product_short'] == 'TOP') {
			$data['insurable_options'] = '';
			$data['toppackagename'] = $this->toppackagename;
			$data['special_note'] = $this->load->view('plan/top/pdf_note_top',$data, TRUE);
		} else {
			$data['insurable_options'] = $this->load->view('plan/detail_other', $data, TRUE);
			$data['special_note'] = " ";
		}
		
		$data['title_txt'] = 'Policy';
		$data['customer_product_name'] = $this->product_model->get_product_customize_name($beuser['user_id'], $data['plan']['product_short']);
		$data['style'] = $this->load->view('common/pdf_style',$data, TRUE);		
		$data['hadheaderfooter'] = 0;
    if ($data['plan']['product_short'] == 'JFVTC') {
      $mpdf = new mPDF('+aCJK', 'A4', 0, '', $mgl = 0, $mgr = 0, $mgt = 15, $mgb = 0, $mgh = 0, $mgf = 0, $orientation = 'P');
      if ($plan['status_id'] < 2) {
        $mpdf->SetWatermarkText ("QUOTE", 0.1);
        $mpdf->showWatermarkText = true;
      } else if (($plan['status_id'] != 2) && ($plan['status_id'] != 3)) {
        $mpdf->SetWatermarkText ("INVALID", 0.1);
        $mpdf->showWatermarkText = true;
      }
      $data['hadheaderfooter'] = 1;
      $html = $this->load->view('plan/pdf_jfvtc', $data, TRUE);
      if ($data['withlogo']) {
        $mpdf->SetHTMLHeader('<img style="width:100%;" src="'.base_url().'image/pdf_header.png" />');
      }
    } else if (($data['plan']['product_short'] == 'JFPL') || ($data['plan']['product_short'] == 'JFGD')  || ($data['plan']['product_short'] == 'TCS') || ($data['plan']['product_short'] == 'JFOS') || ($data['plan']['product_short'] == 'JFSL')) {
      $mpdf = new mPDF('+aCJK', 'A4', 0, '', $mgl = 0, $mgr = 0, $mgt = 15, $mgb = 0, $mgh = 0, $mgf = 0, $orientation = 'P');
      if ($plan['status_id'] < 2) {
        $mpdf->SetWatermarkText ("QUOTE", 0.1);
        $mpdf->showWatermarkText = true;
      } else if (($plan['status_id'] != 2) && ($plan['status_id'] != 3)) {
        $mpdf->SetWatermarkText ("INVALID", 0.1);
        $mpdf->showWatermarkText = true;
      }
      $data['hadheaderfooter'] = 1;
      $html = $this->load->view('plan/pdf', $data, TRUE);
      if ($data['withlogo']) {
        $mpdf->SetHTMLHeader('<img style="width:100%;" src="'.base_url().'image/pdf_header.png" />');
      }
    } else {
      $mpdf = new mPDF('+aCJK');
      if ($plan['status_id'] < 2) {
        $mpdf->SetWatermarkText ("QUOTE", 0.1);
        $mpdf->showWatermarkText = true;
      } else if (($plan['status_id'] != 2) && ($plan['status_id'] != 3)) {
        $mpdf->SetWatermarkText ("INVALID", 0.1);
        $mpdf->showWatermarkText = true;
      }
      $html = $this->load->view('plan/pdf', $data, TRUE);
    }
    $this->output_heads();
    $mpdf->autoLangToFont=true;
    $mpdf->autoScriptToLang=true;
		$mpdf->writeHTML($html);
		$mpdf->Output("Policy.pdf","I");
  }

  // send package
  public function sendpackage() {
    $this->error = "";
    $this->load->model("app_model");
    $this->load->model("user_model");
    $this->load->helper('url');
    $this->lang->load('message');
    $this->lang->load('message', 'english');

    $user = $this->app_model->check_token($this->input->post("token"));

    if (empty($user)) {
      if (empty($this->error)) {
        $this->error = "Session Expired";
      }
      return $this->app_model->return_error($this->error);
    }
    $post = $this->input->post();
    if (($user["user_group_id"] < 100) && ($bid = $this->input->post("bid"))) {
      $user = $this->user_model->get_user_by_id($bid);
      if (empty($user)) {
        return $this->app_model->return_error("Unknown agent");
      }
    }

    $plan_id = $this->input->post("plan_id");
		if (empty($plan_id)) {
      return $this->app_model->return_error("Unknown plan");
		}

		$beuser = $user;

    if ($user["user_group_id"] >= 100) {
      $post["withlogo"] = 1;
      $post["withprice"] = 1;
    }

    $data = array();
		$this->load->model('plan_model');
		$plan = $this->plan_model->get_plan_by_id($plan_id);
		if (empty($plan)) {
      return $this->app_model->return_error("Can't find plan");
		}
		
		$data['beuser'] = $beuser;
		$data['plan'] = $plan;
		$data['pdf_enable'] = empty($beuser['pdf_product']) ? array() : json_decode($beuser['pdf_product']);
		$data['emailaddr'] = $plan['contact_email'];
    $data['withlogo'] = isset($post['withlogo'])?$post['withlogo']:1;
    $data['withprice'] = isset($post['withprice'])?$post['withprice']:1;
    $disablePriceArr = array(4968,4942,2151,2626,4258,798,339,3698,3799);
    if (in_array($beuser['user_id'], $disablePriceArr)) {
      $data['withprice'] = 0;
    }

    $data['sendfrench'] = isset($post['sendfrench'])?$post['sendfrench']:0;
    $emailaddr = isset($post['emailaddr'])?$post['emailaddr']:"";
    if (!empty($emailaddr)) {
      $data['emailaddr'] = $emailaddr;
    }
    $this->load->model('verify_model');
    if ($this->verify_model->isEmail($data['emailaddr'])) {
      $this->load->model('customer_model');
      $this->load->model('product_model');
      $this->load->model('paytype_model');
      $this->load->model('status_model');
      $this->load->model('payment_model');
      $this->load->model('html_model');
      $product = $this->product_model->get_product($plan['product_short']);
      $data['payment'] = '';
      if ($plan['payment_id']) {
        $data['payment'] = $this->payment_model->get_payment_by_id($plan['payment_id']);
      }
      $data['plan_full_name'] = $product ? $product['full_name'] : '';
      $data['customer'] = $this->customer_model->get_customer_by_id($data['plan']['customer_id']);
      $data['customers'] = $this->customer_model->get_customer_by_parent_id($data['plan']['customer_id']);
      $data['paytype_list'] = $this->paytype_model->paytype_list();
      $data['status_list'] = $this->status_model->status_list();
      $data['html_model'] = $this->html_model;
      
      if ($plan['user_id']) {
        $data['user'] = $this->user_model->get_user_by_id($plan['user_id']);
      }
      if ($data['plan']['product_short'] == 'OPL') {
        $data['insurable_options'] = $this->load->view('plan/detail_opl', $data, TRUE);
        $data['special_note'] = $this->load->view('plan/pdf_note_opl',$data, TRUE);
        $files = array(
        'OPL_Policy.pdf' => DOWNLOADDIR . 'OPL_Policy.pdf',
        'OPL_Claim_Procedure.pdf' => DOWNLOADDIR . 'OPL_Claim_Procedure.pdf',
        'OPL_Consent_Form.pdf' => DOWNLOADDIR . 'OPL_Consent_Form.pdf',
        'OPL_Claim_Form.pdf' => DOWNLOADDIR . 'OPL_Claim_Form.pdf',
        'OPL_Brochure.pdf' => DOWNLOADDIR . 'OPL_Brochure.pdf'
        );
      } else if ($data['plan']['product_short'] == 'JFVTC') {
        if ($data['sendfrench']) {
          $files = array(
          'JFVTC_Policy.pdf' => DOWNLOADDIR . 'JFVTC_Policy_French.pdf',
          'JFVTC_Claim_Procedure.pdf' => DOWNLOADDIR . 'JFVTC_Claim_Procedure_French.pdf',
          'JFVTC_Claim_Form.pdf' => DOWNLOADDIR . 'JFVTC_Claim_Form_French.pdf',
          'JFVTC_Brochure.pdf' => DOWNLOADDIR . 'JFVTC_Brochure_French.pdf'
          );
          if ($plan['effective_date'] < '2023-05-01') {
            $files['JFVTC_Policy.pdf'] = DOWNLOADDIR . 'JFVTC_Policy_French_old.pdf';
          }
        } else {
          $files = array(
          'JFVTC_Policy.pdf' => DOWNLOADDIR . 'JFVTC_Policy.pdf',
          'JFVTC_Claim_Procedure.pdf' => DOWNLOADDIR . 'JFVTC_Claim_Procedure.pdf',
          'JFVTC_Claim_Form.pdf' => DOWNLOADDIR . 'JFVTC_Claim_Form.pdf',
          'JFVTC_Brochure.pdf' => DOWNLOADDIR . 'JFVTC_Brochure.pdf'
          );
          if ($plan['effective_date'] < '2023-05-01') {
            $files['JFVTC_Policy.pdf'] = DOWNLOADDIR . 'JFVTC_Policy_old.pdf';
          }
        }
      } else if ($data['plan']['product_short'] == 'JFR') {
        if ($data['sendfrench']) {
          $files = array(
            'JFR_Policy.pdf' => DOWNLOADDIR . 'JFR_Policy_French.pdf',
            'JFR_Claim_Procedure.pdf' => DOWNLOADDIR . 'JFR_Clinic_Map_French.pdf',
            'JFR_Claim_Form.pdf' => DOWNLOADDIR . 'JFR_Claim_Form_French.pdf',
            'JFR_Brochure.pdf' => DOWNLOADDIR . 'JFR_Brochure_French.pdf'
            );
        } else {
          $data['insurable_options'] = $this->load->view('plan/detail_opl', $data, TRUE);
          $data['special_note'] = $this->load->view('plan/pdf_note_jfr',$data, TRUE);
          $files = array(
          'JFR_Policy.pdf' => DOWNLOADDIR . 'JFR_Policy.pdf',
          'JFR_Claim_Procedure.pdf' => DOWNLOADDIR . 'JFR_Claim_Procedure.pdf',
          'JFR_Consent_Form.pdf' => DOWNLOADDIR . 'JFR_Consent_Form.pdf',
          'JFR_Claim_Form.pdf' => DOWNLOADDIR . 'JFR_Claim_Form.pdf',
          'JFR_Brochure.pdf' => DOWNLOADDIR . 'JFR_Brochure.pdf'
          );
        }
      } else if ($data['plan']['product_short'] == 'JUS') {
        $data['insurable_options'] = $this->load->view('plan/detail_jus', $data, TRUE);
        $data['special_note'] = $this->load->view('plan/pdf_note_jus',$data, TRUE);
        $files = array(
        'JUS_Brochure.pdf' => DOWNLOADDIR . 'JUS_Brochure.pdf'
        );
      } else if ($data['plan']['product_short'] == 'NUS') {
        $data['insurable_options'] = $this->load->view('plan/detail_jus', $data, TRUE);
        $data['special_note'] = $this->load->view('plan/pdf_note_nus',$data, TRUE);
        $files = array(
        'NUS_Brochure.pdf' => DOWNLOADDIR . 'NUS_Brochure.pdf'
        );
      } else if ($data['plan']['product_short'] == 'JFS') {
        if ($data['sendfrench']) {
          $files = array(
          'JFS_Policy.pdf' => DOWNLOADDIR . 'JFS_Policy_French.pdf',
          'JFS_Claim_Form.pdf' => DOWNLOADDIR . 'JFS_Claim_Form_French.pdf',
          'JFS_Clinic_Map.pdf' => DOWNLOADDIR . 'JFS_Clinic_Map_French.pdf',
          'JFS_Benefit_Summary.pdf' => DOWNLOADDIR . 'JFS_Benefit_Summary_French.pdf',
          'JFS_Brochure.pdf' => DOWNLOADDIR . 'JFS_Brochure_French.pdf',
          );
        } else {
          $data['special_note'] = $this->load->view('plan/pdf_note_jes',$data, TRUE);
          $files = array(
          'JFS_Policy.pdf' => DOWNLOADDIR . 'JFS_Policy.pdf',
          'JFS_Claim_Form.pdf' => DOWNLOADDIR . 'JFS_Claim_Form.pdf',
          'JFS_Clinic_Map.pdf' => DOWNLOADDIR . 'JFS_Clinic_Map.pdf',
          );
        }
      } else if ($data['plan']['product_short'] == 'JFE') {
        $data['insurable_options'] = $this->load->view('plan/detail_jes', $data, TRUE);
        $data['special_note'] = $this->load->view('plan/pdf_note_jes',$data, TRUE);
        $files = array(
        'JFE_Policy.pdf' => DOWNLOADDIR . 'JFE_Policy.pdf',
        'JFE_Claim_Form.pdf' => DOWNLOADDIR . 'JFE_Claim_Form.pdf',
        'JFE_Clinic_Map.pdf' => DOWNLOADDIR . 'JFE_Clinic_Map.pdf',
        );
      } else if ($data['plan']['product_short'] == 'BHS') {
        $data['insurable_options'] = $this->load->view('plan/detail_jes', $data, TRUE);
        $data['special_note'] = $this->load->view('plan/pdf_note_jes',$data, TRUE);
        $files = array(
        'BHS_Policy.pdf' => DOWNLOADDIR . 'BHS_Policy.pdf',
        'BHS_Claim_Form.pdf' => DOWNLOADDIR . 'BHS_Claim_Form.pdf',
        'BHS_Clinic_Map.pdf' => DOWNLOADDIR . 'BHS_Clinic_Map.pdf',
        );
      } else if ($data['plan']['product_short'] == 'JES') {
        if ($data['sendfrench']) {
          $files = array(
          'JES_Policy.pdf' => DOWNLOADDIR . 'JES_Policy_French.pdf',
          'JES_Claim_Form.pdf' => DOWNLOADDIR . 'JES_Claim_Form_French.pdf',
          'JES_Clinic_Map.pdf' => DOWNLOADDIR . 'JES_Clinic_Map_French.pdf',
          'JES_Brochure.pdf' => DOWNLOADDIR . 'JES_Brochure_French.pdf'
          );
        } else {
          $data['special_note'] = $this->load->view('plan/pdf_note_jes',$data, TRUE);
          $files = array(
          'JES_Policy.pdf' => DOWNLOADDIR . 'JES_Policy.pdf',
          'JES_Claim_Form.pdf' => DOWNLOADDIR . 'JES_Claim_Form.pdf',
          'JES_Clinic_Map.pdf' => DOWNLOADDIR . 'JES_Clinic_Map.pdf',
          'JES_Brochure.pdf' => DOWNLOADDIR . 'JES_Brochure.pdf'
          );
        }
      } else if ($data['plan']['product_short'] == 'JFPL') {
        if ($data['sendfrench']) {
          $files = array(
          'JFPL_Policy.pdf' => DOWNLOADDIR . 'JFPL_Policy_French.pdf',
          'JFPL_Claim_Form.pdf' => DOWNLOADDIR . 'JFPL_Claim_Form_French.pdf',
          'JFPL_Clinic_Map.pdf' => DOWNLOADDIR . 'JFPL_Clinic_Map_French.pdf',
          'JFPL_Benefit_Summary.pdf' => DOWNLOADDIR . 'JFPL_Benefit_Summary_French.pdf',
          );
          if ($plan['effective_date'] < '2023-01-01') {
            $files['JFPL_Policy.pdf'] = DOWNLOADDIR . 'JFPL_Policy_French_old.pdf';
          }
        } else {
          $data['special_note'] = $this->load->view('plan/pdf_note_jes',$data, TRUE);
          $files = array(
          'JFPL_Policy.pdf' => DOWNLOADDIR . 'JFPL_Policy.pdf',
          'JFPL_Claim_Form.pdf' => DOWNLOADDIR . 'JFPL_Claim_Form.pdf',
          'JFPL_Clinic_Map.pdf' => DOWNLOADDIR . 'JFPL_Clinic_Map.pdf',
          'JFPL_Benefit_Summary.pdf' => DOWNLOADDIR . 'JFPL_Benefit_Summary.pdf'
          );
          if ($plan['effective_date'] < '2023-01-01') {
            $files['JFPL_Policy.pdf'] = DOWNLOADDIR . 'JFPL_Policy_old.pdf';
          }
        }
      } else if ($data['plan']['product_short'] == 'JFSL') {
        $data['insurable_options'] = $this->load->view('plan/detail_jes', $data, TRUE);
        $data['special_note'] = $this->load->view('plan/pdf_note_jes',$data, TRUE);
        $files = array(
        'JFSL_Policy.pdf' => DOWNLOADDIR . 'JFSL_Policy.pdf',
        'JFSL_Claim_Form.pdf' => DOWNLOADDIR . 'JFSL_Claim_Form.pdf',
        'JFSL_Clinic_Map.pdf' => DOWNLOADDIR . 'JFSL_Clinic_Map.pdf',
        'JFSL_Benefit_Summary.pdf' => DOWNLOADDIR . 'JFSL_Benefit_Summary.pdf'
        );
      } else if ($data['plan']['product_short'] == 'JFGD') {
        if ($data['sendfrench']) {
          $files = array(
          'JFGD_Policy.pdf' => DOWNLOADDIR . 'JFGD_Policy_French.pdf',
          'JFGD_Claim_Form.pdf' => DOWNLOADDIR . 'JFGD_Claim_Form_French.pdf',
          'JFGD_Clinic_Map.pdf' => DOWNLOADDIR . 'JFGD_Clinic_Map_French.pdf',
          'JFGD_Benefit_Summary.pdf' => DOWNLOADDIR . 'JFGD_Benefit_Summary_French.pdf',
          'JFGD_Brochure.pdf' => DOWNLOADDIR . 'JFGD_Brochure_French.pdf'
          );
        } else {
          $data['insurable_options'] = $this->load->view('plan/detail_jes', $data, TRUE);
          $data['special_note'] = $this->load->view('plan/pdf_note_jes',$data, TRUE);
          $files = array(
          'JFGD_Policy.pdf' => DOWNLOADDIR . 'JFGD_Policy.pdf',
          'JFGD_Claim_Form.pdf' => DOWNLOADDIR . 'JFGD_Claim_Form.pdf',
          'JFGD_Clinic_Map.pdf' => DOWNLOADDIR . 'JFGD_Clinic_Map.pdf',
          'JFGD_Benefit_Summary.pdf' => DOWNLOADDIR . 'JFGD_Benefit_Summary.pdf'
          );
        }
      } else if ($data['plan']['product_short'] == 'TCS') {
        $data['insurable_options'] = $this->load->view('plan/detail_jes', $data, TRUE);
        $data['special_note'] = $this->load->view('plan/pdf_note_jes',$data, TRUE);
        $files = array(
        'TCS_Policy.pdf' => DOWNLOADDIR . 'TCS_Policy.pdf',
        'TCS_Claim_Form.pdf' => DOWNLOADDIR . 'TCS_Claim_Form.pdf',
        'TCS_Clinic_Map.pdf' => DOWNLOADDIR . 'TCS_Clinic_Map.pdf',
        'TCS_Benefit_Summary.pdf' => DOWNLOADDIR . 'TCS_Benefit_Summary.pdf'
        );
      } else if ($data['plan']['product_short'] == 'JFOS') {
        if ($data['sendfrench']) {
          $files = array(
          'JFOS_Policy.pdf' => DOWNLOADDIR . 'JFOS_Policy_French.pdf',
          'JFOS_Claim_Form.pdf' => DOWNLOADDIR . 'JFOS_Claim_Form_French.pdf',
          'JFOS_Clinic_Map.pdf' => DOWNLOADDIR . 'JFOS_Clinic_Map_French.pdf',
          'JFOS_Benefit_Summary.pdf' => DOWNLOADDIR . 'JFOS_Benefit_Summary_French.pdf',
          'JFOS_Brochure.pdf' => DOWNLOADDIR . 'JFOS_Brochure_French.pdf'
          );
        } else {
          $data['insurable_options'] = $this->load->view('plan/detail_jes', $data, TRUE);
          $data['special_note'] = $this->load->view('plan/pdf_note_jes',$data, TRUE);
          $files = array(
          'JFOS_Policy.pdf' => DOWNLOADDIR . 'JFOS_Policy.pdf',
          'JFOS_Claim_Form.pdf' => DOWNLOADDIR . 'JFOS_Claim_Form.pdf',
          'JFOS_Clinic_Map.pdf' => DOWNLOADDIR . 'JFOS_Clinic_Map.pdf',
          'JFOS_Benefit_Summary.pdf' => DOWNLOADDIR . 'JFOS_Benefit_Summary.pdf'
          );
        }
      } else if ($data['plan']['product_short'] == 'JESP') {
        if ($data['sendfrench']) {
          $files = array(
          'JESP_Policy.pdf' => DOWNLOADDIR . 'JESP_Policy_French.pdf',
          'JESP_Claim_Form.pdf' => DOWNLOADDIR . 'JESP_Claim_Form_French.pdf',
          'JESP_Brochure.pdf' => DOWNLOADDIR . 'JESP_Brochure_French.pdf'
          );
        } else {
        $data['special_note'] = $this->load->view('plan/pdf_note_jes',$data, TRUE);
        $files = array(
        'JESP_Policy.pdf' => DOWNLOADDIR . 'JESP_Policy.pdf',
        'JESP_Claim_Form.pdf' => DOWNLOADDIR . 'JESP_Claim_Form.pdf',
        'JESP_Brochure.pdf' => DOWNLOADDIR . 'JESP_Brochure.pdf'
        );
        }
      } else if ($data['plan']['product_short'] == 'JFC') {
        $data['insurable_options'] = $this->load->view('plan/detail_jes', $data, TRUE);
        $data['special_note'] = $this->load->view('plan/pdf_note_jfc',$data, TRUE);
        $files = array(
        'JFC_Policy.pdf' => DOWNLOADDIR . 'JFC_Policy.pdf',
        'JFC_Claim_Form.pdf' => DOWNLOADDIR . 'JFC_Claim_Form.pdf',
        'JFC_Brochure.pdf' => DOWNLOADDIR . 'JFC_Brochure.pdf'
        );
      } else if ($data['plan']['product_short'] == 'JFP') {
        $data['insurable_options'] = $this->load->view('plan/detail_jes', $data, TRUE);
        $data['special_note'] = $this->load->view('plan/pdf_note_jfc',$data, TRUE);
        $files = array(
        'JFP_Policy.pdf' => DOWNLOADDIR . 'JFP_Policy.pdf',
        'JFP_Claim_Form.pdf' => DOWNLOADDIR . 'JFP_Claim_Form.pdf',
        'JFP_Brochure.pdf' => DOWNLOADDIR . 'JFP_Brochure.pdf'
        );
      } else if ($data['plan']['product_short'] == 'TOPN') {
				if ($data['sendfrench']) {
					$files = array(
						'TOPN_Policy.pdf' => DOWNLOADDIR . 'TOPN_Policy_French.pdf',
						'TOPN_Claim_Form.pdf' => DOWNLOADDIR . 'TOPN_Claim_Form_French.pdf',
						'TOPN_Brochure.pdf' => DOWNLOADDIR . 'TOPN_Brochure_French.pdf'
					);
				} else {
					$data['special_note'] = $this->load->view('plan/top/pdf_note_top', $data, TRUE);
					$files = array(
						'TOPN_Policy.pdf' => DOWNLOADDIR . 'TOPN_Policy.pdf',
						'TOPN_Claim_Form.pdf' => DOWNLOADDIR . 'TOPN_Claim_Form.pdf',
						'TOPN_Brochure.pdf' => DOWNLOADDIR . 'TOPN_Brochure.pdf'
					);
				}
			} else if ($data['plan']['product_short'] == 'TOP') {
        if ($data['sendfrench']) {
          $files = array(
          'TOP_Policy.pdf' => DOWNLOADDIR . 'TOP_Policy_French.pdf',
          'TOP_Baggage_Claim_Form.pdf' => DOWNLOADDIR . 'TOP_Baggage_Claim_Form_French.pdf',
          'TOP_Cancellation_Claim_Form.pdf' => DOWNLOADDIR . 'TOP_Cancellation_Claim_Form_French.pdf',
          'TOP_Medical_Claim_Form.pdf' => DOWNLOADDIR . 'TOP_Medical_Claim_Form_French.pdf',
          'TOP_Benefit_Summary.pdf' => DOWNLOADDIR . 'TOP_Benefit_Summary_French.pdf',
          'TOP_Brochure.pdf' => DOWNLOADDIR . 'TOP_Brochure_French.pdf'
          );
        } else {
          $data['special_note'] = $this->load->view('plan/top/pdf_note_top',$data, TRUE);
          $files = array(
          'TOP_Policy.pdf' => DOWNLOADDIR . 'TOP_Policy.pdf',
          'TOP_Baggage_Claim_Form.pdf' => DOWNLOADDIR . 'TOP_Baggage_Claim_Form.pdf',
          'TOP_Cancellation_Claim_Form.pdf' => DOWNLOADDIR . 'TOP_Cancellation_Claim_Form.pdf',
          'TOP_Medical_Claim_Form.pdf' => DOWNLOADDIR . 'TOP_Medical_Claim_Form.pdf',
          'TOP_Benefit_Summary.pdf' => DOWNLOADDIR . 'TOP_Benefit_Summary.pdf'
          );
        }
      } else {
        $data['insurable_options'] = $this->load->view('plan/detail_other', $data, TRUE);
      }
      
      $policy_file = tempnam("/tmp", "Policy");
      //$policy_file = "C:\Users\Administrator\AppData\Local\Temp\Policy";
      $data['title_txt'] = 'Policy';
      $data['style'] = $this->load->view('common/pdf_style',$data, TRUE);
      $data['hadheaderfooter'] = 0;
      if ($data['plan']['product_short'] == 'JFVTC') {
        $mpdf = new mPDF('c', 'A4', 0, '', $mgl = 0, $mgr = 0, $mgt = 15, $mgb = 0, $mgh = 0, $mgf = 0, $orientation = 'P');
        if ($data['withlogo']) {
          $mpdf->SetHTMLHeader('<img style="width:100%;" src="'.base_url().'image/pdf_header.png" />');
        }
        $data['hadheaderfooter'] = 1;
        if ($data['sendfrench']) {
          $html = $this->load->view('plan/pdf_OR_visitor_french', $data, TRUE);
        } else {
          $html = $this->load->view('plan/pdf_jfvtc', $data, TRUE);
        }
      } else if (($data['plan']['product_short'] == 'JFPL') || ($data['plan']['product_short'] == 'JFGD')  || ($data['plan']['product_short'] == 'TCS') || ($data['plan']['product_short'] == 'JFOS') || ($data['plan']['product_short'] == 'JFSL')) {
        $mpdf = new mPDF('c', 'A4', 0, '', $mgl = 0, $mgr = 0, $mgt = 15, $mgb = 0, $mgh = 0, $mgf = 0, $orientation = 'P');
        if ($data['withlogo']) {
          $mpdf->SetHTMLHeader('<img style="width:100%;" src="'.base_url().'image/pdf_header.png" />');
        }
        // $mpdf->SetHTMLFooter('<img style="width:100%;" src="'.base_url().'image/pdf_footer.png" />');
        $data['hadheaderfooter'] = 1;
        if ($data['sendfrench']) {
          $html = $this->load->view('plan/pdf_OR_student_french', $data, TRUE);
        } else {
          $html = $this->load->view('plan/pdf', $data, TRUE);
        }
      } else {
        $mpdf = new mPDF('c');
        if ($data['sendfrench']) {
          if (($data['plan']['product_short'] == 'JES') || ($data['plan']['product_short'] == 'JESP') || ($data['plan']['product_short'] == 'JFS')) {
            $html = $this->load->view('plan/pdf_berkley_student_french', $data, TRUE);
          } else if ($data['plan']['product_short'] == 'JFR') {
            $html = $this->load->view('plan/pdf_jfr_french', $data, TRUE);
          } else if ($data['plan']['product_short'] == 'TOP') {
            $html = $this->load->view('plan/pdf_berkley_visitor_french', $data, TRUE);
          } else {
            $html = $this->load->view('plan/pdf', $data, TRUE);  
          }
        } else {
          $html = $this->load->view('plan/pdf', $data, TRUE);
        }
      }
      $mpdf->writeHTML($html);
      $mpdf->Output($policy_file, 'F');
      $this->load->model('mymail_model');
      if ($data['sendfrench']) {
        $body = $this->load->view('mail/package_french',$data, TRUE);
        $title = "Confirmation d’assurance - " . $plan['policy'] . " - " . $data['customer']['firstname'] . " " . $data['customer']['lastname'];
      } else {
        $body = $this->load->view('mail/package',$data, TRUE);
        $title = "Confirmation of Insurance - " . $plan['policy'] . " - " . $data['customer']['firstname'] . " " . $data['customer']['lastname'];
      }
      
      $files['policy_confirmation.pdf'] = $policy_file;
      $sendok = $this->mymail_model->send_mymail($data['emailaddr'], $title, $body, $files, $from='JF Insurance');
      unlink($policy_file);

      if ($sendok) {
        return $this->app_model->return_ok(array("message" => "OK"));
      } else {
        return $this->app_model->return_error("Something wrong with send email");
      }
    } else {
      return $this->app_model->return_error("Please input valid email address");
    }
		return $this->app_model->return_ok($data);
  }

	public function cancelprint() {
    $this->error = "";
    $this->load->model("app_model");
    $this->load->model("user_model");
    $this->load->helper('url');
    $this->lang->load('message');
    $this->lang->load('message', 'english');

    $user = $this->app_model->check_token($this->input->post("token"));

    if (empty($user)) {
      if (empty($this->error)) {
        $this->error = "Session Expired";
      }
      return $this->app_model->return_error($this->error);
    }
    if (($user["user_group_id"] < 100) && ($bid = $this->input->post("bid"))) {
      $user = $this->user_model->get_user_by_id($bid);
      if (empty($user)) {
        return $this->app_model->return_error("Unknown agent");
      }
    }

    $plan_id = $this->input->post("plan_id");
		if (empty($plan_id)) {
      return $this->app_model->return_error("Unknown plan");
		}

		$beuser = $user;
		$data = array();
		$this->load->model('plan_model');
		$plan = $this->plan_model->get_plan_by_id($plan_id);
		if (empty($plan) && ($plan['status'] != 5)) {
      return $this->app_model->return_error("Unknown plan info");
		}
		$this->load->model('product_model');
		$this->load->model('payment_model');
		$product = $this->product_model->get_product($plan['product_short']);
		$payment = $this->payment_model->get_payment_by_id($plan['payment_id']);
		
		$data['beuser'] = $beuser;
		$data['plan'] = $plan;

		$total_amount = (float)$payment['amount'] * (-1);
		$admin_fee = (float)$payment['admin_fee'];
		$refund_amount = $total_amount + $admin_fee;

		$this->load->model('status_model');
		$data['status_list'] = $this->status_model->status_list();
		$data['refund_amount'] = $refund_amount;
		$data['admin_fee'] = $admin_fee;
		$data['total_amount'] = $total_amount;
				
		$this->load->model('customer_model');
    $this->load->model('html_model');
		$data['customer'] = $this->customer_model->get_customer_by_id($data['plan']['customer_id']);
		$data['customers'] = $this->customer_model->get_customer_by_parent_id($data['plan']['customer_id']);
		$data['html_model'] = $this->html_model;
		if ($data['plan']['product_short'] == 'OPL') {
			$data['insurable_options'] = $this->load->view('plan/detail_opl', $data, TRUE);
		} else if (($data['plan']['product_short'] == 'JFVTC') || ($data['plan']['product_short'] == 'JFR')) {
			$data['insurable_options'] = $this->load->view('plan/detail_opl', $data, TRUE);
		} else if ($data['plan']['product_short'] == 'JUS') {
			$data['insurable_options'] = $this->load->view('plan/detail_jus', $data, TRUE);
		} else if ($data['plan']['product_short'] == 'NUS') {
			$data['insurable_options'] = $this->load->view('plan/detail_jus', $data, TRUE);
		} else if (($data['plan']['product_short'] == 'JFS') || ($data['plan']['product_short'] == 'JFE') || ($data['plan']['product_short'] == 'BHS')) {
			$data['insurable_options'] = $this->load->view('plan/detail_jes', $data, TRUE);
		} else if (($data['plan']['product_short'] == 'JES') || ($data['plan']['product_short'] == 'JFPL') || ($data['plan']['product_short'] == 'JFSL') || ($data['plan']['product_short'] == 'JFGD') || ($data['plan']['product_short'] == 'TCS') || ($data['plan']['product_short'] == 'JFOS')) {
			$data['insurable_options'] = $this->load->view('plan/detail_jes', $data, TRUE);
		} else if ($data['plan']['product_short'] == 'JESP') {
			$data['insurable_options'] = $this->load->view('plan/detail_jes', $data, TRUE);
		} else if ($data['plan']['product_short'] == 'JFC') {
			$data['insurable_options'] = $this->load->view('plan/detail_jes', $data, TRUE);
		} else if ($data['plan']['product_short'] == 'JFP') {
			$data['insurable_options'] = $this->load->view('plan/detail_jes', $data, TRUE);
		} else if ($data['plan']['product_short'] == 'TOPN') {
			$data['insurable_options'] = $this->load->view('plan/detail_jes', $data, TRUE);
		} else if ($data['plan']['product_short'] == 'TOP') {
			$data['insurable_options'] = $this->load->view('plan/detail_jes', $data, TRUE);
		} else {
			$data['insurable_options'] = $this->load->view('plan/detail_other', $data, TRUE);
		}

		$data['insure_co'] = 'Allianz Travel Insurance Coordinators Ltd';
		if (($plan['product_short'] == 'JFR') || ($plan['product_short'] == 'JES') || ($plan['product_short'] == 'JESP') || ($plan['product_short'] == 'JFS') || ($plan['product_short'] == 'JFE') || ($plan['product_short'] == 'BHS')) {
			$data['insure_co'] = "Berkley Canada";
		} else if ($plan['product_short'] == 'JFVTC') {
      $data['insure_co'] = "Old Republic";
    }
		$data['style'] = $this->load->view('common/pdf_style',$data, TRUE);
		if (($data['plan']['product_short'] == 'TOP') || ($data['plan']['product_short'] == 'TOPN')) {
      $html = $this->load->view('plan/top/cancel', $data, TRUE);
    } else {
      $html = $this->load->view('plan/cancel', $data, TRUE);
    }
		$mpdf = new mPDF('c');
    $this->output_heads();
    $mpdf->autoLangToFont=true;
    $mpdf->autoScriptToLang=true;
		$mpdf->writeHTML($html);
		$mpdf->Output("policy_cancel.pdf","I");
	}
	
	public function refundprint() {
    $this->error = "";
    $this->load->model("app_model");
    $this->load->model("user_model");
    $this->load->helper('url');
    $this->lang->load('message');
    $this->lang->load('message', 'english');

    $user = $this->app_model->check_token($this->input->post("token"));

    if (empty($user)) {
      if (empty($this->error)) {
        $this->error = "Session Expired";
      }
      return $this->app_model->return_error($this->error);
    }
    if (($user["user_group_id"] < 100) && ($bid = $this->input->post("bid"))) {
      $user = $this->user_model->get_user_by_id($bid);
      if (empty($user)) {
        return $this->app_model->return_error("Unknown agent");
      }
    }

    $plan_id = $this->input->post("plan_id");
		if (empty($plan_id)) {
      return $this->app_model->return_error("Unknown plan");
		}

		$beuser = $user;
		$this->load->model('plan_model');
		$plan = $this->plan_model->get_plan_by_id($plan_id);
		if (empty($plan)) {
      return $this->app_model->return_error("Unknown plan info");
		}
		$this->load->model('product_model');
		$this->load->model('payment_model');
		$product = $this->product_model->get_product($plan['product_short']);
		$payment = $this->payment_model->get_payment_by_id($plan['payment_id']);

		$data['beuser'] = $beuser;
		$data['plan'] = $plan;
		$data['refundprint_url'] = base_url ( "plan/refundprint" ) . "/" . $plan_id;
		
		$total_amount = (float)$payment['amount'] * (-1);
		$admin_fee = (float)$payment['admin_fee'];
		$refund_amount = $total_amount + $admin_fee;
		
		$this->load->model('status_model');
		$data['status_list'] = $this->status_model->status_list();
		$data['refund_amount'] = $refund_amount;
		$data['admin_fee'] = $admin_fee;
		$data['total_amount'] = $total_amount;
    $data['street_number'] = '';
    $data['street_name'] = '';
		
		$this->load->model('customer_model');
    $this->load->model('html_model');
		$data['customer'] = $this->customer_model->get_customer_by_id($data['plan']['customer_id']);
		$data['customers'] = $this->customer_model->get_customer_by_parent_id($data['plan']['customer_id']);
		$data['html_model'] = $this->html_model;
		if ($data['plan']['product_short'] == 'OPL') {
			$data['insurable_options'] = $this->load->view('plan/detail_opl', $data, TRUE);
		} else if (($data['plan']['product_short'] == 'JFVTC') || ($data['plan']['product_short'] == 'JFR')) {
			$data['insurable_options'] = $this->load->view('plan/detail_opl', $data, TRUE);
		} else if ($data['plan']['product_short'] == 'JUS') {
			$data['insurable_options'] = $this->load->view('plan/detail_jus', $data, TRUE);
		} else if ($data['plan']['product_short'] == 'NUS') {
			$data['insurable_options'] = $this->load->view('plan/detail_jus', $data, TRUE);
		} else if (($data['plan']['product_short'] == 'JFS') || ($data['plan']['product_short'] == 'JFE') || ($data['plan']['product_short'] == 'BHS')) {
			$data['insurable_options'] = $this->load->view('plan/detail_jes', $data, TRUE);
		} else if (($data['plan']['product_short'] == 'JES') || ($data['plan']['product_short'] == 'JFPL') || ($data['plan']['product_short'] == 'JFSL') || ($data['plan']['product_short'] == 'JFGD') || ($data['plan']['product_short'] == 'TCS') || ($data['plan']['product_short'] == 'JFOS')) {
			$data['insurable_options'] = $this->load->view('plan/detail_jes', $data, TRUE);
		} else if ($data['plan']['product_short'] == 'JESP') {
			$data['insurable_options'] = $this->load->view('plan/detail_jes', $data, TRUE);
		} else if ($data['plan']['product_short'] == 'JFC') {
			$data['insurable_options'] = $this->load->view('plan/detail_jes', $data, TRUE);
		} else if ($data['plan']['product_short'] == 'JFP') {
			$data['insurable_options'] = $this->load->view('plan/detail_jes', $data, TRUE);
		} else if ($data['plan']['product_short'] == 'TOPN') {
			$data['insurable_options'] = $this->load->view('plan/detail_jes', $data, TRUE);
		} else if ($data['plan']['product_short'] == 'TOP') {
			$data['insurable_options'] = $this->load->view('plan/detail_jes', $data, TRUE);
		} else {
			$data['insurable_options'] = $this->load->view('plan/detail_other', $data, TRUE);
		}

		//print_r($this->input->post());die('====');

		if ($this->input->post('customer_full_name') || ($data['plan']['product_short'] == 'TOP') || ($data['plan']['product_short'] == 'TOPN')) {
			$data['insure_co'] = 'Allianz Travel Insurance Coordinators Ltd';
			if (($plan['product_short'] == 'JFR') || ($plan['product_short'] == 'JES') || ($plan['product_short'] == 'JFPL') || ($plan['product_short'] == 'JESP') || ($plan['product_short'] == 'JFS') || ($plan['product_short'] == 'JFE') || ($plan['product_short'] == 'BHS')) {
				$data['insure_co'] = "Berkley Canada";
			} else if (($plan['product_short'] == 'JFVTC') || ($data['plan']['product_short'] == 'JFSL') || ($data['plan']['product_short'] == 'JFGD') || ($data['plan']['product_short'] == 'TCS') || ($data['plan']['product_short'] == 'JFOS')) {
        $data['insure_co'] = "Old Republic";
      }
			$data['customer_full_name'] = $this->input->post('customer_full_name');
			$data['full_address'] = $this->input->post('full_address');
			$data['city'] = $this->input->post('city');
			$data['province2'] = $this->input->post('province2');
			$data['postcode'] = $this->input->post('postcode');
			$data['style'] = $this->load->view('common/pdf_style',$data, TRUE);
      if (($data['plan']['product_short'] == 'TOP') || ($data['plan']['product_short'] == 'TOPN')) {
        $html = $this->load->view('plan/top/cancel', $data, TRUE);
      } else {
        $html = $this->load->view('plan/refund', $data, TRUE);
      }
		} else {
			$data['customer_full_name'] = $data['customer']['firstname'] . " " . $data['customer']['lastname'];
			$data['full_address'] = empty($plan['suite_number']) ? '' : "Suite " . $plan['suite_number'] . " ";
			$data['full_address'] .= $plan['street_number'] . ' ' . $plan['street_name'];
			$data['city'] = $plan['city'];
			$data['province2'] = $plan['province2'];
			$data['postcode'] = $plan['postcode'];

			$html = $this->load->view('plan/refund_addr', $data, TRUE);
		}
    $mpdf = new mPDF('c');
    $this->output_heads();
    $mpdf->autoLangToFont=true;
    $mpdf->autoScriptToLang=true;
    $mpdf->writeHTML($html);
    $mpdf->Output("policy_refund.pdf","I");
  }

  function get_activelog_history()
	{
    $this->error = "";
    $this->load->model("app_model");
    $this->load->model("user_model");
		$this->load->model('log_model');
    $user = $this->app_model->check_token($this->input->post("token"));

    if (empty($user)) {
      if (empty($this->error)) {
        $this->error = "Session Expired";
      }
      return $this->app_model->return_error($this->error);
    }
    if ($user["user_group_id"] > 100) {
      return $this->app_model->return_error("Unknown user");
    }
		$plan_id = $this->input->post('plan_id');
    if (empty($plan_id)) {
      return $this->app_model->return_error("Unknown Policy");
    }

		$tb = $this->input->post('tb');
    if (empty($tb)) {
      $tb = 'activity';
    }
		$activelog_tables = $this->log_model->history_tables;

		$rt = [];
		if (in_array($tb, $activelog_tables)) {
			$records = $this->log_model->get_activity_by_plan_id_tb($plan_id, $tb);
			foreach ($records as $p) {
        $rt[] = array("username" => $p['username'], "tm" => $p['tm'], "message" => $p['message']);
			}
		}
		return $this->app_model->return_ok(array("tables" => $activelog_tables, "history" => $rt));
	}

	function get_payment_history()
	{
    $this->error = "";
    $this->load->model("app_model");
    $this->load->model("user_model");
    $this->load->model('payment_model');
    $user = $this->app_model->check_token($this->input->post("token"));

    if (empty($user)) {
      if (empty($this->error)) {
        $this->error = "Session Expired";
      }
      return $this->app_model->return_error($this->error);
    }
    if ($user["user_group_id"] > 100) {
      return $this->app_model->return_error("Unknown user");
    }
		$plan_id = $this->input->post('plan_id');
    if (empty($plan_id)) {
      return $this->app_model->return_error("Unknown Policy");
    }

		$tb = $this->input->post('tb');
    if (empty($tb)) {
      $tb = 'payment';
    }
		$payment_tables = $this->payment_model->history_tables;
		$rt = [];
		if (in_array($tb, $payment_tables)) {
			$payments = $this->payment_model->get_payment_by_plan_id_tb($plan_id, $tb);
			foreach ($payments as $p) {
				$pay_str = '';
				if ($p['pay_type'] == 'up_commission') continue;
				if ($p['pay_type'] == 'refund_up_commission') continue;
				if ($p['pay_type'] == 'cancel_up_commission') continue;

				$sbstr = substr($p['pay_type'], 0, 6);
				if ($p['ispaid']) {
					$pay_str = 'Paid';
				} else {
					if ($sbstr == 'refund') {
            $pay_str = 'Refund';
						// $pay_str = "<a href='" . base_url("payment/revert") . "/" . $p['payment_id'] . "'>Revert Refund</a>";
					} else if ($sbstr == 'cancel') {
            $pay_str = 'Cancel';
						// $pay_str = "<a href='" . base_url("payment/revert") . "/" . $p['payment_id'] . "'>Revert Cancel</a>";
					} else {
						$pay_str = '-';
					}
				}
				$pay_info = '';
				$ck_info = $p['cheque_number'];
				if ($p['pay_date'] > "2020-01-01") $ck_info .= ":" . $p['pay_date'];
				if (!empty($p['invoice_num'])) $pay_info .= "[" . $p['invoice_num'] . "]";
				if (!empty($p['bank_name'])) $pay_info .= "[" . $p['bank_name'] . "]";
				if (!empty($p['payor_name'])) $pay_info .= "[" . $p['payor_name'] . "]";
				if (!empty($p['cheque_number'])) $pay_info .= "[" . $p['cheque_number'] . "]";
				if (!empty($p['pay_to'])) $pay_info .= "[" . $p['pay_to'] . "]";
				if (!empty($p['name'])) $pay_info .= "[" . $p['name'] . "]";
				if (!empty($p['first5'])) $pay_info .= "[" . $p['first5'] . "]";
				if (!empty($p['last4'])) $pay_info .= "[" . $p['last4'] . "]";
				if (!empty($p['expiry_month'])) $pay_info .= "[" . $p['expiry_month'] . "]";
				if (!empty($p['expiry_year'])) $pay_info .= "[" . $p['expiry_year'] . "]";

        $rt[] = [
				// $rt .= "<td>" . (empty($p['ispaid']) ? "<input type='checkbox' name='payment[]' value='" . $p['payment_id'] . "'>" : "") . "</td>\n";
          'payment_id' => $p['payment_id'],
          'last_update' => $p['last_update'],
          'pay_type' => $p['pay_type'],
          'pay_mothed' => $p['pay_mothed'],
          'amount' => $p['amount'],
          'rate' => $p['rate'],
          'pay_info' => $pay_str,
          'check_info' => $ck_info,
          'pay_detail' => $pay_info,
          'note' => $p['note'],
        ];
      }
		}
		return $this->app_model->return_ok(array("tables" => $payment_tables, "history" => $rt));
	}
}
