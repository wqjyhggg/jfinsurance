<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

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
				<h3><?php echo $title_txt; ?></h3>
			</div>
		</div>
		<div class="clearfix"></div>
		<!-- Filter Section -->
		<?php if (isset($errormsg) && $errormsg) { ?>
		<div class="alert-error"><?php echo $errormsg; ?></div>
		<?php } ?>
		<?php if ($beuser['user_group_id'] < 100) { ?>
		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="x_panel">
					<div class="x_title">
						<h2>Choose Agent</h2>
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
											<option value="<?=$agent['user_id'] ?>" <?php if ($agent_id == $agent['user_id']) { echo "selected"; } ?>><?php echo $agent['username'] . " ( ". $agent['full_name'] . " )"; ?></option>
											<?php endforeach; ?>
										</select>
									</div>
								</div>
								<!-- Agent input box end -->
								<!-- submit button -->
								<div class="col-sm-3">
									<br />
									<button class="btn btn-primary pull-right">Submit</button>
								</div>
								<!-- submit button -->
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
		<!-- End Filter Section -->
		<?php } ?>
		<!-- List Section -->
		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="x_panel">
					<div class="x_title">
						<h2>Report Data</h2>
						<div class="clearfix"></div>
					</div>
					<?php if (isset($record)) { ?>
					<form method="post" action="<?=$action_url ?>" class="form-horizontal">
						<input type='hidden' name='<?php echo $csrf['name']; ?>' value='<?php echo $csrf['value']; ?>'>
						<input type='hidden' name='agent_id' value='<?php echo $agent_id; ?>'>
						<table class="table table-hover">
							<thead>
								<tr>
									<th>Month</th>
									<th>Premium</th>
									<th>Commission</th>
									<th>Payment</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>January</td>
									<td>
										<input type="text" name="premium[1]" value="<?php echo $record['premium']['1']; ?>">
									</td>
									<td>
										<input type="text" name="commission[1]" value="<?php echo $record['commission']['1']; ?>">
									</td>
									<td>
										<input type="text" name="payment[1]" value="<?php echo $record['payment']['1']; ?>">
									</td>
								</tr>
								<tr>
									<td>February</td>
									<td>
										<input type="text" name="premium[2]" value="<?php echo $record['premium']['2']; ?>">
									</td>
									<td>
										<input type="text" name="commission[2]" value="<?php echo $record['commission']['2']; ?>">
									</td>
									<td>
										<input type="text" name="payment[2]" value="<?php echo $record['payment']['2']; ?>">
									</td>
								</tr>
								<tr>
									<td>March</td>
									<td>
										<input type="text" name="premium[3]" value="<?php echo $record['premium']['3']; ?>">
									</td>
									<td>
										<input type="text" name="commission[3]" value="<?php echo $record['commission']['3']; ?>">
									</td>
									<td>
										<input type="text" name="payment[3]" value="<?php echo $record['payment']['3']; ?>">
									</td>
								</tr>
								<tr>
									<td>April</td>
									<td>
										<input type="text" name="premium[4]" value="<?php echo $record['premium']['4']; ?>">
									</td>
									<td>
										<input type="text" name="commission[4]" value="<?php echo $record['commission']['4']; ?>">
									</td>
									<td>
										<input type="text" name="payment[4]" value="<?php echo $record['payment']['4']; ?>">
									</td>
								</tr>
								<tr>
									<td>May</td>
									<td>
										<input type="text" name="premium[5]" value="<?php echo $record['premium']['5']; ?>">
									</td>
									<td>
										<input type="text" name="commission[5]" value="<?php echo $record['commission']['5']; ?>">
									</td>
									<td>
										<input type="text" name="payment[5]" value="<?php echo $record['payment']['5']; ?>">
									</td>
								</tr>
								<tr>
									<td>June</td>
									<td>
										<input type="text" name="premium[6]" value="<?php echo $record['premium']['6']; ?>">
									</td>
									<td>
										<input type="text" name="commission[6]" value="<?php echo $record['commission']['6']; ?>">
									</td>
									<td>
										<input type="text" name="payment[6]" value="<?php echo $record['payment']['6']; ?>">
									</td>
								</tr>
								<tr>
									<td>July</td>
									<td>
										<input type="text" name="premium[7]" value="<?php echo $record['premium']['7']; ?>">
									</td>
									<td>
										<input type="text" name="commission[7]" value="<?php echo $record['commission']['7']; ?>">
									</td>
									<td>
										<input type="text" name="payment[7]" value="<?php echo $record['payment']['7']; ?>">
									</td>
								</tr>
								<tr>
									<td>August</td>
									<td>
										<input type="text" name="premium[8]" value="<?php echo $record['premium']['8']; ?>">
									</td>
									<td>
										<input type="text" name="commission[8]" value="<?php echo $record['commission']['8']; ?>">
									</td>
									<td>
										<input type="text" name="payment[8]" value="<?php echo $record['payment']['8']; ?>">
									</td>
								</tr>
								<tr>
									<td>September</td>
									<td>
										<input type="text" name="premium[9]" value="<?php echo $record['premium']['9']; ?>">
									</td>
									<td>
										<input type="text" name="commission[9]" value="<?php echo $record['commission']['9']; ?>">
									</td>
									<td>
										<input type="text" name="payment[9]" value="<?php echo $record['payment']['9']; ?>">
									</td>
								</tr>
								<tr>
									<td>October</td>
									<td>
										<input type="text" name="premium[10]" value="<?php echo $record['premium']['10']; ?>">
									</td>
									<td>
										<input type="text" name="commission[10]" value="<?php echo $record['commission']['10']; ?>">
									</td>
									<td>
										<input type="text" name="payment[10]" value="<?php echo $record['payment']['10']; ?>">
									</td>
								</tr>
								<tr>
									<td>November</td>
									<td>
										<input type="text" name="premium[11]" value="<?php echo $record['premium']['11']; ?>">
									</td>
									<td>
										<input type="text" name="commission[11]" value="<?php echo $record['commission']['11']; ?>">
									</td>
									<td>
										<input type="text" name="payment[11]" value="<?php echo $record['payment']['11']; ?>">
									</td>
								</tr>
								<tr>
									<td>December</td>
									<td>
										<input type="text" name="premium[12]" value="<?php echo $record['premium']['12']; ?>">
									</td>
									<td>
										<input type="text" name="commission[12]" value="<?php echo $record['commission']['12']; ?>">
									</td>
									<td>
										<input type="text" name="payment[12]" value="<?php echo $record['payment']['12']; ?>">
									</td>
								</tr>
								<tr>
									<td><b>Total</b></td>
									<td>
										<div id="premium">0</div>
									</td>
									<td>
										<div id="commission">0</div>
									</td>
									<td>
										<div id="payment">0</div>
									</td>
								</tr>
							</tbody>
						</table>
						<div class="row">
							<div class="form-group col-sm-6">Previous year total sales : <input type="text" name="last_year_total" value="<?php echo $record['last_year_total']; ?>">
							</div>
							<?php if ($beuser['user_group_id'] < 100) { ?>
							<div class="col-sm-3">
								<input type='submit' name='submit' value='Submit' class="btn btn-primary">
							</div>
							<?php } ?>
							<div class="col-sm-3">
								<input type='submit' name='export' value='Export' class="btn btn-primary">
							</div>
						</div>
					</form>
					<?php } else { ?>
					Please select agent
					<?php } ?>
				</div>
			</div>
		</div>
		<!-- End List Section -->
	</div>
</div>
<!-- /page content -->
<script>
function sum_premium (name) {
	var sum = 0;
	$('input[name^="'+name+'"]').each(function() {
		sum += parseFloat($(this).val());
	});
	$('#'+name).html(parseFloat(sum).toFixed(2));
}

$( document ).ready(function() {
	<?php if ($beuser['user_group_id'] > 100) { ?>
	$("input").prop('disabled', true);
	<?php } ?>
	$('input[name^="premium"]').change(function() {
		sum_premium('premium');
	});
	$('input[name^="commission"]').change(function() {
		sum_premium('commission');
	});
	$('input[name^="payment"]').change(function() {
		sum_premium('payment');
	});
	sum_premium('premium');
	sum_premium('commission');
	sum_premium('payment');
});
</script>
