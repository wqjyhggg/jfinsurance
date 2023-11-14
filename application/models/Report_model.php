<?php
if (!defined('BASEPATH'))
  exit('No direct script access allowed');


class Report_model extends CI_Model
{
  const QUOTE = 1;
  const SOLD = 2;
  const PAID = 3;
  const CLAIMED = 4;
  const CANCEL = 5;
  const REFUND = 6;
  const CHANGED = 7;

  const ADMIN = 1;
  const STAFF = 2;
  const ACCOUNTING = 3;
  const SCHOOL = 103;
  const BROKERAGE = 104;
  const AGENT = 105;

  public $payment_tables = [
    "payment", 
    "payment2019", 
    "payment2017"
  ];

  /**
   * Get sales report to agent data
   *
   * @param array $para Parameter array
   * @return array sales report to agent data
   */
  public function get_sales_report_agent($para)
  {
    if (empty($para['user_list'])) {
      // Agent report must have user_list
      return NULL;
    }
    $uArr = array();
    foreach ($para['user_list'] as $u) {
      $uArr[] = $u['user_id'];
    }

    $sql  = "SELECT pa.amount,";
    $sql .= "		pa2.amount as commission,";
    $sql .= "		pa.payment_id,";
    $sql .= "		pa.invoice_num,";
    $sql .= "		pa.ispaid,";
    $sql .= "		pa.pay_date,";
    $sql .= "		pa.added,";
    $sql .= "		pa.pay_type,";
    $sql .= "		pa.last_update,";
    $sql .= "		pa.pay_mothed,";
    $sql .= "		pl.plan_id,";
    $sql .= "		pl.user_id,";
    $sql .= "		pl.status_id,";
    $sql .= "		pl.region_id,";
    $sql .= "		pl.policy,";
    $sql .= "		pl.product_short,";
    $sql .= "		pl.apply_date,";
    $sql .= "		pl.effective_date,";
    $sql .= "		pl.expiry_date,";
    $sql .= "		pl.dailyrate,";
    $sql .= "		pl.totaldays,";
    $sql .= "		pl.premium,";
    $sql .= "		pl.note,";
    $sql .= "		pl.contact_email,";
    $sql .= "		pl.contact_phone,";
    $sql .= "		pr.full_name,";
    $sql .= "		pr.up_insuer,";
    $sql .= "		CONCAT(cu.firstname, ' ', cu.lastname) as insured";
    $sql .= " FROM __payment__ pa";
    $sql .= " JOIN plan pl ON (pa.plan_id=pl.plan_id)";
    $sql .= " JOIN product pr ON (pl.product_short=pr.product_short)";
    $sql .= " JOIN customer cu ON (pl.customer_id=cu.customer_id)";
    $sql .= " LEFT JOIN __payment__ pa2 ON (pa.plan_id=pa2.plan_id AND pa.payment_id=pa2.premium_payment_id AND pa2.pay_type IN ('commission','cancel_commission','refund_commission'))";
    $sql .= " WHERE pa.pay_type IN ('premium','cancel','refund') AND ABS(pa.amount)>=0.10";
    if (!empty($para['payment_added_from'])) {
      $sql .= " AND pa.added >= " . $this->db->escape($para['payment_added_from'] . " 00:00:00");
    }
    if (!empty($para['payment_added_to'])) {
      $sql .= " AND pa.added <= " . $this->db->escape($para['payment_added_to'] . " 23:59:59");
    }
    if (!empty($para['payment_date_from'])) {
      $sql .= " AND pa.last_update >= " . $this->db->escape($para['payment_date_from'] . " 00:00:00");
    }
    if (!empty($para['payment_date_to'])) {
      $sql .= " AND pa.last_update <= " . $this->db->escape($para['payment_date_to'] . " 23:59:59");
    }
    if (!empty($para['agent_id'])) {
      $sql .= " AND pl.user_id='" . (int)$para['agent_id'] . "'";
    }
    $sql .= " AND pl.user_id IN ('" . join("','", $uArr) . "')";
    if (!empty($para['product_short'])) {
      $sql .= " AND pl.product_short=" . $this->db->escape($para['product_short']);
    }
    if (!empty($para['region_id'])) {
      $sql .= " AND pl.region_id='" . (int)$para['region_id'] . "'";
    }

    $sqlu = "";
    foreach ($this->payment_tables as $tb) {
      $sqlu .= (empty($sqlu)?"":" UNION ").str_replace('__payment__', $tb, $sql);
    }

    $sql = $sqlu . " ORDER BY user_id ASC, apply_date";
    $query = $this->db->query($sql)->result_array();

    $results = array();
    foreach ($query as $row) {
      if (empty($results[$row['user_id']])) {
        $agent = $this->db->query("SELECT * from user WHERE user_id='" . (int)$row['user_id'] . "'")->row_array();
        $results[$row['user_id']] = array(
          'data' => array(
            'agent_username' => $agent['username'],
            'agent_email' => $agent['email'],
            'agent_firstname' => $agent['firstname'],
            'agent_lastname' => $agent['lastname'],
            'policy_premium' => 0,
            'net_premium' => 0,
          ),
          'results' => array(),
          'payment' => 0,
          'commission' => 0
        );
      }

      $results[$row['user_id']]['data']['policy_premium'] += $row['amount'];
      $results[$row['user_id']]['data']['net_premium'] += $row['amount'] - $row['commission'];

      $results[$row['user_id']]['records'][] = $row;
    }
    return $results;
  }

