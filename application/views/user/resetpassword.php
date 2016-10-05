
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="container">
	<div class="row">
		<div class="col-sm-12">
			<div class="main">
				<div class="login-form">
					<h3 class="login-title" >Reset Password</h3>
					<?php if (!empty($error_message)) { ?>
					<div class="alert-error">
						<p><?php echo $error_message; ?></p>
						<br />
					</div>
					<?php } ?>
				
					<form action='<?php $action_url; ?>' method='POST'>
						<div class="form-group row">
							<input type='hidden'  name='<?php echo $csrf['name']; ?>' value='<?php echo $csrf['value']; ?>'>
							<label for="password" class="col-sm-3 form-control-label">Your New Password</label>
							<div class="col-sm-9">
								<input type='password' class="form-control" id="password" name='password' value='' placeholder="Password">
							</div>
						</div>
						<div class="form-group row">
							<label for="password2" class="col-sm-3 form-control-label">New Password Again</label>
							<div class="col-sm-9">
								<input type='password' class="form-control" id="password2" name='password2' value='' placeholder="Password">
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
				
			</div>
		</div>
	</div>
</div>