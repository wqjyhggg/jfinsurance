<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
?>
<!-- Special parts -->
							<div class="row">
								<div class="col-sm-6">
									<label class="inline">Beneficiary:</label>
									<span><?php echo $plan['beneficiary']; ?>cccccc</span>
								</div>
								<?php if ($plan['isfamilyplan'] == 1) { ?>
								<div class="col-sm-6">
									<label class="inline">Family Plan:</label>
									<span>Yes</span>
								</div>
								<?php } else if ($plan['isfamilyplan'] == 2) { ?>
								<div class="col-sm-6">
									<label class="inline">Group Plan:</label>
									<span>Yes</span>
								</div>
								<?php } ?>
							</div>
<!-- Special parts end -->