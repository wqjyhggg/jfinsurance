<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Product_model extends CI_Model {
	/**
	 * Get All product list
	 * 
	 * @return	array					user table search result
	 */
	public function product_list() {
		$sql = "SELECT * FROM product ORDER BY product_short ASC";
		return $this->db->query($sql)->result_array();
	}
}
