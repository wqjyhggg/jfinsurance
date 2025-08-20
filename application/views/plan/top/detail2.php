<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
$usepsi = false;
$hideForFrench = false;
$Agree = $this->lang->line("Agree");
if ($Agree != "Agree") {
  $hideForFrench = true;
}
?>
<style>
.container {
    background-color: #f0f0f0; /* Light gray background */
    width: 100%;
    text-align: center;
}
.main {
    inline-size: 100%;
    margin-inline: auto;
    max-inline-size: 1440px;
}
.title-left {
    font-size: 1.25rem;
    font-weight: 500;
    letter-spacing: .0125em;
    overflow: hidden;
    margin-top: 2.5rem;
    margin-bottom: 1rem;
    padding: .5rem 2rem .5rem 2rem;
    text-overflow: ellipsis;
    text-transform: none;
    white-space: nowrap;
    word-break: normal;
    word-wrap: break-word;
    border: 1px solid #ccc; /* Light gray border */
    border-radius: .25rem; /* Rounded corners */
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); /* Subtle shadow */
    background: rgba(222, 226, 222, .8);
}
.info {
    display: flex;
    flex-wrap: wrap;
    margin: -10px;
    padding: 0 3rem;
}
.info-card {
    flex: 1 1 25%;
    margin: .5rem 0 0 0;
    box-sizing: border-box;
    padding: 0 .5rem;
    display: flex;
    align-items: left;
    justify-content: left;
}
.info-text {
    font-weight: 600;
}
.card-info {
    flex: 1 1 100%;
    margin: .5rem 0 0 0;
    box-sizing: border-box;
    display: flex;
    align-items: left;
    justify-content: left;
}
.card-sub {
    flex: 0 1 auto;
    min-width: 10rem;
    text-align: right;
    margin-right: 2rem;
}
.card-fit {
    flex: 0 1 auto;
    text-align: left;
}
@media (max-width: 600px) {
    .info-card {
        flex: 1 1 100%; /* 100% width on mobile */
    }
}
</style>
<div class="container" style="padding:0;">
  <div class="hlogo">
    <img class="img-responsive" src="/image/logo.png" alt="JF Insurance">
  </div>
