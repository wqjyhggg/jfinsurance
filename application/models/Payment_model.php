<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Payment_model extends CI_Model {
	public $logstr;
	public $sqlstr;
	public $error;
	
	/**
	 * Sort pay type string
	 * 
	 * @param	string	$str	String for format
	 * @return	string				permitied string
	 */
	public function pay_type($str) {
		switch ($str) {
			case 'premium': return 'premium';
			case 'refund': return 'refund';
			case 'cancel': return 'cancel';
			case 'commission': return 'commission';
			case 'refund_commission': return 'refund_commission';
			case 'cancel_commission': return 'cancel_commission';
			case 'up_commission': return 'up_commission';
			case 'refund_up_commission': return 'refund_up_commission';
			case 'cancel_up_commission': return 'cancel_up_commission';
		}
		return 'unknown : ' . $str;
	}

	/**
	 * Get payment by id
	 *
	 * @param integer $payment_id
	 * @return array
	 */
	public function get_payment_by_id($payment_id) {
		$this->db->where('payment_id', $payment_id);
		return $this->db->get('payment')->row_array();
	}
	
	/**
	 * Get plan payment history
	 *
	 * @param integer $plan_id
	 * @return array
	 */
	public function get_payment_by_plan_id($plan_id, $sort='') {
		$this->db->where('plan_id', $plan_id);
		$this->db->where('amount !=', 0);
		if ($sort == 'type') {
			$this->db->order_by('pay_type', 'asc');
		} else {
			$this->db->order_by('last_update', 'asc');
		}
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
		$this->db->where('amount>', 0);
		$this->db->order_by('payment_id', 'DESC');
		$this->db->limit(1);
		return $this->db->get('payment')->row_array();
	}
	
	/**
	 * Get plan payment total
	 *
	 * @param integer $plan_id
	 * @return array
	 */
	public function get_total_paid($plan_id, $pay_type='premium') {
		$this->db->select_sum('amount');
		$this->db->where('plan_id', $plan_id);
		$this->db->where('pay_type', $pay_type);
		// $this->db->where('ispaid', 1);
		return $this->db->get('payment')->row()->amount;
	}

	/**
	 * Check plan payment period
	 *
	 * @param integer $plan_id
	 * @return datetime string
	 */
	public function check_payment_period($plan_id, $starttm, $endtm) {
		$sql = "SELECT * FROM payment WHERE plan_id='" . (int)$plan_id ."' AND (added<" .$this->db->escape($starttm). " OR added>" .$this->db->escape($endtm). ")";
		$rows = $this->db->query($sql)->result_array();
		if ($rows) {
			return FALSE;
		}
		return TRUE;
	}

	/**
	 * Get plan payment cancel date
	 *
	 * @param integer $plan_id
	 * @return datetime string
	 */
	public function get_cancel_date($plan_id) {
		$this->db->select('added');
		$this->db->where('plan_id', $plan_id);
		$this->db->where('pay_type', 'cancel');
		$this->db->order_by('added', 'ASC');
		$this->db->limit(1);
		$row = $this->db->get('payment')->row();
		if ($row) {
			return $row->added;
		}
		$this->db->select('last_update');
		$this->db->where('plan_id', $plan_id);
		return $this->db->get('plan')->row()->last_update;
	}
	
	/**
	 * Get plan payment  refund date
	 *
	 * @param integer $plan_id
	 * @return datetime string
	 */
	public function get_refund_date($plan_id) {
		$this->db->select('added');
		$this->db->where('plan_id', $plan_id);
		$this->db->where('pay_type', 'refund');
		$this->db->order_by('added', 'ASC');
		$this->db->limit(1);
		$row = $this->db->get('payment')->row();
		if ($row) {
			return $row->added;
		}
		$this->db->select('last_update');
		$this->db->where('plan_id', $plan_id);
		return $this->db->get('plan')->row()->last_update;
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
	
	/**
	 * Update payment data
	 * 
	 * @param	plan_id	$plan_id	
	 * @return	none
	 */
	public function delete_plan($plan_id) {
		$this->logstr = '';
		$this->db->where('plan_id', $plan_id);
		$this->db->delete('payment');
		$this->sqlstr = $this->db->last_query();
		$this->logstr = 'remove payments by plan[' . $plan_id . ']';
		return $plan_id;
	}
	
	/**
	 * adjust commission added date
	 * 
	 * @para plan_id
	 * @para date
	 * @return effect rows
	 */
	public function adjust_commission_added_date($plan_id, $date, $change_last_update=TRUE) {
		$this->logstr = '';
		if (strlen($date) <= 10) {
			$date .= ' 00:00:00';
		}
		$this->db->where('plan_id', $plan_id);
		$this->db->where('pay_type', 'commission');
		$this->db->where('ispaid', '0');
		$this->db->set('added', $date);
		if ($change_last_update) $this->db->set('last_update', 'last_update', FALSE);
		$this->db->update('payment');
		$this->sqlstr = $this->db->last_query();
		$this->logstr = 'Adjust Commission Added[' . $plan_id . '][' . $date . ']';
		return $this->db->affected_rows() > 0;
	}
	
	/**
	 * adjust commission added date
	 * 
	 * @para plan_id
	 * @para date
	 * @return effect rows
	 */
	public function get_last_payment($plan_id) {
		$this->db->where('plan_id', $plan_id);
		$this->db->where('pay_type', 'premium');
		$this->db->order_by('payment_id', 'DESC');
		$this->db->limit(1);
		return $this->db->get('payment')->row_array();
	}
}
