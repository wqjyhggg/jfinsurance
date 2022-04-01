<?php

if (!defined('BASEPATH'))
  exit('No direct script access allowed');

class Plan_history_model extends CI_Model
{
  const QUOTE = 1;
  const SOLD = 2;
  const PAID = 3;
  const CLAIMED = 4;
  const CANCEL = 5;
  const REFUND = 6;
  const CHANGED = 7;

  const MAX_PLANS = 200;

  public $logstr;
  public $sqlstr;

  /**
   * Get plan_history current policy number
   * 
   * @param	integer	$plan_id		Parameters
   * @return	string					policy number
   */
  public function get_plan_by_id($plan_history_id)
  {
    $sql = "SELECT * FROM plan_history WHERE plan_history_id='" . (int)$plan_history_id . "'";
    return $this->db->query($sql)->row_array();
  }

  /**
   * Get plan_history current policy number
   * 
   * @param	integer	$plan_id		Parameters
   * @return	string					policy number
   */
  public function get_plan_history_by_plan_id($plan_id)
  {
    $sql = "SELECT * FROM plan_history WHERE plan_id='" . (int)$plan_id . "' ORDER BY plan_history_id DESC limit 1";
    return $this->db->query($sql)->row_array();
  }

  /**
   * Create plan
   * 
   * @param	array	$para		Parameters
   * @return	array					user table search result
   */
  public function add($plan_id, $new_status_id)
  {
    $ishead = 1;
    if ($this->db->where('plan_id', $plan_id)->get('plan_history')->row_array()) {
      $ishead = 0;
    }
    $sql  = "INSERT INTO plan_history (plan_id, ishead, customer_id, user_id, status_id, region_id, policy, agree, product_short, batch_number, apply_date, isfamilyplan, arrival_date, effective_date, expiry_date, beneficiary, stable_condition, stable_condition_confirm, rate_options, holiday_rate, spouse, sum_insured, deductible_amount, dailyrate, actualrate, totaldays, totalyears, premium, tax, commission_amount, street_number, street_name, suite_number, city, province2, country2, postcode, payment_id, note)";
    $sql .= " SELECT plan_id, ".$ishead.", customer_id, user_id, ".intval($new_status_id).", region_id, policy, agree, product_short, batch_number, apply_date, isfamilyplan, arrival_date, effective_date, expiry_date, beneficiary, stable_condition, stable_condition_confirm, rate_options, holiday_rate, spouse, sum_insured, deductible_amount, dailyrate, premium/totaldays, totaldays, totalyears, premium, tax, commission_amount, street_number, street_name, suite_number, city, province2, country2, postcode, payment_id, note FROM plan";
    $sql .= " WHERE plan_id=".intval($plan_id);
    $this->db->query($sql);
    $plan_history_id = $this->db->insert_id();
    return $plan_history_id;
  }

  public function add_remove($plan_history_id)
  {
    // Add a cancel record for old record
    $sql  = "INSERT INTO plan_history (plan_id, customer_id, user_id, status_id, region_id, policy, agree, product_short, batch_number, apply_date, isfamilyplan, arrival_date, effective_date, expiry_date, beneficiary, stable_condition, stable_condition_confirm, rate_options, holiday_rate, spouse, sum_insured, deductible_amount, dailyrate, actualrate, totaldays, totalyears, premium, tax, commission_amount, street_number, street_name, suite_number, city, province2, country2, postcode, payment_id, note)";
    $sql .= " SELECT plan_id, customer_id, user_id, 5, region_id, policy, agree, product_short, batch_number, apply_date, isfamilyplan, arrival_date, effective_date, expiry_date, beneficiary, stable_condition, stable_condition_confirm, rate_options, holiday_rate, spouse, sum_insured, deductible_amount, -dailyrate, -premium/totaldays, totaldays, totalyears, -premium, -tax, -commission_amount, street_number, street_name, suite_number, city, province2, country2, postcode, payment_id, note FROM plan_history";
    $sql .= " WHERE plan_history_id=".intval($plan_history_id);
    $this->db->query($sql);
    $plan_history_id = $this->db->insert_id();
    return $plan_history_id;
  }

