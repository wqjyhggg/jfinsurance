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
											<input type='text' name='sum_insured' value='<?php echo number_format($sum_insured, 2); ?>' class="form-control">
										</div>
									</div>
									<div class="form-group col-sm-3">
										<label class="col-sm-12">Deductible amount (CAD):</label>
										<div class="input-group col-sm-12">
											<input type='text' name='deductible_amount' value='<?php echo number_format($deductible_amount, 2); ?>' class="form-control">
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-12">
										<label class="inline">With stable pre-existion condition coverage</label>
										<div class="inline">
											<input type='radio' class='setpremium' name='stable_condition' value='1' <?php echo (empty($stable_condition) || ($stable_condition != 2 )) ? "checked" : ""; ?>>
										</div>
									</div>
									<div class="col-sm-12">
										<label class="inline">Without stable pre-existion condition coverage </label>
										<div class="inline">
											<input type='radio' class='setpremium' name='stable_condition' value='2' <?php echo (!empty($stable_condition) && ($stable_condition == 2 )) ? "checked" : ""; ?>>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-12">
										<label class="inline">Rate Options Plus</label>
										<div class="inline">
											<input type='radio' class='setpremium' name='rate_options' value='1' <?php echo (empty($rate_options) || ($rate_options != 2 )) ? "checked" : ""; ?>>
										</div>
									</div>
									<div class="col-sm-12">
										<label class="inline">Rate Options Preferred</label>
										<div class="inline">
											<input type='radio' class='setpremium' name='rate_options' value='2' <?php echo (!empty($rate_options) && ($rate_options == 2 )) ? "checked" : ""; ?>>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="form-group col-sm-3">
										<label class="col-sm-12">Institution</label>
										<div class="input-group col-sm-12">
											<input type='text' name='institution' value='<?php echo $institution; ?>' class="form-control">
										</div>
									</div>
									<div class="form-group col-sm-3">
										<label class="col-sm-12">Student ID</label>
										<div class="input-group col-sm-12">
											<input type='text' name='student_id' value='<?php echo $student_id; ?>' class="form-control">
										</div>
									</div>
									<div class="form-group col-sm-3">
										<label class="col-sm-12">Institution Phone</label>
										<div class="input-group col-sm-12">
											<input type='text' name='institution' value='<?php echo $institution; ?>' class="form-control">
										</div>
									</div>
									<div class="form-group col-sm-3">
										<label class="col-sm-12">Institution Fax</label>
										<div class="input-group col-sm-12">
											<input type='text' name='institution_addr' value='<?php echo $institution_fax; ?>' class="form-control">
										</div>
									</div>
									<div class="form-group col-sm-12">
										<label class="col-sm-12">Institution Address</label>
										<div class="input-group col-sm-12" style="border: 1px solid #ccc;padding: 3px;">
											<input type='text' name='institution_addr' value='<?php echo $institution_addr; ?>' class="form-control">
										</div>
									</div>
								</div>
							</fieldset>
						</div>
					</div><br />
