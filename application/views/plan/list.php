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
          <div class="main-div">
            <div class="page-title">
              <div class="title_left">
                <h3>View/Edit Policy
                	
                	<?php if($beuser['user_group_id'] != 3 && $beuser['user_group_id'] != 103){ ?>
                	<span class="btn btn-info" data-toggle="collapse" data-target="#create-div" title="Create New"><i class="fa fa-plus"></i> New</span>
	            	<?php } ?>
	            </h3>
              </div>
            </div>
            <div class="clearfix"></div>

             <!-- Create Section -->
            <div class="row collapse" id="create-div">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Create<small></small></h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <form action='<?php echo $add_url; ?>' method='POST' class="form-horizontal">
						<input type='hidden' name='<?php echo $csrf['name']; ?>' value='<?php echo $csrf['value']; ?>'>
						<div class="row">
							<div class="form-group col-sm-3">
				                <label class="col-sm-12">Product List:</label>
				                <select name='product_short' class="form-control">
									<?php foreach ($product_list_a as $key => $value) { ?>
									<option value='<?php echo $key; ?>'><?php echo $value['full_name']; ?></option>
									<?php } ?>
								</select>				                
				            </div>
							<div class="form-group col-sm-3">
								<label class="col-sm-12"> &nbsp;</label>
								<input class="btn btn-primary" type='submit' name='add' value='Create'>
							</div>
						</div>
					</form> 
                  </div>
                </div>
              </div>
            </div><!-- End Create Section -->

           <!-- Filter Section -->
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Policy Filter<small></small></h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
	
			                <?php if (!empty($error_message)) { echo $error_message . "<br>"; } ?>
							<form action='<?php echo $search_url; ?>' method='POST'  class="form-horizontal">
			    				  <input type='hidden' name='<?php echo $csrf['name']; ?>' value='<?php echo $csrf['value']; ?>'>

			    				  <div class="row">
                    				<!-- Product select box -->
			                        <div class="form-group col-sm-5 col-xs-12">
			                          <label class="col-sm-12">Our Product:</label>
			                          <select name='product_short' class="form-control">
										<option value='0'> -- select product -- </option>
										<?php foreach ($product_list as $key => $value) { ?>
										<option value='<?php echo $key; ?>' <?php echo ($key == $product_short) ? 'selected' : ''; ?>><?php echo $value['full_name']; ?></option>
										<?php } ?>
									  </select>
			                              
			                        </div>
			                        <!-- Product select box end -->
			                        <!-- Policy Number input box -->
			                        <div class="form-group col-sm-3">
			                          <label class="col-sm-12">Policy Number:</label>
			                          <div class="input-group col-sm-12">
			                              <input type="text" name='policy' value='<?php echo $policy; ?>' class="form-control"/>
			                          </div>
			                        </div>
			                        <!-- Policy Number input box end -->
			                        <div class="form-group col-sm-4">
			                        	<label class="col-sm-12">&nbsp;</label>
			                        	<input class="btn btn-primary" type='submit' name='search' value='Search'>
			                        	<input type="button" class="btn btn-info" data-toggle="collapse" data-target="#adv-search" name='search' value='More Filter'>
			                        </div>	
                    			</div>

			                      <!-- personal information search -->
			                    <div id="adv-search" <?php if ($morefilter) { ?>class="collapse" <?php } ?>>
			                      <div class="row">
			                        <!-- Last Name input box -->
			                        <div class="form-group col-sm-3">
			                          <!--label class="col-sm-4">Last Name:</label-->
			                          <div class="input-group col-sm-12">
			                              <input type="text" name="lastname" placeholder="Last Name" data-toggle="tooltip" title="Last Name" value="<?php echo $lastname; ?>" class="form-control"/>
			                          </div>
			                        </div>
			                        <!-- Last Name input box end -->
			                        <!-- First Name input box -->
			                        <div class="form-group col-sm-3">
			                          <!--label class="col-sm-4">First Name:</label-->
			                          <div class="input-group col-sm-12">
			                              <input type="text" name="firstname" placeholder="First Name" data-toggle="tooltip" title="First Name" value="<?php echo $firstname; ?>" class="form-control"/>
			                          </div>
			                        </div>
			                        <!-- First Name input box end -->
			                        <!-- Birthdate select box -->
			                        <div class="form-group col-sm-3">
			                          <!--label for="application_date_from" class="col-sm-4">Birthdate From:</label-->
			                            <div class="input-group date" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd">
			                                <input type="text" size="16" name="birthday" placeholder="Birthdate From" data-toggle="tooltip" title="Birthdate From" value="<?php echo $birthday; ?>" class="form-control">
			                                <div class="input-group-addon">
			                                    <span class="glyphicon glyphicon-calendar"></span>
			                                </div>
			                            </div>
			                            
			                        </div>
			                        <div class="form-group col-sm-3">
			                          <!--label for="application_date_from" class="col-sm-4">Birthdate to:</label-->
			                            <div class="input-group date" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd">
			                                <input type="text" size="16" name="birthday2" placeholder="Birthdate To" data-toggle="tooltip" title="Birthdate To" value="<?php echo $birthday2; ?>" class="form-control">
			                                <div class="input-group-addon">
			                                    <span class="glyphicon glyphicon-calendar"></span>
			                                </div>
			                            </div>
			                        </div>
			                        <!-- Birthdate select box end -->

			                    </div>
			                      <!-- policy information search -->
			                    <div class="row">
			                        
			                        <!-- Agent input box -->
			                        <div class="form-group col-sm-3">
			                          <!--label class="col-sm-12">Agent/School Name:</label-->
			                          <div class="input-group col-sm-12">
			                              <input type="text" name='uname' placeholder="Agent/School Name" data-toggle="tooltip" title="Agent/School Name" value='<?php echo $uname; ?>' class="form-control"/>
			                          </div>
			                        </div>
			                        <!-- Agent input box end -->
			                        <!-- Batch No. input box -->
			                        <div class="form-group col-sm-3">
			                          <!--label class="col-sm-12">Batch No.:</label-->
			                          <div class="input-group col-sm-12">
			                              <input type="text" name='batch_number' placeholder="Batch No." data-toggle="tooltip" title="Batch No." value='<?php echo $batch_number; ?>' class="form-control"/>
			                          </div>
			                        </div>
			                        <!-- Batch No. input box end -->

			                        
			                    </div>
			                      <!-- related date search -->
			                    <div class="row">
			                        <!-- Application Date -->
			                        <div class="form-group col-sm-3">
			                          <!-- Application Date from -->
			                            <!--label for="application_date_from" class="col-sm-12">Application Date From</label-->
			                            <div class="input-group date" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd">
			                                <input type="text" size="16" name='apply_date' placeholder="Application Date From" data-toggle="tooltip" title="Application Date From" value='<?php echo $apply_date; ?>' class="form-control">
			                                <div class="input-group-addon">
			                                    <span class="glyphicon glyphicon-calendar"></span>
			                                </div>
			                            </div>

			                            <input type="hidden" id="application_date_from" value="" />
			                            <!-- Application Date from End-->
			                            <!-- Application Date to -->
			                            <!--label for="application_date_to" class="col-sm-12">Application Date To</label-->
			                            <div class="input-group date" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd">
			                                <input type="text" size="16" name='apply_date2' placeholder="Application Date To" data-toggle="tooltip" title="Application Date To" value='<?php echo $apply_date2; ?>' class="form-control">
			                                <div class="input-group-addon">
			                                    <span class="glyphicon glyphicon-calendar"></span>
			                                </div>
			                            </div>
			                            
			                            <input type="hidden" id="application_date_to" value="" />
			                            <!-- Application Date to End -->
			                        </div>
			                        <!-- Application Date End-->
			                        <!-- Create Date-->
			                        <div class="form-group col-sm-3">
			                            <!-- Arrival Date From-->
			                            <!--label for="create_date_from" class="col-sm-12">Arrival Date From</label-->
			                            <div class="input-group date" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd">
			                                <input type="text" size="16" name='arrival_date' placeholder="Arrival Date From" data-toggle="tooltip" title="Arrival Date From" value='<?php echo $arrival_date; ?>' class="form-control">
			                                <div class="input-group-addon">
			                                    <span class="glyphicon glyphicon-calendar"></span>
			                                </div>
			                            </div>
			                           
			                            <input type="hidden" id="create_date_from" value="" />
			                            <!-- Arrival Date From End-->
			                            <!-- Create Date to -->
			                            <!--label for="create_date_to" class="col-sm-12">Arrival Date To</label-->
			                            <div class="input-group date" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd">
			                                <input type="text" size="16" name='arrival_date2' placeholder="Arrival Date To" data-toggle="tooltip" title="Arrival Date To" value='<?php echo $arrival_date2; ?>' class="form-control">
			                                <div class="input-group-addon">
			                                    <span class="glyphicon glyphicon-calendar"></span>
			                                </div>
			                            </div>
			                            
			                            <input type="hidden" id="create_date_to" value="" />
			                            <!-- Create Date to End -->
			                        </div>
			                        <!-- Create Date End -->
			                        <!-- Effective Date-->
			                        <div class="form-group col-sm-3">
			                            <!-- Effective Date From-->
			                            <!--label for="effective_date_from" class="col-sm-12">Effective Date From</label-->
			                            <div class="input-group date" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd">
			                                <input type="text" size="16" name='effective_date' placeholder="Effective Date From" data-toggle="tooltip" title="Effective Date From" value='<?php echo $effective_date; ?>' class="form-control">
			                                <div class="input-group-addon">
			                                    <span class="glyphicon glyphicon-calendar"></span>
			                                </div>
			                            </div>
			                            
			                            <input type="hidden" id="effective_date_from" value="" />
			                            <!-- Effective Date From End-->
			                            <!-- Effective Date to -->
			                            <!--label for="effective_date_to" class="col-sm-12">Effective Date To</label-->
			                            <div class="input-group date" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd">
			                                <input type="text" size="16" name='effective_date2' placeholder="Effective Date To" data-toggle="tooltip" title="Effective Date To" value='<?php echo $effective_date2; ?>' class="form-control">
			                                <div class="input-group-addon">
			                                    <span class="glyphicon glyphicon-calendar"></span>
			                                </div>
			                            </div>
			                            
			                            <input type="hidden" id="effective_date_to" value="" />
			                            <!-- Effective Date to End -->
			                        </div>
			                        
			                        <div class="form-group col-sm-3">
			                            
			                            <!--label for="payment_update_date_from" class="col-sm-12">Expiry Date From</label-->
			                            <div class="input-group date" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd">
			                                <input type="text" size="16" name='expiry_date' placeholder="Expiry Date From" data-toggle="tooltip" title="Expiry Date From" value='<?php echo $expiry_date; ?>' class="form-control">
			                                <div class="input-group-addon">
			                                    <span class="glyphicon glyphicon-calendar"></span>
			                                </div>
			                            </div>
			                            
			                            <input type="hidden" id="payment_update_date_from" value="" />
			                           
			                            <!--label for="payment_update_date_to" class="col-sm-12">Expiry Date To</label-->
			                            <div class="input-group date" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd">
			                                <input class="form-control" size="16" type="text" name='expiry_date2' placeholder="Expiry Date To" data-toggle="tooltip" title="Expiry Date To" value='<?php echo $expiry_date2; ?>' >
			                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
			                            </div>
			                            <input type="hidden" id="payment_update_date_to" value="" />
			                       
			                        </div>
			                        
			                    </div>
			                      
			                    <div class="row">
			                      	<!-- Policy Status select box -->
			                        <div class="form-group col-sm-3">
			                          <!--label class="col-sm-12">Policy Status:</label-->
			                          <select name='status_id' class="form-control">
										<option value='0'> -- select policy status -- </option>
										<?php foreach ($status_list as $key => $value) { ?>
										<option value='<?php echo $key; ?>' <?php echo ($key == $status_id) ? 'selected' : ''; ?>><?php echo $value['name']; ?></option>
										<?php } ?>
									  </select>
			                             
			                        </div>
			                        <!-- Policy Status select box end -->
			                        <div class="form-group col-sm-3">
			                          <!--label class="col-sm-12">Province:</label-->
			                          <div class="input-group col-sm-12">
			                              <div id='province2_div'></div>
			                          </div>
			                        </div>
			                        <div class="form-group col-sm-3">
			                          <!--label class="col-sm-12">Country:</label-->
			                          <div class="input-group col-sm-12">
			                              <div id='country2_div'></div>
			                          </div>
			                        </div>			                        
			                    </div>			                      
			                </div>
			            </form>
		                	
				  </div>
                </div>
              </div>
            </div><!-- End Filter Section -->
            <!-- Result List Section -->
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Search Result <span> <a class="btn btn-info" href='<?php echo $export_list; ?>'><i class="fa fa-share"></i> Export Xlsx</a></span></h2>
                   
                    <?php if ($export_logo_price_option) { ?>
						<div class='pull-right'>
							<span style="color:#73879C;">Export Option for JES and JFC: </span>
							<input type='checkbox' class='withlogobox' checked> With Logo  &nbsp;&nbsp;<input type='checkbox' class='withpricebox' checked> With Price 
						</div>
					<?php } ?>
					 <div class="clearfix"></div>

                  </div>
                  <div class="x_content">
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
						<?php if ($beuser['user_group_id'] < 105) { ?>
								<th>Agent</th>
						<?php } ?>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
					<?php foreach ($plan_list as $plan) { ?>
						<tr>
							<td><a style="color:#46b8da;" href='<?php echo $edit_url.$plan['plan_id']; ?>'><?php echo $plan['policy']?></a></td>
							<td><?php echo $plan['batch_number'] ? $plan['batch_number'] : ''; ?></td>
							<td><?php echo $plan['product_short']; ?></td>
							<td><?php echo $status_list[$plan['status_id']]['name']; ?></td>
							<td><?php echo $plan['effective_date']; ?></td>
							<td><?php echo $plan['firstname'] . " " . $plan['lastname']; ?></td>
					<?php if ($beuser['user_group_id'] < 105) { ?>
							<td><?php echo $plan['agent_firstname'] . " " . $plan['agent_lastname'] . " [ Agent ID: " . $plan['agent_id'] . " ] "; ?></td>
					<?php } ?>
							<td><a style="color:#46b8da;" href='<?php echo $copy_url.$plan['plan_id']; ?>'>Copy</a> | <a style="color:#46b8da;" href='<?php echo $pdf_url.$plan['plan_id']; ?>' target='_blabk'>Export PDF</a> <?php if (($plan['status_id'] == 2) || ($plan['status_id'] == 3)) { ?> | <a style="color:#46b8da;" href='<?php echo $sendpackage_url . $plan['plan_id']; ?>'>Send Package</a><?php } ?></td>
						</tr>
					<?php } ?>
					    </tbody>
					</table>
                    
                  </div>
                </div>
              </div>
            </div><!-- End Result List Section -->
           
          </div>
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
});
<?php } ?>
</script>