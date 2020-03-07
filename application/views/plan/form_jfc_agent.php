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
											<?php echo htmlspecialchars($beneficiary); ?>
										</div>
										<input type='hidden' name='beneficiary' value='<?php echo $html_model->escapeQuote($beneficiary); ?>' class="form-control">
									</div>
									<div class="form-group col-sm-3">
										<label class="col-sm-12">Sum Insured (CAD) : $2,000,000</label>
										<input type='hidden' name='sum_insured' value='2000000' />
									</div>
								</div>
								<div class="row">
									<div class="form-group col-sm-3">
										<label class="col-sm-12">Student ID : </label>
										<div class="form_text_show">
											<?php echo $student_id; ?>
										</div>
										<input type='hidden' name='student_id' value='<?php echo $student_id; ?>' class="form-control">
									</div>
									<div class="form-group col-sm-3">
										<label class="col-sm-12">School Name : </label>
										<div class="form_text_show">
											<?php echo htmlspecialchars($institution); ?>
										</div>
										<input type='hidden' name='institution' value='<?php echo $html_model->escapeQuote($institution); ?>' class="form-control">
									</div>
									<div class="form-group col-sm-3">
										<label class="col-sm-12">School Full Address: </label>
										<div class="form_text_show">
											<?php echo htmlspecialchars($institution_addr); ?>
										</div>
										<input type='hidden' name='institution_addr' value='<?php echo $html_model->escapeQuote($institution_addr); ?>' class="form-control">
									</div>
									<div class="form-group col-sm-3">
										<label class="col-sm-12">School Phone: </label>
										<div class="form_text_show">
											<?php echo htmlspecialchars($institution_phone); ?>
										</div>
										<input type='hidden' name='institution_phone' value='<?php echo $html_model->escapeQuote($institution_phone); ?>' class="form-control">
									</div>
								</div>
							</fieldset>
						</div>
					</div><br />
