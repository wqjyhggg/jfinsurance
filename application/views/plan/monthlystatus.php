<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$payStatusArr = [
	'1'=>"Paid",
	'0'=>"Unpaid",
	'-1'=>"Cancelled",
	'-2'=>"Failed Payment",
	'-3'=>"Terminated",
];
?>
<!-- Plan List page content -->
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
				<h3>Monthly Plan Search List</h3>
			</div>
		</div>
		<div class="clearfix"></div>
			<!-- Create Section -->
		<div class="row" id="create-div">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="x_panel">
					<!-- <div class="x_title"></div> -->
					<div class="x_content">
						<?php if (!empty($error_message)) { echo $error_message . "<br>"; } ?>
						<form action='<?php echo $base_url; ?>' method='POST' class="form-horizontal">
							<div class="row">
								<div class="form-group col-sm-3">
									<label class="col-sm-12">Status</label>
									<select name='paid' class="form-control">
										<option value=''></option>
										<option value='-2'>Failed Payment</option>
										<option value='-1'>Cancelled</option>
										<option value='-3'>Terminated</option>
									</select>				                
								</div>
								<div class="form-group col-sm-3">
									<label class="col-sm-12"><?php echo $this->lang->line("Policy Number"); ?>:</label>
									<div class="input-group col-sm-12">
										<input type="text" name='policy' value='<?php echo $html_model->escapeQuote($policy); ?>' class="form-control" />
									</div>
								</div>
								<div class="form-group col-sm-3">
									<label for="application_date_from" class="col-sm-12">Payment Date From:</label>
									<div class="input-group date" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd">
										<input type="text" size="16" name="date_start" placeholder="Start Date" data-toggle="tooltip" title="Payment Date Start From" class="form-control" />
										<div class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></div>
									</div>
								</div>
								<div class="form-group col-sm-3">
									<label for="application_date_from" class="col-sm-12">Payment Date To:</label>
									<div class="input-group date" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd">
										<input type="text" size="16" name="date_end" placeholder="End Date" data-toggle="tooltip" title="Payment Date End To" class="form-control" />
										<div class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="form-group col-sm-9">
								</div>
								<div class="form-group col-sm-3">
									<label class="col-sm-12">&nbsp;</label>
									<input class="btn btn-primary" type='submit' name='search' value='<?php echo $this->lang->line("Search"); ?>'>
								</div>
							</div>
						</form> 
					</div>
				</div>
			</div>
		</div><!-- End Create Section -->
		<!-- Result List Section -->
		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="x_panel">
					<div class="x_title">
						<h2><?php echo $this->lang->line("Search Result"); ?></h2>
					</div>
					<div class="x_content">
						<div class="table-responsive">
						<table class="table table-hover table-bordered">
							<thead>
								<tr>
									<th><?php echo $this->lang->line("Policy No."); ?></th>
									<th><?php echo $this->lang->line("Status"); ?></th>
									<th><?php echo $this->lang->line("Amount"); ?></th>
									<th><?php echo $this->lang->line("Pay Status"); ?></th>
									<th><?php echo $this->lang->line("Payment Date"); ?></th>
									<th>Retry Date</th>
									<th><?php echo $this->lang->line("Action"); ?></th>
								</tr>
							</thead>
							<tbody>
							<?php foreach ($plan_list as $plan) { $pid = $plan['plan_id']; $mpid = $plan['monthly_payment_id']; ?>
								<tr>
									<td><a style="color:#46b8da;" href='<?php echo $edit_url.$pid; ?>'><?php echo $pid; ?></a></td>
									<td><?php echo $status_list[$plan['status_id']]; ?></td>
									<td><?php echo $plan['amount']; ?></td>
									<td><?php echo $payStatusArr[$plan['paid']]; ?></td>
									<td><?php echo $plan['pay_date']; ?></td>
									<td><?php echo $plan['retry']?$plan['retry_date']."(".$plan['retry'].")":""; ?></td>
									<td><?php if ($plan['status_id']=='-2') { ?><button class="btn btn-primary retry-button" onclick='retry_payment("<?php echo $mpid; ?>")'>Try To Pay</button><?php } ?></td>
								</tr>
							<?php } ?>
							</tbody>
						</table>
					</div>
					<div class="row">
						<div class="col-md-12 col-sm-12 col-xs-12">
						</div>
					</div>
				</div>
			</div>
		</div><!-- End Result List Section -->
	</div>
</div>
<!-- /page content -->
</div>
<script type="text/javascript">
let max_count = 35; 	// 180 seconds
let w_count = 0;
let mpid = 0;
$(document).ready(function() {
	function get_plan_status() { 
		$.ajax({
			url: "<?php echo $pay_status_url; ?>" + mpid,
			type: 'GET',
			success: function(data, textStatus, jqXHR) {
				if (data == 1) {
					alert("Payment OK.");
					window.location = '<?php echo $base_url; ?>'
				} else {
					w_count++;
					if (w_count < max_count) {
						setTimeout(get_plan_status, 5000);
					} else {
						alert("Payment timed out. Please contact system support.");
						window.location = '<?php echo $base_url; ?>'
					}
				}
			},
		});
	}
});
function retry_payment(monthly_payment_id) {
	mpid = monthly_payment_id;
	$.ajax({
		url: '<?php echo $pay_url; ?>' + monthly_payment_id,
		type: 'GET',
		success: function(data, textStatus, jqXHR) {
			setTimeout(get_plan_status, 10000);
		},
	});
}
</script>
