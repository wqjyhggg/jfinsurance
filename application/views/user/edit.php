
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!-- Report/ Sales report to JF page content -->

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
          <div class="main-div">
            <div class="page-title">
              <div class="title_left">
                <h3>User Form</h3>
              </div>
            </div>
            <div class="clearfix"></div>
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Edit Form<small></small></h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
					<?php if (!empty($error_message)) { ?>

					<div class="alert-error">
						<?php echo $error_message; ?>
						<br />
					</div>
					<?php } ?>
					<form action='<?php echo $action_url; ?>' method='POST' class="form-horizontal">

					<input type='hidden' name='<?php echo $csrf['name']; ?>' value='<?php echo $csrf['value']; ?>'>
					<input type='hidden' name='user_id' value='<?php echo $user_id; ?>'>
<?php if ($op_user_group_id < 100) { ?>
					<div class="row">
						<div class="col-sm-3 form-group">
		                  <label class="col-sm-12">User Type:</label>
		                  <div class="col-sm-12 input-group">
							<select name='user_group_id' class="form-control">
							<option value='0' <?php echo empty($user_group_id) ? 'selected' : ''; ?>> -- select user group -- </option>
							<?php foreach ($user_group_list as $key => $name) { ?>
							<option value='<?php echo $key; ?>' <?php echo ($user_group_id == $key) ? 'selected' : ''; ?>><?php echo $name; ?></option>
							<?php } ?>
							</select>
							<?php if (!empty($error_user_group)){ ?>
							<div class="alert-error">	
								<?php echo $error_user_group; ?>
							</div>
							<?php } ?>
						  </div>
	              		</div>
	              		<div class="col-sm-3 form-group">
		                  <label class="col-sm-12">Company:</label>
		                  <div class="col-sm-12 input-group">
		                  	<select name='parent_user_id' class="form-control">
								<option value='0' <?php echo empty($parent_user_id) ? 'selected' : ''; ?>> -- select brokerage -- </option>
								<?php foreach ($broker_list as $key => $name) { ?>
								<option value='<?php echo $key; ?>' <?php echo ($parent_user_id == $key) ? 'selected' : ''; ?>><?php echo $name; ?></option>
								<?php } ?>
							</select>
		                  </div>
	              		</div>
	              		<div class="form-group col-sm-3">
			                <label class="col-sm-12">Region:</label>
			                <div class="input-group col-sm-12">
                            <select name='region_id' class="form-control">
                              <option value='0'> -- All Region -- </option>
                              <?php foreach ($regions as $key => $name) { ?>
                              <option value='<?php echo $key; ?>' <?php echo ($region_id == $key) ? 'selected' : ''; ?>><?php echo $name; ?></option>
                              <?php } ?>
                            </select>
			                </div>
			            </div>
			            <div class="form-group col-sm-3">
			                <label class="col-sm-12">Business Name:</label>
			                <div class="input-group col-sm-12">
			                	<input type='text' name='business' value='<?php echo $business; ?>' class="form-control">
			                	<?php if (!empty($error_business)){ ?>
								<div class="alert-error">	
									<?php echo $error_business; ?>
								</div>
								<?php } ?>
			                </div>
			            </div>

	              	</div>
<?php } else {?>					
					<input type='hidden' name='user_group_id' value='<?php echo $user_group_id; ?>'>
					<input type='hidden' name='parent_user_id' value='<?php echo $parent_user_id; ?>'>
					<input type='hidden' name='region' value='<?php echo $region; ?>'>
					<input type='hidden' name='business' value='<?php echo $business; ?>'>
					<div class="row">
			            <div class="form-group col-sm-3">
			                <label class="col-sm-12"><?php echo $business; ?></label>
			            </div>
	              	</div>
<?php } ?>
	              	<div class="row">
	              		<div class="form-group col-sm-3">
			                <label class="col-sm-12">Username:</label>
			                <div class="input-group col-sm-12">
			                	<input type='text' name='username' value='<?php echo $username; ?>' class="form-control">
			                	<?php if (!empty($error_username)){ ?>
								<div class="alert-error">	
									<?php echo $error_username; ?>
								</div>
								<?php } ?>
			                </div>
			            </div>
			            <div class="form-group col-sm-3">
			                <label class="col-sm-12">Password:</label>
			                <div class="input-group col-sm-12">
			                	<input type='password' name='password' value='' class="form-control" class="form-control">
			                	
			                	<?php if (!empty($error_password)){ ?>
								<div class="alert-error">	
									<?php echo $error_password; ?>
								</div>
								<?php } ?>			                	
			                </div>
			            </div>
			            <div class="form-group col-sm-2">
			                <label class="col-sm-12">First Name:</label>
			                <div class="input-group col-sm-12">
			                	<input type='text' name='firstname' value='<?php echo $firstname; ?>' class="form-control">
			                	<?php if (!empty($error_firstname)){ ?>
								<div class="alert-error">	
									<?php echo $error_firstname; ?>
								</div>
								<?php } ?>
			                </div>
			            </div>
			            <div class="form-group col-sm-2">
			                <label class="col-sm-12">Last Name:</label>
			                <div class="input-group col-sm-12">
			                	<input type='text' name='lastname' value='<?php echo $lastname; ?>' class="form-control">
			                	<?php if (!empty($error_lastname)){ ?>
								<div class="alert-error">	
									<?php echo $error_lastname; ?>
								</div>
								<?php } ?>
			                </div>
			            </div>
			            <div class="form-group col-sm-2">
			                <label class="col-sm-12">Gender:</label>
			                <div class="col-sm-12 input-group">
				                <select name='gender' class="form-control">
									<option value='M' <?php echo (empty($gender) || ($gender == 'M')) ? 'selected' : ''; ?>> M - Male </option>
									<option value='F' <?php echo ($gender == 'F') ? 'selected' : ''; ?>> F - Female </option>
								</select>
							</div>
			            </div>
	              	</div>

	              	<div class="row">
	              		<div class="form-group col-sm-3">
			                <label class="col-sm-12">E-mail:</label>
			                <div class="input-group col-sm-12">
			                	<input type='text' name='email' value='<?php echo $email; ?>' class="form-control">
			                	<?php if (!empty($error_email)){ ?>
								<div class="alert-error">	
									<?php echo $error_email; ?>
								</div>
								<?php } ?>
			                </div>
			            </div>
			            <div class="form-group col-sm-9">
			                <label class="col-sm-12">Address:</label>
			                <div class="input-group col-sm-12">
			                	<input type='text' name='address' value='<?php echo $address; ?>' class="form-control">
			                	<?php if (!empty($error_address)){ ?>
								<div class="alert-error">	
									<?php echo $error_address; ?>
								</div>
								<?php } ?>
			                </div>
			            </div>
			        </div>
			        <div class="row">
			            <div class="form-group col-sm-3">
			                <label class="col-sm-12">City:</label>
			                <div class="input-group col-sm-12">
			                	<input type='text' name='city' value='<?php echo $city; ?>' class="form-control">
			                	<?php if (!empty($error_city)){ ?>
								<div class="alert-error">	
									<?php echo $error_city; ?>
								</div>
								<?php } ?>
			                </div>
			            </div>
			            <div class="form-group col-sm-3">
			                <label class="col-sm-12">Province:</label>
			                <div class="input-group col-sm-12">
			                	<div id='province2_div'></div>
			                	<?php if (!empty($error_province)){ ?>
								<div class="alert-error">	
									<?php echo $error_province; ?>
								</div>
								<?php } ?>
			                </div>
			            </div>
			            <div class="form-group col-sm-3">
			                <label class="col-sm-12">Country:</label>
			                <div class="input-group col-sm-12">
			                	<div id='country2_div'></div>
			                </div>
			            </div>
			            <div class="form-group col-sm-3">
			                <label class="col-sm-12">Post Code:</label>
			                <div class="col-sm-12 input-group">
				                <input type='text' name='postcode' value='<?php echo $postcode; ?>' class="form-control">
								<?php if (!empty($error_postcode)){ ?>
								<div class="alert-error">	
									<?php echo $error_postcode; ?>
								</div>
								<?php } ?>
							</div>
			            </div>
	              	</div>
	              	<div class="row">
	              		<div class="form-group col-sm-3">
			                <label>Mail is same:</label>
		                	<input type='checkbox' name='issame' id='issame' value='1' checked>
			            </div>
			            <div class="form-group col-sm-9 mailaddr">
			                <label class="col-sm-12">Mail Address:</label>
			                <div class="input-group col-sm-12">
			                	<input type='text' name='mail_address' value='<?php echo $mail_address; ?>' class="form-control">
			                	<?php if (!empty($error_mail_address)){ ?>
								<div class="alert-error">	
									<?php echo $error_mail_address; ?>
								</div>
								<?php } ?>
			                </div>
			            </div>
			        </div>
			        <div class="row mailaddr">
			            <div class="form-group col-sm-3">
			                <label class="col-sm-12">Mail City:</label>
			                <div class="input-group col-sm-12">
			                	<input type='text' name='mail_city' value='<?php echo $mail_city; ?>' class="form-control">
			                	<?php if (!empty($error_mail_city)){ ?>
								<div class="alert-error">	
									<?php echo $error_mail_city; ?>
								</div>
								<?php } ?>
			                </div>
			            </div>
			            <div class="form-group col-sm-3">
			                <label class="col-sm-12">Mail Province:</label>
			                <div class="input-group col-sm-12">
			                	<div id='mail_province2_div'></div>
			                	<?php if (!empty($error_mail_province)){ ?>
								<div class="alert-error">	
									<?php echo $error_mail_province; ?>
								</div>
								<?php } ?>
			                </div>
			            </div>
			            <div class="form-group col-sm-3">
			                <label class="col-sm-12">Mail Country:</label>
			                <div class="input-group col-sm-12">
			                	<div id='mail_country2_div'></div>
			                </div>
			            </div>
			            <div class="form-group col-sm-3">
			                <label class="col-sm-12">Mail Post Code:</label>
			                <div class="col-sm-12 input-group">
				                <input type='text' name='mail_postcode' value='<?php echo $mail_postcode; ?>' class="form-control">
								<?php if (!empty($error_mail_postcode)){ ?>
								<div class="alert-error">	
									<?php echo $error_mail_postcode; ?>
								</div>
								<?php } ?>
							</div>
			            </div>
	              	</div>
	              	<div class="row">
			            <div class="form-group col-sm-3">
			                <label class="col-sm-12">Website:</label>
			                <div class="input-group col-sm-12">
			                	<input type='text' name='website' value='<?php echo $website; ?>' class="form-control">
			                </div>
			            </div>
			            <div class="form-group col-sm-3">
			                <label class="col-sm-12">Licence No.:</label>
			                <div class="input-group col-sm-12">
			                	<input type='text' name='licence_number' value='<?php echo $licence_number; ?>' class="form-control">
			                	<?php if (!empty($error_licence_number)){ ?>
								<div class="alert-error">	
									<?php echo $error_licence_number; ?>
								</div>
								<?php } ?>
			                </div>
			            </div>
			            <div class="form-group col-sm-3">
			                <label class="col-sm-12">Licence Expire Date:</label>
			                <div class="input-group col-sm-12">
			                	<div class="input-group date" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd">
			                        <input class="form-control" size="16" type="text" name='licence_expire' value='<?php echo $licence_expire; ?>' >
			                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
			                    </div>

			                	<?php if (!empty($error_licence_expire)){ ?>
								<div class="alert-error">	
									<?php echo $error_licence_expire; ?>
								</div>
								<?php } ?>
			                </div>
			            </div>
			            <div class="form-group col-sm-3">
			                <label class="col-sm-12">Create Date:</label>
			                <div class="input-group col-sm-12">
			                	<?php echo $date_added; ?>
			                </div>
			            </div>
			            
	              	</div>
					<div class="row">
			            <div class="form-group col-sm-3">
			                <label class="col-sm-12">Business Phone:</label>
			                <div class="input-group col-sm-12">
			                	<input type='text' name='business_phone' value='<?php echo $business_phone; ?>' class="form-control">
			                </div>
			            </div>
			            <div class="form-group col-sm-3">
			                <label class="col-sm-12">Mobile Phone:</label>
			                <div class="input-group col-sm-12">
			                	<input type='text' name='mobile_phone' value='<?php echo $mobile_phone; ?>' class="form-control">
			                </div>
			            </div>
			            <div class="form-group col-sm-3">
			                <label class="col-sm-12">Fax:</label>
			                <div class="input-group col-sm-12">
			                	<input type='text' name='fax_number' value='<?php echo $fax_number; ?>' class="form-control">
			                </div>
			            </div>
			            <div class="form-group col-sm-3">
			                <label class="col-sm-12">Toll Free:</label>
			                <div class="input-group col-sm-12">
			                	<input type='text' name='toll_free' value='<?php echo $toll_free; ?>' class="form-control">
			                </div>
			            </div>
			            
	              	</div>
					<hr />
