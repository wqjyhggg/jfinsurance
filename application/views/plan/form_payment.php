<div class="col-sm-12">
	<button type="button" class="btn btn-info" data-toggle="collapse" data-target="#history1"> Payments <span class="fa fa-chevron-down"></span></button>
	<div id="history1">
       	<button type="button" class="btn btn-payment-sort" data-type="date">Sort By Date</button>
       	<button type="button" class="btn btn-payment-sort" data-type="type">Sort By Type</button>
		<form action='<?php echo $makepay_url; ?>' method='POST'
			class="form-horizontal">
			<input type='hidden' name='<?php echo $csrf['name']; ?>'
				value='<?php echo $csrf['value']; ?>'>
			<div class="table-responsive">
				<table class="table table-hover table-bordered">
					<thead>
						<tr>
							<th>&nbsp;</th>
							<th>Date</th>
							<th>Type</th>
							<th>Pay Type</th>
							<th>Amount</th>
							<th>Rate</th>
							<th>Pay Status</th>
							<th>Info</th>
							<th>Notes</th>
						</tr>
					</thead>
					<tbody>
<?php
foreach ( $payments as $p ) {
	$pay_str = '';
	if ($p['pay_type'] == 'up_commission') continue;
	if ($p['pay_type'] == 'refund_up_commission') continue;
	if ($p['pay_type'] == 'cancel_up_commission') continue;
	
	$sbstr = substr($p['pay_type'], 0, 6);
	if ($p['ispaid']) {
		if (($sbstr == 'refund') || ($sbstr == 'cancel')) {
			$pay_str = 'N / A';
		} else {
			$pay_str = 'Paid';
		}
	} else {
		if ($sbstr == 'refund') {
			$pay_str = "<a href='" . $revert_url . $p['payment_id'] . "'>Revert Refund</a>";
		} else if ($sbstr == 'cancel') {
			$pay_str = "<a href='" . $revert_url . $p['payment_id'] . "'>Revert Cancel</a>";
		} else {
			$pay_str = '-';
		}
	}
	$pay_info = '';
	if (! empty($p['invoice_num'] )) $pay_info .= "[" . $p['invoice_num'] . "]";
	if (! empty($p['bank_name'] )) $pay_info .= "[" . $p['bank_name'] . "]";
	if (! empty($p['payor_name'] )) $pay_info .= "[" . $p['payor_name'] . "]";
	if (! empty($p['cheque_number'] )) $pay_info .= "[" . $p['cheque_number'] . "]";
	if (! empty($p['pay_to'] )) $pay_info .= "[" . $p['pay_to'] . "]";
	if (! empty($p['name'] )) $pay_info .= "[" . $p['name'] . "]";
	if (! empty($p['first5'] )) $pay_info .= "[" . $p['first5'] . "]";
	if (! empty($p['last4'] )) $pay_info .= "[" . $p['last4'] . "]";
	if (! empty($p['expiry_month'] )) $pay_info .= "[" . $p['expiry_month'] . "]";
	if (! empty($p['expiry_year'] )) $pay_info .= "[" . $p['expiry_year'] . "]";
?>
						<tr>
							<td><?php if (empty($p['ispaid'])) { ?><input type='checkbox' name='payment[]' value='<?php echo $p['payment_id']; ?>'><?php } ?></td>
							<td><?php echo $p['last_update']; ?></td>
							<td><?php echo $p['pay_type']; ?></td>
							<td><?php echo $p['pay_mothed']; ?></td>
							<td><?php echo $p['amount']; ?></td>
							<td><?php echo $p['rate'] . "%"; ?></td>
							<td><?php echo $pay_str; ?></td>
							<td><?php echo $pay_info; ?></td>
							<td><?php echo (strlen($p['note']) > 60) ? (htmlspecialchars(substr($p['note'], 0, 57)) . "...") : htmlspecialchars($p['note']); ?></td>
						</tr>
<?php } ?>
<?php if (!empty($total_payment)) { ?>
<?php     foreach($total_payment as $k => $t) { 
			if ($k == 'up_commission') continue;
			if ($k == 'refund_up_commission') continue;
			if ($k == 'cancel_up_commission') continue;
?>
						<tr>
							<td>&nbsp;</td>
							<td><?php echo $k; ?></td>
							<td colspan='7'><?php echo number_format($t, 2); ?></td>
						</tr>
<?php    } ?>
<?php } ?>
					</tbody>
				</table>
			</div>
			<div class="row">
				<div class="col-sm-12">
					<input type="submit" class="btn btn-primary" name='submit' value='Make Pay'>
				</div>
			</div>
		</form>
		<hr />
	</div>
</div>
