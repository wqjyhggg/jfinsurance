<?php defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' ); ?>
<style>
.nav-tabs > li.active > a{
  background-color: #ffffff !important;
}
.nav-tabs > li > a:hover{
  background-color: #ffffff !important;
}
.btn {
  margin: -15px -12px;
}
.blcok-space {
  margin-bottom: 20px;
}
</style><!-- Plan page content -->
<?php if (isset($menu) && is_array($menu) && (sizeof($menu)>0)) { ?>
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
<div class="right_col" role="main" style="padding-bottom: 60px;">
<?php } else { ?>
<!-- page content -->
<div role="main" style="padding-left: 28px; padding-bottom: 60px;">
<?php } ?>
	<div class="main-div">
		<div class="page-title">
			<div class="col-sm-6"><h3><?php echo $plan_full_name; ?></h3></div>
			<div class="col-sm-6"><h2>Premium: <span id='premium_value'></span></h2></div>
		</div>
		<div class="clearfix"></div>
		<!-- Form Section -->
		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="alert-error"><?php echo empty($error_message) ? '' : $error_message . "<br>";?></div>
				<ul class="nav nav-tabs" id="top-nav-tabs">
					<li class="active"><a data-toggle="tab" id="date_members_tab" href="#date_members">Date / Members</a></li>
					<li ><a data-toggle="tab" id="packages_tab" href="#packages">Packages</a></li>
					<li ><a data-toggle="tab" id="questionnaire_tab" href="#questionnaire" style="display:none;">Questionnaire</a></li>
					<?php if (!empty($plan_id) && !empty($status_id)) { ?>
						<?php if ($status_id == 1 && $user_group_id != 103) { /* qutoe */ ?>
							<li style="float: right;"><a href="<?php echo $pay_url; ?>"><span class="btn btn-info" style='color: #fff;'>Pay</span></a></li>
						<?php } ?> 
						<?php if (($status_id == 2) || ($status_id == 3)) { ?> 
							<?php if ($export_logo_price_option) { ?>
							<li style="float: right;">
							<div class='pull-right spdf-option' style='margin-top: 9px;'>
								<input type='checkbox' class='withlogobox' checked> With Logo <br />
								<input type='checkbox' class='withpricebox' checked> With Price
							</div>
							</li>
							<?php } ?>
							<li style="float: right;"><a href="<?php echo $pdf_url; ?>" target="_blank"><span class="btn btn-info" style='color: #fff;'>Export PDF</span></a></li>
							<?php if (!empty($print_receipt_url)) { ?>
							<li style="float: right;"><a href="<?php echo $print_receipt_url; ?>" target="_blank"><span class="btn btn-info" style='color: #fff;'>Print Receipt</span></a></li>
							<?php } ?>
							<?php if (!empty($print_card_url)) { ?>
							<li style="float: right;"><a href="<?php echo $print_card_url; ?>" target="_blank"><span class="btn btn-info" style='color: #fff;'>Print Card</span></a></li>
							<?php } ?>
							<li style="float: right;"><a href="<?php echo $sendpackage_url . $plan_id; ?>"><span class="btn btn-info" style='color: #fff;'>Send Package</span></a></li>
						<?php } ?>
						<?php if ((($status_id == 3) || ($status_id == 7)) && $user_group_id <= 100 ) { ?>
						<?php   if(time() < strtotime($effective_date)){ ?>
							<li style="float: right;"><a href="<?php echo $cancel_url; ?>" target="_blank"><span class="btn btn-info" style='color: #fff;'>Cancel</span></a></li>
						<?php   }else{?>
							<li style="float: right;"><a href="<?php echo $refund_url; ?>" target="_blank"><span class="btn btn-info" style='color: #fff;'>Refund</span></a></li>
						<?php   } ?>
						<?php } else if (($status_id == 6) && ($user_group_id <= 100) && !empty($refund_letter_url)) { ?>
							<li style="float: right;"><a id="popRefund"><span class="btn btn-info" style='color: #fff;'>Refund Letter</span></a></li>
						<?php } else if (($status_id == 5) && ($user_group_id <= 100) && !empty($cancel_letter_url)) { ?>
							<li style="float: right;"><a href="<?php echo $cancel_letter_url; ?>" target="_blank"><span class="btn btn-info" style='color: #fff;'>Cancel Letter</span></a></li>
						<?php } ?>
					<?php } ?>
				</ul>
				<div class="clearfix"></div>
				<form action='<?php echo $action_url; ?>' method='POST' id='plan_edit_form' class="form-horizontal" id="plan_form">
					<div class="tab-content">
						<div id="date_members" class="tab-pane fade in active">
							<!-- Start data members -->
							<div class="x_panel">
								<div class="x_content">
									<div class="form-group col-sm-6"><h2><label><span>Date / Members</span></label></h2></div>
									<?php if ($user_group_id != 1) { ?>
									<div class="form-group col-sm-3"><label style="font-size: 16px;">Status: <?php echo $status_list[$status_id]['name']; ?> </label></div>
									<input type='hidden' name='status_id' value='<?php echo $status_id; ?>' class="form-control">
									<?php } else { ?>
									<div class="form-group col-sm-3">
										<label style="display: inline-block;">Status:</label>
										<div style="display: inline-block;">
											<select name='status_id' class="form-control">
												<option value='0'>-- select policy status --</option>
												<?php foreach ($status_list as $key => $value) { ?>
												<option value='<?php echo $key; ?>'<?php echo ($key == $status_id) ? 'selected' : ''; ?>><?php echo $value['name']; ?></option>
												<?php } ?>
											</select>
										</div>
									</div>
									<?php } ?>
									<?php if (!empty($plan_cancel_date)) {?>
									<div class="form-group col-sm-3">
										<label class="inline">Cancel Date: ( <?php echo $plan_cancel_date; ?> )</label>
									</div>
									<?php } else if (!empty($plan_refund_date)) {?>
									<div class="form-group col-sm-3">
										<label class="inline">Refund Date: ( <?php echo $plan_refund_date; ?> )</label>
									</div>
									<?php } ?>
									<div class="form-group col-sm-3">
										<label class="col-sm-12">Premium: </label>
										<div class="form_text_show" id='premiumdisplay' style="display: inline-block;">
										</div>
									</div>
									<div class="clearfix"></div>

									<div class="col-sm-12 blcok-space">
										<fieldset>
											<legend>Travel Dates</legend>
											<input type="hidden" name='arrival_date' id='arrival_date' value='<?php echo $arrival_date; ?>' data-date-format="yyyy-mm-dd">
											<input type='hidden' name='dailyrate' step='0.01' id='dailyrate' value='<?php echo $dailyrate; ?>'>
											<input type='hidden' name='totalyears' id='totalyears' value='<?php echo $totalyears; ?>'>
											<input type='hidden' name='premium' step='0.01' id='premium' value='<?php echo $premium; ?>'>
											<input type='hidden' name='isfamilyplan' value='1'>
											<div class="row">
												<div class="form-group col-sm-3">
													<label class="col-sm-12">Apply Date (YYYY-MM-DD):</label>
													<?php if ($user_group_id != 1) { ?>
													<input type="hidden" name='apply_date' value='<?php echo $apply_date; ?>'>
													<?php echo $apply_date; ?>
													<?php } else { ?>
													<div class="input-group date" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd">
														<input class="form-control" size="16" type="text"name='apply_date' value='<?php echo $apply_date; ?>'>
														<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
													</div>
													<?php } ?>
												</div>
												<div class="form-group col-sm-3">
													<label class="col-sm-12">Effective Date (YYYY-MM-DD): </label>
													<div class="input-group date" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd" id='effective_date_div'>
														<input class="setpremium form-control" size="16" type="text" name='effective_date' value='<?php echo $effective_date; ?>'>
														<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
													</div>
													<?php if (!empty($error_effective_date)) { ?>
													<div class="alert-error"><?php echo $error_effective_date; ?></div>
													<?php } ?>
												</div>
												<div class="form-group col-sm-3">
													<label class="col-sm-12">Expiry Date (YYYY-MM-DD): </label>
													<div class="input-group date" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd" id='expiry_date_div'>
														<input size="16" type='text' name='expiry_date' class='setpremium form-control' id='expiry_date' value='<?php echo $expiry_date; ?>'>
														<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
													</div>
													<?php if (!empty($error_expiry_date)) { ?>
													<div class="alert-error"><?php echo $error_expiry_date;?></div>
													<?php } ?>
												</div>
												<div class="form-group col-sm-2">
													<label class="col-sm-12">Days: </label>
													<div class='form_text_show'>
														<input class="form-control" type='number' name='totaldays' id='totaldays' value='<?php echo $totaldays; ?>'>
													</div>
												</div>
											</div>
										</fieldset>
									</div>

									<div class="col-sm-12 blcok-space">
										<fieldset>
											<legend>Insurable Member(s)</legend>
											<input type='hidden' name='customer_id' value='<?php echo !empty($customer_id) ? $customer_id : 0; ?>'>
											<div class="row">
												<label class="col-sm-12">Customer Information</label>
												<div class="col-sm-3">
													<label class="col-sm-12">First Name:</label>
													<div class="input-group col-sm-12">
														<input class="form-control" type='text' name='firstname' value='<?php echo !empty($firstname) ? $firstname : ''; ?>'>
													</div>
													<?php if (!empty($error_firstname)) {?>
														<div class="alert-error"><?php echo $error_firstname; ?></div>
													<?php } ?>
												</div>
												<div class="col-sm-3">
													<label class="col-sm-12">Last Name:</label>
													<div class="input-group col-sm-12">
														<input class="form-control" type='text' name='lastname' value='<?php echo !empty($lastname) ? $lastname : ''; ?>'>
													</div>
													<?php if (!empty($error_lastname)) {?>
														<div class="alert-error"><?php echo $error_lastname; ?></div>
													<?php } ?>
												</div>
												<div class="col-sm-3">
													<label class="col-sm-12">Birth Date (YYYY-MM-DD):</label>
													<div class="input-group date" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd" data-date-end-date="0d">
														<input size="16" type="text" class='setpremium form-control' name='birthday' value='<?php echo !empty($birthday) ? $birthday : ''; ?>'>
														<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
													</div>
													<?php if (!empty($error_birthday)) {?>
														<div class="alert-error"><?php echo $error_birthday; ?></div>
													<?php } ?>
												</div>
												<div class="col-sm-3">
													<div class="row">
														<div class="col-sm-6">
															<label class="col-sm-12">Gender: </label>
															<div class="input-group col-sm-12">
																<select name='gender' class="form-control" style="padding: 6px 2px;">
																	<option value='M' <?php echo (empty($gender) || ($gender != 'F')) ? "selected" : ""; ?>>Male</option>
																	<option value='F' <?php echo (!empty($gender) && ($gender == 'F')) ? "selected" : ""; ?>>Female</option>
																</select>
															</div>
														</div>
														<div class="col-sm-6">
															<label class="col-sm-12">&nbsp;</label>
															<div class="col-sm-12">
															<?php if ((($status_id == 2) || ($status_id == 3) || ($status_id == 4)) && !empty($customer_id) && $user_group_id !=3 && $user_group_id != 103) { ?>
																<a class="btn btn-primary" href='<?php echo $claimurl . $customer_id; ?>'>Claim</a>
															<?php } ?>
															</div>
														</div>
													</div>
												</div>
											</div>

											<div id='family_member'>
												<?php for ($i = 1; $i <= $max_member; $i++) { ?>
												<div class="row" id='customer_member_<?php echo $i; ?>' style='display: none'>
													<input type='hidden' name='customer_id_<?php echo $i; ?>' id='customer_id_<?php echo $i; ?>' value='<?php echo !empty(${'customer_id_'.$i}) ? ${'customer_id_'.$i} : 0; ?>'>
													<hr />
													<div class="col-sm-12">
														<label>Family Member <?php echo $i; ?> </label>
														<span class="alert-error" id='errormessage_<?php echo $i;?>'></span>
													</div>
													<div class="col-sm-3">
														<label class="col-sm-12">First Name: </label>
														<div class="input-group col-sm-12">
															<input class="form-control" type='text' name='firstname_<?php echo $i; ?>' id='firstname_<?php echo $i; ?>' value='<?php echo !empty(${'firstname_'.$i}) ? ${'firstname_'.$i} : ''; ?>'>
														</div>
													</div>
													<div class="col-sm-3">
														<label class="col-sm-12">Last Name: </label>
														<div class="input-group col-sm-12">
															<input class="form-control" type='text' name='lastname_<?php echo $i; ?>' id='lastname_<?php echo $i; ?>' value='<?php echo !empty(${'lastname_'.$i}) ? ${'lastname_'.$i} : ''; ?>'>
														</div>
													</div>
													<div class="col-sm-3">
														<label class="col-sm-12">Birth Date: </label>
														<div class="input-group date" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd" data-date-end-date="0d">
															<input size="16" type="text" class='setpremium form-control' name='birthday_<?php echo $i; ?>' id='birthday_<?php echo $i; ?>' value='<?php echo !empty(${'birthday_'.$i}) ? ${'birthday_'.$i} : ''; ?>'>
															<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
														</div>
														<?php if (!empty(${'error_birthday_'.$i})) {?>
														<div class="alert-error"><?php echo ${'error_birthday_'.$i}; ?></div>
														<?php } ?>
													</div>
													<div class="col-sm-3">
														<div class="row">
															<div class="col-sm-4">
																<label class="col-sm-12">Gender: </label>
																<div class="input-group col-sm-12">
																	<select name='gender_<?php echo $i; ?>'
																		id='gender_<?php echo $i; ?>' class="form-control"
																		style="padding: 6px 2px;">
																		<option value='M'
																			<?php echo (empty(${'gender_'.$i}) || (${'gender_'.$i} != 'F')) ? "selected" : ""; ?>>Male</option>
																		<option value='F'
																			<?php echo (!empty(${'gender_'.$i}) && (${'gender_'.$i} == 'F')) ? "selected" : ""; ?>>Female</option>
																	</select>
																</div>
															</div>
															<div class="col-sm-4">
																<label class="col-sm-12">&nbsp;</label>
																<?php if ((($status_id == 2) || ($status_id == 3) || ($status_id == 4)) && !empty(${'customer_id_'.$i}) && $user_group_id !=3 && $user_group_id != 103) {?>
																<a class="btn btn-primary" href='<?php echo $claimurl . ${'customer_id_'.$i}; ?>'>Claim</a>
																<?php } ?>
															</div>
															<div class="col-sm-4">
																<label class="col-sm-12">&nbsp;</label>
																<input type='button' onclick='remove_member(<?php echo $i;?>)' value='Remove' data-toggle="tooltip" title="Remove Memeber" class="btn btn-info">
															</div>
														</div>
													</div>
												</div>
												<?php } ?>
												<br />
												<div class="row">
													<div class="col-sm-12">
														<?php if ($isprocessplan && ($status_id != 5) && ($status_id != 6)) { ?>
														<input class="btn btn-info" type='button' id='addmorememberid' name='addmorememberid' value='Add More Member' onclick='addmoremember(1);'>
														<?php } ?>
													</div>
												</div>
											</div>
											<!-- Insurable Members  End-->
										</fieldset>
									</div>

									<div class="col-sm-12 blcok-space">
										<fieldset>
											<legend>Address</legend>
											<div class="row">
												<div class="form-group col-sm-3">
													<label class="col-sm-12">Street No: </label>
													<div class="input-group col-sm-12">
														<input class="form-control" type='text' name='street_number' value="<?php echo $street_number; ?>">
													</div>
													<?php if (!empty($error_street_number)) {?>
													<div class="alert-error"><?php echo $error_street_number; ?></div>
													<?php } ?>
												</div>
												<div class="form-group col-sm-3">
													<label class="col-sm-12">Street Name: </label>
													<div class="input-group col-sm-12">
														<input class="form-control" type='text' name='street_name' value="<?php echo str_replace("\"", "'", $street_name); ?>">
													</div>
													<?php if (!empty($error_street_name)) {?>
													<div class="alert-error"><?php echo $error_street_name; ?></div>
													<?php } ?>
												</div>
												<div class="form-group col-sm-3">
													<label class="col-sm-12">Suite No.: </label>
													<div class="input-group col-sm-12">
														<input class="form-control" type='text' name='suite_number' value="<?php echo $suite_number; ?>">
													</div>
												</div>
												<div class="form-group col-sm-3">
													<label class="col-sm-12">City: </label>
													<div class="input-group col-sm-12">
														<input class="form-control" type='text' name='city' value="<?php echo $city; ?>">
													</div>
													<?php if (!empty($error_city)) {?>
													<div class="alert-error"><?php echo $error_city; ?></div>
													<?php } ?>
												</div>
											</div>
											<div class="row">
												<div class="form-group col-sm-3">
													<label class="col-sm-12">Province: </label>
													<div class="input-group col-sm-12">
														<div id='province2_div'></div>
													</div>
												</div>
												<div class="form-group col-sm-3">
													<label class="col-sm-12">Country: </label>
													<div class="input-group col-sm-12">
														<div id='country2_div'></div>
													</div>
												</div>
												<div class="form-group col-sm-3">
													<label class="col-sm-12">Postcode: </label>
													<div class="input-group col-sm-12">
														<input class="form-control" type='text' name='postcode' value='<?php echo $postcode; ?>'>
													</div>
													<?php if (!empty($error_postcode)) {?>
													<div class="alert-error"><?php echo $error_postcode; ?></div>
													<?php } ?>
												</div>
											</div>
											<div class="row">
												<div class="col-sm-3">
													<label class="col-sm-12">Phone1: </label>
													<div class="input-group col-sm-12">
														<input class="form-control" type='text' name='phone1' value='<?php echo $phone1; ?>'>
													</div>
													<?php if (!empty($error_phone1)) {?>
													<div class="alert-error"><?php echo $error_phone1; ?></div>
													<?php } ?>
												</div>
												<div class="col-sm-3">
													<label class="col-sm-12">Phone2: </label>
													<div class="input-group col-sm-12">
														<input class="form-control" type='text' name='phone2' value='<?php echo $phone2; ?>'>
													</div>
												</div>
											</div>
										<!-- Address End-->
										</fieldset>
									</div>
									<br/>

									<div class="col-sm-12 blcok-space">
										<fieldset>
											<legend>Contact</legend>
											<div class="row">
												<div class="col-sm-3">
													<label class="col-sm-12">Email: </label>
													<div class="input-group col-sm-12">
														<input class="form-control" type='text' name='contact_email' value='<?php echo $contact_email; ?>'>
													</div>
													<?php if (!empty($error_contact_email)) {?>
													<div class="alert-error"><?php echo $error_contact_email; ?></div>
													<?php } ?>
												</div>
												<div class="col-sm-3">
													<label class="col-sm-12">Contact Phone: </label>
													<div class="input-group col-sm-12">
														<input class="form-control" type='text' name='contact_phone' value='<?php echo $contact_phone; ?>'>
													</div>
												</div>
												<div class="col-sm-3">
													<label class="col-sm-12">Country of Origin: </label>
													<div class="input-group col-sm-12">
														<input class="form-control" type='text' name='residence' value='<?php echo $residence; ?>'>
													</div>
												</div>
										<!-- Contact End-->
										</fieldset>
									</div>

									<div class="col-sm-12 blcok-space" <?php if ($user_group_id > 100) { ?>style="display: none;" <?php } ?>>
										<fieldset>
											<legend>Special Note/Instructions</legend>
											<div class="row">
												<div class="col-sm-12">
													<label class="col-sm-12">Notes: </label>
													<div class="input-group col-sm-12">
														<textarea class="form-control" name="note"><?php echo $note; ?></textarea>
													</div>
												</div>
											</div>
										</fieldset>
									</div>
								</div>
							</div>
							<!-- End data members -->
						</div>
						<div id="packages" class="tab-pane fade">
							<!-- Start packages -->
							<h3>Packages</h3>
							<p>Some content in menu 1.</p>
							<!-- End packages -->
						</div>
						<div id="questionnaire" class="tab-pane fade">
							<!-- Start questionnaire -->
							<h3>More Packages</h3>
							<p>Some content in menu 2.</p>
							<!-- End questionnaire -->
						</div>
					</div>
					<div class="row">
						<div class="col-sm-4 text-center">
							<buttom type='button' id='page-prev' class='btn btn-info disabled'>Prev</buttom>
						</div>
						<div class="col-sm-4 text-center">
							<buttom type='button' id='page-submit' class='btn btn-info'>Submit</buttom>
						</div>
						<div class="col-sm-4 text-center">
							<buttom type='button' id='page-next' class='btn btn-info'>Next</buttom>
						</div>
					</div>
				</form>
			</div>
		</div>
		<?php if ($show_history) { ?>
		<div class="row">
			<div class="col-sm-12">
				<div class="x_panel">
					<div class="x_title">
						<h2>
							Policy History<small></small>
						</h2>
						<div class="clearfix"></div>
					</div>
					<div class="x_content">
						<div class="row" id='payment_history'>
							<?php if (!empty($payments) && is_array($payments) && (sizeof($payments > 0))) { ?>
		                  	<div class="col-sm-12">
		                  		<button type="button" class="btn btn-info" data-toggle="collapse" data-target="#history1">Payments <span class="fa fa-chevron-down"></span></button>
		                  		<div id="history1" class="collapse">
									<button type="button" class="btn btn-payment-sort" data-type="date">Sort By Date</button>
									<button type="button" class="btn btn-payment-sort" data-type="type">Sort By Type</button>
									<form action='<?php echo $makepay_url; ?>' method='POST' class="form-horizontal">
										<input type='hidden' name='<?php echo $csrf['name']; ?>' value='<?php echo $csrf['value']; ?>'>
										<div class="table-responsive">
											<table class="table table-hover table-bordered">
												<thead>
													<tr>
														<th>&nbsp;</th>
														<th>Last Update</th>
														<th>Type</th>
														<th>Pay Type</th>
														<th>Amount</th>
														<th>Rate</th>
														<th>Pay Status</th>
														<th>Info</th>
														<th>Notes</th>
													</tr>
												</thead>
												<tbody>
													<?php
													foreach ( $payments as $p ) {
														$pay_str = '';
														if ($p ['pay_type'] == 'up_commission') continue;
														if ($p ['pay_type'] == 'refund_up_commission') continue;
														if ($p ['pay_type'] == 'cancel_up_commission') continue;
														$sbstr = substr ( $p ['pay_type'], 0, 6 );
														if ($p ['ispaid']) {
															$pay_str = 'Paid';
														} else {
															if ($sbstr == 'refund') {
																$pay_str = "<a href='" . $revert_url . $p ['payment_id'] . "'>Revert Refund</a>";
															} else if ($sbstr == 'cancel') {
																$pay_str = "<a href='" . $revert_url . $p ['payment_id'] . "'>Revert Cancel</a>";
															} else {
																$pay_str = '-';
															}
														}
														$pay_info = '';
														if (! empty ( $p ['invoice_num'] )) $pay_info .= "[" . $p ['invoice_num'] . "]";
														if (! empty ( $p ['bank_name'] )) $pay_info .= "[" . $p ['bank_name'] . "]";
														if (! empty ( $p ['payor_name'] )) $pay_info .= "[" . $p ['payor_name'] . "]";
														if (! empty ( $p ['cheque_number'] )) $pay_info .= "[" . $p ['cheque_number'] . "]";
														if (! empty ( $p ['pay_to'] )) $pay_info .= "[" . $p ['pay_to'] . "]";
														if (! empty ( $p ['name'] )) $pay_info .= "[" . $p ['name'] . "]";
														if (! empty ( $p ['first5'] )) $pay_info .= "[" . $p ['first5'] . "]";
														if (! empty ( $p ['last4'] )) $pay_info .= "[" . $p ['last4'] . "]";
														if (! empty ( $p ['expiry_month'] )) $pay_info .= "[" . $p ['expiry_month'] . "]";
														if (! empty ( $p ['expiry_year'] )) $pay_info .= "[" . $p ['expiry_year'] . "]";
													?>
													<tr>
														<td><?php if (empty($p['ispaid'])) { ?><input type='checkbox' name='payment[]' value='<?php echo $p['payment_id']; ?>'><?php } ?></td>
														<td><?php echo $p['last_update']; ?></td>
														<td><?php echo $p['pay_type']; ?></td>
														<td><?php echo $p['pay_mothed']; ?></td>
														<td><?php echo $p['amount']; ?></td>
														<td><?php echo $p['rate'] . "%"; ?></td>
														<td><?php echo $pay_str; ?></td>
														<td><?php echo $pay_info; ?></td>
														<td><?php echo (strlen($p['note']) > 60) ? (substr($p['note'], 0, 57) . "...") : $p['note']; ?></td>
													</tr>
													<?php } ?>
												</tbody>
											</table>
										</div>
										<div class="row">
											<div class="col-sm-12"><input type="submit" class="btn btn-primary" name='submit' value='Make Pay'></div>
										</div>
									</form>
									<hr />
								</div>
							</div>
							<?php 	} ?>
						</div>
						<div class="row">
							<?php 	if (!empty($activelogs) && is_array($activelogs) && (sizeof($activelogs > 0))) { ?>
							<div class="col-sm-12">
								<button type="button" class="btn btn-info" data-toggle="collapse" data-target="#history2"> Changes <span class="fa fa-chevron-down"></span></button>
								<div id="history2" class="collapse">
									<div class="table-responsive">
										<table class="table table-hover table-bordered">
											<thead>
												<tr>
													<th>Username</th>
													<th>Date Time</th>
													<th>Message</th>
												</tr>
											</thead>
											<tbody>
											<?php foreach ($activelogs as $p) { ?>
												<tr>
													<td><?php echo $p['username']; ?></td>
													<td><?php echo $p['tm']; ?></td>
													<td><?php echo (strlen($p['message']) > 120) ? (substr($p['message'], 0, 117) . "...") : $p['message']; ?></td>
												</tr>
											<?php } ?>
											</tbody>
										</table>
									</div>
								</div>
							</div>
							<?php } ?>
						</div>
					</div>
					<!--/x_content end-->
				</div>
				<!--x_panel-->
			</div>
		</div>
		<?php } /* history */ ?>
		<div class="row">
			<div class="col-sm-12 alert-error float-error" title="Click to Close the notice" id='error_message_ajax' style="display: none;"></div>
		</div>
	</div>
