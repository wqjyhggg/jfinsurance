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

    /**
     * Get sales report to agent data
     *
     * @param array $para Parameter array
     * @return array sales report to agent data
     */
    public function get_sales_report_agent($para)
    {
        $query = $this->get_sales_report_agent_query($para);
        $results = $this->get_sales_report_agent_result($query);
        return $results;
    }

    private function get_sales_report_agent_query($para)
    {
        $this->sales_report_agent_fields();
        $this->sales_report_agent_from();
        $this->sales_report_agent_where($para);
        return $this->db->get()->result_array();
    }

    private function sales_report_agent_fields()
    {
        $this->db->select('
            pl.apply_date AS order_date,
            pl.policy,
            pr.up_insuer AS insurer,
            pr.full_name AS product,
            CONCAT(c.lastname, ", ", c.firstname) AS insured_name,
            pl.effective_date,
            pl.expiry_date,
            pl.totaldays AS total_days,
            pl.dailyrate AS daily_rate,
            pl.status_id,
            st.name AS status,
        	pl.premium AS policy_premium,
            pr.commission AS pr_commission,
            up.commission AS up_commission,
            u.email AS agent_email,
            u.username AS agent_username,
        	u.firstname AS agent_firstname,
            u.lastname AS agent_lastname,
        	u.user_id
        ');
    }

    private function sales_report_agent_from()
    {
        $this->common_from();
        $this->db->join('user_product up', 'u.user_id = up.user_id and pr.product_short = up.product_short', 'left');
        $this->db->join('payment pa', 'pa.payment_id=
            (SELECT MIN(payment_id) FROM payment pa2 WHERE pl.payment_id = pa2.payment_id AND pa2.pay_type = "premium" AND ispaid = 1)', 'left');
        $this->db->join('status st', 'pl.status_id = st.status_id', 'left');
    }

    private function sales_report_agent_where($para)
    {
        $this->db->where_in('pl.status_id', array(self::SOLD, self::PAID, self::CLAIMED, self::CHANGED));
        $this->common_report_where($para);
        if (!empty($para['payment_date_from'])) {
            $this->db->where('pa.last_update >=', $para['payment_date_from']);
        }
        if (!empty($para['payment_date_to'])) {
            $this->db->where('pa.last_update <=', $para['payment_date_to']);
        }
    }

    private function get_sales_report_agent_result($query)
    {
        $results = array();
        foreach ($query as $row) {
            $row = $this->common_set_row($row);

            $results[$row['user_id']]['data']['agent_username'] = $row['agent_username'];
            $results[$row['user_id']]['data']['agent_email'] = $row['agent_email'];
            $results[$row['user_id']]['data']['agent_firstname'] = $row['agent_firstname'];
            $results[$row['user_id']]['data']['agent_lastname'] = $row['agent_lastname'];
            $results[$row['user_id']]['data']['policy_premium'] = (
                empty($results[$row['user_id']]['data']['policy_premium']) ?
                $row['policy_premium'] : $results[$row['user_id']]['data']['policy_premium'] + $row['policy_premium']);
            $results[$row['user_id']]['data']['net_premium'] = (
                empty($results[$row['user_id']]['data']['net_premium']) ?
                $row['net_premium'] : $results[$row['user_id']]['data']['net_premium'] + $row['net_premium']);

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
        $query = $this->get_sales_report_jf_query($para);
        $results = $this->get_sales_report_jf_result($query);
        return $results;
    }

    private function get_sales_report_jf_query($para)
    {
        $this->sales_report_jf_fields();
        $this->sales_report_jf_from();
        $this->sales_report_jf_where($para);
        return $this->db->get()->result_array();
    }

    private function sales_report_jf_fields()
    {
        $this->db->select('
            pl.plan_id,
            pl.apply_date AS order_date,
            pl.policy,
            pa.invoice_num,
            pr.up_insuer AS insurerCoName,
            pr.full_name AS product,
            CONCAT(c.lastname, ", ", c.firstname) AS insured,
            u.username AS agency,
            u.firstname AS agency_fname,
            u.lastname AS agency_lname,
            pl.effective_date,
            pl.expiry_date,
            datediff(pl.expiry_date, pl.effective_date) AS total_days,
            pl.dailyrate AS daily_rate,
            pl.premium AS policy_premium,
            pr.commission AS pr_commission,
            up.commission AS up_commission,
            u.user_id,
            pl.plan_id
        ');
    }

    private function sales_report_jf_from()
    {
        $this->common_from();
        $this->db->join('user_product up', 'u.user_id = up.user_id and pr.product_short = up.product_short', 'left');
        $this->db->join('payment pa', 'pa.payment_id=
            (SELECT MIN(payment_id) FROM payment pa2 WHERE pl.payment_id = pa2.payment_id AND pa2.pay_type = "premium" AND ispaid = 1)', 'left');
    }

    private function sales_report_jf_where($para)
    {
        $this->db->where_in('pl.status_id', array(self::SOLD, self::PAID, self::CLAIMED));
        $this->common_report_where($para);
        if (!empty($para['payment_date_from'])) {
            $this->db->where('pa.last_update >=', $para['payment_date_from']);
        }
        if (!empty($para['payment_date_to'])) {
            $this->db->where('pa.last_update <=', $para['payment_date_to']);
        }
    }
    private function get_sales_report_jf_result($query)
    {
        $results = array();
        foreach ($query as $row) {
            $row = $this->common_set_row($row);

            $results[$row['user_id']]['data'][$row['order_date']]['cnt'] = (
                empty($results[$row['user_id']]['data'][$row['order_date']]['cnt']) ? 1 : ++$results[$row['user_id']]['data'][$row['order_date']]['cnt']
            );

            $results[$row['user_id']]['data'][$row['order_date']]['policy_premium'] = (
                empty($results[$row['user_id']]['data'][$row['order_date']]['policy_premium']) ?
                $row['policy_premium'] : $results[$row['user_id']]['data'][$row['order_date']]['policy_premium'] + $row['policy_premium']);
            $results[$row['user_id']]['data'][$row['order_date']]['net_premium'] = (
                empty($results[$row['user_id']]['data]'][$row['order_date']]['net_premium']) ?
                $row['net_premium'] : $results[$row['user_id']]['data']['data'][$row['order_date']]['net_premium'] + $row['net_premium']);
            $results[$row['user_id']]['data'][$row['order_date']]['commission'] = (
                empty($results[$row['user_id']]['data'][$row['order_date']]['commission']) ?
                $row['commission_amount'] : $results[$row['user_id']]['data'][$row['order_date']]['commission'] + $row['commission_amount']);
            $results[$row['user_id']]['policy_premium'] = (empty($results[$row['user_id']]['policy_premium']) ?
                $row['policy_premium'] : $results[$row['user_id']]['policy_premium'] + $row['policy_premium']);
            $results[$row['user_id']]['agency'] = $row['agency'];
            $results[$row['user_id']]['agency_lname'] = $row['agency_lname'];
            $results[$row['user_id']]['agency_fname'] = $row['agency_fname'];

            $results[$row['user_id']]['data'][$row['order_date']]['records'][] = $row;
        }
        return $results;
    }

    /**
     * Get sales report to insurer data
     *
     * @param array $para Parameter array
     * @return array sales report to insurer data
     */
    public function get_sales_report_insurer($para)
    {
        $query = $this->get_sales_report_insurer_query($para);
        $results = $this->get_sales_report_insurer_result($query);
        return $results;
    }

    private function get_sales_report_insurer_query($para)
    {
        $this->sales_report_insurer_fields();
        $this->sales_report_insurer_from();
        $this->sales_report_insurer_where($para);
        return $this->db->get()->result_array();
    }

    private function sales_report_insurer_fields()
    {
        $this->db->select('
            pl.policy,
            c.firstname,
            c.lastname,
            c.gender,
            c.birthday,
            CONCAT(pl.street_number, " ", pl.street_name) AS address,
            pl.suite_number,
            pl.city,
            pl.province2 AS province,
            pl.postcode,
            pl.effective_date,
            pl.expiry_date,
            datediff(pl.expiry_date, pl.effective_date) AS total_days,
            pl.sum_insured,
            pl.deductible_amount,
            pl.premium AS policy_premium,
            (100 - pr.up_pay_rate) AS commission_rate_jf,
            pr.commission AS pr_commission,
            up.commission AS up_commission,
            "2.5" AS merchant_fee_per,
            "5" AS claims_handling_fee_per
        ');
    }

    private function sales_report_insurer_from()
    {
        $this->common_from();
        $this->db->join('user_product up', 'u.user_id = up.user_id and pr.product_short = up.product_short', 'left');
        $this->db->join('payment pa', 'pa.payment_id=
            (SELECT MIN(payment_id) FROM payment pa2 WHERE pl.payment_id = pa2.payment_id AND pa2.pay_type = "premium" AND ispaid = 1)', 'left');
    }

    private function sales_report_insurer_where($para)
    {
        $this->db->where_in('pl.status_id', array(self::SOLD, self::PAID, self::CLAIMED));
        $this->common_report_where($para);
        if (!empty($para['payment_date_from'])) {
            $this->db->where('pa.last_update >=', $para['payment_date_from']);
        }
        if (!empty($para['payment_date_to'])) {
            $this->db->where('pa.last_update <=', $para['payment_date_to']);
        }
    }

    private function get_sales_report_insurer_result($query)
    {
        $results = array();
        foreach ($query as $row) {
            $row['commission_amount'] =  sprintf("%01.2f",
                ($row['policy_premium'] * $row['commission_rate_jf'] / 100));
            $row['merchant_fee'] =  sprintf("%01.2f",
                ($row['policy_premium'] * $row['merchant_fee_per'] / 100));
            $row['claims_handling_fee'] =  sprintf("%01.2f",
                ($row['policy_premium'] * $row['claims_handling_fee_per'] / 100));

            $row['total_compensation'] = $row['commission_amount'] + $row['merchant_fee'] + $row['claims_handling_fee'];
            $row['net_premium'] = sprintf("%01.2f", ($row['policy_premium'] - $row['total_compensation']));
            $row['total_compensation_per'] = $row['commission_rate_jf'] + $row['merchant_fee_per'] + $row['claims_handling_fee_per'];
            if ($row['total_days'] <= 0) {
                $row['daily_rate'] = 0;
            } else {
                $row['daily_rate'] = sprintf("%01.2f", ($row['policy_premium'] / $row['total_days']));
            }
            $results[] = $row;
        }
        return $results;
    }

    /**
     * Get Receivable report data
     *
     * @param array $para Parameter array
     * @return array receivable report data
     */
    public function get_receivable($para)
    {
        $query = $this->get_receivable_query($para);
        $results = $this->get_receivable_result($query);
        $results['period']['from'] = $para['application_date_from'];
        $results['period']['to'] = $para['application_date_to'];
        return $results;
    }

    private function get_receivable_query($para)
    {
        $this->receivable_fields();
        $this->receivable_from();
        $this->receivable_where($para);
        return $this->db->get()->result_array();
    }

    private function receivable_fields()
    {
        $this->db->select('
            pl.apply_date AS order_date,
            pl.policy,
            u.username AS insurer,
            pr.full_name AS product,
            CONCAT(c.lastname, ", ", c.firstname) AS insured_name,
            pl.effective_date,
            pl.expiry_date,
            (datediff(pl.expiry_date, pl.effective_date) + 1) AS total_days,
            pl.dailyrate AS daily_rate,
            pl.premium AS policy_premium,
            pr.commission AS pr_commission,
            up.commission AS up_commission,
            u.user_id,
            CONCAT(u.firstname," ", u.lastname) AS agent_name,
            CONCAT(u.address, " ", u.city) AS address,
            u.province2 AS province,
            u.postcode,
            pl.status_id,
            pa.amount AS pa_amount,
            pa.pay_type,
            pa.currency,
            pa.last_update
        ');
    }

    private function receivable_from()
    {
        $this->common_from();
        $this->db->join('user_product up', 'u.user_id = up.user_id and pr.product_short = up.product_short', 'left');
        $this->db->join('payment pa', 'pl.plan_id = pa.plan_id AND pa.ispaid = 0');
    }

    private function receivable_where($para)
    {
        $this->common_report_where($para);
        if (!empty($para['policy_status']) && in_array($para['policy_status'], array(self::QUOTE, self::SOLD))) {
            $this->db->where('pl.status_id', $para['policy_status']);
        } else {
            $this->db->where('pl.status_id', self::SOLD);
        }
        $this->db->where('pa.amount >', 0);
        if (!empty($para['payment_update_date_from'])) {
            $this->db->where('pa.last_update >=', $para['payment_update_date_from']);
        }
        if (!empty($para['payment_update_date_to'])) {
            $this->db->where('pa.last_update <=', $para['payment_update_date_to']);
        }
        $this->db->order_by('pl.policy, pa.payment_id');
    }

    private function get_receivable_result($query)
    {
        $results = array();
        $policy = '';
        $premium_last_update = 0;
        foreach ($query as $row) {
            $row = $this->common_set_row($row);
            $results['data'][$row['user_id']]['agency']['agent_name'] = $row['agent_name'];
            $results['data'][$row['user_id']]['agency']['address'] = $row['address'];
            $results['data'][$row['user_id']]['agency']['province'] = $row['province'];
            $results['data'][$row['user_id']]['agency']['postal_code'] = $row['postcode'];
            if (!isset($results['data'][$row['user_id']]['agency']['outstanding'])) {
            	$results['data'][$row['user_id']]['agency']['outstanding'] = 0;
            	$results['data'][$row['user_id']]['agency']['commission'] = 0;
            	$results['data'][$row['user_id']]['agency']['payable_to_jf'] = 0;
            }
            
            if ($row['pay_type'] === 'premium') {
                $results['data'][$row['user_id']]['agency']['outstanding'] += $row['pa_amount'];
                $results['data'][$row['user_id']]['agency']['commission'] += $row['commission_amount'];
                $results['data'][$row['user_id']]['agency']['payable_to_jf'] += $row['net_premium'];
                if ($row['pa_amount'] > 0) {
                	$row['cal_comm_rate'] = sprintf('%2.1f', $row['commission_amount'] * 100.0 / $row['pa_amount']);
                } else {
                	$row['cal_comm_rate'] = 0;
                }
                $results['data'][$row['user_id']]['records'][] = $row;
            }
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
        $query = $this->get_claim_report_query($para);
        $results = $this->get_claim_report_result($query);
        $results['period']['from'] = $para['create_date_from'];
        $results['period']['to'] = $para['create_date_to'];
        return $results;
    }

    private function get_claim_report_query($para)
    {
        $this->claim_report_fields();
        $this->claim_report_from();
        $this->claim_report_where($para);
        $this->db->order_by('cl.plan_id');
        return $this->db->get()->result_array();
    }

    private function claim_report_fields()
    {
        $this->db->select('
            cl.policy_number,
            pl.deductible_amount,
            CONCAT(cl.firstname, " ", cl.lastname) AS customer_name,
            cl.birthday,
            CONCAT(pl.street_number, " ", pl.street_name) AS address,
            pl.suite_number,
            pl.city,
            pl.province2 AS province,
            pl.postcode,
            cl.gender,
            CONCAT(u.firstname, " ", u.lastname) AS agent_name,
            cl.claim_number,
            cl.claim_date,
            ci.service_date,
            ci.diagnosis,
            cc.name,
            ci.claimed,
            ci.paid,
            ci.received AS amount_received,
            ci.cheque_number,
            ci.cashed_date,
            ci.pay_to,
            ci.external_note,
            u.user_id
        ');
    }

    private function claim_report_from()
    {
        $this->db->from('claim cl');
        $this->db->join('citem ci', 'cl.claim_id = ci.claim_id');
        $this->db->join('plan pl', 'cl.plan_id = pl.plan_id');
        $this->db->join('customer c', 'cl.customer_id = c.customer_id');
        $this->db->join('product pr', 'cl.product_short = pr.product_short');
        $this->db->join('user u', 'cl.user_id = u.user_id');
        $this->db->join('coverage_code cc', 'ci.coverage_code_id = cc.coverage_code_id');
    }

    private function claim_report_where($para)
    {
        $this->common_report_where($para);
        if (!empty($para['create_date_from'])) {
            $this->db->where('cl.claim_date >=', $para['create_date_from']);
        }
        if (!empty($para['create_date_to'])) {
            $this->db->where('cl.claim_date <=', $para['create_date_to']);
        }
        $this->db->where_in('pl.status_id', array(self::SOLD, self::PAID, self::CLAIMED));
        if (!empty($para['region_id'])) {
            $this->db->where('pl.region_id', $para['region_id']);
        }
    }

    private function get_claim_report_result($query)
    {
        $results = array();
        foreach ($query as $row) {
            $results['data'][$row['user_id']]['agency'] = $row['agent_name'];
            if (!empty($row['suite_number'])) {
                $row['address'] .= ', ' . $row['suite_number'];
            }
            $results['data'][$row['user_id']]['records'][] = $row;
        }
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
        $query = $this->get_refund_report_query($para);
        return $query;
    }

    private function get_refund_report_query($para)
    {
        $this->refund_report_fields();
        $this->refund_report_from();
        $this->refund_report_where($para);
        $this->db->order_by('pm.plan_id', 'ASC');
        $this->db->order_by('pm.payment_id', 'ASC');
        return $this->db->get()->result_array();
    }

    private function refund_report_fields()
    {
        $this->db->select('
            pl.policy,
            pl.deductible_amount,
            CONCAT(c.firstname, " ", c.lastname) AS customer_name,
            c.birthday,
            CONCAT(pl.street_number, " ", pl.street_name) AS address,
            pl.suite_number,
            pl.city,
            pl.province2 AS province,
            pl.postcode,
            CONCAT(u.firstname, " ", u.lastname) AS agent_name,
            pm.amount,
            pm.admin_fee,
            pm.ispaid,
        	pm.added,
        	pm.pay_date,
        	pm.pay_to
        ');
    }

    private function refund_report_from()
    {
        $this->db->from('payment pm');
        $this->db->join('plan pl', 'pm.plan_id = pl.plan_id');
        $this->db->join('user u', 'pl.user_id = u.user_id');
        $this->db->join('customer c', 'pl.customer_id = c.customer_id');
    }

    private function refund_report_where($para)
    {
        if (!empty($para['product_short'])) {
            $this->db->where('pl.product_short', $para['product_short']);
        }
        if (!empty($para['region_id'])) {
            $this->db->where('pl.region_id', $para['region_id']);
        }
        $this->db->where('pm.pay_type =', 'refund');
    	$this->db->where('pm.ispaid =', (int)$para['ispaid']);
    	if (!empty($para['create_date_from'])) {
            $this->db->where('pm.added >=', $para['create_date_from']);
        }
        if (!empty($para['create_date_to'])) {
            $this->db->where('pm.added <=', $para['create_date_to']);
        }
        if (!empty($para['pay_date_from'])) {
            $this->db->where('pm.pay_date >=', $para['pay_date_from']);
        }
        if (!empty($para['pay_date_to'])) {
            $this->db->where('pm.pay_date <=', $para['pay_date_to']);
        }
    }

    /**
     * Get Renewal  Report data
     *
     * @param array $para Parameter array
     * @return array Renewal Report data
     */
    public function get_renewal_report($para)
    {
        $query = $this->get_renewal_report_query($para);
        $results = $this->get_renewal_report_result($query);
        $results['period']['from'] = $para['expiry_date_from'];
        $results['period']['to'] = $para['expiry_date_to'];
        return $results;
    }

    private function get_renewal_report_query($para)
    {
        $this->renewal_report_fields();
        $this->renewal_report_from();
        $this->renewal_report_where($para);
        return $this->db->get()->result_array();
    }

    private function renewal_report_fields()
    {
        $this->db->select('
            pl.policy,
            pl.effective_date,
            pl.expiry_date,
            CONCAT(c.firstname, " ", c.lastname) AS customer_name,
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

    private function renewal_report_where($para)
    {
    	$this->db->where('pl.status_id > ', self::QUOTE);
    	$this->common_report_where($para);
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
        $query = $this->get_commission_query($para);
        //echo "<pre>"; echo "[".$this->db->last_query() . "]\n"; print_r($query); die("XXX");
        $results = $this->get_commission_result($query);
        $results['period']['from'] = $para['application_date_from'];
        $results['period']['to'] = $para['application_date_to'];
        return $results;
    }

    private function get_commission_query($para)
    {
        $this->commission_fields();
        $this->commission_from();
        $this->commission_where($para);
        return $this->db->get()->result_array();
    }

    private function commission_fields()
    {
        $this->db->select('
            pl.apply_date,
            pl.policy,
            pr.full_name AS product,
            pr.up_insuer AS insurerCoName,
            CONCAT(c.lastname, ", ", c.firstname) AS insured_name,
            pl.effective_date,
            pl.expiry_date,
            datediff(pl.expiry_date, pl.effective_date) AS total_days,
            pr.commission AS pr_commission,
            up.commission AS up_commission,
            u.user_id,
            CONCAT(u.firstname," ", u.lastname) AS agent_name,
            CONCAT(u.address, " ", u.city) AS address,
            u.province2 AS province,
            u.postcode,
            u.note AS cheque_title,
            u.pay_type,
            pl.status_id,
            pa2.added AS premium_pay_date,
        	pa2.amount AS policy_premium,
        	pa.amount AS pa_commission,
            pa.ispaid
        ');
    }

    private function commission_from()
    {
        $this->common_from();
        $this->db->join('user_product up', 'u.user_id = up.user_id and pr.product_short = up.product_short', 'left');
        $this->db->join('payment pa', 'pl.plan_id = pa.plan_id AND pa.pay_type = "commission"');
        $this->db->join('payment pa2', 'pa.plan_id = pa2.plan_id AND pa2.pay_type = "premium" AND ABS(pa2.payment_id - pa.payment_id) <3');
//        $this->db->join('payment pa2', 'pa.plan_id = pa2.plan_id AND pa2.pay_type = "premium" AND ABS(TIMESTAMPDIFF(SECOND, pa2.added, pa.added)) <5');
    }

    private function commission_where($para)
    {
        $this->common_report_where($para);
        $this->db->where_in('pl.status_id', array(self::SOLD, self::PAID, self::CLAIMED));
        if (!empty($para['payment_update_date_from'])) {
            $this->db->where('pa.last_update >=', $para['payment_update_date_from']);
        }
        if (!empty($para['payment_update_date_to'])) {
            $this->db->where('pa.last_update <=', $para['payment_update_date_to']);
        }
    }

    private function get_commission_result($query)
    {
        $results = array();
        foreach ($query as $row) {
            $row = $this->common_set_row($row);
            $row['paid_status'] = $this->get_status($row['status_id']);
            if ($row['ispaid'] == 1) {
                $premium_pay_date = empty($row['premium_pay_date']) ? '' : $row['premium_pay_date'];
                $row['commission_status'] = 'Paid';
                $row['payment_status'] = 'Paid on ' . $premium_pay_date;
            } else {
                $row['commission_status'] = 'Unpaid';
                $row['payment_status'] = '';
            }
            $row['commission_amount'] = (empty($row['pa_commission'])) ? $row['commission_amount'] : $row['pa_commission'];

            $results['data'][$row['user_id']]['agency']['agent_name'] = $row['agent_name'];
            $results['data'][$row['user_id']]['agency']['address'] = $row['address'];
            $results['data'][$row['user_id']]['agency']['province'] = $row['province'];
            $results['data'][$row['user_id']]['agency']['postal_code'] = $row['postcode'];
            $results['data'][$row['user_id']]['agency']['payment_method'] = $row['pay_type'];
            $results['data'][$row['user_id']]['agency']['cheque_title'] = $row['cheque_title'];

            $results['data'][$row['user_id']]['records'][] = $row;
        }
        return $results;
    }

    /**
     * Get Agent Commission report data
     *
     * @param array $para Parameter array
     * @return array commission report data
     */
    public function get_agent_commission_report($para)
    {
        $query = $this->get_agent_commission_query($para);
        $results = $this->get_agent_commission_result($query);
        return $results;
    }

    private function get_agent_commission_query($para)
    {
        $this->agent_commission_fields();
        $this->agent_commission_from();
        $this->agent_commission_where($para);
        return $this->db->get()->result_array();
    }

    private function agent_commission_fields()
    {
        $this->db->select('
            CONCAT(u.firstname," ", u.lastname) AS agent_name,
            SUM(amount) AS total_balance,
            u.pay_type AS payment_method,
        	u.receive_type,
        	u.note,
        	u.user_id AS agent_id
        ');
    }

    private function agent_commission_from()
    {
        $this->db->from('user u');
        $this->db->join('payment pa', 'u.user_id = pa.user_id AND pa.pay_type = "commission"');
    }

    private function agent_commission_where($para)
    {
        $beuser = $this->session->beuser;
        $available_user_ids = array_keys($para['user_list']);
        
        if (!empty($para['payment_update_date_from'])) {
            $this->db->where('pa.last_update >=', $para['payment_date_from']);
        }
        if (!empty($para['payment_update_date_to'])) {
            $this->db->where('pa.last_update <=', $para['payment_date_to']);
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
            $this->db->where('u.region_id', $para['region_id']);
        }
        if (empty($para['paied'])) {
            $this->db->where('pa.ispaid', 0);
        } else {
        	$this->db->where('pa.ispaid', 1);
        }
        if (!empty($para['payment_method'])) {
            $this->db->like('u.pay_type', $para['payment_method']);
        }
        $this->db->group_by('agent_id');
        if (!empty($para['minvalue'])) {
        	$this->db->having('total_balance >', (int)$para['minvalue']);
        } else {
        	$this->db->having('total_balance >', 0);
        }
    }

    private function get_agent_commission_result($query)
    {
        $results = array();
        foreach ($query as $row) {
            $results['data']['records'][] = $row;
        }
        return $results;
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

    private function common_report_where($para)
    {
        $beuser = $this->session->beuser;
        $available_user_ids = array_keys($para['user_list']);
        $available_product_short = array();
        foreach ($para['product_list'] as $product) {
            $available_product_short[] = $product['product_short'];
        }

        if (!empty($available_product_short)) {
            if (empty($para['product_short']) || !in_array($para['product_short'], $available_product_short)) {
                $this->db->where_in('pr.product_short', $available_product_short);
            } else {
                $this->db->where('pr.product_short', $para['product_short']);
            }
        } else {
            $this->db->where('pr.product_short','');
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
}
