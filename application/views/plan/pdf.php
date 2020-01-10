<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
?>
<html lang="en">
<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>PDF File</title>
	<?php echo $style;?>
</head>
<body>
	<header>
		<!--p class="rh">JF Group</p-->
	</header>
	<div class="container">	
		<div class="row">
<?php if ($withlogo) { ?>
			<?php if (empty($user['pdf_logo']) || !in_array($plan['product_short'], $pdf_enable)) { ?>
			<div style="float:left;width:90px;">
				<img class="img-responsive" style="width:80px;" src="<?php echo base_url();?>image/jf_logo.jpg" />
			</div>
			<?php } else { ?>
			<div style="float:left;width:90px;">
				<img class="img-responsive" style="width:80px;" src="<?php echo base_url('agent/img') . '/' . $user['pdf_logo']; ?>" />
			</div>
			<?php } ?>
<?php if (($plan['product_short'] == 'JFR') || ($plan['product_short'] == 'TOP')) { ?>			
			<div style="float:right;width:200px;">
				<img class="img-responsive" style="width:80px;padding-bottom:-30px;" src="<?php echo base_url();?>image/Berkley.png" />
			</div>
<?php } ?>
			<div style="float:left;width:400px;">
<?php if (($plan['product_short'] == 'NUS') || ($plan['product_short'] == 'JUS')) { ?>			
				<p class="topp" style="font-weight:bold;"><span style="text-transform: capitalize;font-weight:bold;">HK Leung</span></p>
				<p class="topp">JF Insurance Agency Group Inc.</p>
				<p class="topp">939 Arcadia Ave, #R, Arcadia, CA91007</p>
				<p class="topp">Tel: 1-877-832-5541</p>
<?php } else { ?>			
<?php if ($user['user_group_id'] > 100) { ?>			
				<p class="topp" style="font-weight:bold;"><?php echo empty($user['business']) ? 'JF Agent' : htmlspecialchars($user['business']); ?> - <span style="text-transform: capitalize;font-weight:bold;"><?php echo ($user) ? htmlspecialchars($user['firstname'] . " " . $user['lastname']) : ''; ?></span></p>
				<p class="topp"><?php echo ($user)? htmlspecialchars($user['address'] . ', ' . $user['city'] . ' ' . $user['province2'] . ' ' . $user['postcode']): ''; ?></p>
				<p class="topp"><?php echo ($user)? htmlspecialchars($user['business_phone']) : '' ; ?></p>
				<?php if (!empty($user['website'])) { ?>
				<p class="topp"><?php echo htmlspecialchars($user['website']); ?></p>
				<?php } ?>
<?php } else { ?>			
				<p class="topp" style="font-weight:bold;"> JF Agent - <span style="text-transform: capitalize;font-weight:bold;">Johnson Fu</span></p>
				<p class="topp"><?php echo ($user)? htmlspecialchars($user['address'] . ', ' . $user['city'] . ' ' . $user['province2'] . ' ' . $user['postcode']): ''; ?></p>
				<p class="topp"><?php echo ($user)? htmlspecialchars($user['business_phone']) : '' ; ?></p>
<?php } ?>			
<?php } ?>			
			</div>
<?php } ?>
		</div>
		<div class="row">
			<div class="col-sm-12 text-right">
				<h2 style="margin:-15px 0 0;"><?php if ($plan['status_id'] < 2) { ?>Quote<?php } else {?>Confirmation<?php } ?> of Insurance</h2>
			</div>
		</div>
		
		<!--div class="row">
			<h4 class="col-sm-6 nopadding"><?php if ($plan['status_id'] < 2) { ?>Quote<?php } else {?>Policy<?php } ?> Number: <span><?php echo $plan['policy']; ?></span></h4>
			<h4 class="col-sm-6 nopadding" style="margin-top:0px;">Policy Status: <?php echo $status_list[$plan['status_id']]['name']; ?></h4>
		</div-->
		<!--div>
			<div class="p-detail"-->
				<!-- policy detail -->
				<div class="row" style="margin-top: -15px;">
					<div class="col-sm-6 nopadding">
						<h4 style="margin-bottom:15px;"><u>Policy Details</u></h4>
						<h4>Policy Holder: <span><?php echo htmlspecialchars($customer['firstname'] . " " . $customer['lastname']); ?></span></h4>
						<h4>Date of Birth: <span><?php echo $customer['birthday'];?></sapn> </h4>
						<h4>Address: <span><?php if(!empty($plan['suite_number'])){echo  htmlspecialchars($plan['suite_number']) . "- ";} ?><?php echo htmlspecialchars($plan['street_number'] . ' ' . $plan['street_name'] . ' ' . $plan['city'] . ', ' . $plan['province2'] . ' ' . $plan['postcode']); ?></sapn> </h4>
						<h4>Phone Number: <span><?php echo htmlspecialchars($plan['phone1']);?></sapn> </h4>
						<h4>Email: <span><?php echo htmlspecialchars($plan['contact_email']);?></sapn> </h4>
					</div>
					<div class="col-sm-6 nopadding">
						<h4 style="margin-bottom:15px;">&nbsp;&nbsp;</h4>
						<h4><?php if ($plan['status_id'] < 2) { ?>Quote<?php } else {?>Policy<?php } ?> Number: <span><?php echo $plan['policy']; ?></span></h4>
						<h4>Application Date: <span><?php echo $plan['apply_date'];?></sapn> </h4>
						<h4>Effective Date: <span><?php echo $plan['effective_date']; ?></sapn> </h4>
						<h4>Expiry Date: <span><?php echo $plan['expiry_date']; ?></sapn> </h4>
						<h4>Number of Days: <span><?php echo $plan['totaldays'];?></sapn> </h4>
					</div>
				</div>
				<!-- end policy detail -->
				<hr style="margin:3px auto;" />

				<!-- Coverage and payment Details-->
				<div class="row">
					<div class="col-sm-6 nopadding">
						<h4 style="margin-bottom:15px;"><u>Coverage Details</u></h4>
