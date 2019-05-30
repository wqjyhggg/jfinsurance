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
				<h2 style="margin:0 auto;">TAX RECEIPT FOR INSURANCE</h2>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12 nopadding">
				<p class="nopm" style="margin-bottom:2px;"><?php echo $customer['firstname'] . " " . $customer['lastname']; ?></p>
				<p class="nopm"><?php if(!empty($plan['suite_number'])){echo  $plan['suite_number'] . "- ";} ?><?php echo $plan['street_number'] . ' ' . $plan['street_name'] . '<br />' . $plan['city'] . ', ' . $plan['province2'] . ', ' . $plan['postcode']; ?></p>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12 nopadding receipt-info">
				<h4><?php if ($plan['status_id'] < 2) { ?>QUOTE<?php } else {?>POLICY<?php } ?> NUMBER: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span><?php echo $plan['policy']; ?></span></h4>
				<h4>EFFECTIVE DATE: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span><?php echo $plan['effective_date']; ?></sapn> </h4>
				<h4>EXPIRATION DATE: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span><?php echo $plan['expiry_date']; ?></sapn> </h4>
				<h4>PREMIUM PAID: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span>$<?php echo number_format($plan['premium'], 2, '.', ',');?></span> </h4>
				<h4>SALES AGENT: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span><?php echo $agent['firstname'] . " " . $agent['lastname'];?></span> </h4>
				<h4>PRODUCT: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span><?php echo $plan_full_name;?></span> </h4>
				<h4>TYPE: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span><?php if($plan['isfamilyplan']==1){ echo "Family";} else if ($plan['isfamilyplan']==2){ echo "Group";}else{echo "Individual";} ?></sapn> </h4>
				
			</div>
		</div>
		<hr />
		<div class="row">
			<div style="float:left;width:80px;">
				<img class="img-responsive" style="width:70px;" src="<?php echo base_url();?>image/jf_logo.jpg" />
			</div>
			<div style="float:left;margin-top:5px;" class="receipt-info">
				<p class="topp">Johnson Fu Insurance Agency Inc.</p>
				<p class="topp">15 Wertheim Court, Suite 501, Richmond Hill, ON, L4B3H7</p>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12 nopm text-right">
				<h4 class="nopm">TAX RECEIPT</h4>
			</div>
		</div>
		<div style="border-bottom:3px solid #eee;margin:10px auto 20px;"></div>

		<div class="row">
			<div class="col-sm-6 nopadding receipt-info">
				<h4>DATE: <span><?php echo isset($payment['added']) ? $payment['added'] : ''; ?></span></h4>
				<h4>NAME: <span><?php echo $customer['firstname'] . " " . $customer['lastname']; ?></span></h4>
				<h4>ADDRESS: <span><?php if(!empty($plan['suite_number'])){echo  $plan['suite_number'] . "- ";} ?><?php echo $plan['street_number'] . ' ' . $plan['street_name'] . ' ' . $plan['city'] . ', ' . $plan['province2'] . ' ' . $plan['postcode']; ?></sapn> </h4>
				<h4><?php if ($plan['status_id'] < 2) { ?>QUOTE<?php } else {?>POLICY<?php } ?>: <span><?php echo $plan['policy']; ?></span></h4>
				<h4>FOR: <span><?php if($plan['isfamilyplan']==1){ echo "Family";}elseif($plan['isfamilyplan']==2){ echo "Group";}else{echo "Individual";} ?></sapn> </h4>
			</div>
			<div class="col-sm-6 nopadding receipt-info">
				<h4>INSURANCE PREMIUM: <span>$<?php echo number_format($plan['premium'], 2, '.', ',');?></sapn> </h4>
				<h4>PAYER NAME: <span><?php echo $plan['beneficiary']; ?> </h4><br />
				<h4>EFFECTIVE DATE: <span><?php echo $plan['effective_date']; ?></sapn> </h4>
				<h4>EXPIRATION DATE: <span><?php echo $plan['expiry_date']; ?></sapn> </h4>
			</div>
		</div>
		<div style="border-bottom:1px dotted;margin:20px 0;"></div>
		<div class="row">
			<div style="float:left;width:80px;">
				<img class="img-responsive" style="width:70px;" src="<?php echo base_url();?>image/jf_logo.jpg" />
			</div>
			<div style="float:left;margin-top:5px;" class="receipt-info">
				<p class="topp">Johnson Fu Insurance Agency Inc.</p>
				<p class="topp">15 Wertheim Court, Suite 501, Richmond Hill, ON, L4B3H7</p>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12 nopm text-right">
				<h4 class="nopm">TAX RECEIPT</h4>
			</div>
		</div>
		<div style="border-bottom:3px solid #eee;margin:10px auto 20px;"></div>
		<div class="row">
			<div class="col-sm-6 nopadding receipt-info">
				<h4>DATE: <span><?php echo isset($payment['added']) ? $payment['added'] : ''; ?></span></h4>
				<h4>NAME: <span><?php echo $customer['firstname'] . " " . $customer['lastname']; ?></span></h4>
				<h4>ADDRESS: <span><?php if(!empty($plan['suite_number'])){echo  $plan['suite_number'] . "- ";} ?><?php echo $plan['street_number'] . ' ' . $plan['street_name'] . ' ' . $plan['city'] . ', ' . $plan['province2'] . ' ' . $plan['postcode']; ?></sapn> </h4>
				<h4><?php if ($plan['status_id'] < 2) { ?>QUOTE<?php } else {?>POLICY<?php } ?>: <span><?php echo $plan['policy']; ?></span></h4>
				<h4>FOR: <span><?php if($plan['isfamilyplan']==1){ echo "Family";}elseif($plan['isfamilyplan']==2){ echo "Group";}else{echo "Individual";} ?></sapn> </h4>
			</div>
			<div class="col-sm-6 nopadding receipt-info">
				<h4>INSURANCE PREMIUM: <span>$<?php echo number_format($plan['premium'], 2, '.', ',');?></sapn> </h4>
				<h4>PAYER NAME: <span><?php echo $plan['beneficiary']; ?></sapn> </h4><br />
				<h4>EFFECTIVE DATE: <span><?php echo $plan['effective_date']; ?></sapn> </h4>
				<h4>EXPIRATION DATE: <span><?php echo $plan['expiry_date']; ?></sapn> </h4>
			</div>
		</div>
		<hr />
	</div>
</body>
</html>
