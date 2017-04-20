<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!-- Plan List page content -->

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
                <h3>Send Policy Package</h3>
              </div>
            </div>
            <div class="clearfix"></div>

           <!-- Filter Section -->
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_content">
                  <?php if (!empty($error_message)) { ?>
                  <div class="alert-error">
                    <?php echo $error_message . "<br>";?>
                  </div>
                  <?php } ?>
                  <?php if ($plan['premium'] < $admin_fee) { ?>
                  <div class="alert-error">
                    Premium is less than Admin Fee. Can't cancel
                  </div>
                  <?php } else { ?>
                    <form action='<?php echo $action_url; ?>' method='POST'  class="form-horizontal">
            				  <input type='hidden' name='<?php echo $csrf['name']; ?>' value='<?php echo $csrf['value']; ?>'>
            				  <input type='hidden' name='plan_id' value='<?php echo $plan_id; ?>'>

                        <div class="form-group col-sm-4 col-xs-12"  style="display:none;">
                          <label>Refund Premium:</label> <!--span id="refund_amount"></span-->
                          <input readonly type="number" step="any" name='refund_amount' id='refund_amount' value='<?php echo $plan['premium']; ?>' class="form-control"/>
                        </div>

        					  <div class="row">

                        <div class="form-group col-sm-5 col-xs-12" style="display:none;">
                          <label class="col-sm-12">Admin Fee:</label>
                          <div class="input-group col-sm-12">
                            <input type="number" step="any" name='admin_fee' value='<?php echo $admin_fee; ?>' class="form-control"/>
                          </div>
                        </div>
                        
                      </div><br />
                      <div class="row">
                        <div class="form-group col-sm-12 text-center">
                          <label class="inline">Are you sure you want to cancel this policy? </label>
                         <input class="btn btn-primary inline" type='submit' name='send' value='YES'>
                         <a class="btn btn-default inline" href="<?php echo $url_back_to_policy;?>">NO</a>
                        </div>  
                      </div>
			              </form>
                  <?php } ?>
				          </div>
                </div>
              </div>
            </div><!-- End Filter Section -->
          </div>
        </div>
      </div>
      <!-- /page content -->
