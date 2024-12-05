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
            </nav>
          </div>
        </div>
        <!-- Content top navigation End-->

        <!-- page content -->
        <div class="right_col" role="main">
          <div class="main-div">
            <div class="page-title">
              <div class="title_left">
                <h3>Agent Search</h3>
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
        					<?php if (!empty($error_message)) { ?>
                    <div class="row">
                      <div class="col-sm-12" style="color: brown;">
                        <?php echo $error_message; ?>
                      </div>
                    </div>
                  <?php } ?>
        					<form action='<?php $action_url; ?>' method='GET' class="form-horizontal">
        					  <!-- input type='hidden' name='<?php echo $csrf['name']; ?>' value='<?php echo $csrf['value']; ?>' -->
          					
                    <div class="row">
                    <?php if ($beuser_group_id > 100) { ?>
                    	<input type='hidden' name='user_group_id' value="0">
                    <?php } else { ?>
                      <div class="col-sm-3 form-group">
                          <label class="col-sm-12">User Type:</label>
                          <div class="col-sm-12 input-group">
                            <select name='user_group_id' class="form-control">
                              <option value='0'> -- select user group -- </option>
                              <?php foreach ($user_group_list as $key => $name) { ?>
                              <option value='<?php echo $key; ?>' <?php echo ($user_group_id == $key) ? 'selected' : ''; ?>><?php echo $name; ?></option>
                              <?php } ?>
                            </select>
                          </div>
                      </div>
                    <?php } ?>
                    <?php if (empty($beuser_region_id)) { ?>
                      <div class="form-group col-sm-3">
                        <label class="col-sm-12">Region:</label>
                        <div class="input-group col-sm-12">
                            <select name='region_id' class="form-control">
                              <option value='0'> -- All Region -- </option>
                              <?php foreach ($regions as $key => $name) { ?>
                              <option value='<?php echo $key; ?>' <?php echo ($region_id == $key) ? 'selected' : ''; ?>><?php echo $name; ?></option>
                              <?php } ?>
                            </select>
                        </div>
                      </div>
                    <?php } else { ?>
                    	<input type='hidden' name='region_id' value='<?php echo $beuser_region_id; ?>'>
                    <?php } ?>
                      <div class="form-group col-sm-3">
                        <label class="col-sm-12">Company:</label>
                        <div class="input-group col-sm-12">
                          <input type='text' name='business' value='<?php echo $business; ?>' class="form-control">
                        </div>
                      </div>
                      <div class="form-group col-sm-3">
                        <label class="col-sm-12">ID:</label>
                        <div class="input-group col-sm-12">
                          <input type='text' name='user_id' value='<?php echo $user_id; ?>' class="form-control">
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <!-- Last Name input box -->
                      <div class="form-group col-sm-3">
                        <label class="col-sm-12">Last Name:</label>
                        <div class="input-group col-sm-12">
                          <input type='text' name='lastname' value='<?php echo $lastname; ?>' class="form-control">
                        </div>
                      </div>
                      <!-- Last Name input box end -->
                      <!-- First Name input box -->
                      <div class="form-group col-sm-3">
                        <label class="col-sm-12">First Name:</label>
                        <div class="input-group col-sm-12">
                           <input type='text' name='firstname' value='<?php echo $firstname; ?>' class="form-control">
                         </div>
                      </div>
                  
                      <!-- User Name input box -->
                      <div class="form-group col-sm-3">
                        <label class="col-sm-12">Username:</label>
                        <div class="input-group col-sm-12">
                          <input type='text' name='username' value='<?php echo $username; ?>' class="form-control">
                         </div>
                      </div>
                      <!-- Email input box -->
                      <div class="form-group col-sm-3">
                        <label class="col-sm-12">E-mail:</label>
                        <div class="input-group col-sm-12">
                          <input type='text' name='email' value='<?php echo $email; ?>' class="form-control">
                        </div>
                      </div>
                    </div>
        				
                    <br />
        			      <div class="row">
                      <!-- submit button -->
                      <div class="col-sm-12">
                        <button class="btn btn-primary pull-right">Display Agent</button>
                      </div> 
                      <!-- submit button -->
                    </div>
        					  
        					</form>
                    
                  </div>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <?php if (($beuser_group_id < 100) && ($beuser_group_id != 2)) { ?>
                    <div class="row">
                      <div class="col-md-2 col-sm-2 col-xs-4">
                        <h2>User List</h2>
                      </div>
                      <div class="col-md-2 col-sm-2 col-xs-4">
                        <a class="btn btn-info" href='<?php echo $edit_url."0"; ?>'><i class="fa fa-plus"></i> Add User</a>
                      </div>
          					  <?php if (($beuser_user_id == '1') || ($beuser_user_id == '2762')) { ?>
                        <div class="col-md-2 col-sm-2 col-xs-4">
                          <a class="btn btn-info" href='<?php echo $export_url; ?>'><i class="fa fa-download"></i> Export User</a>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <form action='<?php echo $import_url; ?>' method='POST' class="form-horizontal" enctype="multipart/form-data">
                            <div class="row">
                              <div class="col-md-4 col-sm-4 col-xs-6">
                                <input type='file' name='file' />
                              </div>
                              <div class="col-md-4 col-sm-4 col-xs-6">
                                <button type='submit' class="btn btn-info"><i class="fa fa-upload"></i> Import User</button>
                                <input type='hidden' name='<?php echo $csrf['name']; ?>' value='<?php echo $csrf['value']; ?>'>
                                </div>
                            </div>
                          </form>
                        </div>
          					  <?php } ?>
                    </div>
                    <div class="clearfix"></div>
                  <?php } ?>
                  <div class="x_content">
                   
                    <div class="table-responsive">
                      <table class="table table-hover table-bordered">
                        <thead>
                          <tr>
                            <th>Username</th>
                            <th>Type</th>
