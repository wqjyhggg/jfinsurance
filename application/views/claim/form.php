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
                <h3>Claim</h3>
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
                      <input type='hidden' name='claim_id' value='<?php echo $claim_id; ?>'>
                      <input type='hidden' name='plan_id' value='<?php echo $plan_id; ?>'>
                      <input type='hidden' name='customer_id' value='<?php echo $customer_id; ?>'>
                      <input type='hidden' name='product_short' value='<?php echo $product_short; ?>'>
                      <input type='hidden' name='policy_number' value='<?php echo $policy_number; ?>'>
                      <div class="row">
                        <div class="form-group col-sm-3">
                          <label class="inline">Claim No.: </label>
                          <input style="width:100px;" class="inline form-control" type='text' name='claim_number' value='<?php echo $claim_number; ?>'>
                        </div>
                        <div class="form-group col-sm-3">
                          <label class="inline">Product: </label> <?php echo $product_short; ?>
                        </div>
                        <div class="form-group col-sm-3">
                          <label class="inline">Policy Number: </label> <?php echo $policy_number; ?>
                        </div>
                        <div class="form-group col-sm-3">
                          <label class="inline">Finish Claim: </label> <input style="vertical-align:top;" type='checkbox' name='done' <?php echo ($done == 1) ? 'checked' : ''; ?> >
                        </div>
                        
                      </div>
                      <div class="row">
                        <div class="form-group col-sm-3">
                          <label class="col-sm-12">Last Name: </label>
                          <div class="input-group col-sm-12">
                            <input class="form-control" type='text' name='lastname' value='<?php echo $lastname; ?>'>
                          </div>
                        </div>
                        <div class="form-group col-sm-3">
                          <label class="col-sm-12">First Name: </label>
                          <div class="input-group col-sm-12">
                            <input class="form-control" type='text' name='firstname' value='<?php echo $firstname; ?>'>
                          </div>
                        </div>
                        <div class="form-group col-sm-3">
                            <label class="col-sm-12">Birthdate From:</label>
                            <div class="input-group date form_date col-sm-12" data-date="" data-date-format="yyyy/mm/dd" data-link-field="birthday" data-link-format="yyyy/mm/dd">
                                <input class="form-control" size="16" type="text" name="birthday" placeholder="Birthdate From" value="<?php echo $birthday; ?>" readonly>
                                <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div>
                            <input type="hidden" id="application_date_from" value="" />
                        </div>
                        
                        <div class="form-group col-sm-3">
                          <label  class="col-sm-12">Gender: </label>
                            <select name='gender' class="form-control">
                              <option value='M' <?php echo (empty($gender) || ($gender != 'F')) ? "selected" : ""; ?>>Male</option>
                              <option value='F' <?php echo (!empty($gender) && ($gender == 'F')) ? "selected" : ""; ?>>Female</option>
                            </select>
                        </div>
                      </div>
                      <div class="row">
                        <div class="form-group col-sm-3">
                          <label  class="col-sm-12">Claim Date: </label>
                          <div class="input-group col-sm-12">
                            <input class="form-control" value="<?php echo $claim_date; ?>" disabled/>
                          </div>
                        </div>
                        <div class="form-group col-sm-3">
                          <label  class="col-sm-12">Claimed: </label>
                          <div class="input-group col-sm-12">
                            <input class="form-control" type='number' name='claimed' value='<?php echo $claimed; ?>'>
                          </div>
                        </div>
                        <div class="form-group col-sm-3">
                          <label class="col-sm-12">Paid: </label>
                          <div class="input-group col-sm-12">
                            <input class="form-control" type='number' name='paid' value='<?php echo $paid; ?>'>
                          </div>
                        </div>
                        <div class="form-group col-sm-3">
                          <label  class="col-sm-12">Pay To: </label>
                          <div class="input-group col-sm-12">
                            <input class="form-control" type='text' name='pay_to' value='<?php echo $pay_to; ?>'>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="form-group col-sm-3">
                          <label  class="col-sm-12">Cheque Number: </label>
                          <div class="input-group col-sm-12">
                            <input class="form-control" type='text' name='cheque_number' value='<?php echo $cheque_number; ?>'>
                          </div>
                        </div>
                      
                        <div class="form-group col-sm-3">
                          <label  class="col-sm-12">Coverage Code: </label>
                            <select name='coverage_code_id' class="form-control">
                            <option value='' <?php echo (empty($coverage_code_id)) ? "selected" : ""; ?>> -- select code --</option>
                            <?php foreach ($coverage_codes as $ccode) { ?>
                            <option value='<?php echo $ccode['coverage_code_id']?>' <?php echo ($coverage_code_id == $ccode['coverage_code_id']) ? "selected" : ""; ?>><?php echo $ccode['name']; ?></option>
                            <?php } ?>
                            </select>
                          
                        </div>
                        <div class="form-group col-sm-3">
                            <label class="col-sm-12">Service Date: </label>
                            <div class="input-group date form_date col-sm-12" data-date="" data-date-format="yyyy/mm/dd" data-link-field="birthday" data-link-format="yyyy/mm/dd">
                                <input class="form-control" size="16" type="text" name='service_date' placeholder="Birthdate From" value='<?php echo $service_date; ?>' readonly>
                                <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div>
                            <input type="hidden" id="application_date_from" value="" />
                        </div>
                        
                      </div>
                      <div class="row">
                        <div class="form-group col-sm-12">
                          <label class="col-sm-12">Diagnosis: </label>
                          <div class="input-group col-sm-12">
                            <textarea class="form-control" rows="3" name='diagnosis'><?php echo $diagnosis; ?></textarea>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="form-group col-sm-12">
                          <label class="col-sm-12">Memo: </label>
                          <div class="input-group col-sm-12">  
                            <textarea class="form-control" rows="3" name='memo'><?php echo $memo; ?></textarea>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="form-group col-sm-12">
                          <label>Decline Reason: </label>
                          <div class="input-group col-sm-12">
                            <textarea class="form-control" rows="3" name='decline_reason'><?php echo $decline_reason; ?></textarea>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="form-group col-sm-12 text-right">
                          <input class="btn btn-primary" type='submit' name='submit' value='Update'>
                        </div>
                      </div>

                    </form>

                  </div>
                </div>
              </div>
            </div><!-- End Filter Section -->
        </div>
        <!-- /page content -->
        