<?php if ($plan['product_short'] == 'TOP') { ?>
						<h4>
							JF Canadian Travel Out Plan:
							<br />&nbsp;&nbsp;&nbsp;<span><?php echo $toppackagename[$plan['package']]; ?></span>
							<?php if ($plan['package'] == 'all_inclusive') { ?>
							<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span>Medical : $10,000,000</span>
							<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span>Baggage : $1,000</span>
							<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span>AD&D : $50,000</span>
							<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span>Flight Accident: $100,000</span>
							<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span>Trip Cancellation and Interruption: $<?php echo number_format($plan['sum_insured'], 2); ?></span>
							<?php if ($plan['free_cancel']) { ?>
							<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span>Cancel trip for any reason</span>
							<?php } ?>
							<?php } else if (($plan['package'] == 'single_medical_plan') || ($plan['package'] == 'optional_plan')) { ?>
							<?php     if ($plan['package'] == 'single_medical_plan') { ?>
							<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span>Sum Insured: $10,000,000</span>
							<?php     } ?>
							<?php     if ($plan['ad_and_d_ck']) { ?>
							<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span>AD & D : $<?php echo number_format($plan['ad_and_d_insured'], 2); ?></span>
							<?php     } ?>
							<?php     if ($plan['flight_accident_ck']) { ?>
							<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span>Flight Accident : $<?php echo number_format($plan['flight_accident_insured'], 2); ?></span>
							<?php     } ?>
							<?php     if ($plan['trip_cancellation_ck']) { ?>
							<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span>Trip Cancellation and Interruption : $<?php echo number_format($plan['trip_cancellation_insured'], 2); ?></span>
							<?php     } ?>
							<?php } else if ($plan['package'] == 'annual_plan') { ?>
							<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span>Selected days : <?php echo $plan['annual_plan_days']; ?></span>
							<?php } ?>
							<?php if ($plan['stable_condition']) { ?>
							<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span><?php echo ($plan['stable_condition'] == 1) ? 'Including' : 'Excluding'; ?> stable pre-existing condition coverage</span>
							<?php } ?>
							<?php if ($plan['questionnaire'] > 0) { ?>
							<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span>Your rate table is Table<?php echo $plan['questionnaire']; ?></span> 
							<?php if (0) { ?>
							<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span>With Questionnaire answers</span> 
							<?php if ($plan['question1_lung'] || $plan['question1_diabets'] || $plan['question1_heart']) { ?>
							<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span>Question 1 : 
								<?php if ($plan['question1_lung']) { ?>
									<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Lung conditions/disease (include asthma) : take <?php echo $plan['question1_lung']; ?> medication(s)
								<?php } ?>
								<?php if ($plan['question1_diabets']) { ?>
									<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Diabetes : take <?php echo $plan['question1_diabets']; ?> medication(s)
								<?php } ?>
								<?php if ($plan['question1_heart']) { ?>
									<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Heart conditions/disease (do not include aspirin, hypertension (high blood pressure) or high cholesterol medications) : take <?php echo $plan['question1_heart']; ?> medication(s)
								<?php } ?>
								</span>
							<?php } ?>
							<?php if ($plan['question2']) { ?>
							<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span>Question 2 : 
								<?php if ($plan['question2']) { ?>
								<?php     if ($plan['question2'] == 2) { ?> Yes
								<?php     } else  { ?> No <?php } ?> 
								<?php } ?>
								</span>
							<?php if ($plan['question3']) { ?>
							<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span>Question 3 : 
								<?php if ($plan['question3_bowel'] == 'Y') { ?>
									<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Bowel obstruction including bleeding and inflammation : yes
								<?php } ?>
								<?php if ($plan['question3_cancer'] == 'Y') { ?>
									<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Cancer : yes
								<?php } ?>
								<?php if ($plan['question3_diabetes'] == 'Y') { ?>
									<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Diabetes (controlled by medication or diet) : yes	
								<?php } ?>
								<?php if ($plan['question3_diverticu'] == 'Y') { ?>
									<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Diverticulitis/Diverticulosis : yes
								<?php } ?>
								<?php if ($plan['question3_gerd'] == 'Y') { ?>
									<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;GERD (gastro-esophageal reflux disease) : yes
								<?php } ?>
								<?php if ($plan['question3_heart'] == 'Y') { ?>
									<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Heart conditions/disease (include aspirin) : yes
								<?php } ?>
								<?php if ($plan['question3_hyper'] == 'Y') { ?>
									<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Hypertension : yes
								<?php } ?>
								<?php if ($plan['question3_kidney'] == 'Y') { ?>
									<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Kidney disease : yes
								<?php } ?>
								<?php if ($plan['question3_lung'] == 'Y') { ?>
									<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Lung conditions/disease (include asthma) : yes
								<?php } ?>
								<?php if ($plan['question3_peptic'] == 'Y') { ?>
									<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Peptic ulcer : yes
								<?php } ?>
								</span>
							<?php if ($plan['question4']) { ?>
							<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span>Question 4 : 
								<?php if ($plan['question4']) { ?>
								<?php     if ($plan['question4'] == 2) { ?> Yes
								<?php     } else  { ?> No <?php } ?> 
								<?php } ?>
								</span>
							<?php if ($plan['question5']) { ?>
							<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span>Question 5 : 
								<?php if ($plan['question5']) { ?>
								<?php     if ($plan['question2'] == 2) { ?> Yes
								<?php     } else  { ?> No <?php } ?> 
								<?php } ?>
								</span>
							<?php } // question5 ?>
							<?php } // question4 ?>
							<?php } // question3 ?>
							<?php } // question2 ?>
							<?php } // if (0) ?>
							<?php } // questionnaire ?>
						</h4>
<?php } else { // else TOP ?>
<?php if (in_array($plan['product_short'], $pdf_enable) && !empty($customer_product_name)) { ?>
						<h4>Insurance Plan:<br />&nbsp;&nbsp;&nbsp;<span><?php echo $customer_product_name;?></span></h4>
<?php } else { ?>
<?php if (($plan['product_short'] == 'JES') && $plan['holiday_rate']) { ?>
						<h4>Insurance Plan:<br />&nbsp;&nbsp;&nbsp;<span>JF Elite Plus to Canada</span></h4>
<?php } else { ?>
						<h4>Insurance Plan:<br />&nbsp;&nbsp;&nbsp;<span><?php echo $plan_full_name;?></span></h4>
<?php } ?>
<?php } ?>
						<h4>Plan Type: <span><?php if($plan['isfamilyplan']==1){ echo "Family";}else{echo "Individual";} ?></sapn> </h4>
						<?php if ($plan['sum_insured']) { ?><h4>Sum Insured: <span>$<?php echo number_format($plan['sum_insured'], 2); ?></sapn> </h4><?php } ?>
						<?php if ($plan['deductible_amount'] || ($plan['product_short'] == 'OPL') || ($plan['product_short'] == 'JFR')) { ?><h4>Deductible: <span>$<?php echo number_format($plan['deductible_amount'], 2);?></sapn> </h4><?php } ?>
						<h4>Beneficiary: <span><?php echo htmlspecialchars($plan['beneficiary']);?></sapn> </h4>
<?php } // end if TOP ?>
					</div>
					<div class="col-sm-6 nopadding">
