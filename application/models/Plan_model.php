<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Plan_model extends CI_Model {
    const QUOTE = 1;
    const SOLD = 2;
    const PAID = 3;
    const CLAIMED = 4;
    const CANCEL = 5;
    const REFUND = 6;
    const CHANGED = 7;
	
    public $logstr;
	public $sqlstr;
	
	/**
	 * Get Plan current policy number
	 * 
	 * @param	integer	$plan_id		Parameters
	 * @return	string					policy number
	 */
	public function get_plan_by_id($plan_id) {
		$sql = "SELECT * FROM plan WHERE plan_id='" . (int)$plan_id . "'";
		return $this->db->query($sql)->row_array();
	}
	
	/**
	 * Get Plan Key
	 * 
	 * @param	integer	$plan_id		Parameters
	 * @return	string					policy number
	 */
	public function get_plan_key($plan_id) {
		$plan = $this->get_plan_by_id($plan_id);
		$key = md5('jfuk0621' . $plan_id . $plan['user_id'] . $plan['customer_id']);
		return $key;
	}
	
	/**
	 * Get Plan refund amount
	 * 
	 * @param	integer	$plan_id		Parameters
	 * @return	float
	 */
	public function refund_amount($plan_id, $refund_date) {
		$plan = $this->get_plan_by_id($plan_id);
		if ($plan) {
			$stm = strtotime($refund_date);
			$etm = strtotime($plan['expiry_date']);
			if (empty($stm) || empty($etm) || ($stm > $etm)) {
				return 0;
			}
			$premium = $plan['premium'];

			$this->load->model('product_model');
			$this->load->model('customer_model');
			$number_customer = $this->customer_model->get_number_customer($plan['customer_id'], $plan['isfamilyplan']);
			$birthday = $this->customer_model->get_max_birthday($plan['customer_id'], $plan['isfamilyplan'], $plan['product_short']);
			$para = array(
						'product_short' => $plan['product_short'],
						'apply_date' => $plan['apply_date'],
						'effective_date' => $plan['effective_date'],
						'expiry_date' => $refund_date,
						'isfamilyplan' => $plan['isfamilyplan'],
						'number_customer' => $number_customer,
						'sum_insured' => $plan['sum_insured'],
						'deductible_amount' => $plan['deductible_amount'],
						'stable_condition' => $plan['stable_condition'],
						'rate_options' => $plan['rate_options'],
						'spouse' => $plan['spouse'],
						'holiday_rate' => $plan['holiday_rate'],
						'birthday' => $birthday);
			$rarr = $this->product_model->get_premium($para);
			if (isset($rarr['premium']) && ($rarr['premium'] > 0.01)) {
				return round($premium - $rarr['premium'], 2);
			}
		}
		return 0;
	}
	
	/**
	 * Get Plan current policy number
	 * 
	 * @param	integer	$plan_id		Parameters
	 * @param	integer	$status			status id for policy
	 * @return	string					policy number
	 */
	public function get_policy_number($plan_id, $status=0) {
		$plan = $this->get_plan_by_id($plan_id);
		if (empty($plan)) {
			return "Unknow1:";
		}
		$product_short = $plan['product_short'];

		$this->load->model('product_model');
		$product = $this->product_model->get_product($product_short);
		
		if ($product['calculate']) {
			if (empty($status)) {
				$status = $plan['status_id'];
			}
			if ($status <= 1) {
				return $product['qoute_pre'] . sprintf("%06d", $plan_id);
			} else {
				return $product['plan_pre'] . sprintf("%06d", $plan_id);
			}
		} else {
			return $plan['policy'];
		}
	}
	
	/**
	 * Create plan
	 * 
	 * @param	array	$para		Parameters
	 * @return	array					user table search result
	 */
	public function add($para) {
		$this->load->model('customer_model');
		$beuser = $this->session->userdata ( 'beuser' );
		if (empty($beuser)) {
			return 0;
		}
		if ((($para['product_short'] == 'NUS') || ($para['product_short'] == 'JUS')) && isset($para['rate_options']) && ($para['rate_options'] == 2)) {
			$para['deductible_amount'] = 50;
		}
		$isfamilyplan = empty($para['isfamilyplan']) ? 0 : 1;
		$cpara = array(
				'plan_id' => 0,
				'gender' => $para['gender'],
				'firstname' => $para['firstname'],
				'lastname' => $para['lastname'],
				'birthday' => $para['birthday'],
				'parent_customer_id' => 0
		);
		$customer_id = $this->customer_model->add($cpara);
		$this->sqlstr = $this->customer_model->sqlstr . "; ";
		$this->logstr = $this->customer_model->logstr . "; ";
		
		$sql  = "INSERT INTO plan SET";
		$sql .= " customer_id='" . (int)$customer_id . "', ";
		$sql .= " user_id='" . (int)$beuser['user_id'] . "', ";
		$status_id = empty($para['status_id']) ? 1 : (int)$para['status_id'];
		$sql .= " status_id='" . (int)$status_id . "', ";
		$sql .= " product_short=" . $this->db->escape($para['product_short']) . ", ";
		$sql .= " region_id=" . (int)$beuser['region_id'] . ", ";
		if (isset($para['policy'])) $sql .= " policy=" . $this->db->escape($para['policy']) . ", ";
		if (isset($para['batch_number'])) $sql .= " batch_number=" . $this->db->escape($para['batch_number']) . ", ";
		$sql .= " isfamilyplan='" . (int)$isfamilyplan . "', ";
		if (isset($para['apply_date'])) $sql .= " apply_date=" . $this->db->escape($para['apply_date']) . ", ";
		if (isset($para['arrival_date'])) $sql .= " arrival_date=" . $this->db->escape($para['arrival_date']) . ", ";
		if (isset($para['effective_date'])) $sql .= " effective_date=" . $this->db->escape($para['effective_date']) . ", ";
		if (isset($para['expiry_date'])) $sql .= " expiry_date=" . $this->db->escape($para['expiry_date']) . ", ";
		if (isset($para['beneficiary'])) $sql .= " beneficiary=" . $this->db->escape($para['beneficiary']) . ", ";
		if (isset($para['stable_condition'])) $sql .= " stable_condition='" . (int)$para['stable_condition'] . "', ";
		if (isset($para['rate_options'])) $sql .= " rate_options='" . (int)$para['rate_options'] . "', ";
		if (isset($para['holiday_rate'])) $sql .= " holiday_rate='" . (int)$para['holiday_rate'] . "', ";
		if (isset($para['spouse'])) $sql .= " spouse='" . (int)$para['spouse'] . "', ";
		if (isset($para['sum_insured'])) $sql .= " sum_insured='" . (int)$para['sum_insured'] . "', ";
		if (isset($para['deductible_amount'])) $sql .= " deductible_amount='" . (int)$para['deductible_amount'] . "', ";
		if (isset($para['totaldays'])) $sql .= " totaldays='" . (int)$para['totaldays'] . "', ";
		if (isset($para['totalyears'])) $sql .= " totalyears='" . (int)$para['totalyears'] . "', ";
		if (isset($para['dailyrate']))  $sql .= " dailyrate='" . (float) $para['dailyrate'] . "', ";
		if (isset($para['premium']))  $sql .= " premium='" . (float) $para['premium'] . "', ";
		if (isset($para['street_number'])) $sql .= " street_number=" . $this->db->escape(trim($para['street_number'])) . ", ";
		if (isset($para['street_name'])) $sql .= " street_name=" . $this->db->escape(trim($para['street_name'])) . ", ";
		if (isset($para['suite_number'])) $sql .= " suite_number=" . $this->db->escape(trim($para['suite_number'])) . ", ";
		if (isset($para['city'])) $sql .= " city=" . $this->db->escape(trim($para['city'])) . ", ";
		if (isset($para['province2'])) $sql .= " province2=" . $this->db->escape(trim($para['province2'])) . ", ";
		if (isset($para['country2'])) $sql .= " country2=" . $this->db->escape(trim($para['country2'])) . ", ";
		if (isset($para['postcode'])) $sql .= " postcode=" . $this->db->escape(trim($para['postcode'])) . ", ";
		if (isset($para['phone1'])) $sql .= " phone1=" . $this->db->escape(trim($para['phone1'])) . ", ";
		if (isset($para['phone2'])) $sql .= " phone2=" . $this->db->escape(trim($para['phone2'])) . ", ";
		if (isset($para['institution'])) $sql .= " institution=" . $this->db->escape(trim($para['institution'])) . ", ";
		if (isset($para['institution_addr'])) $sql .= " institution_addr=" . $this->db->escape(trim($para['institution_addr'])) . ", ";
		if (isset($para['student_id'])) $sql .= " student_id=" . $this->db->escape(trim($para['student_id'])) . ", ";
		if (isset($para['institution_phone'])) $sql .= " institution_phone=" . $this->db->escape(trim($para['institution_phone'])) . ", ";
		if (isset($para['institution_fax'])) $sql .= " institution_fax=" . $this->db->escape(trim($para['institution_fax'])) . ", ";
		if (isset($para['contact_email'])) $sql .= " contact_email=" . $this->db->escape(trim($para['contact_email'])) . ", ";
		if (isset($para['contact_phone'])) $sql .= " contact_phone=" . $this->db->escape(trim($para['contact_phone'])) . ", ";
		if (isset($para['residence'])) $sql .= " residence=" . $this->db->escape(trim($para['residence'])) . ", ";
		if (isset($para['payment_id'])) $sql .= " payment_id=" . (int)$para['payment_id'] . ", ";
		if (isset($para['commission_payment_id'])) $sql .= " commission_payment_id=" . (int)$para['commission_payment_id'] . ", ";
		if (isset($para['payinfo'])) $sql .= " payinfo=" . $this->db->escape(trim($para['payinfo'])) . ", ";
		if (isset($para['note'])) $sql .= " note=" . $this->db->escape(trim($para['note'])) . ", ";
		$sql .= " ip=" . $this->db->escape($_SERVER['REMOTE_ADDR']) . ", ";
		$sql .= " commission_amount='0' ";
		$this->db->query($sql);
		$plan_id = $this->db->insert_id();
		$this->sqlstr .= $this->db->last_query() . "; ";
		$this->logstr .= "Add Plan (" . (int)$plan_id . "): " . $para['product_short'];
		
		$cpara = array('plan_id' => $plan_id);
		$this->customer_model->update($customer_id, $cpara);
		$this->sqlstr = $this->customer_model->sqlstr . "; ";
		$this->logstr = $this->customer_model->logstr . "; ";
		
		if (empty($para['batch_number']) && empty($para['policy'])) {
			$policy = $this->get_policy_number($plan_id);
			$sql  = "UPDATE plan SET policy=" . $this->db->escape($policy) . " WHERE plan_id='" . (int)$plan_id . "'";
			$this->db->query($sql);
		}
		
		if ($isfamilyplan) {
			$i = 1;
			for ( ; $i < 9; $i++) {
				if (!empty($para['gender_' . $i])) {
					$gender = $para['gender_' . $i];
				} else {
					break;
				}
				if (!empty($para['firstname_' . $i])) {
					$firstname = $para['firstname_' . $i];
				} else {
					break;
				}
				if (!empty($para['lastname_' . $i])) {
					$lastname = $para['lastname_' . $i];
				} else {
					break;
				}
				if (!empty($para['birthday_' . $i])) {
					$birthday = $para['birthday_' . $i];
				} else {
					break;
				}
				
				$cpara = array(
						'plan_id' => $plan_id,
						'gender' => $gender,
						'firstname' => $firstname,
						'lastname' => $lastname,
						'birthday' => $birthday,
						'parent_customer_id' => $customer_id
				);
				$customer_id_this = $this->customer_model->add($cpara);
				$this->sqlstr = $this->customer_model->sqlstr . "; ";
				$this->logstr = $this->customer_model->logstr . "; ";
			}
			$this->logstr .= "Plan: Add " . $i . " customers";
		}
		
		return $plan_id;
	}
	
	/**
	 * Update Plan information
	 * 
	 * @param	integer	$plan_id	plan_id
	 * @param	array	$para		Parameters
	 * @return	array					user table search result
	 */
	public function update($plan_id, $para, $checkboxArr=array()) {
		$this->logstr = '';
		$this->sqlstr = '';
		$this->load->model('customer_model');
		$arr = array();
		$plan = $this->get_plan_by_id($plan_id);
		if (empty($plan)) {
			$this->logstr = 'Can not find plan by id[' . $plan_id . ']';
			$this->sqlstr = 'Can not find plan by id[' . $plan_id . ']';
			return 0;
		}
		$beuser = $this->session->userdata ( 'beuser' );
		if (empty($beuser)) {
			$this->logstr = 'Can not find beuser;';
			$this->sqlstr = 'Can not find beuser';
			$this->load->model('user_model');
			$beuser = $this->user_model->get_user_by_id($plan['user_id']);
			if (empty($beuser)) {
				$this->logstr = 'Can not find user by plan['.$plan_id.']['.$plan['user_id'].']';
				$this->sqlstr = 'Can not find user by plan['.$plan_id.']['.$plan['user_id'].']';
				return 0;
			}
		}
		$plan_id = $plan['plan_id'];
		$this->logstr .= "Change Plan (" . (int)$plan_id . "): ";
		$isfamilyplan = empty($para['isfamilyplan']) ? 0 : 1;
		$holiday_rate = empty($para['holiday_rate']) ? 0 : 1;
		$spouse = empty($para['spouse']) ? 0 : 1;
	
		if (!empty($para['gender']) && !empty($para['firstname']) && !empty($para['lastname']) && !empty($para['birthday'])) {
			$cpara = array(
					'gender' => $para['gender'],
					'firstname' => $para['firstname'],
					'lastname' => $para['lastname'],
					'birthday' => $para['birthday']
			);
			$customer_id = $this->customer_model->update($plan['customer_id'], $cpara);
			if ($customer_id) {
				$this->sqlstr .= $this->customer_model->sqlstr . "; ";
				$this->logstr .= $this->customer_model->logstr . "; ";
			}
		}
		$customer_id = $plan['customer_id'];
		
		if ($isfamilyplan && !empty($para['gender_1']) && !empty($para['firstname_1']) && !empty($para['lastname_1']) && !empty($para['birthday_1'])) {
			$this->customer_model->delete_by_parent_id($customer_id);
			for ($i = 1 ; $i < 9; $i++) {
				if (empty($para['gender_' . $i]) || empty($para['firstname_' . $i]) || empty($para['lastname_' . $i]) || empty($para['birthday_' . $i])) {
					continue;
				}
				$cpara = array(
						'plan_id' => $plan_id,
						'parent_customer_id' => $customer_id,
						'gender' => $para['gender_' . $i],
						'firstname' => $para['firstname_' . $i],
						'lastname' => $para['lastname_' . $i],
						'birthday' => $para['birthday_' . $i]
				);
				$customer_id_this = $this->customer_model->add($cpara);
				if ($customer_id_this) {
					$this->sqlstr .= $this->customer_model->sqlstr . "; ";
					$this->logstr .= $this->customer_model->logstr . "; ";
				}
			}
		}

		$sql  = "UPDATE plan SET";
		if (isset($para['user_id']) && ((int)$para['user_id'] != (int)$plan['user_id'])) {
			$this->logstr .= " user_id " . $para['user_id'] . "(" . $plan['user_id'] . ")";
			$sql .= " user_id='" . (int)$para['user_id'] . ", ";
		}
		if (isset($para['policy']) && ($para['policy'] != $plan['policy'])) {
			$this->logstr .= " policy " . $para['policy'] . "(" . $plan['policy'] . ")";
			$sql .= " policy=" . $this->db->escape($para['policy']) . ", ";
		}
		if (isset($para['agree']) && ((int)$para['agree'] != (int)$plan['agree'])) {
			$this->logstr .= " agree " . $para['agree'] . "(" . $plan['agree'] . ")";
			$sql .= " agree='" . (int)$para['agree'] . "', ";
		}
		if (isset($para['product_short']) && ($para['product_short'] != $plan['product_short'])) {
			$this->logstr .= " product_short " . $para['product_short'] . "(" . $plan['product_short'] . ")";
			$sql .= " product_short=" . $this->db->escape($para['product_short']) . ", ";
		}
		if (isset($para['batch_number']) && ($para['batch_number'] != $plan['batch_number'])) {
			$this->logstr .= " batch_number " . $para['batch_number'] . "(" . $plan['batch_number'] . ")";
			$sql .= " batch_number=" . $this->db->escape($para['batch_number']) . ", ";
		}
		if (isset($checkboxArr['isfamilyplan']) && ($isfamilyplan != $plan['isfamilyplan'])) {
			$this->logstr .= " isfamilyplan " . $isfamilyplan . "(" . $plan['isfamilyplan'] . ")";
			$sql .= " isfamilyplan='" . (int)$isfamilyplan . "', ";
		}
		if (($beuser['user_group_id'] == 1) || ($plan['status_id'] < 2)) {
			if (isset($para['apply_date']) && ($para['apply_date'] != $plan['apply_date'])) {
				$this->logstr .= " apply_date " . $para['apply_date'] . "(" . $plan['apply_date'] . ")";
				$sql .= " apply_date=" . $this->db->escape($para['apply_date']) . ", ";
			}
		}
		if (isset($para['arrival_date']) && ($para['arrival_date'] != $plan['arrival_date'])) {
			$this->logstr .= " arrival_date " . $para['arrival_date'] . "(" . $plan['arrival_date'] . ")";
			$sql .= " arrival_date=" . $this->db->escape($para['arrival_date']) . ", ";
		}
		if (isset($para['effective_date']) && ($para['effective_date'] != $plan['effective_date'])) {
			$this->logstr .= " effective_date " . $para['effective_date'] . "(" . $plan['effective_date'] . ")";
			$sql .= " effective_date=" . $this->db->escape($para['effective_date']) . ", ";
		}
		if (isset($para['expiry_date']) && ($para['expiry_date'] != $plan['expiry_date'])) {
			$this->logstr .= " expiry_date " . $para['expiry_date'] . "(" . $plan['expiry_date'] . ")";
			$sql .= " expiry_date=" . $this->db->escape($para['expiry_date']) . ", ";
		}
		if (isset($para['beneficiary']) && ($para['beneficiary'] != $plan['beneficiary'])) {
			$this->logstr .= " beneficiary " . $para['beneficiary'] . "(" . $plan['beneficiary'] . ")";
			$sql .= " beneficiary=" . $this->db->escape($para['beneficiary']) . ", ";
		}
		if (isset($para['stable_condition']) && ((int)$para['stable_condition'] != (int)$plan['stable_condition'])) {
			$this->logstr .= " stable_condition " . $para['stable_condition'] . "(" . $plan['stable_condition'] . ")";
			$sql .= " stable_condition='" . (int)$para['stable_condition'] . "', ";
		}
		if (isset($para['rate_options']) && ((int)$para['rate_options'] != (int)$plan['rate_options'])) {
			$this->logstr .= " rate_options " . $para['rate_options'] . "(" . $plan['rate_options'] . ")";
			$sql .= " rate_options='" . (int)$para['rate_options'] . "', ";
		}
		if (isset($checkboxArr['holiday_rate']) && ($holiday_rate != (int)$plan['holiday_rate'])) {
			$this->logstr .= " holiday_rate " . $holiday_rate . "(" . $plan['holiday_rate'] . ")";
			$sql .= " holiday_rate='" . $holiday_rate . "', ";
		}
		if (isset($checkboxArr['spouse']) && ($spouse != (int)$plan['spouse'])) {
			$this->logstr .= " spouse " . $spouse . "(" . $plan['spouse'] . ")";
			$sql .= " spouse='" . (int)$spouse . "', ";
		}
		if (isset($para['sum_insured']) && ($para['sum_insured'] != $plan['sum_insured'])) {
			$this->logstr .= " sum_insured " . $para['sum_insured'] . "(" . $plan['sum_insured'] . ")";
			$sql .= " sum_insured=" . $this->db->escape($para['sum_insured']) . ", ";
		}
		if (isset($para['deductible_amount']) && ($para['deductible_amount'] != $plan['deductible_amount'])) {
			$this->logstr .= " deductible_amount " . $para['deductible_amount'] . "(" . $plan['deductible_amount'] . ")";
			$sql .= " deductible_amount=" . $this->db->escape($para['deductible_amount']) . ", ";
		}
		if (isset($para['totaldays']) && ($para['totaldays'] != $plan['totaldays'])) {
			$this->logstr .= " totaldays " . $para['totaldays'] . "(" . $plan['totaldays'] . ")";
			$sql .= " totaldays=" . $this->db->escape($para['totaldays']) . ", ";
		}
		if (isset($para['totalyears']) && ($para['totalyears'] != $plan['totalyears'])) {
			$this->logstr .= " totalyears " . $para['totalyears'] . "(" . $plan['totalyears'] . ")";
			$sql .= " totalyears=" . $this->db->escape($para['totalyears']) . ", ";
		}
		if (isset($para['dailyrate'])) {
			$dailyrate = round((float)$para['dailyrate'],2);
			if ($dailyrate != (float)$plan['dailyrate']) {
				$this->logstr .= " dailyrate " . $dailyrate . "(" . $plan['dailyrate'] . ")";
				$sql .= " dailyrate='" . $dailyrate . "', ";
			}
		}
		$premiumchanged = 0;
		if (isset($para['premium'])) {
			$premium = round((float)$para['premium'],2);
			if ($premium != (float)$plan['premium']) {
				$this->logstr .= " premium " . $premium . "(" . $plan['premium'] . ")";
				$sql .= " premium='" . $premium . "', ";
				$premiumchanged = 1;
			}
		}
		if (isset($para['status_id']) && ((int)$para['status_id'] != (int)$plan['status_id'])) {
			$this->logstr .= " status_id " . $para['status_id'] . "(" . $plan['status_id'] . ")";
			$sql .= " status_id='" . (int)$para['status_id'] . "', ";
		} else if ($premiumchanged) {
			if (($plan['status_id'] == 2) || ($plan['status_id'] == 3)) {
				// Forced to changed status
				$this->logstr .= " status_id 7(" . $plan['status_id'] . ")";
				$sql .= " status_id='" . self::CHANGED . "', ";
			}
		}
		if (isset($para['commission_amount'])) {
			$commission_amount = round((float)$para['commission_amount'],2);
			if ($commission_amount != (float)$plan['commission_amount']) {
				$this->logstr .= " commission_amount " . $commission_amount . "(" . $plan['commission_amount'] . ")";
				$sql .= " commission_amount='" . $commission_amount . "', ";
			}
		}
		if (isset($para['street_number']) && ($para['street_number'] != $plan['street_number'])) {
			$this->logstr .= " street_number " . $para['street_number'] . "(" . $plan['street_number'] . ")";
			$sql .= " street_number=" . $this->db->escape($para['street_number']) . ", ";
		}
		if (isset($para['street_name']) && ($para['street_name'] != $plan['street_name'])) {
			$this->logstr .= " street_name " . $para['street_name'] . "(" . $plan['street_name'] . ")";
			$sql .= " street_name=" . $this->db->escape($para['street_name']) . ", ";
		}
		if (isset($para['suite_number']) && ($para['suite_number'] != $plan['suite_number'])) {
			$this->logstr .= " suite_number " . $para['suite_number'] . "(" . $plan['suite_number'] . ")";
			$sql .= " suite_number=" . $this->db->escape($para['suite_number']) . ", ";
		}
		if (isset($para['city']) && ($para['city'] != $plan['city'])) {
			$this->logstr .= " city " . $para['city'] . "(" . $plan['city'] . ")";
			$sql .= " city=" . $this->db->escape($para['city']) . ", ";
		}
		if (isset($para['province2']) && ($para['province2'] != $plan['province2'])) {
			$this->logstr .= " province2 " . $para['province2'] . "(" . $plan['province2'] . ")";
			$sql .= " province2=" . $this->db->escape($para['province2']) . ", ";
		}
		if (isset($para['country2']) && ($para['country2'] != $plan['country2'])) {
			$this->logstr .= " country2 " . $para['country2'] . "(" . $plan['country2'] . ")";
			$sql .= " country2=" . $this->db->escape($para['country2']) . ", ";
		}
		if (isset($para['postcode']) && ($para['postcode'] != $plan['postcode'])) {
			$this->logstr .= " postcode " . $para['postcode'] . "(" . $plan['postcode'] . ")";
			$sql .= " postcode=" . $this->db->escape($para['postcode']) . ", ";
		}
		if (isset($para['phone1']) && ($para['phone1'] != $plan['phone1'])) {
			$this->logstr .= " phone1 " . $para['phone1'] . "(" . $plan['phone1'] . ")";
			$sql .= " phone1=" . $this->db->escape($para['phone1']) . ", ";
		}
		if (isset($para['phone2']) && ($para['phone2'] != $plan['phone2'])) {
			$this->logstr .= " phone2 " . $para['phone2'] . "(" . $plan['phone2'] . ")";
			$sql .= " phone2=" . $this->db->escape($para['phone2']) . ", ";
		}
		if (isset($para['institution']) && ($para['institution'] != $plan['institution'])) {
			$this->logstr .= " institution " . $para['institution'] . "(" . $plan['institution'] . ")";
			$sql .= " institution=" . $this->db->escape($para['institution']) . ", ";
		}
		if (isset($para['institution_addr']) && ($para['institution_addr'] != $plan['institution_addr'])) {
			$this->logstr .= " institution_addr " . $para['institution_addr'] . "(" . $plan['institution_addr'] . ")";
			$sql .= " institution_addr=" . $this->db->escape($para['institution_addr']) . ", ";
		}
		if (isset($para['student_id']) && ($para['student_id'] != $plan['student_id'])) {
			$this->logstr .= " student_id " . $para['student_id'] . "(" . $plan['student_id'] . ")";
			$sql .= " student_id=" . $this->db->escape($para['student_id']) . ", ";
		}
		if (isset($para['institution_phone']) && ($para['institution_phone'] != $plan['institution_phone'])) {
			$this->logstr .= " institution_phone " . $para['institution_phone'] . "(" . $plan['institution_phone'] . ")";
			$sql .= " institution_phone=" . $this->db->escape($para['institution_phone']) . ", ";
		}
		if (isset($para['institution_fax']) && ($para['institution_fax'] != $plan['institution_fax'])) {
			$this->logstr .= " institution_fax " . $para['institution_fax'] . "(" . $plan['institution_fax'] . ")";
			$sql .= " institution_fax=" . $this->db->escape($para['institution_fax']) . ", ";
		}
		if (isset($para['contact_email']) && ($para['contact_email'] != $plan['contact_email'])) {
			$this->logstr .= " contact_email " . $para['contact_email'] . "(" . $plan['contact_email'] . ")";
			$sql .= " contact_email=" . $this->db->escape($para['contact_email']) . ", ";
		}
		if (isset($para['contact_phone']) && ($para['contact_phone'] != $plan['contact_phone'])) {
			$this->logstr .= " contact_phone " . $para['contact_phone'] . "(" . $plan['contact_phone'] . ")";
			$sql .= " contact_phone=" . $this->db->escape($para['contact_phone']) . ", ";
		}
		if (isset($para['residence']) && ($para['residence'] != $plan['residence'])) {
			$this->logstr .= " residence " . $para['residence'] . "(" . $plan['residence'] . ")";
			$sql .= " residence=" . $this->db->escape($para['residence']) . ", ";
		}
		if (isset($para['payment_id']) && ((int)$para['payment_id'] != (int)$plan['payment_id'])) {
			$this->logstr .= " payment_id " . $para['payment_id'] . "(" . $plan['payment_id'] . ")";
			$sql .= " payment_id=" . (int)$para['payment_id'] . ", ";
		}
		if (isset($para['commission_payment_id']) && ((int)$para['commission_payment_id'] != (int)$plan['commission_payment_id'])) {
			$this->logstr .= " commission_payment_id " . $para['commission_payment_id'] . "(" . $plan['commission_payment_id'] . ")";
			$sql .= " commission_payment_id=" . (int)$para['commission_payment_id'] . ", ";
		}
		if (isset($para['payinfo']) && ($para['payinfo'] != $plan['payinfo'])) {
			$this->logstr .= " payinfo " . $para['payinfo'] . "(" . $plan['payinfo'] . ")";
			$sql .= " payinfo=" . $this->db->escape($para['payinfo']) . ", ";
		}
		if (isset($para['note']) && ($para['note'] != $plan['note'])) {
			$this->logstr .= " note " . $para['note'] . "(" . $plan['note'] . ")";
			$sql .= " note=" . $this->db->escape($para['note']) . ", ";
		}
		if ($sql == "UPDATE plan SET") {
			// No change 
			return $plan_id;
		}
		$sql .= " plan_id=plan_id ";
		$sql .= " WHERE plan_id='" . (int)$plan_id . "'";
		$this->db->query($sql);
		$this->sqlstr = $this->db->last_query() . "; ";

		if (isset($para['status_id']) && ((int)$para['status_id'] != (int)$plan['status_id']) && ((int)$para['status_id'] == 3) && ((int)$plan['status_id'] == 2)) {
			$payment_id = empty($plan['payment_id']) ? (empty($para['payment_id']) ? 0 : $para['payment_id']) : $plan['payment_id'];
			if ($payment_id) {
				$this->load->model('payment_model');
				$dt['ispaid'] = 1;
				$payment_id = $this->payment_model->update($payment_id, $dt);
				$para = array(
						'plan_id' => $plan['plan_id'],
						'customer_id' => $plan['customer_id'],
						'payment_id' => $payment_id,
						'message' => $this->payment_model->logstr,
						'systemlog' => $this->payment_model->sqlstr
				);
				$this->log_model->activity('payment', $para);
			}
		}
		
		return $plan_id;
	}
	
	/**
	 * Get plan's commission
	 * 
	 * @param integer $plan_id
	 * @return float 
	 */
	public function delete($plan_id) {
		$plan = $this->get_plan_by_id($plan_id);
		if ($plan) {
			$this->load->model('customer_model');
			$this->customer_model->delete($plan['customer_id']);
			$this->customer_model->delete_by_parent_id($plan['customer_id']);
			$this->db->delete('plan', array('plan_id' => $plan_id));
		}
	}
	
	/**
	 * Search Plans (policies) by conditions
	 * 
	 * @param	array	$para		Search parameters
	 * @param	int		$limit		limit
	 * @return	array					user table search result
	 */
	public function plan_search($para, $limit=0) {
		$beuser = $this->session->userdata('beuser');
		if (empty($beuser)) {
			return array();
		}
		
		$plans = array();
		$carr = array();
		if (!empty($para['firstname'])) {
			$carr[] = "firstname LIKE " . $this->db->escape($para['firstname'] . "%");
		} 
		if (!empty($para['lastname'])) {
			$carr[] = "lastname LIKE " . $this->db->escape($para['lastname'] . "%");
		} 
		if (!empty($para['birthday'])) {
			if (!empty($para['birthday2'])) {
				$carr[] = "birthday >= " . $this->db->escape($para['birthday']);
				$carr[] = "birthday <= " . $this->db->escape($para['birthday2']);
			} else {
				$carr[] = "birthday >= " . $this->db->escape($para['birthday']);
			}
		}
		if (!empty($carr)) {
			$sql = "SELECT distinct plan_id FROM customer WHERE " . join(" AND ", $carr);
			$rows = $this->db->query($sql)->result_array();
			foreach ($rows as $row) {
				$plans[] = $row['plan_id'];
			}
			if (empty($plans)) {
				// Not customer on plans
				return array();
			}
		}
		$users = array();
		if ($beuser['user_group_id'] < 100) {
			if (!empty($para['uname'])) {
				$sql = "SELECT user_id FROM user WHERE firstname LIKE " . $this->db->escape($para['uname'] . "%") . " OR lastname LIKE " . $this->db->escape($para['uname'] . "%");
				$rows = $this->db->query($sql)->result_array();
				foreach ($rows as $row) {
					$users[] = $row['user_id'];
				}
			}
		} else if ($beuser['user_group_id'] == 104) {
			if (!empty($para['uname'])) {
				$sql = "SELECT user_id FROM user WHERE (user_id='".(int)$beuser['user_id']."' OR parent_user_id='".(int)$beuser['user_id']."') AND firstname LIKE " . $this->db->escape($para['uname'] . "%") . " OR lastname LIKE " . $this->db->escape($para['uname'] . "%");
			} else {
				$sql = "SELECT user_id FROM user WHERE user_id='".(int)$beuser['user_id']."' OR parent_user_id='".(int)$beuser['user_id']."'";
			}
			$rows = $this->db->query($sql)->result_array();
			foreach ($rows as $row) {
				$users[] = $row['user_id'];
			}
		} else {
			$sql = "SELECT user_id FROM user WHERE user_id='".(int)$beuser['user_id']."'";
			$rows = $this->db->query($sql)->result_array();
			foreach ($rows as $row) {
				$users[] = $row['user_id'];
			}
		}
		$sql  = "SELECT p.*, c.firstname, c.lastname, c.gender, c.birthday, u.firstname AS agent_firstname, u.lastname AS agent_lastname, u.user_id AS agent_id FROM plan p";
		$sql .= " INNER JOIN customer c ON (p.customer_id=c.customer_id)";
		$sql .= " INNER JOIN user u ON (p.user_id=u.user_id)";
		$where = array();
		if (!empty($para['plan_id'])) {
			$where[] = "p.plan_id=" . (int)$para['plan_id'];
		}
		if (!empty($para['policy'])) {
			$where[] = "p.policy=" . $this->db->escape($para['policy']);
		}
		if (!empty($para['batch_number'])) {
			$where[] = "p.batch_number=" . $this->db->escape($para['batch_number']);
		}
		if (!empty($para['status_id'])) {
			$where[] = "p.status_id='" . (int)$para['status_id'] . "'";
		}
		if (!empty($para['product_short'])) {
			$where[] = "p.product_short=" . $this->db->escape($para['product_short']);
		}
		if ($beuser['region_id']) {
			$where[] = "p.region_id = " . (int)$beuser['region_id'];
		}
		if (!empty($plans)) {
			$where[] = "p.plan_id IN (" . join(",", $plans) . ")";
		}
		if (!empty($users)) {
			$where[] = "p.user_id IN (" . join(",", $users) . ")";
		}
		if (!empty($para['province2'])) {
			$where[] = "p.province2=" . $this->db->escape($para['province2']);
		}
		if (!empty($para['country2'])) {
			$where[] = "p.country2=" . $this->db->escape($para['country2']);
		}
		if (!empty($para['apply_date'])) {
			if (!empty($para['apply_date2'])) {
				$where[] = "p.apply_date >= " . $this->db->escape($para['apply_date']);
				$where[] = "p.apply_date <= " . $this->db->escape($para['apply_date2']);
			} else {
				$where[] = "p.apply_date >= " . $this->db->escape($para['apply_date']);
			}
		}
		if (!empty($para['arrival_date'])) {
			if (!empty($para['arrival_date2'])) {
				$where[] = "p.arrival_date >= " . $this->db->escape($para['arrival_date']);
				$where[] = "p.arrival_date <= " . $this->db->escape($para['arrival_date2']);
			} else {
				$where[] = "p.arrival_date >= " . $this->db->escape($para['arrival_date']);
			}
		}
		if (!empty($para['effective_date'])) {
			if (!empty($para['effective_date2'])) {
				$where[] = "p.effective_date >= " . $this->db->escape($para['effective_date']);
				$where[] = "p.effective_date <= " . $this->db->escape($para['effective_date2']);
			} else {
				$where[] = "p.effective_date >= " . $this->db->escape($para['effective_date']);
			}
		}
		if (!empty($para['expiry_date'])) {
			if (!empty($para['expiry_date2'])) {
				$where[] = "p.expiry_date >= " . $this->db->escape($para['expiry_date']);
				$where[] = "p.expiry_date <= " . $this->db->escape($para['expiry_date2']);
			} else {
				$where[] = "p.expiry_date >= " . $this->db->escape($para['expiry_date']);
			}
		}
		if (!empty($para['last_update'])) {
			if (!empty($para['last_update2'])) {
				$where[] = "p.last_update >= " . $this->db->escape($para['last_update']);
				$where[] = "p.last_update <= " . $this->db->escape($para['last_update2']);
			} else {
				$where[] = "p.last_update >= " . $this->db->escape($para['last_update']);
			}
		}
		if (!empty($where)) {
			$sql .= " WHERE " . join(" AND ", $where);
		} else {
			if (empty($limit)) {
				$limit = 200;
			}
		}
		$sql .= " ORDER BY plan_id DESC";
		if ($limit) {
			$sql .= " LIMIT " . $limit;
		}
		return $this->db->query($sql)->result_array();
	}

	/**
	 * Search Plans (policies) by conditions for export
	 * 
	 * @param	array	$para		Search parameters
	 * @param	int		$limit		limit
	 * @return	array					user table search result
	 */
	public function plan_export_search($para) {
		$beuser = $this->session->userdata('beuser');
		if (empty($beuser)) {
			return array();
		}
		
		$rArr = $this->plan_search($para);
		for ($i = 0; $i < sizeof($rArr); $i++) {
			if ($rArr[$i]['isfamilyplan']) {
				$this->db->where('plan_id', $rArr[$i]['plan_id']);
				$this->db->where('parent_customer_id', $rArr[$i]['customer_id']);
				$cArr = $this->db->get('customer')->result_array();
				for ($j = 0; $j < sizeof($cArr); $j++) {
					$m = $j + 1;
					$rArr[$i]['gender_'.$m] = $cArr[$j]['gender'];
					$rArr[$i]['firstname_'.$m] = $cArr[$j]['firstname'];
					$rArr[$i]['lastname_'.$m] = $cArr[$j]['lastname'];
					$rArr[$i]['birthday_'.$m] = $cArr[$j]['birthday'];
				}
			}
		}
		return $rArr;
	}
	
	/**
	 * Get plan's commission
	 * 
	 * @param integer $plan_id
	 * @return float 
	 */
	public function get_commission($plan_id, $amount=0) {
		$plan = $this->get_plan_by_id($plan_id);
		if (empty($plan)) {
			return 0;
		}
		
		$this->db->where('user_id', $plan['user_id']);
		$this->db->where('product_short', $plan['product_short']);
		$user_product = $this->db->get('user_product')->row_array();
		if (empty($user_product)) {
			return 0;
		}
		
		if (empty($amount)) {
			$commission = $plan['premium'] * $user_product['commission'] / 100;
		} else {
			$commission = $amount * $user_product['commission'] / 100;
		}
		return $commission;
	}
}
