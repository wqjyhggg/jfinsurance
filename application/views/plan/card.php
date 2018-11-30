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
			<img class="img-responsive" style="max-width:390px;" src="<?php echo base_url();?>image/logo.png" />
				<h3 style="margin:20px auto 0;"><?php echo $plan_full_name;?></h3>
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
				<p>Thank you for choosing to insure with JF Insurance Agency Inc . Please review your policy declaration below and the enclosed policy wording carefully; your coverage and premium are based on the information you provided. If the policy declaration information is incorrect, you must inform us immediately at 1-877-832-5541. </p>
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
				<h4>
				<?php if ($plan['product_short'] == 'TOP') { ?>
					Departure Date:
				 	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span><?php echo $plan['arrival_date'];?></sapn>
				<?php } else { ?>
					Application Date:
				 	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span><?php echo $plan['apply_date'];?></sapn>
				<?php } ?>
				</h4>
				<h4>Effective Date: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span><?php echo $plan['effective_date']; ?></sapn> </h4>
				<h4>Expiry Date: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span><?php echo $plan['expiry_date']; ?></sapn> </h4>
				<?php if (($plan['product_short'] != "JES") && ($plan['product_short'] !="JFC") && ($plan['product_short'] !="TOP")) { ?>
				<h4>Number of Days: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span><?php echo $plan['totaldays'];?></sapn> </h4>
				<?php } ?>
				
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12 nopadding text-center">
				<h4 style="margin-top:180px;"><span>Detach this card and carry it with you at all times</span></h4>
				<hr style="border-style: dotted;">
				
			</div>
		</div>
		<div class="row">
			<div class="jfcard" style="float: left;vertical-align:top;position:absolute;bottom:5px;left:5px;width:320px;margin-top: 30px;">
				<?php if($plan['product_short'] == "JES"){ ?>
					<h4 class="text-center" style="background: #eee;"><span>JF Elite PLus Student Insurance</span></h4>
				<?php }elseif ($plan['product_short'] == "JFC") { ?>
					<h4 class="text-center" style="background: #eee;"><span>JF Care Student Insurance</span></h4>
				<?php }else{ ?>
					<h4 class="text-center" style="background: #eee;"><span><?php echo $plan_full_name;?></span></h4>
				<?php } ?>

				<div style="width: 260px; margin: 0 auto;">
					<h4><span class="small"><?php if ($plan['status_id'] < 2) { ?>Quote<?php } else {?>Certificate<?php } ?> No.: &nbsp;&nbsp;</span><span class="small"><?php echo $plan['policy']; ?></span></h4>
					<h4><span class="small"> Insured Name:&nbsp;&nbsp;&nbsp;</span><span class="small">
					<?php
					echo $customer['firstname'] . " " . $customer['lastname'];
					if (!empty($customers)) {
						foreach ($customers as $c) {
							echo ", " . $c['firstname'] . " " . $c['lastname'];
						}
					}
					?>
					</span></h4>
					<!--h4> Deductible: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span><?php echo $plan['deductible_amount']; ?></span></h4-->
					<h4>
						<span class="small pull-left">Effective Date: &nbsp;&nbsp;</span><span class="small"><?php echo $plan['effective_date']; ?></sapn>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<span class="small" style="text-align: right;">Expiry Date: &nbsp;&nbsp;</span><span class="small"><?php echo $plan['expiry_date']; ?></sapn>
					</h4>
					
				</div>
				<div class="row"><div class="col-sm-12 nopadding text-center">
					<img class="img-responsive" style="max-width:170px;" src="<?php echo base_url();?>image/logo.png" />
				</div></div>
			</div>

			<div class="jfcard" style="float: right;vertical-align:top;position:absolute;bottom:5px;left:5px;width:320px;margin-top: 0px;">

				<?php if($plan['product_short'] == "JES"){ ?>
					<h4 class="text-center" style="background: #eee;"><span>JF Elite PLus Student Insurance</span></h4>
				<?php }elseif ($plan['product_short'] == "JFC") { ?>
					<h4 class="text-center" style="background: #eee;"><span>JF Care Student Insurance</span></h4>
				<?php }else{ ?>
					<h4 class="text-center" style="background: #eee;"><span><?php echo $plan_full_name;?></span></h4>
				<?php } ?>
				
				<div style="width: 260px; margin: 0 auto;">
					<h4><span class="small"><?php if ($plan['status_id'] < 2) { ?>Quote<?php } else {?>Certificate<?php } ?> No.: &nbsp;&nbsp;</span><span class="small"><?php echo $plan['policy']; ?></span></h4>
					<h4><span class="small"> Insured Name:&nbsp;&nbsp;&nbsp;</span><span class="small">
					<?php 
					echo $customer['firstname'] . " " . $customer['lastname'];
					if (!empty($customers)) {
						foreach ($customers as $c) {
							echo ", " . $c['firstname'] . " " . $c['lastname'];
						}
					}
					?>
					</span></h4>
					<!--h4> Deductible: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span><?php echo $plan['deductible_amount']; ?></span></h4-->
					<h4>
						<span class="small pull-left">Effective Date: &nbsp;&nbsp;</span><span class="small"><?php echo $plan['effective_date']; ?></sapn>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<span class="small" style="text-align: right;">Expiry Date: &nbsp;&nbsp;</span><span class="small"><?php echo $plan['expiry_date']; ?></sapn>
					</h4>
					
				</div>
				<div class="row"><div class="col-sm-12 nopadding text-center">
					<img class="img-responsive" style="max-width:170px;" src="<?php echo base_url();?>image/logo.png" />
				</div></div>
			</div>
		</div>		
	</div>
</body>
</html>