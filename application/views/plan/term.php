<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!-- Plan page content -->

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
        <div class="right_col" role="main" style="margin-bottom:40px;">
          <div class="main-div">
            <div class="page-title">
              <div class="title_left">
                <h3><?php echo $this->lang->line("Terms & Conditions"); ?></h3>
              </div>
            </div>
            <div class="clearfix"></div>
           <!-- Term Section -->
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2><small></small></h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">

        					<p><b><?php echo $this->lang->line("The following statements must be true for all members."); ?></b></p>
        					<p><?php echo $this->lang->line("All persons insured are subject to the terms and conditions below."); ?><br /><br />
                     <?php echo $this->lang->line("This document does not constitute the entire insurance policy, and the applicant has been advised to read the policy booklet for full details of coverage and exclusions. You must agree to the terms of the contract."); ?>
                  </p>
                  <hr />
                  <p><b><?php echo $this->lang->line("Terms and Conditions"); ?>:</b></p>
                  <p>
                    <?php echo $this->lang->line("All persons insured are subject to the terms and conditions below."); ?><br /><br />
                    <?php echo $this->lang->line("This document does not constitute the entire insurance policy, and the applicant(s) has been advised to read the policy full details of coverage and exclusions."); ?><br /><br />
                    <ul>
                      <li><?php echo $this->lang->line("The eligibility requirements for the plan applied for are material to the risk for which insurance is sought. If the applicant(s) does not meet the eligibility requirements for the plan selected, or if there is any misrepresentation or concealment of or failure to disclose any facts or matters pertaining to the applicant(s) and that are the subject insurance coverage provided."); ?></li>

                      <li><?php echo $this->lang->line("The applicant(s) is aware that this insurance covers only Emergencies (as defined in the policy) and may not cover expenses incurred after the applicant(s) is able to travel home for treatment. The applicant(s) is aware that Pre-existing Conditions (as defined in the policy) are excluded in some circumstances and that further details are provided in the policy."); ?></li>
                    </ul>
                  </p>
                  <br />
<?php } else { ?>
        <!-- page content -->
        <div role="main" style="padding-left:28px;padding-bottom:40px;">
          <div class="main-div">
            <div class="page-title">
              <div class="title_left">
                <h3><?php echo $this->lang->line("Travel Insurance Disclaimer"); ?></h3>
              </div>
            </div>
            <div class="clearfix"></div>
           <!-- Term Section -->
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2><small></small></h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
   					<p><b><?php echo $this->lang->line("Eligibility"); ?></b></p>
   					<p><?php echo $this->lang->line("My insurance representative has reviewed the Visitors/International students to Canada Insurance Policy ('Policy') Eligibility criteria with me. I understand that coverage is not available for purchase if I have (or the person I am purchasing coverage for has) any of the excluded conditions listed under the Eligibility section of the Policy."); ?> </p>
                  <hr />
                  <p><b><?php echo $this->lang->line("Policy Benefits and Exclusions"); ?></b></p>
                  <p><?php echo $this->lang->line("My insurance representative has reviewed the Policy benefits, definitions and exclusions with me. I understand that if I have (or the person I am purchasing coverage for has) any pre-existing conditions, I (or the person I am purchasing coverage for) must disclose these to my insurance representative prior to purchasing coverage to allow for an informed assessment of the type of insurance coverage that is best suited to protect me (or the person I am purchasing coverage for)."); ?></p>
                  <hr />
                  <p><b><?php echo $this->lang->line("Confirmation"); ?></b></p>
                  <p> <?php echo $this->lang->line("understand that I need to contact with my insurance representative to get confirmation package after I completed the application."); ?></p>
                  <br />
<?php } ?>
                  <p>
                    <?php echo $this->lang->line("IN THE EVENT OF A MEDICAL EMERGENCY OR A CLAIM THAT MAY REQUIRE OR RESULT IN HOSPITALIZATION, CALL TIC IMMEDIATELY."); ?>
                  </p>
                  <p><?php echo $this->lang->line("Toll Free Canada/U.S.A. 1-800-869-6747"); ?></p>
                  <p><?php echo $this->lang->line("If unable to contact us toll free, please call collect: 416-340-8809"); ?></p>
                  <br />
                  <p>
                    <?php echo $this->lang->line("If you notice any errors in the above information or have any questions, please contact JF Insurance Agency Group Inc."); ?>
                  </p>

                  <div class="row">
                    <div class="col-sm-6">
                        <p>Ontario:</p>
                        <p>15 Wertheim Court, Suite 501,</p>
                        <p>Richmond Hill, ON, Canada L4B3H7</p>
                        <p>Phone: 905-707-1512 Or 1-877-832-5541</p>
                        <p>info@jfgroup.ca</p>
                    </div>
                    <div class="col-sm-6">
                        <p>British Columbia:</p>
                        <p>128 - 6061 No. 3 Road,</p>
                        <p>Richmond, BC, Canada V6Y 2B2</p>
                        <p>Phone: 604-232-0896, Or 1-877-232-0896</p>
                        <p>vancouver@jfuinsurance.com</p>   
                    </div>
                  </div>

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
                    <div class="col-sm-6">
                      <p><?php echo $this->lang->line("For All Members"); ?></p>
                    </div>
                    <div class="col-sm-6 text-right">
        					   <?php echo $this->lang->line("By checking the box, I agree to the above term."); ?>: <input type='checkbox' name='agree' id='agree' <?php echo $agree ? 'checked' : ''; ?>>
        					   <input class="btn btn-primary" type='submit' name='submit' value='<php echo $this->lang->line("Agree"); ?>'><br>
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