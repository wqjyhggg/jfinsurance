<?php
defined('BASEPATH') or exit('No direct script access allowed');
$hideForFrench = false;
$Agree = $this->lang->line("Agree");
if ($Agree != "Agree") {
  $hideForFrench = true;
}
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
        <div class="right_col" role="main" style="padding-bottom:60px;">
          <div class="main-div">
            <div class="page-title">
              <div class="title_left">
                <h3><?php echo $p_header; ?></h3>
              </div>
            </div>
            <div class="clearfix"></div>
           <!-- Form Section -->
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2><?php echo $this->lang->line("Policy Detail"); ?><small></small></h2>
          <div class="pull-right text-right">
          <?php if ($isprocessplan) { ?>
            <?php if ($user_group_id != 3 && $user_group_id != 103) { ?>
              <a href='<?php echo $copy_url; ?>'><span class="btn btn-info" style='color:#fff;'><?php echo $this->lang->line("Copy"); ?></span></a>
            <?php } ?>
            <?php if ($status_id > 1 && $user_group_id != 3) { ?>
              <?php if (($status_id == 2) || ($status_id == 3)) { ?>
                  <a href='<?php echo $sendpackage_url . $plan_id; ?>'><span class="btn btn-info" style='color:#fff;'><?php echo $this->lang->line("Send Package"); ?></span></a>
                  <a class="btn btn-info pull-right" target="_blank" href='<?php echo $pdf_url; ?>'><?php echo $this->lang->line("Export PDF"); ?></a>
                  <?php if (!empty($print_receipt_url) && !$hideForFrench) { ?>
                    <a href='<?php echo $print_receipt_url; ?>' target="_blank"><span class="btn btn-info" style='color:#fff;'><?php echo $this->lang->line("Print Receipt"); ?></span></a>
                  <?php } ?>
                  <?php if (!empty($print_card_url) && !$hideForFrench) { ?>
                    <a href='<?php echo $print_card_url; ?>' target="_blank"><span class="btn btn-info" style='color:#fff;'><?php echo $this->lang->line("Print Card"); ?></span></a>
                  <?php } ?>
                  <?php if ($export_logo_price_option) { ?>
                                                        <div class='pull-right spdf-option'><input type='checkbox' class='withlogobox' checked> With Logo <br /><input type='checkbox' class='withpricebox' checked> With Price </div>
                                                      <?php } ?>
              <?php } ?>
            <?php } ?>
          <?php } ?>

          </div>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    
          <!--<script src="<?php echo base_url(); ?>js/jquery-3.0.0.min.js" type="text/javascript"></script>-->
          <?php if (!empty($error_message)) { ?>
            <div class="alert-error">
              <?php echo $error_message . "<br>"; ?>
            </div>
          <?php } ?>

          <form action='<?php echo $action_url; ?>' method='POST' id='plan_edit_form' class="form-horizontal">
            <input type='hidden' name='<?php echo $csrf['name']; ?>' value='<?php echo $csrf['value']; ?>'>
            <input type='hidden' name='plan_id' value='<?php echo $plan_id; ?>'>
            <input type='hidden' name='product_short' value='<?php echo $product_short; ?>'>
            <input type='hidden' name='force_deductable' value=''>
            <input type='hidden' name='batch_number' value='<?php echo $batch_number; ?>'>
          <div class="row" style="margin-bottom:15px;">
            
            <div class="form-group col-sm-3">
              <label><span><?php echo $plan_full_name; ?></span></label>
              <label><?php echo $this->lang->line("Policy Number"); ?>: <span><?php echo $policy; ?></span></label>
            </div>
            
            <div class="form-group col-sm-3">
              <label style="text-transform: capitalize;"><?php echo $this->lang->line("By Agent"); ?><?php echo "[ AgentID:" . $policy_user['user_id'] . " ] "; ?>: <?php echo htmlspecialchars($policy_user['firstname'] . " " . $policy_user['lastname']); ?></label>
            </div>
							<?php /* it is school or brokerage or agent */ ?>
              <div class="form-group col-sm-3">
                <label style="font-size:16px;"><?php echo $this->lang->line("Status"); ?>: <?php echo $status_list[$status_id]['name']; ?> </label>
              </div>
              <input type='hidden' name='status_id' value='<?php echo $status_id; ?>' class="form-control">
          </div>

          <div class="row">
            
            <div class="col-sm-12">
            <fieldset>
                <legend><?php echo $this->lang->line("Travel Dates"); ?></legend>
                <div class="row">
              <div class="form-group col-sm-3">
                <label class="col-sm-12"><?php echo $this->lang->line("Apply Date"); ?>:</label>
                              <input type="hidden" name='apply_date' value='<?php echo $apply_date; ?>'>
                              <div class='form_text_show'><?php echo $apply_date; ?></div>
              </div>
              <div class="form-group col-sm-3">
                <label class="col-sm-12"><?php echo $this->lang->line("Arrival Date"); ?>: </label>
                            <input class="form-control" size="16" type="hidden" name='arrival_date' id='arrival_date' value='<?php echo $arrival_date; ?>' data-date-format="yyyy-mm-dd">
                            <div class='form_text_show'><?php echo $arrival_date; ?></div>
              </div>
              <div class="form-group col-sm-3">
                <label class="col-sm-12"><?php echo $this->lang->line("Effective Date"); ?>: </label>
                            <div class='form_text_show'><?php echo $effective_date; ?></div>
                <input class="setpremium form-control" size="16" type="hidden" name='effective_date' value='<?php echo $effective_date; ?>'>
              </div>
              <div class="form-group col-sm-3">
                <label class="col-sm-12"><?php echo $this->lang->line("Expiry Date"); ?>: </label>
                <div class='form_text_show'><?php echo $expiry_date; ?></div>
                <input size="16" type='hidden' name='expiry_date' class='setpremium form-control' id='expiry_date' value='<?php echo $expiry_date; ?>'>
              </div>
            </div>
            <?php if ($batch_number) { ?>
                  <div class="row">
                    <div class="col-sm-3">
                      <label class="col-sm-12"><?php echo $this->lang->line("Days"); ?>: </label>
                      <div class='form_text_show'>
                        <input class="form-control" type='number' name='totaldays' id='totaldays' value='<?php echo $totaldays; ?>' >
                      </div>
                    </div>
                    <div class="col-sm-3">
                      <label class="col-sm-12"><?php echo $this->lang->line("Daily Rate"); ?>: </label>
                      <div class='form_text_show'>
                        <input class="form-control" type='number' name='dailyrate' step='0.01' id='dailyrate' value='<?php echo $dailyrate; ?>'>
                      </div>
                    </div>
                    <div class="col-sm-3">
                      <label class="col-sm-12"><?php echo $this->lang->line("Age"); ?>: </label>
                      <div class='form_text_show'>
                        <input class="form-control" type='number' name='totalyears' id='totalyears' value='<?php echo $totalyears; ?>'>
                      </div>
                    </div>
                    <div class="col-sm-3">
                      <label class="col-sm-12"><?php echo $this->lang->line("Premium"); ?>: </label>
                      <div class="form_text_show" id='premiumdisplay'>
                        <input class="form-control" type='number' name='premium' step='0.01' id='premium' value='<?php echo $premium; ?>'>	
                      </div>
                    </div>
                  </div>
            <?php } else { ?>
                  <div class="row">
                    <div class="col-sm-3">
                      <label class="col-sm-12"><?php echo $this->lang->line("Days"); ?>: </label>
                      <div class='form_text_show'><?php echo $totaldays; ?></div>
                      <input class="form-control" type='hidden' name='totaldays' id='totaldays' value='<?php echo $totaldays; ?>' >
                    </div>
                    <div class="col-sm-3">
                      <label class="col-sm-12"><?php echo $this->lang->line("Daily Rate"); ?>: </label>
                      <div class='form_text_show'><?php echo $dailyrate; ?></div>
                      <input class="form-control" type='hidden' name='dailyrate' id='dailyrate' value='<?php echo $dailyrate; ?>' readonly >
                    </div>
                    <div class="col-sm-3">
                      <label class="col-sm-12"><?php echo $this->lang->line("Age"); ?>: </label>
                      <div class='form_text_show'><?php echo $totalyears; ?></div>
                      <input class="form-control" type='hidden' name='totalyears' id='totalyears' value='<?php echo $totalyears; ?>' readonly >
                    </div>
                    <div class="col-sm-3">
                      <label class="col-sm-12"><?php echo $this->lang->line("Premium"); ?>: </label>
                      <input class="form-control" type='hidden' name='premium' id='premium' value='<?php echo $premium; ?>'>	
                      <div class="form_text_show" id='premiumdisplay'><?php echo $premium; ?></div>	
                      </div>
                    </div>
                  </div>
            <?php } ?>
             </fieldset>
             </div>
          </div><br />
					<?php if (!empty($plan) && !empty($plan['monthlypay'])) { ?>
					<div class="row">
						<div class="col-sm-12">
							<fieldset>
								<legend>Monthly Payment Plan</legend>
								<div class="row">
									<div class="form-group col-sm-3">
										<label class="col-sm-12">Payment Plan Status:
											<?php echo $monthly_status; ?>
										</label>
									</div>
									<div class="form-group col-sm-3">
										<label class="col-sm-12">Paid Months: 
											<?php if ($status_id == Plan_model::CANCEL) { echo 0; } else { echo (12 - intval($monthly_unpay_count)); } ?>
										</label>
									</div>
									<div class="form-group col-sm-3">
										<label class="col-sm-12">Paid Premium: 
											<?php if ($status_id == Plan_model::CANCEL) { echo 0; } else { echo number_format($monthly_paid, 2, ".", ""); } ?>
										</label>
									</div>
									<div class="form-group col-sm-3">
										<label class="col-sm-12">Outstanding Premium: 
											<?php if (($status_id != Plan_model::PAID) && ($status_id != Plan_model::SOLD)) { echo 0; } else { echo number_format($monthly_unpay, 2, ".", ""); } ?>
										</label>
									</div>
								</div>
							</fieldset>
					 	</div>
					</div><br />
					<?php } ?>
          <?php echo $insurable_options; ?>
          <?php if (!empty($error_claim)) { ?>
            <div class="row">
              <div class="col-sm-12">
                <div class="alert-error"> 
                  <?php echo $this->lang->line("Please contact JF staff for further assistance 905-707-1512"); ?>.
                </div>
              </div>
            </div>
          <?php } ?>
          <div class="row">
            <div class="col-sm-12">
              <fieldset>
                <legend><?php echo $this->lang->line("Insurable Members"); ?></legend>
                <input type='hidden' name='customer_id' value='<?php echo !empty($customer_id) ? $customer_id : 0; ?>'>
                <div class="row">
                  <label class="col-sm-12"><?php echo $this->lang->line("Customer Information"); ?></label>
                  <div class="col-sm-3">
                    <label class="col-sm-12"><?php echo $this->lang->line("First Name"); ?>:</label>
                    <div class="input-group col-sm-12">
                      <input class="form-control" type='text' name='firstname' value='<?php echo !empty($firstname) ? $html_model->escapeQuote($firstname) : ''; ?>'>
                    </div>
                    <?php if (!empty($error_firstname)) { ?>
                      <div class="alert-error">
                        <?php echo $error_firstname; ?>
                      </div>
                    <?php } ?>
                  </div>
                  <div class="col-sm-3">
                    <label class="col-sm-12"><?php echo $this->lang->line("Last Name"); ?>:</label>
                    <div class="input-group col-sm-12">
                      <input class="form-control" type='text' name='lastname' value='<?php echo !empty($lastname) ? $html_model->escapeQuote($lastname) : ''; ?>'>
                    </div>
                    <?php if (!empty($error_lastname)) { ?>
                      <div class="alert-error">
                        <?php echo $error_lastname; ?>
                      </div>
                    <?php } ?>
                  </div>
                  <div class="col-sm-3">
                    <label class="col-sm-12"><?php echo $this->lang->line("Birth Date"); ?>:</label>
                    <div class='form_text_show'><?php echo !empty(${'birthday'}) ? ${'birthday'} : ''; ?></div>
                    <input type="hidden" name='birthday' id='birthday' value='<?php echo !empty(${'birthday'}) ? ${'birthday'} : ''; ?>'>
                  </div>
                  <div class="col-sm-3">
                    <div class="row">
                      <div class="col-sm-6">
                        <label class="col-sm-12"><?php echo $this->lang->line("Gender"); ?>: </label>
                        <div class="input-group col-sm-12">
                          <select name='gender' class="form-control" style="padding:6px 2px;">
                          <option value='M' <?php echo (empty($gender) || ($gender != 'F')) ? "selected" : ""; ?>><?php echo $this->lang->line("Male"); ?></option>
                          <option value='F' <?php echo (!empty($gender) && ($gender == 'F')) ? "selected" : ""; ?>><?php echo $this->lang->line("Female"); ?></option>
                          </select>
                        </div>
                      </div>
                      <div class="col-sm-6">
                        <label class="col-sm-12">&nbsp;</label>
                      </div>
                    </div>
                  </div>
                  
                </div>	

                <div id='family_member' style='display:none'>
                  <?php for ($i = 1; $i < 9; $i++) { ?>
                    <div class="row" id='customer_member_<?php echo $i; ?>' style='display:none'>
                    <input type='hidden' name='customer_id_<?php echo $i; ?>'  id='customer_id_<?php echo $i; ?>' value='<?php echo !empty(${'customer_id_' . $i}) ? ${'customer_id_' . $i} : 0; ?>'>
                    <hr />
                    <div class="col-sm-12">
                    <label><?php echo $this->lang->line("Family Member"); ?>   <?php echo $i; ?> </label><br /> <span class="alert-error" id='errormessage_<?php echo $i; ?>'></span>
                    </div>
                  
                      <div class="col-sm-3">
                        <label class="col-sm-12"><?php echo $this->lang->line("First Name"); ?>: </label>
                        <div class="input-group col-sm-12" >
                          <input class="form-control" type='text' name='firstname_<?php echo $i; ?>' id='firstname_<?php echo $i; ?>' value='<?php echo !empty(${'firstname_' . $i}) ? $html_model->escapeQuote(${'firstname_' . $i}) : ''; ?>'>
                        </div>
                      </div>
                      <div class="col-sm-3">
                        <label class="col-sm-12"><?php echo $this->lang->line("Last Name"); ?>: </label>

                        <div class="input-group col-sm-12">	
                          <input class="form-control" type='text' name='lastname_<?php echo $i; ?>' id='lastname_<?php echo $i; ?>' value='<?php echo !empty(${'lastname_' . $i}) ? $html_model->escapeQuote(${'lastname_' . $i}) : ''; ?>'>
                        </div>
                      </div>
                      <div class="col-sm-3">
                        <label class="col-sm-12"><?php echo $this->lang->line("Birth Date"); ?>: </label>
                        <div class='form_text_show'><?php echo !empty(${'birthday_' . $i}) ? ${'birthday_' . $i} : ''; ?></div>
                        <input type="hidden" name='birthday_<?php echo $i; ?>' id='birthday_<?php echo $i; ?>' value='<?php echo !empty(${'birthday_' . $i}) ? ${'birthday_' . $i} : ''; ?>'>
                      </div>
                      <div class="col-sm-3">
                        <div class="row">
                          <div class="col-sm-6">
                            <label class="col-sm-12"><?php echo $this->lang->line("Gender"); ?>: </label>
                            <div class="input-group col-sm-12">
                              <select name='gender_<?php echo $i; ?>' id='gender_<?php echo $i; ?>' class="form-control" style="padding:6px 2px;">
                              <option value='M' <?php echo (empty(${'gender_' . $i}) || (${'gender_' . $i} != 'F')) ? "selected" : ""; ?>><?php echo $this->lang->line("Male"); ?></option>
                              <option value='F' <?php echo (!empty(${'gender_' . $i}) && (${'gender_' . $i} == 'F')) ? "selected" : ""; ?>><?php echo $this->lang->line("Female"); ?></option>
                              </select>
                            </div>
                          </div>
                          <div class="col-sm-6">
                            <label class="col-sm-12"><?php echo $this->lang->line("Relationship"); ?>: </label>
                            <div class="input-group col-sm-12">
                              <select name='relationship_<?php echo $i; ?>' id='relationship_<?php echo $i; ?>' class="form-control relation-selection" style="padding:6px 2px;" onchange="updateRelationOptions()">
                                <option value='Spouse' <?php echo (isset(${'relationship_'.$i}) && (${'relationship_'.$i} == 'Spouse')) ? "selected" : ""; ?>><?php echo $this->lang->line("Spouse"); ?></option>
                                <option value='Parent' <?php echo (isset(${'relationship_'.$i}) && (${'relationship_'.$i} == 'Parent')) ? "selected" : ""; ?>><?php echo $this->lang->line("Parent"); ?></option>
                                <option value='Dependent Child' <?php echo (isset(${'relationship_'.$i}) && (${'relationship_'.$i} == 'Dependent Child')) ? "selected" : ""; ?>><?php echo $this->lang->line("Dependent Child"); ?></option>
                              </select>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  <?php } ?>
                </div>
              </fieldset>
            </div>
          </div><br />

          <div class="row">
            <div class="col-sm-12">
              <fieldset>
                <legend><?php echo $this->lang->line("Address"); ?></legend>
                <div class="row">
                  <div class="form-group col-sm-3">
                    <label class="col-sm-12"><?php echo $this->lang->line("Street No"); ?>: </label>
                    <div class="input-group col-sm-12">
                      <input class="form-control" type='text' name='street_number' value='<?php echo $html_model->escapeQuote($street_number); ?>'>
                    </div>
                    <?php if (!empty($error_street_number)) { ?>
                      <div class="alert-error">
                        <?php echo $error_street_number; ?>
                      </div>
                    <?php } ?>
                  </div>
                  <div class="form-group col-sm-3">
                    <label class="col-sm-12"><?php echo $this->lang->line("Street Name"); ?>: </label>
                    <div class="input-group col-sm-12">
                      <input class="form-control" type='text' name='street_name' value='<?php echo $html_model->escapeQuote($street_name); ?>'>
                    </div>
                    <?php if (!empty($error_street_name)) { ?>
                      <div class="alert-error">
                        <?php echo $error_street_name; ?>
                      </div>
                    <?php } ?>
                  </div>
                  <div class="form-group col-sm-3">
                    <label class="col-sm-12"><?php echo $this->lang->line("Suite No."); ?>: </label>
                    <div class="input-group col-sm-12">
                      <input class="form-control" type='text' name='suite_number' value='<?php echo $html_model->escapeQuote($suite_number); ?>'>
                    </div>
                  </div>
                  <div class="form-group col-sm-3">
                    <label class="col-sm-12"><?php echo $this->lang->line("City"); ?>: </label>
                    <div class="input-group col-sm-12">
                      <input class="form-control" type='text' name='city' value='<?php echo $html_model->escapeQuote($city); ?>'>
                    </div>
                    <?php if (!empty($error_city)) { ?>
                      <div class="alert-error">
                        <?php echo $error_city; ?>
                      </div>
                    <?php } ?>
                  </div>
                </div>
                <div class="row">	
                  <div class="form-group col-sm-3">
                    <label class="col-sm-12"><?php echo $this->lang->line("Province"); ?>: </label>
                    <div class="input-group col-sm-12">
                      <div id='province2_div'></div>
                    </div>
                    <?php if (!empty($error_province2)) { ?>
                      <div class="alert-error">
                        <?php echo $error_province2; ?>
                      </div>
                    <?php } ?>
                  </div>
                  <div class="form-group col-sm-3">
                    <label class="col-sm-12"><?php echo $this->lang->line("Country"); ?>: </label>
                    <div class="input-group col-sm-12">
                    <?php if (($product_short == "JES") || ($product_short == "JESP") || ($product_short == "JFR") || ($product_short == "JFS") || ($product_short == "JFVTC")) { ?>
                        Canada
                        <input type='hidden' name='country2' value='CA'>
                    <?php } else { ?>
                        <div id='country2_div'></div>
                    <?php } ?>
                    </div>
                  </div>
                  <div class="form-group col-sm-3">
                    <label class="col-sm-12"><?php echo $this->lang->line("Postcode"); ?>: </label>
                    <div class="input-group col-sm-12">
                      <input class="form-control" type='text' name='postcode' value='<?php echo $html_model->escapeQuote($postcode); ?>'>
                    </div>
                    <?php if (!empty($error_postcode)) { ?>
                      <div class="alert-error">
                        <?php echo $error_postcode; ?>
                      </div>
                    <?php } ?>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-3">
                    <label class="col-sm-12"><?php echo $this->lang->line("Phone"); ?>1: </label>
                    <div class="input-group col-sm-12">
                      <input class="form-control" type='text' name='phone1' value='<?php echo $html_model->escapeQuote($phone1); ?>'>
                    </div>
                    <?php if (!empty($error_phone1)) { ?>
                      <div class="alert-error">
                        <?php echo $error_phone1; ?>
                      </div>
                    <?php } ?>
                  </div>
                  <div class="col-sm-3">
                    <label class="col-sm-12"><?php echo $this->lang->line("Phone"); ?>2: </label>
                    <div class="input-group col-sm-12">
                      <input class="form-control" type='text' name='phone2' value='<?php echo $html_model->escapeQuote($phone2); ?>'>
                    </div>
                  </div>
                </div>
              </fieldset>
            </div>
          </div><br />
          <div class="row">
            <div class="col-sm-12">
              <fieldset>
                <legend>Contact</legend>
                <div class="row">
                  <div class="col-sm-3">
                    <label class="col-sm-12"><?php echo $this->lang->line("Email"); ?>: </label>
                    <div class="input-group col-sm-12">
                      <input class="form-control" type='text' name='contact_email' value='<?php echo $html_model->escapeQuote($contact_email); ?>'>
                    </div>
                    <?php if (!empty($error_contact_email)) { ?>
                      <div class="alert-error">
                        <?php echo $error_contact_email; ?>
                      </div>
                    <?php } ?>
                  </div>
                  <div class="col-sm-3">
                    <label class="col-sm-12"><?php echo $this->lang->line("Contact Phone"); ?>: </label>
                    <div class="input-group col-sm-12">
                      <input class="form-control" type='text' name='contact_phone' value='<?php echo $html_model->escapeQuote($contact_phone); ?>'>
                    </div>
                  </div>
                  <div class="col-sm-3">
                    <label class="col-sm-12"><?php echo $this->lang->line("Country of Origin"); ?>: </label>
                    <div class="input-group col-sm-12">
                      <input class="form-control" type='text' name='residence' value='<?php echo $html_model->escapeQuote($residence); ?>'>
                    </div>
                  </div>
                  <div class="col-sm-3">
                    <label class="col-sm-12"><?php echo $this->lang->line("Prefer Language"); ?>: </label>
                    <div class="input-group col-sm-12">
                      <select name='contact_language' id='contact_language' class="form-control" style="padding:6px 2px;">
                        <option value='English' <?php echo (empty($contact_language) || ($contact_language == 'English')) ? "selected" : ""; ?>>English</option>
                        <option value='French' <?php echo ($contact_language == 'French') ? "selected" : ""; ?>>French</option>
                      </select>
                    </div>
                  </div>
              </fieldset>
            </div>
          </div><br />	
                    
          <div class="row">
            <div class="col-sm-12">
              <fieldset>
                <legend><?php echo $this->lang->line("Special Note"); ?>/<?php echo $this->lang->line("Instructions"); ?></legend>
                <div class="row">
                  <div class="col-sm-12">
                    <label class="col-sm-12"><?php echo $this->lang->line("Notes"); ?>: </label>
                    <div class="input-group col-sm-12">
                       <input type='hidden' name="note" value='<?php echo $html_model->escapeQuote($note); ?>'>
                       <?php echo $note; ?>
                     </div>
                   </div>
                </div>
                
              </fieldset>
            </div>
          </div><br />
					<?php if (!empty($monthly_payment)) { ?>
						<div class="row">
							<div class="col-sm-12">
								<div class="x_panel">
									<div class="x_title">
										<h2>Monthly Payment Detail</h2>
										<div class="clearfix"></div>
									</div>
									<div class="x_content">
										<div class="row">
											<div class="col-sm-3">
												<label class="inline">Payment Date</label>
											</div>
											<div class="col-sm-3">
												<label class="inline">Amount</label>
											</div>
											<div class="col-sm-3">
												<label class="inline">Payment Status</label>
											</div>
											<div class="col-sm-3">
												<label class="inline">&nbsp;</label>
											</div>
										</div>
										<?php $idx = 0; foreach ($monthly_payment as $mr) { $idx++; ?>
										<div class="row" style="line-height: 32px;">
											<div class="col-sm-3">
												<span><?php echo $mr["retry_date"]; ?></span>
											</div>
											<div class="col-sm-3">
												<span><?php echo number_format($mr["amount"], 2, ".", ""); ?></span>
											</div>
											<div class="col-sm-3">
												<span><?php echo ($mr["paid"]==1)?"Paid":(($mr["paid"]==-2)?"Error":(($mr["paid"]==-1)?"Void":"-")); ?></span>
											</div>
											<div class="col-sm-3">
												&nbsp;
											</div>
										</div>
										<?php } ?>
									</div><!--/x_content end-->
								</div><!--x_panel-->
							</div>
						</div>
  				<?php } ?>
          <?php if ($user_group_id != 106) { ?>
            <div class="row">
              <div class="col-sm-12" id='goto_next_page'>
              <?php if (!empty($next_url)) { ?>
                <a href='<?php echo $next_url; ?>'><span class="btn btn-info"><?php echo $this->lang->line("No Change"); ?></span></a>
              <?php } ?>
              <?php if ($isprocessplan && ($status_id != 5) && ($status_id != 6)) { ?>
                  <input class="btn btn-primary pull-right" type='submit' id='page-submit' name='submit' value='<?php echo $submit; ?>' />		
              <?php } ?>
              </div>

              <div class="col-sm-12 alert-error float-error" title="Click to Close the notice" style="display:none;" id='error_next_page'>
              </div>
            </div>
          <?php } ?>
          
          <div class="row">
            <div class="col-sm-12">
              <?php if (!empty($error_premium)) { ?>
                  <div class="alert-error float-perror" title="Click to Close the notice" id="premium_error">
                  <?php echo $error_premium; ?>
                  </div>
              <?php } ?>
            </div>
          </div>
        </form> 
        </div><!-- /x_content end-->
                </div><!-- /x_panel end -->
              </div>
            </div><!-- End Form Section-->
      </div>
        </div>
        <!-- /page content -->
