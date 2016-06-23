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
                <h3>View/Edit Policy</h3>
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
           <!-- Filter Section -->
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Policy Filter<small></small></h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <br />




					<p>Search Form</p>
					<?php if (!empty($error_message)) { echo $error_message . "<br>"; } ?>
					<form action='<?php echo $search_url; ?>' method='POST'>
					<input type='hidden' name='<?php echo $csrf['name']; ?>' value='<?php echo $csrf['value']; ?>'><br>
					Last Name: <input type='text' name='firstname' value='<?php echo $firstname; ?>'><br>
					First Name: <input type='text' name='lastname' value='<?php echo $lastname; ?>'><br>
					Birth Date From: <input type='date' name='birthday' value='<?php echo $birthday; ?>'><br>
					Birth Date To: <input type='date' name='birthday2' value='<?php echo $birthday2; ?>'><br>
					Policy Number: <input type='text' name='policy' value='<?php echo $policy; ?>'><br>
					Apply Date From: <input type='date' name='apply_date' value='<?php echo $apply_date; ?>'><br>
					Apply Date To: <input type='date' name='apply_date2' value='<?php echo $apply_date2; ?>'><br>
					Arrival Date From: <input type='date' name='arrival_date' value='<?php echo $arrival_date; ?>'><br>
					Arrival Date To: <input type='date' name='arrival_date2' value='<?php echo $arrival_date2; ?>'><br>
					Effective Date From: <input type='date' name='effective_date' value='<?php echo $effective_date; ?>'><br>
					Effective Date To: <input type='date' name='effective_date2' value='<?php echo $effective_date2; ?>'><br>
					Expiry Date From: <input type='date' name='expiry_date' value='<?php echo $expiry_date; ?>'><br>
					Expiry Date To: <input type='date' name='expiry_date2' value='<?php echo $expiry_date2; ?>'><br>
					<?php if ($beuser['user_group_id'] > 5) { ?>
					Agent/School Name: <input type='text' name='uname' value='<?php echo $uname; ?>'><br>
					<?php } ?>
					Batch No.: <input type='text' name='batch_number' value='<?php echo $batch_number; ?>'><br>
					Policy Status: <select name='status_id'>
					<option value='0'> -- select policy status -- </option>
					<?php foreach ($status_list as $key => $value) { ?>
					<option value='<?php echo $key; ?>' <?php echo ($key == $status_id) ? 'selected' : ''; ?>><?php echo $value['name']; ?></option>
					<?php } ?>
					</select><br>
					Product List: <select name='product_short'>
					<option value='0'> -- select product -- </option>
					<?php foreach ($product_list as $key => $value) { ?>
					<option value='<?php echo $key; ?>' <?php echo ($key == $product_short) ? 'selected' : ''; ?>><?php echo $value['full_name']; ?></option>
					<?php } ?>
					</select><br>
					Province: <div id='province2_div'></div><br>
					Country: <div id='country2_div'></div><br>
					<input type='submit' name='search' value='Search'><br>
					</form><br />
					=========================================================================<br />
					<form action='<?php echo $add_url; ?>' method='POST'>
					<input type='hidden' name='<?php echo $csrf['name']; ?>' value='<?php echo $csrf['value']; ?>'><br>
					Product List: <select name='product_short'>
					<?php foreach ($product_list as $key => $value) { ?>
					<option value='<?php echo $key; ?>'><?php echo $value['full_name']; ?></option>
					<?php } ?>
					</select><br>
					<input type='submit' name='add' value='Create'><br>
					</form> 
					=========================================================================<br />
					<p>Policy List</p>
					<table>
						<tr>
							<th>Policy No.</th>
							<th>Batch No.</th>
							<th>Name</th>
							<th>Status</th>
							<th>Effect Date</th>
							<th>User</th>
					<?php if ($beuser['user_group_id'] > 5) { ?>
							<th>Agent</th>
					<?php } ?>
							<th>&nbsp</th>
						</tr>
					<?php foreach ($plan_list as $plan) { ?>
						<tr>
							<td><a href='<?php echo $edit_url.$plan['plan_id']; ?>'><?php echo $plan['policy']?></a></td>
							<td><?php echo $plan['batch_number']; ?></td>
							<td><?php echo $plan['full_name']; ?></td>
							<td><?php echo $status_list[$plan['status_id']]; ?></td>
							<td><?php echo $plan['effective_date']; ?></td>
							<td><?php echo $plan['firstname'] . " " . $plan['lastname']; ?></td>
					<?php if ($beuser['user_group_id'] > 5) { ?>
							<td><?php echo $plan['agent_firstname'] . " " . $plan['agent_lastname']; ?></td>
					<?php } ?>
							<td><a href='<?php echo $copy_url.$plan['plan_id']; ?>'>Copy</a></td>
						</tr>
					<?php } ?>
					</table>


				</div>
                </div>
              </div>
            </div><!-- End Filter Section -->
            <!-- List Section -->
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Search Result<small></small></h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <br />
                    <div class="table-responsive">
                      <table class="table table-hover table-bordered">
                        <thead>
                          <tr>
                            <th>Travel Plan Number</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Gender</th>
                            <th>Birthdate</th>
                            <th>Address Line 1</th>
                            <th>City</th>
                            <th>Province</th>
                            <th>Postal Code</th>
                            <th>Option</th>
                            <th>Agent</th>
                            <th>Application Date</th>
                            <th>Create Date</th>
                            <th>Effective Date</th>
                            <th>Expiry Date</th>
                            <th>Number of Days</th>
                            <th>Sum Insured</th>
                            <th>Net Premium</th>
                           
                            <th>Gross Premium</th>
                           
                            <th>Rate Per Day</th>
                          </tr>
                        </thead>
                        <tbody>
                            <tr>
                              <td colspan="22">JF Optimum Plus</td>
                            </tr>
                            <tr>
                              <td><a href="">OPL248778</a></td>
                              <td>TestGiven</td>
                              <td>TestLast</td>
                              <td>M</td>
                              <td>1980-05-03</td>
                              <td>14 Asdf fdsfsdf</td>
                              <td>Toronto</td>
                              <td>ON</td>
                              <td>M2M 4M5</td>
                              <td>9283</td>
                              <td>458286</td>
                              <td>2016-06-05</td>
                              <td>2016-05-05</td>
                              <td>2016-08-03</td>
                              <td>2017-08-02</td>
                              <td>365</td>
                              <td>10,000</td>
                              <td>733.21</td>
                              <td>1,357.80</td>
                              <td>3.72</td>
                            </tr>
                            <tr>
                              <td><a href="">OPL248777</a></td>
                              <td>TestGiven</td>
                              <td>TestLast</td>
                              <td>M</td>
                              <td>1980-05-03</td>
                              <td>14 Asdf fdsfsdf</td>
                              <td>Toronto</td>
                              <td>ON</td>
                              <td>M2M 4M5</td>
                              <td>9283</td>
                              <td>458286</td>
                              <td>2016-06-05</td>
                              <td>2016-05-05</td>
                              <td>2016-08-03</td>
                              <td>2017-08-02</td>
                              <td>365</td>
                              <td>10,000</td>
                              <td>733.21</td>
                              <td>1,357.80</td>
                              <td>3.72</td>
                            </tr>
                            <tr>
                              <td colspan="22">JF Royal Visitor to Canada</td>
                            </tr>
                            <tr>
                              <td><a href="">JFR248775</a></td>
                              <td>TestGiven</td>
                              <td>TestLast</td>
                              <td>M</td>
                              <td>1980-05-03</td>
                              <td>14 Asdf fdsfsdf</td>
                              <td>Toronto</td>
                              <td>ON</td>
                              <td>M2M 4M5</td>
                              <td>9283</td>
                              <td>458286</td>
                              <td>2016-06-05</td>
                              <td>2016-05-05</td>
                              <td>2016-08-03</td>
                              <td>2017-08-02</td>
                              <td>365</td>
                              <td>10,000</td>
                              <td>733.21</td>
                              <td>1,357.80</td>
                              <td>3.72</td>
                            </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div><!-- End List Section -->

          </div>
        </div>
        <!-- /page content -->




<script>
$( document ).ready(function() {
	$.ajax({
		url: '<?php echo $province_url; ?>',
		type: 'GET',
		data: {neednull:1},
		success: function(data, textStatus, jqXHR) {
        	$('#province2_div').html(data);
    	},
	});
	$.ajax({
		url: '<?php echo $country_url; ?>',
		type: 'GET',
		data: {neednull:1},
		success: function(data, textStatus, jqXHR) {
        	$('#country2_div').html(data);
    	},
	});
});
</script>

