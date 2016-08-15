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
                        <div class="form-group col-sm-4">
                          <!-- all report before the selected date -->
                            <label for="payment_update_date_to" class="col-sm-12">Up to Date</label>
                            <div class="input-group date" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd">
                                <input name="payment_update_date_to" class="form-control" size="16" type="text" value="<?=$payment_update_date_to ?>" >
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div>
                            <input type="hidden" id="payment_update_date_to" value="" />
                        </div>
                        <div class="form-group col-sm-4">
                          <label class="col-sm-12">Payment Method:</label>
                          <div class="input-group col-sm-12">
                              <select name="payment_method" class="form-control">
                                <option value=''>Choose Payment Method</option>
<?php foreach (array('Cash', 'Direct Deposit', 'Cheque') as $my_payment_method) : ?>
    <?php if ($payment_method == $my_payment_method) : ?>
                                <option value='<?=$my_payment_method ?>' selected>
    <?php else : ?>
                                <option value='<?=$my_payment_method ?>'>
    <?php endif; ?>
                                <?=$my_payment_method ?></option>
<?php endforeach ?>
                              </select>
                          </div>
                        </div>
                      </div>
                      <br />
                      <div class="row">
                        <!-- submit button -->
                          <div class="col-sm-12">
                            <button class="btn btn-primary pull-right">Display Commission Report</button>
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
                        <thead>
                          <tr>
                            <th>Agent</th>
                            <th>Total Balance</th>
                            <th>Payment Method <span class="fa fa-caret-down"></span></th><!-- Payment Method should be able to sort by acs -->
                          </tr>
                        </thead>
                        <tbody>
<?php foreach ($report_data as $data) : ?>
    <?php foreach ($data['records'] as $record) : ?>
                            <tr>
                            <td><?=$record['agent_name'] ?></td>
                            <td><?=$record['total_balance'] ?></td>
                            <td><?=$record['payment_method'] ?></td>
                            </tr>
    <?php endforeach; ?>
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
