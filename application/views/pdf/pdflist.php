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
                  <?php $i =0; foreach ($filelist as $filename) { $i++; ?>
                    <form action="<?php echo $upload_url; ?>" method="post" >
                      <input type='hidden' name='<?php echo $csrf['name']; ?>' value='<?php echo $csrf['value']; ?>'>
                    	<input type='hidden' name='lcfilename' value='<?php echo $filename; ?>'>
                   
                     <div class="row df-row">
                       
                        <label class="inline col-sm-6 mdfile"><?php echo $filename; ?></label>
                        <div class="inline">
                        <input id="<?php echo 'uploadFile' . $i; ?>" placeholder="Choose File" class="uploadFile"/>
                        <div class="fileUpload btn btn-info">
                          <span>Select File</span>
                          <input id="<?php echo 'uploadBtn' . $i; ?>" type="file" class="upload" name="userfile" size="20"/>
                        </div>
                        <script>
                          document.getElementById("<?php echo 'uploadBtn' . $i; ?>").onchange = function () {
                          document.getElementById("<?php echo 'uploadFile' . $i; ?>").value = this.value;
                        };
                        </script>
                        
                        <input style="display: inline-block; vertical-align: bottom;" class="btn btn-primary" type="submit" value="Update" />
                        </div>
                    </div>
                   </form>
                  <?php }?>
                  </div>
                </div>
              </div>
            </div>

          </div>
        </div>
        <!-- /page content -->
        