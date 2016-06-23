<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!-- Plan List page content -->

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
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3>View/Edit Policy</h3>
              </div>
            </div>
            <div class="clearfix"></div>
           <!-- Filter Section -->
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Policy Filter<small></small></h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <br />

		                    <?php if (!empty($error_message)) { echo $error_message . "<br>"; } ?>
							<form action='<?php echo $search_url; ?>' method='POST'  class="form-horizontal">
		    				  <input type='hidden' name='<?php echo $csrf['name']; ?>' value='<?php echo $csrf['value']; ?>'>
		                      <!-- personal information search -->
		                      <div class="row">
		                        <!-- Last Name input box -->
		                        <div class="form-group col-sm-3">
		                          <label class="col-sm-12">Last Name:</label>
		                          <div class="input-group col-sm-12">
		                              <input type="text" name="lastname" value="<?php echo $lastname; ?>" class="form-control"/>
		                          </div>
		                        </div>
		                        <!-- Last Name input box end -->
		                        <!-- First Name input box -->
		                        <div class="form-group col-sm-3">
		                          <label class="col-sm-12">First Name:</label>
		                          <div class="input-group col-sm-12">
		                              <input type="text" name="firstname" value="<?php echo $firstname; ?>" class="form-control"/>
		                          </div>
		                        </div>
		                        <!-- First Name input box end -->
		                        <!-- Birthdate select box -->
		                        <div class="form-group col-sm-3">
		                          <label for="application_date_from" class="col-sm-12">Birthdate From:</label>
		                            <div class="input-group date form_date col-sm-12" data-date="" data-date-format="yyyy/mm/dd" data-link-field="application_date_from" data-link-format="yyyy-mm-dd">
		                                <input class="form-control" size="16" type="text" name="birthday" value="<?php echo $birthday; ?>" readonly>
		                                <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
		                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
		                            </div>
		                            <input type="hidden" id="application_date_from" value="" />
		                        </div>
		                        <div class="form-group col-sm-3">
		                          <label for="application_date_from" class="col-sm-12">Birthdate to:</label>
		                            <div class="input-group date form_date col-sm-12" data-date="" data-date-format="yyyy/mm/dd" data-link-field="application_date_from" data-link-format="yyyy-mm-dd">
		                                <input class="form-control" size="16" type="text" name="birthday2" value="<?php echo $birthday2; ?>" readonly>
		                                <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
		                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
		                            </div>
		                            <input type="hidden" id="application_date_from" value="" />
		                        </div>
		                        <!-- Birthdate select box end -->

		                      </div>
		                      <!-- policy information search -->
		                      <div class="row">
		                        <!-- Policy Number input box -->
		                        <div class="form-group col-sm-3">
		                          <label class="col-sm-12">Policy Number:</label>
		                          <div class="input-group col-sm-12">
		                              <input type="text" name='policy' value='<?php echo $policy; ?>' class="form-control"/>
		                          </div>
		                        </div>
		                        <!-- Policy Number input box end -->
		                        <!-- Agent input box -->
		                        <div class="form-group col-sm-3">
		                          <label class="col-sm-12">Agent/School Name:</label>
		                          <div class="input-group col-sm-12">
		                              <input type="text" name='uname' value='<?php echo $uname; ?>' class="form-control"/>
		                          </div>
		                        </div>
		                        <!-- Agent input box end -->
		                        <!-- Batch No. input box -->
		                        <div class="form-group col-sm-3">
		                          <label class="col-sm-12">Batch No.:</label>
		                          <div class="input-group col-sm-12">
		                              <input type="text" name='batch_number' value='<?php echo $batch_number; ?>' class="form-control"/>
		                          </div>
		                        </div>
		                        <!-- Batch No. input box end -->

		                        <!-- Product select box -->
		                        <div class="form-group col-sm-3">
		                          <label class="col-sm-12">Our Product:</label>
		                          <select name='product_short' class="form-control">
									<option value='0'> -- select product -- </option>
									<?php foreach ($product_list as $key => $value) { ?>
									<option value='<?php echo $key; ?>' <?php echo ($key == $product_short) ? 'selected' : ''; ?>><?php echo $value['full_name']; ?></option>
									<?php } ?>
								  </select>
		                              
		                        </div>
		                        <!-- Product select box end -->
		                      </div>
		                      <!-- related date search -->
		                      <div class="row">
		                        <!-- Application Date -->
		                        <div class="form-group col-sm-3">
		                          <!-- Application Date from -->
		                            <label for="application_date_from" class="col-sm-12">Application Date From</label>
		                            <div class="input-group date form_date col-sm-12" data-date="" data-date-format="yyyy/mm/dd" data-link-field="application_date_from" data-link-format="yyyy-mm-dd">
		                                <input class="form-control" size="16" type="text" name='apply_date' value='<?php echo $apply_date; ?>' readonly>
		                                <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
		                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
		                            </div>


		                            <input type="hidden" id="application_date_from" value="" />
		                            <!-- Application Date from End-->
		                            <!-- Application Date to -->
		                            <label for="application_date_to" class="col-sm-12">Application Date To</label>
		                            <div class="input-group date form_date col-sm-12" data-date="" data-date-format="yyyy/mm/dd" data-link-field="application_date_to" data-link-format="yyyy-mm-dd">
		                                <input class="form-control" size="16" type="text" name='apply_date2' value='<?php echo $apply_date2; ?>' readonly>
		                                <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
		                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
		                            </div>
		                            <input type="hidden" id="application_date_to" value="" />
		                            <!-- Application Date to End -->
		                        </div>
		                        <!-- Application Date End-->
		                        <!-- Create Date-->
		                        <div class="form-group col-sm-3">
		                            <!-- Create Date From-->
		                            <label for="create_date_from" class="col-sm-12">Arrival Date From</label>
		                            <div class="input-group date form_date col-sm-12" data-date="" data-date-format="yyyy/mm/dd" data-link-field="create_date_from" data-link-format="yyyy-mm-dd">
		                                <input class="form-control" size="16" type="text" name='arrival_date' value='<?php echo $arrival_date; ?>' readonly>
		                                <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
		                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
		                            </div>
		                            <input type="hidden" id="create_date_from" value="" />
		                            <!-- Create Date From End-->
		                            <!-- Create Date to -->
		                            <label for="create_date_to" class="col-sm-12">Arrival Date To</label>
		                            <div class="input-group date form_date col-sm-12" data-date="" data-date-format="yyyy/mm/dd" data-link-field="create_date_to" data-link-format="yyyy-mm-dd">
		                                <input class="form-control" size="16" type="text" name='arrival_date2' value='<?php echo $arrival_date2; ?>' readonly>
		                                <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
		                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
		                            </div>
		                            <input type="hidden" id="create_date_to" value="" />
		                            <!-- Create Date to End -->
		                        </div>
		                        <!-- Create Date End -->
		                        <!-- Effective Date-->
		                        <div class="form-group col-sm-3">
		                            <!-- Effective Date From-->
		                            <label for="effective_date_from" class="col-sm-12">Effective Date From</label>
		                            <div class="input-group date form_date col-sm-12" data-date="" data-date-format="yyyy/mm/dd" data-link-field="effective_date_from" data-link-format="yyyy-mm-dd">
		                                <input class="form-control" size="16" type="text" name='effective_date' value='<?php echo $effective_date; ?>' readonly>
		                                <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
		                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
		                            </div>
		                            <input type="hidden" id="effective_date_from" value="" />
		                            <!-- Effective Date From End-->
		                            <!-- Effective Date to -->
		                            <label for="effective_date_to" class="col-sm-12">Effective Date To</label>
		                            <div class="input-group date form_date col-sm-12" data-date="" data-date-format="yyyy/mm/dd" data-link-field="effective_date_to" data-link-format="yyyy-mm-dd">
		                                <input class="form-control" size="16" type="text" name='effective_date2' value='<?php echo $effective_date2; ?>' readonly>
		                                <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
		                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
		                            </div>
		                            <input type="hidden" id="effective_date_to" value="" />
		                            <!-- Effective Date to End -->
		                        </div>
		                        
		                        <div class="form-group col-sm-3">
		                            
		                            <label for="payment_update_date_from" class="col-sm-12">Expiry Date From</label>
		                            <div class="input-group date form_date col-sm-12" data-date="" data-date-format="yyyy/mm/dd" data-link-field="payment_update_date_from" data-link-format="yyyy-mm-dd">
		                                <input class="form-control" size="16" type="text" name='expiry_date' value='<?php echo $expiry_date; ?>' readonly>
		                                <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
		                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
		                            </div>
		                            <input type="hidden" id="payment_update_date_from" value="" />
		                           
		                            <label for="payment_update_date_to" class="col-sm-12">Expiry Date To</label>
		                            <div class="input-group date form_date col-sm-12" data-date="" data-date-format="yyyy/mm/dd" data-link-field="payment_update_date_to" data-link-format="yyyy-mm-dd">
		                                <input class="form-control" size="16" type="text" name='expiry_date2' value='<?php echo $expiry_date2; ?>' readonly>
		                                <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
		                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
		                            </div>
		                            <input type="hidden" id="payment_update_date_to" value="" />
		                       
		                        </div>
		                        
		                      </div>
		                      <div class="row">
		                      	<!-- Policy Status select box -->
		                        <div class="form-group col-sm-3">
		                          <label class="col-sm-12">Policy Status:</label>
		                          <select name='status_id' class="form-control">
									<option value='0'> -- select policy status -- </option>
									<?php foreach ($status_list as $key => $value) { ?>
									<option value='<?php echo $key; ?>' <?php echo ($key == $status_id) ? 'selected' : ''; ?>><?php echo $value['name']; ?></option>
									<?php } ?>
								  </select>
		                             
		                        </div>
		                        <!-- Policy Status select box end -->
		                        <div class="form-group col-sm-3">
		                          <label class="col-sm-12">Province:</label>
		                          <div class="input-group col-sm-12">
		                              <div class="form-control" id='province2_div'></div>
		                          </div>
		                        </div>
		                        <div class="form-group col-sm-3">
		                          <label class="col-sm-12">Province:</label>
		                          <div class="input-group col-sm-12">
		                              <div class="form-control" id='country2_div'></div>
		                          </div>
		                        </div>
		                        
		                      </div>
		                      <!-- submit button -->
		                      <div class="row">
		                        <!-- submit button -->
		                          <div class="col-sm-12">
		                          	<input class="btn btn-default pull-right" type='submit' name='search' value='Start Search'>
		                    
		                          </div> 
		                        <!-- submit button -->
		                      </div>
		                    </form>

				</div>
                </div>
              </div>
            </div><!-- End Filter Section -->
            <!-- Create Section -->
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Create<small></small></h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <br />
                    <form action='<?php echo $add_url; ?>' method='POST' class="form-horizontal">

					<input type='hidden' name='<?php echo $csrf['name']; ?>' value='<?php echo $csrf['value']; ?>'>

					<div class="form-group col-sm-3">
		                <label class="col-sm-12">Product List:</label>
		                <select name='product_short' class="form-control">
							<?php foreach ($product_list as $key => $value) { ?>
							<option value='<?php echo $key; ?>'><?php echo $value['full_name']; ?></option>
							<?php } ?>
						</select>
		                
		            </div>

					<div class="form-group col-sm-3">
						<label class="col-sm-12"> &nbsp;</label>
						<input class="btn btn-default" type='submit' name='add' value='Create'>
					</div>
					</form> 
                  </div>
                </div>
              </div>
            </div><!-- End Create Section -->
            <!-- List Section -->
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Search Result<small></small></h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <br />
                     <div class="table-responsive">
                      <table class="table table-hover table-bordered">
                      	<thead>
							<tr>
								<th>Policy No.</th>
								<th>Batch No.</th>
								<th>Name</th>
								<th>Status</th>
								<th>Effect Date</th>
								<th>User</th>
						<?php if ($beuser['user_group_id'] > 5) { ?>
								<th>Agent</th>
						<?php } ?>
								<th>&nbsp</th>
							</tr>
						</thead>
						<tbody>
					<?php foreach ($plan_list as $plan) { ?>
						<tr>
							<td><a href='<?php echo $edit_url.$plan['plan_id']; ?>'><?php echo $plan['policy']?></a></td>
							<td><?php echo $plan['batch_number']; ?></td>
							<td><?php echo $plan['full_name']; ?></td>
							<td><?php echo $status_list[$plan['status_id']]; ?></td>
							<td><?php echo $plan['effective_date']; ?></td>
							<td><?php echo $plan['firstname'] . " " . $plan['lastname']; ?></td>
					<?php if ($beuser['user_group_id'] > 5) { ?>
							<td><?php echo $plan['agent_firstname'] . " " . $plan['agent_lastname']; ?></td>
					<?php } ?>
							<td><a href='<?php echo $copy_url.$plan['plan_id']; ?>'>Copy</a></td>
						</tr>
					<?php } ?>
					    </tbody>
					</table>
                    
                  </div>
                </div>
              </div>
            </div><!-- End List Section -->

          </div>
        </div>
        <!-- /page content -->




<script>
$( document ).ready(function() {
	$.ajax({
		url: '<?php echo $province_url; ?>',
		type: 'GET',
		data: {neednull:1},
		success: function(data, textStatus, jqXHR) {
        	$('#province2_div').html(data);
    	},
	});
	$.ajax({
		url: '<?php echo $country_url; ?>',
		type: 'GET',
		data: {neednull:1},
		success: function(data, textStatus, jqXHR) {
        	$('#country2_div').html(data);
    	},
	});
});
</script>

