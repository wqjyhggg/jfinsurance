<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!-- claim report content -->

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
                <h3>Claim Report</h3>
              </div>

            </div>
            <div class="clearfix"></div>
            <!-- Filter Section -->
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Report Filter<small></small></h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                  <form method="post" action="<?php echo $action_url; ?>" class="form-horizontal">
						<input type='hidden' name='<?php echo $csrf['name']; ?>' value='<?php echo $csrf['value']; ?>'>
                      <div class="row">
                      <!-- Agent input box -->
                        <div class="form-group col-sm-4">
                          <label class="col-sm-12">Agent:</label>
                          <div class="input-group col-sm-12">
                              <select name="agent_id" class="form-control">
                                <option value=0>Choose Agent</option>
<?php foreach ($user_list as $agent) : ?>
    <?php if ($agent_id == $agent['user_id']) : ?>
                                <option value="<?php echo $agent['user_id']; ?>"  selected>
    <?php else : ?>
                                <option value="<?php echo $agent['user_id']; ?>" >
    <?php endif; ?>
                                    <?php echo $agent['username'] ." - " . $agent['full_name']; ?>
                                </option>
<?php endforeach; ?>
                              </select>
                          </div>
                        </div>
                        <!-- Agent input box end -->

                        <!-- Product input box -->
                        <div class="form-group col-sm-4">
                          <label class="col-sm-12">Product:</label>
                            <div class="input-group col-sm-12">
                              <select name='product_short' class="form-control">
                                <option value="">Choose Product</option>
<?php foreach ($product_list as $product) : ?>
    <?php if ($product_short == $product['product_short']) : ?>
                                <option value="<?php echo $product['product_short']; ?>"  selected>
    <?php else : ?>
                                <option value="<?php echo $product['product_short']; ?>" >
    <?php endif; ?>
                                    <?php echo $product['full_name'] . " ( " . $product['product_short'] . " )"; ?>
                                </option>
<?php endforeach; ?>
                              </select>
                          </div>
                        </div>
                        <!-- Product input box end -->
                        <!-- Region input box end -->
<?php if ($beuser['region_id'] == 0) { ?>
                        <!-- Product input box -->
                        <div class="form-group col-sm-4">
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
<?php } else { ?>
						<input type='hidden' name='region_id' value='<?php echo $beuser['region_id']; ?>'>
<?php } ?>
                        <!-- Region input box end -->
                      </div>

                      <div class="row">
                        <!-- Application Date -->
                        <div class="form-group col-sm-3">
                          <!-- Application Date from -->
                            <label for="application_date_from" class="col-sm-12">Application Date From</label>
                            <div class="input-group date" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd">
                                <input name="application_date_from" class="form-control" size="16" type="text" value="<?php echo $application_date_from; ?>" >
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div>
                            <input type="hidden" id="application_date_from" value="" />
                            <!-- Application Date from End-->
                            <!-- Application Date to -->
                            <label for="application_date_to" class="col-sm-12">Application Date To</label>
                            <div class="input-group date" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd">
                                <input name="application_date_to" class="form-control" size="16" type="text" value="<?php echo $application_date_to; ?>" >
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div>
                            <input type="hidden" id="application_date_to" value="" /><br/>
                            <!-- Application Date to End -->
                        </div>
                        <!-- Application Date End-->
                        <!-- Effective Date-->
                        <div class="form-group col-sm-3">
                            <!-- Effective Date From-->
                            <label for="effective_date_from" class="col-sm-12">Effective Date From</label>
                            <div class="input-group date" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd">
                                <input name="effective_date_from" class="form-control" size="16" type="text" value="<?php echo $effective_date_from; ?>" >
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div>
                            <input type="hidden" id="effective_date_from" value="" />
                            <!-- Effective Date From End-->
                            <!-- Effective Date to -->
                            <label for="effective_date_to" class="col-sm-12">Effective Date To</label>
                            <div class="input-group date" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd">
                                <input name="effective_date_to" class="form-control" size="16" type="text" value="<?php echo $effective_date_to; ?>" >
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div>
                            <input type="hidden" id="effective_date_to" value="" /><br/>
                            <!-- Effective Date to End -->
                        </div>
                        <!-- Effective Date End -->
                        <!-- Payment Update Date-->
                        <div class="form-group col-sm-3">
                            <!-- Payment Update Date From-->
                            <label for="payment_update_date_from" class="col-sm-12">Item Paid Date From</label>
                            <div class="input-group date" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd">
                                <input name="payment_update_date_from" class="form-control" size="16" type="text" value="<?php echo $payment_update_date_from; ?>" >
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div>
                            <input type="hidden" id="payment_update_date_from" value="" />
                            <!-- Payment Update Date From End-->
                            <!-- Payment Update Date to -->
                            <label for="payment_update_date_to" class="col-sm-12">Item Paid Date To</label>
                            <div class="input-group date" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd">
                                <input name="payment_update_date_to" class="form-control" size="16" type="text" value="<?php echo $payment_update_date_to; ?>" >
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div>
                            <input type="hidden" id="payment_update_date_to" value="" /><br/>
                            <!-- Payment Update Date to End -->
                        </div>
                        <!-- Payment Update Date End -->
                        <!-- Create Date-->
                        <div class="form-group col-sm-3">
                            
                            <label for="create_date_from" class="col-sm-12">Create Date From</label>
                            <div class="input-group date" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd">
                                <input name="create_date_from" class="form-control" size="16" type="text" value="<?php echo $create_date_from; ?>" >
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div>
                            <input type="hidden" id="create_date_from" value="" />
                           
                            <label for="create_date_to" class="col-sm-12">Create Date To</label>
                            <div class="input-group date" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd">
                                <input name="create_date_to" class="form-control" size="16" type="text" value="<?php echo $create_date_to; ?>" >
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div>
                            <input type="hidden" id="create_date_to" value="" /><br/>
                           
                        </div>
                        <!-- Create Date End -->
                      </div>
                      <div class="row">
                        <!-- submit button -->
                          <div class="col-sm-12">
                            <button class="btn btn-primary pull-right">Display Sales Report</button>
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
                    <h2>Search Result <span class="inline-m">
                    <form method="get" action="<?php echo $export_list; ?>" class="form-horizontal">
						<input type='hidden' name="agent_id" value="<?php echo $agent_id; ?>" />
						<input type='hidden' name="product_short" value="<?php echo $product_short; ?>" />
						<input type='hidden' name="region_id" value="<?php echo $region_id; ?>" />
						<input type='hidden' name="application_date_from" value="<?php echo $application_date_from; ?>" />
						<input type='hidden' name="application_date_to" value="<?php echo $application_date_to; ?>" />
						<input type='hidden' name="effective_date_from" value="<?php echo $effective_date_from; ?>" />
						<input type='hidden' name="effective_date_to" value="<?php echo $effective_date_to; ?>" />
						<input type='hidden' name="payment_update_date_from" value="<?php echo $payment_update_date_from; ?>" />
						<input type='hidden' name="payment_update_date_to" value="<?php echo $payment_update_date_to; ?>" />
						<input type='hidden' name="create_date_from" value="<?php echo $create_date_from; ?>" />
						<input type='hidden' name="create_date_to" value="<?php echo $create_date_to; ?>" />
						<input class="btn btn-info" type='submit' value="Export Xlsx" />
					</form>
					</span></h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <div class="table-responsive">
