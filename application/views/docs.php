<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<!DOCTYPE html>
<html lang="utf-8">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?php echo isset($title_txt) ? $title_txt : "JF Group"; ?></title>

  <link rel="stylesheet" href="<?php echo base_url(); ?>main.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>stylesheet/bootstrap/dist/css/bootstrap.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>stylesheet/bootstrap/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>build/css/custom.min.css">
  <script src="<?php echo base_url(); ?>stylesheet/jquery/dist/jquery.min.js"></script>
  <script src="<?php echo base_url(); ?>stylesheet/bootstrap/dist/js/bootstrap.min.js"></script>
</head>

<body class="nav-md">
  <div class="container" style="padding:0;">
    <header>
      <div class="hlogo">
        <img class="img-responsive" src="<?php echo base_url(); ?>image/logo.png" alt="JF Insurance">
      </div>
      <nav class="navbar navbar-default">
        <div class="container-fluid">
          <div class="navbar-header">
          </div>
          <div class="collapse navbar-collapse" id="myNavbar">
            <ul class="nav navbar-nav">
              <li> </li>
            </ul>
          </div>
      </nav>
    </header>
    <div class="row">
      <div class="col">
        <?php if (empty($filelist)) { ?>
          <div class="login-form">
            <h3 class="login-title">Please input your Policy Number</h3>
            <?php if (!empty($error_message)) { ?>
              <div class="alert-error">
                <p><?php echo $error_message; ?></p>
                <br />
              </div>
            <?php } ?>
            <form action='<?php $action_url; ?>' method='POST'>
              <div class="form-group row">
                <input type='hidden' name='<?php echo $csrf['name']; ?>' value='<?php echo $csrf['value']; ?>'>
                <div class="col" style="width: 60%; margin:auto;">
                  <input type='text' class="form-control" name='policy' value='<?php echo $this->input->post('policy'); ?>'>
                </div>
              </div>
              <br /><br />
              <div class="form-group row">
                <div class="col-sm-12 pull-right">
                  <input class="btn btn-primary" style="padding:6px 25px;" type='submit' value="Submit">
                </div>
              </div>
            </form>
          </div>
        <?php } else { ?>
	  <div style="margin: auto; width: 60%; min-height: 540px;">
            <h3>Click file name to download file.</h3>
            <ul class="list-group">
            <?php foreach ($filelist as $file) { ?>
              <li class="list-group-item"><a href="<?php echo base_url('pdf/download/'.$file); ?>" target="_blank"><?php echo $file; ?></a</li>
            <?php } ?>
            </ul>
          </div>
        <?php } ?>
      </div>
    </div>
    <footer>
      <div class="row">
        <div class="col-sm-12 text-center">
          <p>Copyright &copy; 2009-<?php echo date("Y"); ?> JF Insurance Agency Group Inc. All rights reserved.</p>
        </div>
      </div>
    </footer>
  </div>
</body>
</html>
