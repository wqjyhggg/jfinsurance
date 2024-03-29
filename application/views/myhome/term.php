<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
// $show = true;
// $fYear = $this->lang->line('Year');
// if ($fYear != "Year") {
//   $show = false;
// }
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
<div class="right_col" role="main" style="margin-bottom: 40px;">
	<div class="main-div">
		<div class="page-title">
			<div class="title_left">
				<h3><?php echo $this->lang->line('Agreement to use MyHome service'); ?></h3>
			</div>
		</div>
		<div class="clearfix"></div>
		<!-- Term Section -->
		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="x_panel">
					<div class="x_title">
						<h2>
							<small></small>
						</h2>
						<div class="clearfix"></div>
					</div>
					<div class="x_content">
						<p><b><?php echo $this->lang->line('Qualify your client'); ?>:</b></p>
						<p><?php echo $this->lang->line("Determine if your client qualifies for coverage. Review the Policy's Eligibility criteria with your client"); ?>.</p>
						<hr />
						<p><b><?php echo $this->lang->line('Explain to your client'); ?>:</b></p>
						<p>
						<ul>
							<li><?php echo $this->lang->line('Stable pre-existing condition'); ?></li>
							<li><?php echo $this->lang->line('Investigations, consultations or treatments'); ?></li>
							<li><?php echo $this->lang->line('Claim procedure'); ?></li>
							<li><?php echo $this->lang->line('Policy Wording'); ?>:
								<p><?php echo $this->lang->line('Always review the policy with your client. Make sure to point out where they can find benefits, definitions and exclusions and recommend they read the policy as soon as possible – preferably, before the effective date. Defined terms are shown in bold italics. You can give your client a copy of the policy or email it to them directly from JF Insurance'); ?>.</p>
							</li>
								
							<li><?php echo $this->lang->line('CONFIRMATION OF COVERAGE'); ?>:
								<p><?php echo $this->lang->line('Always give your client a copy of the confirmation of coverage. Documents can be emailed to your client directly from JF Insurance'); ?></p>
							</li>
						</ul>
						</p>
					</div>
					<div class="col-md-12 col-sm-12 col-xs-12">
						<div class="col-md-6 col-sm-6 col-xs-6 text-center">
							<a href="<?php echo $no_url; ?>" class="btn btn-info" role="button"><?php echo $this->lang->line('Disagree'); ?></a>
						</div>
						<div class="col-md-6 col-sm-6 col-xs-6 text-center">
							<a href="<?php echo $yes_url; ?>" class="btn btn-info" role="button"><?php echo $this->lang->line('Agree'); ?></a>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- End Term -->
	</div>
</div>
<!-- /page content -->