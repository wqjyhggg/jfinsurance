<?php
defined('BASEPATH') or exit('No direct script access allowed');
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
				<h3>Payment<small></small></h3>
			</div>
		</div>
		<div class="clearfix"></div>
		<!-- Filter Section -->
		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="x_panel">
					<div class="x_content">
						<form method="post" action="<?php echo $action_url ?>" class="form-horizontal">
							<div class="row">
								<div class="col-sm-3">
									<label>Plan ID:</label>
									<input name="plan_id" class="form-control" type="text" value="<?php echo $this->input->post('plan_id'); ?>">
								</div>
								<div class="col-sm-3">
								</div>
								<div class="col-sm-3">
									<label style="margin-top: 2em">&nbsp; </label>
									<button class="btn btn-primary">Get Payment Record</button>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
		<!-- End Filter Section -->
		<!-- List Section -->
		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="x_panel">
					<div class="x_content">
						<div class="table-responsive">
							<div>Plan ID : <?php echo $plan_id; ?></div>
							<table class="table table-hover table-bordered">
								<thead>
									<tr>
										<th>&nbsp;</th>
										<th>User Id</th>
										<th>Amount</th>
										<th>Admin Fee</th>
										<th>Rate</th>
										<th>Is Paid</th>
										<th>Pay Method</th>
										<th>Last Update</th>
										<th>Created Date</th>
										<th>Pay Date</th>
										<th>Note</th>
										<th>Bank Name</th>
										<th>Payor Name</th>
										<th>Cheque Number</th>
										<th>&nbsp;</th>
									</tr>
								</thead>
								<tbody>
<?php foreach ($payments as $pay) : ?>
<?php if (preg_match('/up_/', $pay['pay_type'])) continue; ?>
								<form method="post" action="<?php echo $action_url ?>" class="form-horizontal">
									<input type="hidden" name="plan_id" value="<?php echo $plan_id; ?>"></td>
									<input type="hidden" name="payment_id" value="<?php echo $pay['payment_id']; ?>"></td>
									<tr>
										<td><input type="submit" name="update" value="Update" class="btn btn-primary"></td>
										<td><input class="form-control" type="text" name="user_id" value="<?php echo $pay['user_id']; ?>"></td>
										<td><input class="form-control" type="text" name="amount" value="<?php echo $pay['amount']; ?>"></td>
										<td><input class="form-control" type="text" name="admin_fee" value="<?php echo $pay['admin_fee']; ?>"></td>
										<td><input class="form-control" type="text" name="rate" value="<?php echo $pay['rate']; ?>"></td>
										<td><input class="form-control" type="text" name="ispaid" value="<?php echo $pay['ispaid']; ?>"></td>
										<td><input class="form-control" type="text" name="pay_mothed" value="<?php echo $pay['pay_mothed']; ?>"></td>
										<td><input class="form-control" type="text" name="last_update" value="<?php echo $pay['last_update']; ?>"></td>
										<td><input class="form-control" type="text" name="added" value="<?php echo $pay['added']; ?>"></td>
										<td><input class="form-control" type="text" name="pay_date" value="<?php echo $pay['pay_date']; ?>"></td>
										<td><input class="form-control" type="text" name="note" value="<?php echo $pay['note']; ?>"></td>
										<td><input class="form-control" type="text" name="bank_name" value="<?php echo $pay['bank_name']; ?>"></td>
										<td><input class="form-control" type="text" name="payor_name" value="<?php echo $pay['payor_name']; ?>"></td>
										<td><input class="form-control" type="text" name="cheque_number" value="<?php echo $pay['cheque_number']; ?>"></td>
										<td><input type="submit" name="delete" value="Delete" class="btn btn-primary"></td>
									</tr>
								</form>
<?php endforeach; ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- End List Section -->
	</div>
</div>
<!-- /page content -->
