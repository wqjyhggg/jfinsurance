<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

class Customer_model extends CI_Model {
	public $logstr;
	public $sqlstr;
	
	/**
	 *
	 * Get customer by id
	 *
	 * @param integer	$customer_id
	 * @return array
	 */
	public function get_customer_by_id($customer_id) {
		$this->db->where( "customer_id", $customer_id );
		return $this->db->get('customer')->row_array();
	}

	/**
	 *
	 * Get customer by parent_customer_id
	 *
	 * @param integer	$customer_id
	 * @return array
	 */
	public function get_customer_by_parent_id($customer_id) {
		$this->db->where( "parent_customer_id", $customer_id );
		return $this->db->get('customer')->result_array();
	}

	/**
	 * Add customer
	 *
	 * @param array		$para	user paramers
	 * @return integer	insert_id
	 */
	public function add($para) {
		$this->logstr = "Add customer: " . $para['firstname'] . " " . $para['lastname'];
		$this->db->set( $para );
		$this->db->insert('customer');
		$id = $this->db->insert_id();
		$this->sqlstr = $this->db->last_query();
		return $id;
	}

	/**
	 * Delete customer
	 *
	 * @param integer	$customer_id
	 * @return integer	insert_id
	 */
	public function delete($customer_id) {
		$customer = $this->get_customer_by_id($customer_id);
		if (empty($customer)) {
			$this->logstr = "Unknow customer (" . $customer['customer_id'] . ") ";
			return null;
		} else {
			$this->logstr = "Delete customer (" . $customer['firstname'] . " " . $customer['lastname'];
			$this->db->delete('customer', array('customer_id' => $customer_id));
			$this->sqlstr = $this->db->last_query();
			return $customer['customer_id'];
		}
	}

	/**
	 * Delete customer by parent id
	 *
	 * @param integer	$customer_id
	 * @return integer	insert_id
	 */
	public function delete_by_parent_id($customer_id) {
		$customer = $this->get_customer_by_id($customer_id);
		if (empty($customer)) {
			$this->logstr = "Unknow customer (" . $customer['customer_id'] . ") ";
		} else {
			$this->logstr = "Delete customers by customer (" . $customer['firstname'] . " " . $customer['lastname'];
			$this->db->delete('customer', array('parent_customer_id' => $customer_id));
			$this->sqlstr = $this->db->last_query();
			if ($this->db->affected_rows() > 0) {
				return $customer['customer_id'];
			}
		}
		return null;
	}

	/**
	 *
	 * Update customer
	 *
	 * @param integer	$customer_id
	 * @param array		$para	user paramers
	 * @return integer	insert_id
	 */
	public function update($customer_id, $para) {
		$customer = $this->get_customer_by_id($customer_id);
		if (empty($customer)) {
			$this->logstr = "Unknow customer (" . $customer_id . ") ";
		} else {
			$this->logstr = "";
			if (isset($para['plan_id']) && ($para['plan_id'] != $customer['plan_id'])) {
				$this->logstr .= " plan_id " . $para['plan_id'] . "(" . $customer['plan_id'] . ")";
			}
			if (isset($para['parent_customer_id']) && ($para['parent_customer_id'] != $customer['parent_customer_id'])) {
				$this->logstr .= " parent_customer_id " . $para['parent_customer_id'] . "(" . $customer['parent_customer_id'] . ")";
			}
			if (isset($para['gender']) && ($para['gender'] != $customer['gender'])) {
				$this->logstr .= " gender " . $para['gender'] . "(" . $customer['gender'] . ")";
			}
			if (isset($para['firstname']) && ($para['firstname'] != $customer['firstname'])) {
				$this->logstr .= " firstname " . $para['firstname'] . "(" . $customer['firstname'] . ")";
			}
			if (isset($para['lastname']) && ($para['lastname'] != $customer['lastname'])) {
				$this->logstr .= " lastname " . $para['lastname'] . "(" . $customer['lastname'] . ")";
			}
			if (isset($para['birthday']) && ($para['birthday'] != $customer['birthday'])) {
				$this->logstr .= " birthday " . $para['birthday'] . "(" . $customer['birthday'] . ")";
			}
			if ($this->logstr != "") {
				$id = $this->db->insert_id();
				$this->sqlstr = $this->db->last_query();
				return $id;
			}
		}
		return null;
	}
	
	/**
	 * Get max birthday for a plan
	 * 
	 * @param integer $customer_id	master customer ImagickDraw
	 * @return string		oldest customer birthday
	 */
	get_max_birthday($plan['customer_id'])
}