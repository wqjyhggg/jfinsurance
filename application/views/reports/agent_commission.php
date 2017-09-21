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
                <h3>Agent Commission Report   <small>--- Agent’s total commission</small></h3>
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
                        <div class="form-group col-sm-3">
                          <label class="col-sm-12">Pay Mothed:</label>
                            <div class="input-group col-sm-12">
                            <select name='receive_type' class="form-control">
                              <option value='0'> -- Select Mothed -- </option>
                              <?php foreach ($paytype_list as $name) { ?>
                              <option value='<?php echo $name; ?>' <?php echo ($receive_type == $name) ? 'selected' : ''; ?>><?php echo $name; ?></option>
                              <?php } ?>
                            </select>
                          </div>
                        </div>
                        <!-- Payment Update Date-->
                        <div class="form-group col-sm-3">
                            <!-- Payment Update Date From-->
                            <label for="payment_date_from" class="col-sm-12">Payment Update Date From</label>
                            <div class="input-group date" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd">
                            <input name="payment_date_from" class="form-control" size="16" type="text" value="<?=$payment_date_from ?>" >
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div>
                            <input type="hidden" id="payment_date_from" value="" />
                            <!-- Payment Update Date From End-->
                        </div>
                        <!-- Payment Update Date-->
                        <div class="form-group col-sm-3">
                            <!-- Payment Update Date to -->
                            <label for="payment_date_to" class="col-sm-12">Payment Update Date To</label>
                            <div class="input-group date" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd">
                                <input name="payment_date_to" class="form-control" size="16" type="text" value="<?=$payment_date_to ?>" >
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div>
                            <input type="hidden" id="payment_date_to" value="" /><br/>
                            <!-- Payment Update Date to End -->
                        </div>
                        <!-- Payment Update Date End -->
                      </div>
                      <div class="row">
                        <div class="form-group col-sm-3">
                          <label class="col-sm-12">Minimum Value:</label>
                          <div class="input-group col-sm-12">
                              <input type='number' name="minvalue" value='<?php echo $minvalue; ?>'>
                          </div>
                        </div>
                        <div class="form-group col-sm-3">
                          <label class="col-sm-12">Paid:</label>
                          <div class="input-group col-sm-12">
                              <input type='checkbox' name="paied" <?php echo ($paied) ? "checked" : ''; ?>> check for paied report
                          </div>
                        </div>
                        <!-- Product input box -->
                        <div class="form-group col-sm-3">
<?php if ($beuser['region_id'] == 0) { ?>
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
                        <!-- submit button -->
                        <div class="form-group col-sm-3">
                          <label class="col-sm-12">&nbsp;</label>
                          <div class="col-sm-12">
                            <button class="btn btn-primary pull-right">Display Commission Report</button>
                          </div>
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
                    <form method="get" action="<?php echo $export_list ?>" class="form-horizontal">
                    	<input type='hidden' name="agent_id" value="<?php echo $agent_id; ?>" />
                    	<input type='hidden' name="payment_date_from" value="<?php echo $payment_date_from; ?>" />
                    	<input type='hidden' name="payment_date_to" value="<?php echo $payment_date_to; ?>" />
                    	<input type='hidden' name="minvalue" value="<?php echo $minvalue; ?>" />
                    	<input type='hidden' name="paied" value="<?php echo $paied; ?>" />
                    	<input type='hidden' name="receive_type" value="<?php echo $receive_type; ?>" />
                    	<input type='hidden' name='region_id' value='<?php echo $region_id; ?>'>
						<input class="btn btn-info" type='submit' value="Export Xlsx" />
					</form>
					</span></h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <div class="table-responsive">
                      <div>Period : <?php echo $payment_date_from . " - " . $payment_date_to; ?></div>
                      <table class="table table-hover table-bordered">
                        <thead>
                          <tr>
                            <th>Username</th>
                            <th>Agent Name</th>
                            <th>Total Balance</th>
                            <th>Unpaid Premium</th>
                            <th>Last Paid Date</th>
                            <th>Pay Method <span class="fa fa-caret-down"></span></th>
                            <th>Note <span class="fa fa-caret-down"></span></th>
                          </tr>
                        </thead>
                        <tbody>
<?php foreach ($report_data as $record) : ?>
                            <tr>
                            <td><?php echo $record['username']; ?></td>
                            <td><?php echo $record['agent_name']; ?></td>
                            <td><?php echo $record['total_balance']; ?></td>
                            <td><?php echo $record['unpaid_premium']; ?></td>
                            <td><?php echo substr($record['last_paid'], 0, 10); ?></td>
                            <td><?php echo $record['receive_type']; ?></td>
                            <td><?php echo $record['note']; ?></td>
                            </tr>
<?php endforeach; ?>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div><!-- End List Section -->

          </div>
        </div>
        <!-- /page content -->
