<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Batch_model extends CI_Model {
	public $error;
	
	function unixstamp( $excelDateTime ) {
		$excelDateTime =trim($excelDateTime);
		if (ctype_digit($excelDateTime)) {
			$d = floor( $excelDateTime ); // seconds since 1900
			$t = $excelDateTime - $d + 1;
			return ($d > 0) ? ( $d - 25569 ) * 86400 + $t * 86400 : $t * 86400;
		} else {
			return strtotime($excelDateTime);
		}
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

		$data = array();

		if (empty($para['customer_id'])) { ; } else { $data['customer_id'] = $para['customer_id']; }
		if (empty($para['user_id'])) { ; } else { $data['user_id'] = $para['user_id']; }
		if (empty($para['status_id'])) { $data['status_id'] = 2; } else { $data['status_id'] = $para['status_id']; }
		if (empty($para['policy'])) { $data['policy'] = $this->plan_model->get_policy_number($plan_id, 2); } else { $data['policy'] = $para['policy']; }
		if (empty($para['product_short'])) { 
			$this->error = 'Need product_short';
			return 0;
		} else {
			$data['product_short'] = $para['product_short']; 
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
		} else {
			$plan_id = $this->plan_model->update($para['plan_id'], $data);
		}
		if ($plan_id) {
			$plan = $this->plan_model->get_plan_by_id($plan_id);
			$para1 = array(
					'plan_id' => $plan_id,
					'customer_id' => $plan['customer_id'],
					'payment_id' => 0,
					'message' => $this->plan_model->logstr,
					'systemlog' => $this->plan_model->sqlstr
			);
			$this->log_model->activity('plan', $para1);
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
}
