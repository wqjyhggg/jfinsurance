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
		<?php if ($beuser['user_group_id'] < 100) { ?>
		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="x_panel">
					<div class="x_title">
						<h2>Choose Agent</h2>
						<div class="clearfix"></div>
					</div>
					<div class="x_content">
						<form method="post" action="<?=$action_url ?>" class="form-horizontal">
							<input type='hidden' name='<?php echo $csrf['name']; ?>' value='<?php echo $csrf['value']; ?>'>
							<div class="row">
								<!-- Agent input box -->
								<div class="form-group col-sm-3">
									<label class="col-sm-12">Agent:</label>
									<div class="input-group col-sm-12">
										<select name="agent_id" class="form-control">
											<option value=0>Choose Agent</option>
											<?php foreach ($user_list as $agent) : ?>
											<option value="<?=$agent['user_id'] ?>" <?php if ($agent_id == $agent['user_id']) { echo "selected"; } ?>><?php echo $agent['username'] . " ( ". $agent['full_name'] . " )"; ?></option>
											<?php endforeach; ?>
										</select>
									</div>
								</div>
								<!-- Agent input box end -->
								<!-- submit button -->
								<div class="col-sm-3">
									<br />
									<button class="btn btn-primary pull-right">Submit</button>
								</div>
								<!-- submit button -->
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
		<!-- End Filter Section -->
		<?php } ?>
		<!-- List Section -->
		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="x_panel">
					<div class="x_title">
						<h2>File List</h2>
						<div class="clearfix"></div>
					</div>
					<?php if (isset($filelist) && $filelist) { ?>
					<div class="x_content">
						<table class="table table-hover">
							<thead>
								<tr>
									<th>Month</th>
									<th>File</th>
									<?php if ($beuser['user_group_id'] < 100) { ?>
									<th>&nbsp</th>
									<?php } ?>
								</tr>
							</thead>
							<tbody>
								<?php foreach ( $filelist as $key => $rc ) : ?>
								<tr>
									<td><?php echo $key; ?></td>
									<td>
										<?php if ($rc['has']) { ?>
										<a href='<?php echo base_url('pdf/download/'.$rc['filename']); ?>'><?php echo basename($rc['filename']); ?></a>
										<?php } ?>
									</td>
									<?php if ($beuser['user_group_id'] < 100) { ?>
									<td>
										<form method="post" enctype="multipart/form-data" action="<?php echo $upload_url; ?>">
											<input type="hidden" name="agent_id" value="<?php echo $agent_id;?>">
											<input type="hidden" name="key" value="<?php echo $key;?>">
											<span class="inline"><input type="file" name="file"></span>
											<span class="inline"><input type="submit" value="Upload" class="btn btn-primary"></span>
										</form>
									</td>
									<?php } ?>
								</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
					<?php } else { ?>
					Please select agent
					<?php } ?>
				</div>
			</div>
		</div>
		<!-- End List Section -->
	</div>
</div>
<!-- /page content -->
