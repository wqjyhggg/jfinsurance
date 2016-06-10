<p>Search Form</p>
<?php if (!empty($error_message)) { echo $error_message . "<br>"; } ?>
<form action='<?php $action_url; ?>' method='POST'>
<input type='hidden' name='<?php echo $csrf['name']; ?>' value='<?php echo $csrf['value']; ?>'><br>
<select name='user_group_id'>
<option value='0'> -- select user group -- </option>
<?php foreach ($user_group_list as $key => $name) { ?>
<option value='<?php echo $key; ?>'><?php echo $name; ?></option>
<?php } ?>
</select><br>
Username: <input type='text' name='username' value='<?php echo $username; ?>'><br>
Firstname: <input type='text' name='firstname' value='<?php echo $firstname; ?>'><br>
Lastname: <input type='text' name='lastname' value='<?php echo $lastname; ?>'><br>
Email: <input type='text' name='email' value='<?php echo $email; ?>'><br>
Brockerage: <input type='text' name='business' value='<?php echo $business; ?>'><br>
<input type='submit'><br>
</form> 

<p>User List <a href='<?php echo $edit_url."0"; ?>'>Add user</a></p>
<table>
	<tr>
		<th>Username</th>
		<th>Type</th>
		<th>Brokerage</th>
		<th>Name</th>
		<th>Email</th>
		<th>Licence#</th>
		<th>Expire</th>
		<th>Pay Types</th>
		<th>Status</th>
	</tr>
<?php foreach ($user_list as $user) { ?>
	<tr>
		<td><a href='<?php echo $edit_url.$user['user_id']; ?>'><?php echo $user['username']?></a></td>
		<td><?php echo $user_group_list[$user['user_group_id']]; ?></td>
		<td><?php echo $user['business']; ?></td>
		<td><?php echo $user['lastname'] . " " . $user['lastname']; ?></td>
		<td><?php echo $user['email']; ?></td>
		<td><?php echo $user['licence_number']; ?></td>
		<td><?php echo $user['licence_expire']; ?></td>
		<td><?php echo $user['pay_type']; ?></td>
		<td><?php echo $user['status'] ? 'Act' : '-'; ?></td>
	</tr>
<?php } ?>
</table>
