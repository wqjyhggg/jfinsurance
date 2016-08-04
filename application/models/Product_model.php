<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Product_model extends CI_Model {
	public $message;
	
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
	 * Get product commission rate
	 *
	 * @param integer $product_short
	 * @param integer $user_id
	 * @return float
	 */
	public function get_commission_rate($product_short, $user_id) {
		$product = $this->get_product($product_short);
		if (empty($product)) {
			return 0;
		}
	
		$commission = $product['commission'];
		$this->db->where('user_id', $user_id);
		$this->db->where('product_short', $product_short);
		$user_product = $this->db->get('user_product')->row_array();
		if ($user_product) {
			$commission = $user_product['commission'];
		}
		return $commission;
	}
	
	/**
	 * Get product commission rate
	 *
	 * @param integer $product_short
	 * @return float
	 */
	public function get_up_commission_rate($product_short) {
		$product = $this->get_product($product_short);
		if (empty($product)) {
			return 0;
		}
		return $product['up_pay_rate'];
	}
	
	/**
	 * Get All product list
	 * 
	 * @param	integer	$processonly
	 * @return	array					user table search result
	 */
	public function product_array($processonly=0) {
		$beuser = $this->session->userdata ( 'beuser' );
		if (empty($beuser)) {
			return array();
		}
		$sql = "SELECT p.* FROM product p ";
		if ($beuser['user_group_id'] > 100) {
			$sql .= " INNER JOIN user_product up ON (up.product_short=p.product_short) WHERE up.user_id='". (int)$beuser['user_id'] ."'";
			if ($processonly) {
				$sql .= " AND p.calculate='1'";
			}
		} else {
			if ($processonly) {
				$sql .= " WHERE p.calculate='1'";
			}
		}
		$sql .= " ORDER BY p.product_short ASC";
		return $this->db->query($sql)->result_array();
	}

	/**
	 * Get All product indexed array
	 * 
	 * @return	array					user table search result
	 */
	public function product_list($processonly=0) {
		$rt = $this->product_array($processonly);
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
	 * Get Plan deductible
	 * 
	 * @param	string	$product_short		Search parameters
	 * @return	array					user table search result
	 */
	public function product_deductible($product_short) {
		$arr = array();
		$sql = "SELECT amount FROM product_deductible WHERE product_short=" . $this->db->escape($product_short) . " ORDER BY product_deductible_id";
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
		return (int)((($etm - $stm) / 86400) + 1);
	}
	
	public function getYears($applydt, $brithdt) {
		$a = new DateTime($applydt);
		$b = new DateTime($brithdt);
		return $a->diff($b)->y;
	}
	
	public function getYearDays($dt) {
		$a = new DateTime($dt);
		$b = new DateTime($dt);
		$a->add(new DateInterval('P1Y'));
		return $a->diff($b)->days;
	}
	
	/**
	 * Get Product premium
	 * 
	 * @param array $para	parameter array 'product_short', 'apply_date', 'effective_date', 'expiry_date', 'isfamilyplan', 'number_customer', 'sum_insured', 'deductible_amount', 'stable_condition', 'birthday'
	 * @return array.
	 */
	public function get_premium($para) {
		$premiumArr = array('premium' => 0, 'totalyears' => 0, 'totaldays' => 0, 'dailyrate' => 0, 'message' => 0, 'force_deductable' => 0, 'sum_insured' => 0, 'deductible_amount' => 0);
		
		if (empty($para['effective_date']) || empty($para['expiry_date'])) {
			return FALSE;
		}
		$days = $this->getDays($para['effective_date'], $para['expiry_date']);	// $dt1 = date_create('1900-01-01 00:00:00'); $dt2 = date_create('1970-01-01 12:12:12'); echo $dt2->diff($dt1)->days;
		$yearday = $this->getYearDays($para['effective_date']);
		if ($days > $yearday) {
			$premiumArr['totaldays'] = $days;
			$premiumArr['message'] = "Period longer than a year, too long";
			return $premiumArr;
		}
		
		if (empty($days)) {
			return FALSE;
		}

		if (empty($para['birthday'])) {
			return FALSE;
		}
		$years = $this->getYears($para['apply_date'], $para['birthday']);	// 
		$d1 = new \DateTime("now");
		$d2 = new \DateTime($para['birthday']);
		$df = $d2->diff($d1);
		if ($df->invert) {
			$premiumArr['message'] = "Check birthday";
			return $premiumArr;
		}
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
						else				  	{ $premiumArr['message'] = "Over 85 years old must select without stable pre-existing condition coverage option"; return $premiumArr; }
						break;
					case 15000:
						if ($years <= 25) 		$rate = 2.04;
						elseif ($years <= 40) 	$rate = 2.22;
						elseif ($years <= 60) 	$rate = 2.55;
						elseif ($years <= 64) 	$rate = 3.2;
						elseif ($years <= 69) 	$rate = 3.91;
						elseif ($years <= 74) 	$rate = 6.32;
						elseif ($years <= 79) 	$rate = 7.54;
						elseif ($years <= 85) 	{ $premiumArr['message'] = "Over 80 years old $15,000 option isn't available"; return $premiumArr; }
						else				  	{ $premiumArr['message'] = "Over 80 years old $15,000 option isn't available"; return $premiumArr; }
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
						else				  	{ $premiumArr['message'] = "Over 85 years old must select without stable pre-existing condition coverage option"; return $premiumArr; }
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
						else				  	{ $premiumArr['message'] = "Over 85 years old must select without stable pre-existing condition coverage option"; return $premiumArr; }
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
						else				  	{ $premiumArr['message'] = "Over 85 years old must select without stable pre-existing condition coverage option"; return $premiumArr; }
						break;
					case 150000:
						if ($years <= 25) 		$rate = 4.3;
						elseif ($years <= 40) 	$rate = 4.66;
						elseif ($years <= 60) 	$rate = 5.75;
						elseif ($years <= 64) 	$rate = 6.34;
						elseif ($years <= 69) 	$rate = 7.4;
						elseif ($years <= 74) 	$rate = 12.17;
						elseif ($years <= 79) 	$rate = 14.41;
						elseif ($years <= 85) 	{ $premiumArr['message'] = "Over 80 years old $150,000 option isn't available"; return $premiumArr; }
						else				  	{ $premiumArr['message'] = "Over 80 years old $150,000 option isn't available"; return $premiumArr; }
						break;
					default:
						return FALSE;
				}
				
			} else {
				// With stable pre-existing conditions coverage option
				switch ($para['sum_insured']) {
					case 10000:
						if ($years <= 69) 		{ $premiumArr['message'] = "Under 70 years old must select with stable pre-existing condition coverage option"; return $premiumArr; }
						elseif ($years <= 74) 	$rate = 3.82;
						elseif ($years <= 79) 	$rate = 4.59;
						elseif ($years <= 85) 	$rate = 6.23;
						else				  	$rate = 9.57;
						break;
					case 15000:
						if ($years <= 69) 		{ $premiumArr['message'] = "Under 70 years old must select with stable pre-existing condition coverage option"; return $premiumArr; }
						elseif ($years <= 74) 	$rate = 4.99;
						elseif ($years <= 79) 	$rate = 5.98;
						elseif ($years <= 85) 	$rate = 7.88;
						else				  	$rate = 12.37;
						break;
					case 25000:
						if ($years <= 69) 		{ $premiumArr['message'] = "Under 70 years old must select with stable pre-existing condition coverage option"; return $premiumArr; }
						elseif ($years <= 74) 	$rate = 5.99;
						elseif ($years <= 79) 	$rate = 7.11;
						elseif ($years <= 85) 	$rate = 9.69;
						else				  	$rate = 14.9;
						break;
					case 50000:
						if ($years <= 69) 		{ $premiumArr['message'] = "Under 70 years old must select with stable pre-existing condition coverage option"; return $premiumArr; }
						elseif ($years <= 74) 	$rate = 6.59;
						elseif ($years <= 79) 	$rate = 7.83;
						elseif ($years <= 85) 	$rate = 10.43;
						else				  	$rate = 16.4;
						break;
					case 100000:
						if ($years <= 69) 		{ $premiumArr['message'] = "Under 70 years old must select with stable pre-existing condition coverage option"; return $premiumArr; }
						elseif ($years <= 74) 	$rate = 8.12;
						elseif ($years <= 79) 	$rate = 10.32;
						elseif ($years <= 85) 	$rate = 13.94;
						else				  	$rate = 22.30;
						break;
					case 150000:
					default:
						$premiumArr['message'] = "$150,000 option isn't available";
						return $premiumArr;
				}
			}
			$discount = 1;
			if ($years <= 85) {
				switch ($para['deductible_amount']) {
					case 100:
						$discount = 0.95;
						break;
					case 1000:
						$discount = 0.8;
						break;
					case 2500:
						if ($para['sum_insured'] == 25000) {
							$discount = 0.7;
						} else if ($para['sum_insured'] == 50000) {
							$discount = 0.75;
						} else {
							$premiumArr['message'] = "$25,000 deductible amount isn't available";
							return $premiumArr;
						}
						break;
					case 3000:
						$discount = 0.7;
						break;
				}
			}
			if ($para['isfamilyplan']) {
				$rate *= 2;
			}
			$premium = $rate * $days * $discount;
			$message = "";
			if ($years > 85) {
				$premiumArr['message'] = "<p style='color:#2e6da4;'>Over 85 years old must have $500 Deductible</p>";
			}
			$premiumArr['premium'] = $premium;
			$premiumArr['totalyears'] = $years;
			$premiumArr['totaldays'] = $days;
			$premiumArr['dailyrate'] = $rate;
			$premiumArr['sum_insured'] = number_format($para['sum_insured'], 2, '.', ',');
			$premiumArr['deductible_amount'] =  number_format($para['deductible_amount'], 2, '.', ',');
		} else if ($para['product_short'] == 'JFR') {
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
						else				  	{ $premiumArr['message'] = "Over 85 years old must select without stable pre-existing condition coverage option"; return $premiumArr; }
						break;
					case 15000:
						if ($years <= 25) 		$rate = 2.04;
						elseif ($years <= 40) 	$rate = 2.22;
						elseif ($years <= 60) 	$rate = 2.55;
						elseif ($years <= 64) 	$rate = 3.11;
						elseif ($years <= 69) 	$rate = 3.91;
						elseif ($years <= 74) 	$rate = 6.32;
						elseif ($years <= 79) 	$rate = 7.54;
						elseif ($years <= 85) 	{ $premiumArr['message'] = "Over 80 years old $15,000 option isn't available"; return $premiumArr; }
						else				  	{ $premiumArr['message'] = "Over 80 years old $15,000 option isn't available"; return $premiumArr; }
						break;
					case 25000:
						if ($years <= 25) 		$rate = 2.27;
						elseif ($years <= 40) 	$rate = 2.49;
						elseif ($years <= 60) 	$rate = 2.86;
						elseif ($years <= 64) 	$rate = 3.73;
						elseif ($years <= 69) 	$rate = 4.72;
						elseif ($years <= 74) 	$rate = 7.6;
						elseif ($years <= 79) 	$rate = 8.96;
						elseif ($years <= 85) 	$rate = 17.76;
						else				  	{ $premiumArr['message'] = "Over 85 years old must select without stable pre-existing condition coverage option"; return $premiumArr; }
						break;
					case 50000:
						if ($years <= 25) 		$rate = 2.49;
						elseif ($years <= 40) 	$rate = 2.74;
						elseif ($years <= 60) 	$rate = 3.11;
						elseif ($years <= 64) 	$rate = 4.02;
						elseif ($years <= 69) 	$rate = 5.09;
						elseif ($years <= 74) 	$rate = 8.35;
						elseif ($years <= 79) 	$rate = 9.88;
						elseif ($years <= 85) 	$rate = 19.58;
						else				  	{ $premiumArr['message'] = "Over 85 years old must select without stable pre-existing condition coverage option"; return $premiumArr; }
						break;
					case 100000:
						if ($years <= 25) 		$rate = 3.59;
						elseif ($years <= 40) 	$rate = 4.02;
						elseif ($years <= 60) 	$rate = 4.95;
						elseif ($years <= 64) 	$rate = 4.98;
						elseif ($years <= 69) 	$rate = 5.94;
						elseif ($years <= 74) 	$rate = 9.79;
						elseif ($years <= 79) 	$rate = 11.59;
						elseif ($years <= 85) 	$rate = 22.95;
						else				  	{ $premiumArr['message'] = "Over 85 years old must select without stable pre-existing condition coverage option"; return $premiumArr; }
						break;
					case 150000:
						if ($years <= 25) 		$rate = 4.3;
						elseif ($years <= 40) 	$rate = 4.66;
						elseif ($years <= 60) 	$rate = 5.75;
						elseif ($years <= 64) 	$rate = 6.16;
						elseif ($years <= 69) 	$rate = 7.4;
						elseif ($years <= 74) 	$rate = 12.17;
						elseif ($years <= 79) 	$rate = 14.41;
						elseif ($years <= 85) 	{ $premiumArr['message'] = "Over 80 years old $150,000 option isn't available"; return $premiumArr; }
						else				  	{ $premiumArr['message'] = "Over 80 years old $150,000 option isn't available"; return $premiumArr; }
						break;
					default:
						return $premiumArr;
				}
				
			} else {
				// With stable pre-existing conditions coverage option
				switch ($para['sum_insured']) {
					case 10000:
						if ($years <= 69) 		{ $premiumArr['message'] = "Under 70 years old must select with stable pre-existing condition coverage option"; return $premiumArr; }
						elseif ($years <= 74) 	$rate = 3.82;
						elseif ($years <= 79) 	$rate = 4.59;
						elseif ($years <= 85) 	$rate = 6.23;
						else				  	$rate = 9.57;
						break;
					case 15000:
						if ($years <= 69) 		{ $premiumArr['message'] = "Under 70 years old must select with stable pre-existing condition coverage option"; return $premiumArr; }
						elseif ($years <= 74) 	$rate = 4.99;
						elseif ($years <= 79) 	$rate = 5.98;
						elseif ($years <= 85) 	$rate = 7.88;
						else				  	$rate = 12.37;
						break;
					case 25000:
						if ($years <= 69) 		{ $premiumArr['message'] = "Under 70 years old must select with stable pre-existing condition coverage option"; return $premiumArr; }
						elseif ($years <= 74) 	$rate = 5.99;
						elseif ($years <= 79) 	$rate = 7.11;
						elseif ($years <= 85) 	$rate = 9.69;
						else				  	$rate = 14.9;
						break;
					case 50000:
						if ($years <= 69) 		{ $premiumArr['message'] = "Under 70 years old must select with stable pre-existing condition coverage option"; return $premiumArr; }
						elseif ($years <= 74) 	$rate = 6.59;
						elseif ($years <= 79) 	$rate = 7.83;
						elseif ($years <= 85) 	$rate = 10.43;
						else				  	$rate = 16.4;
						break;
					case 100000:
						if ($years <= 69) 		{ $premiumArr['message'] = "Under 70 years old must select with stable pre-existing condition coverage option"; return $premiumArr; }
						elseif ($years <= 74) 	$rate = 8.12;
						elseif ($years <= 79) 	$rate = 10.32;
						elseif ($years <= 85) 	$rate = 13.94;
						else				  	$rate = 22.30;
						break;
					case 150000:
					default:
						$premiumArr['message'] = "$150,000 option isn't available";
						return $premiumArr;
				}
			}
			$discount = 1;
			if ($years <= 85) {
				switch ($para['deductible_amount']) {
					case 100:
						$discount = 0.95;
						break;
					case 1000:
						$discount = 0.8;
						break;
					case 2500:
						if ($para['sum_insured'] == 25000) {
							$discount = 0.7;
						} else if ($para['sum_insured'] == 50000)	{
							$discount = 0.75;
						} else {
							$premiumArr['message'] = "$25,000 deductible amount isn't available";
							return $premiumArr;
						}
						break;
					case 3000:
						$discount = 0.7;
						break;
				}
			}
			if ($para['isfamilyplan']) {
				$rate *= 2;
			}
			$premium = $rate * $days * $discount;
			if ($years > 85) {
				$premiumArr['message'] = "<p style='color:#2e6da4;'>Notice: Over 85 years old will have $500 Deductible</p>";
			}
			$premiumArr['premium'] = $premium;
			$premiumArr['totalyears'] = $years;
			$premiumArr['totaldays'] = $days;
			$premiumArr['dailyrate'] = $rate;
			$premiumArr['sum_insured'] = number_format($para['sum_insured'], 2, '.', ',');
			$premiumArr['deductible_amount'] =  number_format($para['deductible_amount'], 2, '.', ',');
		} else if ($para['product_short'] == 'JUS') {
			$number_customer = (int)$para['number_customer'] - 2;
			if ($para['rate_options'] != 2) {	// Here is Plus / Prefer
				if ($years <= 24) 		$rate = 3.25;
				elseif ($years <= 30) 	$rate = 4.65;
				elseif ($years <= 40) 	$rate = 10.4;
				else				  	$rate = 21.47;
				if ($para['isfamilyplan'] && ($number_customer == 0)) {
					$rate += 21.23;
				}
				if ($number_customer > 0) {
					$rate += 11.51 * $number_customer;
				}
			} else {
				if ($years <= 24) 		$rate = 3.25;
				elseif ($years <= 30) 	$rate = 4.65;
				elseif ($years <= 40) 	$rate = 10.4;
				else				  	$rate = 21.47;
				if ($para['isfamilyplan'] && ($number_customer == 0)) {
					$rate += 24.79;
				}
				if ($number_customer > 0) {
					$rate += 11.54 * $number_customer;
				}
			}
			$premium = $rate * $days;
			$premiumArr['premium'] = $premium;
			$premiumArr['totalyears'] = $years;
			$premiumArr['totaldays'] = $days;
			$premiumArr['dailyrate'] = $rate;
			$premiumArr['sum_insured'] = number_format($para['sum_insured'], 2, '.', ',');
			$premiumArr['deductible_amount'] =  number_format($para['deductible_amount'], 2, '.', ',');
		} else if ($para['product_short'] == 'NUS') {
			$number_customer = (int)$para['number_customer'] - 2;
			if ($para['rate_options'] != 2) {	// Here is Plus / Prefer
				if ($years <= 24) 		$rate = 4.1;
				elseif ($years <= 30) 	$rate = 5.82;
				elseif ($years <= 40) 	$rate = 12;
				else				  	$rate = 24.66;
				if ($para['isfamilyplan'] && ($number_customer == 0)) {
					$rate += 24.71;
				}
				if ($number_customer > 0) {
					$rate += 14.32 * $number_customer;
				}
			} else {
				if ($years <= 24) 		$rate = 4.69;
				elseif ($years <= 30) 	$rate = 6.62;
				elseif ($years <= 40) 	$rate = 13.16;
				else				  	$rate = 28.07;
				if ($para['isfamilyplan'] && ($number_customer == 0)) {
					$rate += 27.45;
				}
				if ($number_customer > 0) {
					$rate += 14.35 * $number_customer;
				}
			}
			$premium = $rate * $days;
			$premiumArr['premium'] = $premium;
			$premiumArr['totalyears'] = $years;
			$premiumArr['totaldays'] = $days;
			$premiumArr['dailyrate'] = $rate;
			$premiumArr['sum_insured'] = number_format($para['sum_insured'], 2, '.', ',');
			$premiumArr['deductible_amount'] =  number_format($para['deductible_amount'], 2, '.', ',');
		} else if ($para['product_short'] == 'JES') {
			$number_customer = (int)$para['number_customer'];
			$rate = 1.6;
			if (isset($para['holiday_rate']) && $para['holiday_rate']) $rate = 1.85; 
			if ($number_customer == 2) {
				$premium = $rate * $days * 2.5;
			} else {
				$premium = $rate * $days * $number_customer;
			}
			$premiumArr['premium'] = $premium;
			$premiumArr['totalyears'] = $years;
			$premiumArr['totaldays'] = $days;
			$premiumArr['dailyrate'] = $rate;
			$premiumArr['sum_insured'] = number_format($para['sum_insured'], 2, '.', ',');
			$premiumArr['deductible_amount'] =  number_format($para['deductible_amount'], 2, '.', ',');
		} else if ($para['product_short'] == 'JFC') {
			if ($years <= 3) {
				$premiumArr['message'] = "Customer age must over 3 years old";
				return $premiumArr;
			} else if ($years <= 59) {
				$rate = 1.5;
			} else {
				$premiumArr['message'] = "Customer age can't older than 60 years old";
				return $premiumArr;
			}
			$premium = $rate * $days;
			$premiumArr['premium'] = $premium;
			$premiumArr['totalyears'] = $years;
			$premiumArr['totaldays'] = $days;
			$premiumArr['dailyrate'] = $rate;
			$premiumArr['sum_insured'] = number_format($para['sum_insured'], 2, '.', ',');
			$premiumArr['deductible_amount'] =  number_format($para['deductible_amount'], 2, '.', ',');
		} else {
			$premiumArr['message'] = "Unknow Product";
			return $premiumArr;
		}
		
		$this->load->model('plan_model');
		$product = $this->get_product($para['product_short']);
		if (!empty($product['min_premium'])) {
			if ((float)$product['min_premium'] > $premiumArr['premium']) {
				$premiumArr['message'] = "Minumum premium must more than " . (float)$product['min_premium'] . " ( " . (float)$premiumArr['premium'] . " estimate )";
				$premiumArr['premium'] = 0;
			}
		}
		return $premiumArr;
	}

    /**
     * Get available product list
     *
     * @return array product list
     */
    public function get_available_product_list()
    {
        $this->load->model('user_model');
        $available_user = $this->user_model->get_available_user_list();
        $this->db->distinct();
        $this->db->select('p.*');
        $this->db->from('product p');
        $this->db->join('user_product up', 'p.product_short = up.product_short');
        $this->db->where_in('up.user_id', array_keys($available_user));
        $this->db->order_by('p.product_short');
        return $this->db->get()->result_array();
    }
}