</div>
<!-- Plan page content -->
<div class="main">
  <div class="title-left">
    Policy Detail
    <?php if (($plan['status_id'] == 2) || ($plan['status_id'] == 3)) { ?>
      <span style='color: red;'><?php echo $this->lang->line("Please contact your agent to get your policy details and the insurance package."); ?></span>
    <?php } ?> 
    <?php if (!empty($error_message)) { ?><div style="color: red;""><?php echo $error_message; ?></div><?php } ?>
    <?php if (!empty($errormsg)) { ?><div style="color: red;""><?php echo $errormsg; ?></div><?php } ?>
  </div>
  <div class="info">
    <div class="info-card" style="flex: 1 1 100%;">
      <div class="info-text"><?php echo $plan_full_name; ?></div>
    </div>
    <div class="info-card">
      <span class="info-lable"><?php echo $this->lang->line("Policy"); ?>:<span>
      <span class="info-text"><?php echo $plan['product_short']; ?></span>
    </div>
    <div class="info-card">
      <span class="info-lable"><?php if ($plan['status_id'] < 2) { echo "Quote" } else { echo "Policy"; } ?> Number:<span>
      <span class="info-text"><?php echo $plan['policy']; ?></span>
    </div>
    <div class="info-card">
      <span class="info-lable">Status:<span>
      <span class="info-text"><?php echo $status_list[$plan['status_id']]['name']; ?></span>
    </div>
    <div class="info-card">&nbsp;</div>
  </div>
  <div class="title-left">
    Travel Dates
  </div>
  <div class="info">
    <div class="info-card">
      <span class="info-lable">Apply Date:<span>
      <span class="info-text"><?php echo $plan['apply_date']; ?></span>
    </div>
    <div class="info-card">
      <span class="info-lable">Arrival Date:<span>
      <span class="info-text"><?php echo $plan['arrival_date']; ?></span>
    </div>
    <div class="info-card">
      <span class="info-lable">Effective Date:<span>
      <span class="info-text"><?php echo $plan['effective_date']; ?></span>
    </div>
    <div class="info-card">
      <span class="info-lable">Expiry Date:<span>
      <span class="info-text"><?php echo $plan['expiry_date']; ?></span>
    </div>
    <div class="info-card">
      <span class="info-lable">Days:<span>
      <span class="info-text"><?php echo $plan['totaldays']; ?></span>
    </div>
    <div class="info-card">
      <span class="info-lable">Daily Rate:<span>
      <span class="info-text"><?php echo $plan['dailyrate']; ?></span>
    </div>
    <div class="info-card">
      <span class="info-lable">Age:<span>
      <span class="info-text"><?php echo $plan['totalyears']; ?></span>
    </div>
    <div class="info-card">
      <span class="info-lable">Premiums:<span>
      <span class="info-text">$<?php echo number_format($plan['premium'], 2); ?></span>
    </div>
  </div>
  <div class="title-left">
    Insurable Options
  </div>
  <div class="info">
    <div class="info-card">
      <span class="info-lable">Beneficiary:</span>
      <span class="info-text"><?php echo $plan['beneficiary']; ?></span>
    </div>
    <div class="info-card">
      <span class="info-lable">Sum Insured:<span>
      <span class="info-text">$<?php echo number_format($plan['sum_insured'], 2); ?></span>
    </div>
    <div class="info-card" stlye="flex: 1 1 50%">
      <?php 
        if ($plan['stable_condition']) {
          echo (($plan['stable_condition'] == 1) ? 'Including' : 'Excluding')." stable pre-existing condition coverage";
      } ?>
    </div>
  </div>
  <div class="title-left">
    Insurable Members
  </div>
  <div class="info">
    <div class="infor-card" style="flex: 1 1 100%;"><b>Student Information</b></div>
    <div class="info-card">
      <span class="info-lable">First Name:<span>
      <span class="info-text"><?php echo $customer['firstname']; ?></span>
    </div>
    <div class="info-card">
      <span class="info-lable">Last Name:<span>
      <span class="info-text"><?php echo $customer['lastname']; ?></span>
    </div>
    <div class="info-card">
      <span class="info-lable">Birthday:<span>
      <span class="info-text"><?php echo $customer['birthday']; ?></span>
    </div>
    <div class="info-card">
      <span class="info-lable">Gender:<span>
      <span class="info-text"><?php echo $customer['gender']; ?></span>
    </div>
    <?php if ($plan['isfamilyplan']) { ?>
      Family/Group Member Information
      <div>Student Information</div>
      <?php for ($i = 0; $i < 25; $i++) { ?>
      <?php  if (empty($customers[$i]['lastname']) && empty($customers[$i]['firstname'])) break; ?>
      <div class="info-card">
        <span class="info-lable">First Name:<span>
        <span class="info-text"><?php echo $customers[$i]['firstname']; ?></span>
      </div>
      <div class="info-card">
        <span class="info-lable">Last Name:<span>
        <span class="info-text"><?php echo $customers[$i]['lastname']; ?></span>
      </div>
      <div class="info-card">
        <span class="info-lable">Birthday:<span>
        <span class="info-text"><?php echo $customers[$i]['birthday']; ?></span>
      </div>
      <div class="info-card">
        <span class="info-lable">Gender:<span>
        <span class="info-text"><?php echo $customers[$i]['gender']; ?></span>
      </div>
      <?php } ?>
    <?php } ?>
  </div>
  <div class="title-left">
    Address
  </div>
  <div class="info">
    <div class="info-card">
      <span class="info-lable">Street Number:<span>
      <span class="info-text"><?php echo $plan['street_number']; ?></span>
    </div>
    <div class="info-card">
      <span class="info-lable">Street Name:<span>
      <span class="info-text"><?php echo $plan['street_name']; ?></span>
    </div>
    <div class="info-card">
      <span class="info-lable">Suite No:<span>
      <span class="info-text"><?php echo $plan['suite_number']; ?></span>
    </div>
    <div class="info-card">
      <span class="info-lable">City:<span>
      <span class="info-text"><?php echo $plan['city']; ?></span>
    </div>
    <div class="info-card">
      <span class="info-lable">Province:<span>
      <span class="info-text"><?php echo $plan['province2']; ?></span>
    </div>
    <div class="info-card">
      <span class="info-lable">Country:<span>
      <span class="info-text"><?php echo $plan['country2']; ?></span>
    </div>
    <div class="info-card">
      <span class="info-lable">PostCode:<span>
      <span class="info-text"><?php echo $plan['postcode']; ?></span>
    </div>
    <div class="info-card">
      <span class="info-lable">Phone:<span>
      <span class="info-text"><?php echo $plan['phone1']; ?></span>
    </div>
  </div>
  <div class="title-left">
    Contact Information
  </div>
  <div class="info">
    <div class="info-card">
      <span class="info-lable">Email:<span>
      <span class="info-text"><?php echo $plan['contact_email']; ?></span>
    </div>
    <div class="info-card">
      <span class="info-lable">Contact Phone:<span>
      <span class="info-text"><?php echo $plan['contact_phone']; ?></span>
    </div>
    <div class="info-card">
      <span class="info-lable">Country of Origin:<span>
      <span class="info-text"><?php echo $plan['residence']; ?></span>
    </div>
    <div class="info-card">&nbsp;</div>
  </div>
  <?php if (!empty($payment_total)) { ?>
  <div class="title-left">
    <?php echo $this->lang->line("Pay By Credit Card"); ?>
  </div>
  <div class="info">
    <div class="pay-card">
      <form action='<?php echo ($usepsi ? $psi_active_url : $active_url); ?>' method='POST'>
      <?php if ($usepsi) { ?>
        <input type='hidden' name='CustomerRefNo' value='<?php echo $plan['plan_id']; ?>'>
        <input type='hidden' name='Bname' value='<?php echo $plan['plan_id']; ?>'>
        <input type='hidden' name='StoreKey' value='<?php echo $StoreKey; ?>'>
        <input type='hidden' name='SubTotal' value='<?php echo $payment_total; ?>'>
        <input type='hidden' name='ResponseFormat' value='HTML'>
        <input type='hidden' name='ThanksURL' value='<?php echo $psi_thanks_url;?>'>
        <input type='hidden' name='NoThanksURL' value='<?php echo $psi_nothanks_url;?>'>
        <input type='hidden' name='CustomerIP' value='<?php echo $CustomerIP;?>'>
        <input type='hidden' name='PaymentType' value=''>
        <input type='hidden' name='CardAction' value='0'>
      <?php } else { ?>
        <input type='hidden' name='<?php echo $csrf['name']; ?>' value='<?php echo $csrf['value']; ?>'>
        <input type='hidden' name='plan_id' value='<?php echo $plan['plan_id']; ?>'>
        <input type='hidden' name='play_type' value='Credit Card'>
        <input type='hidden' name='sekey' value='<?php echo $sekey; ?>'>
        <input type='hidden' name='premium' value='<?php echo $payment_total; ?>'>
      <?php } ?>
      <div class="card-info">
        <div class="card-sub" style="margin-bottom:0;"><?php echo $this->lang->line("Card Number"); ?>:</div>
        <input class="card-fit" type='text' name='<?php echo ($usepsi ? "CardNumber" : "card_number"); ?>' value='' placeholder="Card Number">
      </div>
      <?php if (! $usepsi) { ?>
      <div class="card-info">
        <div class="card-sub" style="margin-bottom:0;"><?php echo $this->lang->line("Card Holder's Name"); ?>:</div>
        <input class="card-fit" type='text' name='<?php echo ($usepsi ? "CardNumber" : "card_number"); ?>' value='' placeholder="Card Holder's Name">
      </div>
      <?php } ?>
      <div class="card-info">
        <div class="card-sub"><?php echo $this->lang->line("Expiry Month"); ?>:</div>
        <select name='<?php echo ($usepsi ? "CardExpMonth" : "expiry_month"); ?>' class="card-fit">
          <option value='01' <?php echo (($expiry_month == 1) ? 'selected' : ''); ?>>01</option>
          <option value='02' <?php echo (($expiry_month == 2) ? 'selected' : ''); ?>>02</option>
          <option value='03' <?php echo (($expiry_month == 3) ? 'selected' : ''); ?>>03</option>
          <option value='04' <?php echo (($expiry_month == 4) ? 'selected' : ''); ?>>04</option>
          <option value='05' <?php echo (($expiry_month == 5) ? 'selected' : ''); ?>>05</option>
          <option value='06' <?php echo (($expiry_month == 6) ? 'selected' : ''); ?>>06</option>
          <option value='07' <?php echo (($expiry_month == 7) ? 'selected' : ''); ?>>07</option>
          <option value='08' <?php echo (($expiry_month == 8) ? 'selected' : ''); ?>>08</option>
          <option value='09' <?php echo (($expiry_month == 9) ? 'selected' : ''); ?>>09</option>
          <option value='10' <?php echo (($expiry_month == 10) ? 'selected' : ''); ?>>10</option>
          <option value='11' <?php echo (($expiry_month == 11) ? 'selected' : ''); ?>>11</option>
          <option value='12' <?php echo (($expiry_month == 12) ? 'selected' : ''); ?>>12</option>
        </select> 
      </div>
      <div class="card-info">
        <div class="card-sub"><?php echo $this->lang->line("Expiry Year"); ?>:</div>
        <select name='<?php echo ($usepsi ? "CardExpYear" : "expiry_year"); ?>' class="cart-fit">
          <option value='<?php echo date('y'); ?>'> <?php echo date('y'); ?> </option>
          <option value='<?php echo date('y',strtotime('+1 years')); ?>'> <?php echo date('y',strtotime('+1 years')); ?> </option>
          <option value='<?php echo date('y',strtotime('+2 years')); ?>'> <?php echo date('y',strtotime('+2 years')); ?> </option>
          <option value='<?php echo date('y',strtotime('+3 years')); ?>'> <?php echo date('y',strtotime('+3 years')); ?> </option>
          <option value='<?php echo date('y',strtotime('+4 years')); ?>'> <?php echo date('y',strtotime('+4 years')); ?> </option>
          <option value='<?php echo date('y',strtotime('+5 years')); ?>'> <?php echo date('y',strtotime('+5 years')); ?> </option>
          <option value='<?php echo date('y',strtotime('+6 years')); ?>'> <?php echo date('y',strtotime('+6 years')); ?> </option>
          <option value='<?php echo date('y',strtotime('+7 years')); ?>'> <?php echo date('y',strtotime('+7 years')); ?> </option>
          <option value='<?php echo date('y',strtotime('+8 years')); ?>'> <?php echo date('y',strtotime('+8 years')); ?> </option>
          <option value='<?php echo date('y',strtotime('+9 years')); ?>'> <?php echo date('y',strtotime('+9 years')); ?> </option>
          <option value='<?php echo date('y',strtotime('+10 years')); ?>'> <?php echo date('y',strtotime('+10 years')); ?> </option>
          <option value='<?php echo date('y',strtotime('+11 years')); ?>'> <?php echo date('y',strtotime('+11 years')); ?> </option>
        </select>
      </div>
      <div class="card-info">
        <div class="card-sub"><?php echo $this->lang->line("Card CVV"); ?>:</div>
        <input type='text' name='<?php echo ($usepsi ? "CardIDNumber" : "card_cvv"); ?>' value='' class="card-fit">
      </div>
      <div class="card-info" style="margin-top: 1.5rem;">
        <div style="flex: 1 1 100%; text-align: center;"><?php echo $this->lang->line("Amount"); ?>: <b>$<?php echo number_format($payment_total, 2, '.', ','); ?></b></div>
        <input type='submit' name='submit' value='<?php echo $this->lang->line("Pay Now"); ?>' />
      </div>
    </div>
  </div>
  <?php } ?>
  <div class="container">
    © <?php echo date("Y"); ?> Made By JF Insurance
  </div>
</div>
