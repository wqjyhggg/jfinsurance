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
											<input type='text' name='beneficiary' value='<?php echo $beneficiary; ?>' class="form-control">
										</div>
										<?php if (!empty($error_beneficiary)) {?>
										<div class="alert-error">
											<?php echo $error_beneficiary;?>
										</div>	
										<?php } ?>
									</div>
									<div class="form-group col-sm-3">
										<label class="col-sm-12">Is Family Plan</label>
										<div class="input-group col-sm-12" style="border: 1px solid #ccc;padding: 3px;">
											 <input type='checkbox' class='setpremium' name='isfamilyplan' id='isfamilyplan' <?php echo empty($isfamilyplan) ? "" : "checked"; ?>> Yes
										</div>
									</div>
									<div class="form-group col-sm-3">
										<label class="col-sm-12">Sum Insured (CAD):</label>
										<div class="input-group col-sm-12">
											<div id='sum_insured_div'></div>
										</div>
									</div>
									<div class="form-group col-sm-3">
										<label class="col-sm-12">Deductible amount (CAD):</label>
										<div class="input-group col-sm-12">
											 <div id='deductible_amount_div'></div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-12">
										<label class="inline">Select pre-existing condition coverage</label>
										<div class="inline">
											<select name='stable_condition' class="form-control setpremium" id='stable_condition_select'>
												<option value='0'> -- select condition -- </option>
												<option value='1' <?php echo ($stable_condition == 1) ? 'selected' : ''; ?>>Including stable pre-existing condition coverage</option>
												<option value='2' <?php echo ($stable_condition == 2) ? 'selected' : ''; ?>>Excluding stable pre-existing condition coverage</option>
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
											<input type='checkbox' class='setpremium' id='stable_condition_confirm' name='stable_condition_confirm' <?php echo ($stable_condition_confirm ? "checked" : ""); ?>> Please confirm you have selected the "Excluding stable pre-existing condition coverage" <br />
											<B>WARNING</B>: Please confirm with the insured(s) that this option does not cover ANY Pre-Existing Medical Condition(s).<br />

Pre-Existing Medical Condition(s) means any medical condition, sickness or injury for which at any time prior to the effective date, you have experienced symptoms, you have received medical care, advice, investigation or medical treatment, you have been hospitalized, you have been prescribed (including prescribed as needed) or have taken medication, or you have undergone a medical surgical procedure.<br />

By checking the below box, you confirm that both you and your client understand the term and that the option selected does NOT cover any Pre-Existing Medical Condition(s). 
										</div>
										<?php if (!empty($error_stable_condition_confirm)) {?>
										<div class="alert-error">
											<?php echo $error_stable_condition_confirm;?>
										</div>	
										<?php } ?>
									</div>
								</div>
							</fieldset>
						</div>
					</div><br />
