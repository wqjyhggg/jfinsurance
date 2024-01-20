<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

class Cron1 extends MY_Controller {
	public $error;
	
	private function valid() {
		$this->error = '';

		if ((php_sapi_name() === 'cli')) {
			$this->load->model('setting_model');
			$this->setting_model->set_default_user();
			return TRUE;
		}
		show_error("ERROR", 404);
	}
	
  public function check_customer($plan, $st, $firstname, $lastname, $dob) {
    $vrecords = $this->plan_model->verify_customer($firstname, $lastname, $dob);
    $claim_amount = 0;
    $case_amount = 0;
    if ($vrecords['status'] == 'OK') {
      foreach ($vrecords['cases'] as $case) {
        $case_amount += (float)$case['amount'];
      }
      foreach ($vrecords['claims'] as $claim) {
        $claim_amount += (float)$claim['amount'];
      }
    }
    if (empty($claim_amount) && empty($case_amount)) {
      // continue check next customer
      ;
    } else if (($claim_amount <= 2000) && ($case_amount <= 2000)) {
      echo '"'.$plan['policy'].'","'.$st.'","<2000","'.$lastname.'","'.$firstname.'","'.$dob.'"'."\n";
    } else /* if (($claim_amount > 2000) || ($case_amount > 2000)) */ {
      echo '"'.$plan['policy'].'","'.$st.'",">2000","'.$lastname.'","'.$firstname.'","'.$dob.'"'."\n";
    }
  }

	public function index() {
		if ($this->valid()) {
      $this->load->model('plan_model');

      $status = array(1=>"Quote", 2=>"Sold",3=>"Paid",4=>"Claimed",7=>"Changed");
      $dt = date("Y-m-d");
      foreach ($status as $st=>$sstr) {
        $plans = $this->plan_model->plan_search(array('status_id'=>$st,'expiry_date'=>$dt));
        foreach ($plans as $plan) {
          $this->check_customer($plan, $sstr, $plan['firstname'], $plan['lastname'], $plan['birthday']);
          if ($plan['isfamilyplan']) {
            $customers = $this->customer_model->get_plan_customers_by_id($plan['plan_id']);
            foreach ($customers as $cust) {
              if ($plan['customer_id'] == $cust['customer_id']) {
                continue;
              }
              $this->check_customer($plan, $sstr, $cust['firstname'], $cust['lastname'], $cust['birthday']);
            }
          }
        }
      }
			die("OK\n");
		} else {
			die($this->error."\n");
		}
	}

	public function annual() {
		if (!$this->valid()) {
      die("Valid Error");
    }
    $sql = "SELECT user_id, username, firstname, lastname, email FROM user WHERE status='1' AND user_group_id='105'";
    if ($rt = $this->db->query($sql)->result_array()) {
      $file = "/tmp/annual.csv";
      $fp = fopen($file, 'w');
      if (!$fp) {
        die("Can't open write file: ".$file." at line ".__LINE__);
      }
      foreach ($rt as $rc) {
        // $sql  = "SELECT pa.payment_id, pa.plan_id, pa.premium_payment_id, pa.last_update, pa.added, pl.user_id, pl.policy, (pa.amount - pa.admin_fee) AS premium FROM payment pa";
        // $sql .= " JOIN plan pl ON pa.plan_id = pl.plan_id";
        // $sql .= " WHERE pa.pay_type IN ('premium','cancel_premium','refund_premium') AND ABS(pa.amount)>=0.01 ";
        // $sql .= " AND pa.added >= '2023-01-01 00:00:00'";
        // $sql .= " AND pa.added <= '2023-12-31 23:59:59'";
        // $sql .= " AND pl.user_id='" . (int)$rc['user_id'] . "'";
        // if ($rt1 = $this->db->query($sql)->result_array()) {
        //   $premium = 0;
        //   foreach ($rt1 as $rc1) {
        //     $premium += $rc1["premium"];
        //   }
        //   fputcsv($fp, [$rc["user_id"], $rc["username"], $rc["firstname"], $rc["lastname"], $rc["email"], $premium]);
        // }
        $sql  = "SELECT";
        $sql .= "   sum(pa2.amount - pa2.admin_fee) AS premium,";
        $sql .= "   sum(pa.amount) as commission";
        $sql .= " FROM __payment__ pa";
        $sql .= " JOIN plan pl ON pa.plan_id = pl.plan_id";
        $sql .= " JOIN customer c ON pl.customer_id = c.customer_id";
        $sql .= " JOIN product pr ON pl.product_short = pr.product_short";
        $sql .= " JOIN status st ON pl.status_id = st.status_id ";
        $sql .= " LEFT JOIN __payment__ pa2 ON (pa.premium_payment_id=pa2.payment_id)";
        $sql .= " WHERE pl.user_id='" . intval($rc['user_id']) . "'";
        $sql .= " AND pa.added >= '2023-01-01 00:00:00'";
        $sql .= " AND pa.added < '2024-01-01 00:00:00'";
        $sql .= " AND pa.pay_type IN ('commission','cancel_commission','refund_commission') AND ABS(pa.amount)>=0.01";
        if ($rc1 = $this->db->query($sql)->row_array()) {
          fputcsv($fp, [$rc["user_id"], $rc["username"], $rc["firstname"], $rc["lastname"], $rc["email"], $rc1["premium"]]);
        }
      }
      fclose($fp);
      die("Do to write ".$file."\n");
    } else {
      die("No agent!!!\n");
    }
  }
}
