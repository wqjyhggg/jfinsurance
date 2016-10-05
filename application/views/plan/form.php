<?php
defined('BASEPATH') OR exit('No direct script access allowed');
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
        <div class="right_col" role="main" style="padding-bottom:60px;">
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
                    <h2>Policy Detail<small></small></h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    
					<!--<script src="<?php echo base_url(); ?>js/jquery-3.0.0.min.js" type="text/javascript"></script>-->
					<?php if (!empty($error_message)) {?>
					<div class="alert-error">
						<?php echo $error_message . "<br>";?>
					</div>
					<?php } ?>

					<form action='<?php echo $action_url; ?>' method='POST' class="form-horizontal">
						<input type='hidden' name='<?php echo $csrf['name']; ?>' value='<?php echo $csrf['value']; ?>'>
						<input type='hidden' name='plan_id' value='<?php echo $plan_id; ?>'>
						<input type='hidden' name='product_short' value='<?php echo $product_short; ?>'>
						<input type='hidden' name='force_deductable' value=''>
					<?php if (empty($plan_id) || empty($status_id)) { ?>
						<?php /* it should be new plan */ ?>
						<input type='hidden' name='status_id' value='0'>
					<div class="row" style="margin-bottom:15px;">
						<div class="form-group col-sm-3">
							<label><span><?php echo $plan_full_name; ?></span></label>
						</div>
						<div class="form-group col-sm-3">
							<label style="text-transform: capitalize;">By Agent<?php echo "[ AgentID:" . $policy_user['user_id'] . " ] "; ?>: <?php echo $policy_user['firstname'] . " " . $policy_user['lastname']; ?></label>
						</div>
					</div>
					<?php } else { ?>
					<div class="row" style="margin-bottom:15px;">
						
						<div class="col-sm-3 pull-right text-right">
						<?php /* it should be created plan */ ?>
						<?php if ($status_id == 1 && $user_group_id != 103) { /* qutoe */ ?>

						<a href='<?php echo $pay_url; ?>'><span class="btn btn-info">Pay</span></a>
						<?php } ?> 

						<?php if($user_group_id != 3  && $user_group_id != 103){ ?>
						<a href='<?php echo $copy_url; ?>'><span class="btn btn-info">Copy</span></a>
						<?php } ?>
						<?php if ($status_id > 1 && $user_group_id !=3) { ?>
						<?php 	if (($status_id == 2) || ($status_id == 3)) { ?>
							<a href='<?php echo $sendpackage_url . $plan_id; ?>'><span class="btn btn-info">Send Package</span></a>
						<?php 	} ?>
						<?php if ($status_id == 3 && $user_group_id <= 100 ) { ?>
						<?php if((time()-(60*60*24)) < strtotime($effective_date)){ ?>
							<a href='<?php echo $cancel_url . $plan_id; ?>'><span class="btn btn-info">Cancel</span></a>
							<?php }else{?>
							<a href='<?php echo $refund_url . $plan_id; ?>'><span class="btn btn-info">Refund</span></a>
							<?php } ?>
						<?php } else if ($status_id == 6 && $user_group_id <= 100) { ?>
							<a target='_blank' href='<?php echo $refundprint_url . $plan_id; ?>'><span class="btn btn-info">Refund Letter</span></a>
						<?php } else if ($status_id == 5 && $user_group_id <= 100) { ?>
							<a target='_blank' href='<?php echo $cancelprint_url . $plan_id; ?>'><span class="btn btn-info">Cancel Letter</span></a>
						<?php } ?>
						<?php } ?>

						</div>
						<div class="form-group col-sm-3">
							<label><span><?php echo $plan_full_name; ?></span></label>
							<label><?php if ($status_id < 2) { ?>Quote<?php } else {?>Policy<?php } ?> Number: <span><?php echo $policy; ?></span></label>
							 
						</div>
						
						<div class="form-group col-sm-3">
							<label style="text-transform: capitalize;">By Agent<?php echo "[ AgentID:" . $policy_user['user_id'] . " ] "; ?>: <?php echo $policy_user['firstname'] . " " . $policy_user['lastname']; ?></label>
						</div>
						<?php if ($user_group_id != 1) { ?>
							<?php /* it is school or brokerage or agent */ ?>
							<div class="form-group col-sm-3">
								<label style="font-size:16px;">Status: <?php echo $status_list[$status_id]['name']; ?> </label>
							</div>
							<input type='hidden' name='status_id' value='<?php echo $status_id; ?>' class="form-control">
							<?php } else { ?>
							<div class="form-group col-sm-3">
							<label style="display:inline-block;vertical-align:middle;">Status:</label>
							<div style="display:inline-block;vertical-align:middle;">
							<select name='status_id' class="form-control">
								<option value='0'> -- select policy status -- </option>
								<?php foreach ($status_list as $key => $value) { ?>
								<option value='<?php echo $key; ?>' <?php echo ($key == $status_id) ? 'selected' : ''; ?>><?php echo $value['name']; ?></option>
								<?php } ?>
							</select></div>
							</div>
							<?php } ?>
					</div>
					<?php } ?>
					<div class="row">
						
						<div class="col-sm-12">
						<fieldset>
   						 <legend>Travel Dates</legend>
   						 <div class="row">
							<div class="form-group col-sm-3">
								<label class="col-sm-12">Apply Date:</label>
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
								<label class="col-sm-12">Arrival Date: </label>
								<div class="input-group date" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd" id='arrival_date_div' >
			                        <input class="form-control" size="16" type="text" name='arrival_date' id='arrival_date' value='<?php echo $arrival_date; ?>' data-date-format="yyyy-mm-dd" data-date-end-date="+0d" data-date-start-date="-1d">
			                        
			                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
			                    </div>
			                    <?php if (!empty($error_arrival_date)) { ?>
			                    <div class="alert-error"> 
			                        <?php echo $error_arrival_date; ?>
			                    </div>
			                    <?php } ?>

							</div>
							<div class="form-group col-sm-3">
								<label class="col-sm-12">Effective Date: </label>
								<div class="input-group date" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd" id='effective_date_div'>
			                        <input class="setpremium form-control" size="16" type="text" name='effective_date' value='<?php echo $effective_date; ?>'>
			                        
			                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
			                    </div>
			                    <?php if (!empty($error_effective_date)) { ?>
			                    <div class="alert-error">
			                    	<?php echo $error_effective_date; ?> 
			                	</div>
			                    <?php } ?>
							</div>
							<div class="form-group col-sm-3">
								<label class="col-sm-12">Expiry Date: </label>
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
								<div class="row">
									<div class="col-sm-3">
										<label class="col-sm-12">Days: </label>
										<div class="input-group col-sm-12">
											<!-- div id='totaldays' class="div-box"></div -->
											<input class="form-control" type='text' name='totaldays' id='totaldays' value='<?php echo $totaldays; ?>' >
										</div>
									</div>
									<div class="col-sm-3">
										<label class="col-sm-12">Daily Rate: </label>
										<div class="input-group col-sm-12">
											<input class="form-control" type='text' name='dailyrate' id='dailyrate' value='<?php echo $dailyrate; ?>' readonly >
										</div>
									</div>
									<div class="col-sm-3">
										<label class="col-sm-12">Age: </label>
										<div class="input-group col-sm-12">
											<input class="form-control" type='text' name='totalyears' id='totalyears' value='<?php echo $totalyears; ?>' readonly >
										</div>
									</div>
									<div class="col-sm-3">
										<label class="inline">Premium: </label>
										<div class="input-group col-sm-12">
											<?php if ($user_group_id < 100) { ?>
											<input class="form-control" type='input' name='premium' id='premium' value='<?php echo $premium; ?>'>
											<?php } else { ?>
											<input class="form-control" type='hidden' name='premium' id='premium' value='<?php echo $premium; ?>'>	
											<div id='premiumdisplay'><?php echo $premium; ?></div>	
											<?php } ?>
										</div>
									</div>
								</div>
					 	</fieldset>
					 	</div>
					</div><br />
					<?php echo $insurable_options; ?>
					<div class="row">
						<div class="col-sm-12">
							<fieldset>
								<legend>Insurable Members</legend>
								<input type='hidden' name='customer_id' value='<?php echo !empty($customer_id) ? $customer_id : 0; ?>'>
								<div class="row">
									<label class="col-sm-12">Customer Information</label>
									<div class="col-sm-3">
										<label class="col-sm-12">First Name:</label>
										<div class="input-group col-sm-12">
											<input class="form-control" type='text' name='firstname' value='<?php echo !empty($firstname) ? $firstname : ''; ?>'>
										</div>
										<?php if (!empty($error_firstname)) {?>
										<div class="alert-error">
											<?php echo $error_firstname; ?>
										</div>
										<?php } ?>
									</div>
									<div class="col-sm-3">
										<label class="col-sm-12">Last Name:</label>
										<div class="input-group col-sm-12">
											<input class="form-control" type='text' name='lastname' value='<?php echo !empty($lastname) ? $lastname : ''; ?>'>
										</div>
										<?php if (!empty($error_lastname)) {?>
										<div class="alert-error">
											<?php echo $error_lastname; ?>
										</div>
										<?php } ?>
									</div>
									<div class="col-sm-3">
										<label class="col-sm-12">Birth Date:</label>
										<div class="input-group date" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd" data-date-end-date="0d" >
					                        <input size="16" type="text" class='setpremium form-control' name='birthday' value='<?php echo !empty($birthday) ? $birthday : ''; ?>'>
					                        
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
												<label class="col-sm-12">Gender: </label>
												<div class="input-group col-sm-12">
													<select name='gender' class="form-control" style="padding:6px 2px;">
													<option value='M' <?php echo (empty($gender) || ($gender != 'F')) ? "selected" : ""; ?>>Male</option>
													<option value='F' <?php echo (!empty($gender) && ($gender == 'F')) ? "selected" : ""; ?>>Female</option>
													</select>
												</div>
											</div>
											<div class="col-sm-6">
												<label class="col-sm-12">&nbsp;</label>
												<div class="col-sm-12">
													<?php if ((($status_id == 2) || ($status_id == 3) || ($status_id == 4)) && !empty($customer_id) && $user_group_id !=3 && $user_group_id != 103) {?>
													<a class="btn btn-primary" href='<?php echo $claimurl . $customer_id; ?>'>Claim</a>
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
											<div class="alert-error">
												<?php echo ${'error_birthday_'.$i}; ?>
											</div>
											<?php } ?>
										</div>
										<div class="col-sm-3">
											<div class="row">
												<div class="col-sm-6">
													<label class="col-sm-12">Gender: </label>
													<div class="input-group col-sm-12">
														<select name='gender_<?php echo $i; ?>' id='gender_<?php echo $i; ?>' class="form-control" style="padding:6px 2px;">
														<option value='M' <?php echo (empty(${'gender_'.$i}) || (${'gender_'.$i} != 'F')) ? "selected" : ""; ?>>Male</option>
														<option value='F' <?php echo (!empty(${'gender_'.$i}) && (${'gender_'.$i} == 'F')) ? "selected" : ""; ?>>Female</option>
														</select>
													</div>
												</div>
												<div class="col-sm-6">
													<label class="col-sm-12">&nbsp;</label>
													<div class="col-sm-12">
														<?php if ((($status_id == 2) || ($status_id == 3) || ($status_id == 4)) && !empty($customer_id) && $user_group_id !=3 && $user_group_id != 103) {?>
														<a class="btn btn-primary" href='<?php echo $claimurl . ${'customer_id_'.$i}; ?>'>Claim</a>
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
											<input class="btn btn-info" type='button'  id='addmorememberid' name='addmorememberid' value='Add More Member' onclick='addmoremember();'>
										</div>
									</div>
								</div>
							</fieldset>
						</div>
					</div><br />

					<div class="row">
						<div class="col-sm-12">
							<fieldset>
								<legend>Address</legend>
								<div class="row">
									<div class="form-group col-sm-3">
										<label class="col-sm-12">Street No: </label>
										<div class="input-group col-sm-12">
											<input class="form-control" type='text' name='street_number' value='<?php echo $street_number; ?>'>
										</div>
										<?php if (!empty($error_street_number)) {?>
										<div class="alert-error">
											<?php echo $error_street_number; ?>
										</div>
										<?php } ?>
									</div>
									<div class="form-group col-sm-3">
										<label class="col-sm-12">Street Name: </label>
										<div class="input-group col-sm-12">
											<input class="form-control" type='text' name='street_name' value='<?php echo $street_name; ?>'>
										</div>
										<?php if (!empty($error_street_name)) {?>
										<div class="alert-error">
											<?php echo $error_street_name; ?>
										</div>
										<?php } ?>
									</div>
									<div class="form-group col-sm-3">
										<label class="col-sm-12">Suite No.: </label>
										<div class="input-group col-sm-12">
											<input class="form-control" type='text' name='suite_number' value='<?php echo $suite_number; ?>'>
										</div>
									</div>
									<div class="form-group col-sm-3">
										<label class="col-sm-12">City: </label>
										<div class="input-group col-sm-12">
											<input class="form-control" type='text' name='city' value='<?php echo $city; ?>'>
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
										<div class="alert-error">
											<?php echo $error_postcode; ?>
										</div>
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
										<div class="alert-error">
											<?php echo $error_phone1; ?>
										</div>
										<?php } ?>
									</div>
									<div class="col-sm-3">
										<label class="col-sm-12">Phone2: </label>
										<div class="input-group col-sm-12">
											<input class="form-control" type='text' name='phone2' value='<?php echo $phone2; ?>'>
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
										<label class="col-sm-12">Email: </label>
										<div class="input-group col-sm-12">
											<input class="form-control" type='text' name='contact_email' value='<?php echo $contact_email; ?>'>
										</div>
										<?php if (!empty($error_contact_email)) {?>
										<div class="alert-error">
											<?php echo $error_contact_email; ?>
										</div>
										<?php } ?>
									</div>
									<div class="col-sm-3">
										<label class="col-sm-12">Contact Phone: </label>
										<div class="input-group col-sm-12">
											<input class="form-control" type='text' name='contact_phone' value='<?php echo $contact_phone; ?>'>
										</div>
									</div>
									<div class="col-sm-3">
										<label class="col-sm-12">Residence: </label>
										<div class="input-group col-sm-12">
											<input class="form-control" type='text' name='residence' value='<?php echo $residence; ?>'>
										</div>
									</div>
									
							</fieldset>
						</div>
					</div><br />	
										
					<div class="row">
						<div class="col-sm-12">
							<fieldset>
								<legend>Special Note/Instructions</legend>
								<div class="row" <?php if ($user_group_id > 100) { ?>style='display:none; '<?php } ?>>
									<div class="col-sm-12">
										<label class="col-sm-12">Notes: </label>
										<div class="input-group col-sm-12">
								 			<textarea class="form-control" name="note"><?php echo $note; ?></textarea>
								 		</div>
								 	</div>
								</div>
								
							</fieldset>
						</div>
					</div><br />


					<?php if(($user_group_id>100 && $status_id>1) || $user_group_id == 3 || $user_group_id == 103){ ?>
					<div class="row" style="display:none">
					<?php }else{ ?>
					<div class="row">
					<?php } ?>	
						<div class="col-sm-12" id='goto_next_page'>
						<?php if (!empty($next_url)) { ?>
						<a href='<?php echo $next_url; ?>'><span class="btn btn-info">No Change</span></a>
						<?php } ?>
							<input class="btn btn-primary pull-right" type='submit' name='submit' value='<?php echo $submit; ?>' />		
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
		                  <div class="row">
							<?php 	if (!empty($payments) && is_array($payments) && (sizeof($payments > 0))) { ?>
		                  	<div class="col-sm-12">
			                  	<button type="button" class="btn btn-info" data-toggle="collapse" data-target="#history1">Payments <span class="fa fa-chevron-down"></span></button>
			  			        <div id="history1" class="collapse">                	
			                  	<form action='<?php echo $makepay_url; ?>' method='POST' class="form-horizontal">
								<input type='hidden' name='<?php echo $csrf['name']; ?>' value='<?php echo $csrf['value']; ?>'>
			                  		<div class="table-responsive">
			                  			<table class="table table-hover table-bordered">
				                      	<thead>
											<tr>
												<th>&nbsp;</th>
												<th>Type</th>
												<th>Pay Type</th>
												<th>Amount</th>
												<th>Rate</th>
												<th>Pay Status</th>
												<th>Date</th>
												<th>Info</th>
												<th>Notes</th>
											</tr>
										</thead>
										<tbody>
											<?php 
											foreach ($payments as $p) {
												$pay_str = '';
												$sbstr = substr($p['pay_type'], 0, 6);
												if ($p['ispaid']) {
													if (($sbstr == 'refund') || ($sbstr == 'cancel')) {
														$pay_str = 'N / A';
													} else {
														$pay_str = 'Paid';
													}
												} else {
													if ($sbstr == 'refund') {
														$pay_str = "<a href='" . $revert_url . $p['payment_id'] . "'>Revert Refund</a>";
													} else if ($sbstr == 'cancel') {
														$pay_str = "<a href='" . $revert_url . $p['payment_id'] . "'>Revert Cancel</a>";
													} else {
														$pay_str = '-';
													}
												}
												$pay_info = '';
												if (!empty($p['invoice_num'])) $pay_info .= "[".$p['invoice_num']."]";
												if (!empty($p['bank_name'])) $pay_info .= "[".$p['bank_name']."]";
												if (!empty($p['payor_name'])) $pay_info .= "[".$p['payor_name']."]";
												if (!empty($p['cheque_number'])) $pay_info .= "[".$p['cheque_number']."]";
												if (!empty($p['pay_to'])) $pay_info .= "[".$p['pay_to']."]";
												if (!empty($p['name'])) $pay_info .= "[".$p['name']."]";
												if (!empty($p['first5'])) $pay_info .= "[".$p['first5']."]";
												if (!empty($p['last4'])) $pay_info .= "[".$p['last4']."]";
												if (!empty($p['expiry_month'])) $pay_info .= "[".$p['expiry_month']."]";
												if (!empty($p['expiry_year'])) $pay_info .= "[".$p['expiry_year']."]";
												?>
											<tr>
												<td><?php if (empty($p['ispaid'])) { ?><input type='checkbox' name='payment[]' value='<?php echo $p['payment_id']; ?>'><?php } ?></td>
												<td><?php echo $p['pay_type']; ?></td>
												<td><?php echo $p['pay_mothed']; ?></td>
												<td><?php echo $p['amount']; ?></td>
												<td><?php echo $p['rate'] . "%"; ?></td>
												<td><?php echo $pay_str; ?></td>
												<td><?php echo $p['added']; ?></td>
												<td><?php echo $pay_info; ?></td>
												<td><?php echo (strlen($p['note']) > 60) ? (substr($p['note'], 0, 57) . "...") : $p['note']; ?></td>
											</tr>
											<?php 		} ?>
										</tbody>
										</table>
			                  		</div>
			                  		<div class="row"><div class="col-sm-12">
			                  			<input type="submit" class="btn btn-primary" name='submit' value='Make Pay'>
			                  		</div></div>
			                  		</form>
			                  		<hr />
			                  	</div>
			                  	
		                  	</div>
							<?php 	} ?>
		                  </div>
		                  <div class="row">
							<?php 	if (!empty($activelogs) && is_array($activelogs) && (sizeof($activelogs > 0))) { ?>
		                  	<div class="col-sm-12">
			                  	<button type="button" class="btn btn-info" data-toggle="collapse" data-target="#history2">Changes <span class="fa fa-chevron-down"></span></button>
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
										<?php 		} ?>
										</tbody>
										</table>
			                  		</div>
			                  	</div>
		                  	</div>
							<?php 	} ?>
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
	$('#error_next_page').click(function(){
		$('#error_next_page').css('display','none');
	});
