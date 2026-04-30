<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$hideForFrench = false;
$Agree = $this->lang->line("Agree");
if ($Agree != "Agree") {
  $hideForFrench = true;
}
?>

<!-- Plan page content -->
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
        <div class="right_col" role="main" style="padding-bottom:60px;">
<?php } else { ?>
        <!-- page content -->
        <div role="main" style="padding-left:28px;padding-bottom:60px;">
<?php } ?>
          <div class="main-div">
            <div class="page-title">
              <div class="title_left">
                <h3><?php echo $p_header; ?></h3>
              </div>
            </div>
            <div class="clearfix"></div>
           <!-- Form Section -->
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2><?php echo $this->lang->line("Policy Detail"); ?><small></small></h2>
                    <?php if ($user_group_id < 100) { ?>
                      <?php if (!empty($claims)) { ?>
                        <span style="color: red;"><?php echo $this->lang->line("There is an existing claim or open cases."); ?></span>
                      <?php } ?>
                    <?php } ?>
					<?php if (!empty($plan_id) && !empty($status_id)) { ?>
						<div class="pull-right text-right">
						<?php /* it should be created plan */ ?>
						<?php if ($isprocessplan) { ?>
						<?php if ($status_id == Plan_model::QUOTE && $user_group_id != 103 && $next_url) { /* qutoe */ ?>

						<a href='<?php echo $pay_url; ?>'><span class="btn btn-info" style='color:#fff;'><?php echo $this->lang->line("Pay"); ?></span></a>
						<?php } ?> 

						<?php if($user_group_id != Plan_model::PAID && $user_group_id != 103){ ?>
						<a href='<?php echo $renewal_url; ?>'><span class="btn btn-info" style='color:#fff;'><?php echo $this->lang->line("Renewal"); ?></span></a>
						<a href='<?php echo $copy_url; ?>'><span class="btn btn-info" style='color:#fff;'><?php echo $this->lang->line("Copy"); ?></span></a>
						<?php } ?>
						<?php if ($status_id > 1 && $user_group_id != Plan_model::PAID) { ?>
						<?php 	if ((($status_id == Plan_model::SOLD) || ($status_id == Plan_model::PAID)) && ($product_short != 'NUS') && ($product_short != 'JUS')) { ?> 
							<a href='<?php echo $sendpackage_url . $plan_id; ?>'><span class="btn btn-info"  style='color:#fff;'><?php echo $this->lang->line("Send Package"); ?></span></a>
						<?php 	} ?>
						<?php 	if (($status_id == Plan_model::SOLD) || ($status_id == Plan_model::PAID)) { ?> 
							<?php if ($export_logo_price_option) { ?>
							<div class='pull-right spdf-option'><input type='checkbox' class='withlogobox' checked> With Logo <br /><input type='checkbox' class='withpricebox' checked> With Price </div>
							<?php } ?>
							<a class="btn btn-info pull-right" target="_blank" href='<?php echo $pdf_url; ?>'><?php echo $this->lang->line("Export PDF"); ?></a>
							<?php if (!empty($print_receipt_url) && !$hideForFrench) { ?>
							<a href='<?php echo $print_receipt_url; ?>' target="_blank"><span class="btn btn-info" style='color:#fff;'><?php echo $this->lang->line("Print Receipt"); ?></span></a>
							<?php } ?>
							<?php if (!empty($print_card_url) && !$hideForFrench) { ?>
							<a href='<?php echo $print_card_url; ?>' target="_blank"><span class="btn btn-info" style='color:#fff;'><?php echo $this->lang->line("Print Card"); ?></span></a>
							<?php } ?>
						<?php 	} ?>
						<?php if ((($status_id == Plan_model::PAID) || ($status_id == Plan_model::SOLD) || ($status_id == Plan_model::CHANGED)) && $user_group_id <= 100 ) { ?>
							<?php if (!empty($plan) && !empty($plan['monthlypay'])) { ?>
							<a href='<?php echo $terminate_url . $plan_id; ?>'><span class="btn btn-info" style='color:#fff;'><?php echo $this->lang->line("Terminate"); ?></span></a>
							<?php } ?>
							<a href='<?php echo $cancel_url . $plan_id; ?>'><span class="btn btn-info" style='color:#fff;'><?php echo $this->lang->line("Cancel"); ?></span></a>
							<a href='<?php echo $refund_url . $plan_id; ?>'><span class="btn btn-info" style='color:#fff;'><?php echo $this->lang->line("Refund"); ?></span></a>
						<?php } else if (($status_id == Plan_model::REFUND) && ($user_group_id <= 100) && !empty($refund_letter_url)) { ?>
							<a id="popRefund"><span class="btn btn-info" style='color:#fff;'><?php echo $this->lang->line("Refund Letter"); ?></span></a>
						<?php } else if (($status_id == Plan_model::CANCEL) && ($user_group_id <= 100) && !empty($cancel_letter_url)) { ?>
							<a target='_blank' href='<?php echo $cancel_letter_url; ?>'><span class="btn btn-info" style='color:#fff;'><?php echo $this->lang->line("Cancel Letter"); ?></span></a>
						<?php } ?>
						<?php } ?>
						<?php } ?>
						</div>
					<?php } ?>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    
					<!--<script src="<?php echo base_url(); ?>js/jquery-3.0.0.min.js" type="text/javascript"></script>-->
					<?php if (!empty($error_message)) {?>
					<div class="alert-error">
						<?php echo $error_message . "<br>";?>
					</div>
					<?php } ?>

					<form action='<?php echo $action_url; ?>' method='POST' id='plan_edit_form' class="form-horizontal">
						<input type='hidden' name='<?php echo $csrf['name']; ?>' value='<?php echo $csrf['value']; ?>'>
						<input type='hidden' name='plan_id' value='<?php echo $plan_id; ?>'>
						<input type='hidden' name='product_short' value='<?php echo $product_short; ?>'>
						<input type='hidden' name='force_deductable' value=''>
						<input type='hidden' name='batch_number' value='<?php echo $batch_number; ?>'>
					<?php if (empty($plan_id) || empty($status_id)) { ?>
						<?php /* it should be new plan */ ?>
						<input type='hidden' name='status_id' value='0'>
					<div class="row" style="margin-bottom:15px;">
						<div class="form-group col-sm-3">
							<label><span><?php echo $plan_full_name; ?></span></label>
						</div>
						<div class="form-group col-sm-3">
							<label style="text-transform: capitalize;"><?php echo $this->lang->line("By Agent"); ?><?php echo "[ AgentID:" . $policy_user['user_id'] . " ] "; ?>: <?php echo htmlspecialchars($policy_user['firstname'] . " " . $policy_user['lastname']); ?></label>
						</div>
					</div>
					<?php } else { ?>
					<div class="row" style="margin-bottom:15px;">
						
						<div class="form-group col-sm-3">
							<label><span><?php echo $plan_full_name; ?></span></label>
							<label><?php if ($status_id < 2) { ?><?php echo $this->lang->line("Quote"); ?><?php } else {?><?php echo $this->lang->line("Policy"); ?><?php } ?> <?php echo $this->lang->line("Number"); ?>: <span><?php echo $policy; ?></span></label>
							 
						</div>
						
						<?php if (($beuser_user_id == 1) || ($beuser_user_id == 2762)) { ?>
						<div class="form-group col-sm-3">
							<label style="display: inline-block;"><?php echo $this->lang->line("By Agent"); ?></label>
							<div style="display: inline-block;">
								<input class="form-control" type="text" name='user_id' value='<?php echo $user_id; ?>'>
							</div>
						</div>
						<?php } else { ?>
						<div class="form-group col-sm-3">
							<label style="text-transform: capitalize;"><?php echo $this->lang->line("By Agent"); ?><?php echo "[ AgentID:" . $policy_user['user_id'] . " ] "; ?>: <?php echo htmlspecialchars($policy_user['firstname'] . " " . $policy_user['lastname']); ?></label>
						</div>
						<?php } ?>
						<?php if (($user_group_id != 1) || (($beuser_user_id != 1) && ($beuser_user_id != 2762) && ($beuser_user_id != 2254) && ($beuser_user_id != 2661) && ($beuser_user_id != 4272) )) { ?>
							<?php /* it is school or brokerage or agent */ ?>
							<div class="form-group col-sm-3">
								<label style="font-size:16px;"><?php echo $this->lang->line("Status"); ?>: <?php echo $status_list[$status_id]['name']; ?> </label>
							</div>
							<input type='hidden' name='status_id' value='<?php echo $status_id; ?>' class="form-control">
							<?php } else { ?>
							<div class="form-group col-sm-3">
							<label style="display:inline-block;vertical-align:middle;"><?php echo $this->lang->line("Status"); ?>:</label>
							<div style="display:inline-block;vertical-align:middle;">
							<select name='status_id' class="form-control">
								<option value='0'> -- <?php echo $this->lang->line("select policy status"); ?> -- </option>
								<?php foreach ($status_list as $key => $value) { ?>
								<option value='<?php echo $key; ?>' <?php echo ($key == $status_id) ? 'selected' : ''; ?>><?php echo $value['name']; ?></option>
								<?php } ?>
							</select></div>
							</div>
							<?php } ?>
							<?php if (!empty($plan) && !empty($plan['monthlypay'])) { ?>
							<div class="form-group col-sm-3">
							<label style="display:inline-block;vertical-align:middle;">Payment Plan: Monthly</label>
							</div>
							<?php } ?>
					</div>
					<?php } ?>
	
					<div class="row">
						
						<div class="col-sm-12">
						<fieldset>
   						 <legend><?php echo $this->lang->line("Travel Dates"); ?></legend>
   						 <div class="row">
							<div class="form-group col-sm-3">
								<label class="col-sm-12"><?php echo $this->lang->line("Apply Date"); ?> (YYYY-MM-DD):</label>
								<?php if ($user_group_id != 1) { ?>
			                        <input type="hidden" name='apply_date' value='<?php echo $apply_date; ?>'>
			                        <?php echo $apply_date; ?>
								<?php } else { ?>
								<div class="input-group date" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd">
			                        <input class="form-control" size="16" type="text" name='apply_date' value='<?php echo $apply_date; ?>'>
			                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
			                    </div>
								<?php } ?>
							</div>
							<div class="form-group col-sm-3">
								<label class="col-sm-12"><?php echo $this->lang->line("Arrival Date"); ?> (YYYY-MM-DD): </label>
								<div class="input-group date" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd" id='arrival_date_div' >
			                        <input class="form-control" size="16" type="text" name='arrival_date' id='arrival_date' value='<?php echo $arrival_date; ?>' data-date-format="yyyy-mm-dd">
			                        
			                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
			                    </div>
			                    <?php if (!empty($error_arrival_date)) { ?>
			                    <div class="alert-error"> 
			                        <?php echo $error_arrival_date; ?>
			                    </div>
			                    <?php } ?>

							</div>
							<div class="form-group col-sm-3">
								<label class="col-sm-12"><?php echo $this->lang->line("Effective Date"); ?> (YYYY-MM-DD): </label>
								<div class="input-group date" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd" id='effective_date_div'>
			                        <input class="setpremium form-control" size="16" type="text" name='effective_date' id='effective_date' value='<?php echo $effective_date; ?>'>
			                        
			                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
			                    </div>
			                    <?php if (!empty($error_effective_date)) { ?>
			                    <div class="alert-error">
			                    	<?php echo $error_effective_date; ?> 
			                	</div>
			                    <?php } ?>
							</div>
							<div class="form-group col-sm-3">
								<label class="col-sm-12"><?php echo $this->lang->line("Expiry Date"); ?> (YYYY-MM-DD): </label>
						  		<div class="input-group date" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd" id='expiry_date_div'>
									<input size="16" type='text' name='expiry_date' class='setpremium form-control' id='expiry_date' value='<?php echo $expiry_date; ?>'>
									
			                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
								</div>
								<?php if (!empty($error_expiry_date)) { ?>
								<div class="alert-error">
									<?php echo $error_expiry_date;?> 															
								</div>
								<?php } ?>
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
								<label class="inline"><?php echo $this->lang->line("Refund Date"); ?>:</label>
								<span><?php echo $plan_refund_date; ?></span>
							</div>
						</div>
						<?php } ?>
						<?php if ($batch_number ) { ?>
								<div class="row">
									<div class="col-sm-3">
										<label class="col-sm-12"><?php echo $this->lang->line("Days"); ?>: </label>
										<div class='form_text_show'>
											<input class="form-control" type='number' name='totaldays' id='totaldays' value='<?php echo $totaldays; ?>' >
										</div>
									</div>
									<div class="col-sm-3">
										<label class="col-sm-12"><?php echo $this->lang->line("Daily Rate"); ?>: </label>
										<div class='form_text_show'>
											<input class="form-control" type='number' name='dailyrate' step='0.01' id='dailyrate' value='<?php echo $dailyrate; ?>'>
										</div>
									</div>
									<div class="col-sm-3">
										<label class="col-sm-12"><?php echo $this->lang->line("Age"); ?>: </label>
										<div class='form_text_show'>
											<input class="form-control" type='number' name='totalyears' id='totalyears' value='<?php echo $totalyears; ?>'>
										</div>
									</div>
									<div class="col-sm-3">
										<label class="col-sm-12"><?php echo $this->lang->line("Premium"); ?>: </label>
										<div class="form_text_show" id='premiumdisplay'>
											<input class="form-control" type='number' name='premium' step='0.01' id='premium' value='<?php echo $premium; ?>'>	
										</div>
									</div>
								</div>
						<?php } else { ?>
								<div class="row">
									<div class="col-sm-1">
										<label class="col-sm-12"><?php echo $this->lang->line("Days"); ?>: </label>
										<div class="input-group col-sm-12">
											<!-- div id='totaldays' class="div-box"></div -->
											<input class="form-control" type='text' name='totaldays' id='totaldays' value='<?php echo $totaldays; ?>' >
										</div>
									</div>
									<div class="col-sm-2">
										<div class="input-group col-sm-12" style="padding-top: 28px;">
											 <input type='checkbox' class='setpremium' id='checkboxdays'> 1 <?php echo $this->lang->line("Year"); ?>
										</div>
									</div>
									<div class="col-sm-3">
										<label class="col-sm-12"><?php echo $this->lang->line("Daily Rate"); ?>: </label>
										<div class="input-group col-sm-12">
											<input class="form-control" type='text' name='dailyrate' id='dailyrate' value='<?php echo $dailyrate; ?>' readonly >
										</div>
									</div>
									<div class="col-sm-3">
										<label class="col-sm-12"><?php echo $this->lang->line("Age"); ?>: </label>
										<div class="input-group col-sm-12">
											<input class="form-control" type='text' name='totalyears' id='totalyears' value='<?php echo $totalyears; ?>' readonly >
										</div>
									</div>
									<div class="col-sm-3">
										<label class="inline"><?php echo $this->lang->line("Premium"); ?>: </label>
										<div class="input-group col-sm-12">
											<?php if ($user_group_id < 100) { ?>
											<input class="form-control" type='text' name='premium' id='premium' value='<?php echo $premium; ?>'>
											<?php } else { ?>
											<input class="form-control" type='hidden' name='premium' id='premium' value='<?php echo $premium; ?>'>	
											<div id='premiumdisplay'><?php echo $premium; ?></div>	
											<?php } ?>
										</div>
									</div>
								</div>
						<?php } ?>
					 	</fieldset>
					 	</div>
					</div><br />

					<?php if (!empty($plan) && !empty($plan['monthlypay'])) { ?>
					<div class="row">
						<div class="col-sm-12">
							<fieldset>
								<legend>Monthly Payment Plan</legend>
								<div class="row">
									<div class="form-group col-sm-3">
										<label class="col-sm-12">Payment Plan Status:
											<?php echo $monthly_status; ?>
										</label>
									</div>
									<div class="form-group col-sm-3">
										<label class="col-sm-12">Paid Months: 
											<?php if ($status_id == Plan_model::CANCEL) { echo 0; } else { echo (12 - intval($monthly_unpay_count)); } ?>
										</label>
									</div>
									<div class="form-group col-sm-3">
										<label class="col-sm-12">Paid Premium: 
											<?php if ($status_id == Plan_model::CANCEL) { echo 0; } else { echo number_format($monthly_paid, 2, ".", ""); } ?>
										</label>
									</div>
									<div class="form-group col-sm-3">
										<label class="col-sm-12">Outstanding Premium: 
											<?php echo number_format($monthly_unpay, 2, ".", ""); ?>
										</label>
									</div>
								</div>
							</fieldset>
					 	</div>
					</div><br />
					<?php } ?>

					<?php echo $insurable_options; ?>
					<?php if (!empty($error_claim)) { ?>
					<div class="row">
						<div class="col-sm-12">
							<div class="alert-error"> 
								<strong><?php echo $error_claim; ?></strong>
							</div>
						</div>
					</div>
					<?php if ($do_user_id > 0) { ?>
					<?php if ($user_group_id < 100) { ?>
					<div class="row">
						<div class="col-sm-12">
							<label class="col-sm-12"><?php echo $this->lang->line("By Check the checkbox, you can allow this policy to continue to pay. Please fill in your reason before cilck the checkbox."); ?></label>
						</div>
					</div>
					<div class="row">
						<div class="form-group col-sm-6">
							 <textarea name='claim_allow_note' id='claim_allow_note' <?php if ($status_id > 1) { echo "disabled"; } ?> style='width: 100%'><?php echo $claim_allow_note;?></textarea>
						</div>
						<div class="form-group col-sm-2">
							 <input type='checkbox' class='setpremium' id='claim_allowed' <?php if ($status_id > 1) { echo "disabled"; } ?> <?php if ($claim_allow_by > 0) { echo "checked"; } ?>> <?php echo $this->lang->line("Allow this policy"); ?>
							 <input type='hidden' name='claim_allow_by' id='claim_allow_by' value='<?php echo $claim_allow_by;?>'>
						</div>
					</div>
					<?php } else if (($claim_allow_by > 0) && $claim_allow_note) { ?>
					<div class="row">
						<div class="form-group col-sm-6">
							 <?php echo htmlspecialchars($claim_allow_note);?>
						</div>
					</div>
					<?php } ?>
					<?php } ?>
					<?php } ?>

					<div class="row">
						<div class="col-sm-12">
							<fieldset>
								<legend><?php echo $this->lang->line("Insurable Members"); ?></legend>
								<input type='hidden' name='customer_id' value='<?php echo !empty($customer_id) ? $customer_id : 0; ?>'>
								<div class="row">
                <?php if (($product_short == 'JFS') || ($product_short == 'JFE') || ($product_short == 'BHS') || ($product_short == 'JES') || ($product_short == 'JFPL') || ($product_short == 'JFSL') || ($product_short == 'JFGD') || ($product_short == 'JFOS')) { ?>
									<label class="col-sm-12"><?php echo $this->lang->line("Insured student"); ?></label>
                <?php } else { ?>
                  <label class="col-sm-12"><?php echo $this->lang->line("Customer Information"); ?></label>
                <?php } ?>
									<div class="col-sm-3">
                  <?php if (($product_short == 'JFS') || ($product_short == 'JFE') || ($product_short == 'BHS') || ($product_short == 'JES') || ($product_short == 'JFPL') || ($product_short == 'JFSL') || ($product_short == 'JFGD') || ($product_short == 'JFOS')) { ?>
		  							<label class="col-sm-12"><?php echo $this->lang->line("Student First Name"); ?>:</label>
                  <?php } else { ?>
										<label class="col-sm-12"><?php echo $this->lang->line("First Name"); ?>:</label>
                  <?php } ?>
										<div class="input-group col-sm-12">
											<input class="form-control" type='text' name='firstname' value='<?php echo !empty($firstname) ? $html_model->escapeQuote($firstname) : ''; ?>'>
										</div>
										<?php if (!empty($error_firstname)) {?>
										<div class="alert-error">
											<?php echo $error_firstname; ?>
										</div>
										<?php } ?>
									</div>
									<div class="col-sm-3">
                  <?php if (($product_short == 'JFS') || ($product_short == 'JFE') || ($product_short == 'BHS') || ($product_short == 'JES') || ($product_short == 'JFPL') || ($product_short == 'JFSL') || ($product_short == 'JFGD') || ($product_short == 'JFOS')) { ?>
		  							<label class="col-sm-12"><?php echo $this->lang->line("Student Last Name"); ?>:</label>
                  <?php } else { ?>
										<label class="col-sm-12"><?php echo $this->lang->line("Last Name"); ?>:</label>
                  <?php } ?>
										<div class="input-group col-sm-12">
											<input class="form-control" type='text' name='lastname' value='<?php echo !empty($lastname) ? $html_model->escapeQuote($lastname) : ''; ?>'>
										</div>
										<?php if (!empty($error_lastname)) {?>
										<div class="alert-error">
											<?php echo $error_lastname; ?>
										</div>
										<?php } ?>
									</div>
									<div class="col-sm-3">
                  <?php if (($product_short == 'JFS') || ($product_short == 'JFE') || ($product_short == 'BHS') || ($product_short == 'JES') || ($product_short == 'JFPL') || ($product_short == 'JFSL') || ($product_short == 'JFGD') || ($product_short == 'JFOS')) { ?>
		  							<label class="col-sm-12"><?php echo $this->lang->line("Student Birth Date"); ?> (YYYY-MM-DD):</label>
                  <?php } else { ?>
										<label class="col-sm-12"><?php echo $this->lang->line("Birth Date"); ?> (YYYY-MM-DD):</label>
                  <?php } ?>
										<div class="input-group date" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd" data-date-end-date="0d" >
					                        <input size="16" type="text" class='setpremium form-control' name='birthday' id='birthday' value='<?php echo !empty($birthday) ? $birthday : ''; ?>'>
					                        
					                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
					                    </div>
					                   <?php if (!empty($error_birthday)) {?>
										<div class="alert-error">
											<?php echo $error_birthday; ?>
										</div>
										<?php } ?>
									</div>
									<div class="col-sm-3">
										<div class="row">
											<div class="col-sm-6">
												<label class="col-sm-12"><?php echo $this->lang->line("Gender"); ?>: </label>
												<div class="input-group col-sm-12">
													<select name='gender' class="form-control" style="padding:6px 2px;">
													<option value='M' <?php echo (empty($gender) || ($gender != 'F')) ? "selected" : ""; ?>><?php echo $this->lang->line("Male"); ?></option>
													<option value='F' <?php echo (!empty($gender) && ($gender == 'F')) ? "selected" : ""; ?>><?php echo $this->lang->line("Female"); ?></option>
													</select>
												</div>
											</div>
											<div class="col-sm-6">
												<label class="col-sm-12">&nbsp;</label>
												<div class="col-sm-12">
													<?php if (0 &&$isprocessplan) { ?>
													<?php if ((($status_id == 2) || ($status_id == 3) || ($status_id == 4)) && !empty($customer_id) && $user_group_id !=3 && $user_group_id != 103) {?>
													<a class="btn btn-primary" href='<?php echo $claimurl . $customer_id; ?>'>Claim</a>
													<?php } ?>
													<?php } ?>
												</div>
											</div>
										</div>
									</div>
									
								</div>	

								<div id='family_member' style='display:none'>
									<?php for ($i = 1; $i < 9; $i++) { ?>
									<div class="row" id='customer_member_<?php echo $i; ?>' style='display:none'>
									<input type='hidden' name='customer_id_<?php echo $i; ?>'  id='customer_id_<?php echo $i; ?>' value='<?php echo !empty(${'customer_id_'.$i}) ? ${'customer_id_'.$i} : 0; ?>'>
									<hr />
									<div class="col-sm-12">
									<label>Family Member <?php echo $i; ?> </label><span> [ <input type='button' onclick='remove_member(<?php echo $i;?>)' value='Remove' data-toggle="tooltip" title="Remove Member!" class="btn btn-warn">]</span><br /> <span class="alert-error" id='errormessage_<?php echo $i;?>'></span>
									</div>
									
										<div class="col-sm-2">
											<label class="col-sm-12"><?php echo $this->lang->line("First Name"); ?>: </label>
											<div class="input-group col-sm-12">
												<input class="form-control" type='text' name='firstname_<?php echo $i; ?>' id='firstname_<?php echo $i; ?>' value='<?php echo !empty(${'firstname_'.$i}) ? $html_model->escapeQuote(${'firstname_'.$i}) : ''; ?>'>
											</div>
										</div>
										<div class="col-sm-2">
											<label class="col-sm-12"><?php echo $this->lang->line("Last Name"); ?>: </label>
											<div class="input-group col-sm-12">	
												<input class="form-control" type='text' name='lastname_<?php echo $i; ?>' id='lastname_<?php echo $i; ?>' value='<?php echo !empty(${'lastname_'.$i}) ? $html_model->escapeQuote(${'lastname_'.$i}) : ''; ?>'>
											</div>
										</div>
										<div class="col-sm-2">
                      <label class="col-sm-12"><?php echo $this->lang->line("Relationship"); ?>: </label>
                      <div class="input-group col-sm-12">
                        <select name='relationship_<?php echo $i; ?>' id='relationship_<?php echo $i; ?>' class="form-control relation-selection" style="padding:6px 2px;" onchange="updateRelationOptions()">
                          <option value='Spouse' <?php echo (isset(${'relationship_'.$i}) && (${'relationship_'.$i} == 'Spouse')) ? "selected" : ""; ?>><?php echo $this->lang->line("Spouse"); ?></option>
                          <option value='Parent' <?php echo (isset(${'relationship_'.$i}) && (${'relationship_'.$i} == 'Parent')) ? "selected" : ""; ?>><?php echo $this->lang->line("Parent"); ?></option>
                          <option value='Dependent Child' <?php echo (isset(${'relationship_'.$i}) && (${'relationship_'.$i} == 'Dependent Child')) ? "selected" : ""; ?>><?php echo $this->lang->line("Dependent Child"); ?></option>
                        </select>
                      </div>
										</div>
										<div class="col-sm-3">
											<label class="col-sm-12"><?php echo $this->lang->line("Birth Date"); ?>: </label>
											
											<div class="input-group date" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd" data-date-end-date="0d">
					                        	<input size="16" type="text" class='setpremium form-control' name='birthday_<?php echo $i; ?>' id='birthday_<?php echo $i; ?>' value='<?php echo !empty(${'birthday_'.$i}) ? ${'birthday_'.$i} : ''; ?>'>
					                        	
					                        	<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
					                        </div>
											<?php if (!empty(${'error_birthday_'.$i})) {?>
											<div class="alert-error">
												<?php echo ${'error_birthday_'.$i}; ?>
											</div>
											<?php } ?>
										</div>
										<div class="col-sm-3">
											<div class="row">
												<div class="col-sm-6">
													<label class="col-sm-12"><?php echo $this->lang->line("Gender"); ?>: </label>
													<div class="input-group col-sm-12">
														<select name='gender_<?php echo $i; ?>' id='gender_<?php echo $i; ?>' class="form-control" style="padding:6px 2px;">
														<option value='M' <?php echo (empty(${'gender_'.$i}) || (${'gender_'.$i} != 'F')) ? "selected" : ""; ?>><?php echo $this->lang->line("Male"); ?></option>
														<option value='F' <?php echo (!empty(${'gender_'.$i}) && (${'gender_'.$i} == 'F')) ? "selected" : ""; ?>><?php echo $this->lang->line("Female"); ?></option>
														</select>
													</div>
												</div>
												<div class="col-sm-6">
													<label class="col-sm-12">&nbsp;</label>
													<div class="col-sm-12">
													<?php if (0 && $isprocessplan) { ?>
														<?php if ((($status_id == 2) || ($status_id == 3) || ($status_id == 4)) && !empty(${'customer_id_'.$i}) && $user_group_id !=3 && $user_group_id != 103) {?>
														<a class="btn btn-primary" href='<?php echo $claimurl . ${'customer_id_'.$i}; ?>'>Claim</a>
														<?php } ?>
													<?php } ?>
													</div>
												</div>
											</div>
										</div>
									</div>
									<?php } ?>
									<br />
									<div class="row">
										<div class="col-sm-12">
										<?php if ($isprocessplan && ($status_id != 5) && ($status_id != 6)) { ?>
											<input class="btn btn-info" type='button'  id='addmorememberid' name='addmorememberid' value='Add More Member' onclick='addmoremember();'>
										<?php } ?>
										</div>
									</div>
								</div>
							</fieldset>
						</div>
					</div><br />

					<div class="row">
						<div class="col-sm-12">
							<fieldset>
								<legend><?php echo $this->lang->line("Address"); ?></legend>
								<div class="row">
									<div class="form-group col-sm-3">
										<label class="col-sm-12"><?php echo $this->lang->line("Street No"); ?>: </label>
										<div class="input-group col-sm-12">
											<input class="form-control" type='text' name='street_number' value='<?php echo $html_model->escapeQuote($street_number); ?>'>
										</div>
										<?php if (!empty($error_street_number)) {?>
										<div class="alert-error">
											<?php echo $error_street_number; ?>
										</div>
										<?php } ?>
									</div>
									<div class="form-group col-sm-3">
										<label class="col-sm-12"><?php echo $this->lang->line("Street Name"); ?>: </label>
										<div class="input-group col-sm-12">
											<input class="form-control" type='text' name='street_name' value='<?php echo $html_model->escapeQuote($street_name); ?>'>
										</div>
										<?php if (!empty($error_street_name)) {?>
										<div class="alert-error">
											<?php echo $error_street_name; ?>
										</div>
										<?php } ?>
									</div>
									<div class="form-group col-sm-3">
										<label class="col-sm-12"><?php echo $this->lang->line("Suite No."); ?>: </label>
										<div class="input-group col-sm-12">
											<input class="form-control" type='text' name='suite_number' value='<?php echo $html_model->escapeQuote($suite_number); ?>'>
										</div>
									</div>
									<div class="form-group col-sm-3">
										<label class="col-sm-12"><?php echo $this->lang->line("City"); ?>: </label>
										<div class="input-group col-sm-12">
											<input class="form-control" type='text' name='city' value='<?php echo $html_model->escapeQuote($city); ?>'>
										</div>
										<?php if (!empty($error_city)) {?>
										<div class="alert-error">
											<?php echo $error_city; ?>
										</div>
										<?php } ?>
									</div>
								</div>
								<div class="row">	
									<div class="form-group col-sm-3">
										<label class="col-sm-12"><?php echo $this->lang->line("Province"); ?>: </label>
										<div class="input-group col-sm-12">
											<div id='province2_div'></div>
										</div>
										<?php if (!empty($error_province2)) {?>
										<div class="alert-error">
											<?php echo $error_province2; ?>
										</div>
										<?php } ?>
									</div>
									<div class="form-group col-sm-3">
										<label class="col-sm-12"><?php echo $this->lang->line("Country"); ?>: </label>
										<div class="input-group col-sm-12">
                    <?php if (($product_short=="JES") || ($product_short=="JESP") || ($product_short=="JFR") || ($product_short=="JFS") || ($product_short=="JFVTC")) { ?>
                      Canada
											<input type='hidden' name='country2' value='CA'>
                    <?php } else { ?>
											<div id='country2_div'></div>
                    <?php } ?>
										</div>
									</div>
									<div class="form-group col-sm-3">
										<label class="col-sm-12"><?php echo $this->lang->line("Postcode"); ?>: </label>
										<div class="input-group col-sm-12">
											<input class="form-control" type='text' name='postcode' value='<?php echo $html_model->escapeQuote($postcode); ?>'>
										</div>
										<?php if (!empty($error_postcode)) {?>
										<div class="alert-error">
											<?php echo $error_postcode; ?>
										</div>
										<?php } ?>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-3">
										<label class="col-sm-12"><?php echo $this->lang->line("Phone"); ?>1: </label>
										<div class="input-group col-sm-12">
											<input class="form-control" type='text' name='phone1' value='<?php echo $html_model->escapeQuote($phone1); ?>'>
										</div>
										<?php if (!empty($error_phone1)) {?>
										<div class="alert-error">
											<?php echo $error_phone1; ?>
										</div>
										<?php } ?>
									</div>
									<div class="col-sm-3">
										<label class="col-sm-12"><?php echo $this->lang->line("Phone"); ?>2: </label>
										<div class="input-group col-sm-12">
											<input class="form-control" type='text' name='phone2' value='<?php echo $html_model->escapeQuote($phone2); ?>'>
										</div>
									</div>
								</div>
							</fieldset>
						</div>
					</div><br />
					<div class="row">
						<div class="col-sm-12">
							<fieldset>
								<legend>Contact</legend>
								<div class="row">
									<div class="col-sm-3">
										<label class="col-sm-12"><?php echo $this->lang->line("Email"); ?>: </label>
										<div class="input-group col-sm-12">
											<input class="form-control" type='text' name='contact_email' value='<?php echo $html_model->escapeQuote($contact_email); ?>'>
										</div>
										<?php if (!empty($error_contact_email)) {?>
										<div class="alert-error">
											<?php echo $error_contact_email; ?>
										</div>
										<?php } ?>
									</div>
									<div class="col-sm-3">
										<label class="col-sm-12"><?php echo $this->lang->line("Contact Phone"); ?>: </label>
										<div class="input-group col-sm-12">
											<input class="form-control" type='text' name='contact_phone' value='<?php echo $html_model->escapeQuote($contact_phone); ?>'>
										</div>
									</div>
									<div class="col-sm-3">
										<label class="col-sm-12"><?php echo $this->lang->line("Country of Origin"); ?>: </label>
										<div class="input-group col-sm-12">
											<input class="form-control" type='text' name='residence' value='<?php echo $html_model->escapeQuote($residence); ?>'>
										</div>
									</div>
									<div class="col-sm-3">
										<label class="col-sm-12"><?php echo $this->lang->line("Prefer Language"); ?>: </label>
                    <div class="input-group col-sm-12">
                      <select name='contact_language' id='contact_language' class="form-control" style="padding:6px 2px;">
                        <option value='English' <?php echo (empty($contact_language) || ($contact_language == 'English')) ? "selected" : ""; ?>>English</option>
                        <option value='French' <?php echo ($contact_language == 'French') ? "selected" : ""; ?>>French</option>
                      </select>
                    </div>
									</div>
									
							</fieldset>
						</div>
					</div><br />	
					<?php if ($user_group_id < 100) { ?>
					<div class="row">
						<div class="col-sm-12">
							<fieldset>
								<legend><?php echo $this->lang->line("Special Note/Instructions"); ?></legend>
								<div class="row" >
									<div class="col-sm-12">
										<label class="col-sm-12"><?php echo $this->lang->line("Notes"); ?>: </label>
										<div class="input-group col-sm-12">
								 			<textarea class="form-control" name="note"><?php echo htmlspecialchars($note); ?></textarea>
								 		</div>
								 	</div>
								</div>
							</fieldset>
						</div>
					</div><br />
					<?php } ?>

					<?php if ((($user_group_id>100) && ($status_id>1)) || ($user_group_id == 3) || ($user_group_id == 103) || (($user_group_id == 106) && !empty($plan_id))) { ?>
					<div class="row" style="display:none;">
					<?php }else{ ?>
					<div class="row">
					<?php } ?>	
						<div class="col-sm-12" id='goto_next_page'>
						<?php if (!empty($next_url)) { ?>
						<a href='<?php echo $next_url; ?>'><span class="btn btn-info"><?php echo $this->lang->line("No Change"); ?></span></a> <?php echo $this->lang->line("Go next page without change"); ?>
						<?php } ?>
						<?php if (($user_group_id == 1) || ($isprocessplan && ($status_id != 5) && ($status_id != 6))) { ?>
							<input class="btn btn-primary pull-right" type='submit' id='page-submit' name='submit' value='<?php echo $submit; ?>' />		
						<?php } ?>
						</div>

						<div class="col-sm-12 alert-error float-error" title="Click to Close the notice" style="display:none;" id='error_next_page'>
						</div>
					</div>
					
					<div class="row">
						<div class="col-sm-12">
							<?php if (!empty($error_premium)) {?>
								<div class="alert-error float-perror" title="Click to Close the notice" id="premium_error">
								<?php echo $error_premium; ?>
								</div>
							<?php } ?>
						</div>
					</div>
				</form> 
				</div><!-- /x_content end-->
                </div><!-- /x_panel end -->
              </div>
            </div><!-- End Form Section-->

            <!-- Pop Section -->
            	<div class="row">
					<div class="popRefund" id="popRdiv" style="display:none;">
						<span class="pop-close"><i class="fa fa-times"></i></span>
						<?php if (!empty($refund_letter_url)) echo $popRefund; ?>
					</div>

					<script>
						$('#popRefund').click(function(){
							$('.popRefund').removeAttr("class");
							$('#popRdiv').attr('style','display:block;');
							$('#popRdiv').attr('class','show-pop');
										
						});
						$('.pop-close').click(function(){
							$('#popRdiv').attr('style','display:none;');
						});
					</script>
				</div>
            <!-- Pop Section -->
				<?php if (!empty($monthly_payment)) { ?>
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
									<?php $idx = 0; foreach ($monthly_payment as $mr) { $idx++; ?>
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
											&nbsp;
										</div>
									</div>
									<?php } ?>
								</div><!--/x_content end-->
							</div><!--x_panel-->
						</div>
					</div>
					<?php } ?>

           <!-- New Section -->
			<?php if ($show_history) { ?>
            <div class="row">
            	<div class="col-sm-12">
            		<div class="x_panel">
	                  <div class="x_title">
	                    <h2>Policy History<small></small></h2>
	                    <div class="clearfix"></div>
	                  </div>
	                  <div class="x_content">
		                  <div class="row" id='payment_history'>
                        <?php	if (!empty($payment_tables) && is_array($payment_tables) && (sizeof($payment_tables) > 0)) { ?>
		                  	<div class="col-sm-12">
			                  	<button type="button" id="payment_history_button" class="btn btn-info" data-toggle="collapse" data-target="#history1"><?php echo $this->lang->line("Payments"); ?> <span class="fa fa-chevron-down"></span></button>
			  			            <div id="history1" class="collapse">
                            <?php	if (sizeof($payment_tables) > 1) { ?>
                              <button type="button" id="payment_get_history_button" class="btn"><?php echo $this->lang->line("Get More"); ?></button>
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
                                        <th><Admin</th>
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
			                  	</div>
		                  	</div>
                        <?php	} ?>
		                  </div>
		                  <div class="row">
                        <?php if (!empty($activelog_tables) && is_array($activelog_tables) && (sizeof($activelog_tables) > 0)) { ?>
		                  	<div class="col-sm-12">
			                  	<button type="button" id="activelog_history_button" class="btn btn-info" data-toggle="collapse" data-target="#history2">Changes <span class="fa fa-chevron-down"></span></button>
			                  	<div id="history2" class="collapse">
                            <?php	if (sizeof($activelog_tables) > 1) { ?>
                              <button type="button" id="activelog_get_history_button" class="btn"><?php echo $this->lang->line("Get More"); ?></button>
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
		                  	</div>
                        <?php } ?>
		                  </div>
	                  </div><!--/x_content end-->
	              </div><!--x_panel-->
            	</div>
            </div>
			<?php } ?>
		  </div>
        </div>
        <!-- /page content -->
<script>
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

$('#error_next_page').click(function(){
  $('#error_next_page').css('display','none');
});

$('#premium_error').click(function(){
  $('#premium_error').css('display','none');
});
</script>
<script>
var age85 = 0;
$( document ).ready(function() {
	$.ajax({
		url: '<?php echo $province_url; ?>',
		type: 'GET',
		success: function(data, textStatus, jqXHR) {
        	$('#province2_div').html(data);
        	get_premium();
    	},
	});
	$.ajax({
		url: '<?php echo $country_url; ?>',
		type: 'GET',
		success: function(data, textStatus, jqXHR) {
        	$('#country2_div').html(data);
        	get_premium();
    	},
	});
	if ( $( "#sum_insured_div" ).length ) {
		$.ajax({
			url: '<?php echo $sum_insured_url; ?>',
			success: function(data, textStatus, jqXHR) {
	        	$('#sum_insured_div').html(data);
				get_premium();
	    	},
		});
	}
	if ( $( "#deductible_amount_div" ).length ) {
		$.ajax({
			url: '<?php echo $deductible_amount_url_now; ?>',
			success: function(data, textStatus, jqXHR) {
	        	$('#deductible_amount_div').html(data);
				get_premium();
	    	},
		});
	}
	if ( $( "#isfamilyplan" ).length ) {
		if ($('#isfamilyplan').get(0).checked) {
			$('#family_member').show();
			if ($('#spousediv').length) {
				$('#spousediv').show();
			}
		}
		$('#isfamilyplan').change(function() {
	        if ($(this).get(0).checked) {
	    		$('#family_member').show();
				if ($('#spousediv').length) {
					$('#spousediv').show();
				}
	        } else {
	        	$('#family_member').hide();
				if ($('#spousediv').length) {
					$('#spousediv').hide();
				}
	        	$("input[name^='firstname_']").each(function() {
	            	$(this).val('');
	        	});
	        	$("input[name^='lastname_']").each(function() {
	            	$(this).val('');
	        	});
	        }
	    });
	}

	$('.setpremium').change(get_premium); 
	if ($('input[name="holiday_rate"]').length) {
		$('input[name="holiday_rate"]').change(get_premium);
	}
	addmoremember();
  updateRelationOptions();

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
		for (j = i + 1 ; j < 8; j++) {
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
			s = '#relationship_' + j;
			d = '#relationship_' + i;
			$(d).val($(s).val());
			i++;
		}
		$('#customer_id_8').val(0);
		$('#firstname_8').val('');
		$('#lastname_8').val('');
		$('#birthday_8').val('');
		$('#gender_8').val('M');
		$('#relationship_8').val('');
		get_premium();
		addmoremember();
	}
}

function updateRelationOptions() {
  // Get all select elements
  const allSelects = document.querySelectorAll('select.relation-selection');
  
  // Reset all options to enabled first
  allSelects.forEach(select => {
    Array.from(select.options).forEach(option => {
      if (option.value === 'Parent' || option.value === 'Dependent Child') {
        option.disabled = false;
      }
    });
  });
  
  // Check if any select has "Parent" or "Dependent Child" selected
  let hasParent = false;
  let hasChild = false;
  
  allSelects.forEach(select => {
    if (select.value === 'Parent') hasParent = true;
    if (select.value === 'Dependent Child') hasChild = true;
  });
  
  // Apply disabling logic
  if (hasParent) {
    allSelects.forEach(select => {
      Array.from(select.options).forEach(option => {
        if (option.value === 'Dependent Child' && select.value !== 'Dependent Child') {
          option.disabled = true;
        }
      });
    });
  }
  
  if (hasChild) {
    allSelects.forEach(select => {
      Array.from(select.options).forEach(option => {
        if (option.value === 'Parent' && select.value !== 'Parent') {
          option.disabled = true;
        }
      });
    });
  }
}

function addmoremember() {
	for (i = 1; i < 9; i++) {
		$('#customer_member_' + i).show();
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
			$('#errormessage_' + i).html("Please fill in all required information.");
			break;
		}
		$('#errormessage_' + i).html("");
	}
	i++;
	for ( ; i < 9; i++) {
		$('#firstname_' + i).removeClass('alert-error');
		$('#lastname_' + i).removeClass('alert-error');
		$('#birthday_' + i).removeClass('alert-error');
		$('#customer_member_' + i).hide();
		$('#errormessage_' + i).html("");
	}
}

function get_premium() {
<?php if (empty($batch_number)) { ?>
	var status_id = '<?php echo $status_id; ?>';
	var plan_id = $('input[name="plan_id"]').val();
	var product_short = $('input[name="product_short"]').val();
	var apply_date = $('input[name="apply_date"]').val();
	var effective_date = $('input[name="effective_date"]').val();
	var expiry_date = $('input[name="expiry_date"]').val();
	var isfamilyplan = '';
	var spouse = '';

	if ( $( "#isfamilyplan" ).length ) {
		if ($('input[name="isfamilyplan"]').is(':checked')) {
			isfamilyplan = 1;	// checkbox
		}
	}
	var sum_insured = -1;
	if ($('select[name="sum_insured"]').length) {
		sum_insured = $('select[name="sum_insured"]').val();	// select
	}
	var deductible_amount = -1;
	if ($('select[name="deductible_amount"]').length) {
		deductible_amount = $('select[name="deductible_amount"]').val();	// select
	}
	var stable_condition = -1;
	if ($('select[name="stable_condition"]').length) {
		stable_condition = $('select[name="stable_condition"]').val();	// radio
	}
	var rate_options = -1;
	if ($('select[name="rate_options"]').length) {
		rate_options = $('select[name="rate_options"]').val();	// radio
	}
	var holiday_rate = -1;
	if ($('input[name="holiday_rate"]').length) {
		if ($('input[name="holiday_rate"]').is(':checked')) {
			holiday_rate = 1;	// checkbox
		} else {
			holiday_rate = 0;	// checkbox
		}
	}
	var birthday = $('input[name="birthday"]').val();	// 
	var number_customer = 0;
	if (isfamilyplan && (product_short != 'NUS') && (product_short != 'JUS')) {
		if (new Date(birthday) > new Date($('input[name="birthday_1"]').val())) {
			birthday = $('input[name="birthday_1"]').val();
		} 
		if (new Date(birthday) > new Date($('input[name="birthday_2"]').val())) {
			birthday = $('input[name="birthday_2"]').val();
		} 
		if (new Date(birthday) > new Date($('input[name="birthday_3"]').val())) {
			birthday = $('input[name="birthday_3"]').val();
		} 
		if (new Date(birthday) > new Date($('input[name="birthday_4"]').val())) {
			birthday = $('input[name="birthday_4"]').val();
		} 
		if (new Date(birthday) > new Date($('input[name="birthday_5"]').val())) {
			birthday = $('input[name="birthday_5"]').val();
		} 
		if (new Date(birthday) > new Date($('input[name="birthday_6"]').val())) {
			birthday = $('input[name="birthday_6"]').val();
		} 
		if (new Date(birthday) > new Date($('input[name="birthday_7"]').val())) {
			birthday = $('input[name="birthday_7"]').val();
		} 
		if (new Date(birthday) > new Date($('input[name="birthday_8"]').val())) {
			birthday = $('input[name="birthday_8"]').val();
		}
	}
	
	if ($('input[name="firstname"]').val() && $('input[name="lastname"]').val()) number_customer++;
	if (isfamilyplan >= 1) {
	if ($('input[name="firstname_1"]').val() && $('input[name="lastname_1"]').val()) number_customer++;
	if ($('input[name="firstname_2"]').val() && $('input[name="lastname_2"]').val()) number_customer++;
	if ($('input[name="firstname_3"]').val() && $('input[name="lastname_3"]').val()) number_customer++;
	if ($('input[name="firstname_4"]').val() && $('input[name="lastname_4"]').val()) number_customer++;
	if ($('input[name="firstname_5"]').val() && $('input[name="lastname_5"]').val()) number_customer++;
	if ($('input[name="firstname_6"]').val() && $('input[name="lastname_6"]').val()) number_customer++;
	if ($('input[name="firstname_7"]').val() && $('input[name="lastname_7"]').val()) number_customer++;
	if ($('input[name="firstname_8"]').val() && $('input[name="lastname_8"]').val()) number_customer++;
	}

	if ($('#spousediv').length) {
		if ($('#spouse').get(0).checked) {
			spouse = 1;
		}
	}

	if (effective_date && expiry_date) {
    var birthdays = [];
    birthdays.push($('input[name="birthday"]').val());
    for (var i=1; i < 9; i++) {
      if ($('input[name="birthday_'+i+'"]').val()) {
        birthdays.push($('input[name="birthday_'+i+'"]').val());
      } else {
        break;
      }
    }
		$.ajax({
			url: '<?php echo $premium_url; ?>',
			type: 'get',
			data: {
        status_id: status_id,
				plan_id: plan_id,
				product_short: product_short,
				apply_date: apply_date,
				effective_date: effective_date,
				expiry_date: expiry_date,
				isfamilyplan: isfamilyplan,
				sum_insured: sum_insured,
				deductible_amount: deductible_amount,
				stable_condition: stable_condition,
				rate_options: rate_options,
				holiday_rate: holiday_rate,
				number_customer: number_customer,
				spouse: spouse,
				birthday: birthday,
				birthdays: birthdays
      },
			success: function(data, textStatus, jqXHR) {
				if (data['status'] == 'OK') {
	        $('input[name="premium"]').val(data['premiumarr']['premium']);
          $("#page-submit").attr("disabled", false);
					<?php if ($user_group_id > 100) { ?>
					$('#premiumdisplay').html(data['premiumarr']['premium']);
					<?php } ?>
					$('#totalyears').val(data['premiumarr']['totalyears']);
					$('#totaldays').val(data['premiumarr']['totaldays']);
					$('#dailyrate').val(data['premiumarr']['dailyrate']);
					if (data['premiumarr']['message']) {
						$('#error_next_page').html(data['premiumarr']['message']);
						$('#error_next_page').css('display','block');
					} else {
						$('#error_next_page').html('');
						$('#error_next_page').css('display','none');
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
								url: '<?php echo $deductible_amount_url; ?>/500only',
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
	}
<?php } ?>
} 
</script>
<script type="text/javascript">
$(document).ready(function(){
  $('#totaldays, #birthday, #effective_date, #expiry_date').on('focus', function(e) {
    $("#page-submit").attr("disabled", true);
  });
	$('#plan_edit_form').on('keyup keypress', function(e) {
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
		<?php if (!empty($plan) && !empty($plan['monthlypay']) && (($status_id == 2) || ($status_id == 3))) { ?>
		$('#effective_date_div').datepicker({ autoclose: true, format:'yyyy-mm-dd' }).on('changeDate', function(e){
			var effective_date = $('input[name="effective_date"]').val();
			var today = '<?php echo date("Y-m-d"); ?>';
			if (effective_date == today) {
				alert("Once the effective date is changed to today, clicking the submit button will charge the first monthly recurring fee.");
      }
    });
		<?php } ?>
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
	<?php if (($product_short == 'JFVTC') || ($product_short == 'JFR') || ($product_short == 'OPL')) { ?> 
	$('#stable_condition_select').change(function() {
		var selected_val = $('#stable_condition_select').val();
		if (selected_val == 2) {
			// without stable  condition
			$('#stable_condition_confirm_div').show();
		} else {
			$('#stable_condition_confirm_div').hide();
		}
	});
	<?php } ?> 
    
	<?php if (($do_user_id > 0) && ($user_group_id < 100) && ($status_id < 2)) { ?>
		$('#claim_allowed').change(function(){
        if (this.checked) {
            if (!$('#claim_allow_note').val()) {
                $('#claim_allowed').prop('checked', false);
                alert('Please fill up note before you allow process');
                return;
            }
            $('#claim_allow_by').val('<?php echo $do_user_id; ?>');
        } else {
            $('#claim_allow_by').val(''); 
        }
    });
	<?php } ?> 
});
</script>
