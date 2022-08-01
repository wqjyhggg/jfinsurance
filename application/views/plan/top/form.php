<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<style>
  .nav-tabs>li.active>a {
    background-color: #ffffff !important;
  }
  .nav-tabs>li>a:hover {
    background-color: #ffffff !important;
  }
  .btn {
    margin: -15px -12px;
  }
  .block-space {
    margin-top: 20px;
  }
</style>
<!-- Plan page content -->
<?php if (isset($menu) && is_array($menu) && (sizeof($menu) > 0)) { ?>
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
<div class="right_col" role="main" style="padding-bottom: 60px;">
<?php } else { ?>
<!-- page content -->
<div role="main" style="padding-left: 28px; padding-bottom: 60px;">
<?php } ?>
  <div class="main-div">
    <div class="page-title">
      <div class="col-sm-6">
        <h3><?php echo $plan_full_name; ?></h3>
      </div>
      <div class="col-sm-6">
        <h2>Premium: $<span id='premium_value'><?php echo $premium; ?></span> <span id='premium_rate_table'></span></h2>
      </div>
    </div>
    <div class="clearfix"></div>
    <!-- Form Section -->
    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="alert-error"><H2 style="width: 100%; text-align: center;" id="title_alert_message"><?php if (empty($plan_id) || empty($status_id)) { ?>The policy must purchase before departure<?php } ?></H2></div>

        <div class="alert-error"><?php echo empty($error_message) ? '' : $error_message . "<br>"; ?></div>
        <ul class="nav nav-tabs" id="top-nav-tabs">
          <li id='date_members_li' class="active"><a data-toggle="tab" id="date_members_tab" href="#date_members">Date / Members</a></li>
          <li id='packages_li'><a data-toggle="tab" id="packages_tab" href="#packages">Packages</a></li>
          <li id='questionnaire_li'><a data-toggle="tab" id="questionnaire_tab" href="#questionnaire" <?php if (empty($questionnaire)) { ?>style='display: none' <?php } ?>>Questionnaire</a></li>
          <?php if (!empty($plan_id) && !empty($status_id)) { ?>
            <?php if ($status_id == 1 && $user_group_id != 103 && $next_url) { /* qutoe */ ?>
              <li style="float: right;"><a href="<?php echo $pay_url; ?>"><span class="btn btn-info" style='color: #fff;'>Pay</span></a></li>
            <?php } ?>
            <?php if (($status_id == Plan_model::SOLD) || ($status_id == Plan_model::PAID)) { ?>
              <?php if ($export_logo_price_option) { ?>
                <li style="float: right;">
                  <div class='pull-right spdf-option' style='margin-top: 9px;'>
                    <input type='checkbox' class='withlogobox' checked> With Logo <br />
                    <input type='checkbox' class='withpricebox' checked> With Price
                  </div>
                </li>
              <?php } ?>
              <li style="float: right;"><a href="<?php echo $pdf_url; ?>" target="_blank"><span class="btn btn-info" style='color: #fff;'>Export PDF</span></a></li>
              <?php if (!empty($print_receipt_url)) { ?>
                <li style="float: right;"><a href="<?php echo $print_receipt_url; ?>" target="_blank"><span class="btn btn-info" style='color: #fff;'>Print Receipt</span></a></li>
              <?php } ?>
              <?php if (!empty($print_card_url)) { ?>
                <li style="float: right;"><a href="<?php echo $print_card_url; ?>" target="_blank"><span class="btn btn-info" style='color: #fff;'>Print Card</span></a></li>
              <?php } ?>
              <li style="float: right;"><a href="<?php echo $sendpackage_url . $plan_id; ?>"><span class="btn btn-info" style='color: #fff;'>Send Package</span></a></li>
            <?php } ?>
            <?php if ((($status_id == Plan_model::PAID) || ($status_id == Plan_model::SOLD) || ($status_id == Plan_model::CHANGED)) && $user_group_id <= 100) { ?>
              <li style="float: right;"><a href="<?php echo $cancel_url . $plan_id; ?>" target="_blank"><span class="btn btn-info" style='color: #fff;'>Cancel</span></a></li>
              <li style="float: right;"><a href="<?php echo $refund_url . $plan_id; ?>" target="_blank"><span class="btn btn-info" style='color: #fff;'>Refund</span></a></li>
            <?php } else if (($status_id == Plan_model::REFUND) && ($user_group_id <= 100) && !empty($refund_letter_url)) { ?>
              <li style="float: right;"><a href="<?php echo $refund_letter_url; ?>" target="_blank"><span class="btn btn-info" style='color: #fff;'>Refund Letter</span></a></li>
            <?php } else if (($status_id == Plan_model::CANCEL) && ($user_group_id <= 100) && !empty($cancel_letter_url)) { ?>
              <li style="float: right;"><a href="<?php echo $cancel_letter_url; ?>" target="_blank"><span class="btn btn-info" style='color: #fff;'>Cancel Letter</span></a></li>
            <?php } ?>
          <?php } ?>
        </ul>
        <div class="clearfix"></div>
        <form action='<?php echo $action_url; ?>' method='POST' class="form-horizontal" id="plan_form">
          <input type='hidden' name='<?php echo $csrf['name']; ?>' value='<?php echo $csrf['value']; ?>'>
          <input type='hidden' name='dailyrate' step='0.01' id='dailyrate' value='<?php echo $dailyrate; ?>'>
          <input type='hidden' name='plan_id' value='<?php echo $plan_id; ?>'>
          <input type='hidden' name='totalyears' id='totalyears' value='<?php echo $totalyears; ?>'>
          <input type='hidden' name='premium' step='0.01' id='premium' value='<?php echo $premium; ?>'>
          <input type='hidden' name='tax' step='0.01' id='tax' value='<?php echo $tax; ?>'>
          <input type='hidden' name=product_short id='product_short' value='<?php echo $product_short; ?>'>
          <input type="hidden" name="questionnaire" value="<?php echo $questionnaire; ?>">
          <input type="hidden" name="country2" id='country2' value="CA">
          <div class="tab-content">
            <div id="date_members" class="tab-pane fade in active">
              <!-- Start data members -->
              <div class="x_panel">
                <div class="x_content">
                  <?php if (($beuser_user_id == 1) || ($beuser_user_id == 2762)) { ?>
                    <div class="form-group col-sm-3">
                      <h2><label><span>Date / Members</span></label></h2>
                    </div>
                    <div class="form-group col-sm-3">
                      <label style="display: inline-block;">User ID:</label>
                      <div style="display: inline-block;">
                        <input class="form-control" type="text" name='user_id' value='<?php echo $user_id; ?>'>
                      </div>
                    </div>
                  <?php } else { ?>
                    <div class="form-group col-sm-6">
                      <h2><label><span>Date / Members</span></label></h2>
                    </div>
                  <?php } ?>
                  <?php if ($user_group_id != 1) { ?>
                    <div class="form-group col-sm-3"><label style="font-size: 16px;">Status: <?php echo $status_list[$status_id]['name']; ?> </label></div>
                    <input type='hidden' name='status_id' value='<?php echo $status_id; ?>' class="form-control">
                  <?php } else { ?>
                    <div class="form-group col-sm-3">
                      <label style="display: inline-block;">Status:</label>
                      <div style="display: inline-block;">
                        <select name='status_id' class="form-control">
                          <option value='0'>-- select policy status --</option>
                          <?php foreach ($status_list as $key => $value) { ?>
                            <option value='<?php echo $key; ?>' <?php echo ($key == $status_id) ? 'selected' : ''; ?>><?php echo $value['name']; ?></option>
                          <?php } ?>
                        </select>
                      </div>
                    </div>
                  <?php } ?>
                  <?php if (!empty($plan_cancel_date)) { ?>
                    <div class="form-group col-sm-3">
                      <label class="inline">Cancel Date: ( <?php echo $plan_cancel_date; ?> )</label>
                    </div>
                  <?php } else if (!empty($plan_refund_date)) { ?>
                    <div class="form-group col-sm-3">
                      <label class="inline">Refund Date: ( <?php echo $plan_refund_date; ?> )</label>
                    </div>
                  <?php } ?>
                  <div class="clearfix"></div>

                  <div class="col-sm-12 block-space">
                    <fieldset>
                      <legend>Travel Dates</legend>
                      <div class="row">
                        <div class="form-group col-sm-3">
                          <label class="col-sm-12">Apply Date (YYYY-MM-DD):</label>
                          <?php if ($user_group_id != 1) { ?>
                            <input type="hidden" name='apply_date' value='<?php echo $apply_date; ?>'>
                            <?php echo $apply_date; ?>
                          <?php } else { ?>
                            <div class="input-group date" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd">
                              <input class="form-control" size="16" type="text" name='apply_date' value='<?php echo $apply_date; ?>'>
                              <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div>
                          <?php } ?>
                        </div>
                        <div class="form-group col-sm-3">
                          <label class="col-sm-12">Departure Date (YYYY-MM-DD): </label>
                          <?php if (($user_group_id > 100) && $no_change) { ?>
                            <input type="hidden" name='arrival_date' value='<?php echo $arrival_date; ?>'>
                            <?php echo $arrival_date; ?>
                          <?php } else { ?>
                            <div class="input-group date" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd" id='arrival_date_div'>
                              <input class="form-control" size="16" type="text" name='arrival_date' value='<?php echo $arrival_date; ?>'>
                              <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div>
                            <?php if (!empty($arrival_date_date)) { ?>
                              <div class="alert-error"><?php echo $arrival_date_date; ?></div>
                            <?php } ?>
                          <?php } ?>
                        </div>
                        <div class="form-group col-sm-3">
                          <label class="col-sm-12">Effective Date (YYYY-MM-DD): </label>
                          <?php if (($user_group_id > 100) && $no_change) { ?>
                            <input type="hidden" name='effective_date' value='<?php echo $effective_date; ?>'>
                            <?php echo $effective_date; ?>
                          <?php } else { ?>
                            <div class="input-group date" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd" id='effective_date_div'>
                              <input class="check_premium form-control" size="16" type="text" name='effective_date' value='<?php echo $effective_date; ?>'>
                              <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div>
                            <?php if (!empty($error_effective_date)) { ?>
                              <div class="alert-error"><?php echo $error_effective_date; ?></div>
                            <?php } ?>
                          <?php } ?>
                        </div>
                        <div class="form-group col-sm-3">
                          <label class="col-sm-12">Expiry Date (YYYY-MM-DD): </label>
                          <?php if (($user_group_id > 100) && $no_change) { ?>
                            <input type="hidden" name='expiry_date' value='<?php echo $expiry_date; ?>'>
                            <?php echo $expiry_date; ?>
                          <?php } else { ?>
                            <div class="input-group date" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd" id='expiry_date_div'>
                              <input size="16" type='text' name='expiry_date' class='check_premium form-control' id='expiry_date' value='<?php echo $expiry_date; ?>'>
                              <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div>
                            <?php if (!empty($error_expiry_date)) { ?>
                              <div class="alert-error"><?php echo $error_expiry_date; ?></div>
                            <?php } ?>
                          <?php } ?>
                        </div>
                        <div class="form-group col-sm-3">
                          <label class="col-sm-12">Days: </label>
                          <?php if (($user_group_id > 100) && $no_change) { ?>
                            <input type="hidden" name='totaldays' value='<?php echo $totaldays; ?>'>
                            <?php echo $totaldays; ?>
                          <?php } else { ?>
                            <div class='form_text_show'>
                              <input class="form-control check_premium" type='number' name='totaldays' id='totaldays' value='<?php echo $totaldays; ?>'>
                            </div>
                          <?php } ?>
                        </div>
                        <div class="form-group col-sm-3">
                          <label class="col-sm-12">Family / Group: </label>
                          <?php if (($user_group_id > 100) && $no_change) { ?>
                            <input type="hidden" name='isfamilyplan' value='<?php echo $isfamilyplan; ?>'>
                            <?php echo ($isfamilyplan == 1) ? 'Family' : (($isfamilyplan == 2) ? 'Group' : 'Single'); ?>
                          <?php } else { ?>
                            <div class='form_text_show'>
                              <select name='isfamilyplan' class="form-control check_premium" style="padding: 6px 2px;">
                                <option value='0' <?php echo (($isfamilyplan != 1) && ($isfamilyplan != 2)) ? "selected" : ""; ?>>Single</option>
                                <option value='1' <?php echo ($isfamilyplan == 1) ? "selected" : ""; ?>>Family</option>
                                <option value='2' <?php echo ($isfamilyplan == 2) ? "selected" : ""; ?>>Group</option>
                              </select>
                            </div>
                          <?php } ?>
                        </div>
                        <div class="form-group col-sm-3">
                          <label class="col-sm-12">Beneficiary</label>
                          <div class="input-group col-sm-12">
                            <input type='text' name='beneficiary' value='<?php echo $html_model->escapeQuote($beneficiary); ?>' class="form-control">
                          </div>
                          <?php if (!empty($error_beneficiary)) { ?>
                            <div class="alert-error"><?php echo $error_beneficiary; ?></div>
                          <?php } ?>
                        </div>
                      </div>
                      <?php if (!empty($error_claim)) { ?>
                        <div class="row">
                          <div class="col-sm-12">
                            <div class="alert-error">
                              <strong><?php echo $error_claim; ?></strong>
                            </div>
                          </div>
                        </div>
                        <?php if (($do_user_id > 0) && ($user_group_id < 100)) { ?>
                          <div class="row">
                            <div class="col-sm-12">
                              <label class="col-sm-12">By Check the checkbox, you can allow this policy to continue to pay. Please fill in your reason before cilck the checkbox.</label>
                            </div>
                          </div>
                          <div class="row">
                            <div class="form-group col-sm-6">
                              <textarea name='claim_allow_note' id='claim_allow_note' style='width: 100%'><?php echo $claim_allow_note; ?></textarea>
                            </div>
                            <div class="form-group col-sm-2">
                              <input type='checkbox' class='setpremium' id='claim_allowed'> Allow this policy
                              <input type='hidden' name='claim_allow_by' id='claim_allow_by' value=''>
                            </div>
                          </div>
                        <?php } ?>
                      <?php } else if (($claim_allow_by > 0) && ($status_id < 2)) { ?>
                        <div class="row">
                          <div class="form-group col-sm-6">
                            <textarea name='claim_allow_note' id='claim_allow_note' style='width: 100%'><?php echo $claim_allow_note; ?></textarea>
                          </div>
                          <div class="form-group col-sm-6">
                            <input type='checkbox' class='setpremium' id='claim_allowed' checked> Un-check to Disallow this policy
                            <input type='hidden' name='claim_allow_by' id='claim_allow_by' value=''>
                          </div>
                        </div>
                      <?php } ?>
                    </fieldset>
                  </div>

                  <div class="col-sm-12 blcok-space">
                    <fieldset>
                      <legend>Insurable Member(s)</legend>
                      <input type='hidden' name='customer_id' value='<?php echo !empty($customer_id) ? $customer_id : 0; ?>'>
                      <div class="row">
                        <label class="col-sm-12">Customer Information</label>
                        <div class="col-sm-3">
                          <label class="col-sm-12">First Name:</label>
                          <div class="input-group col-sm-12">
                            <input class="form-control" type='text' name='firstname' value='<?php echo !empty($firstname) ? $html_model->escapeQuote($firstname) : ''; ?>'>
                          </div>
                          <?php if (!empty($error_firstname)) { ?>
                            <div class="alert-error"><?php echo $error_firstname; ?></div>
                          <?php } ?>
                        </div>
                        <div class="col-sm-3">
                          <label class="col-sm-12">Last Name:</label>
                          <div class="input-group col-sm-12">
                            <input class="form-control" type='text' name='lastname' value='<?php echo !empty($lastname) ? $html_model->escapeQuote($lastname) : ''; ?>'>
                          </div>
                          <?php if (!empty($error_lastname)) { ?>
                            <div class="alert-error"><?php echo $error_lastname; ?></div>
                          <?php } ?>
                        </div>
                        <div class="col-sm-3">
                          <label class="col-sm-12">Birth Date (YYYY-MM-DD):</label>
                          <div class="input-group date" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd" data-date-end-date="0d">
                            <input size="16" type="text" class='check_premium form-control' name='birthday' value='<?php echo !empty($birthday) ? $birthday : ''; ?>'>
                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                          </div>
                          <?php if (!empty($error_birthday)) { ?>
                            <div class="alert-error"><?php echo $error_birthday; ?></div>
                          <?php } ?>
                        </div>
                        <div class="col-sm-3">
                          <div class="row">
                            <div class="col-sm-6">
                              <label class="col-sm-12">Gender: </label>
                              <div class="input-group col-sm-12">
                                <select name='gender' class="form-control" style="padding: 6px 2px;">
                                  <option value='M' <?php echo (empty($gender) || ($gender != 'F')) ? "selected" : ""; ?>>Male</option>
                                  <option value='F' <?php echo (!empty($gender) && ($gender == 'F')) ? "selected" : ""; ?>>Female</option>
                                </select>
                              </div>
                            </div>
                            <div class="col-sm-6">
                              <label class="col-sm-12">&nbsp;</label>
                              <div class="col-sm-12">
                                <?php if ((($status_id == 2) || ($status_id == 3) || ($status_id == 4)) && !empty($customer_id) && $user_group_id != 3 && ($user_group_id < 100)) { ?>
                                  <a class="btn btn-primary" href='<?php echo $claimurl . $customer_id; ?>'>Claim</a>
                                <?php } ?>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>

                      <div id='family_member'>
                        <?php for ($i = 1; $i <= $max_member; $i++) { ?>
                          <div class="row" id='customer_member_<?php echo $i; ?>' <?php if (empty(${'birthday_' . $i})) { ?>style='display: none' <?php } ?>>
                            <input type='hidden' name='customer_id_<?php echo $i; ?>' id='customer_id_<?php echo $i; ?>' value='<?php echo !empty(${'customer_id_' . $i}) ? ${'customer_id_' . $i} : 0; ?>'>
                            <hr />
                            <div class="col-sm-12">
                              <label>Family Member <?php echo $i; ?> </label>
                              <span class="alert-error" id='errormessage_<?php echo $i; ?>'></span>
                            </div>
                            <div class="col-sm-3">
                              <label class="col-sm-12">First Name: </label>
                              <div class="input-group col-sm-12">
                                <input class="form-control" type='text' name='firstname_<?php echo $i; ?>' id='firstname_<?php echo $i; ?>' value='<?php echo !empty(${'firstname_' . $i}) ? $html_model->escapeQuote(${'firstname_' . $i}) : ''; ?>'>
                              </div>
                            </div>
                            <div class="col-sm-3">
                              <label class="col-sm-12">Last Name: </label>
                              <div class="input-group col-sm-12">
                                <input class="form-control" type='text' name='lastname_<?php echo $i; ?>' id='lastname_<?php echo $i; ?>' value='<?php echo !empty(${'lastname_' . $i}) ? $html_model->escapeQuote(${'lastname_' . $i}) : ''; ?>'>
                              </div>
                            </div>
                            <div class="col-sm-3">
                              <label class="col-sm-12">Birth Date: </label>
                              <div class="input-group date" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd" data-date-end-date="0d">
                                <input size="16" type="text" class='check_premium form-control' name='birthday_<?php echo $i; ?>' id='birthday_<?php echo $i; ?>' value='<?php echo !empty(${'birthday_' . $i}) ? ${'birthday_' . $i} : ''; ?>'>
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                              </div>
                              <?php if (!empty(${'error_birthday_' . $i})) { ?>
                                <div class="alert-error"><?php echo ${'error_birthday_' . $i}; ?></div>
                              <?php } ?>
                            </div>
                            <div class="col-sm-3">
                              <div class="row">
                                <div class="col-sm-4">
                                  <label class="col-sm-12">Gender: </label>
                                  <div class="input-group col-sm-12">
                                    <select name='gender_<?php echo $i; ?>' id='gender_<?php echo $i; ?>' class="form-control" style="padding: 6px 2px;">
                                      <option value='M' <?php echo (empty(${'gender_' . $i}) || (${'gender_' . $i} != 'F')) ? "selected" : ""; ?>>Male</option>
                                      <option value='F' <?php echo (!empty(${'gender_' . $i}) && (${'gender_' . $i} == 'F')) ? "selected" : ""; ?>>Female</option>
                                    </select>
                                  </div>
                                </div>
                                <div class="col-sm-4">
                                  <label class="col-sm-12">&nbsp;</label>
                                  <?php if ((($status_id == 2) || ($status_id == 3) || ($status_id == 4)) && !empty(${'customer_id_' . $i}) && $user_group_id != 3 && $user_group_id != 103) { ?>
                                    <a class="btn btn-primary" href='<?php echo $claimurl . ${'customer_id_' . $i}; ?>'>Claim</a>
                                  <?php } ?>
                                </div>
                                <div class="col-sm-4">
                                  <label class="col-sm-12">&nbsp;</label>
                                  <?php if (($user_group_id > 100) && $no_change) { ?>
                                  <?php } else { ?>
                                    <input type='button' onclick='remove_member(<?php echo $i; ?>)' value='Remove' data-toggle="tooltip" title="Remove Memeber" class="btn btn-info">
                                  <?php }  ?>
                                </div>
                              </div>
                            </div>
                          </div>
                        <?php } ?>
                        <br />
                        <div class="row">
                          <div class="col-sm-1">
                          </div>
                          <div class="col-sm-11">
                            <?php if (($user_group_id > 100) && $no_change) { ?>
                            <?php } else { ?>
                              <?php if ($isprocessplan && ($status_id != 5) && ($status_id != 6)) { ?>
                                <input class="btn btn-info btn-sm" type='button' id='addmorememberid' name='addmorememberid' value='Add More Member' onclick='addmoremember(1);'>
                              <?php } ?>
                            <?php } ?>
                          </div>
                        </div>
                      </div>
                      <!-- Insurable Members  End-->
                    </fieldset>
                  </div>

                  <div class="col-sm-12 blcok-space">
                    <fieldset>
                      <legend>Address</legend>
                      <div class="row">
                        <div class="form-group col-sm-3">
                          <label class="col-sm-12">Street No: </label>
                          <div class="input-group col-sm-12">
                            <input class="form-control" type='text' name='street_number' value='<?php echo $html_model->escapeQuote($street_number); ?>'>
                          </div>
                          <?php if (!empty($error_street_number)) { ?>
                            <div class="alert-error"><?php echo $error_street_number; ?></div>
                          <?php } ?>
                        </div>
                        <div class="form-group col-sm-3">
                          <label class="col-sm-12">Street Name: </label>
                          <div class="input-group col-sm-12">
                            <input class="form-control" type='text' name='street_name' value='<?php echo $html_model->escapeQuote($street_name); ?>'>
                          </div>
                          <?php if (!empty($error_street_name)) { ?>
                            <div class="alert-error"><?php echo $error_street_name; ?></div>
                          <?php } ?>
                        </div>
                        <div class="form-group col-sm-3">
                          <label class="col-sm-12">Suite No.: </label>
                          <div class="input-group col-sm-12">
                            <input class="form-control" type='text' name='suite_number' value='<?php echo $html_model->escapeQuote($suite_number); ?>'>
                          </div>
                        </div>
                        <div class="form-group col-sm-3">
                          <label class="col-sm-12">City: </label>
                          <div class="input-group col-sm-12">
                            <input class="form-control" type='text' name='city' value='<?php echo $html_model->escapeQuote($city); ?>'>
                          </div>
                          <?php if (!empty($error_city)) { ?>
                            <div class="alert-error"><?php echo $error_city; ?></div>
                          <?php } ?>
                        </div>
                      </div>
                      <div class="row">
                        <div class="form-group col-sm-3">
                          <label class="col-sm-12">Province: </label>
                          <div class="input-group col-sm-12">
                            <div id='province2_div'></div>
                          </div>
                        </div>
                        <div class="form-group col-sm-3">
                          <label class="col-sm-12">Country: </label>
                          <div class="input-group col-sm-12">
                            <div id='country2_div'>Canada</div>
                          </div>
                        </div>
                        <div class="form-group col-sm-3">
                          <label class="col-sm-12">Postcode: </label>
                          <div class="input-group col-sm-12">
                            <input class="form-control" type='text' name='postcode' value='<?php echo $html_model->escapeQuote($postcode); ?>'>
                          </div>
                          <?php if (!empty($error_postcode)) { ?>
                            <div class="alert-error"><?php echo $error_postcode; ?></div>
                          <?php } ?>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-sm-3">
                          <label class="col-sm-12">Phone1: </label>
                          <div class="input-group col-sm-12">
                            <input class="form-control" type='text' name='phone1' value='<?php echo $html_model->escapeQuote($phone1); ?>'>
                          </div>
                          <?php if (!empty($error_phone1)) { ?>
                            <div class="alert-error"><?php echo $error_phone1; ?></div>
                          <?php } ?>
                        </div>
                        <div class="col-sm-3">
                          <label class="col-sm-12">Phone2: </label>
                          <div class="input-group col-sm-12">
                            <input class="form-control" type='text' name='phone2' value='<?php echo $html_model->escapeQuote($phone2); ?>'>
                          </div>
                        </div>
                      </div>
                      <!-- Address End-->
                    </fieldset>
                  </div>
                  <br />

                  <div class="col-sm-12 blcok-space">
                    <fieldset>
                      <legend>Contact</legend>
                      <div class="row">
                        <div class="col-sm-3">
                          <label class="col-sm-12">Email: </label>
                          <div class="input-group col-sm-12">
                            <input class="form-control" type='text' name='contact_email' value='<?php echo $html_model->escapeQuote($contact_email); ?>'>
                          </div>
                          <?php if (!empty($error_contact_email)) { ?>
                            <div class="alert-error"><?php echo $error_contact_email; ?></div>
                          <?php } ?>
                        </div>
                        <div class="col-sm-3">
                          <label class="col-sm-12">Contact Phone: </label>
                          <div class="input-group col-sm-12">
                            <input class="form-control" type='text' name='contact_phone' value='<?php echo $html_model->escapeQuote($contact_phone); ?>'>
                          </div>
                        </div>
                        <div class="col-sm-3">
                          <label class="col-sm-12">Country of Origin: </label>
                          <div class="input-group col-sm-12">
                            <input class="form-control" type='text' name='residence' value='<?php echo $html_model->escapeQuote($residence); ?>'>
                          </div>
                        </div>
                        <!-- Contact End-->
                    </fieldset>
                  </div>

                  <div class="col-sm-12 blcok-space" <?php if ($user_group_id > 100) { ?>style="display: none;" <?php } ?>>
                    <fieldset>
                      <legend>Special Note/Instructions</legend>
                      <div class="row">
                        <div class="col-sm-12">
                          <label class="col-sm-12">Notes: (Please provide previous coverage)</label>
                          <div class="input-group col-sm-12">
                            <textarea class="form-control" name="note"><?php echo $note; ?></textarea>
                          </div>
                        </div>
                      </div>
                    </fieldset>
                  </div>
                </div>
              </div>
              <!-- End data members -->
            </div>
            <div id="packages" class="tab-pane fade">
              <!-- Start packages -->
              <div class="x_panel">
                <div class="x_content">
                  <div class="form-group col-sm-6">
                    <h2><label><strong>Packages</strong></label></h2>
                  </div>
                  <div class="clearfix"></div>
                  <div class="form-group col-sm-12"><label><strong>All Inclusive plan and trip cancellation is non-refundable.</strong></label></div>
                  <div class="clearfix"></div>
                  <div class="col-sm-12 block-space">
                    <fieldset>
                      <legend>Select Package</legend>
                      <div class="row">
                        <?php if (($user_group_id > 100) && $no_change) { ?>
                          <input type="hidden" name="package" value="<?php echo $package; ?>">
                          <div class="col-sm-3">
                            <?php if ($package == 'all_inclusive') { echo '<span class="glyphicon glyphicon-ok"></span>'; } ?>
                            <a href="#" data-toggle="popover" data-trigger="hover" title="All Inclusive" data-content="Including: Emergency Hospital & Medical: $10,000,000; AD&D: $50,000; Flight Accident: $100,000; Trip Cancellation; Baggage: $1,000">
                              <?php echo $toppackagename['all_inclusive']; ?> <span class="glyphicon glyphicon-question-sign"></span>
                            </a>
                          </div>
                          <div class="col-sm-3">
                            <?php if ($package == 'single_medical_plan') { echo '<span class="glyphicon glyphicon-ok"></span>'; } ?>
                            <a href="#" data-toggle="popover" data-trigger="hover" title="Out of Province" data-content="Explaination for Out of Province">
                              <?php echo $toppackagename['single_medical_plan']; ?> <span class="glyphicon glyphicon-question-sign"></span>
                            </a>
                          </div>
                          <div class="col-sm-3">
                            <?php if ($package == 'optional_plan') { echo '<span class="glyphicon glyphicon-ok"></span>'; } ?>
                            <a href="#" data-toggle="popover" data-trigger="hover" title="Optional Plan" data-content="Explaination for Optional Plan">
                              <?php echo $toppackagename['optional_plan']; ?> <span class="glyphicon glyphicon-question-sign"></span>
                            </a>
                          </div>
                        <?php } else { ?>
                          <div class="col-sm-3">
                            <input <?php echo (($user_group_id > 100) && $no_change) ? 'disable="disable"' : ''; ?> type="radio" name="package" class='check_premium' value="all_inclusive" <?php if ($package == 'all_inclusive') { echo 'checked'; } ?>>
                            <a href="#" data-toggle="popover" data-trigger="hover" title="All Inclusive" data-content="Including: Emergency Hospital & Medical: $10,000,000; AD&D: $50,000; Flight Accident: $100,000; Trip Cancellation; Baggage: $1,000">
                              <?php echo $toppackagename['all_inclusive']; ?> <span class="glyphicon glyphicon-question-sign"></span>
                            </a>
                          </div>
                          <div class="col-sm-3">
                            <input <?php echo (($user_group_id > 100) && $no_change) ? 'disable="disable"' : ''; ?> type="radio" name="package" class='check_premium' value="single_medical_plan" <?php if ($package == 'single_medical_plan') { echo 'checked'; } ?>>
                            <a href="#" data-toggle="popover" data-trigger="hover" title="Out of Province" data-content="Explaination for Out of Province">
                              <?php echo $toppackagename['single_medical_plan']; ?> <span class="glyphicon glyphicon-question-sign"></span>
                            </a>
                          </div>
                          <div class="col-sm-3">
                            <input <?php echo (($user_group_id > 100) && $no_change) ? 'disable="disable"' : ''; ?> type="radio" name="package" class='check_premium' value="optional_plan" <?php if ($package == 'optional_plan') { echo 'checked'; } ?>>
                            <a href="#" data-toggle="popover" data-trigger="hover" title="Optional Plan" data-content="Explaination for Optional Plan">
                              <?php echo $toppackagename['optional_plan']; ?> <span class="glyphicon glyphicon-question-sign"></span>
                            </a>
                          </div>
                        <?php } // no_change 
                        ?>
                      </div>
                    </fieldset>
                  </div>
                  <div class="col-sm-12 block-space " id='all_inclusive_div' <?php if ($package != 'all_inclusive') { ?>style="display:none;" <?php } ?>>
                    <fieldset>
                      <legend>Conditions</legend>
                      <div class="row">
                        <div class="col-sm-12">
                          <label class="col-sm-12">Trip cancellation before departure, refer to section V of policy wording for full description</label>
                        </div>
                        <div class="col-sm-4">
                          <?php if (($user_group_id > 100) && $no_change) { ?>
                            <input type="hidden" name="sum_insured" value="<?php echo $sum_insured; ?>">$<?php echo number_format($sum_insured, 2); ?>
                          <?php } else { ?>
                            <input type="number" name="sum_insured" class='check_premium' min="0" max="8000" step="100" value="<?php echo $sum_insured; ?>"> ( 0 - 8,000 every 100s)
                          <?php } ?>
                        </div>
                        <!-- <div class="col-sm-4">
                        <?php if (($user_group_id > 100) && $no_change) { ?>
                        <input type="hidden" name="free_cancel" value="<?php echo $free_cancel ? 1 : 0; ?>">
                        <?php if ($free_cancel) { echo '<span class="glyphicon glyphicon-ok"></span>'; } ?> Free Cancellation
                        <?php } else { ?>
                        <input type="checkbox" name="free_cancel" class='check_premium' value="1" <?php echo ($free_cancel ? 'checked' : ''); ?>> Free Cancellation
                        <?php } ?>
                        </div> -->
                      </div>
                    </fieldset>
                  </div>
                  <div class="col-sm-12 block-space " id='annual_plan_div' <?php if ($package != 'annual_plan') { ?>style="display:none;" <?php } ?>>
                    <fieldset>
                      <legend>Days</legend>
                      <div class="row">
                        <div class="col-sm-2 text-right">
                          <label class="col-sm-12">Select Days : </label>
                        </div>
                        <div class="col-sm-10">
                          <label class="col-sm-12">Select Days</label>
                          <?php if (($user_group_id > 100) && $no_change) { ?>
                            <input type="hidden" name="annual_plan_days" value="<?php echo $annual_plan_days; ?>"><?php echo $annual_plan_days; ?> Days
                          <?php } else { ?>
                            <select name='annual_plan_days' class="check_premium" style="padding: 6px 2px;">
                              <option value='4' <?php echo ($annual_plan_days == 4) ? "selected" : ""; ?>>4 Days</option>
                              <option value='9' <?php echo ($annual_plan_days == 9) ? "selected" : ""; ?>>9 Days</option>
                              <option value='17' <?php echo ($annual_plan_days == 17) ? "selected" : ""; ?>>17 Days</option>
                              <option value='30' <?php echo ($annual_plan_days == 30) ? "selected" : ""; ?>>30 Days</option>
                              <option value='60' <?php echo ($annual_plan_days == 60) ? "selected" : ""; ?>>60 Days</option>
                            </select>
                          <?php } ?>
                        </div>
                      </div>
                    </fieldset>
                  </div>
                  <div class="col-sm-12 block-space " id='optional_plan_div' <?php if (($package != 'single_medical_plan') && ($package != 'optional_plan')) { ?>style="display:none;" <?php } ?>>
                    <fieldset>
                      <legend>Optional Plans</legend>
                      <div class="row panel-group">
                        <div class="panel panel-default">
                          <div class="panel-heading">
                            <?php if (($user_group_id > 100) && $no_change) { ?>
                              <input type="hidden" name="ad_and_d_ck" value="<?php echo $ad_and_d_ck ? 1 : 0; ?>">
                              <?php if ($ad_and_d_ck) { echo '<span class="glyphicon glyphicon-ok"></span>'; } ?> AD & D
                            <?php } else { ?>
                              <input type="checkbox" name="ad_and_d_ck" class='check_premium' value="1" <?php echo $ad_and_d_ck ? 'checked' : ''; ?>> AD & D
                            <?php } ?>
                          </div>
                          <div class="panel-body">
                            AD & D description
                            <div class='row'>
                              <div class="col-sm-2 text-right"><label>Insured Amount : </label></div>
                              <div class="col-sm-10">
                                <?php if (($user_group_id > 100) && $no_change) { ?>
                                  <input type="hidden" name="ad_and_d_insured" value="<?php echo $ad_and_d_insured; ?>">$<?php echo number_format($ad_and_d_insured); ?>
                                <?php } else { ?>
                                  <select name='ad_and_d_insured' class="check_premium" style="padding: 6px 2px;">
                                    <option value='25000' <?php echo ($ad_and_d_insured == 25000) ? "selected" : ""; ?>>25,000</option>
                                    <option value='50000' <?php echo ($ad_and_d_insured == 50000) ? "selected" : ""; ?>>50,000</option>
                                    <option value='75000' <?php echo ($ad_and_d_insured == 75000) ? "selected" : ""; ?>>75,000</option>
                                    <option value='100000' <?php echo ($ad_and_d_insured == 100000) ? "selected" : ""; ?>>100,000</option>
                                  </select>
                                <?php } ?>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="panel panel-default">
                          <div class="panel-heading">
                            <?php if (($user_group_id > 100) && $no_change) { ?>
                              <input type="hidden" name="flight_accident_ck" value="<?php echo $flight_accident_ck ? 1 : 0; ?>">
                              <?php if ($flight_accident_ck) {
                                echo '<span class="glyphicon glyphicon-ok"></span>';
                              } ?> Flight Accident
                            <?php } else { ?>
                              <input type="checkbox" name="flight_accident_ck" class='check_premium' value="1" <?php echo $flight_accident_ck ? 'checked' : ''; ?>> Flight Accident
                            <?php } ?>
                          </div>
                          <div class="panel-body">
                            Flight Accident description
                            <div class='row'>
                              <div class="col-sm-2 text-right"><label>Insured Amount : </label></div>
                              <div class="col-sm-10">
                                <?php if (($user_group_id > 100) && $no_change) { ?>
                                  <input type="hidden" name="flight_accident_insured" value="<?php echo $flight_accident_insured; ?>">$<?php echo number_format($flight_accident_insured); ?>
                                <?php } else { ?>
                                  <select name='flight_accident_insured' class="check_premium" style="padding: 6px 2px;">
                                    <option value='100000' <?php echo ($flight_accident_insured == 100000) ? "selected" : ""; ?>>100,000</option>
                                    <option value='200000' <?php echo ($flight_accident_insured == 200000) ? "selected" : ""; ?>>200,000</option>
                                    <option value='300000' <?php echo ($flight_accident_insured == 300000) ? "selected" : ""; ?>>300,000</option>
                                  </select>
                                <?php } ?>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="panel panel-default">
                          <div class="panel-heading">
                            <input type="hidden" name="trip_cancellation_ck" value="<?php echo $trip_cancellation_ck; ?>">
                            <?php if (($user_group_id > 100) && $no_change) { ?>
                              <?php if ($trip_cancellation_ck) { echo '<span class="glyphicon glyphicon-ok"></span>'; } ?>
                            <?php } else { ?>
                              <input type="checkbox" name="trip_cancellation_ckbox" class='check_premium' value="1" <?php echo $trip_cancellation_ck ? 'checked' : ''; ?>>
                            <?php } ?>
                            Trip cancellation and interruption
                          </div>
                          <div class="panel-body">
                            <div class='row'>
                              <div class="col-sm-12">
                                The trip cancellation will be effective on the date of purchase.
                              </div>
                            </div>
                            <div class='row'>
                              <div class="col-sm-12">
                                Trip cancellation before departure, refer to section V of policy wording for full description
                              </div>
                            </div>
                            <div class='row'>
                              <div class="col-sm-2 text-right"><label>Insured Amount : </label></div>
                              <div class="col-sm-10">
                                <?php if (($user_group_id > 100) && $no_change) { ?>
                                  <input type="hidden" name="trip_cancellation_insured" value="<?php echo $trip_cancellation_insured; ?>">$<?php echo number_format($trip_cancellation_insured); ?>
                                <?php } else { ?>
                                  <input type="number" name="trip_cancellation_insured" class='check_premium' min="100" max="8000" step="100" value="<?php echo ($trip_cancellation_insured < 100) ? 100 : $trip_cancellation_insured; ?>"> ( every 100s )
                                <?php } ?>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </fieldset>
                  </div>
                  <div class="col-sm-12 block-space" id='stable_conditions_div' <?php if (empty($stable_condition)) { ?>style="display:none;" <?php } ?>>
                    <fieldset>
                      <legend>Pre-existing conditons</legend>
                      <div class="row">
                        <label class="inline">Select pre-existing condition coverage</label>
                        <div class="inline">
                          <?php if (($user_group_id > 100) && $no_change) { ?>
                            <?php echo ($stable_condition == 1) ? 'Including' : 'Excluding'; ?> stable pre-existing condition coverage
                          <?php } else { ?>
                            <select name='stable_condition' class="form-control check_premium" id='stable_condition_select'>
                              <option value='0'> -- select condition -- </option>
                              <option value='1' <?php echo ($stable_condition == 1) ? 'selected' : ''; ?>>Including stable pre-existing condition coverage</option>
                              <option value='2' <?php echo ($stable_condition == 2) ? 'selected' : ''; ?>>Excluding stable pre-existing condition coverage</option>
                            </select>
                          <?php } ?>
                        </div>
                        <?php if (!empty($error_stable_condition)) { ?>
                          <div class="alert-error">
                            <?php echo $error_stable_condition; ?>
                          </div>
                        <?php } ?>
                      </div>
                    </fieldset>
                  </div>
                </div>
              </div>
              <!-- End packages -->
            </div>
            <div id="questionnaire" class="tab-pane fade">
              <!-- Start questionnaire -->
              <div class="x_panel">
                <div class="x_content">
                  <div class="form-group col-sm-6">
                    <h2><label><span>Medical Questionnaire</span></label></h2>
                  </div>
                  <div class="clearfix"></div>
                  <div class="col-sm-12 block-space">
                    <fieldset>
                      <legend>To be eligible for coverage under this plan, the applicant must on the Effective Date of Coverage:</legend>
                      <div class="row">
                        <div class="col-sm-12">
                          <ol>
                            <li type="a">be at least 15 days old and not more than 84 years old travelling for no more than 90 days per trip; and</li>
                            <li type="a">be a Canadian resident covered under a government health insurance plan for the entire duration of your trip; and</li>
                            <li type="a">purchase coverage for the entire duration of your trip prior to the date of departure from your province, or territory of residence, or Canada; and</li>
                            <li type="a">be in good health at the time you purchase your policy and on the date of departure from your province, or territory of residence, or Canada and know of no reason to seek medical consultation during the coverage period.</li>
                          </ol>
                          <div class="form-check form-check-inline">
                            Do you confirm that you are eligible to apply
                            <input type='radio' name='medical_eligible1' value='Yes' class="form-check-input">
                            <label class="form-check-label">Yes</label>
                            <input type='radio' name='medical_eligible1' value='No' class="form-check-input">
                            <label class="form-check-label">No</label>
                          </div>
                        </div>
                      </div>
                    </fieldset>
                  </div>
                  <div class="col-sm-12 block-space" id="medical_eligible2_div" style="display: none;">
                    <fieldset>
                      <legend>On the Effective Date of Coverage:</legend>
                      <div class="row">
                        <div class="col-sm-12">
                          <ol>
                            <li>Has your physician advised you not to travel?</li>
                            <li>Have you been diagnosed with or suffer from a terminal illness?</li>
                            <li>Have you been diagnosed with congestive heart failure at any point in the last 15 years?</li>
                            <li>Have you had your most recent heart surgery (bypass; angioplasty; stent placement; valve; pacemaker implant) less than 6 months ago or more than 12 years ago?</li>
                            <li>Have you been diagnosed with an unrepaired aneurysm of 4.5 centimetres or more?</li>
                            <li>Have you suffered from kidney disease treated through dialysis?</li>
                            <li>Have you been diagnosed with or treated for stage III or IV cancer or cancer that has metastasized?</li>
                            <li>In the past 12 months, have you been prescribed or have you used (by personal choice or as recommended by a health care professional) home supplemental oxygen?</li>
                            <li>In the past 90 days, have you been experiencing new or undiagnosed symptoms and/or know of any reason to seek medical attention, or sought medical attention?</li>
                            <li>Do you require assistance with any of the Activities of Daily Living?</li>
                          </ol>
                          <div class="form-check form-check-inline">
                            Do you confirm that you are eligible to apply
                            <input type='radio' name='medical_eligible2' value='Yes' class="form-check-input">
                            <label class="form-check-label">Yes</label>
                            <input type='radio' name='medical_eligible2' value='No' class="form-check-input">
                            <label class="form-check-label">No</label>
                          </div>
                        </div>
                      </div>
                    </fieldset>
                  </div>
                  <div class="col-sm-12 block-space" id="question_step1" <?php if (empty($question1)) { ?>style="display:none;" <?php } ?>>
                    <fieldset>
                      <legend>The following Medical Questionnaire must be completed if:</legend>
                      <div class="row">
                        <div class="col-sm-12">
                          <ol>
                            <li style="list-style-type: none;">An applicant is 60 years of age to 74 years of age travelling for more than 60 days; or</li>
                            <li style="list-style-type: none;">An applicant is 75 years of age to 84 years of age travelling for any trip length.</li>
                          </ol>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-sm-12">
                          <H4>Medical conditions:</H4>
                        </div>
                        <div class="col-sm-12">
                          <ul>
                            <li>Lung conditions/disease (include asthma)</li>
                            <li>Diabetes</li>
                            <li>Heart conditions/hypertensive disease (do not include aspirin or high cholesterol medications)</li>
                          </ul>
                        </div>
                        <?php if (($user_group_id > 100) && $no_change) { ?>
                          <input type="hidden" name="question1" value="<?php echo $question1; ?>">
                        <?php } else { ?>
                          <div class="col-sm-12">
                            <input type='radio' name='question1' value='3' class="form-check-input">
                            <label class="form-check-label">3 or more medications</label>
                            <input type='radio' name='question1' value='2' class="form-check-input">
                            <label class="form-check-label">2 medications</label>
                            <input type='radio' name='question1' value='1' class="form-check-input">
                            <label class="form-check-label">1 medication or None</label>
                          </div>
                        <?php } ?>
                      </div>
                    </fieldset>
                  </div>
                  <div class="col-sm-12 block-space" id="questionnaire_q2" <?php if (empty($question2)) { ?>style="display:none;" <?php } ?>>
                    <fieldset>
                      <div class="row">
                        <div class="col-sm-12">
                          <H4>Within the 24 months prior to the date of application, have you had a heart attack, stroke and/or transient ischemic attack (mini-stroke, TIA)?</H4>
                        </div>
                        <?php if (($user_group_id > 100) && $no_change) { ?>
                          <input type="hidden" name="question2" value="<?php echo $question2; ?>">
                          <div class="col-sm-3">
                            <?php if ($question2 == 2) { echo '<span class="glyphicon glyphicon-ok"> Yes</span>'; } ?>
                            <?php if ($question2 == 1) { echo '<span class="glyphicon glyphicon-ok"> No</span>'; } ?>
                          </div>
                        <?php } else { ?>
                          <div class="col-sm-3">
                            <input type="radio" name="question2" value="2" <?php if ($question2 == 2) { echo "checked"; } ?>><label class="form-check-label"> Yes</label>
                          </div>
                          <div class="col-sm-3">
                            <input type="radio" name="question2" value="1" <?php if ($question2 == 1) { echo "checked"; } ?>><label class="form-check-label"> No</label>
                          </div>
                        <?php } ?>
                      </div>
                    </fieldset>
                  </div>
                  <div class="col-sm-12 block-space" id="questionnaire_q3" <?php if (empty($question3)) { ?>style="display:none;" <?php } ?>>
                    <fieldset>
                      <div class="row">
                        <div class="col-sm-12">
                          <H4>Within 6 months of the date of application, how many of the following medical conditions did you take medication for or received treatment for?</H4>
                          <H4>Treatment includes medication* that you take or have been ordered to take by a physician.</H4>
                        </div>
                        <?php if (($user_group_id > 100) && $no_change) { ?>
                          <div class="col-sm-12">
                            <input type="hidden" name="question3" value="<?php echo $question3; ?>">
                            <ul>
                              <li>Bowel conditions/disease including bleeding and inflammation <?php echo $question3_bowel; ?></li>
                              <li>Cancer <?php echo $question3_cancer; ?></li>
                              <li>Diabetes (controlled by medication or diet) <?php echo $question3_diabetes; ?></li>
                              <li>Diverticulitis/Diverticulosis <?php echo $question3_diverticu; ?></li>
                              <li>GERD (gastro-esophageal reflux disease) <?php echo $question3_gerd; ?></li>
                              <li>Heart conditions/disease (include aspirin) <?php echo $question3_heart; ?></li>
                              <li>Hypertension <?php echo $question3_hyper; ?></li>
                              <li>Kidney disease (not requiring dialysis) <?php echo $question3_kidney; ?></li>
                              <li>Lung conditions/disease (include asthma) <?php echo $question3_lung; ?></li>
                              <li>Peptic ulcer <?php echo $question3_peptic; ?></li>
                            </ul>
                          </div>
                        <?php } else { ?>
                          <div class="col-sm-12">
                            <ul>
                              <li>Bowel conditions/disease including bleeding and inflammation <input type="radio" name="question3_bowel" value="Y" <?php if ($question3_bowel == 'Y') { echo "checked"; } ?>> Yes / <input type="radio" name="question3_bowel" value="N" <?php if ($question3_bowel == 'N') { echo "checked"; } ?>> No </li>
                              <li>Cancer <input type="radio" name="question3_cancer" value="Y" <?php if ($question3_cancer == 'Y') { echo "checked"; } ?>> Yes / <input type="radio" name="question3_cancer" value="N" <?php if ($question3_cancer == 'N') { echo "checked"; } ?>> No </li>
                              <li>Diabetes (controlled by medication or diet) <input type="radio" name="question3_diabetes" value="Y" <?php if ($question3_diabetes == 'Y') { echo "checked"; } ?>> Yes / <input type="radio" name="question3_diabetes" value="N" <?php if ($question3_diabetes == 'N') { echo "checked"; } ?>> No </li>
                              <li>Diverticulitis/Diverticulosis <input type="radio" name="question3_diverticu" value="Y" <?php if ($question3_diverticu == 'Y') { echo "checked"; } ?>> Yes / <input type="radio" name="question3_diverticu" value="N" <?php if ($question3_diverticu == 'N') { echo "checked"; } ?>> No </li>
                              <li>GERD (gastro-esophageal reflux disease) <input type="radio" name="question3_gerd" value="Y" <?php if ($question3_gerd == 'Y') { echo "checked"; } ?>> Yes / <input type="radio" name="question3_gerd" value="N" <?php if ($question3_gerd == 'N') { echo "checked"; } ?>> No </li>
                              <li>Heart conditions/disease (include aspirin) <input type="radio" name="question3_heart" value="Y" <?php if ($question3_heart == 'Y') { echo "checked"; } ?>> Yes / <input type="radio" name="question3_heart" value="N" <?php if ($question3_heart == 'N') { echo "checked"; } ?>> No </li>
                              <li>Hypertension <input type="radio" name="question3_hyper" value="Y" <?php if ($question3_hyper == 'Y') { echo "checked"; } ?>> Yes / <input type="radio" name="question3_hyper" value="N" <?php if ($question3_hyper == 'N') { echo "checked"; } ?>> No </li>
                              <li>Kidney disease (not requiring dialysis) <input type="radio" name="question3_kidney" value="Y" <?php if ($question3_kidney == 'Y') { echo "checked"; } ?>> Yes / <input type="radio" name="question3_kidney" value="N" <?php if ($question3_kidney == 'N') { echo "checked"; } ?>> No </li>
                              <li>Lung conditions/disease (include asthma) <input type="radio" name="question3_lung" value="Y" <?php if ($question3_lung == 'Y') { echo "checked"; } ?>> Yes / <input type="radio" name="question3_lung" value="N" <?php if ($question3_lung == 'N') { echo "checked"; } ?>> No </li>
                              <li>Peptic ulcer <input type="radio" name="question3_peptic" value="Y" <?php if ($question3_peptic == 'Y') { echo "checked"; } ?>> Yes / <input type="radio" name="question3_peptic" value="N" <?php if ($question3_peptic == 'N') { echo "checked"; } ?>> No </li>
                            </ul>
                          </div>
                          <div class="col-sm-12 text-center">
                            <buttom type='button' id='question3_next' class='btn btn-info'>Next</buttom>
                          </div>
                        <?php } ?>
                      </div>
                    </fieldset>
                  </div>
                  <div class="col-sm-12 block-space" id="questionnaire_q4" <?php if (empty($question4)) { ?>style="display:none;" <?php } ?>>
                    <fieldset>
                      <div class="row">
                        <div class="col-sm-12">
                          <H4>At the time of application, do you have any medical conditions that were not listed in the previous questions for which you are currently receiving treatment?</H4>
                          <H4>Treatment includes medication* that you take or have been ordered to take by a physician, not including a minor ailment.</H4>
                          <H4>Minor ailment means a condition which does not require:</H4>
                        </div>
                        <div class="col-sm-12">
                          <ol>
                            <li type='a'>Treatment for a period of greater than 30 consecutive days; or,</li>
                            <li type='a'>More than one follow-up visit or referral visit to a physician or other registered medical practitioner; or,</li>
                            <li type='a'>Hospitalization or surgical intervention.</li>
                          </ol>
                        </div>
                        <?php if (($user_group_id > 100) && $no_change) { ?>
                          <div class="col-sm-3">
                            <input type="hidden" name="question4" value="<?php echo $question4; ?>">
                            <?php if ($question4 == 2) { echo '<span class="glyphicon glyphicon-ok"> Yes</span>'; } ?>
                            <?php if ($question4 == 1) { echo '<span class="glyphicon glyphicon-ok"> No</span>'; } ?>
                          </div>
                        <?php } else { ?>
                          <div class="col-sm-3">
                            <input type="radio" name="question4" value="2" <?php if ($question4 == 2) { echo "checked"; } ?>> Yes
                          </div>
                          <div class="col-sm-3">
                            <input type="radio" name="question4" value="1" <?php if ($question4 == 1) { echo "checked"; } ?>> No
                          </div>
                        <?php } ?>
                      </div>
                    </fieldset>
                  </div>
                  <div class="col-sm-12 block-space" id="questionnaire_q5" <?php if (empty($question5)) { ?>style="display:none;" <?php } ?>>
                    <fieldset>
                      <div class="row">
                        <div class="col-sm-12">
                          <H4>Have you used any tobacco products in the past 24 months?</H4>
                        </div>
                        <?php if (($user_group_id > 100) && $no_change) { ?>
                          <div class="col-sm-3">
                            <input type="hidden" name="question4" value="<?php echo $question5; ?>">
                            <?php if ($question5 == 2) { echo '<span class="glyphicon glyphicon-ok"> Yes</span>'; } ?>
                            <?php if ($question5 == 1) { echo '<span class="glyphicon glyphicon-ok"> No</span>'; } ?>
                          </div>
                        <?php } else { ?>
                          <div class="col-sm-3">
                            <input type="radio" name="question5" value="2" <?php if ($question5 == 2) { echo "checked"; } ?>> Yes
                          </div>
                          <div class="col-sm-3">
                            <input type="radio" name="question5" value="1" <?php if ($question5 == 1) { echo "checked"; } ?>> No
                          </div>
                        <?php } ?>
                      </div>
                    </fieldset>
                  </div>
                </div>
              </div>
              <!-- End questionnaire -->
            </div>
          </div>
          <div class="row  blcok-space">
            <div class="col-sm-4 text-center">
              <buttom type='button' id='page-prev' class='btn btn-info' style="display:none;">Prev</buttom>
            </div>
            <div class="col-sm-4 text-center">
              <input type='submit' id='page-submit' class='btn btn-info' name='submit' value='Submit' style="display:none;" />
            </div>
            <div class="col-sm-4 text-center">
              <buttom type='button' id='page-next' class='btn btn-info'>Next</buttom>
            </div>
            <div class="col-sm-12 alert-error float-error" title="Click to Close the notice" style="display:none;" id='error_page_message'></div>
          </div>
        </form>
      </div>
    </div>
    <?php if ($show_history) { ?>
      <div class="row block-space">
        <div class="col-sm-12">
          <div class="x_panel">
            <div class="x_title">
              <h2>
                Policy History<small></small>
              </h2>
              <div class="clearfix"></div>
            </div>
            <div class="x_content">
              <div class="row" id='payment_history'>
                <?php if (!empty($payments) && is_array($payments) && (sizeof($payments) > 0)) { ?>
                  <div class="col-sm-12">
                    <button type="button" class="btn btn-info" data-toggle="collapse" data-target="#history1">Payments <span class="fa fa-chevron-down"></span></button>
                    <div id="history1" class="collapse block-space">
                      <button type="button" class="btn btn-payment-sort" data-type="date">Sort By Date</button>
                      <button type="button" class="btn btn-payment-sort" data-type="type">Sort By Type</button>
                      <form action='<?php echo $makepay_url; ?>' method='POST' class="form-horizontal">
                        <input type='hidden' name='<?php echo $csrf['name']; ?>' value='<?php echo $csrf['value']; ?>'>
                        <div class="table-responsive">
                          <table class="table table-hover table-bordered">
                            <thead>
                              <tr>
                                <th>&nbsp;</th>
                                <th>Last Update</th>
                                <th>Type</th>
                                <th>Pay Type</th>
                                <th>Amount</th>
                                <th>Rate</th>
                                <th>Pay Status</th>
                                <th>CK Info</th>
                                <th>Info</th>
                                <th>Notes</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php
                              foreach ($payments as $p) {
                                $pay_str = '';
                                if ($p['pay_type'] == 'up_commission') continue;
                                if ($p['pay_type'] == 'refund_up_commission') continue;
                                if ($p['pay_type'] == 'cancel_up_commission') continue;
                                $sbstr = substr($p['pay_type'], 0, 6);
                                if ($p['ispaid']) {
                                  $pay_str = 'Paid';
                                } else {
                                  if ($sbstr == 'refund') {
                                    $pay_str = "<a href='" . $revert_url . $p['payment_id'] . "'>Revert Refund</a>";
                                  } else if ($sbstr == 'cancel') {
                                    $pay_str = "<a href='" . $revert_url . $p['payment_id'] . "'>Revert Cancel</a>";
                                  } else {
                                    $pay_str = '-';
                                  }
                                }
                                $pay_info = '';
                                $ck_info = $p['cheque_number'];
                                if ($p['pay_date'] > "2020-01-01") $ck_info .= ":" . $p['pay_date'];
                                if (!empty($p['invoice_num'])) $pay_info .= "[" . $p['invoice_num'] . "]";
                                if (!empty($p['bank_name'])) $pay_info .= "[" . $p['bank_name'] . "]";
                                if (!empty($p['payor_name'])) $pay_info .= "[" . $p['payor_name'] . "]";
                                // if (! empty ( $p ['cheque_number'] )) $pay_info .= "[" . $p ['cheque_number'] . "]";
                                if (!empty($p['pay_to'])) $pay_info .= "[" . $p['pay_to'] . "]";
                                if (!empty($p['name'])) $pay_info .= "[" . $p['name'] . "]";
                                if (!empty($p['first5'])) $pay_info .= "[" . $p['first5'] . "]";
                                if (!empty($p['last4'])) $pay_info .= "[" . $p['last4'] . "]";
                                if (!empty($p['expiry_month'])) $pay_info .= "[" . $p['expiry_month'] . "]";
                                if (!empty($p['expiry_year'])) $pay_info .= "[" . $p['expiry_year'] . "]";
                              ?>
                                <tr>
                                  <td><?php if (empty($p['ispaid'])) { ?><input type='checkbox' name='payment[]' value='<?php echo $p['payment_id']; ?>'><?php } ?></td>
                                  <td><?php echo $p['last_update']; ?></td>
                                  <td><?php echo $p['pay_type']; ?></td>
                                  <td><?php echo $p['pay_mothed']; ?></td>
                                  <td><?php echo $p['amount']; ?></td>
                                  <td><?php echo $p['rate'] . "%"; ?></td>
                                  <td><?php echo $pay_str; ?></td>
                                  <td><?php echo $ck_info; ?></td>
                                  <td><?php echo $pay_info; ?></td>
                                  <td><?php echo (strlen($p['note']) > 60) ? (substr($p['note'], 0, 57) . "...") : $p['note']; ?></td>
                                </tr>
                              <?php } ?>
                            </tbody>
                          </table>
                        </div>
                        <div class="row">
                          <div class="col-sm-12"><input type="submit" class="btn btn-primary" name='submit' value='Make Pay'></div>
                        </div>
                      </form>
                      <hr />
                    </div>
                  </div>
                <?php   } ?>
              </div>
              <div class="row">
                <?php if (!empty($activelogs) && is_array($activelogs) && (sizeof($activelogs) > 0)) { ?>
                  <div class="col-sm-12 block-space">
                    <button type="button" class="btn btn-info" data-toggle="collapse" data-target="#history2"> Changes <span class="fa fa-chevron-down"></span></button>
                    <div id="history2" class="collapse block-space">
                      <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                          <thead>
                            <tr>
                              <th>Username</th>
                              <th>Date Time</th>
                              <th>Message</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php foreach ($activelogs as $p) { ?>
                              <tr>
                                <td><?php echo $p['username']; ?></td>
                                <td><?php echo $p['tm']; ?></td>
                                <td><?php echo (strlen($p['message']) > 120) ? (substr($p['message'], 0, 117) . "...") : $p['message']; ?></td>
                              </tr>
                            <?php } ?>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                <?php } ?>
              </div>
            </div>
            <!--/x_content end-->
          </div>
          <!--x_panel-->
        </div>
      </div>
    <?php } /* history */ ?>
  </div>
