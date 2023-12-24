<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!-- Report/ Receivable report page content -->

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
                <h3><?php echo $this->lang->line("Receivable Report"); ?></h3>
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
<?php if (is_array($user_list) && (sizeof($user_list) == 1)) { $agent = array_shift($user_list); ?>
						<input type='hidden' name="agent_id" value="<?php echo $agent['user_id']; ?>">
<?php } else { ?>                      
                      <!-- Agent select box -->
                        <div class="form-group col-sm-3">
                          <label class="col-sm-12"><?php echo $this->lang->line("Agent"); ?>:</label>
                          <div class="input-group col-sm-12">
                              <select name="agent_id" class="form-control">
                                <option value=0><?php echo $this->lang->line("Choose Agent"); ?></option>
<?php foreach ($user_list as $agent) : ?>
    <?php if ($agent_id == $agent['user_id']) : ?>
                                <option value="<?=$agent['user_id'] ?>"  selected>
    <?php else : ?>
                                <option value="<?=$agent['user_id'] ?>" >
    <?php endif; ?>
                                    <?php echo $agent['username'] . " ( ". $agent['full_name'] . " )"; ?>
                                </option>
<?php endforeach; ?>
                              </select>
                          </div>
                        </div>
                        <!-- Agent select box end -->
<?php } ?>                      

                        <!-- Product select box -->
                        <div class="form-group col-sm-3">
                          <label class="col-sm-12"><?php echo $this->lang->line("Product"); ?>:</label>
                            <div class="input-group col-sm-12">
                              <select name="product_short" class="form-control">
                                <option value=""><?php echo $this->lang->line("Choose Product"); ?></option>
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
                        <!-- Product select box -->
                        <div class="form-group col-sm-3">
                          <label class="col-sm-12"><?php echo $this->lang->line("Policy Status"); ?>:</label>
                            <div input-group class="col-sm-12">
                              <select name="policy_status" class="form-control">
                                <option value=0 <?php if (($policy_status != 1) && ($policy_status != 2)) { echo "selected"; } ?>><?php echo $this->lang->line("All"); ?></option>
                                <option value=1 <?php if ($policy_status == 1) { echo "selected"; } ?>><?php echo $this->lang->line("Quote"); ?></option>
                                <option value=2 <?php if ($policy_status == 2) { echo "selected"; } ?>><?php echo $this->lang->line("Sold"); ?></option>
                              </select>
                          </div>
                        </div>
                        <!-- Product select box end -->
                        <!-- Region input box end -->
