<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
?>

		<div class="row">
			<div class="col-sm-8 col-sm-offset-2">
				<h2 class="text-center">Edit Information</h2>
				<div class="popForm">
					<form target="_blank" action='<?php echo $refundprint_url?>' method='post'>
			
					<div class="row">
						<div class="col-sm-12">
							<span style="width:110px;">Customer Name : </span><input type='text' name='customer_full_name' value='<?php echo !empty($firstname) ? $firstname : ''; ?>'><br />
						</div>
						<div class="col-sm-12">
							</br>
							<span style="width:110px;">Address : </span><input type='text' name='full_address' value='<?php echo !empty($suite_number)? $suite_number .' - '. $street_number .' , '. $street_name: $street_number .' , '. $street_name ; ?>'><br />
						</div>
						<div class="col-sm-12">
							</br>
							<span style="width:110px;">City: </span><input type='text' name='city' value='<?php echo $city; ?>'><br />
						</div>
						<div class="col-sm-12">
							</br>
							<span style="width:110px;">Province: </span><input type='text' name='province2' value='<?php echo $province2; ?>'><br />
						</div>
						<div class="col-sm-12">
							</br>
							<span style="width:110px;">Post Code: </span><input type='text' name='postcode' value='<?php echo $postcode; ?>'><br />
						</div>
					</div>
					<div class="row">	
						<div class="col-sm-12 text-center">
							<br />
							<input type='submit' class="btn btn-info" value='Export PDF'>
						</div>
					</div>
					</form>
				</div>
			</div>
		</div>
		