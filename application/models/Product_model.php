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
		$sql = "SELECT amount FROM product_insured WHERE product_short=" . $this->db->escape($product_short) . " ORDER BY product_insured_id";
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
		$sql = "SELECT amount FROM product_deductiable WHERE product_short=" . $this->db->escape($product_short) . " ORDER BY product_deductiable_id";
		$rows = $this->db->query($sql)->result_array();
		foreach ($rows as $row) {
			$arr[] = $row['amount'];
		}
		return $arr;
	}
	
	public function getDays($startday, $endday) {
		$stm = strtotime($startday);
		$etm = strtotime($endday);
		if (empty($stm) || empty($etm) || ($stm > $etm)) {
			return 0;
		}
		return (($etm - $stm) / 86400) + 1;
	}
	
	public function getYears($applydt, $brithdt) {
		$a = new DateTime($applydt);
		$b = new DateTime($brithdt);
		return $a->diff($b)->format('%y');
	}
	
	/**
	 * Get Product premium
	 * 
	 * @param array $para	parameter array 'product_short', 'apply_date', 'effective_date', 'expiry_date', 'isfamilyplan', 'sum_insured', 'deductiable_amount', 'stable_condition', 'brithday'
	 * @return float 		premium, 8 means can't caculate.
	 */
	public function get_premium($para) {
		$premium = 0;
		$days = $this->getDays($para['effective_date'], $para['expiry_date']);
		$years = $this->getYears($para['apply_date'], $para['brithday']);
		if ($para['product_short'] == 'OPL') {
			if ($para['stable_condition'] == 1) {
				// With stable pre-existing conditions coverage option
				switch ($para['sum_insured']) {
					case 10000:
						if ($years <= 25) 		$rate = 1.7;
						elseif ($years <= 40) 	$rate = 1.86;
						elseif ($years <= 60) 	$rate = 2.14;
						elseif ($years <= 64) 	$rate = 2.44;
						elseif ($years <= 69) 	$rate = 3;
						elseif ($years <= 74) 	$rate = 4.85;
						elseif ($years <= 79) 	$rate = 5.8;
						elseif ($years <= 85) 	$rate = 11.48;
						else				  	return 0;
						break;
					case 15000:
						if ($years <= 25) 		$rate = 2.04;
						elseif ($years <= 40) 	$rate = 2.22;
						elseif ($years <= 60) 	$rate = 2.55;
						elseif ($years <= 64) 	$rate = 3.2;
						elseif ($years <= 69) 	$rate = 3.91;
						elseif ($years <= 74) 	$rate = 6.32;
						elseif ($years <= 79) 	$rate = 7.54;
						elseif ($years <= 85) 	return 0;
						else				  	return 0;
						break;
					case 25000:
						if ($years <= 25) 		$rate = 2.27;
						elseif ($years <= 40) 	$rate = 2.49;
						elseif ($years <= 60) 	$rate = 2.86;
						elseif ($years <= 64) 	$rate = 3.84;
						elseif ($years <= 69) 	$rate = 4.72;
						elseif ($years <= 74) 	$rate = 7.6;
						elseif ($years <= 79) 	$rate = 8.96;
						elseif ($years <= 85) 	$rate = 17.76;
						else				  	return 0;
						break;
					case 50000:
						if ($years <= 25) 		$rate = 2.49;
						elseif ($years <= 40) 	$rate = 2.74;
						elseif ($years <= 60) 	$rate = 3.11;
						elseif ($years <= 64) 	$rate = 4.14;
						elseif ($years <= 69) 	$rate = 5.09;
						elseif ($years <= 74) 	$rate = 8.35;
						elseif ($years <= 79) 	$rate = 9.88;
						elseif ($years <= 85) 	$rate = 19.58;
						else				  	return 0;
						break;
					case 100000:
						if ($years <= 25) 		$rate = 3.59;
						elseif ($years <= 40) 	$rate = 4.02;
						elseif ($years <= 60) 	$rate = 4.95;
						elseif ($years <= 64) 	$rate = 5.13;
						elseif ($years <= 69) 	$rate = 5.94;
						elseif ($years <= 74) 	$rate = 9.79;
						elseif ($years <= 79) 	$rate = 11.59;
						elseif ($years <= 85) 	$rate = 22.95;
						else				  	return 0;
						break;
					case 150000:
						if ($years <= 25) 		$rate = 4.3;
						elseif ($years <= 40) 	$rate = 4.66;
						elseif ($years <= 60) 	$rate = 5.75;
						elseif ($years <= 64) 	$rate = 6.34;
						elseif ($years <= 69) 	$rate = 7.4;
						elseif ($years <= 74) 	$rate = 12.17;
						elseif ($years <= 79) 	$rate = 14.41;
						elseif ($years <= 85) 	return 0;
						else				  	return 0;
						break;
					default:
						return 0;
				}
				
			} else {
				// With stable pre-existing conditions coverage option
				switch ($para['sum_insured']) {
					case 10000:
						if ($years <= 69) 		return 0;
						elseif ($years <= 74) 	$rate = 3.82;
						elseif ($years <= 79) 	$rate = 4.59;
						elseif ($years <= 85) 	$rate = 6.23;
						else				  	$rate = 9.57;
						break;
					case 15000:
						if ($years <= 69) 		return 0;
						elseif ($years <= 74) 	$rate = 4.99;
						elseif ($years <= 79) 	$rate = 5.98;
						elseif ($years <= 85) 	$rate = 7.88;
						else				  	$rate = 12.37;
						break;
					case 25000:
						if ($years <= 69) 		return 0;
						elseif ($years <= 74) 	$rate = 5.99;
						elseif ($years <= 79) 	$rate = 7.11;
						elseif ($years <= 85) 	$rate = 9.69;
						else				  	$rate = 14.9;
						break;
					case 50000:
						if ($years <= 69) 		return 0;
						elseif ($years <= 74) 	$rate = 6.59;
						elseif ($years <= 79) 	$rate = 7.83;
						elseif ($years <= 85) 	$rate = 10.43;
						else				  	$rate = 16.4;
						break;
					case 100000:
						if ($years <= 69) 		return 0;
						elseif ($years <= 74) 	$rate = 8.12;
						elseif ($years <= 79) 	$rate = 10.32;
						elseif ($years <= 85) 	$rate = 13.94;
						else				  	$rate = 22.30;
						break;
					case 150000:
					default:
						return 0;
				}
			}
			$discount = 1;
			switch ($para['deductiable_amount']) {
				case 100:
					$discount = 0.95;
					break;
				case 1000:
					$discount = 0.8;
					break;
				case 2500:
					if (($para['sum_insured'] == 25000) && ($years <= 85))	{
						$discount = 0.8;
					} else {
						return 0;
					}
					break;
				case 3000:
					$discount = 0.7;
					break;
			}
			if ($para['isfamilyplan']) {
				$rate *= 2;
			}
			$premium = $rate * $days * $discount;
		}
		return $premium; 
	}
	
}