<?php if ($beuser['region_id'] == 0) { ?>
                        <!-- Product input box -->
                        <div class="form-group col-sm-3">
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
                            <label for="payment_added_from" class="col-sm-12"><?php echo $this->lang->line("Payment Added Date From"); ?></label>
                            <div class="input-group date" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd">
                            <input name="payment_added_from" class="form-control" size="16" type="text" value="<?php $payment_added_from ?>" >
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div>
                            <input type="hidden" id="payment_added_from" value="" />
                            <!-- Payment Added Date From End-->
                            <!-- Payment Added Date to -->
                            <label for="payment_added_to" class="col-sm-12"><?php echo $this->lang->line("Payment Added Date To"); ?></label>
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
                            <label for="payment_date_from" class="col-sm-12"><?php echo $this->lang->line("Payment Update Date From"); ?></label>
                            <div class="input-group date" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd">
                            <input name="payment_date_from" class="form-control" size="16" type="text" value="<?php $payment_date_from ?>" >
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div>
                            <input type="hidden" id="payment_date_from" value="" />
                            <!-- Payment Update Date From End-->
                            <!-- Payment Update Date to -->
                            <label for="payment_date_to" class="col-sm-12"><?php echo $this->lang->line("Payment Update Date To"); ?></label>
                            <div class="input-group date" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd">
                                <input name="payment_date_to" class="form-control" size="16" type="text" value="<?php $payment_date_to ?>" >
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
                            <button class="btn btn-primary pull-right"><?php echo $this->lang->line("Display Report"); ?></button>
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
                    		<input type='hidden' name="policy_status" value="<?php echo $policy_status; ?>">
                    		<input type='hidden' name="payment_added_from" value="<?php echo $payment_added_from; ?>">
                    		<input type='hidden' name="payment_added_to" value="<?php echo $payment_added_to; ?>">
                    		<input type='hidden' name="payment_date_from" value="<?php echo $payment_date_from; ?>">
                    		<input type='hidden' name="payment_date_to" value="<?php echo $payment_date_to; ?>">
		                    <div class="row">
		                        <!-- submit button -->
		                        <div class="col-sm-12">
		                            <button class="btn btn-primary pull-right"><?php echo $this->lang->line("Export Report"); ?></button>
		                        </div>
		                        <!-- submit button -->
		                    </div>
		                </form>
					</span></h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">

                    <div class="table-responsive">
<?php if (!empty($report_data['data'])) : ?>
                       <div>
                         JF Insurance Agency Group Inc. <br>
                         15 Wertheim court, Suite 501, Richmond Hill, ON, L4B 3H7 <br>
                         Tel: 905-707-1512 Fax: 905-707-1513 Toll free: 1-877-832-5541<br>
                         <?php echo $this->lang->line("Invoice Statement"); ?><br>
                         <?php echo $this->lang->line("For Policy of"); ?>
                         <?php echo $this->lang->line("From"); ?>:<?=$report_data['period']['from'] ?>
                         <?php echo $this->lang->line("To"); ?>:<?=$report_data['period']['to'] ?><br><br>
                       </div>
                      <table class="table table-hover table-bordered">
    <?php foreach ($report_data['data'] as $user_id => $data) :?>
                        <thead>
                        <tr><td colspan=10>
                          <?php echo $this->lang->line("Bill to"); ?>:
                          <?=$data['agency']['agent_name'] ?><br>
                          <?=$data['agency']['address'] ?><br>
                          <?=$data['agency']['province'] ?>,&nbsp;&nbsp; <?=$data['agency']['postal_code'] ?><br><br>
                          <?php echo $this->lang->line("Premium collected on behalf of JF Insurance Agency Inc"); ?>: $<?=$data['agency']['outstanding'] ?><br>
                          <?php echo $this->lang->line("Less Administration Fee for handaling the following policies"); ?>: $<?=$data['agency']['commission'] ?><br>
                          <?php echo $this->lang->line("Net payable to JF Insurance Ageny Inc."); ?>: $<?=$data['agency']['payable_to_jf'] ?>
                        </td></tr>
                        </thead>
                        <tbody>
                          <tr>
                            <th><?php echo $this->lang->line("Pruchase Date"); ?></th>
                            <th><?php echo $this->lang->line("Policy Number"); ?></th>
                            <th><?php echo $this->lang->line("Customer Name"); ?></th>
                            <th><?php echo $this->lang->line("Student ID"); ?></th>
                            <th><?php echo $this->lang->line("Effective Date"); ?></th>
                            <th><?php echo $this->lang->line("Expiry Date"); ?></th>
                            <th><?php echo $this->lang->line("Refund Date"); ?></th>
                            <th><?php echo $this->lang->line("Trip Length"); ?></th>
                            <th><?php echo $this->lang->line("Premium"); ?></th>
                            <th><?php echo $this->lang->line("Net"); ?></th>
                            <th><?php echo $this->lang->line("Commission"); ?></th>
                            <th><?php echo $this->lang->line("Ratio"); ?></th>
                          </tr>
		<?php if (!empty($data['records'])) : ?>
        <?php foreach ($data['records'] as $record) : ?>
                            <tr>
                              <td><?=$record['order_date'] ?></td>
                              <td><?=$record['policy'] ?></td>
                              <td><?=$record['insured_name'] ?></td>
                              <td><?=$record['student_id'] ?></td>
                              <td><?=$record['effective_date'] ?></td>
                              <td><?=$record['expiry_date'] ?></td>
                              <td><?php if ($record['status_id'] == 6) { echo $record['refund_date']; } ?></td>
                              <td><?=$record['total_days'] ?></td>
                              <td>$<?=number_format($record['policy_premium'],2) ?></td>
                              <td>$<?=number_format($record['net_premium'],2) ?></td>
                              <td>$<?=number_format($record['commission_amount'],2) ?></td>
                              <td><?php printf("%2.1f", $record['cal_comm_rate']); ?>%</td>
                            </tr>
        <?php endforeach; ?>
		<?php endif; ?>
                        </tbody>
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

