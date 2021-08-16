<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
					<div class="row">
						<div class="col-sm-12">
							<fieldset>
								<legend>Insurable Options</legend>
								<div class="row">
									<div class="form-group col-sm-3">
										<label class="col-sm-12">Beneficiary</label>
										<div class="input-group col-sm-12">
											<input type='text' name='beneficiary' value='<?php echo htmlspecialchars($beneficiary); ?>' class="form-control">
										</div>
										<?php if (!empty($error_beneficiary)) {?>
										<div class="alert-error">
											<?php echo $error_beneficiary;?>
										</div>	
										<?php } ?>
									</div>
<?php if ($product_short != 'JESP') { ?>
									<div class="form-group col-sm-3">
										<label class="col-sm-12">Is Family Plan : </label>
										<div class="input-group col-sm-12" style="border: 1px solid #ccc;padding: 3px;">
											<input type='checkbox' class='setpremium' name='isfamilyplan' id='isfamilyplan' <?php echo empty($isfamilyplan) ? "" : "checked"; ?>> Yes
										</div>
									</div>
<?php } ?>
									<?php if (($product_short == "JES") && ($user_group_id < 100)) { ?>
									<div class="form-group col-sm-3">
										<label class="col-sm-12">Holiday Rate : </label>
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
										<label class="col-sm-12">Sum Insured (CAD) : $2,000,000</label>
										<input type='hidden' name='sum_insured' value='2000000' />
<?php } else { ?>
                    <label class="col-sm-12">Sum Insured (CAD) : $5,000,000</label>
										<input type='hidden' name='sum_insured' value='5000000' />
<?php } ?>
									</div>
								</div>
								<div class="row">
									<div class="form-group col-sm-3">
<?php if ($product_short == 'JESP') { ?>
                                                                                <label class="col-sm-12">Student Name : </label>
<?php } else { ?>
										<label class="col-sm-12">Student ID : </label>
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
										<label class="col-sm-12">School Name : </label>
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
										<label class="col-sm-12">School Full Address: </label>
										<div class="input-group col-sm-12">
											<input type='text' name='institution_addr' value='<?php echo $html_model->escapeQuote($institution_addr); ?>' class="form-control">
										</div>
									</div>
									<div class="form-group col-sm-3">
										<label class="col-sm-12">School Phone: </label>
										<div class="input-group col-sm-12">
											<input type='text' name='institution_phone' value='<?php echo $html_model->escapeQuote($institution_phone); ?>' class="form-control">
										</div>
									</div>
								</div>
                <?php if ($product_short == 'JFS') { ?>
                <div class="row">
									<div class="col-sm-12">
										<label class="inline">Select pre-existing condition coverage</label>
										<div class="inline">
											<select name='stable_condition' class="form-control setpremium" id='stable_condition_select'>
												<option value='0'> -- select condition -- </option>
												<option value='1' <?php echo ($stable_condition == 1) ? 'selected' : ''; ?>>Including stable pre-existing condition coverage</option>
												<option value='2' <?php echo ($stable_condition == 2) ? 'selected' : ''; ?>>No pre-existing condition coverage</option>
											</select>
										</div>
										<?php if (!empty($error_stable_condition)) {?>
										<div class="alert-error">
											<?php echo $error_stable_condition;?>
										</div>	
										<?php } ?>
									</div>
								</div>
								<div class="row" id="stable_condition_confirm_div" <?php echo ($stable_condition == 2) ? '' : 'style="display: none"'; ?>>
									<div class="col-sm-12">
										<div class="inline">
											<input type='hidden' name='stable_condition_confirm' value='<?php echo $stable_condition_confirm; ?>'>
											<i class="fa fa-check"></i>
											<B>WARNING</B>: Please confirm with the insured that this plan does not cover ANY Pre-Existing Medical Condition(s)..<br />
                      Pre-Existing Medical Condition(s) means any medical condition, sickness or injury for which at any time prior to the effective date, you have experienced symptoms, you have received medical care, advice, investigation or medical treatment, you have been hospitalized, you have been prescribed (including prescribed as needed) or have taken medication, or you have undergone a medical surgical procedure.<br />
                      BBy checking the below box, you confirm that both you and your client understand the term and that the plan selected does NOT cover any Pre-Existing Medical Condition(s). <br />
                      Please confirm you have selected the "No pre-existing condition coverage"
										</div>
									</div>
								</div>
                <?php } ?>
							</fieldset>
						</div>
					</div><br />
