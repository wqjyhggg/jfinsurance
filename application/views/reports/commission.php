<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!-- Report/ Commission report page content -->

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
                <h3>Commission Report</h3>
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

                    <form method="post" action="<?=$action_url ?>" class="form-horizontal" id='searchform'>
                      <input type='hidden' name='<?php echo $csrf['name']; ?>' value='<?php echo $csrf['value']; ?>'>
                      <div class="row">
                      <!-- Agent select box -->
                        <div class="form-group col-sm-4">
                          <label class="col-sm-12">Agent:</label>
                          <div class="input-group col-sm-12">
                              <select name="agent_id" class="form-control">
                                <option value=0>Choose Agent</option>
<?php foreach ($user_list as $agent) : ?>
    <?php if ($agent_id == $agent['user_id']) : ?>
                                <option value="<?php echo $agent['user_id']; ?>"  selected>
    <?php else : ?>
                                <option value="<?php echo $agent['user_id']; ?>" >
    <?php endif; ?>
                                    <?php echo $agent['username'] . " ( ". $agent['full_name'] . " )"; ?>
                                </option>
<?php endforeach; ?>
                              </select>
                              <input type='checkbox' name='asbroker' value='1' <?php echo ($asbroker ? 'checked' : ''); ?>> As brokerage
                          </div>
                        </div>
                        <!-- Agent select box end -->

                        <!-- Product select box -->
                        <div class="form-group col-sm-4">
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
<?php if ($beuser['region_id'] == 0) { ?>
                        <!-- Product input box -->
                        <div class="form-group col-sm-4">
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
                      </div>

                      <div class="row">
                        <!-- Payment Added Date-->
                        <div class="form-group col-sm-4">
                            <!-- Payment Added Date From-->
                            <label for="payment_added_from" class="col-sm-12">Payment Added Date From</label>
                            <div class="input-group date" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd">
                            <input name="payment_added_from" id="payment_added_from" class="form-control" size="16" type="text" value="<?php echo $payment_added_from ?>" >
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div>
                            <!-- Payment Added Date From End-->
                            <!-- Payment Added Date to -->
                            <label for="payment_added_to" class="col-sm-12">Payment Added Date To</label>
                            <div class="input-group date" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd">
                                <input name="payment_added_to" id="payment_added_to" class="form-control" size="16" type="text" value="<?php echo $payment_added_to ?>" >
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div>
                            <!-- Payment Update Date to End -->
                        </div>
                        <!-- Payment Added Date End -->
                        <!-- Payment Update Date-->
                        <div class="form-group col-sm-4">
                            <!-- Payment Update Date From-->
                            <label for="payment_date_from" class="col-sm-12">Payment Update Date From</label>
                            <div class="input-group date" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd">
                            <input name="payment_date_from" id="payment_date_from" class="form-control" size="16" type="text" value="<?php echo $payment_date_from ?>" >
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div>
                            <!-- Payment Update Date From End-->
                            <!-- Payment Update Date to -->
                            <label for="payment_date_to" class="col-sm-12">Payment Update Date To</label>
                            <div class="input-group date" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd">
                                <input name="payment_date_to" id="payment_date_to" class="form-control" size="16" type="text" value="<?php echo $payment_date_to ?>" >
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div>
                            <!-- Payment Update Date to End -->
                        </div>
                        <!-- Payment Update Date End -->
                        <div class="form-group col-sm-4">
                            <label for="ispaid" class="col-sm-12">&nbsp;</label>
                            <span class='col-sm-12 text-center'><input type='checkbox' name='ispaid' value='1' <?php echo ($ispaid ? 'checked' : ''); ?>> Is paid</span>
                        </div>
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
						<span class="inline-m" style="padding-left: 20px;">
							<div class="row">
	                          <div class="col-sm-6">
		                    	<form method="get" action="<?php echo $export_list; ?>" class="form-horizontal">
		                    		<input type='hidden' name="agent_id" value="<?php echo $agent_id; ?>">
		                    		<input type='hidden' name="product_short" value="<?php echo $product_short; ?>">
		                    		<input type='hidden' name="region_id" value="<?php echo $region_id; ?>">
		                    		<input type='hidden' name="payment_added_from" value="<?php echo $payment_added_from; ?>">
		                    		<input type='hidden' name="payment_added_to" value="<?php echo $payment_added_to; ?>">
		                    		<input type='hidden' name="payment_date_from" value="<?php echo $payment_date_from; ?>">
		                    		<input type='hidden' name="payment_date_to" value="<?php echo $payment_date_to; ?>">
		                            <button class="btn btn-primary">Export xlsx</button>
				                </form>
				              </div>
	                          <div class="col-sm-6">
		                    	<form method="get" action="<?php echo $export_pdf; ?>" class="form-horizontal" target="_blank">
		                    		<input type='hidden' name="agent_id" value="<?php echo $agent_id; ?>">
		                    		<input type='hidden' name="product_short" value="<?php echo $product_short; ?>">
		                    		<input type='hidden' name="region_id" value="<?php echo $region_id; ?>">
		                    		<input type='hidden' name="payment_added_from" value="<?php echo $payment_added_from; ?>">
		                    		<input type='hidden' name="payment_added_to" value="<?php echo $payment_added_to; ?>">
		                    		<input type='hidden' name="payment_date_from" value="<?php echo $payment_date_from; ?>">
		                    		<input type='hidden' name="payment_date_to" value="<?php echo $payment_date_to; ?>">
		                            <button class="btn btn-primary">Export PDF</button>
				                </form>
				              </div>
							</div>
						</span>
					  </h2>
                    <div class="clearfix"></div>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
<?php if (!empty($report_data)) : ?>
					<input type='checkbox' name='payment_all'> Check All
		            <button class="btn btn-primary btn-xs makepay">Makepay Commission</button>
                    <div class="table-responsive">
    <?php $total_a_premium = 0; $total_a_commission = 0; $unpaid_a_premium = 0; ?>
    <?php foreach ($report_data as $user_id => $data) :?>
                      <table class="table table-hover table-bordered">
                        <tbody>
        <?php if (empty($asbroker)) : ?>
                          <tr>
                          	<td colspan='8'>
								<?php if ($data ['agent'] ['receive_type'] == 'Cheque') { ?>
								<div>
									<div style='display: inline-flex;'>To:</div>
									<div style='display: inline-flex; padding-left: 12px;'>
										<?php echo $data['agent']['firstname'] . " " . $data['agent']['lastname']; ?><br />
										<?php echo $data['agent']['mail_address']; ?><br>
										<?php echo $data ['agent'] ['mail_city'] . "," . $data ['agent'] ['mail_province2']; ?><br>
										<?php echo $data ['agent'] ['mail_postcode']; ?><br><br>
									</div>
								</div>
								<?php } ?>
                          		Agent Name: <?php echo $data['agent']['firstname'] . " " . $data['agent']['lastname']; ?><br />
                          		Payment Method: <?php echo $data['agent']['receive_type']; ?><br />
								<?php
									if ($data['agent']['receive_type'] == 'Deposit') {
										echo "Pay to: " . $data['agent']['note'] . "<br />";
										echo "E-Mail Address: " . $data['agent']['email'];
									} else if ($data['agent']['receive_type'] == 'Cheque') {
										echo "Pay to: " . $data['agent']['note'] . "<br />";
									} else { // Cash
									}
								?>                          		  
                          	</td>
                          	<td colspan='4'>
                          		Payment Period: <?php echo $payment_added_from . " - " . $payment_added_to; ?>
                          	</td>
                          </tr>
        <?php else : ?>
        <?php    if (isset($report_data['asbroker'])) : ?>
                          <tr>
                          	<td colspan='8'>
								<?php if ($report_data['asbroker']['receive_type'] == 'Cheque') { ?>
								<div>
									<div style='display: inline-flex;'>To:</div>
									<div style='display: inline-flex; padding-left: 12px;'>
										<?php echo $report_data['asbroker']['firstname'] . " " . $report_data['asbroker']['lastname']; ?><br />
										<?php echo $report_data['asbroker']['mail_address']; ?><br>
										<?php echo $report_data['asbroker']['mail_city'] . "," . $report_data['asbroker']['mail_province2']; ?><br>
										<?php echo $report_data['asbroker']['mail_postcode']; ?><br><br>
									</div>
								</div>
								<?php } ?>
                          		Brokerage Name: <?php echo $report_data['asbroker']['firstname'] . " " . $report_data['asbroker']['lastname']; ?><br />
                          		Payment Method: <?php echo $report_data['asbroker']['receive_type']; ?><br />
								<?php
									if ($report_data['asbroker']['receive_type'] == 'Deposit') {
										echo "Pay to: " . $report_data['asbroker']['note'] . "<br />";
										echo "E-Mail Address: " . $report_data['asbroker']['email'];
									} else if ($report_data['asbroker']['receive_type'] == 'Cheque') {
										echo "Pay to: " . $report_data['asbroker']['note'] . "<br />";
									} else { // Cash
									}
								?>                          		  
                          	</td>
                          	<td colspan='4'>
                          		Payment Period: <?php echo $payment_added_from . " - " . $payment_added_to; ?>
                          	</td>
                          </tr>
        <?php        unset($report_data['asbroker']); ?>
        <?php    endif; ?>
                          <tr>
                          	<td colspan='12'>
                          		Agent Name: <?php echo $data['agent']['firstname'] . " " . $data['agent']['lastname']; ?><br />
                          	</td>
                          </tr>
        <?php endif; ?>
                          <tr>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                            <th>Payment Date</th>
                            <th>Policy Number</th>
                            <th>Customer Name</th>
                            <th>Effective Date</th>
                            <th>Expiry Date</th>
                            <th>Trip Length</th>
                            <th>Premium Amount</th>
                            <th>Premium Payment</th>
                            <th>Commission Rate</th>
                            <th>Commission Amount</th>
                          </tr>
        <?php $cnt = 1; $total_premium = 0; $total_commission = 0; $unpaid_premium = 0; ?>
        <?php foreach ($data['data'] as $record) : ?>
        <?php $total_a_premium += $record['premium']; $total_a_commission += $record['amount']; $unpaid_a_premium += ($record['premiumispaid']) ? 0 : $record['premium']; ?>
        <?php $total_premium += $record['premium']; $total_commission += $record['amount']; $unpaid_premium += ($record['premiumispaid']) ? 0 : $record['premium']; ?>
                            <tr>
                              <td><input type='checkbox' name='payment_id[]' data-id='<?php echo $record['payment_id']; ?>'></td>
                              <td><?php echo $cnt++; ?></td>
                              <td><?php echo substr($record['added'], 0, 10); ?></td>
                              <td><?php echo $record['policy']; ?></td>
                              <td><?php echo $record['customer_name']; ?></td>
                              <td><?php echo $record['effective_date']; ?></td>
                              <td><?php echo $record['expiry_date']; ?></td>
                              <td><?php echo $record['total_days']; ?></td>
                              <td>$<?php echo number_format($record['premium'], 2); ?></td>
                              <td><?php echo ($record['premiumispaid']) ? "Paid" : '-'; ?></td>
                              <td><?php echo $record['rate']; ?>%</td>
                              <td>$<?php echo number_format($record['amount'], 2); ?></td>
                            </tr>
        <?php endforeach; ?>
        <?php if (empty($asbroker)) : ?>
                            <tr>
                              <td>&nbsp;</td>
                              <td><B>TOTAL</B></td>
                              <td>&nbsp;</td>
                              <td>&nbsp;</td>
                              <td>&nbsp;</td>
                              <td>&nbsp;</td>
                              <td>&nbsp;</td>
                              <td>&nbsp;</td>
                              <td>$<?php echo number_format($total_premium, 2); ?></td>
                              <td>&nbsp;</td>
                              <td>&nbsp;</td>
                              <td>$<?php echo number_format($total_commission, 2); ?></td>
                            </tr>
                            <tr style='background:#eee;'>
                              <td>&nbsp;</td>
                              <td colspan='2'><B>Total Commission for Above</B></td>
                              <td colspan='9'>$<?php echo number_format($total_commission, 2); ?></td>
                            </tr>
                            <tr style='background:#eee;'>
                              <td>&nbsp;</td>
                              <td colspan='2'><B>Unpaid Premium</B></td>
                              <td colspan='9'>$<?php echo number_format($unpaid_premium, 2); ?></td>
                            </tr>
                            <tr style='background:#eee;'>
                              <td>&nbsp;</td>
                              <td colspan='2'><B>Balance</B></td>
                              <td colspan='9'>$<?php echo number_format($total_commission - $unpaid_premium, 2); ?></td>
                            </tr>
                            <tr><td colspan='11'></td></tr>
        <?php endif; ?>
                        </tbody>
                      </table>
    <?php endforeach; ?>
                    </div>
    <?php if (!empty($asbroker)) : ?>
                    <div class="table-responsive">
                    	<div class="row">
                    		<div class="col-sm-2 text-right"><B>Total Commission : </B></div>
                            <div class="col-sm-10">$<?php echo number_format($total_commission, 2); ?></div>
                        </div>
                    	<div class="row">
                    		<div class="col-sm-2 text-right"><B>Unpaid Premium : </B></div>
                            <div class="col-sm-10">$<?php echo number_format($unpaid_premium, 2); ?></div>
                        </div>
                    	<div class="row">
                    		<div class="col-sm-2 text-right"><B>Balance : </B></div>
                            <div class="col-sm-10">$<?php echo number_format($total_commission - $unpaid_premium, 2); ?></div>
                        </div>
                    </div>
    <?php endif; ?>
<?php endif; ?>
                  </div>
                </div>
              </div>
            </div><!-- End List Section -->

          </div>
        </div>
        <!-- /page content -->
<script>
$( document ).ready(function() {
	$("input[name='payment_all']").change(function() {
		if ( $('input[name="payment_all"]').is(':checked') ) {
			$('input[name="payment_id[]"]').prop('checked', true);
		} else {
			$('input[name="payment_id[]"]').prop('checked', false);
		}
	});

	$(".makepay").on('click', function() {
		$("#searchform").submit(function(e) {
			$('input[name="payment_id[]"]:checked').each(function() {
				$('<input />').attr('type', 'hidden').attr('name', "payment_id[]").attr('value', $(this).attr('data-id')).appendTo('#searchform');
			})
			$('<input />').attr('type', 'hidden').attr('name', "makepay").attr('value', "1").appendTo('#searchform');
			return true;
		});

		$("#searchform").submit();
	});
})
</script>
