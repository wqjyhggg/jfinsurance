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
										<label class="col-sm-12">Is Family Plan : <input type='checkbox' class='setpremium' name='isfamilyplan' id='isfamilyplan' <?php echo empty($isfamilyplan) ? "" : "checked"; ?>></label>
									</div>
									<?php if (($product_short == "JES") && ($user_group_id < 100)) { ?>
									<div class="form-group col-sm-3">
										<label class="col-sm-12">Holiday Rate : <input type='checkbox' name='holiday_rate' id='holiday_rate' value='1' <?php echo empty($holiday_rate) ? "" : "checked"; ?>></label>
									</div>
									<?php } ?>
									<div class="form-group col-sm-3">
										<label class="col-sm-12">Sum Insured (CAD) : $2,000,000</label>
										<input type='hidden' name='sum_insured' value='2000000' />
									</div>
								</div>
							</fieldset>
						</div>
					</div><br />
