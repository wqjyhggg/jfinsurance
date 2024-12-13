<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<!-- Report/ Sales report to insurer page content -->

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
        <h3>Sales Report to Insurer</h3>
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
              <input type='hidden' name='<?php echo $csrf['name']; ?>' value='<?php echo $csrf['value']; ?>' />
              <div class="row">
                <div class="form-group col-sm-3">
                  <label class="col-sm-12"><?php echo $this->lang->line("Agent"); ?>:</label>
                  <div class="input-group col-sm-12">
                    <select name="agent_id" class="form-control">
                      <option value=0><?php echo $this->lang->line("Choose Agent"); ?></option>
                      <?php foreach ($user_list as $agent) { ?>
                      <option value="<?=$agent['user_id'] ?>"  <?php if ($agent_id == $agent['user_id']) { echo "selected"; } ?>>
                        <?php echo $agent['username'] . " ( ". $agent['full_name'] . " )"; ?>
                      </option>
                      <?php } ?>
                    </select>
                  </div>
                </div>
                <div class="form-group col-sm-3">
                  <label for="payment_added_from" class="col-sm-12"><?php echo $this->lang->line("Payment Added Date From"); ?></label>
                  <div class="input-group date" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd">
                    <input name="payment_added_from" class="form-control" size="16" type="text" value="<?php echo $payment_added_from ?>" >
                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                  </div>
                  <input type="hidden" id="payment_added_from" value="" />
                </div>
                <div class="form-group col-sm-3">
                  <label for="payment_date_from" class="col-sm-12"><?php echo $this->lang->line("Payment Update Date From"); ?></label>
                  <div class="input-group date" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd">
                  <input name="payment_date_from" class="form-control" size="16" type="text" value="<?php echo $payment_date_from ?>" >
                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                  </div>
                  <input type="hidden" id="payment_date_from" value="" />
                </div>
              </div>
              <div class="row">
                <div class="form-group col-sm-3">
                  <label class="col-sm-12"><?php echo $this->lang->line("Product"); ?>:</label>
                    <div class="input-group col-sm-12">
                      <select name='product_short' class="form-control">
                        <option value=""><?php echo $this->lang->line("Choose Product"); ?></option>
                        <?php foreach ($product_list as $product) : ?>
                        <option value="<?=$product['product_short'] ?>" <?php if ($product_short == $product['product_short']) { echo "selected"; }?>>
                          <?php echo $product['full_name']." (".$product['product_short'].")"; ?>
                        </option>
                        <?php endforeach; ?>
                      </select>
                  </div>
                </div>
                <div class="form-group col-sm-3">
                  <label for="payment_added_to" class="col-sm-12"><?php echo $this->lang->line("Payment Added Date To"); ?></label>
                  <div class="input-group date" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd">
                    <input name="payment_added_to" class="form-control" size="16" type="text" value="<?php echo $payment_added_to ?>" >
                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                  </div>
                  <input type="hidden" id="payment_added_to" value="" />
                </div>
                <div class="form-group col-sm-3">
                  <label for="payment_date_to" class="col-sm-12"><?php echo $this->lang->line("Payment Update Date To"); ?></label>
                  <div class="input-group date" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd">
                    <input name="payment_date_to" class="form-control" size="16" type="text" value="<?php echo $payment_date_to ?>" >
                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                  </div>
                  <input type="hidden" id="payment_date_to" value="" />
                </div>
              </div>
              <div class="row">
                <div class="form-group col-sm-3">
                  <?php if ($beuser['region_id'] == 0) { ?>
                  <label class="col-sm-12"><?php echo $this->lang->line("Region"); ?>:</label>
                  <div class="input-group col-sm-12">
                    <select name='region_id' class="form-control">
                      <option value='0'> -- <?php echo $this->lang->line("All Region"); ?> -- </option>
                      <?php foreach ($regions as $key => $name) { ?>
                      <option value='<?php echo $key; ?>' <?php echo ($region_id == $key) ? 'selected' : ''; ?>><?php echo $name; ?></option>
                      <?php } ?>
                    </select>
                  </div>
                  <?php } else { ?>
                  <input type='hidden' name='region_id' value='<?php echo $beuser['region_id']; ?>'>
                  <?php } ?>
                </div>
                <div class="form-group col-sm-3">
                </div>
                <div class="form-group col-sm-3">
                  <div class="col-sm-12" style="'margin-top: 16px;" >
                    <input type="submit" name="submit"  class="btn btn-primary pull-right" value="<?php echo $this->lang->line("Display Sales Report"); ?>" />
                  </div>
                </div>
                <div class="form-group col-sm-3">
                  <div class="col-sm-12" style="'margin-top: 16px;" >
                    <input type="submit" name="request" class="btn btn-primary pull-right" value="<?php echo $this->lang->line("Request Sales Report"); ?>" />
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div><!-- End Filter Section -->
    <?php if ($download_request && (sizeof($download_request) > 0)) : ?>
    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <h2>Report Request</h2>
      </div>
      <div class="col-md-12 col-sm-12 col-xs-12">
        <ol>
        <?php foreach ($download_request as $req) : ?>
          <li>
          <?php echo $req["require_time"] . " : "?>
          <?php if ($req["is_done"]) : ?>
            <a href="<?php echo $download_url . "/" . $req["filepath"]; ?>" style="color: #23527c; text-decoration: underline;">download <?php echo basename($req["filepath"]); ?></a>
          <?php endif; ?>
          <?php echo $req["para_data"]; ?>
          </li>
          <?php endforeach; ?>
        </ol>
      </div>
    </div><!-- Jobs List -->
    <?php endif; ?>
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
                      <button class="btn btn-primary pull-right">Export Report</button>
                    </div>
                    <!-- submit button -->
                  </div>
                </form>
              </span></h2>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <div class="table-responsive limit-tableh">
              <?php if (!empty($report_data)) : ?>
                <table class="table table-hover table-bordered">
                  <thead>
                    <tr>
                      <th>Policy Number</th>
                      <th>First Name</th>
                      <th>Last Name</th>
                      <th>Gender</th>
                      <th>Birth Date</th>
                      <th>Address</th>
                      <th>City</th>
                      <th>Province</th>
                      <th>Post Code</th>
                      <th>Effective Date</th>
                      <th>Expiry Date</th>
                      <th>Number of Days</th>
                      <th>Sum Insured</th>
                      <th>Deductible Amount</th>
                      <th>Daily Rate</th>
                      <th>Policy Premium</th>
                      <!--th>Commission Rate to JF</th-->
                      <th>Merchant Fee(Credit Card Fee)%</th>
                      <th>Claims Handling</th>
                      <th>Commission Amount</th>
                      <th>Merchant Fee(Credit Card Fee)</th>
                      <th>Claims Handling Fee</th>
                      <th>Net Premium</th>
                      <th>Total Compensation Rate(%)</th>
                      <th>Total Compensation Amount</th>
                      <th>Total Members</th>
                      <!--th>Coverage</th-->
                      <th>Purchase Date</th>
                      <th>Refund Date</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php $premium_total = 0; ?>
                    <?php foreach ($report_data as $record) : ?>
                      <?php $premium_total += $record['policy_premium']; ?>
                      <tr>
                        <td><?= $record['policy'] ?></td>
                        <td><?= $record['firstname'] ?></td>
                        <td><?= $record['lastname'] ?></td>
                        <td><?= $record['gender'] ?></td>
                        <td><?= $record['birthday'] ?></td>
                        <td><?= $record['address'] ?></td>
                        <td><?= $record['city'] ?></td>
                        <td><?= $record['province'] ?></td>
                        <td><?= $record['postcode'] ?></td>
                        <td><?= $record['effective_date'] ?></td>
                        <td><?= $record['expiry_date'] ?></td>
                        <td><?= $record['total_days'] ?></td>
                        <td>$<?= $record['sum_insured'] ?></td>
                        <td>$<?= $record['deductible_amount'] ?></td>
                        <td>$<?= $record['daily_rate'] ?></td>
                        <td>$<?= $record['policy_premium'] ?></td>
                        <!--td><?= $record['commission_rate_jf'] ?>%</td-->
                        <td><?= $record['merchant_fee_per'] ?>%</td>
                        <td><?= $record['claims_handling_fee_per'] ?>%</td>
                        <td>$<?= $record['commission_amount'] ?></td>
                        <td>$<?= $record['merchant_fee'] ?></td>
                        <td>$<?= $record['claims_handling_fee'] ?></td>
                        <td>$<?= $record['net_premium'] ?></td>
                        <td><?= $record['total_compensation_per'] ?>%</td>
                        <td>$<?= $record['total_compensation'] ?></td>
                        <td><?= $record['customer_cnt'] ?></td>
                        <!--td><?= $record['coverage'] ?></td-->
                        <td><?= $record['added'] ?></td>
                        <td><?= ($record['status_id'] == 6) ? $record['refund_date'] : '' ?></td>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
                Total Premium: <?php echo $premium_total; ?>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
    </div><!-- End List Section -->

  </div>
</div>
<!-- /page content -->