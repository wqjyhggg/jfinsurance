<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
?>
		<table width='100%' style="display: block; font-family: serif; font-size: 10pt;">
			<tbody>
				<tr>
					<td width='10%'>
						<img style="width: 80px;" src="<?php echo base_url();?>image/jf_logo.jpg" />
					</td>
					<td width='40%'>
						<?php if ($data ['agent'] ['receive_type'] == 'Cheque') { ?>
						<table>
							<tr>
								<td valign='top'>To:</td>
								<td>
									<?php echo $data['agent']['firstname'] . " " . $data['agent']['lastname']; ?><br />
									<?php echo $data['agent']['mail_address']; ?><br>
									<?php echo $data ['agent'] ['mail_city'] . "," . $data ['agent'] ['mail_province2']; ?><br>
									<?php echo $data ['agent'] ['mail_postcode']; ?><br>
								</td>
							</tr>
						</table>
						<?php } ?>
						Agent Name: <?php echo $data['agent']['firstname'] . " " . $data['agent']['lastname']; ?><br />
						Payment Method: <?php echo $data['agent']['receive_type']; ?><br />
						<?php
							if ($data ['agent'] ['receive_type'] == 'Deposit') {
								echo "Pay to: " . $data ['agent'] ['note'] . "<br />";
								echo "E-Mail Address: " . $data ['agent'] ['email'];
							} else if ($data ['agent'] ['receive_type'] == 'Cheque') {
								echo "Pay to: " . $data ['agent'] ['note'] . "<br />";
							} else { // Cash
							}
						?>
					</td>
					<td width='50%' align='right'>
						<H1 style="font-size: 36px;">Commission Report</H1><br />
						<div>For Period: <?php echo $payment_added_from . " - " . $payment_added_to; ?>&nbsp;&nbsp;&nbsp;</div>
					</td>
				</tr>
			</tbody>
		</table>
		<table style="font-family: serif; font-size: 10pt; border-spacing: 0;" border='1'>
			<tbody>
				<tr>
					<th style="border: 1px solid black;">&nbsp;</th>
					<th style="border: 1px solid black;">Payment Date</th>
					<th>Policy Number</th>
					<th>Customer Name</th>
					<th>Effective Date</th>
					<th>Expiry Date</th>
					<th>Trip Length</th>
					<th>Premium Amount</th>
					<th>Premium Payment</th>
					<th>Commission Rate</th>
					<th>Commission Amount</th>
				</tr>
				<?php $cnt = 1; $total_premium = 0; $total_commission = 0; $unpaid_premium = 0; ?>
				<?php foreach ($data['data'] as $record) : ?>
				<?php     $total_premium += $record['premium']; $total_commission += $record['amount']; $unpaid_premium += ($record['premiumispaid']) ? 0 : $record['premium']; ?>
				<tr>
					<td style="padding-top: 6px;"><?php echo $cnt++; ?></td>
					<td style="padding-top: 6px;"><?php echo substr($record['added'], 0, 10); ?></td>
					<td style="padding-top: 6px;"><?php echo $record['policy']; ?></td>
					<td style="padding-top: 6px;"><?php echo $record['customer_name']; ?></td>
					<td style="padding-top: 6px;"><?php echo $record['effective_date']; ?></td>
					<td style="padding-top: 6px;"><?php echo $record['expiry_date']; ?></td>
					<td style="padding-top: 6px;"><?php echo $record['total_days']; ?></td>
					<td style="padding-top: 6px;">$<?php echo number_format($record['premium'], 2); ?></td>
					<td style="padding-top: 6px;"><?php echo ($record['premiumispaid']) ? "Paid" : '-'; ?></td>
					<td style="padding-top: 6px;"><?php echo $record['rate']; ?>%</td>
					<td style="padding-top: 6px;">$<?php echo number_format($record['amount'], 2); ?></td>
				</tr>
				<?php endforeach; ?>
				<tr>
					<td style="padding-top: 10px;"><B>Unpaid Premium</B></td>
					<td style="padding-top: 10px;">&nbsp;</td>
					<td style="padding-top: 10px;">&nbsp;</td>
					<td style="padding-top: 10px;">&nbsp;</td>
					<td style="padding-top: 10px;">&nbsp;</td>
					<td style="padding-top: 10px;">&nbsp;</td>
					<td style="padding-top: 10px;">&nbsp;</td>
					<td style="padding-top: 10px;">$<?php echo number_format($unpaid_premium, 2); ?></td>
					<td style="padding-top: 10px;">&nbsp;</td>
					<td style="padding-top: 10px;">&nbsp;</td>
					<td style="padding-top: 10px;">$<?php echo number_format($total_commission, 2); ?> - $<?php echo number_format($unpaid_premium, 2); ?></td>
				</tr>
				<tr>
					<td style="padding-top: 10px;"><B>TOTAL</B></td>
					<td style="padding-top: 10px;">&nbsp;</td>
					<td style="padding-top: 10px;">&nbsp;</td>
					<td style="padding-top: 10px;">&nbsp;</td>
					<td style="padding-top: 10px;">&nbsp;</td>
					<td style="padding-top: 10px;">&nbsp;</td>
					<td style="padding-top: 10px;">&nbsp;</td>
					<td style="padding-top: 10px;">$<?php echo number_format($total_premium, 2); ?></td>
					<td style="padding-top: 10px;">&nbsp;</td>
					<td style="padding-top: 10px;">&nbsp;</td>
					<td style="padding-top: 10px;">$<?php echo number_format($total_commission - $unpaid_premium, 2); ?></td>
				</tr>
			</tbody>
		</table>
