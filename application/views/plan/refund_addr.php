<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
?>
<html lang="en">
<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Change information for PDF File</title>
</head>
<body>
	<header>
		<!--p class="rh">JF Group</p-->
	</header>
	<div class="container">	
		<div class="row">
			<div style="width:390px; margin:0 auto;">
				<div style="float:left;width:90px;">
					<img class="img-responsive" style="width:80px;" src="<?php echo base_url();?>image/jf_logo.png" />
				</div>
				<div style="float:left;width:300px;text-align:center;">
					<h3 style="margin-bottom:0;">JF Insurance Agency Group Inc.</h3>
					<h3 style="margin-top:0;">www.jfgroup.ca</h3>
				</div>
			</div>
		</div><br />
		<br />
		<br />
		<br />
		<br />
		<br />
		<br />
		<form action='<?php echo $refundprint_url?>' method='post'>
		<div class="row">
			<div class="col-sm-12 nopadding">
				Customer Name : <input type='text' name='customer_full_name' value='<?php echo $customer_full_name; ?>'><br />
				Address : <input type='text' name='full_address' value='<?php $full_address; ?>'><br />
				City: <input type='text' name='city' value='<?php echo $city; ?>'><br />
				Province: <input type='text' name='province2' value='<?php echo $province2; ?>'><br />
				Post Code: <input type='text' name='postcode' value='<?php echo $postcode; ?>'><br />
			</div>
		</div>
		<input type='submit' value='Export PDF'><br />
		</form>
	</div><!-- End Container -->
</body>
</html>