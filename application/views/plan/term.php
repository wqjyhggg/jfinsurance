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
          <div class="main-div">
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

        					<p>Term & condition.</p>
        					<p>Hac urna excepturi, quisque adipisicing numquam, enim lorem. 
                    Duis ea duis dapibus pretium ex tortor? Etiam, aperiam, cupidatat ratione repudiandae. 
                    Condimentum primis, perferendis, dolorum sunt illo quisque hac? 
                    Voluptatem eget tempus tempus quia necessitatibus provident risus a leo eius ullamcorper, dicta fames incidunt quae? 
                    Nascetur! Libero! Accumsan quam conubia exercitation, ultrices! Deserunt? 
                    Nunc voluptates, rerum! 
                    Aenean, officiis imperdiet dapibus numquam. Esse posuere lacinia cupiditate, laboriosam morbi. 
                    Pretium sociosqu curabitur sit nisi, irure! 
                    Inceptos lectus placeat morbi penatibus animi officia tempora! 
                    Nemo quasi irure qui. Maxime, blanditiis feugiat. Ut? Iste accumsan, odit. 
                    Aliquip expedita explicabo rem tempore expedita, nulla adipiscing rerum.</p>

                  <br />
                  
                    <?php if (!empty($message)) { ?>
                      <div class="alert-error">
                        <?php echo $message;?>
                      </div>
                    <?php } ?>  
                    
                  <br />  
        					<form action='<?php echo $action_url; ?>' method='POST'>
        					<input type='hidden' name='<?php echo $csrf['name']; ?>' value='<?php echo $csrf['value']; ?>'>
                  <input type='hidden' name='plan_id' value='<?php echo $plan_id; ?>'>
                  <div class="row">
                    <div class="col-sm-12 text-right">
        					   Agree: <input type='checkbox' name='agree' id='agree'>
        					   <input class="btn btn-primary" type='submit' name='submit' value='Agree'><br>
        					  </div>
                  </div>
                  </form> 
        	
        				</div>
                </div>
              </div>
            </div><!-- End Term -->
            </div>
        </div>
        <!-- /page content -->