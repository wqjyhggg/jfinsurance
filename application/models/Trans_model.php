<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Trans_model extends CI_Model {
	public $logstr;
	public $sqlstr;
	public $error;
	
	/**
	 * Sort pay type string
	 * 
	 * @param	string	$str	String for format
	 * @return	string				permitied string
	 */
	private function pay_type($str) {
		switch ($str) {
			case 'premium': return 'premium';
			case 'commission': return 'commission';
		}
		return 'unknown : ' . $str;
	}

	/**
	 * Get plan payment history
	 *
	 * @param integer $plan_id
	 * @return array
	 */
	public function get_payment_by_plan_id($plan_id) {
		$this->db->where('plan_id', $plan_id);
		return $this->db->get('payment')->result_array();
	}
	
	/**
	 * Get plan payment history
	 *
	 * @param integer $plan_id
	 * @return array
	 */
	public function get_payment($plan_id, $pay_type='premium', $ispaid=0) {
		$this->db->where('plan_id', $plan_id);
		$this->db->where('pay_type', $pay_type);
		$this->db->where('ispaid', $ispaid);
		$this->db->order_by('payment_id', 'DESC');
		$this->db->limit(1);
		return $this->db->get('payment')->row_array();
	}
	
	/**
	 * Add payment data
	 * 
	 * @param	array	$para	Add payment record
	 * @return	integer	record ID
	 */
	public function add($para) {
		if (isset($para['pay_type'])) {
			$para['pay_type'] = $this->pay_type($para['pay_type']);
		}
		if ($beuser = $this->session->userdata ( 'beuser' )) {
			$para['user_id'] = $beuser['user_id'];
		}
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
		if (isset($para['pay_type'])) {
			$para['pay_type'] = $this->pay_type($para['pay_type']);
		}
		$this->logstr = '';
		$this->db->where('payment_id', $payment_id);
		$this->db->update('payment', $para);
		$this->sqlstr = $this->db->last_query();
		$this->logstr = 'Update payment[' . $payment_id . ']:' . join(', ', $para);
		return $payment_id;
	}
}
