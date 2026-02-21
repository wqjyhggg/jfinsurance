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
        <h3>OR Monthly Premium Report</h3>
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
							<?php if (0) { ?>
              <div class="row">
                <div class="form-group col-sm-12">
                  <label class="col-sm-12">Product:</label>
                  <div class="input-group col-sm-12">
                    <?php foreach ($product_list as $product) : ?>
                    <?php     if (empty($product["calculate"])) continue; ?>
                      <input type='checkbox' name='product_short[<?php echo $product["product_short"]; ?>]' <?php if (in_array($product["product_short"], $product_short)) { ?>checked<?php } ?>> <?php echo $product["product_short"]; ?> &nbsp; &nbsp; 
                    <?php endforeach; ?>
                  </div>
                </div>
              </div>
							<?php } ?>

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
              </div>

              <div class="row">
                <div class="form-group col-sm-6 text-center">
                </div>
                <!-- export button -->
                <div class="form-group col-sm-2 text-center">
                  <input type="submit" name="export" value="Export" class="btn btn-primary" />
                </div>
                <!-- submit button -->
                <div class="form-group col-sm-2 text-center">
                  <input type="submit" name="submit" value="Display" class="btn btn-primary" />
                </div>
                <?php if ($beuser["user_group_id"] == 1) { ?>
                <div class="form-group col-sm-2 text-center">
                  <input type="submit" name="request" value="Request" class="btn btn-primary" />
                </div>
                <?php } ?>
              </div>
            </form>

          </div>
        </div>
      </div>
    </div><!-- End Filter Section -->
    <!-- Jobs List -->
    <?php if (($beuser["user_group_id"] == 1) && $download_request && (sizeof($download_request) > 0)) { ?>
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
    <?php } ?>
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
                8 => "Adjust",
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
                      <th>Quantity</th>
                      <th>Discounted Amount</th>
                      <th>Total</th>
                      <th>Earned</th>
                      <th>Unearned</th>
                      <th>Product</th>
                      <th>Plan Type</th>
                      <th>Total Premium</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php $total = $tearned = 0; $solddate = ''; ?>
                    <?php foreach ($report_data as $record) : ?>
                      <?php
                        if ($record['ishead']>0) {
                          $solddate = substr($record['add_time'],0,10);
                        }
                        if ($record['status_id'] == 6) {
                          $dte = strtotime($record['expiry_date']);
                          $dts = strtotime($record['effective_date']);
                          $record['totaldays'] = round(($dte-$dts)/(60 * 60 * 24)) + 1; 
                        }
                        if (abs($record['premium']) <= 25) {
                          $record['premium'] = floatval($record['dailyrate'] * $record['totaldays']);
                        }
                        if ($record['days_used'] >= $record['totaldays']) {
                          $earned = $record['premium'];
                          $unearned = 0;
                        } else if ($record['days_used'] > 0) {
                          $earned = $record['premium']*$record['days_used']/$record['totaldays'];
                          $unearned = $record['premium'] - $earned;
                        } else {
                          $earned = 0;
                          $unearned = $record['premium'];
                        }

												if ($record['ishead']==2) {
                          $earned = 0;
                          $unearned = 0;
												}

                        $total += $record['premium'];
                        $tearned += $earned;
                        $discount = 0;
                        // if ($record['totaldays'] >= 365) {
                        //   $discount = $record['totaldays'] * $record['dailyrate'] - $record['premium'];
                        //   if ($discount < 5) {
                        //     $discount = 0;
                        //   }
                        // }
                      ?>
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
                        <td>$<?= number_format($record['deductible_amount'],2) ?></td>
                        <td>$<?= number_format($record['dailyrate'],2) ?></td>
                        <td><?= $record['customer_cnt'] ?></td>
                        <td>$<?= number_format($discount,2) ?></td>
                        <td>$<?= number_format($record['premium'],2) ?></td>
                        <td>$<?= number_format($earned,2) ?></td>
                        <td>$<?= number_format($unearned,2) ?></td>
                        <td><?= $record['product_short'] ?></td>
                        <td>Monthly</td>
                        <td>$<?= number_format($record['total_premium'],2) ?></td>
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
                      <td>&nbsp;</td>
                      <td>$<?= number_format($total,2) ?></td>
                      <td>$<?= number_format($tearned,2) ?></td>
                      <td>$<?= number_format($total-$tearned,2) ?></td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
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
