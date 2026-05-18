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
				<p class="nopm"><b>Termination of Insurance Policy Due to Nonpayment</b></p>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12 nopadding">
				<p  class="nopm">Dear <?php echo htmlspecialchars($customer['firstname'] . " " . $customer['lastname']); ?>,</p>
				<p  class="nopm">
					We are writing in regard to your insurance policy <span><b><?php echo $plan['policy']; ?></b> which was purchased on <b><?php echo $plan['apply_date']; ?></b></span>.
					<?php if (!empty($monthly_data) && !empty($monthly_data["refund_record"]) ) { ?>
					Our records indicate that payment for this policy was not received on <b><?php echo $monthly_data['first_pay_fail_date']; ?></b>, and 
					we were unable to reach you to resolve it within 30 days. As a result, your policy was terminated and will now expiry on <b><?php echo $monthly_data['last_available_date']; ?></b>
					<?php } else { ?>
					We were unable to reach you to resolve it within 30 days. As a result, your policy was terminated and will now expiry on <b><?php echo $plan['expiry_date']; ?></b>
					<?php } ?>
				</p>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-12 nopadding">
				<table class="bordered">
					<thead>
						<tr>
							<th colspan="3">Revised policy expiry date:</th>
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
								<td>&nbsp;</td><td>&nbsp;</td><td><span>&nbsp;</span></td>
							</tr>
							<tr>	
								<td>Paid Premium: </td><td><?php echo $monthly_data["refund_record"]["paid_month"]; ?> months</td><td><span>$<?php echo number_format($monthly_data["refund_record"]["charged_amount"], 2, '.', ','); ?></span></td>
							</tr>
							<tr>	
								<td>&nbsp;</td><td>&nbsp;</td><td><span>&nbsp;</span></td>
							</tr>
							<tr>	
								<td>Unpaid Premium: </td><td><?php echo $monthly_data["refund_record"]["unpaid_month"]; ?> months</td><td><span>$<?php echo number_format($monthly_data['premium'] - $monthly_data["refund_record"]["charged_amount"], 2, '.', ','); ?></span></td>
							</tr>
							<tr>	
								<td>&nbsp;</td><td>&nbsp;</td><td><span>&nbsp;</span></td>
							</tr>
							<tr>	
								<td>Original Expiry Date: </td><td>&nbsp;</td><td><span><?php echo $monthly_data["refund_record"]["origi_expiry_date"]; ?></span></td>
							</tr>
							<tr>	
								<td>New Expiry Date: </td><td>&nbsp;</td><td><span><?php echo $monthly_data["refund_record"]["expiry_date"]; ?></span></td>
							</tr>
						</tbody>
					<?php } ?>
				</table>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12 nopadding">
				If you believe this termination is in error or if you have any questions, please contact your agent immediately to discuss possible options.<br />
				Please contact us at <b>(905) 707-1512</b> or by email at info@jfgroup.ca.<br />
				Thank you for your immediate attention to this matter.<br />
		</div>
		<div class="row">
			<div class="col-sm-12 nopadding">
				Sincerely,
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
				<div class="text-center;">Head Office: 15 Wertheim Court, Suite 501, Richmond Hill, Ontario L4B 3H7</div>
				<div class="text-center;">Phone: <u>905-707-1512</u> Fax:<u>905-707-1513</u> Toll free:<u>1-877-832-5541</u></div>
			</div>
		</div>
	</div><!-- End Container -->
</body>
</html>
