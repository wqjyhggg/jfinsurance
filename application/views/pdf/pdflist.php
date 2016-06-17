<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!-- List Download Files page content -->

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
                <h3>Download Files</h3>
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
                  <div class="x_title">
                    <h2>All PDF Files<small></small></h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <br />
                    <?php foreach ($filelist as $filename) {?>
          					<?php echo $filename; ?><br />
          					<?php }?>
                    <br /><br />
                    <div class="text-element"><p>In order to update a specific file, please make sure use the same file name!</p></div>
                    <form action="" method="post" >
                    <input type='hidden' name='<?php echo $csrf['name']; ?>' value='<?php echo $csrf['value']; ?>'>

                    <input style="display:inline-block;vertical-align:middle;" type="file" name="userfile" size="20" />
                    <input style="display:inline-block;vertical-align:middle;" type="submit" value="Update" />
                    </form>

                  </div>
                </div>
              </div>
            </div>

          </div>
        </div>
        <!-- /page content -->
        