<?php if (empty($beuser_region_id)) { ?>
                            <th>Region</th>
<?php } ?>
                            <th>Brokerage</th>
                            <th>Agent ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Licence#</th>
                            <th>Expire</th>
                            <th>Pay Types</th>
                            <th>Status</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($user_list as $user) { ?>
                          <tr>
                            <td>
<?php if ($beuser_group_id < 100) { ?>
                            	<a style="color:#46b8da;" href='<?php echo $edit_url.$user['user_id']; ?>'><?php echo $user['username']?></a>
<?php } else { ?>
                            	<?php echo $user['username']?>
<?php } ?>
                            </td>
                            <td><?php echo $user_group_list[$user['user_group_id']]; ?></td>
<?php if (empty($beuser_region_id)) { ?>
                            <td><?php echo empty($regions[$user['region_id']]) ? 'All' : $regions[$user['region_id']]; ?></td>
<?php } ?>
                            <td><?php echo (!empty($user['parent_user_id']) && isset($broker_list[$user['parent_user_id']])) ? $broker_list[$user['parent_user_id']] : '';  ?></td>
                            <td><?php echo $user['user_id']; ?></td>
                            <td><?php echo $user['lastname'] . ", " . $user['firstname']; ?></td>
                            <td><?php echo $user['email']; ?></td>
                            <td><?php echo $user['licence_number']; ?></td>
                            <td><?php echo $user['licence_expire']; ?></td>
                            <td><?php echo $user['pay_type']; ?></td>
                            <td><?php echo $user['status'] ? 'Act' : '-'; ?></td>
<?php if (($beuser_group_id < $user['user_group_id']) && !empty($behalf_url)) { ?>
                            <td><a style="color:#46b8da;" href='<?php echo $behalf_url.$user['user_id']; ?>'>Behalf</a></td>
<?php } else { ?>
                            <td>-</td>
<?php } ?>
                          </tr>
                        <?php } ?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class='pageination pull-right'><?php echo $pagination; ?></div>

        </div>
      </div>
      <!-- /page content -->
        


