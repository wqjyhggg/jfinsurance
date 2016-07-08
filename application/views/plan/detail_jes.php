<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
?>
<!-- Special parts -->
							<div class="row">
								<div class="col-sm-6">
									<label class="inline">Beneficiary:</label>
									<span><?php echo $plan['beneficiary']; ?></span>
								</div>
								<?php if ($plan['isfamilyplan']) { ?>
								<div class="col-sm-6">
									<label class="inline">Family Plan:</label>
									<span>Yes</span>
								</div>
								<?php } ?>
							</div>
<!-- Special parts end -->