<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!-- Content with left menu -->
<?php if (isset($menu) && is_array($menu) && (sizeof($menu)>0)) { ?>
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
                <h3>Home</h3>
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
                    <div class="main" style="min-height:800px;">
        
                      <div class="lh-topdiv">
                        <img class="img-responsive" src="<?php echo base_url();?>image/homepic.png" alt="JF Insurance">
                        <div class="lh-top">
                              <h1>WHY SHOULD YOU BUY TRAVEL INSURANCE?</h1>
                              <p>We don't like to think about it, but unexpected accidents or illnesses can occur when you and your family are traveling abroad, and finding and paying for adequate medical attention can be very difficult.</p>
                              <p>Healthcare costs around the world can be very expensive; hospitals can charge thousands of dollars per day. Your government healthcare plan may or may not cover a minimal portion of these costs. Without adequate insurance coverage, you could be responsible for a significant amount of medical expenses that could have an immense impact on your personal finances. Why take this risk?</p>
                            </div>
                      </div>
                      <div class="row" style="margin:40px 0;">
                        <div class="col-sm-12 text-center">
                          <h3>ABOUT US</h3>
                          <h4>We will take care of you</h4>
                          <hr style="border-bottom:2px solid; width:50px; margin:0 auto 20px;" />
                          <p>JF Insurance Agency Group Inc. (JF) is a licensed brokerage firm incorporated in 1992. We are the leading private firm in providing Emergency Hospital and Medical coverage for Canadians Travellers, Visitors to Canada and International Students. We are recognized for our dedication in serving our clients on an individual basis and by association groups.</p>
                        </div>
                      </div>
                      <div class="row" style="margin-right:0;margin-left:0;">
                        <div class="col-sm-12" style="padding-right:0;padding-left:0;">
                          <div style="background-color:#f6f6f6; padding:15px;margin:20px auto;">
                            <div class="row">
                              <div class="col-sm-6" style="padding:15px 5%;">
                                <h2>Founder</h2>
                                <hr style="border-bottom:2px solid #ddd; width:50px; margin:0 0 15px;"/>
                                <p>Mr. Johnson Fu has more than 30 years of experience in the Insurance and Financial Industry. Mr. Fu is an active member of his community as well as many local and international charities. His active involvement and dedication to community service has led to the JF philosophy "To Serve". It is this philosophy and attitude that resonates and helps guide all staff members at JF.</p>
                              </div>
                              <div class="col-sm-6" style="padding:15px 5%;">
                                <h2>Mission</h2>
                                <hr style="border-bottom:2px solid #ddd; width:50px; margin:0 0 15px;"/>
                                <p>To reach all that can benefit from the peace of mind we offer; to ensure claims are processed in a timely manner.</p>
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
                                    <p>Healthcare costs in Canada can be expensive if you’re not covered by a Canadian government healthcare plan. Make sure you have the proper visitor insurance coverage to help protect you and your family and enjoy a secure stay in Canada.</p>
                                  </div>
                                  <div class="col-sm-4" style="padding:15px 5%;">
                                    <img class="img-responsive img-title" src="<?php echo base_url();?>image/t2.jpg" alt="Emergency Hospital Insurance" />
                                    <h2>International Student to Canada</h2>
                                    <hr style="border-bottom:2px solid #ddd; width:50px; margin:0 0 15px;"/>
                                    <p>Studying abroad is exciting and adventurous. Make sure you have the right insurance coverage for your journey while in Canada.</p>
                                  </div>
                                  <div class="col-sm-4" style="padding:15px 5%;">
                                    <img class="img-responsive img-title" src="<?php echo base_url();?>image/t3.jpg" alt="Emergency Hospital Insurance" />
                                    <h2>Canadian Travellers</h2>
                                    <hr style="border-bottom:2px solid #ddd; width:50px; margin:0 0 15px;"/>
                                    <p>It may not come as a surprise that your provincial health plan may not cover your health care costs incurred outside of Canada, but did you also know that you could have limited coverage when traveling to other provinces/territories within Canada? Make sure you are properly covered when travelling abroad or from your home province. </p>
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
                              </div>
                            </div>
                            <div class="row h-contact">
                              <div class="col-sm-6 contact-left">
                                <h3>Toronto Office</h3><br />
                                <h5>15 Wertheim Court, Suite 501</h5>
                                <h5>Richmond Hill, ON</h5>
                                <h5>L4B 3H7 CANADA</h5><br />
                                <h5>Phone: 905-707-1512</h5>
                                <h5>Fax: 905-707-1513</h5>
                                <h5>Toll Free: 1-877-832-5541</h5>
                                <h5>Toll Free Fax: 1-888-988-3268</h5><br />
                                <h5>E-mail: info@jfgroup.ca</h5>

                              </div>
                              <div class="col-sm-6 contact-right">
                                <h3>Vancouver Office</h3><br />
                                <h5>128-6061 No. 3 Road</h5>
                                <h5>Richmond, BC</h5>
                                <h5>V6Y 2B2 CANADA</h5><br />
                                <h5>Phone: 604-232-0896</h5>
                                <h5>Fax: 604-232-0897</h5>
                                <h5>Toll Free: 1-877-232-0896</h5>
                                <h5> &nbsp;</h5>
                                <br />
                                
                                <h5>E-mail: vancouver@jfuinsurance.com</h5>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>

                      
                    </div>
                    
                  </div>
                </div>
              </div>
            </div>

          </div>
        </div>
        <!-- /page content -->
        <?php } else{ ?>
<!-- End content with left menu --> 

<!-- Content without left menu -->
		<div class="container" style="padding:0 0 40px 0;">	
			<div class="main" style="min-height:800px;">
        
        <div class="h-topdiv">
<?php if (!empty($myhomeimage)) {?>
				  <img class="img-responsive" src="<?php echo $myhomeimage; ?>" alt="JF Insurance">
<?php } else { ?>
				  <img class="img-responsive" src="<?php echo base_url();?>image/homepic.png" alt="JF Insurance">
<?php } ?>
<?php if (!empty($myhome)) {?>
                        <div class="h-top">
                              <h1><?php echo $myhome['top_title']; ?></h1>
                              <p><?php echo str_replace("\n", "</p><p>", $myhome['top_desc']); ?></p>
                        </div>
<?php } else { ?>
          <div class="h-top">
                <h1>WHY SHOULD YOU BUY TRAVEL INSURANCE?</h1>
                <p>We don't like to think about it, but unexpected accidents or illnesses can occur when you and your family are traveling abroad, and finding and paying for adequate medical attention can be very difficult.</p>
                <p>Healthcare costs around the world can be very expensive; hospitals can charge thousands of dollars per day. Your government healthcare plan may or may not cover a minimal portion of these costs. Without adequate insurance coverage, you could be responsible for a significant amount of medical expenses that could have an immense impact on your personal finances. Why take this risk?</p>
              </div>
<?php } ?>
        </div>
        <div class="row" style="margin:40px 0;">
          <div class="col-sm-12 text-center">
            <h3>ABOUT US</h3>
            <h4>We will take care of you</h4>
            <hr style="border-bottom:2px solid; width:50px; margin:0 auto 20px;" />
            <p style="max-width:600px;width:100%;margin:0 auto;">JF Insurance Agency Group Inc. (JF) is a licensed brokerage firm incorporated in 1992. We are the leading private firm in providing Emergency Hospital and Medical coverage for Canadians Travellers, Visitors to Canada and International Students. We are recognized for our dedication in serving our clients on an individual basis and by association groups.</p>
          </div>
        </div>
				<div class="row" style="margin-right:0;margin-left:0;">
					<div class="col-sm-12" style="padding-right:0;padding-left:0;">
						<div style="background-color:#f6f6f6; padding:15px;margin:20px auto;">
							<div class="row">
                <div class="col-sm-6" style="padding:15px 5%;">
                  <h2>Founder</h2>
                  <hr style="border-bottom:2px solid #ddd; width:50px; margin:0 0 15px;"/>
                  <p>Mr. Johnson Fu has more than 30 years of experience in the Insurance and Financial Industry. Mr. Fu is an active member of his community as well as many local and international charities. His active involvement and dedication to community service has led to the JF philosophy "To Serve". It is this philosophy and attitude that resonates and helps guide all staff members at JF.</p>
                </div>
                <div class="col-sm-6" style="padding:15px 5%;">
                  <h2>Mission</h2>
                  <hr style="border-bottom:2px solid #ddd; width:50px; margin:0 0 15px;"/>
                  <p>To reach all that can benefit from the peace of mind we offer; to ensure claims are processed in a timely manner.</p>
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
                      <p>Healthcare costs in Canada can be expensive if you’re not covered by a Canadian government healthcare plan. Make sure you have the proper visitor insurance coverage to help protect you and your family and enjoy a secure stay in Canada. </p>
                    </div>
                    <div class="col-sm-4" style="padding:15px 5%;">
                      <img class="img-responsive img-title" src="<?php echo base_url();?>image/t2.jpg" alt="Emergency Hospital Insurance" />
                      <h2>International Student to Canada</h2>
                      <hr style="border-bottom:2px solid #ddd; width:50px; margin:0 0 15px;"/>
                      <p>Studying abroad is exciting and adventurous. Make sure you have the right insurance coverage for your journey while in Canada.</p>
                    </div>
                    <div class="col-sm-4" style="padding:15px 5%;">
                      <img class="img-responsive img-title" src="<?php echo base_url();?>image/t3.jpg" alt="Emergency Hospital Insurance" />
                      <h2>Canadian Travellers</h2>
                      <hr style="border-bottom:2px solid #ddd; width:50px; margin:0 0 15px;"/>
                      <p>It may not come as a surprise that your provincial health plan may not cover your health care costs incurred outside of Canada, but did you also know that you could have limited coverage when traveling to other provinces/territories within Canada? Make sure you are properly covered when travelling abroad or from your home province. </p>
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
                </div>
              </div>
              <div class="row h-contact">
                <div class="col-sm-6 contact-left">
                  <h3>Toronto Office</h3><br />
                  <h5>15 Wertheim Court, Suite 501</h5>
                  <h5>Richmond Hill, ON</h5>
                  <h5>L4B 3H7 CANADA</h5><br />
                  <h5>Phone: 905-707-1512</h5>
                  <h5>Fax: 905-707-1513</h5>
                  <h5>Toll Free: 1-877-832-5541</h5>
                  <h5>Toll Free Fax: 1-888-988-3268</h5><br />
                  <h5>E-mail: info@jfgroup.ca</h5>

                </div>
<?php if (!empty($myhome)) { ?>
                <div class="col-sm-6 contact-right">
                  <h3><?php echo $myhome['foot_title']; ?></h3><br />
                  <h5><?php echo $myhome['address']; ?></h5>
                  <h5><?php echo $myhome['city_province']; ?></h5>
                  <h5><?php echo $myhome['post_code']; ?></h5><br />
                  <h5><?php echo $myhome['phone']; ?></h5>
                  <h5><?php echo $myhome['fax']; ?></h5>
                  <h5><?php echo $myhome['toll_free']; ?></h5>
                  <h5> &nbsp;</h5>
                  <br />
                  
                  <h5><?php echo $myhome['email']; ?></h5>
<?php } else { ?>
                <div class="col-sm-6 contact-right">
                  <h3>Vancouver Office</h3><br />
                  <h5>128-6061 No. 3 Road</h5>
                  <h5>Richmond, BC</h5>
                  <h5>V6Y 2B2 CANADA</h5><br />
                  <h5>Phone: 604-232-0896</h5>
                  <h5>Fax: 604-232-0897</h5>
                  <h5>Toll Free: 1-877-232-0896</h5>
                  <h5> &nbsp;</h5>
                  <br />
                  
                  <h5>E-mail: vancouver@jfuinsurance.com</h5>
<?php } ?>
                </div>
              </div>
            </div>
          </div>
        </div>

        
			</div>	
		</div>
<!-- End Content without left menu -->
<?php } ?>