  /**
   * Update plan_history information
   * 
   * @param	integer	$plan_id	plan_id
   * @param	array	$para		Parameters
   * @return	array					user table search result
   */
  public function update($plan_history_id, $para)
  {
    $this->db->where("plan_history_id", $plan_history_id);
    if (isset($para['plan_id'])) {
      $this->db->set("plan_id", $para['plan_id']);
    }
    if (isset($para['ishead'])) {
      $this->db->set("ishead", $para['ishead']);
    }
    if (isset($para['add_time'])) {
      $this->db->set("add_time", $para['add_time']);
    }
    if (isset($para['customer_id'])) {
      $this->db->set("customer_id", $para['customer_id']);
    }
    if (isset($para['user_id'])) {
      $this->db->set("user_id", $para['user_id']);
    }
    if (isset($para['status_id'])) {
      $this->db->set("status_id", $para['status_id']);
    }
    if (isset($para['region_id'])) {
      $this->db->set("region_id", $para['region_id']);
    }
    if (isset($para['policy'])) {
      $this->db->set("policy", $para['policy']);
    }
    if (isset($para['agree'])) {
      $this->db->set("agree", $para['agree']);
    }
    if (isset($para['product_short'])) {
      $this->db->set("product_short", $para['product_short']);
    }
    if (isset($para['batch_number'])) {
      $this->db->set("batch_number", $para['batch_number']);
    }
    if (isset($para['apply_date'])) {
      $this->db->set("apply_date", $para['apply_date']);
    }
    if (isset($para['isfamilyplan'])) {
      $this->db->set("isfamilyplan", $para['isfamilyplan']);
    }
    if (isset($para['arrival_date'])) {
      $this->db->set("arrival_date", $para['arrival_date']);
    }
    if (isset($para['effective_date'])) {
      $this->db->set("effective_date", $para['effective_date']);
    }
    if (isset($para['expiry_date'])) {
      $this->db->set("expiry_date", $para['expiry_date']);
    }
    if (isset($para['beneficiary'])) {
      $this->db->set("beneficiary", $para['beneficiary']);
    }
    if (isset($para['stable_condition'])) {
      $this->db->set("stable_condition", $para['stable_condition']);
    }
    if (isset($para['stable_condition_confirm'])) {
      $this->db->set("stable_condition_confirm", $para['stable_condition_confirm']);
    }
    if (isset($para['rate_options'])) {
      $this->db->set("rate_options", $para['rate_options']);
    }
    if (isset($para['holiday_rate'])) {
      $this->db->set("holiday_rate", $para['holiday_rate']);
    }
    if (isset($para['spouse'])) {
      $this->db->set("spouse", $para['spouse']);
    }
    if (isset($para['sum_insured'])) {
      $this->db->set("sum_insured", $para['sum_insured']);
    }
    if (isset($para['deductible_amount'])) {
      $this->db->set("deductible_amount", $para['deductible_amount']);
    }
    if (isset($para['dailyrate'])) {
      $this->db->set("dailyrate", $para['dailyrate']);
    }
    if (isset($para['actualrate'])) {
      $this->db->set("actualrate", $para['actualrate']);
    }
    if (isset($para['totaldays'])) {
      $this->db->set("totaldays", $para['totaldays']);
    }
    if (isset($para['totalyears'])) {
      $this->db->set("totalyears", $para['totalyears']);
    }
    if (isset($para['premium'])) {
      $this->db->set("premium", $para['premium']);
    }
    if (isset($para['tax'])) {
      $this->db->set("tax", $para['tax']);
    }
    if (isset($para['commission_amount'])) {
      $this->db->set("commission_amount", $para['commission_amount']);
    }
    if (isset($para['street_number'])) {
      $this->db->set("street_number", $para['street_number']);
    }
    if (isset($para['street_name'])) {
      $this->db->set("street_name", $para['street_name']);
    }
    if (isset($para['suite_number'])) {
      $this->db->set("suite_number", $para['suite_number']);
    }
    if (isset($para['city'])) {
      $this->db->set("city", $para['city']);
    }
    if (isset($para['province2'])) {
      $this->db->set("province2", $para['province2']);
    }
    if (isset($para['country2'])) {
      $this->db->set("country2", $para['country2']);
    }
    if (isset($para['postcode'])) {
      $this->db->set("postcode", $para['postcode']);
    }
    if (isset($para['payment_id'])) {
      $this->db->set("payment_id", $para['payment_id']);
    }
    if (isset($para['note'])) {
      $this->db->set("note", $para['note']);
    }

    $this->db->update("plan_history");
    return $plan_history_id;
  }