<?php if ($withprice) { ?>
						<h4 style="margin-bottom:15px;"><u>Payment Details</u></h4>
						<h4>Total Premium: <span>$<?php echo number_format($plan['premium'], 2, '.', ',');?></span></h4>
						<h4>Premium: <span>$<?php echo number_format((float)$plan['premium'] - (float)$plan['tax'], 2, '.', ','); ?></sapn> </h4>
						<h4>Tax: <span>$<?php echo number_format($plan['tax'], 2, '.', ','); ?></sapn> </h4>
						<h4>Payment Date: <span><?php echo (($plan['status_id'] >= 2) && isset($payment['added'])) ? date('Y-m-d', strtotime($payment['added'])) : ''; ?></sapn> </h4>
						<h4>Payment Method: <span><?php echo (($plan['status_id'] >= 2) && isset($payment['pay_mothed'])) ? $payment['pay_mothed'] : ''; ?></sapn> </h4>
<?php } ?>
					</div>
				</div>

				<?php if ($plan['isfamilyplan']) { ?>
				<div class="row">
					<div class="col-sm-12" style="padding:0;">	
						<p><u><?php echo ($plan['isfamilyplan'] == 1) ? 'Family' : 'Group'; ?>Members</u>
<?php if (($plan['product_short'] == 'JFR') || ($plan['product_short'] == 'OPL')) { ?>
						&nbsp;&nbsp;&nbsp;&nbsp; ( Coverage is per person per trip )
<?php } ?>
						</p>
					</div>
				</div>
					
				<div class="row" style="margin-top: -35px;margin-bottom: 0">
					<?php for ($i = 0; $i < sizeof($customers); $i++) { ?>
						<?php if (empty($customers[$i]['lastname']) && empty($customers[$i]['firstname'])) continue; ?>
						<div class="col-sm-4" style="padding:0;">
							<p style="margin-bottom: 0;"><span><?php echo htmlspecialchars($customers[$i]['firstname']  . " " . $customers[$i]['lastname']); ?></span>&nbsp;&nbsp;&nbsp;&nbsp;<span><?php echo $customers[$i]['birthday']; ?></span></p>
						</div>
						
					<?php } ?>
				</div>
					
				<?php } ?>
				<!-- End Family Member -->
		<div class="row">
			<div class="col-sm-12 nopm special-note">
				<h4 style="border-bottom:1px solid #777;">Special Note</h4>
			</div>
		</div>
		<?php echo $special_note;?>

			<!--/div--><!-- end p-detail -->
		<!--/div--><!-- x_content -->
		<div class="row">
			<div class="col-sm-12 nopm">
				<p class="small">If you notice any errors in the above information or have any questions, please contact <?php if (in_array($plan['product_short'], $pdf_enable) && !empty($user['business'])) { echo htmlspecialchars($user['business']); } else { ?>JF Insurance Agency Group Inc<?php } ?>.</p>
			</div>
			<div class="col-sm-4 nopm">
			<?php if (in_array($plan['product_short'], $pdf_enable)) { ?>
				<p class="small">
				<?php echo $user['pdf_f_left1']; ?><br />
				<?php echo $user['pdf_f_left2']; ?><br />
				<?php echo $user['pdf_f_left3']; ?><br />
				<?php echo $user['pdf_f_left4']; ?><br />
				<?php echo $user['pdf_f_left5']; ?><br />
				<?php echo $user['pdf_f_left6']; ?><br />
				</p>
			<?php } else { ?>
				<p class="small">Ontario:<br />
				15 Wertheim Court, Suite 501,<br />
				Richmond Hill, ON, Canada L4B 3H7<br />
				Phone: 905-707-1512 Or 1-877-832-5541</p>
			<?php } ?>
			</div>
			<div class="col-sm-2 nopm">
			<?php if (!empty($user['pdf_qr']) && in_array($plan['product_short'], $pdf_enable)) { ?>
				<img class="img-responsive" style="width:60px;padding-top:10px;" src="<?php echo base_url('agent/img') . '/' . $user['pdf_qr']; ?>" />
			<?php } ?>
			</div>
			<div class="col-sm-4 nopm">
			<?php if (in_array($plan['product_short'], $pdf_enable)) { ?>
				<p class="small">
				<?php echo $user['pdf_f_right1']; ?><br />
				<?php echo $user['pdf_f_right2']; ?><br />
				<?php echo $user['pdf_f_right3']; ?><br />
				<?php echo $user['pdf_f_right4']; ?><br />
				<?php echo $user['pdf_f_right5']; ?><br />
				<?php echo $user['pdf_f_right6']; ?><br />
				</p>
			<?php } else { ?>
				<p class="small">British Columbia:<br />
				128 - 6061 No. 3 Road<br />
				Richmond, BC, Canadian V6Y 282<br />
				Phone: 604-232-0896 Or 1-877-232-0896</p>
			<?php } ?>
			</div>
			<div class="col-sm-2 nopm">
			<?php if (!empty($user['pdf_qr2']) && in_array($plan['product_short'], $pdf_enable)) { ?>
				<img class="img-responsive" style="width:60px;padding-top:10px;" src="<?php echo base_url('agent/img') . '/' . $user['pdf_qr2']; ?>" />
			<?php } ?>
			</div>
		</div>
	</div><!-- End Container -->
</body>
</html>
