<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
?>
		<table width='100%' style="display: block; font-family: serif; font-size: 10pt;">
			<tbody>
				<tr>
					<td width='10%'>
						<img style="width: 80px;" src="<?php echo base_url();?>image/jf_logo.jpg" />
					</td>
					<td width='55%'>
						Agent Name: <?php echo $data['agent']['firstname'] . " " . $data['agent']['lastname']; ?><br />
						Payment Method: <?php echo $data['agent']['receive_type']; ?><br />
						<?php
							if ($data ['agent'] ['receive_type'] == 'Deposit') {
								echo "Pay to: " . $data ['agent'] ['note'] . "<br />";
								echo "Mailing Address: " . $data ['agent'] ['mail_address'] . " " . $data ['agent'] ['mail_city'] . "," . $data ['agent'] ['mail_province2'] . " " . $data ['agent'] ['mail_postcode'];
							} else if ($data ['agent'] ['receive_type'] == 'Cheque') {
								echo "Pay to: " . $data ['agent'] ['note'] . "<br />";
							} else { // Cash
							}
						?>
					</td>
					<td width='35%'>
						<H2>Commission Report</H2><br />
						For Period: <?php echo $payment_added_from . " - " . $payment_added_to; ?>
					</td>
				</tr>
			</tbody>
		</table>
		<table style="font-family: serif; font-size: 10pt; border-spacing: 0;" border='1'>
			<tbody>
				<tr>
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
				<?php $cnt = 1; $total_premium = 0; $total_commission = 0; ?>
				<?php foreach ($data['data'] as $record) : ?>
				<?php     $total_premium += $record['premium']; $total_commission += $record['amount']; ?>
				<tr>
					<td style="border: 1px solid black;"><?php echo substr($record['added'], 0, 10); ?></td>
					<td><?php echo $record['policy']; ?></td>
					<td><?php echo $record['customer_name']; ?></td>
					<td><?php echo $record['effective_date']; ?></td>
					<td><?php echo $record['expiry_date']; ?></td>
					<td><?php echo $record['total_days']; ?></td>
					<td>$<?php echo number_format($record['premium'], 2); ?></td>
					<td><?php echo ($record['premiumispaid']) ? "Paid" : '-'; ?></td>
					<td><?php echo $record['rate']; ?>%</td>
					<td>$<?php echo number_format($record['amount'], 2); ?></td>
				</tr>
				<?php endforeach; ?>
				<tr>
					<td><B>TOTAL</B></td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>$<?php echo number_format($total_premium, 2); ?></td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>$<?php echo number_format($total_commission, 2); ?></td>
				</tr>
			</tbody>
		</table>
