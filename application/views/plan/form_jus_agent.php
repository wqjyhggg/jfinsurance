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
										<div class="form_text_show">
											<?php echo htmlspecialchars($beneficiary); ?>
										</div>
										<input type='hidden' name='beneficiary' value='<?php echo $html_model->escapeQuote($beneficiary); ?>' class="form-control">
									</div>
									<div class="form-group col-sm-3">
										<label class="col-sm-12"><?php echo $this->lang->line("Is Family Plan"); ?></label>
										<div class="form_text_show">
											 <?php echo empty($isfamilyplan) ? "No" : "Yes"; ?>
										</div>
										 <input type='hidden' class='setpremium' name='isfamilyplan' id='isfamilyplan' value='<?php echo $isfamilyplan; ?>'>
									</div>
									<div class="form-group col-sm-3">
										<label class="col-sm-12"><?php echo $this->lang->line("Include Spouse"); ?></label>
										<div class="form_text_show">
											 <?php echo empty($spouse) ? "No" : "Yes"; ?>
										</div>
										 <input type='hidden' class='setpremium' name='spouse' id='spouse' value='<?php echo $html_model->escapeQuote($spouse); ?>'>
									</div>
									<div class="form-group col-sm-3">
										<label class="col-sm-12"><?php echo $this->lang->line("Deductible amount"); ?> (USD):</label>
										<div class="form_text_show">
											 <?php echo $deductible_amount; ?>
										</div>
										<input type='hidden' class='deductible_amount' name='deductible_amount' id='deductible_amount' value='<?php echo $deductible_amount; ?>'>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-12">
										<label class="inline"><?php echo ($rate_options == '1') ? $this->lang->line("Rate Options Plus") : $this->lang->line("Rate Options Preferred"); ?></label>
										<input type='hidden' name='rate_options' id='rate_options' class="form-control setpremium" value='<?php echo $rate_options; ?>'>
									</div>
								</div>
								<div class="row">
									<div class="form-group col-sm-3">
										<label class="col-sm-12"><?php echo $this->lang->line("Student ID"); ?> : </label>
										<input type='text' name='student_id' value='<?php echo $student_id; ?>' class="form-control">
									</div>
									<div class="form-group col-sm-3">
										<label class="col-sm-12"><?php echo $this->lang->line("School Name"); ?> : </label>
										<input type='text' name='institution' value='<?php echo $html_model->escapeQuote($institution); ?>' class="form-control">
									</div>
									<div class="form-group col-sm-3">
										<label class="col-sm-12"><?php echo $this->lang->line("School Full Address"); ?>: </label>
										<input type='text' name='institution_addr' value='<?php echo $html_model->escapeQuote($institution_addr); ?>' class="form-control">
									</div>
									<div class="form-group col-sm-3">
										<label class="col-sm-12"><?php echo $this->lang->line("School Phone"); ?>: </label>
										<input type='text' name='institution_phone' value='<?php echo $html_model->escapeQuote($institution_phone); ?>' class="form-control">
									</div>
								</div>
							</fieldset>
						</div>
					</div><br />
