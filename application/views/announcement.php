<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<script src='<?php echo base_url(); ?>js/editor.js'></script>
<link href="<?php echo base_url(); ?>/stylesheet/editor.css" type="text/css" rel="stylesheet"/>
<div class="right_col" role="main">
	<div class="container">
		<div class="row">
			<div class="col-sm-12">
				<textarea id="mytextarea" name="html"></textarea>
				<div class="main">
					<form method="post" action="<?php echo $save_url; ?>" enctype="multipart/form-data">
					<input type='hidden' name='<?php echo $csrf['name']; ?>' value='<?php echo $csrf['value']; ?>'>
					<textarea id="textdata" name="textdata" style="display: none;"></textarea>
					<div><input type="submit" id="send" value="Submit"></div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
$(document).ready(function() {
	$("#mytextarea").Editor();
	$("#mytextarea").Editor("setText", '<?php echo $html; ?>');
	$("#send").click(function(){
		var txt = $("#mytextarea").Editor("getText");
		$("#textdata").val(encodeURIComponent(txt));
		return true;
	});
});
</script>
