<?php if (!empty($error_message)) { echo $error_message . "<br>"; } ?>
<form action='<?php echo $action_url; ?>' method='POST'>
<input type='hidden' name='<?php echo $csrf['name']; ?>' value='<?php echo $csrf['value']; ?>'><br>
<input type='hidden' name='plan_id' value='<?php echo $plan_id; ?>'>
<input type='hidden' name='product_short' value='<?php echo $product_short; ?>'>
Apply Date: <?php echo empty($apply_time) ? "N/A" : $apply_time; ?> 
<?php if (empty(plan_id) || empty(status_id)) { ?>
	<?php /* it should be new plan */ ?>
	<input type='hidden' name='status_id' value='0'>
<?php } else { ?>
	<?php /* it should be created plan */ ?>
	<?php if ($status_id == 1) { /* qutoe */ ?>
	<a href='<?php echo $pay_url; ?>'>Pay</a>
	<?php } ?>
	<a href='<?php echo $copy_url; ?>'>Copy</a>
	<?php if ($beuser['user_group_id'] > 2) { ?>
		<?php /* it is school or brokerage or agent */ ?>
		Policy Status: <?php echo $status_list[$status_id]; ?> 
		<input type='hidden' name='status_id' value='<?php echo $status_id; ?>'>
		<?php } else { ?>
		Policy Status: <select name='status_id'>
			<option value='0'> -- select policy status -- </option>
			<?php foreach ($status_list as $key => $value) { ?>
			<option value='<?php echo $key; ?>' <?php echo ($key == $status_id) ? 'selected' : ''; ?>><?php echo $value['name']; ?></option>
			<?php } ?>
		</select><br>
		<?php } ?>
<?php } ?>
Arrival Date: <input type='date' name='arrival_date' value='<?php echo $arrival_date; ?>'><?php if (!empty($error_arrival_date)) { echo $error_arrival_date; } ?><br>
Effective Date: <input type='date' name='effective_date' value='<?php echo $effective_date; ?>'<?php if (!empty($error_effective_date)) { echo $error_effective_date; } ?>><br>
Expiry Date: <input type='date' name='expiry_date' value='<?php echo $expiry_date; ?>'><?php if (!empty($error_expiry_date)) { echo $error_expiry_date; } ?><br>
=========================================================================<br />
Beneficiary: <input type='text' name='beneficiary' value='<?php echo $beneficiary; ?>'><?php if (!empty($error_beneficiary)) { echo $error_beneficiary; } ?><br>
Medical History Options: ??? <div id='medical_history_div'></div><br>
Is Family Plan: <input type='checkbox' name='isfamilyplan' id='isfamilyplan' <?php echo empty($isfamilyplan) ? "" : "checked"; ?>><br>
Sum Insured (CAD): <div id='sum_insured_div'></div>
Deductible amount (CAD):  <div id='deductiable_amount_div'></div>
=========================================================================<br />
<input type='text' name='customer_id' value='<?php echo empty($customer_id) ? $customer_id : 0; ?>'>
Last Name: <input type='text' name='firstname' value='<?php echo empty($firstname) ? $firstname : ''; ?>'><?php if (!empty($error_firstname)) { echo $error_firstname; } ?><br>
First Name: <input type='text' name='lastname' value='<?php echo empty($lastname) ? $lastname : ''; ?>'><?php if (!empty($error_lastname)) { echo $error_lastname; } ?><br>
Birth Date: <input type='date' name='brithday' value='<?php echo empty($brithday) ? $brithday : ''; ?>'><?php if (!empty($error_brithday)) { echo $error_brithday; } ?><br>
Gender: <select name='gender'>
<option value='M' <?php echo (empty($gender) || ($gender != 'F')) ? "selected" : ""; ?>>Male</option>
<option value='F' <?php echo (!empty($gender) && ($gender == 'F')) ? "selected" : ""; ?>>Female</option>
</select><br>
<div id='family_member' style='display:none'>
<?php for ($i = 1; $i < 9; $i++) { ?>
<input type='text' name='customer_id_<?php echo $i; ?>' value='<?php echo empty(${'customer_id_'.$i}) ? ${'customer_id_'.$i} : 0; ?>'>
Last Name: <input type='text' name='firstname_<?php echo $i; ?>' value='<?php echo empty(${'firstname_'.$i}) ? ${'firstname_'.$i} : ''; ?>'><br>
First Name: <input type='text' name='lastname_<?php echo $i; ?>' value='<?php echo empty(${'lastname_'.$i}) ? ${'lastname_'.$i} : ''; ?>'><br>
Birth Date: <input type='date' name='brithday_<?php echo $i; ?>' value='<?php echo empty(${'brithday_'.$i}) ? ${'brithday_'.$i} : ''; ?>'><br>
Gender: <select name='gender_<?php echo $i; ?>'>
<option value='M' <?php echo (empty(${'gender_'.$i}) || (${'gender_'.$i} != 'F')) ? "selected" : ""; ?>>Male</option>
<option value='F' <?php echo (!empty(${'gender_'.$i}) && (${'gender_'.$i} == 'F')) ? "selected" : ""; ?>>Female</option>
</select><br>
<?php } ?>
</div>
Street No: <input type='text' name='street_number' value='<?php echo $street_number; ?>'><?php if (!empty($error_street_number)) { echo $error_street_number; } ?><br>
Street Name: <input type='text' name='street_name' value='<?php echo $street_name; ?>'><?php if (!empty($error_street_name)) { echo $error_street_name; } ?><br>
Suite No.: <input type='text' name='suite_number' value='<?php echo $suite_number; ?>'><br>
City: <input type='text' name='city' value='<?php echo $city; ?>'><?php if (!empty($error_city)) { echo $error_city; } ?><br>
Province: <div id='province2_div'></div><br>
Country: <div id='country2_div'></div><br>
Postcode: <input type='text' name='postcode' value='<?php echo $postcode; ?>'><br>
Phone1: <input type='text' name='phone1' value='<?php echo $phone1; ?>'><?php if (!empty($error_phone1)) { echo $error_phone1; } ?><br>
Phone2: <input type='text' name='phone2' value='<?php echo $phone2; ?>'><br>
=========================================================================<br />
Email: <input type='text' name='contact_email' value='<?php echo $contact_email; ?>'><?php if (!empty($error_contact_email)) { echo $error_contact_email; } ?><br>
Contact Phone: <input type='text' name='contact_phone' value='<?php echo $contact_phone; ?>'><br>
Residence: <input type='text' name='residence' value='<?php echo $residence; ?>'><br>
=========================================================================<br />
Notes: <input type='text' name='note' value='<?php echo $note; ?>'><br>
=========================================================================<br />
<input type='submit' name='submit' value='<?php echo $submit; ?>'><br>
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
	$.ajax({
		url: '<?php echo $sum_insured_url; ?>',
		success: function(data, textStatus, jqXHR) {
        	$('#sum_insured_div').html(data);
    	},
	});
	$.ajax({
		url: '<?php echo $deductiable_amount_url; ?>',
		success: function(data, textStatus, jqXHR) {
        	$('#deductiable_amount_div').html(data);
    	},
	});
	if ($('#isfamilyplan').get(0).checked) {
		$('#family_member').show();
	}
	$('#isfamilyplan').change(function() {
        if ($(this).get(0).checked) {
    		$('#family_member').show();
        } else {
        	$('#family_member').hidden();
        	$("input[name^='firstname_']").each(function() {
            	$(this).val('');
        	});
        	$("input[name^='lastname_']").each(function() {
            	$(this).val('');
        	});
        }
    });
});
</script>

