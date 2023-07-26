<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!-- Report/ Sales report to jf page content -->

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
                <h3>Sales Report to Jf</h3>
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
                  <form method="post" action="<?php $action_url ?>" class="form-horizontal">
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
                                <option value="<?php echo $agent['user_id'] ?>"  selected>
    <?php else : ?>
                                <option value="<?php echo $agent['user_id'] ?>" >
    <?php endif; ?>
                                    <?php echo $agent['username'] . " ( ". $agent['full_name'] . " )"; ?>
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
                                    <?php echo $product['product_short'] . " - " . $product['full_name']; ?>
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
                        <!-- Payment Added Date-->
                        <div class="form-group col-sm-4">
                            <!-- Payment Added Date From-->
                            <label for="payment_added_from" class="col-sm-12">Payment Added Date From</label>
                            <div class="input-group date" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd">
                            <input name="payment_added_from" class="form-control" size="16" type="text" value="<?php echo $payment_added_from ?>" >
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div>
                            <input type="hidden" id="payment_added_from" value="" />
                            <!-- Payment Added Date From End-->
                            <!-- Payment Added Date to -->
                            <label for="payment_added_to" class="col-sm-12">Payment Added Date To</label>
                            <div class="input-group date" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd">
                                <input name="payment_added_to" class="form-control" size="16" type="text" value="<?php echo $payment_added_to ?>" >
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div>
                            <input type="hidden" id="payment_added_to" value="" /><br/>
                            <!-- Payment Update Date to End -->
                        </div>
                        <!-- Payment Added Date End -->
                        <!-- Payment Update Date-->
                        <div class="form-group col-sm-4">
                            <!-- Payment Update Date From-->
                            <label for="payment_date_from" class="col-sm-12">Payment Update Date From</label>
                            <div class="input-group date" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd">
                            <input name="payment_date_from" class="form-control" size="16" type="text" value="<?php echo $payment_date_from ?>" >
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div>
                            <input type="hidden" id="payment_date_from" value="" />
                            <!-- Payment Update Date From End-->
                            <!-- Payment Update Date to -->
                            <label for="payment_date_to" class="col-sm-12">Payment Update Date To</label>
                            <div class="input-group date" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd">
                                <input name="payment_date_to" class="form-control" size="16" type="text" value="<?php echo $payment_date_to ?>" >
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div>
                            <input type="hidden" id="payment_date_to" value="" /><br/>
                            <!-- Payment Update Date to End -->
                        </div>
                        <!-- Payment Update Date End -->
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
                    <h2>Search Result 
                    	<span class="inline-m">
	                    	<form method="get" action="<?php echo $export_list; ?>" class="form-horizontal">
	                    		<input type='hidden' name="agent_id" value="<?php echo $agent_id; ?>">
	                    		<input type='hidden' name="product_short" value="<?php echo $product_short; ?>">
	                    		<input type='hidden' name="region_id" value="<?php echo $region_id; ?>">
	                    		<input type='hidden' name="payment_added_from" value="<?php echo $payment_added_from; ?>">
	                    		<input type='hidden' name="payment_added_to" value="<?php echo $payment_added_to; ?>">
	                    		<input type='hidden' name="payment_date_from" value="<?php echo $payment_date_from; ?>">
	                    		<input type='hidden' name="payment_date_to" value="<?php echo $payment_date_to; ?>">
			                    <div class="row">
			                        <!-- submit button -->
			                        <div class="col-sm-12">
			                            <button class="btn btn-primary pull-right">Export Sales Report</button>
			                        </div>
			                        <!-- submit button -->
			                    </div>
			                </form>
						</span>
					</h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <div class="table-responsive">
<?php if (!empty($report_data)) :?>
		              <div class="col-md-12 col-sm-12 col-xs-12">
		              <B>Total Pay Amount : $<?php echo number_format($report_data['amount'],2); unset($report_data['amount']);?></B>
		              </div>
                      <table class="table table-hover table-bordered">
<?php foreach ($report_data as $short => $data) :?>
<?php $cnt = 1; ?>
                          <tr>
                              <td colspan='19'>&nbsp;<?php 	echo $data['full_name']; ?></td>
                          </tr>
                          <tr>
                            <th>Count</th>
                            <th>Policy</th>
                            <th>Apply Date</th>
                            <th>Payment Date</th>
                            <th>Invoice Num</th>
                            <th>InsurerCoName</th>
                            <th>Product</th>
                            <th>Insured Name</th>
                            <th>Effective Date</th>
                            <th>Expiry Date</th>
                            <th>Number of Days</th>
                            <th>Daily Rate</th>
                            <th>Premium</th>
                            <th>Tax</th>
                            <th>Pay Amount</th>
                            <th>Paid</th>
                            <th>Type</th>
                            <th>Pay Mothed</th>
                            <th>Commission Rate</th>
                            <th>Net Amount</th>
                            <th>Commission Amount</th>
                            <?php if ($region_id == 4) { ?>
                            <th>Contact Email</th>
                            <th>Contact Phone</th>
                            <?php } ?>
                            <th>Note</th>
                          </tr>
    <?php foreach ($data['results'] as $record) :?>
                          <tr>
                            <td><?php echo $cnt++; ?></td>
                            <td><?php echo $record['policy']; ?></td>
                            <td><?php echo $record['apply_date']; ?></td>
                            <td><?php echo substr($record['added'],0,10); ?></td>
                            <td><?php echo $record['invoice_num']; ?></td>
                            <td><?php echo $record['up_insuer']; ?></td>
                            <td><?php echo $record['product_short']; ?></td>
                            <td><?php echo $record['insured']; ?></td>
                            <td><?php echo $record['effective_date']; ?></td>
                            <td><?php echo $record['expiry_date']; ?></td>
                            <td><?php echo $record['totaldays']; ?></td>
                            <td>$<?php echo $record['dailyrate']; ?></td>
                            <td>$<?php echo $record['premium']; ?></td>
                            <td>$<?php echo $record['tax']; ?></td>
                            <td>$<?php echo $record['amount']; ?></td>
                            <td><?php echo (($record['ispaid'] == 1) ? 'Y' : '-'); ?></td>
                            <td><?php echo $record['pay_type']; ?></td>
                            <td><?php echo $record['pay_mothed']; ?></td>
                            <?php if (abs($record['amount']) > 0.009) { ?>
                            <td><?php printf("%0.2f", $record['commission'] * 100 / $record['amount']); ?></td>
                            <?php } else { ?>
                            <td>&nbsp;</td>
                            <?php } ?>
                            <td>$<?php echo ($record['amount'] - $record['commission']); ?></td>
                            <td>$<?php echo $record['commission']; ?></td>
                            <?php if ($region_id == 4) { ?>
                            <td><?php echo $record['contact_email']; ?></td>
                            <td><?php echo $record['contact_phone']; ?></td>
                            <?php } ?>
                            <td><?php echo $record['note']; ?></td>
                          </tr>
        <?php endforeach; ?>
                          <tr>
                            <td colspan='14'>&nbsp;</td>
                            <td>$<?php echo $data['amount']; ?></td>
                            <td colspan='3'>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>$<?php echo ($data['amount'] - $data['commission']); ?></td>
                            <td>$<?php echo $data['commission']; ?></td>
                            <?php if ($region_id == 4) { ?>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <?php } ?>
                          </tr>
<?php endforeach; ?>
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