</div>
<!-- /page content -->
<script>
  var need_questionnaire = <?php printf("%d", $questionnaire); ?>;
  var cur_max_member = <?php printf("%d", $cur_max_member); ?>;
  var show_ajax_message = 0;
  var answer;
  $(document).ready(function() {
    <?php if (($user_group_id > 100) && $no_change) { ?>
    <?php } else { ?>
      $('input[name="package"]').on('change', function(e) {
        var q = $('input[name=package]:checked').val();
        $('#all_inclusive_div').hide();
        $('#annual_plan_div').hide();
        $('#optional_plan_div').hide();
        if (q == 'all_inclusive') {
          $('#all_inclusive_div').show();
        } else if (q == 'annual_plan') {
          $('#annual_plan_div').show();
        } else { // single_medical_plan, optional_plan
          $('#optional_plan_div').show();
        }
        show_ajax_message = 1;
      });

      $('input[name="trip_cancellation_ckbox"]').on('change', function(e) {
        if ($('input[name="trip_cancellation_ckbox"]').is(':checked')) {
          $('input[name="trip_cancellation_ck"]').val(1);
        } else {
          $('input[name="trip_cancellation_ck"]').val(0);
        }
      });

      $('input[name="question1"]').on('change', function(e) {
        var q = $('input[name="question1"]:checked').val();
        $('input[name=question2]').prop('checked', false);
        $('input[name=question3]').val(0);
        $('input[name=question4]').prop('checked', false);
        $('input[name=question5]').prop('checked', false);
        $('#questionnaire_q3').css('display', 'none');
        $('#questionnaire_q4').css('display', 'none');
        $('#questionnaire_q5').css('display', 'none');
        if (q >= 3) {
          $('#questionnaire_q2').css('display', 'none');
          $('input[name=questionnaire]').val(6);
          get_premium();
        } else if (q >= 2) {
          $('#questionnaire_q2').css('display', 'none');
          $('input[name=questionnaire]').val(5);
          get_premium();
        } else {
          $('#questionnaire_q2').css('display', 'block');
          $('#page-submit').hide();
        }
      });

      $('input[name=question2]').on('change', function(e) {
        var q = $('input[name=question2]:checked').val();
        $('input[name=question3]').val(0);
        $('input[name=question4]').prop('checked', false);
        $('input[name=question5]').prop('checked', false);
        $('#questionnaire_q4').css('display', 'none');
        $('#questionnaire_q5').css('display', 'none');
        if (q == 2) {
          $('#questionnaire_q3').css('display', 'none');
          $('input[name=questionnaire]').val(5);
          get_premium();
        } else if (q == 1) {
          $('#questionnaire_q3').css('display', 'block');
          $('#page-submit').hide();
        }
      });

      $('#question3_next').on('click', function(e) {
        var q = 0;
        if ($('input[name=question3_bowel]:checked').val() == 'Y') q++;
        if ($('input[name=question3_cancer]:checked').val() == 'Y') q++;
        if ($('input[name=question3_diabetes]:checked').val() == 'Y') q++;
        if ($('input[name=question3_diverticu]:checked').val() == 'Y') q++;
        if ($('input[name=question3_gerd]:checked').val() == 'Y') q++;
        if ($('input[name=question3_heart]:checked').val() == 'Y') q++;
        if ($('input[name=question3_hyper]:checked').val() == 'Y') q++;
        if ($('input[name=question3_kidney]:checked').val() == 'Y') q++;
        if ($('input[name=question3_lung]:checked').val() == 'Y') q++;
        if ($('input[name=question3_peptic]:checked').val() == 'Y') q++;

        $('input[name=question3]').val(q);
        $('input[name=question4]').prop('checked', false);
        $('input[name=question5]').prop('checked', false);
        $('#questionnaire_q5').css('display', 'none');
        if (q >= 2) {
          $('#questionnaire_q4').css('display', 'none');
          $('input[name=questionnaire]').val(4);
          get_premium();
        } else if (q >= 1) {
          $('#questionnaire_q4').css('display', 'none');
          $('input[name=questionnaire]').val(3);
          get_premium();
        } else {
          $('#questionnaire_q4').css('display', 'block');
          $('#page-submit').hide();
        }
      });

      $('input[name=question4]').on('change', function(e) {
        var q = $('input[name=question4]:checked').val();
        $('input[name=question5]').prop('checked', false);
        if (q == 2) {
          $('#questionnaire_q5').css('display', 'none');
          $('input[name=questionnaire]').val(2);
          get_premium();
        } else if (q == 1) {
          $('#questionnaire_q5').css('display', 'block');
          $('#page-submit').hide();
        }
      });

      $('input[name=question5]').on('change', function(e) {
        var q = $('input[name=question5]:checked').val();
        if (q == 2) {
          $('input[name=questionnaire]').val(2);
        } else if (q == 1) {
          $('input[name=questionnaire]').val(1);
        }
        get_premium();
      });
    <?php } ?>

    $('a[data-toggle="tab"]').on('click', function(e) {
      var id = $(this).attr('id');

      if (id == 'date_members_tab') {
        $('#page-prev').css('display', 'none');
        $('#page-next').css('display', '');
      } else if (id == 'packages_tab') {
        $('#page-prev').css('display', '');
        if (need_questionnaire) {
          $('#page-next').css('display', '');
        } else {
          $('#page-next').css('display', 'none');
        }
      } else if (id == 'questionnaire_tab') {
        $('#page-prev').css('display', '');
        $('#page-next').css('display', 'none');

        var q = $('input[name=questionnaire]').val();
        if (q == 0) {
          $('input[name=questionnaire]').val(0);
          $('input[name=question2]').prop('checked', false);
          $('input[name=question3]').val(0);
          $('input[name=question4]').prop('checked', false);
          $('input[name=question5]').prop('checked', false);

          $('#questionnaire_q2').css('display', 'none');
          $('#questionnaire_q3').css('display', 'none');
          $('#questionnaire_q4').css('display', 'none');
          $('#questionnaire_q5').css('display', 'none');

          $('#page-submit').hide();
          $('input[name="premium"]').val(0);
          $('#premium_value').html('');
          $('#premium_rate_table').html('');
        }
        $('#error_page_message').hide();
        $('#error_page_message').html('');
      }
    })

    $('#error_message_ajax').click(function() {
      $('#error_message_ajax').css('display', 'none');
    });

    $('#page-prev').on('click', function(e) {
      $("ul#top-nav-tabs li.active").prev('li').find('a').trigger("click");
    });

    $('#page-next').on('click', function(e) {
      $("ul#top-nav-tabs li.active").next('li').find('a').trigger("click");
    });

    $.ajax({
      url: '<?php echo $province_url; ?>',
      type: 'GET',
      success: function(data, textStatus, jqXHR) {
        $('#province2_div').html(data);
        get_premium();
      },
    });
    addmoremember(0);

    $(".btn-payment-sort").click(sorting_payment);

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

    $('input[step]').change(function() {
      var step = $(this).attr('step');
      if (step > 10) {
        var v = $(this).val();
        var v = v - (v % step);
        $(this).val(v);
      }
    });

    $('#page-submit').on('click', function(e) {
      get_premium(); // Last confirm the changes
      $('#page-submit').hide();
    });

    var premium = $('input[name="premium"]').val();
    if (premium > 0) {
      $('#page-submit').show();
    }

    check_isfamilyplan();
    $('select[name=isfamilyplan]').on('change', check_isfamilyplan);
  });

  function check_isfamilyplan() {
    var sls = $('select[name=isfamilyplan]').val();
    if (sls < 1) {
      //single member
      $('#family_member').hide();
      $("input[name^='firstname_']").each(function() {
        $(this).val('');
      });
      $("input[name^='lastname_']").each(function() {
        $(this).val('');
      });
    } else {
      // family or group
      $('#family_member').show();
    }
  }

  function sorting_payment() {
    var d = $(this).attr('data-type');
    $.ajax({
      url: '<?php echo $payhistory_url; ?>?s=' + d,
      success: function(data, textStatus, jqXHR) {
        $('#payment_history').html(data);
        $(".btn-payment-sort").click(sorting_payment);
      },
    });
  }

  function remove_member(i) {
    if (confirm("Are you sure to delete this info?") == true) {
      var s, d;
      for (j = i + 1; j <= cur_max_member; j++) {
        s = '#customer_id_' + j;
        d = '#customer_id_' + i;
        $(d).val($(s).val());
        s = '#firstname_' + j;
        d = '#firstname_' + i;
        $(d).val($(s).val());
        s = '#lastname_' + j;
        d = '#lastname_' + i;
        $(d).val($(s).val());
        s = '#birthday_' + j;
        d = '#birthday_' + i;
        $(d).val($(s).val());
        s = '#gender_' + j;
        d = '#gender_' + i;
        $(d).val($(s).val());
        i++;
      }
      $('#customer_id_' + cur_max_member).val(0);
      $('#firstname_' + cur_max_member).val('');
      $('#lastname_' + cur_max_member).val('');
      $('#birthday_' + cur_max_member).val('');
      $('#gender_' + cur_max_member).val('M');
      $('#customer_member_' + cur_max_member).hide();
      cur_max_member--;
      get_premium();
    }
    show_ajax_message = 1;
  }

  function addmoremember(addnumber) {
    // Remove all error message
    if (addnumber) show_ajax_message = 1;
    for (i = 1; i <= <?php echo $max_member; ?>; i++) {
      $('#firstname_' + i).removeClass('alert-error-input');
      $('#lastname_' + i).removeClass('alert-error-input');
      $('#birthday_' + i).removeClass('alert-error-input');
      if (!$('#firstname_' + i).val() && !$('#lastname_' + i).val() && !$('#birthday_' + i).val()) {
        break;
      }
      if (!$('#firstname_' + i).val()) {
        $('#firstname_' + i).addClass('alert-error-input');
      }
      if (!$('#lastname_' + i).val()) {
        $('#lastname_' + i).addClass('alert-error-input');
      }
      if (!$('#birthday_' + i).val()) {
        $('#birthday_' + i).addClass('alert-error-input');
      }
      if (!$('#firstname_' + i).val() || !$('#lastname_' + i).val() || !$('#birthday_' + i).val()) {
        break;
      }
      $('#errormessage_' + i).html("");
    }
    cur_max_member += addnumber;
    if (addnumber > 0) {
      if (cur_max_member > <?php echo $max_member; ?>) {
        cur_max_member -= addnumber;
        alert("You reached maxium numbers");
        return;
      }
      $('#customer_id_' + cur_max_member).val(0);
      $('#firstname_' + cur_max_member).val('');
      $('#lastname_' + cur_max_member).val('');
      $('#birthday_' + cur_max_member).val('');
      $('#gender_' + cur_max_member).val('M');
      $('#customer_member_' + cur_max_member).show();
    }
  }

  function get_premium() {
    <?php if (empty($batch_number)) { ?>
      $.ajax({
        url: '<?php echo $premium_url; ?>',
        type: 'post',
        data: $('#plan_form').serialize(),
        success: function(data, textStatus, jqXHR) {
          answer = data;

          /*
          if (data['all_inclusive']) {
            $('input[name=package][value=all_inclusive]').prop("disabled", false);
          } else {
            $('input[name=package][value=all_inclusive]').prop("disabled", true);
          }
          if (data['single_medical_plan']) {
            $('input[name=package][value=single_medical_plan]').prop("disabled", false);
          } else {
            $('input[name=package][value=single_medical_plan]').prop("disabled", true);
          }
          if (data['annual_plan']) {
            $('input[name=package][value=annual_plan]').prop("disabled", false);
          } else {
            $('input[name=package][value=annual_plan]').prop("disabled", true);
          }
          if (data['optional_plan']) {
            $('input[name=package][value=optional_plan]').prop("disabled", false);
          } else {
            $('input[name=package][value=optional_plan]').prop("disabled", true);
          }
          */


          if (data['stable_condition']) {
            $('#stable_conditions_div').show();
          } else {
            $('#stable_conditions_div').hide();
          }

          need_questionnaire = data['questionnaire'];
          var tab_id = $('.nav-tabs .active').attr('id');

          if (data['questionnaire']) {
            $('#questionnaire_tab').show();
            if (tab_id == 'packages_li') {
              $('#page-next').css('display', '');
            }
          } else {
            $('#questionnaire_tab').hide();
            if (tab_id == 'packages_li') {
              $('#page-next').css('display', 'none');
            }
            $('input[name=questionnaire]').val(0);
            $('input[name=question2]').prop('checked', false);
            $('input[name=question3]').val(0);
            $('input[name=question4]').prop('checked', false);
            $('input[name=question5]').prop('checked', false);
          }

          if (data['totaldays'] > 0) {
            $('#totaldays').val(data['totaldays']);
          }
          if (data['totalyears'] > 0) {
            $('#totalyears').val(data['totalyears']);
          }

          if (data['status'] == 'OK') {
            $('input[name="premium"]').val(data['premium']);
            $('input[name="tax"]').val(data['tax']);
            $('#premium_value').html(data['premium']);
            if (data['premium'] > 0) {
              var vt = $('input[name=questionnaire]').val();
              if (vt > 0) {
                $('#premium_rate_table').html('(Table' + vt + ')');
              }
              $('#page-submit').show();
            }

            if (data['message']) {
              $('#error_page_message').html(data['message']);
              $('#error_page_message').show();
            } else {
              $('#error_page_message').html('');
              $('#error_page_message').hide();
            }
            show_ajax_message = 1;
          } else {
            $('#page-submit').hide();
            if (data['message']) {
              // var submitcss = $('#page-submit').css('display');
              //if (submitcss != 'none')
              if (show_ajax_message) {
                $('#error_page_message').html(data['message']);
                $('#error_page_message').show();
                if (data['active_tab'] != "undefined") {
                  $('#' + data['active_tab']).trigger("click");
                }
              }
            }
            $('#premium_value').html('');
            $('#premium_rate_table').html('');
            $('input[name="premium"]').val(0);
          }
        },
      });
    <?php } ?>
  }
