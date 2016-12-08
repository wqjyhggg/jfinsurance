<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
?>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>Claim Letter</title>
	<?php echo $style;?>
</head>
<body>
	<header>
	</header>
	<div class="container">
		<div class="row">
			<div width="100%" align="middle"><img class="img-responsive" src="<?php echo base_url();?>image/logo.jpg" /></div>
		</div>
		<div class="row" style="margin-top: -20px;">
			<div>
				<table border="0">
					<tr><td style='padding-right: 30px;'>  To: </td><td><?php echo $fullname; ?></td></tr>
					<tr><td style='padding-right: 30px;'>&nbsp;</td><td><?php echo $street_number . ' ' . $street_name . ', ' . $suite_number; ?></td></tr>
					<tr><td style='padding-right: 30px;'>&nbsp;</td><td><?php echo $city . ', ' . $province2; ?></td></tr>
					<tr><td style='padding-right: 30px;'>&nbsp;</td><td><?php echo $postcode; ?></td></tr>
				</table>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12"><?php echo date("M j, Y"); ?></div>
		</div>
		<div class="row">
			<div class="col-sm-12 nopadding">To whom it may concern:</div>
		</div>
		<div class="row">
			<div class="col-sm-12 nopadding">Enclosed is the claim check for full settlement of the subject insured and policy.</div>
		</div>
		<div class="row">
			<div class="col-sm-12 nopadding">
				<table>
					<tr><td style='padding-left: 20px; padding-right: 30px;'><B>Policy Number:</B></td><td><?php echo $plan['policy']; ?></td></tr>
					<tr><td style='padding-left: 20px; padding-right: 30px;'><B>Policy Holder:</B></td><td><?php echo $claim['lastname'] . ", " . $claim['firstname']; ?></td></tr>
					<tr><td style='padding-left: 20px; padding-right: 30px;'><B>Coverage Period:</B></td><td><?php echo  $plan['effective_date'] . " To " . $plan['expiry_date']; ?></td></tr>
				</table>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12 nopadding">
				<table class="bordered">
					<thead>
						<tr>
							<th>Service</th>
							<th>Date of Service</th>
							<th>Claim Amount</th>
							<th>Payable Amount</th>
							<th>Claim Notes</th>
						</tr>
					</thead>
					<tbody>
					<?php foreach ($itemlist as $item) { ?>
						<tr>
							<td><?php echo $item['description']; ?></td>
							<td><?php echo $item['service']; ?></td>
							<td><?php echo '$ ' . number_format($item['claimed'], 2); ?></td>
							<td><?php echo '$ ' . number_format($item['paid'], 2); ?></td>
							<td><?php echo $item['note']; ?></td>
						</tr>
					<?php } ?>
						<tr>
							<td colspan='2'><h4>Total</h4></td>
							<td><?php echo '$ ' . number_format($claimed_total, 2); ?></td>
							<td><?php echo '$ ' . number_format($claimed_paid, 2); ?></td>
							<td>&nbsp;</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12">
				<p style="margin-top: -10px;">If you have any questions, please contact me at 905-707-1512.<br /><br />Thank you,<br /><br />For an on behalf of</p>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12">
				<p style="margin-top: -10px; border-bottom: solid; padding-bottom: 30px;display: inline-block; " width='250px'><b>JF Insurance Agency Group Inc.</b></p>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12" style='margin-top: -38px;'>Rene Ren</div>
		</div>
		<div class="row">
			<div class="col-sm-12">* This  claim  is  paid  on  a  “without  prejudice”  basis.  Additional  information  may  be  required  for further  claims.</div>
		</div>
		<div class="row">
			<div class="col-sm-12">
				<hr>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12">
				<table cellpadding="5px" style="font-size: 10px; margin-top: -20px; line-height: 2px;">
					<tbody>
						<tr>
							<td>15 Wertheim Court, Suite 501, Richmond Hill, ON, L4B 3H7</td>
							<td>Tel: (905) 707-1512</td>
							<td>Fax: (905) 707-1513</td>
							<td>Toll Free Tel: 1-877-832-5541</td>
						</tr>
						<tr>
							<td>128 - 6061 no. 3 road, Richmond, BC, V6Y 2B2</td>
							<td>Tel: (604) 232-0896</td>
							<td>Fax: (604) 232-0897</td>
							<td>Toll Free Tel: 1-888-988-3268</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</body>
</html>