  /**
   * Get sales report to JF data
   *
   * @param array $para Parameter array
   * @return array sales report to agent data
   */
  public function get_sales_report_jf($para)
  {
    $sql  = "SELECT (pa.amount - pa.admin_fee) as amount,";
    $sql .= "		pa2.amount as commission,";
    $sql .= "		pa.payment_id,";
    $sql .= "		pa.invoice_num,";
    $sql .= "		pa.ispaid,";
    $sql .= "		pa.pay_date,";
    $sql .= "		pa.added,";
    $sql .= "		pa.pay_type,";
    $sql .= "		pa.last_update,";
    $sql .= "		pa.pay_mothed,";
    $sql .= "		pl.plan_id,";
    $sql .= "		pl.user_id,";
    $sql .= "		pl.status_id,";
    $sql .= "		pl.region_id,";
    $sql .= "		pl.policy,";
    $sql .= "		pl.product_short,";
    $sql .= "		pl.apply_date,";
    $sql .= "		pl.effective_date,";
    $sql .= "		pl.expiry_date,";
    $sql .= "		pl.dailyrate,";
    $sql .= "		pl.totaldays,";
    $sql .= "		(pl.premium - pl.tax) as premium,";
    $sql .= "		pl.tax,";
    $sql .= "		pl.contact_email,";
    $sql .= "		pl.contact_phone,";
    $sql .= "		pl.note,";
    $sql .= "		pr.full_name,";
    $sql .= "		pr.up_insuer,";
    $sql .= "		CONCAT(cu.firstname, ' ', cu.lastname) as insured";
    $sql .= " FROM __payment__ pa";
    $sql .= " JOIN plan pl ON (pa.plan_id=pl.plan_id)";
    $sql .= " JOIN product pr ON (pl.product_short=pr.product_short)";
    $sql .= " JOIN customer cu ON (pl.customer_id=cu.customer_id)";
    $sql .= " LEFT JOIN __payment__ pa2 ON (pa.plan_id=pa2.plan_id AND pa.payment_id=pa2.premium_payment_id AND pa2.pay_type IN ('commission','cancel_commission','refund_commission'))";
    $sql .= " WHERE pa.pay_type IN ('premium','cancel','refund') AND ABS(pa.amount)>=0.01";
    if (!empty($para['payment_added_from'])) {
      $sql .= " AND pa.added >= " . $this->db->escape($para['payment_added_from'] . " 00:00:00");
    }
    if (!empty($para['payment_added_to'])) {
      $sql .= " AND pa.added <= " . $this->db->escape($para['payment_added_to'] . " 23:59:59");
    }
    if (!empty($para['payment_date_from'])) {
      $sql .= " AND pa.last_update >= " . $this->db->escape($para['payment_date_from'] . " 00:00:00");
    }
    if (!empty($para['payment_date_to'])) {
      $sql .= " AND pa.last_update <= " . $this->db->escape($para['payment_date_to'] . " 23:59:59");
    }
    if (!empty($para['agent_id'])) {
      $sql .= " AND pl.user_id='" . (int)$para['agent_id'] . "'";
    }
    if (!empty($para['product_short'])) {
      $sql .= " AND pl.product_short=" . $this->db->escape($para['product_short']);
    }
    if (!empty($para['region_id'])) {
      $sql .= " AND pl.region_id='" . (int)$para['region_id'] . "'";
    }
    $sqlu = "";
    foreach ($this->payment_tables as $tb) {
      $sqlu .= (empty($sqlu)?"":" UNION ").str_replace('__payment__', $tb, $sql);
    }
    $sql = $sqlu . " ORDER BY user_id ASC, apply_date";
    $query = $this->db->query($sql)->result_array();
    $results = array();
    $amount = 0;
    $commission = 0;
    foreach ($query as $rc) {
      if (empty($results[$rc['product_short']])) {
        $results[$rc['product_short']] = array('full_name' => $rc['full_name'], 'results' => array(), 'amount' => 0, 'commission' => 0);
      }
      $results[$rc['product_short']]['results'][] = $rc;
      $results[$rc['product_short']]['amount'] += $rc['amount'];
      $results[$rc['product_short']]['commission'] += $rc['commission'];
      $amount += $rc['amount'];
      $commission += $rc['commission'];
    }
    $results['amount'] = $amount;
    //$results['commission'] = $commission;
    return $results;
  }

  /**
   * Get sales report to JF data
   *
   * @param array $para Parameter array
   * @return array sales report to agent data
   */
  public function get_report_in_period($para)
  {
    $sql  = "SELECT distinct plan_id FROM __payment__ WHERE ABS(amount)>=0.01 AND pay_type='commission'";
    if (!empty($para['payment_added_from'])) {
      $sql .= " AND added >= " . $this->db->escape($para['payment_added_from'] . " 00:00:00");
    }
    if (!empty($para['payment_added_to'])) {
      $sql .= " AND added <= " . $this->db->escape($para['payment_added_to'] . " 23:59:59");
    }
    if (!empty($para['payment_date_from'])) {
      $sql .= " AND last_update >= " . $this->db->escape($para['payment_date_from'] . " 00:00:00");
    }
    if (!empty($para['payment_date_to'])) {
      $sql .= " AND last_update <= " . $this->db->escape($para['payment_date_to'] . " 23:59:59");
    }
    $sqlu = "";
    foreach ($this->payment_tables as $tb) {
      $sqlu .= (empty($sqlu)?"":" UNION ").str_replace('__payment__', $tb, $sql);
    }
    $sql = $sqlu;

    $sql2  = "SELECT distinct user_id FROM plan WHERE plan_id IN (" . $sql . ")";

    $sql3  = "SELECT user_id, username, email, firstname, lastname, receive_type, pay_type FROM user WHERE user_id IN (" . $sql2 . ") ORDER BY user_id ASC";
    return $this->db->query($sql3)->result_array();
  }

