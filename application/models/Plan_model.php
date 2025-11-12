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
    const ADJUST = 8;

    const MAX_PLANS = 200;

    const CLAIM_URL = "https://claim.otcww.com/api/";
    //const CLAIM_URL = "https://claim.mmoo.ca/api/";
    //const CLAIM_URL = "http://dev3.com/api/";
    const CLAIM_KEY = "H5FqpJdc";
    
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
	
  public function get_plan_by_policy($policy) {
    return $this->db->where("policy", $policy)->get("plan")->row_array();
  }

	/**
	 * Get Plan current policy number
	 * 
	 * @param	integer	$plan_id		Parameters
	 * @return	string					policy number
	 */
	public function get_plan_customers_by_id($plan_id) {
		$sql = "SELECT * FROM customer WHERE plan_id='" . (int)$plan_id . "'";
		return $this->db->query($sql)->result_array();
	}
	
	/**
	 * Get Customer Claim status
	 * 
	 * @param	integer	$plan_id		Parameters
	 * @return	string					policy number
	 */
	public function verify_customer($firstname, $lastname, $dob) {
		// prepare post data
		$data ['key'] = self::CLAIM_KEY;
		$data ['firstname'] = $firstname;
		$data ['lastname'] = $lastname;
		$data ['birth'] = $dob;
		$post_data = http_build_query ( $data );
		
		// get list of policy status here
		$url = self::CLAIM_URL."claim_search";
		$curl = curl_init ();
		
		// Post Data
		curl_setopt ( $curl, CURLOPT_POST, 1 );
		curl_setopt ( $curl, CURLOPT_POSTFIELDS, $post_data );
		
		// Optional Authentication:
		//if (API_USER and API_PASSWORD) {
		//	curl_setopt ( $curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC );
		//	curl_setopt ( $curl, CURLOPT_USERPWD, API_USER . ":" . API_PASSWORD );
		//}
		
		curl_setopt ( $curl, CURLOPT_URL, $url );
		curl_setopt ( $curl, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt ( $curl, CURLOPT_DNS_USE_GLOBAL_CACHE, false );
		curl_setopt ( $curl, CURLOPT_DNS_CACHE_TIMEOUT, 2 );
		curl_setopt ( $curl, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 );
		curl_setopt ( $curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt ( $curl, CURLOPT_SSL_VERIFYHOST, false);
		
		$result = curl_exec ( $curl );
		
		curl_close ( $curl );
		$rt = json_decode ( $result, TRUE );
		return $rt;
	}
	
	/**
	 * Get Customer Claim status
	 * 
	 * @param	integer	$plan_id		Parameters
	 * @return	string					policy number
	 */
	public function verify_policy($policy) {
		// prepare post data
		$data ['key'] = self::CLAIM_KEY;
		$data ['policy'] = $policy;
		$post_data = http_build_query ( $data );
		
		// get list of policy status here
		$url = self::CLAIM_URL."claim_exist";
		$curl = curl_init ();
		
		// Post Data
		curl_setopt ( $curl, CURLOPT_POST, 1 );
		curl_setopt ( $curl, CURLOPT_POSTFIELDS, $post_data );
		
		// Optional Authentication:
		//if (API_USER and API_PASSWORD) {
		//	curl_setopt ( $curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC );
		//	curl_setopt ( $curl, CURLOPT_USERPWD, API_USER . ":" . API_PASSWORD );
		//}
		
		curl_setopt ( $curl, CURLOPT_URL, $url );
		curl_setopt ( $curl, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt ( $curl, CURLOPT_DNS_USE_GLOBAL_CACHE, false );
		curl_setopt ( $curl, CURLOPT_DNS_CACHE_TIMEOUT, 2 );
		curl_setopt ( $curl, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 );
		curl_setopt ( $curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt ( $curl, CURLOPT_SSL_VERIFYHOST, false);
		
		$result = curl_exec ( $curl );
		
		curl_close ( $curl );
		$rt = json_decode ( $result, TRUE );
		return $rt;
	}
	
	/**
	 * Get Plan Key
	 * 
	 * @param	integer	$plan_id		Parameters
	 * @return	string					policy number
	 */
	public function get_plan_key($plan_id) {
		$plan = $this->get_plan_by_id($plan_id);
    if (!empty($plan['api'])) {
      $key = $plan['api'];
    } else {
      $key = md5('jfuk0621' . $plan_id . $plan['user_id'] . $plan['customer_id']);
      $this->db->where('plan_id', $plan_id);
      $this->db->update('plan', array('api' => $key));
    }
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
            'status_id' => $plan['status_id'],
            'plan_id' => $plan['plan_id'],
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
	 * @return	int					user table search result
	 */
	public function add($para, $apiuser=null) {
		$this->load->model('customer_model');
    $isvsuser = 0;
    if (empty($apiuser)) {
      $beuser = $this->session->userdata ( 'beuser' );
      if (empty($beuser)) {
        $beuser = $this->session->userdata ( 'vsuser' );
        $isvsuser = 1;
        if (empty($beuser)) {
          return 0;
        }
      }
    } else {
      $beuser = $apiuser;
    }
    $dt = date("Y-m-d");
    if (($dt >= "2021-07-01") && ($para['product_short'] == 'OPL')) {
      return 0;
    }
		if ((($para['product_short'] == 'NUS') || ($para['product_short'] == 'JUS')) && isset($para['rate_options']) && ($para['rate_options'] == 2)) {
			$para['deductible_amount'] = 50;
		}
		$isfamilyplan = empty($para['isfamilyplan']) ? 0 : ((intval($para['isfamilyplan']) > 0) ? intval($para['isfamilyplan']) : 1);
		$cpara = array(
				'plan_id' => 0,
				'gender' => trim($para['gender']),
				'firstname' => trim($para['firstname']),
				'lastname' => trim($para['lastname']),
				'birthday' => $para['birthday'],
        'relationship' => '',
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
		if (isset($para['key'])) $sql .= " api=" . $this->db->escape($para['key']) . ", ";
		if (isset($para['batch_number'])) $sql .= " batch_number=" . $this->db->escape($para['batch_number']) . ", ";
		$sql .= " isfamilyplan='" . (int)$isfamilyplan . "', ";
		if (isset($para['apply_date'])) $sql .= " apply_date=" . $this->db->escape($para['apply_date']) . ", ";
		if (isset($para['arrival_date'])) $sql .= " arrival_date=" . $this->db->escape($para['arrival_date']) . ", ";
		if (isset($para['effective_date'])) $sql .= " effective_date=" . $this->db->escape($para['effective_date']) . ", ";
		if (isset($para['expiry_date'])) $sql .= " expiry_date=" . $this->db->escape($para['expiry_date']) . ", ";
		if (isset($para['beneficiary'])) $sql .= " beneficiary=" . $this->db->escape($para['beneficiary']) . ", ";
		if (isset($para['stable_condition'])) $sql .= " stable_condition='" . (int)$para['stable_condition'] . "', ";
		if ((($para['product_short'] == 'JFVTC') || ($para['product_short'] == 'JFR')) && ((int)$para['stable_condition'] == 2)) {
			$sql .= " stable_condition_confirm='" . (empty($para['stable_condition_confirm']) ? "0" : "1") . "', ";
		} else if ($para['product_short'] == 'TOPN') {
			$sql .= " stable_condition_confirm='" . (empty($para['stable_condition_confirm']) ? "0" : "1") . "', ";
		}
		
		if (isset($para['rate_options'])) $sql .= " rate_options='" . (int)$para['rate_options'] . "', ";
		if (isset($para['holiday_rate'])) $sql .= " holiday_rate='" . (int)$para['holiday_rate'] . "', ";
		if (isset($para['spouse'])) $sql .= " spouse='" . (int)$para['spouse'] . "', ";
		if (isset($para['sum_insured'])) $sql .= " sum_insured='" . (int)$para['sum_insured'] . "', ";
		if (isset($para['deductible_amount'])) $sql .= " deductible_amount='" . (int)$para['deductible_amount'] . "', ";
		if (isset($para['totaldays'])) $sql .= " totaldays='" . (int)$para['totaldays'] . "', ";
		if (isset($para['totalyears'])) $sql .= " totalyears='" . (int)$para['totalyears'] . "', ";
		if (isset($para['dailyrate']))  $sql .= " dailyrate='" . (float) $para['dailyrate'] . "', ";
		if (isset($para['premium']))  $sql .= " premium='" . (float) $para['premium'] . "', ";
		if (isset($para['monthlypay']))  $sql .= " monthlypay='" . int_val($para['monthlypay']) . "', ";
		if (isset($para['tax']))  $sql .= " tax='" . (float) $para['tax'] . "', ";
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
		if (isset($para['contact_language'])) $sql .= " contact_language=" . $this->db->escape(trim($para['contact_language'])) . ", ";
		if (isset($para['residence'])) $sql .= " residence=" . $this->db->escape(trim($para['residence'])) . ", ";
		if (isset($para['payment_id'])) $sql .= " payment_id=" . (int)$para['payment_id'] . ", ";
		if (isset($para['commission_payment_id'])) $sql .= " commission_payment_id=" . (int)$para['commission_payment_id'] . ", ";
		if (isset($para['payinfo'])) $sql .= " payinfo=" . $this->db->escape(trim($para['payinfo'])) . ", ";
		if (isset($para['note'])) $sql .= " note=" . $this->db->escape(trim($para['note'])) . ", ";
		
		// For TOP plan
		if (isset($para['package'])) $sql .= " package=" . $this->db->escape(trim($para['package'])) . ", ";
		if (isset($para['free_cancel'])) $sql .= " free_cancel='1', ";
		if (isset($para['annual_plan_days'])) $sql .= " annual_plan_days=" . (int)$para['annual_plan_days'] . ", ";
		if (isset($para['ad_and_d_ck']) && ($para['ad_and_d_ck']!=0)) {
			$sql .= " ad_and_d_ck='1', ";
		}
		if (isset($para['ad_and_d_insured'])) $sql .= " ad_and_d_insured=" . (int)$para['ad_and_d_insured'] . ", ";
    if (isset($para['flight_accident_ck']) && ($para['flight_accident_ck']!=0)) {
      $sql .= " flight_accident_ck='1', ";
		}
		if (isset($para['flight_accident_insured'])) $sql .= " flight_accident_insured=" . (int)$para['flight_accident_insured'] . ", ";
		if (isset($para['trip_cancellation_ck'])) {
			$sql .= " trip_cancellation_ck='" . intval($para['trip_cancellation_ck']) . "', ";
		}
		if (isset($para['trip_cancellation_insured'])) $sql .= " trip_cancellation_insured=" . (int)$para['trip_cancellation_insured'] . ", ";
		if (isset($para['questionnaire'])) $sql .= " questionnaire=" . (int)$para['questionnaire'] . ", ";
		if (isset($para['question1'])) $sql .= " question1=" . (int)$para['question1'] . ", ";
		if (isset($para['question1_lung'])) $sql .= " question1_lung=" . (int)$para['question1_lung'] . ", ";
		if (isset($para['question1_diabets'])) $sql .= " question1_diabets=" . (int)$para['question1_diabets'] . ", ";
		if (isset($para['question1_heart'])) $sql .= " question1_heart=" . (int)$para['question1_heart'] . ", ";
		if (isset($para['question2'])) $sql .= " question2=" . (int)$para['question2'] . ", ";
		if (isset($para['question3'])) $sql .= " question3=" . (int)$para['question3'] . ", ";
		if (isset($para['question3_bowel'])) $sql .= " question3_bowel=" . $this->db->escape(trim($para['question3_bowel'])) . ", ";
		if (isset($para['question3_cancer'])) $sql .= " question3_cancer=" . $this->db->escape(trim($para['question3_cancer'])) . ", ";
		if (isset($para['question3_diabetes'])) $sql .= " question3_diabetes=" . $this->db->escape(trim($para['question3_diabetes'])) . ", ";
		if (isset($para['question3_diverticu'])) $sql .= " question3_diverticu=" . $this->db->escape(trim($para['question3_diverticu'])) . ", ";
		if (isset($para['question3_gerd'])) $sql .= " question3_gerd=" . $this->db->escape(trim($para['question3_gerd'])) . ", ";
		if (isset($para['question3_heart'])) $sql .= " question3_heart=" . $this->db->escape(trim($para['question3_heart'])) . ", ";
		if (isset($para['question3_hyper'])) $sql .= " question3_hyper=" . $this->db->escape(trim($para['question3_hyper'])) . ", ";
		if (isset($para['question3_kidney'])) $sql .= " question3_kidney=" . $this->db->escape(trim($para['question3_kidney'])) . ", ";
		if (isset($para['question3_lung'])) $sql .= " question3_lung=" . $this->db->escape(trim($para['question3_lung'])) . ", ";
		if (isset($para['question3_peptic'])) $sql .= " question3_peptic=" . $this->db->escape(trim($para['question3_peptic'])) . ", ";
		if (isset($para['question4'])) $sql .= " question4=" . (int)$para['question4'] . ", ";
		if (isset($para['question5'])) $sql .= " question5=" . (int)$para['question5'] . ", ";

		
		$sql .= " ip=" . $this->db->escape($_SERVER['REMOTE_ADDR']) . ", ";
		$sql .= " commission_amount='0' ";
		$this->db->query($sql);
		$plan_id = $this->db->insert_id();
		$this->sqlstr .= $this->db->last_query() . "; ";
		$this->logstr .= "Add Plan (" . (int)$plan_id . "): " . $para['product_short'] . ($isvsuser ? ' by customer' : '');
		
		$cpara = array('plan_id' => $plan_id);
		$this->customer_model->update($customer_id, $cpara);
		$this->sqlstr .= $this->customer_model->sqlstr . "; ";
		$this->logstr .= $this->customer_model->logstr . "; ";
		
		if (empty($para['batch_number']) && empty($para['policy'])) {
			$policy = $this->get_policy_number($plan_id);
			$sql  = "UPDATE plan SET policy=" . $this->db->escape($policy) . " WHERE plan_id='" . (int)$plan_id . "'";
			$this->db->query($sql);
		}
		
		if ($isfamilyplan) {
			$i = 1;
			for ( ; $i < 25; $i++) {
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
        $relationship = "";
				if (!empty($para['relationship_' . $i])) {
					$relationship = $para['relationship_' . $i];
				}
				
				$cpara = array(
						'plan_id' => $plan_id,
						'gender' => $gender,
						'firstname' => trim($firstname),
						'lastname' => trim($lastname),
						'birthday' => $birthday,
            'relationship' => $relationship,
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
	 * @return	integer					user table search result
	 */
	public function update($plan_id, $para, $checkboxArr=array(), $apiuser=null) {
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
    $isvsuser = 0;
    if (empty($apiuser)) {
      $beuser = $this->session->userdata ( 'beuser' );
      if (empty($beuser)) {
        $beuser = $this->session->userdata ( 'vsuser' );
        $isvsuser = 1;
        if (empty($beuser)) {
          $this->logstr = 'Can not find beuser;';
          $this->sqlstr = 'Can not find beuser';
          return 0;
        }
      }
    } else {
      $beuser = $apiuser;
    }

		if (empty($beuser)) {
			$this->load->model('user_model');
			$beuser = $this->user_model->get_user_by_id($plan['user_id']);
			if (empty($beuser)) {
				$this->logstr = 'Can not find user by plan['.$plan_id.']['.$plan['user_id'].']';
				$this->sqlstr = 'Can not find user by plan['.$plan_id.']['.$plan['user_id'].']';
				return 0;
			}
		}
		$plan_id = $plan['plan_id'];
		$this->logstr .= "Change Plan (" . (int)$plan_id . "): " . ($isvsuser ? ' by customer ' : ' ');
    $update_history = 0;

		$isfamilyplan = 0;
		if (isset($para['isfamilyplan'])) {
			if ((int)$para['isfamilyplan'] >= 1) {
				$isfamilyplan = (int)$para['isfamilyplan'];
			} else if (!empty($para['isfamilyplan'])) {
				$isfamilyplan = 1;
			}
		}
		$holiday_rate = empty($para['holiday_rate']) ? 0 : 1;
		$spouse = empty($para['spouse']) ? 0 : 1;
	
		if (!empty($para['gender']) && !empty($para['firstname']) && !empty($para['lastname']) && !empty($para['birthday'])) {
			$cpara = array(
					'gender' => $para['gender'],
					'firstname' => trim($para['firstname']),
					'lastname' => trim($para['lastname']),
					'birthday' => $para['birthday']
			);
			$customer_id = $this->customer_model->update($plan['customer_id'], $cpara);
			if ($customer_id) {
				$this->sqlstr .= $this->customer_model->sqlstr . "; ";
				$this->logstr .= $this->customer_model->logstr . "; ";
			}
		}
		$customer_id = $plan['customer_id'];
		
		if (isset($para['gender_1']) && isset($para['firstname_1']) && isset($para['lastname_1']) && isset($para['birthday_1'])) {
			$this->customer_model->delete_by_parent_id($customer_id);
		}
		if ($isfamilyplan && !empty($para['gender_1']) && !empty($para['firstname_1']) && !empty($para['lastname_1']) && !empty($para['birthday_1'])) {
			for ($i = 1 ; $i < 25; $i++) {
				if (!isset($para['gender_' . $i])) break;
				if (empty($para['gender_' . $i]) || empty($para['firstname_' . $i]) || empty($para['lastname_' . $i]) || empty($para['birthday_' . $i])) {
					continue;
				}
				$cpara = array(
						'plan_id' => $plan_id,
						'parent_customer_id' => $customer_id,
						'gender' => $para['gender_' . $i],
						'firstname' => trim($para['firstname_' . $i]),
						'lastname' => trim($para['lastname_' . $i]),
            'relationship' => trim($para['relationship_' . $i])?trim($para['relationship_' . $i]):"",
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
			$sql .= " user_id='" . (int)$para['user_id'] . "', ";
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
      if (($plan['status_id'] == self::PAID) || ($plan['status_id'] == self::SOLD)) {
        $update_history = 1;
      }
			$this->logstr .= " arrival_date " . $para['arrival_date'] . "(" . $plan['arrival_date'] . ")";
			$sql .= " arrival_date=" . $this->db->escape($para['arrival_date']) . ", ";
		}
		if (isset($para['effective_date']) && ($para['effective_date'] != $plan['effective_date'])) {
      if (($plan['status_id'] == self::PAID) || ($plan['status_id'] == self::SOLD)) {
        $update_history = 1;
      }
			$this->logstr .= " effective_date " . $para['effective_date'] . "(" . $plan['effective_date'] . ")";
			$sql .= " effective_date=" . $this->db->escape($para['effective_date']) . ", ";
		}
		if (isset($para['expiry_date']) && ($para['expiry_date'] != $plan['expiry_date'])) {
      if (($plan['status_id'] == self::PAID) || ($plan['status_id'] == self::SOLD)) {
        $update_history = 1;
      }
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
		if (isset($para['product_short']) && (($para['product_short'] == 'JFVTC') | ($para['product_short'] == 'JFR')) && ((int)$para['stable_condition'] == 2)) {
			$this->logstr .= " stable_condition_confirm " . $para['stable_condition_confirm'] . "(" . $plan['stable_condition_confirm'] . ")";
			$sql .= " stable_condition_confirm='" . (empty($para['stable_condition_confirm']) ? "0" : "1") . "', ";
		} else if (isset($para['product_short']) && ($para['product_short'] == 'TOPN')) {
			$this->logstr .= " stable_condition_confirm " . $para['stable_condition_confirm'] . "(" . $plan['stable_condition_confirm'] . ")";
			$sql .= " stable_condition_confirm='" . (empty($para['stable_condition_confirm']) ? "0" : "1") . "', ";
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
      if (($plan['status_id'] == self::PAID) || ($plan['status_id'] == self::SOLD)) {
        $update_history = 1;
      }
			$this->logstr .= " sum_insured " . $para['sum_insured'] . "(" . $plan['sum_insured'] . ")";
			$sql .= " sum_insured=" . $this->db->escape($para['sum_insured']) . ", ";
		}
		if (isset($para['deductible_amount']) && ($para['deductible_amount'] != $plan['deductible_amount'])) {
      if (($plan['status_id'] == self::PAID) || ($plan['status_id'] == self::SOLD)) {
        $update_history = 1;
      }
			$this->logstr .= " deductible_amount " . $para['deductible_amount'] . "(" . $plan['deductible_amount'] . ")";
			$sql .= " deductible_amount=" . $this->db->escape($para['deductible_amount']) . ", ";
		}
		if (isset($para['totaldays']) && ($para['totaldays'] != $plan['totaldays'])) {
      if (($plan['status_id'] == self::PAID) || ($plan['status_id'] == self::SOLD)) {
        $update_history = 1;
      }
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
		if (isset($para['monthlypay'])) {
			$monthlypay = int_val($para['monthlypay']);
			if ($monthlypay != $plan['monthlypay']) {
				$this->logstr .= " monthlypay " . $monthlypay . "(" . $plan['monthlypay'] . ")";
				$sql .= " monthlypay='" . $monthlypay . "', ";
				$premiumchanged = 1;
			}
		}
		if (isset($para['tax'])) {
			$tax = round((float)$para['tax'],2);
			if ($tax != (float)$plan['tax']) {
				$this->logstr .= " tax " . $tax . "(" . $plan['tax'] . ")";
				$sql .= " tax='" . $tax . "', ";
			}
		}
		$status_idchanged = 0;
		if (isset($para['status_id']) && ((int)$para['status_id'] != (int)$plan['status_id'])) {
			$this->logstr .= " status_id " . $para['status_id'] . "(" . $plan['status_id'] . ")";
			$sql .= " status_id='" . (int)$para['status_id'] . "', ";
      $status_idchanged = 1;
		} else if ($premiumchanged || $update_history) {
      if (($plan['status_id'] == self::PAID) || ($plan['status_id'] == self::SOLD)) {
				// Forced to changed status
				$this->logstr .= " status_id 7(" . $plan['status_id'] . ")";
				$sql .= " status_id='" . self::CHANGED . "', ";
        $status_idchanged = 1;
			}
		}
    if ($premiumchanged || $status_idchanged) {
      $api = md5($plan['api']);
      $this->logstr .= " api ".$api."(" . $plan['api'] . ")";
      $sql .= " api='" . $api . "', ";
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
		if (isset($para['contact_language']) && ($para['contact_language'] != $plan['contact_language'])) {
			$this->logstr .= " contact_language " . $para['contact_language'] . "(" . $plan['contact_language'] . ")";
			$sql .= " contact_language=" . $this->db->escape($para['contact_language']) . ", ";
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
		if (isset($para['refund_date']) && ($para['refund_date'] != $plan['refund_date'])) {
			$this->logstr .= " refund_date " . $para['refund_date'] . "(" . $plan['refund_date'] . ")";
			$sql .= " refund_date=" . $this->db->escape($para['refund_date']) . ", ";
		}
		// For TOP plan
		if (isset($para['package']) && ($para['package'] != $plan['package'])) {
			$this->logstr .= " package " . $para['package'] . "(" . $plan['package'] . ")";
			$sql .= " package=" . $this->db->escape(trim($para['package'])) . ", ";
		}
		
		if (isset($para['annual_plan_days']) && ($para['annual_plan_days'] != $plan['annual_plan_days'])) {
			$this->logstr .= " annual_plan_days " . $para['annual_plan_days'] . "(" . $plan['annual_plan_days'] . ")";
			$sql .= " annual_plan_days=" . $this->db->escape(trim($para['annual_plan_days'])) . ", ";
		}
		if (isset($para['package'])) {
			if (!isset($para['agree']) || ((int)$para['agree'] == (int)$plan['agree'])) {
				$free_cancel = isset($para['free_cancel']) ? 1 : 0;
				if ($free_cancel != $plan['free_cancel']) {
					$this->logstr .= " free_cancel " . $para['free_cancel'] . "(" . $plan['free_cancel'] . ")";
					$sql .= " free_cancel='".$free_cancel."', ";
				}

				if (isset($para['ad_and_d_insured']) && ($para['ad_and_d_insured'] > 0)) {
					$ad_and_d_ck = (isset($para['ad_and_d_ck'])&&($para['ad_and_d_ck']!=0)) ? 1 : 0;
					if ($ad_and_d_ck != $plan['ad_and_d_ck']) {
						$this->logstr .= " ad_and_d_ck " . $para['ad_and_d_ck'] . "(" . $plan['ad_and_d_ck'] . ")";
						$sql .= " ad_and_d_ck='".intval($ad_and_d_ck)."', ";
					}
					if ($para['ad_and_d_insured'] != $plan['ad_and_d_insured']) {
						$this->logstr .= " ad_and_d_insured " . $para['ad_and_d_insured'] . "(" . $plan['ad_and_d_insured'] . ")";
						$sql .= " ad_and_d_insured=" . intval($para['ad_and_d_insured']) . ", ";
					}
				}
				if (isset($para['flight_accident_insured']) && ($para['flight_accident_insured'] > 0)) {
					$flight_accident_ck = (isset($para['flight_accident_ck'])&&($para['flight_accident_ck']!=0)) ? 1 : 0;
					if ($flight_accident_ck != $plan['flight_accident_ck']) {
						$this->logstr .= " flight_accident_ck " . $para['flight_accident_ck'] . "(" . $plan['flight_accident_ck'] . ")";
						$sql .= " flight_accident_ck='".intval($flight_accident_ck)."', ";
					}
					if ($para['flight_accident_insured'] != $plan['flight_accident_insured']) {
						$this->logstr .= " flight_accident_insured " . $para['flight_accident_insured'] . "(" . $plan['flight_accident_insured'] . ")";
						$sql .= " flight_accident_insured=" . (int)$para['flight_accident_insured'] . ", ";
					}
				}
				if (isset($para['flight_accident_insured'])) {
					$trip_cancellation_ck = intval($para['trip_cancellation_ck']);
					$this->logstr .= " trip_cancellation_ck " . $para['trip_cancellation_ck'] . "(" . $plan['trip_cancellation_ck'] . ")";
					$sql .= " trip_cancellation_ck='".intval($trip_cancellation_ck)."', ";
				}
			}
		}
		
		if (isset($para['trip_cancellation_insured']) && ($para['trip_cancellation_insured'] != $plan['trip_cancellation_insured'])) {
			$this->logstr .= " trip_cancellation_insured " . $para['trip_cancellation_insured'] . "(" . $plan['trip_cancellation_insured'] . ")";
			$sql .= " trip_cancellation_insured=" . (int)$para['trip_cancellation_insured'] . ", ";
		}
		if (isset($para['questionnaire']) && ($para['questionnaire'] != $plan['questionnaire'])) {
			$this->logstr .= " questionnaire " . $para['questionnaire'] . "(" . $plan['questionnaire'] . ")";
			$sql .= " questionnaire=" . $this->db->escape(trim($para['questionnaire'])) . ", ";
		}
		if (isset($para['question1']) && ($para['question1'] != $plan['question1'])) {
			$this->logstr .= " question1 " . $para['question1'] . "(" . $plan['question1'] . ")";
			$sql .= " question1=" . $this->db->escape(trim($para['question1'])) . ", ";
		}
		if (isset($para['question1_lung']) && ($para['question1_lung'] != $plan['question1_lung'])) {
			$this->logstr .= " question1_lung " . $para['question1_lung'] . "(" . $plan['question1_lung'] . ")";
			$sql .= " question1_lung=" . $this->db->escape(trim($para['question1_lung'])) . ", ";
		}
		if (isset($para['question1_diabets']) && ($para['question1_diabets'] != $plan['question1_diabets'])) {
			$this->logstr .= " question1_diabets " . $para['question1_diabets'] . "(" . $plan['question1_diabets'] . ")";
			$sql .= " question1_diabets=" . $this->db->escape(trim($para['question1_diabets'])) . ", ";
		}
		if (isset($para['question1_heart']) && ($para['question1_heart'] != $plan['question1_heart'])) {
			$this->logstr .= " question1_heart " . $para['question1_heart'] . "(" . $plan['question1_heart'] . ")";
			$sql .= " question1_heart=" . $this->db->escape(trim($para['question1_heart'])) . ", ";
		}
		if (isset($para['question2']) && ($para['question2'] != $plan['question2'])) {
			$this->logstr .= " question2 " . $para['question2'] . "(" . $plan['question2'] . ")";
			$sql .= " question2=" . $this->db->escape(trim($para['question2'])) . ", ";
		}
		if (isset($para['question3']) && ($para['question3'] != $plan['question3'])) {
			$this->logstr .= " question3 " . $para['question3'] . "(" . $plan['question3'] . ")";
			$sql .= " question3=" . $this->db->escape(trim($para['question3'])) . ", ";
		}
		if (isset($para['question3_bowel']) && ($para['question3_bowel'] != $plan['question3_bowel'])) {
			$this->logstr .= " question3_bowel " . $para['question3_bowel'] . "(" . $plan['question3_bowel'] . ")";
			$sql .= " question3_bowel=" . $this->db->escape(trim($para['question3_bowel'])) . ", ";
		}
		if (isset($para['question3_cancer']) && ($para['question3_cancer'] != $plan['question3_cancer'])) {
			$this->logstr .= " question3_cancer " . $para['question3_cancer'] . "(" . $plan['question3_cancer'] . ")";
			$sql .= " question3_cancer=" . $this->db->escape(trim($para['question3_cancer'])) . ", ";
		}
		if (isset($para['question3_diabetes']) && ($para['question3_diabetes'] != $plan['question3_diabetes'])) {
			$this->logstr .= " question3_diabetes " . $para['question3_diabetes'] . "(" . $plan['question3_diabetes'] . ")";
			$sql .= " question3_diabetes=" . $this->db->escape(trim($para['question3_diabetes'])) . ", ";
		}
		if (isset($para['question3_diverticu']) && ($para['question3_diverticu'] != $plan['question3_diverticu'])) {
			$this->logstr .= " question3_diverticu " . $para['question3_diverticu'] . "(" . $plan['question3_diverticu'] . ")";
			$sql .= " question3_diverticu=" . $this->db->escape(trim($para['question3_diverticu'])) . ", ";
		}
		if (isset($para['question3_gerd']) && ($para['question3_gerd'] != $plan['question3_gerd'])) {
			$this->logstr .= " question3_gerd " . $para['question3_gerd'] . "(" . $plan['question3_gerd'] . ")";
			$sql .= " question3_gerd=" . $this->db->escape(trim($para['question3_gerd'])) . ", ";
		}
		if (isset($para['question3_heart']) && ($para['question3_heart'] != $plan['question3_heart'])) {
			$this->logstr .= " question3_heart " . $para['question3_heart'] . "(" . $plan['question3_heart'] . ")";
			$sql .= " question3_heart=" . $this->db->escape(trim($para['question3_heart'])) . ", ";
		}
		if (isset($para['question3_hyper']) && ($para['question3_hyper'] != $plan['question3_hyper'])) {
			$this->logstr .= " question3_hyper " . $para['question3_hyper'] . "(" . $plan['question3_hyper'] . ")";
			$sql .= " question3_hyper=" . $this->db->escape(trim($para['question3_hyper'])) . ", ";
		}
		if (isset($para['question3_kidney']) && ($para['question3_kidney'] != $plan['question3_kidney'])) {
			$this->logstr .= " question3_kidney " . $para['question3_kidney'] . "(" . $plan['question3_kidney'] . ")";
			$sql .= " question3_kidney=" . $this->db->escape(trim($para['question3_kidney'])) . ", ";
		}
		if (isset($para['question3_lung']) && ($para['question3_lung'] != $plan['question3_lung'])) {
			$this->logstr .= " question3_lung " . $para['question3_lung'] . "(" . $plan['question3_lung'] . ")";
			$sql .= " question3_lung=" . $this->db->escape(trim($para['question3_lung'])) . ", ";
		}
		if (isset($para['question3_peptic']) && ($para['question3_peptic'] != $plan['question3_peptic'])) {
			$this->logstr .= " question3_peptic " . $para['question3_peptic'] . "(" . $plan['question3_peptic'] . ")";
			$sql .= " question3_peptic=" . $this->db->escape(trim($para['question3_peptic'])) . ", ";
		}
		if (isset($para['question4']) && ($para['question4'] != $plan['question4'])) {
			$this->logstr .= " question4 " . $para['question4'] . "(" . $plan['question4'] . ")";
			$sql .= " question4=" . $this->db->escape(trim($para['question4'])) . ", ";
		}
		if (isset($para['question5']) && ($para['question5'] != $plan['question5'])) {
			$this->logstr .= " question5 " . $para['question5'] . "(" . $plan['question5'] . ")";
			$sql .= " question5=" . $this->db->escape(trim($para['question5'])) . ", ";
		}
		if (isset($para['claim_flag']) && ($para['claim_flag'] != $plan['claim_flag'])) {
			$this->logstr .= " claim_flag " . $para['claim_flag'] . "(" . $plan['claim_flag'] . ")";
			$sql .= " claim_flag='" . (int)$para['claim_flag'] . "', ";
		}
		if (isset($para['claim_allow_by']) && ($para['claim_allow_by'] != $plan['claim_allow_by'])) {
			$this->logstr .= " claim_allow_by " . $para['claim_allow_by'] . "(" . $plan['claim_allow_by'] . ")";
			$sql .= " claim_allow_by='" . (int)$para['claim_allow_by'] . "', ";
			if (isset($para['claim_allow_note']) && ($para['claim_allow_note'] != $plan['claim_allow_note'])) {
				$this->logstr .= " claim_allow_note " . $para['claim_allow_note'] . "(" . $plan['claim_allow_note'] . ")";
				$sql .= " claim_allow_note=" . $this->db->escape(trim($para['claim_allow_note'])) . ", ";
			}
		}
		
		if ($sql == "UPDATE plan SET") {
			// No change 
			$this->logstr = '';
			$this->sqlstr = '';
			return $plan_id;
		}
		$sql .= " plan_id=plan_id ";
		$sql .= " WHERE plan_id='" . (int)$plan_id . "'";
		$this->db->query($sql);
		$this->sqlstr = $this->db->last_query() . "; ";
    // Change to do it at payment
    // if ($update_history) {
    //   $this->load->model("plan_history_model");
    //   if (($history = $this->plan_history_model->get_plan_history_by_plan_id($plan_id)) && ($history["actualrate"]>0)) {
    //     $this->plan_history_model->add_remove($history["plan_history_id"]);
    //   }
    //   $h_status_id = $plan['status_id'];
    //   if (isset($para['status_id'])) {
    //     $h_status_id = $plan['status_id'];
    //   }

    //   $this->plan_history_model->add($plan_id, $h_status_id);
    // }

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

  public function check_plan_expire($user_id, $start_dt, $end_dt) {
    $this->db->select("plan.plan_id, plan.policy, plan.effective_date, plan.expiry_date, customer.firstname, customer.lastname ");
    $this->db->from('plan');
    $this->db->join('customer', 'plan.customer_id = customer.customer_id');
    $this->db->where( "plan.user_id", $user_id);
    $this->db->where_in( "plan.status_id", [2,3]);
    $this->db->where( "plan.expiry_date >=", $start_dt);
    $this->db->where( "plan.expiry_date <", $end_dt);
		return $this->db->get()->result_array();
	}

  public function check_plan_effective($user_id, $start_dt, $end_dt) {
    $this->db->select("plan.plan_id, plan.policy, plan.effective_date, customer.firstname, customer.lastname ");
    $this->db->from('plan');
    $this->db->join('customer', 'plan.customer_id = customer.customer_id');
    $this->db->where( "plan.user_id", $user_id);
    $this->db->where_in( "plan.status_id", [2,3]);
    $this->db->where( "effective_date >=", $start_dt);
    $this->db->where( "effective_date <", $end_dt);
    $this->db->where_in( "plan.product_short", ["JFR", "JFVTC"]);
    $this->db->where( "plan.sum_insured>=", 100000);
    $this->db->where( "plan.totaldays>=", 365);
		return $this->db->get()->result_array();
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

			$this->load->model('payment_model');
			$this->payment_model->delete_plan($plan_id);

			$this->db->delete('plan', array('plan_id' => $plan_id));
		}
	}
	
	public function plan_activities($user, $para, $limit=0, $start=0, $sorder='', $desc=1) {
    $users = [];
		if ($user["user_group_id"] == 104) {
      $sql = "SELECT user_id FROM user WHERE user_id='".intval($user['user_id'])."' OR parent_user_id='".intval($user['user_id'])."'";
      $rows = $this->db->query($sql)->result_array();
      foreach ($rows as $row) {
        $users[] = $row['user_id'];
      }
    }

		$sql  = "SELECT p.*, c.firstname, c.lastname, c.gender, c.birthday, u.firstname AS agent_firstname, u.lastname AS agent_lastname, u.user_id AS agent_id, u.business_phone as agent_phone FROM plan p";
		$sql .= " INNER JOIN customer c ON (p.customer_id=c.customer_id)";
		$sql .= " INNER JOIN user u ON (p.user_id=u.user_id)";
		$where = array();
		if (!empty($para['user_id'])) {
			$where[] = "p.user_id=" . (int)$para['user_id'];
		}
		if (!empty($para['plan_id'])) {
			$where[] = "p.plan_id=" . (int)$para['plan_id'];
		}
		if (!empty($para['product_short'])) {
			$where[] = "p.product_short=" . $this->db->escape($para['product_short']);
		}
		if (!empty($para['status_id'])) {
			$where[] = "p.status_id='" . (int)$para['status_id'] . "'";
		}
		if (!empty($para['status_ids'])) {
			$where[] = "p.status_id in (" . $this->db->escape($para['status_id']) . ")";
		}
    if (!empty($users)) {
			$where[] = "p.user_id IN (" . join(",", $users) . ")";
		}
		if (!empty($para['policy'])) {
			$where[] = "p.policy=" . $this->db->escape($para['policy']);
		} else if (!empty($para['policy_match'])) {
			$where[] = "p.policy LIKE " . $this->db->escape('%'.$para['policy_match'].'%');
		}
		if (!empty($para['contact_email'])) {
			$where[] = "LCASE(p.contact_email)=" . $this->db->escape(strtolower($para['contact_email']));
		}
		if (!empty($para['contact_phone'])) {
			$where[] = "p.contact_phone=" . $this->db->escape($para['contact_phone']);
		}
		if (!empty($para['institution'])) {
			$where[] = "p.institution=" . $this->db->escape($para['institution']);
		}
		if (!empty($para['batch_number'])) {
			$where[] = "p.batch_number=" . $this->db->escape($para['batch_number']);
		}
		if (!empty($para['firstname'])) {
			$where[] = "LCASE(c.firstname) LIKE " . $this->db->escape("%".strtolower($para['firstname'])."%");
		}
		if (!empty($para['lastname'])) {
			$where[] = "LCASE(c.lastname) LIKE " . $this->db->escape("%".strtolower($para['lastname'])."%");
		}
    if (!empty($para['birthday'])) {
			if (!empty($para['birthday2'])) {
				$carr[] = "birthday >= " . $this->db->escape($para['birthday']);
				$carr[] = "birthday <= " . $this->db->escape($para['birthday2']);
			} else {
				$carr[] = "birthday >= " . $this->db->escape($para['birthday']);
			}
		}
    if (!empty($para['uname'])) {
      $where[] = "(u.firstname LIKE " . $this->db->escape($para['uname'] . "%") . " OR u.lastname LIKE " . $this->db->escape($para['uname'] . "%") .")";
    }
		if (!empty($para['student_id'])) {
			$where[] = "p.student_id=" . $this->db->escape($para['student_id']);
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
    if (!empty($where)) {
			$sql .= " WHERE " . join(" AND ", $where);
		}
    if ($sorder) {
      if ($desc == 1) {
        $sql .= " ORDER BY ".$sorder." DESC";
      } else {
        $sql .= " ORDER BY ".$sorder." ASC";
      }
    } else {
      $sql .= " ORDER BY plan_id DESC";
    }
		if ($limit) {
			if ($start) {
				$sql .= " LIMIT " . (int)$start . ", " . (int)$limit;
			} else {
				$sql .= " LIMIT " . (int)$limit;
			}
		} else {
			$sql .= " LIMIT " . self::MAX_PLANS;
		}
		return $this->db->query($sql)->result_array();
	}

	public function plan_activitie_totals($user, $para) {
    $users = [];
		if ($user["user_group_id"] == 104) {
      $sql = "SELECT user_id FROM user WHERE user_id='".intval($user['user_id'])."' OR parent_user_id='".intval($user['user_id'])."'";
      $rows = $this->db->query($sql)->result_array();
      foreach ($rows as $row) {
        $users[] = $row['user_id'];
      }
    }
		
		$sql  = "SELECT p.*, c.firstname, c.lastname, c.gender, c.birthday, u.firstname AS agent_firstname, u.lastname AS agent_lastname, u.user_id AS agent_id, u.business_phone as agent_phone FROM plan p";
		$sql .= " INNER JOIN customer c ON (p.customer_id=c.customer_id)";
		$sql .= " INNER JOIN user u ON (p.user_id=u.user_id)";
		$where = array();
		if (!empty($para['user_id'])) {
			$where[] = "p.user_id=" . (int)$para['user_id'];
		}
		if (!empty($para['status_id'])) {
			$where[] = "p.status_id='" . (int)$para['status_id'] . "'";
		}
		if (!empty($para['plan_id'])) {
			$where[] = "p.plan_id=" . (int)$para['plan_id'];
		}
		if (!empty($para['product_short'])) {
			$where[] = "p.product_short=" . $this->db->escape($para['product_short']);
		}
    if (!empty($users)) {
			$where[] = "p.user_id IN (" . join(",", $users) . ")";
		}
		if (!empty($para['policy'])) {
			$where[] = "p.policy=" . $this->db->escape($para['policy']);
		} else if (!empty($para['policy_match'])) {
			$where[] = "p.policy LIKE " . $this->db->escape('%'.$para['policy_match'].'%');
		}
		if (!empty($para['contact_email'])) {
			$where[] = "LCASE(p.contact_email)=" . $this->db->escape(strtolower($para['contact_email']));
		}
		if (!empty($para['contact_phone'])) {
			$where[] = "p.contact_phone=" . $this->db->escape($para['contact_phone']);
		}
		if (!empty($para['institution'])) {
			$where[] = "p.institution=" . $this->db->escape($para['institution']);
		}
		if (!empty($para['batch_number'])) {
			$where[] = "p.batch_number=" . $this->db->escape($para['batch_number']);
		}
		if (!empty($para['firstname'])) {
			$where[] = "LCASE(c.firstname) LIKE " . $this->db->escape("%".strtolower($para['firstname'])."%");
		}
		if (!empty($para['lastname'])) {
			$where[] = "LCASE(c.lastname) LIKE " . $this->db->escape("%".strtolower($para['lastname'])."%");
		}
    if (!empty($para['birthday'])) {
			if (!empty($para['birthday2'])) {
				$where[] = "birthday >= " . $this->db->escape($para['birthday']);
				$where[] = "birthday <= " . $this->db->escape($para['birthday2']);
			} else {
				$where[] = "birthday >= " . $this->db->escape($para['birthday']);
			}
		}
    if (!empty($para['uname'])) {
      $where[] = "(u.firstname LIKE " . $this->db->escape($para['uname'] . "%") . " OR u.lastname LIKE " . $this->db->escape($para['uname'] . "%") .")";
    }

		if (!empty($para['student_id'])) {
			$where[] = "p.student_id=" . $this->db->escape($para['student_id']);
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
    if (!empty($where)) {
			$sql .= " WHERE " . join(" AND ", $where);
		}
		return $this->db->query($sql)->num_rows();
	}

  /**
	 * Search Plans (policies) by conditions
	 * 
	 * @param	array	$para		Search parameters
	 * @param	int		$limit		limit
	 * @return	array					user table search result
	 */
	public function plan_search($para, $limit=0, $start=0) {
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
		$sql  = "SELECT p.*, c.firstname, c.lastname, c.gender, c.birthday, u.firstname AS agent_firstname, u.lastname AS agent_lastname, u.user_id AS agent_id, u.business_phone as agent_phone FROM plan p";
		$sql .= " INNER JOIN customer c ON (p.customer_id=c.customer_id)";
		$sql .= " INNER JOIN user u ON (p.user_id=u.user_id)";
		$where = array();
		if (!empty($para['plan_id'])) {
			$where[] = "p.plan_id=" . (int)$para['plan_id'];
		}
		if (!empty($para['policy'])) {
			$where[] = "p.policy=" . $this->db->escape($para['policy']);
		} else if (!empty($para['policy_match'])) {
			$where[] = "p.policy LIKE " . $this->db->escape('%'.$para['policy_match'].'%');
		}
		if (!empty($para['batch_number'])) {
			$where[] = "p.batch_number=" . $this->db->escape($para['batch_number']);
		}
		if (!empty($para['student_id'])) {
			$where[] = "p.student_id=" . $this->db->escape($para['student_id']);
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
			if ($start) {
				$sql .= " LIMIT " . (int)$start . ", " . (int)$limit;
			} else {
				$sql .= " LIMIT " . (int)$limit;
			}
		} else {
			$sql .= " LIMIT " . self::MAX_PLANS;
		}
		return $this->db->query($sql)->result_array();
	}

	public function plan_search_count($para) {
    $beuser = $this->session->userdata('beuser');
		if (empty($beuser)) {
			return 0;
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
				return 0;
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
		$sql  = "SELECT count(*) as cnt FROM plan p";
		$sql .= " INNER JOIN customer c ON (p.customer_id=c.customer_id)";
		$sql .= " INNER JOIN user u ON (p.user_id=u.user_id)";
		$where = array();
		if (!empty($para['plan_id'])) {
			$where[] = "p.plan_id=" . (int)$para['plan_id'];
		}
		if (!empty($para['policy'])) {
			$where[] = "p.policy=" . $this->db->escape($para['policy']);
		} else if (!empty($para['policy_match'])) {
			$where[] = "p.policy LIKE " . $this->db->escape('%'.$para['policy_match'].'%');
		}
		if (!empty($para['batch_number'])) {
			$where[] = "p.batch_number=" . $this->db->escape($para['batch_number']);
		}
		if (!empty($para['student_id'])) {
			$where[] = "p.student_id=" . $this->db->escape($para['student_id']);
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
		}
		if ($rt = $this->db->query($sql)->row_array()) {
			return $rt['cnt'];
		}
		return 0;
	}
	
	/**
	 * Search Plans (policies) by conditions for export
	 * 
	 * @param	array	$para		Search parameters
	 * @param	int		$limit		limit number
	 * @param	int		$start		start from number
	 * @param	int		$payment		need payment
	 * @return	array					user table search result
	 */
	public function plan_export_search($para, $payment=0, $limit=0, $start=0) {
		$beuser = $this->session->userdata('beuser');
		if (empty($beuser)) {
			return array();
		}
		
		$rArr = $this->plan_search($para, $limit, $start);
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
					$rArr[$i]['relationship_'.$m] = $cArr[$j]['relationship'];
				}
			}
			if ($payment) {
				$nameArr = $this->db->list_fields('payment');
				$this->db->where('plan_id', $rArr[$i]['plan_id']);
				// $this->db->where_in('pay_type', array('premium','refund');
				$this->db->where('amount>', 0.001);
				$pArr = $this->db->get('payment')->result_array();
				for ($j = 0; $j < sizeof($pArr); $j++) {
					foreach ($nameArr as $name) {
						$rArr[$i][$name . '_' . $j] = $pArr[$j][$name];
					}
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
	
	/**
	 * Get summary for claim system
	 * 
	 * @param array	input parameter
	 * @return array 
	 */
	public function claim_summary($data) {
		$products = " AND product_short IN ('JFS','JFE','BHS','JES',JFPL','JFSL','JFGD','JFOS','JESP','JUS','JFC','JFP','NUS','JFVTC','JFR','OPL','TOP','TOPN')";
		$st = new DateTime($data['start_dt']);
		$et = new DateTime($data['end_dt']);
		$interval = new DateInterval('P1M');
		
		$ps = array();
		if (isset($data['agent_id']) || isset($data['product_short'])) {
			$ststr = $st->format("Y-m-01 00:00:00");
			$edstr = $et->format("Y-m-t 23:59:59");
			$sql  = "SELECT DISTINCT policy FROM plan WHERE status_id IN (".SELF::CHANGED.",".SELF::PAID.",".SELF::SOLD.",".SELF::CLAIMED.")";
			if (isset($data['agent_id'])) {
				$sql .= " AND user_id=".$this->db->escape($data['agent_id']);
			}
			if (isset($data['product_short'])) {
				$sql .= " AND product_short=".$this->db->escape($data['product_short']);
			} else {
				$sql .= $products;
			}
			$sql .= " AND effective_date<=".$this->db->escape($edstr)." AND expiry_date>=".$this->db->escape($ststr);
			$policies = $this->db->query($sql)->result_array();
			foreach ($policies as $p) {
				$ps[] = $p['policy'];
			}
		}

		$rt = array();
		while ($st <= $et) {
			$monthstr = $st->format("Y-m");
			$ststr = $st->format("Y-m-01 00:00:00");
			$edstr = $st->format("Y-m-t 23:59:59");
			$st->add($interval);
			
			$rt[$monthstr] = array('writen' => 0, 'earned' => 0);

			$sql  = "SELECT SUM(premium) as writen FROM plan WHERE status_id IN (".SELF::CHANGED.",".SELF::PAID.",".SELF::SOLD.",".SELF::CLAIMED.")";
			if (isset($data['agent_id'])) {
				$sql .= " AND user_id=".$this->db->escape($data['agent_id']);
			}
			if (isset($data['product_short'])) {
				$sql .= " AND product_short=".$this->db->escape($data['product_short']);
			} else {
				$sql .= $products;
			}
			$sql .= " AND effective_date<=".$this->db->escape($edstr)." AND effective_date>=".$this->db->escape($ststr);
			if ($mrt = $this->db->query($sql)->row_array()) {
				$rt[$monthstr]['writen'] = (float)$mrt['writen'];
			}

			$sql  = "SELECT SUM(premium * (DATEDIFF(IF(expiry_date>".$this->db->escape($edstr).",".$this->db->escape($edstr).",expiry_date),IF(effective_date<".$this->db->escape($ststr).",".$this->db->escape($ststr).",effective_date))) / DATEDIFF(expiry_date, effective_date)) as earned";
			$sql .= " FROM plan WHERE status_id IN (".SELF::CHANGED.",".SELF::PAID.",".SELF::SOLD.",".SELF::CLAIMED.")";
			if (isset($data['agent_id'])) {
				$sql .= " AND user_id=".$this->db->escape($data['agent_id']);
			}
			if (isset($data['product_short'])) {
				$sql .= " AND product_short=".$this->db->escape($data['product_short']);
			} else {
				$sql .= $products;
			}
			$sql .= " AND effective_date<=".$this->db->escape($edstr)." AND expiry_date>=".$this->db->escape($ststr);
			if ($mrt = $this->db->query($sql)->row_array()) {
				$rt[$monthstr]['earned'] = (float)$mrt['earned'];
			}
		}
		return array('summary' => $rt, 'policies' => $ps);
	}

  /**
	 * Get user performance
	 * 
	 * @param integer $user_id
	 * @return array 
	 */
	public function get_performance($user_id) {
    $month_start = date("Y-m-01 00:00:00");
    $year_start = date("Y-01-01 00:00:00");
    $rt = array("month_count" => 0, "month_amount" => 0, "year_count" => 0, "year_amount" => 0);
		$sql = "SELECT COUNT(*) as month_count, SUM(premium) as month_amount FROM plan WHERE user_id='".intval($user_id)."' AND status_id IN (2,3) AND apply_date>='".$month_start."'";
		if ($rc = $this->db->query($sql)->row_array()) {
      $rt["month_count"] = $rc["month_count"];
      $rt["month_amount"] = empty($rc["month_amount"])?0:$rc["month_amount"];
    }
		$sql = "SELECT COUNT(*) as year_count, SUM(premium) as year_amount FROM plan WHERE user_id='".intval($user_id)."' AND status_id IN (2,3) AND apply_date>='".$year_start."'";
		if ($rc = $this->db->query($sql)->row_array()) {
      $rt["year_count"] = $rc["year_count"];
      $rt["year_amount"] = empty($rc["year_amount"])?0:$rc["year_amount"];
    }
		return $rt;
	}
}
