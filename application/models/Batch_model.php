<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Batch_model extends CI_Model {
	public $error;
	const PROCESSING=0;
	const FINISHED=1;
	const ERROR=2;
	
	function unixstamp( $excelDateTime ) {
		if (!is_string($excelDateTime)) {
			if (get_class($excelDateTime) == 'DateTime') {
				return $excelDateTime->getTimestamp();
			} else {
				return 0;
			}
		}
		$excelDateTime =trim($excelDateTime);
		if (is_numeric($excelDateTime)) {
			$t = $excelDateTime - 25569; // seconds since 1900
			if ($t > 0) {
				return round($t * 86400);
			} else {
				return 0;
			}
		} else {
			return strtotime($excelDateTime);
		}
	}
	
	/**
	 * Add trasaction records base on plan and product data
	 * 
	 */
	private function add_payment($plan_id, $changeval = 0) {
		$this->load->model('plan_model');
		$this->load->model('product_model');
		$this->load->model('payment_model');
		
		$payinfo = "Batch Upload; ";
		
		$plan = $this->plan_model->get_plan_by_id($plan_id);
		if (empty($plan)) {
			return FALSE;
		}
		$product = $this->product_model->get_product($plan['product_short']);
		if (empty($product)) {
			return FALSE;
		}
		if (empty($changeval)) {
			$premium = $plan['premium'];
		} else {
			$premium = $changeval;
		}
		
		$dt = array();
		$dt['plan_id'] = $plan_id;
		$dt['amount'] = $premium;
		$dt['pay_type'] = 'premium';
		$dt['currency'] = $product['currency'];
		$dt['pay_mothed'] = 'Cash';
		$dt['added'] = date('c');
		$dt['note'] = $payinfo;
		$dt['ispaid'] = 0;
		
		$commission_rate = $this->product_model->get_commission_rate($plan['product_short'], $plan['user_id']);
		$commission_amount = $premium * $commission_rate / 100.0;
		$up_commission_rate = $this->product_model->get_up_commission_rate($plan['product_short']);
		$up_commission_amount = $premium * $up_commission_rate / 100.0;
		
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
		$premium = $dt['amount'];	// Adjust amount if it was paid
		
		// up commission
		$dt['amount'] = $up_commission_amount;
		$dt['rate'] = $up_commission_rate;
		$dt['pay_type'] = 'up_commission';
		$dt['premium_payment_id'] = $payment_id;
		$up_commission_payment_id = $this->payment_model->add($dt);
		$para = array(
				'plan_id' => $plan_id,
				'customer_id' => $plan['customer_id'],
				'payment_id' => $up_commission_payment_id,
				'message' => $this->payment_model->logstr,
				'systemlog' => $this->payment_model->sqlstr
		);
		$this->log_model->activity('up_commission', $para);
		
		// commission
		$dt['amount'] = $commission_amount;
		$dt['rate'] = $commission_rate;
		$dt['pay_type'] = 'commission';
		$dt['premium_payment_id'] = $payment_id;
		if (($plan['product_short'] == 'OPL') || ($plan['product_short'] == 'JFR') && ($premium > 100000)) {
			$dt['added'] = $plan['effective_date'];
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
		
		$para = array('payment_id' => $payment_id, 'payinfo' => $payinfo, 'commission_payment_id' => $commission_payment_id, 'status_id' => 2, 'policy' => $this->plan_model->get_policy_number($plan_id, 2));
		$this->plan_model->update($plan_id, $para);
		$para = array(
				'plan_id' => $plan_id,
				'customer_id' => $plan['customer_id'],
				'payment_id' => $payment_id,
				'message' => $this->plan_model->logstr,
				'systemlog' => $this->plan_model->sqlstr
		);
		$this->log_model->activity('plan', $para);
		return TRUE;
	}

	/**
	 * Add / Update policy record
	 * 
	 * @param	array	$para	Parameter array
	 * @return	array					user table search result
	 */
	public function add_record($para) {
		$this->load->model('plan_model');
		$this->load->model('customer_model');
		$this->load->model('product_model');
		$beuser = $this->session->userdata ( 'beuser' );

		$data = array();

		if (empty($para['customer_id'])) { ; } else { $data['customer_id'] = $para['customer_id']; }
		if (empty($para['user_id'])) { ; } else { $data['user_id'] = $para['user_id']; }
		if (empty($para['status_id'])) { $data['status_id'] = 2; } else { $data['status_id'] = $para['status_id']; }
		if (empty($para['product_short'])) { 
			$this->error = 'Need product_short';
			return 0;
		}
		$data['product_short'] = $para['product_short']; 
		$product = $this->product_model->get_product($para['product_short']);
		if (empty($product)) {
			$this->error = 'Unknown product_short';
			return 0;
		}

		if (empty($para['policy'])) {
			$data['policy'] = '';
			if ($product['calculate'] != 1) {
				$this->error = 'Unknow policy';
				return 0;
			}
		} else { 
			$data['policy'] = $para['policy'];
		}
		$data['batch_number'] = $para['batch_number'];
		if (empty($para['isfamilyplan'])) { $data['isfamilyplan'] = 0; } else { $data['isfamilyplan'] = $para['isfamilyplan']; }
		if (empty($para['apply_date'])) { $data['apply_date'] = date('Y-m-d'); } else { $data['apply_date'] = $para['apply_date']; }
		if (empty($para['arrival_date'])) {
			$this->error = 'Need Arrival Date.';
			return 0;
		} else {
			$dt = $this->unixstamp($para['arrival_date']);
			if (empty($dt)) {
				$this->error = 'Unknown arrival_date';
				return 0;
			} else {
				$data['arrival_date'] = date('Y-m-d', $dt);
			}
		}
		if (empty($para['effective_date'])) {
			$this->error = 'Need Effective Date.';
			return 0;
		} else {
			$dt = $this->unixstamp($para['effective_date']);
			if (empty($dt)) {
				$this->error = 'Unknown effective_date';
				return 0;
			} else {
				$data['effective_date'] = date('Y-m-d', $dt);
			}
		}
		if (empty($para['expiry_date'])) {
			$this->error = 'Need Expiry Date.';
			return 0;
		} else {
			$dt = $this->unixstamp($para['expiry_date']);
			if (empty($dt)) {
				$this->error = 'Unknown expiry_date';
				return 0;
			} else {
				$data['expiry_date'] = date('Y-m-d', $dt);
			}
		}
		if (isset($para['beneficiary'])) { $data['beneficiary'] = $para['beneficiary']; } else { $this->error = 'Unknown beneficiary.'; return 0; }
		if (isset($para['stable_condition'])) { $data['stable_condition'] = $para['stable_condition']; } else { ; }
		if (isset($para['rate_options'])) { $data['rate_options'] = $para['rate_options']; } else { ; }
		if (isset($para['sum_insured'])) { $data['sum_insured'] = $para['sum_insured']; } else { ; }
		if (isset($para['deductible_amount'])) { $data['deductible_amount'] = $para['deductible_amount']; } else { ; }
		if (isset($para['dailyrate'])) { $data['dailyrate'] = $para['dailyrate']; } else { ; }
		if (isset($para['totaldays'])) { $data['totaldays'] = $para['totaldays']; } else { ; }
		if (isset($para['totalyears'])) { $data['premium'] = $para['totalyears']; } else { ; }
		if (isset($para['premium'])) { $data['premium'] = $para['premium']; } else { ; }
		if (isset($para['commission_amount'])) { $data['commission_amount'] = $para['commission_amount']; } else { ; }
		if (isset($para['street_number'])) { $data['street_number'] = $para['street_number']; } else { ; }
		if (isset($para['street_name'])) { $data['street_name'] = $para['street_name']; } else { ; }
		if (isset($para['suite_number'])) { $data['suite_number'] = $para['suite_number']; } else { ; }
		if (isset($para['city'])) { $data['city'] = $para['city']; } else { ; }
		if (isset($para['province2'])) { $data['province2'] = $para['province2']; } else { ; }
		if (isset($para['country2'])) { $data['country2'] = $para['country2']; } else { ; }
		if (isset($para['postcode'])) { $data['postcode'] = $para['postcode']; } else { ; }
		if (isset($para['phone1'])) { $data['phone1'] = $para['phone1']; } else { ; }
		if (isset($para['phone2'])) { $data['phone2'] = $para['phone2']; } else { ; }
		if (isset($para['institution'])) { $data['institution'] = $para['institution']; } else { ; }
		if (isset($para['institution_addr'])) { $data['institution_addr'] = $para['institution_addr']; } else { ; }
		if (isset($para['student_id'])) { $data['student_id'] = $para['student_id']; } else { ; }
		if (isset($para['institution_phone'])) { $data['institution_phone'] = $para['institution_phone']; } else { ; }
		if (isset($para['institution_fax'])) { $data['institution_fax'] = $para['institution_fax']; } else { ; }
		if (isset($para['contact_email'])) { $data['contact_email'] = $para['contact_email']; } else { ; }
		if (isset($para['contact_phone'])) { $data['contact_phone'] = $para['contact_phone']; } else { ; }
		if (isset($para['residence'])) { $data['residence'] = $para['residence']; } else { ; }
		if (isset($para['payinfo'])) { $data['payinfo'] = $para['payinfo']; } else { ; }
		$data['note'] = 'Batch upload';
		$data['ip'] = $_SERVER['REMOTE_ADDR'];

		if (empty($para['firstname'])) { $data['firstname'] = ''; } else { $data['firstname'] = $para['firstname']; }
		if (empty($para['lastname'])) { $data['lastname'] = ''; } else { $data['lastname'] = $para['lastname']; }
		if (empty($para['gender'])) { $data['gender'] = 'M'; } else { $data['gender'] = $para['gender']; }
		if (empty($para['birthday'])) {
			$data['birthday'] = '';
		} else {
			$dt = $this->unixstamp($para['birthday']);
			if (empty($dt)) {
				$data['birthday'] = '';
			} else {
				$data['birthday'] = date('Y-m-d', $dt);
			}
		}
		
		for ($i = 1; $i < 9; $i++) {
			if (empty($para['firstname_'.$i])) { $data['firstname_'.$i] = ''; } else { $data['firstname_'.$i] = $para['firstname_'.$i]; }
			if (empty($para['lastname_'.$i])) { $data['lastname_'.$i] = ''; } else { $data['lastname_'.$i] = $para['lastname_'.$i]; }
			if (empty($para['gender_'.$i])) { $data['gender_'.$i] = 'M'; } else { $data['gender_'.$i] = $para['gender_'.$i]; }
			if (empty($para['birthday_'.$i])) {
				$data['birthday_'.$i] = '';
			} else {
				$dt = $this->unixstamp($para['birthday_'.$i]);
				if (empty($dt)) {
					$data['birthday_'.$i] = '';
				} else {
					$data['birthday_'.$i] = date('Y-m-d', $dt);
				}
			}
		}
		
		if (isset($para['plan_id'])) {
			$plan = $this->plan_model->get_plan_by_id($para['plan_id']);
		}
		if (empty($plan)) {
			// Add
			$plan_id = $this->plan_model->add($data);
			if (!empty($data['batch_number']) && empty($data['policy'])) {
				$policy = $this->plan_model->get_policy_number($plan_id, 2);
				$sql  = "UPDATE plan SET policy=" . $this->db->escape($policy) . " WHERE plan_id='" . (int)$plan_id . "'";
				$this->db->query($sql);
			}
			$this->add_payment($plan_id);
		} else {
			$plan_id = $this->plan_model->update($para['plan_id'], $data);
			if ($data['premium'] != $plan['premium']) {
				$this->add_payment($plan_id, (float)$data['premium'] - (float)$plan['premium']);
			}
		}
		return $plan_id;
	}
	
	/**
	 * Get Batch Number
	 * 
	 * @param string	$name
	 * @param string	$memo
	 * @return string	batch number
	 */
	public function get_batch_number($name, $memo) {
		$data = array(
				'name' => $name, 
				'memo' => $memo
		);
		$this->db->insert('batch', $data);
		$id = $this->db->insert_id();
		return $id;
	}
	
	/**
	 * Get Batch Number Status
	 * 
	 * @param int	$batch_number	table id
	 * @return int	batch status
	 */
	public function get_batch_status($batch_number) {
		$this->db->where('batch_number', $batch_number);
		return $this->db->get('batch')->row_array();
	}
	
	/**
	 * Set Batch Number Status
	 * 
	 * @param int	$batch_number	table id
	 * @param int	$status			Job satus
	 * @param string	$memo
	 * @return int	batch status
	 */
	public function set_batch_status($batch_number, $status, $memo='') {
		$this->db->set('status', $status);
		if (!empty($memo)) $this->db->set('memo', $memo);
		$this->db->where('batch_number', $batch_number);
		$this->db->update('batch');
	}
	
	/**
	 * Batch update payment status
	 */
	public function batch_pay($batch_number, $payarr, $pay_type) {
		$this->load->model('payment_model');
		$this->load->model('plan_model');
		
		$this->db->where('batch_number', $batch_number);
		$plans = $this->db->get('plan')->result_array();
		$beuser = $this->session->userdata ( 'beuser' );
		
		if ($plans) {
			foreach ($plans as $plan) {
				$payments = $this->payment_model->get_payment_by_plan_id($plan['plan_id']);
				if ($payments) {
					foreach ($payments as $payment) {
						if ($payment['pay_type'] != $pay_type) continue;
						$payarr['note'] = "Make pay by " . $this->session->userdata ( 'user' )['username'] . "; " . $payment['note'];
						$this->payment_model->update($payment['payment_id'], $payarr);
						$note = 'Mark pay by: ' . $beuser['username'] . "; " . $plan['note'];
						if (($pay_type == 'premium') && ($plan['status_id'] == Plan_model::SOLD)) {
							$unpaied = $this->payment_model->get_payment($plan['plan_id'], 'premium', 0);
							if (empty($unpaied)) {
								$para = array('note' => $note, 'status_id' => Plan_model::PAID);
								$this->plan_model->update($plan['plan_id'], $para);
								$para = array(
										'plan_id' => $plan['plan_id'],
										'customer_id' => $plan['customer_id'],
										'payment_id' => $plan['payment_id'],
										'message' => $this->plan_model->logstr,
										'systemlog' => $this->plan_model->sqlstr
								);
								$this->log_model->activity('plan', $para);
							}
						}
					}
				}
			}
		}
	}
}
