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
                <h3>Claim Item List</h3>
              </div>

            </div>
            <div class="clearfix"></div>
           <!-- Filter Section -->
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
<?php if (!empty($error_message)) { ?>
                  <div class="x_content">
                  	<div class='alert-error'><?php echo $error_message; ?></div>
                  </div>
<?php } ?>
                  <div class="x_content">
                   
                    <form method="post" class="form-horizontal" action='<?php echo $add_url; ?>'>
                        <input type='hidden' name='<?php echo $csrf['name']; ?>' value='<?php echo $csrf['value']; ?>'>
                    	<input type="hidden" name="claim_id" value='<?php echo $claim['claim_id']; ?>'>
                      <!-- personal information search -->
                      <div class="row">
                        <!-- claim_number box -->
                        <div class="form-group col-sm-3">
                           <label class="inline">Policy No.: </label> <?php echo $claim['policy_number']; ?>
                        </div>
                        <!-- claim_number box end -->
                        <!-- claim_number box -->
                        <div class="form-group col-sm-3">
                          <label class="inline">Claim No.: </label> <?php echo $claim['claim_number']; ?>
                        </div>
                        <!-- claim_number box end -->
                        <!-- First Name input box -->
                        <div class="form-group col-sm-3">
                          <label class="inline">Name: </label> <?php echo $claim['firstname']; ?> <?php echo $claim['lastname']; ?>
                        </div>
                        <!-- First Name input box end -->
                      </div>
                      <div class="row">
                        <!-- Policy Number input box -->
                        <div class="form-group col-sm-3">
                          <label class="inline">Birthday: </label> <?php echo $claim['birthday']; ?>
                        </div>
                        <!-- Policy Number input box end -->
                        <!-- Claim Number input box -->
                        <div class="form-group col-sm-3">
                          <label class="inline">Gender:</label> <?php echo $claim['gender']; ?>
                        </div>
                        <!-- Claim Number input box end -->
                        
                        <!-- submit button -->
                          <div class="col-sm-3 col-sm-offset-3">
                            <button class="btn btn-primary pull-right">Add New Claim Item</button>
                          </div> 
                        <!-- submit button -->
                      </div>
                    </form>

                  </div>
                </div>
              </div>
            </div>

<?php if (!empty($lists)) { ?>
            <!-- List Section -->
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_content">
                    
                    <div class="table-responsive">
                      <table class="table table-hover table-bordered">
                        <thead>
                          <tr>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Gender</th>
                            <th>Birth Date</th>
                            <th>Claim Date</th>
                            <th>Claim Amount</th>
                            <th>Paid</th>
                            <th>Pay To</th>
                            <th>Cheque Number</th>
                            <th>Recieved</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($lists as $c) { ?>
                            <tr>
                              <td><?php echo $c['firstname']; ?></td>
                              <td><?php echo $c['lastname']; ?></td>
                              <td><?php echo $c['gender']; ?></td>
                              <td><?php echo $c['birthday']; ?></td>
                              <td><?php echo $c['claim_date']; ?></td>
                              <td><?php echo $c['claimed']; ?></td>
                              <td><?php echo $c['paid']; ?></td>
                              <td><?php echo $c['pay_to']; ?></td>
                              <td><?php echo $c['cheque_number']; ?></td>
                              <td><?php echo $c['received']; ?></td>
                              <td><a style="color:#46b8da;" href="<?php echo $edit_url."/".$c['citem_id']?>">Edit</a> | <a target="_blank" style="color:#46b8da;" href="<?php echo $letter_url."/".$c['citem_id']?>">Letter</a></td>
                            </tr>
                        <?php } ?>    
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div><!-- End List Section -->
<?php } else { ?>
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
              	No Data for list
              </div>
            </div><!-- End List Section -->
<?php } ?>

          
        </div>
        <!-- /page content -->
        