  /**
   * Search Plans (policies) by conditions
   * 
   * @param	array	$para		Search parameters
   * @param	int		$limit		limit
   * @return	array					user table search result
   */
  public function plan_search($para, $limit = 0, $start = 0)
  {
    $plans = array();
    $carr = array();
    if (!empty($para['firstname'])) {
      $carr[] = "firstname LIKE " . $this->db->escape($para['firstname'] . "%");
    }
    if (!empty($para['lastname'])) {
      $carr[] = "lastname LIKE " . $this->db->escape($para['lastname'] . "%");
    }
    if (!empty($para['birthday'])) {
      if (!empty($para['birthday2'])) {
        $carr[] = "birthday >= " . $this->db->escape($para['birthday']);
        $carr[] = "birthday <= " . $this->db->escape($para['birthday2']);
      } else {
        $carr[] = "birthday >= " . $this->db->escape($para['birthday']);
      }
    }
    if (!empty($carr)) {
      $sql = "SELECT distinct plan_id FROM customer WHERE " . join(" AND ", $carr);
      $rows = $this->db->query($sql)->result_array();
      foreach ($rows as $row) {
        $plans[] = $row['plan_id'];
      }
      if (empty($plans)) {
        // Not customer on plans
        return array();
      }
    }
    $users = array();
    if ($beuser['user_group_id'] < 100) {
      if (!empty($para['uname'])) {
        $sql = "SELECT user_id FROM user WHERE firstname LIKE " . $this->db->escape($para['uname'] . "%") . " OR lastname LIKE " . $this->db->escape($para['uname'] . "%");
        $rows = $this->db->query($sql)->result_array();
        foreach ($rows as $row) {
          $users[] = $row['user_id'];
        }
      }
    } else if ($beuser['user_group_id'] == 104) {
      if (!empty($para['uname'])) {
        $sql = "SELECT user_id FROM user WHERE (user_id='" . (int)$beuser['user_id'] . "' OR parent_user_id='" . (int)$beuser['user_id'] . "') AND firstname LIKE " . $this->db->escape($para['uname'] . "%") . " OR lastname LIKE " . $this->db->escape($para['uname'] . "%");
      } else {
        $sql = "SELECT user_id FROM user WHERE user_id='" . (int)$beuser['user_id'] . "' OR parent_user_id='" . (int)$beuser['user_id'] . "'";
      }
      $rows = $this->db->query($sql)->result_array();
      foreach ($rows as $row) {
        $users[] = $row['user_id'];
      }
    } else {
      $sql = "SELECT user_id FROM user WHERE user_id='" . (int)$beuser['user_id'] . "'";
      $rows = $this->db->query($sql)->result_array();
      foreach ($rows as $row) {
        $users[] = $row['user_id'];
      }
    }
    $sql  = "SELECT p.*, c.firstname, c.lastname, c.gender, c.birthday, u.firstname AS agent_firstname, u.lastname AS agent_lastname, u.user_id AS agent_id, u.business_phone as agent_phone FROM plan_history p";
    $sql .= " INNER JOIN customer c ON (p.customer_id=c.customer_id)";
    $sql .= " INNER JOIN user u ON (p.user_id=u.user_id)";
    $where = array();
    if (!empty($para['plan_id'])) {
      $where[] = "p.plan_id=" . (int)$para['plan_id'];
    }
    if (!empty($para['policy'])) {
      $where[] = "p.policy=" . $this->db->escape($para['policy']);
    } else if (!empty($para['policy_match'])) {
      $where[] = "p.policy LIKE " . $this->db->escape('%' . $para['policy_match'] . '%');
    }
    if (!empty($para['batch_number'])) {
      $where[] = "p.batch_number=" . $this->db->escape($para['batch_number']);
    }
    if (!empty($para['student_id'])) {
      $where[] = "p.student_id=" . $this->db->escape($para['student_id']);
    }
    if (!empty($para['status_id'])) {
      $where[] = "p.status_id='" . (int)$para['status_id'] . "'";
    }
    if (!empty($para['product_short'])) {
      $where[] = "p.product_short=" . $this->db->escape($para['product_short']);
    }
    if ($beuser['region_id']) {
      $where[] = "p.region_id = " . (int)$beuser['region_id'];
    }
    if (!empty($plans)) {
      $where[] = "p.plan_id IN (" . join(",", $plans) . ")";
    }
    if (!empty($users)) {
      $where[] = "p.user_id IN (" . join(",", $users) . ")";
    }
    if (!empty($para['province2'])) {
      $where[] = "p.province2=" . $this->db->escape($para['province2']);
    }
    if (!empty($para['country2'])) {
      $where[] = "p.country2=" . $this->db->escape($para['country2']);
    }
    if (!empty($para['apply_date'])) {
      if (!empty($para['apply_date2'])) {
        $where[] = "p.apply_date >= " . $this->db->escape($para['apply_date']);
        $where[] = "p.apply_date <= " . $this->db->escape($para['apply_date2']);
      } else {
        $where[] = "p.apply_date >= " . $this->db->escape($para['apply_date']);
      }
    }
    if (!empty($para['arrival_date'])) {
      if (!empty($para['arrival_date2'])) {
        $where[] = "p.arrival_date >= " . $this->db->escape($para['arrival_date']);
        $where[] = "p.arrival_date <= " . $this->db->escape($para['arrival_date2']);
      } else {
        $where[] = "p.arrival_date >= " . $this->db->escape($para['arrival_date']);
      }
    }
    if (!empty($para['effective_date'])) {
      if (!empty($para['effective_date2'])) {
        $where[] = "p.effective_date >= " . $this->db->escape($para['effective_date']);
        $where[] = "p.effective_date <= " . $this->db->escape($para['effective_date2']);
      } else {
        $where[] = "p.effective_date >= " . $this->db->escape($para['effective_date']);
      }
    }
    if (!empty($para['expiry_date'])) {
      if (!empty($para['expiry_date2'])) {
        $where[] = "p.expiry_date >= " . $this->db->escape($para['expiry_date']);
        $where[] = "p.expiry_date <= " . $this->db->escape($para['expiry_date2']);
      } else {
        $where[] = "p.expiry_date >= " . $this->db->escape($para['expiry_date']);
      }
    }
    if (!empty($para['last_update'])) {
      if (!empty($para['last_update2'])) {
        $where[] = "p.last_update >= " . $this->db->escape($para['last_update']);
        $where[] = "p.last_update <= " . $this->db->escape($para['last_update2']);
      } else {
        $where[] = "p.last_update >= " . $this->db->escape($para['last_update']);
      }
    }
    if (!empty($where)) {
      $sql .= " WHERE " . join(" AND ", $where);
    } else {
      if (empty($limit)) {
        $limit = 200;
      }
    }
    $sql .= " ORDER BY plan_id DESC";
    if ($limit) {
      if ($start) {
        $sql .= " LIMIT " . (int)$start . ", " . (int)$limit;
      } else {
        $sql .= " LIMIT " . (int)$limit;
      }
    } else {
      $sql .= " LIMIT " . self::MAX_PLANS;
    }
    return $this->db->query($sql)->result_array();
  }

