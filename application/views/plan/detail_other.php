<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
?>
<!-- Special parts -->
							<div class="row">
								<div class="col-sm-3">
									<label class="inline">Beneficiary:</label>
									<span><?php echo $plan['beneficiary']; ?></span>
								</div>
								<div class="col-sm-3">
									<label class="inline">Sum Insured (CAD):</label>
									<span>$<?php echo number_format($plan['sum_insured'], 2, '.', ',');  ?></span>
								</div>
								<div class="col-sm-6">
									<label class="inline">Deductible amount (CAD):</label>
									<span>$<?php echo number_format($plan['deductible_amount'], 2, '.', ','); ?></span>
								</div>
							</div>
							
							<div class="row">
								<?php if ($plan['isfamilyplan']) { ?>
								<div class="col-sm-3">
									<label class="inline">Family Plan:</label>
									<span>Yes</span>
								</div>
								<?php } ?>
								<?php if ($plan ['stable_condition'] == 1) { ?>
								<div class="col-sm-3">
									<label class="inline">With stable pre-existion condition coverage</label>
								</div>
								<?php } else if ($plan ['stable_condition'] == 2) { ?>
								<div class="col-sm-3">
									<label class="inline">Without stable pre-existion condition coverage</label>
								</div>	
								<?php } ?>
								<?php if ($plan ['rate_options'] == 1) { ?>
								<div class="col-sm-3">
									<label class="inline">Rate Options Plus</label>
								</div>
								<?php } else if ($plan ['rate_options'] == 2) { ?>
								<div class="col-sm-3">
									<label class="inline">Rate Options Preferred</label>
								</div>	
								<?php } ?>
							</div>

							<div class="row">
								<div class="form-group col-sm-3">
									<label class="col-sm-12">Institution</label>
									<span><?php echo $institution; ?></span>
								</div>
								<div class="form-group col-sm-3">
									<label class="col-sm-12">Student ID</label>
									<span><?php echo $student_id; ?></span>
								</div>
								<div class="form-group col-sm-3">
									<label class="col-sm-12">Institution Phone</label>
									<span><?php echo $institution_phone; ?></span>
								</div>
								<div class="form-group col-sm-3">
									<label class="col-sm-12">Institution Fax</label>
									<span><?php echo $institution_fax; ?></span>
								</div>
								<div class="form-group col-sm-12">
									<label class="col-sm-12">Institution Address</label>
									<span><?php echo $institution_addr; ?></span>
								</div>
							</div>
<!-- Special parts end -->