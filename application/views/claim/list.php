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
        <?php if (isset($customer)) { ?>
           <!-- Policy Info Section -->
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Policy Info<small></small></h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                   
                    <form method="post" class="form-horizontal" action='<?php echo $add_url; ?>'>
                        <input type='hidden' name='<?php echo $csrf['name']; ?>' value='<?php echo $csrf['value']; ?>'>
                    	<input type="hidden" name="customer_id" value='<?php echo $customer['customer_id']; ?>'>
                      <!-- personal information search -->
                      <div class="row">
                        <!-- Last First Name input box -->
                        <div class="form-group col-sm-3">
                          <label class="col-sm-12">Policy No.:</label>
                          <div class="input-group col-sm-12">
                              <input type="text" name="policyNo" value="<?php echo $plan['policy']; ?>" class="form-control" disabled />
                          </div>
                        </div>
                        <!-- Last First Name input box end -->
                        
                        <div class="form-group col-sm-3">
                          <label class="col-sm-12">Arrival Date:</label>
                          <div class="input-group col-sm-12">
                              <input type="text" name="arrival_date" value="<?php echo $plan['arrival_date']; ?>" class="form-control" disabled />
                          </div>
                        </div>
                        
                        <div class="form-group col-sm-3">
                          <label class="col-sm-12">Effective Date:</label>
                          <div class="input-group col-sm-12">
                              <input type="text" name="effective_date" value="<?php echo $plan['effective_date']; ?>" class="form-control" disabled />
                          </div>
                        </div>
                        
                        <div class="form-group col-sm-3">
                          <label class="col-sm-12">Expiry Date:</label>
                          <div class="input-group col-sm-12">
                              <input type="text" name="expiry_date" value="<?php echo $plan['expiry_date']; ?>" class="form-control" disabled />
                          </div>
                        </div>
                        
                      </div>
                      <div class="row">
                        <div class="form-group col-sm-3">
                          <label class="col-sm-12">Beneficiary:</label>
                          <div class="input-group col-sm-12">
                              <input type="text" name="beneficiary" value="<?php echo $plan['beneficiary']; ?>" class="form-control" disabled />
                          </div>
                        </div>
                        <div class="form-group col-sm-3">
                          <label class="col-sm-12">Sum Insured:</label>
                          <div class="input-group col-sm-12">
                              <input type="text" name="sum_insured" value="<?php echo $plan['sum_insured']; ?>" class="form-control" disabled />
                          </div>
                        </div>
                        <div class="form-group col-sm-3">
                          <label class="col-sm-12">Deductible:</label>
                          <div class="input-group col-sm-12">
                              <input type="text" name="deductible" value="<?php echo $plan['deductible_amount']; ?>" class="form-control" disabled />
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <!-- Last First Name input box -->
                        <div class="form-group col-sm-3">
                          <label class="col-sm-12">Last First Name:</label>
                          <div class="input-group col-sm-12">
                              <input type="text" name="lastname" value='<?php echo $customer['lastname'] . ', ' . $customer['firstname']; ?>' class="form-control" disabled />
                          </div>
                        </div>
                        <!-- Last First Name input box end -->
                        
                        <div class="form-group col-sm-3">
                          <label class="col-sm-12">Birthday:</label>
                          <div class="input-group col-sm-12">
                              <input type="text" name="birthday" value='<?php echo $customer['birthday']; ?>' class="form-control" disabled />
                          </div>
                        </div>
                        
                        <div class="form-group col-sm-3">
                          <label class="col-sm-12">Gender:</label>
                          <div class="input-group col-sm-12">
                              <input type="text" name="gender" value='<?php echo $customer['gender']; ?>' class="form-control" disabled />
                          </div>
                        </div>
                      </div>
                      <div class="row">  
                        <div class="form-group col-sm-12">
                          <label class="col-sm-12">Address:</label>
                          <div class="input-group col-sm-12">
                              <input type="text" name="full_address" value="<?php if(!empty($plan['suite_number'])){echo  $plan['suite_number'] . "- ";} ?><?php echo $plan['street_number'] . ' ' . $plan['street_name'] . ' ' . $plan['city'] . ', ' . $plan['province2'] . ', ' . $plan['postcode']; ?>" class="form-control" disabled />
                          </div>
                        </div>
                        
                      </div><br />
                      <!-- submit button -->
                      <div class="row">
                        <!-- submit button -->
                          <div class="col-sm-12">
                            <button class="btn btn-primary pull-right">Add New Claim</button>
                          </div> 
                        <!-- submit button -->
                      </div>
                    </form>

                  </div>
                </div>
              </div>
            </div>
        <?php } ?>
           <!-- Filter Section -->
          <?php if (isset($customer)) { ?>
            <div class="row" style="display:none;">
          <?php }else{ ?>
            <div class="row">
          <?php } ?>      
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Claim Search<small>Please enter the Search Criteria</small></h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                   
                    <form method="get" class="form-horizontal" action='<?php echo $action_url; ?>'>
                      <!-- personal information search -->
                      <div class="row">
                        <!-- Last Name input box -->
                        <div class="form-group col-sm-3">
                          <label class="col-sm-12">Last Name:</label>
                          <div class="input-group col-sm-12">
                              <input type="text" name="lastname" value='<?php echo $lastname; ?>' class="form-control"/>
                          </div>
                        </div>
                        <!-- Last Name input box end -->
                        <!-- First Name input box -->
                        <div class="form-group col-sm-3">
                          <label class="col-sm-12">First Name:</label>
                          <div class="input-group col-sm-12">
                              <input type="text" name="firstname" value='<?php echo $firstname; ?>' class="form-control"/>
                          </div>
                        </div>
                        <!-- First Name input box end -->
                        
                        <!-- Policy Number input box -->
                        <div class="form-group col-sm-3">
                          <label class="col-sm-12">Policy Number:</label>
                          <div class="input-group col-sm-12">
                              <input type="text" name="policy_number" value='<?php echo $policy_number; ?>' class="form-control"/>
                          </div>
                        </div>
                        <!-- Policy Number input box end -->
                        <!-- Claim Number input box -->
                        <div class="form-group col-sm-3">
                          <label class="col-sm-12">Claim Number:</label>
                          <div class="input-group col-sm-12">
                              <input type="text" name="claim_number" value='<?php echo $claim_number; ?>' class="form-control"/>
                          </div>
                        </div>
                        <!-- Claim Number input box end -->
                        
                      </div>
                      <!-- policy information search -->
                      <div class="row">

                        <!-- Product select box -->
                        <div class="form-group col-sm-3">
                          <label class="col-sm-12">Our Product:</label>
                              <select class="form-control" name='product_short'>
                                <option value=''>Choose option</option>
                                <?php foreach ($products as $p) {?>
								<option value='<?php echo $p['product_short']; ?>' <?php echo ($p['product_short'] == $product_short) ? "selected" : ""; ?>><?php echo $p['full_name']; ?></option>
                                <?php } ?>
                              </select>
                        </div>
                        <!-- Product select box end -->
                      
                        <!-- Claim Date -->
                        <div class="form-group col-sm-3">
                          <!-- Claim Date from -->
                            <label for="claim_date_from" class="col-sm-12">Claim Date From</label>
                            <div class="input-group date" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd">
                                <input class="form-control" size="16" type="text" name='claim_date' value="<?php echo $claim_date; ?>" >
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div>
                            <!-- Claim Date from End-->
                        </div>    
                        <div class="form-group col-sm-3">    
                            <!-- Claim Date to -->
                            <label for="claim_date_to" class="col-sm-12">Claim Date To</label>
                            <div class="input-group date" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd">
                                <input class="form-control" size="16" type="text" name='claim_date2' value="<?php echo $claim_date2; ?>" >
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div>
                            <!-- Claim Date to End -->
                        </div>
                        <!-- Claim Date End-->
                      </div>
                      <br/>
                      <!-- submit button -->
                      <div class="row">
                        <!-- submit button -->
                          <div class="col-sm-12">
                            <button class="btn btn-primary pull-right">Display Claim</button>
                          </div> 
                        <!-- submit button -->
                      </div>
                    </form>

                  </div>
                </div>
              </div>
            </div><!-- End Filter Section -->
            <!-- List Section -->
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <?php if (isset($customer)) { ?>
                    <h2>Claim Info<small></small></h2>
                    <?php }else{ ?>
                    <h2>Search Result<small></small></h2>
                    <?php } ?>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    
                    <div class="table-responsive">
                      <table class="table table-hover table-bordered">
                        <thead>
                          <tr>
                            <!-- th>Detail</th -->
                            <th>Policy Number</th>
                            <th>Claim Number</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Gender</th>
                            <th>Birth Date</th>
                            <th>Claim Date</th>
                            <th>Claim Amount</th>
                            <th>Paid Amount</th>
                            <th>&nbsp;</th>
                          </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($lists as $c) { ?>
                            <tr>
                              <!-- td><a style="color:#46b8da;" href="<?php echo $citem_url."/".$c['claim_id']; ?>"><?php echo $c['claim_id']; ?></a></td -->
                              <td><?php echo $c['policy_number']; ?></td>
                              <td><?php echo $c['claim_number']; ?></td>
                              <td><?php echo $c['firstname']; ?></td>
                              <td><?php echo $c['lastname']; ?></td>
                              <td><?php echo $c['gender']; ?></td>
                              <td><?php echo $c['birthday']; ?></td>
                              <td><?php echo $c['claim_date']; ?></td>
                              <td><?php echo $c['claim_total']; ?></td>
                              <td><?php echo $c['paid_total']; ?></td>
                              <td><?php if ($c['done'] == 1) { ?><a style="color:#46b8da;" href="<?php echo $edit_url."/".$c['claim_id']?>">Finished</a><?php } else { ?><a style="color:#46b8da;" href="<?php echo $edit_url."/".$c['claim_id']?>">Detail</a><?php } ?></td>
                            </tr>
                        <?php } ?>    
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div><!-- End List Section -->

          
        </div>
        <!-- /page content -->
        