</div>
<!-- /page content -->
<script>
var need_questionnaire = 0;
var cur_max_member = 0;

$('a[data-toggle="tab"]').on('click', function (e) {
	var id = $(this).attr('id');

	if (id == 'date_members_tab') {
		$('#page-prev').addClass('disabled');
		$('#page-next').removeClass('disabled');
	} else if (id == 'packages_tab') {
		$('#page-prev').removeClass('disabled');
		if (need_questionnaire) {
			$('#page-next').removeClass('disabled');
		} else {
			$('#page-next').addClass('disabled');
		}
	} else if (id == 'questionnaire_tab') {
		$('#page-prev').removeClass('disabled');
		$('#page-next').addClass('disabled');
	} 
})

$('#error_message_ajax').click(function(){
	$('#error_message_ajax').css('display','none');
});

$('#page-prev').on('click', function(e) {
	$("ul#top-nav-tabs li.active").prev('li').find('a').trigger("click");
});

$('#page-next').on('click', function(e) {
	$("ul#top-nav-tabs li.active").next('li').find('a').trigger("click");
});

/*
<ul class="nav nav-tabs" id="top-nav-tabs">
<li class="active"><a data-toggle="tab" id="date_members_tab" href="#date_members">Date / Members</a></li>
<li ><a data-toggle="tab" id="packages_tab" href="#packages">Packages</a></li>
<li ><a data-toggle="tab" id="questionnaire_tab" href="#questionnaire">Questionnaire</a></li>
	$('#error_next_page').click(function(){
		$('#error_next_page').css('display','none');
	});
	*/
