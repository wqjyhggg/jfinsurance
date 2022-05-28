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
    if ($ishead && $plan_history_id) {
      $sql = "UPDATE `plan_history` ph SET ph.add_time=(SELECT added FROM payment pa WHERE ph.payment_id=pa.payment_id) WHERE plan_history_id=".$plan_history_id;
      $this->db->query($sql);
    }
    return $plan_history_id;
  }

  public function add_remove($plan_history_id)
  {
    // Add a cancel record for old record
    $sql  = "INSERT INTO plan_history (plan_id, customer_id, user_id, status_id, region_id, policy, agree, product_short, batch_number, apply_date, isfamilyplan, arrival_date, effective_date, expiry_date, beneficiary, stable_condition, stable_condition_confirm, rate_options, holiday_rate, spouse, sum_insured, deductible_amount, dailyrate, actualrate, totaldays, totalyears, premium, tax, commission_amount, street_number, street_name, suite_number, city, province2, country2, postcode, payment_id, note)";
    $sql .= " SELECT plan_id, customer_id, user_id, 8, region_id, policy, agree, product_short, batch_number, apply_date, isfamilyplan, arrival_date, effective_date, expiry_date, beneficiary, stable_condition, stable_condition_confirm, rate_options, holiday_rate, spouse, sum_insured, deductible_amount, -dailyrate, -premium/totaldays, totaldays, totalyears, -premium, -tax, -commission_amount, street_number, street_name, suite_number, city, province2, country2, postcode, payment_id, note FROM plan_history";
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

  public function remove_history_by_payment_id($plan_id, $payment_id)
  {
    if ($his = $this->db->where("plan_id", $plan_id)->where("payment_id", $payment_id)->get("plan_history")->row_array()) {
      // Find previous record is adjust or not.
      if ($his1 = $this->db->where("plan_history_id", $his["plan_history_id"]-1)->where("status_id", 8)->get("plan_history")->row_array()) {
        // Find both do delete
        $this->db->delete('plan_history', array('plan_history_id' => $his1["plan_history_id"]));
        $this->db->delete('plan_history', array('plan_history_id' => $his["plan_history_id"]));
      }
    }
  }
}
