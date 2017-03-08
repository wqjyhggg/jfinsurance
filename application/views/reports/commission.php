<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!-- Report/ Commission report page content -->

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
                <h3>Commission Report</h3>
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

                    <form method="post" action="<?=$action_url ?>" class="form-horizontal">
                      <input type='hidden' name='<?php echo $csrf['name']; ?>' value='<?php echo $csrf['value']; ?>'>
                      <div class="row">
                      <!-- Agent select box -->
                        <div class="form-group col-sm-4">
                          <label class="col-sm-12">Agent:</label>
                          <div class="input-group col-sm-12">
                              <select name="agent_id" class="form-control">
                                <option value=0>Choose Agent</option>
<?php foreach ($user_list as $agent) : ?>
    <?php if ($agent_id == $agent['user_id']) : ?>
                                <option value="<?=$agent['user_id'] ?>"  selected>
    <?php else : ?>
                                <option value="<?=$agent['user_id'] ?>" >
    <?php endif; ?>
                                    <?=$agent['full_name'] ?> (<?=$agent['username'] ?>)
                                </option>
<?php endforeach; ?>
                              </select>
                          </div>
                        </div>
                        <!-- Agent select box end -->

                        <!-- Product select box -->
                        <div class="form-group col-sm-4">
                          <label class="col-sm-12">Product:</label>
                            <div class="input-group col-sm-12">
                              <select name="product_short" class="form-control">
                                <option value="">Choose Product</option>
<?php foreach ($product_list as $product) : ?>
    <?php if ($product_short == $product['product_short']) : ?>
                                <option value="<?=$product['product_short'] ?>"  selected>
    <?php else : ?>
                                <option value="<?=$product['product_short'] ?>" >
    <?php endif; ?>
                                    <?=$product['full_name'] ?> (<?=$product['product_short'] ?>)
                                </option>
<?php endforeach; ?>
                              </select>
                          </div>
                        </div>
                        <!-- Product select box end -->
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
                      </div>

                        <!-- Payment Added Date-->
                        <div class="form-group col-sm-4">
                            <!-- Payment Added Date From-->
                            <label for="payment_added_from" class="col-sm-12">Payment Added Date From</label>
                            <div class="input-group date" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd">
                            <input name="payment_added_from" class="form-control" size="16" type="text" value="<?php $payment_added_from ?>" >
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div>
                            <input type="hidden" id="payment_added_from" value="" />
                            <!-- Payment Added Date From End-->
                            <!-- Payment Added Date to -->
                            <label for="payment_added_to" class="col-sm-12">Payment Added Date To</label>
                            <div class="input-group date" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd">
                                <input name="payment_added_to" class="form-control" size="16" type="text" value="<?php $payment_added_to ?>" >
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
                            <input name="payment_date_from" class="form-control" size="16" type="text" value="<?php $payment_date_from ?>" >
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div>
                            <input type="hidden" id="payment_date_from" value="" />
                            <!-- Payment Update Date From End-->
                            <!-- Payment Update Date to -->
                            <label for="payment_date_to" class="col-sm-12">Payment Update Date To</label>
                            <div class="input-group date" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd">
                                <input name="payment_date_to" class="form-control" size="16" type="text" value="<?php $payment_date_to ?>" >
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div>
                            <input type="hidden" id="payment_date_to" value="" /><br/>
                            <!-- Payment Update Date to End -->
                        </div>
                        <!-- Payment Update Date End -->

                      <div class="row">
                        <!-- submit button -->
                          <div class="col-sm-12">
                            <button class="btn btn-primary pull-right">Display Report</button>
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
		                            <button class="btn btn-primary pull-right">Export Commission Report</button>
		                        </div>
		                        <!-- submit button -->
		                    </div>
		                </form>
					</span></h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
<?php if (!empty($report_data)) : ?>
                    <div class="table-responsive">
                      <table class="table table-hover table-bordered">

    <?php foreach ($report_data as $user_id => $data) :?>
                        <tbody>
                          <tr><td colspan=2>Agent Name: </td>
                            <td colspan=8><?php echo $data['agent']['firstname'] . " " . $data['agent']['lastname']; ?></td>
                            <td colspan=2>Payment Method: </td>
                            <td colspan=2><?php echo $data['agent']['receive_type']; ?></td>
                          </tr>
                          <tr>
                            <td colspan=10></td>
                            <td colspan=2>Mailing Address: </td>
                            <td colspan=2><?php echo $data['agent']['mail_address'] . " " . $data['agent']['mail_city'] . "," . $data['agent']['mail_province2'] . " " . $data['agent']['mail_postcode']; ?></td>
                          </tr>
                          <tr>
                            <td colspan=2>Commission Cheque Title: </td>
                            <td colspan=12><?php echo $data['agent']['note']; ?></td>
                          </tr>
                          <tr><td colspan=14>&nbsp;</td></tr>
                          <tr>
                            <th>Count</th>
                            <th>Date</th>
                            <th>Policy Number</th>
                            <th>Policy Status</th>
                            <th>Insurer</th>
                            <th>Customer Name</th>
                            <th>Effective Date</th>
                            <th>Expiry Date</th>
                            <th>Trip Length</th>
                            <th>Premium Amount</th>
                            <th>Premium Pay Status</th>
                            <th>Commission Rate</th>
                            <th>Commission Amount</th>
                            <th>Commission Pay Status</th>
                          </tr>
        <?php $cnt = 1; $total_premium = 0; $total_commission = 0; ?>
        <?php foreach ($data['data'] as $record) : ?>
        <?php $total_premium += $record['premium']; $total_commission += $record['amount']; ?>
                            <tr>
                              <td><?php echo $cnt++; ?></td>
                              <td><?php echo substr($record['added'], 0, 10); ?></td>
                              <td><?php echo $record['policy']; ?></td>
                              <td><?php echo $record['status']; ?></td>
                              <td><?php echo $record['up_insuer']; ?></td>
                              <td><?php echo $record['customer_name']; ?></td>
                              <td><?php echo $record['effective_date']; ?></td>
                              <td><?php echo $record['expiry_date']; ?></td>
                              <td><?php echo $record['total_days']; ?></td>
                              <td>$<?php echo $record['premium']; ?></td>
                              <td><?php echo ($record['premiumispaid']) ? "Paid" : '-'; ?></td>
                              <td><?php echo $record['rate']; ?>%</td>
                              <td>$<?php echo $record['amount']; ?></td>
                              <td><?php echo ($record['ispaid']) ? "Paid" : '-'; ?></td>
                            </tr>
        <?php endforeach; ?>
                            <tr>
                              <td><B>TOTAL</B></td>
                              <td>&nbsp;</td>
                              <td>&nbsp;</td>
                              <td>&nbsp;</td>
                              <td>&nbsp;</td>
                              <td>&nbsp;</td>
                              <td>&nbsp;</td>
                              <td>&nbsp;</td>
                              <td>&nbsp;</td>
                              <td>$<?php echo $total_premium; ?></td>
                              <td>&nbsp;</td>
                              <td>&nbsp;</td>
                              <td>$<?php echo $total_commission; ?></td>
                              <td>&nbsp;</td>
                            </tr>
                            <tr style="background:#eee;"><td colspan=14></td></tr>
                        </tbody>
    <?php endforeach; ?>
                      </table>
                    </div>
<?php endif; ?>
                  </div>
                </div>
              </div>
            </div><!-- End List Section -->

          </div>
        </div>
        <!-- /page content -->
