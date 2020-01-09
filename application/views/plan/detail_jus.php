<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
?>
<!-- Special parts -->
							<div class="row">
								<div class="col-sm-3">
									<label class="inline">Beneficiary:</label>
									<span><?php echo htmlspecialchars($plan['beneficiary']); ?></span>
								</div>
								<div class="col-sm-3">
									<label class="inline">Sum Insured (USD):</label>
									<span>Unlimited</span>
								</div>
								<div class="col-sm-3">
									<label class="inline">Deductible amount (USD):</label>
									<span>$<?php echo number_format($plan['deductible_amount'], 2, '.', ','); ?></span>
								</div>
								<?php if ($plan['isfamilyplan']) { ?>
								<div class="col-sm-3">
									<label class="inline">Family Plan:</label>
									<span>Yes</span>
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
<!-- Special parts end -->