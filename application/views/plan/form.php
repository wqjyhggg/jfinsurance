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
        <div class="right_col" role="main" style="margin-bottom:60px;">
          
            <div class="page-title">
              <div class="title_left">
                <h3>Create Policy</h3>
              </div>
            </div>
            <div class="clearfix"></div>
           <!-- Form Section -->
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Policy Form<small></small></h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    
					<!--<script src="<?php echo base_url(); ?>js/jquery-3.0.0.min.js" type="text/javascript"></script>-->
					<?php if (!empty($error_message)) { echo $error_message . "<br>"; } ?>

					<form action='<?php echo $action_url; ?>' method='POST' class="form-horizontal">

						<input type='hidden' name='<?php echo $csrf['name']; ?>' value='<?php echo $csrf['value']; ?>'>
						<input type='hidden' name='plan_id' value='<?php echo $plan_id; ?>'>
						<input type='hidden' name='product_short' value='<?php echo $product_short; ?>'>
					<?php if (empty($plan_id) || empty($status_id)) { ?>
						<?php /* it should be new plan */ ?>
						<input type='hidden' name='status_id' value='0'>
					<?php } else { ?>
						<?php /* it should be created plan */ ?>
						<?php if ($status_id == 1) { /* qutoe */ ?>
						<a href='<?php echo $pay_url; ?>'>Pay</a>
						<?php } ?>
						<a href='<?php echo $copy_url; ?>'>Copy</a>
						<?php if ($user_group_id > 2) { ?>
							<?php /* it is school or brokerage or agent */ ?>
							Policy Status: <?php echo $status_list[$status_id]; ?> 
							<input type='hidden' name='status_id' value='<?php echo $status_id; ?>'>
							<?php } else { ?>
							Policy Status: <select name='status_id'>
								<option value='0'> -- select policy status -- </option>
								<?php foreach ($status_list as $key => $value) { ?>
								<option value='<?php echo $key; ?>' <?php echo ($key == $status_id) ? 'selected' : ''; ?>><?php echo $value['name']; ?></option>
								<?php } ?>
							</select>
							<?php } ?>
					<?php } ?>
					<div class="row">
						<div class="col-sm-12">
						<fieldset>
   						 <legend>Travel Dates</legend>
   						 <div class="row">
							<div class="form-group col-sm-3">
								<label class="col-sm-12">Apply Date:</label>
								<div class="input-group date form_date col-sm-12" data-date="" data-date-format="yyyy/mm/dd" data-link-field="application_date_from" data-link-format="yyyy-mm-dd">
			                        <input class="form-control" size="16" type="text" name='apply_date' value='<?php echo $apply_date; ?>' readonly>
			                        <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
			                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
			                    </div>
							</div>
							<div class="form-group col-sm-3">
								<label class="col-sm-12">Arrival Date: </label>
								<div class="input-group date form_date col-sm-12" data-date="" data-date-format="yyyy/mm/dd" data-link-field="application_date_from" data-link-format="yyyy-mm-dd">
			                        <input class="form-control" size="16" type="text" name='arrival_date' value='<?php echo $arrival_date; ?>' readonly>
			                        <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
			                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
			                        <?php if (!empty($error_arrival_date)) { echo $error_arrival_date; } ?>
			                    </div>

							</div>
							<div class="form-group col-sm-3">
								<label class="col-sm-12">Effective Date: </label>
								<div class="input-group date form_date col-sm-12" data-date="" data-date-format="yyyy/mm/dd" data-link-field="application_date_from" data-link-format="yyyy-mm-dd">
			                        <input class="form-control" size="16" type="text" name='effective_date' value='<?php echo $effective_date; ?>' readonly>
			                        <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
			                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
			                        <?php if (!empty($error_effective_date)) { echo $error_effective_date; } ?>
			                    </div>
							</div>
							<div class="form-group col-sm-3">
								<label class="col-sm-12">Expiry Date: </label>
						  		<div class="input-group date form_date col-sm-12" data-data="" data-data-format="yyyy/mm/dd" data-link-field="application_date_form" data-link-format="yyyy-mm-dd">
									<input type='text' name='expiry_date' name='expiry_date' class='setpremium form-control' value='<?php echo $expiry_date; ?>' readonly>
									<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
			                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
									<?php if (!empty($error_expiry_date)) { echo $error_expiry_date; } ?>							
								</div>
								
							</div>
						</div>
					 	</fieldset>
					 	</div>
					</div><br />
					<div class="row">
						<div class="col-sm-12">
							<fieldset>
								<legend>Insurable Options</legend>
								<div class="row">
									<div class="form-group col-sm-3">
										<label class="col-sm-12">Beneficiary</label>
										<div class="input-group col-sm-12">
											<input type='text' name='beneficiary' value='<?php echo $beneficiary; ?>' class="form-control">
											<?php if (!empty($error_beneficiary)) { echo $error_beneficiary; } ?>

										</div>
									</div>
									<div class="form-group col-sm-3">
										<label class="col-sm-12">Is Family Plan</label>
										<div class="input-group col-sm-12" style="border: 1px solid #ccc;padding: 5px;">
											 <input type='checkbox' class='setpremium' name='isfamilyplan' id='isfamilyplan' <?php echo empty($isfamilyplan) ? "" : "checked"; ?>> Yes
										</div>
									</div>
									<div class="form-group col-sm-3">
										<label class="col-sm-12">Sum Insured (CAD):</label>
										<div class="input-group col-sm-12">
											<div id='sum_insured_div'></div>
										</div>
									</div>
									<div class="form-group col-sm-3">
										<label class="col-sm-12">Deductible amount (CAD):</label>
										<div class="input-group col-sm-12">
											 <div id='deductiable_amount_div'></div>
										</div>
									</div>
								</div>
								<div class="row">
									<?php if (empty($disable_stable_condition)) { ?>
									<div class="col-sm-6">
										<label class="col-sm-8">With stable pre-existion condition coverage</label>
										<div class="input-group col-sm-4">
											<input type='radio' class='setpremium' name='stable_condition' value='1' <?php echo (empty($stable_condition) || ($stable_condition != 2 )) ? "checked" : ""; ?>>
										</div>
										<label class="col-sm-8">Without stable pre-existion condition coverage </label>
										<div class="input-group col-sm-4">
											<input type='radio' class='setpremium' name='stable_condition' value='2' <?php echo (!empty($stable_condition) && ($stable_condition == 2 )) ? "checked" : ""; ?>>
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
								<legend>Insurable Members</legend>
								<input type='hidden' name='customer_id' value='<?php echo !empty($customer_id) ? $customer_id : 0; ?>'>
								<div class="row">
									<label class="col-sm-12">Customer Information</label>
									<div class="col-sm-3">
										<label class="col-sm-12">First Name:</label>
										<div class="input-group col-sm-12">
											<input class="form-control" type='text' name='firstname' value='<?php echo !empty($firstname) ? $firstname : ''; ?>'>
											<?php if (!empty($error_firstname)) { echo $error_firstname; } ?>
										</div>
									</div>
									<div class="col-sm-3">
										<label class="col-sm-12">Last Name:</label>
										<div class="input-group col-sm-12">
											<input class="form-control" type='text' name='lastname' value='<?php echo !empty($lastname) ? $lastname : ''; ?>'>
											<?php if (!empty($error_lastname)) { echo $error_lastname; } ?>
										</div>
									</div>
									<div class="col-sm-3">
										<label class="col-sm-12">Birth Date:</label>
										<div class="input-group date form_date col-sm-12" data-date="" data-date-format="yyyy/mm/dd" data-link-field="application_date_from" data-link-format="yyyy-mm-dd">
					                        <input size="16" type="text" class='setpremium form-control' name='birthday' value='<?php echo !empty($birthday) ? $birthday : ''; ?>' readonly>
					                        <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
					                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
					                        <?php if (!empty($error_birthday)) { echo $error_birthday; } ?>
					                    </div>
									</div>
									<div class="col-sm-3">
										<label class="col-sm-12">Gender: </label>
										<div class="col-sm-12">
											<select name='gender' class="form-control">
											<option value='M' <?php echo (empty($gender) || ($gender != 'F')) ? "selected" : ""; ?>>Male</option>
											<option value='F' <?php echo (!empty($gender) && ($gender == 'F')) ? "selected" : ""; ?>>Female</option>
											</select>
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
										<div class="col-sm-3">
											<label class="col-sm-12">Gender: </label>
											<div class="col-sm-12">
												<select name='gender_<?php echo $i; ?>' class="form-control">
												<option value='M' <?php echo (empty(${'gender_'.$i}) || (${'gender_'.$i} != 'F')) ? "selected" : ""; ?>>Male</option>
												<option value='F' <?php echo (!empty(${'gender_'.$i}) && (${'gender_'.$i} == 'F')) ? "selected" : ""; ?>>Female</option>
												</select>
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
											<?php if (!empty($error_street_number)) { echo $error_street_number; } ?>
										</div>
									</div>
									<div class="col-sm-3">
										<label class="col-sm-12">Street Name: </label>
										<div class="input-group col-sm-12">
											<input class="form-control" type='text' name='street_name' value='<?php echo $street_name; ?>'>
											<?php if (!empty($error_street_name)) { echo $error_street_name; } ?>
										</div>
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
											<?php if (!empty($error_city)) { echo $error_city; } ?>
										</div>
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
									</div>
								</div>
								<div class="row">
									<div class="col-sm-3">
										<label class="col-sm-12">Phone1: </label>
										<div class="input-group col-sm-12">
											<input class="form-control" type='text' name='phone1' value='<?php echo $phone1; ?>'>
											<?php if (!empty($error_phone1)) { echo $error_phone1; } ?>
										</div>
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
											<?php if (!empty($error_contact_email)) { echo $error_contact_email; } ?>
										</div>
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
								<?php if ($user_group_id <= 3) { ?>
								<div class="row">
									<div class="col-sm-3">
										<label class="col-sm-12">Premium: </label>
										<div class="input-group col-sm-12">
											<input type='input' name='premium' id='premium' value='<?php echo $premium; ?>'>
										</div>
									</div>
								</div>
								<?php } else { ?>
									<input class="form-control" type='hidden' name='premium' id='premium' value='<?php echo $premium; ?>'>	
								<?php } ?>
								<div class="row">
									<div class="col-sm-12">
										<label class="col-sm-12">Notes: </label>
										<div class="input-group col-sm-12">
								 			<textarea class="form-control" name="note" value="<?php echo $note; ?>"></textarea>
								 		</div>
								 	</div>
								</div>
								
							</fieldset>
						</div>
					</div><br />
					
					<div class="row">
						<div class="col-sm-12">
							<input class="btn btn-default pull-right" type='submit' name='submit' value='<?php echo $submit; ?>' />		
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
					$.ajax({
						url: '<?php echo $sum_insured_url; ?>',
						success: function(data, textStatus, jqXHR) {
				        	$('#sum_insured_div').html(data);
				    	},
					});
					$.ajax({
						url: '<?php echo $deductiable_amount_url; ?>',
						success: function(data, textStatus, jqXHR) {
				        	$('#deductiable_amount_div').html(data);
				    	},
					});
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
					var sum_insured = $('select[name="sum_insured"]').val();	// select
					var deductiable_amount = $('select[name="deductiable_amount"]').val();	// select
					var stable_condition = $('input[name="stable_condition"]:checked').val();	// radio
					var birthday = $('input[name="birthday"]').val();	// 
					if (new Date(birthday) < new Date($('input[name="birthday_1"]').val())) {
						birthday = $('input[name="birthday_1"]').val();
					} 
					if (new Date(birthday) < new Date($('input[name="birthday_2"]').val())) {
						birthday = $('input[name="birthday_2"]').val();
					} 
					if (new Date(birthday) < new Date($('input[name="birthday_3"]').val())) {
						birthday = $('input[name="birthday_3"]').val();
					} 
					if (new Date(birthday) < new Date($('input[name="birthday_4"]').val())) {
						birthday = $('input[name="birthday_4"]').val();
					} 
					if (new Date(birthday) < new Date($('input[name="birthday_5"]').val())) {
						birthday = $('input[name="birthday_5"]').val();
					} 
					if (new Date(birthday) < new Date($('input[name="birthday_6"]').val())) {
						birthday = $('input[name="birthday_6"]').val();
					} 
					if (new Date(birthday) < new Date($('input[name="birthday_7"]').val())) {
						birthday = $('input[name="birthday_7"]').val();
					} 
					if (new Date(birthday) < new Date($('input[name="birthday_8"]').val())) {
						birthday = $('input[name="birthday_8"]').val();
					}
					if (effective_date && expiry_date && sum_insured && birthday) {
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
								deductiable_amount: deductiable_amount,
								stable_condition: stable_condition,
								birthday: birthday},
							success: function(data, textStatus, jqXHR) {
								if (data['status'] == 'OK') {
					        		$('#premium').val(data['premium']);
								} else {
									alert('Unavailable');
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
<?php 		foreach ($payments as $p) { ?>
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
<?php 		foreach ($activelogs as $p) { ?>
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
        <!-- /page content -->