  /**
   * Get sales report to insurer data
   *
   * @param array $para Parameter array
   * @return array sales report to insurer data
   */
  public function get_sales_report_insurer($para)
  {
    $sql  = "SELECT";
    $sql .= "	pl.policy,";
    $sql .= "	pl.product_short,";
    $sql .= "	pl.status_id,";
    $sql .= "	pl.refund_date,";
    $sql .= "	c.firstname,";
    $sql .= "	c.lastname, ";
    $sql .= "	c.gender, ";
    $sql .= "	c.birthday, ";
    $sql .= "	CONCAT(pl.street_number, ' ', pl.street_name) AS address,";
    $sql .= "	pl.suite_number,";
    $sql .= "	pl.city,";
    $sql .= "	pl.province2 AS province,";
    $sql .= "	pl.postcode,";
    $sql .= "	pl.effective_date,";
    $sql .= "	pl.expiry_date,";
    $sql .= "	pl.totaldays AS total_days,";
    $sql .= "	pl.sum_insured,";
    $sql .= "	pl.package,";
    $sql .= "	pl.free_cancel,";
    $sql .= "	pl.annual_plan_days,";
    $sql .= "	pl.ad_and_d_ck,";
    $sql .= "	pl.ad_and_d_insured,";
    $sql .= "	pl.flight_accident_ck,";
    $sql .= "	pl.flight_accident_insured,";
    $sql .= "	pl.trip_cancellation_ck,";
    $sql .= "	pl.trip_cancellation_insured,";
    $sql .= "	pl.deductible_amount,";
    $sql .= "	(pa.amount - pa.admin_fee) AS policy_premium,";
    $sql .= "	(100 - pr.up_pay_rate) AS commission_rate_jf,";
    $sql .= "	pa2.amount AS pr_commission,";
    $sql .= "	pa3.amount AS up_commission,";
    $sql .= "	'2.5' AS merchant_fee_per, ";
    $sql .= "	(SELECT count(cus.plan_id) FROM customer cus WHERE cus.plan_id=pa.plan_id) as customer_cnt, ";
    $sql .= "	pa.added, ";
    $sql .= "	'5' AS claims_handling_fee_per";
    $sql .= " FROM __payment__ pa";
    $sql .= " JOIN plan pl ON pa.plan_id = pl.plan_id";
    $sql .= " JOIN customer c ON pl.customer_id = c.customer_id";
    $sql .= " JOIN product pr ON pl.product_short = pr.product_short";
    $sql .= " JOIN user u ON pl.user_id = u.user_id ";
    $sql .= " LEFT JOIN user_product up ON u.user_id = up.user_id and pr.product_short = up.product_short";
    $sql .= " LEFT JOIN __payment__ pa2 ON (pa.plan_id=pa2.plan_id AND pa.payment_id=pa2.premium_payment_id AND pa2.pay_type IN ('commission','cancel_commission','refund_commission'))";
    $sql .= " LEFT JOIN __payment__ pa3 ON (pa.plan_id=pa3.plan_id AND pa.payment_id=pa3.premium_payment_id AND pa3.pay_type IN ('up_commission','cancel_up_commission','refund_up_commission'))";
    $sql .= " WHERE pa.pay_type IN ('premium','cancel','refund') AND ABS(pa.amount)>=0.01";
    if (!empty($para['payment_added_from'])) {
      $sql .= " AND pa.added >= " . $this->db->escape($para['payment_added_from'] . " 00:00:00");
    }
    if (!empty($para['payment_added_to'])) {
      $sql .= " AND pa.added <= " . $this->db->escape($para['payment_added_to'] . " 23:59:59");
    }
    if (!empty($para['payment_date_from'])) {
      $sql .= " AND pa.last_update >= " . $this->db->escape($para['payment_date_from'] . " 00:00:00");
    }
    if (!empty($para['payment_date_to'])) {
      $sql .= " AND pa.last_update <= " . $this->db->escape($para['payment_date_to'] . " 23:59:59");
    }
    if (!empty($para['agent_id'])) {
      $sql .= " AND pl.user_id='" . (int)$para['agent_id'] . "'";
    }
    if (!empty($para['product_short'])) {
      $sql .= " AND pl.product_short=" . $this->db->escape($para['product_short']);
    }
    if (!empty($para['region_id'])) {
      $sql .= " AND pl.region_id='" . (int)$para['region_id'] . "'";
    }
    $sqlu = "";
    foreach ($this->payment_tables as $tb) {
      $sqlu .= (empty($sqlu)?"":" UNION ").str_replace('__payment__', $tb, $sql);
    }

    $query = $this->db->query($sqlu)->result_array();
    $results = array();

    // From Plan controll should change to plan model. do it later if have time.
    $toppackagename = array(
      'all_inclusive' => "All Inclusive plan",
      'single_medical_plan' => "Single medical plan",
      'annual_plan' => "Annual plan",
      'optional_plan' => "Optional plan",
    );

    foreach ($query as $row) {
      $row['commission_amount'] =  sprintf(
        "%01.2f",
        ($row['policy_premium'] * $row['commission_rate_jf'] / 100)
      );
      $row['merchant_fee'] =  sprintf(
        "%01.2f",
        ($row['policy_premium'] * $row['merchant_fee_per'] / 100)
      );
      $row['claims_handling_fee'] =  sprintf(
        "%01.2f",
        ($row['policy_premium'] * $row['claims_handling_fee_per'] / 100)
      );

      $row['total_compensation'] = $row['commission_amount'] + $row['merchant_fee'] + $row['claims_handling_fee'];
      $row['net_premium'] = sprintf("%01.2f", ($row['policy_premium'] - $row['total_compensation']));
      $row['total_compensation_per'] = $row['commission_rate_jf'] + $row['merchant_fee_per'] + $row['claims_handling_fee_per'];
      if ($row['total_days'] <= 0) {
        $row['daily_rate'] = 0;
      } else {
        $row['daily_rate'] = sprintf("%01.2f", ($row['policy_premium'] / $row['total_days']));
      }
      $row['coverage'] = '';
      if ($row['product_short'] == 'TOP') {
        if (isset($toppackagename[$row['package']])) $row['coverage'] = $toppackagename[$row['package']] . " -";
        switch ($row['package']) {
          case 'all_inclusive':
            if ($row['free_cancel']) {
              $row['coverage'] .= " with Free cancel;";
            }
            break;
          case 'single_medical_plan':
          case 'optional_plan':
            if ($row['ad_and_d_ck']) {
              $row['coverage'] .= " AD&D(" . $row['ad_and_d_insured'] . ");";
            }
            if ($row['flight_accident_ck']) {
              $row['coverage'] .= " Flight Accident(" . $row['flight_accident_insured'] . ");";
            }
            if ($row['trip_cancellation_ck']) {
              $row['coverage'] .= " Trip Cancellation(" . $row['trip_cancellation_insured'] . ");";
            }
            break;
          case 'annual_plan':
            break;
        }
      }
      $results[] = $row;
    }
    return $results;
  }

  public function get_sales_report_insurer2($para)
  {
    $sql  = "SELECT ph.*,";
    $sql .= "	c.firstname,";
    $sql .= "	c.lastname, ";
    $sql .= "	c.gender, ";
    $sql .= "	c.birthday, ";
    $sql .= "	IF(ph.payment_id=0,0,pa2.amount) AS commission_amount,";
    $sql .= "	IF(ph.payment_id=0,0,pa2.rate) AS commission_rate,";
    $sql .= "	'2.5' AS merchant_fee_per, ";
    $sql .= "	ph.premium*0.025 as merchant_fee, ";
    $sql .= "	'5' AS claims_handling_fee_per,";
    $sql .= "	ph.premium*0.05 as claims_handling_fee,";
    $sql .= "	ph.premium - (IF(ph.payment_id=0,0,pa2.amount)+ph.premium*0.075) as total_compensation,";
    $sql .= "	(IF(ph.payment_id=0,0,pa2.amount)+ph.premium*0.075) as net_premium,";
    $sql .= "	(100 - pr.up_pay_rate + 0.075) as total_compensation_per";
    $sql .= " FROM plan_history ph";
    $sql .= " JOIN customer c ON ph.customer_id = c.customer_id";
    $sql .= " JOIN product pr ON ph.product_short = pr.product_short";
    $sql .= " LEFT JOIN __payment__ pa2 ON (ph.payment_id!=0 AND ph.payment_id=pa2.premium_payment_id AND pa2.pay_type IN ('commission','cancel_commission','refund_commission'))";
    if (!empty($para['payment_added_from'])) {
      $sql .= "WHERE ph.add_time >= " . $this->db->escape($para['payment_added_from'] . " 00:00:00");
    } else {
      $sql .= "WHERE ph.add_time >= " . $this->db->escape(date("Y-m-d")." 00:00:00");
    }
    if (!empty($para['payment_added_to'])) {
      $sql .= " AND ph.add_time <= " . $this->db->escape($para['payment_added_to'] . " 23:59:59");
    } else {
      $sql .= " AND ph.add_time <= " . $this->db->escape(date("Y-m-d")." 23:59:59");
    }
    if (!empty($para['product_short'])) {
      if (is_array($para['product_short'])) {
        $sql .= " AND ph.product_short IN ('" . implode("','", str_replace("'", "", $para['product_short'])) . "')";
      } else {
        $sql .= " AND ph.product_short IN ('" . str_replace("'", "", $para['product_short']) . "')";
      }
    }
    $sqlu = "";
    foreach ($this->payment_tables as $tb) {
      $sqlu .= (empty($sqlu)?"":" UNION ").str_replace('__payment__', $tb, $sql);
    }
    $sql = $sqlu . " ORDER BY plan_id ASC, plan_history_id ASC";

    return $this->db->query($sql)->result_array();
  }

