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
    	$sql .= "		pr.full_name,";
    	$sql .= "		pr.up_insuer,";
    	$sql .= "		CONCAT(cu.firstname, ' ', cu.lastname) as insured";
    	$sql .= " FROM payment pa";
    	$sql .= " JOIN plan pl ON (pa.plan_id=pl.plan_id)";
    	$sql .= " JOIN product pr ON (pl.product_short=pr.product_short)";
    	$sql .= " JOIN customer cu ON (pl.customer_id=cu.customer_id)";
    	$sql .= " LEFT JOIN payment pa2 ON (pa.plan_id=pa2.plan_id AND pa.payment_id=pa2.premium_payment_id AND pa2.pay_type IN ('commission','cancel_commission','refund_commission'))";
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
    	$sql .= " ORDER BY pl.user_id ASC, pl.apply_date";
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
        				'commission' => 0);
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
    	$sql  = "SELECT pa.amount,";
    	$sql .= "		pa2.amount as commission,";
    	$sql .= "		pa.payment_id,";
    	$sql .= "		pa.invoice_num,";
    	$sql .= "		pa.ispaid,";
    	$sql .= "		pa.pay_date,";
    	$sql .= "		pa.added,";
    	$sql .= "		pa.pay_type,";
    	$sql .= "		pa.last_update,";
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
    	$sql .= "		pr.full_name,";
    	$sql .= "		pr.up_insuer,";
    	$sql .= "		CONCAT(cu.firstname, ' ', cu.lastname) as insured";
    	$sql .= " FROM payment pa";
    	$sql .= " JOIN plan pl ON (pa.plan_id=pl.plan_id)";
    	$sql .= " JOIN product pr ON (pl.product_short=pr.product_short)";
    	$sql .= " JOIN customer cu ON (pl.customer_id=cu.customer_id)";
    	$sql .= " LEFT JOIN payment pa2 ON (pa.plan_id=pa2.plan_id AND pa.payment_id=pa2.premium_payment_id AND pa2.pay_type IN ('commission','cancel_commission','refund_commission'))";
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
    	$sql .= " ORDER BY pl.user_id ASC, pl.apply_date";
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
        //$results['amount'] = $amount;
        //$results['commission'] = $commission;
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
    	$sql  = "SELECT"; 
    	$sql .= "	pl.policy,"; 
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
    	$sql .= "	pl.deductible_amount,";
    	$sql .= "	pa.amount AS policy_premium,";
    	$sql .= "	(100 - pr.up_pay_rate) AS commission_rate_jf,";
    	$sql .= "	pa2.amount AS pr_commission,";
    	$sql .= "	pa3.amount AS up_commission,";
    	$sql .= "	'2.5' AS merchant_fee_per, ";
    	$sql .= "	'5' AS claims_handling_fee_per"; 
    	$sql .= " FROM payment pa"; 
    	$sql .= " JOIN plan pl ON pa.plan_id = pl.plan_id"; 
    	$sql .= " JOIN customer c ON pl.customer_id = c.customer_id"; 
    	$sql .= " JOIN product pr ON pl.product_short = pr.product_short"; 
    	$sql .= " JOIN user u ON pl.user_id = u.user_id ";
    	$sql .= " LEFT JOIN user_product up ON u.user_id = up.user_id and pr.product_short = up.product_short"; 
    	$sql .= " LEFT JOIN payment pa2 ON (pa.plan_id=pa2.plan_id AND pa.payment_id=pa2.premium_payment_id AND pa2.pay_type IN ('commission','cancel_commission','refund_commission'))";
    	$sql .= " LEFT JOIN payment pa3 ON (pa.plan_id=pa3.plan_id AND pa.payment_id=pa3.premium_payment_id AND pa3.pay_type IN ('up_commission','cancel_up_commission','refund_up_commission'))";
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

    	$query = $this->db->query($sql)->result_array();
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
        	pa.added AS pa_added,
            pa.pay_type,
            pa.currency,
            pa.last_update,
            pa2.amount AS commission_amount
        ');
    }

    private function receivable_from()
    {
        $this->common_from();
        $this->db->join('user_product up', 'u.user_id = up.user_id and pr.product_short = up.product_short', 'left');
        $this->db->join("payment pa", "pl.plan_id = pa.plan_id AND pa.pay_type = 'premium' AND pa.ispaid = 0");
        $this->db->join("payment pa2", "pa.plan_id = pa2.plan_id AND pa2.pay_type = 'commission' AND pa.payment_id = pa2.premium_payment_id", 'left');
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
            $this->db->where('pa.last_update >=', $para['payment_update_date_from'] . " 00:00:00");
        }
        if (!empty($para['payment_update_date_to'])) {
            $this->db->where('pa.last_update <=', $para['payment_update_date_to'] . " 23:59:59");
        }
        $this->db->order_by('pl.policy, pa.payment_id');
    }

    private function get_receivable_result($query)
    {
        $results = array();
        $policy = '';
        $premium_last_update = 0;
        foreach ($query as $row) {
            $row['commission_rate'] = $row['pr_commission'];
            $row['net_premium'] = sprintf("%01.2f", ($row['pa_amount'] - $row['commission_amount']));
            
            $results['data'][$row['user_id']]['agency']['agent_name'] = $row['agent_name'];
            $results['data'][$row['user_id']]['agency']['address'] = $row['address'];
            $results['data'][$row['user_id']]['agency']['province'] = $row['province'];
            $results['data'][$row['user_id']]['agency']['postal_code'] = $row['postcode'];
            if (!isset($results['data'][$row['user_id']]['agency']['outstanding'])) {
            	$results['data'][$row['user_id']]['agency']['outstanding'] = 0;
            	$results['data'][$row['user_id']]['agency']['commission'] = 0;
            	$results['data'][$row['user_id']]['agency']['payable_to_jf'] = 0;
            }
            
			$results['data'][$row['user_id']]['agency']['outstanding'] += $row['pa_amount'];
            $results['data'][$row['user_id']]['agency']['commission'] += $row['commission_amount'];
            $results['data'][$row['user_id']]['agency']['payable_to_jf'] += $row['pa_amount'] - $row['commission_amount'];
            if (abs($row['pa_amount']) > 0.005) {
              	$row['cal_comm_rate'] = sprintf('%2.1f', $row['commission_amount'] * 100.0 / $row['pa_amount']);
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
		$sql = "SELECT
					pl.policy, pl.refund_date, CONCAT(pl.street_number, ' ', pl.street_name) AS address, pl.suite_number, pl.city, pl.province2 AS province, pl.postcode,
					(SELECT sum(amount) FROM payment pm1 WHERE pm1.plan_id=pl.plan_id AND pay_type='premium') as premium,
					(SELECT sum(amount) FROM payment pm1 WHERE pm1.plan_id=pl.plan_id AND pay_type='commission') as commission,
					CONCAT(c.firstname, ' ', c.lastname) AS customer_name, c.birthday,
					CONCAT(u.firstname, ' ', u.lastname) AS agent_name,
					pm.amount, pm.admin_fee, (pm.amount + pm.admin_fee) AS net_amount, pm.ispaid, pm.added, pm.pay_date, pm.pay_to
				FROM payment pm
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
		$sql .= " ORDER BY pm.plan_id ASC, pm.payment_id ASC";
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
    	$sql  = "SELECT"; 
    	$sql .= "	pa.payment_id,"; 
    	$sql .= "	pa.plan_id,"; 
    	$sql .= "	pa.premium_payment_id,"; 
    	$sql .= "	pa.last_update,"; 
    	$sql .= "	pa.added,";		//  Payment Date
    	$sql .= "	pl.user_id,"; 
    	$sql .= "	pl.policy,"; 
    	$sql .= "	st.name AS status,"; 
    	$sql .= "	pr.up_insuer,";
    	$sql .= "	CONCAT(c.firstname, ' ', c.lastname) AS customer_name,"; 
    	$sql .= "	pl.effective_date,";
    	$sql .= "	pl.expiry_date,";
    	$sql .= "	pl.totaldays AS total_days,";
    	$sql .= "	pa2.amount AS premium,";
    	$sql .= "	pa2.ispaid AS premiumispaid,";
    	$sql .= "	pa.rate,";
    	$sql .= "	pa.amount,";
    	$sql .= "	pa.ispaid ";
    	$sql .= " FROM payment pa"; 
    	$sql .= " JOIN plan pl ON pa.plan_id = pl.plan_id"; 
    	$sql .= " JOIN customer c ON pl.customer_id = c.customer_id"; 
    	$sql .= " JOIN product pr ON pl.product_short = pr.product_short"; 
    	$sql .= " JOIN status st ON pl.status_id = st.status_id ";
    	$sql .= " LEFT JOIN payment pa2 ON (pa.premium_payment_id=pa2.payment_id)";
    	$sql .= " WHERE pa.pay_type IN ('commission','cancel_commission','refund_commission') AND ABS(pa.amount)>=0.01";
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
    	$sql .= " ORDER BY pl.policy ASC, user_id ASC, added ASC";

    	$query = $this->db->query($sql)->result_array();
    	$results = array();
    	foreach ($query as $row) {
    		if (empty($results[$row['user_id']])) {
    			$agent = $this->db->query("SELECT * FROM user WHERE user_id='" . (int)$row['user_id'] . "'")->row_array();
    			$results[$row['user_id']] = array('agent' => $agent, 'data' => array());
    		}
    		$results[$row['user_id']]['data'][] = $row;
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
            $this->db->where('pa.last_update >=', $para['payment_date_from'] . " 00:00:00");
        }
        if (!empty($para['payment_update_date_to'])) {
            $this->db->where('pa.last_update <=', $para['payment_date_to'] . " 23:59:59");
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