</script>

<script>
$( document ).ready(function() {
	$.ajax({
		url: '<?php echo $province_url; ?>',
		type: 'GET',
		success: function(data, textStatus, jqXHR) {
        	$('#province2_div').html(data);
    	},
	});
	$.ajax({
		url: '<?php echo $country_url; ?>',
		type: 'GET',
		success: function(data, textStatus, jqXHR) {
        	$('#country2_div').html(data);
    	},
	});

	$('.setpremium').change(get_premium); 

	get_premium();
	addmoremember(0);

	$( ".btn-payment-sort" ).click(sorting_payment);

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
});

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

function remove_member(i) {
	if(confirm("Are you sure to delete this info?") == true){
		var s, d;
		for (j = i + 1 ; j <= cur_max_member; j++) {
			s = '#customer_id_' + j;
			d = '#customer_id_' + i;
			$(d).val($(s).val());
			s = '#firstname_' + j;
			d = '#firstname_' + i;
			$(d).val($(s).val());
			s = '#lastname_' + j;
			d = '#lastname_' + i;
			$(d).val($(s).val());
			s = '#birthday_' + j;
			d = '#birthday_' + i;
			$(d).val($(s).val());
			s = '#gender_' + j;
			d = '#gender_' + i;
			$(d).val($(s).val());
			i++;
		}
		$('#customer_id_' + cur_max_member).val(0);
		$('#firstname_' + cur_max_member).val('');
		$('#lastname_' + cur_max_member).val('');
		$('#birthday_' + cur_max_member).val('');
		$('#gender_' + cur_max_member).val('M');
		$('#customer_member_' + cur_max_member).hide();
		get_premium();
		cur_max_member--;
	}
}