  public function get_sales_report_insurer22($para)
  {
    $sql  = "SELECT ph.*,";
    $sql .= "	c.firstname,";
    $sql .= "	c.lastname, ";
    $sql .= "	c.gender, ";
    $sql .= "	c.birthday, ";
    $sql .= "	IF(ph.payment_id=0,0,pa2.amount) AS commission_amount,";
    $sql .= "	IF(ph.payment_id=0,0,pa2.rate) AS commission_rate,";
    $sql .= "	'2.5' AS merchant_fee_per, ";
    $sql .= "	ph.premium*0.025 as merchant_fee, ";
    $sql .= "	'5' AS claims_handling_fee_per,";
    $sql .= "	ph.premium*0.05 as claims_handling_fee,";
    $sql .= "	ph.premium - (IF(ph.payment_id=0,0,pa2.amount)+ph.premium*0.075) as total_compensation,";
    $sql .= "	(IF(ph.payment_id=0,0,pa2.amount)+ph.premium*0.075) as net_premium,";
    $sql .= "	(100 - pr.up_pay_rate + 0.075) as total_compensation_per";
    $sql .= " FROM plan_history ph";
    $sql .= " JOIN customer c ON ph.customer_id = c.customer_id";
    $sql .= " JOIN product pr ON ph.product_short = pr.product_short";
    $sql .= " JOIN __payment__ pa ON (ph.payment_id=pa.payment_id)";
    $sql .= " JOIN __payment__ pa2 ON (ph.payment_id=pa2.premium_payment_id AND pa2.pay_type IN ('commission','cancel_commission','refund_commission'))";
    $sql .= " WHERE ph.status_id != 8 AND ph.payment_id != 0";
    $sql .= "   AND ph.plan_history_id=(SELECT MIN(ph2.plan_history_id) FROM plan_history ph2 WHERE ph2.plan_id=ph.plan_id AND ph2.payment_id=ph.payment_id)";
    if (!empty($para['payment_added_from'])) {
      $sql .= " AND pa.added >= " . $this->db->escape($para['payment_added_from'] . " 00:00:00");
    } else {
      $sql .= " AND pa.added >= " . $this->db->escape(date("Y-m-d")." 00:00:00");
    }
    if (!empty($para['payment_added_to'])) {
      $sql .= " AND pa.added <= " . $this->db->escape($para['payment_added_to'] . " 23:59:59");
    } else {
      $sql .= " AND pa.added <= " . $this->db->escape(date("Y-m-d")." 23:59:59");
    }
    if (!empty($para['product_short'])) {
      if (is_array($para['product_short'])) {
        $sql .= " AND ph.product_short IN ('" . implode("','", str_replace("'", "", $para['product_short'])) . "')";
      } else {
        $sql .= " AND ph.product_short IN ('" . str_replace("'", "", $para['product_short']) . "')";
      }
    }
    $sqlu = "";
    foreach ($this->payment_tables as $tb) {
      $sqlu .= (empty($sqlu)?"":" UNION ").str_replace('__payment__', $tb, $sql);
    }
    $sql = $sqlu . " ORDER BY plan_id ASC, plan_history_id ASC";

    return $this->db->query($sql)->result_array();
  }

  public function get_premium_report2($para)
  {
    if (1) {
      $sql = "SELECT ph2.plan_id FROM plan_history ph2 WHERE ph2.ishead=1 ";
      if (!empty($para['payment_added_from'])) {
        $sql .= " AND ph2.add_time >= " . $this->db->escape($para['payment_added_from'] . " 00:00:00");
      } else {
        $sql .= " AND ph2.add_time >= " . $this->db->escape(date("Y-m-d")." 00:00:00");
      }
      if (!empty($para['payment_added_to'])) {
        $sql .= " AND ph2.add_time <= " . $this->db->escape($para['payment_added_to'] . " 23:59:59");
      } else {
        $sql .= " AND ph2.add_time <= " . $this->db->escape(date("Y-m-d")." 23:59:59");
      }
      if (!empty($para['product_short'])) {
        if (is_array($para['product_short'])) {
          $sql .= " AND ph2.product_short IN ('" . implode("','", str_replace("'", "", $para['product_short'])) . "')";
        } else {
          $sql .= " AND ph2.product_short IN ('" . str_replace("'", "", $para['product_short']) . "')";
        }
      }
      $sql .= " ORDER BY ph2.plan_id ASC";
      if ($rt = $this->db->query($sql)->result_array()) {
        $rtt = array();
        $sql  = "SELECT ph.*,";
        $sql .= "	c.firstname,";
        $sql .= "	c.lastname, ";
        $sql .= "	c.lastname, ";
        $sql .= "	(SELECT count(cus.plan_id) FROM customer cus WHERE cus.plan_id=ph.plan_id) as customer_cnt, ";
        $sql .= "	IF (". $this->db->escape($para['earned_to'])."<ph.expiry_date, datediff(". $this->db->escape($para['earned_to']).", ph.effective_date) + 1, datediff(ph.expiry_date, ph.effective_date) + 1) as days_used ";
        $sql .= " FROM plan_history ph";
        $sql .= " JOIN customer c ON ph.customer_id = c.customer_id";
        $sql .= " WHERE ph.plan_id = ";
        $sql2 = " ORDER BY ph.plan_history_id ASC";
        foreach ($rt as $rc) {
          $sql1 = $sql . $rc["plan_id"] . $sql2;
          if ($rtt1 = $this->db->query($sql1)->result_array()) {
            $rtt = array_merge($rtt, $rtt1);
          }
        }
        return $rtt;
      }
      return array();
    } else {
    $sql  = "SELECT ph.*,";
    $sql .= "	c.firstname,";
    $sql .= "	c.lastname, ";
    $sql .= "	c.lastname, ";
    $sql .= "	(SELECT count(cus.plan_id) FROM customer cus WHERE cus.plan_id=ph.plan_id) as customer_cnt, ";
    $sql .= "	IF (". $this->db->escape($para['earned_to'])."<ph.expiry_date, datediff(". $this->db->escape($para['earned_to']).", ph.effective_date) + 1, datediff(ph.expiry_date, ph.effective_date) + 1) as days_used ";
    $sql .= " FROM plan_history ph";
    $sql .= " JOIN customer c ON ph.customer_id = c.customer_id";
    $sql .= " WHERE ph.plan_id in (";
    $sql .= " SELECT ph2.plan_id FROM plan_history ph2 WHERE ph2.ishead=1 ";
    if (!empty($para['payment_added_from'])) {
      $sql .= " AND ph2.add_time >= " . $this->db->escape($para['payment_added_from'] . " 00:00:00");
    } else {
      $sql .= " AND ph2.add_time >= " . $this->db->escape(date("Y-m-d")." 00:00:00");
    }
    if (!empty($para['payment_added_to'])) {
      $sql .= " AND ph2.add_time <= " . $this->db->escape($para['payment_added_to'] . " 23:59:59");
    } else {
      $sql .= " AND ph2.add_time <= " . $this->db->escape(date("Y-m-d")." 23:59:59");
    }
    $sql .= " )";
    if (!empty($para['product_short'])) {
      $sql .= " AND ph.product_short IN ('" . implode("','", str_replace("'", "", $para['product_short'])) . "')";
    }
    $sql .= " ORDER BY ph.plan_id ASC, ph.plan_history_id ASC";

    return $this->db->query($sql)->result_array();
    }
  }

