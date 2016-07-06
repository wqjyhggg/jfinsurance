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
					<?php if (empty($plan_id) || empty($status_id)) { ?>
						<?php /* it should be new plan */ ?>
						<input type='hidden' name='status_id' value='0'>
					<?php } else { ?>
					<div class="row" style="margin-bottom:15px;">
						<div class="col-sm-4">
						<?php /* it should be created plan */ ?>
						<?php if ($status_id == 1) { /* qutoe */ ?>

						<a href='<?php echo $pay_url; ?>'><span class="btn btn-info">Pay</span></a>
						<?php } ?>

						<a href='<?php echo $copy_url; ?>'><span class="btn btn-info">Copy</span></a>
						</div>
						<?php if ($user_group_id > 2) { ?>
							<?php /* it is school or brokerage or agent */ ?>
							Policy Status: <?php echo $status_list[$status_id]; ?> 
							<input type='hidden' name='status_id' value='<?php echo $status_id; ?>' class="form-control">
							<?php } else { ?>
							<div class="form-group col-sm-8">
							<label style="display:inline-block;vertical-align:middle;">Policy Status:</label>
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
							<label class="inline"><?php if ($status_id < 2) { ?>Quote<?php } else {?>Policy<?php } ?> Number: <span><?php echo $policy; ?></span></label>
						</div>
						<div class="col-sm-12">
						<fieldset>
   						 <legend>Travel Dates</legend>
   						 <div class="row">
							<div class="form-group col-sm-3">
								<label class="col-sm-12">Apply Date:</label>
								<div class="input-group date form_date col-sm-12" data-date="" data-date-format="yyyy/mm/dd" data-link-field="apply_date" data-link-format="yyyy-mm-dd">
			                        <input class="form-control" size="16" type="text" name='apply_date' value='<?php echo $apply_date; ?>' readonly>
			                        <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
			                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
			                    </div>
							</div>
							<div class="form-group col-sm-3">
								<label class="col-sm-12">Arrival Date: </label>
								<div class="input-group date form_date col-sm-12" data-date="" data-date-format="yyyy/mm/dd" data-link-field="arrival_date" data-link-format="yyyy-mm-dd">
			                        <input class="form-control" size="16" type="text" name='arrival_date' value='<?php echo $arrival_date; ?>' readonly>
			                        <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
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
								<div class="input-group date form_date col-sm-12" data-date="" data-date-format="yyyy/mm/dd" data-link-field="effective_date" data-link-format="yyyy-mm-dd">
			                        <input class="setpremium form-control" size="16" type="text" name='effective_date' value='<?php echo $effective_date; ?>' readonly>
			                        <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
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
						  		<div class="input-group date form_date col-sm-12" data-data="" data-date-format="yyyy/mm/dd" data-link-field="expiry_date" data-link-format="yyyy-mm-dd">
									<input size="16" type='text' name='expiry_date' class='setpremium form-control' value='<?php echo $expiry_date; ?>' readonly>
									<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
			                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
								</div>
								<?php if (!empty($error_expiry_date)) { ?>
								<div class="alert-error">
									<?php echo $error_expiry_date;?> 															
								</div>
								<?php } ?>
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
										<div class="input-group date form_date col-sm-12" data-date="" data-date-format="yyyy/mm/dd" data-link-field="application_date_from" data-link-format="yyyy-mm-dd">
					                        <input size="16" type="text" class='setpremium form-control' name='birthday' value='<?php echo !empty($birthday) ? $birthday : ''; ?>' readonly>
					                        <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
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
												<div class="col-sm-12">
													<select name='gender' class="form-control" style="padding:6px 2px;">
													<option value='M' <?php echo (empty($gender) || ($gender != 'F')) ? "selected" : ""; ?>>Male</option>
													<option value='F' <?php echo (!empty($gender) && ($gender == 'F')) ? "selected" : ""; ?>>Female</option>
													</select>
												</div>
											</div>
											<div class="col-sm-6">
												<label class="col-sm-12">&nbsp;</label>
												<div class="col-sm-12">
													<?php if (($status_id >= 2) && !empty($customer_id)) {?>
													<a class="btn btn-primary" href='<?php echo $claimurl . $customer_id; ?>'>Claim</a>
													<?php } ?>
												</div>
											</div>
										</div>
									</div>
									
								</div>	

								<div id='family_member' style='display:none'>
									<?php for ($i = 1; $i < 9; $i++) { ?>
									<div class="row">
									<input type='hidden' name='customer_id_<?php echo $i; ?>' value='<?php echo !empty(${'customer_id_'.$i}) ? ${'customer_id_'.$i} : 0; ?>'>
									<hr />
									<label class="col-sm-12">Family member <?php echo $i; ?> :</label>
										<div class="col-sm-3">
											<label class="col-sm-12">First Name: </label>
											<div class="col-sm-12">
												<input class="form-control" type='text' name='firstname_<?php echo $i; ?>' value='<?php echo !empty(${'firstname_'.$i}) ? ${'firstname_'.$i} : ''; ?>'>
											</div>
										</div>
										<div class="col-sm-3">
											<label class="col-sm-12">Last Name: </label>
											<div class="col-sm-12">	
												<input class="form-control" type='text' name='lastname_<?php echo $i; ?>' value='<?php echo !empty(${'lastname_'.$i}) ? ${'lastname_'.$i} : ''; ?>'>
											</div>
										</div>
										<div class="col-sm-3">
											<label class="col-sm-12">Birth Date: </label>
											
											<div class="input-group date form_date col-sm-12" data-date="" data-date-format="yyyy/mm/dd" data-link-field="application_date_from" data-link-format="yyyy-mm-dd">
					                        <input size="16" type="text" class='setpremium form-control' name='birthday_<?php echo $i; ?>' value='<?php echo !empty(${'birthday_'.$i}) ? ${'birthday_'.$i} : ''; ?>' readonly>
					                        <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
					                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
					                        </div>
										</div>
										<div class="col-sm-1">
											<label class="col-sm-12">Gender: </label>
											<div class="col-sm-12">
												<select name='gender_<?php echo $i; ?>' class="form-control">
												<option value='M' <?php echo (empty(${'gender_'.$i}) || (${'gender_'.$i} != 'F')) ? "selected" : ""; ?>>Male</option>
												<option value='F' <?php echo (!empty(${'gender_'.$i}) && (${'gender_'.$i} == 'F')) ? "selected" : ""; ?>>Female</option>
												</select>
											</div>
										</div>
										<div class="col-sm-2">
											<label class="col-sm-12">&nbsp;</label>
											<div class="col-sm-12">
												<?php if (($status_id >= 2) && !empty(${'customer_id_'.$i})) {?>
												<a href='<?php echo $claimurl . ${'customer_id_'.$i}; ?>'>Claim</a>
												<?php } ?>
											</div>
										</div>
									</div>
									<?php } ?>
								</div>
							</fieldset>
						</div>
					</div><br />

					<div class="row">
						<div class="col-sm-12">
							<fieldset>
								<legend>Address</legend>
								<div class="row">
									<div class="col-sm-3">
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
									<div class="col-sm-3">
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
									<div class="col-sm-3">
										<label class="col-sm-12">Suite No.: </label>
										<div class="input-group col-sm-12">
											<input class="form-control" type='text' name='suite_number' value='<?php echo $suite_number; ?>'>
										</div>
									</div>
									<div class="col-sm-3">
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
									<div class="col-sm-3">
										<label class="col-sm-12">Province: </label>
										<div class="input-group col-sm-12">
											<div id='province2_div'></div>
										</div>
									</div>
									<div class="col-sm-3">
										<label class="col-sm-12">Country: </label>
										<div class="input-group col-sm-12">
											<div id='country2_div'></div>
										</div>
									</div>
									<div class="col-sm-3">
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
								<div class="row">
									<div class="col-sm-3">
										<label class="col-sm-12">Days: </label>
										<div class="input-group col-sm-12">
											<div id='days'></div>	
										</div>
									</div>
									<div class="col-sm-3">
										<label class="col-sm-12">Oldest Customer: </label>
										<div class="input-group col-sm-12">
											<div id='years'></div>	
										</div>
									</div>
									<div class="col-sm-3">
										<label class="col-sm-12">Premium: </label>
										<div class="input-group col-sm-12">
											<?php if ($user_group_id <= 3) { ?>
											<input type='input' name='premium' id='premium' value='<?php echo $premium; ?>'>
											<?php } else { ?>
											<input class="form-control" type='hidden' name='premium' id='premium' value='<?php echo $premium; ?>'>	
											<div id='premium'><?php echo $premium; ?></div>	
											<?php } ?>
										</div>
									</div>
								</div>
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
					</div><br />
					
					<div class="row">
						<div class="col-sm-12">
							<input class="btn btn-primary pull-right" type='submit' name='submit' value='<?php echo $submit; ?>' />		
						</div>
					</div>
				</form> 

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
					if ( $( "#sum_insured_div" ).length ) {
						$.ajax({
							url: '<?php echo $sum_insured_url; ?>',
							success: function(data, textStatus, jqXHR) {
					        	$('#sum_insured_div').html(data);
					    	},
						});
					}
					if ( $( "#deductible_amount_div" ).length ) {
						$.ajax({
							url: '<?php echo $deductible_amount_url; ?>',
							success: function(data, textStatus, jqXHR) {
					        	$('#deductible_amount_div').html(data);
					    	},
						});
					}
					if ($('#isfamilyplan').get(0).checked) {
						$('#family_member').show();
					}
					$('#isfamilyplan').change(function() {
				        if ($(this).get(0).checked) {
				    		$('#family_member').show();
				        } else {
				        	$('#family_member').hide();
				        	$("input[name^='firstname_']").each(function() {
				            	$(this).val('');
				        	});
				        	$("input[name^='lastname_']").each(function() {
				            	$(this).val('');
				        	});
				        }
				    });

					$('.setpremium').change(get_premium); 
				});

				function get_premium() {
					var product_short = $('input[name="product_short"]').val();
					var apply_date = $('input[name="apply_date"]').val();
					var effective_date = $('input[name="effective_date"]').val();
					var expiry_date = $('input[name="expiry_date"]').val();
					var isfamilyplan = '';
					if ($('input[name="isfamilyplan"]').is(':checked')) {
						isfamilyplan = 1;	// checkbox
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
					if ($('input[name="stable_condition"]').length) {
						stable_condition = $('input[name="stable_condition"]:checked').val();	// radio
					}
					var rate_options = 0;
					if ($('input[name="rate_options"]').length) {
						rate_options = $('input[name="rate_options"]:checked').val();	// radio
					}
					var birthday = $('input[name="birthday"]').val();	// 
					var number_customer = 0;
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
					
					if ($('input[name="firstname"]').val() && $('input[name="lastname"]').val()) number_customer++;
					if ($('input[name="firstname_1"]').val() && $('input[name="lastname_1"]').val()) number_customer++;
					if ($('input[name="firstname_2"]').val() && $('input[name="lastname_2"]').val()) number_customer++;
					if ($('input[name="firstname_3"]').val() && $('input[name="lastname_3"]').val()) number_customer++;
					if ($('input[name="firstname_4"]').val() && $('input[name="lastname_4"]').val()) number_customer++;
					if ($('input[name="firstname_5"]').val() && $('input[name="lastname_5"]').val()) number_customer++;
					if ($('input[name="firstname_6"]').val() && $('input[name="lastname_6"]').val()) number_customer++;
					if ($('input[name="firstname_7"]').val() && $('input[name="lastname_7"]').val()) number_customer++;
					if ($('input[name="firstname_8"]').val() && $('input[name="lastname_8"]').val()) number_customer++;

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
								number_customer: number_customer,
								birthday: birthday},
							success: function(data, textStatus, jqXHR) {
								if (data['status'] == 'OK') {
					        		$('input[name="premium"]').val(data['premiumarr']['premium']);
									<?php if ($user_group_id > 3) { ?>
									$('#premium').html(data['premiumarr']['premium']);
									<?php } ?>
									$('#years').html(data['premiumarr']['years']);
									$('#days').html(data['premiumarr']['days']);
									if (data['premiumarr']['message']) {
										alert(data['premiumarr']['message']);
									}
								} else {
									if (data['message']) {
										alert(data['message']);
									}
								}
					    	},
						});
					}
				} 
				</script>

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
			                  		<div class="table-responsive">
			                  			<table class="table table-hover table-bordered">
				                      	<thead>
											<tr>
												<th>ID</th>
												<th>Amount</th>
												<th>Type</th>
												<th>Date</th>
												<th>Notes</th>
											</tr>
										</thead>
										<tbody>
											<?php foreach ($payments as $p) { ?>
											<tr>
												<td><?php echo $p['payment_id']; ?></td>
												<td><?php echo $p['amount']; ?></td>
												<td><?php echo $p['ispaid'] ? "Paied" : $p['pay_type']; ?></td>
												<td><?php echo $p['added']; ?></td>
												<td><?php echo (strlen($p['note']) > 60) ? (substr($p['note'], 0, 57) . "...") : $p['note']; ?></td>
											</tr>
											<?php 		} ?>
										</tbody>
										</table>
			                  		</div>
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
												<th>ID</th>
												<th>Message</th>
											</tr>
										</thead>
										<tbody>
										<?php foreach ($activelogs as $p) { ?>
											<tr>
												<td><?php echo $p['activity_id']; ?></td>
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