<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>PDF File</title>
  <?php echo $style; ?>
</head>

<body>
  <header>
    <!--p class="rh">JF Group</p-->
  </header>
  <div class="container" <?php if ($hadheaderfooter) { ?> style="padding: 0 40px;" <?php } ?>>
    <div class="row" <?php if ($hadheaderfooter) { ?> style="padding-top: 60px;" <?php } ?>>
      <div class="col-sm-6 text-left">
        <h2><?php if ($plan['status_id'] < 2) { ?><?php echo $this->lang->line("Quote"); ?><?php } else { ?><?php echo $this->lang->line("Confirmation"); ?><?php } ?> <?php echo $this->lang->line("of Insurance"); ?></h2>
      </div>
      <div class="col-sm-6 text-left">
        <?php if ($user['user_group_id'] > 100) { ?>
          <p class="topp" style="font-weight:bold;">
            <?php echo empty($user['business']) ? 'JF Agent' : htmlspecialchars($user['business']); ?> -
            <span style="text-transform: capitalize;font-weight:bold;"><?php echo ($user) ? htmlspecialchars($user['firstname'] . " " . $user['lastname']) : ''; ?></span>
          </p>
          <p class="topp">
            <?php echo ($user) ? htmlspecialchars($user['address'] . ' ' . $user['city'] . ' ' . $user['province2'] . ' ' . $user['postcode']) : ''; ?>
          </p>
          <p class="topp">
            <?php echo ($user) ? htmlspecialchars($user['business_phone']) : ''; ?>
            <?php if (!empty($user['website'])) { ?>
              &nbsp;<?php echo htmlspecialchars($user['website']); ?>
            <?php } ?>
          </p>
        <?php } else { ?>
          <p class="topp" style="font-weight:bold;"> JF Agent - <span style="text-transform: capitalize;font-weight:bold;">Johnson Fu</span></p>
          <p class="topp"><?php echo ($user) ? htmlspecialchars($user['address'] . ', ' . $user['city'] . ' ' . $user['province2'] . ' ' . $user['postcode']) : ''; ?></p>
          <p class="topp"><?php echo ($user) ? htmlspecialchars($user['business_phone']) : ''; ?></p>
        <?php } ?>
        <h4><br /></h4>
      </div>
    </div>
    <div style="display:block;">
      <hr style="margin:3px auto;" />
    </div>
    <div class="row" style="margin-top: -15px;">
      <div class="col-sm-12 nopadding">
        <h4><u><?php echo $this->lang->line("Policy Detail"); ?></u></h4>
      </div>
    </div>
    <div class="row" style="margin-top: -15px;">
      <div class="col-sm-6 nopadding">
        <h4><?php echo $this->lang->line("Policy Holder"); ?>: <span><?php echo htmlspecialchars($customer['firstname'] . " " . $customer['lastname']); ?></span></h4>
        <h4><?php echo $this->lang->line("Date of Birth"); ?>: <span><?php echo $customer['birthday']; ?></span>
        </h4>
        <h4><?php echo $this->lang->line("Address"); ?>: <span>
            <?php if (!empty($plan['suite_number'])) {
              echo  "Suite " . htmlspecialchars($plan['suite_number']) . " ";
            } ?>
            <?php echo htmlspecialchars($plan['street_number'] . ' ' . $plan['street_name']); ?>, <?php echo htmlspecialchars($plan['city'] . ', ' . $plan['province2'] . ', ' . $plan['postcode']); ?></span>
        </h4>
        <h4><?php echo $this->lang->line("Phone Number"); ?>: <span><?php echo htmlspecialchars($plan['phone1']); ?></span>
        </h4>
        <h4><?php echo $this->lang->line("Email"); ?>: <span><?php echo htmlspecialchars($plan['contact_email']); ?></span>
        </h4>
      </div>
      <div class="col-sm-1 nopadding">
        &nbsp;
      </div>
      <div class="col-sm-5 nopadding2">
        <h4><?php if ($plan['status_id'] < 2) { ?><?php echo $this->lang->line("Quote"); ?><?php } else { ?><?php echo $this->lang->line("Policy"); ?><?php } ?> <?php echo $this->lang->line("Number"); ?>: <span><?php echo $plan['policy']; ?></span></h4>
        <h4><?php echo $this->lang->line("Application Date"); ?>: <span><?php echo $plan['apply_date']; ?></span>
        </h4>
        <h4><?php echo $this->lang->line("Effective Date"); ?>: <span><?php echo $plan['effective_date']; ?></span>
        </h4>
        <h4><?php echo $this->lang->line("Expiry Date"); ?>: <span><?php echo $plan['expiry_date']; ?></span>
        </h4>
        <h4><?php echo $this->lang->line("Number of Days"); ?>: <span><?php echo $plan['totaldays']; ?></span>
        </h4>
        <h4><br /><br /></h4>
      </div>
    </div>
    <!-- end policy detail -->
    <div style="display:block;">
      <hr style="margin:3px auto;" />
    </div>
    <!-- Coverage and payment Details-->
    <div class="row" style="margin-top: -15px;">
      <div class="col-sm-6 nopadding">
        <h4><u><?php echo $this->lang->line("Coverage Details"); ?></u></h4>
        <h4><?php echo $this->lang->line("Insurance Plan"); ?>:<br />&nbsp;&nbsp;&nbsp;<span><?php echo $plan_full_name; ?></span></h4>
        <h4><?php echo $this->lang->line("Plan Type"); ?>: <span><?php if ($plan['isfamilyplan'] == 1) {
                                echo $this->lang->line("Family");
                              } else {
                                echo $this->lang->line("Individual");
                              } ?></span>
        </h4>
        <h4><?php echo $this->lang->line("Sum Insured"); ?>: <span>$<?php echo number_format($plan['sum_insured'], 2); ?></span>
        </h4>
        <h4><?php echo $this->lang->line("Deductible"); ?>: <span>$<?php echo number_format($plan['deductible_amount'], 2); ?></span>
        </h4>
        <h4><?php echo $this->lang->line("Beneficiary"); ?>: <span><?php echo htmlspecialchars($plan['beneficiary']); ?></span>
        </h4>
        <h4><?php echo $this->lang->line("Stable pre-existing condition coverage"); ?>: <span><?php echo ($plan['stable_condition'] == 1) ? $this->lang->line("Yes") : $this->lang->line("No"); ?></span>
        </h4>
      </div>
      <div class="col-sm-1 nopadding2">
        &nbsp;
      </div>
      <div class="col-sm-5 nopadding">
        <?php if ($withprice) { ?>
          <h4><u><?php echo $this->lang->line("Payment Details"); ?></u></h4>
          <h4><?php echo $this->lang->line("Total Premium"); ?>: <span>$<?php echo number_format($plan['premium'], 2, '.', ','); ?></span></h4>
          <!-- <h4>Premium: <span>$<?php echo number_format((float)$plan['premium'] - (float)$plan['tax'], 2, '.', ','); ?></span>
          </h4> -->
          <h4><?php echo $this->lang->line("Tax"); ?>: <span>$<?php echo number_format($plan['tax'], 2, '.', ','); ?></span>
          </h4>
          <h4><?php echo $this->lang->line("Payment Date"); ?>: <span><?php echo (($plan['status_id'] >= 2) && isset($payment['added'])) ? date('Y-m-d', strtotime($payment['added'])) : ''; ?></span>
          </h4>
          <h4><?php echo $this->lang->line("Payment Method"); ?>: <span><?php echo (($plan['status_id'] >= 2) && isset($payment['pay_mothed'])) ? $payment['pay_mothed'] : ''; ?></span>
          </h4>
        <?php } ?>
      </div>
    </div>
    <div class="row">
      <div class="col-sm-12 nopm special-note">
        <h4 style="border-bottom:1px solid #777;"><?php echo $this->lang->line("Special Note"); ?></h4>
      </div>
    </div>
    <div class="row">
      <div class="col-sm-12 nopm">
        <p class="small" style="margin-top:2px;">
        <?php echo $this->lang->line("All persons insured are subject to the terms and conditions below."); ?> <br />
        <?php echo $this->lang->line("This document does not constitute the entire insurance policy, and the insured(s) has been advised to read the full policy details of coverage and exclusions"); ?>. <br />
        <?php echo $this->lang->line("The eligibility requirements for the plan applied for are material to the risk for which insurance is sought. If the insured(s) does not meet the eligibility requirements for the plan selected, or if there is any misrepresentation, concealment, or failure to disclose any facts or matters pertaining to the insured(s) that is the subject of the application form, then there shall be no insurance coverage provided"); ?>. <br />
        <?php echo $this->lang->line("The insured(s) is aware that this insurance covers Emergencies (as defined in the policy) and may not cover expenses incurred after the insured(s) is able to travel home for treatment. The insured(s) is aware that Pre-existing Conditions (as defined in the policy) are excluded in some circumstances and that further details are provided in the policy"); ?>.<br />
        <?PHP echo $this->lang->line("IN THE EVENT OF A MEDICAL EMERGENCY OR CLAIM THAT MAY REQUIRE OR RESULT IN HOSPITALIZATION, CALL ONTIME CARE WORLDWIDE INC. IMMEDIATELY"); ?> <br />
        <?php echo $this->lang->line("Toll Free Canada/U.S.A. 1-866-209-5804"); ?><br />
        <?php echo $this->lang->line("Collect call worldwide 905-707-9555"); ?><br />
        <?php echo $this->lang->line("If you notice any errors in the above information or have any questions, please contact JF Insurance Agency Group Inc."); ?><br />
        </p>
      </div>
    </div><br />
    <!--/div-->
    <!-- end p-detail -->
    <!--/div-->
    <!-- x_content -->
    <div class="row">
      <div class="col-sm-6 nopm">
        <p class="small">Ontario:<br />
          15 Wertheim Court, Suite 501,<br />
          Richmond Hill, ON, Canada L4B 3H7<br />
          Phone: 905-707-1512 Or 1-877-832-5541</p>
      </div>
      <!-- <div class="col-sm-6 nopm">
        <p class="small">British Columbia:<br />
          128 - 6061 No. 3 Road<br />
          Richmond, BC, Canada V6Y 282<br />
          Phone: 604-232-0896 Or 1-877-232-0896</p>
      </div> -->
    </div>
    <div class="row">
      <div class="col-sm-12 nopm special-note">
        <p class="small"><?php echo $this->lang->line("Underwritten by Old Republic Insurance Company of Canada and administered by JF Insurance Agency Group Inc."); ?></p>
      </div>
    </div>
  </div><!-- End Container -->
  <?php 
  if ($plan['isfamilyplan']) { 
    for ($i = 0; $i < sizeof($customers); $i++) {
      if (empty($customers[$i]['lastname']) && empty($customers[$i]['firstname'])) continue;
  ?>
  <pagebreak>
  <div class="container" <?php if ($hadheaderfooter) { ?> style="padding: 0 40px;" <?php } ?>>
    <div class="row" <?php if ($hadheaderfooter) { ?> style="padding-top: 60px;" <?php } ?>>
      <div class="col-sm-6 text-left">
        <h2><?php if ($plan['status_id'] < 2) { ?><?php echo $this->lang->line("Quote"); ?><?php } else { ?><?php echo $this->lang->line("Confirmation"); ?><?php } ?> <?php echo $this->lang->line("of Insurance"); ?></h2>
      </div>
      <div class="col-sm-6 text-left">
        <?php if ($user['user_group_id'] > 100) { ?>
          <p class="topp" style="font-weight:bold;">
            <?php echo empty($user['business']) ? 'JF Agent' : htmlspecialchars($user['business']); ?> -
            <span style="text-transform: capitalize;font-weight:bold;"><?php echo ($user) ? htmlspecialchars($user['firstname'] . " " . $user['lastname']) : ''; ?></span>
          </p>
          <p class="topp">
            <?php echo ($user) ? htmlspecialchars($user['address'] . ' ' . $user['city'] . ' ' . $user['province2'] . ' ' . $user['postcode']) : ''; ?>
          </p>
          <p class="topp">
            <?php echo ($user) ? htmlspecialchars($user['business_phone']) : ''; ?>
            <?php if (!empty($user['website'])) { ?>
              &nbsp;<?php echo htmlspecialchars($user['website']); ?>
            <?php } ?>
          </p>
        <?php } else { ?>
          <p class="topp" style="font-weight:bold;"> JF Agent - <span style="text-transform: capitalize;font-weight:bold;">Johnson Fu</span></p>
          <p class="topp"><?php echo ($user) ? htmlspecialchars($user['address'] . ', ' . $user['city'] . ' ' . $user['province2'] . ' ' . $user['postcode']) : ''; ?></p>
          <p class="topp"><?php echo ($user) ? htmlspecialchars($user['business_phone']) : ''; ?></p>
        <?php } ?>
        <h4><br /></h4>
      </div>
    </div>
    <div style="display:block;">
      <hr style="margin:3px auto;" />
    </div>
    <div class="row" style="margin-top: -15px;">
      <div class="col-sm-12 nopadding">
        <h4><u><?php echo $this->lang->line("Policy Detail"); ?></u></h4>
      </div>
    </div>
    <div class="row" style="margin-top: -15px;">
      <div class="col-sm-6 nopadding">
        <h4><?php echo $this->lang->line("Policy Holder"); ?>: <span><?php echo htmlspecialchars($customers[$i]['firstname'] . " " . $customers[$i]['lastname']); ?></span></h4>
        <h4><?php echo $this->lang->line("Date of Birth"); ?>: <span><?php echo $customers[$i]['birthday']; ?></span>
        </h4>
        <h4><?php echo $this->lang->line("Address"); ?>: <span>
            <?php if (!empty($plan['suite_number'])) {
              echo  "Suite " . htmlspecialchars($plan['suite_number']) . " ";
            } ?>
            <?php echo htmlspecialchars($plan['street_number'] . ' ' . $plan['street_name']); ?>, <?php echo htmlspecialchars($plan['city'] . ', ' . $plan['province2'] . ', ' . $plan['postcode']); ?></span>
        </h4>
        <h4><?php echo $this->lang->line("Phone Number"); ?>: <span><?php echo htmlspecialchars($plan['phone1']); ?></span>
        </h4>
        <h4><?php echo $this->lang->line("Email"); ?>: <span><?php echo htmlspecialchars($plan['contact_email']); ?></span>
        </h4>
      </div>
      <div class="col-sm-1 nopadding">
        &nbsp;
      </div>
      <div class="col-sm-5 nopadding2">
        <h4><?php if ($plan['status_id'] < 2) { ?><?php echo $this->lang->line("Quote"); ?><?php } else { ?><?php echo $this->lang->line("Policy"); ?><?php } ?> <?php echo $this->lang->line("Number"); ?>: <span><?php echo $plan['policy']; ?></span></h4>
        <h4><?php echo $this->lang->line("Application Date"); ?>: <span><?php echo $plan['apply_date']; ?></span>
        </h4>
        <h4><?php echo $this->lang->line("Effective Date"); ?>: <span><?php echo $plan['effective_date']; ?></span>
        </h4>
        <h4><?php echo $this->lang->line("Expiry Date"); ?>: <span><?php echo $plan['expiry_date']; ?></span>
        </h4>
        <h4><?php echo $this->lang->line("Number of Days"); ?>: <span><?php echo $plan['totaldays']; ?></span>
        </h4>
        <h4><br /><br /></h4>
      </div>
    </div>
    <!-- end policy detail -->
    <div style="display:block;">
      <hr style="margin:3px auto;" />
    </div>
    <!-- Coverage and payment Details-->
    <div class="row" style="margin-top: -15px;">
      <div class="col-sm-6 nopadding">
        <h4><u><?php echo $this->lang->line("Coverage Details"); ?></u></h4>
        <h4><?php echo $this->lang->line("Insurance Plan"); ?>:<br />&nbsp;&nbsp;&nbsp;<span><?php echo $plan_full_name; ?></span></h4>
        <h4><?php echo $this->lang->line("Plan Type"); ?>: <span><?php if ($plan['isfamilyplan'] == 1) {
                                echo $this->lang->line("Family");
                              } else {
                                echo $this->lang->line("Individual");
                              } ?></span>
        </h4>
        <h4><?php echo $this->lang->line("Sum Insured"); ?>: <span>$<?php echo number_format($plan['sum_insured'], 2); ?></span>
        </h4>
        <h4><?php echo $this->lang->line("Deductible"); ?>: <span>$<?php echo number_format($plan['deductible_amount'], 2); ?></span>
        </h4>
        <h4><?php echo $this->lang->line("Beneficiary"); ?>: <span><?php echo htmlspecialchars($plan['beneficiary']); ?></span>
        </h4>
        <h4><?php echo $this->lang->line("Stable pre-existing condition coverage"); ?>: <span><?php echo ($plan['stable_condition'] == 1) ? $this->lang->line("Yes") : $this->lang->line("No"); ?></span>
        </h4>
      </div>
      <div class="col-sm-1 nopadding2">
        &nbsp;
      </div>
      <div class="col-sm-5 nopadding">
        <?php if ($withprice) { ?>
          <h4><u><?php echo $this->lang->line("Payment Details"); ?></u></h4>
          <h4><?php echo $this->lang->line("Total Premium"); ?>: <span>$<?php echo number_format($plan['premium'], 2, '.', ','); ?></span></h4>
          <!-- <h4>Premium: <span>$<?php echo number_format((float)$plan['premium'] - (float)$plan['tax'], 2, '.', ','); ?></span>
          </h4> -->
          <h4><?php echo $this->lang->line("Tax"); ?>: <span>$<?php echo number_format($plan['tax'], 2, '.', ','); ?></span>
          </h4>
          <h4><?php echo $this->lang->line("Payment Date"); ?>: <span><?php echo (($plan['status_id'] >= 2) && isset($payment['added'])) ? date('Y-m-d', strtotime($payment['added'])) : ''; ?></span>
          </h4>
          <h4><?php echo $this->lang->line("Payment Method"); ?>: <span><?php echo (($plan['status_id'] >= 2) && isset($payment['pay_mothed'])) ? $payment['pay_mothed'] : ''; ?></span>
          </h4>
        <?php } ?>
      </div>
    </div>
    <div class="row">
      <div class="col-sm-12 nopm special-note">
        <h4 style="border-bottom:1px solid #777;"><?php echo $this->lang->line("Special Note"); ?></h4>
      </div>
    </div>
    <div class="row">
      <div class="col-sm-12 nopm">
        <p class="small" style="margin-top:2px;">
        <?php echo $this->lang->line("All persons insured are subject to the terms and conditions below."); ?> <br />
        <?php echo $this->lang->line("This document does not constitute the entire insurance policy, and the insured(s) has been advised to read the full policy details of coverage and exclusions"); ?>. <br />
        <?php echo $this->lang->line("The eligibility requirements for the plan applied for are material to the risk for which insurance is sought. If the insured(s) does not meet the eligibility requirements for the plan selected, or if there is any misrepresentation, concealment, or failure to disclose any facts or matters pertaining to the insured(s) that is the subject of the application form, then there shall be no insurance coverage provided"); ?>. <br />
        <?php echo $this->lang->line("The insured(s) is aware that this insurance covers Emergencies (as defined in the policy) and may not cover expenses incurred after the insured(s) is able to travel home for treatment. The insured(s) is aware that Pre-existing Conditions (as defined in the policy) are excluded in some circumstances and that further details are provided in the policy"); ?>.<br />
        <?PHP echo $this->lang->line("IN THE EVENT OF A MEDICAL EMERGENCY OR CLAIM THAT MAY REQUIRE OR RESULT IN HOSPITALIZATION, CALL ONTIME CARE WORLDWIDE INC. IMMEDIATELY"); ?> <br />
        <?php echo $this->lang->line("Toll Free Canada/U.S.A. 1-866-209-5804"); ?><br />
        <?php echo $this->lang->line("Collect call worldwide 905-707-9555"); ?><br />
        <?php echo $this->lang->line("If you notice any errors in the above information or have any questions, please contact JF Insurance Agency Group Inc."); ?><br />
        </p>
      </div>
    </div><br />
    <!--/div-->
    <!-- end p-detail -->
    <!--/div-->
    <!-- x_content -->
    <div class="row">
      <div class="col-sm-6 nopm">
        <p class="small">Ontario:<br />
          15 Wertheim Court, Suite 501,<br />
          Richmond Hill, ON, Canada L4B 3H7<br />
          Phone: 905-707-1512 Or 1-877-832-5541</p>
      </div>
      <!-- <div class="col-sm-6 nopm">
        <p class="small">British Columbia:<br />
          128 - 6061 No. 3 Road<br />
          Richmond, BC, Canada V6Y 282<br />
          Phone: 604-232-0896 Or 1-877-232-0896</p>
      </div> -->
    </div>
    <div class="row">
      <div class="col-sm-12 nopm special-note">
        <p class="small"><?php echo $this->lang->line("Underwritten by Old Republic Insurance Company of Canada and administered by JF Insurance Agency Group Inc."); ?></p>
      </div>
    </div>
  </div><!-- End Container -->
<?php
    }
  }
?>
</body>
</html>
