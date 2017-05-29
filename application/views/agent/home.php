<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title><?php echo isset($title_txt) ? $title_txt : "JF Group"; ?></title>

<link rel="stylesheet" href="<?php echo base_url();?>main.css">
<!-- bootstrap style -->
<link rel="stylesheet" href="<?php echo base_url();?>stylesheet/bootstrap/dist/css/bootstrap.css">
<link rel="stylesheet" href="<?php echo base_url();?>stylesheet/bootstrap/dist/css/bootstrap.min.css">

<link rel="stylesheet" href="<?php echo base_url();?>stylesheet/datetimepicker/css/bootstrap-datepicker.css">

<!-- font-awesome style -->
<link rel="stylesheet" href="<?php echo base_url();?>stylesheet/font-awesome/css/font-awesome.min.css" >
<!-- customize template style -->
<link rel="stylesheet" href="<?php echo base_url();?>stylesheet/iCheck/skins/flat/green.css" >
<link rel="stylesheet" href="<?php echo base_url();?>stylesheet/google-code-prettify/bin/prettify.min.css" >
<link rel="stylesheet" href="<?php echo base_url();?>stylesheet/select2/dist/css/select2.min.css" >
<link rel="stylesheet" href="<?php echo base_url();?>stylesheet/switchery/dist/switchery.min.css" >
<link rel="stylesheet" href="<?php echo base_url();?>stylesheet/starrr/dist/starrr.css" >
<!-- Build theme style -->
<link rel="stylesheet" href="<?php echo base_url();?>/build/css/custom.min.css">
 <!-- jQuery -->
<script src="<?php echo base_url();?>stylesheet/jquery/dist/jquery.min.js"></script>
<script src="<?php echo base_url();?>stylesheet/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="<?php echo base_url();?>stylesheet/datetimepicker/js/bootstrap-datetimepicker.js"></script>

</head>
<body class="nav-md">
<header>
	<div class="container" style="padding:0;">
		<div class="hlogo">
			<img class="img-responsive" src="<?php echo base_url('agent/img') . '/' . $logo; ?>" alt="JF Insurance">
		</div>
		<nav class="navbar navbar-default" style="display: none;">
		</nav>
	</div>
