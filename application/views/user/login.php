
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="container">
	<div class="row">
		<div class="col-sm-12">
			<div class="main">
				<div class="login-form">
					<h3 class="login-title" ><?php echo $this->lang->line("Login"); ?></h3>
					<?php if (!empty($error_message)) { ?>
					<div class="alert-error">
						<p><?php echo $error_message; ?></p>
						<br />
					</div>
					<?php } ?>
				
					<form action='<?php $action_url; ?>' method='POST'>
						<div class="form-group row">
							<input type='hidden'  name='<?php echo $csrf['name']; ?>' value='<?php echo $csrf['value']; ?>'>
							<label for="username" class="col-sm-3 form-control-label"><?php echo $this->lang->line("Username"); ?></label>
							<div class="col-sm-9">
								<input type='text' class="form-control" id="username" name='username' value='<?php echo $username; ?>' placeholder='<?php echo $this->lang->line("Username"); ?>'>
								<?php if (!empty($username_error)) { ?>
								<div class="alert-error text-left"> 
									<?php echo $username_error . "<br>";?>
								</div>
								<?php } ?>

							</div>
						</div>
						<div class="form-group row">
							<label for="password" class="col-sm-3 form-control-label"><?php echo $this->lang->line("Password"); ?></label>
							<div class="col-sm-9">
								<input type='password' class="form-control" id="password" name='password' value='' placeholder='<?php echo $this->lang->line("Password"); ?>'>
								<?php if (!empty($password_error)) {?>
								<div class="alert-error text-left">
								<?php echo $password_error . "<br>"; ?>
								</div>
								<?php } ?>
							</div>
						</div>
						<br /><br />
						<div class="form-group row">
						    <div class="col-sm-12 pull-right">
						      <input class="btn btn-primary" style="padding:6px 25px;" type='submit' value="Submit">
						    </div>
						</div>
						<div class="form-group row">
						    <div class="col-sm-12 pull-right">
                  <br />
						      <a href="<?php echo base_url("/user/forget"); ?>"><?php echo $this->lang->line("Forget password"); ?></a>
						    </div>
						</div>
					</form> 
				</div>
				
			</div>
		</div>
	</div>
</div>
