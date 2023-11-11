<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Report extends CI_Controller
{
  public $error;
  public $data;

  public function index()
  {
    $this->load->model("app_model");
    $this->load->model("user_model");
    $user = $this->app_model->check_token($this->input->post("token"));

    if (empty($user)) {
      if (empty($this->error)) {
        $this->error = "Timeout";
      }
      return $this->app_model->return_error($this->error);
    }

    $this->load->model('product_model');
    $this->load->model('region_model');
    // $this->load->model('paytype_model');

    $regions_list = $this->region_model->get_regions();
    $product_list = $this->product_model->get_available_product_list($user);
    $user_list = $this->user_model->get_available_user_list($user);
    // $paytype_list = $this->paytype_model->paytype_list();

    $this->app_model->return_ok([
      "product_list" => $product_list, // For or_premium, sales_agent, jf
      "user_list" => $user_list, // For sales_agent, jf
      "regions_list" => $regions_list, // For sales_agent, jf
    ]);
  }

  /* Premium2.php */
  function or_premium()
  {
    $this->error = "";
    $this->load->model("app_model");
    $this->load->model("user_model");
    $user = $this->app_model->check_token($this->input->post("token"));

    if (empty($user)) {
      if (empty($this->error)) {
        $this->error = "Timeout";
      }
      return $this->app_model->return_error($this->error);
    }

    $beuser = $user;
    $data = array();
    $data['beuser'] = $beuser;

    $this->load->model('report_model');

    $data['title_txt'] = 'OR Premium Report';

    $data['product_short'] = empty($this->input->post('product_short')) ? array() : $this->input->post('product_short');
    $data['payment_added_from'] = empty($this->input->post('payment_added_from')) ? date("Y-m-d") : $this->input->post('payment_added_from');
    $data['payment_added_to'] = empty($this->input->post('payment_added_to')) ? date("Y-m-d") : $this->input->post('payment_added_to');
    $data['earned_to'] = empty($this->input->post('earned_to')) ? date("Y-m-d") : $this->input->post('earned_to');

    $report_data = $this->report_model->get_premium_report2($data);
    $report = array();

    $status_list = array(
      1 => "Quote",
      2 => "Sold",
      3 => "Paid",
      4 => "Claimed",
      5 => "Cancel",
      6 => "Refund",
      7 => "Changed",
      8 => "Adjust",
    );

    $kArr = array(
      'policy' => 'Policy Number',
      'firstname' => 'First Name',
      'lastname' => 'Last Name',
      'province2' => 'Province',
      'status_id' => 'Status',
      'sold_date' => 'Sold Date',
      'add_time' => 'Payment Date',
      'effective_date' => 'Effective Date',
      'expiry_date' => 'Expire Date',
      'totaldays' => 'Number of Days',
      'days_used' => 'Days of Used',
      'sum_insured' => 'Sum Insured',
      'customer_cnt' => 'Quantity',
      'deductible_amount' => 'Deductible Amount',
      'dailyrate' => 'Daily Rate',
      'discount' => 'Discounted Amount',
      'premium' => 'Total',
      'earned' => 'Earned',
      'unearned' => 'Unearned',
      'product_short' => 'Product',
    );

    $date_range = $data['payment_added_from'] . " to " . $data['payment_added_to'];
    $product_short = empty($data['product_short']) ? "" : $data['product_short'];
    $title = array_values($kArr);

    $total = $tearned = 0;
    $solddate = '';
    foreach ($report_data as $record) {
      if ($record['ishead'] == 1) {
        $solddate = substr($record['add_time'], 0, 10);
      }
      if ($record['status_id'] == 6) {
        $dte = strtotime($record['expiry_date']);
        $dts = strtotime($record['effective_date']);
        $record['totaldays'] = round(($dte - $dts) / (60 * 60 * 24)) + 1;
      }
      if (abs($record['premium']) <= 25) {
        $record['premium'] = floatval($record['dailyrate'] * $record['totaldays']);
      }
      if ($record['days_used'] >= $record['totaldays']) {
        $earned = $record['premium'];
        $unearned = 0;
      } else if ($record['days_used'] > 0) {
        $earned = $record['premium'] * $record['days_used'] / $record['totaldays'];
        $unearned = $record['premium'] - $earned;
      } else {
        $earned = 0;
        $unearned = $record['premium'];
      }
      $total += $record['premium'];
      $tearned += $earned;
      $discount = 0;
      if ($record['totaldays'] >= 365) {
        $discount = $record['totaldays'] * $record['dailyrate'] - $record['premium'];
        if ($discount < 5) {
          $discount = 0;
        }
      }

      $arr = array();

      foreach ($kArr as $k => $v) {
        if ($k == "earned") {
          $arr[] = $earned;
        } else if ($k == "sold_date") {
          $arr[] = $solddate;
        } else if ($k == "status_id") {
          $arr[] = $status_list[$record["status_id"]];
        } else if ($k == "add_time") {
          $arr[] = substr($record[$k], 0, 10);
        } else if ($k == "days_used") {
          $arr[] = ($record[$k] > 0) ? $record[$k] : 0;
        } else if ($k == "discount") {
          $arr[] = number_format($discount, 2);
        } else if ($k == "premium") {
          $arr[] = number_format($record['premium'], 2);
        } else if ($k == "unearned") {
          $arr[] = number_format($unearned, 2);
        } else if ($k == "earned") {
          $arr[] = number_format($earned, 2);
        } else if ($k == "deductible_amount") {
          $arr[] = number_format($record[$k], 2);
        } else if ($k == "dailyrate") {
          $arr[] = number_format($record[$k], 2);
        } else {
          $arr[] = $record[$k];
        }
      }
      array_push($report, $arr);
    }
    $arr = array();
    foreach ($kArr as $k => $v) {
      if ($k == "earned") {
        $arr[] = number_format($tearned, 2);
      } else if ($k == "unearned") {
        $arr[] = number_format($total - $tearned, 2);
      } else if ($k == "premium") {
        $arr[] = number_format($total, 2);
      } else if ($k == "deductible_amount") {
        $arr[] = 'Total:';
      } else {
        $arr[] = '';
      }
    }
    $this->app_model->return_ok([
      "date_range" => $date_range,
      "product_short" => $product_short,
      "title" => $title,
      "report" => $report,
      "summary" => $arr,
    ]);
  }

  /* Agent.php */
  function sales_agent()
  {
    $this->error = "";
    $this->load->model("app_model");
    $this->load->model("user_model");
    $user = $this->app_model->check_token($this->input->post("token"));

    if (empty($user)) {
      if (empty($this->error)) {
        $this->error = "Timeout";
      }
      return $this->app_model->return_error($this->error);
    }

    $beuser = $user;
    $data = array();
    $data['beuser'] = $beuser;

    $this->load->model('product_model');
    $this->load->model('report_model');

    $data['title_txt'] = 'Sales Report to Agent';

    $data['product_short'] = $this->input->post('product_short');
    $data['agent_id'] = empty($this->input->post('agent_id')) ? 0 : (int)$this->input->post('agent_id');
    $data['region_id'] = empty($this->input->post('region_id')) ? $beuser['region_id'] : $this->input->post('region_id');
    $data['payment_added_from'] = $this->input->post('payment_added_from');
    $data['payment_added_to'] = $this->input->post('payment_added_to');
    $data['payment_date_from'] = $this->input->post('payment_date_from');
    $data['payment_date_to'] = $this->input->post('payment_date_to');
    $data['user_list'] = $this->user_model->get_available_user_list($beuser);


    $report_data = $this->report_model->get_sales_report_agent($data);
    $report = array();

    $kArr = array(
      'added' => 'Payment Date',
      'policy' => 'Policy No.',
      'up_insuer' => 'Insurer',
      'full_name' => 'Product',
      'insured' => 'Insured Name',
      'effective_date' => 'Effective Date',
      'expiry_date' => 'Expiry Date',
      'totaldays' => 'Number of Days',
      'dailyrate' => 'Daily Rate',
      'amount' => 'Policy Premium',
      'net_premium' => 'Net Premium',
    );
    if (($beuser['user_id'] == 2810) || ($beuser['user_id'] == 3297)) {
      $kArr['pay_mothed'] = 'Pay Mothed';
      $kArr['contact_email'] = 'Contact Email';
      $kArr['contact_phone'] = 'Contact Phone';
    }

    if (($beuser['user_id'] == 450) || ($beuser['user_id'] == 2018)) {
      $kArr['note'] = "Note";
    }

    foreach ($report_data as $data) {
      array_push($report, array_values($kArr));
      foreach ($data['records'] as $record) {
        $arr = array();
        foreach ($kArr as $k => $v) {
          if ($k == 'added') {
            $arr[] = substr($record[$k], 0, 10);
          } else if ($k == 'net_premium') {
            $arr[] = $record['amount'] - $record['commission'];
          } else {
            $arr[] = $record[$k];
          }
        }
        array_push($report, $arr);
      }
      $arr = array('Total Premium: $' . $data['data']['policy_premium'], '', '', 'Total Net Premium: $' . $data['data']['net_premium'], '', '', 'Username:' . $data['data']['agent_username'] . ' Email: ' . $data['data']['agent_email']);
      array_push($report, $arr);
      $arr = array('', '', '', '', '', '', '', '');
      array_push($report, $arr);
    }
    $this->app_model->return_ok([
      "report" => $report,
    ]);
  }

  /* Premium2.php */
  function annual()
  {
    $this->error = "";
    $this->load->model("app_model");
    $this->load->model("user_model");
    $user = $this->app_model->check_token($this->input->post("token"));

    if (empty($user)) {
      if (empty($this->error)) {
        $this->error = "Timeout";
      }
      return $this->app_model->return_error($this->error);
    }

    $beuser = $user;
    $data = array();
    $data['beuser'] = $beuser;

    $this->load->model('report_model');

    $data['title_txt'] = 'Annual Report';

    if ($beuser['user_group_id'] > 100) {
      $data['agent_id'] = $beuser['user_id'];
    } else {
      $data['agent_id'] = empty($this->input->post('agent_id')) ? "" : (int)$this->input->post('agent_id');
    }
    if (empty($data['agent_id'])) {
      return $this->app_model->return_error("Unknown Agent");
    }
    $agent = $this->user_model->get_user_by_id($data['agent_id']);
    if (empty($agent)) {
      return $this->app_model->return_error("Unknown Agent Info");
    }
    $user_id = $user["user_id"];
    $year = $this->input->post('year');
    $isupdate = $this->input->post('update');
    if (empty($year)) {
      $year = date("Y") - 1;
    }

    if ($isupdate == 1) {
      if ($beuser['user_group_id'] < 100) {
        $post = $this->input->post();
        $post['user_id'] = $beuser['user_id'];
        $post['date_time'] = date("Y-m-d H:i:s");
        $this->report_model->save_annual($data['agent_id'], $year, $user_id, $post);
      }
    }
    $report_data = $this->report_model->get_annual($data['agent_id'], $year, $user_id);
    if ($beuser['user_group_id'] < 100) {
      $report_data["premium"] = array();
      $report_data["commission"] = array();
      for ($i = 1; $i <= 12; $i++) {
        $rc = $this->report_model->get_month_payment($data['agent_id'], $year, $i);
        $report_data["premium"][$i] = $rc["premium"];
        if (!isset($report_data["premium2"][$i])) {
          $report_data["premium2"][$i] = $report_data["premium"][$i];
        }
        $report_data["commission"][$i] = $rc["commission"];
        if (!isset($report_data["commission2"][$i])) {
          $report_data["commission2"][$i] = $report_data["commission"][$i];
        }
      }
    }

    $premium = $commission = 0;
    if (isset($data['premium2'])) {
      for ($i = 1; $i <= 12; $i++) {
        $premium += $data['premium2'][$i];
        $commission += $data['commission2'][$i];
      }
    }

    $this->app_model->return_ok([
      "year" => $year,
      "premium" => $premium,
      "commission" => $commission,
      "report" => $report_data,
      "agent" => $agent,
    ]);
  }

  /* Jf.php */
  function jf()
  {
    $this->error = "";
    $this->load->model("app_model");
    $this->load->model("user_model");
    $user = $this->app_model->check_token($this->input->post("token"));

    if (empty($user)) {
      if (empty($this->error)) {
        $this->error = "Timeout";
      }
      return $this->app_model->return_error($this->error);
    }

    $beuser = $user;
    $data = array();
    $data['beuser'] = $beuser;

    $this->load->model('product_model');
    $this->load->model('report_model');

    $data['title_txt'] = 'Sales Report to Jf';

    $data['product_short'] = empty($data['product_short']) ? array() : array_keys($data['product_short']);
    $data['agent_id'] = empty($this->input->post('agent_id')) ? 0 : (int)$this->input->post('agent_id');
    $data['region_id'] = empty($this->input->post('region_id')) ? $beuser['region_id'] : $this->input->post('region_id');
    $data['payment_added_from'] = $this->input->post('payment_added_from');
    $data['payment_added_to'] = $this->input->post('payment_added_to');
    $data['payment_date_from'] = $this->input->post('payment_date_from');
    $data['payment_date_to'] = $this->input->post('payment_date_to');

    $report_data = $this->report_model->get_sales_report_jf($data);
    unset($report_data['amount']);
    $report = array();

    $kArr = array(
      'policy' => 'Policy No.',
      'apply_date' => 'Apply Date',
      'added' => 'Pay Date',
      'invoice_num' => 'Invoice Num',
      'up_insuer' => 'InsurerCoName',
      'product_short' => 'Product',
      'insured' => 'Insured Name',
      'effective_date' => 'Effective Date',
      'expiry_date' => 'Expiry Date',
      'totaldays' => 'Number of Days',
      'dailyrate' => 'Daily Rate',
      'premium' => 'Policy Premium',
      'tax' => 'Tax',
      'amount' => 'Pay Amount',
      'ispaid' => 'Paied',
      'pay_type' => 'Type',
      'pay_mothed' => 'Pay Mothed',
      'commission_rate' => 'Commission Rate',
      'net_premium' => 'Net Amount',
      'commission' => 'Commission Amount',
      'contact_email' => 'Contact Email',
      'contact_phone' => 'Contact Phone',
      'note' => 'Note',
    );
    if ($data['region_id'] != 4) {
      unset($kArr['contact_email']);
      unset($kArr['contact_phone']);
    }

    foreach ($report_data as $datas) {
      array_push($report, array($datas['full_name']));
      array_push($report, array_values($kArr));

      foreach ($datas['results'] as $record) {
        $arr = array();
        foreach ($kArr as $k => $v) {
          if ($k == 'ispaid') {
            $arr[] = ($record[$k] == 1) ? 'Y' : '-';
          } else if ($k == 'commission_rate') {
            if (abs($record['amount']) > 0.009) {
              $arr[] = sprintf("%0.2f", $record['commission'] * 100 / $record['amount']);
            } else {
              $arr[] = '';
            }
          } else if ($k == 'net_premium') {
            $arr[] = $record['amount'] - $record['commission'];
          } else if ($k == 'added') {
            $arr[] = substr($record[$k], 0, 10);
          } else {
            $arr[] = $record[$k];
          }
        }
        array_push($report, $arr);
      }

      $arr = array('', '', '', '', '', '', '', '', '', '', '', '', '', $datas['amount'], '', '', '', '', $datas['amount'] - $datas['commission'], $datas['commission']);
      array_push($report, $arr);
      array_push($report, array(''));
    }
    $this->app_model->return_ok([
      "report" => $report,
    ]);
  }

  /* Agentperiod.php */
  function sales_period()
  {
    $this->error = "";
    $this->load->model("app_model");
    $this->load->model("user_model");
    $user = $this->app_model->check_token($this->input->post("token"));

    if (empty($user)) {
      if (empty($this->error)) {
        $this->error = "Timeout";
      }
      return $this->app_model->return_error($this->error);
    }

    $beuser = $user;
    $data = array();
    $data['beuser'] = $beuser;

    $this->load->model('product_model');
    $this->load->model('report_model');

    $data['title_txt'] = 'Sales Report in period';

    $data['payment_added_from'] = $this->input->post('payment_added_from');
    $data['payment_added_to'] = $this->input->post('payment_added_to');
    $data['payment_date_from'] = $this->input->post('payment_date_from');
    $data['payment_date_to'] = $this->input->post('payment_date_to');

    $report_data = $this->report_model->get_report_in_period($data);

    $report = array();

    $kArr = array(
      'user_id' => 'ID',
      'username' => 'Username',
      'email' => 'Email',
      'firstname' => 'Firstname',
      'lastname' => 'Lastname',
      'receive_type' => 'Pay Type',
      'pay_type' => 'Allowed Pay method'
    );

    array_push($report, array_values($kArr));
    foreach ($report_data as $record) {
      $arr = array();
      foreach ($kArr as $k => $v) {
        $arr[] = $record[$k];
      }
      array_push($report, $arr);
    }

    $this->app_model->return_ok([
      "report" => $report,
    ]);
  }

  /* Insurer.php */
  function insurer()
  {
    $this->error = "";
    $this->load->model("app_model");
    $this->load->model("user_model");
    $user = $this->app_model->check_token($this->input->post("token"));

    if (empty($user)) {
      if (empty($this->error)) {
        $this->error = "Timeout";
      }
      return $this->app_model->return_error($this->error);
    }

    $beuser = $user;
    $data = array();
    $data['beuser'] = $beuser;

    $this->load->model('product_model');
    $this->load->model('report_model');

    $data['title_txt'] = 'Sales Report to Insurer';

    $data['product_short'] = $this->input->post('product_short');
    $data['agent_id'] = empty($this->input->post('agent_id')) ? 0 : (int)$this->input->post('agent_id');
    $data['region_id'] = empty($this->input->post('region_id')) ? $beuser['region_id'] : $this->input->post('region_id');
    $data['payment_added_from'] = $this->input->post('payment_added_from');
    $data['payment_added_to'] = $this->input->post('payment_added_to');
    $data['payment_date_from'] = $this->input->post('payment_date_from');
    $data['payment_date_to'] = $this->input->post('payment_date_to');

    $report_data = $this->report_model->get_sales_report_insurer($data);
    $report = array();

    $kArr = array(
      'policy' => 'Policy Number',
      'firstname' => 'First Name',
      'lastname' => 'Last Name',
      'gender' => 'Gender',
      'birthday' => 'Birth Date',
      'address' => 'Address',
      'city' => 'City',
      'province' => 'Province',
      'postcode' => 'Postcode',
      'effective_date' => 'Effective Date',
      'expiry_date' => 'Expire Date',
      'total_days' => 'Number of Days',
      'sum_insured' => 'Sum Insured',
      'deductible_amount' => 'Deductible Amount',
      'daily_rate' => 'Daily Rate',
      'policy_premium' => 'Policy Premium',
      'commission_rate_jf' => 'Commission Rate to JF',
      'merchant_fee_per' => 'Merchant Fee(Credit Card Fee)%',
      'claims_handling_fee_per' => 'Claims Handling',
      'commission_amount' => 'Commission Amount',
      'merchant_fee' => 'Merchant Fee(Credit Card Fee)',
      'claims_handling_fee' => 'Claims Handling Fee',
      'net_premium' => 'Net Premium',
      'total_compensation_per' => 'Total Compensation Rate(%)',
      'total_compensation' => 'Total Compensation Amount',
      'customer_cnt' => 'Total Members',
      // 'coverage' => 'Coverage',
      'added' => 'Purchase Date',
      'refund_date' => 'Refund Date',
    );

    array_push($report, array_values($kArr));
    foreach ($report_data as $data) {
      $arr = array();
      foreach ($kArr as $k => $v) {
        if ($k == "refund_date") {
          if ($data["status_id"] == 6) {
            $arr[] = $data[$k];
          } else {
            $arr[] = '';
          }
        } else {
          $arr[] = $data[$k];
        }
      }
      array_push($report, $arr);
    }
    $this->app_model->return_ok([
      "report" => $report,
    ]);
  }

  /* Insurer_top.php */
  function insurer_top()
  {
    $this->error = "";
    $this->load->model("app_model");
    $this->load->model("user_model");
    $user = $this->app_model->check_token($this->input->post("token"));

    if (empty($user)) {
      if (empty($this->error)) {
        $this->error = "Timeout";
      }
      return $this->app_model->return_error($this->error);
    }

    $beuser = $user;
    $data = array();
    $data['beuser'] = $beuser;

    $this->load->model('product_model');
    $this->load->model('report_model');

    $data['title_txt'] = 'Sales Report to Insurer';

    $data['product_short'] = 'TOP';
    $data['agent_id'] = empty($this->input->post('agent_id')) ? 0 : (int)$this->input->post('agent_id');
    $data['region_id'] = empty($this->input->post('region_id')) ? $beuser['region_id'] : $this->input->post('region_id');
    $data['payment_added_from'] = $this->input->post('payment_added_from');
    $data['payment_added_to'] = $this->input->post('payment_added_to');
    $data['payment_date_from'] = $this->input->post('payment_date_from');
    $data['payment_date_to'] = $this->input->post('payment_date_to');

    $report_data = $this->report_model->get_sales_report_insurer($data);
    $report = array();

    $kArr = array(
      'policy' => 'Policy Number',
      'firstname' => 'First Name',
      'lastname' => 'Last Name',
      'gender' => 'Gender',
      'birthday' => 'Birth Date',
      'address' => 'Address',
      'city' => 'City',
      'province' => 'Province',
      'postcode' => 'Postcode',
      'effective_date' => 'Effective Date',
      'expiry_date' => 'Expire Date',
      'total_days' => 'Number of Days',
      'sum_insured' => 'Sum Insured',
      'deductible_amount' => 'Deductible Amount',
      'daily_rate' => 'Daily Rate',
      'policy_premium' => 'Policy Premium',
      'commission_rate_jf' => 'Commission Rate to JF',
      'merchant_fee_per' => 'Merchant Fee(Credit Card Fee)%',
      'claims_handling_fee_per' => 'Claims Handling',
      'commission_amount' => 'Commission Amount',
      'merchant_fee' => 'Merchant Fee(Credit Card Fee)',
      'claims_handling_fee' => 'Claims Handling Fee',
      'net_premium' => 'Net Premium',
      'total_compensation_per' => 'Total Compensation Rate(%)',
      'total_compensation' => 'Total Compensation Amount',
      // 'customer_cnt' => 'Total Members',
      'coverage' => 'Coverage',
      'added' => 'Purchase Date',
      'refund_date' => 'Refund Date',
    );

    array_push($report, array_values($kArr));
    foreach ($report_data as $data) {
      $arr = array();
      foreach ($kArr as $k => $v) {
        if ($k == "refund_date") {
          if ($data["status_id"] == 6) {
            $arr[] = $data[$k];
          } else {
            $arr[] = '';
          }
        } else {
          $arr[] = $data[$k];
        }
      }
      array_push($report, $arr);
    }
    $this->app_model->return_ok([
      "report" => $report,
    ]);
  }

  /* Receivable.php */
  function receivable()
  {
    $this->error = "";
    $this->load->model("app_model");
    $this->load->model("user_model");
    $user = $this->app_model->check_token($this->input->post("token"));

    if (empty($user)) {
      if (empty($this->error)) {
        $this->error = "Timeout";
      }
      return $this->app_model->return_error($this->error);
    }

    $beuser = $user;
    $data = array();
    $data['beuser'] = $beuser;

    $this->load->model('product_model');
    $this->load->model('report_model');

    $data['title_txt'] = 'Receivable Report';

    $data['product_short'] = $this->input->post('product_short');
    $data['policy_status'] = $this->input->post('policy_status');
    $data['agent_id'] = empty($this->input->post('agent_id')) ? 0 : (int)$this->input->post('agent_id');
    $data['region_id'] = empty($this->input->post('region_id')) ? $beuser['region_id'] : $this->input->post('region_id');
    $data['payment_added_from'] = $this->input->post('payment_added_from');
    $data['payment_added_to'] = $this->input->post('payment_added_to');
    $data['payment_date_from'] = $this->input->post('payment_date_from');
    $data['payment_date_to'] = $this->input->post('payment_date_to');

    $report_data = $this->report_model->get_receivable($data);
    $report = array();

    $kArr = array(
      'order_date' => 'Pruchase Date',
      'policy' => 'Policy Number',
      'insured_name' => 'Customer Name',
      'student_id' => 'Student ID',
      'effective_date' => 'Effective Date',
      'expiry_date' => 'Expiry Date',
      'refund_date' => 'Refund Date',
      'total_days' => 'Trip Length',
      'policy_premium' => 'Premium',
      'net_premium' => 'Net',
      'commission_amount' => 'Commission',
      'cal_comm_rate' => 'Ratio',
    );

    array_push($report, array('JF Insurance Agency Group Inc.'));
    array_push($report, array('15 Wertheim court, Suite 501, Richmond Hill, ON, L4B 3H7'));
    array_push($report, array('Tel: 905-707-1512 Fax: 905-707-1513 Toll free: 1-877-832-5541'));
    array_push($report, array(''));
    array_push($report, array('Invoice Statement'));
    array_push($report, array('For Policy of: ', 'From ' . $report_data['period']['from'], 'To ' . $report_data['period']['to']));
    array_push($report, array('', '', '', '', '', '', '', ''));

    foreach ($report_data as $datas) {
      if (empty($datas['agency'])) {
        continue;
      }
      $arr = array('Bill to: ' . $datas['agency']['agent_name'] . ', ' . $datas['agency']['address'] . ', ' . $datas['agency']['province'] . ', ' . $datas['agency']['postal_code']);
      array_push($report, $arr);
      $arr = array('', '', '', '', '', '', '', '');
      array_push($report, $arr);

      $arr = array('Total Premium ' . $datas['agency']['outstanding']);
      array_push($report, $arr);
      $arr = array('Net Payable Amount: ' . $datas['agency']['payable_to_jf']);
      array_push($report, $arr);

      $arr = array('', '', '', '', '', '', '', '');
      array_push($report, $arr);

      array_push($report, array_values($kArr));

      foreach ($datas['records'] as $record) {
        $arr = array();
        foreach ($kArr as $k => $v) {
          if ($k == "refund_date") {
            if ($record["status_id"] == 6) {
              $arr[] = $record[$k];
            } else {
              $arr[] = "";
            }
          } else {
            $arr[] = $record[$k];
          }
        }
        array_push($report, $arr);
      }
    }

    $this->app_model->return_ok([
      "report" => $report,
    ]);
  }

  /* Receivable.php */
  function renewal()
  {
    $this->error = "";
    $this->load->model("app_model");
    $this->load->model("user_model");
    $user = $this->app_model->check_token($this->input->post("token"));

    if (empty($user)) {
      if (empty($this->error)) {
        $this->error = "Timeout";
      }
      return $this->app_model->return_error($this->error);
    }

    $beuser = $user;
    $data = array();
    $data['beuser'] = $beuser;

    if (($beuser['user_group_id'] > 100) && ($beuser['user_group_id'] != 104) && empty($data['agent_id'])) {
      return $this->app_model->return_error("No Report Data");
    }

    $this->load->model('product_model');
    $this->load->model('report_model');

    $data['title_txt'] = 'Renewal Report';

    $data['product_short'] = $this->input->post('product_short');
    $data['agent_id'] = empty($this->input->post('agent_id')) ? 0 : (int)$this->input->post('agent_id');
    $data['region_id'] = empty($this->input->post('region_id')) ? $beuser['region_id'] : $this->input->post('region_id');
    $data['application_date_from'] = $this->input->post('application_date_from');
    $data['application_date_to'] = $this->input->post('application_date_to');
    $data['arrival_date_from'] = $this->input->post('arrival_date_from');
    $data['arrival_date_to'] = $this->input->post('arrival_date_to');
    $data['effective_date_from'] = $this->input->post('effective_date_from');
    $data['effective_date_to'] = $this->input->post('effective_date_to');
    $data['expiry_date_from'] = $this->input->post('expiry_date_from');
    $data['expiry_date_to'] = $this->input->post('expiry_date_to');

    $report_data = $this->report_model->get_renewal_report($data, $user);
    $report = array();

    $kArr = array(
      'policy' => 'Policy Number',
      'effective_date' => 'Effective Date',
      'expiry_date' => 'Expiry Date',
      'customer_name' => 'Customer Name',
      'gender' => 'Gender',
      'birthday' => 'Birthday',
      'province' => 'Province',
      'phone' => 'Phone Number',
      'email' => 'Email Address'
    );

    $date_from = $report_data['period']['from'];
    $date_to = $report_data['period']['to'];
    if ($report_data['data']) {
      foreach ($report_data['data'] as $datas) {
        $arr = array('Expire Date From: ', $date_from, 'To: ', $date_to);
        array_push($report, $arr);
        $arr = array('Agent: ', $datas['agency']);
        array_push($report, $arr);
        $arr = array('', '');
        array_push($report, $arr);
        array_push($report, array_values($kArr));
  
        foreach ($datas['records'] as $record) {
          $arr = array();
          foreach ($kArr as $k => $v) {
            $arr[] = $record[$k];
          }
          array_push($report, $arr);
        }
        $arr = array('', '');
        array_push($report, $arr);
      }
    }

    $this->app_model->return_ok([
      "report" => $report,
    ]);
  }

  /* Refund.php */
  function refund()
  {
    $this->error = "";
    $this->load->model("app_model");
    $this->load->model("user_model");
    $user = $this->app_model->check_token($this->input->post("token"));

    if (empty($user)) {
      if (empty($this->error)) {
        $this->error = "Timeout";
      }
      return $this->app_model->return_error($this->error);
    }

    $beuser = $user;
    $data = array();
    $data['beuser'] = $beuser;

    if ($beuser['user_group_id'] > 100) {
      return $this->app_model->return_error("No Report Data");
    }

    $this->load->model('product_model');
    $this->load->model('report_model');

    $data['title_txt'] = 'Renewal Report';

    $data['product_short'] = $this->input->post('product_short');
    $data['ispaid'] = empty($this->input->post('ispaid')) ? 0 : 1;
    $data['region_id'] = empty($this->input->post('region_id')) ? $beuser['region_id'] : $this->input->post('region_id');
    $data['pay_date_from'] = $this->input->post('pay_date_from');
    $data['pay_date_to'] = $this->input->post('pay_date_to');
    $data['create_date_from'] = $this->input->post('create_date_from');
    $data['create_date_to'] = $this->input->post('create_date_to');

    $report_data = $this->report_model->get_refund_report($data);
    $report = array();

    $kArr = array(
      'policy' => 'Policy #',
      'customer_name' => 'Customer Name',
      'birthday' => 'DOB',
      'refund_date' => 'Refund Date',
      'agent_name' => 'Agent Name',
      'premium' => 'Original Premium',
      'commission' => 'Original Net Premium',
      'net_amount' => 'Refund Amount',
      'admin_fee' => 'Admin Fee<',
      'amount' => 'Total Refund',
      'added' => 'Refund Process Date'
    );

    array_push($report, array_values($kArr));
    $total = 0;
    foreach ($report_data as $record) {
      $arr = array();
      $total += $record['amount'];
      foreach ($kArr as $k => $v) {
        if ($k == 'commission') {
          $arr[] = (float)($record['premium'] - $record['commission']);
        } else if ($k == 'premium') {
          $arr[] = (float)$record['premium'];
        } else if ($k == 'net_amount') {
          $arr[] = (float)$record['net_amount'];
        } else if ($k == 'admin_fee') {
          $arr[] = (float)$record['admin_fee'];
        } else if ($k == 'amount') {
          $arr[] = (float)$record['amount'];
        } else if ($k == 'added') {
          $arr[] = substr($record['added'], 0, 10);
        } else {
          $arr[] = $record[$k];
        }
      }
      array_push($report, $arr);
    }

    $arr = array('Total', '', '', '', '', '', '', '', '', $total);
    array_push($report, $arr);

    $this->app_model->return_ok([
      "report" => $report,
    ]);
  }

  /* Commission.php */
  function commission()
  {
    $this->error = "";
    $this->load->model("app_model");
    $this->load->model("user_model");
    $user = $this->app_model->check_token($this->input->post("token"));

    if (empty($user)) {
      if (empty($this->error)) {
        $this->error = "Timeout";
      }
      return $this->app_model->return_error($this->error);
    }

    $beuser = $user;
    $data = array();
    $data['beuser'] = $beuser;

    $this->load->model('product_model');
    $this->load->model('report_model');

    $data['title_txt'] = 'Sales Report to Insurer';

    $data['product_short'] = $this->input->post('product_short');
    $data['ispaid'] = empty($this->input->post('ispaid')) ? 0 : 1;
    $data['asbroker'] = empty($this->input->post('asbroker')) ? 0 : 1;
    $data['agent_id'] = empty($this->input->post('agent_id')) ? 0 : (int)$this->input->post('agent_id');
    $data['region_id'] = empty($this->input->post('region_id')) ? $beuser['region_id'] : $this->input->post('region_id');
    $data['payment_added_from'] = $this->input->post('payment_added_from');
    $data['payment_added_to'] = $this->input->post('payment_added_to');
    $data['payment_date_from'] = $this->input->post('payment_date_from');
    $data['payment_date_to'] = $this->input->post('payment_date_to');

    $report_data = $this->report_model->get_commission_report($data);
    $report = array();

    $kArr = array(
      'added' => 'Date',
      'policy' => 'Policy Number',
      'customer_name' => 'Customer Name',
      'effective_date' => 'Effective Date',
      'expiry_date' => 'Expiry Date',
      'total_days' => 'Trip Length',
      'premium' => 'Premium Amount',
      'premiumispaid' => 'Premium Payment',
      'rate' => 'Commission Rate',
      'amount' => 'Commission Amount'
    );

    array_push($report, array("JF INSURANCE AGENCY GROUP INC - COMMISSION REPORT"));
    $total_a_premium = 0;
    $total_a_commission = 0;
    $unpaid_a_premium = 0;
    $asbroker = $data['asbroker'];
    // array_push($report, array_values($kArr));
    foreach ($report_data as $user_id => $datas) {
      if (empty($datas['data'])) continue;
      $arr = array('', '', '', '', '', '');
      if ($user_id != 'asbroker') {
        array_push($arr, "Payment Period: " . $data['payment_added_from'] . " - " . $data['payment_added_to']);
      }
      array_push($report, $arr);
      if (empty($asbroker)) {
        if ($datas['agent']['receive_type'] == 'Cheque') {
          $arr = array("To: ", $datas['agent']['mail_name']);
          array_push($report, $arr);
          $arr = array('', $datas['agent']['mail_address']);
          array_push($report, $arr);
          $arr = array('', $datas['agent']['mail_city'] . "," . $datas['agent']['mail_province2']);
          array_push($report, $arr);
          $arr = array('', $datas['agent']['mail_postcode']);
          array_push($report, $arr);
        }
        array_push($report, array("Agent Name: " . $datas['agent']['mail_name']));
        array_push($report, array("Payment Method: " . $datas['agent']['receive_type']));

        if ($datas['agent']['receive_type'] == 'Deposit') {
          array_push($report, array("Pay to: " . $datas['agent']['note']));
          array_push($report, array("E-Mail Address: " . $datas['agent']['email']));
        } else if ($datas['agent']['receive_type'] == 'Cheque') {
          array_push($report, array("Pay to: " . $datas['agent']['note']));
        }
      } else {
        if (isset($report_data['asbroker'])) {
          if ($report_data['asbroker']['receive_type'] == 'Cheque') {
            array_push($report, array("To: ", $report_data['asbroker']['mail_name']));
            array_push($report, array('', $report_data['asbroker']['mail_address']));
            array_push($report, array('', $report_data['asbroker']['mail_city'] . "," . $report_data['asbroker']['mail_province2']));
            array_push($report, array('', $report_data['asbroker']['mail_postcode']));
          }
          array_push($report, array("Agent Name: " . $report_data['asbroker']['mail_name']));
          array_push($report, array("Payment Method: " . $report_data['asbroker']['receive_type']));
          if ($report_data['asbroker']['receive_type'] == 'Deposit') {
            array_push($report, array("Pay to: " . $report_data['asbroker']['note']));
            array_push($report, array("E-Mail Address: " . $report_data['asbroker']['email']));
          } else if ($report_data['asbroker']['receive_type'] == 'Cheque') {
            array_push($report, array("Pay to: " . $report_data['asbroker']['note']));
          }
          unset($report_data['asbroker']);
        }
        if ($user_id == 'asbroker') continue;
        array_push($report, array("Agent Name: " . $datas['agent']['mail_name']));
      }

      $arr = array();
      foreach ($kArr as $k => $v) {
        array_push($arr, $v);
      }
      array_push($report, $arr);

      $total_premium = 0;
      $total_commission = 0;
      $unpaid_premium = 0;
      foreach ($datas['data'] as $record) {
        $total_a_premium += $record['premium'];
        $total_a_commission += $record['amount'];
        $unpaid_a_premium += ($record['premiumispaid']) ? 0 : $record['premium'];
        $total_premium += $record['premium'];
        $total_commission += $record['amount'];
        $unpaid_premium += ($record['premiumispaid']) ? 0 : $record['premium'];
        $arr = array(
          $record['added'],
          $record['policy'],
          $record['customer_name'],
          $record['effective_date'],
          $record['expiry_date'],
          $record['total_days'],
          $record['premium'],
          $record['premiumispaid'] ? 'Paid' : '-',
          $record['rate'] / 100.0,
          $record['amount']
        );
        array_push($report, $arr);
      }

      if (empty($asbroker)) {
        $arr = array(
          'TOTAL',
          '',
          '',
          '',
          '',
          '',
          $total_premium,
          '',
          '',
          $total_commission
        );
        array_push($report, $arr);

        $arr = array(
          'Total Commission for Above',
          '',
          $total_commission
        );
        array_push($report, $arr);

        $arr = array(
          'Unpaid Premium',
          '',
          $unpaid_premium
        );
        array_push($report, $arr);

        $arr = array(
          'Balance',
          '',
          $total_commission - $unpaid_premium
        );
        array_push($report, $arr);
        array_push($report, array(''));
      }
    }

    if ($asbroker) {
      $arr = array(
        'Total Commission',
        '',
        $total_a_commission
      );
      array_push($report, $arr);

      $arr = array(
        'Unpaid Premium',
        '',
        $unpaid_a_premium
      );
      array_push($report, $arr);

      $arr = array(
        'Balance',
        '',
        $total_a_commission - $unpaid_a_premium
      );
      array_push($report, $arr);
    }

    $this->app_model->return_ok([
      "report" => $report,
    ]);
  }

  /* Agent_Commission.php */
  function agent_commission()
  {
    $this->error = "";
    $this->load->model("app_model");
    $this->load->model("user_model");
    $user = $this->app_model->check_token($this->input->post("token"));

    if (empty($user)) {
      if (empty($this->error)) {
        $this->error = "Timeout";
      }
      return $this->app_model->return_error($this->error);
    }

    $beuser = $user;
    $data = array();
    $data['beuser'] = $beuser;

    $this->load->model('product_model');
    $this->load->model('report_model');

    $data['title_txt'] = 'Sales Report to Insurer';

    $data['agent_id'] = empty($this->input->post('agent_id')) ? 0 : (int)$this->input->post('agent_id');
    $data['receive_type'] = $this->input->post('receive_type');
    $data['minvalue'] = $this->input->post('minvalue');
    $data['region_id'] = empty($this->input->post('region_id')) ? $beuser['region_id'] : $this->input->post('region_id');
    $data['payment_date_from'] = $this->input->post('payment_date_from');
    $data['payment_date_to'] = $this->input->post('payment_date_to');

    if ($user["user_group_id"] > 100) {
      $data['user_list'] = $this->user_model->get_available_user_list($buser);
    }

    $report_data = $this->report_model->get_agent_commission_report($data, $beuser);
    $report = array();

    $kArr = array(
      'username' => 'Username',
      'agent_name' => 'Agent Name',
      'total_balance' => 'Total Balance',
      'unpaid_premium' => 'Unpaid Premium',
      'last_paid' => 'Last Paid Date',
      'receive_type' => 'Pay Method',
      'note' => 'Note'
    );

    array_push($report, array('Period : ' . $data["payment_date_from"] . " - " . $data["payment_date_to"]));
    array_push($report, array_values($kArr));
    foreach ($report_data as $record) {
			$arr = array();
			foreach ($kArr as $k => $v) {
				if ($k == 'last_paid') {
					$arr[] = substr($record[$k], 0, 10);
				} else {
					$arr[] = $record[$k];
				}
			}
			array_push($report, $arr);
		}
    $this->app_model->return_ok([
      "report" => $report,
    ]);
  }
}
