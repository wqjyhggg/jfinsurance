<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Trans_model extends CI_Model {
	public $logstr;
	public $sqlstr;
	
	/**
	 * Sort pay type string
	 * 
	 * @param	string	$str	String for format
	 * @return	string				permitied string
	 */
	public function pay_type($str) {
		switch ($str) {
			case 'premium': return 'premium';
			case 'commission': return 'commission';
		}
		return 'unknown : ' . $str;
	}

	/**
	 * Add payment data
	 * 
	 * @param	array	$para	Add payment record
	 * @return	integer	record ID
	 */
	public function add($para) {
		$this->logstr = '';
		$this->db->insert('payment', $para);
		$payment_id = $this->db->insert_id();
		$this->sqlstr = $this->db->last_query();
		$this->logstr = 'Add payment[' . $payment_id . ']:' . join(', ', $para);
		return $payment_id;
	}

	/**
	 * Update payment data
	 * 
	 * @param	array	$para	Add payment record
	 * @return	integer	record ID
	 */
	public function update($payment_id, $para) {
		$this->logstr = '';
		$this->db->where('payment_id', $payment_id);
		$this->db->update('payment', $para);
		$this->sqlstr = $this->db->last_query();
		$this->logstr = 'Update payment[' . $payment_id . ']:' . join(', ', $para);
		return $payment_id;
	}
	
	public function get_payment_by_plan_id($plan_id) {
		$this->db->where('plan_id', $plan_id);
		$this->db->order_by('payment_id', 'ASC');
		return $this->db->get('payment')->result_array();
	}
}
