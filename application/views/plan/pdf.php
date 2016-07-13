<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
?>
<html lang="en">
<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>PDF File</title>
	<link rel="stylesheet" href="<?php echo $bootstrap;?>">
	<link rel="stylesheet" href="<?php echo $bootstrapmin;?>">
	<?php echo $style;?>
</head>
<body>
	<header>
		<!--p class="rh">JF Group</p-->
	</header>
	<div class="container">	
		<div class="row">
			<h3 class="col-sm-12">
				<?php echo $plan_full_name;?>
			</h3>
		</div>
		<div class="row">
			<h4 class="col-sm-6"><?php if ($plan['status_id'] < 2) { ?>Quote<?php } else {?>Policy<?php } ?> Number: <span><?php echo $plan['policy']; ?></span></h4>
			<h4 class="col-sm-6 text-right pull-right">Policy Status: <?php echo $status_list[$plan['status_id']]['name']; ?></h4>
		</div>
		<div>
			<div class="p-detail"><!-- policy detail -->
				<div class="row">
					<div class="col-sm-3">
						<label class="inline">Apply Date:</label>
						<span><?php echo $plan['apply_date']; ?></span>
					</div>
					<div class="col-sm-3">
						<label class="inline">Arrival Date:</label>
						<span><?php echo $plan['arrival_date']; ?></span>
					</div>
					<div class="col-sm-3">
						<label class="inline">Effective Date:</label>
						<span><?php echo $plan['effective_date']; ?></span>
					</div>
					<div class="col-sm-3">
						<label class="inline">Expiry Date:</label>
						<span><?php echo $plan['expiry_date']; ?></span>
					</div>
				</div>

				<?php echo $insurable_options; ?>

				<div class="row">
					<div class="col-sm-12" style="background-color:#5bc0de; color:#fff;">
						<label class="inline">Customer Information</label>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-3">
						<label class="inline">Last Name:</label>
						<span><?php echo $customer['lastname']; ?></span>
					</div>
					<div class="col-sm-3">
						<label class="inline">First Name:</label>
						<span><?php echo $customer['lastname']; ?></span>
					</div>
					<div class="col-sm-3">
						<label class="inline">Birth Date:</label>
						<span><?php echo $customer['birthday']; ?></span>
					</div>
					<div class="col-sm-3">
						<label class="inline">Gender:</label>
						<span><?php echo $customer['gender']; ?></span>
					</div>
				</div>

				<?php if ($plan['isfamilyplan']) { ?>
				<div class="row">
					<div class="col-sm-12" style="background-color:#5bc0de; color:#fff;">
						<label class="inline">Family Member Information</label>
					</div>
					<?php for ($i = 0; $i < 9; $i++) { ?>
					<?php if (empty($customers[$i]['lastname']) && empty($customers[$i]['firstname'])) continue; ?>
					<div class="col-sm-3">
						<label class="inline">Last Name:</label>
						<span><?php echo $customers[$i]['lastname']; ?></span>
					</div>
					<div class="col-sm-3">
						<label class="inline">First Name:</label>
						<span><?php echo $customers[$i]['firstname']; ?></span>
					</div>
					<div class="col-sm-3">
						<label class="inline">Birth Date:</label>
						<span><?php echo $customers[$i]['birthday']; ?></span>
					</div>
					<div class="col-sm-3">
						<label class="inline">Gender:</label>
						<span><?php echo $customers[$i]['gender']; ?></span>
					</div>
					<?php } ?>
				</div>
				<?php } ?>	

				<div class="row">
					<div class="col-sm-12" style="background-color:#5bc0de; color:#fff;"><label>Address Information</label>
					</div>
					<div class="col-sm-3">
						<label class="inline">Street No:</label>
						<span><?php echo $plan['street_number']; ?></span>
					</div>
					<div class="col-sm-3">
						<label class="inline">Street Name:</label>
						<span><?php echo $plan['street_name']; ?></span>
					</div>
					<div class="col-sm-3">
						<label class="inline">Suite No.:</label>
						<span><?php echo $plan['suite_number']; ?></span>
					</div>
					<div class="col-sm-3">
						<label class="inline">City: </label>
						<span><?php echo $plan['city']; ?></span>
					</div>	

					<div class="col-sm-3">
						<label class="inline">Province:</label>
						<span><?php echo $plan['province2']; ?></span>
					</div>
					<div class="col-sm-3">
						<label class="inline">Country:</label>
						<span><?php echo $plan['country2']; ?></span>
					</div>
					<div class="col-sm-3">
						<label class="inline">Postcode:</label>
						<span><?php echo $plan['postcode']; ?></span>
					</div>

					<div class="col-sm-3">
						<label class="inline">Phone1:</label>
						<span><?php echo $plan['phone1']; ?></span>
					</div>
					<div class="col-sm-3">
						<label class="inline">Phone2:</label>
						<span><?php echo $plan['phone2']; ?></span>
					</div>

				</div>
				<div class="row">
					<div class="col-sm-12" style="background-color:#5bc0de; color:#fff;">
						<label class="inline">Contact Information</label>
					</div>
					<div class="col-sm-3">
						<label class="inline">Email:</label>
						<span><?php echo $plan['contact_email']; ?></span>
					</div>
					<div class="col-sm-3">
						<label class="inline">Contact Phone:</label>
						<span><?php echo $plan['contact_phone']; ?></span>
					</div>
					<div class="col-sm-3">
						<label class="inline">Residence:</label>
						<span><?php echo $plan['residence']; ?></span>
					</div>
				</div>

				<div class="row">
					<div class="col-sm-12" style="background-color:#5bc0de; color:#fff;">
						<label class="inline">Special Note/Instructions</label>
					</div>
					<div class="col-sm-12">
						<label class="inline">Premium:</label>
						<span>$<?php echo number_format($plan['premium'], 2, '.', ',');?></span>
					</div>
					<div class="col-sm-12">
						<label class="inline">Notes:</label>
						<span><?php echo $plan['note']; ?></span>
					</div>

				</div>

			</div><!-- end p-detail --><br />
		</div><!-- x_content -->
	</div><!-- End Container -->
</body>
</html>