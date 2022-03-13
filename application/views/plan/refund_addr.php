<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
?>

		<div class="row">
			<div class="col-sm-8 col-sm-offset-2">
				<h2 class="text-center">Edit Information</h2>
				<div class="popForm">
					<form id="popRform" target="_blank" action='<?php echo $refund_letter_url; ?>' method='post'>
						<input type='hidden' name='<?php echo $csrf['name']; ?>' value='<?php echo $csrf['value']; ?>'>
					<div class="row">
						<div class="col-sm-12">
							<span style="width:110px;">Customer Name : </span><input type='text' name='customer_full_name' value="<?php echo !empty($firstname . ' ' . $lastname) ? $html_model->escapeQuote2($firstname . ' ' . $lastname) : ''; ?>"><br />
						</div>
						<div class="col-sm-12">
							<br>
							<span style="width:110px;">Address : </span><input type='text' name='full_address' value="<?php echo !empty($suite_number)? "Suite " . $html_model->escapeQuote2($suite_number .' '. $street_number .' , '. $street_name) : $html_model->escapeQuote2($street_number .' , '. $street_name); ?>"><br />
						</div>
						<div class="col-sm-12">
							<br>
							<span style="width:110px;">City: </span><input type='text' name='city' value="<?php echo $html_model->escapeQuote2($city); ?>"><br />
						</div>
						<div class="col-sm-12">
							<br>
							<span style="width:110px;">Province: </span><input type='text' name='province2' value="<?php echo $html_model->escapeQuote2($province2); ?>"><br />
						</div>
						<div class="col-sm-12">
							<br>
							<span style="width:110px;">Post Code: </span><input type='text' name='postcode' value="<?php echo $html_model->escapeQuote2($postcode); ?>"><br />
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
		

		<script>
			$('#popRform').submit(function(event){
				var oldCookie = document.cookie;

			     var cookiePoll = setInterval(function() {
			         if(oldCookie != document.cookie) {
			             // stop polling
			             clearInterval(cookiePoll);

			             // assuming a login happened, reload page
			             window.location.reload();
			         }
			     },1000); // check every second

			     $('#popRdiv').attr('style','display:none;');
			});

		</script>