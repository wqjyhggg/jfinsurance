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

						<?php if (!empty($errormsg)) { ?>
						<div class="alert-error">
							<?php echo $errormsg; ?><br />
						</div>
						<?php } ?>
						<?php if (!empty($successmsg)) { ?>
						<div class="alert-error">
							<?php echo $successmsg; ?><br />
						</div>
						<?php } ?>
          				<form action="<?php $action_url; ?>" method="post" enctype="multipart/form-data" class="form-horizontal form-label-left">
							<input type='hidden' name='<?php echo $csrf['name']; ?>' value='<?php echo $csrf['value']; ?>'>
							<div class="row">
								<div class="form-group col-sm-4">
									<label class="col-sm-12">School</label>
									<select class="form-control" name='user_id'>
										<option value=''>-- Choose School --</option>
										<?php foreach ($schools as $key => $value) { ?>                            
		                            	<option value='<?php echo $key?>' <?php echo ($user_id == $key) ? 'selected' : ''; ?>><?php echo $value; ?></option>
										<?php } ?>
		                          	</select>
								</div>
								<div class="form-group col-sm-4">
									<label class="col-sm-12">Insurance Product</label>
									<select class="form-control" name='product_short'>
										<option value=''>-- Choose Product --</option>
										<?php foreach ($products as $key => $value) { ?>                            
		                           		<option value='<?php echo $key?>' <?php echo ($product_id == $key) ? 'selected' : ''; ?>><?php echo $value['full_name']; ?></option>
										<?php } ?>
		                        	</select>
								</div>
							</div>
							<hr />
							<div class="row">
								<div class="form-group col-sm-8">
									<input id="uploadFile" placeholder="Choose File" />
									<div class="fileUpload btn btn-primary">
										<span>Select File</span>
										<input id="uploadBtn" type="file" class="upload" name="userfile" size="20"/>
									</div>
									<script>
										document.getElementById("uploadBtn").onchange = function () {
										document.getElementById("uploadFile").value = this.value;
									};
									</script>
									<input style="display: inline-block; vertical-align: bottom;" class="btn btn-primary" type="submit" name='submit' value="Upload" />
								</div>
							</div>
							
						</form>
					</div>
				</div>
			</div>
		</div>

	</div>
</div>
<!-- /page content -->
