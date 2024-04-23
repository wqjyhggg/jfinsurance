<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
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
              </div>

            </div>
            <div class="clearfix"></div>
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <!--div class="x_title">
                    
                  </div-->
                  <div class="x_content">
                    <br />
                    <div class="main" style="min-height:800px;padding-bottom:50px;" >

                  <!-- Content with left menu -->
                  <form action='<?php echo $action_url; ?>' method='POST' class="form-horizontal" enctype="multipart/form-data">
                    <div class="row"><div class="col-sm-12 text-right">
                      <input  class="btn btn-primary" type='submit' value='update'><hr />
                    </div></div>  
                  		<input type='hidden' name='<?php echo $csrf['name']; ?>' value='<?php echo $csrf['value']; ?>'>
                  		<input type='hidden' name='user_id' value='<?php echo $user_id; ?>'>
                  		<input type='hidden' name='myname' value='<?php echo $myname; ?>'>
                  		<div class="row">
                  				<div class="form-group col-sm-2">
                  				</div>
                  				<div class="form-group col-sm-10">
                  					<B>Your Url:</B> <?php echo $myhome_url . "/" . $myname; ?>
                  				</div>
                  				<div class="col-sm-12"><hr>
                  			</div>
                  		</div>
                   		<div class="hlogo">
                     		<div style="text-align: center;">
                    			<img style='max-width: 390px; width: auto; max-height: 70px; position: relative;' id='logo_image_life' src="<?php echo base_url('agent/img') . '/' . $logo_src; ?>" alt="JF Insurance">
                    			<br />
                    			<input style='border:1px solid #ddd;max-width: 390px; width: auto; max-height: 70px; position: relative; display: inline-block;' id='logo_image' type='file' name='logo_src'> <?php echo $this->lang->line('Modify Logo Image'); ?>
                     		</div>
                  		</div>
		                  <br />
                      <hr />
                      <div class="lh-topdiv" style="min-height: 200px;border:1px dashed #ddd;">
                        <img id='big_image_life' class="img-responsive" src="<?php echo base_url('agent/img') . '/' . $image_src; ?>" alt="JF Insurance">
                        <input class="mypbg-img" type='file' id='big_image' name='image_src' value='' style='border:1px solid #ddd;'>
                        <p  class="mypbg-label" ><?php echo $this->lang->line('Modify Background Image'); ?></p>
                        <div class="lh-top" >
                              <h1><input type='text' name='top_title' value='<?php echo $top_title; ?>' class="form-control"></h1>
                              <textarea style="width: 100%;color: #333;" rows='5' name='top_desc'><?php echo $top_desc; ?></textarea>
                            </div>
                      </div>
                      <div class="row" style="margin:40px 0;">
                        <div class="col-sm-4 col-sm-offset-4 text-center">
                          <h3><input type='text' name='about_title' value='<?php echo $about_title; ?>' class="form-control"></h3>
                          <h4><input type='text' name='about_short' value='<?php echo $about_short; ?>' class="form-control"></h4>
                          <hr style="border-bottom:2px solid; width:50px; margin:0 auto 20px;" />
                        </div>
                      </div>
                      <div class="row" style="margin:0 0 40px;">
                        <div class="col-sm-8 col-sm-offset-2">    
                          <textarea style="width: 100%;" rows='4' name='about_desc'><?php echo $about_desc; ?></textarea>
                        </div>
                      </div>
 
                      <div class="row" style="margin-right:0;margin-left:0;">
                        <div class="col-sm-12" style="padding-right:0;padding-left:0;">
                          <div style="background-color:#f6f6f6; padding:15px;margin:20px auto;">
                            <div class="row">
      			                  <div class="x_title">
      			                    <h2><?php echo $this->lang->line("Our Plans"); ?><small></small></h2>
      			                    <div class="clearfix"></div>
      			                  </div>
      			                  <div class="x_content">
      			<?php foreach ($file_url as $product_short => $farr) { ?>                    
      			                    <div class="row dfile-list">
      			                      <div class="col-sm-12">
      			                        <br />
      			                        <label><?php echo $product_short; ?> --- <?php echo $farr['fullname']; ?></label>
      			                      </div>
      			                      <div class="col-sm-12">
      			<?php 	foreach ($farr['files'] as $rc) { ?>                      
      			                        <a class="d_brochure" target="_blank" href="<?php echo $rc['url']; ?>"><?php echo $rc['name']; ?></a>
      			<?php 	} ?>                    
      			                      </div>
      			                    </div>
      			<?php } ?>                    
      			                  </div>
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="row" style="margin:40px 0;">
                        <div class="col-sm-12 col-md-12 text-center">
                          <h3><?php echo $this->lang->line("WE OFFER"); ?></h3>
                          <h4><?php echo $this->lang->line("we take care of you"); ?></h4>
                          <hr style="border-bottom:2px solid; width:50px; margin:0 auto 20px;" />

                        </div>

                            <div class="col-sm-12" style="padding-right:0;padding-left:0;">
                              <div style="padding:15px;margin:20px auto;">
                                <div class="row">
                                  <div class="col-sm-4" style="padding:15px 5%;">
                                    <img class="img-responsive img-title" src="<?php echo base_url();?>image/t1.jpg" alt="Emergency Hospital Insurance" />
                                    <h2><?php echo $this->lang->line("Visitor to Canada"); ?></h2>
                                    <hr style="border-bottom:2px solid #ddd; width:50px; margin:0 0 15px;"/>
                                    <p><?php echo $this->lang->line("Healthcare costs in Canada can be expensive if you’re not covered by a Canadian government healthcare plan. Make sure you have the proper visitor insurance coverage to help protect you and your family and enjoy a secure stay in Canada."); ?> </p>
                                  </div>
                                  <div class="col-sm-4" style="padding:15px 5%;">
                                    <img class="img-responsive img-title" src="<?php echo base_url();?>image/t2.jpg" alt="Emergency Hospital Insurance" />
                                    <h2><?php echo $this->lang->line("International Student to Canada"); ?></h2>
                                    <hr style="border-bottom:2px solid #ddd; width:50px; margin:0 0 15px;"/>
                                    <p><?php echo $this->lang->line("Studying abroad is exciting and adventurous. Make sure you have the right insurance coverage for your journey while in Canada."); ?></p>
                                  </div>
                                  <div class="col-sm-4" style="padding:15px 5%;">
                                    <img class="img-responsive img-title" src="<?php echo base_url();?>image/t3.jpg" alt="Emergency Hospital Insurance" />
                                    <h2><?php echo $this->lang->line("Canadian Travellers"); ?></h2>
                                    <hr style="border-bottom:2px solid #ddd; width:50px; margin:0 0 15px;"/>
                                    <p><?php echo $this->lang->line("Canadian Travellers"); ?></p>
                                  </div>
                                </div>
                              </div>
                            </div>
                         
                      </div>
                      <div class="row" style="margin-right:0;margin-left:0;">
                        <div class="col-sm-12" style="padding-right:0;padding-left:0;">
                          <div style="background-color:#f6f6f6; padding:15px;margin:20px auto;">
                            <div class="row" style="margin:40px 0 20px;">
                              <div class="col-sm-12 text-center">
                                <h3><?php echo $this->lang->line("CONTACT US"); ?></h3>
                                <hr style="border-bottom:2px solid; width:50px; margin:0 auto 20px;" />
                              </div>
                            </div>
                            <div class="row h-contact">
                              
                              <div class="col-sm-12 contact-left">
                                <h3><input name='foot_title' value='<?php echo $foot_title; ?>'></h3><br />
                                <h5><input name='address' value='<?php echo $address; ?>'></h5>
                                <h5><input name='city_province' value='<?php echo $city_province; ?>'></h5>
                                <h5><input name='post_code' value='<?php echo $post_code; ?>'></h5><br />
                                <h5><input name='phone' value='<?php echo $phone; ?>'></h5>
                                <h5><input name='fax' value='<?php echo $fax; ?>'></h5>
                                <h5><input name='toll_free' value='<?php echo $toll_free; ?>'></h5>
                                <h5><input name='toll_free_fax' value='<?php echo $toll_free_fax; ?>'></h5><br />
                                <h5><input name='email' value='<?php echo $email; ?>'></h5>

                              </div>
                              <!-- <div class="col-sm-6 contact-right">
                                <h3>Toronto Office</h3><br />
                                <h5>15 Wertheim Court, Suite 501</h5>
                                <h5>Richmond Hill, ON</h5>
                                <h5>L4B 3H7 CANADA</h5><br />
                                <h5>Phone: 905-707-1512</h5>
                                <h5>Fax: 905-707-1513</h5>
                                <h5>Toll Free: 1-877-832-5541</h5>
                                <h5>Toll Free Fax: 1-888-988-3268</h5><br />
                                <h5>E-mail: info@jfgroup.ca</h5>
                              </div> -->
                            </div>
                            <div class="row">    
                              <div class="col-sm-12"><hr /></div>
                              <div class="col-sm-4">
                                <p><?php echo $this->lang->line("Upload Your QR Code"); ?></p>
                                <div style="">
                                <?php if ($qr_src !== 'noqr.png'){?>
                                  <img style="width: 100%;max-width: 100px;" id='qr_image_life' src="<?php echo base_url('agent/img') . '/' . $qr_src; ?>" alt="JF Insurance">
                                <?php } ?>  
                                  <input id='qr_image' type='file' name='qr_src'>
                                  <h5><input name='qr_desc' value='<?php echo $qr_desc; ?>'></h5>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>

                        <input class="btn btn-primary" type='submit' value='<?php echo $this->lang->line("Update"); ?>'>
                      
                    </div>
                    
                  </div>
                </div>
              </div>
            </div>

          </div>
        </div>
        <!-- /page content -->
      </form>
