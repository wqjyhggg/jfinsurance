<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!-- Plan page content -->

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
        <div class="right_col" role="main" style="margin-bottom:40px;">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3>Terms & Conditions</h3>
              </div>
            </div>
            <div class="clearfix"></div>
           <!-- Term Section -->
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Terms & Conditions<small></small></h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <br />


					<p>Term & condition.</p>
					<p>babababababababa.</p>

					<?php if (!empty($message)) { echo $message . "<br>"; } ?>

					<form action='<?php echo $action_url; ?>' method='POST'>
					<input type='hidden' name='<?php echo $csrf['name']; ?>' value='<?php echo $csrf['value']; ?>'><br>
					<input type='hidden' name='plan_id' value='<?php echo $plan_id; ?>'>
					Agree: <input type='checkbox' name='agree' id='agree'><br>
					<input type='submit' name='submit' value='<?php echo $submit; ?>'><br>
					</form> 
	
				</div>
                </div>
              </div>
            </div><!-- End Term -->
            </div>
        </div>
        <!-- /page content -->