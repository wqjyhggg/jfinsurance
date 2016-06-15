<p>Edit Form</p>
<?php if (!empty($error_message)) { echo $error_message . "<br>"; } ?>
<form action='<?php $action_url; ?>' method='POST'>
<input type='hidden' name='<?php echo $csrf['name']; ?>' value='<?php echo $csrf['value']; ?>'>
<input type='hidden' name='user_id' value='<?php echo $user_id; ?>'>
<select name='user_group_id'>
<option value='0' <?php echo empty($user_group_id) ? 'selected' : ''; ?>> -- select user group -- </option>
<?php foreach ($user_group_list as $key => $name) { ?>
<option value='<?php echo $key; ?>' <?php echo ($user_group_id == $key) ? 'selected' : ''; ?>><?php echo $name; ?></option>
<?php } ?>
</select><br>
<select name='parent_user_id'>
<option value='0' <?php echo empty($parent_user_id) ? 'selected' : ''; ?>> -- select brokerage -- </option>
<?php foreach ($broker_list as $key => $name) { ?>
<option value='<?php echo $key; ?>' <?php echo ($parent_user_id == $key) ? 'selected' : ''; ?>><?php echo $name; ?></option>
<?php } ?>
</select><br>
Username: <input type='text' name='username' value='<?php echo $username; ?>'><br>
Password: <input type='text' name='password' value=''><br>
Region: <input type='text' name='region' value='<?php echo $region; ?>'><br>
Brokerage: <input type='text' name='business' value='<?php echo $business; ?>'><br>
Gender: 
<select name='gender'>
<option value='M' <?php echo (empty($gender) || ($gender == 'M')) ? 'selected' : ''; ?>> M - Male </option>
<option value='F' <?php echo ($gender == 'F') ? 'selected' : ''; ?>> F - Female </option>
</select><br>
Firstname: <input type='text' name='firstname' value='<?php echo $firstname; ?>'><br>
Lastname: <input type='text' name='lastname' value='<?php echo $lastname; ?>'><br>
Email: <input type='text' name='email' value='<?php echo $email; ?>'><br>
Address: <input type='text' name='address' value='<?php echo $address; ?>'><br>
City: <input type='text' name='city' value='<?php echo $city; ?>'><br>
Province: <div id='province2_div'></div><br>
Country: <div id='country2_div'></div><br>
Post Code: <input type='text' name='postcode' value='<?php echo $postcode; ?>'><br>
Website: <input type='text' name='website' value='<?php echo $website; ?>'><br>
Licence#: <input type='text' name='licence_number' value='<?php echo $licence_number; ?>'><br>
Licence Expire: <input type='date' name='licence_expire' value='<?php echo $licence_expire; ?>'><br>
Business Phone: <input type='text' name='business_phone' value='<?php echo $business_phone; ?>'><br>
Mobile Phone: <input type='text' name='mobile_phone' value='<?php echo $mobile_phone; ?>'><br>
FAX: <input type='text' name='fax_number' value='<?php echo $fax_number; ?>'><br>
Toll Free: <input type='text' name='toll_free' value='<?php echo $toll_free; ?>'><br>
<b>Products:</b><br>
<?php foreach ($product_list as $key => $pd) { ?>
<input type='checkbox' name='product_list[]' value='<?php echo $key; ?>' <?php echo $pd['checked']; ?>> <?php echo $pd['product_short'] . " - " . $pd['full_name']; ?><br>
<?php echo $pd['product_short']; ?> Commission (%): <input type='text' name='product_commission_<?php echo $key; ?>' value='<?php echo $pd['commission']; ?>'><br>
<?php } ?>
<b>Pay type:</b><br>
<?php foreach ($paytype_list as $pay) { ?>
<input type='checkbox' name='paytype_list[]' value='<?php echo $pay; ?>' <?php echo (strpos($pay_type, $pay) === FALSE) ? '' : 'checked'; ?>> <?php echo $pay; ?><br>
<?php } ?>
<b>Status:</b><br>
<select name='status'>
<option value='1' <?php echo ($status || empty($user_id)) ? 'selected' : ''; ?>>Active</option>
<option value='0' <?php echo (empty($status) && $user_id) ? 'selected' : ''; ?>>Disable</option>
</select><br>
<b>Notes:</b><br>
<textarea name='note'><?php echo $note; ?></textarea><br>
<input type='submit' value='<?php echo ($user_id) ? "Update" : "Add"; ?>'><br>
</form>
<script>
$( document ).ready(function() {
	$.ajax({
		url: '<?php echo $province_url; ?>',
		type: 'GET',
		success: function(data, textStatus, jqXHR) {
        	$('#province2_div').html(data);
    	},
	});
	$.ajax({
		url: '<?php echo $country_url; ?>',
		type: 'GET',
		success: function(data, textStatus, jqXHR) {
        	$('#country2_div').html(data);
    	},
	});
});
</script>
