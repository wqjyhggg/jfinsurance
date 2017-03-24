<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

        <!-- page content -->
            <div class="page-title">
              <div class="title_left">
                <h3 style='margin-left: 20px;'>Claim Letter</h3>
              </div>
				
            </div>
            <div class="clearfix"></div>
           <!-- Filter Section -->
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_content">
                   
                    <form method="post" class="form-horizontal" action='<?php echo $active_url; ?>'>
                      <input type='hidden' name='<?php echo $csrf['name']; ?>' value='<?php echo $csrf['value']; ?>'>
                      <?php foreach ($citem_ids as $citem_id) { ?>
                      <input type='hidden' name='citem_id[]' value='<?php echo $citem_id; ?>'>
                      <?php } ?>
                      <input type='hidden' name='claim_id' value='<?php echo $claim_id; ?>'>
                      <div class="row">
                        <div class="form-group col-sm-3">
                          <label >Full Name: </label>
                          <div class="input-group col-sm-12">
                            <input class="form-control" type='text' name='fullname' value="<?php echo str_replace("\"", "'", $print_name); ?>">
                          </div>
                        </div>
                        <div class="form-group col-sm-3">
                          <label >Number: </label>
                          <div class="input-group col-sm-12">
                            <input class="form-control" type='text' name='street_number' value="<?php echo str_replace("\"", "'", $print_street_number); ?>">
                          </div>
                        </div>
                        <div class="form-group col-sm-3">
                          <label >Street: </label>
                          <div class="input-group col-sm-12">
                            <input class="form-control" type='text' name='street_name' value="<?php echo str_replace("\"", "'", $print_street_name); ?>">
                          </div>
                        </div>
                        <div class="form-group col-sm-3">
                          <label >Suite Number: </label>
                          <div class="input-group col-sm-12">
                            <input class="form-control" type='text' name='suite_number' value="<?php echo str_replace("\"", "'", $print_suite_number); ?>">
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="form-group col-sm-3">
                          <label >City: </label>
                          <div class="input-group col-sm-12">
                            <input class="form-control" type='text' name='city' value="<?php echo str_replace("\"", "'", $print_city); ?>">
                          </div>
                        </div>
                        <div class="form-group col-sm-3">
                          <label >Province: </label>
                          <div class="input-group col-sm-12">
                            <input class="form-control" type='text' name='province2' value='<?php echo $print_province2; ?>'>
                          </div>
                        </div>
                        <div class="form-group col-sm-3">
                          <label >Country2: </label>
                          <div class="input-group col-sm-12">
                            <input class="form-control" type='text' name='country2' value='<?php echo $print_country2; ?>'>
                          </div>
                        </div>
                        <div class="form-group col-sm-3">
                          <label >Postcode: </label>
                          <div class="input-group col-sm-12">
                            <input class="form-control" type='text' name='postcode' value='<?php echo $print_postcode; ?>'>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="form-group col-sm-3">
                          <label >Pay To: </label>
                          <div class="input-group col-sm-12">
                            <input class="form-control" type='text' name='pay_to' value='<?php echo $print_pay_to; ?>'>
                          </div>
                        </div>
                        <div class="form-group col-sm-9">
                          <label >Cheque Number: </label>
                          <div class="input-group col-sm-12">
                            <input class="form-control" type='text' name='cheque_number' value='<?php echo $print_cheque_number; ?>'>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="form-group col-sm-3">
                          <button class="btn btn-primary" id="claim-letter-close">Close</button>
                        </div>
                        <div class="form-group col-sm-9 text-right">
                          <input class="btn btn-primary" type='submit' name='submit' value="Print">
                        </div>
                      </div>

                    </form>

                  </div>
                </div>
              </div>
            </div><!-- End Filter Section -->
