<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Report_model extends CI_Model {
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
        $this->db->from('plan pl');
        $this->db->join('customer c', 'pl.customer_id = c.customer_id');
        $this->db->join('product pr', 'pl.product_short = pr.product_short');
        $this->db->join('user u', 'pl.user_id = u.user_id');
        $this->db->join('user_product up', 'u.user_id = up.user_id and pr.product_short = up.product_short', 'left');
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
        if (!empty($para['create_date_from'])) {
            $this->db->where('pl.create_date >=', $para['create_date_from']);
        }
        if (!empty($para['create_date_to'])) {
            $this->db->where('pl.create_date <=', $para['create_date_to']);
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
        return $this->db->get()->result_array();
    }

    private function get_sales_report_agent_result($query)
    {
        $results = array();
        foreach ($query as $row) {
            $row['commission_rate'] = empty($row['up_commission']) ? $row['pr_commission'] . '%' : $row['up_commission'] . '%'; 
            $row['commission_amount'] =  sprintf("%01.2f", ($row['policy_premium'] * $row['commission_rate'] / 100));
            $row['net_premium'] = sprintf("%01.2f", ($row['policy_premium'] - $row['commission_amount']));
            $row['daily_rate'] = sprintf("%01.2f", ($row['policy_premium'] / $row['total_days']));
            $results[] = $row; 
        }
        return $results;
    }
}
