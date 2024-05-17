<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
					<div class="row">
						<div class="col-sm-12">
							<fieldset>
								<legend><?php echo $this->lang->line("Insurable Options"); ?></legend>
								<div class="row">
									<div class="form-group col-sm-3">
										<label class="col-sm-12"><?php echo $this->lang->line("Beneficiary"); ?></label>
										<div class="input-group col-sm-12">
											<input type='text' name='beneficiary' value='<?php echo htmlspecialchars($beneficiary); ?>' class="form-control">
										</div>
										<?php if (!empty($error_beneficiary)) {?>
										<div class="alert-error">
											<?php echo $error_beneficiary;?>
										</div>	
										<?php } ?>
									</div>
<?php if (($product_short != 'JESP') && ($product_short != 'JFS') && ($product_short != 'JFSL')) { ?>
									<div class="form-group col-sm-3">
										<label class="col-sm-12"><?php echo $this->lang->line("Is Family Plan"); ?> : </label>
										<div class="input-group col-sm-12" style="border: 1px solid #ccc;padding: 3px;">
											<input type='checkbox' class='setpremium' name='isfamilyplan' id='isfamilyplan' <?php echo empty($isfamilyplan) ? "" : "checked"; ?>> Yes
										</div>
									</div>
<?php } ?>
<?php $holidyUser = array(388,1005,1932,2198,2284,2316,2318,2426,2435,2465,2467,2468,3042,3704,3848,4393); ?>
                  <?php if ((($product_short == "JES") || ($product_short == "JFGD") || ($product_short == "JFSL") || ($product_short == "JESP")) && (($user_group_id < 100) || in_array($beuser_user_id, $holidyUser))) { ?>
									<div class="form-group col-sm-3">
										<label class="col-sm-12"><?php echo $this->lang->line("Holiday Rate"); ?> : </label>
										<div class="input-group col-sm-12" style="border: 1px solid #ccc;padding: 3px;">
											<input type='checkbox' name='holiday_rate' id='holiday_rate' value='1' <?php echo empty($holiday_rate) ? "" : "checked"; ?>> Yes
										</div>
									</div>
									<?php } else { ?>
                  <?php if ($product_short != "BHS") { ?>
									<input type='hidden' name='holiday_rate' id='holiday_rate' value='<?php echo $holiday_rate; ?>'>
                  <?php } ?> 
									<?php } ?>
									<div class="form-group col-sm-3">
<?php if ($product_short == 'BHS') { ?>
										<label class="col-sm-12"><?php echo $this->lang->line("Sum Insured"); ?> (CAD) : $2,000,000</label>
										<input type='hidden' name='sum_insured' value='2000000' />
<?php } else { ?>
                    <label class="col-sm-12"><?php echo $this->lang->line("Sum Insured"); ?> (CAD) : $5,000,000</label>
										<input type='hidden' name='sum_insured' value='5000000' />
<?php } ?>
									</div>
								</div>
								<div class="row">
									<div class="form-group col-sm-3">
<?php if ($product_short == 'JESP') { ?>
                    <label class="col-sm-12"><?php echo $this->lang->line("Student Name"); ?> : </label>
<?php } else { ?>
										<label class="col-sm-12"><?php echo $this->lang->line("Student ID"); ?> : </label>
<?php } ?>
										<div class="input-group col-sm-12">
											<input type='text' name='student_id' value='<?php echo $student_id; ?>' class="form-control">
										</div>
										<?php if (!empty($error_student_id)) {?>
										<div class="alert-error">
											<?php echo $error_student_id;?>
										</div>	
										<?php } ?>
									</div>
									<div class="form-group col-sm-3">
										<label class="col-sm-12"><?php echo $this->lang->line("School Name"); ?> : </label>
										<div class="input-group col-sm-12">
											<input type='text' name='institution' value='<?php echo $html_model->escapeQuote($institution); ?>' class="form-control">
										</div>
										<?php if (!empty($error_institution)) {?>
										<div class="alert-error">
											<?php echo $error_institution;?>
										</div>	
										<?php } ?>
									</div>
									<div class="form-group col-sm-3">
										<label class="col-sm-12"><?php echo $this->lang->line("School Full Address"); ?>: </label>
										<div class="input-group col-sm-12">
											<input type='text' name='institution_addr' value='<?php echo $html_model->escapeQuote($institution_addr); ?>' class="form-control">
										</div>
									</div>
									<div class="form-group col-sm-3">
										<label class="col-sm-12"><?php echo $this->lang->line("School Phone"); ?>: </label>
										<div class="input-group col-sm-12">
											<input type='text' name='institution_phone' value='<?php echo $html_model->escapeQuote($institution_phone); ?>' class="form-control">
										</div>
									</div>
								</div>
                <?php if ($product_short == 'JFS') { ?>
								<div class="row" id="stable_condition_confirm_div">
									<div class="col-sm-12">
                    <div class="inline">
											<B><?php echo $this->lang->line("WARNING"); ?></B>: <?php echo $this->lang->line("Please confirm with the insured that this plan does not cover ANY Pre-Existing Medical Condition(s).."); ?><br />
                      <?php echo $this->lang->line("Pre-Existing Medical Condition(s) means any medical condition, sickness or injury for which at any time prior to the effective date, you have experienced symptoms, you have received medical care, advice, investigation or medical treatment, you have been hospitalized, you have been prescribed (including prescribed as needed) or have taken medication, or you have undergone a medical surgical procedure."); ?><br />
                      <input type='checkbox' name='stable_condition_confirm' value='1' <?php echo ($stable_condition_confirm?"checked":""); ?>>
                      <?php echo $this->lang->line("By checking the box, you confirm that both you and your client understand the term and that the plan selected does NOT cover any Pre-Existing Medical Condition(s)."); ?> <br />
                      <input type='hidden' name='stable_condition' value='2'>
										</div>
                    <?php if (!empty($error_stable_condition_confirm)) {?>
											<div class="alert-error">
												<?php echo $error_stable_condition_confirm;?>
											</div>	
										<?php } ?>
									</div>
								</div>
                <?php } ?>
							</fieldset>
						</div>
					</div><br />
