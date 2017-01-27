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
												<option value='1' <?php echo ($stable_condition == 1) ? 'selected' : ''; ?>>With stable pre-existing condition coverage</option>
												<option value='2' <?php echo ($stable_condition == 2) ? 'selected' : ''; ?>>Without stable pre-existing condition coverage</option>
											</select>
										</div>
										<?php if (!empty($error_stable_condition)) {?>
										<div class="alert-error">
											<?php echo $error_stable_condition;?>
										</div>	
										<?php } ?>
									</div>
								</div>
							</fieldset>
						</div>
					</div><br />
