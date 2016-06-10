<p>Login</p>
<?php if (!empty($error_message)) { echo $error_message . "<br>"; } ?>
<form action='<?php $action_url; ?>' method='POST'>
<input type='hidden' name='<?php echo $csrf['name']; ?>' value='<?php echo $csrf['value']; ?>'><br>
<input type='text' name='username' value='<?php echo $username; ?>'><br>
<?php if (!empty($username_error)) { echo $username_error . "<br>"; } ?>
<input type='password' name='password' value=''><br>
<?php if (!empty($password_error)) { echo $password_error . "<br>"; } ?>
<input type='submit'><br>
</form> 