  public function plan_search_count($para)
  {
    $beuser = $this->session->userdata('beuser');
    if (empty($beuser)) {
      return 0;
    }

    $plans = array();
    $carr = array();
    if (!empty($para['firstname'])) {
      $carr[] = "firstname LIKE " . $this->db->escape($para['firstname'] . "%");
    }
    if (!empty($para['lastname'])) {
      $carr[] = "lastname LIKE " . $this->db->escape($para['lastname'] . "%");
    }
    if (!empty($para['birthday'])) {
      if (!empty($para['birthday2'])) {
        $carr[] = "birthday >= " . $this->db->escape($para['birthday']);
        $carr[] = "birthday <= " . $this->db->escape($para['birthday2']);
      } else {
        $carr[] = "birthday >= " . $this->db->escape($para['birthday']);
      }
    }
    if (!empty($carr)) {
      $sql = "SELECT distinct plan_id FROM customer WHERE " . join(" AND ", $carr);
      $rows = $this->db->query($sql)->result_array();
      foreach ($rows as $row) {
        $plans[] = $row['plan_id'];
      }
      if (empty($plans)) {
        // Not customer on plans
        return 0;
      }
    }
    $users = array();
    if ($beuser['user_group_id'] < 100) {
      if (!empty($para['uname'])) {
        $sql = "SELECT user_id FROM user WHERE firstname LIKE " . $this->db->escape($para['uname'] . "%") . " OR lastname LIKE " . $this->db->escape($para['uname'] . "%");
        $rows = $this->db->query($sql)->result_array();
        foreach ($rows as $row) {
          $users[] = $row['user_id'];
        }
      }
    } else if ($beuser['user_group_id'] == 104) {
      if (!empty($para['uname'])) {
        $sql = "SELECT user_id FROM user WHERE (user_id='" . (int)$beuser['user_id'] . "' OR parent_user_id='" . (int)$beuser['user_id'] . "') AND firstname LIKE " . $this->db->escape($para['uname'] . "%") . " OR lastname LIKE " . $this->db->escape($para['uname'] . "%");
      } else {
        $sql = "SELECT user_id FROM user WHERE user_id='" . (int)$beuser['user_id'] . "' OR parent_user_id='" . (int)$beuser['user_id'] . "'";
      }
      $rows = $this->db->query($sql)->result_array();
      foreach ($rows as $row) {
        $users[] = $row['user_id'];
      }
    } else {
      $sql = "SELECT user_id FROM user WHERE user_id='" . (int)$beuser['user_id'] . "'";
      $rows = $this->db->query($sql)->result_array();
      foreach ($rows as $row) {
        $users[] = $row['user_id'];
      }
    }
    $sql  = "SELECT count(*) as cnt FROM plan_history p";
    $sql .= " INNER JOIN customer c ON (p.customer_id=c.customer_id)";
    $sql .= " INNER JOIN user u ON (p.user_id=u.user_id)";
    $where = array();
    if (!empty($para['plan_id'])) {
      $where[] = "p.plan_id=" . (int)$para['plan_id'];
    }
    if (!empty($para['policy'])) {
      $where[] = "p.policy=" . $this->db->escape($para['policy']);
    } else if (!empty($para['policy_match'])) {
      $where[] = "p.policy LIKE " . $this->db->escape('%' . $para['policy_match'] . '%');
    }
    if (!empty($para['batch_number'])) {
      $where[] = "p.batch_number=" . $this->db->escape($para['batch_number']);
    }
    if (!empty($para['student_id'])) {
      $where[] = "p.student_id=" . $this->db->escape($para['student_id']);
    }
    if (!empty($para['status_id'])) {
      $where[] = "p.status_id='" . (int)$para['status_id'] . "'";
    }
    if (!empty($para['product_short'])) {
      $where[] = "p.product_short=" . $this->db->escape($para['product_short']);
    }
    if ($beuser['region_id']) {
      $where[] = "p.region_id = " . (int)$beuser['region_id'];
    }
    if (!empty($plans)) {
      $where[] = "p.plan_id IN (" . join(",", $plans) . ")";
    }
    if (!empty($users)) {
      $where[] = "p.user_id IN (" . join(",", $users) . ")";
    }
    if (!empty($para['province2'])) {
      $where[] = "p.province2=" . $this->db->escape($para['province2']);
    }
    if (!empty($para['country2'])) {
      $where[] = "p.country2=" . $this->db->escape($para['country2']);
    }
    if (!empty($para['apply_date'])) {
      if (!empty($para['apply_date2'])) {
        $where[] = "p.apply_date >= " . $this->db->escape($para['apply_date']);
        $where[] = "p.apply_date <= " . $this->db->escape($para['apply_date2']);
      } else {
        $where[] = "p.apply_date >= " . $this->db->escape($para['apply_date']);
      }
    }
    if (!empty($para['arrival_date'])) {
      if (!empty($para['arrival_date2'])) {
        $where[] = "p.arrival_date >= " . $this->db->escape($para['arrival_date']);
        $where[] = "p.arrival_date <= " . $this->db->escape($para['arrival_date2']);
      } else {
        $where[] = "p.arrival_date >= " . $this->db->escape($para['arrival_date']);
      }
    }
    if (!empty($para['effective_date'])) {
      if (!empty($para['effective_date2'])) {
        $where[] = "p.effective_date >= " . $this->db->escape($para['effective_date']);
        $where[] = "p.effective_date <= " . $this->db->escape($para['effective_date2']);
      } else {
        $where[] = "p.effective_date >= " . $this->db->escape($para['effective_date']);
      }
    }
    if (!empty($para['expiry_date'])) {
      if (!empty($para['expiry_date2'])) {
        $where[] = "p.expiry_date >= " . $this->db->escape($para['expiry_date']);
        $where[] = "p.expiry_date <= " . $this->db->escape($para['expiry_date2']);
      } else {
        $where[] = "p.expiry_date >= " . $this->db->escape($para['expiry_date']);
      }
    }
    if (!empty($para['last_update'])) {
      if (!empty($para['last_update2'])) {
        $where[] = "p.last_update >= " . $this->db->escape($para['last_update']);
        $where[] = "p.last_update <= " . $this->db->escape($para['last_update2']);
      } else {
        $where[] = "p.last_update >= " . $this->db->escape($para['last_update']);
      }
    }
    if (!empty($where)) {
      $sql .= " WHERE " . join(" AND ", $where);
    }
    if ($rt = $this->db->query($sql)->row_array()) {
      return $rt['cnt'];
    }
    return 0;
  }

