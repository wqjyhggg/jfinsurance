
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
									<label class="col-sm-12">First Name:</label>
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
									<label class="col-sm-12">Last Name:</label>
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
								<div class="col-sm-12"><hr></div>
							</div>

							<div class="row">
								<div class="col-sm-6">
									<div class="row">
										<div class="form-group col-sm-12">
											<h2 class="edit-title">Change Logo</h2>
										
											<div class="input-group col-sm-12">
												
												<!--div class="custom-file-upload">
												    <!--<label for="file">File: </label>--> 
												    <!--input type="file" id="file" name="myfiles[]" multiple />
												</div-->



												<input type='file' name='logo_img' value='' class="form-control">
							                	<?php if (!empty($error_myname_logo)){ ?>
												<div class="alert-error">	
													<?php echo $error_myname_logo; ?>
												</div>
												<?php } ?>
												<br />
												<img class="img-responsive logo-img" src='<?php echo $logo_src; ?>'>
							               
											</div>
										</div>
									</div>
									<div class="row">
										<div class="form-group col-sm-12">
											<h2 class="edit-title">Part 1</h2>

											<label class="col-sm-12">Background Image:(suggest image size: 1700px * 440px)</label>
											<div class="input-group col-sm-12">
												<input type='file' name='image_img' value='' class="form-control">
							                	<?php if (!empty($error_myname_image)){ ?>
												<div class="alert-error">	
													<?php echo $error_myname_image; ?>
												</div>
												<?php } ?>
												<br />
												<img class="img-responsive bg-img" src='<?php echo $image_src; ?>'>
							                </div>
										</div>
										<div class="form-group col-sm-12">
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
										<div class="form-group col-sm-12">
											<label class="col-sm-12">Description(around 520 characters):</label>
											<div class="input-group col-sm-12">
												<textarea rows='8' type='text' name='top_desc' class="form-control"><?php echo $top_desc; ?></textarea> 
							                	<?php if (!empty($error_top_desc)){ ?>
												<div class="alert-error">	
													<?php echo $error_top_desc; ?>
												</div>
												<?php } ?>
							                </div>
										</div>
										<hr>
									</div>
									<div class="row">	
										<div class="col-sm-12">
											<h2 class="edit-title">Part 2</h2>
										</div>
										<div class="form-group col-sm-6">
											
											<label class="col-sm-12">Title:</label>
											<div class="input-group col-sm-12">
												<input type='text' name='p2_title' value='<?php echo "$p2_title"; ?>' class="form-control">
							                	
							                </div>
										</div>
										<div class="form-group col-sm-6">
											<label class="col-sm-12">Short Description:</label>
											<div class="input-group col-sm-12">
												<input type='text' name='p2_short_desc' value='<?php echo "$p2_short_desc"; ?>' class="form-control">
							                	
							                </div>
										</div>
										<div class="form-group col-sm-12">
											<label class="col-sm-12">Description(around 340 characters):</label>
											<div class="input-group col-sm-12">
												<textarea rows='8' type='text' name='top_desc' class="form-control"><?php echo '$p2_desc'; ?></textarea> 
							                	
							                </div>
										</div>
									</div>
									<div class="row">
										<div class="col-sm-12">
											<h2 class="edit-title">Part 3 (Page bottom information)</h2>
										</div>
										<div class="form-group col-sm-12">
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
										<div class="form-group col-sm-12">
											<label class="col-sm-12">Text 1:</label>
											<div class="input-group col-sm-12">
												<input type='text' name='address' value='<?php echo $address; ?>' class="form-control">
							                	<?php if (!empty($error_address)){ ?>
												<div class="alert-error">	
													<?php echo $error_address; ?>
												</div>
												<?php } ?>
							                </div>
										</div>
										<div class="form-group col-sm-12">
											<label class="col-sm-12">Text 2:</label>
											<div class="input-group col-sm-12">
												<input type='text' name='city_province' value='<?php echo $city_province; ?>' class="form-control">
							                	<?php if (!empty($error_city_province)){ ?>
												<div class="alert-error">	
													<?php echo $error_city_province; ?>
												</div>
												<?php } ?>
							                </div>
										</div>
										
									</div>
									<div class="row">
										<div class="form-group col-sm-12">
											<h2 class="edit-title">QR Code Image</h2>

											<div class="input-group col-sm-12">
													
													<!--div class="custom-file-upload">
													    <!--<label for="file">File: </label>--> 
													    <!--input type="file" id="file" name="myfiles[]" multiple />
													</div-->



												<input type='file' name='qr_img' value='' class="form-control">
								               
												<br />
												<img class="img-responsive qr-img" src='#'>
								            </div>
										</div>
									</div>
									
								</div>

								<div class="col-sm-6">
									<h2 class="edit-title">Page Template (Click on image get large view)</h2>
									<a target="_blank" href="<?php echo base_url();?>image/jf-agent.png"><img class="template-page" src="<?php echo base_url();?>image/jf-agent.png" alt="template page">
									</a>
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

<script>
          
          ;(function($) {

          // Browser supports HTML5 multiple file?
          var multipleSupport = typeof $('<input/>')[0].multiple !== 'undefined',
              isIE = /msie/i.test( navigator.userAgent );

          $.fn.customFile = function() {

            return this.each(function() {

              var $file = $(this).addClass('custom-file-upload-hidden'), // the original file input
                  $wrap = $('<div class="file-upload-wrapper">'),
                  $input = $('<input type="text" class="file-upload-input" />'),
                  // Button that will be used in non-IE browsers
                  $button = $('<button type="button" class="file-upload-button">Select a File</button>'),
                  // Hack for IE
                  $label = $('<label class="file-upload-button" for="'+ $file[0].id +'">Select a File</label>');

                  // Hide by shifting to the left so we
                  // can still trigger events
                  $file.css({
                    position: 'absolute',
                    left: '-9999px'
                  });

                  $wrap.insertAfter( $file )
                    .append( $file, $input, ( isIE ? $label : $button ) );

                  // Prevent focus
                  $file.attr('tabIndex', -1);
                  $button.attr('tabIndex', -1);

                  $button.click(function () {
                    $file.focus().click(); // Open dialog
                  });

                  $file.change(function() {

                    var files = [], fileArr, filename;

                    // If multiple is supported then extract
                    // all filenames from the file array
                    if ( multipleSupport ) {
                      fileArr = $file[0].files;
                      for ( var i = 0, len = fileArr.length; i < len; i++ ) {
                        files.push( fileArr[i].name );
                      }
                      filename = files.join(', ');

                    // If not supported then just take the value
                    // and remove the path to just show the filename
                    } else {
                      filename = $file.val().split('\\').pop();
                    }

                    $input.val( filename ) // Set the value
                      .attr('title', filename) // Show filename in title tootlip
                      .focus(); // Regain focus

                  });

                  $input.on({
                    blur: function() { $file.trigger('blur'); },
                    keydown: function( e ) {
                      if ( e.which === 13 ) { // Enter
                        if ( !isIE ) { $file.trigger('click'); }
                      } else if ( e.which === 8 || e.which === 46 ) { // Backspace & Del
                        // On some browsers the value is read-only
                        // with this trick we remove the old input and add
                        // a clean clone with all the original events attached
                        $file.replaceWith( $file = $file.clone( true ) );
                        $file.trigger('change');
                        $input.val('');
                      } else if ( e.which === 9 ){ // TAB
                        return;
                      } else { // All other keys
                        return false;
                      }
                    }
                  });

                });

              };


          }(jQuery));

          $('input[type=file]').customFile();
</script>