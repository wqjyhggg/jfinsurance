<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Product_model extends CI_Model {
	/**
	 * Get product 
	 * 
	 * @param	string	$product_short
	 * @return	array					user table search result
	 */
	public function get_product($product_short) {
		$sql = "SELECT * FROM product WHERE product_short=". $this->db->escape($product_short);
		return $this->db->query($sql)->row_array();
	}

	/**
	 * Get All product list
	 * 
	 * @return	array					user table search result
	 */
	public function product_array() {
		$beuser = $this->session->userdata ( 'beuser' );
		if (empty($beuser)) {
			return array();
		}
		$sql = "SELECT p.* FROM product p ";
		if ($beuser['user_group_id'] > 2) {
			$sql .= " INNER JOIN user_product up ON (up.product_short=p.product_short) WHERE up.user_id='". (int)$beuser['user_id'] ."'";
		}
		$sql .= " ORDER BY p.product_short ASC";
		return $this->db->query($sql)->result_array();
	}

	/**
	 * Get All product indexed array
	 * 
	 * @return	array					user table search result
	 */
	public function product_list() {
		$rt = $this->product_array();
		$rArr = array();
		foreach ($rt as $rc) {
			$rArr[$rc['product_short']] = $rc;
		}
		return $rArr;
	}

	/**
	 * Get Plan insured
	 * 
	 * @param	string	$product_short		Search parameters
	 * @return	array					user table search result
	 */
	public function product_insured($product_short) {
		$arr = array();
		$sql = "SELECT amount FROM plan_insured WHERE product_short=" . $this->db->escape($product_short) . " ORDER BY amount";
		$rows = $this->db->query($sql)->result_array();
		foreach ($rows as $row) {
			$arr[] = $row['amount'];
		}
		return $arr;
	}
	
	/**
	 * Get Plan deductiable
	 * 
	 * @param	string	$product_short		Search parameters
	 * @return	array					user table search result
	 */
	public function product_deductiable($product_short) {
		$arr = array();
		$sql = "SELECT amount FROM plan_deductiable WHERE product_short=" . $this->db->escape($product_short) . " ORDER BY amount";
		$rows = $this->db->query($sql)->result_array();
		foreach ($rows as $row) {
			$arr[] = $row['amount'];
		}
		return $arr;
	}
	
}
