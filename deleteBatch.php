<?php
$connArr = array(
  "jfweb" => array(
    array(
      "host" => "172.31.91.60",
      "user" => "remoteacc",
      "pass" => "b3FFc99bFE34885",
    ),
    array(
      "host" => "aurora1.cluster-coadisrdeyad.us-east-1.rds.amazonaws.com",
      "user" => "admin",
      "pass" => "Aurora1JF221119",
    ),
    array(
      "activity" => "activity_id",
      "activity2017" => "activity_id",
      "activity2019" => "activity_id",
      "activity_bk_20221011" => "activity_id",
      "batch" => "batch_number",
      "citem" => "citem_id",
      "claim" => "claim_id",
      "country" => "country_id",
      "coverage_code" => "coverage_code_id",
      "customer" => "customer_id",
      "forgetpwd" => "forgetid",
      "myhome" => "user_id",
      "payment" => "payment_id",
      "payment2017" => "payment_id",
      "payment2019" => "payment_id",
      "payment_220514" => "payment_id",
      "payment_bk" => "payment_id",
      "payment_bk_20221011" => "payment_id",
      "plan" => "plan_id",
      "plan_220514" => "plan_id",
      "plan_220920" => "plan_id",
      "plan_history" => "plan_history_id",
      "plan_history_220514" => "plan_history_id",
      "plan_history_220528" => "plan_history_id",
      "product" => "product_short",
      "product_20220906" => "product_short",
      "product_customize" => "product_customize_id",
      "product_deductible" => "product_deductible_id",
      "product_insured" => "product_insured_id",
      "province" => "province_id",
      "province_orig" => "province_id",
      "psigate" => "psigate_id",
      "ptmp" => "plan_id",
      "region" => "region_id",
      "setting" => "setting_id",
      "status" => "status_id",
      "user" => "user_id",
      "user_annual_report" => "user_id,year",
      "user_group" => "user_group_id",
      "user_meta" => "user_id,type",
      "user_product" => "user_id,product_short",
      "user_product_0418_TOP" => "user_id,product_short",
      "user_product_2" => "user_id,product_short",
      "user_product_220701" => "user_id,product_short",
    ),
  ),
  "jf_claim_management" => array(
    array(
      "host" => "172.31.91.60",
      "user" => "remoteacc",
      "pass" => "b3FFc99bFE34885",
    ),
    array(
      "host" => "aurora1.cluster-coadisrdeyad.us-east-1.rds.amazonaws.com",
      "user" => "admin",
      "pass" => "Aurora1JF221119",
    ),
    array(
      // "active" => "active_id",
      // "api_login" => "api_id",
      // "api_login_try" => "try_id",
      "case" => "id",
      "case_claim_master" => "id",
      "claim" => "id",
      "claim_20210924" => "id",
      "claim_status_change" => "id",
      "country" => "id",
      // "currency" => "name",
      // "currency_exchange" => "name",
      "diagnosis" => "id",
      "eclaim" => "id",
      "eclaim_file" => "id",
      "expenses_claimed" => "id",
      "expenses_claimed_20220714" => "id",
      "expenses_claimed_20220923" => "id",
      "expenses_provider" => "id",
      "groups" => "id",
      "intake_form" => "id",
      "login_attempts" => "id",
      "logs" => "id",
      "mytask" => "id",
      "payees" => "id",
      // "phone_action" => "phone_action_id",
      // "phone_agent" => "phone_agent_id",
      "phone_call" => "id",
      // "phone_cron" => "phone_cron_id",
      // "phone_records" => "phone_id",
      // "phone_ring" => "phone_id,event_tm,agent,user_id,queue",
      // "policies" => "policy_no",
      // "product" => "product_short",
      "product_220515" => "product_short",
      "provider" => "id",
      "province" => "id",
      "reason2s" => "id",
      "reasons" => "id",
      // "relations" => "id",
      // "schedule" => "id",
      // "states" => "id",
      // "template" => "id",
      // "template_20190909" => "id",
      // "template_20200722" => "id",
      // "user_group_id" => "user_product" => "id",
      // "users" => "id",
      // "users_groups" => "id",
      // "word_comments" => "id",
    ),
  ),
);

$sconn = null;
$dconn = null;
foreach ($connArr as $db => $conn) {
  echo "Start do " . $db . " at " . date("Y-m-d H:i:s") . "\n";
  if (empty($sconn)) {
    $sconn = new mysqli($conn[0]["host"], $conn[0]["user"], $conn[0]["pass"], $db);
    // Check connection
    if ($sconn->connect_error) {
      die("Connection soruce db error: " . $sconn->connect_error);
    }
    $sql = "SET time_zone = 'America/Toronto'";
    $sconn->query($sql);
  }

  if (empty($dconn)) {
    $dconn = new mysqli($conn[1]["host"], $conn[1]["user"], $conn[1]["pass"], $db);
    // Check connection
    if ($dconn->connect_error) {
      die("Connection destnation db error: " . $dconn->connect_error);
    }
    $dconn->query($sql);
  }

  $tables = $conn[2];
  foreach ($tables as $tab => $key) {
    $keys = explode(",", $key);
    $limit = 10000;
    $start = 0;
    echo $db . "." . $tab . " >>>>>>>>> " . $key . "\n";
    do {
      $cnt = 0;
      $sql = "SELECT * FROM `" . $tab . "` ORDER BY " . $keys[0];
      for ($i = 1; $i < sizeof($keys); $i++) {
        $sql .= "," . $keys[$i];
      }
      $sql .= " LIMIT " . $start . "," . $limit;
      $result = $dconn->query($sql);
      if ($result && ($result->num_rows > 0)) {
        echo $sql . "\n";
        $sql1 = "SELECT * FROM `" . $tab . "` WHERE " . $keys[0] . "='";
        while ($row = $result->fetch_assoc()) {
          print_r($row);
          $cnt++;
          echo "cnt:" . $cnt . "\n";
          $sqls = $sql1 . $row[$keys[0]] . "'";
          for ($i = 1; $i < sizeof($keys); $i++) {
            $sqls .= " AND " . $keys[$i] . "='" . $row[$keys[$i]] . "'";
          }
          $result1 = $sconn->query($sqls);
          $err = false;
          if ($row1 = $result1->fetch_assoc()) {
            echo "rowcnt:" . sizeof($row) . "\n";
            foreach ($row as $idx => $val) {
              if ($val != $row1[$idx]) {
                $err = true;
                break;
              }
            }
          } else {
            $err =  true;
          }
          if ($err) {
            echo "=================\n";
            echo $sqls . ";\n";
            print_r($row);
            print_r($row1);
          }
        }
        $start += $limit;
      } else {
        echo "=================\n";
        echo $sql . ";\n";
        echo "No Result !!!\n";
      }
    } while ($cnt == $limit);
  }
}
echo "All Done at: " . date("Y-m-d H:i:s") . "\n";
