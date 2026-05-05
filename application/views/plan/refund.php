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
			<div style="width:390px; margin:0 auto;">
				<div style="float:left;width:90px;">
					<img class="img-responsive" style="width:80px;" src="<?php echo base_url();?>image/jf_logo.jpg" />
				</div>
				<div style="float:left;width:300px;text-align:center;">
					<h3 style="margin-bottom:0;">JF Insurance Agency Group Inc.</h3>
					<h3 style="margin-top:0;">www.jfgroup.ca</h3>
				</div>
			</div>
		</div><br /><br /><br />
		<div class="row">
			<div class="col-sm-12 nopadding">
				<p class="nopm"><?php echo date("F j, Y");?></p>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12 nopadding">
				<p  class="nopm"><?php echo $customer_full_name; ?></p>
				<p  class="nopm">
					<?php echo $full_address; ?><br />
					<?php echo $city . ', ' . $province2 . ', ' . $postcode; ?></p>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12 nopadding">
				<p  class="nopm">Dear <?php echo htmlspecialchars($customer['firstname'] . " " . $customer['lastname']); ?>,</p>
				<?php if (empty($plan["monthlypay"])) { ?>
					<p  class="nopm">We have processed your request to refund the policy of <span><b><?php echo $plan['policy']; ?></b></span>, <b><?php echo htmlspecialchars($customer['firstname'] . " " . $customer['lastname']); ?></b>. We are pleased to provide you a refund for the policy.</p>
				<?php } else { ?>
					<p  class="nopm">
						We have processed your request to refund the policy of <span><b><?php echo $plan['policy']; ?></b></span>, 
						<b><?php echo htmlspecialchars($customer['firstname'] . " " . $customer['lastname']); ?></b>. 
						We are pleased to provide you a refund for the policy. Please note that the $80 monthly plan fee and 2 months of premium are nonefundable on a Monthly Payment Plan.
					</p>
				<?php } ?>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-12 nopadding">
				<p  class="nopm">Please find summary details below for this refund:</p>
				<table class="bordered">
				<?php if (!empty($plan["monthlypay"])) { ?>
						<thead>
							<tr>
								<th colspan="3">Policy Refund to the Insured:</th>
							</tr>
						</thead>
						<?php if (empty($monthly_data) || empty($monthly_data["refund_record"]) ) { ?>
							<tbody>
								<tr>
									<td colspan="3">Missing Refund Record</td>
								</tr>
							<tbody>
						<?php } else { ?>
							<tbody>
								<tr>
									<td>Original Premium: </td><td></td><td><span>$<?php echo number_format($monthly_data['premium'], 2, '.', ',');?></span></td>
								</tr>
								<tr>	
									<td>Paid Premium: </td><td><?php echo $monthly_data["refund_record"]["paid_month"]; ?></td><td><span>$<?php echo number_format($monthly_data["refund_record"]["charged_amount"], 2, '.', ','); ?></span></td>
								</tr>
								<tr>	
									<td>Paid Monthly Plan Fee: </td><td></td><td><span>+$<?php echo number_format($monthly_data["refund_record"]["admin_fee"], 2, '.', ','); ?></span></td>
								</tr>
								<tr>	
									<td>Total Charged: </td><td></td><td><span>$<?php echo number_format($monthly_data["refund_record"]["charged_amount"]+$monthly_data["refund_record"]["admin_fee"], 2, '.', ','); ?></span></td>
								</tr>
								<tr>	
									<td>Monthly Plan Fee (non-refundable): </td><td></td><td><span>-$<?php echo number_format($monthly_data["refund_record"]["admin_fee"], 2, '.', ','); ?></span></td>
								</tr>
								<tr>	
									<td>Used Premium: </td><td><?php echo $monthly_data["refund_record"]["used_month"]; ?></td><td><span>-$<?php echo number_format($monthly_data["refund_record"]["used_month"]*$monthly_data["monthly_pay"], 2, '.', ','); ?></span></td>
								</tr>
								<tr>	
									<td>Refund Admin Fee: </td><td></td><td><span>-$<?php echo number_format($monthly_data["refund_record"]["extra_admin_fee"], 2, '.', ','); ?></span></td>
								</tr>
								<tr>	
									<td>Total Refund: </td><td><?php echo ($monthly_data["refund_record"]["paid_month"] - $monthly_data["refund_record"]["used_month"]); ?></td><td><span>$<?php echo number_format($monthly_data["refund_record"]["refund_amount"] - $monthly_data["refund_record"]["extra_admin_fee"], 2, '.', ','); ?></span></td>
								</tr>
								<tr>	
									<td colspan="3"><?php echo "From: ".$plan['effective_date']." to ".$plan['refund_date']." (count as ".$monthly_data["refund_record"]["used_month"]." months)"; ?></td>
								</tr>
							</tbody>
						<?php } ?>
					<?php } else { ?>
					<thead>
						<tr>
							<th colspan="2">Policy Refund to the Insured:</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>Original Premium: </td><td><span>$<?php echo number_format($plan['premium'], 2, '.', ',');?></span></td>
						</tr>
						<?php if (0) { ?>
						<tr>
							<td>Paid Premium: </td><td><span>$<?php echo number_format($paid_premium, 2, '.', ',');?></span></td>
						</tr>
						<?php } ?>
						<tr>
							<td>Used Premium: </td><td><span>$<?php echo number_format($used_premium, 2, '.', ','); ?></span></td>
						</tr>
						<tr>
							<td>Monthly Plan Admin Fee: </td><td><span>$<?php echo number_format($admin_fee, 2, '.', ','); ?></span></td>
						</tr>
						<tr>	
							<td>Total Refund: </td><td><span>$<?php echo number_format($total_amount, 2, '.', ','); ?></span></td>
						</tr>
					</tbody>
					<?php } ?>
				</table>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12 nopadding">
			<p>
				Please find enclosed a cheque with the amount stated for "Total Refund".<br />
				By accepting this refund, <b>JF Insurance Agency Group Inc.</b> will no longer be liable for any claims on this policy.
			</p>
		</div>
		<br />
		<div class="row">
			<div class="col-sm-12 nopadding">
				<p>Sincerely,</p>
			</div>
		</div>	
		<br />
		<div class="row">
			<div class="col-sm-12 nopadding">
				<p><span style="border-top:1px solid #777;">For and on behalf of</span><br /> JF Insurance Agency Group Inc.</p>
			</div>
		</div>		
		<div class="row">
			<div class="col-sm-12 nopadding text-center">
				<hr class="nopm"/>
				<p class="text-center;">Head Office: 15 Wertheim Court, Suite 501, Richmond Hill, Ontario L4B 3H7</p>
				<p class="text-center;">Phone: <u>905-707-1512</u> Fax:<u>905-707-1513</u> Toll free:<u>1-877-832-5541</u></p>
			
			</div>
		</div>
	</div><!-- End Container -->
</body>
</html>
