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
                  <div class="x_title">
                    <h2>Claim Search<small>Please enter the Search Criteria</small></h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                   
                    <form method="get" class="form-horizontal" active='<?php echo $action_url; ?>'>
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
                        <!-- Cheque No. input box -->
                        <div class="form-group col-sm-3">
                          <label class="col-sm-12">Cheque No.:</label>
                          <div class="input-group col-sm-12">
                              <input type="text" name="cheque_number" value='<?php echo $cheque_number; ?>' class="form-control"/>
                          </div>
                        </div>
                        <!-- Cheque No. input box end -->
                      
                        <!-- Claim Date -->
                        <div class="form-group col-sm-3">
                          <!-- Claim Date from -->
                            <label for="claim_date_from" class="col-sm-12">Claim Date From</label>
                            <div class="input-group date form_date col-sm-12" data-date="" data-date-format="yyyy/mm/dd" data-link-field="claim_date_from" data-link-format="yyyy-mm-dd">
                                <input class="form-control" size="16" type="text" name='claim_date' value="<?php echo $claim_date; ?>" readonly>
                                <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div>
                            <!-- Claim Date from End-->
                        </div>    
                        <div class="form-group col-sm-3">    
                            <!-- Claim Date to -->
                            <label for="claim_date_to" class="col-sm-12">Claim Date To</label>
                            <div class="input-group date form_date col-sm-12" data-date="" data-date-format="yyyy/mm/dd" data-link-field="claim_date_to" data-link-format="yyyy-mm-dd">
                                <input class="form-control" size="16" type="text" name='claim_date2' value="<?php echo $claim_date2; ?>" readonly>
                                <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div>
                            <!-- Claim Date to End -->
                        </div>
                        <!-- Claim Date End-->
                      </div>
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
                    <h2>Search Result<small></small></h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    
                    <div class="table-responsive">
                      <table class="table table-hover table-bordered">
                        <thead>
                          <tr>
                            <th>Action</th>
                            <th>Policy Number</th>
                            <th>Claim Number</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Gender</th>
                            <th>Birth Date</th>
                            <th>Claim Date</th>
                            <th>Claimed</th>
                            <th>Paid</th>
                            <th>Pay To</th>
                            <th>Cheque Number</th>
                            <th>Recieved</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($lists as $c) { ?>
                            <tr>
                              <td><a style="color:#46b8da;" href="<?php echo $edit_url."/".$c['claim_id']?>">Edit</a></td>
                              <td><?php echo $c['policy_number']; ?></td>
                              <td><?php echo $c['claim_number']; ?></td>
                              <td><?php echo $c['firstname']; ?></td>
                              <td><?php echo $c['lastname']; ?></td>
                              <td><?php echo $c['gender']; ?></td>
                              <td><?php echo $c['birthday']; ?></td>
                              <td><?php echo $c['claim_date']; ?></td>
                              <td><?php echo $c['claimed']; ?></td>
                              <td><?php echo $c['paid']; ?></td>
                              <td><?php echo $c['pay_to']; ?></td>
                              <td><?php echo $c['cheque_number']; ?></td>
                              <td><?php echo ($c['done'] == 1) ? 'Y' : 'N'; ?></td>
                              <td><a style="color:#46b8da;" href="<?php echo $edit_url."/".$c['claim_id']?>">Edit</a></td>
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
        