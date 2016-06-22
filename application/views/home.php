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

              <ul class="nav navbar-nav navbar-right">
                <!-- User section -->
                <li class="">
                  <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                    <img src="images/img.jpg" alt="">John Doe
                    <span class=" fa fa-angle-down"></span>
                  </a>
                  <ul class="dropdown-menu dropdown-usermenu pull-right">
                    <li><a href="javascript:;"> Profile</a></li>
                    <li>
                      <a href="javascript:;">
                        <span class="badge bg-red pull-right">50%</span>
                        <span>Settings</span>
                      </a>
                    </li>
                    <li><a href="javascript:;">Help</a></li>
                    <li><a href="login.html"><i class="fa fa-sign-out pull-right"></i> Log Out</a></li>
                  </ul>
                </li>
                <!-- User section End -->

                <!-- Notification section -->
                <!--li role="presentation" class="dropdown">
                  <a href="javascript:;" class="dropdown-toggle info-number" data-toggle="dropdown" aria-expanded="false">
                    <i class="fa fa-envelope-o"></i>
                    <span class="badge bg-green">6</span>
                  </a>
                  <ul id="menu1" class="dropdown-menu list-unstyled msg_list" role="menu">
                    <li>
                      <a>
                        <span class="image"><img src="images/img.jpg" alt="Profile Image" /></span>
                        <span>
                          <span>John Smith</span>
                          <span class="time">3 mins ago</span>
                        </span>
                        <span class="message">
                          Film festivals used to be do-or-die moments for movie makers. They were where...
                        </span>
                      </a>
                    </li>
                    <li>
                      <a>
                        <span class="image"><img src="images/img.jpg" alt="Profile Image" /></span>
                        <span>
                          <span>John Smith</span>
                          <span class="time">3 mins ago</span>
                        </span>
                        <span class="message">
                          Film festivals used to be do-or-die moments for movie makers. They were where...
                        </span>
                      </a>
                    </li>
                    <li>
                      <a>
                        <span class="image"><img src="images/img.jpg" alt="Profile Image" /></span>
                        <span>
                          <span>John Smith</span>
                          <span class="time">3 mins ago</span>
                        </span>
                        <span class="message">
                          Film festivals used to be do-or-die moments for movie makers. They were where...
                        </span>
                      </a>
                    </li>
                    <li>
                      <a>
                        <span class="image"><img src="images/img.jpg" alt="Profile Image" /></span>
                        <span>
                          <span>John Smith</span>
                          <span class="time">3 mins ago</span>
                        </span>
                        <span class="message">
                          Film festivals used to be do-or-die moments for movie makers. They were where...
                        </span>
                      </a>
                    </li>
                    <li>
                      <div class="text-center">
                        <a>
                          <strong>See All Alerts</strong>
                          <i class="fa fa-angle-right"></i>
                        </a>
                      </div>
                    </li>
                  </ul>
                </li-->
                <!-- Notification section End -->
              </ul>
            </nav>
          </div>
        </div>
        <!-- Content top navigation End-->

        <!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3>Home</h3>
              </div>

              <div class="title_right">
                <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
                  <div class="input-group">
                    <input type="text" class="form-control" placeholder="Search for...">
                    <span class="input-group-btn">
                      <button class="btn btn-default" type="button">Go!</button>
                    </span>
                  </div>
                </div>
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
                        <img class="img-responsive" src="<?php echo base_url();?>image/homepic.jpg" alt="JF Insurance">
                        <div class="lh-top">
                              <h1>WHY BUY INSURANCE</h1>
                              <p>
                                We don't like to think about it, but sudden, unexpected accidents or illnesses do happen,
                                and trying to find an pay for adequate medical attention can be difficult when you are abroad.

                              </p>
                              <p>
                                Health car costs around the world can be bery expensive. Hospital can charge thousands of dollars
                                per day. Your health plan may or may not cover a minute protion of these cost. Without adequate
                                insurance coverage you could be responsible from dollar one, which could create a massive impact on
                                your personal finances. Why take the risk?
                              </p>
                            </div>
                      </div>
                      <div class="row" style="margin:40px 0;">
                        <div class="col-sm-12 text-center">
                          <h3>ABOUT US</h3>
                          <h4>we take care of you</h4>
                          <hr style="border-bottom:2px solid; width:50px; margin:0 auto 20px;" />
                          <p>JF Insurance Agency Group Inc. is an insurance broker incorporated in 1992. JF is a private Canadain firm that provides travel, emergency hospital and medical insurance.</p>
                        </div>
                      </div>
                      <div class="row" style="margin-right:0;margin-left:0;">
                        <div class="col-sm-12" style="padding-right:0;padding-left:0;">
                          <div style="background-color:#f6f6f6; padding:15px;margin:20px auto;">
                            <div class="row">
                              <div class="col-sm-4" style="padding:15px 5%;">
                                <h2>Founder</h2>
                                <hr style="border-bottom:2px solid #ddd; width:50px; margin:0 0 15px;"/>
                                <p>The founder Mr. Johnson Fu has more than 30 plus years of experience in the Insurance and Financial Industry. 
                                  Mr. Fu is also very active among many communities and charity events. 
                                  It is through his involvement in community service that the JF corporate philosophy "To Serve" has come to fruition. 
                                  This philosophy emphasizes the attitude of all staff members at JF.</p>
                              </div>
                              <div class="col-sm-4" style="padding:15px 5%;">
                                <h2>Mission</h2>
                                <hr style="border-bottom:2px solid #ddd; width:50px; margin:0 0 15px;"/>
                                <p>The founder Mr. Johnson Fu has more than 30 plus years of experience in the Insurance and Financial Industry. 
                                  Mr. Fu is also very active among many communities and charity events. 
                                  It is through his involvement in community service that the JF corporate philosophy "To Serve" has come to fruition. 
                                  This philosophy emphasizes the attitude of all staff members at JF.</p>
                              </div>
                              <div class="col-sm-4" style="padding:15px 5%;">
                                <h2>Location</h2>
                                <hr style="border-bottom:2px solid #ddd; width:50px; margin:0 0 15px;"/>
                                <p>The founder Mr. Johnson Fu has more than 30 plus years of experience in the Insurance and Financial Industry. 
                                  Mr. Fu is also very active among many communities and charity events. 
                                  It is through his involvement in community service that the JF corporate philosophy "To Serve" has come to fruition. 
                                  This philosophy emphasizes the attitude of all staff members at JF.</p>
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
                                    <h2>Emergency Hospital Insurance</h2>
                                    <hr style="border-bottom:2px solid #ddd; width:50px; margin:0 0 15px;"/>
                                    <p>The founder Mr. Johnson Fu has more than 30 plus years of experience in the Insurance and Financial Industry. 
                                      Mr. Fu is also very active among many communities and charity events. 
                                      It is through his involvement in community service that the JF corporate philosophy "To Serve" has come to fruition. 
                                      This philosophy emphasizes the attitude of all staff members at JF.</p>
                                  </div>
                                  <div class="col-sm-4" style="padding:15px 5%;">
                                    <h2>Travel Insurance</h2>
                                    <hr style="border-bottom:2px solid #ddd; width:50px; margin:0 0 15px;"/>
                                    <p>The founder Mr. Johnson Fu has more than 30 plus years of experience in the Insurance and Financial Industry. 
                                      Mr. Fu is also very active among many communities and charity events. 
                                      It is through his involvement in community service that the JF corporate philosophy "To Serve" has come to fruition. 
                                      This philosophy emphasizes the attitude of all staff members at JF.</p>
                                  </div>
                                  <div class="col-sm-4" style="padding:15px 5%;">
                                    <h2>Medical Insurance</h2>
                                    <hr style="border-bottom:2px solid #ddd; width:50px; margin:0 0 15px;"/>
                                    <p>The founder Mr. Johnson Fu has more than 30 plus years of experience in the Insurance and Financial Industry. 
                                      Mr. Fu is also very active among many communities and charity events. 
                                      It is through his involvement in community service that the JF corporate philosophy "To Serve" has come to fruition. 
                                      This philosophy emphasizes the attitude of all staff members at JF.</p>
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
				  <img class="img-responsive" src="<?php echo base_url();?>image/homepic.jpg" alt="JF Insurance">
          <div class="h-top">
                <h1>WHY BUY INSURANCE</h1>
                <p>
                  We don't like to think about it, but sudden, unexpected accidents or illnesses do happen,
                  and trying to find an pay for adequate medical attention can be difficult when you are abroad.

                </p>
                <p>
                  Health car costs around the world can be bery expensive. Hospital can charge thousands of dollars
                  per day. Your health plan may or may not cover a minute protion of these cost. Without adequate
                  insurance coverage you could be responsible from dollar one, which could create a massive impact on
                  your personal finances. Why take the risk?
                </p>
              </div>
        </div>
        <div class="row" style="margin:40px 0;">
          <div class="col-sm-12 text-center">
            <h3>ABOUT US</h3>
            <h4>we take care of you</h4>
            <hr style="border-bottom:2px solid; width:50px; margin:0 auto 20px;" />
            <p>JF Insurance Agency Group Inc. is an insurance broker incorporated in 1992. JF is a private Canadain firm that provides travel, emergency hospital and medical insurance.</p>
          </div>
        </div>
				<div class="row" style="margin-right:0;margin-left:0;">
					<div class="col-sm-12" style="padding-right:0;padding-left:0;">
						<div style="background-color:#f6f6f6; padding:15px;margin:20px auto;">
							<div class="row">
                <div class="col-sm-4" style="padding:15px 5%;">
                  <h2>Founder</h2>
                  <hr style="border-bottom:2px solid #ddd; width:50px; margin:0 0 15px;"/>
                  <p>The founder Mr. Johnson Fu has more than 30 plus years of experience in the Insurance and Financial Industry. 
                    Mr. Fu is also very active among many communities and charity events. 
                    It is through his involvement in community service that the JF corporate philosophy "To Serve" has come to fruition. 
                    This philosophy emphasizes the attitude of all staff members at JF.</p>
                </div>
                <div class="col-sm-4" style="padding:15px 5%;">
                  <h2>Mission</h2>
                  <hr style="border-bottom:2px solid #ddd; width:50px; margin:0 0 15px;"/>
                  <p>The founder Mr. Johnson Fu has more than 30 plus years of experience in the Insurance and Financial Industry. 
                    Mr. Fu is also very active among many communities and charity events. 
                    It is through his involvement in community service that the JF corporate philosophy "To Serve" has come to fruition. 
                    This philosophy emphasizes the attitude of all staff members at JF.</p>
                </div>
                <div class="col-sm-4" style="padding:15px 5%;">
                  <h2>Location</h2>
                  <hr style="border-bottom:2px solid #ddd; width:50px; margin:0 0 15px;"/>
                  <p>The founder Mr. Johnson Fu has more than 30 plus years of experience in the Insurance and Financial Industry. 
                    Mr. Fu is also very active among many communities and charity events. 
                    It is through his involvement in community service that the JF corporate philosophy "To Serve" has come to fruition. 
                    This philosophy emphasizes the attitude of all staff members at JF.</p>
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
                      <h2>Emergency Hospital Insurance</h2>
                      <hr style="border-bottom:2px solid #ddd; width:50px; margin:0 0 15px;"/>
                      <p>The founder Mr. Johnson Fu has more than 30 plus years of experience in the Insurance and Financial Industry. 
                        Mr. Fu is also very active among many communities and charity events. 
                        It is through his involvement in community service that the JF corporate philosophy "To Serve" has come to fruition. 
                        This philosophy emphasizes the attitude of all staff members at JF.</p>
                    </div>
                    <div class="col-sm-4" style="padding:15px 5%;">
                      <h2>Travel Insurance</h2>
                      <hr style="border-bottom:2px solid #ddd; width:50px; margin:0 0 15px;"/>
                      <p>The founder Mr. Johnson Fu has more than 30 plus years of experience in the Insurance and Financial Industry. 
                        Mr. Fu is also very active among many communities and charity events. 
                        It is through his involvement in community service that the JF corporate philosophy "To Serve" has come to fruition. 
                        This philosophy emphasizes the attitude of all staff members at JF.</p>
                    </div>
                    <div class="col-sm-4" style="padding:15px 5%;">
                      <h2>Medical Insurance</h2>
                      <hr style="border-bottom:2px solid #ddd; width:50px; margin:0 0 15px;"/>
                      <p>The founder Mr. Johnson Fu has more than 30 plus years of experience in the Insurance and Financial Industry. 
                        Mr. Fu is also very active among many communities and charity events. 
                        It is through his involvement in community service that the JF corporate philosophy "To Serve" has come to fruition. 
                        This philosophy emphasizes the attitude of all staff members at JF.</p>
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
<!-- End Content without left menu -->
<?php } ?>