  /**
   * Search Plans (policies) by conditions for export
   * 
   * @param	array	$para		Search parameters
   * @param	int		$limit		limit number
   * @param	int		$start		start from number
   * @param	int		$payment		need payment
   * @return	array					user table search result
   */
  public function plan_export_search($para, $payment = 0, $limit = 0, $start = 0)
  {
    $beuser = $this->session->userdata('beuser');
    if (empty($beuser)) {
      return array();
    }

    $rArr = $this->plan_search($para, $limit, $start);
    for ($i = 0; $i < sizeof($rArr); $i++) {
      if ($rArr[$i]['isfamilyplan']) {
        $this->db->where('plan_id', $rArr[$i]['plan_id']);
        $this->db->where('parent_customer_id', $rArr[$i]['customer_id']);
        $cArr = $this->db->get('customer')->result_array();
        for ($j = 0; $j < sizeof($cArr); $j++) {
          $m = $j + 1;
          $rArr[$i]['gender_' . $m] = $cArr[$j]['gender'];
          $rArr[$i]['firstname_' . $m] = $cArr[$j]['firstname'];
          $rArr[$i]['lastname_' . $m] = $cArr[$j]['lastname'];
          $rArr[$i]['birthday_' . $m] = $cArr[$j]['birthday'];
        }
      }
      if ($payment) {
        $nameArr = $this->db->list_fields('payment');
        $this->db->where('plan_id', $rArr[$i]['plan_id']);
        // $this->db->where_in('pay_type', array('premium','refund');
        $this->db->where('amount>', 0.001);
        $pArr = $this->db->get('payment')->result_array();
        for ($j = 0; $j < sizeof($pArr); $j++) {
          foreach ($nameArr as $name) {
            $rArr[$i][$name . '_' . $j] = $pArr[$j][$name];
          }
        }
      }
    }
    return $rArr;
  }

