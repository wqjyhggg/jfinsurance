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
											<div class='form_text_show'><?php echo htmlspecialchars($beneficiary); ?></div>
											<input type='hidden' name='beneficiary' value='<?php echo $html_model->escapeQuote($beneficiary); ?>' class="form-control">
										</div>
									</div>
<?php if (($product_short != 'JESP') && ($product_short != 'JFS')) { ?>
									<div class="form-group col-sm-3">
										<label class="col-sm-12">Is Family Plan : </label>
										<div class="input-group col-sm-12">
											<div class='form_text_show'><?php echo empty($isfamilyplan) ? "No" : "Yes"; ?></div>
											<input type='hidden' name='isfamilyplan' id='isfamilyplan' value='<?php echo $isfamilyplan; ?>'>
										</div>
									</div>
<?php } ?>
									<input type='hidden' name='holiday_rate' id='holiday_rate' value='<?php echo $holiday_rate; ?>'>
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
									</div>
									<div class="form-group col-sm-3">
										<label class="col-sm-12">School Name : </label>
										<div class="input-group col-sm-12">
											<input type='text' name='institution' value='<?php echo $html_model->escapeQuote($institution); ?>' class="form-control">
										</div>
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
								<div class="row" id="stable_condition_confirm_div">
									<div class="col-sm-12">
                    <div class="inline">
											<B>WARNING</B>: Please confirm with the insured that this plan does not cover ANY Pre-Existing Medical Condition(s)..<br />
                      Pre-Existing Medical Condition(s) means any medical condition, sickness or injury for which at any time prior to the effective date, you have experienced symptoms, you have received medical care, advice, investigation or medical treatment, you have been hospitalized, you have been prescribed (including prescribed as needed) or have taken medication, or you have undergone a medical surgical procedure.<br />
                      <input type='checkbox' name='stable_condition_confirm' value='1' <?php echo ($stable_condition_confirm?"checked":""); ?>>
                      By checking the box, you confirm that both you and your client understand the term and that the plan selected does NOT cover any Pre-Existing Medical Condition(s). <br />
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
