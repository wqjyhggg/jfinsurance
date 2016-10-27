
<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
?>
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
	<div class="main-div">
		<div class="page-title">
			<div class="title_left">
				<h3>My Home Information</h3>
			</div>
		</div>
		<div class="clearfix"></div>
		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="x_panel">
					<div class="x_title">
						<h2>Update Information<small></small></h2>
						<div class="clearfix"></div>
					</div>
					<div class="x_content">
						<?php if (!empty($success_message)) { ?>
						<div class="alert-error">
							<?php echo $success_message; ?>
						<br />
						</div>
						<?php } ?>
	                	<?php if (!empty($error_myname)){ ?>
						<div class="alert-error">	
							<?php echo $error_myname; ?>
						</div>
						<?php } ?>
						<form action='<?php echo $action_url; ?>' method='POST' class="form-horizontal" enctype="multipart/form-data">
							<input type='hidden' name='<?php echo $csrf['name']; ?>' value='<?php echo $csrf['value']; ?>'>
							<input type='hidden' name='user_id' value='<?php echo $user_id; ?>'>
							<input type='hidden' name='myname' value='<?php echo $myname; ?>'>
							<div class="row">
								<div class="form-group col-sm-3">
									<label class="col-sm-12">Firstname:</label>
									<div class="input-group col-sm-12">
										<input type='text' name='firstname' value='<?php echo $firstname; ?>' class="form-control">
					                	<?php if (!empty($error_firstname)){ ?>
										<div class="alert-error">	
											<?php echo $error_firstname; ?>
										</div>
										<?php } ?>			                	
					                </div>
								</div>
								<div class="form-group col-sm-3">
									<label class="col-sm-12">Lastname:</label>
									<div class="input-group col-sm-12">
										<input type='text' name='lastname' value='<?php echo $lastname; ?>' class="form-control" class="form-control">
					                	<?php if (!empty($error_lastname)){ ?>
										<div class="alert-error">	
											<?php echo $error_lastname; ?>
										</div>
										<?php } ?>			                	
					                </div>
								</div>
								<div class="form-group col-sm-6">
									<label class="col-sm-12">Url:</label>
									<div id='showmyname' class="input-group col-sm-12"></div>
								</div>
							</div>

							<div class="row">
								<div class="form-group col-sm-3">
									<label class="col-sm-12">Top Title:</label>
									<div class="input-group col-sm-12">
										<input type='text' name='top_title' value='<?php echo $top_title; ?>' class="form-control">
					                	<?php if (!empty($error_top_title)) { ?>
										<div class="alert-error">	
											<?php echo $error_top_title; ?>
										</div>
										<?php } ?>
					                </div>
								</div>
								<div class="form-group col-sm-9">
									<label class="col-sm-12">Description:</label>
									<div class="input-group col-sm-12">
										<textarea rows='8' type='text' name='top_desc' class="form-control"><?php echo $top_desc; ?></textarea> 
					                	<?php if (!empty($error_top_desc)){ ?>
										<div class="alert-error">	
											<?php echo $error_top_desc; ?>
										</div>
										<?php } ?>
					                </div>
								</div>
							</div>
							<div class="row">
								<div class="form-group col-sm-3">
									<label class="col-sm-12">Foot Title:</label>
									<div class="input-group col-sm-12">
										<input type='text' name='foot_title' value='<?php echo $foot_title; ?>' class="form-control">
					                	<?php if (!empty($error_foot_title)){ ?>
										<div class="alert-error">	
											<?php echo $error_foot_title; ?>
										</div>
										<?php } ?>
					                </div>
								</div>
								<div class="form-group col-sm-3">
									<label class="col-sm-12">Address Row:</label>
									<div class="input-group col-sm-12">
										<input type='text' name='address' value='<?php echo $address; ?>' class="form-control">
					                	<?php if (!empty($error_address)){ ?>
										<div class="alert-error">	
											<?php echo $error_address; ?>
										</div>
										<?php } ?>
					                </div>
								</div>
								<div class="form-group col-sm-3">
									<label class="col-sm-12">City Row:</label>
									<div class="input-group col-sm-12">
										<input type='text' name='city_province' value='<?php echo $city_province; ?>' class="form-control">
					                	<?php if (!empty($error_city_province)){ ?>
										<div class="alert-error">	
											<?php echo $error_city_province; ?>
										</div>
										<?php } ?>
					                </div>
								</div>
								<div class="form-group col-sm-3">
									<label class="col-sm-12">Post Code Row:</label>
									<div class="input-group col-sm-12">
										<input type='text' name='post_code' value='<?php echo $post_code; ?>' class="form-control">
					                	<?php if (!empty($error_post_code)){ ?>
										<div class="alert-error">	
											<?php echo $error_post_code; ?>
										</div>
										<?php } ?>
					                </div>
								</div>
							</div>
							<div class="row">
								<div class="form-group col-sm-3">
									<label class="col-sm-12">Phone Row:</label>
									<div class="input-group col-sm-12">
										<input type='text' name='phone' value='<?php echo $phone; ?>' class="form-control">
					                	<?php if (!empty($error_phone)){ ?>
										<div class="alert-error">	
											<?php echo $error_phone; ?>
										</div>
										<?php } ?>
					                </div>
								</div>
								<div class="form-group col-sm-3">
									<label class="col-sm-12">Fax Row:</label>
									<div class="input-group col-sm-12">
										<input type='text' name='fax' value='<?php echo $fax; ?>' class="form-control">
					                	<?php if (!empty($error_fax)){ ?>
										<div class="alert-error">	
											<?php echo $error_fax; ?>
										</div>
										<?php } ?>
					                </div>
								</div>
								<div class="form-group col-sm-3">
									<label class="col-sm-12">Toll Free Row:</label>
									<div class="input-group col-sm-12">
										<input type='text' name='toll_free' value='<?php echo $toll_free; ?>' class="form-control">
					                	<?php if (!empty($error_toll_free)){ ?>
										<div class="alert-error">	
											<?php echo $error_toll_free; ?>
										</div>
										<?php } ?>
					                </div>
								</div>
								<div class="form-group col-sm-3">
									<label class="col-sm-12">Email Row:</label>
									<div class="input-group col-sm-12">
										<input type='text' name='email' value='<?php echo $email; ?>' class="form-control">
					                	<?php if (!empty($error_email)){ ?>
										<div class="alert-error">	
											<?php echo $error_email; ?>
										</div>
										<?php } ?>
					                </div>
								</div>
							</div>
							<div class="row">
								<div class="form-group col-sm-3">
									<label class="col-sm-12">Logo:</label>
									<div class="input-group col-sm-12">
										<input type='file' name='logo_img' value='' class="form-control">
					                	<?php if (!empty($error_myname_logo)){ ?>
										<div class="alert-error">	
											<?php echo $error_myname_logo; ?>
										</div>
										<?php } ?>
										<br />
										<img class="img-responsive" src='<?php echo $logo_src; ?>' width='100%'>
					                </div>
								</div>
								<div class="form-group col-sm-9">
									<label class="col-sm-12">Background:</label>
									<div class="input-group col-sm-12">
										<input type='file' name='image_img' value='' class="form-control">
					                	<?php if (!empty($error_myname_image)){ ?>
										<div class="alert-error">	
											<?php echo $error_myname_image; ?>
										</div>
										<?php } ?>
										<br />
										<img class="img-responsive" src='<?php echo $image_src; ?>' width='1500px'>
					                </div>
								</div>
							</div>

							<div class="row" style="margin-bottom: 200px;">
								<!-- submit button -->
								<div class="col-sm-12">
									<input class="btn btn-primary pull-right" type='submit' value='<?php echo ($user_id) ? "Update" : "Add"; ?>'>
								</div>
								<!-- submit button -->
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- /page content -->

<script>
function load_myname() {

	$.ajax({
		url: '<?php echo $myname_url; ?>',
		type: 'GET',
		data: { firstname : $("input[name='firstname']").val(), lastname : $("input[name='lastname']").val(), },
		success: function(data, textStatus, jqXHR) {
        	$('#showmyname').html('<?php echo $myhome_url; ?>' + '/' + data);
        	$("input[name='myname']").val(data);
    	},
	});
}
$( document ).ready(function() {
	$("input[name='firstname'], input[name='lastname']").change(function() {
		console.log("SSS");  //XXXXXXXXX
		load_myname();
	});
	load_myname();
});
</script>
