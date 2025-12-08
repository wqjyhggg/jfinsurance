<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!-- Content without left menu -->
<div class="container" style="padding:0 0 40px 0;">
	<div class="main" style="min-height:800px;">
		<div class="h-topdiv">
		<div class="row" style="margin:40px 0;">
				<div class="col-sm-12 text-center">
					<a class='btn btn-primary pull-right' href="<?php echo $back_url; ?>"><?php echo $this->lang->line("Back"); ?></a></label>
				</div>
			</div>
			<div class="row" style="margin:40px 0;">
				<div class="col-sm-12 text-center">
					<div style="font-size: 10px;">First Pay: $<?php echo $month_amount; ?> and Recurring Pay: $<?php echo $month_amount; ?> x <?php echo $pay_times; ?></div>
				</div>
			</div>
			<div class="row" style="margin:40px 0;">
				<div class="col-sm-12 text-center">
					<iframe src="<?php echo $monthly_pay_url; ?>" title="Monthly payment"></iframe>
				</div>
			</div>
    </div>
	</div>	
</div>
<script type="text/javascript">
$(document).ready(function() {
	let 
	$('#ali_div').click(function() {
		setTimeout(function() { 
			$.ajax({
				url: "<?php echo $get_plan_status_url; ?>",
				type: 'GET',
				success: function(data, textStatus, jqXHR) {
					if (data != "WAIT") {
						window.location = '<?php echo $back_url; ?>'
					}
				},
			});
		}, 10000);
	});
});
</script>
