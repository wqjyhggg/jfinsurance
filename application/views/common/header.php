<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title><?php echo isset($title_txt) ? $title_txt : "JF Group"; ?></title>
<!--
<link rel="stylesheet" href="<?php echo base_url();?>main.css">
<link rel="stylesheet" href="<?php echo base_url();?>bootstrap/dist/css/bootstrap.css">
-->
<link rel="stylesheet" href="<?php echo base_url();?>bootstrap/dist/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
<script src="<?php echo base_url();?>bootstrap/dist/js/bootstrap.min.js"></script>
<script src="<?php echo base_url();?>js/jquery-3.0.0.min.js"></script>


</head>
<body>

<header>
	<div class="container">
		<div class="hlogo">
			<img src="<?php echo base_url();?>image/logo.jpg" alt="JF Insurance">
		</div>
		<nav class="navbar navbar-default">

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
		      <ul class="nav navbar-nav">
		        <li class="active"><a href="#">Home</a></li>
		        <li><a href="#">ABOUT</a></li>
		        <li><a href="#">PRODUCTS</a></li>
		        <li><a href="#">PARTNERS</a></li>
		        <li><a href="#">DOWNLOADS</a></li>
		        <li><a href="#">CAREER</a></li>
		        <li><a href="#">CONTACT</a></li>
		        <li><a href="#">Language</a></li>
		        <li><a href="#">AGENT LOGIN</a></li>
		        <!--li class="dropdown">
		          <a class="dropdown-toggle" data-toggle="dropdown" href="#"></a>
		          <ul class="dropdown-menu">
		            <li><a href="#">Page 1-1</a></li>
		            <li><a href="#">Page 1-2</a></li>
		            <li><a href="#">Page 1-3</a></li>
		          </ul>
		        </li-->
		        
		      </ul>
		      <!--ul class="nav navbar-nav navbar-right">
		        <li><a href="#"><span class="glyphicon glyphicon-user"></span> Sign Up</a></li>
		        <li><a href="#"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
		      </ul-->
		    </div>
		  </div>
		</nav>
	</div>
</header>
