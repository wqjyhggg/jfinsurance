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
      </div>
      <div class="col-sm-1 text-left">
        &nbsp;
      </div>
      <div class="col-sm-5 text-left">
        <h2><?php if ($plan['status_id'] < 2) { ?><?php echo $this->lang->line("Quote"); ?><?php } else { ?><?php echo $this->lang->line("Confirmation"); ?><?php } ?> <?php echo $this->lang->line("of Insurance"); ?></h2>
      </div>
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
              echo  "Suite " . htmlspecialchars($plan['suite_number']) . " - ";
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
				<?php if ($withprice) { ?>
					<?php if (!empty($plan['monthlypay']) && !empty($monthly_record)) { ?>
					<h4><u><?php echo $this->lang->line("Payment Schedule"); ?></u></h4>
						<?php foreach($monthly_record as $rc) { ?>
							<?php if (($rc["paid"]>=0) && (($plan["status_id"] == 2)||($plan["status_id"] == 3))) { ?>
								<h4><?php echo $rc["pay_date"]." : ".$rc["amount"]?></h4>
							<?php } ?>
						<?php } ?>
					<?php } ?>
				<?php } ?>
	    </div>
      <div class="col-sm-1 nopadding2">
        &nbsp;
      </div>
      <div class="col-sm-5 nopadding">
        <?php if ($withprice) { ?>
					<?php if (!empty($plan['monthlypay']) && !empty($monthly_data)) { ?>
					<h4><u><?php echo $this->lang->line("Payment Details"); ?></u></h4>
          <h4><?php echo $this->lang->line("Total Premium"); ?>: <span>$<?php echo number_format($plan['premium'], 2, '.', ','); ?></span></h4>
          <h4><?php echo $this->lang->line("Monthly Plan Fee"); ?>: <span>$<?php echo number_format($monthly_data['admin_fee'], 2, '.', ','); ?></span></h4>
          <h4><?php echo $this->lang->line("Paid Premium"); ?>: <span>$<?php echo number_format($monthly_data['paid_premium'], 2, '.', ','); ?></span></h4>
          <h4><?php echo $this->lang->line("Total Charged"); ?>: <span>$<?php echo number_format($monthly_data['total_paid'], 2, '.', ','); ?></span></h4>
          <h4><?php echo $this->lang->line("Outstanding Premium"); ?>: <span>$<?php echo number_format($monthly_data['premium'] - $monthly_data['paid_premium'], 2, '.', ','); ?></span></h4>
          <h4><?php echo $this->lang->line("Tax"); ?>: <span>$<?php echo number_format($plan['tax'], 2, '.', ','); ?></span></h4>
          <h4><?php echo $this->lang->line("Init Payment Date"); ?>: <span><?php echo ($plan['status_id'] >= 2) ? $monthly_data['init_pay_date'] : ''; ?></span></h4>
          <h4><?php echo $this->lang->line("Last Payment Date"); ?>: <span><?php echo ($plan['status_id'] >= 2) ? $monthly_data['last_pay_date'] : ''; ?></span></h4>
          <h4><?php echo $this->lang->line("Payment Method"); ?>: <span><?php echo (($plan['status_id'] >= 2) && isset($payment['pay_mothed'])) ? $payment['pay_mothed'] : ''; ?></span></h4>
          <h4><?php echo $this->lang->line("Recurring Payment"); ?>: <span>$<?php echo number_format($monthly_data['monthly_pay'], 2, '.', ','); ?></span></h4>
          <h4><span><?php echo $monthly_data['recurrent_times']; ?> <?php echo $this->lang->line("recurring monthly payments will begin on the Effective Date of the policy"); ?></span></h4>
					<?php } else { ?>
          <h4><u><?php echo $this->lang->line("Payment Details"); ?></u></h4>
          <h4><?php echo $this->lang->line("Total Premium"); ?>: <span>$<?php echo number_format($plan['premium'], 2, '.', ','); ?></span></h4>
          <h4><?php echo $this->lang->line("Tax"); ?>: <span>$<?php echo number_format($plan['tax'], 2, '.', ','); ?></span></h4>
          <h4><?php echo $this->lang->line("Payment Date"); ?>: <span><?php echo (($plan['status_id'] >= 2) && isset($payment['added'])) ? date('Y-m-d', strtotime($payment['added'])) : ''; ?></span></h4>
          <h4><?php echo $this->lang->line("Payment Method"); ?>: <span><?php echo (($plan['status_id'] >= 2) && isset($payment['pay_mothed'])) ? $payment['pay_mothed'] : ''; ?></span></h4>
					<?php } ?>
        <?php } ?>
      </div>
    </div>
		<pagebreak>
    <div class="row" style="padding-top: 60px;">
      <div class="col-sm-12 nopm special-note">
        <h4 style="border-bottom:1px solid #777;"><?php echo $this->lang->line("Special Note"); ?></h4>
      </div>
    </div>
    <div class="row">
      <div class="col-sm-12 nopm">
        <p class="small" style="margin-top:2px;"><b><?php echo $this->lang->line("Please retain this confirmation as your receipt"); ?>.</b><br />
          <?php echo $this->lang->line("Please read and understand the enclosed, which fully explains the terms, conditions, limitations and exclusions that are part of your policy"); ?>.<br /><br />
					<?php if (!empty($plan['monthlypay']) && !empty($monthly_data)) { ?>
					<p class="small" style="margin-top:2px;"><b>Monthly Payment Option</b><br />
					At the time of purchase, the applicant must pay a one-time $<?php echo $monthly_data["admin_fee"]; ?> fee and two months of premium. 
					These amounts are nonrefundable without a visa rejection letter (unless it was cancelled within 10 days of purchase and provided coverage has not yet begun).<br />
					Ten (10) recurring monthly credit card payments will be charged each month starting on the effective date of the policy. <br />
					If a monthly payment is not received, an email notification will be sent immediately to the email provided on your application and to your agent/broker. You will have 30 days from the email notice to resolve the missed payment. If the missed payment is not collected within 30 days, the monthly payment plan option will be cancelled, and the policy will terminate on the premium paid-to-date.<br /><br />
					<?php } ?>
          <?php echo $this->lang->line("If you have a change in your health, including any change in your medication, between the date of application and the effective date of the policy, you must contact us to ensure that you remain eligible for this insurance. Ontime Care Worldwide Inc. must be notified prior to any surgery being performed or within 24 hours of admission to a hospital. Failure to do so, without reasonable cause, will result in the reduction of eligible benefit amounts payable"); ?>. <br /><br />
          <?PHP echo $this->lang->line("IN THE EVENT OF AN EMERGENCY, CONTACT ONTIME CARE WORLDWIDE INC IMMEDIATELY"); ?>: <br />
          <?PHP echo $this->lang->line("TOLL FREE CANADA/U.S.A.: 1-888-988-3268 IF UNABLE TO CONTACT US TOLL FREE, PLEASE CALL COLLECT: 905-707-9555"); ?>.
        </p>
        <p class="small" style="margin-top:2px;"><b><?php echo $this->lang->line("Waiting Period"); ?></b> - When coverage takes effect after your arrival in Canada, the following waiting periods apply:<br />
          a. If you are <U>age 86 or older</U>, then in respect of any sickness, you will not be entitled to receive reimbursement for sickness or symptoms which manifested or were contracted or treated within <U>15 days</U> following the effective date of this policy.<br />
          b. If <U>age 85 or under</U> and coverage takes effect <U>within 30 days after arrival in Canada</U>, then in respect of any sickness, you will not be entitled to receive reimbursement for sickness or symptoms which manifested or were contracted or treated within 48 hours of the effective date of this policy.<br />
          c. If <U>age 85 or under</U> and coverage takes effect <U>more than 30 days after your arrival in Canada</U>, then in respect of any sickness, you will not be entitled to receive reimbursement for sickness or symptoms which manifested or were contracted or treated within <U>7 days</U> of the effective date of this policy.<br />
					<br />
          <U>The waiting period may be waived by the Administrator Company under certain conditions prior to purchasing this policy</U>. You must receive written confirmation from the Administrator Company that the waiting period has been waived. Please refer to the Section IV: Insurance Agreement of the policy for details.<br />
          <br />
          <?php echo $this->lang->line("If you notice any errors in the above information or have any questions, please contact JF Insurance Agency Group Inc."); ?>
        </p>
      </div>
    </div>
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
      </div>
      <div class="col-sm-1 text-left">
        &nbsp;
      </div>
      <div class="col-sm-5 text-left">
        <h2><?php if ($plan['status_id'] < 2) { ?><?php echo $this->lang->line("Quote"); ?><?php } else { ?><?php echo $this->lang->line("Confirmation"); ?><?php } ?> <?php echo $this->lang->line("of Insurance"); ?></h2>
      </div>
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
              echo  "Suite " . htmlspecialchars($plan['suite_number']) . " - ";
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
        <h4><?php if ($plan['status_id'] < 2) { ?><?php echo $this->lang->line("Quote"); ?><?php } else { ?><?php echo $this->lang->line("Policy"); ?><?php } ?> Number: <span><?php echo $plan['policy']; ?></span></h4>
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
					<?php if (!empty($plan['monthlypay']) && !empty($monthly_data)) { ?>
          <h4><u><?php echo $this->lang->line("Payment Details"); ?></u></h4>
          <h4><?php echo $this->lang->line("Total Premium"); ?>: <span>$<?php echo number_format($plan['premium'], 2, '.', ','); ?></span></h4>
          <h4><?php echo $this->lang->line("Monthly Plan Fee"); ?>: <span>$<?php echo number_format($monthly_data['admin_fee'], 2, '.', ','); ?></span></h4>
          <h4><?php echo $this->lang->line("Paid Premium"); ?>: <span>$<?php echo number_format($monthly_data['paid_premium'], 2, '.', ','); ?></span></h4>
          <h4><?php echo $this->lang->line("Total Charged"); ?>: <span>$<?php echo number_format($monthly_data['total_paid'], 2, '.', ','); ?></span></h4>
          <h4><?php echo $this->lang->line("Outstanding Premium"); ?>: <span>$<?php echo number_format($monthly_data['premium'] - $monthly_data['paid_premium'], 2, '.', ','); ?></span></h4>
          <h4><?php echo $this->lang->line("Tax"); ?>: <span>$<?php echo number_format($plan['tax'], 2, '.', ','); ?></span></h4>
          <h4><?php echo $this->lang->line("Init Payment Date"); ?>: <span><?php echo ($plan['status_id'] >= 2) ? $monthly_data['init_pay_date'] : ''; ?></span></h4>
          <h4><?php echo $this->lang->line("Last Payment Date"); ?>: <span><?php echo ($plan['status_id'] >= 2) ? $monthly_data['last_pay_date'] : ''; ?></span></h4>
          <h4><?php echo $this->lang->line("Payment Method"); ?>: <span><?php echo (($plan['status_id'] >= 2) && isset($payment['pay_mothed'])) ? $payment['pay_mothed'] : ''; ?></span></h4>
          <h4><?php echo $this->lang->line("Recurring Payment"); ?>: <span>$<?php echo number_format($monthly_data['monthly_pay'], 2, '.', ','); ?></span></h4>
          <h4><span><?php echo $monthly_data['recurrent_times']; ?> <?php echo $this->lang->line("recurring monthly payments will begin on the Effective Date of the policy"); ?></span></h4>
					<?php } else { ?>
          <h4><u><?php echo $this->lang->line("Payment Details"); ?></u></h4>
          <h4><?php echo $this->lang->line("Total Premium"); ?>: <span>$<?php echo number_format($plan['premium'], 2, '.', ','); ?></span></h4>
          <h4><?php echo $this->lang->line("Tax"); ?>: <span>$<?php echo number_format($plan['tax'], 2, '.', ','); ?></span></h4>
          <h4><?php echo $this->lang->line("Payment Date"); ?>: <span><?php echo (($plan['status_id'] >= 2) && isset($payment['added'])) ? date('Y-m-d', strtotime($payment['added'])) : ''; ?></span></h4>
          <h4><?php echo $this->lang->line("Payment Method"); ?>: <span><?php echo (($plan['status_id'] >= 2) && isset($payment['pay_mothed'])) ? $payment['pay_mothed'] : ''; ?></span></h4>
					<?php } ?>
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
        <p class="small" style="margin-top:2px;"><b><?php echo $this->lang->line("Please retain this confirmation as your receipt"); ?>.</b><br />
          <?php echo $this->lang->line("Please read and understand the enclosed, which fully explains the terms, conditions, limitations and exclusions that are part of your policy"); ?>.<br /><br />
          <?php echo $this->lang->line("If you have a change in your health, including any change in your medication, between the date of application and the effective date of the policy, you must contact us to ensure that you remain eligible for this insurance. Ontime Care Worldwide Inc. must be notified prior to any surgery being performed or within 24 hours of admission to a hospital. Failure to do so, without reasonable cause, will result in the reduction of eligible benefit amounts payable"); ?>. <br /><br />
          <?PHP echo $this->lang->line("IN THE EVENT OF AN EMERGENCY, CONTACT ONTIME CARE WORLDWIDE INC IMMEDIATELY"); ?>: <br />
          <?PHP echo $this->lang->line("TOLL FREE CANADA/U.S.A.: 1-888-988-3268 IF UNABLE TO CONTACT US TOLL FREE, PLEASE CALL COLLECT: 905-707-9555"); ?>.
        </p>
        <p class="small" style="margin-top:2px;"><b><?php echo $this->lang->line("Waiting Period"); ?></b> - When coverage takes effect after your arrival in Canada, the following waiting periods apply:<br />
          a. If you are <U>age 86 or older</U>, then in respect of any sickness, you will not be entitled to receive reimbursement for sickness or symptoms which manifested or were contracted or treated within 15 days following the effective date of this policy.<br />
          b. If <U>age 85 or under</U> and coverage takes effect <U>within 30 days after arrival in Canada</U>, then in respect of any sickness, you will not be entitled to receive reimbursement for sickness or symptoms which manifested or were contracted or treated within 48 hours of the effective date of this policy.<br />
          c. If <U>age 85 or under</U> and coverage takes effect <U>more than 30 days after your arrival in Canada</U>, then in respect of any sickness, you will not be entitled to receive reimbursement for sickness or symptoms which manifested or were contracted or treated within <U>7 days</U> of the effective date of this policy.<br />
          <U>The waiting period may be waived by the Administrator Company under certain conditions prior to purchasing this policy</U>. You must receive written confirmation from the Administrator Company that the waiting period has been waived. Please refer to the Section IV: Insurance Agreement of the policy for details.<br />
          <br />
          <?php echo $this->lang->line("If you notice any errors in the above information or have any questions, please contact JF Insurance Agency Group Inc."); ?>
        </p>
      </div>
    </div>
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

