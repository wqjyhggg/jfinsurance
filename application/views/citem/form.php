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
        <div class="right_col" role="main">
          
            <div class="page-title">
              <div class="title_left">
                <h3>Claim Item Detail <!--a target="_blank" class="btn btn-info" href='<?php echo $letter_url; ?>' >Letter</a--> </h3>
              </div>
				
            </div>
            <div class="clearfix"></div>
           <!-- Filter Section -->
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_content">
                   
                    <form method="post" class="form-horizontal" action='<?php echo $edit_url; ?>'>
                      <input type='hidden' name='<?php echo $csrf['name']; ?>' value='<?php echo $csrf['value']; ?>'>
                      <input type='hidden' name='citem_id' value='<?php echo $citem_id; ?>'>
                      <input type='hidden' name='claim_id' value='<?php echo $claim_id; ?>'>
                      <input type='hidden' name='plan_id' value='<?php echo $plan_id; ?>'>
                      <input type='hidden' name='customer_id' value='<?php echo $customer_id; ?>'>
                      <input type='hidden' name='product_short' value='<?php echo $product_short; ?>'>
                      <input type='hidden' name='policy_number' value='<?php echo $policy_number; ?>'>
                      <input type='hidden' name='claim_number' value='<?php echo $claim_number; ?>'>
                      <input type="hidden" name='claim_date' value='<?php echo $claim_date; ?>'>
                      <input type="hidden" name='lastname' value='<?php echo $lastname; ?>'>
                      <input type="hidden" name='firstname' value='<?php echo $firstname; ?>'>
                      <input type="hidden" name='birthday' value='<?php echo $birthday; ?>'>
                      <input type="hidden" name='gender' value='<?php echo $gender; ?>'>
                      <div class="row">
                        <div class="form-group col-sm-3">
                          <label class="inline">Claim No.: </label> <?php echo $claim_number; ?>
                        </div>
                        <div class="form-group col-sm-3">
                          <label class="inline">Product: </label> <?php echo $product_short; ?>
                        </div>
                        <div class="form-group col-sm-3">
                          <label class="inline">Policy Number: </label> <?php echo $policy_number; ?>
                        </div>
                        <div class="form-group col-sm-3">
                          <label class="inline">Claim Date: </label> <?php echo $claim_date; ?>
                        </div>
                      </div>
                      <div class="row">
                        <div class="form-group col-sm-3">
                          <label class="inline">Last Name: </label> <?php echo $lastname; ?>
                        </div>
                        <div class="form-group col-sm-3">
                          <label class="inline">First Name: </label> <?php echo $firstname; ?>
                        </div>
                        <div class="form-group col-sm-3">
                            <label class="inline">Birthdate From: </label> <?php echo $birthday; ?>
                        </div>
                        <div class="form-group col-sm-3">
                          <label  class="inline">Gender: </label> <?php echo (empty($gender) || ($gender != 'F')) ? "Male" : "Female"; ?>
                        </div>
                      </div>
                      <div class="row">
                        <div class="form-group col-sm-3">
                            <label>Service Date: </label>
                            <div class="input-group date" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd">
                                <input class="form-control" size="16" type="text" name='service_date' placeholder="Service Date" value='<?php echo $service_date; ?>' >
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div>
                            <?php if (isset($error_service_date)) { ?>
		 					<div class="alert-error">
								<?php echo $error_service_date;?>
							</div>
                          	<?php } ?>
                        </div>
                        <div class="form-group col-sm-3">
                          <label >Claim Amount: </label>
                          <div class="input-group col-sm-12">
                            <input class="form-control" type='number' step="0.01" name='claimed' value='<?php echo $claimed; ?>'>
                          </div>
                        </div>
                        <div class="form-group col-sm-3">
                          <label>Paid: </label>
                          <div class="input-group col-sm-12">
                            <input class="form-control" type='number' step="0.01" name='paid' value='<?php echo $paid; ?>'>
                          </div>
                            <?php if (isset($error_paid)) { ?>
		 					<div class="alert-error">
								<?php echo $error_paid;?>
							</div>
                          	<?php } ?>
                        </div>
                        <div class="form-group col-sm-3">
                          <label >Pay To: </label>
                          <div class="input-group col-sm-12">
                            <input class="form-control" type='text' name='pay_to' value='<?php echo $pay_to; ?>'>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="form-group col-sm-3">
                            <label>Paid Date: </label>
                            <div class="input-group date" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd">
                                <input class="form-control" size="16" type="text" name='paid_date' placeholder="Paid Date" value='<?php echo $paid_date; ?>' >
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div>
                            <?php if (isset($error_paid_date)) { ?>
		 					<div class="alert-error">
								<?php echo $error_paid_date;?>
							</div>
                          	<?php } ?>
                        </div>
                        <div class="form-group col-sm-3">
                          <label >Coverage Code: </label>
                            <select name='coverage_code_id' class="form-control">
                            <option value='' <?php echo (empty($coverage_code_id)) ? "selected" : ""; ?>> -- select code --</option>
                            <?php foreach ($coverage_codes as $ccode) { ?>
                            <option value='<?php echo $ccode['coverage_code_id']?>' <?php echo ($coverage_code_id == $ccode['coverage_code_id']) ? "selected" : ""; ?>><?php echo $ccode['name']; ?></option>
                            <?php } ?>
                            </select>
                        </div>
                        <div class="form-group col-sm-3">
                          <label >Address: </label>
                          <div class="input-group col-sm-12">
                            <input class="form-control" type='text' name='address' value='<?php echo $address; ?>'>
                          </div>
                        </div>
                        <div class="form-group col-sm-3">
                          <label >City: </label>
                          <div class="input-group col-sm-12">
                            <input class="form-control" type='text' name='city' value='<?php echo $city; ?>'>
                          </div>
                        </div>
 			            <div class="form-group col-sm-3">
			                <label>Province:</label>
			                <div class="input-group col-sm-12">
			                	<div id='province2_div'></div>
			                </div>
			            </div>
			            <div class="form-group col-sm-3">
			                <label>Country:</label>
			                <div class="input-group col-sm-12">
			                	<div id='country2_div'></div>
			                </div>
			            </div>
			            <div class="form-group col-sm-3">
			                <label>Post Code:</label>
			                <div class="col-sm-12 input-group">
				                <input type='text' name='postcode' value='<?php echo $postcode; ?>' class="form-control">
							</div>
			            </div>
                        <div class="form-group col-sm-3">
                            <label>EOB Date: </label>
                            <div class="input-group date" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd">
                                <input class="form-control" size="16" type="text" name='eob_date' placeholder="EOB Date" value='<?php echo $eob_date; ?>' >
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div>
                        </div>
                      
                        <div class="form-group col-sm-3">
                          <label >Cheque Number: </label>
                          <div class="input-group col-sm-12">
                            <input class="form-control" type='text' name='cheque_number' value='<?php echo $cheque_number; ?>'>
                          </div>
                        </div>
                      
                        <div class="form-group col-sm-3">
                          <label >Amount Received: </label>
                          <div class="input-group col-sm-12">
                            <input class="form-control" type='number' step="0.01" name='received' value='<?php echo $received; ?>'>
                          </div>
                        </div>
                        <div class="form-group col-sm-3">
                            <label>Cheque Cashed Date: </label>
                            <div class="input-group date" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd">
                                <input class="form-control" size="16" type="text" name='cashed_date' placeholder="Cheque Cashed Date" value='<?php echo $cashed_date; ?>' >
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div>
                            <?php if (isset($error_cashed_date)) { ?>
		 					<div class="alert-error">
								<?php echo $error_cashed_date;?>
							</div>
                          	<?php } ?>
                        </div>
                        <div class="form-group col-sm-3">
                          <label >EOB Cheque No: </label>
                          <div class="input-group col-sm-12">
                            <input class="form-control" type='text' name='eob_cheque_no' value='<?php echo $eob_cheque_no; ?>'>
                          </div>
                        </div>
                        
                      </div>
                      <div class="row">
                        <div class="form-group col-sm-3">
                          <label >Invoice Number: </label>
                          <div class="input-group col-sm-12">
                            <input class="form-control" type='text' name='invoice_number' value='<?php echo $invoice_number; ?>'>
                          </div>
                        </div>
                        
                      </div>
                      <div class="row">
                        <div class="form-group col-sm-12">
                          <label>Diagnosis: </label>
                          <div class="input-group col-sm-12">
                            <textarea class="form-control" rows="3" name='diagnosis'><?php echo $diagnosis; ?></textarea>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="form-group col-sm-12">
                          <label>Internal Note: </label>
                          <?php if (!empty($internal_note_pre)) { ?>
                          <div class="input-group col-sm-12">  
                            <?php echo str_replace("\n", "<br />", $internal_note_pre); ?>
                          </div>
                          <?php } ?>
                          <div class="input-group col-sm-12">  
                            <textarea class="form-control" rows="3" name='internal_note'><?php echo $internal_note; ?></textarea>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="form-group col-sm-12">
                          <label>External Note: </label>
                          <div class="input-group col-sm-12">  
                            <textarea class="form-control" rows="3" name='external_note'><?php echo $external_note; ?></textarea>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="form-group col-sm-12 text-right">
                          <input class="btn btn-primary" type='submit' name='submit' value="<?php echo $button_text;?>">
                        </div>
                      </div>

                    </form>

                  </div>
                </div>
              </div>
            </div><!-- End Filter Section -->
        </div>
        <!-- /page content -->
<script>
$( document ).ready(function() {
	$.ajax({
		url: '<?php echo $country_url; ?>',
		type: 'GET',
		success: function(data, textStatus, jqXHR) {
        	$('#country2_div').html(data);
			$.ajax({
				url: '<?php echo $province_url; ?>',
				type: 'GET',
				success: function(data, textStatus, jqXHR) {
		        	$('#province2_div').html(data);
		    	},
			});
    	},
	});
});
</script>
