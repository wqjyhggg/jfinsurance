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
        <div class="right_col" role="main" style="margin-bottom:40px;">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3>Create Policy</h3>
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
           <!-- Form Section -->
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Policy Form<small></small></h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <br />



<?php if (!empty($error_message)) { echo $error_message . "<br>"; } ?>
<form action='<?php echo $action_url; ?>' method='POST'>
<input type='hidden' name='<?php echo $csrf['name']; ?>' value='<?php echo $csrf['value']; ?>'><br>
<input type='hidden' name='plan_id' value='<?php echo $plan_id; ?>'>
<input type='hidden' name='product_short' value='<?php echo $product_short; ?>'>
<?php if (empty($plan_id) || empty(status_id)) { ?>
	<?php /* it should be new plan */ ?>
	<input type='hidden' name='status_id' value='0'>
<?php } else { ?>
	<?php /* it should be created plan */ ?>
	<?php if ($status_id == 1) { /* qutoe */ ?>
	<a href='<?php echo $pay_url; ?>'>Pay</a>
	<?php } ?>
	<a href='<?php echo $copy_url; ?>'>Copy</a>
	<?php if ($beuser['user_group_id'] > 2) { ?>
		<?php /* it is school or brokerage or agent */ ?>
		Policy Status: <?php echo $status_list[$status_id]; ?> 
		<input type='hidden' name='status_id' value='<?php echo $status_id; ?>'>
		<?php } else { ?>
		Policy Status: <select name='status_id'>
			<option value='0'> -- select policy status -- </option>
			<?php foreach ($status_list as $key => $value) { ?>
			<option value='<?php echo $key; ?>' <?php echo ($key == $status_id) ? 'selected' : ''; ?>><?php echo $value['name']; ?></option>
			<?php } ?>
		</select><br>
		<?php } ?>
<?php } ?>
Apply Date: <?php echo $apply_date; ?><br>
Arrival Date: <?php echo $plan['arrival_date']; ?>'<br>
Effective Date: <?php echo $plan['effective_date']; ?>'<br>
Expiry Date: <?php echo $plan['expiry_date']; ?><br>
=========================================================================<br />
Beneficiary: <?php echo $plan['beneficiary']; ?><br>
<?php if ($plan['isfamilyplan']) { ?> Family Plan <br><?php } ?>
Sum Insured (CAD): $<?php echo number_format($plan['sum_insured'], 2, '.', ','); ?><br>
Deductible amount (CAD):  $<?php echo number_format($plan['deductiable_amount'], 2, '.', ','); ?><br>
<?php if (empty($disable_stable_condition)) { ?>
<?php if ($plan['stable_condition'] == 1) { ?>
With stable pre-existion condition coverage<br>
<?php } else if ($plan['stable_condition'] == 2) { ?>
Without stable pre-existion condition coverage<br>
<?php } ?>
<?php } ?>
=========================================================================<br />
Custmer Name:<br>
Last Name: <?php echo $customer['lastname']; ?><br>
First Name: <?php echo $customer['firstname']; ?><br>
Birth Date: <?php echo $customer['birthday']; ?><br>
Gender: <?php echo $customer['gender']; ?><br>
=========================================================================<br />
<?php if ($plan['isfamilyplan']) { ?>
Family members:<br>
<?php for ($i = 1; $i < 9; $i++) { ?>
<?php if (empty($customers[$i]['lastname']) && empty($customers[$i]['firstname'])) continue; ?>
Last Name: <?php echo $customers[$i]['lastname']; ?><br>
First Name: <?php echo $customers[$i]['firstname']; ?><br>
Birth Date: <?php echo $customers[$i]['birthday']; ?><br>
Gender: <?php echo $customers[$i]['gender']; ?><br>
=========================================================================<br />
<?php } ?>
<?php } ?>
Street No: <?php echo $plan['street_number']; ?><br>
Street Name: <?php echo $plan['street_name']; ?><br>
Suite No.: <?php echo $plan['suite_number']; ?><br>
City: <?php echo $plan['city']; ?><br>
Province: <?php echo $plan['province2']; ?><br>
Country: <?php echo $plan['country2']; ?><br>
Postcode: <?php echo $plan['postcode']; ?><br>
Phone1: <?php echo $plan['phone1']; ?><br>
Phone2: <?php echo $plan['phone2']; ?><br>
=========================================================================<br />
Email: <?php echo $plan['contact_email']; ?><br>
Contact Phone: <?php echo $plan['contact_phone']; ?><br>
Residence: <?php echo $plan['residence']; ?><br>
=========================================================================<br />
Premium: $<?php echo number_format($plan['premium'], 2, '.', ','); ?><br>
Notes: <?php echo $plan['note']; ?><br>
=========================================================================<br />

				</div>
                </div>
              </div>
            </div><!-- End Form -->
            </div>
        </div>
        <!-- /page content -->