  /**
   * Get Receivable report data
   *
   * @param array $para Parameter array
   * @return array receivable report data
   */
  public function get_receivable($para)
  {
    $sql  = "SELECT";
    $sql .= " `pl`.`apply_date` AS `order_date`,";
    $sql .= " `pl`.`policy`,";
    $sql .= " `pl`.`student_id`,";
    $sql .= " `u`.`username` AS `insurer`,";
    $sql .= " `pr`.`full_name` AS `product`,";
    $sql .= " CONCAT(c.lastname, \", \", c.firstname) AS insured_name,";
    $sql .= " `pl`.`effective_date`,";
    $sql .= " `pl`.`expiry_date`,";
    $sql .= " `pl`.`refund_date`,";
    $sql .= " pl.totaldays AS total_days,";
    $sql .= " `pl`.`dailyrate` AS `daily_rate`,";
    $sql .= " `pa`.`amount` AS `policy_premium`,";
    $sql .= " `pr`.`commission` AS `pr_commission`,";
    $sql .= " `up`.`commission` AS `up_commission`,";
    $sql .= " `u`.`user_id`, u.mail_name AS agent_name,";
    $sql .= " CONCAT(u.address, \" \", u.city) AS address,";
    $sql .= " `u`.`province2` AS `province`,";
    $sql .= " `u`.`postcode`,";
    $sql .= " `pl`.`status_id`,";
    $sql .= " `pa`.`payment_id`,";
    $sql .= " `pa`.`added` AS `pa_added`,";
    $sql .= " `pa`.`pay_type`,";
    $sql .= " `pa`.`currency`,";
    $sql .= " `pa`.`last_update`,";
    $sql .= " `pa2`.`amount` AS `commission_amount`";
    $sql .= " FROM `plan` `pl`";
    $sql .= " JOIN `customer` `c` ON `pl`.`customer_id` = `c`.`customer_id`";
    $sql .= " JOIN `product` `pr` ON `pl`.`product_short` = `pr`.`product_short`";
    $sql .= " JOIN `user` `u` ON `pl`.`user_id` = `u`.`user_id`";
    $sql .= " LEFT JOIN `user_product` `up` ON `u`.`user_id` = `up`.`user_id` and `pr`.`product_short` = `up`.`product_short`";
    $sql .= " JOIN __payment__ `pa` ON `pl`.`plan_id` = `pa`.`plan_id` AND `pa`.`pay_type` in ('premium','refund','cancel') AND `pa`.`ispaid` = 0";
    $sql .= " LEFT JOIN __payment__ `pa2` ON `pa`.`plan_id` = `pa2`.`plan_id` AND `pa2`.`pay_type` in ('commission','refund_commission','cancel_commission') AND `pa`.`payment_id` = `pa2`.`premium_payment_id`";
    $sql .= " WHERE `pa`.`amount` !=0";

    if (empty($para['policy_status'])) {
      $sql .= " AND `pl`.`status_id` >= 2";
    } else {
      $sql .= " AND `pl`.`status_id` = " . (int)$para['policy_status'];
    }
    if (!empty($para['payment_added_from'])) {
      $sql .= " AND pa.added >= " . $this->db->escape($para['payment_added_from'] . " 00:00:00");
    }
    if (!empty($para['payment_added_to'])) {
      $sql .= " AND pa.added <= " . $this->db->escape($para['payment_added_to'] . " 23:59:59");
    }
    if (!empty($para['payment_date_from'])) {
      $sql .= " AND pa.last_update >= " . $this->db->escape($para['payment_date_from'] . " 00:00:00");
    }
    if (!empty($para['payment_date_to'])) {
      $sql .= " AND pa.last_update <= " . $this->db->escape($para['payment_date_to'] . " 23:59:59");
    }
    if (!empty($para['agent_id'])) {
      $sql .= " AND pl.user_id='" . (int)$para['agent_id'] . "'";
    }
    if (!empty($para['product_short'])) {
      $sql .= " AND pl.product_short=" . $this->db->escape($para['product_short']);
    }
    if (!empty($para['region_id'])) {
      $sql .= " AND pl.region_id='" . (int)$para['region_id'] . "'";
    }
    $sqlu = "";
    foreach ($this->payment_tables as $tb) {
      $sqlu .= (empty($sqlu)?"":" UNION ").str_replace('__payment__', $tb, $sql);
    }
    $sql = $sqlu . " ORDER BY `policy`, `payment_id`";
    $query = $this->db->query($sql)->result_array();
    $results = $this->get_receivable_result($query);
    $results['period']['from'] = $para['payment_added_from'];
    $results['period']['to'] = $para['payment_added_to'];
    if (empty($results['period']['from']) && empty($results['period']['to'])) {
      $results['period']['from'] = $para['payment_date_from'];
      $results['period']['to'] = $para['payment_date_to'];
    }
    return $results;
  }

  private function get_receivable_result($query)
  {
    $results = array();
    $policy = '';
    $premium_last_update = 0;
    foreach ($query as $row) {
      $row['commission_rate'] = $row['pr_commission'];
      $row['net_premium'] = sprintf("%01.2f", ($row['policy_premium'] - $row['commission_amount']));

      $results['data'][$row['user_id']]['agency']['agent_name'] = $row['agent_name'];
      $results['data'][$row['user_id']]['agency']['address'] = $row['address'];
      $results['data'][$row['user_id']]['agency']['province'] = $row['province'];
      $results['data'][$row['user_id']]['agency']['postal_code'] = $row['postcode'];
      if (!isset($results['data'][$row['user_id']]['agency']['outstanding'])) {
        $results['data'][$row['user_id']]['agency']['outstanding'] = 0;
        $results['data'][$row['user_id']]['agency']['commission'] = 0;
        $results['data'][$row['user_id']]['agency']['payable_to_jf'] = 0;
      }

      $results['data'][$row['user_id']]['agency']['outstanding'] += $row['policy_premium'];
      $results['data'][$row['user_id']]['agency']['commission'] += $row['commission_amount'];
      $results['data'][$row['user_id']]['agency']['payable_to_jf'] += $row['policy_premium'] - $row['commission_amount'];
      if (abs($row['policy_premium']) > 0.005) {
        $row['cal_comm_rate'] = sprintf('%2.1f', $row['commission_amount'] * 100.0 / $row['policy_premium']);
      } else {
        $row['cal_comm_rate'] = 0;
      }
      $results['data'][$row['user_id']]['records'][] = $row;
    }
    return $results;
  }

