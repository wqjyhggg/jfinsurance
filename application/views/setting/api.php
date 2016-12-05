<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
?>

<!-- Upload policy page content -->

<!-- Content top navigation -->
<div class="top_nav">
	<div class="nav_menu">
		<nav class="" role="navigation">
			<div class="nav toggle">
				<a id="menu_toggle"><i class="fa fa-bars"></i></a>
			</div>
		</nav>
	</div>
</div>
<!-- Content top navigation End-->

<!-- page content -->
<div class="right_col" role="main">
	<div class="">
		<div class="page-title">
			<div class="title_left">
				<h3>Upload Policy</h3>
			</div>
		</div>
		<div class="clearfix"></div>
		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="x_panel">
					<div class="x_title">
						<h2>
							API<small></small>
						</h2>
						<div class="clearfix"></div>
					</div>
					<div class="x_content">
						<br />

						<div id='alert_message' class="alert-error">
						<?php if (!empty($errormsg)) { echo $errormsg . "<br />"; } ?>
						<?php if (!empty($successmsg)) { echo $successmsg . "<br />"; } ?>
						</div>
						<table class="table table-hover table-bordered">
							<thead>
								<tr>
									<th>API IP</th>
									<th>API Key</th>
									<th>&nbsp;</th>
								</tr>
							</thead>
							<tbody>
                        <?php foreach ($api as $c) { ?>
                            	<tr>
									<td><?php echo $c['name']; ?></td>
									<td><?php echo $c['value']; ?></td>
									<td><button class='removekey' data-id='<?php echo $c['setting_id'];?>'>Remove</button></td>
								</tr>
                        <?php } ?>    
                        </tbody>
						</table>
						<form id='newkeyval' action='<?php echo $add_url; ?>'>
							<input type='hidden' name='type' value='api'>
							IP Address: <input type='text' name='name' value='' maxlength='64'>
							Key: <input type='text' name='value' value='' maxlength='128'>
							<input type='submit' value='Add'>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
$( document ).ready(function() {
	$('.removekey').click( function(e) {
		var setting_id = $(this).attr("data-id");
		$.ajax({
			url: '<?php echo $delete_url; ?>',
			data: {setting_id: setting_id},
			type: 'POST',
			dataType: 'json',
			error: function(jqXHR, textStatus, errorThrown) {
				location.reload();
			},
			success: function(rt) {
				if (rt.errormsg) {
					$('#alert_message').html(rt.errormsg);
				} else if (rt.successmsg) {
					location.reload();
				}
			},
		});
	});

	$('#newkeyval').submit( function(e) {
		e.preventDefault();
		var data = $(this).serializeArray(); // <-- 'this' is your form element
		console.log(data);
		$('#newkeyval').hide();
		$.ajax({
			url: '<?php echo $add_url; ?>',
			data: data,
			type: 'POST',
			dataType: 'json',
			error: function(jqXHR, textStatus, errorThrown) {
				$('#newkeyval').show();
				if(textStatus==="timeout") {
					alert("File is too big to process. Please as admin for double check"); //Handle the timeout
				} else {
					alert("Somthing is wrong your file may cause some system error. Please contact admin"); //Handle other error type
				}
			},
			success: function(rt) {
				if (rt.errormsg) {
					$('#alert_message').html(rt.errormsg);
				} else if (rt.successmsg) {
					location.reload();
				}
			},
		});
	});
});
</script>
<!-- /page content -->