</script>

<script>
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
    	},
	});
	$.ajax({
		url: '<?php echo $country_url; ?>',
		type: 'GET',
		success: function(data, textStatus, jqXHR) {
        	$('#country2_div').html(data);
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
			url: '<?php echo $deductible_amount_url; ?>',
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
	get_premium();
	addmoremember();
});

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
			i++;
		}
		$('#customer_id_8').val(0);
		$('#firstname_8').val('');
		$('#lastname_8').val('');
		$('#birthday_8').val('');
		$('#gender_8').val('M');
		get_premium();
		addmoremember();
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
	var sum_insured = 0;
	if ($('select[name="sum_insured"]').length) {
		sum_insured = $('select[name="sum_insured"]').val();	// select
	}
	var deductible_amount = 0;
	if ($('select[name="deductible_amount"]').length) {
		deductible_amount = $('select[name="deductible_amount"]').val();	// select
	}
	var stable_condition = 0;
	if ($('select[name="stable_condition"]').length) {
		stable_condition = $('select[name="stable_condition"]').val();	// radio
	}
	var rate_options = 0;
	if ($('select[name="rate_options"]').length) {
		rate_options = $('select[name="rate_options"]').val();	// radio
	}
	var holiday_rate = 0;
	if ($('input[name="holiday_rate"]').length) {
		holiday_rate = $('input[name="holiday_rate"]:checked').val();	// checkbox
	}
	var birthday = $('input[name="birthday"]').val();	// 
	var number_customer = 0;
	if ((product_short != 'NUS') && (product_short != 'JUS')) {
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
	if ($('input[name="firstname_1"]').val() && $('input[name="lastname_1"]').val()) number_customer++;
	if ($('input[name="firstname_2"]').val() && $('input[name="lastname_2"]').val()) number_customer++;
	if ($('input[name="firstname_3"]').val() && $('input[name="lastname_3"]').val()) number_customer++;
	if ($('input[name="firstname_4"]').val() && $('input[name="lastname_4"]').val()) number_customer++;
	if ($('input[name="firstname_5"]').val() && $('input[name="lastname_5"]').val()) number_customer++;
	if ($('input[name="firstname_6"]').val() && $('input[name="lastname_6"]').val()) number_customer++;
	if ($('input[name="firstname_7"]').val() && $('input[name="lastname_7"]').val()) number_customer++;
	if ($('input[name="firstname_8"]').val() && $('input[name="lastname_8"]').val()) number_customer++;

	if ($('#spousediv').length) {
		if ($('#spouse').get(0).checked) {
			spouse = 1;
		}
	}

	if (effective_date && expiry_date && birthday) {
		$.ajax({
			url: '<?php echo $premium_url; ?>',
			type: 'get',
			data: {
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
				birthday: birthday},
			success: function(data, textStatus, jqXHR) {
				if (data['status'] == 'OK') {
	        		$('input[name="premium"]').val(data['premiumarr']['premium']);
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
} 
</script>
<script type="text/javascript">
$(document).ready(function(){
    $("#arrival_date_div").datepicker({
        startDate: '-5y',
        endDate: '+2y',
    });
    $('#totaldays').change(function(){
        var days = $('#totaldays').val();
        var effective = $('#effective_date_div').datepicker('getDate');
        var myDate = new Date(effective);
        days--;
        myDate.setTime(myDate.getTime() + (days * 86400000));
        $('#expiry_date_div').datepicker('setDate', myDate);
        get_premium();
    });
});
</script>
