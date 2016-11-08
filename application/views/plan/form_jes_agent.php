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
											<div class='form_text_show'><?php echo $beneficiary; ?></div>
											<input type='hidden' name='beneficiary' value='<?php echo $beneficiary; ?>' class="form-control">
										</div>
									</div>
									<div class="form-group col-sm-3">
										<label class="col-sm-12">Is Family Plan : </label>
										<div class="input-group col-sm-12">
											<div class='form_text_show'><?php echo empty($isfamilyplan) ? "No" : "Yes"; ?></div>
											<input type='hidden' name='isfamilyplan' id='isfamilyplan' value='<?php echo $isfamilyplan; ?>'>
										</div>
									</div>
									<input type='hidden' name='holiday_rate' id='holiday_rate' value='<?php echo $holiday_rate; ?>'>
									<div class="form-group col-sm-3">
										<label class="col-sm-12">Sum Insured (CAD) : $5,000,000</label>
										<input type='hidden' name='sum_insured' value='5000000' />
									</div>
								</div>
								<div class="row">
									<div class="form-group col-sm-3">
										<label class="col-sm-12">Student ID : </label>
										<div class="input-group col-sm-12">
											<div class='form_text_show'><?php echo $student_id; ?></div>
											<input type='hidden' name='student_id' value='<?php echo $student_id; ?>' class="form-control">
										</div>
									</div>
									<div class="form-group col-sm-3">
										<label class="col-sm-12">School Name : </label>
										<div class="input-group col-sm-12">
											<div class='form_text_show'><?php echo $institution; ?></div>
											<input type='hidden' name='institution' value='<?php echo $institution; ?>' class="form-control">
										</div>
									</div>
									<div class="form-group col-sm-3">
										<label class="col-sm-12">School Full Address: </label>
										<div class="input-group col-sm-12">
											<div class='form_text_show'><?php echo $institution_addr; ?></div>
											<input type='hidden' name='institution_addr' value='<?php echo $institution_addr; ?>' class="form-control">
										</div>
									</div>
									<div class="form-group col-sm-3">
										<label class="col-sm-12">School Phone: </label>
										<div class="input-group col-sm-12">
											<div class='form_text_show'><?php echo $institution_phone; ?></div>
											<input type='hidden' name='institution_phone' value='<?php echo $institution_phone; ?>' class="form-control">
										</div>
									</div>
								</div>
							</fieldset>
						</div>
					</div><br />
