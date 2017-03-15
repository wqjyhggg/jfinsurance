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
							Upload Form<small></small>
						</h2>
						<div class="clearfix"></div>
					</div>
					<div class="x_content">
						<br />

						<div id='alert_message' class="alert-error"></div>
          				<form action="<?php echo $action_url; ?>" id='uploadform' method="post" enctype="multipart/form-data" class="form-horizontal form-label-left">
							<input type='hidden' name='<?php echo $csrf['name']; ?>' value='<?php echo $csrf['value']; ?>'>
							<div class="row">
								<div class="form-group col-sm-4">
									<input id="uploadFile" name="userfilename" value="" placeholder="Choose File" />
									<div class="fileUpload btn btn-primary">
										<span>Select File</span>
										<input id="uploadBtn" type="file" class="upload" name="userfile" size="20"/>
									</div>
								</div>
								<div class="form-group col-sm-2">
									<div id='submitbutton'>
										<input style="display: inline-block; vertical-align: bottom;" class="btn btn-primary" type="submit" name='submit' value="Upload" />
									</div>
									<div id='loading'>
										Processing......
									</div>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="x_panel">
					<div class="x_title">
						<h2>
							Down Load<small></small>
						</h2>
						<div class="clearfix"></div>
					</div>
					<div class="x_content">
						<br />
						<div class="row">
	          				<form action="<?php echo $download_url; ?>" id='downloadform' method="get" class="form-horizontal form-label-left">
							<div class="form-group col-sm-4">
								<div>
									<span>Batch Number: </span>
									<input id="download" type="number" name="batch_number" size="20"/>
								</div>
							</div>
							<div class="form-group col-sm-2">
								<div id='submitbutton'>
									<input style="display: inline-block; vertical-align: bottom;" class="btn btn-primary" type="submit" name='submit' value="Download" />
								</div>
							</div>
	          				</form>
						</div>
					</div>
				</div>
			</div>
		</div>

	</div>
</div>
<script>
$( document ).ready(function() {
	$('#submitbutton').show();
	$('#loading').hide();

	document.getElementById("uploadBtn").onchange = function () {
		$('#alert_message').html('');
		document.getElementById("uploadFile").value = this.value;
	};

	$('#uploadform').submit( function(e) {
		e.preventDefault();
		$('#submitbutton').hide();
		$('#loading').show();
		$('#alert_message').html('');
		var data = new FormData(this); // <-- 'this' is your form element
		$.ajax({
			url: '<?php echo $process_url; ?>',
			data: data,
			cache: false,
			contentType: false,
			processData: false,
			timeout: 600000,	// 10 mintes 
			type: 'POST',
			//dataType: 'json',
			error: function(jqXHR, textStatus, errorThrown) {
				$('#submitbutton').show();
				$('#loading').hide();
				if(textStatus==="timeout") {
					alert("File is too big to process. Please as admin for double check"); //Handle the timeout
				} else {
					alert("Somthing is wrong your file may cause some system error. Please contact admin"); //Handle other error type
				}
			},
			success: function(rt) {
				$('#submitbutton').show();
				$('#loading').hide();
				if (rt.errormsg) {
					$('#alert_message').html(rt.errormsg);
				} else if (rt.successmsg) {
					alert(rt.successmsg);
					window.location=rt.succ_url;
				}
			},
		});
	});
});
</script>
<!-- /page content -->