  /**
   * Get Claim Report data
   *
   * @param array $para Parameter array
   * @return array Claim Report data
   */
  public function get_claim_report($para)
  {
    $sql  = "SELECT pl.policy, pl.deductible_amount, CONCAT(pl.street_number, ' ', pl.street_name) AS address, pl.suite_number, pl.city, pl.province2, pl.postcode,";
    $sql .= "       CONCAT(cl.firstname, ' ', cl.lastname) AS customer_name, cl.birthday,cl.gender, cl.claim_number, cl.claim_date,";
    $sql .= "       ci.service_date, ci.diagnosis, ci.coverage_code_id, ci.claimed, ci.paid, ci.received AS amount_received, ci.cheque_number, ci.cashed_date, ci.pay_to, ci.external_note,";
    $sql .= "       u.username AS agent_name,";
    $sql .= "       u2.username staff_name";
    $sql .= " FROM plan as pl";
    $sql .= " JOIN claim as cl ON (pl.plan_id=cl.plan_id)";
    $sql .= " JOIN citem as ci ON (cl.claim_id=ci.claim_id)";
    $sql .= " JOIN user as u ON (pl.user_id=u.user_id)";
    $sql .= " JOIN user as u2 ON (cl.user_id=u2.user_id)";

    $where = array();
    if (!empty($para['application_date_from'])) $where[] = "pl.apply_date >= " . $this->db->escape($para['application_date_from']);
    if (!empty($para['application_date_to'])) $where[] = "pl.apply_date <= " . $this->db->escape($para['application_date_to']);
    if (!empty($para['effective_date_from'])) $where[] = "pl.effective_date >= " . $this->db->escape($para['effective_date_from']);
    if (!empty($para['effective_date_to'])) $where[] = "pl.effective_date <= " . $this->db->escape($para['effective_date_to']);
    if (!empty($para['agent_id'])) $where[] = "pl.user_id='" . (int)$para['agent_id'] . "'";
    if (!empty($para['region_id'])) $where[] = "pl.region_id='" . (int)$para['region_id'] . "'";
    if (!empty($para['product_short'])) $where[] = "pl.product_short=" . $this->db->escape($para['product_short']);
    $where[] = "pl.status_id = '4'";
    $where[] = "ci.claimed > '0'";
    if (!empty($para['create_date_from'])) $where[] = "cl.claim_date >= " . $this->db->escape($para['create_date_from']);
    if (!empty($para['create_date_to'])) $where[] = "cl.claim_date <= " . $this->db->escape($para['create_date_to']);
    if (!empty($para['payment_update_date_from'])) $where[] = "ci.paid_date >= " . $this->db->escape($para['payment_update_date_from']);
    if (!empty($para['payment_update_date_to'])) $where[] = "ci.paid_date <= " . $this->db->escape($para['payment_update_date_to']);

    if (!empty($where)) {
      $sql .= " WHERE " . join(" AND ", $where);
    }

    $sql .= " ORDER BY pl.product_short ASC, pl.apply_date";
    $results = $this->db->query($sql)->result_array();
    return $results;
  }

  /**
   * Get Refund Report data
   *
   * @param array $para Parameter array
   * @return array Refund Report data
   */
  public function get_refund_report($para)
  {
    $sql = "SELECT pl.plan_id,
					pl.policy, pl.refund_date, CONCAT(pl.street_number, ' ', pl.street_name) AS address, pl.suite_number, pl.city, pl.province2 AS province, pl.postcode,
					(SELECT sum(amount) FROM __payment__ pm1 WHERE pm1.plan_id=pl.plan_id AND pay_type='premium') as premium,
					(SELECT sum(amount) FROM __payment__ pm1 WHERE pm1.plan_id=pl.plan_id AND pay_type='commission') as commission,
					CONCAT(c.firstname, ' ', c.lastname) AS customer_name, c.birthday,
					CONCAT(u.firstname, ' ', u.lastname) AS agent_name,
					pm.payment_id, pm.amount, pm.admin_fee, (pm.amount + pm.admin_fee) AS net_amount, pm.ispaid, pm.added, pm.pay_date, pm.pay_to
				FROM __payment__ pm
				JOIN plan pl ON pm.plan_id = pl.plan_id
				JOIN user u ON pl.user_id = u.user_id
				JOIN customer c ON pl.customer_id = c.customer_id
				WHERE pm.pay_type='refund'";
    $sql .= " AND pm.ispaid = '" . (int)$para['ispaid'] . "'";
    if (!empty($para['pay_date_from'])) {
      $sql .= " AND pm.pay_date >= " . $this->db->escape($para['pay_date_from']);
    }
    if (!empty($para['pay_date_to'])) {
      $sql .= " AND pm.pay_date <= " . $this->db->escape($para['pay_date_to']);
    }
    if (!empty($para['create_date_from'])) {
      $sql .= " AND pm.added >= " . $this->db->escape($para['create_date_from']);
    }
    if (!empty($para['create_date_to'])) {
      $sql .= " AND pm.added <= " . $this->db->escape($para['create_date_to']);
    }
    if (!empty($para['region_id'])) {
      $sql .= " AND pl.region_id = '" . (int)$para['region_id'] . "'";
    }
    if (!empty($para['product_short'])) {
      $sql .= " AND pl.product_short = " . $this->db->escape($para['product_short']);
    }
    $sqlu = "";
    foreach ($this->payment_tables as $tb) {
      $sqlu .= (empty($sqlu)?"":" UNION ").str_replace('__payment__', $tb, $sql);
    }
    $sql = $sqlu . " ORDER BY plan_id ASC, payment_id ASC";
    $query = $this->db->query($sql)->result_array();
    //die($this->db->last_query());
    return $query;
  }

  /**
   * Get Renewal  Report data
   *
   * @param array $para Parameter array
   * @return array Renewal Report data
   */
  public function get_renewal_report($para, $buser=null)
  {
    $query = $this->get_renewal_report_query($para, $buser);
    $results = $this->get_renewal_report_result($query);
    $results['period']['from'] = $para['expiry_date_from'];
    $results['period']['to'] = $para['expiry_date_to'];
    return $results;
  }

  private function get_renewal_report_query($para, $buser=null)
  {
    $this->renewal_report_fields();
    $this->renewal_report_from();
    $this->renewal_report_where($para, $buser);
    return $this->db->get()->result_array();
  }