</script>
<script type="text/javascript">
  function mytestdate() {
    var effectivedt = $('input[name="effective_date"]').val();
    if (!effectivedt) {
      return;
    }
    var effective = new Date(effectivedt);

    var expirydt = $('input[name="expiry_date"]').val();
    if (!expirydt) {
      return;
    }
    var expiry = new Date(expirydt);

    var diffdays = (expiry.getTime() - effective.getTime()) / (1000 * 24 * 3600) + 1;
    if (diffdays > 90) {
      $('#title_alert_message').text("Policy must less then 90 days");
      return;
    }

    var departturedt = $('input[name="arrival_date"]').val();
    if (!departturedt) {
      return;
    }
    var departture = new Date(departturedt);
    if (departture.getTime() > effective.getTime()) {
      $('#title_alert_message').text("Policy departture date must earlier than effective date");
      return;
    } else if (departturedt == effectivedt) {
      // Check apply data <= departture date (if normal policy)
      $('#title_alert_message').text("Policy departture date must earlier than effective date");
      return;
    } else {
      var applydt = $('input[name="apply_date"]').val();
      if (!applydt) {
        return;
      }
      var apply = new Date(applydt);
      if (departture.getTime() < apply.getTime()) {
        $('#title_alert_message').text("Confirm this is an extended policy, And input provide previous coverage to specail note area");
        return;
      } else {
        $('#title_alert_message').text("Confirm this is a TOP UP policy");
        return;
      }
    }
    $('#title_alert_message').text('The policy must purchase before departure!!')
  }
  $(document).ready(function() {
    $('[data-toggle="popover"]').popover();

    $('#plan_form').on('keyup keypress', function(e) {
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
    $('#checkboxdays').change(function() {
      if (this.checked) {
        var effective = $('#effective_date_div').datepicker('getDate');
        var myDate1 = new Date(effective);
        myDate1.setFullYear(myDate1.getFullYear() + 1);
        var myDate3 = myDate1.getTime() - 86400000;
        var myDate = new Date(myDate3);
        $('#expiry_date_div').datepicker('setDate', myDate);
      }
      $('input[name="expiry_date"]').trigger("change");
    });
    $('#totaldays').change(function() {
      var days = $('#totaldays').val();
      var effective = $('#effective_date_div').datepicker('getDate');
      var myDate = new Date(effective);
      days--;
      var tm = myDate.getTime() + (days * 86400000) + 43200000;
      myDate.setTime(tm);
      $('#expiry_date_div').datepicker('setDate', myDate);
      $('input[name="expiry_date"]').trigger("change");
    });

    $('body').on('change', '.check_premium', function() {
      get_premium();
    });


    var premium = $('input[name="premium"]').val();
    if (premium <= 0) {
      get_premium();
    }

    $('input[name="apply_date"]').change(function() {
      mytestdate();
    });
    $('input[name="arrival_date"]').change(function() {
      mytestdate();
    });
    $('input[name="effective_date"]').change(function() {
      mytestdate();
    });
    $('input[name="expiry_date"]').change(function() {
      mytestdate();
    });
    $('input[name="medical_eligible2"]').change(function() {
      var eli2 = $('input[name="medical_eligible2"]:checked').val();
      if (eli2 == "No") {
        $('#question_step1').hide();
        $('#error_page_message').html('You are not eligible for this plan');
        $('#error_page_message').show();
        $('input[name=questionnaire]').val(7);
      } else {
        $('#question_step1').show();
        $('#error_page_message').hide();
      }
    });
    $('input[name="medical_eligible1"]').change(function() {
      var eli1 = $('input[name="medical_eligible1"]:checked').val();
      if (eli1 == "No") {
        $('#medical_eligible2_div').hide();
        $('#error_page_message').html('You are not eligible for this plan');
        $('#error_page_message').show();
        $('input[name=questionnaire]').val(7);
      } else {
        $('#medical_eligible2_div').show();
        $('#error_page_message').hide();
      }
    });
    $('input[name="trip_cancellation_insured"]').change(function() {
      var nm = $('input[name="trip_cancellation_insured"]').val();
      if (nm < 0) {
        nm = 0
      } else if (nm > 8000) {
        nm = 8000;
      }
      nm = parseInt(nm / 100) * 100;
      $('input[name="trip_cancellation_insured"]').val(nm);
    });
    $('input[name="sum_insured"]').change(function() {
      var nm = $('input[name="sum_insured"]').val();
      if (nm < 0) {
        nm = 0
      } else if (nm > 8000) {
        nm = 8000;
      }
      nm = parseInt(nm / 100) * 100;
      $('input[name="sum_insured"]').val(nm);
    });
    $('#claim_allowed').change(function() {
      if (this.checked) {
        if (!$('#claim_allow_note').val()) {
          $('#claim_allowed').prop('checked', false);
          alert('Please fill up note before you allow process');
          return;
        }
        $('#claim_allow_by').val('<?php echo $do_user_id; ?>');
      } else {
        $('#claim_allow_by').val('');
      }
    });
    $('#claim_allowed').change(function() {
      if (this.checked) {} else {
        $('#claim_allow_by').val('');
        $('#page-submit').click();
      }
    });
  });
</script>
