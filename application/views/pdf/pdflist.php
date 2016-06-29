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
            </nav>
          </div>
        </div>
        <!-- Content top navigation End-->

        <!-- page content -->
        <div class="right_col" role="main">
          <div class="main-div">
            <div class="page-title">
              <div class="title_left">
                <h3>Download Files</h3>
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
                   
                    <?php foreach ($filelist as $filename) {?>
          					<?php echo $filename; ?><br />
          					<?php }?>
                    <br /><br />
                    <div class="text-element"><p>In order to update a specific file, please make sure use the same file name!</p></div>
                    <form action="" method="post" >
                    <input type='hidden' name='<?php echo $csrf['name']; ?>' value='<?php echo $csrf['value']; ?>'>
                    <div class="col-sm-12">
                        <div class="row">
                        <div class="col-sm-4">
                        <input class="form-control" type="file" name="userfile" size="20" />  
                        </div>
                        <div class="col-sm-4">
                         <input class="btn btn-primary" type="submit" value="Update" />
                        </div>
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
        