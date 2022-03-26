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
        <h2><?php if ($plan['status_id'] < 2) { ?>Quote<?php } else { ?>Confirmation<?php } ?> of Insurance</h2>
      </div>
    </div>
    <div class="row" style="margin-top: -15px;">
      <div class="col-sm-12 nopadding">
        <h4><u>Policy Details</u></h4>
      </div>
    </div>
    <div class="row" style="margin-top: -15px;">
      <div class="col-sm-6 nopadding">
        <h4>Policy Holder: <span><?php echo htmlspecialchars($customer['firstname'] . " " . $customer['lastname']); ?></span></h4>
        <h4>Date of Birth: <span><?php echo $customer['birthday']; ?></sapn>
        </h4>
        <h4>Address: <span>
            <?php if (!empty($plan['suite_number'])) {
              echo  "Suite " . htmlspecialchars($plan['suite_number']) . " - ";
            } ?>
            <?php echo htmlspecialchars($plan['street_number'] . ' ' . $plan['street_name']); ?>, <?php echo htmlspecialchars($plan['city'] . ', ' . $plan['province2'] . ', ' . $plan['postcode']); ?></sapn>
        </h4>
        <h4>Phone Number: <span><?php echo htmlspecialchars($plan['phone1']); ?></sapn>
        </h4>
        <h4>Email: <span><?php echo htmlspecialchars($plan['contact_email']); ?></sapn>
        </h4>
      </div>
      <div class="col-sm-1 nopadding">
        &nbsp;
      </div>
      <div class="col-sm-5 nopadding2">
        <h4><?php if ($plan['status_id'] < 2) { ?>Quote<?php } else { ?>Policy<?php } ?> Number: <span><?php echo $plan['policy']; ?></span></h4>
        <h4>Application Date: <span><?php echo $plan['apply_date']; ?></sapn>
        </h4>
        <h4>Effective Date: <span><?php echo $plan['effective_date']; ?></sapn>
        </h4>
        <h4>Expiry Date: <span><?php echo $plan['expiry_date']; ?></sapn>
        </h4>
        <h4>Number of Days: <span><?php echo $plan['totaldays']; ?></sapn>
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
        <h4><u>Coverage Details</u></h4>
        <h4>Insurance Plan:<br />&nbsp;&nbsp;&nbsp;<span><?php echo $plan_full_name; ?></span></h4>
        <h4>Plan Type: <span><?php if ($plan['isfamilyplan'] == 1) {
                                echo "Family";
                              } else {
                                echo "Individual";
                              } ?></sapn>
        </h4>
        <h4>Sum Insured: <span>$<?php echo number_format($plan['sum_insured'], 2); ?></sapn>
        </h4>
        <h4>Deductible: <span>$<?php echo number_format($plan['deductible_amount'], 2); ?></sapn>
        </h4>
        <h4>Beneficiary: <span><?php echo htmlspecialchars($plan['beneficiary']); ?></sapn>
        </h4>
        <h4>Stable Pre-existing Condition Coverage: <span><?php echo ($plan['stable_condition'] == 1) ? 'Yes' : 'No'; ?></sapn>
        </h4>
      </div>
      <div class="col-sm-1 nopadding2">
        &nbsp;
      </div>
      <div class="col-sm-5 nopadding">
        <?php if ($withprice) { ?>
          <h4><u>Payment Details</u></h4>
          <h4>Total Premium: <span>$<?php echo number_format($plan['premium'], 2, '.', ','); ?></span></h4>
          <h4>Premium: <span>$<?php echo number_format((float)$plan['premium'] - (float)$plan['tax'], 2, '.', ','); ?></sapn>
          </h4>
          <h4>Tax: <span>$<?php echo number_format($plan['tax'], 2, '.', ','); ?></sapn>
          </h4>
          <h4>Payment Date: <span><?php echo (($plan['status_id'] >= 2) && isset($payment['added'])) ? date('Y-m-d', strtotime($payment['added'])) : ''; ?></sapn>
          </h4>
          <h4>Payment Method: <span><?php echo (($plan['status_id'] >= 2) && isset($payment['pay_mothed'])) ? $payment['pay_mothed'] : ''; ?></sapn>
          </h4>
        <?php } ?>
      </div>
    </div>
    <div class="row">
      <div class="col-sm-12 nopm special-note">
        <h4 style="border-bottom:1px solid #777;">Special Note</h4>
      </div>
    </div>
    <div class="row">
      <div class="col-sm-12 nopm">
        <p class="small" style="margin-top:2px;"><b>Please retain this confirmation as your receipt.</b><br />
          Please read and understand the enclosed, which fully explains the terms, conditions, limitations, and exclusions that are part of your policy.<br /><br />
          If you have a change in your health, including any change in your medication, between the date of application and the effective date of the policy, you must contact us to ensure that you remain eligible for this insurance.
          Ontime Care Worldwide Inc. must be notified prior to any surgery being performed or within 24 hours of admission to a hospital. Failure to do so, without reasonable cause, will result in the reduction of eligible benefit amounts payable. <br /><br />
          IN THE EVENT OF AN EMERGENCY, CONTACT ONTIME CARE WORLDWIDE INC IMMEDIATELY: <br />
          TOLL FREE CANADA/U.S.A.: 1-888-988-3268 IF UNABLE TO CONTACT US TOLL FREE, PLEASE CALL COLLECT: 905-707-9555.
        </p>
        <p class="small" style="margin-top:2px;"><b>Waiting Period</b> - When coverage is purchased any time after your arrival in Canada:<br />
          a. If you are age 86 or older, in respect of any sickness, you will not be entitled to receive reimbursement for sickness or symptoms which manifested or were contracted or treated within 15 days following the effective date of this policy.<br />
          b. If you are age 85 or under, in respect of any sickness, you will not be entitled to receive reimbursement for sickness or symptoms which manifested or were contracted or treated within 48 hours following the effective date of this policy.<br />
          c. For all ages, if the policy was purchased 30 days or more after arrival in Canada, then in respect of any sickness, you will not be entitled to receive reimbursement for sickness or symptoms which manifested or were contracted or treated within seven days following the effective date of this policy.<br />
          The waiting period may be waived by the Administrator Company under certain conditions prior to purchasing this policy. You must receive written confirmation from the Administrator Company that the waiting period has been waived.  Please refer to the Section IV: Insurance Agreement of the policy for details.<br />
          <br />
          If you notice any errors in the above information or have any questions, please contact JF Insurance Agency Group Inc.
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
      <div class="col-sm-6 nopm">
        <p class="small">British Columbia:<br />
          128 - 6061 No. 3 Road<br />
          Richmond, BC, Canada V6Y 282<br />
          Phone: 604-232-0896 Or 1-877-232-0896</p>
      </div>
    </div>
    <div class="row">
      <div class="col-sm-12 nopm special-note">
        <p class="small">Underwritten by Old Republic Insurance Company of Canada and administered by JF Insurance Agency Group Inc.</p>
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
        <h2><?php if ($plan['status_id'] < 2) { ?>Quote<?php } else { ?>Confirmation<?php } ?> of Insurance</h2>
      </div>
    </div>
    <div class="row" style="margin-top: -15px;">
      <div class="col-sm-12 nopadding">
        <h4><u>Policy Details</u></h4>
      </div>
    </div>
    <div class="row" style="margin-top: -15px;">
      <div class="col-sm-6 nopadding">
        <h4>Policy Holder: <span><?php echo htmlspecialchars($customers[$i]['firstname'] . " " . $customers[$i]['lastname']); ?></span></h4>
        <h4>Date of Birth: <span><?php echo $customers[$i]['birthday']; ?></sapn>
        </h4>
        <h4>Address: <span>
            <?php if (!empty($plan['suite_number'])) {
              echo  "Suite " . htmlspecialchars($plan['suite_number']) . " - ";
            } ?>
            <?php echo htmlspecialchars($plan['street_number'] . ' ' . $plan['street_name']); ?>, <?php echo htmlspecialchars($plan['city'] . ', ' . $plan['province2'] . ', ' . $plan['postcode']); ?></sapn>
        </h4>
        <h4>Phone Number: <span><?php echo htmlspecialchars($plan['phone1']); ?></sapn>
        </h4>
        <h4>Email: <span><?php echo htmlspecialchars($plan['contact_email']); ?></sapn>
        </h4>
      </div>
      <div class="col-sm-1 nopadding">
        &nbsp;
      </div>
      <div class="col-sm-5 nopadding2">
        <h4><?php if ($plan['status_id'] < 2) { ?>Quote<?php } else { ?>Policy<?php } ?> Number: <span><?php echo $plan['policy']; ?></span></h4>
        <h4>Application Date: <span><?php echo $plan['apply_date']; ?></sapn>
        </h4>
        <h4>Effective Date: <span><?php echo $plan['effective_date']; ?></sapn>
        </h4>
        <h4>Expiry Date: <span><?php echo $plan['expiry_date']; ?></sapn>
        </h4>
        <h4>Number of Days: <span><?php echo $plan['totaldays']; ?></sapn>
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
        <h4><u>Coverage Details</u></h4>
        <h4>Insurance Plan:<br />&nbsp;&nbsp;&nbsp;<span><?php echo $plan_full_name; ?></span></h4>
        <h4>Plan Type: <span><?php if ($plan['isfamilyplan'] == 1) {
                                echo "Family";
                              } else {
                                echo "Individual";
                              } ?></sapn>
        </h4>
        <h4>Sum Insured: <span>$<?php echo number_format($plan['sum_insured'], 2); ?></sapn>
        </h4>
        <h4>Deductible: <span>$<?php echo number_format($plan['deductible_amount'], 2); ?></sapn>
        </h4>
        <h4>Beneficiary: <span><?php echo htmlspecialchars($plan['beneficiary']); ?></sapn>
        </h4>
        <h4>Stable Pre-existing Condition Coverage: <span><?php echo ($plan['stable_condition'] == 1) ? 'Yes' : 'No'; ?></sapn>
        </h4>
      </div>
      <div class="col-sm-1 nopadding2">
        &nbsp;
      </div>
      <div class="col-sm-5 nopadding">
        <?php if ($withprice) { ?>
          <h4><u>Payment Details</u></h4>
          <h4>Total Premium: <span>$<?php echo number_format($plan['premium'], 2, '.', ','); ?></span></h4>
          <h4>Premium: <span>$<?php echo number_format((float)$plan['premium'] - (float)$plan['tax'], 2, '.', ','); ?></sapn>
          </h4>
          <h4>Tax: <span>$<?php echo number_format($plan['tax'], 2, '.', ','); ?></sapn>
          </h4>
          <h4>Payment Date: <span><?php echo (($plan['status_id'] >= 2) && isset($payment['added'])) ? date('Y-m-d', strtotime($payment['added'])) : ''; ?></sapn>
          </h4>
          <h4>Payment Method: <span><?php echo (($plan['status_id'] >= 2) && isset($payment['pay_mothed'])) ? $payment['pay_mothed'] : ''; ?></sapn>
          </h4>
        <?php } ?>
      </div>
    </div>
    <div class="row">
      <div class="col-sm-12 nopm special-note">
        <h4 style="border-bottom:1px solid #777;">Special Note</h4>
      </div>
    </div>
    <div class="row">
      <div class="col-sm-12 nopm">
        <p class="small" style="margin-top:2px;"><b>Please retain this confirmation as your receipt.</b><br />
          Please read and understand the enclosed, which fully explains the terms, conditions, limitations, and exclusions that are part of your policy.<br /><br />
          If you have a change in your health, including any change in your medication, between the date of application and the effective date of the policy, you must contact us to ensure that you remain eligible for this insurance.
          Ontime Care Worldwide Inc. must be notified prior to any surgery being performed or within 24 hours of admission to a hospital. Failure to do so, without reasonable cause, will result in the reduction of eligible benefit amounts payable. <br /><br />
          IN THE EVENT OF AN EMERGENCY, CONTACT ONTIME CARE WORLDWIDE INC IMMEDIATELY: <br />
          TOLL FREE CANADA/U.S.A.: 1-888-988-3268 IF UNABLE TO CONTACT US TOLL FREE, PLEASE CALL COLLECT: 905-707-9555.
        </p>
        <p class="small" style="margin-top:2px;"><b>Waiting Period</b> - When coverage is purchased any time after your arrival in Canada:<br />
          a. If you are age 86 or older, in respect of any sickness, you will not be entitled to receive reimbursement for sickness or symptoms which manifested or were contracted or treated within 15 days following the effective date of this policy.<br />
          b. If you are age 85 or under, in respect of any sickness, you will not be entitled to receive reimbursement for sickness or symptoms which manifested or were contracted or treated within 48 hours following the effective date of this policy.<br />
          c. For all ages, if the policy was purchased 30 days or more after arrival in Canada, then in respect of any sickness, you will not be entitled to receive reimbursement for sickness or symptoms which manifested or were contracted or treated within seven days following the effective date of this policy.<br />
          The waiting period may be waived by the Administrator Company under certain conditions prior to purchasing this policy. You must receive written confirmation from the Administrator Company that the waiting period has been waived.  Please refer to the Section IV: Insurance Agreement of the policy for details.<br />
          <br />
          If you notice any errors in the above information or have any questions, please contact JF Insurance Agency Group Inc.
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
      <div class="col-sm-6 nopm">
        <p class="small">British Columbia:<br />
          128 - 6061 No. 3 Road<br />
          Richmond, BC, Canada V6Y 282<br />
          Phone: 604-232-0896 Or 1-877-232-0896</p>
      </div>
    </div>
    <div class="row">
      <div class="col-sm-12 nopm special-note">
        <p class="small">Underwritten by Old Republic Insurance Company of Canada and administered by JF Insurance Agency Group Inc.</p>
      </div>
    </div>
  </div><!-- End Container -->
<?php
    }
  }
?>
</body>
</html>
