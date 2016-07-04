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
										<label class="col-sm-12">Sum Insured (CAD): Unlimited</label>
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
							</fieldset>
						</div>
					</div><br />
