
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
					<?php if (!empty($error_message)) { echo $error_message . "<br>"; } ?>
					<form action='<?php $action_url; ?>' method='POST' class="form-horizontal">

					<input type='hidden' name='<?php echo $csrf['name']; ?>' value='<?php echo $csrf['value']; ?>'>
					<input type='hidden' name='user_id' value='<?php echo $user_id; ?>'>
					
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
						  </div>
	              		</div>
	              		<div class="col-sm-3 form-group">
		                  <label class="col-sm-12">User Type:</label>
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
			                	<input type='text' name='region' value='<?php echo $region; ?>' class="form-control">
			                </div>
			            </div>
			            <div class="form-group col-sm-3">
			                <label class="col-sm-12">Brokerage:</label>
			                <div class="input-group col-sm-12">
			                	<input type='text' name='business' value='<?php echo $business; ?>' class="form-control">
			                </div>
			            </div>

	              	</div>
	              	<div class="row">
	              		<div class="form-group col-sm-3">
			                <label class="col-sm-12">Username:</label>
			                <div class="input-group col-sm-12">
			                	<input type='text' name='username' value='<?php echo $username; ?>' class="form-control">
			                </div>
			            </div>
			            <div class="form-group col-sm-3">
			                <label class="col-sm-12">Brokerage:</label>
			                <div class="input-group col-sm-12">
			                	<input type='text' name='password' value='' class="form-control" class="form-control">
			                </div>
			            </div>
			            <div class="form-group col-sm-2">
			                <label class="col-sm-12">First Name:</label>
			                <div class="input-group col-sm-12">
			                	<input type='text' name='firstname' value='<?php echo $firstname; ?>' class="form-control">
			                </div>
			            </div>
			            <div class="form-group col-sm-2">
			                <label class="col-sm-12">Last Name:</label>
			                <div class="input-group col-sm-12">
			                	<input type='text' name='lastname' value='<?php echo $lastname; ?>' class="form-control">
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
			                </div>
			            </div>
			            <div class="form-group col-sm-9">
			                <label class="col-sm-12">Address:</label>
			                <div class="input-group col-sm-12">
			                	<input type='text' name='address' value='<?php echo $address; ?>' class="form-control">
			                </div>
			            </div>
			        </div>
			        <div class="row">
			            <div class="form-group col-sm-3">
			                <label class="col-sm-12">City:</label>
			                <div class="input-group col-sm-12">
			                	<input type='text' name='city' value='<?php echo $city; ?>' class="form-control">
			                </div>
			            </div>
			            <div class="form-group col-sm-3">
			                <label class="col-sm-12">Province:</label>
			                <div class="input-group col-sm-12">
			                	<div id='province2_div'></div>
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
			                </div>
			            </div>
			            <div class="form-group col-sm-3">
			                <label class="col-sm-12">Licence Expire Date:</label>
			                <div class="input-group col-sm-12">
			                	<input type='date' name='licence_expire' value='<?php echo $licence_expire; ?>' class="form-control">
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
							<p><b>Pay type:</b></p>
							<?php foreach ($paytype_list as $pay) { ?>
							<div class="col-sm-4">
							<input type='checkbox' name='paytype_list[]' value='<?php echo $pay; ?>' <?php echo (strpos($pay_type, $pay) === FALSE) ? '' : 'checked'; ?>> <?php echo $pay; ?><br>
							</div>
							<?php } ?>
						</div>
						<div class="form-group col-sm-6">
							<label class="col-sm-12">Status:</label>
			                <div class="col-sm-12 input-group">
								<select name='status' class="form-control">
									<option value='1' <?php echo ($status || empty($user_id)) ? 'selected' : ''; ?>>Active</option>
									<option value='0' <?php echo (empty($status) && $user_id) ? 'selected' : ''; ?>>Disable</option>
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

					<div class="row">
		              <!-- submit button -->
		              <div class="col-sm-12">
		              	<input class="btn btn-default pull-right" type='submit' value='<?php echo ($user_id) ? "Update" : "Add"; ?>'>
		              </div> 
		              <!-- submit button -->
		            </div>
					
					<br>
					
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
					});
					</script>


 				  </div>
                </div>
              </div>
            </div>

          </div>
        </div>
        <!-- /page content -->