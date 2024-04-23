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
										<label class="col-sm-12"><?php echo $this->lang->line("Sum Insured"); ?> (CAD) : $2,000,000</label>
										<input type='hidden' name='sum_insured' value='2000000' />
									</div>
								</div>
								<div class="row">
									<div class="form-group col-sm-3">
										<label class="col-sm-12"><?php echo $this->lang->line("Student ID"); ?> : </label>
										<input type='text' name='student_id' value='<?php echo $student_id; ?>' class="form-control">
									</div>
									<div class="form-group col-sm-3">
										<label class="col-sm-12"><?php echo $this->lang->line("Student Name"); ?> : </label>
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
