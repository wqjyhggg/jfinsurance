<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<!-- Content top navigation -->
<div class="top_nav">
	<div class="nav_menu">
		<nav class="" role="navigation">
			<div class="nav toggle">
				<a id="menu_toggle"><i class="fa fa-bars"></i></a>
			</div>
		</nav>
	</div>
</div>
<!-- Content top navigation End-->

<!-- page content -->
<div class="right_col" role="main">
	<div class="main-div">
		<div class="page-title">
			<div class="title_left">
				<h3><?php echo $title_txt; ?></h3>
			</div>
		</div>
		<div class="clearfix"></div>
		<!-- Filter Section -->
		<?php if (isset($errormsg) && $errormsg) { ?>
		<div class="alert-error"><?php echo $errormsg; ?></div>
		<?php } ?>
		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="x_panel">
					<div class="x_content">
						<form method="post" action="<?=$action_url ?>" class="form-inline">
							<input type='hidden' name='<?php echo $csrf['name']; ?>' value='<?php echo $csrf['value']; ?>'>
							<div class="row">
                <?php if ($beuser['user_group_id'] < 100) { ?>
								<div class="form-group col-sm-4">
                  <label for="agent_id">Agent:</label>
									<input type="text" name="agent_id" id="agent_id" value="<?php echo $agent_id; ?>" class="form-control">
								</div>
                <?php } ?>
								<div class="form-group col-sm-4">
                  <label for="year"><?php echo $this->lang->line("Year"); ?>:</label>
                  <select class="form-control" name="year" id="year">
                    <?php for ($y = "2018"; $y <= date("Y"); $y++) { ?>
                      <option value="<?php echo $y; ?>" <?php if ($year == $y) {?>selected<?php } ?>><?php echo $y; ?></option>
                    <?php } ?>
                  </select>
								</div>
								<!-- submit button -->
								<div class="form-group col-sm-4">
									<button class="btn btn-primary"><?php echo $this->lang->line("Submit"); ?></button>
								</div>
								<!-- submit button -->
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
		<!-- End Filter Section -->
		<!-- List Section -->
		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="x_panel">
					<div class="x_title">
            <div class="col-sm-12">
  						<h2>Report Data</h2>
              <?php if (!empty($record)) { ?>
								<a href="<?php echo $export_url; ?>" class="btn btn-primary pull-right mr-6" target="_blank"><?php echo $this->lang->line("Export"); ?></a>
              <?php } ?>
						</div>
						<div class="clearfix"></div>
					</div>
					<?php if (!empty($record)) { ?>
					<form method="post" action="<?=$action_url ?>" class="form-horizontal">
						<input type='hidden' name='<?php echo $csrf['name']; ?>' value='<?php echo $csrf['value']; ?>'>
						<input type='hidden' name='agent_id' value='<?php echo $agent_id; ?>'>
						<table class="table table-hover">
							<thead>
								<tr>
									<th>
                  <?php if ($beuser['user_group_id'] < 100) { ?>
                    <?php echo $this->lang->line("Month"); ?>
                  <?php } ?>
                  </th>
									<th><?php echo $this->lang->line("Premium"); ?></th>
                  <?php if ($beuser['user_group_id'] < 100) { ?>
									<th><?php echo $this->lang->line("Premium Copy"); ?></th>
                  <?php } ?>
									<th><?php echo $this->lang->line("Commission"); ?></th>
                  <?php if ($beuser['user_group_id'] < 100) { ?>
									<th><?php echo $this->lang->line("Commission Copy"); ?></th>
                  <?php } ?>
								</tr>
							</thead>
							<tbody>
                <?php $total_premium = 0; $total_commission = 0; ?>
                <?php for ($i = 1; $i <= 12; $i++) { ?>
                <?php $total_premium += floatval($record['premium2'][$i]); $total_commission += floatval($record['commission2'][$i]); ?>
                <?php if ($beuser['user_group_id'] < 100) { ?>
                <tr>
									<td><?php echo date('F', mktime(0, 0, 0, $i, 10)); ?></td>
                  <?php if ($beuser['user_group_id'] < 100) { ?>
									<td>
										<?php echo $record['premium'][$i]; ?>
									</td>
                  <?php } ?>
									<td>
										<input type="text" name="premium2[<?php echo $i; ?>]" value="<?php echo $record['premium2'][$i]; ?>">
									</td>
                  <?php if ($beuser['user_group_id'] < 100) { ?>
									<td>
										<?php echo $record['commission'][$i]; ?>
									</td>
                  <?php } ?>
									<td>
										<input type="text" name="commission2[<?php echo $i; ?>]" value="<?php echo $record['commission2'][$i]; ?>">
									</td>
								</tr>
                <?php } ?>
                <?php } ?>
								<tr>
									<td><b><?php echo $this->lang->line("Total"); ?></b></td>
                  <?php if ($beuser['user_group_id'] < 100) { ?>
									<td>
										&nbsp;
									</td>
                  <?php } ?>
									<td>
										<div id="premium2"><?php echo $total_premium; ?></div>
									</td>
                  <?php if ($beuser['user_group_id'] < 100) { ?>
									<td>
										&nbsp;
									</td>
                  <?php } ?>
									<td>
										<div id="commission2"><?php echo $total_commission; ?></div>
									</td>
								</tr>
							</tbody>
						</table>
						<div class="row">
              <div class="col-sm-6">
              </div>
              <?php if ($beuser['user_group_id'] < 100) { ?>
                <div class="col-sm-3">
                  <input type='submit' name='update' value='Update' class="btn btn-primary">
                </div>
              <?php } ?>
              <div class="col-sm-3">
                <a href="<?php echo $export_url; ?>" class="btn btn-primary" target="_blank"><?php echo $this->lang->line("Export"); ?></a>
              </div>
						</div>
					</form>
					<?php } else { ?>
            <?php if ($beuser['user_group_id'] < 100) { ?>
              Please select agent
            <?php } else { ?>
              The report is not available for selected year.   Pls contact Johnson Insurance for more details
            <?php } ?>
					<?php } ?>
				</div>
			</div>
		</div>
		<!-- End List Section -->
	</div>
</div>
<!-- /page content -->
<script>
function sum_premium (name) {
	var sum = 0;
	$('input[name^="'+name+'"]').each(function() {
    let v = $(this).val();
    if (v && !isNaN(v)) {
		  sum += parseFloat(v);
    }
	});
	$('#'+name).html(parseFloat(sum).toFixed(2));
}

$( document ).ready(function() {
	<?php if ($beuser['user_group_id'] > 100) { ?>
	$("input[type=text]").prop('disabled', true);
	<?php } else { ?>
	$('input[name^="premium2"]').change(function() {
		sum_premium('premium2');
	});
	$('input[name^="commission2"]').change(function() {
		sum_premium('commission2');
	});
	sum_premium('premium2');
	sum_premium('commission2');
	<?php } ?>
});
</script>
