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
                <h3>Receivable Report</h3>
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
                        <div class="form-group col-sm-3">
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
                                    <?php echo $agent['username'] . " ( ". $agent['full_name'] . " )"; ?>
                                </option>
<?php endforeach; ?>
                              </select>
                          </div>
                        </div>
                        <!-- Agent select box end -->

                        <!-- Product select box -->
                        <div class="form-group col-sm-3">
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
                        <!-- Product select box -->
                        <div class="form-group col-sm-3">
                          <label class="col-sm-12">Policy Status:</label>
                            <div input-group class="col-sm-12">
                              <select name="policy_status" class="form-control">
                                <option value=1>Quote</option>
                                <option value=2>Sold</option>
                                <option value=0>All</option>
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
                            <!-- Arrival Date From-->
                            <label for="arrival_date_from" class="col-sm-12">Arrival Date From</label>
                            <div class="input-group date" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd">
                                <input class="form-control" size="16" type="text" value="<?php echo $arrival_date_from; ?>" >
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div>
                            <input type="hidden" id="arrival_date_from" value="" />
                            <!-- Arrival Date From End-->
                            <!-- Create Date to -->
                            <label for="arrival_date_to" class="col-sm-12">Arrival Date To</label>
                            <div class="input-group date" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd">
                                <input name="arrival_date_to" class="form-control" size="16" type="text" value="<?=$arrival_date_to ?>" >
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div>
                            <input type="hidden" id="arrival_date_to" value="" /><br/>
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
                            <label for="expiry_date_from" class="col-sm-12">Expiry Date From</label>
                            <div class="input-group date" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd">
                                <input name="expiry_date_from" class="form-control" size="16" type="text" value="<?=$expiry_date_from ?>" >
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div>
                            <input type="hidden" id="expiry_date_from" value="" />
                            <!-- Payment Update Date From End-->
                            <!-- Payment Update Date to -->
                            <label for="expiry_date_to" class="col-sm-12">Expiry Date To</label>
                            <div class="input-group date" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd">
                                <input name="expiry_date_to" class="form-control" size="16" type="text" value="<?=$expiry_date_to ?>" >
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div>
                            <input type="hidden" id="expiry_date_to" value="" /><br/>
                            <!-- Payment Update Date to End -->
                        </div>
                        <!-- Payment Update Date End -->
                      </div>
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
                    <h2>Search Result <span class="inline-m"><?php echo $export_form; ?> </span></h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">

                    <div class="table-responsive">
<?php if (!empty($report_data['data'])) : ?>
                       <div>
                         JF Insurance Agency Group Inc. <br>
                         15 Wertheim court, Suite 501, Richmond Hill, ON, L4B 3H7 <br>
                         Tel: 905-707-1512 Fax: 905-707-1513 Toll free: 1-877-832-5541<br>
                         Invoice Statement<br>
                         For Policy of
                         From:<?=$report_data['period']['from'] ?>
                         To:<?=$report_data['period']['to'] ?><br><br>
                       </div>
                      <table class="table table-hover table-bordered">
    <?php foreach ($report_data['data'] as $user_id => $data) :?>
                        <thead>
                        <tr><td colspan=10>
                          Bill to:
                          <?=$data['agency']['agent_name'] ?><br>
                          <?=$data['agency']['address'] ?><br>
                          <?=$data['agency']['province'] ?>,&nbsp;&nbsp; <?=$data['agency']['postal_code'] ?><br><br>
                          Premium collected on behalf of JF Insurance Agency Inc: $<?=$data['agency']['outstanding'] ?><br>
                          Less Administration Fee for handaling the following policies: $<?=$data['agency']['commission'] ?><br>
                          Net payable to JF Insurance Ageny Inc.: $<?=$data['agency']['payable_to_jf'] ?>
                        </td></tr>
                        </thead>
                        <tbody>
                          <tr>
                            <th>Pruchase Date</th>
                            <th>Policy Number</th>
                            <th>Customer Name</th>
                            <th>Effective Date</th>
                            <th>Expiry Date</th>
                            <th>Trip Length</th>
                            <th>Premium</th>
                            <th>Net</th>
                            <th>Commission</th>
                            <th>Ratio</th>
                          </tr>
		<?php if (!empty($data['records'])) : ?>
        <?php foreach ($data['records'] as $record) : ?>
                            <tr>
                              <td><?=$record['order_date'] ?></td>
                              <td><?=$record['policy'] ?></td>
                              <td><?=$record['insured_name'] ?></td>
                              <td><?=$record['effective_date'] ?></td>
                              <td><?=$record['expiry_date'] ?></td>
                              <td><?=$record['total_days'] ?></td>
                              <td>$<?=number_format($record['policy_premium'],2) ?></td>
                              <td>$<?=number_format($record['pa_amount'],2) ?></td>
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

