<p>Search Form</p>
<?php if (!empty($error_message)) { echo $error_message . "<br>"; } ?>
<form action='<?php echo $search_url; ?>' method='POST'>
<input type='hidden' name='<?php echo $csrf['name']; ?>' value='<?php echo $csrf['value']; ?>'><br>
Last Name: <input type='text' name='firstname' value='<?php echo $firstname; ?>'><br>
First Name: <input type='text' name='lastname' value='<?php echo $lastname; ?>'><br>
Birth Date From: <input type='date' name='brithday' value='<?php echo $brithday; ?>'><br>
Birth Date To: <input type='date' name='brithday2' value='<?php echo $brithday2; ?>'><br>
Policy Number: <input type='text' name='policy' value='<?php echo $policy; ?>'><br>
Apply Date From: <input type='date' name='apply_time' value='<?php echo $apply_time; ?>'><br>
Apply Date To: <input type='date' name='apply_time2' value='<?php echo $apply_time2; ?>'><br>
Arrival Date From: <input type='date' name='arrival_date' value='<?php echo $arrival_date; ?>'><br>
Arrival Date To: <input type='date' name='arrival_date2' value='<?php echo $arrival_date2; ?>'><br>
Effective Date From: <input type='date' name='effective_date' value='<?php echo $effective_date; ?>'><br>
Effective Date To: <input type='date' name='effective_date2' value='<?php echo $effective_date2; ?>'><br>
Expiry Date From: <input type='date' name='expiry_date' value='<?php echo $expiry_date; ?>'><br>
Expiry Date To: <input type='date' name='expiry_date2' value='<?php echo $expiry_date2; ?>'><br>
<?php if ($beuser['user_group_id'] > 5) { ?>
Agent/School Name: <input type='text' name='uname' value='<?php echo $uname; ?>'><br>
<?php } ?>
Batch No.: <input type='text' name='batch_number' value='<?php echo $batch_number; ?>'><br>
Policy Status: <select name='status_id'>
<option value='0'> -- select policy status -- </option>
<?php foreach ($status_list as $key => $value) { ?>
<option value='<?php echo $key; ?>' <?php echo ($key == $status_id) ? 'selected' : ''; ?>><?php echo $value['name']; ?></option>
<?php } ?>
</select><br>
Product List: <select name='product_short'>
<option value='0'> -- select product -- </option>
<?php foreach ($product_list as $key => $value) { ?>
<option value='<?php echo $key; ?>' <?php echo ($key == $product_short) ? 'selected' : ''; ?>><?php echo $value['full_name']; ?></option>
<?php } ?>
</select><br>
Province: <div id='province2_div'></div><br>
Country: <div id='country2_div'></div><br>
<input type='submit' name='search' value='Search'><br>
</form><br />
=========================================================================<br />
<form action='<?php echo $add_url; ?>' method='POST'>
<input type='hidden' name='<?php echo $csrf['name']; ?>' value='<?php echo $csrf['value']; ?>'><br>
Product List: <select name='product_short'>
<?php foreach ($product_list as $key => $value) { ?>
<option value='<?php echo $key; ?>'><?php echo $value['full_name']; ?></option>
<?php } ?>
</select><br>
<input type='submit' name='add' value='Create'><br>
</form> 
=========================================================================<br />
<p>Policy List</p>
<table>
	<tr>
		<th>Policy No.</th>
		<th>Batch No.</th>
		<th>Name</th>
		<th>Status</th>
		<th>Effect Date</th>
		<th>User</th>
<?php if ($beuser['user_group_id'] > 5) { ?>
		<th>Agent</th>
<?php } ?>
		<th>&nbsp</th>
	</tr>
<?php foreach ($plan_list as $plan) { ?>
	<tr>
		<td><a href='<?php echo $edit_url.$plan['plan_id']; ?>'><?php echo $plan['policy']?></a></td>
		<td><?php echo $plan['batch_number']; ?></td>
		<td><?php echo $plan['full_name']; ?></td>
		<td><?php echo $status_list[$plan['status_id']]; ?></td>
		<td><?php echo $plan['effective_date']; ?></td>
		<td><?php echo $plan['firstname'] . " " . $plan['lastname']; ?></td>
<?php if ($beuser['user_group_id'] > 5) { ?>
		<td><?php echo $plan['agent_firstname'] . " " . $plan['agent_lastname']; ?></td>
<?php } ?>
		<td><a href='<?php echo $copy_url.$plan['plan_id']; ?>'>Copy</a></td>
	</tr>
<?php } ?>
</table>
<script>
$( document ).ready(function() {
	$.ajax({
		url: '<?php echo $province_url; ?>',
		type: 'GET',
		data: {neednull:1},
		success: function(data, textStatus, jqXHR) {
        	$('#province2_div').html(data);
    	},
	});
	$.ajax({
		url: '<?php echo $country_url; ?>',
		type: 'GET',
		data: {neednull:1},
		success: function(data, textStatus, jqXHR) {
        	$('#country2_div').html(data);
    	},
	});
});
</script>

