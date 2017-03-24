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
											<?php echo ($stable_condition == 1) ? "Including stable pre-existing condition coverage" : "Excluding stable pre-existing condition coverage"; ?>
										</div>
										<input type='hidden' class='stable_condition' name='stable_condition' id='stable_condition' value='<?php echo $stable_condition; ?>'>
									</div>
								</div>
							</fieldset>
						</div>
					</div><br />
