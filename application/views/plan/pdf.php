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
			<div style="float:left;width:90px;">
				<img class="img-responsive" style="width:80px;" src="<?php echo base_url();?>image/jf_logo.png" />
			</div>
			<div style="float:left;width:400px;">
				<p class="topp" style="font-weight:bold;"> JF Agent - <?php echo ($user) ? $user['firstname'] . " " . $user['lastname'] : ''; ?></p>
				<p class="topp">228-340 Ferrier Street, Markham Ontario L3R 2Z5</p>
				<p class="topp">Tel: 647-993-8826</p>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12 text-right">
				<h2 style="margin:0;">Confirmation of Insurance</h2>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12 nopadding">
				<h4 style="margin:0;"><u>Policy Details</u></h4>
			</div>
		</div>
		<!--div class="row">
			<h4 class="col-sm-6 nopadding"><?php if ($plan['status_id'] < 2) { ?>Quote<?php } else {?>Policy<?php } ?> Number: <span><?php echo $plan['policy']; ?></span></h4>
			<h4 class="col-sm-6 nopadding" style="margin-top:0px;">Policy Status: <?php echo $status_list[$plan['status_id']]['name']; ?></h4>
		</div-->
		<!--div>
			<div class="p-detail"-->
				<!-- policy detail -->
				<div class="row">
					<div class="col-sm-6 nopadding">
						<h4>Policy Holder: <span><?php echo $customer['firstname'] . " " . $customer['lastname']; ?></span></h4>
						<h4>Date of Birth: <span><?php echo $customer['birthday'];?></sapn> </h4>
						<h4>Address: <span><?php if(!empty($plan['suite_number'])){echo  $plan['suite_number'] . "- ";} ?><?php echo $plan['street_number'] . ' ' . $plan['street_name'] . ' ' . $plan['city'] . ', ' . $plan['province2'] . ' ' . $plan['postcode']; ?></sapn> </h4>
						<h4>Phone Number: <span><?php echo $plan['phone1'];?></sapn> </h4>
						<h4>Email: <span><?php echo $plan['contact_email'];?></sapn> </h4>
					</div>
					<div class="col-sm-6 nopadding">
						<h4><?php if ($plan['status_id'] < 2) { ?>Quote<?php } else {?>Policy<?php } ?> Number: <span><?php echo $plan['policy']; ?></span></h4>
						<h4>Application Date: <span><?php echo $plan['apply_date'];?></sapn> </h4>
						<h4>Effective Date: <span><?php echo $plan['effective_date']; ?></sapn> </h4>
						<h4>Expiry Date: <span><?php echo $plan['expiry_date']; ?></sapn> </h4>
						<h4>Number of Days: <span><?php echo $plan['totaldays'];?></sapn> </h4>
					</div>
				</div>
				<!-- end policy detail -->
				<hr/>

				<!-- Coverage and payment Details-->
				<div class="row">
					<div class="col-sm-6 nopadding">
						<h4><u>Coverage Details</u></h4>
						<h4>Insurance Plan: <span><?php echo $customer['firstname'] . " " . $customer['lastname'];?></span></h4>
						<h4>Plan Type: <span><?php echo $customer['birthday'];?></sapn> </h4>
						<h4>Sum Insured: <span><?php echo $plan['sum_insured']; ?></sapn> </h4>
						<h4>Deductible: <span><?php echo $plan['deductible_amount'];?></sapn> </h4>
						<h4>Beneficiary: <span><?php echo $plan['beneficiary'];?></sapn> </h4>
					</div>
					<div class="col-sm-6 nopadding">
						<h4><u>Payment Details</u></h4>
						<h4>Total Permium: <span>$<?php echo number_format($plan['premium'], 2, '.', ',');?></span></h4>
						<h4>Taxes: <span><?php echo 'N / A';?></sapn> </h4>
						<h4>Premium: <span>$<?php echo number_format($plan['premium'], 2, '.', ','); ?></sapn> </h4>
						<h4>Payment Date: <span><?php echo isset($payment['added']) ? date('Y-m-d', strtotime($payment['added'])) : ''; ?></sapn> </h4>
						<h4>Payment Method: <span><?php echo isset($payment['pay_mothed']) ? $payment['pay_mothed'] : ''; ?></sapn> </h4>
					</div>
				</div>
				<!-- End coverage adn payment details -->
				
				
			<!--/div--><!-- end p-detail -->
		<!--/div--><!-- x_content -->
		<div class="row">
			<div class="col-sm-12 nopm">
				<p class="small">If you notice any errors in the above information or have any questions, please contact JF Insurance Agency Group Inc.</p>
			</div>
			<div class="col-sm-6 nopm">
				<p class="small">Ontario:</p>
				<p class="small">15 Wertheim Court, Suite 501,</p>
				<p class="small">Richmond Hill, ON, Canada L4B 3H7</p>
				<p class="small">Phone: 905-707-1512 Or 1-877-832-5541</p>
			</div>
			<div class="col-sm-6 nopm">
				<p class="small">British Columbia:</p>
				<p class="small">128 - 6061 No. 3 Road</p>
				<p class="small">Richmond, BC, Canadian V6Y 282</p>
				<p class="small">Phone: 604-232-0896 Or 1-877-232-0896</p>
			</div>
		</div>
	</div><!-- End Container -->
</body>
</html>