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
    padding: .5rem 1rem;
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
}
.info-card {
    flex: 1 1 25%;
    margin: 10px;
    box-sizing: border-box;
    padding: 20px; /* Add some padding */
    display: flex;
    align-items: left;
    justify-content: left;
    min-height: 100px;
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
    <?php echo $this->lang->line("Policy Detail"); ?>
    <?php if (($plan['status_id'] == 2) || ($plan['status_id'] == 3)) { ?>
      <span style='color: red;'><?php echo $this->lang->line("Please contact your agent to get your policy details and the insurance package."); ?></span>
    <?php } ?>
  </div>
  <div class="info">
    <div class="info-card">
      <span class="info-lable"><?php echo $this->lang->line("Policy"); ?>:<span>
      <span class="info-text"><?php echo $plan['product_short']; ?></span>
    </div>
    <div class="info-card">
      <span class="info-lable">Quote Number:<span>
      <span class="info-text"><?php echo $plan['policy']; ?></span>
    </div>
    <div class="info-card">
      <span class="info-lable">Status:<span>
      <span class="info-text"><?php $status_list[$plan['status_id']]['name']; ?>]; ?></span>
    </div>
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
  </div>
  <div class="info">
    <div class="info-card">
      <span class="info-lable"><?php echo $this->lang->line("Policy"); ?>:<span>
      <span class="info-text"><?php echo $plan['product_short']; ?></span>
    </div>
  </div>
  <div class="info">
    <div class="info-card">
      <span class="info-lable"><?php echo $this->lang->line("Policy"); ?>:<span>
      <span class="info-text"><?php echo $plan['product_short']; ?></span>
    </div>
  </div>
					<div class="x_title">
						<h2 class="col-xs-12 col-sm-12 col-md-12" style="width: auto;">
							<?php echo $this->lang->line("Review Policy Detail"); ?>
							<span><b>[ <?php echo $status_list[$plan['status_id']]['name']; ?> ]</b></span>
						</h2>
						<div class="clearfix"></div>
					</div>
					<div class="x_content">
						<?php if (!empty($error_message)) { ?>
						<div class="alert-error" style="margin-bottom:15px;">
							<?php echo $error_message; ?>
						</div>
						<?php } ?>

						<div class="p-detail"><!-- policy detail -->
							<div class="row">
								<div class="col-sm-3">
									<label><span><?php echo $plan_full_name; ?></span></label>
								</div>
								<div class="col-sm-3">
									<label><?php if ($plan['status_id'] < 2) { ?><?php echo $this->lang->line("Quote"); ?><?php } else {?><?php echo $this->lang->line("Policy"); ?><?php } ?> <?php echo $this->lang->line("Number"); ?>: <span><?php echo $plan['policy']; ?></span></label>
								</div>
								<div class="col-sm-3">
									<label style="text-transform: capitalize;">  <?php echo $this->lang->line("By Agent"); ?> <?php echo "[ AgentID:" . $policy_user['user_id'] . " ] "; ?>: <?php echo htmlspecialchars($policy_user['firstname'] . " " . $policy_user['lastname']); ?></label>
								</div>
								
							</div>		
							<div class="row">
								<div class="col-sm-3">
									<label class="inline"><?php echo $this->lang->line("Apply Date"); ?>:</label>
									<span><?php echo $apply_date; ?></span>
								</div>
								<div class="col-sm-3">
									<label class="inline"><?php echo $this->lang->line("Arrival Date"); ?>:</label>
									<span><?php echo $plan['arrival_date']; ?></span>
								</div>
								<div class="col-sm-3">
									<label class="inline"><?php echo $this->lang->line("Effective Date"); ?>:</label>
									<span><?php echo $plan['effective_date']; ?></span>
								</div>
								<div class="col-sm-3">
									<label class="inline"><?php echo $this->lang->line("Expiry Date"); ?>:</label>
									<span><?php echo $plan['expiry_date']; ?></span>
								</div>
							</div>
							<?php if (!empty($plan_cancel_date)) {?>
							<div class="row">
								<div class="col-sm-3">
									<label class="inline"><?php echo $this->lang->line("Cancel Date"); ?>:</label>
									<span><?php echo $plan_cancel_date; ?></span>
								</div>
							</div>
							<?php } ?>
							<?php if (!empty($plan_refund_date)) {?>
							<div class="row">
								<div class="col-sm-3">
									<label class="inline"><?php echo $this->lang->line("Refund Porcess Date"); ?>:</label>
									<span><?php echo substr($plan_refund_date, 0, 10); ?></span>
								</div>
								<div class="col-sm-3">
									<label class="inline"><?php echo $this->lang->line("Refund Date"); ?>:</label>
									<span><?php echo $plan['refund_date']; ?></span>
								</div>
								<div class="col-sm-3">
									<label class="inline"><?php echo $this->lang->line("Total Days"); ?>:</label>
									<span><?php echo $plan['totaldays']; ?></span>
								</div>
								<div class="col-sm-3">
									<label class="inline"><?php echo $this->lang->line("Used Days"); ?>:</label>
									<span><?php echo $used_days; ?></span>
								</div>
							</div>
							<?php } ?>
							<?php echo $insurable_options; ?>
							<div class="row">
								<div class="col-sm-12" style="background-color:#5bc0de; color:#fff;">
									<label class="inline"><?php echo $this->lang->line("Customer Information"); ?></label>
								</div>
							
								<div class="col-sm-3">
									<label class="inline"><?php echo $this->lang->line("Last Name"); ?>:</label>
									<span><?php echo htmlspecialchars($customer['lastname']); ?></span>
								</div>
								<div class="col-sm-3">
									<label class="inline"><?php echo $this->lang->line("First Name"); ?>:</label>
									<span><?php echo htmlspecialchars($customer['firstname']); ?></span>
								</div>
								<div class="col-sm-3">
									<label class="inline"><?php echo $this->lang->line("Birth Date"); ?>:</label>
									<span><?php echo $customer['birthday']; ?></span>
								</div>
								<div class="col-sm-3">
									<label class="inline"><?php echo $this->lang->line("Gender"); ?>:</label>
									<span><?php echo $customer['gender']; ?></span>
								</div>
							</div>

							<?php if ($plan['isfamilyplan']) { ?>
								<div class="row">
									<div class="col-sm-12" style="background-color:#5bc0de; color:#fff;">
										<label class="inline"><?php echo $this->lang->line("Insured's Family Member Information"); ?></label>
									</div>
								
								<?php for ($i = 0; $i < 9; $i++) { ?>
									<?php if (empty($customers[$i]['lastname']) && empty($customers[$i]['firstname'])) continue; ?>
									
										<div class="col-sm-3">
											<label class="inline"><?php echo $this->lang->line("Last Name"); ?>:</label>
											<span><?php echo htmlspecialchars($customers[$i]['lastname']); ?></span>
										</div>
										<div class="col-sm-3">
											<label class="inline"><?php echo $this->lang->line("First Name"); ?>:</label>
											<span><?php echo htmlspecialchars($customers[$i]['firstname']); ?></span>
										</div>
										<div class="col-sm-3">
											<label class="inline"><?php echo $this->lang->line("Birth Date"); ?>:</label>
											<span><?php echo $customers[$i]['birthday']; ?></span>
										</div>
										<div class="col-sm-3">
											<label class="inline"><?php echo $this->lang->line("Gender"); ?>:</label>
											<span><?php echo $customers[$i]['gender']; ?></span>
										</div>
									

								<?php } ?>
								</div>
							<?php } ?>	

							<div class="row">
								<div class="col-sm-12" style="background-color:#5bc0de; color:#fff;"><label><?php echo $this->lang->line("Address Information"); ?></label></div>
								<div class="col-sm-3">
									<label class="inline"><?php echo $this->lang->line("Street No"); ?>:</label>
									<span><?php echo htmlspecialchars($plan['street_number']); ?></span>
								</div>
								<div class="col-sm-3">
									<label class="inline"><?php echo $this->lang->line("Street Name"); ?>:</label>
									<span><?php echo htmlspecialchars($plan['street_name']); ?></span>
								</div>
								<div class="col-sm-3">
									<label class="inline"><?php echo $this->lang->line("Suite No."); ?>:</label>
									<span><?php echo htmlspecialchars($plan['suite_number']); ?></span>
								</div>
								<div class="col-sm-3">
									<label class="inline"><?php echo $this->lang->line("City"); ?>: </label>
									<span><?php echo htmlspecialchars($plan['city']); ?></span>
								</div>	
							
								<div class="col-sm-3">
									<label class="inline"><?php echo $this->lang->line("Province"); ?>:</label>
									<span><?php echo htmlspecialchars($plan['province2']); ?></span>
								</div>
								<div class="col-sm-3">
									<label class="inline"><?php echo $this->lang->line("Country"); ?>:</label>
									<span><?php echo htmlspecialchars($plan['country2']); ?></span>
								</div>
								<div class="col-sm-3">
									<label class="inline"><?php echo $this->lang->line("Postcode"); ?>:</label>
									<span><?php echo htmlspecialchars($plan['postcode']); ?></span>
								</div>
								
								<div class="col-sm-3">
									<label class="inline"><?php echo $this->lang->line("Phone"); ?>1:</label>
									<span><?php echo htmlspecialchars($plan['phone1']); ?></span>
								</div>
								<div class="col-sm-3">
									<label class="inline"><?php echo $this->lang->line("Phone"); ?>2:</label>
									<span><?php echo htmlspecialchars($plan['phone2']); ?></span>
								</div>
								
							</div>
							<div class="row">
								<div class="col-sm-12" style="background-color:#5bc0de; color:#fff;">
									<label class="inline"><?php echo $this->lang->line("Contact Information"); ?></label>
								</div>
								<div class="col-sm-3">
									<label class="inline"><?php echo $this->lang->line("Email"); ?>:</label>
									<span><?php echo htmlspecialchars($plan['contact_email']); ?></span>
								</div>
								<div class="col-sm-3">
									<label class="inline"><?php echo $this->lang->line("Contact Phone"); ?>:</label>
									<span><?php echo htmlspecialchars($plan['contact_phone']); ?></span>
								</div>
								<div class="col-sm-3">
									<label class="inline"><?php echo $this->lang->line("Country of Origin"); ?>:</label>
									<span><?php echo htmlspecialchars($plan['residence']); ?></span>
								</div>
							</div>

							<div class="row">
								<div class="col-sm-12" style="background-color:#5bc0de; color:#fff;">
									<label class="inline"><?php echo $this->lang->line("Special Note/Instructions"); ?></label>
								</div>
								<div class="col-sm-3">
									<label class="inline"><?php echo $this->lang->line("Age"); ?></label>
									<span><?php echo $plan['totalyears']; ?></span>
								</div>
								<div class="col-sm-3">
									<label class="inline"><?php echo $this->lang->line("Daily Rate"); ?>:</label>
									<span>$<?php echo number_format($plan['dailyrate'], 2, '.', ','); ?></span>
								</div>
								<div class="col-sm-3">
									<label class="inline"><?php echo $this->lang->line("Total Days"); ?>:</label>
									<span><?php echo $plan['totaldays']; ?></span>
								</div>
								<div class="col-sm-3">
									<label class="inline"><?php echo $this->lang->line("Premium"); ?>:</label>
									<span>$<?php echo number_format($plan['premium'], 2, '.', ','); ?></span>
								</div>
							</div>
							
						</div><!-- end p-detail --><br />
						<div class="row">
								<div class="col-sm-12">
									<button class="btn btn-primary pull-right" data-toggle="collapse" data-target="#payment-div"><?php echo $this->lang->line("Confirm and Pay"); ?></button>		
								</div>
						</div>
					</div><!-- x_content -->
				</div>
			</div>
		</div>
		<!-- End Form -->
	<?php if (!empty($payment_total)) { ?>
		<!-- Payment -->
		<div class="row" id="payment-div" style="padding-bottom:30px;">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="x_panel">
					<div class="x_title">
						<h2>
							Payment<small>Select one of the following payment methods to pay</small>
						</h2>
						<div class="clearfix"></div>
					</div>
					<div class="x_content">
						<?php if (!empty($errormsg)) { ?>
						<div class="alert-error" style="margin-bottom:15px;">
							<?php echo $errormsg; ?>
						</div>
						<?php } ?>

						<div class="row">
						<?php if (in_array('Credit Card', $paytype_list)) { ?>
							<div class="col-sm-3">
								<?php if (isset($credit_dis)) { ?>
								<div id='credit_card_div'><a class="btn btn-info col-sm-12"><?php echo $this->lang->line("Pay By Credit Card"); ?> <i class="fa fa-chevron-down"></i></a></div>
								<script type="text/javascript">
								$(document).ready(function() {
									$('#credit_card_div').click(function() {
										$('#credit_card').show();
                <?php if (isset($ali_dis)) { ?>
                    $('#ali').hide();
                <?php } ?>
								<?php if (isset($cheque_dis)) { ?>
										$('#cheque').hide();
								<?php } ?>
								<?php if (isset($cash_dis)) { ?>
										$('#cash').hide();
								<?php } ?>
									});
								});
								</script>

								<div id='credit_card' <?php if (empty($credit_dis)) { ?> style='display: none;' <?php } ?>>
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
								<?php if ($payment_total >= 0) { ?>
									<div class="row">
										<div class="col-sm-12">
											<label  class="inline" style="margin-bottom:0;"><?php echo $this->lang->line("Card Number"); ?>:</label>
											<div class="col-sm-12 input-group">
												<input type='text' name='<?php echo ($usepsi ? "CardNumber" : "card_number"); ?>' value='' class="form-control">
											</div>
										</div>
									</div>
									<?php if (! $usepsi) { ?>
									<div class="row">
										<div class="col-sm-12">
											<label  class="inline" style="margin-bottom:0;"><?php echo $this->lang->line("Card Holder's Name"); ?>:</label>
											<div class="col-sm-12 input-group">
												<input type='text' name='card_name' value='' class="form-control">
											</div>
										</div>
									</div>
									<?php } ?>
									<div class="row">
										<div class="col-sm-12">
											<div style="width:100px;" class="inline">
												<label class="inline"><?php echo $this->lang->line("Expiry Month"); ?>: </label>
												<select name='<?php echo ($usepsi ? "CardExpMonth" : "expiry_month"); ?>' class="form-control" style="width:100px;text-align:center;">
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
											<div style="width:100px;" class="inline">
												<label class="inline"><?php echo $this->lang->line("Expiry Year"); ?>: </label>
												<select name='<?php echo ($usepsi ? "CardExpYear" : "expiry_year"); ?>' class="form-control" style="width:100px;text-align:center;">
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
										</div>
									</div>
									<div class="row">
										<div class="col-sm-12" style="padding:10px;">
											<label class="inline" style="margin-bottom:0;"><?php echo $this->lang->line("Card CVV"); ?>:</label>
											<div style="display:inline-block;vertical-align:middle;" >
												<input type='text' name='<?php echo ($usepsi ? "CardIDNumber" : "card_cvv"); ?>' value='' class="form-control" style="width:137px;">
											</div>
										</div>
									</div>
									<?php } ?>
									<div class="row">
										<div class="col-sm-12  text-right">
											<label class="inline"><?php echo $this->lang->line("Amount"); ?>:</label><span> <b>$<?php echo number_format($payment_total, 2, '.', ','); ?></b></span>
											<input class="btn btn-primary paysubmit" type='submit' name='submit' value='<?php echo $this->lang->line("Pay Now"); ?>'>
										</div>
									</div>
								</form>
								<hr />
								</div><!-- End pay by credit card -->
								<?php } ?>
							</div>
						<?php } /* end cheque pay */ ?>
            <?php if (in_array('Ali', $paytype_list) && ($payment_total > 0)) { ?>
							<div class="col-sm-3">
								<?php if (isset($ali_dis)) { ?>
								<div id='ali_div'><a class="btn btn-info col-sm-12"><?php echo $this->lang->line("Pay By Alipay"); ?> <i class="fa fa-chevron-down"></i></a></div>
								<script type="text/javascript">
								$(document).ready(function() {
									$('#ali_div').click(function() {
										$('#ali').show();
                    <?php if (isset($cash_dis)) { ?>
                    $('#cash').hide();
                    <?php } ?>
						    		<?php if (isset($credit_dis)) { ?>
										$('#credit_card').hide();
				    				<?php } ?>
		    						<?php if (isset($cheque_dis)) { ?>
										$('#cheque').hide();
    								<?php } ?>

                    $.ajax({
                      url: '<?php echo $get_ali_url; ?>' + '?sekey=' + '<?php echo $sekey; ?>',
                      type: 'GET',
                      success: function(data, textStatus, jqXHR) {
                        $('#ali_submit').attr('href', data);
                        $('#ali_submit').show();
                      },
                    });
									});
								});
								</script>
								<div id='ali' style="padding:10px; display: none;">
									<div class="col-sm-12">
										<label class="inline"><?php echo $this->lang->line("Amount"); ?>:</label><span><b> $<?php echo number_format($payment_total, 2, '.', ','); ?></b></span>
                    <a class="btn btn-primary paysubmit" id="ali_submit" style="display:none;"><?php echo $this->lang->line("Pay Now"); ?></a>
								</div>
								</form><hr />
								</div>
								<?php } ?>
							</div>
						<?php } /* end ali pay */ ?>
						<?php if (in_array('Cheque', $paytype_list)) { ?>
							<div class="col-sm-3">
								<?php if (isset($cheque_dis)) { ?>
								<div id='cheque_div'><a class="btn btn-info col-sm-12"><?php echo $this->lang->line("Pay By Cheque"); ?> <i class="fa fa-chevron-down"></i></a></div>
								<script type="text/javascript">
								$(document).ready(function() {
									$('#cheque_div').click(function() {
										$('#cheque').show();
                <?php if (isset($ali_dis)) { ?>
                    $('#ali').hide();
                <?php } ?>
								<?php if (isset($credit_dis)) { ?>
										$('#credit_card').hide();
								<?php } ?>
								<?php if (isset($cash_dis)) { ?>
										$('#cash').hide();
								<?php } ?>
									});
								});
								</script>
							
								<div id='cheque' <?php if (empty($cheque_dis)) { ?> style='display: none;' <?php } ?>>
								<div class="row">
									<div class="col-sm-12">
										<label  class="inline" style="margin-bottom:0;color: #92082c;padding-bottom: 10px;"><?php echo $this->lang->line("below info is not the actual payment. Please make the cheque payble to “JF Insurance Agency Group Inc.” and send the original cheque to JF via mail or in person"); ?></label>
									</div>
								</div>
								<form action='<?php echo $active_url; ?>' method='POST'>
									<input type='hidden' name='<?php echo $csrf['name']; ?>' value='<?php echo $csrf['value']; ?>'>
									<input type='hidden' name='plan_id' value='<?php echo $plan['plan_id']; ?>'>
									<input type='hidden' name='play_type' value='Cheque'>
									<input type='hidden' name='sekey' value='<?php echo $sekey; ?>'>
									<input type='hidden' name='premium' value='<?php echo $payment_total; ?>'>
									<div class="row">
										<div class="col-sm-12">
											<label  class="inline" style="margin-bottom:0;"><?php echo $this->lang->line("Bank Name"); ?>:</label>
											<div class="col-sm-12 input-group">
												<input type='text' name='bank_name' value='' class="form-control">
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-sm-12">
											<label  class="inline" style="margin-bottom:0;"><?php echo $this->lang->line("Payor Name"); ?>:</label>
											<div class="col-sm-12 input-group">
												<input type='text' name='payor_name' value='' class="form-control">
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-sm-12">
											<label  class="inline" style="margin-bottom:0;"><?php echo $this->lang->line("Cheque No."); ?>:</label>
											<div class="col-sm-12 input-group">
												<input type='text' name='cheque_number' value='' class="form-control">
											</div>
										</div>
									</div>
									
									<div class="row" style="padding:10px;">
										<div class="col-sm-12 text-right">
											<label class="inline"><?php echo $this->lang->line("Amount"); ?>:</label><span><b> $<?php echo number_format($payment_total, 2, '.', ','); ?></b></span>
											<input class="btn btn-primary paysubmit" type='submit' name='submit' value='<?php echo $this->lang->line("Pay Now"); ?>'>
										</div>
									</div>
									 
								</form>
								<hr />
								</div>
								<?php } ?>
							</div>
						<?php } /* end cheque pay */ ?>
						<?php if (in_array('Cash', $paytype_list)) { ?>
							<div class="col-sm-3">
								<?php if (isset($cash_dis)) { ?>
								<div id='cash_div'><a class="btn btn-info col-sm-12"><?php echo $this->lang->line("Pay By Cash"); ?> <i class="fa fa-chevron-down"></i></a></div>
								<script type="text/javascript">
								$(document).ready(function() {
									$('#cash_div').click(function() {
										$('#cash').show();
                <?php if (isset($ali_dis)) { ?>
                    $('#ali').hide();
                <?php } ?>
								<?php if (isset($credit_dis)) { ?>
										$('#credit_card').hide();
								<?php } ?>
								<?php if (isset($cheque_dis)) { ?>
										$('#cheque').hide();
								<?php } ?>
									});
								});
								</script>

								<div id='cash' <?php if (empty($cash_dis)) { ?> style='display: none;' <?php } ?>>
								<form action='<?php echo $active_url; ?>' method='POST'>
								<input type='hidden' name='<?php echo $csrf['name']; ?>' value='<?php echo $csrf['value']; ?>'>
								<input type='hidden' name='plan_id' value='<?php echo $plan['plan_id']; ?>'>
								<input type='hidden' name='play_type' value='Cash'>
								<input type='hidden' name='sekey' value='<?php echo $sekey; ?>'>
								<input type='hidden' name='premium' value='<?php echo $payment_total; ?>'>
								<div class="row" style="padding:10px;">
									<div class="col-sm-12">
										<label class="inline"><?php echo $this->lang->line("Amount"); ?>:</label><span><b> $<?php echo number_format($payment_total, 2, '.', ','); ?></b></span>
										<input class="btn btn-primary paysubmit" type='submit' name='submit' value='<?php echo $this->lang->line("Pay Now"); ?>'>
									</div>
								</div>
								
								</form><hr />
								</div>
								<?php } ?>
							</div>
						<?php } /* end cash pay */ ?>
						</div>

				</div><!-- x_content -->
				</div>
			</div>
		</div>
		<!-- End Payment -->
	<?php } ?>
	</div>
</div>
<!-- /page content -->
