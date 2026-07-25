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
				<p  class="nopm"><?php echo htmlspecialchars($customer['firstname']) . " " . htmlspecialchars($customer['lastname']); ?></p>
				<p  class="nopm"><?php if(!empty($plan['suite_number'])){echo  "Suite " . htmlspecialchars($plan['suite_number']) . " ";} ?><?php echo htmlspecialchars($plan['street_number']) . ' ' . htmlspecialchars($plan['street_name']) . '<br />' . htmlspecialchars($plan['city']) . ', ' . htmlspecialchars($plan['province2']) . ', ' . htmlspecialchars($plan['postcode']); ?></p>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12 nopadding">
				<p  class="nopm">Dear <?php echo htmlspecialchars($customer['firstname']) . " " . htmlspecialchars($customer['lastname']); ?>,</p>
				<p  class="nopm">We have processed your request to refund the policy of <span><b><?php echo $plan['policy']; ?></b></span>, <b><?php echo htmlspecialchars($customer['firstname']) . " " . htmlspecialchars($customer['lastname']); ?></b>. We are pleased to provide you a refund for the policy.</p>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-12 nopadding">
				<p  class="nopm">Please find summary details below for this refund:</p>
				<table class="bordered">
				<?php if (empty($monthly_data)) { ?>
					<thead>
						<tr>
							<th colspan="2">Policy Refund to the Insured:</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>Original Premium: </td><td><span>$<?php echo number_format($plan['premium'], 2, '.', ',');?></span></td>
						</tr>
						<tr>
							<td>Used Premium: </td><td><span>$<?php echo number_format(((float)$plan['premium'] - (float)$refund_amount), 2, '.', ','); ?></span></td>
						</tr>
						<tr>	
							<td>Un-used Premium: </td><td><span>$<?php echo number_format($refund_amount, 2, '.', ','); ?></span></td>
						</tr>
						<tr>	
							<td>Minus Cancellation Fee: </td><td><span>$<?php echo number_format($admin_fee, 2, '.', ','); ?></span></td>
						</tr>
						<tr>	
							<td>Total Refund: </td><td><span>$<?php echo number_format($total_amount, 2, '.', ','); ?></span></td>
						</tr>
					</tbody>
				<?php } else { ?>
					<thead>
						<tr>
							<th colspan="3">Policy Refund to the Insured:</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>Original Premium: </td><td></td><td><span>$<?php echo number_format($monthly_data['premium'], 2, '.', ',');?></span></td>
						</tr>
						<tr>
							<td>&nbsp;</td><td></td><td><span>&nbsp;</span></td>
						</tr>
						<tr>	
							<td>Paid Premium: </td><td><?php echo round($monthly_data["paid_premium"]/$monthly_data["monthly_pay"]); ?> months</td><td><span>$<?php echo number_format($monthly_data["paid_premium"], 2, '.', ','); ?></span></td>
						</tr>
						<tr>
							<td>Paid Monthly Plan Fee: </td><td></td><td><span>+$<?php echo number_format($monthly_data["admin_fee"], 2, '.', ','); ?></span></td>
						</tr>
						<tr>
							<td>Total Charged: </td><td></td><td><span>$<b><?php echo number_format($monthly_data["total_paid"], 2, '.', ','); ?></b></span></td>
						</tr>
						<!-- <tr>	
							<td>Refund Admin Fee: </td><td></td><td><span>-$<?php echo number_format($monthly_data["refund_record"]["extra_admin_fee"], 2, '.', ','); ?></span></td>
						</tr> -->
						<tr>
							<td>Total Refund: </td><td><?php echo round($monthly_data["paid_premium"]/$monthly_data["monthly_pay"]); ?> months</td><td><span>$<b><?php echo number_format($monthly_data["total_paid"]-$monthly_data["refund_record"]["extra_admin_fee"], 2, '.', ','); ?></b></span></td>
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
  <?php if ($plan["monthlypay"]) { ?>
  <pagebreak />
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
				<p  class="nopm"><?php echo htmlspecialchars($customer['firstname']) . " " . htmlspecialchars($customer['lastname']); ?></p>
				<p  class="nopm"><?php if(!empty($plan['suite_number'])){echo  "Suite " . htmlspecialchars($plan['suite_number']) . " ";} ?><?php echo htmlspecialchars($plan['street_number']) . ' ' . htmlspecialchars($plan['street_name']) . '<br />' . htmlspecialchars($plan['city']) . ', ' . htmlspecialchars($plan['province2']) . ', ' . htmlspecialchars($plan['postcode']); ?></p>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12 nopadding">
				<p  class="nopm">Madame, Monsieur <?php echo htmlspecialchars($customer['firstname']) . " " . htmlspecialchars($customer['lastname']); ?>,</p>
				<p  class="nopm">Nous avons traité votre demande de remboursement de la police <span><b><?php echo $plan['policy']; ?></b></span>, <b><?php echo htmlspecialchars($customer['firstname']) . " " . htmlspecialchars($customer['lastname']); ?></b>. Nous avons le plaisir de vous accorder un remboursement pour cette police.</p>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-12 nopadding">
				<p  class="nopm">Veuillez trouver ci-dessous un résumé des détails de ce remboursement:</p>
				<table class="bordered">
				<?php if (empty($monthly_data)) { ?>
					<thead>
						<tr>
							<th colspan="2">Remboursement de la police à l’assuré:</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>Prime initiale: </td><td><span>$<?php echo number_format($plan['premium'], 2, '.', ',');?></span></td>
						</tr>
						<tr>
							<td>Prime payée: </td><td><span>$<?php echo number_format(((float)$plan['premium'] - (float)$refund_amount), 2, '.', ','); ?></span></td>
						</tr>
						<tr>	
							<td>Premium non utilisé: </td><td><span>$<?php echo number_format($refund_amount, 2, '.', ','); ?></span></td>
						</tr>
						<tr>	
							<td>Frais mensuels du plan payés: </td><td><span>$<?php echo number_format($admin_fee, 2, '.', ','); ?></span></td>
						</tr>
						<tr>	
							<td>Total facturé: </td><td><span>$<?php echo number_format($total_amount, 2, '.', ','); ?></span></td>
						</tr>
					</tbody>
				<?php } else { ?>
					<thead>
						<tr>
							<th colspan="3">Remboursement de la police à l’assuré:</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>Prime initiale: </td><td></td><td><span>$<?php echo number_format($monthly_data['premium'], 2, '.', ',');?></span></td>
						</tr>
						<tr>
							<td>&nbsp;</td><td></td><td><span>&nbsp;</span></td>
						</tr>
						<tr>	
							<td>Prime payée: </td><td><?php echo round($monthly_data["paid_premium"]/$monthly_data["monthly_pay"]); ?> months</td><td><span>$<?php echo number_format($monthly_data["paid_premium"], 2, '.', ','); ?></span></td>
						</tr>
						<tr>
							<td>Frais mensuels du plan payés: </td><td></td><td><span>+$<?php echo number_format($monthly_data["admin_fee"], 2, '.', ','); ?></span></td>
						</tr>
						<tr>
							<td>Total facturé: </td><td></td><td><span>$<b><?php echo number_format($monthly_data["total_paid"], 2, '.', ','); ?></b></span></td>
						</tr>
						<!-- <tr>	
							<td>Refund Admin Fee: </td><td></td><td><span>-$<?php echo number_format($monthly_data["refund_record"]["extra_admin_fee"], 2, '.', ','); ?></span></td>
						</tr> -->
						<tr>
							<td>Remboursement total: </td><td><?php echo round($monthly_data["paid_premium"]/$monthly_data["monthly_pay"]); ?> months</td><td><span>$<b><?php echo number_format($monthly_data["total_paid"]-$monthly_data["refund_record"]["extra_admin_fee"], 2, '.', ','); ?></b></span></td>
						</tr>
					</tbody>
					<?php } ?>
				</table>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12 nopadding">
			<p>
				Veuillez trouver ci-joint un chèque correspondant au montant indiqué sous « Remboursement total ».<br />
				En acceptant ce remboursement, <b>JF Insurance Agency Group Inc.</b> ne sera plus responsable de toute réclamation relative à cette police.
			</p>
		</div>
		<br />
		<div class="row">
			<div class="col-sm-12 nopadding">
				<p>Cordialement,</p>
			</div>
		</div>	
		<br />
		<div class="row">
			<div class="col-sm-12 nopadding">
				<p><span style="border-top:1px solid #777;">Pour et au nom de</span><br /> JF Insurance Agency Group Inc.</p>
			</div>
		</div>		
		<div class="row">
			<div class="col-sm-12 nopadding text-center">
				<hr class="nopm"/>
				<p class="text-center;">Siège social: 15 Wertheim Court, Suite 501, Richmond Hill, Ontario L4B 3H7</p>
				<p class="text-center;">Téléphone: <u>905-707-1512</u> Télécopieur:<u>905-707-1513</u> Sans frais:<u>1-877-832-5541</u></p>
			
			</div>
		</div>
	</div><!-- End Container -->
  <?php } ?>
</body>
</html>