<script>
  $('#error_next_page').click(function(){
    $('#error_next_page').css('display','none');
  });
</script>

<script>
  $('#premium_error').click(function(){
    $('#premium_error').css('display','none');
  });
</script>
<script>
var age85 = 0;
$( document ).ready(function() {
  $.ajax({
    url: '<?php echo $province_url; ?>',
    type: 'GET',
    success: function(data, textStatus, jqXHR) {
       $('#province2_div').html(data);
      get_premium();
     },
  });
  $.ajax({
    url: '<?php echo $country_url; ?>',
    type: 'GET',
    success: function(data, textStatus, jqXHR) {
       $('#country2_div').html(data);
      get_premium();
     },
  });

  addmoremember();

  $("form").submit(function() {
    // plan_edit_form
    var dynamicSelect = document.getElementById('province2');
    // console.log("Submitted dynamicSelect", dynamicSelect);
    // console.log("Submitted dynamicSelect2:", dynamicSelect.value);
    var myform = document.getElementById('plan_edit_form');
    myform.appendChild(dynamicSelect);
    myform.submit();
  });

        $('.withlogobox').change(function() {
                var w = '0';
                if ($(this).is(':checked')) {
                        $('.withlogobox').prop('checked', true);
                        w = '1';
                } else {
                        $('.withlogobox').prop('checked', false);
                }
                $.ajax({
                        url: '<?php echo $export_logo_url; ?>' + w,
                        type: 'GET',
                        success: function(data, textStatus, jqXHR) {
                        //console.log(data);
                },
                });
        });

        $('.withpricebox').change(function() {
                var w = '0';
                if ($(this).is(':checked')) {
                        $('.withpricebox').prop('checked', true);
                        w = '1';
                } else {
                        $('.withpricebox').prop('checked', false);
                }
                $.ajax({
                        url: '<?php echo $export_price_url; ?>' + w,
                        type: 'GET',
                        success: function(data, textStatus, jqXHR) {
                        //console.log(data);
                },
                });
        });

});

