<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
?>

<!-- Plan page content -->

<!-- Content top navigation -->
<div class="top_nav">
	<div class="nav_menu">
		<nav class="" role="navigation">
			<div class="nav toggle">
				<a id="menu_toggle"><i class="fa fa-bars"></i></a>
			</div>

			<ul class="nav navbar-nav navbar-right">
				<!-- User section -->
				<li class=""><a href="javascript:;"
					class="user-profile dropdown-toggle" data-toggle="dropdown"
					aria-expanded="false"> <img src="images/img.jpg" alt="">John Doe <span
						class=" fa fa-angle-down"></span>
				</a>
					<ul class="dropdown-menu dropdown-usermenu pull-right">
						<li><a href="javascript:;"> Profile</a></li>
						<li><a href="javascript:;"> <span class="badge bg-red pull-right">50%</span>
								<span>Settings</span>
						</a></li>
						<li><a href="javascript:;">Help</a></li>
						<li><a href="login.html"><i class="fa fa-sign-out pull-right"></i>
								Log Out</a></li>
					</ul></li>
				<!-- User section End -->
			</ul>
		</nav>
	</div>
</div>
<!-- Content top navigation End-->

<!-- page content -->
<div class="right_col" role="main" style="margin-bottom: 40px;">
	<div class="">
		<div class="page-title">
			<div class="title_left">
				<h3>Create Policy</h3>
			</div>

			<div class="title_right">
				<div
					class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
					<div class="input-group">
						<input type="text" class="form-control"
							placeholder="Search for..."> <span class="input-group-btn">
							<button class="btn btn-default" type="button">Go!</button>
						</span>
					</div>
				</div>
			</div>
		</div>
		<div class="clearfix"></div>
		<!-- Form Section -->
		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="x_panel">
					<div class="x_title">
						<h2>
							Policy<small></small>
						</h2>
						<div class="clearfix"></div>
					</div>
					<div class="x_content">
						<br />
