<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title><?php echo isset($title_txt) ? $title_txt : "JF Group"; ?></title>

<link rel="stylesheet" href="<?php echo base_url();?>main.css">
<!-- bootstrap style -->
<link rel="stylesheet" href="<?php echo base_url();?>stylesheet/bootstrap/dist/css/bootstrap.css">
<link rel="stylesheet" href="<?php echo base_url();?>stylesheet/bootstrap/dist/css/bootstrap.min.css">

<link rel="stylesheet" href="<?php echo base_url();?>stylesheet/datetimepicker/css/bootstrap-datetimepicker.min.css">

<!-- font-awesome style -->
<link rel="stylesheet" href="<?php echo base_url();?>stylesheet/font-awesome/css/font-awesome.min.css" >
<!-- customize template style -->
<link rel="stylesheet" href="<?php echo base_url();?>stylesheet/iCheck/skins/flat/green.css" >
<link rel="stylesheet" href="<?php echo base_url();?>stylesheet/google-code-prettify/bin/prettify.min.css" >
<link rel="stylesheet" href="<?php echo base_url();?>stylesheet/select2/dist/css/select2.min.css" >
<link rel="stylesheet" href="<?php echo base_url();?>stylesheet/switchery/dist/switchery.min.css" >
<link rel="stylesheet" href="<?php echo base_url();?>stylesheet/starrr/dist/starrr.css" >
<!-- Build theme style -->
<link rel="stylesheet" href="<?php echo base_url();?>/build/css/custom.min.css">
 <!-- jQuery -->
<script src="<?php echo base_url();?>stylesheet/jquery/dist/jquery.min.js"></script>

</head>
<body class="nav-md">

<header>
	<div class="container" style="padding:0;">
		<div class="hlogo">
			<img class="img-responsive" src="<?php echo base_url();?>image/logo.jpg" alt="JF Insurance">
		</div>
		<nav class="navbar navbar-default">
		  <!--div class="col-sm-3 pull-left hidden-xs">
			<img class="img-responsive" src="<?php echo base_url();?>image/logo.jpg" alt="JF Insurance">
		  </div-->
		  <div class="container-fluid">
		    <div class="navbar-header">
		    	<div class="visible-xs">
			      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
			        <span class="icon-bar"></span>
			        <span class="icon-bar"></span>
			        <span class="icon-bar"></span>
			      </button>
			      <a class="navbar-brand" href="#">Menu</a>
			    </div>  
		    </div>
		    <div class="collapse navbar-collapse" id="myNavbar">

		    	<!-- Top Menu -->
		    	<?php if (isset($top_menu) && is_array($top_menu)) { ?>
					<ul class="nav navbar-nav">
					<?php foreach ($top_menu as $tm) { ?>
					<li> <?php echo $tm; ?> </li>
					<?php } ?>
					</ul>
				<?php } ?>
		    	<!-- Top Menu End-->
		    	<div class="col-sm-3 col-md-3 pull-right">
		            <form class="navbar-form" >
		                <div class="input-group" style="margin-bottom:0;">
		                    <input type="text" class="form-control" placeholder="Search" name="q">
		                    <div class="input-group-btn">
		                        <button class="btn btn-default" type="submit" style="margin-bottom:0;padding-bottom:5px;"><i class="glyphicon glyphicon-search"></i></button>
		                    </div>
		                </div>
		            </form>
		        </div> 
		    </div>
		  </div>
		</nav>
	</div>
</header>