function addmoremember() {
  for (i = 1; i < 9; i++) {
    $('#firstname_' + i).removeClass('alert-error-input');
    $('#lastname_' + i).removeClass('alert-error-input');
    $('#birthday_' + i).removeClass('alert-error-input');
    if ( !$('#firstname_' + i).val() && !$('#lastname_' + i).val() && !$('#birthday_' + i).val()) {
      break;
    }
    $('#customer_member_' + i).show();
    $('#family_member').show();
    if ( !$('#firstname_' + i).val() ) {
      $('#firstname_' + i).addClass('alert-error-input');
    }
    if ( !$('#lastname_' + i).val() ) {
      $('#lastname_' + i).addClass('alert-error-input');
    }
    if ( !$('#birthday_' + i).val() ) {
      $('#birthday_' + i).addClass('alert-error-input');
    }
    if ( !$('#firstname_' + i).val() || !$('#lastname_' + i).val() || !$('#birthday_' + i).val()) {
      $('#errormessage_' + i).html("Please fill in all required information.");
      break;
    }
    $('#errormessage_' + i).html("");
  }
  i++;
  for ( ; i < 9; i++) {
    $('#firstname_' + i).removeClass('alert-error');
    $('#lastname_' + i).removeClass('alert-error');
    $('#birthday_' + i).removeClass('alert-error');
    $('#customer_member_' + i).hide();
    $('#errormessage_' + i).html("");
  }
}
function updateRelationOptions() {
  // Get all select elements
  const allSelects = document.querySelectorAll('select.relation-selection');
  
  // Reset all options to enabled first
  allSelects.forEach(select => {
    Array.from(select.options).forEach(option => {
      if (option.value === 'Parent' || option.value === 'Dependent Child') {
        option.disabled = false;
      }
    });
  });
  
  // Check if any select has "Parent" or "Dependent Child" selected
  let hasParent = false;
  let hasChild = false;
  
  allSelects.forEach(select => {
    if (select.value === 'Parent') hasParent = true;
    if (select.value === 'Dependent Child') hasChild = true;
  });
  
  // Apply disabling logic
  if (hasParent) {
    allSelects.forEach(select => {
      Array.from(select.options).forEach(option => {
        if (option.value === 'Dependent Child' && select.value !== 'Dependent Child') {
          option.disabled = true;
        }
      });
    });
  }
  
  if (hasChild) {
    allSelects.forEach(select => {
      Array.from(select.options).forEach(option => {
        if (option.value === 'Parent' && select.value !== 'Parent') {
          option.disabled = true;
        }
      });
    });
  }
}
function get_premium() {
<?php if (empty($batch_number)) { ?>
    var status_id = '<?php echo $status_id; ?>';
    var plan_id = $('input[name="plan_id"]').val();
    var product_short = $('input[name="product_short"]').val();
    var apply_date = $('input[name="apply_date"]').val();
    var effective_date = $('input[name="effective_date"]').val();
    var expiry_date = $('input[name="expiry_date"]').val();
    var isfamilyplan = '';
    var spouse = '';

    if ( $( "#isfamilyplan" ).length ) {
      isfamilyplan = $('input[name="isfamilyplan"]').val();
    }
    var sum_insured = 0;
    if ( $( "#sum_insured" ).length ) {
      sum_insured = $('input[name="sum_insured"]').val();
    }
    var deductible_amount = 0;
    if ($('#deductible_amount').length) {
      deductible_amount = $('input[name="deductible_amount"]').val();
    }
    var stable_condition = 0;
    if ($('#stable_condition').length) {
      stable_condition = $('input[name="stable_condition"]').val();
    }
    var rate_options = 0;
    if ($('#rate_options').length) {
      rate_options = $('input[name="rate_options"]').val();
    }
    var holiday_rate = 0;
    if ($('input[name="holiday_rate"]').length) {
      holiday_rate = $('input[name="holiday_rate"]').val();	// checkbox
    }
    var birthday = $('input[name="birthday"]').val();	// 
    var number_customer = 0;
    if ((product_short != 'NUS') && (product_short != 'JUS')) {
      if (new Date(birthday) > new Date($('input[name="birthday_1"]').val())) {
        birthday = $('input[name="birthday_1"]').val();
      } 
      if (new Date(birthday) > new Date($('input[name="birthday_2"]').val())) {
        birthday = $('input[name="birthday_2"]').val();
      } 
      if (new Date(birthday) > new Date($('input[name="birthday_3"]').val())) {
        birthday = $('input[name="birthday_3"]').val();
      } 
      if (new Date(birthday) > new Date($('input[name="birthday_4"]').val())) {
        birthday = $('input[name="birthday_4"]').val();
      } 
      if (new Date(birthday) > new Date($('input[name="birthday_5"]').val())) {
        birthday = $('input[name="birthday_5"]').val();
      } 
      if (new Date(birthday) > new Date($('input[name="birthday_6"]').val())) {
        birthday = $('input[name="birthday_6"]').val();
      } 
      if (new Date(birthday) > new Date($('input[name="birthday_7"]').val())) {
        birthday = $('input[name="birthday_7"]').val();
      } 
      if (new Date(birthday) > new Date($('input[name="birthday_8"]').val())) {
        birthday = $('input[name="birthday_8"]').val();
      }
    }
  
    if ($('input[name="firstname"]').val() && $('input[name="lastname"]').val()) number_customer++;
    if ($('input[name="firstname_1"]').val() && $('input[name="lastname_1"]').val()) number_customer++;
    if ($('input[name="firstname_2"]').val() && $('input[name="lastname_2"]').val()) number_customer++;
    if ($('input[name="firstname_3"]').val() && $('input[name="lastname_3"]').val()) number_customer++;
    if ($('input[name="firstname_4"]').val() && $('input[name="lastname_4"]').val()) number_customer++;
    if ($('input[name="firstname_5"]').val() && $('input[name="lastname_5"]').val()) number_customer++;
    if ($('input[name="firstname_6"]').val() && $('input[name="lastname_6"]').val()) number_customer++;
    if ($('input[name="firstname_7"]').val() && $('input[name="lastname_7"]').val()) number_customer++;
    if ($('input[name="firstname_8"]').val() && $('input[name="lastname_8"]').val()) number_customer++;

    if ($('#spousediv').length) {
      if ($('#spouse').get(0).checked) {
        spouse = 1;
      }
    }

    if (effective_date && expiry_date) {
      var birthdays = [];
      birthdays.push($('input[name="birthday"]').val());
      for (var i=1; i < 9; i++) {
        if ($('input[name="birthday_'+i+'"]').val()) {
          birthdays.push($('input[name="birthday_'+i+'"]').val());
        } else {
          break;
        }
      }
      $.ajax({
        url: '<?php echo $premium_url; ?>',
        type: 'get',
        data: {
          status_id: status_id,
          plan_id: plan_id,
          product_short: product_short,
          apply_date: apply_date,
          effective_date: effective_date,
          expiry_date: expiry_date,
          isfamilyplan: isfamilyplan,
          sum_insured: sum_insured,
          deductible_amount: deductible_amount,
          stable_condition: stable_condition,
          rate_options: rate_options,
          holiday_rate: holiday_rate,
          number_customer: number_customer,
          spouse: spouse,
          birthday: birthday,
          birthdays: birthdays
        },
        success: function(data, textStatus, jqXHR) {
          if (data['status'] == 'OK') {
                $('input[name="premium"]').val(data['premiumarr']['premium']);
            <?php if ($user_group_id > 100) { ?>
              $('#premiumdisplay').html(data['premiumarr']['premium']);
            <?php } ?>
            $('#totalyears').val(data['premiumarr']['totalyears']);
            $('#totaldays').val(data['premiumarr']['totaldays']);
            $('#dailyrate').val(data['premiumarr']['dailyrate']);
            if (data['premiumarr']['message']) {
              $('#error_next_page').html(data['premiumarr']['message']);
              $('#error_next_page').css('display','block');
            } else {
              $('#error_next_page').html('');
              $('#error_next_page').css('display','none');
            }
            if (data['premiumarr']['force_deductable']) {
                  $('input[name="force_deductable"]').val(data['premiumarr']['force_deductable']);
            } else {
                  $('input[name="force_deductable"]').val(0);
            }

            if (age85 && (data['premiumarr']['totalyears'] <= 85)) {
              if ( $( "#deductible_amount_div" ).length ) {
                $.ajax({
                  url: '<?php echo $deductible_amount_url; ?>',
                  success: function(data, textStatus, jqXHR) {
                        $('#deductible_amount_div').html(data);
                    get_premium();
                    },
                });
              }
              age85 = 0;
            } else if (!age85 && (data['premiumarr']['totalyears'] > 85)) {
              if ( $( "#deductible_amount_div" ).length ) {
                $.ajax({
                  url: '<?php echo $deductible_amount_url; ?>/500only',
                  success: function(data, textStatus, jqXHR) {
                        $('#deductible_amount_div').html(data);
                    get_premium();
                    },
                });
              }
              age85 = 1;
            }
            //if (data['premiumarr']['premium']) {
            //	$('#goto_next_page').show();
            //} else {
            //	$('#goto_next_page').hide();
            //}
          } else if (data['status'] == 'Days') {
            $('#totaldays').val(data['days']);
          } else {
            // $('#goto_next_page').hide();
            if (data['message']) {
              $('#error_next_page').html(data['message']);
              $('input[name="premium"]').val(0);
            }
          }
          },
      });
    }
<?php } ?>
} 
</script>
<script type="text/javascript">
$(document).ready(function(){
  $('#formid').on('keyup keypress', function(e) {
    var keyCode = e.keyCode || e.which;
    if (keyCode === 13) {
      e.preventDefault();
      return false;
    }
  });
  $("#arrival_date_div").datepicker({
        startDate: '-5y',
        endDate: '+2y',
    });
    $('#totaldays').change(function(){
        var days = $('#totaldays').val();
        var effective = $('#effective_date_div').datepicker('getDate');
        var myDate = new Date(effective);
        days--;
        var tm = myDate.getTime() + (days * 86400000) + 43200000;
        myDate.setTime(tm);
        $('#expiry_date_div').datepicker('setDate', myDate);
        get_premium();
    });
});
</script>
