<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!-- Report/ Sales report to agent page content -->

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
                <h3>Sales Report to Agent</h3>
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
                      <!-- Agent input box -->
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
                        <!-- Agent input box end -->

                        <!-- Product input box -->
                        <div class="form-group col-sm-4">
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
                                    <?=$product['full_name'] ?> (<?=$product['product_short'] ?>)
                                </option>
<?php endforeach; ?>
                              </select>
                          </div>
                        </div>
                        <!-- Product input box end -->
                      </div>

                      <div class="row">
                        <!-- Application Date -->
                        <div class="form-group col-sm-3">
                          <!-- Application Date from -->
                            <label for="application_date_from" class="col-sm-12">Application Date From</label>
                            <div class="input-group date" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd">
                                <input name="application_date_from" class="form-control" size="16" type="text" value="<?=$application_date_from ?>" >
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div>
                            <input type="hidden" id="application_date_from" value="" />
                            <!-- Application Date from End-->
                            <!-- Application Date to -->
                            <label for="application_date_to" class="col-sm-12">Application Date To</label>
                            <div class="input-group date" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd">
                                <input name="application_date_to" class="form-control" size="16" type="text" value="<?=$application_date_to ?>" >
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div>
                            <input type="hidden" id="application_date_to" value="" /><br/>
                            <!-- Application Date to End -->
                        </div>
                        <!-- Application Date End-->
                        <!-- Create Date-->
                        <div class="form-group col-sm-3">
                            <!-- Create Date From-->
                            <label for="create_date_from" class="col-sm-12">Create Date From</label>
                            <div class="input-group date" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd">
                                <input name="create_date_from" class="form-control" size="16" type="text" value="<?=$create_date_from ?>" >
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div>
                            <input type="hidden" id="create_date_from" value="" />
                            <!-- Create Date From End-->
                            <!-- Create Date to -->
                            <label for="create_date_to" class="col-sm-12">Create Date To</label>
                            <div class="input-group date" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd">
                                <input name="create_date_to" class="form-control" size="16" type="text" value="<?=$create_date_to ?>" >
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div>
                            <input type="hidden" id="create_date_to" value="" /><br/>
                            <!-- Create Date to End -->
                        </div>
                        <!-- Create Date End -->
                        <!-- Effective Date-->
                        <div class="form-group col-sm-3">
                            <!-- Effective Date From-->
                            <label for="effective_date_from" class="col-sm-12">Effective Date From</label>
                            <div class="input-group date" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd">
                                <input name="effective_date_from" class="form-control" size="16" type="text" value="<?=$effective_date_from ?>" >
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div>
                            <input type="hidden" id="effective_date_from" value="" />
                            <!-- Effective Date From End-->
                            <!-- Effective Date to -->
                            <label for="effective_date_to" class="col-sm-12">Effective Date To</label>
                            <div class="input-group date" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd">
                                <input name="effective_date_to" class="form-control" size="16" type="text" value="<?=$effective_date_to ?>" >
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div>
                            <input type="hidden" id="effective_date_to" value="" /><br/>
                            <!-- Effective Date to End -->
                        </div>
                        <!-- Effective Date End -->
                        <!-- Payment Update Date-->
                        <div class="form-group col-sm-3">
                            <!-- Payment Update Date From-->
                            <label for="payment_update_date_from" class="col-sm-12">Payment Update Date From</label>
                            <div class="input-group date" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd">
                                <input name="payment_update_date_from" class="form-control" size="16" type="text" value="<?=$payment_update_date_from ?>" >
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div>
                            <input type="hidden" id="payment_update_date_from" value="" />
                            <!-- Payment Update Date From End-->
                            <!-- Payment Update Date to -->
                            <label for="payment_update_date_to" class="col-sm-12">Payment Update Date To</label>
                            <div class="input-group date" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd">
                                <input name="payment_update_date_to" class="form-control" size="16" type="text" value="<?=$payment_update_date_to ?>" >
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div>
                            <input name="payment_update_date_to" type="hidden" id="payment_update_date_to" value="" /><br/>
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
                    <h2>Search Result<small></small></h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <div class="table-responsive">
                      <table class="table table-hover table-bordered">
<?php foreach ($report_data as $data) :?>
                        <thead>
                          <tr>
                            <th>Count</th>
                            <th>Order Date</th>
                            <th>Policy No</th>
                            <th>Insurer</th>
                            <th>Product</th>
                            <th>Insured Name</th>
                            <th>Effective Date</th>
                            <th>Expiry Date</th>
                            <th>Number of Days</th>
                            <th>Daily Rate</th>
                            <th>Policy Premium</th>
                            <th>Commission Rate</th>
                            <th>Net Premium</th>
                            <th>Commission Amount</th>
                          </tr>
                        </thead>
                        <tbody>
    <?php $cnt = 1; ?>
    <?php foreach ($data['records'] as $record) :?>
                            <tr>
                              <td><?=$cnt++ ?></td>
                              <td><?=$record['order_date'] ?></td>
                              <td><?=$record['policy'] ?></td>
                              <td><?=$record['insurer'] ?></td>
                              <td><?=$record['product'] ?></td>
                              <td><?=$record['insured_name'] ?></td>
                              <td><?=$record['effective_date'] ?></td>
                              <td><?=$record['expiry_date'] ?></td>
                              <td><?=$record['total_days'] ?></td>
                              <td>$<?=$record['daily_rate'] ?></td>
                              <td>$<?=$record['policy_premium'] ?></td>
                              <td><?=$record['commission_rate'] ?></td>
                              <td>$<?=$record['net_premium'] ?></td>
                              <td>$<?=$record['commission_amount'] ?></td>
                            </tr>
    <?php endforeach; ?>
                            <tr><td colspan=14>
                                Total Premium: $<?=$data['data']['policy_premium'] ?>;&nbsp;&nbsp;
                                Total Net Premium: $<?=$data['data']['net_premium'] ?>
                            </td></tr>
                            <tr style="background:#eee;"><td colspan=14></td></tr>
                        </tbody>
<?php endforeach; ?>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div><!-- End List Section -->

          </div>
        </div>
        <!-- /page content -->
