<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
$usepsi = false;
$hideForFrench = false;
$Agree = $this->lang->line("Agree");
if ($Agree != "Agree") {
  $hideForFrench = true;
}
?>

<!-- Plan page content -->

<?php if (isset($menu) && is_array($menu) && (sizeof($menu)>0)) { ?>
<?php $noagent = 0; ?>
<!-- Content top navigation -->
<div class="top_nav">
	<div class="nav_menu">
		<nav class="" role="navigation">
			<div class="nav toggle">
				<a id="menu_toggle"><i class="fa fa-bars"></i></a>
			</div>
		</nav>
	</div>
</div>
<!-- Content top navigation End-->

<!-- page content -->
<div class="right_col" role="main">
<?php } else { ?>
<?php $noagent = 1; ?>
<div role="main" style="padding-left:28px">
<?php } ?>
 	<div class="main-div" style="padding-bottom:50px;">
		<div class="page-title">
			<div class="title_left">
				<h3><?php echo $this->lang->line("Policy"); ?></h3>
			</div>
		</div>
		<div class="clearfix"></div>
		<!-- Form Section -->
		<?php if ($noagent && (($plan['status_id'] == 2) || ($plan['status_id'] == 3))) { ?>
			<div class="alert alert-danger" style='font-size: 24px;'><?php echo $this->lang->line("Please contact your agent to get your policy details and the insurance package."); ?></div>
		<?php } ?>
		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="x_panel">
					<div class="x_title">
						<h2 class="col-xs-12 col-sm-12 col-md-12" style="width: auto;">
							<?php echo $this->lang->line("Review Policy Detail"); ?>
							<span><b>[ <?php echo $status_list[$plan['status_id']]['name']; ?> ]</b></span>
						</h2>
					<?php if (($plan['status_id'] == 1) || ($plan['status_id'] == 2) || ($plan['status_id'] == 3)) { ?>

						<?php if ($export_logo_price_option) { ?>

						<div class='pull-right spdf-option'><input type='checkbox' class='withlogobox' checked> With Logo <br /><input type='checkbox' class='withpricebox' checked> With Price </div>
						<?php } ?>
						<?php if (empty($sekey)) { ?>
 						<a class="btn btn-info pull-right" target="_blank" href='<?php echo $pdf_url; ?>'><?php echo $this->lang->line("Export PDF"); ?></a>
						<?php } ?>
					<?php } ?>
					<?php if ($isprocessplan) { ?>
					<?php if (($plan['status_id'] == 2) || ($plan['status_id'] == 3)) { ?>
						<?php if (!empty($sendpackage_url)) { ?>
						<a class="btn btn-info pull-right" target="_blank" href='<?php echo $sendpackage_url; ?>'><?php echo $this->lang->line("Send Package"); ?></a>
						<?php } ?>
						<?php if (!empty($print_card_url) && !$hideForFrench) { ?>
						<a class="btn btn-info pull-right" target="_blank" href='<?php echo $print_card_url; ?>'><?php echo $this->lang->line("Print Card"); ?></a>
						<?php } ?>
						<?php if (!empty($print_receipt_url) && !$hideForFrench) { ?>
						<a class="btn btn-info pull-right" target="_blank" href='<?php echo $print_receipt_url; ?>'><?php echo $this->lang->line("Print Receipt"); ?></a>
						<?php } ?>
					<?php } ?>
					<?php } ?>
					<?php if (!empty($plan_url)) { ?>
						<a class="btn btn-info pull-right" href='<?php echo $plan_url; ?>'><?php echo $this->lang->line("Edit"); ?></a>
					<?php } ?>
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
								<div class="col-sm-3">
									<label><span>Payment Plan:</span></label><?php echo empty($plan['monthlypay'])?"Full":"Monthly"; ?>
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
							<?php if (!empty($plan['monthlypay'])) {?>
							<div class="row">
								<div class="col-sm-3">
									<label class="inline">Payment Plan Status:</label>
									<span><?php echo $plan["monthly_status"]; ?></span>
								</div>
								<div class="col-sm-3">
									<label class="inline">Paid Months:</label>
									<span><?php echo (12 - intval($plan["monthly_unpay_count"])); ?></span>
								</div>
								<div class="col-sm-3">
									<label class="inline">Paid Premium:</label>
									<span>$<?php echo $plan["monthly_paid"]; ?></span>
								</div>
								<div class="col-sm-3">
									<label class="inline">Outstanding Premium:</label>
									<span>$<?php echo $plan["monthly_unpay"]; ?></span>
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
								<?php if ($beuser['user_group_id'] < 100) { ?>
                <?php if (empty($sekey) && empty($isvsuser)) { ?>
								<div class="col-sm-12">
									<label class="inline"><?php echo $this->lang->line("Notes"); ?>:</label>
									<span><?php echo htmlspecialchars($plan['note']); ?></span>
								</div>
								<?php } ?>
								<?php } ?>
							</div>
							
						</div><!-- end p-detail --><br />
            <?php if ($beuser['user_group_id'] < 106) { ?>
						<?php if (($plan['monthlypay'] != 1) && ($plan['status_id'] <= 1)) { ?>
						<div class="row">
								<div class="col-sm-12">
								<?php if (!empty($payment_total)) { ?>						
									<button class="btn btn-primary pull-right" data-toggle="collapse" data-target="#payment-div"><?php echo $this->lang->line("Confirm and Pay"); ?></button>		
                <?php } else if ($plan['status_id'] == 7) { ?>
                  <form action='<?php echo $active_url; ?>' method='POST'>
                    <input type='hidden' name='<?php echo $csrf['name']; ?>' value='<?php echo $csrf['value']; ?>'>
                    <input type='hidden' name='plan_id' value='<?php echo $plan['plan_id']; ?>'>
                    <input type='hidden' name='play_type' value='Confirm'>
                    <input type='hidden' name='sekey' value='<?php echo $sekey; ?>'>
                    <input type='hidden' name='premium' value='0'>
                    <input class="btn btn-primary pull-right" type='submit' name='submit' value='Confirm Change'>
                  </form>
								<?php } ?>
								<?php if (($plan['status_id'] == 1) || ($plan['status_id'] == 2) || ($plan['status_id'] == 3)) { ?>
									<?php if ($export_logo_price_option) { ?>
									<div class='pull-right spdf-option'><input type='checkbox' class='withlogobox' checked> With Logo <br /><input type='checkbox' class='withpricebox' checked> With Price </div>
									<?php } ?>
									<?php if (empty($sekey)) { ?>
									<a class="btn btn-info pull-right" target="_blank" href='<?php echo $pdf_url; ?>'><?php echo $this->lang->line("Export PDF"); ?></a>
									<?php } ?>
								<?php } ?>
								</div>
						</div>
            <?php } ?>
            <?php } ?>
					</div><!-- x_content -->
				</div>
			</div>
		</div>
		<!-- End Form -->
	<?php if (($plan["monthlypay"] == 1) && ($plan["status_id"] > 1) && !empty($plan['monthlypay'])) { ?>
		<div class="row">
      <div class="col-sm-12">
        <div class="x_panel">
          <div class="x_title">
            <h2>Monthly Payment Detail</h2>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
						<div class="row">
							<div class="col-sm-3">
								<label class="inline">Payment Date</label>
							</div>
							<div class="col-sm-3">
								<label class="inline">Amount</label>
							</div>
							<div class="col-sm-3">
								<label class="inline">Payment Status</label>
							</div>
							<div class="col-sm-3">
								<label class="inline">&nbsp;</label>
							</div>
						</div>
						<?php $idx = 0; foreach ($plan['monthly_payment'] as $mr) { $idx++; ?>
						<div class="row" style="line-height: 32px;">
							<div class="col-sm-3">
								<span><?php echo $mr["retry_date"]; ?></span>
							</div>
							<div class="col-sm-3">
								<span><?php echo number_format($mr["amount"], 2, ".", ""); ?></span>
							</div>
							<div class="col-sm-3">
								<span><?php echo ($mr["paid"]==1)?"Paid":(($mr["paid"]==-2)?"Pay Error":(($mr["paid"]==-1)?"Void":"-")); ?></span>
							</div>
							<div class="col-sm-3">
							<?php if ($mr["paid"]==-2) { ?>
								<button class="btn btn-primary retry-button" onclick='retry_payment(<?php echo $mr["monthly_payment_id"]; ?>)'>Retry</button>
							<?php } ?>
							</div>
						</div>
						<?php } ?>
          </div><!--/x_content end-->
        </div><!--x_panel-->
			</div>
		</div>

	<?php } else { ?>
	<?php if (!empty($payment_total) && ($beuser['user_group_id'] < 106)) { ?>
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
								<?php if ($payurltm) { ?>
									<div class="row">
										<div class="col-sm-12">
											<label class="inline" style="margin-bottom:0;"><?php echo $this->lang->line("Pay url to user by Email"); ?> (<?php echo $this->lang->line("Valid before"); ?> <?php echo $payurltm; ?>):</label>
										</div><br />
									</div>
									<div class="row">
										<div class="col-sm-12">
											<div class="input-group">
											    <input  id="copy-input" class="form-control" type='text' name='payurl' value='<?php echo $payurl; ?>' readonly>
											    <span class="input-group-btn">
											      <button class="btn btn-default" type="button" id="copy-button"
											          data-toggle="tooltip"
											          title="Copy to Clipboard">
											        <?php echo $this->lang->line("Copy"); ?>
											      </button>
											    </span>
											</div>
										</div>
									</div>
								<?php } ?>
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
									<div class="row card_full_pay">
										<div class="col-sm-12  text-right">
											<label class="inline"><?php echo $this->lang->line("Amount"); ?>:</label><span> <b>$<?php echo number_format($payment_total, 2, '.', ','); ?></b></span>
											<input class="btn btn-primary paysubmit" type='submit' name='submit' value='<?php echo $this->lang->line("Pay Now"); ?>'>
										</div>
									</div>
									<?php if (!$usepsi && isset($recurrent)) { /* disable recurrent for now */ ?>
										<hr />
										<div class="row">
											<div class="col-sm-12">
												<label class="inline">Pay Monthly Option</label>
												<div style="font-size: 10px;">Pay today: <b>$<?php echo number_format($recurrent[0], 2, '.', ','); ?></b><br><?php echo $recurrent[2]; ?> Recurring Pay: <b>$<?php echo number_format($recurrent[1], 2, '.', ','); ?></b></div>
												<a class='btn btn-primary pull-right' href="<?php echo $monthly_pay_url; ?>"><?php echo $this->lang->line("Pay Now"); ?></a>
											</div>
										</div>
										<!-- <div class="row">
											<div class="col-sm-12">
												<label class="inline">Monthly Payment Option (without Iframe)</label>
												<div style="font-size: 10px;">First Pay: $<?php echo number_format($recurrent[0], 2, '.', ','); ?> and Recurring Pay: $<?php echo number_format($recurrent[1], 2, '.', ','); ?> x <?php echo $recurrent[2]; ?></div>
												<a class='btn btn-primary pull-right' href="<?php echo $monthly_pay_url2; ?>"><?php echo $this->lang->line("Pay Now"); ?></a>
											</div>
										</div> -->
									<?php } ?>
									
									
									<!-- Copy button -->
								 	<script>
								 		$(document).ready(function() {
										  // Initialize the tooltip.
										  $('#copy-button').tooltip();

										  // When the copy button is clicked, select the value of the text box, attempt
										  // to execute the copy command, and trigger event to update tooltip message
										  // to indicate whether the text was successfully copied.
										  $('#copy-button').bind('click', function() {
										    $('#copy-input').select();
										    try {
										      var success = document.execCommand('copy');
										      if (success) {
										        $('#copy-button').trigger('copied', ['Copied!']);
										      } else {
										        $('#copy-button').trigger('copied', ['Copy with Ctrl-c']);
										      }
										    } catch (err) {
										      $('#copy-button').trigger('copied', ['Copy with Ctrl-c']);
										    }
										  });

										  // Handler for updating the tooltip message.
										  $('#copy-button').bind('copied', function(event, message) {
										    $(this).attr('title', message)
										        .tooltip('fixTitle')
										        .tooltip('show')
										        .attr('title', "Copy to Clipboard")
										        .tooltip('fixTitle');
										  });
										});
								 	</script><!-- End copy button -->
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

									// Initialize the tooltip.
									$('#copy-button2').tooltip();

									// When the copy button is clicked, select the value of the text box, attempt
									// to execute the copy command, and trigger event to update tooltip message
									// to indicate whether the text was successfully copied.
									$('#copy-button2').bind('click', function() {
										$('#copy-input2').select();
										try {
											var success = document.execCommand('copy');
											if (success) {
												$('#copy-button2').trigger('copied', ['Copied!']);
											} else {
												$('#copy-button2').trigger('copied', ['Copy with Ctrl-c']);
											}
										} catch (err) {
											$('#copy-button2').trigger('copied', ['Copy with Ctrl-c']);
										}
									});

									// Handler for updating the tooltip message.
									$('#copy-button2').bind('copied', function(event, message) {
										$(this).attr('title', message)
												.tooltip('fixTitle')
												.tooltip('show')
												.attr('title', "Copy to Clipboard")
												.tooltip('fixTitle');
									});

								});
								</script>

								<?php if (($payurltm) && ($payment_total >= 0)) { ?>
									<div class="row">
										<div class="col-sm-12">
											<label class="inline" style="margin-bottom:0;"><?php echo $this->lang->line("Pay url to user by Email"); ?> (<?php echo $this->lang->line("Valid before"); ?> <?php echo $payurltm; ?>):</label>
										</div><br />
									</div>
									<div class="row">
										<div class="col-sm-12">
											<div class="input-group">
											    <input  id="copy-input2" class="form-control" type='text' name='payurl2' value='<?php echo $payurl; ?>' readonly>
											    <span class="input-group-btn">
											      <button class="btn btn-default" type="button" id="copy-button2"
											          data-toggle="tooltip"
											          title="Copy to Clipboard">
											        <?php echo $this->lang->line("Copy"); ?>
											      </button>
											    </span>
											</div>
										</div>
									</div>
								<?php } ?>
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
	<?php } ?>
	<?php if ($show_history) { ?>
    <div class="row">
      <div class="col-sm-12">
        <div class="x_panel">
          <div class="x_title">
            <h2>Policy History</h2>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <div class="row" id='payment_history'>
              <div class="col-sm-12">
                <?php if (!empty($payment_tables) && is_array($payment_tables) && (sizeof($payment_tables) > 0)) { ?>
                  <button type="button" id="payment_history_button" class="btn btn-info" data-toggle="collapse" data-target="#history1"><?php echo $this->lang->line("Payments"); ?> <span class="fa fa-chevron-down"></span></button>
                  <div id="history1" class="collapse">                	
                  <?php	if (sizeof($payment_tables) > 1) { ?>
                    <button type="button" id="payment_get_history_button" class="btn">Get More</button>
                  <?php } ?>
                  <form action='<?php echo $makepay_url; ?>' method='POST' class="form-horizontal">
                    <input type='hidden' name='<?php echo $csrf['name']; ?>' value='<?php echo $csrf['value']; ?>'>
                    <div class="table-responsive">
                      <table class="table table-hover table-bordered" id="payment_history_table">
                        <thead>
                          <tr>
                            <th>&nbsp;</th>
                            <th><?php echo $this->lang->line("Last Update"); ?></th>
                            <th><?php echo $this->lang->line("Type"); ?></th>
                            <th><?php echo $this->lang->line("Pay Type"); ?></th>
                            <th><?php echo $this->lang->line("Amount"); ?></th>
                            <th><?php echo $this->lang->line("Rate"); ?></th>
                            <th><?php echo $this->lang->line("Pay Status"); ?></th>
                            <th>CK Info</th>
                            <th>Info</th>
                            <th><?php echo $this->lang->line("Notes"); ?></th>
                          </tr>
                        </thead>
                        <tbody id="payment_history_table_tbody">
                        </tbody>
                      </table>
                    </div>
                    <div class="row">
                      <div class="col-sm-12">
                        <input type="submit" class="btn btn-primary" name='submit' value='Make Pay'>
                      </div>
                    </div>
                  </form>
                  <hr />
                <?php } ?>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-12">
                <?php if (!empty($activelog_tables) && is_array($activelog_tables) && (sizeof($activelog_tables) > 0)) { ?>
                  <button type="button" class="btn btn-info" data-toggle="collapse" data-target="#history2"><?php echo $this->lang->line("Changes"); ?> <span class="fa fa-chevron-down"></span></button>
                  <div id="history2" class="collapse">
                    <?php	if (sizeof($activelog_tables) > 1) { ?>
                      <button type="button" id="activelog_get_history_button" class="btn">Get More</button>
                    <?php } ?>
                    <div class="table-responsive">
                      <table class="table table-hover table-bordered" id="activelog_history_table">
                        <thead>
                          <tr>
                            <th><?php echo $this->lang->line("Username"); ?></th>
                            <th><?php echo $this->lang->line("Date Time"); ?></th>
                            <th><?php echo $this->lang->line("Message"); ?></th>
                          </tr>
                        </thead>
                        <tbody id="activelog_history_table_tbody">
                        </tbody>
                      </table>
                    </div>
                  </div>
                <?php } ?>
             	</div>
            </div>
          </div><!--/x_content end-->
        </div><!--x_panel-->
			</div>
		</div>
	<?php } ?>
	</div>
