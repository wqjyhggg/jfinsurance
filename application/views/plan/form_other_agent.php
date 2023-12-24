<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
					<div class="row">
						<div class="col-sm-12">
							<fieldset>
								<legend>Insurable Options</legend>
								<div class="row">
									<div class="form-group col-sm-3">
										<label class="col-sm-12"><?php echo $this->lang->line("Beneficiary"); ?></label>
										<div class="input-group col-sm-12">
											<div class='form_text_show'><?php echo htmlspecialchars($beneficiary); ?></div>
											<input type='hidden' name='beneficiary' value='<?php echo $html_model->escapeQuote($beneficiary); ?>' class="form-control">
										</div>
									</div>
									<div class="form-group col-sm-3">
										<label class="col-sm-12"><?php echo $this->lang->line("Is Family Plan"); ?> : </label>
										<div class="input-group col-sm-12">
											<div class='form_text_show'><?php echo empty($isfamilyplan) ? "No" : "Yes"; ?></div>
											<input type='hidden' name='isfamilyplan' id='isfamilyplan' value='<?php echo $isfamilyplan; ?>'>
										</div>
									</div>
								</div>
							</fieldset>
						</div>
					</div><br />