function addmoremember(addnumber) {
	// Remove all error message
	for (i = 1; i <= <?php echo $max_member; ?>; i++) {
		$('#firstname_' + i).removeClass('alert-error-input');
		$('#lastname_' + i).removeClass('alert-error-input');
		$('#birthday_' + i).removeClass('alert-error-input');
		if ( !$('#firstname_' + i).val() && !$('#lastname_' + i).val() && !$('#birthday_' + i).val()) {
			break;
		}
		if ( !$('#firstname_' + i).val() ) {
			$('#firstname_' + i).addClass('alert-error-input');
		}
		if ( !$('#lastname_' + i).val() ) {
			$('#lastname_' + i).addClass('alert-error-input');
		}
		if ( !$('#birthday_' + i).val() ) {
			$('#birthday_' + i).addClass('alert-error-input');
		}
		if ( !$('#firstname_' + i).val() || !$('#lastname_' + i).val() || !$('#birthday_' + i).val()) {
			break;
		}
		$('#errormessage_' + i).html("");
	}
	cur_max_member += addnumber;
	if (addnumber > 0) {
		if (cur_max_member > <?php echo $max_member; ?>) {
			cur_max_member -= addnumber;
			alert("You reached maxium numbers");
			return;
		}
		$('#customer_id_' + cur_max_member).val(0);
		$('#firstname_' + cur_max_member).val('');
		$('#lastname_' + cur_max_member).val('');
		$('#birthday_' + cur_max_member).val('');
		$('#gender_' + cur_max_member).val('M');
		$('#customer_member_' + cur_max_member).show();
	}
}

