<?php
defined('BASEPATH') or exit('No direct script access allowed');
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
			<div>
				<img class="img-responsive" src="<?php echo base_url();?>image/logo.png" />
			</div>
		</div>
		<div class="row">
			<div>
				<br /> <br />
				<p class="topp"><?php echo $agent['mail_name']; ?></p>
				<br /> <br />
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12">
				<p class="topp">Dear Agent :</p>
				<p>This letter is to confirm your Total Sales and Total Commission earned with JF Insurance Agency Group Inc. in <?php echo $year; ?>. They are as follows : </p>
			</div>
				<p><b>Total Sale : </b><span> $ <?php echo number_format($premium, 2);?></span></p>
				<p><b>Commission Earned : </b><span> $ <?php echo number_format($commission, 2); ?></span></p>
			</div>
			<br /> <br />
			<div class="col-sm-12">
				<p>Regards,</p>
				<p> </p>
				<p>Accounting Department</p>
				<p>JF Insurance Agency Group Inc.</p>
			</div>
		</div>

		<div class="row" style="padding-top: 10em">
			<div class="col-sm-12 text-center">
				<p class="small"><b>This is the annual commission statement; T4's or any other taxation statements are not issued by JF.<br /> Please retain this document for future purposes.</b></p>
			</div>
		</div>
		<div class="row" style="padding-top: 10em">
			<div class="col-sm-12">
				<p class="small">Head Office : 15 Wertheim Court, Suite 501, Richmond Hill, ON, Canada L4B 3H7 &nbsp;&nbsp;&nbsp; Phone: 905-707-1512 &nbsp;&nbsp;&nbsp; Fax: 905-707-1513</p>
				<p class="small">BC Office : 128 - 6061 No. 3 Road, Richmond, BC, Canadian V6Y 282 &nbsp;&nbsp;&nbsp; Phone: 604-232-0896 &nbsp;&nbsp;&nbsp; Fax: 604-232-0897</p>
			</div>
		</div>
	</div>
	<!-- End Container -->
</body>
</html>