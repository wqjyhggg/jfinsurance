<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!-- Report/ Sales report to JF page content -->

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
                <h3>Agent Search</h3>
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
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Search Form<small></small></h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <br />



					<?php if (!empty($error_message)) { echo $error_message . "<br>"; } ?>
					<form action='<?php $action_url; ?>' method='POST'>
					<input type='hidden' name='<?php echo $csrf['name']; ?>' value='<?php echo $csrf['value']; ?>'><br>
					<select name='user_group_id'>
					<option value='0'> -- select user group -- </option>
					<?php foreach ($user_group_list as $key => $name) { ?>
					<option value='<?php echo $key; ?>'><?php echo $name; ?></option>
					<?php } ?>
					</select><br>
					Username: <input type='text' name='username' value='<?php echo $username; ?>'><br>
					Firstname: <input type='text' name='firstname' value='<?php echo $firstname; ?>'><br>
					Lastname: <input type='text' name='lastname' value='<?php echo $lastname; ?>'><br>
					Email: <input type='text' name='email' value='<?php echo $email; ?>'><br>
					Brockerage: <input type='text' name='business' value='<?php echo $business; ?>'><br>
					<input type='submit'><br>
					</form> 

					<p>User List <a href='<?php echo $edit_url."0"; ?>'>Add user</a></p>
					<table>
						<tr>
							<th>Username</th>
							<th>Type</th>
							<th>Brokerage</th>
							<th>Name</th>
							<th>Email</th>
							<th>Licence#</th>
							<th>Expire</th>
							<th>Pay Types</th>
							<th>Status</th>
						</tr>
					<?php foreach ($user_list as $user) { ?>
						<tr>
							<td><a href='<?php echo $edit_url.$user['user_id']; ?>'><?php echo $user['username']?></a></td>
							<td><?php echo $user_group_list[$user['user_group_id']]; ?></td>
							<td><?php echo $user['business']; ?></td>
							<td><?php echo $user['lastname'] . " " . $user['lastname']; ?></td>
							<td><?php echo $user['email']; ?></td>
							<td><?php echo $user['licence_number']; ?></td>
							<td><?php echo $user['licence_expire']; ?></td>
							<td><?php echo $user['pay_type']; ?></td>
							<td><?php echo $user['status'] ? 'Act' : '-'; ?></td>
						</tr>
					<?php } ?>
					</table>



                    
                  </div>
                </div>
              </div>
            </div>

          </div>
        </div>
        <!-- /page content -->
        


