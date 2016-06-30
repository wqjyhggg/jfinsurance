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
		if (isset($para['product_short'])) $this->db->where('product_short', $para['product_short']);
		if (isset($para['claim_date2'])) {
			if (isset($para['claim_date'])) {
				$this->db->where('claim_date >=', $para['claim_date']);
				$this->db->where('claim_date <=', $para['claim_date2']);
			}
		} else {
			if (isset($para['claim_date'])) {
				$this->db->where('claim_date', $para['claim_date']);
			}
		}
		if (isset($para['policy_number'])) $this->db->like('policy_number', $para['policy_number']);
		if (isset($para['claim_number'])) $this->db->like('claim_number', $para['claim_number']);
		if (isset($para['lastname'])) $this->db->like('lastname', $para['lastname']);
		if (isset($para['firstname'])) $this->db->like('firstname', $para['firstname']);
		if (isset($para['cheque_number'])) $this->db->like('cheque_number', $para['cheque_number']);
		return $this->db->get()->result_array();
	}
	
	/**
	 * Get Claim record
	 * 
	 * @param	integer	$claim_id
	 * @return	array					user table search result
	 */
	public function get_claim_by_id($claim_id) {
		$this->db->where('claim_id', $claim_id);
		return $this->db->get()->row_array();
	}
	
	/**
	 * Add Claim record
	 * 
	 * @param	array	$para	Parameter array
	 * @return	array					user table search result
	 */
	public function add($para) {
		$this->db->insert($para);
		$this->sqlstr = $this->db->last_query();
		$claim_id = $this->db->insert_id();
		$this->logstr = "Add claim record (" . $claim_id . ") : " . join(',', $para);
		return $claim_id;
	}
	
	/**
	 * Update Claim record
	 * 
	 * @param	integer	$claim_id
	 * @param	array	$para	Parameter array
	 * @return	array					user table search result
	 */
	public function update($claim_id, $para) {
		$this->db->insert($para);
		$this->sqlstr = $this->db->last_query();
		$claim_id = $this->db->insert_id();
		$this->logstr = "Add claim record (" . $claim_id . ") : " . join(',', $para);
		return $claim_id;
	}
}