  /**
   * Get plan's commission
   * 
   * @param integer $plan_id
   * @return float 
   */
  public function get_commission($plan_id, $amount = 0)
  {
    $plan = $this->get_plan_by_id($plan_id);
    if (empty($plan)) {
      return 0;
    }

    $this->db->where('user_id', $plan['user_id']);
    $this->db->where('product_short', $plan['product_short']);
    $user_product = $this->db->get('user_product')->row_array();
    if (empty($user_product)) {
      return 0;
    }

    if (empty($amount)) {
      $commission = $plan['premium'] * $user_product['commission'] / 100;
    } else {
      $commission = $amount * $user_product['commission'] / 100;
    }
    return $commission;
  }

  /**
   * Get summary for claim system
   * 
   * @param array	input parameter
   * @return array 
   */
  public function claim_summary($data)
  {
    $products = " AND product_short IN ('JFS','JFE','BHS','JES',JFPL','JFSL','JFGD','JESP','JUS','JFC','JFP','NUS','JFVTC','JFR','OPL','TOP')";
    $st = new DateTime($data['start_dt']);
    $et = new DateTime($data['end_dt']);
    $interval = new DateInterval('P1M');

    $ps = array();
    if (isset($data['agent_id']) || isset($data['product_short'])) {
      $ststr = $st->format("Y-m-01 00:00:00");
      $edstr = $et->format("Y-m-t 23:59:59");
      $sql  = "SELECT DISTINCT policy FROM plan_history WHERE status_id IN (" . SELF::CHANGED . "," . SELF::PAID . "," . SELF::SOLD . "," . SELF::CLAIMED . ")";
      if (isset($data['agent_id'])) {
        $sql .= " AND user_id=" . $this->db->escape($data['agent_id']);
      }
      if (isset($data['product_short'])) {
        $sql .= " AND product_short=" . $this->db->escape($data['product_short']);
      } else {
        $sql .= $products;
      }
      $sql .= " AND effective_date<=" . $this->db->escape($edstr) . " AND expiry_date>=" . $this->db->escape($ststr);
      $policies = $this->db->query($sql)->result_array();
      foreach ($policies as $p) {
        $ps[] = $p['policy'];
      }
    }

    $rt = array();
    while ($st <= $et) {
      $monthstr = $st->format("Y-m");
      $ststr = $st->format("Y-m-01 00:00:00");
      $edstr = $st->format("Y-m-t 23:59:59");
      $st->add($interval);

      $rt[$monthstr] = array('writen' => 0, 'earned' => 0);

      $sql  = "SELECT SUM(premium) as writen FROM plan_history WHERE status_id IN (" . SELF::CHANGED . "," . SELF::PAID . "," . SELF::SOLD . "," . SELF::CLAIMED . ")";
      if (isset($data['agent_id'])) {
        $sql .= " AND user_id=" . $this->db->escape($data['agent_id']);
      }
      if (isset($data['product_short'])) {
        $sql .= " AND product_short=" . $this->db->escape($data['product_short']);
      } else {
        $sql .= $products;
      }
      $sql .= " AND effective_date<=" . $this->db->escape($edstr) . " AND effective_date>=" . $this->db->escape($ststr);
      if ($mrt = $this->db->query($sql)->row_array()) {
        $rt[$monthstr]['writen'] = (float)$mrt['writen'];
      }

      $sql  = "SELECT SUM(premium * (DATEDIFF(IF(expiry_date>" . $this->db->escape($edstr) . "," . $this->db->escape($edstr) . ",expiry_date),IF(effective_date<" . $this->db->escape($ststr) . "," . $this->db->escape($ststr) . ",effective_date))) / DATEDIFF(expiry_date, effective_date)) as earned";
      $sql .= " FROM plan_history WHERE status_id IN (" . SELF::CHANGED . "," . SELF::PAID . "," . SELF::SOLD . "," . SELF::CLAIMED . ")";
      if (isset($data['agent_id'])) {
        $sql .= " AND user_id=" . $this->db->escape($data['agent_id']);
      }
      if (isset($data['product_short'])) {
        $sql .= " AND product_short=" . $this->db->escape($data['product_short']);
      } else {
        $sql .= $products;
      }
      $sql .= " AND effective_date<=" . $this->db->escape($edstr) . " AND expiry_date>=" . $this->db->escape($ststr);
      if ($mrt = $this->db->query($sql)->row_array()) {
        $rt[$monthstr]['earned'] = (float)$mrt['earned'];
      }
    }
    return array('summary' => $rt, 'policies' => $ps);
  }
}
