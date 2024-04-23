<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
?>
<!-- Special parts -->
							<div class="row">
								<div class="col-sm-6">
									<label class="inline"><?php echo $this->lang->line("Beneficiary"); ?>:</label>
									<span><?php echo htmlspecialchars($plan['beneficiary']); ?></span>
								</div>
								<?php if ($plan['isfamilyplan']) { ?>
								<div class="col-sm-6">
									<label class="inline"><?php echo $this->lang->line("Family Plan"); ?>:</label>
									<span><?php echo $this->lang->line("Yes"); ?></span>
								</div>
								<?php } ?>
								<div class="col-sm-6">
									<label class="inline"><?php echo $this->lang->line("Stable pre-existing condition coverage"); ?>:</label>
									<span><?php echo $this->lang->line("Yes"); ?></span>
								</div>
							</div>
<!-- Special parts end -->