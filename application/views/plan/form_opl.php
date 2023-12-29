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
											<input type='text' name='beneficiary' value='<?php echo $html_model->escapeQuote($beneficiary); ?>' class="form-control">
										</div>
										<?php if (!empty($error_beneficiary)) {?>
										<div class="alert-error">
											<?php echo $error_beneficiary;?>
										</div>	
										<?php } ?>
									</div>
									<div class="form-group col-sm-3">
										<label class="col-sm-12"><?php echo $this->lang->line("Is Family Plan"); ?></label>
										<div class="input-group col-sm-12" style="border: 1px solid #ccc;padding: 3px;">
											 <input type='checkbox' class='setpremium' name='isfamilyplan' id='isfamilyplan' <?php echo empty($isfamilyplan) ? "" : "checked"; ?>> Yes
										</div>
									</div>
									<div class="form-group col-sm-3">
										<label class="col-sm-12"><?php echo $this->lang->line("Sum Insured"); ?> (CAD):</label>
										<div class="input-group col-sm-12">
											<div id='sum_insured_div'></div>
										</div>
									</div>
									<div class="form-group col-sm-3">
										<label class="col-sm-12"><?php echo $this->lang->line("Deductible amount"); ?> (CAD):</label>
										<div class="input-group col-sm-12">
											 <div id='deductible_amount_div'></div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-12">
										<label class="inline"><?php echo $this->lang->line("Select pre-existing condition coverage"); ?></label>
										<div class="inline">
											<select name='stable_condition' class="form-control setpremium" id='stable_condition_select'>
												<option value='0'> -- <?php echo $this->lang->line("select condition"); ?> -- </option>
												<option value='1' <?php echo ($stable_condition == 1) ? 'selected' : ''; ?>><?php echo $this->lang->line("Including stable pre-existing condition coverage"); ?></option>
												<option value='2' <?php echo ($stable_condition == 2) ? 'selected' : ''; ?>><?php echo $this->lang->line("No pre-existing condition coverage"); ?></option>
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
											<br />
											<B><?PHP ECHO $THIS->LANG->LINE("WARNING"); ?></B>: <?php echo $this->lang->line("Please confirm with the insured(s) that this option does not cover ANY Pre-Existing Medical Condition(s)"); ?>.<br /><br />

<?php echo $this->lang->line("Pre-Existing Medical Condition(s) means any medical condition, sickness or injury for which at any time prior to the effective date, you have experienced symptoms, you have received medical care, advice, investigation or medical treatment, you have been hospitalized, you have been prescribed (including prescribed as needed) or have taken medication, or you have undergone a medical surgical procedure."); ?><br /><br />

<?php echo $this->lang->line("By checking the below box, you confirm that both you and your client understand the term and that the option selected does NOT cover any Pre-Existing Medical Condition(s)."); ?> <br /><br />
											<input type='checkbox' class='setpremium' id='stable_condition_confirm' name='stable_condition_confirm' <?php echo ($stable_condition_confirm ? "checked" : ""); ?>> <?php echo $this->lang->line("Please confirm you have selected the 'No pre-existing condition coverage'"); ?> <br />
											<?php if (!empty($error_stable_condition_confirm)) {?>
											<div class="alert-error">
												<?php echo $error_stable_condition_confirm;?>
											</div>	
											<?php } ?>
										</div>
									</div>
								</div>
							</fieldset>
						</div>
					</div><br />
