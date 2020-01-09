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
									<div class="form-group col-sm-3">
										<label class="col-sm-12">Is Family Plan : </label>
										<div class="input-group col-sm-12" style="border: 1px solid #ccc;padding: 3px;">
											<input type='checkbox' class='setpremium' name='isfamilyplan' id='isfamilyplan' <?php echo empty($isfamilyplan) ? "" : "checked"; ?>> Yes
										</div>
									</div>
									<?php if (($product_short == "JES") && ($user_group_id < 100)) { ?>
									<div class="form-group col-sm-3">
										<label class="col-sm-12">Holiday Rate : </label>
										<div class="input-group col-sm-12" style="border: 1px solid #ccc;padding: 3px;">
											<input type='checkbox' name='holiday_rate' id='holiday_rate' value='1' <?php echo empty($holiday_rate) ? "" : "checked"; ?>> Yes
										</div>
									</div>
									<?php } else { ?>
									<input type='hidden' name='holiday_rate' id='holiday_rate' value='<?php echo $holiday_rate; ?>'>
									<?php } ?>
									<div class="form-group col-sm-3">
										<label class="col-sm-12">Sum Insured (CAD) : $5,000,000</label>
										<input type='hidden' name='sum_insured' value='5000000' />
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