Apply Date: <?php echo $apply_date; ?><br>
Arrival Date: <?php echo $plan['arrival_date']; ?><br>
Effective Date: <?php echo $plan['effective_date']; ?><br>
Expiry Date: <?php echo $plan['expiry_date']; ?><br>
=========================================================================<br />
Beneficiary: <?php echo $plan['beneficiary']; ?><br>
<?php if ($plan['isfamilyplan']) { ?> Family Plan <br><?php } ?>
Sum Insured (CAD): $<?php echo number_format($plan['sum_insured'], 2, '.', ','); ?><br>
Deductible amount (CAD):  $<?php echo number_format($plan['deductiable_amount'], 2, '.', ','); ?><br>
<?php
if (empty ( $disable_stable_condition )) {
	if ($plan ['stable_condition'] == 1) {
		echo "With stable pre-existion condition coverage<br>";
	} else if ($plan ['stable_condition'] == 2) {
		echo "Without stable pre-existion condition coverage<br>";
	}
}
?>
=========================================================================<br />
Custmer Name:<br>
Last Name: <?php echo $customer['lastname']; ?><br>
First Name: <?php echo $customer['firstname']; ?><br>
Birth Date: <?php echo $customer['birthday']; ?><br>
Gender: <?php echo $customer['gender']; ?><br>
=========================================================================<br />
<?php if ($plan['isfamilyplan']) { ?>
Family members:<br>
<?php for ($i = 1; $i < 9; $i++) { ?>
<?php if (empty($customers[$i]['lastname']) && empty($customers[$i]['firstname'])) continue; ?>
Last Name: <?php echo $customers[$i]['lastname']; ?><br>
First Name: <?php echo $customers[$i]['firstname']; ?><br>
Birth Date: <?php echo $customers[$i]['birthday']; ?><br>
Gender: <?php echo $customers[$i]['gender']; ?><br>
=========================================================================<br />
<?php } ?>
<?php } ?>
Street No: <?php echo $plan['street_number']; ?><br>
Street Name: <?php echo $plan['street_name']; ?><br>
Suite No.: <?php echo $plan['suite_number']; ?><br>
City: <?php echo $plan['city']; ?><br>
Province: <?php echo $plan['province2']; ?><br>
Country: <?php echo $plan['country2']; ?><br>
Postcode: <?php echo $plan['postcode']; ?><br>
Phone1: <?php echo $plan['phone1']; ?><br>
Phone2: <?php echo $plan['phone2']; ?><br>
=========================================================================<br />
Email: <?php echo $plan['contact_email']; ?><br>
Contact Phone: <?php echo $plan['contact_phone']; ?><br>
Residence: <?php echo $plan['residence']; ?><br>
=========================================================================<br />
Premium: $<?php echo number_format($plan['premium'], 2, '.', ','); ?><br>
Notes: <?php echo $plan['note']; ?><br>
=========================================================================<br />
<?php if (!empty($errormsg)) { ?>
<?php echo $errormsg; ?><br>
<?php } ?>
<?php if (isset($credit_dis)) { ?>
<div id='credit_card_div'>Pay By Credit Card</div>
<script type="text/javascript">
$(document).ready(function() {
	$('#credit_card_div').click(function() {
		if ($('#credit_card').is(":visible")) {
			$('#credit_card').hide();
		} else {
			$('#credit_card').show();
<?php if (isset($cheque_dis)) { ?>
			$('#cheque').hide();
<?php } ?>
<?php if (isset($cash_dis)) { ?>
			$('#cash').hide();
<?php } ?>
		}
	});
});
</script>
<div id='credit_card' <?php if (empty($credit_dis)) { ?> style='display: none;' <?php } ?>>
<form action='<?php echo $active_url; ?>' method='POST'>
<input type='hidden' name='<?php echo $csrf['name']; ?>' value='<?php echo $csrf['value']; ?>'>
<input type='hidden' name='plan_id' value='<?php echo $plan['plan_id']; ?>'>
<input type='hidden' name='play_type' value='<?php echo $pay_type; ?>'>
<input type='hidden' name='sekey' value='<?php echo $sekey; ?>'>
<input type='hidden' name='premium' value='<?php echo number_format($plan['premium'], 2, '.', ','); ?>'>
Card Number: <input type='text' name='card_number' value=''> | 
Card Name: <input type='text' name='card_name' value=''> | 
Expiry Month: <select name='expiry_month'>
	<option value='01'>01</option>
	<option value='02'>02</option>
	<option value='03'>03</option>
	<option value='04'>04</option>
	<option value='05'>05</option>
	<option value='06'>06</option>
	<option value='07'>07</option>
	<option value='08'>08</option>
	<option value='09'>09</option>
	<option value='10'>10</option>
	<option value='11'>11</option>
	<option value='12'>12</option>
</select> | 
Expiry Year: <select name='expiry_month'>
	<option value='<?php echo date('y'); ?>'> <?php echo date('y'); ?> </option>
	<option value='<?php echo date('y',strtotime('+1 years')); ?>'> <?php echo date('y',strtotime('+1 years')); ?> </option>
	<option value='<?php echo date('y',strtotime('+2 years')); ?>'> <?php echo date('y',strtotime('+2 years')); ?> </option>
	<option value='<?php echo date('y',strtotime('+3 years')); ?>'> <?php echo date('y',strtotime('+3 years')); ?> </option>
	<option value='<?php echo date('y',strtotime('+4 years')); ?>'> <?php echo date('y',strtotime('+4 years')); ?> </option>
	<option value='<?php echo date('y',strtotime('+5 years')); ?>'> <?php echo date('y',strtotime('+5 years')); ?> </option>
	<option value='<?php echo date('y',strtotime('+6 years')); ?>'> <?php echo date('y',strtotime('+6 years')); ?> </option>
	<option value='<?php echo date('y',strtotime('+7 years')); ?>'> <?php echo date('y',strtotime('+7 years')); ?> </option>
	<option value='<?php echo date('y',strtotime('+8 years')); ?>'> <?php echo date('y',strtotime('+8 years')); ?> </option>
	<option value='<?php echo date('y',strtotime('+9 years')); ?>'> <?php echo date('y',strtotime('+9 years')); ?> </option>
</select> |
Amount: $<?php echo number_format($plan['premium'], 2, '.', ','); ?> |
<input type='submit' name='submit' value='Pay'><br> 
Pay url send to user by Email: <input type='text' name='payurl' value='<?php echo $payurl; ?>' readonly>
</form>
</div>
<?php } ?>
<?php if (isset($cheque_dis)) { ?>
<div id='cheque_div'>Pay By Check</div>
<script type="text/javascript">
$(document).ready(function() {
	$('#cheque_div').click(function() {
		if ($('#cheque').is(":visible")) {
			$('#cheque').hide();
		} else {
			$('#cheque').show();
<?php if (isset($credit_dis)) { ?>
			$('#credit_card').hide();
<?php } ?>
<?php if (isset($cash_dis)) { ?>
			$('#cash').hide();
<?php } ?>
		}
	});
});
</script>
<div id='cheque' <?php if (empty($cheque_dis)) { ?> style='display: none;' <?php } ?>>
<form action='<?php echo $active_url; ?>' method='POST'>
<input type='hidden' name='<?php echo $csrf['name']; ?>' value='<?php echo $csrf['value']; ?>'>
<input type='hidden' name='plan_id' value='<?php echo $plan['plan_id']; ?>'>
<input type='hidden' name='play_type' value='<?php echo $pay_type; ?>'>
<input type='hidden' name='sekey' value='<?php echo $sekey; ?>'>
<input type='hidden' name='premium' value='<?php echo number_format($plan['premium'], 2, '.', ','); ?>'>
Bank Name: <input type='text' name='bank_name' value=''> | 
Payor Name: <input type='text' name='payor_name' value=''> | 
Checque #: <input type='text' name='cheque_number' value=''> |
Amount: $<?php echo number_format($plan['premium'], 2, '.', ','); ?> |
<input type='submit' name='submit' value='Pay'><br> 
</form>
</div>
<?php } ?>
<?php if (isset($cash_dis)) { ?>
<div id='cash_div'>Pay By Cash</div>
<script type="text/javascript">
$(document).ready(function() {
	$('#cash_div').click(function() {
		if ($('#cash').is(":visible")) {
			$('#cash').hide();
		} else {
			$('#cash').show();
<?php if (isset($credit_dis)) { ?>
			$('#credit_card').hide();
<?php } ?>
<?php if (isset($cheque_dis)) { ?>
			$('#cheque').hide();
<?php } ?>
		}
	});
});
</script>
<div id='cash' <?php if (empty($cash_dis)) { ?> style='display: none;' <?php } ?>>
<form action='<?php echo $active_url; ?>' method='POST'>
<input type='hidden' name='<?php echo $csrf['name']; ?>' value='<?php echo $csrf['value']; ?>'>
<input type='hidden' name='plan_id' value='<?php echo $plan['plan_id']; ?>'>
<input type='hidden' name='play_type' value='<?php echo $pay_type; ?>'>
<input type='hidden' name='sekey' value='<?php echo $sekey; ?>'>
<input type='hidden' name='premium' value='<?php echo number_format($plan['premium'], 2, '.', ','); ?>'>
Amount: $<?php echo number_format($plan['premium'], 2, '.', ','); ?> |
<input type='submit' name='submit' value='Pay'><br> 
Pay url send to user by Email: <input type='text' name='payurl' value='<?php echo $payurl; ?>' readonly>
</form>
</div>
<?php } ?>
				</div>
				</div>
			</div>
		</div>
		<!-- End Form -->
	</div>
</div>
<!-- /page content -->