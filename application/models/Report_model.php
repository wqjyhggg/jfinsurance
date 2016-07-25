<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Report_model extends CI_Model {

    const QUOTE = 1;
    const SOLD = 2;
    const PAID = 3;
    const CLAIMED = 4;
    const CANCEL = 5;
    const REFUND = 6;

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
            CONCAT(u.lastname, ", ", u.firstname) AS insurer,
            pr.full_name AS product,
            CONCAT(c.lastname, ", ", c.firstname) AS insured_name,
            pl.effective_date,
            pl.expiry_date,
            datediff(pl.expiry_date, pl.effective_date) AS total_days,
            pl.dailyrate AS daily_rate,
            pl.premium AS policy_premium,
            pr.commission AS pr_commission,
            up.commission AS up_commission,
            u.user_id
        ');
    }

    private function sales_report_agent_from()
    {
        $this->common_from();
        $this->db->join('user_product up', 'u.user_id = up.user_id and pr.product_short = up.product_short', 'left');
    }

    private function sales_report_agent_where($para)
    {
        $this->db->where_in('pl.status_id', array(self::SOLD, self::PAID, self::CLAIMED));
        $this->common_report_where($para);
    }

    private function get_sales_report_agent_result($query)
    {
        $results = array();
        foreach ($query as $row) {
            $row = $this->common_set_row($row);

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
            pr.up_pay_rate AS commission_rate_jf,
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
    }

    private function sales_report_insurer_where($para)
    {
        $this->db->where_in('pl.status_id', array(self::SOLD, self::PAID, self::CLAIMED));
        $this->common_report_where($para);
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
            $row['daily_rate'] = sprintf("%01.2f", ($row['policy_premium'] / $row['total_days']));
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
        $results['period']['from'] = $this->input->post('application_date_from', true);
        $results['period']['to'] = $this->input->post('application_date_to', true);
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
            datediff(pl.expiry_date, pl.effective_date) AS total_days,
            pl.dailyrate AS daily_rate,
            pl.premium AS policy_premium,
            pr.commission AS pr_commission,
            up.commission AS up_commission,
            u.user_id,
            CONCAT(u.firstname," ", u.lastname) AS agent_name,
            CONCAT(u.address, " ", u.city) AS address,
            u.province2 AS province,
            u.postcode
        ');
    }

    private function receivable_from()
    {
        $this->common_from();
        $this->db->join('user_product up', 'u.user_id = up.user_id and pr.product_short = up.product_short', 'left');
        //group by pl.plan_id sum(premium) and/or sum(commission)
        //$this->db->join('outstanding o', 'pl.plan_id = o.plan_id', 'left')
    }

    private function receivable_where($para)
    {
        $this->common_report_where($para);
        if (!empty($para['policy_status']) && in_array($para['policy_status'], array(self::QUOTE, self::SOLD))) {
            $this->db->where('pl.status_id', $para['policy_status']);
        } else {
            $this->db->where('pl.status_id <=', self::SOLD);
        }
    }

    private function get_receivable_result($query)
    {
        $results = array();
        foreach ($query as $row) {
            $row = $this->common_set_row($row);

            $results['data'][$row['user_id']]['agency']['agent_name'] = $row['agent_name'];
            $results['data'][$row['user_id']]['agency']['address'] = $row['address'];
            $results['data'][$row['user_id']]['agency']['province'] = $row['province'];
            $results['data'][$row['user_id']]['agency']['postal_code'] = $row['postcode'];
            //todo
            //outstanding is outstanding, not premium, here is temporary place holder to display data only
            $results['data'][$row['user_id']]['agency']['outstanding'] = (
                empty($results['data'][$row['user_id']]['agency']['outstanding']) ?
                $row['policy_premium'] : $results['data'][$row['user_id']]['agency']['outstanding'] + $row['policy_premium']);
            $results['data'][$row['user_id']]['agency']['commission'] = (
                empty($results['data'][$row['user_id']]['agency']['commission']) ?
                $row['commission_amount'] : $results['data'][$row['user_id']]['agency']['commission'] + $row['commission_amount']);
            $results['data'][$row['user_id']]['agency']['payable_to_jf'] = (
                empty($results['data'][$row['user_id']]['agency']['payable_to_jf']) ?
                $row['net_premium'] : $results['data'][$row['user_id']]['agency']['payable_to_jf'] + $row['net_premium']);

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
        $query = $this->get_claim_report_query($para);
        $results = $this->get_claim_report_result($query);
        $results['period']['from'] = $this->input->post('create_date_from', true);
        $results['period']['to'] = $this->input->post('create_date_to', true);
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
            CONCAT(cl.firstname, " ", u.lastname) AS customer_name,
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
            cl.service_date,
            cl.diagnosis,
            cc.name,
            cl.claimed,
            cl.paid,
            "Amount Received" AS amount_received,
            cl.cheque_number,
            pa.cheque_cash_date,
            cl.pay_to,
            cl.memo,
            u.user_id,
            CONCAT(u.firstname, " ", u.lastname) AS agent
        ');
    }

    private function claim_report_from()
    {
        $this->db->from('claim cl');
        $this->db->join('plan pl', 'cl.plan_id = pl.plan_id');
        $this->db->join('customer c', 'cl.customer_id = c.customer_id');
        $this->db->join('product pr', 'cl.product_short = pr.product_short');
        $this->db->join('user u', 'cl.user_id = u.user_id');
        $this->db->join('coverage_code cc', 'cl.coverage_code_id = cc.coverage_code_id');
        $this->db->join('payment pa', 'pa.payment_id = cl.payment_id', 'left');
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
     * Get Renewal  Report data
     *
     * @param array $para Parameter array
     * @return array Renewal Report data
     */
    public function get_renewal_report($para)
    {
        $query = $this->get_renewal_report_query($para);
        $results = $this->get_renewal_report_result($query);
        $results['period']['from'] = $this->input->post('expiry_date_from', true);
        $results['period']['to'] = $this->input->post('expiry_date_to', true);
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
            u.user_id
        ');
    }

    private function renewal_report_from()
    {
        $this->common_from();
    }

    private function renewal_report_where($para)
    {
        $this->common_report_where($para);
    }

    private function get_renewal_report_result($query)
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
     * Get Commission report data
     *
     * @param array $para Parameter array
     * @return array commission report data
     */
    public function get_commission_report($para)
    {
        $query = $this->get_commission_query($para);
        $results = $this->get_commission_result($query);
        $results['period']['from'] = $this->input->post('application_date_from', true);
        $results['period']['to'] = $this->input->post('application_date_to', true);
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
            pl.apply_date AS order_date,
            pl.policy,
            pr.full_name AS product,
            u.username AS insurer,
            CONCAT(c.lastname, ", ", c.firstname) AS insured_name,
            pl.effective_date,
            pl.expiry_date,
            datediff(pl.expiry_date, pl.effective_date) AS total_days,
            pl.premium AS policy_premium,
            pr.commission AS pr_commission,
            up.commission AS up_commission,
            u.user_id,
            CONCAT(u.firstname," ", u.lastname) AS agent_name,
            CONCAT(u.address, " ", u.city) AS address,
            u.province2 AS province,
            u.postcode,
            "u.cheque_title" AS cheque_title,
            u.pay_type,
            pl.status_id
        ');
    }

    private function commission_from()
    {
        $this->common_from();
        $this->db->join('user_product up', 'u.user_id = up.user_id and pr.product_short = up.product_short', 'left');
        //$this->db->join('outstanding o', 'pl.plan_id = o.plan_id', 'left')
    }

    private function commission_where($para)
    {
        $this->common_report_where($para);
        /*todo we need get sold and uppaid records and paid but has outstanding (+ or -)
        if (!empty($para['policy_status']) && in_array($para['policy_status'], array(self::QUOTE, self::SOLD))) {
            $this->db->where('pl.status_id', $para['policy_status']);
        } else {
            $this->db->where('pl.status_id <=', self::SOLD);
        }
         */
    }

    private function get_commission_result($query)
    {
        $results = array();
        foreach ($query as $row) {
            $row = $this->common_set_row($row);
            $row['payment_status'] = $this->get_status($row['status_id']);
            //todo
            $row['commission_status'] = 'commission_status';

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

        if (empty($para['product_short']) || !in_array($para['product_short'], $available_product_short)) {
            $this->db->where_in('pr.product_short', $available_product_short);
        } else {
            $this->db->where('pr.product_short', $para['product_short']);
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
        if (!empty($para['application_date_from'])) {
            $this->db->where('pl.apply_date >=', $para['application_date_from']);
        }
        if (!empty($para['application_date_to'])) {
            $this->db->where('pl.apply_date <=', $para['application_date_to']);
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
        if (!empty($para['payment_update_date_from'])) {
            $this->db->where('pl.create_date >=', $para['payment_update_date_from']);
        }
        if (!empty($para['payment_update_date_to'])) {
            $this->db->where('pl.create_date <=', $para['payment_update_date_to']);
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