</div>
<?php if ($export_logo_price_option) { ?>
<script type="text/javascript">
$(document).ready(function() {
	$('.billing_info').hide();
	$('.card_full_pay').show();
	$('.monthpay').change(function() {
		if ($(this).is(':checked')) {
			$('.billing_info').show();
			$('.card_full_pay').hide();
		} else {
			$('.billing_info').hide();
			$('.card_full_pay').show();
		}
	});
	$('.withlogobox').change(function() {
		var w = '0';
		if ($(this).is(':checked')) {
			$('.withlogobox').prop('checked', true);
			w = '1';
		} else {
			$('.withlogobox').prop('checked', false);
		}
		$.ajax({
			url: '<?php echo $export_logo_url; ?>' + w,
			type: 'GET',
			success: function(data, textStatus, jqXHR) {
	        	//console.log(data);
	    	},
		});
	});

	$('.withpricebox').change(function() {
		var w = '0';
		if ($(this).is(':checked')) {
			$('.withpricebox').prop('checked', true);
			w = '1';
		} else {
			$('.withpricebox').prop('checked', false);
		}
		$.ajax({
			url: '<?php echo $export_price_url; ?>' + w,
			type: 'GET',
			success: function(data, textStatus, jqXHR) {
	        	//console.log(data);
	    	},
		});
	});

	$('.billing_info').hide();

	$('.paysubmit').click(function() {
		$('.paysubmit').hide();
	});

	$( ".btn-payment-sort" ).click(sorting_payment);
});

function retry_payment(id) {
	console.log("retry_payment", id); //XXXXXXXXXXXXXXXXXXXXXXX
	$.ajax({
		url: '<?php echo $retry_payment_url; ?>?id=' + id,
		success: function(data, textStatus, jqXHR) {
			console.log("retry_payment R", id, data, textStatus, jqXHR); //XXXXXXXXXXXXXXXXXXXXXXX
		},
	});
}

function sorting_payment() {
	var d = $(this).attr('data-type');
	$.ajax({
		url: '<?php echo $payhistory_url; ?>?s=' + d,
		success: function(data, textStatus, jqXHR) {
        	$('#payment_history').html(data);
        	$( ".btn-payment-sort" ).click(sorting_payment);
    	},
	});
}
</script>
<?php } ?>
<script type="text/javascript">
var paytb=[];
var logtb=[];
<?php	
if (!empty($payment_tables) && is_array($payment_tables) && (sizeof($payment_tables) > 0)) {
  foreach ($payment_tables as $tb) {
    echo "paytb.push('".$tb."');\n";
  }
}
if (!empty($activelog_tables) && is_array($activelog_tables) && (sizeof($activelog_tables) > 0)) {
  foreach ($activelog_tables as $tb) {
    echo "logtb.push('".$tb."');\n";
  }
}
?>
$('#activelog_history_button').click(function(){
  var vs = $("#history2").is( ":visible" );
  if (!vs) {
    var tb = logtb.shift();
    $.ajax({
      url: '<?php echo $get_activelog_history_url; ?>' + '?tb=' + tb,
      type: 'GET',
      success: function(data, textStatus, jqXHR) {
        var x=document.getElementById('activelog_history_table_tbody').innerHTML;
        document.getElementById('activelog_history_table_tbody').innerHTML = x + data;
    	},
  	});
  }
  if (logtb.length <= 0) {
    $('#activelog_get_history_button').css('display','none');
  }
});
$('#activelog_get_history_button').click(function(){
  var tb = logtb.shift();
  $.ajax({
    url: '<?php echo $get_activelog_history_url; ?>' + '?tb=' + tb,
    type: 'GET',
    success: function(data, textStatus, jqXHR) {
      var x=document.getElementById('activelog_history_table_tbody').innerHTML;
      document.getElementById('activelog_history_table_tbody').innerHTML = x + data;
    },
  });
  if (logtb.length <= 0) {
    $('#activelog_get_history_button').css('display','none');
  }
});

$('#payment_history_button').click(function(){
  var vs = $("#history1").is( ":visible" );
  if (!vs) {
    var tb = paytb.shift();
    $.ajax({
      url: '<?php echo $get_payment_history_url; ?>' + '?tb=' + tb,
      type: 'GET',
      success: function(data, textStatus, jqXHR) {
        var x=document.getElementById('payment_history_table_tbody').innerHTML;
        document.getElementById('payment_history_table_tbody').innerHTML = x + data;
    	},
    });
  }
  if (paytb.length <= 0) {
    $('#payment_get_history_button').css('display','none');
  }
});
$('#payment_get_history_button').click(function(){
  var tb = paytb.shift();
  $.ajax({
    url: '<?php echo $get_payment_history_url; ?>' + '?tb=' + tb,
    type: 'GET',
    success: function(data, textStatus, jqXHR) {
      var x=document.getElementById('payment_history_table_tbody').innerHTML;
      document.getElementById('payment_history_table_tbody').innerHTML = x + data;
    },
  });
  if (paytb.length <= 0) {
    $('#payment_get_history_button').css('display','none');
  }
});

$(document).ready(function() {
	setTimeout(function(){ window.location.replace("/"); }, 900000);
});
</script>
<!-- /page content -->
