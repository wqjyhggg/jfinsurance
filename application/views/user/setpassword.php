<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<div class="container">
  <div class="row">
    <div class="col-sm-12">
      <div class="main">
        <div class="login-form">
          <h3 class="login-title">Set Password</h3>
          <form action='<?php $action_url; ?>' method='POST' id="myForm">
            <div class="form-group row">
              <input type='hidden' name='<?php echo $csrf['name']; ?>' value='<?php echo $csrf['value']; ?>'>
              <label for="username" class="col-sm-3 form-control-label">Password</label>
              <div class="col-sm-9">
                <input type='text' class="form-control password" id="password" name='password' value='<?php echo $this->input->post("password"); ?>' placeholder="New passwrod">
              </div>
            </div>
            <div class="form-group row">
              <label for="username" class="col-sm-3 form-control-label">Verify Password</label>
              <div class="col-sm-9">
                <input type='text' class="form-control password" id="vpassword" name='vpassword' value='<?php echo $this->input->post("vpassword"); ?>' placeholder="Re enter passwrod">
								<div class="alert-error text-left">
								</div>
              </div>
            </div>
            <br /><br />
            <div class="form-group row">
              <div class="col-sm-12 pull-right">
                <input class="btn btn-primary" style="padding:6px 25px;" type='submit' value="Reset Password">
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
function verify_password() {
  var v1 = $('#password').val();
  var v2 = $('#vpassword').val()
  if ((v1 != '') && (v2 != '') && (v1 != v2)) {
    $('.alert-error').html('Password and Verify Password are different, please re-enter');
    $('.alert-error').show();
    return false;
  } else {
    $('.alert-error').html('');
    $('.alert-error').hide();
    return true;
  }
}
$(document).ready(function() {
  $('.password').change(verify_password);

  $('#myForm').on('submit', function(e){
    e.preventDefault();
    if (!verify_password()) {
      return;
    }
    this.submit();
  });
});
</script>
