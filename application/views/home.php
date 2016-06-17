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
                    <img class="img-responsive" src="<?php echo base_url();?>image/homepic.jpg" alt="JF Insurance">
                    <div class="row" style="margin-right:0;margin-left:0;">
                      <div class="col-sm-12">
                        <div class="text-element">
                          <h2>Why Buy Insurance?</h2>
                          <p>
                            We don’t like to think about it, but sudden, unexpected accidents or illnesses do happen, and trying to find and pay for adequate medical attention can be difficult when you are abroad
                          </p>
                          <p>
                            Health care costs around the world can be very expensive. Hospital can charge thousands of dollars per day. Your health plan may or may not cover a minute portion of these cost. Without adequate insurance coverage you could be responsible from dollar one, which could create a massive impact on your personal finances. Why take the risk?
                          </p>
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
		<div class="container" style="padding:0;">	
			<div class="main" style="min-height:800px;">
				<img class="img-responsive" src="<?php echo base_url();?>image/homepic.jpg" alt="JF Insurance">
				<div class="row" style="margin-right:0;margin-left:0;">
					<div class="col-sm-8 col-sm-offset-2">
						<div style="background-color:#f6f6f6; padding:15px;margin:20px auto;">
							<h2>Why Buy Insurance?</h2>
							<p>
								We don’t like to think about it, but sudden, unexpected accidents or illnesses do happen, and trying to find and pay for adequate medical attention can be difficult when you are abroad
							</p>
							<p>
								Health care costs around the world can be very expensive. Hospital can charge thousands of dollars per day. Your health plan may or may not cover a minute portion of these cost. Without adequate insurance coverage you could be responsible from dollar one, which could create a massive impact on your personal finances. Why take the risk?
							</p>
						</div>
					</div>
				</div>
			</div>	
		</div>
<!-- End Content without left menu -->
<?php } ?>