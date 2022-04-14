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

            <form method="post" action="<?= $action_url ?>" class="form-horizontal">
              <input type='hidden' name='<?php echo $csrf['name']; ?>' value='<?php echo $csrf['value']; ?>'>
              <div class="row">
                <!-- Product input box -->
                <div class="form-group col-sm-12">
                  <label class="col-sm-12">Product:</label>
                  <div class="input-group col-sm-12">
                    <?php foreach ($product_list as $product) : ?>
                    <?php     if (empty($product["calculate"])) continue; ?>
                      <input type='checkbox' name='product_short[<?php echo $product["product_short"]; ?>]' <?php if (in_array($product["product_short"], $product_short)) { ?>checked<?php } ?>> <?php echo $product["product_short"]; ?> &nbsp; &nbsp; 
                    <?php endforeach; ?>
                  </div>
                </div>
                <!-- Product input box end -->
              </div>

              <div class="row">
                <!-- Payment Added Date-->
                <div class="form-group col-sm-3">
                  <!-- Payment Added Date From-->
                  <label for="payment_added_from" class="col-sm-12">Sold Date From</label>
                  <div class="input-group date" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd">
                    <input name="payment_added_from" class="form-control" size="16" type="text" value="<?php echo $payment_added_from ?>">
                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                  </div>
                  <input type="hidden" id="payment_added_from" value="" />
                  <!-- Payment Added Date From End-->
                </div>
                <div class="form-group col-sm-3">
                  <!-- Payment Added Date to -->
                  <label for="payment_added_to" class="col-sm-12">Sold Date To</label>
                  <div class="input-group date" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd">
                    <input name="payment_added_to" class="form-control" size="16" type="text" value="<?php echo $payment_added_to ?>">
                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                  </div>
                  <input type="hidden" id="payment_added_to" value="" /><br />
                  <!-- Payment Update Date to End -->
                </div>
                <!-- Payment Added Date End -->
                <div class="form-group col-sm-3">
                  <!-- Earned To date -->
                  <label for="earned_to" class="col-sm-12">Earned To Date</label>
                  <div class="input-group date" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd">
                    <input name="earned_to" class="form-control" size="16" type="text" value="<?php echo $earned_to ?>">
                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                  </div>
                  <input type="hidden" id="payment_added_to" value="" /><br />
                  <!-- Earned To date -->
                </div>
                <!-- export button -->
                <div class="form-group col-sm-1 text-center">
                  <label class="col-sm-12">&nbsp;</label>
                  <input type="submit" name="export" value="Export" class="btn btn-primary" />
                </div>
                <!-- export button -->
                <!-- submit button -->
                <div class="form-group col-sm-1">
                  <label class="col-sm-12">&nbsp;</label>
                  <input type="submit" name="submit" value="Display" class="btn btn-primary" />
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
            <h2>Search Result</h2>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <div class="table-responsive limit-tableh">
              <?php if (!empty($report_data)) : ?>
	      <?php
              $status_list = array(
                1 => "Quote",
                2 => "Sold",
                3 => "Paid",
                4 => "Claimed",
                5 => "Cancel",
                6 => "Refund",
                7 => "Changed",
              );
              ?>
                <table class="table table-hover table-bordered">
                  <thead>
                    <tr>
                      <th>Policy Number</th>
                      <th>First Name</th>
                      <th>Last Name</th>
                      <th>Province</th>
                      <th>Status</th>
                      <th>Sold Date</th>
                      <th>Payment Date</th>
                      <th>Effective Date</th>
                      <th>Expiry Date</th>
                      <th>Number of Days</th>
                      <th>Days of Used</th>
                      <th>Sum Insured</th>
                      <th>Deductible Amount</th>
                      <th>Daily Rate</th>
                      <th>Discounted Amount</th>
                      <th>Total</th>
                      <th>Earned</th>
                      <th>Unearned</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php $total = $tearned = 0; $solddate = ''; ?>
                    <?php foreach ($report_data as $record) : ?>
                      <?php if ($record['status_id'] == 6) {$dte = strtotime($record['expiry_date']); $dts = strtotime($record['effective_date']); $record['totaldays'] = round(($dte-$dts)/(60 * 60 * 24)) + 1; }; ?>
                      <?php $premium = floatval($record['dailyrate'] * $record['totaldays']); ?>
                      <?php $dispremium = abs($premium) - abs($record['premium']); ?>
                      <?php if ($dispremium < 0) $dispremium = 0; ?>
                      <?php if (abs($premium) > abs($record['premium'])) { $premium = floatval($record['premium']); } ?>
                      <?php $earned = ($record['days_used']>0)? ($premium*floatval($record['days_used'])/floatval($record['totaldays'])) : 0; ?>
                      <?php $unearned = number_format($premium - $earned, 2); ?>
                      <?php $total += $premium; $tearned += $earned; ?>
                      <?php if ($record['ishead']==1) { $solddate = substr($record['add_time'],0,10); } ?>
                      <?php $dispremium = number_format($dispremium,2); ?>
                      <?php $premium = number_format($premium,2); ?>
                      <?php $earned = number_format($earned,2); ?>
                      <tr>
                        <td><?= $record['policy'] ?></td>
                        <td><?= $record['firstname'] ?></td>
                        <td><?= $record['lastname'] ?></td>
                        <td><?= $record['province2'] ?></td>
                        <td><?= $status_list[$record['status_id']] ?></td>
                        <td><?= $solddate ?></td>
                        <td><?= substr($record['add_time'],0,10) ?></td>
                        <td><?= $record['effective_date'] ?></td>
                        <td><?= $record['expiry_date'] ?></td>
                        <td><?= $record['totaldays'] ?></td>
                        <td><?= ($record['days_used']>0)?$record['days_used']:0 ?></td>
                        <td>$<?= $record['sum_insured'] ?></td>
                        <td>$<?= $record['deductible_amount'] ?></td>
                        <td>$<?= $record['dailyrate'] ?></td>
                        <td>$<?= $dispremium ?></td>
                        <td>$<?= $premium ?></td>
                        <td>$<?= $earned ?></td>
                        <td>$<?= $unearned ?></td>
                      </tr>
                    <?php endforeach; ?>
                    <tr>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td>$<?= number_format($total,2) ?></td>
                      <td>$<?= number_format($tearned,2) ?></td>
                      <td>$<?= number_format($total-$tearned,2) ?></td>
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
