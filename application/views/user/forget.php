<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<div class="container">
  <div class="row">
    <div class="col-sm-12">
      <div class="main">
        <div class="login-form">
          <h3 class="login-title">Forget Password</h3>
          <?php if ($ispost) { ?>
            <div class="form-group row">
              <div class="col-sm-12 pull-right">
                Your application has been accepted, please check your email. And use the link in the email to reset your password.
              </div>
            </div>
          <?php } else { ?>
            <form action='<?php $action_url; ?>' method='POST'>
              <div class="form-group row">
                <input type='hidden' name='<?php echo $csrf['name']; ?>' value='<?php echo $csrf['value']; ?>'>
                <label for="username" class="col-sm-3 form-control-label">Username</label>
                <div class="col-sm-9">
                  <input type='text' class="form-control" id="username" name='username' value='<?php echo $username; ?>' placeholder="Your Username or Email">
                </div>
              </div>
              <br /><br />
              <div class="form-group row">
                <div class="col-sm-12 pull-right">
                  <input class="btn btn-primary" style="padding:6px 25px;" type='submit' value="Reset Password">
                </div>
              </div>
            </form>
          <?php } ?>
        </div>
      </div>
    </div>
  </div>
</div>