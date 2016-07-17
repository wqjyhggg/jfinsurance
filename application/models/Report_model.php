<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Report_model extends CI_Model {

    const QUTOE = 1;
    const SOLD = 2;
    const PAID = 3;
    const CLAIMED = 4;
    const CANCEL = 5;
    const REFUND = 6;

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
            u.username AS insurer,
            pr.full_name AS product,
            concat(c.lastname, ", ", c.firstname) AS insured_name,
            pl.effective_date,
            pl.expiry_date,
            datediff(pl.expiry_date, pl.effective_date) AS total_days,
            pl.dailyrate AS daily_rate,
            pl.premium AS policy_premium,
            pr.commission AS pr_commission,
            up.commission AS up_commission
        ');
    }

    private function sales_report_agent_from()
    {
        $this->db->from('plan pl');
        $this->db->join('customer c', 'pl.customer_id = c.customer_id');
        $this->db->join('product pr', 'pl.product_short = pr.product_short');
        $this->db->join('user u', 'pl.user_id = u.user_id');
        $this->db->join('user_product up', 'u.user_id = up.user_id and pr.product_short = up.product_short', 'left');
// todo need to clear payment data struct, and usage        
//        $this->db->join('payment p1', 'pl.payment_id = p1.payment_id AND pl.user_id=p1.user_id AND pl.plan_id = p1.plan_id');
//        $this->db->join('payment p2', 'pl.commission_payment_id = p2.payment_id AND pl.user_id=p2.user_id AND pl.plan_id = p2.plan_id AND p2.ispaid = 1');
    }

    private function sales_report_agent_where($para)
    {
        $this->common_report_where($para);
    }

    private function get_sales_report_agent_result($query)
    {
        $results = array();
        foreach ($query as $row) {
            //$row['commission_rate'] = empty($row['up_commission']) ? $row['pr_commission'] . '%' : $row['up_commission'] . '%';
            $row['commission_rate'] = $row['up_commission'];
            $row['commission_amount'] =  sprintf("%01.2f", ($row['policy_premium'] * $row['commission_rate'] / 100));
            $row['net_premium'] = sprintf("%01.2f", ($row['policy_premium'] - $row['commission_amount']));
            $row['daily_rate'] = sprintf("%01.2f", ($row['policy_premium'] / $row['total_days']));
            $results[] = $row;
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
            "invoice num" AS invoice_num,
            u.business AS insurerCoName,
            pr.full_name AS product,
            concat(c.lastname, ", ", c.firstname) AS insured,
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
        $this->db->from('plan pl');
        $this->db->join('customer c', 'pl.customer_id = c.customer_id');
        $this->db->join('product pr', 'pl.product_short = pr.product_short');
        $this->db->join('user u', 'pl.user_id = u.user_id');
        $this->db->join('user_product up', 'u.user_id = up.user_id and pr.product_short = up.product_short', 'left');
//        $this->db->join('payment p1', 'pl.payment_id = p1.payment_id AND pl.user_id=p1.user_id AND pl.plan_id = p1.plan_id');
//        $this->db->join('payment p2', 'pl.commission_payment_id = p2.payment_id AND pl.user_id=p2.user_id AND pl.plan_id = p2.plan_id AND p2.ispaid = 1');
    }

    private function sales_report_jf_where($para)
    {
        $this->common_report_where($para);
    }

    private function get_sales_report_jf_result($query)
    {
        $results = array();
        foreach ($query as $row) {
            //$row['commission_rate'] = empty($row['up_commission']) ? $row['pr_commission'] . '%' : $row['up_commission'] . '%';
            $row['commission_rate'] = $row['up_commission'];
            $row['commission_amount'] =  sprintf("%01.2f", ($row['policy_premium'] * $row['commission_rate'] / 100));
            $row['net_premium'] = sprintf("%01.2f", ($row['policy_premium'] - $row['commission_amount']));
            $row['daily_rate'] = sprintf("%01.2f", ($row['net_premium'] / $row['total_days']));

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
            concat(pl.street_number, " ", pl.street_name) AS address,  
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
            pr.commission AS pr_commission,
            up.commission AS up_commission,
            "2.5" AS merchant_fee_per,
            "5" AS claims_handling_fee_per
        ');
    }

    private function sales_report_insurer_from()
    {
        $this->db->from('plan pl');
        $this->db->join('customer c', 'pl.customer_id = c.customer_id');
        $this->db->join('product pr', 'pl.product_short = pr.product_short');
        $this->db->join('user u', 'pl.user_id = u.user_id');
        $this->db->join('user_product up', 'u.user_id = up.user_id and pr.product_short = up.product_short', 'left');
//        $this->db->join('payment p1', 'pl.payment_id = p1.payment_id AND pl.user_id=p1.user_id AND pl.plan_id = p1.plan_id');
//        $this->db->join('payment p2', 'pl.commission_payment_id = p2.payment_id AND pl.user_id=p2.user_id AND pl.plan_id = p2.plan_id AND p2.ispaid = 1');
    }

    private function sales_report_insurer_where($para)
    {
        $this->common_report_where($para);
    }

    private function get_sales_report_insurer_result($query)
    {
        $results = array();
        foreach ($query as $row) {
            //$row['commission_rate'] = empty($row['up_commission']) ? $row['pr_commission'] . '%' : $row['up_commission'] . '%';
            $row['commission_rate'] = $row['pr_commission'];
            $row['commission_amount'] =  sprintf("%01.2f",
                ($row['policy_premium'] * $row['commission_rate'] / 100));
            $row['merchant_fee'] =  sprintf("%01.2f",
                ($row['policy_premium'] * $row['merchant_fee_per'] / 100));
            $row['claims_handling_fee'] =  sprintf("%01.2f",
                ($row['policy_premium'] * $row['claims_handling_fee_per'] / 100));
            $row['total_compensation'] = $row['commission_amount'] + $row['commission_amount'] + $row['merchant_fee'];
            $row['net_premium'] = sprintf("%01.2f", ($row['policy_premium'] - $row['total_compensation']));
            $row['total_compensation_per'] = $row['commission_rate'] + $row['merchant_fee_per'] + $row['claims_handling_fee_per'];
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
            concat(c.lastname, ", ", c.firstname) AS insured_name,
            pl.effective_date,
            pl.expiry_date,
            datediff(pl.expiry_date, pl.effective_date) AS total_days,
            pl.dailyrate AS daily_rate,
            pl.premium AS policy_premium,
            pr.commission AS pr_commission,
            up.commission AS up_commission,
            u.user_id,
            concat(u.firstname," ", u.lastname) AS agent_name,
            concat(u.address, " ", u.city) AS address,
            u.province2 AS province,
            u.postcode
        ');
    }

    private function receivable_from()
    {
        $this->db->from('plan pl');
        $this->db->join('customer c', 'pl.customer_id = c.customer_id');
        $this->db->join('product pr', 'pl.product_short = pr.product_short');
        $this->db->join('user u', 'pl.user_id = u.user_id');
        $this->db->join('user_product up', 'u.user_id = up.user_id and pr.product_short = up.product_short', 'left');
//        $this->db->join('payment p1', 'pl.payment_id = p1.payment_id AND pl.user_id=p1.user_id AND pl.plan_id = p1.plan_id');
//        $this->db->join('payment p2', 'pl.commission_payment_id = p2.payment_id AND pl.user_id=p2.user_id AND pl.plan_id = p2.plan_id AND p2.ispaid = 1');
    }

    private function receivable_where($para)
    {
        $this->common_report_where($para);
        if (!empty($para['policy_status'])) {
            if ($para['policy_status'] <= 0 || $para['policy_status'] > self::SOLD) {
                $this->db->where('pl.status <=', self::SOLD);
            } else {
                $this->db->where('pl.status', $para['policy_status']);
            }
        }
    }

    private function get_receivable_result($query)
    {
        $results = array();
        foreach ($query as $row) {
            //$row['commission_rate'] = empty($row['up_commission']) ? $row['pr_commission'] . '%' : $row['up_commission'] . '%';
            $row['commission_rate'] = $row['up_commission'];
            $row['commission_amount'] =  sprintf("%01.2f", ($row['policy_premium'] * $row['commission_rate'] / 100));
            $row['net_premium'] = sprintf("%01.2f", ($row['policy_premium'] - $row['commission_amount']));
            $row['daily_rate'] = sprintf("%01.2f", ($row['policy_premium'] / $row['total_days']));

            $results['data'][$row['user_id']]['agency']['agent_name'] = $row['agent_name'];
            $results['data'][$row['user_id']]['agency']['address'] = $row['address'];
            $results['data'][$row['user_id']]['agency']['province'] = $row['province'];
            $results['data'][$row['user_id']]['agency']['postal_code'] = $row['postcode'];
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
        return $this->db->get()->result_array();
    }

    private function claim_report_fields()
    {
        $this->db->select('
            cl.policy_number,
            pl.deductible_amount,
            concat(cl.firstname, " ", u.lastname) AS customer_name,
            cl.birthday,
            concat(pl.street_number, " ", pl.street_name) AS address,  
            pl.suite_number,
            pl.city,
            pl.province2 AS province,
            pl.postcode,
            cl.gender,
            concat(u.firstname, " ", u.lastname) AS agent_name,
            cl.claim_number,
            cl.claim_date,
            cl.service_date,
            cl.diagnosis,
            cc.name,
            cl.claimed,
            cl.paid,
            "Amount Received" AS amount_received,
            cl.cheque_number,
            "Cheque Cash Day" as cheque_cash_day,
            cl.pay_to,
            cl.memo,
            u.user_id,
            concat(u.firstname, " ", u.lastname) AS agent
        ');
    }

    private function claim_report_from()
    {
        $this->db->from('claim cl');
        $this->db->join('customer c', 'cl.customer_id = c.customer_id');
        $this->db->join('user u', 'cl.user_id = u.user_id');
        $this->db->join('product pr', 'cl.product_short = pr.product_short');
        $this->db->join('plan pl', 'cl.plan_id = pl.plan_id');
        $this->db->join('coverage_code cc', 'cl.coverage_code_id = cc.coverage_code_id', 'left');
//        $this->db->join('payment p1', 'pl.payment_id = p1.payment_id AND pl.user_id=p1.user_id AND pl.plan_id = p1.plan_id');
//        $this->db->join('payment p2', 'pl.commission_payment_id = p2.payment_id AND pl.user_id=p2.user_id AND pl.plan_id = p2.plan_id AND p2.ispaid = 1');
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
            concat(c.firstname, " ", c.lastname) AS customer_name,
            pl.province2 AS province,
            pl.phone1 AS phone,
            pl.contact_email AS email,
            concat(u.firstname, " ", u.lastname) AS agent_name,
            u.user_id
        ');
    }

    private function renewal_report_from()
    {
        $this->db->from('plan pl');
        $this->db->join('product pr', 'pl.product_short = pr.product_short');
        $this->db->join('customer c', 'pl.customer_id = c.customer_id');
        $this->db->join('user u', 'pl.user_id = u.user_id');
//        $this->db->join('payment p1', 'pl.payment_id = p1.payment_id AND pl.user_id=p1.user_id AND pl.plan_id = p1.plan_id');
//        $this->db->join('payment p2', 'pl.commission_payment_id = p2.payment_id AND pl.user_id=p2.user_id AND pl.plan_id = p2.plan_id AND p2.ispaid = 1');
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
            concat(c.lastname, ", ", c.firstname) AS insured_name,
            pl.effective_date,
            pl.expiry_date,
            datediff(pl.expiry_date, pl.effective_date) AS total_days,
            pl.premium AS policy_premium,
            pr.commission AS pr_commission,
            up.commission AS up_commission,
            u.user_id,
            concat(u.firstname," ", u.lastname) AS agent_name,
            concat(u.address, " ", u.city) AS address,
            u.province2 AS province,
            u.postcode,
            "u.cheque_title" AS cheque_title,
            u.pay_type,
            pl.status_id
        ');
    }

    private function commission_from()
    {
        $this->db->from('plan pl');
        $this->db->join('customer c', 'pl.customer_id = c.customer_id');
        $this->db->join('product pr', 'pl.product_short = pr.product_short');
        $this->db->join('user u', 'pl.user_id = u.user_id');
        $this->db->join('user_product up', 'u.user_id = up.user_id and pr.product_short = up.product_short', 'left');
//        $this->db->join('payment p1', 'pl.payment_id = p1.payment_id AND pl.user_id=p1.user_id AND pl.plan_id = p1.plan_id');
//        $this->db->join('payment p2', 'pl.commission_payment_id = p2.payment_id AND pl.user_id=p2.user_id AND pl.plan_id = p2.plan_id AND p2.ispaid = 1');
    }

    private function commission_where($para)
    {
        $this->common_report_where($para);
        if (!empty($para['policy_status'])) {
            if ($para['policy_status'] <= 0 || $para['policy_status'] > self::SOLD) {
                $this->db->where('pl.status <=', self::SOLD);
            } else {
                $this->db->where('pl.status', $para['policy_status']);
            }
        }
    }

    private function get_commission_result($query)
    {
        $results = array();
        foreach ($query as $row) {
            //$row['commission_rate'] = empty($row['up_commission']) ? $row['pr_commission'] . '%' : $row['up_commission'] . '%';
            $row['commission_rate'] = $row['up_commission'];
            $row['commission_amount'] =  sprintf("%01.2f", ($row['policy_premium'] * $row['commission_rate'] / 100));
            $row['net_premium'] = sprintf("%01.2f", ($row['policy_premium'] - $row['commission_amount']));
            $row['daily_rate'] = sprintf("%01.2f", ($row['policy_premium'] / $row['total_days']));
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
        case self::QUTOE:
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
    private function common_report_where($para)
    {
        if (!empty($para['product_short'])) {
            $this->db->where('pr.product_short', $para['product_short']);
        }
        if (!empty($para['agent_id'])) {
            $this->db->where('u.user_id', $para['agent_id']);
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
        if (!empty($para['payment_update_date_from'])) {
            $this->db->where('pl.create_date >=', $para['payment_update_date_from']);
        }
        if (!empty($para['payment_update_date_to'])) {
            $this->db->where('pl.create_date <=', $para['payment_update_date_to']);
        }
    }
}
