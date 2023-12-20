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

<link rel="stylesheet" href="<?php echo base_url();?>stylesheet/datetimepicker/css/bootstrap-datepicker.css">

<!-- font-awesome style -->
<link rel="stylesheet" href="<?php echo base_url();?>stylesheet/font-awesome/css/font-awesome.min.css" >
<!-- customize template style -->
<link rel="stylesheet" href="<?php echo base_url();?>stylesheet/iCheck/skins/flat/green.css" >
<link rel="stylesheet" href="<?php echo base_url();?>stylesheet/google-code-prettify/bin/prettify.min.css" >
<link rel="stylesheet" href="<?php echo base_url();?>stylesheet/select2/dist/css/select2.min.css" >
<link rel="stylesheet" href="<?php echo base_url();?>stylesheet/switchery/dist/switchery.min.css" >
<link rel="stylesheet" href="<?php echo base_url();?>stylesheet/starrr/dist/starrr.css" >
<!-- Build theme style -->
<link rel="stylesheet" href="<?php echo base_url();?>build/css/custom.min.css">
 <!-- jQuery -->
<script src="<?php echo base_url();?>stylesheet/jquery/dist/jquery.min.js"></script>
<script src="<?php echo base_url();?>stylesheet/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="<?php echo base_url();?>stylesheet/datetimepicker/js/bootstrap-datetimepicker.js"></script>
<script>
$( document ).ready(function() {
	$("#select_lang").on('change', function (){
		var url = '<?php echo base_url();?>lang/' + $("#select_lang").val();
		window.location.href=url;
	});
});
</script>

</head>
<body class="nav-md">

<header>
	<div class="container" style="padding:0;">
		<div class="hlogo">
<?php if (!empty($myhomelogo)) { ?>
			<img class="img-responsive" src="<?php echo $myhomelogo; ?>" alt="JF Insurance">
<?php } else { ?>
			<img class="img-responsive" src="<?php echo base_url();?>image/logo.png" alt="JF Insurance">
<?php } ?>
<?php $uri = explode("?", $_SERVER['REQUEST_URI']); ?>
<?php if (0 && ($uri[0] == '/')) { ?>
			<span class="pull-right" style="margin-top: -2em; margin-right: 2em;">
				<select id='select_lang' onchange="window.location.href='<?php echo base_url();?>lang/' + $(this).val()" >
					<option value='english' <?php if ($language == 'english') { echo "SELECTED"; } ?>><?php echo $lang['txt_english']?></option>
					<option value='french' <?php if ($language == 'french') { echo "SELECTED"; } ?>><?php echo $lang['txt_french']?></option>
					<!-- <option value='chinese' <?php if ($language == 'chinese') { echo "SELECTED"; } ?>><?php echo $lang['txt_chinese']?></option>
					<option value='japanese' <?php if ($language == 'japanese') { echo "SELECTED"; } ?>><?php echo $lang['txt_japanese']?></option>
					<option value='korean' <?php if ($language == 'korean') { echo "SELECTED"; } ?>><?php echo $lang['txt_korean']?></option> -->
				</select>
			</span>
<?php } ?>
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
		    	<?php if (isset($top_menu) && isset($top_menu['menu']) && is_array($top_menu['menu'])) { ?>
					<ul class="nav navbar-nav">
					<?php foreach ($top_menu['menu'] as $tm) { ?>
					<li> <?php echo $tm; ?> </li>
					<?php } ?>
					</ul>
				<?php } ?>
		    	<!-- Top Menu End-->

		    	<?php if (isset($top_menu) && !empty($top_menu['islogin'])){ ?>
		    	<div class="col-sm-3 col-md-3 pull-right">
		            <form class="navbar-form" method='GET' action='<?php echo base_url('plan'); ?>'>
		                <div class="input-group" style="margin-bottom:0;">
		                    <input type="text" class="form-control" placeholder="Search" name="q">
		                    <div class="input-group-btn">
		                        <button class="btn btn-default" type="submit" style="margin-bottom:0;padding-bottom:5px;"><i class="glyphicon glyphicon-search"></i></button>
		                    </div>
		                </div>
		            </form>
		        </div> 
		        <?php } ?>
		    </div>
		  </div>
		</nav>
	</div>
</header>
