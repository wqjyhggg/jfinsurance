<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!-- Content without left menu -->
<div class="container">
	<div class="row">
		<div class="col-sm-12 text-center">
			<label>First Pay: $<?php echo $month_amount; ?> and Recurring Pay: $<?php echo $month_amount; ?> x <?php echo $pay_times; ?></label>
			<a class='btn btn-primary pull-right' href="<?php echo $back_url; ?>">Back</a>
		</div>
	</div>
	<div class="row" style="margin:40px 0;">
		<div class="col-sm-12 iframe-container">
			<iframe src="<?php echo $monthly_pay_url; ?>" title="Monthly payment"></iframe>
		</div>
	</div>
</div>
<style>
.container {
	padding: 10px;
}
.iframe-container {
  position: relative;
  width: 100%;
  height: 650px;
  overflow: hidden;
	border: 1px solid #ccc;
}
iframe {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  border: none;
}
.btn {
	margin-right: 40px;
}
.navbar {
	min-height: 2px;
} 
</style>
<script type="text/javascript">
$(document).ready(function() {
	function get_plan_status() { 
		$.ajax({
			url: "<?php echo $get_plan_status_url; ?>",
			type: 'GET',
			success: function(data, textStatus, jqXHR) {
				if (data != "WAIT") {
					window.location = '<?php echo $back_url; ?>'
				} else {
					setTimeout(get_plan_status, 10000);
				}
			},
		});
	}
	setTimeout(get_plan_status, 10000);
});
</script>