<?php if (!empty($report_data)) :?>
                      <table class="table table-hover table-bordered">
                        <thead>
                          <tr>
                            <th>Claim Number</th>
                            <th>Policy Number</th>
                            <th>Agent Name</th>
                            <th>Enter User</th>
                            <th>Customer Name</th>
                            <th>Date of Birth</th>
                            <th>Address</th>
                            <th>City</th>
                            <th>Province</th>
                            <th>Postal Code</th>
                            <th>Gender</th>
                            <th>Claim Date</th>
                            <th>Service Date</th>
                            <th>Diagnosis</th>
                            <th>Coverage Code</th>
                            <th>Deductible</th>
                            <th>Claim Amount</th>
                            <th>Amount Paid</th>
                            <th>Amount Received</th>
                            <th>Cheque Number</th>
                            <th>Cheque Cash Day</th>
                            <th>Payee Name</th>
                            <th>External Notes</th>
                          </tr>
                        </thead>
                        <tbody>
    <?php foreach ($report_data as $record) : ?>
                            <tr>
                              <td><?php echo $record['claim_number']; ?></td>
                              <td><?php echo $record['policy']; ?></td>
                              <td><?php echo $record['agent_name']; ?></td>
                              <td><?php echo $record['staff_name']; ?></td>
                              <td><?php echo $record['customer_name']; ?></td>
                              <td><?php echo $record['birthday']; ?></td>
                              <td><?php echo $record['address']; ?></td>
                              <td><?php echo $record['city']; ?></td>
                              <td><?php echo $record['province2']; ?></td>
                              <td><?php echo $record['postcode']; ?></td>
                              <td><?php echo $record['gender']; ?></td>
                              <td><?php echo $record['claim_date']; ?></td>
                              <td><?php echo $record['service_date']; ?></td>
                              <td><?php echo $record['diagnosis']; ?></td>
                              <td><?php echo $record['coverage_code_id']; ?></td>
                              <td><?php echo $record['deductible_amount']; ?></td>
                              <td>$<?php echo $record['claimed']; ?></td>
                              <td>$<?php echo $record['paid']; ?></td>
                              <td>$<?php echo $record['amount_received']; ?></td>
                              <td><?php echo $record['cheque_number']; ?></td>
                              <td><?php echo $record['cashed_date']; ?></td>
                              <td><?php echo $record['pay_to']; ?></td>
                              <td><?php echo $record['external_note']; ?></td>
                            </tr>
    <?php endforeach; ?>
                        </tbody>
                      </table>
<?php endif; ?>                      
                    </div>
                  </div>
                </div>
              </div>
            </div><!-- End List Section -->

          </div>
        </div>
        <!-- /page content -->
