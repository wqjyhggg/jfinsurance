<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!-- refund report content -->

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
                <h3>Refund Report</h3>
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
                        <div class="form-group col-sm-3">
 <?php if ($beuser['region_id'] == 0) { ?>
                        <!-- Product input box -->
                          <label class="col-sm-12">Region:</label>
                            <div class="input-group col-sm-12">
                            <select name='region_id' class="form-control">
                              <option value='0'> -- All Region -- </option>
                              <?php foreach ($regions as $key => $name) { ?>
                              <option value='<?php echo $key; ?>' <?php echo ($region_id == $key) ? 'selected' : ''; ?>><?php echo $name; ?></option>
                              <?php } ?>
                            </select>
                          </div>
<?php } else { ?>
							<input type='hidden' name='region_id' value='<?php echo $beuser['region_id']; ?>'>
<?php } ?>
                        </div>
                        <!-- Product input box -->
                        <div class="form-group col-sm-3">
                          <label class="col-sm-12">Product:</label>
                            <div class="input-group col-sm-12">
                              <select name='product_short' class="form-control">
                                <option value="">Choose Product</option>
<?php foreach ($product_list as $product) : ?>
    <?php if ($product_short == $product['product_short']) : ?>
                                <option value="<?=$product['product_short'] ?>"  selected>
    <?php else : ?>
                                <option value="<?=$product['product_short'] ?>" >
    <?php endif; ?>
                                    <?php echo $agent['username'] . " ( ". $agent['full_name'] . " )"; ?>
                                </option>
<?php endforeach; ?>
                              </select>
                          </div><br>
                          <div class="input-group col-sm-12">
                              <input type='checkbox' name="ispaid" <?php echo ($ispaid) ? 'checked' : ''; ?>> Paid
                          </div>
                        </div>
                        <!-- Product input box end -->

                        <!-- Created Date -->
                        <div class="form-group col-sm-3">
                          <!-- Application Date from -->
                            <label for="create_date_from" class="col-sm-12">Created Date From</label>
                            <div class="input-group date" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd">
                                <input id="create_date_from" name="create_date_from" class="form-control" size="16" type="text" value="<?=$create_date_from ?>" >
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div>
                            <!-- create Date from End-->
                            <!-- create Date to -->
                            <label for="create_date_to" class="col-sm-12">create Date To</label>
                            <div class="input-group date" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd">
                                <input id="create_date_to" name="create_date_to" class="form-control" size="16" type="text" value="<?=$create_date_to ?>" >
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div>
                            <!-- create Date to End -->
                        </div>
                        <!-- Application Date End-->
                        <!-- pay Date-->
                        <div class="form-group col-sm-3">
                            <!-- pay Date From-->
                            <label for="pay_date_from" class="col-sm-12">Pay Date From</label>
                            <div class="input-group date" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd">
                                <input name="pay_date_from" class="form-control" size="16" type="text" value="<?=$pay_date_from ?>" >
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div>
                            <input type="hidden" id="pay_date_from" value="" />
                            <!-- pay Date From End-->
                            <!-- pay Date to -->
                            <label for="pay_date_to" class="col-sm-12">Pay Date To</label>
                            <div class="input-group date" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd">
                                <input name="pay_date_to" class="form-control" size="16" type="text" value="<?=$pay_date_to ?>" >
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div>
                            <input type="hidden" id="pay_date_to" value="" /><br/>
                            <!-- pay Date to End -->
                        </div>
                        <!-- pay Date End -->
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
                  <div class="x_content">
                    <div class="table-responsive">
<?php if (!empty($report_data)) :?>
					<div class="x_title" style="border:none;">
						<h2>Search Result<span class="inline-m">
							<form method="get" action="<?php echo $export_list ?>" class="form-horizontal">
								<input type='hidden' name='<?php echo $csrf['name']; ?>' value='<?php echo $csrf['value']; ?>'>
								<input type='hidden' name="ispaid" value="<?php echo $ispaid; ?>" />
								<input type='hidden' name="product_short" value="<?php echo $product_short; ?>" />
								<input type='hidden' name="pay_date_from" value="<?php echo $pay_date_from; ?>" />
								<input type='hidden' name="pay_date_to" value="<?php echo $pay_date_to; ?>" />
								<input type='hidden' name="create_date_from" value="<?php echo $create_date_from; ?>" />
								<input type='hidden' name="create_date_to" value="<?php echo $create_date_to; ?>" />
								<input class="btn btn-info" type='submit' value="Export Xlsx" />
							</form>
						</span></h2>
					</div>
                      <table class="table table-hover table-bordered">
                        <thead>
                          <tr>
                            <th>Policy #</th>
                            <th>Customer Name</th>
                            <th>DOB</th>
                            <th>Refund Date</th>
                            <th>Agent Name</th>
                            <th>Original Premium</th>
                            <th>Original Net Premium</th>
                            <th>Refund Amount</th>
                            <th>Admin Fee</th>
                            <th>Total Refund</th>
                            <th>Refund Process Date</th>
                          </tr>
                        </thead>
                        <tbody>
    <?php $total = 0; ?>
    <?php foreach ($report_data as $record) :?>
    <?php $total += $record['amount']; ?>
                            <tr>
                              <td><?php echo $record['policy']; ?></td>
                              <td><?php echo $record['customer_name']; ?></td>
                              <td><?php echo $record['birthday']; ?></td>
                              <td><?php echo $record['refund_date']; ?></td>
                              <td><?php echo $record['agent_name']; ?></td>
                              <td><?php echo number_format($record['premium'], 2); ?></td>
                              <td><?php echo number_format($record['premium'] - $record['commission'], 2); ?></td>
                              <td><?php echo number_format($record['net_amount'], 2); ?></td>
                              <td><?php echo number_format($record['admin_fee'], 2); ?></td>
                              <td><?php echo number_format($record['amount'], 2); ?></td>
                              <td><?php echo substr($record['added'], 0 , 10); ?></td>
                            </tr>
        <?php endforeach; ?>
                            <tr>
                              <td><b>Total</b></td>
                              <td>&nbsp;</td>
                              <td>&nbsp;</td>
                              <td>&nbsp;</td>
                              <td>&nbsp;</td>
                              <td>&nbsp;</td>
                              <td>&nbsp;</td>
                              <td>&nbsp;</td>
                              <td>&nbsp;</td>
                              <td><?php echo number_format($total, 2); ?></td>
                              <td>&nbsp;</td>
                            </tr>
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
