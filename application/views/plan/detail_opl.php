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
								<div class="col-sm-6">
									<label class="inline">Family Plan:</label>
									<span>Yes</span>
								</div>
								<?php } ?>
								<?php if ($plan ['stable_condition'] == 1) { ?>
								<div class="col-sm-6">
									<label class="inline">With stable pre-existing condition coverage</label>
								</div>
								<?php } else if ($plan ['stable_condition'] == 2) { ?>
								<div class="col-sm-6">
									<label class="inline">Without stable pre-existing condition coverage</label>
								</div>	
								<?php } ?>

							</div>
<!-- Special parts end -->