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
        <div class="right_col" role="main">
          
            <div class="page-title">
              <div class="title_left">
                <h3>Claim</h3>
              </div>

            </div>
            <div class="clearfix"></div>
           <!-- Filter Section -->
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_content">
                   
                    <form method="post" class="form-horizontal" action='<?php echo $edit_url; ?>'>
                    <input type='hidden' name='<?php echo $csrf['name']; ?>' value='<?php echo $csrf['value']; ?>'>
                    <input type='hidden' name='claim_id' value='<?php echo $claim_id; ?>'>
                    <input type='hidden' name='plan_id' value='<?php echo $plan_id; ?>'>
                    <input type='hidden' name='customer_id' value='<?php echo $customer_id; ?>'>
                    Product: <?php echo $product_short; ?><br>
                    Policy Number: <?php echo $policy_number; ?><br>
                    Finish Claim: <input type='checkbox' name='done' <?php echo ($done == 1) ? 'checked' : ''; ?>'><br>
                    Cliaim Number: <input type='text' name='cliaim_number' value='<?php echo $cliaim_number; ?>'><br>
                    Last Name: <input type='text' name='lastname' value='<?php echo $lastname; ?>'><br>
                    First Name: <input type='text' name='firstname' value='<?php echo $firstname; ?>'><br>
                    Birth Day: <input type='date' name='birthday' value='<?php echo $birthday; ?>'><br>
                    Gender:	<select name='gender' class="form-control">
											<option value='M' <?php echo (empty($gender) || ($gender != 'F')) ? "selected" : ""; ?>>Male</option>
											<option value='F' <?php echo (!empty($gender) && ($gender == 'F')) ? "selected" : ""; ?>>Female</option>
											</select><br>
                    Claim Date: <?php echo $claim_date; ?><br>
					Claimed: <input type='number' name='claimed' value='<?php echo $claimed; ?>'><br>
					Paid: <input type='number' name='paid' value='<?php echo $paid; ?>'><br>
                    Pay To: <input type='text' name='pay_to' value='<?php echo $pay_to; ?>'><br>
                    Cheque Number: <input type='text' name='cheque_number' value='<?php echo $cheque_number; ?>'><br>
                    Coverage Code:	<select name='coverage_code_id' class="form-control">
											<option value='' <?php echo (empty($coverage_code_id)) ? "selected" : ""; ?>> -- select code --</option>
											<?php foreach ($coverage_codes as $ccode) { ?>
											<option value='<?php echo $ccode['coverage_code_id']?>' <?php echo ($coverage_code_id == $ccode['coverage_code_id']) ? "selected" : ""; ?>><?php echo $ccode['name']; ?></option>
											<?php } ?>
											</select><br>
                    Service Date: <input type='date' name='service_date' value='<?php echo $service_date; ?>'><br>
                    Diagnosis: <input type='text' name='diagnosis' value='<?php echo $diagnosis; ?>'><br>
                    Memo: <input type='text' name='memo' value='<?php echo $memo; ?>'><br>
                    Decline Reason: <input type='text' name='decline_reason' value='<?php echo $decline_reason; ?>'><br>
                    <input type='submit' name='submit' value='Update'><br>
                    </form>

                  </div>
                </div>
              </div>
            </div><!-- End Filter Section -->
        </div>
        <!-- /page content -->
        