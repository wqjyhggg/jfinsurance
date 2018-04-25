<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!-- Report/ Sales report to jf page content -->

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
                <h3>Sales Report in period</h3>
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
                  <form method="post" action="<?php $action_url ?>" class="form-horizontal">
					<input type='hidden' name='<?php echo $csrf['name']; ?>' value='<?php echo $csrf['value']; ?>'>
					<div class="row">
                        <!-- Payment Added Date-->
                        <div class="form-group col-sm-4">
                            <!-- Payment Added Date From-->
                            <label for="payment_added_from" class="col-sm-12">Payment Added Date From</label>
                            <div class="input-group date" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd">
                            <input name="payment_added_from" class="form-control" size="16" type="text" value="<?php echo $payment_added_from ?>" >
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div>
                            <input type="hidden" id="payment_added_from" value="" />
                            <!-- Payment Added Date From End-->
                            <!-- Payment Added Date to -->
                            <label for="payment_added_to" class="col-sm-12">Payment Added Date To</label>
                            <div class="input-group date" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd">
                                <input name="payment_added_to" class="form-control" size="16" type="text" value="<?php echo $payment_added_to ?>" >
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
                            <input name="payment_date_from" class="form-control" size="16" type="text" value="<?php echo $payment_date_from ?>" >
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div>
                            <input type="hidden" id="payment_date_from" value="" />
                            <!-- Payment Update Date From End-->
                            <!-- Payment Update Date to -->
                            <label for="payment_date_to" class="col-sm-12">Payment Update Date To</label>
                            <div class="input-group date" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd">
                                <input name="payment_date_to" class="form-control" size="16" type="text" value="<?php echo $payment_date_to ?>" >
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
                    <h2>Search Result 
                    	<span class="inline-m">
	                    	<form method="get" action="<?php echo $export_list; ?>" class="form-horizontal">
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
						</span>
					</h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <div class="table-responsive">
<?php if (!empty($report_data)) :?>
                      <table class="table table-hover table-bordered">
                          <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Firstname</th>
                            <th>Lastname</th>
                            <th>Pay Type</th>
                            <th>Allowed Pay method</th>
                          </tr>
<?php foreach ($report_data as $record) :?>
                          <tr>
                            <td><?php echo $record['user_id']; ?></td>
                            <td><?php echo $record['username']; ?></td>
                            <td><?php echo $record['email']; ?></td>
                            <td><?php echo $record['firstname']; ?></td>
                            <td><?php echo $record['lastname']; ?></td>
                            <td><?php echo $record['receive_type']; ?></td>
                            <td><?php echo $record['pay_type']; ?></td>
                          </tr>
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
