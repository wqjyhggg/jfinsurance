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
											<input type='text' name='beneficiary' value='<?php echo $html_model->escapeQuote($beneficiary); ?>' class="form-control">
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
										<label class="col-sm-12">Include Spouse</label>
										<div class="input-group col-sm-12" style="border: 1px solid #ccc;padding: 3px;" id='spousediv'>
											 <input type='checkbox' class='setpremium' name='spouse' id='spouse' value='1' <?php echo empty($spouse) ? "" : "checked"; ?>> Yes
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-12">
										<label class="inline">Rate Options</label>
										<div class="inline">
											<select name='rate_options' class="form-control setpremium">
												<option value='0'> -- select condition -- </option>
												<option value='1' <?php echo ($rate_options == 1) ? 'selected' : ''; ?>>Rate Options Plus</option>
												<option value='2' <?php echo ($rate_options == 2) ? 'selected' : ''; ?>>Rate Options Preferred</option>
											</select>
											<input type='hidden' name='deductible_amount' value='<?php echo $deductible_amount; ?>'>
										</div>
										<?php if (!empty($error_rate_options)) {?>
										<div class="alert-error">
											<?php echo $error_rate_options;?>
										</div>	
										<?php } ?>
									</div>
								</div>
								<div class="row">
									<div class="form-group col-sm-3">
										<label class="col-sm-12">Student ID : </label>
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
							</fieldset>
						</div>
					</div><br />