<script type="text/javascript">
function showImage(src,target) {
	var fr=new FileReader();

	// when image is loaded, set the src of the image where you want to display it
	fr.onload = function(e) { target.src = this.result; };
	src.addEventListener("change",function() {
		// fill fr with image data
		fr.readAsDataURL(src.files[0]);
	});
}

var logo_image = document.getElementById("logo_image");
var logo_image_life = document.getElementById("logo_image_life");
var qr_image = document.getElementById("qr_image");
var qr_image_life = document.getElementById("qr_image_life");
var big_image = document.getElementById("big_image");
var big_image_life = document.getElementById("big_image_life");

showImage(logo_image,logo_image_life);
showImage(qr_image,qr_image_life);
showImage(big_image,big_image_life);
//showImage($('#big_image'),$('#big_image_life'));
</script>
<?php if (0) { ?>
<script>
function load_myname() {

	$.ajax({
		url: '<?php echo $myname_url; ?>',
		type: 'GET',
		data: { firstname : $("input[name='firstname']").val(), lastname : $("input[name='lastname']").val(), },
		success: function(data, textStatus, jqXHR) {
			if (data.status == 1) {
	        	$('#showmyname').html('<?php echo $myhome_url; ?>' + '/' + data.name);
	        	$("input[name='myname']").val(data.name);
			} else {
	        	$('#showmyname').html(data.message);
			}
    	},
	});
}
$( document ).ready(function() {
	$("input[name='firstname'], input[name='lastname']").change(function() {
		load_myname();
	});
	load_myname();
});
</script>
<?php } ?>