</header>
<!-- Content without left menu -->
		<div class="container" style="padding:0 0 40px 0;">	
			<div class="main agentmyhome" style="min-height:800px;">
        
        <div class="h-topdiv">
			<img class="img-responsive" src="<?php echo base_url('agent/img') . '/' . $image; ?>">
			
            <div class="h-top">
				<h1><?php echo $top_title; ?></h1>
				<p><?php echo str_replace("\n", "</p><p>", $top_desc); ?></p>
			</div>
        </div>
        <div class="row" style="margin:40px 0;">
          <div class="col-sm-12 text-center">
            <h3><?php echo $about_title; ?></h3>
            <h4><?php echo $about_short; ?></h4>
            <hr style="border-bottom:2px solid; width:50px; margin:0 auto 20px;" />
            <p style="max-width:600px;width:100%;margin:0 auto;"><?php echo str_replace("\n", "</p><p>", $about_desc); ?></p>
          </div>
        </div>
				<div class="row" style="margin-right:0;margin-left:0;">
					<div class="col-sm-12" style="padding-right:0;padding-left:0;">
						<div style="background-color:#f6f6f6; padding:15px;margin:20px auto;">
							<div class="row">
                <div class="col-sm-12">
							    <h3 style="text-align: center;">Our Productions</h3>
                  <hr style="border-bottom:2px solid; width:50px; margin:0 auto 20px;">
                  <div class="row dfile-list">
			<?php foreach ($file_url as $product_short => $farr) { ?>                    
			                    
			              <div class="col-sm-4" style="padding-right: 5%;padding-left: 5%;margin-bottom: 15px;">
			                 <div class="onetypep" style="padding:15px;min-height: 115px">          
			                   <label class="agentmypl"><?php echo $farr['fullname']; ?></label>
			                   <br />
			<?php 	foreach ($farr['files'] as $rc) { ?>                      
			                   <a class="d_brochure" target="_blank" href="<?php echo $rc['url']; ?>"><?php echo $rc['name']; ?></a><br />
			<?php 	} ?>       
			                   <a class="btn btn-primary" style='color: #ffffff !important; padding: 5px 15px !important; margin-top: 5px; text-decoration: inherit;' href="<?php echo $buy_url . $product_short; ?>"><B>Purchase</B></a><br />
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
            <h3>WE OFFER</h3>
            <h4>we take care of you</h4>
            <hr style="border-bottom:2px solid; width:50px; margin:0 auto 20px;" />

          </div>
        

              <div class="col-sm-12" style="padding-right:0;padding-left:0;">
                <div style="padding:15px;margin:20px auto;">
                  <div class="row">
                    <div class="col-sm-4" style="padding:15px 5%;">
                      <img class="img-responsive img-title" src="<?php echo base_url();?>image/t1.jpg" alt="Emergency Hospital Insurance" />
                      <h2>Visitor to Canada</h2>
                      <hr style="border-bottom:2px solid #ddd; width:50px; margin:0 0 15px;"/>
                      <p>Health care costs in Canada can be expensive if you’re not covered by a Canadian government health insurance plan. Therefore, be sure to carry visitor insurance to protect your finances and enjoy a secured stay while you are away from your home country.</p>
                    </div>
                    <div class="col-sm-4" style="padding:15px 5%;">
                      <img class="img-responsive img-title" src="<?php echo base_url();?>image/t2.jpg" alt="Emergency Hospital Insurance" />
                      <h2>International Student to Canada</h2>
                      <hr style="border-bottom:2px solid #ddd; width:50px; margin:0 0 15px;"/>
                      <p>Studying aboard is exciting and adventurous. Make sure you have the right insurance coverage for your journey while in Canada.</p>
                    </div>
                    <div class="col-sm-4" style="padding:15px 5%;">
                      <img class="img-responsive img-title" src="<?php echo base_url();?>image/t3.jpg" alt="Emergency Hospital Insurance" />
                      <h2>Canadian Travel Out</h2>
                      <hr style="border-bottom:2px solid #ddd; width:50px; margin:0 0 15px;"/>
                      <p>Your provincial health plan only covers a part of your health care costs incurred outside of Canada and limits coverage when travelling to another province.</p>
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
                  <h3>CONTACT US</h3>
                  <hr style="border-bottom:2px solid; width:50px; margin:0 auto 20px;" />
                  <h3><?php echo $foot_title; ?></h3><br />
                  
                  <?php if($qr !== "noqr.png"){ ?>
              <div>
                 <div class="text-center"><div class="floatqr">
                  <img style='margin: 15px auto;width: 100%;' id='qr_image_life' src="<?php echo base_url('agent/img') . '/' . $qr; ?>" alt="JF Insurance">
                  <p><?php echo $qr_desc; ?></p>
                  </div>
                </div></div>
              <?php } ?>  
                </div>
              </div>
              <div class="row h-contact">
                <div class="col-sm-6 contact-left">
                  
                  <h5><?php echo $address; ?></h5>
                  <h5><?php echo $city_province; ?></h5>
                  <h5><?php echo $post_code; ?></h5><br />
                  <h5><?php echo $email; ?></h5>
                </div>
                <div class="col-sm-6 contact-right">
                  <h5><?php echo $phone; ?></h5>
                  <h5><?php echo $fax; ?></h5>
                  <h5><?php echo $toll_free; ?></h5>
                  <h5><?php echo $toll_free_fax; ?></h5><br />
                </div>
                  

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
              
              </div>
            </div>
          </div>
        </div>

        
			</div>	
		</div>
<!-- End Content without left menu -->
<footer>
	<div class="container">
		<div class="row">
			<div class="col-sm-12 text-center">
				<p>.Copyright &copy; 2009-2016 JF Insurance Agency Group Inc. All rights reserved.</p>
			</div>
		</div>
	</div>
	<a href="#" class="scrollToTop"><i class="fa fa-arrow-circle-up"></i></a>
</footer>
</div><!-- End of div.main_container -->
</div><!-- End of div.container.body-->
</div><!-- End Body Content -->
<script>
	
	//Check to see if the window is top if not then display button
	$(window).scroll(function(){
		if ($(this).scrollTop() > 100) {
			$('.scrollToTop').fadeIn();
		} else {
			$('.scrollToTop').fadeOut();
		}
	});
	
	//Click event to scroll to top
	$('.scrollToTop').click(function(){
		$('html, body').animate({scrollTop : 0},800);
		return false;
	});
	
</script>

 <!-- Bootstrap -->

 <!-- FastClick -->
<script src="<?php echo base_url();?>stylesheet/fastclick/lib/fastclick.js"></script>
<!-- NProgress -->
<script src="<?php echo base_url();?>stylesheet/nprogress/nprogress.js"></script>
<!-- bootstrap-progressbar -->
<script src="<?php echo base_url();?>stylesheet/bootstrap-progressbar/bootstrap-progressbar.min.js"></script>
<!-- iCheck -->
<script src="<?php echo base_url();?>stylesheet/iCheck/icheck.min.js"></script>
<!-- bootstrap-daterangepicker -->
<script src="<?php echo base_url();?>js/cjs/moment/moment.min.js"></script>
<script src="<?php echo base_url();?>js/cjs/datepicker/daterangepicker.js"></script>
<!-- bootstrap-wysiwyg -->
<script src="<?php echo base_url();?>stylesheet/bootstrap-wysiwyg/js/bootstrap-wysiwyg.min.js"></script>
<script src="<?php echo base_url();?>stylesheet/jquery.hotkeys/jquery.hotkeys.js"></script>
<script src="<?php echo base_url();?>stylesheet/google-code-prettify/src/prettify.js"></script>
<!-- jQuery Tags Input -->
<script src="<?php echo base_url();?>stylesheet/jquery.tagsinput/src/jquery.tagsinput.js"></script>
<!-- Switchery -->
<script src="<?php echo base_url();?>stylesheet/switchery/dist/switchery.min.js"></script>
<!-- Select2 -->
<script src="<?php echo base_url();?>stylesheet/select2/dist/js/select2.full.min.js"></script>
<!-- Parsley -->
<script src="<?php echo base_url();?>stylesheet/parsleyjs/dist/parsley.min.js"></script>
<!-- Autosize -->
<script src="<?php echo base_url();?>stylesheet/autosize/dist/autosize.min.js"></script>
<!-- jQuery autocomplete -->
<script src="<?php echo base_url();?>stylesheet/devbridge-autocomplete/dist/jquery.autocomplete.min.js"></script>
<!-- starrr -->
<script src="<?php echo base_url();?>stylesheet/starrr/dist/starrr.js"></script>
<!-- Build Custom Theme Scripts -->
<script src="<?php echo base_url();?>build/js/custom.min.js"></script>

</body>
</html>