<?php if ($op_user_group_id < 100) { ?>
					<div class="row">
						<div class="form-group col-sm-12">
							<h4>Products:</h4>

							<?php foreach ($product_list as $key => $pd) { ?>
							<div class="col-sm-6">
							<input  type='checkbox' name='product_list[]' value='<?php echo $key; ?>' <?php echo $pd['checked']; ?>> <?php echo $pd['product_short'] . " - " . $pd['full_name']; ?><br>
							<?php echo $pd['product_short']; ?> Commission (%): <input type='text' name='product_commission_<?php echo $key; ?>' value='<?php echo $pd['commission']; ?>'><br>
							<br />
							</div>
							<?php } ?>
						</div>

					</div>
					
				
					<div class="row">
						<div class="form-group col-sm-6">
							<p><b>Allow Customer Pay type:</b></p>
							<?php foreach ($paytype_list as $pay) { ?>
							<div class="col-sm-4">
							<input type='checkbox' name='paytype_list[]' value='<?php echo $pay; ?>' <?php echo (strpos($pay_type, $pay) === FALSE) ? '' : 'checked'; ?>> <?php echo $pay; ?><br>
							</div>
							<?php } ?>
		                	<?php if (!empty($error_paytype_list)){ ?>
							<div class="alert-error">	
								<?php echo $error_paytype_list; ?>
							</div>
							<?php } ?>
						</div>
						<div class="form-group col-sm-3">
							<label class="col-sm-12">Status:</label>
			                <div class="col-sm-12 input-group">
								<select name='status' class="form-control">
									<option value='1' <?php echo ($status || empty($user_id)) ? 'selected' : ''; ?>>Active</option>
									<option value='0' <?php echo (empty($status) && $user_id) ? 'selected' : ''; ?>>Disable</option>
								</select>
							</div>
					    </div>
						<div class="form-group col-sm-3">
							<label class="col-sm-12">Pay to Agent:</label>
			                <div class="col-sm-12 input-group">
								<select name='receive_type' class="form-control">
									<option value='Cheque' <?php echo ($receive_type == 'Cheque') ? 'selected' : ''; ?>>Cheque</option>
									<option value='Cash' <?php echo ($receive_type == 'Cash') ? 'selected' : ''; ?>>Cash</option>
									<option value='Deposit' <?php echo ($receive_type == 'Deposit') ? 'selected' : ''; ?>>Deposit</option>
								</select>
							</div>
					    </div>

					</div>

					
					<div class="row">
						<label class="col-sm-12">Notes:</label>
			            <div class="col-sm-12 input-group">
			            	<textarea name='note' class="form-control"><?php echo $note; ?></textarea>
			            </div>
					</div><br />
<?php } ?>

					<div class="row" style="margin-bottom:200px;">
		              <!-- submit button -->
		              <div class="col-sm-12">
		              	<input class="btn btn-primary pull-right" type='submit' value='<?php echo ($user_id) ? "Update" : "Add"; ?>'>
		              </div> 
		              <!-- submit button -->
		            </div>
					
					<br>
					
					</form>

					<script>
					$( document ).ready(function() {
						$.ajax({
							url: '<?php echo $country_url; ?>',
							type: 'GET',
							success: function(data, textStatus, jqXHR) {
					        	$('#country2_div').html(data);
								$.ajax({
									url: '<?php echo $mail_country_url; ?>?myid=mail_',
									type: 'GET',
									success: function(data, textStatus, jqXHR) {
							        	$('#mail_country2_div').html(data);
										$.ajax({
											url: '<?php echo $mail_province_url; ?>?myid=mail_',
											type: 'GET',
											success: function(data, textStatus, jqXHR) {
									        	$('#mail_province2_div').html(data);
												$('#province2').change(function() {
													if ($('#issame').is(':checked')) {
														$('#mail_province2').val($('#province2').val());
													}
												});
									    	},
										});
										$('#country2').change(function() {
											if ($('#issame').is(':checked')) {
												$('#mail_country2').val($('#country2').val());
												$("#mail_country2").trigger( "change" );
											}
										});
							    	},
								});
								$.ajax({
									url: '<?php echo $province_url; ?>',
									type: 'GET',
									success: function(data, textStatus, jqXHR) {
							        	$('#province2_div').html(data);
							    	},
								});
					    	},
						});

						$("input[name='address']").change(function() {
							if ($('#issame').is(':checked')) {
								$("input[name='mail_address']").val($("input[name='address']").val());
							}
						});
						$("input[name='city']").change(function() {
							if ($('#issame').is(':checked')) {
								$("input[name='mail_city']").val($("input[name='city']").val());
							}
						});
						$("input[name='postcode']").change(function() {
							if ($('#issame').is(':checked')) {
								$("input[name='mail_postcode']").val($("input[name='postcode']").val());
							}
						});
						$('#issame').change(issamecheck);

						issamecheck();
					});
					function issamecheck() {
						if ($('#issame').is(':checked')) {
							$(".mailaddr").hide();
						} else {
							$("input[name='mail_address']").val($("input[name='address']").val());
							$("input[name='mail_city']").val($("input[name='city']").val());
							$("input[name='mail_postcode']").val($("input[name='postcode']").val());
							$('#mail_country2').val($('#country2').val());
							$('#mail_province2').val($('#province2').val());
							$(".mailaddr").show();
						}
					};
					</script>


 				  </div>
                </div>
              </div>
            </div>

          </div>
        </div>
        <!-- /page content -->
