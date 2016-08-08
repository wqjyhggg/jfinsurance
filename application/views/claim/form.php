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
                <h3>Claim Detail</h3>
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
                        <div class="form-group col-sm-2">
                          <label class="inline">Claim No.: </label>
                          <input style="width:100px;" class="inline form-control" type='text' name='claim_number' value='<?php echo $claim_number; ?>'>
                        </div>
                        <div class="form-group col-sm-2">
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
                        <div class="form-group col-sm-2">
                          <label >Claim Date: </label>
                          <div class="input-group col-sm-12">
                            <input class="form-control" value="<?php echo $claim_date; ?>" disabled/>
                          </div>
                        </div>
                        <div class="form-group col-sm-2">
                          <label>Last Name: </label>
                          <div class="input-group col-sm-12">
                            <input class="form-control" type='text' name='lastname' value='<?php echo $lastname; ?>'>
                          </div>
                        </div>
                        <div class="form-group col-sm-2">
                          <label>First Name: </label>
                          <div class="input-group col-sm-12">
                            <input class="form-control" type='text' name='firstname' value='<?php echo $firstname; ?>'>
                          </div>
                        </div>
                        <div class="form-group col-sm-2">
                            <label>Birthday:</label>
                            <div class="input-group date" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd">
                                <input class="form-control" size="16" type="text" name="birthday" placeholder="Birthdate From" value="<?php echo $birthday; ?>" >
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div>
                            <input type="hidden" id="application_date_from" value="" />
                        </div>
                        <div class="form-group col-sm-2">
                          <label>Gender: </label>
                            <select name='gender' class="form-control">
                              <option value='M' <?php echo (empty($gender) || ($gender != 'F')) ? "selected" : ""; ?>>Male</option>
                              <option value='F' <?php echo (!empty($gender) && ($gender == 'F')) ? "selected" : ""; ?>>Female</option>
                            </select>
                        </div>
                      </div>
                      <div class="row">
                        <div class="form-group col-sm-12">
                          <label>Note: </label>
                          <div class="input-group col-sm-12">  
                            <textarea class="form-control" rows="3" name='note'><?php echo $note; ?></textarea>
                          </div>
                        </div>
                      </div><br />
                      <div class="row">
                        <div class="form-group col-sm-12 text-right">
                          <input class="btn btn-primary" type='submit' name='submit' value='<?php echo $button; ?>'>
                        </div>
                      </div>

                    </form>

                  </div>
                </div>
              </div>
            </div><!-- End Filter Section -->
        </div>
        <!-- /page content -->
        