  private function renewal_report_fields()
  {
    $this->db->select('
            pl.policy,
            pl.effective_date,
            pl.expiry_date,
            CONCAT(c.firstname, " ", c.lastname) AS customer_name,
            c.gender,
            c.birthday,
            pl.province2 AS province,
            pl.phone1 AS phone,
            pl.contact_phone,
            pl.phone1 AS phone,
            pl.contact_email AS email,
            CONCAT(u.firstname, " ", u.lastname) AS agent_name,
            u.user_id,
        	u.email AS agent_email
        ');
  }

  private function renewal_report_from()
  {
    $this->common_from();
  }

  private function renewal_report_where($para, $buser=null)
  {
    $this->db->where_in('pl.status_id', array(self::SOLD, self::PAID, self::CLAIMED, self::CHANGED));
    $this->common_report_where($para, $buser);
  }

  private function get_renewal_report_result($query)
  {
    $results = array();
    foreach ($query as $row) {
      $results['data'][$row['user_id']]['agency'] = $row['agent_name'];
      $results['data'][$row['user_id']]['agency_email'] = $row['agent_email'];
      if (!empty($row['suite_number'])) {
        $row['address'] .= ', ' . $row['suite_number'];
      }
      $results['data'][$row['user_id']]['records'][] = $row;
    }
    return $results;
  }

  /**
   * Get Commission report data
   *
   * @param array $para Parameter array
   * @return array commission report data
   */
  public function get_commission_report($para)
  {
    $sql  = "SELECT";
    $sql .= "	pa.payment_id,";
    $sql .= "	pa.plan_id,";
    $sql .= "	pa.premium_payment_id,";
    $sql .= "	pa.last_update,";
    $sql .= "	pa.added,";    //  Payment Date
    $sql .= "	pl.user_id,";
    $sql .= "	pl.policy,";
    $sql .= "	st.name AS status,";
    $sql .= "	pr.up_insuer,";
    $sql .= "	CONCAT(c.firstname, ' ', c.lastname) AS customer_name,";
    $sql .= "	pl.effective_date,";
    $sql .= "	pl.expiry_date,";
    $sql .= "	pl.totaldays AS total_days,";
    $sql .= "	(pa2.amount - pa2.admin_fee) AS premium,";
    $sql .= "	pa2.ispaid AS premiumispaid,";
    $sql .= "	pa.rate,";
    $sql .= "	pa.amount,";
    $sql .= "	pa.ispaid ";
    $sql .= " FROM __payment__ pa";
    $sql .= " JOIN plan pl ON pa.plan_id = pl.plan_id";
    $sql .= " JOIN customer c ON pl.customer_id = c.customer_id";
    $sql .= " JOIN product pr ON pl.product_short = pr.product_short";
    $sql .= " JOIN status st ON pl.status_id = st.status_id ";
    $sql .= " LEFT JOIN __payment__ pa2 ON (pa.premium_payment_id=pa2.payment_id)";
    $sql .= " WHERE pa.pay_type IN ('commission','cancel_commission','refund_commission') AND ABS(pa.amount)>=0.01 ";
    if (empty($para['ispaid'])) {
      $sql .= " AND pa.ispaid = 0";
    } else {
      $sql .= " AND pa.ispaid = 1";
    }
    if (!empty($para['payment_added_from'])) {
      $sql .= " AND pa.added >= " . $this->db->escape($para['payment_added_from'] . " 00:00:00");
    }
    if (!empty($para['payment_added_to'])) {
      $sql .= " AND pa.added <= " . $this->db->escape($para['payment_added_to'] . " 23:59:59");
    }
    if (!empty($para['payment_date_from'])) {
      $sql .= " AND pa.last_update >= " . $this->db->escape($para['payment_date_from'] . " 00:00:00");
    }
    if (!empty($para['payment_date_to'])) {
      $sql .= " AND pa.last_update <= " . $this->db->escape($para['payment_date_to'] . " 23:59:59");
    }
    if (!empty($para['agent_id'])) {
      if (!empty($para['asbroker'])) {
        $sql .= " AND pl.user_id IN (SELECT u.user_id FROM user u WHERE u.user_id='" . (int)$para['agent_id'] . "' OR u.parent_user_id='" . (int)$para['agent_id'] . "')";
      } else {
        $sql .= " AND pl.user_id='" . (int)$para['agent_id'] . "'";
      }
    }
    if (!empty($para['product_short'])) {
      $sql .= " AND pl.product_short=" . $this->db->escape($para['product_short']);
    }
    if (!empty($para['region_id'])) {
      $sql .= " AND pl.region_id='" . (int)$para['region_id'] . "'";
    }

    $sqlu = "";
    foreach ($this->payment_tables as $tb) {
      $sqlu .= (empty($sqlu)?"":" UNION ").str_replace('__payment__', $tb, $sql);
    }
    $sql = $sqlu . " ORDER BY user_id ASC, plan_id ASC, added ASC";

    $query = $this->db->query($sql)->result_array();
    $results = array();
    foreach ($query as $row) {
      if (empty($results[$row['user_id']])) {
        $agent = $this->db->query("SELECT * FROM user WHERE user_id='" . (int)$row['user_id'] . "'")->row_array();
        $results[$row['user_id']] = array('agent' => $agent, 'data' => array());
        if (!empty($para['asbroker']) && ($row['user_id'] == $para['agent_id'])) {
          $results['asbroker'] = $agent;
        }
      }
      $results[$row['user_id']]['data'][] = $row;
    }
    if (!empty($results) && empty($results['asbroker'])) {
      $results['asbroker'] = $this->db->query("SELECT * FROM user WHERE user_id='" . (int)$para['agent_id'] . "'")->row_array();
    }
    return $results;
  }

  /**
   * Get Agent Commission report data
   *
   * @param array $para Parameter array
   * @return array commission report data
   */
  public function get_agent_commission_report($para, $beuser=false)
  {
    if (!$beuser) {
      $beuser = $this->session->beuser;
    }
    $available_user_ids = empty($para['user_list'])?array():array_keys($para['user_list']);
    $paymenttb = "payment";
    if (!empty($para['payment_date_from'])) {
      $year = substr($para['payment_date_from'], 0, 4);
      if ($year <= '2019') {
        $paymenttb = "payment2019";
        if ($year <= '2017') {
          $paymenttb = "payment2017";
        }
      }
    } else if (!empty($para['payment_date_to'])) {
      $year = substr($para['payment_date_to'], 0, 4);
      if ($year <= '2019') {
        $paymenttb = "payment2019";
        if ($year <= '2017') {
          $paymenttb = "payment2017";
        }
      }
    }

    $sql  = "SELECT p.user_id, u.username, CONCAT(u.firstname, ' ', u.lastname) AS agent_name, SUM(p.amount) as total_balance, SUM(IF(p.ispaid=0, p.amount, 0)) as unpaid_premium, (SELECT MAX(pay_date) FROM ".$paymenttb." p2 WHERE p2.user_id=p.user_id) as last_paid, u.receive_type, u.note FROM ".$paymenttb." p ";
    $sql .= " JOIN user u ON (p.user_id=u.user_id)";
    $sql .= " WHERE p.pay_type='commission'";
    if (!empty($para['receive_type'])) {
      $sql .= " AND u.receive_type =" . $this->db->escape($para['receive_type']);
    }
    if (!empty($para['payment_date_from'])) {
      $sql .= " AND p.last_update >=" . $this->db->escape($para['payment_date_from'] . " 00:00:00");
    }
    if (!empty($para['payment_date_to'])) {
      $sql .= " AND p.last_update <=" . $this->db->escape($para['payment_date_to'] . " 23:59:59");
    }

    if (!empty($para['agent_id'])) {
      if (!in_array($para['agent_id'], $available_user_ids)) {
        $sql .= " AND p.user_id ='" . (int)$beuser['user_id'] . "'";
      } else {
        $sql .= " AND p.user_id ='" . (int)$para['agent_id'] . "'";
      }
    } else {
      if ($beuser['user_group_id'] > self::STAFF) {
        $sql .= " AND p.user_id IN (" . join(",", $available_user_ids) . ")";
      }
    }

    $sql .= " GROUP BY p.user_id";

    if (!empty($para['minvalue'])) {
      $sql .= " HAVING total_balance > " . (int)$para['minvalue'];
    } else {
      $sql .= " HAVING total_balance > 0";
    }
    $sql .= " ORDER BY p.user_id ASC";

    $part = $this->db->query($sql)->result_array();
    if (empty($part)) {
      return array();
    }
    return $part;
  }

  private function get_status($status_id)
  {
    $status = 0;
    switch ($status_id) {
      case self::QUOTE:
        $status = 'Quote';
        break;
      case self::SOLD:
        $status = 'Sold';
        break;
      case self::PAID:
        $status = 'Paid';
        break;
      case self::CLAIMED:
        $status = 'Claimed';
        break;
      case self::CANCEL:
        $status = 'Cancel';
        break;
      case self::REFUND:
        $status = 'Refunc';
        break;
    }
    return $status;
  }

  private function common_from()
  {
    $this->db->from('plan pl');
    $this->db->join('customer c', 'pl.customer_id = c.customer_id');
    $this->db->join('product pr', 'pl.product_short = pr.product_short');
    $this->db->join('user u', 'pl.user_id = u.user_id');
  }

  private function common_report_where($para, $beuser=null)
  {
    if (empty($beuser)) {
      $beuser = $this->session->beuser;
    }
    $available_user_ids = empty($para['user_list'])?array():array_keys($para['user_list']);
    $available_product_short = array();
    if (!empty($para['product_list'])) {
      foreach ($para['product_list'] as $product) {
        $available_product_short[] = $product['product_short'];
      }
    }

    if (!empty($available_product_short)) {
      if (empty($para['product_short']) || !in_array($para['product_short'], $available_product_short)) {
        $this->db->where_in('pr.product_short', $available_product_short);
      } else {
        $this->db->where('pr.product_short', $para['product_short']);
      }
    } else {
      $this->db->where('pr.product_short', '');
    }

    if (!empty($para['agent_id'])) {
      if (!in_array($para['agent_id'], $available_user_ids)) {
        $this->db->where('u.user_id', $beuser['user_id']);
      } else {
        $this->db->where('u.user_id', $para['agent_id']);
      }
    } else {
      if ($beuser['user_group_id'] > self::STAFF) {
        $this->db->where_in('u.user_id', $available_user_ids);
      }
    }
    if (!empty($para['region_id'])) {
      $this->db->where('pl.region_id', $para['region_id']);
    }
    if (!empty($para['application_date_from'])) {
      $this->db->where('pl.apply_date >=', $para['application_date_from']);
    }
    if (!empty($para['application_date_to'])) {
      $this->db->where('pl.apply_date <=', $para['application_date_to']);
    }
    if (!empty($para['arrival_date_from'])) {
      $this->db->where('pl.arrival_date >=', $para['arrival_date_from']);
    }
    if (!empty($para['arrival_date_to'])) {
      $this->db->where('pl.arrival_date <=', $para['arrival_date_to']);
    }
    if (!empty($para['effective_date_from'])) {
      $this->db->where('pl.effective_date >=', $para['effective_date_from']);
    }
    if (!empty($para['effective_date_to'])) {
      $this->db->where('pl.effective_date <=', $para['effective_date_to']);
    }
    if (!empty($para['expiry_date_from'])) {
      $this->db->where('pl.expiry_date >=', $para['expiry_date_from']);
    }
    if (!empty($para['expiry_date_to'])) {
      $this->db->where('pl.expiry_date <=', $para['expiry_date_to']);
    }
  }

  private function common_set_row($row)
  {
    $row['commission_rate'] = empty($row['up_commission']) ? $row['pr_commission'] : $row['up_commission'];
    $row['commission_amount'] =  sprintf("%01.2f", ($row['policy_premium'] * $row['commission_rate'] / 100));
    $row['net_premium'] = sprintf("%01.2f", ($row['policy_premium'] - $row['commission_amount']));
    $row['daily_rate'] = empty($row['total_days']) ? '' : sprintf("%01.2f", ($row['net_premium'] / $row['total_days']));
    return $row;
  }

  function get_annual($user_id, $year)
  {
    $row = $this->db->get_where('user_annual_report', array('user_id' => $user_id, 'year' => $year))->row_array();
    if ($row) {
      return json_decode($row['value'], TRUE);
    } else {
      return NULL;
    }
  }

  function get_month_payment($user_id, $year, $month)
  {
    $dtstart = $year . "-" . str_pad($month, 2, "0", STR_PAD_LEFT) . "-01 00:00:00";
    $dtend = date("Y-m-d 00:00:00", strtotime($dtstart . " +1 month"));
    $sql  = "SELECT";
    $sql .= "   sum(pa2.amount - pa2.admin_fee) AS premium,";
    $sql .= "   sum(pa.amount) as commission";
    $sql .= " FROM __payment__ pa";
    $sql .= " JOIN plan pl ON pa.plan_id = pl.plan_id";
    $sql .= " JOIN customer c ON pl.customer_id = c.customer_id";
    $sql .= " JOIN product pr ON pl.product_short = pr.product_short";
    $sql .= " JOIN status st ON pl.status_id = st.status_id ";
    $sql .= " LEFT JOIN __payment__ pa2 ON (pa.premium_payment_id=pa2.payment_id)";
    $sql .= " WHERE pl.user_id='" . intval($user_id) . "'";
    $sql .= " AND pa.added >= '" . $dtstart . "'";
    $sql .= " AND pa.added < '" . $dtend . "'";
    $sql .= " AND pa.pay_type IN ('commission','cancel_commission','refund_commission') AND ABS(pa.amount)>=0.01";
    $sqlu = "";
    foreach ($this->payment_tables as $tb) {
      $sqlu .= (empty($sqlu)?"":" UNION ").str_replace('__payment__', $tb, $sql);
    }
    $row = $this->db->query($sqlu)->row_array();
    if ($row) {
      return $row;
    }
    return array("commission" => 0, "premium" => 0);
  }

  function get_month_payment1($user_id, $year, $month, $types)
  {
    $dtstart = $year . "-" . str_pad($month, 2, "0", STR_PAD_LEFT) . "-01 00:00:00";
    $dtend = date("Y-m-d 00:00:00", strtotime($dtstart . " +1 month"));
    $sql = "SELECT SUM(pa.amount) as total FROM __payment__ pa JOIN plan pl ON (pa.plan_id = pl.plan_id) WHERE pl.user_id='" . intval($user_id) . "' AND pa.added>='" . $dtstart . "' AND pa.added<'" . $dtend . "' AND pa.pay_type IN (" . $types . ")";
    $sqlu = "";
    foreach ($this->payment_tables as $tb) {
      $sqlu .= (empty($sqlu)?"":" UNION ").str_replace('__payment__', $tb, $sql);
    }
    $row = $this->db->query($sqlu)->row_array();
    if ($row) {
      return $row['total'];
    }
    return 0;
  }

  function save_annual($user_id, $year, $by_id, $data)
  {
    $row = $this->db->get_where('user_annual_report', array('user_id' => $user_id, 'year' => $year))->row_array();
    if ($row) {
      $row['value'] = json_encode($data);
      $row['by_id'] = $by_id;
      $row['last_update'] = date("Y-m-d H:i:s");
      $this->db->replace('user_annual_report', $row);
    } else {
      $row = array('user_id' => $user_id, 'year' => $year, 'by_id' => $by_id, 'value' => json_encode($data));
      $this->db->insert('user_annual_report', $row);
    }
  }

  function get_pophome()
  {
    $row = $this->db->get_where('user_meta', array('user_id' => 1, 'type' => 'pophome'))->row_array();
    if ($row) {
      return rawurldecode($row['value']);
    }
    return '';
  }

  function set_pophome($html)
  {
    $row = $this->db->get_where('user_meta', array('user_id' => 1, 'type' => 'pophome'))->row_array();
    if ($row) {
      $row = array('user_id' => 1, 'type' => 'pophome', 'value' => $html, 'last_update' => date("Y-m-d H:i:s"));
      $this->db->replace('user_meta', $row);
    } else {
      $row = array('user_id' => 1, 'type' => 'pophome', 'value' => $html);
      $this->db->insert('user_meta', $row);
    }
  }
}
