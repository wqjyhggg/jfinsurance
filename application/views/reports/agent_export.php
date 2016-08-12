<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<form method="get" action="<?php echo $export_list ?>" class="form-horizontal">
	<input type='hidden' name="agent_id" value="<?php echo $agent_id; ?>" />
	<input type='hidden' name="product_short" value="<?php echo $product_short; ?>" />
	<input type='hidden' name="application_date_from" value="<?php echo $application_date_from; ?>" />
	<input type='hidden' name="application_date_to" value="<?php echo $application_date_to; ?>" />
	<input type='hidden' name="create_date_from" value="<?php echo $create_date_from; ?>" />
	<input type='hidden' name="create_date_to" value="<?php echo $create_date_to; ?>" />
	<input type='hidden' name="effective_date_from" value="<?php echo $effective_date_from; ?>" />
	<input type='hidden' name="effective_date_to" value="<?php echo $effective_date_to; ?>" />
	<input type='hidden' name="payment_update_date_from" value="<?php echo $payment_update_date_from; ?>" />
	<input type='hidden' name="payment_update_date_to" value="<?php echo $payment_update_date_to; ?>" />
	<input type='submit' name="agent_id" value="Export Xlsx" />
</form>