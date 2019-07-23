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
										<div class="form_text_show">
											<?php echo $beneficiary; ?>
										</div>
										<input type='hidden' name='beneficiary' value='<?php echo $beneficiary; ?>' class="form-control">
										<?php if (!empty($error_beneficiary)) {?>
										<div class="alert-error">
											<?php echo $error_beneficiary;?>
										</div>	
										<?php } ?>
									</div>
									<div class="form-group col-sm-3">
										<label class="col-sm-12">Is Family Plan</label>
										<div class="form_text_show">
											 <?php echo empty($isfamilyplan) ? "No" : "Yes"; ?>
										</div>
										 <input type='hidden' class='setpremium' name='isfamilyplan' id='isfamilyplan' value='<?php echo $isfamilyplan; ?>'>
									</div>
									<div class="form-group col-sm-3">
										<label class="col-sm-12">Sum Insured (CAD):</label>
										<div class="form_text_show">
											 <?php echo $sum_insured; ?>
										</div>
										<input type='hidden' class='sum_insured' name='sum_insured' id='sum_insured' value='<?php echo $sum_insured; ?>'>
									</div>
									<div class="form-group col-sm-3">
										<label class="col-sm-12">Deductible amount (CAD):</label>
										<div class="form_text_show">
											 <?php echo $deductible_amount; ?>
										</div>
										<input type='hidden' class='deductible_amount' name='deductible_amount' id='deductible_amount' value='<?php echo $deductible_amount; ?>'>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-12">
										<label class="inline">Selected pre-existing condition coverage</label>
										<div class="inline">
											<?php echo ($stable_condition == 1) ? "Including stable pre-existing condition coverage" : (($product_short == 'JFR') ? "No pre-existing condition coverage" : "Excluding stable pre-existing condition coverage"); ?>
										</div>
										<input type='hidden' class='stable_condition' name='stable_condition' id='stable_condition' value='<?php echo $stable_condition; ?>'>
									</div>
								</div>
								
								<div class="row" id="stable_condition_confirm_div" <?php echo ($stable_condition == 2) ? '' : 'style="display: none"'; ?>>
									<div class="col-sm-12">
										<div class="inline">
											<input type='hidden' name='stable_condition_confirm' value='<?php echo $stable_condition_confirm; ?>'>
											<i class="fa fa-check"></i>
											<B>WARNING</B>: Please confirm with the insured(s) that this option does not cover ANY Pre-Existing Medical Condition(s).<br />

Pre-Existing Medical Condition(s) means any medical condition, sickness or injury for which at any time prior to the effective date, you have experienced symptoms, you have received medical care, advice, investigation or medical treatment, you have been hospitalized, you have been prescribed (including prescribed as needed) or have taken medication, or you have undergone a medical surgical procedure.<br />

By checking the below box, you confirm that both you and your client understand the term and that the option selected does NOT cover any Pre-Existing Medical Condition(s). 
										</div>
									</div>
								</div>
							</fieldset>
						</div>
					</div><br />
