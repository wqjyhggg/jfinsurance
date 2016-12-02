<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Claim_model extends CI_Model {
	public $error;
	public $logstr;
	public $sqlstr;
	
	/**
	 * Add / Update policy record
	 * 
	 * @param	array	$para	Parameter array
	 * @return	array					user table search result
	 */
	public function search($para) {
		if (!empty($para['plan_id'])) $this->db->where('plan_id', $para['plan_id']);
		if (!empty($para['product_short'])) $this->db->where('product_short', $para['product_short']);
		if (!empty($para['claim_date2'])) {
			if (!empty($para['claim_date'])) {
				$this->db->where('claim_date >=', $para['claim_date']);
				$this->db->where('claim_date <=', $para['claim_date2']);
			}
			
		} else {
			if (!empty($para['claim_date'])) {
				$this->db->where('claim_date >=', $para['claim_date']);
			}
		}
		if (!empty($para['policy_number'])) $this->db->like('policy_number', $para['policy_number']);
		if (!empty($para['claim_number'])) $this->db->like('claim_number', $para['claim_number']);
		if (!empty($para['lastname'])) $this->db->like('lastname', $para['lastname']);
		if (!empty($para['firstname'])) $this->db->like('firstname', $para['firstname']);
		$this->db->order_by('claim_id', 'DESC');
		$arr = $this->db->get('claim')->result_array();
		return $arr;
	}
	
	/**
	 * Get Claim record
	 * 
	 * @param	integer	$claim_id
	 * @return	array					user table search result
	 */
	public function get_claim_by_id($claim_id) {
		$this->db->where('claim_id', $claim_id);
		return $this->db->get('claim')->row_array();
	}
	
	/**
	 * Get Claim record
	 * 
	 * @param	integer	$citem_id
	 * @return	array					user table search result
	 */
	public function get_claim_item_by_id($citem_id) {
		$this->db->where('citem_id', $citem_id);
		return $this->db->get('citem')->row_array();
	}
	
	/**
	 * Get Claim record
	 * 
	 * @param	integer	$claim_id
	 * @return	array					user table search result
	 */
	public function get_item_list($claim_id) {
		$this->db->where('claim_id', $claim_id);
		$this->db->order_by('citem_id', 'asc');
		return $this->db->get('citem')->result_array();
	}
	
	/**
	 * Add Claim record
	 * 
	 * @param	array	$para	Parameter array
	 * @return	array					user table search result
	 */
	public function add($para) {
		$this->db->insert('claim', $para);
		$this->sqlstr = $this->db->last_query();
		$claim_id = $this->db->insert_id();
		$uarr = array('claim_number' => 600000 + $claim_id);
		$this->update($claim_id, $uarr);
		$this->logstr = "Add claim record (" . $claim_id . ") : " . join(',', $para);
		return $claim_id;
	}
	
	/**
	 * Add Claim Item record
	 * 
	 * @param	array	$para	Parameter array
	 * @return	array					user table search result
	 */
	public function additem($para) {
		$user = $this->session->userdata('user');
		if (!empty($para['internal_note'])) {
			$para['internal_note'] = "--" . $user['username'] . "--" . date('Ymd His') . "--\n" . $para['internal_note'];
		}
		$this->db->insert('citem', $para);
		$this->sqlstr = $this->db->last_query();
		$citem_id = $this->db->insert_id();
		$this->logstr = "Add claim record (" . $citem_id . ") : " . join(',', $para);
		return $citem_id;
	}
	
	/**
	 * Update Claim record
	 * 
	 * @param	integer	$claim_id
	 * @param	array	$para	Parameter array
	 * @return	array					user table search result
	 */
	public function update($claim_id, $para) {
		$claim = $this->get_claim_by_id($claim_id);
		$data = array();
		if ($claim) {
			if (isset($para['plan_id']) && ($para['plan_id'] != $claim['plan_id'])) {
				$data['plan_id'] = $para['plan_id'];
				$this->logstr .= " plan_id " . $para['plan_id'] . "(" . $claim['plan_id'] . ")";
			}
			if (isset($para['user_id']) && ($para['user_id'] != $claim['user_id'])) {
				$data['user_id'] = $para['user_id'];
				$this->logstr .= " user_id " . $para['user_id'] . "(" . $claim['user_id'] . ")";
			}
			if (isset($para['customer_id']) && ($para['customer_id'] != $claim['customer_id'])) {
				$data['customer_id'] = $para['customer_id'];
				$this->logstr .= " customer_id " . $para['customer_id'] . "(" . $claim['customer_id'] . ")";
			}
			if (isset($para['done']) && ($para['done'] != $claim['done'])) {
				$data['done'] = $para['done'];
				$this->logstr .= " done " . $para['done'] . "(" . $claim['done'] . ")";
			}
			if (isset($para['product_short']) && ($para['product_short'] != $claim['product_short'])) {
				$data['product_short'] = $para['product_short'];
				$this->logstr .= " product_short " . $para['product_short'] . "(" . $claim['product_short'] . ")";
			}
			if (isset($para['policy_number']) && ($para['policy_number'] != $claim['policy_number'])) {
				$data['policy_number'] = $para['policy_number'];
				$this->logstr .= " policy_number " . $para['policy_number'] . "(" . $claim['policy_number'] . ")";
			}
			if (isset($para['claim_number']) && ($para['claim_number'] != $claim['claim_number'])) {
				$data['claim_number'] = $para['claim_number'];
				$this->logstr .= " claim_number " . $para['claim_number'] . "(" . $claim['claim_number'] . ")";
			}
			if (isset($para['lastname']) && ($para['lastname'] != $claim['lastname'])) {
				$data['lastname'] = $para['lastname'];
				$this->logstr .= " lastname " . $para['lastname'] . "(" . $claim['lastname'] . ")";
			}
			if (isset($para['firstname']) && ($para['firstname'] != $claim['firstname'])) {
				$data['firstname'] = $para['firstname'];
				$this->logstr .= " firstname " . $para['firstname'] . "(" . $claim['firstname'] . ")";
			}
			if (isset($para['birthday']) && ($para['birthday'] != $claim['birthday'])) {
				$data['birthday'] = $para['birthday'];
				$this->logstr .= " birthday " . $para['birthday'] . "(" . $claim['birthday'] . ")";
			}
			if (isset($para['gender']) && ($para['gender'] != $claim['gender'])) {
				$data['gender'] = $para['gender'];
				$this->logstr .= " gender " . $para['gender'] . "(" . $claim['gender'] . ")";
			}
			if (isset($para['claim_date']) && ($para['claim_date'] != $claim['claim_date'])) {
				$data['claim_date'] = $para['claim_date'];
				$this->logstr .= " claim_date " . $para['claim_date'] . "(" . $claim['claim_date'] . ")";
			}
			if (isset($para['claimed']) && ($para['claimed'] != $claim['claimed'])) {
				$data['claimed'] = $para['claimed'];
				$this->logstr .= " claimed " . $para['claimed'] . "(" . $claim['claimed'] . ")";
			}
			if (isset($para['paid']) && ($para['paid'] != $claim['paid'])) {
				$data['paid'] = $para['paid'];
				$this->logstr .= " paid " . $para['paid'] . "(" . $claim['paid'] . ")";
			}
			if (isset($para['pay_to']) && ($para['pay_to'] != $claim['pay_to'])) {
				$data['pay_to'] = $para['pay_to'];
				$this->logstr .= " pay_to " . $para['pay_to'] . "(" . $claim['pay_to'] . ")";
			}
			if (isset($para['invoice_num']) && ($para['invoice_num'] != $claim['invoice_num'])) {
				$data['invoice_num'] = $para['invoice_num'];
				$this->logstr .= " invoice_num " . $para['invoice_num'] . "(" . $claim['invoice_num'] . ")";
			}
			if (isset($para['cheque_number']) && ($para['cheque_number'] != $claim['cheque_number'])) {
				$data['cheque_number'] = $para['cheque_number'];
				$this->logstr .= " cheque_number " . $para['cheque_number'] . "(" . $claim['cheque_number'] . ")";
			}
			if (isset($para['coverage_code_id']) && ($para['coverage_code_id'] != $claim['coverage_code_id'])) {
				$data['coverage_code_id'] = $para['coverage_code_id'];
				$this->logstr .= " coverage_code_id " . $para['coverage_code_id'] . "(" . $claim['coverage_code_id'] . ")";
			}
			if (isset($para['service_date']) && ($para['service_date'] != $claim['service_date'])) {
				$data['service_date'] = $para['service_date'];
				$this->logstr .= " service_date " . $para['service_date'] . "(" . $claim['service_date'] . ")";
			}
			if (isset($para['diagnosis']) && ($para['diagnosis'] != $claim['diagnosis'])) {
				$data['diagnosis'] = $para['diagnosis'];
				$this->logstr .= " diagnosis " . $para['diagnosis'] . "(" . $claim['diagnosis'] . ")";
			}
			if (isset($para['memo']) && ($para['memo'] != $claim['memo'])) {
				$data['memo'] = $para['memo'];
				$this->logstr .= " memo " . $para['memo'] . "(" . $claim['memo'] . ")";
			}
			if (isset($para['decline_reason']) && ($para['decline_reason'] != $claim['decline_reason'])) {
				$data['decline_reason'] = $para['decline_reason'];
				$this->logstr .= " decline_reason " . $para['decline_reason'] . "(" . $claim['decline_reason'] . ")";
			}

			if (empty($data)) {
				$this->sqlstr = '';
				$this->logstr = "No Update";
				return $claim_id;
			}
			$this->db->where('claim_id', $claim_id);
			$this->db->update('claim', $data);
			$this->sqlstr = $this->db->last_query();
			$this->logstr = "Update claim record (" . $claim_id . ") : " . join(',', $data);
			if (isset($data['done'])) {
				// cange claim status, check change plan status back or not
				$this->db->where('plan_id', $claim['plan_id']);
				$this->db->where('done !=', 1);
				if (empty($this->db->get('claim')->row_array())) {
					$this->db->set('status_id' , 3);
					$this->db->where('plan_id' , $claim['plan_id']);
					$this->db->update('plan');
					$this->sqlstr .= '; ' . $this->db->last_query();
					$this->logstr .= "Update plan: " . $claim['plan_id'] . " to paid";
				}
			}
		}
		return $claim_id;
	}

	/**
	 * Update Claim Item record
	 * 
	 * @param	integer	$citem_id
	 * @param	array	$para	Parameter array
	 * @return	array					user table search result
	 */
	public function updateitem($citem_id, $para) {
		$citem = $this->get_claim_item_by_id($citem_id);
		$data = array();
		if ($citem) {
			if (isset($para['claim_id']) && ($para['claim_id'] != $citem['claim_id'])) {
				$data['claim_id'] = $para['claim_id'];
				$this->logstr .= " claim_id " . $para['claim_id'] . "(" . $citem['claim_id'] . ")";
			}
			if (isset($para['plan_id']) && ($para['plan_id'] != $citem['plan_id'])) {
				$data['plan_id'] = $para['plan_id'];
				$this->logstr .= " plan_id " . $para['plan_id'] . "(" . $citem['plan_id'] . ")";
			}
			if (isset($para['user_id']) && ($para['user_id'] != $citem['user_id'])) {
				$data['user_id'] = $para['user_id'];
				$this->logstr .= " user_id " . $para['user_id'] . "(" . $citem['user_id'] . ")";
			}
			if (isset($para['customer_id']) && ($para['customer_id'] != $citem['customer_id'])) {
				$data['customer_id'] = $para['customer_id'];
				$this->logstr .= " customer_id " . $para['customer_id'] . "(" . $citem['customer_id'] . ")";
			}
			if (isset($para['done']) && ($para['done'] != $citem['done'])) {
				$data['done'] = $para['done'];
				$this->logstr .= " done " . $para['done'] . "(" . $citem['done'] . ")";
			}
			if (isset($para['product_short']) && ($para['product_short'] != $citem['product_short'])) {
				$data['product_short'] = $para['product_short'];
				$this->logstr .= " product_short " . $para['product_short'] . "(" . $citem['product_short'] . ")";
			}
			if (isset($para['policy_number']) && ($para['policy_number'] != $citem['policy_number'])) {
				$data['policy_number'] = $para['policy_number'];
				$this->logstr .= " policy_number " . $para['policy_number'] . "(" . $citem['policy_number'] . ")";
			}
			if (isset($para['claim_number']) && ($para['claim_number'] != $citem['claim_number'])) {
				$data['claim_number'] = $para['claim_number'];
				$this->logstr .= " claim_number " . $para['claim_number'] . "(" . $citem['claim_number'] . ")";
			}
			if (isset($para['lastname']) && ($para['lastname'] != $citem['lastname'])) {
				$data['lastname'] = $para['lastname'];
				$this->logstr .= " lastname " . $para['lastname'] . "(" . $citem['lastname'] . ")";
			}
			if (isset($para['firstname']) && ($para['firstname'] != $citem['firstname'])) {
				$data['firstname'] = $para['firstname'];
				$this->logstr .= " firstname " . $para['firstname'] . "(" . $citem['firstname'] . ")";
			}
			if (isset($para['birthday']) && ($para['birthday'] != $citem['birthday'])) {
				$data['birthday'] = $para['birthday'];
				$this->logstr .= " birthday " . $para['birthday'] . "(" . $citem['birthday'] . ")";
			}
			if (isset($para['gender']) && ($para['gender'] != $citem['gender'])) {
				$data['gender'] = $para['gender'];
				$this->logstr .= " gender " . $para['gender'] . "(" . $citem['gender'] . ")";
			}
			if (isset($para['claim_date']) && ($para['claim_date'] != $citem['claim_date'])) {
				$data['claim_date'] = $para['claim_date'];
				$this->logstr .= " claim_date " . $para['claim_date'] . "(" . $citem['claim_date'] . ")";
			}
			if (isset($para['claimed']) && ($para['claimed'] != $citem['claimed'])) {
				$data['claimed'] = $para['claimed'];
				$this->logstr .= " claimed " . $para['claimed'] . "(" . $citem['claimed'] . ")";
			}
			if (isset($para['paid']) && ($para['paid'] != $citem['paid'])) {
				$data['paid'] = $para['paid'];
				$this->logstr .= " paid " . $para['paid'] . "(" . $citem['paid'] . ")";
			}
			if (isset($para['pay_to']) && ($para['pay_to'] != $citem['pay_to'])) {
				$data['pay_to'] = $para['pay_to'];
				$this->logstr .= " pay_to " . $para['pay_to'] . "(" . $citem['pay_to'] . ")";
			}
			if (isset($para['cheque_number']) && ($para['cheque_number'] != $citem['cheque_number'])) {
				$data['cheque_number'] = $para['cheque_number'];
				$this->logstr .= " cheque_number " . $para['cheque_number'] . "(" . $citem['cheque_number'] . ")";
			}
			if (isset($para['coverage_code_id']) && ($para['coverage_code_id'] != $citem['coverage_code_id'])) {
				$data['coverage_code_id'] = $para['coverage_code_id'];
				$this->logstr .= " coverage_code_id " . $para['coverage_code_id'] . "(" . $citem['coverage_code_id'] . ")";
			}
			if (isset($para['service_date']) && ($para['service_date'] != $citem['service_date'])) {
				$data['service_date'] = $para['service_date'];
				$this->logstr .= " service_date " . $para['service_date'] . "(" . $citem['service_date'] . ")";
			}
			if (isset($para['paid_date']) && ($para['paid_date'] != $citem['paid_date'])) {
				$data['paid_date'] = $para['paid_date'];
				$this->logstr .= " paid_date " . $para['paid_date'] . "(" . $citem['paid_date'] . ")";
			}
			if (isset($para['eob_date']) && ($para['eob_date'] != $citem['eob_date'])) {
				$data['eob_date'] = $para['eob_date'];
				$this->logstr .= " eob_date " . $para['eob_date'] . "(" . $citem['eob_date'] . ")";
			}
			if (isset($para['received']) && ($para['received'] != $citem['received'])) {
				$data['received'] = $para['received'];
				$this->logstr .= " received " . $para['received'] . "(" . $citem['received'] . ")";
			}
			if (isset($para['cashed_date']) && ($para['cashed_date'] != $citem['cashed_date'])) {
				$data['cashed_date'] = $para['cashed_date'];
				$this->logstr .= " cashed_date " . $para['cashed_date'] . "(" . $citem['cashed_date'] . ")";
			}
			if (isset($para['eob_cheque_no']) && ($para['eob_cheque_no'] != $citem['eob_cheque_no'])) {
				$data['eob_cheque_no'] = $para['eob_cheque_no'];
				$this->logstr .= " eob_cheque_no " . $para['eob_cheque_no'] . "(" . $citem['eob_cheque_no'] . ")";
			}
			if (isset($para['invoice_number']) && ($para['invoice_number'] != $citem['invoice_number'])) {
				$data['invoice_number'] = $para['invoice_number'];
				$this->logstr .= " invoice_number " . $para['invoice_number'] . "(" . $citem['invoice_number'] . ")";
			}
			if (isset($para['address']) && ($para['address'] != $citem['address'])) {
				$data['address'] = $para['address'];
				$this->logstr .= " address " . $para['address'] . "(" . $citem['address'] . ")";
			}
			if (isset($para['city']) && ($para['city'] != $citem['city'])) {
				$data['city'] = $para['city'];
				$this->logstr .= " city " . $para['city'] . "(" . $citem['city'] . ")";
			}
			if (isset($para['province2']) && ($para['province2'] != $citem['province2'])) {
				$data['province2'] = $para['province2'];
				$this->logstr .= " province2 " . $para['province2'] . "(" . $citem['province2'] . ")";
			}
			if (isset($para['country2']) && ($para['country2'] != $citem['country2'])) {
				$data['country2'] = $para['country2'];
				$this->logstr .= " country2 " . $para['country2'] . "(" . $citem['country2'] . ")";
			}
			if (isset($para['postcode']) && ($para['postcode'] != $citem['postcode'])) {
				$data['postcode'] = $para['postcode'];
				$this->logstr .= " postcode " . $para['postcode'] . "(" . $citem['postcode'] . ")";
			}
			if (isset($para['diagnosis']) && ($para['diagnosis'] != $citem['diagnosis'])) {
				$data['diagnosis'] = $para['diagnosis'];
				$this->logstr .= " diagnosis " . $para['diagnosis'] . "(" . $citem['diagnosis'] . ")";
			}
			if (!empty($para['internal_note'])) {
				$user = $this->session->userdata('user');
				if (!empty($citem['internal_note'])) {
					$data['internal_note'] = $citem['internal_note'] . "\n--" . $user['username'] . "--" . date('Ymd His') . "--\n" . $para['internal_note'];
				} else {
					$data['internal_note'] = "--" . $user['username'] . "--" . date('Ymd His') . "--\n" . $para['internal_note'];
				}
				$this->logstr .= " internal_note " . $para['internal_note'];
			}
			if (isset($para['external_note']) && ($para['external_note'] != $citem['external_note'])) {
				$data['external_note'] = $para['external_note'];
				$this->logstr .= " external_note " . $para['external_note'] . "(" . $citem['external_note'] . ")";
			}

			if (empty($data)) {
				$this->sqlstr = '';
				$this->logstr = "No Update";
				return $citem_id;
			}
			$this->db->where('citem_id', $citem_id);
			$this->db->update('citem', $data);
			$this->sqlstr = $this->db->last_query();
			$this->logstr = "Update claim item record (" . $citem_id . ") : " . join(',', $data);
		}
		return $citem_id;
	}

	/**
	 * Update Claim Item record
	 * 
	 * @param	integer	$citem_id
	 * @param	array	$para	Parameter array
	 * @return	array					user table search result
	 */
	public function getClaimTotal($claim_id) {
		$this->db->select_sum('claimed');
		$this->db->where('claim_id', $claim_id);
		return $this->db->get('citem')->row()->claimed;
	}
}
