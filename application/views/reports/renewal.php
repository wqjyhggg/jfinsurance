<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!-- Report/ Renewal report page content -->

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
                <h3>Renewal Report</h3>
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
                        <!-- Expiry Date-->
                        <div class="form-group col-sm-3">
                            <!-- Expiry Date From-->
                            <label for="expiry_date_from" class="col-sm-12">Expiry Date From</label>
                            <div class="input-group date" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd">
                                <input name="expiry_date_from" class="form-control" size="16" type="text" value="<?=$expiry_date_from ?>" >
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div>
                            <input type="hidden" id="expiry_date_from" value="" />
                            <!-- Expiry Date From End-->
                            <!-- Expiry Date to -->
                            <label for="expiry_date_to" class="col-sm-12">Expiry Date To</label>
                            <div class="input-group date" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd">
                                <input name="expiry_date_to" class="form-control" size="16" type="text" value="<?=$expiry_date_to ?>" >
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div>
                            <input type="hidden" id="expiry_date_to" value="" /><br/>
                            <!-- Expiry Date to End -->
                        </div>
                        <!-- Expiry Date End -->
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
                    <h2>Search Result <span class="inline-m"><?php echo $export_form; ?> </span></h2>
                    <div class="pull-right">
	                  <form method="get" action="<?php echo $mail_url ?>">
						<input type='hidden' name="agent_id" value="<?php echo $agent_id; ?>" />
						<input type='hidden' name='product_short' value="<?php echo $product_short; ?>" />
						<input type='hidden' name="application_date_from" value="<? echo $application_date_from; ?>" />
						<input type='hidden' name="application_date_from" value="<? echo $application_date_to; ?>" />
						<input type="hidden" name="create_date_from" value="<? echo $create_date_from; ?>" />
						<input type="hidden" name="create_date_to" value="<? echo $create_date_to; ?>" />
						<input type="hidden" name="effective_date_from" value="<? echo $effective_date_from; ?>" />
						<input type="hidden" name="effective_date_to" value="<? echo $effective_date_to; ?>" />
						<input type="hidden" name="expiry_date_from" value="<? echo $expiry_date_from; ?>" />
						<input type="hidden" name="expiry_date_to" value="<? echo $expiry_date_to; ?>" />
						<button class="btn btn-primary pull-right">Send Renew Email</button>
	                  </form>
                    </div>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <div class="table-responsive">
<?php if (!empty($report_data['data'])) :?>
                      <table class="table table-hover table-bordered">
                        <thead>
                          
                        </thead>
                        <tbody>
    <?php foreach ($report_data['data'] as $agent_id => $renewal_data) :?>
                            <tr><td>Expiry Date:</td> 
                                <td><?=$report_data['period']['from'] ?></td> 
                                <td>To </td>
                                <td><?=$report_data['period']['to'] ?></td>
                                <td colspan=4></td>
                                
                            </td></tr>
                            <tr><td colspan=7>Agent: <?=$renewal_data['agency'] ?></td></tr>
                            <tr>
                            <th>Policy Number</th>
                            <th>Effective Date</th>
                            <th>Expiry Date</th>
                            <th>Customer Name</th>
                            <th>Province</th>
                            <th>Phone Number</th>
                            <th>Email Address</th>
                          </tr>
        <?php foreach ($renewal_data['records'] as $record) : ?>
                            <tr>
                              <td><?=$record['policy'] ?></td>
                              <td><?=$record['effective_date'] ?></td>
                              <td><?=$record['expiry_date'] ?></td>
                              <td><?=$record['customer_name'] ?></td>
                              <td><?=$record['province'] ?></td>
                              <td><?=$record['phone'] ?></td>
                              <td><?=$record['email'] ?></td>
                            </tr>
        <?php endforeach; ?>
                            <tr><td colspan=8>&nbsp;</td></tr>
    <?php endforeach; ?>

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
