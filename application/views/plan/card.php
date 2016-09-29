<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
?>
<html lang="en">
<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>PDF File</title>
	<?php echo $style;?>
</head>
<body>
	<header>
		<!--p class="rh">JF Group</p-->
	</header>
	<div class="container">	

		<div class="row">
			<div class="col-sm-12 nopadding text-center">
				<h2 style="margin:0 auto;"><?php echo $plan_full_name;?></h2>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12">
				<p class="nopm" style="margin-bottom:2px;"><?php echo $customer['firstname'] . " " . $customer['lastname']; ?></p>
				<p class="nopm"><?php if(!empty($plan['suite_number'])){echo  $plan['suite_number'] . "- ";} ?><?php echo $plan['street_number'] . ' ' . $plan['street_name'] . '<br />' . $plan['city'] . ', ' . $plan['province2'] . ', ' . $plan['postcode']; ?></p>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12 nopadding">
				<p>Dear <?php echo $customer['firstname'] . " " . $customer['lastname']; ?>,</p>
				<p>Thank you for choosing to insure with Johnson Fu Insurance Agency Inc . Please review your policy declaration below and the enclosed policy wording carefully; your coverage and premium are based on the information you provided. If the policy declaration information is incorrect, you must inform us immediately at 1-877-832-5541. </p>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12 nopadding text-center">
				<h4 style="margin:0 auto;"><u>Policy Declaration</u></h4>
				
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12 nopadding">
				<h4><?php if ($plan['status_id'] < 2) { ?>Quote<?php } else {?>Policy<?php } ?> Number: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span><?php echo $plan['policy']; ?></span></h4>
				<h4>Application Date: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span><?php echo $plan['apply_date'];?></sapn> </h4>
				<h4>Effective Date: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span><?php echo $plan['effective_date']; ?></sapn> </h4>
				<h4>Expiry Date: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span><?php echo $plan['expiry_date']; ?></sapn> </h4>
				<h4>Number of Days: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span><?php echo $plan['totaldays'];?></sapn> </h4>
				
			</div>
		</div>
		<div class="row">
			<div style="width:20%;float:left;">
				<h4>Insured Name</h4>
				<span><?php echo $customer['firstname'] . " " . $customer['lastname']; ?></span>
			</div>
			<div style="width:20%;float:left;">
				<h4>Birth Date(Age)</h4>
				<span><?php echo $customer['birthday']; ?></span>
			</div>
			<div style="width:20%;float:left;">
				<h4>Sum Insured</h4>
				<span><?php echo $plan['sum_insured']; ?></span>
			</div>
			<div style="width:20%;float:left;">
				<h4>Deductible</h4>
				<span><?php echo $plan['deductible_amount']; ?></span>
			</div>
			<div style="width:20%;float:left;">
				<h4>Trip Length</h4>
				<span><?php echo $plan['totaldays']; ?></span>
			</div>
		</div>
		<div class="row">
			<div class="jfcard" style="position:absolute;bottom:5px;left:5px;width:280px;margin-top:200px;">

				<h4 class="pull-right text-right"><?php echo $plan_full_name;?></h4>
				<h4> Insured Name:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <span><?php echo $customer['firstname'] . " " . $customer['lastname']; ?></span></h4>
				<h4><?php if ($plan['status_id'] < 2) { ?>Quote<?php } else {?>Policy<?php } ?> Number: &nbsp;&nbsp;&nbsp;&nbsp;<span><?php echo $plan['policy']; ?></span></h4>
				<h4> Deductible: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span><?php echo $plan['deductible_amount']; ?></span></h4>
				<h4>Effective Date: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span><?php echo $plan['effective_date']; ?></sapn> </h4>
				<h4>Expiry Date: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span><?php echo $plan['expiry_date']; ?></sapn> </h4>
			</div>
		</div>		
	</div>
</body>
</html>