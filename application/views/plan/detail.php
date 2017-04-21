<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
?>

<!-- Plan page content -->

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
	<div class="main-div" style="padding-bottom:50px;">
		<div class="page-title">
			<div class="title_left">
				<h3>Policy</h3>
			</div>
		</div>
		<div class="clearfix"></div>
		<!-- Form Section -->
		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="x_panel">
					<div class="x_title">
						<h2 class="col-xs-12 col-sm-12 col-md-12" style="width: auto;">
							Review Policy Detail
							<span><b>[ <?php echo $status_list[$plan['status_id']]['name']; ?> ]</b></span>
						</h2>
					<?php if (($plan['status_id'] == 1) || ($plan['status_id'] == 2) || ($plan['status_id'] == 3)) { ?>

						<?php if ($export_logo_price_option) { ?>

						<div class='pull-right spdf-option'><input type='checkbox' class='withlogobox' checked> With Logo <br /><input type='checkbox' class='withpricebox' checked> With Price </div>
						<?php } ?>
						<a class="btn btn-info pull-right" target="_blank" href='<?php echo $pdf_url; ?>'>Export PDF</a>
						
					<?php } ?>
					<?php if ($isprocessplan) { ?>
					<?php if (($plan['status_id'] == 2) || ($plan['status_id'] == 3)) { ?>
						<?php if (!empty($sendpackage_url)) { ?>
						<a class="btn btn-info pull-right" target="_blank" href='<?php echo $sendpackage_url; ?>'>Send Package</a>
						<?php } ?>
						<?php if (!empty($print_card_url)) { ?>
						<a class="btn btn-info pull-right" target="_blank" href='<?php echo $print_card_url; ?>'>Print Card</a>
						<?php } ?>
						<?php if (!empty($print_receipt_url)) { ?>
						<a class="btn btn-info pull-right" target="_blank" href='<?php echo $print_receipt_url; ?>'>Print Receipt</a>
						<?php } ?>
					<?php } ?>
					<?php } ?>
					<?php if (!empty($plan_url)) { ?>
						<a class="btn btn-info pull-right" href='<?php echo $plan_url; ?>'>Edit</a>
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
									<label><?php if ($plan['status_id'] < 2) { ?>Quote<?php } else {?>Policy<?php } ?> Number: <span><?php echo $plan['policy']; ?></span></label>
								</div>
								<div class="col-sm-3">
									<label style="text-transform: capitalize;">  By agent <?php echo "[ AgentID:" . $policy_user['user_id'] . " ] "; ?>: <?php echo $policy_user['firstname'] . " " . $policy_user['lastname']; ?></label>
								</div>
								
							</div>		
							<div class="row">
								<div class="col-sm-3">
									<label class="inline">Apply Date:</label>
									<span><?php echo $apply_date; ?></span>
								</div>
								<div class="col-sm-3">
									<label class="inline">Arrival Date:</label>
									<span><?php echo $plan['arrival_date']; ?></span>
								</div>
								<div class="col-sm-3">
									<label class="inline">Effective Date:</label>
									<span><?php echo $plan['effective_date']; ?></span>
								</div>
								<div class="col-sm-3">
									<label class="inline">Expiry Date:</label>
									<span><?php echo $plan['expiry_date']; ?></span>
								</div>
							</div>
							<?php if (!empty($plan_cancel_date)) {?>
							<div class="row">
								<div class="col-sm-3">
									<label class="inline">Cancel Date:</label>
									<span><?php echo $plan_cancel_date; ?></span>
								</div>
							</div>
							<?php } ?>
							<?php if (!empty($plan_refund_date)) {?>
							<div class="row">
								<div class="col-sm-3">
									<label class="inline">Refund Porcess Date:</label>
									<span><?php echo substr($plan_refund_date, 0, 10); ?></span>
								</div>
								<div class="col-sm-3">
									<label class="inline">Refund Date:</label>
									<span><?php echo $plan['refund_date']; ?></span>
								</div>
								<div class="col-sm-3">
									<label class="inline">Total Days:</label>
									<span><?php echo $plan['totaldays']; ?></span>
								</div>
								<div class="col-sm-3">
									<label class="inline">Used Days:</label>
									<span><?php echo $used_days; ?></span>
								</div>
							</div>
							<?php } ?>
							<?php echo $insurable_options; ?>
							<div class="row">
								<div class="col-sm-12" style="background-color:#5bc0de; color:#fff;">
									<label class="inline">Customer Information</label>
								</div>
							
								<div class="col-sm-3">
									<label class="inline">Last Name:</label>
									<span><?php echo $customer['lastname']; ?></span>
								</div>
								<div class="col-sm-3">
									<label class="inline">First Name:</label>
									<span><?php echo $customer['firstname']; ?></span>
								</div>
								<div class="col-sm-3">
									<label class="inline">Birth Date:</label>
									<span><?php echo $customer['birthday']; ?></span>
								</div>
								<div class="col-sm-3">
									<label class="inline">Gender:</label>
									<span><?php echo $customer['gender']; ?></span>
								</div>
							</div>

							<?php if ($plan['isfamilyplan']) { ?>
								<div class="row">
									<div class="col-sm-12" style="background-color:#5bc0de; color:#fff;">
										<label class="inline">Family Member Information</label>
									</div>
								
								<?php for ($i = 0; $i < 9; $i++) { ?>
									<?php if (empty($customers[$i]['lastname']) && empty($customers[$i]['firstname'])) continue; ?>
									
										<div class="col-sm-3">
											<label class="inline">Last Name:</label>
											<span><?php echo $customers[$i]['lastname']; ?></span>
										</div>
										<div class="col-sm-3">
											<label class="inline">First Name:</label>
											<span><?php echo $customers[$i]['firstname']; ?></span>
										</div>
										<div class="col-sm-3">
											<label class="inline">Birth Date:</label>
											<span><?php echo $customers[$i]['birthday']; ?></span>
										</div>
										<div class="col-sm-3">
											<label class="inline">Gender:</label>
											<span><?php echo $customers[$i]['gender']; ?></span>
										</div>
									

								<?php } ?>
								</div>
							<?php } ?>	

							<div class="row">
								<div class="col-sm-12" style="background-color:#5bc0de; color:#fff;"><label>Address Information</label></div>
								<div class="col-sm-3">
									<label class="inline">Street No:</label>
									<span><?php echo $plan['street_number']; ?></span>
								</div>
								<div class="col-sm-3">
									<label class="inline">Street Name:</label>
									<span><?php echo $plan['street_name']; ?></span>
								</div>
								<div class="col-sm-3">
									<label class="inline">Suite No.:</label>
									<span><?php echo $plan['suite_number']; ?></span>
								</div>
								<div class="col-sm-3">
									<label class="inline">City: </label>
									<span><?php echo $plan['city']; ?></span>
								</div>	
							
								<div class="col-sm-3">
									<label class="inline">Province:</label>
									<span><?php echo $plan['province2']; ?></span>
								</div>
								<div class="col-sm-3">
									<label class="inline">Country:</label>
									<span><?php echo $plan['country2']; ?></span>
								</div>
								<div class="col-sm-3">
									<label class="inline">Postcode:</label>
									<span><?php echo $plan['postcode']; ?></span>
								</div>
								
								<div class="col-sm-3">
									<label class="inline">Phone1:</label>
									<span><?php echo $plan['phone1']; ?></span>
								</div>
								<div class="col-sm-3">
									<label class="inline">Phone2:</label>
									<span><?php echo $plan['phone2']; ?></span>
								</div>
								
							</div>
							<div class="row">
								<div class="col-sm-12" style="background-color:#5bc0de; color:#fff;">
									<label class="inline">Contact Information</label>
								</div>
								<div class="col-sm-3">
									<label class="inline">Email:</label>
									<span><?php echo $plan['contact_email']; ?></span>
								</div>
								<div class="col-sm-3">
									<label class="inline">Contact Phone:</label>
									<span><?php echo $plan['contact_phone']; ?></span>
								</div>
								<div class="col-sm-3">
									<label class="inline">Country of Origin:</label>
									<span><?php echo $plan['residence']; ?></span>
								</div>
							</div>

							<div class="row">
								<div class="col-sm-12" style="background-color:#5bc0de; color:#fff;">
									<label class="inline">Special Note/Instructions</label>
								</div>
								<div class="col-sm-3">
									<label class="inline">Age</label>
									<span><?php echo $plan['totalyears']; ?></span>
								</div>
								<div class="col-sm-3">
									<label class="inline">Daily Rate:</label>
									<span>$<?php echo number_format($plan['dailyrate'], 2, '.', ','); ?></span>
								</div>
								<div class="col-sm-3">
									<label class="inline">Total Days:</label>
									<span><?php echo $plan['totaldays']; ?></span>
								</div>
								<div class="col-sm-3">
									<label class="inline">Premium:</label>
									<span>$<?php echo number_format($plan['premium'], 2, '.', ','); ?></span>
								</div>
								<div class="col-sm-12">
									<label class="inline">Notes:</label>
									<span><?php echo $plan['note']; ?></span>
								</div>
								
							</div>
							
						</div><!-- end p-detail --><br />
						<div class="row">
								<div class="col-sm-12">
								<?php if (!empty($payment_total)) { ?>						
									<button class="btn btn-primary pull-right" data-toggle="collapse" data-target="#payment-div">Confirm and Pay</button>		
								<?php } ?>
								<?php if (($plan['status_id'] == 1) || ($plan['status_id'] == 2) || ($plan['status_id'] == 3)) { ?>
									<?php if ($export_logo_price_option) { ?>
									<div class='pull-right spdf-option'><input type='checkbox' class='withlogobox' checked> With Logo <br /><input type='checkbox' class='withpricebox' checked> With Price </div>
									<?php } ?>
									<a class="btn btn-info pull-right" target="_blank" href='<?php echo $pdf_url; ?>'>Export PDF</a>
									
								<?php } ?>

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
							<div class="col-sm-4">
								<?php if (isset($credit_dis)) { ?>
								<div id='credit_card_div'><a class="btn btn-info col-sm-12">Pay By Credit Card <i class="fa fa-chevron-down"></i></a></div>
								<script type="text/javascript">
								$(document).ready(function() {
									$('#credit_card_div').click(function() {
										$('#credit_card').show();
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
								<form action='<?php echo $active_url; ?>' method='POST'>
									<input type='hidden' name='<?php echo $csrf['name']; ?>' value='<?php echo $csrf['value']; ?>'>
									<input type='hidden' name='plan_id' value='<?php echo $plan['plan_id']; ?>'>
									<input type='hidden' name='play_type' value='Credit Card'>
									<input type='hidden' name='sekey' value='<?php echo $sekey; ?>'>
									<input type='hidden' name='premium' value='<?php echo $payment_total; ?>'>
								<?php if ($payment_total >= 0) { ?>
								<?php if (empty($sekey) && ((time() - strtotime($plan['last_update'])) < (48 * 3600)) && ($plan['effective_date'] > date("Y-m-d"))) { ?>
									<div class="row">
										<div class="col-sm-12">
											<label class="inline" style="margin-bottom:0;">Pay url to user by Email (Valid before <?php echo $payurltm; ?>):</label>
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
											        Copy
											      </button>
											    </span>
											</div>
										</div>
									</div>
								<?php } ?>
									<div class="row">
										<div class="col-sm-12">
											<label  class="inline" style="margin-bottom:0;">Card Number:</label>
											<div class="col-sm-12 input-group">
												<input type='text' name='card_number' value='<?php echo $card_number; ?>' class="form-control">
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-sm-12">
											<label  class="inline" style="margin-bottom:0;">Card Holder's Name:</label>
											<div class="col-sm-12 input-group">
												<input type='text' name='card_name' value='<?php echo $card_name; ?>' class="form-control">
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-sm-12">
											<div style="width:100px;" class="inline">
												<label class="inline">Expiry Month: </label>
												<select name='expiry_month' class="form-control" style="width:100px;text-align:center;">
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
												<label class="inline">Expiry Year: </label>
												<select name='expiry_year' class="form-control" style="width:100px;text-align:center;">
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
												</select>
											</div> 
										</div>
									</div>
									<div class="row">
										<div class="col-sm-12" style="padding:10px;">
											<label class="inline" style="margin-bottom:0;">Card CVV:</label>
											<div style="display:inline-block;vertical-align:middle;" >
												<input type='text' name='card_cvv' value='<?php echo $card_cvv; ?>' class="form-control" style="width:137px;">
											</div>
										</div>
									</div>
									<?php } ?>
									<div class="row">
										<div class="col-sm-12  text-right">
											<label class="inline">Amount:</label><span> <b>$<?php echo number_format($payment_total, 2, '.', ','); ?></b></span>
											<input class="btn btn-primary paysubmit" type='submit' name='submit' value='Pay Now'>
										</div>
									</div>
									
									
									<!-- Copy button -->
								 	<script>
								 		$(document).ready(function() {
										  // Initialize the tooltip.
										  $('#copy-button').tooltip();

										  // When the copy button is clicked, select the value of the text box, attempt
										  // to execute the copy command, and trigger event to update tooltip message
										  // to indicate whether the text was successfully copied.
										  $('#copy-button').bind('click', function() {
										    var input = document.querySelector('#copy-input');
										    input.setSelectionRange(0, input.value.length + 1);
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
						<?php if (in_array('Cheque', $paytype_list)) { ?>
							<div class="col-sm-4">
								<?php if (isset($cheque_dis)) { ?>
								<div id='cheque_div'><a class="btn btn-info col-sm-12">Pay By Cheque <i class="fa fa-chevron-down"></i></a></div>
								<script type="text/javascript">
								$(document).ready(function() {
									$('#cheque_div').click(function() {
										$('#cheque').show();
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
								<form action='<?php echo $active_url; ?>' method='POST'>
									<input type='hidden' name='<?php echo $csrf['name']; ?>' value='<?php echo $csrf['value']; ?>'>
									<input type='hidden' name='plan_id' value='<?php echo $plan['plan_id']; ?>'>
									<input type='hidden' name='play_type' value='Cheque'>
									<input type='hidden' name='sekey' value='<?php echo $sekey; ?>'>
									<input type='hidden' name='premium' value='<?php echo $payment_total; ?>'>
									<div class="row">
										<div class="col-sm-12">
											<label  class="inline" style="margin-bottom:0;">Bank Name:</label>
											<div class="col-sm-12 input-group">
												<input type='text' name='bank_name' value='' class="form-control">
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-sm-12">
											<label  class="inline" style="margin-bottom:0;">Payor Name:</label>
											<div class="col-sm-12 input-group">
												<input type='text' name='payor_name' value='' class="form-control">
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-sm-12">
											<label  class="inline" style="margin-bottom:0;">Cheque No.:</label>
											<div class="col-sm-12 input-group">
												<input type='text' name='cheque_number' value='' class="form-control">
											</div>
										</div>
									</div>
									
									<div class="row" style="padding:10px;">
										<div class="col-sm-12 text-right">
											<label class="inline">Amount:</label><span><b> $<?php echo number_format($payment_total, 2, '.', ','); ?></b></span>
											<input class="btn btn-primary paysubmit" type='submit' name='submit' value='Pay Now'>
										</div>
									</div>
									 
								</form>
								<hr />
								</div>
								<?php } ?>
							</div>
						<?php } /* end cheque pay */ ?>
						<?php if (in_array('Cash', $paytype_list)) { ?>
							<div class="col-sm-4">
								<?php if (isset($cash_dis)) { ?>
								<div id='cash_div'><a class="btn btn-info col-sm-12">Pay By Cash <i class="fa fa-chevron-down"></i></a></div>
								<script type="text/javascript">
								$(document).ready(function() {
									$('#cash_div').click(function() {
										$('#cash').show();
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
										<label class="inline">Amount:</label><span><b> $<?php echo number_format($payment_total, 2, '.', ','); ?></b></span>
										<input class="btn btn-primary paysubmit" type='submit' name='submit' value='Pay Now'>
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
<?php if ($export_logo_price_option) { ?>
<script type="text/javascript">
$(document).ready(function() {
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

	$('.paysubmit').click(function() {
		$('.paysubmit').hide();
	});
});
<?php } ?>
</script>
<!-- /page content -->