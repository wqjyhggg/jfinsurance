<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!-- Product page content -->

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
          <duv class-"main-div">
            <div class="page-title">
              <div class="title_left">
                <h3>Download Files</h3>
              </div>

            </div>
            <div class="clearfix"></div>
          
            <!-- Product List Section -->
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>File List<small></small></h2>
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
            </div><!-- End List Section -->

          </div>
        </div>
        <!-- /page content -->