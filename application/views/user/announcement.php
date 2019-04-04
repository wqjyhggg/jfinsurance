
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="container">
	<div class="row">
		<div class="col-sm-12" id="popuppage" style="width:100%;height:100%; min-height:700px;">
			<?php echo $html; ?>
		</div>
	</div>
</div>
<script >
$('document').ready(function() {
	$('#leftMenu').hide();
});
</script>