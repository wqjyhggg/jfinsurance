<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<form method="get" action="<?php echo $export_list ?>" class="form-horizontal">
	<input type='hidden' name="agent_id" value="<?php echo $agent_id; ?>" />
	<input type='hidden' name="product_short" value="<?php echo $product_short; ?>" />
	<input type='hidden' name="application_date_from" value="<?php echo $application_date_from; ?>" />
	<input type='hidden' name="application_date_to" value="<?php echo $application_date_to; ?>" />

	<?php if(isset($arrival_date_from)){ ?>
	<input type='hidden' name="arrival_date_from" value="<?php echo $arrival_date_from; ?>" />
	<?php } ?>
	<?php if(isset($arrival_date_to)){ ?>
	<input type='hidden' name="arrival_date_to" value="<?php echo $arrival_date_to; ?>" />
	<?php } ?>
	<input type='hidden' name="effective_date_from" value="<?php echo $effective_date_from; ?>" />
	<input type='hidden' name="effective_date_to" value="<?php echo $effective_date_to; ?>" />
	<?php if(isset($expiry_date_from)){ ?>	
	<input type='hidden' name="expiry_date_from" value="<?php echo $expiry_date_from; ?>" />
	<?php } ?>
	<?php if(isset($arrival_date_to)){ ?>
	<input type='hidden' name="expiry_date_from" value="<?php echo $expiry_date_to; ?>" />
	<?php } ?>
	<input class="btn btn-info" type='submit' value="Export Xlsx" />
</form>