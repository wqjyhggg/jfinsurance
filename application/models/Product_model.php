<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Product_model extends CI_Model {
	const PLANIDCHG2018=385741;
	const PLANIDCHG2018_1=394116;
	const PLANIDCHG2018_2=401652;
	const PLANIDCHG2019_3=452015;
	const PLANIDCHG2019_4=457707;	// OPL new rate
	const PLANIDCHG2019_5=456749;   // JES apply 1.6 * 2. (used to be 1.6 daily * 2.5)
	const PLANIDCHG2019_7=486069;   // JFR rate change
	const PLANIDCHG2019_8=504988;   // OPL rate change
	const PLANIDCHG2021_8=619241;   // JES JESP new rate
	const PLANIDCHG2022_9=693264;   // FOR JFR, JESP rate change
	const PLANIDCHG2024_1=730200;   // FOR TOP new ratio
	const PLANIDCHG2024_2=857047;   // FOR JFVTC 2024-08-01
	public $message;
  public $default_uncheck_product=array('BHS','JFE','JFP','JFC','JUS','NUS','JFPL','JFSL','JFGD','JFOS');  //  JES, JESP, JFS, JFR
	
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
	public function product_array($processonly=0, $user=null) {
		if (empty($user)) {
		$beuser = $this->session->userdata ( 'beuser' );
		if (empty($beuser)) {
			$beuser = $this->session->userdata ( 'vsuser' );
			if (empty($beuser)) {
				return array();
			}
		}
		} else {
			$beuser = $user;
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
			} else {
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
	public function product_list($processonly=0, $user=null) {
		$rt = $this->product_array($processonly, $user);
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
	 * @param	int	$amount				special amount for OPL and JFR
	 * @return	array					user table search result
	 */
	public function product_deductible($product_short, $amount=0) {
		if ($amount == "500only") {
			if (($product_short == 'OPL') || ($product_short == 'JFR') || ($product_short == 'JFVTC')) {
				return array(500);
			}
		}
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
		$etm = strtotime($endday) + 7200;	// avoid sum time error
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
	
	public function get_top_quote($para) {
		$r = array('status' => 'Fail', 'message' => '');
		
		if (!isset($para['isfamilyplan'])) $para['isfamilyplan'] = 0;
		if (!isset($para['adult'])) $para['adult'] = 1;
		if (!isset($para['number_customer'])) $para['number_customer'] = 1;
		if ($para['isfamilyplan'] == 1) {
			if ($para['adult'] >= 3) {
				$r['message'] = 'Family Plan child(ren) no more than 19 years';
			} else if ($para['number_customer'] > 6) {
				$r['message'] = 'Family Plan must less than 6 people';
			} else if (($para['totalyears'] > 60) && ($para['number_customer'] > 1)) {
				$r['message'] = 'Family Plan all members must 60 or under';
			}
		}
		
		if ($r['message']) return $r;

		// Get All needs parameter
		
		$para['package'] = isset($para['package']) ? $para['package'] : '';
		$para['totaldays'] = isset($para['totaldays']) ? $para['totaldays'] : 0;
		$para['sum_insured'] = isset($para['sum_insured']) ? $para['sum_insured'] : 0;
		$para['free_cancel'] = isset($para['free_cancel']) ? $para['free_cancel'] : '';
		$para['age'] = $para['totalyears'];
		$para['questionnaire'] = isset($para['questionnaire']) ? $para['questionnaire'] : 0;
		$para['stable_condition'] = isset($para['stable_condition']) ? $para['stable_condition'] : 0;
		$para['people_number'] = $para['number_customer'];
		$para['ad_and_d'] = isset($para['ad_and_d']) ? $para['ad_and_d'] : '';
		$para['flight_ccident'] = isset($para['flight_ccident']) ? $para['flight_ccident'] : '';
		$para['trip_cancellation'] = isset($para['trip_cancellation']) ? $para['trip_cancellation'] : '';
		$para['agearr'] = array();
		
		if (!empty($para['plan_id']) && ($para['plan_id'] < SELF::PLANIDCHG2024_1)) {
			$this->load->model('top_model');
			return $this->top_model->get_premium($para);
		} else {
			$this->load->model('top2_model');
			return $this->top2_model->get_premium($para);
		}		
	}
	
	
	public function get_top_premium($para) {
		// get number of plan members, oldest years and check member conditions
		$r = array('status' => 'Fail', 'message' => '');
		
		$number = 1;
		$birthday = isset($para['birthday']) ? $para['birthday'] : '';
		$effective_date = isset($para['effective_date']) ? $para['effective_date'] : '';
		$expiry_date = isset($para['expiry_date']) ? $para['expiry_date'] : '';
		$arrival_date = isset($para['arrival_date']) ? $para['arrival_date'] : '';
		$package = isset($para['package']) ? $para['package'] : '';
		
    $limitday = $this->getDays($arrival_date, $expiry_date);
    if (($limitday > 90) && ($package != 'annual_plan')) {
      $r['message'] = "Expiry Date must be 90 days before the Departure date";
    }
    $r['totaldays'] = $para['totaldays'] = $this->getDays($effective_date, $expiry_date);
		
		$oldyears = $this->getYears($effective_date, $birthday);
		$ageArr = array($oldyears);
		if (empty($oldyears)) {
			$mindays = $this->getDays($birthday, $effective_date);
			if ($mindays < 15) {
				$r['message'] = $r['error_birthday'] = "Must older then 15 days";
			}
		}
		
		$people_order_19 = 0;
		if ($oldyears > 19) $people_order_19++;
		$people_number = 1;
		for ($i = 1; $i < 100; $i++) {
			if (empty($para['birthday_'.$i])) break;
			$number++;
			$birthday = $para['birthday_'.$i];
			$years = $this->getYears($effective_date, $birthday);
			if (empty($years)) {
				$mindays = $this->getDays($birthday, $effective_date);
				if ($mindays < 15) {
					$r['message'] = $r['error_birthday_'.$i] = "Must older then 15 days";
					break;
				}
			} else if ($years > $oldyears) {
				$oldyears = $years;
			}
			$ageArr[] = $years;
			if ($years > 19) $people_order_19++;
			$people_number++;
		}
		
		if ($para['isfamilyplan'] == 1) {
			if ($people_order_19 >= 3) {
				$r['message'] = 'Family Plan child(ren) no more than 19 years';
			} else if ($people_number > 6) {
				$r['message'] = 'Family Plan must less than 6 people';
			} else if (($oldyears > 60) && ($people_number > 1)) {
				$r['message'] = 'Family Plan all members must 60 or under';
			}
		}
		
		if ($r['message']) return $r;

		// Get All needs parameter
		
		$para['package'] = isset($para['package']) ? $para['package'] : '';
		$para['totaldays'] = isset($para['totaldays']) ? $para['totaldays'] : 0;
		$para['sum_insured'] = isset($para['sum_insured']) ? $para['sum_insured'] : 0;
		$para['free_cancel'] = isset($para['free_cancel']) ? $para['free_cancel'] : '';
		$para['age'] = $oldyears;
		$para['questionnaire'] = isset($para['questionnaire']) ? $para['questionnaire'] : 0;
		$para['stable_condition'] = isset($para['stable_condition']) ? $para['stable_condition'] : 0;
		$para['people_number'] = $people_number;
		$para['ad_and_d'] = isset($para['ad_and_d']) ? $para['ad_and_d'] : '';
		$para['flight_ccident'] = isset($para['flight_ccident']) ? $para['flight_ccident'] : '';
		$para['trip_cancellation'] = isset($para['trip_cancellation']) ? $para['trip_cancellation'] : '';
		$para['agearr'] = $ageArr;
		
		if (!empty($para['plan_id']) && ($para['plan_id'] < SELF::PLANIDCHG2024_1)) {
			$this->load->model('top_model');
			return $this->top_model->get_premium($para);
		} else {
			$this->load->model('top2_model');
			return $this->top2_model->get_premium($para);
		}		
	}
	
	/**
	 * Get Product premium
	 * 
	 * @param array $para	parameter array 'product_short', 'apply_date', 'effective_date', 'expiry_date', 'isfamilyplan', 'number_customer', 'sum_insured', 'deductible_amount', 'stable_condition', 'birthday'
	 * @return array.
	 */
	public function get_premium($para, $user=false) {
		$premiumArr = array('premium' => 0, 'totalyears' => 0, 'totaldays' => 0, 'dailyrate' => 0, 'message' => 0, 'force_deductable' => 0, 'sum_insured' => 0, 'deductible_amount' => 0);
    $dt = date("Y-m-d");
    if (($dt >= "2021-07-01") && empty($para['plan_id']) && ($para['product_short'] == 'OPL')) {
			$premiumArr['message'] = "OPL not available from 2021-07-01";
			return $premiumArr;  //TTTTTTTTTTTTTTT
    }
    if (($para['effective_date'] >= "2021-12-31") && ($para['product_short'] == 'OPL')) {
			$premiumArr['message'] = "Effective start date has to be before Dec 31, 2021";
			return $premiumArr;  //TTTTTTTTTTTTTTT
    }
		if (empty($para['effective_date']) || empty($para['expiry_date'])) {
			return FALSE;
		}
		$days = $this->getDays($para['effective_date'], $para['expiry_date']);	// $dt1 = date_create('1900-01-01 00:00:00'); $dt2 = date_create('1970-01-01 12:12:12'); echo $dt2->diff($dt1)->days;
		$yearday = $this->getYearDays($para['effective_date']);
    // From 2022-04-21 all plan can't more then one year.
    if ($days > $yearday) {
      $premiumArr['totaldays'] = $days;
      $premiumArr['message'] = "Period longer than a year, too long";
      return $premiumArr;
    }
		
		if (empty($days)) {
			return FALSE;
		}

		$para['total_days'] = $days;
		
		if (empty($para['birthday'])) {
			return FALSE;
		}
		$years = $this->getYears($para['apply_date'], $para['birthday']);	// 
		$d1 = new \DateTime("now");
		$d2 = new \DateTime($para['birthday']);
		$df = $d2->diff($d1);
		if (($df->invert) || ($df->days < 15)) {
			$premiumArr['message'] = "Check birthday";
			return $premiumArr;
		}
		$para['totalyears'] = $years;
		if (($para['product_short'] == 'JUS') || ($para['product_short'] == 'NUS')) {
			if ($days < 90) {
				$premiumArr['message'] = "Minimum length of policy is 90 days";
				return $premiumArr;
			}
			$d1 = new \DateTime('2017-09-30');
			$d2 = new \DateTime($para['expiry_date']);
			$df = $d2->diff($d1);
			if ($df->invert) {
				$premiumArr['message'] = "Expiry Date must before Sep 30, 2017";
				return $premiumArr;
			}
		}
		return $this->get_premium_sub($para, $user);
	}

	/**
	 * Get Product premium
	 * 
	 * @param array $para	parameter array 'product_short', 'apply_date', 'effective_date', 'expiry_date', 'isfamilyplan', 'number_customer', 'sum_insured', 'deductible_amount', 'stable_condition', 'birthday'
	 * @return array.
	 */
	public function get_premium_sub($para, $user=false) {
		$premiumArr = array('premium' => 0, 'totalyears' => 0, 'totaldays' => 0, 'dailyrate' => 0, 'message' => 0, 'force_deductable' => 0, 'sum_insured' => 0, 'deductible_amount' => 0);
		
		$days = $para['total_days'];
		$years = $para['totalyears'];
		if (empty($days)) {
			return FALSE;
		}
		if (!isset($para['deductible_amount'])) $para['deductible_amount'] = 100;
		if (!isset($para['isfamilyplan'])) $para['isfamilyplan'] = 0;
		if (!isset($para['sum_insured'])) $para['sum_insured'] = 10000;
		if (!isset($para['stable_condition'])) $para['stable_condition'] = 1;
		if (!isset($para['number_customer'])) $para['number_customer'] = 1;
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
						elseif ($years <= 85) {
							if (!empty($para['plan_id']) && ($para['plan_id'] < SELF::PLANIDCHG2019_8)) {
								$rate = 11.48;
							} else {
								$rate = 9.5;
							}
						}
						else				  	{ $premiumArr['message'] = "Over 85 years old must select excluding stable pre-existing condition coverage option"; return $premiumArr; }
						break;
					case 15000:
						if ($years <= 25) 		$rate = 2.04;
						elseif ($years <= 40) 	$rate = 2.22;
						elseif ($years <= 60) 	$rate = 2.55;
						elseif ($years <= 64)	{
							if (!empty($para['plan_id']) && ($para['plan_id'] < SELF::PLANIDCHG2019_4)) {
						 		$rate = 3.2;
							} else {
								// new rate changed in 2019/04/03
						 		$rate = 3.11;
							}
						}
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
						elseif ($years <= 64) 	{
							if (!empty($para['plan_id']) && ($para['plan_id'] < SELF::PLANIDCHG2019_4)) {
								$rate = 3.84;
							} else {
								// new rate changed in 2019/04/03
						 		$rate = 3.73;
							}
						}
						elseif ($years <= 69) 	$rate = 4.72;
						elseif ($years <= 74) 	$rate = 7.6;
						elseif ($years <= 79) 	$rate = 8.96;
						elseif ($years <= 85) {
							if (!empty($para['plan_id']) && ($para['plan_id'] < SELF::PLANIDCHG2019_8)) {
								$rate = 17.76;
							} else {
								$rate = 13;
							}
						}
						else				  	{ $premiumArr['message'] = "Over 85 years old must select excluding stable pre-existing condition coverage option"; return $premiumArr; }
						break;
					case 50000:
						if ($years <= 25) 		$rate = 2.49;
						elseif ($years <= 40) 	$rate = 2.74;
						elseif ($years <= 60) 	$rate = 3.11;
						elseif ($years <= 64) 	{
							if (!empty($para['plan_id']) && ($para['plan_id'] < SELF::PLANIDCHG2019_4)) {
								$rate = 4.14;
							} else {
								// new rate changed in 2019/04/03
						 		$rate = 4.02;
							}
						}
						elseif ($years <= 69) 	{
							if (!empty($para['plan_id']) && ($para['plan_id'] < SELF::PLANIDCHG2019_8)) {
								$rate = 5.09;
							} else {
								// new rate changed in 2019/10/01
								$rate = 4.8;
							}
						}
						elseif ($years <= 74) 	$rate = 8.35;
						elseif ($years <= 79) 	{
							if (!empty($para['plan_id']) && ($para['plan_id'] < SELF::PLANIDCHG2019_8)) {
								$rate = 9.88;
							} else {
								// new rate changed in 2019/10/01
								$rate = 9.5;
							}
						}
						elseif ($years <= 85) {
							if (!empty($para['plan_id']) && ($para['plan_id'] < SELF::PLANIDCHG2019_8)) {
								$rate = 19.58;
							} else {
								$rate = 15.52;
							}
						}
						else				  	{ $premiumArr['message'] = "Over 85 years old must select excluding stable pre-existing condition coverage option"; return $premiumArr; }
						break;
					case 100000:
						if ($years <= 25) 		$rate = 3.59;
						elseif ($years <= 40) 	$rate = 4.02;
						elseif ($years <= 60) 	{
							if (!empty($para['plan_id']) && ($para['plan_id'] < SELF::PLANIDCHG2018_1)) {
								$rate = 4.95;
							} else if (!empty($para['plan_id']) && ($para['plan_id'] < SELF::PLANIDCHG2019_4)) {
								// new rate changed in 2018/06/01
								$rate = 4.46;
							} else {
								// new rate changed in 2019/04/03
								$rate = 4.20;
							}
						}
						elseif ($years <= 64)	{
							if (!empty($para['plan_id']) && ($para['plan_id'] < SELF::PLANIDCHG2019_4)) {
								$rate = 5.13;
							} else {
								// new rate changed in 2019/04/03
								$rate = 4.70;
							}
						}
						elseif ($years <= 69) 	$rate = 5.94;
						elseif ($years <= 74) 	{
							if (!empty($para['plan_id']) && ($para['plan_id'] < SELF::PLANIDCHG2019_8)) {
								$rate = 9.79;
							} else {
								// new rate changed in 2019/10/01
								$rate = 9.3;
							}
						}
						elseif ($years <= 79) 	$rate = 11.59;
						elseif ($years <= 85) {
							if (!empty($para['plan_id']) && ($para['plan_id'] < SELF::PLANIDCHG2019_8)) {
								$rate = 22.95;
							} else {
								$rate = 19;
							}
						}
						else				  	{ $premiumArr['message'] = "Over 85 years old must select excluding stable pre-existing condition coverage option"; return $premiumArr; }
						break;
					case 150000:
						if ($years <= 25) 		$rate = 4.3;
						elseif ($years <= 40) 	$rate = 4.66;
						elseif ($years <= 60) 	{
							if (!empty($para['plan_id']) && ($para['plan_id'] < SELF::PLANIDCHG2019_4)) {
								$rate = 5.75;
							} else {
								// new rate changed in 2019/04/03
								$rate = 4.70;
							}
						}
						elseif ($years <= 64) 	{
							if (!empty($para['plan_id']) && ($para['plan_id'] < SELF::PLANIDCHG2019_4)) {
								$rate = 6.34;
							} else {
								// new rate changed in 2019/04/03
								$rate = 5.50;
							}
						}
						elseif ($years <= 69) 	$rate = 7.4;
						elseif ($years <= 74) 	$rate = 12.17;
						elseif ($years <= 79) 	{
							if (!empty($para['plan_id']) && ($para['plan_id'] < SELF::PLANIDCHG2019_8)) {
								$rate = 14.41;
							} else {
								// new rate changed in 2019/10/01
								$rate = 13.8;
							}
						}
						elseif ($years <= 85) 	{ $premiumArr['message'] = "Over 80 years old $150,000 option isn't available"; return $premiumArr; }
						else				  	{ $premiumArr['message'] = "Over 80 years old $150,000 option isn't available"; return $premiumArr; }
						break;
					case 200000:
						if ($years <= 25) 		$rate = 5.38;
						elseif ($years <= 40) 	$rate = 5.83;
						elseif ($years <= 60) 	$rate = 7.19;
						elseif ($years <= 64) 	$rate = 7.93;
						elseif ($years <= 69) 	$rate = 9.25;
						elseif ($years <= 74) 	$rate = 15.21;
						elseif ($years <= 79) 	$rate = 18.01;
						elseif ($years <= 85) 	{ $premiumArr['message'] = "Over 80 years old $200,000 option isn't available"; return $premiumArr; }
						else				  	{ $premiumArr['message'] = "Over 80 years old $200,000 option isn't available"; return $premiumArr; }
						break;
					case 300000:
						if ($years <= 25) 		$rate = 6.24;
						elseif ($years <= 40) 	$rate = 6.76;
						elseif ($years <= 60) 	$rate = 8.34;
						elseif ($years <= 64) 	$rate = 9.19;
						elseif ($years <= 69) 	$rate = 10.73;
						elseif ($years <= 74) 	{
							if (!empty($para['plan_id']) && ($para['plan_id'] < SELF::PLANIDCHG2019_8)) {
								$rate = 17.65;
							} else {
								// new rate changed in 2019/10/01
								$rate = 17.5;
							}
						}
						elseif ($years <= 79) 	{
							if (!empty($para['plan_id']) && ($para['plan_id'] < SELF::PLANIDCHG2019_8)) {
								$rate = 20.89;
							} else {
								// new rate changed in 2019/10/01
								$rate = 20;
							}
						}
						elseif ($years <= 85) 	{ $premiumArr['message'] = "Over 80 years old $300,000 option isn't available"; return $premiumArr; }
						else				  	{ $premiumArr['message'] = "Over 80 years old $300,000 option isn't available"; return $premiumArr; }
						break;
					default:
						return FALSE;
				}
				
			} else if ($para['stable_condition'] == 2) {
				// With stable pre-existing conditions coverage option
				switch ($para['sum_insured']) {
					case 10000:
						if ($years <= 25) 		$rate = 1.14;
						elseif ($years <= 40) 	$rate = 1.28;
						elseif ($years <= 60) 	$rate = 1.43;
						elseif ($years <= 64) 	$rate = 1.84;
						elseif ($years <= 69) 	$rate = 2.10;
						elseif ($years <= 74) 	{
							if (!empty($para['plan_id']) && ($para['plan_id'] < SELF::PLANIDCHG2019_8)) {
								$rate = 3.82;
							} else {
								// new rate changed in 2019/10/01
								$rate = 3.31;
							}
						}
						elseif ($years <= 79) 	{
							if (!empty($para['plan_id']) && ($para['plan_id'] < SELF::PLANIDCHG2019_8)) {
								$rate = 4.59;
							} else {
								// new rate changed in 2019/10/01
								$rate = 4.05;
							}
						}
						elseif ($years <= 85) 	{
							if (!empty($para['plan_id']) && ($para['plan_id'] < SELF::PLANIDCHG2019_8)) {
								$rate = 6.23;
							} else {
								// new rate changed in 2019/10/01
								$rate = 5.93;
							}
						}
						else				  	{
							if (!empty($para['plan_id']) && ($para['plan_id'] < SELF::PLANIDCHG2019_8)) {
								$rate = 9.57;
							} else {
								// new rate changed in 2019/10/01
								$rate = 9.13;
							}
						}
						break;
					case 15000:
						if ($years <= 25) 		$rate = 1.43;
						elseif ($years <= 40) 	$rate = 1.55;
						elseif ($years <= 60) 	$rate = 1.79;
						elseif ($years <= 64) 	$rate = 2.18;
						elseif ($years <= 69) 	$rate = 2.74;
						elseif ($years <= 74) 	{
							if (!empty($para['plan_id']) && ($para['plan_id'] < SELF::PLANIDCHG2019_8)) {
								$rate = 4.99;
							} else {
								// new rate changed in 2019/10/01
								$rate = 4.39;
							}
						}
						elseif ($years <= 79) 	{
							if (!empty($para['plan_id']) && ($para['plan_id'] < SELF::PLANIDCHG2019_8)) {
								$rate = 5.98;
							} else {
								// new rate changed in 2019/10/01
								$rate = 5.26;
							}
						}
						elseif ($years <= 85) 	{
							if (!empty($para['plan_id']) && ($para['plan_id'] < SELF::PLANIDCHG2019_8)) {
								$rate = 7.88;
							} else {
								// new rate changed in 2019/10/01
								$rate = 7.64;
							}
						}
						else				  	{
							if (!empty($para['plan_id']) && ($para['plan_id'] < SELF::PLANIDCHG2019_8)) {
								$rate = 12.37;
							} else {
								// new rate changed in 2019/10/01
								$rate = 12;
							}
						}
						break;
					case 25000:
						if ($years <= 25) 		$rate = 1.55;
						elseif ($years <= 40) 	$rate = 1.7;
						elseif ($years <= 60) 	$rate = 1.99;
						elseif ($years <= 64) 	$rate = 2.75;
						elseif ($years <= 69) 	$rate = 3.09;
						elseif ($years <= 74) 	{
							if (!empty($para['plan_id']) && ($para['plan_id'] < SELF::PLANIDCHG2019_8)) {
								$rate = 5.99;
							} else {
								// new rate changed in 2019/10/01
								$rate = 4.3;
							}
						}
						elseif ($years <= 79) 	{
							if (!empty($para['plan_id']) && ($para['plan_id'] < SELF::PLANIDCHG2019_8)) {
								$rate = 7.11;
							} else {
								// new rate changed in 2019/10/01
								$rate = 5.08;
							}
						}
						elseif ($years <= 85) 	{
							if (!empty($para['plan_id']) && ($para['plan_id'] < SELF::PLANIDCHG2019_8)) {
								$rate = 9.69;
							} else {
								// new rate changed in 2019/10/01
								$rate = 9.35;
							}
						}
						else				  	{
							if (!empty($para['plan_id']) && ($para['plan_id'] < SELF::PLANIDCHG2019_8)) {
								$rate = 14.9;
							} else {
								// new rate changed in 2019/10/01
								$rate = 14.3;
							}
						}
						break;
					case 50000:
						if ($years <= 25) 		$rate = 1.7;
						elseif ($years <= 40) 	$rate = 1.85;
						elseif ($years <= 60) 	$rate = 2.28;
						elseif ($years <= 64) 	$rate = 3.31;
						elseif ($years <= 69) 	$rate = 3.68;
						elseif ($years <= 74) 	{
							if (!empty($para['plan_id']) && ($para['plan_id'] < SELF::PLANIDCHG2019_8)) {
								$rate = 6.59;
							} else {
								// new rate changed in 2019/10/01
								$rate = 6.16;
							}
						}
						elseif ($years <= 79) 	{
							if (!empty($para['plan_id']) && ($para['plan_id'] < SELF::PLANIDCHG2019_8)) {
								$rate = 7.83;
							} else {
								// new rate changed in 2019/10/01
								$rate = 7.16;
							}
						}
						elseif ($years <= 85) 	{
							if (!empty($para['plan_id']) && ($para['plan_id'] < SELF::PLANIDCHG2019_8)) {
								$rate = 10.43;
							} else {
								// new rate changed in 2019/10/01
								$rate = 10;
							}
						}
						else				  	{
							if (!empty($para['plan_id']) && ($para['plan_id'] < SELF::PLANIDCHG2019_8)) {
								$rate = 16.4;
							} else {
								// new rate changed in 2019/10/01
								$rate = 16.4;
							}
						}
						break;
					case 100000:
						if ($years <= 25) 		$rate = 2.28;
						elseif ($years <= 40) 	$rate = 2.65;
						elseif ($years <= 60) 	$rate = 3.38;
						elseif ($years <= 64) 	$rate = 3.97;
						elseif ($years <= 69) 	$rate = 5.08;
						elseif ($years <= 74) 	{
							if (!empty($para['plan_id']) && ($para['plan_id'] < SELF::PLANIDCHG2019_8)) {
								$rate = 8.12;
							} else {
								// new rate changed in 2019/10/01
								$rate = 6.99;
							}
						}
						elseif ($years <= 79) 	{
							if (!empty($para['plan_id']) && ($para['plan_id'] < SELF::PLANIDCHG2019_8)) {
								$rate = 10.32;
							} else {
								// new rate changed in 2019/10/01
								$rate = 8.67;
							}
						}
						elseif ($years <= 85) 	$rate = 13.94;
						else				  	$rate = 22.30;
						break;
					case 150000:
						if ($years <= 25) 		$rate = 2.72;
						elseif ($years <= 40) 	$rate = 3.09;
						elseif ($years <= 60) 	$rate = 4.05;
						elseif ($years <= 64) 	$rate = 4.7;
						elseif ($years <= 69) 	$rate = 5.45;
						elseif ($years <= 74) 	$rate = 9;
						elseif ($years <= 79) 	$rate = 10.25;
						elseif ($years <= 85) 	$rate = 15.5;
						else {
							$premiumArr['message'] = "85+ option isn't available";
							return $premiumArr;
						}                   
						break;
					case 200000:
						if ($years <= 25) 		$rate = 3.4;
						elseif ($years <= 40) 	$rate = 3.85;
						elseif ($years <= 60) 	$rate = 6.11;
						elseif ($years <= 64) 	$rate = 6.75;
						elseif ($years <= 69) 	$rate = 7.4;
						elseif ($years <= 74) 	$rate = 12.5;
						elseif ($years <= 79) 	$rate = 13.58;
						elseif ($years <= 85) 	$rate = 18.85;
						else {
							$premiumArr['message'] = "85+ option isn't available";
							return $premiumArr;
						}                   
						break;
					case 300000:
						if ($years <= 25) 		$rate = 4.41;
						elseif ($years <= 40) 	$rate = 4.78;
						elseif ($years <= 60) 	$rate = 6.07;
						elseif ($years <= 64) 	$rate = 6.76;
						elseif ($years <= 69) 	$rate = 8.01;
						elseif ($years <= 74) 	$rate = 12.96;
						elseif ($years <= 79) 	$rate = 14.98;
						else {
							$premiumArr['message'] = "80+ option isn't available";
							return $premiumArr;
						}                   
						break;
					default:
						$premiumArr['message'] = "the option rate isn't available";
						return $premiumArr;
				}
			} else {
				$premiumArr['message'] = "Please select pre-existing condition coverage";
				return $premiumArr;
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
						} else if ($para['sum_insured'] < 0) {
							return FALSE;
						} else {
							$premiumArr['message'] = "$2,500 deductible amount isn't available";
							return $premiumArr;
						}
						break;
					case 3000:
						$discount = 0.7;
						break;
				}
			}
			if (($para['sum_insured'] < 0) || ($para['deductible_amount'] < 0)) {
				return FALSE;
			}
			if ($para['isfamilyplan']) {
				$rate *= 2;
			}
			$rate = $rate * $discount;
			$premium = $rate * $days;
			$message = "";
			if ($years > 85) {
        if ($user) {
          $premiumArr['message'] = "Notice: Over 85 years old will have $500 Deductible";
        } else {
          $premiumArr['message'] = "<p style='color:#2e6da4;'>Notice: Over 85 years old will have $500 Deductible</p>";
        }
			}
			$premiumArr['premium'] = $premium;
			$premiumArr['totalyears'] = $years;
			$premiumArr['totaldays'] = $days;
			$premiumArr['dailyrate'] = $rate;
			$premiumArr['sum_insured'] = number_format($para['sum_insured'], 2, '.', ',');
			$premiumArr['deductible_amount'] =  number_format($para['deductible_amount'], 2, '.', ',');
		} else if ($para['product_short'] == 'JFVTC') {
			if ($para['stable_condition'] == 1) {
				// With stable pre-existing conditions coverage option
        if (!empty($para['plan_id']) && ($para['plan_id'] < SELF::PLANIDCHG2024_2)) {
        switch ($para['sum_insured']) {
					case 10000:
						if ($years <= 25) 		$rate = 1.7;
						elseif ($years <= 40) 	$rate = 1.86;
						elseif ($years <= 60) 	$rate = 2.14;
						elseif ($years <= 64) 	$rate = 2.44;
						elseif ($years <= 69) 	$rate = 3;
						elseif ($years <= 74) 	$rate = 4.85;
						elseif ($years <= 79) 	$rate = 5.8;
						elseif ($years <= 85)   $rate = 9.5;
						else				  	{ $premiumArr['message'] = "Please check <b>Insurable Options</b>. Over 85 years old must select <b>excluding stable pre-existing condition coverage</b> option"; return $premiumArr; }
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
						elseif ($years <= 85) 	$rate = 13;
						else				  	{ $premiumArr['message'] = "Please check <b>Insurable Options</b>. Over 85 years old must select <b>excluding stable pre-existing condition coverage</b> option"; return $premiumArr; }
						break;
					case 50000:
						if ($years <= 25) 		$rate = 2.49;
						elseif ($years <= 40) 	$rate = 2.74;
						elseif ($years <= 60) 	$rate = 3.11;
						elseif ($years <= 64) 	$rate = 4.02;
						elseif ($years <= 69) 	$rate = 4.8;
						elseif ($years <= 74) 	$rate = 8.35;
						elseif ($years <= 79) 	$rate = 9.5;
						elseif ($years <= 85) 	$rate = 15.52;
						else				  	{ $premiumArr['message'] = "Over 85 years old must select excluding stable pre-existing condition coverage option"; return $premiumArr; }
						break;
					case 100000:
						if ($years <= 25) 		$rate = 3.59;
						elseif ($years <= 40) 	$rate = 4.02;
						elseif ($years <= 60) 	$rate = 4.20;
						elseif ($years <= 64) 	$rate = 4.70;
						elseif ($years <= 69) 	$rate = 5.94;
						elseif ($years <= 74) 	$rate = 9.3;
						elseif ($years <= 79) 	$rate = 11.59;
						elseif ($years <= 85) 	$rate = 19;
						else				  	{ $premiumArr['message'] = "Over 85 years old must select excluding stable pre-existing condition coverage option"; return $premiumArr; }
						break;
					case 150000:
						if ($years <= 25) 		$rate = 4.3;
						elseif ($years <= 40) 	$rate = 4.66;
						elseif ($years <= 60) 	$rate = 4.70;
						elseif ($years <= 64) 	$rate = 5.50;
						elseif ($years <= 69) 	$rate = 7.4;
						elseif ($years <= 74) 	$rate = 12.17;
						elseif ($years <= 79) 	$rate = 13.8;
						elseif ($years <= 85) 	{ $premiumArr['message'] = "Over 80 years old $150,000 option isn't available"; return $premiumArr; }
						else				  	{ $premiumArr['message'] = "Over 80 years old $150,000 option isn't available"; return $premiumArr; }
						break;
					case 200000:
						if ($years <= 25) 		$rate = 5.38;
						elseif ($years <= 40) 	$rate = 5.83;
						elseif ($years <= 60) 	$rate = 7.19;
						elseif ($years <= 64) 	$rate = 7.93;
						elseif ($years <= 69) 	$rate = 9.25;
						elseif ($years <= 74) 	$rate = 15.21;
						elseif ($years <= 79) 	$rate = 18.01;
						elseif ($years <= 85) 	{ $premiumArr['message'] = "Over 80 years old $150,000 option isn't available"; return $premiumArr; }
						else				  	{ $premiumArr['message'] = "Over 80 years old $150,000 option isn't available"; return $premiumArr; }
						break;
					case 300000:
						if ($years <= 25) 		$rate = 6.24;
						elseif ($years <= 40) 	$rate = 6.76;
						elseif ($years <= 60) 	$rate = 8.34;
						elseif ($years <= 64) 	$rate = 9.19;
						elseif ($years <= 69) 	$rate = 10.73;
						elseif ($years <= 74) 	$rate = 17.5;
						elseif ($years <= 79) 	$rate = 20;
						elseif ($years <= 85) 	{ $premiumArr['message'] = "Over 80 years old $150,000 option isn't available"; return $premiumArr; }
						else				  	{ $premiumArr['message'] = "Over 80 years old $150,000 option isn't available"; return $premiumArr; }
						break;
					case -1:
						return FALSE;
						break;
					default:
						return $premiumArr;
				} // switch
        } else {
          switch ($para['sum_insured']) {
            case 10000:
              if ($years <= 25) 		$rate = 1.79;
              elseif ($years <= 40) 	$rate = 1.95;
              elseif ($years <= 60) 	$rate = 2.25;
              elseif ($years <= 64) 	$rate = 2.56;
              elseif ($years <= 69) 	$rate = 3.15;
              elseif ($years <= 74) 	$rate = 5.09;
              elseif ($years <= 79) 	$rate = 6.09;
              elseif ($years <= 85)   $rate = 9.98;
              else				  	{ $premiumArr['message'] = "Please check <b>Insurable Options</b>. Over 85 years old must select <b>excluding stable pre-existing condition coverage</b> option"; return $premiumArr; }
              break;
            case 15000:
              if ($years <= 25) 		$rate = 2.14;
              elseif ($years <= 40) 	$rate = 2.33;
              elseif ($years <= 60) 	$rate = 2.68;
              elseif ($years <= 64) 	$rate = 3.27;
              elseif ($years <= 69) 	$rate = 4.11;
              elseif ($years <= 74) 	$rate = 6.64;
              elseif ($years <= 79) 	$rate = 7.92;
              elseif ($years <= 85) 	{ $premiumArr['message'] = "Over 80 years old $15,000 option isn't available"; return $premiumArr; }
              else				  	{ $premiumArr['message'] = "Over 80 years old $15,000 option isn't available"; return $premiumArr; }
              break;
            case 25000:
              if ($years <= 25) 		$rate = 2.38;
              elseif ($years <= 40) 	$rate = 2.61;
              elseif ($years <= 60) 	$rate = 3;
              elseif ($years <= 64) 	$rate = 3.92;
              elseif ($years <= 69) 	$rate = 4.96;
              elseif ($years <= 74) 	$rate = 7.98;
              elseif ($years <= 79) 	$rate = 9.41;
              elseif ($years <= 85) 	$rate = 13.65;
              else				  	{ $premiumArr['message'] = "Please check <b>Insurable Options</b>. Over 85 years old must select <b>excluding stable pre-existing condition coverage</b> option"; return $premiumArr; }
              break;
            case 50000:
              if ($years <= 25) 		$rate = 2.61;
              elseif ($years <= 40) 	$rate = 2.88;
              elseif ($years <= 60) 	$rate = 3.27;
              elseif ($years <= 64) 	$rate = 4.22;
              elseif ($years <= 69) 	$rate = 5.04;
              elseif ($years <= 74) 	$rate = 8.77;
              elseif ($years <= 79) 	$rate = 9.98;
              elseif ($years <= 85) 	$rate = 16.30;
              else				  	{ $premiumArr['message'] = "Over 85 years old must select excluding stable pre-existing condition coverage option"; return $premiumArr; }
              break;
            case 100000:
              if ($years <= 25) 		$rate = 3.77;
              elseif ($years <= 40) 	$rate = 4.22;
              elseif ($years <= 60) 	$rate = 4.41;
              elseif ($years <= 64) 	$rate = 4.94;
              elseif ($years <= 69) 	$rate = 6.24;
              elseif ($years <= 74) 	$rate = 9.77;
              elseif ($years <= 79) 	$rate = 12.17;
              elseif ($years <= 85) 	$rate = 19.95;
              else				  	{ $premiumArr['message'] = "Over 85 years old must select excluding stable pre-existing condition coverage option"; return $premiumArr; }
              break;
            case 150000:
              if ($years <= 25) 		$rate = 4.52;
              elseif ($years <= 40) 	$rate = 4.89;
              elseif ($years <= 60) 	$rate = 4.94;
              elseif ($years <= 64) 	$rate = 5.78;
              elseif ($years <= 69) 	$rate = 7.77;
              elseif ($years <= 74) 	$rate = 12.78;
              elseif ($years <= 79) 	$rate = 14.49;
              elseif ($years <= 85) 	{ $premiumArr['message'] = "Over 80 years old $150,000 option isn't available"; return $premiumArr; }
              else				  	{ $premiumArr['message'] = "Over 80 years old $150,000 option isn't available"; return $premiumArr; }
              break;
            case 200000:
              if ($years <= 25) 		$rate = 5.65;
              elseif ($years <= 40) 	$rate = 6.12;
              elseif ($years <= 60) 	$rate = 7.55;
              elseif ($years <= 64) 	$rate = 8.33;
              elseif ($years <= 69) 	$rate = 9.71;
              elseif ($years <= 74) 	$rate = 15.97;
              elseif ($years <= 79) 	$rate = 18.91;
              elseif ($years <= 85) 	{ $premiumArr['message'] = "Over 80 years old $150,000 option isn't available"; return $premiumArr; }
              else				  	{ $premiumArr['message'] = "Over 80 years old $150,000 option isn't available"; return $premiumArr; }
              break;
            case 300000:
              if ($years <= 25) 		$rate = 6.55;
              elseif ($years <= 40) 	$rate = 7.10;
              elseif ($years <= 60) 	$rate = 8.76;
              elseif ($years <= 64) 	$rate = 9.65;
              elseif ($years <= 69) 	$rate = 11.27;
              elseif ($years <= 74) 	$rate = 18.38;
              elseif ($years <= 79) 	$rate = 21;
              elseif ($years <= 85) 	{ $premiumArr['message'] = "Over 80 years old $150,000 option isn't available"; return $premiumArr; }
              else				  	{ $premiumArr['message'] = "Over 80 years old $150,000 option isn't available"; return $premiumArr; }
              break;
            case -1:
              return FALSE;
              break;
            default:
              return $premiumArr;
          } // switch
        }
			} else if ($para['stable_condition'] == 2) {
				// Without stable pre-existing conditions coverage option
				switch ($para['sum_insured']) {
					case 10000:
						if ($years <= 25) 		$rate = 1.14;
						elseif ($years <= 40) 	$rate = 1.28;
						elseif ($years <= 60) 	$rate = 1.43;
						elseif ($years <= 64) 	$rate = 1.84;
						elseif ($years <= 69) 	$rate = 2.10;
						elseif ($years <= 74) 	$rate = 3.31;
						elseif ($years <= 79) 	$rate = 4.05;
						elseif ($years <= 85) 	$rate = 5.93;
						else				  	        $rate = 9.13;
						break;
					case 15000:
						if ($years <= 25) 		$rate = 1.43;
						elseif ($years <= 40) 	$rate = 1.55;
						elseif ($years <= 60) 	$rate = 1.79;
						elseif ($years <= 64) 	$rate = 2.18;
						elseif ($years <= 69) 	$rate = 2.74;
						elseif ($years <= 74) 	$rate = 4.39;
						elseif ($years <= 79) 	$rate = 5.26;
						elseif ($years <= 85) 	$rate = 7.64;
						else				  	        $rate = 12;
						break;
					case 25000:
						if ($years <= 25) 		$rate = 1.55;
						elseif ($years <= 40) 	$rate = 1.70;
						elseif ($years <= 60) 	$rate = 1.99;
						elseif ($years <= 64) 	$rate = 2.75;
						elseif ($years <= 69) 	$rate = 3.09;
						elseif ($years <= 74) 	$rate = 4.3;
						elseif ($years <= 79) 	$rate = 5.08;
						elseif ($years <= 85) 	$rate = 9.35;
						else				  	        $rate = 14.3;
						break;
					case 50000:
						if ($years <= 25) 		$rate = 1.70;
						elseif ($years <= 40) 	$rate = 1.85;
						elseif ($years <= 60) 	$rate = 2.28;
						elseif ($years <= 64) 	$rate = 3.31;
						elseif ($years <= 69) 	$rate = 3.68;
						elseif ($years <= 74) 	$rate = 6.16;
						elseif ($years <= 79) 	$rate = 7.16;
						elseif ($years <= 85) 	$rate = 10;
						else				  	        $rate = 16.4;
						break;
					case 100000:
						if ($years <= 25) 		$rate = 2.28;
						elseif ($years <= 40) 	$rate = 2.65;
						elseif ($years <= 60) 	$rate = 3.38;
						elseif ($years <= 64) 	$rate = 3.97;
						elseif ($years <= 69) 	$rate = 5.08;
						elseif ($years <= 74) 	$rate = 6.99;
						elseif ($years <= 79) 	$rate = 8.67;
						elseif ($years <= 85) 	$rate = 13.94;
						else				  	        $rate = 22.30;
						break;
					case 150000:
						if ($years <= 25) 		$rate = 2.72;
						elseif ($years <= 40) 	$rate = 3.09;
						elseif ($years <= 60) 	$rate = 4.05;
						elseif ($years <= 64) 	$rate = 4.70;
						elseif ($years <= 69) 	$rate = 5.45;
						elseif ($years <= 74) 	$rate = 9.00;
						elseif ($years <= 79) 	$rate = 10.25;
						elseif ($years <= 85) 	$rate = 15.50;
						else				  	{ $premiumArr['message'] = "Over 85 years old $150,000 option isn't available"; return $premiumArr; }
						break;
					case 200000:
						if ($years <= 25) 		$rate = 3.40;
						elseif ($years <= 40) 	$rate = 3.85;
						elseif ($years <= 60) 	$rate = 6.11;
						elseif ($years <= 64) 	$rate = 6.75;
						elseif ($years <= 69) 	$rate = 7.40;
						elseif ($years <= 74) 	$rate = 12.50;
						elseif ($years <= 79) 	$rate = 13.58;
						elseif ($years <= 85) 	$rate = 18.85;
						else				  	{ $premiumArr['message'] = "Over 85 years old $200,000 option isn't available"; return $premiumArr; }
						break;
					case 300000:
						if ($years <= 25) 		$rate = 4.41;
						elseif ($years <= 40) 	$rate = 4.78;
						elseif ($years <= 60) 	$rate = 6.07;
						elseif ($years <= 64) 	$rate = 6.76;
						elseif ($years <= 69) 	$rate = 8.02;
						elseif ($years <= 74) 	$rate = 12.96;
						elseif ($years <= 79) 	$rate = 14.98;
						elseif ($years <= 85) 	{ $premiumArr['message'] = "Over 79 years old $300,000 option isn't available"; return $premiumArr; }
						else				  	{ $premiumArr['message'] = "Over 79 years old $300,000 option isn't available"; return $premiumArr; }
						break;
					case -1:
						return FALSE;
						break;
					default:
						return $premiumArr;
				}
			} else {
				$premiumArr['message'] = "Please select pre-existing condition coverage";
				return $premiumArr;
			}
      $discount = 1;
			if ($years <= 85) {
        switch ($para['deductible_amount']) {
          case 100:
            $discount = 0.95; // 5% discount
            break;
          case 500:
            $discount = 0.85; // 15% discount
            break;
          case 1000:
            $discount = 0.80; // 20% discount
            break;
          case 2500:
            if ($para['sum_insured'] == 25000) {
              $discount = 0.70; // 30% discount
            } else if ($para['sum_insured'] == 50000) {
              $discount = 0.80; // 20% discount
            } else if ($para['sum_insured'] < 0)	{
              return FALSE;
            } else {
              $premiumArr['message'] = "$25,000 deductible amount isn't available";
              return $premiumArr;
            }
            break;
          case 3000:
            $discount = 0.70; // 30% discount
            break;
        }
      }
			if (($para['sum_insured'] < 0) || ($para['deductible_amount'] < 0)) {
				return FALSE;
			}
			if ($para['isfamilyplan']) {
        if ($years <= 60) {
          $rate *= 2;
        } else {
          $premiumArr['message'] = "Customer age must less 59 years old for apply family plan";
          return $premiumArr;
        }
        if (intval($para['number_customer']) < 3) {
          $premiumArr['message'] = "Family plan required minimum of 3 policy holders";
          return $premiumArr;
        }
			}
			$rate = $rate * $discount;
			$premium = $rate * $days;
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
            if (!empty($para['plan_id']) && ($para['plan_id'] < SELF::PLANIDCHG2022_9)) {
						if ($years <= 25) 		$rate = 1.7;
						elseif ($years <= 40) 	$rate = 1.86;
						elseif ($years <= 60) 	$rate = 2.14;
						elseif ($years <= 64) 	$rate = 2.44;
						elseif ($years <= 69) 	$rate = 3;
						elseif ($years <= 74) 	$rate = 4.85;
						elseif ($years <= 79) 	$rate = 5.8;
						elseif ($years <= 85) {
							if (!empty($para['plan_id']) && ($para['plan_id'] < SELF::PLANIDCHG2018_2)) {
								$rate = 11.48;
							} else {
								$rate = 9.5;
							}
						} else				  	{ $premiumArr['message'] = "Please check <b>Insurable Options</b>. Over 85 years old must select <b>excluding stable pre-existing condition coverage</b> option"; return $premiumArr; }
            } else {
              if ($years <= 25) 		$rate = 1.79;
              elseif ($years <= 40) 	$rate = 1.95;
              elseif ($years <= 60) 	$rate = 2.25;
              elseif ($years <= 64) 	$rate = 2.68;
              elseif ($years <= 69) 	$rate = 3.30;
              elseif ($years <= 74) 	$rate = 5.34;
              elseif ($years <= 79) 	$rate = 6.67;
              elseif ($years <= 85)   $rate = 10.93;
              else	 { $premiumArr['message'] = "Please check <b>Insurable Options</b>. Over 85 years old must select <b>excluding stable pre-existing condition coverage</b> option"; return $premiumArr; }
            }
						break;
					case 15000:
            if (!empty($para['plan_id']) && ($para['plan_id'] < SELF::PLANIDCHG2022_9)) {
            if ($years <= 25) 		$rate = 2.04;
						elseif ($years <= 40) 	$rate = 2.22;
						elseif ($years <= 60) 	$rate = 2.55;
						elseif ($years <= 64) 	$rate = 3.11;
						elseif ($years <= 69) 	$rate = 3.91;
						elseif ($years <= 74) 	$rate = 6.32;
						elseif ($years <= 79) 	$rate = 7.54;
						elseif ($years <= 85) 	{ $premiumArr['message'] = "Over 80 years old $15,000 option isn't available"; return $premiumArr; }
						else				  	{ $premiumArr['message'] = "Over 80 years old $15,000 option isn't available"; return $premiumArr; }
            } else {
              if ($years <= 25) 		$rate = 2.14;
              elseif ($years <= 40) 	$rate = 2.33;
              elseif ($years <= 60) 	$rate = 2.68;
              elseif ($years <= 64) 	$rate = 3.42;
              elseif ($years <= 69) 	$rate = 4.30;
              elseif ($years <= 74) 	$rate = 6.95;
              elseif ($years <= 79) 	$rate = 8.67;
              elseif ($years <= 85) 	{ $premiumArr['message'] = "Over 80 years old $15,000 option isn't available"; return $premiumArr; }
              else				  	{ $premiumArr['message'] = "Over 80 years old $15,000 option isn't available"; return $premiumArr; }
            }
						break;
					case 25000:
            if (!empty($para['plan_id']) && ($para['plan_id'] < SELF::PLANIDCHG2022_9)) {
						if ($years <= 25) 		$rate = 2.27;
						elseif ($years <= 40) 	$rate = 2.49;
						elseif ($years <= 60) 	$rate = 2.86;
						elseif ($years <= 64) 	$rate = 3.73;
						elseif ($years <= 69) 	$rate = 4.72;
						elseif ($years <= 74) 	$rate = 7.6;
						elseif ($years <= 79) 	$rate = 8.96;
						elseif ($years <= 85) {
							if (!empty($para['plan_id']) && ($para['plan_id'] < SELF::PLANIDCHG2018_2)) {
								$rate = 17.76;
							} else {
								$rate = 13;
							}
						} else				  	{ $premiumArr['message'] = "Please check <b>Insurable Options</b>. Over 85 years old must select <b>excluding stable pre-existing condition coverage</b> option"; return $premiumArr; }
            } else {
              if ($years <= 25) 		$rate = 2.38;
              elseif ($years <= 40) 	$rate = 2.61;
              elseif ($years <= 60) 	$rate = 3.00;
              elseif ($years <= 64) 	$rate = 4.10;
              elseif ($years <= 69) 	$rate = 5.19;
              elseif ($years <= 74) 	$rate = 8.63;
              elseif ($years <= 79) 	$rate = 10.3;
              elseif ($years <= 85)   $rate = 14.95;
              else				  	{ $premiumArr['message'] = "Please check <b>Insurable Options</b>. Over 85 years old must select <b>excluding stable pre-existing condition coverage</b> option"; return $premiumArr; }
            }
            break;
					case 50000:
            if (!empty($para['plan_id']) && ($para['plan_id'] < SELF::PLANIDCHG2022_9)) {
						if ($years <= 25) 		$rate = 2.49;
						elseif ($years <= 40) 	$rate = 2.74;
						elseif ($years <= 60) 	$rate = 3.11;
						elseif ($years <= 64) 	$rate = 4.02;
						elseif ($years <= 69) 	{
							if (!empty($para['plan_id']) && ($para['plan_id'] < SELF::PLANIDCHG2019_7)) {
								$rate = 5.09;
							} else {
								$rate = 4.8;
							}
						}
						elseif ($years <= 74) 	$rate = 8.35;
						elseif ($years <= 79) 	{
							if (!empty($para['plan_id']) && ($para['plan_id'] < SELF::PLANIDCHG2019_7)) {
								$rate = 9.88;
							} else {
								$rate = 9.5;
							}
						}
						elseif ($years <= 85) {
							if (!empty($para['plan_id']) && ($para['plan_id'] < SELF::PLANIDCHG2018_2)) {
								$rate = 19.58;
							} else {
								$rate = 15.52;
							}
						} else				  	{ $premiumArr['message'] = "Over 85 years old must select excluding stable pre-existing condition coverage option"; return $premiumArr; }
            } else {
              if ($years <= 25) 		$rate = 2.61;
              elseif ($years <= 40) 	$rate = 2.88;
              elseif ($years <= 60) 	$rate = 3.27;
              elseif ($years <= 64) 	$rate = 4.42;
              elseif ($years <= 69) 	$rate = 5.28;
              elseif ($years <= 74) 	$rate = 9.19;
              elseif ($years <= 79) 	$rate = 10.93;
              elseif ($years <= 85)   $rate = 17.85;
              else				  	{ $premiumArr['message'] = "Over 85 years old must select excluding stable pre-existing condition coverage option"; return $premiumArr; }
            }
            break;
					case 100000:
            if (!empty($para['plan_id']) && ($para['plan_id'] < SELF::PLANIDCHG2022_9)) {
						if ($years <= 25) 		$rate = 3.59;
						elseif ($years <= 40) 	$rate = 4.02;
						elseif ($years <= 60) {
							if (!empty($para['plan_id']) && ($para['plan_id'] < SELF::PLANIDCHG2018)) {
								$rate = 4.95;
							} else if (!empty($para['plan_id']) && ($para['plan_id'] < SELF::PLANIDCHG2019_3)) {
								$rate = 4.46;
							} else {
								// new rate changed in 2018/04
								$rate = 4.20;
							}
						}
						elseif ($years <= 64) 	{
							if (!empty($para['plan_id']) && ($para['plan_id'] < SELF::PLANIDCHG2019_3)) {
								$rate = 4.98;
							} else {
								// new rate changed in 2019/03/01
								$rate = 4.70;
							}
						}
						elseif ($years <= 69) 	$rate = 5.94;
						elseif ($years <= 74) 	{
							if (!empty($para['plan_id']) && ($para['plan_id'] < SELF::PLANIDCHG2019_7)) {
								$rate = 9.79;
							} else {
								$rate = 9.3;
							}
						}
						elseif ($years <= 79) 	$rate = 11.59;
						elseif ($years <= 85) {
							if (!empty($para['plan_id']) && ($para['plan_id'] < SELF::PLANIDCHG2018_2)) {
								$rate = 22.95;
							} else {
								$rate = 19;
							}
						}
						else				  	{ $premiumArr['message'] = "Over 85 years old must select excluding stable pre-existing condition coverage option"; return $premiumArr; }
            } else {
              if ($years <= 25) 		$rate = 3.77;
              elseif ($years <= 40) 	$rate = 4.22;
              elseif ($years <= 60)   $rate = 4.41;
              elseif ($years <= 64) 	$rate = 5.17;
              elseif ($years <= 69) 	$rate = 6.53;
              elseif ($years <= 74) 	$rate = 10.23;
              elseif ($years <= 79) 	$rate = 13.33;
              elseif ($years <= 85)   $rate = 21.85;
              else				  	{ $premiumArr['message'] = "Over 85 years old must select excluding stable pre-existing condition coverage option"; return $premiumArr; }
            }
						break;
					case 150000:
            if (!empty($para['plan_id']) && ($para['plan_id'] < SELF::PLANIDCHG2022_9)) {
						if ($years <= 25) 		$rate = 4.3;
						elseif ($years <= 40) 	$rate = 4.66;
						elseif ($years <= 60) 	{
							if (!empty($para['plan_id']) && ($para['plan_id'] < SELF::PLANIDCHG2019_3)) {
								$rate = 5.75;
							} else {
								// new rate changed in 2019/03/01
								$rate = 4.70;
							}
						}
						elseif ($years <= 64) 	{
							if (!empty($para['plan_id']) && ($para['plan_id'] < SELF::PLANIDCHG2019_3)) {
								$rate = 6.16;
							} else {
								// new rate changed in 2019/03/01
								$rate = 5.50;
							}
						}
						elseif ($years <= 69) 	$rate = 7.4;
						elseif ($years <= 74) 	$rate = 12.17;
						elseif ($years <= 79) 	{
							if (!empty($para['plan_id']) && ($para['plan_id'] < SELF::PLANIDCHG2019_7)) {
								$rate = 14.41;
							} else {
								$rate = 13.8;
							}
						}
						elseif ($years <= 85) 	{ $premiumArr['message'] = "Over 80 years old $150,000 option isn't available"; return $premiumArr; }
						else				  	{ $premiumArr['message'] = "Over 80 years old $150,000 option isn't available"; return $premiumArr; }
            } else {
              if ($years <= 25) 		$rate = 4.52;
              elseif ($years <= 40) 	$rate = 4.89;
              elseif ($years <= 60) 	$rate = 4.94;
              elseif ($years <= 64) 	$rate = 6.05;
              elseif ($years <= 69) 	$rate = 8.14;
              elseif ($years <= 74) 	$rate = 13.39;
              elseif ($years <= 79) 	$rate = 15.87;
              elseif ($years <= 85) 	{ $premiumArr['message'] = "Over 80 years old $150,000 option isn't available"; return $premiumArr; }
              else				  	{ $premiumArr['message'] = "Over 80 years old $150,000 option isn't available"; return $premiumArr; }
            }
						break;
					case 200000:
            if (!empty($para['plan_id']) && ($para['plan_id'] < SELF::PLANIDCHG2022_9)) {
						if ($years <= 25) 		$rate = 5.38;
						elseif ($years <= 40) 	$rate = 5.83;
						elseif ($years <= 60) 	$rate = 7.19;
						elseif ($years <= 64) 	$rate = 7.93;
						elseif ($years <= 69) 	$rate = 9.25;
						elseif ($years <= 74) 	$rate = 15.21;
						elseif ($years <= 79) 	$rate = 18.01;
						elseif ($years <= 85) 	{ $premiumArr['message'] = "Over 80 years old $150,000 option isn't available"; return $premiumArr; }
						else				  	{ $premiumArr['message'] = "Over 80 years old $150,000 option isn't available"; return $premiumArr; }
            } else {
              if ($years <= 25) 		$rate = 5.65;
              elseif ($years <= 40) 	$rate = 6.12;
              elseif ($years <= 60) 	$rate = 7.55;
              elseif ($years <= 64) 	$rate = 8.72;
              elseif ($years <= 69) 	$rate = 10.18;
              elseif ($years <= 74) 	$rate = 16.73;
              elseif ($years <= 79) 	$rate = 20.71;
              elseif ($years <= 85) 	{ $premiumArr['message'] = "Over 80 years old $150,000 option isn't available"; return $premiumArr; }
              else				  	{ $premiumArr['message'] = "Over 80 years old $150,000 option isn't available"; return $premiumArr; }
            }
						break;
					case 300000:
            if (!empty($para['plan_id']) && ($para['plan_id'] < SELF::PLANIDCHG2022_9)) {
						if ($years <= 25) 		$rate = 6.24;
						elseif ($years <= 40) 	$rate = 6.76;
						elseif ($years <= 60) 	$rate = 8.34;
						elseif ($years <= 64) 	$rate = 9.19;
						elseif ($years <= 69) 	$rate = 10.73;
						elseif ($years <= 74) 	{
							if (!empty($para['plan_id']) && ($para['plan_id'] < SELF::PLANIDCHG2019_7)) {
								$rate = 17.65;
							} else {
								$rate = 17.5;
							}
						}
						elseif ($years <= 79) 	{
							if (!empty($para['plan_id']) && ($para['plan_id'] < SELF::PLANIDCHG2019_7)) {
								$rate = 20.89;
							} else {
								$rate = 20;
							}
						}
						elseif ($years <= 85) 	{ $premiumArr['message'] = "Over 80 years old $150,000 option isn't available"; return $premiumArr; }
						else				  	{ $premiumArr['message'] = "Over 80 years old $150,000 option isn't available"; return $premiumArr; }
            } else {
              if ($years <= 25) 		$rate = 6.55;
              elseif ($years <= 40) 	$rate = 7.10;
              elseif ($years <= 60) 	$rate = 8.76;
              elseif ($years <= 64) 	$rate = 10.11;
              elseif ($years <= 69) 	$rate = 11.80;
              elseif ($years <= 74) 	$rate = 19.25;
              elseif ($years <= 79) 	$rate = 23;
              elseif ($years <= 85) 	{ $premiumArr['message'] = "Over 80 years old $150,000 option isn't available"; return $premiumArr; }
              else				  	{ $premiumArr['message'] = "Over 80 years old $150,000 option isn't available"; return $premiumArr; }
            }
						break;
					case -1:
						return FALSE;
						break;
					default:
						return $premiumArr;
				}
				
			} else if ($para['stable_condition'] == 2) {
				// Without stable pre-existing conditions coverage option
				switch ($para['sum_insured']) {
					case 10000:
						if ($years <= 25) 		$rate = 1.14;
						elseif ($years <= 40) 	$rate = 1.28;
						elseif ($years <= 60) 	$rate = 1.43;
						elseif ($years <= 64) 	$rate = 1.84;
						elseif ($years <= 69) 	$rate = 2.10;
						elseif ($years <= 74) 	{
							if (!empty($para['plan_id']) && ($para['plan_id'] < SELF::PLANIDCHG2019_7)) {
								$rate = 3.82;
							} else {
								$rate = 3.31;
							}
						}
						elseif ($years <= 79) 	{
							if (!empty($para['plan_id']) && ($para['plan_id'] < SELF::PLANIDCHG2019_7)) {
								$rate = 4.59;
							} else {
								$rate = 4.05;
							}
						}
						elseif ($years <= 85) 	{
							if (!empty($para['plan_id']) && ($para['plan_id'] < SELF::PLANIDCHG2019_7)) {
								$rate = 6.23;
							} else {
								$rate = 5.93;
							}
						}
						else				  	{
							if (!empty($para['plan_id']) && ($para['plan_id'] < SELF::PLANIDCHG2019_7)) {
								$rate = 9.57;
							} else {
								$rate = 9.13;
							}
						}
						break;
					case 15000:
						if ($years <= 25) 		$rate = 1.43;
						elseif ($years <= 40) 	$rate = 1.55;
						elseif ($years <= 60) 	$rate = 1.79;
						elseif ($years <= 64) 	$rate = 2.18;
						elseif ($years <= 69) 	$rate = 2.74;
						elseif ($years <= 74) 	{
							if (!empty($para['plan_id']) && ($para['plan_id'] < SELF::PLANIDCHG2019_7)) {
								$rate = 4.99;
							} else {
								$rate = 4.39;
							}
						}
						elseif ($years <= 79) 	{
							if (!empty($para['plan_id']) && ($para['plan_id'] < SELF::PLANIDCHG2019_7)) {
								$rate = 5.98;
							} else {
								$rate = 5.26;
							}
						}
						elseif ($years <= 85) 	{
							if (!empty($para['plan_id']) && ($para['plan_id'] < SELF::PLANIDCHG2019_7)) {
								$rate = 7.88;
							} else {
								$rate = 7.64;
							}
						}
						else				  	{
							if (!empty($para['plan_id']) && ($para['plan_id'] < SELF::PLANIDCHG2019_7)) {
								$rate = 12.37;
							} else {
								$rate = 12;
							}
						}
						break;
					case 25000:
						if ($years <= 25) 		$rate = 1.55;
						elseif ($years <= 40) 	$rate = 1.70;
						elseif ($years <= 60) 	$rate = 1.99;
						elseif ($years <= 64) 	$rate = 2.75;
						elseif ($years <= 69) 	$rate = 3.09;
						elseif ($years <= 74) 	{
							if (!empty($para['plan_id']) && ($para['plan_id'] < SELF::PLANIDCHG2019_7)) {
								$rate = 5.99;
							} else {
								$rate = 4.3;
							}
						}
						elseif ($years <= 79) 	{
							if (!empty($para['plan_id']) && ($para['plan_id'] < SELF::PLANIDCHG2019_7)) {
								$rate = 7.11;
							} else {
								$rate = 5.08;
							}
						}
						elseif ($years <= 85) 	{
							if (!empty($para['plan_id']) && ($para['plan_id'] < SELF::PLANIDCHG2019_7)) {
								$rate = 9.69;
							} else {
								$rate = 9.35;
							}
						}
						else				  	{
							if (!empty($para['plan_id']) && ($para['plan_id'] < SELF::PLANIDCHG2019_7)) {
								$rate = 14.9;
							} else {
								$rate = 14.3;
							}
						}
						break;
					case 50000:
						if ($years <= 25) 		$rate = 1.70;
						elseif ($years <= 40) 	$rate = 1.85;
						elseif ($years <= 60) 	$rate = 2.28;
						elseif ($years <= 64) 	$rate = 3.31;
						elseif ($years <= 69) 	$rate = 3.68;
						elseif ($years <= 74) 	{
							if (!empty($para['plan_id']) && ($para['plan_id'] < SELF::PLANIDCHG2019_7)) {
								$rate = 6.59;
							} else {
								$rate = 6.16;
							}
						}
						elseif ($years <= 79) 	{
							if (!empty($para['plan_id']) && ($para['plan_id'] < SELF::PLANIDCHG2019_7)) {
								$rate = 7.83;
							} else {
								$rate = 7.16;
							}
						}
						elseif ($years <= 85) 	{
							if (!empty($para['plan_id']) && ($para['plan_id'] < SELF::PLANIDCHG2019_7)) {
								$rate = 10.43;
							} else {
								$rate = 10;
							}
						}
						else				  	$rate = 16.4;
						break;
					case 100000:
						if ($years <= 25) 		$rate = 2.28;
						elseif ($years <= 40) 	$rate = 2.65;
						elseif ($years <= 60) 	$rate = 3.38;
						elseif ($years <= 64) 	$rate = 3.97;
						elseif ($years <= 69) 	$rate = 5.08;
						elseif ($years <= 74) 	{
							if (!empty($para['plan_id']) && ($para['plan_id'] < SELF::PLANIDCHG2019_7)) {
								$rate = 8.12;
							} else {
								$rate = 6.99;
							}
						}
						elseif ($years <= 79) 	{
							if (!empty($para['plan_id']) && ($para['plan_id'] < SELF::PLANIDCHG2019_7)) {
								$rate = 10.32;
							} else {
								$rate = 8.67;
							}
						}
						elseif ($years <= 85) 	$rate = 13.94;
						else				  	$rate = 22.30;
						break;
					case 150000:
						if ($years <= 25) 		$rate = 2.72;
						elseif ($years <= 40) 	$rate = 3.09;
						elseif ($years <= 60) 	$rate = 4.05;
						elseif ($years <= 64) 	$rate = 4.70;
						elseif ($years <= 69) 	$rate = 5.45;
						elseif ($years <= 74) 	$rate = 9.00;
						elseif ($years <= 79) 	$rate = 10.25;
						elseif ($years <= 85) 	$rate = 15.50;
						else				  	{ $premiumArr['message'] = "Over 85 years old $150,000 option isn't available"; return $premiumArr; }
						break;
					case 200000:
						if ($years <= 25) 		$rate = 3.40;
						elseif ($years <= 40) 	$rate = 3.85;
						elseif ($years <= 60) 	$rate = 6.11;
						elseif ($years <= 64) 	$rate = 6.75;
						elseif ($years <= 69) 	$rate = 7.40;
						elseif ($years <= 74) 	$rate = 12.50;
						elseif ($years <= 79) 	$rate = 13.58;
						elseif ($years <= 85) 	$rate = 18.85;
						else				  	{ $premiumArr['message'] = "Over 85 years old $200,000 option isn't available"; return $premiumArr; }
						break;
					case 300000:
						if ($years <= 25) 		$rate = 4.41;
						elseif ($years <= 40) 	$rate = 4.78;
						elseif ($years <= 60) 	$rate = 6.07;
						elseif ($years <= 64) 	$rate = 6.76;
						elseif ($years <= 69) 	$rate = 8.02;
						elseif ($years <= 74) 	$rate = 12.96;
						elseif ($years <= 79) 	$rate = 14.98;
						elseif ($years <= 85) 	{ $premiumArr['message'] = "Over 79 years old $300,000 option isn't available"; return $premiumArr; }
						else				  	{ $premiumArr['message'] = "Over 79 years old $300,000 option isn't available"; return $premiumArr; }
						break;
					case -1:
						return FALSE;
						break;
					default:
						return $premiumArr;
				}
			} else {
				$premiumArr['message'] = "Please select pre-existing condition coverage";
				return $premiumArr;
			}
			$discount = 1;
			if ($years <= 85) {
				switch ($para['deductible_amount']) {
					case 100:
						$discount = 0.95;
						break;
					case 500:
						$discount = 0.85;
						break;
					case 1000:
						$discount = 0.8;
						break;
					case 2500:
						if ($para['sum_insured'] == 25000) {
							$discount = 0.7;
						} else if ($para['sum_insured'] == 50000)	{
							$discount = 0.75;
						} else if ($para['sum_insured'] < 0)	{
							return FALSE;
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
			if (($para['sum_insured'] < 0) || ($para['deductible_amount'] < 0)) {
				return FALSE;
			}
			if ($para['isfamilyplan']) {
        if ($years <= 60) {
          $rate *= 2;
        } else {
          $premiumArr['message'] = "Customer age must less 59 years old for apply family plan";
          return $premiumArr;
        }
        if ((int)$para['number_customer'] < 3) {
          $premiumArr['message'] = "Family plan required minimum of 3 policy holders";
          return $premiumArr;
        }
			}
			$rate = $rate * $discount;
			$premium = $rate * $days;
			if ($years > 85) {
        if ($user) {
          $premiumArr['message'] = "Notice: Over 85 years old will have $500 Deductible";
        } else {
  				$premiumArr['message'] = "<p style='color:#2e6da4;'>Notice: Over 85 years old will have $500 Deductible</p>";
        }
			}
			$premiumArr['premium'] = $premium;
			$premiumArr['totalyears'] = $years;
			$premiumArr['totaldays'] = $days;
			$premiumArr['dailyrate'] = $rate;
			$premiumArr['sum_insured'] = number_format($para['sum_insured'], 2, '.', ',');
			$premiumArr['deductible_amount'] =  number_format($para['deductible_amount'], 2, '.', ',');
		} else if ($para['product_short'] == 'JUS') {
			if ($years > 69) {
				$premiumArr['message'] = "Customer age must less 69 years old";
				return $premiumArr;
			}
			$number_customer = (int)$para['number_customer'] - 1;
			if ($para['rate_options'] == 1) {	// Here is Plus
				if ($years <= 24) 		$rate = 3.25;
				elseif ($years <= 30) 	$rate = 4.65;
				elseif ($years <= 40) 	$rate = 10.40;
				else				  	$rate = 21.47;
				if ($para['spouse'] && ($number_customer > 0)) {
					$rate += 21.23;
					$number_customer--;
				}
				if ($number_customer > 0) {
					$rate += 11.51 * $number_customer;
				}
			} else if ($para['rate_options'] == 2) { // Prefer
				if ($years <= 24) 		$rate = 3.71;
				elseif ($years <= 30) 	$rate = 5.29;
				elseif ($years <= 40) 	$rate = 11.45;
				else				  	$rate = 24.47;
				if ($para['spouse'] && ($number_customer > 0)) {
					$rate += 24.79;
					$number_customer--;
				}
				if ($number_customer > 0) {
					$rate += 11.54 * $number_customer;
				}
			} else {
				$premiumArr['message'] = "Please select rate option";
				return $premiumArr;
			}
			$premium = $rate * $days;
			$premiumArr['premium'] = $premium;
			$premiumArr['totalyears'] = $years;
			$premiumArr['totaldays'] = $days;
			$premiumArr['dailyrate'] = $rate;
			$premiumArr['sum_insured'] = number_format($para['sum_insured'], 2, '.', ',');
			$premiumArr['deductible_amount'] =  number_format($para['deductible_amount'], 2, '.', ',');
		} else if ($para['product_short'] == 'NUS') {
			if ($years > 69) {
				$premiumArr['message'] = "Customer age must less 69 years old";
				return $premiumArr;
			}
			$number_customer = (int)$para['number_customer'] - 1;
			if ($para['rate_options'] == 1) {	// Here is Plus 
				if ($years <= 24) 		$rate = 4.10;
				elseif ($years <= 30) 	$rate = 5.82;
				elseif ($years <= 40) 	$rate = 12.0;
				else				  	$rate = 24.66;
				if ($para['spouse'] && ($number_customer > 0)) {
					$rate += 24.71;
					$number_customer--;
				}
				if ($number_customer > 0) {
					$rate += 14.32 * $number_customer;
				}
			} else if ($para['rate_options'] == 2) { // Prefer
				if ($years <= 24) 		$rate = 4.69;
				elseif ($years <= 30) 	$rate = 6.62;
				elseif ($years <= 40) 	$rate = 13.16;
				else				  	$rate = 28.07;
				if ($para['spouse'] && ($number_customer > 0)) {
					$rate += 27.45;
					$number_customer--;
				}
				if ($number_customer > 0) {
					$rate += 14.35 * $number_customer;
				}
			} else {
				$premiumArr['message'] = "Please select rate option";
				return $premiumArr;
			}
			$premium = $rate * $days;
			$premiumArr['premium'] = $premium;
			$premiumArr['totalyears'] = $years;
			$premiumArr['totaldays'] = $days;
			$premiumArr['dailyrate'] = $rate;
			$premiumArr['sum_insured'] = number_format($para['sum_insured'], 2, '.', ',');
			$premiumArr['deductible_amount'] =  number_format($para['deductible_amount'], 2, '.', ',');
		} else if ($para['product_short'] == 'JFS') {
			if ($years <= 3) {
				$premiumArr['message'] = "Customer age must over 4 years old";
				return $premiumArr;
			} else if ($years > 69) {
				$premiumArr['message'] = "Customer age must less 69 years old";
				return $premiumArr;
			}
			$number_customer = (int)$para['number_customer'];
			$rate = 1.6;
			if ($para['isfamilyplan']) {
        $rate = $rate * $number_customer;
			}
      $premium = $rate * $days;
      $premiumArr['premium'] = $premium;
			$premiumArr['totalyears'] = $years;
			$premiumArr['totaldays'] = $days;
			$premiumArr['dailyrate'] = $rate;
			$premiumArr['sum_insured'] = number_format($para['sum_insured'], 2, '.', ',');
			$premiumArr['deductible_amount'] =  number_format($para['deductible_amount'], 2, '.', ',');
		} else if ($para['product_short'] == 'JFE') {
			if ($years <= 3) {
				$premiumArr['message'] = "Customer age must over 4 years old";
				return $premiumArr;
			} else if ($years > 69) {
				$premiumArr['message'] = "Customer age must less 69 years old";
				return $premiumArr;
			}
			$number_customer = (int)$para['number_customer'];
			$rate = 1.85;
			if ($para['isfamilyplan']) {
        $rate = $rate * $number_customer;
			}
      $premium = $rate * $days;
			$premiumArr['premium'] = $premium;
			$premiumArr['totalyears'] = $years;
			$premiumArr['totaldays'] = $days;
			$premiumArr['dailyrate'] = $rate;
			$premiumArr['sum_insured'] = number_format($para['sum_insured'], 2, '.', ',');
			$premiumArr['deductible_amount'] =  number_format($para['deductible_amount'], 2, '.', ',');
		} else if ($para['product_short'] == 'BHS') {
			if ($years <= 3) {
				$premiumArr['message'] = "Customer age must over 4 years old";
				return $premiumArr;
			} else if ($years > 69) {
				$premiumArr['message'] = "Customer age must less 69 years old";
				return $premiumArr;
			}
			$number_customer = (int)$para['number_customer'];
			$rate = 2.6;
			$yrate = 840;
			if ($para['isfamilyplan']) {
        $rate = $rate * $number_customer;
        $yrate = $yrate * $number_customer;
			}
      if ($y = floor($days / 365)) {
        $premium = $yrate * $y + $rate * ($days % 365);
      } else {
        $premium = $rate * $days;
      }
			$premiumArr['premium'] = $premium;
			$premiumArr['totalyears'] = $years;
			$premiumArr['totaldays'] = $days;
			$premiumArr['dailyrate'] = $rate;
			$premiumArr['sum_insured'] = number_format($para['sum_insured'], 2, '.', ',');
			$premiumArr['deductible_amount'] =  number_format($para['deductible_amount'], 2, '.', ',');
		} else if ($para['product_short'] == 'JES') {
			if ($years <= 3) {
				$premiumArr['message'] = "Customer age must over 4 years old";
				return $premiumArr;
			} else if ($years > 69) {
				$premiumArr['message'] = "Customer age must less 69 years old";
				return $premiumArr;
			}
			$number_customer = (int)$para['number_customer'];
      $dt = date("Y-m-d");
      $status_id = 0;
      if (isset($para['status_id'])) {
        $status_id = intval($para['status_id']);
      }
      if (($dt < "2021-08-16") || (($status_id > 1) && !empty($para['plan_id']) && ($para['plan_id'] < SELF::PLANIDCHG2021_8))) {
        $rate = 1.6;
      } else {
        $rate = 1.8;
      }
			if (isset($para['holiday_rate']) && $para['holiday_rate']) {
        $rate = 1.85;
      }
      $members = 1;
			if ($para['isfamilyplan']) {
				if (($number_customer == 2) && (!empty($para['plan_id']) && ($para['plan_id'] < SELF::PLANIDCHG2019_5))) {
					$members = 2.5;
				} else {
					$members = $number_customer;
				}
			}
			if (isset($para['holiday_rate']) && ($para['holiday_rate'] < 0)) {
				return FALSE;
			}
			$premium = $rate * $members * $days;
			if (($days >= 365) && (empty($para['plan_id']) || ($para['plan_id'] > SELF::PLANIDCHG2021_8))) {
        if (empty($para['holiday_rate'])) {
          // $premium -= 12 * $members;  // 1.8 * 365 = 657 - 645 / year = 12
          $premium = 645 * $members;  // Over 365 must be 645
        }
			}
			$premiumArr['premium'] = $premium;
			$premiumArr['totalyears'] = $years;
			$premiumArr['totaldays'] = $days;
			$premiumArr['dailyrate'] = $rate;
			$premiumArr['sum_insured'] = number_format($para['sum_insured'], 2, '.', ',');
			$premiumArr['deductible_amount'] =  number_format($para['deductible_amount'], 2, '.', ',');
		} else if ($para['product_short'] == 'JFPL') {
      $beuser = $user;
      if (!$beuser) {
        $beuser = $this->session->beuser;
      }
            $val170 = array(657, 1116, 2202, 4942, 4968);   // assigned before 2023-02-03
            $val185 = array(2185, 4014);   // assigned before 2023-02-03
            $val180 = array(4249, 4275, 4276, 2661, 4449);   // assigned before 2023-02-03
            // Added 4449 form 2023-02-03
            if (in_array($beuser['user_id'], $val170)) {
                $rate = 1.7;
            } else if (in_array($beuser['user_id'], $val185)) {
                $rate = 1.85;
            } else if (in_array($beuser['user_id'], $val180)) {
                $rate = 1.8;
            } else {
				$premiumArr['message'] = "You don't have permission to sell this production.";
				return $premiumArr;
            }
			if ($years <= 3) {
				$premiumArr['message'] = "Customer age must over 4 years old";
				return $premiumArr;
			} else if ($years > 59) {
				$premiumArr['message'] = "Customer age can't older than 60 years old";
				return $premiumArr;
			}
			$premium = $rate * $days;
			$premiumArr['premium'] = $premium;
			$premiumArr['totalyears'] = $years;
			$premiumArr['totaldays'] = $days;
			$premiumArr['dailyrate'] = $rate;
			$premiumArr['sum_insured'] = number_format($para['sum_insured'], 2, '.', ','); //5,000,000.00
			$premiumArr['deductible_amount'] =  number_format($para['deductible_amount'], 2, '.', ',');
		} else if ($para['product_short'] == 'JFSL') {
      $rate = 1.6;
			if ($years <= 3) {
				$premiumArr['message'] = "Customer age must over 4 years old";
				return $premiumArr;
			} else if ($years > 69) {
				$premiumArr['message'] = "Customer age can't older than 70 years old";
				return $premiumArr;
			}
			$premium = $rate * $days;
			$premiumArr['premium'] = $premium;
			$premiumArr['totalyears'] = $years;
			$premiumArr['totaldays'] = $days;
			$premiumArr['dailyrate'] = $rate;
			$premiumArr['sum_insured'] = number_format($para['sum_insured'], 2, '.', ','); //5,000,000.00
			$premiumArr['deductible_amount'] =  number_format($para['deductible_amount'], 2, '.', ',');
		} else if ($para['product_short'] == 'JFGD') {
      $rate = 1.8;
			if (isset($para['holiday_rate']) && ($para['holiday_rate'] == 1)) {
        $rate = 1.85;
			}
			if ($years <= 3) {
				$premiumArr['message'] = "Customer age must over 4 years old";
				return $premiumArr;
			} else if ($years > 69) {
				$premiumArr['message'] = "Customer age can't older than 70 years old";
				return $premiumArr;
			}
      $number_customer = intval($para['number_customer']);
      $rate *= $number_customer;
			$premium = $rate * $days;
      if ($days >= 365) {
        if (empty($para['holiday_rate']) || ($para['holiday_rate'] != 1)) {
        $premium = 645 * $number_customer;
        }
      }
			$premiumArr['premium'] = $premium;
			$premiumArr['totalyears'] = $years;
			$premiumArr['totaldays'] = $days;
			$premiumArr['dailyrate'] = $rate;
			$premiumArr['sum_insured'] = number_format($para['sum_insured'], 2, '.', ','); //5,000,000.00
			$premiumArr['deductible_amount'] =  number_format($para['deductible_amount'], 2, '.', ',');
		} else if ($para['product_short'] == 'JFOS') {
      $rate = 2;
			if ($years <= 3) {
				$premiumArr['message'] = "Customer age must over 4 years old";
				return $premiumArr;
			} else if ($years > 69) {
				$premiumArr['message'] = "Customer age can't older than 70 years old";
				return $premiumArr;
			}
      $number_customer = intval($para['number_customer']);
      $rate *= $number_customer;
			$premium = $rate * $days;
			$premiumArr['premium'] = $premium;
			$premiumArr['totalyears'] = $years;
			$premiumArr['totaldays'] = $days;
			$premiumArr['dailyrate'] = $rate;
			$premiumArr['sum_insured'] = number_format($para['sum_insured'], 2, '.', ','); //5,000,000.00
			$premiumArr['deductible_amount'] =  number_format($para['deductible_amount'], 2, '.', ',');
		} else if ($para['product_short'] == 'JESP') {
			if ($years <= 3) {
				$premiumArr['message'] = "Customer age must over 4 years old";
				return $premiumArr;
			} else if ($years > 59) {
				$premiumArr['message'] = "Customer age must less 59 years old";
				return $premiumArr;
			}
			$number_customer = (int)$para['number_customer'];
      $dt = date("Y-m-d");
      $status_id = 0;
      if (isset($para['status_id'])) {
        $status_id = intval($para['status_id']);
      }
      if (!empty($para['plan_id']) && ($para['plan_id'] < SELF::PLANIDCHG2022_9)) {
      if (($dt < "2021-08-16") || (($status_id > 1) && !empty($para['plan_id']) && ($para['plan_id'] < SELF::PLANIDCHG2021_8))) {
        $rate = 1.8;
      } else {
        $rate = 1.85;
      }
      } else {
        $rate = 3;
      }
    // if ($para['holiday_rate'] && $para['holiday_rate']) $rate = 1.85;
			if ($para['isfamilyplan']) {
				$premiumArr['message'] = "No family plan for JESP";
				return $premiumArr;
			}
			if (isset($para['holiday_rate']) && ($para['holiday_rate'] < 0)) {
				return FALSE;
			}
			$premium = $rate * $days;
			if ($days >= 365) {
        if (($dt >= "2021-08-16") && (empty($para['plan_id']) || ($para['plan_id'] > SELF::PLANIDCHG2021_8))) {
          $premium -= 10.25;  // 1.85 * 365 = 675.25 - 665 / year = 10.25
        } else {
          $premium -= 7;  // 1.8 * 365 = 657 - 650 / year = 7
        }
			}
			$premiumArr['premium'] = $premium;
			$premiumArr['totalyears'] = $years;
			$premiumArr['totaldays'] = $days;
			$premiumArr['dailyrate'] = $rate;
			$premiumArr['sum_insured'] = number_format($para['sum_insured'], 2, '.', ',');
			$premiumArr['deductible_amount'] =  number_format($para['deductible_amount'], 2, '.', ',');
		} else if (($para['product_short'] == 'JFC') || ($para['product_short'] == 'JFP')) {
			if ($years <= 3) {
				$premiumArr['message'] = "Customer age must over 4 years old";
				return $premiumArr;
			} else if ($years <= 59) {
				$rate = 1.55;
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
			//$premiumArr['message'] = "Unknow Product";
			return $premiumArr;
		}
		
		$this->load->model('plan_model');
		$product = $this->get_product($para['product_short']);
		if (!empty($product['min_premium'])) {
			if ((float)$product['min_premium'] > $premiumArr['premium']) {
				//$premiumArr['message'] = "Minumum premium must more than " . (float)$product['min_premium'] . " ( " . (float)$premiumArr['premium'] . " estimate )";
				$premiumArr['premium'] = (float)$product['min_premium'];
			}
		}
		return $premiumArr;
	}

	/**
     * Get available product list
     *
     * @return array product list
     */
    public function get_available_product_list($beuser=false) {
    	$userArr = array();
			if (!$beuser) {
        $beuser = $this->session->beuser;
			}
    	if ($beuser['user_group_id'] > 100) {
    		if ($beuser['user_group_id'] != 104) {
    			$userArr = array($beuser['user_id']);
    		} else {
        		$this->load->model('user_model');
        		$available_user = $this->user_model->get_available_user_list();
        		$userArr = array_keys($available_user);
    		}
    	}
    	$this->db->distinct();
        $this->db->select('p.*');
        $this->db->from('product p');
        $this->db->order_by('p.product_short');
        if (!empty($userArr)) {
	        $this->db->join('user_product up', 'p.product_short = up.product_short');
	        $this->db->where_in('up.user_id', $userArr);
        }
        return $this->db->get()->result_array();
    }
    
    /**
     * Get all customized plan_name
     * 
     * @param int $user_id
     * @return array product name array
     */
    public function get_product_customize($user_id) {
    	$this->db->where('user_id', $user_id);
    	return $this->db->get('product_customize')->result_array();
    }
    
    /**
     * Get all customized plan_name
     * 
     * @param int $user_id
     * @param string $product_short
     * @return array product name array
     */
    public function get_product_customize_name($user_id, $product_short) {
    	$this->db->where('user_id', $user_id);
    	$this->db->where('product_short', $product_short);
    	$pd = $this->db->get('product_customize')->row_array();
    	if ($pd) {
    		return $pd['name'];
    	}
    	return '';
    }
    
    /**
     * Update all customized plan_name
     * 
     * @param int $user_id
     * @param array $names		indexed name array
     * @return array product name array
     */
    public function set_product_customize($user_id, $names) {
    	$this->db->delete('product_customize', array('user_id' => $user_id));
    	
    	foreach ($names as $product_short => $name) {
    		if (empty($name)) continue;
    		$this->db->set(array('user_id' => $user_id, 'product_short' => $product_short, 'name' => $name));
			$this->db->insert('product_customize');
    	}
    }
}