function get_premium() {
<?php if (empty($batch_number)) { ?>
	$.ajax({
		url: '<?php echo $premium_url; ?>',
		type: 'get',
		data: $('#plan_form').serialize(),
		success: function(data, textStatus, jqXHR) {
			if (data['status'] == 'OK') {
        		$('input[name="premium"]').val(data['premium']);
				$('#premium_value').html(['premium']);
				$('#totalyears').val(data['totalyears']);
				$('#totaldays').val(data['totaldays']);
				$('#dailyrate').val(data['dailyrate']);
				if (data['message']) {
					$('#error_message_ajax').html(data['premiumarr']['message']);
					$('#error_message_ajax').css('display','block');
				} else {
					$('#error_message_ajax').html('');
					$('#error_message_ajax').css('display','none');
				}
				if (data['premiumarr']['force_deductable']) {
	        		$('input[name="force_deductable"]').val(data['premiumarr']['force_deductable']);
				} else {
	        		$('input[name="force_deductable"]').val(0);
				}

				if (age85 && (data['premiumarr']['totalyears'] <= 85)) {
					if ( $( "#deductible_amount_div" ).length ) {
						$.ajax({
							url: '<?php echo $deductible_amount_url; ?>',
							success: function(data, textStatus, jqXHR) {
					        	$('#deductible_amount_div').html(data);
								get_premium();
					    	},
						});
					}
					age85 = 0;
				} else if (!age85 && (data['premiumarr']['totalyears'] > 85)) {
					if ( $( "#deductible_amount_div" ).length ) {
						$.ajax({
							url: '<?php echo $deductible_amount_url; ?>/500',
							success: function(data, textStatus, jqXHR) {
					        	$('#deductible_amount_div').html(data);
								get_premium();
					    	},
						});
					}
					age85 = 1;
				}
				//if (data['premiumarr']['premium']) {
				//	$('#goto_next_page').show();
				//} else {
				//	$('#goto_next_page').hide();
				//}
			} else if (data['status'] == 'Days') {
				$('#totaldays').val(data['days']);
			} else {
				// $('#goto_next_page').hide();
				if (data['message']) {
					$('#error_next_page').html(data['message']);
					$('input[name="premium"]').val(0);
				}
			}
    	},
	});
<?php } ?>
} 
</script>
	<script type="text/javascript">
$(document).ready(function(){
	$('#formid').on('keyup keypress', function(e) {
		var keyCode = e.keyCode || e.which;
		if (keyCode === 13) {
			e.preventDefault();
			return false;
		}
	});
	$("#arrival_date_div").datepicker({
        startDate: '-5y',
        endDate: '+2y',
    });
    $('#checkboxdays').change(function(){
        if (this.checked) {
        	var effective = $('#effective_date_div').datepicker('getDate');
        	var myDate1 = new Date(effective);
        	myDate1.setFullYear(myDate1.getFullYear() + 1);
        	var myDate3 = myDate1.getTime() - 86400000;
        	var myDate = new Date(myDate3);
        	$('#expiry_date_div').datepicker('setDate', myDate);
        	get_premium();
        }
    });
    $('#totaldays').change(function(){
        var days = $('#totaldays').val();
        var effective = $('#effective_date_div').datepicker('getDate');
        var myDate = new Date(effective);
        days--;
        var tm = myDate.getTime() + (days * 86400000) + 43200000;
        myDate.setTime(tm);
        $('#expiry_date_div').datepicker('setDate', myDate);
        get_premium();
    });
});
</script>