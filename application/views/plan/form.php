<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!-- Plan page content -->

	    <!-- Content top navigation -->
        <div class="top_nav">
          <div class="nav_menu">
            <nav class="" role="navigation">
              <div class="nav toggle">
                <a id="menu_toggle"><i class="fa fa-bars"></i></a>
              </div>

              <ul class="nav navbar-nav navbar-right">
                <!-- User section -->
                <li class="">
                  <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                    <img src="images/img.jpg" alt="">John Doe
                    <span class=" fa fa-angle-down"></span>
                  </a>
                  <ul class="dropdown-menu dropdown-usermenu pull-right">
                    <li><a href="javascript:;"> Profile</a></li>
                    <li>
                      <a href="javascript:;">
                        <span class="badge bg-red pull-right">50%</span>
                        <span>Settings</span>
                      </a>
                    </li>
                    <li><a href="javascript:;">Help</a></li>
                    <li><a href="login.html"><i class="fa fa-sign-out pull-right"></i> Log Out</a></li>
                  </ul>
                </li>
                <!-- User section End -->
              </ul>
            </nav>
          </div>
        </div>
        <!-- Content top navigation End-->

        <!-- page content -->
        <div class="right_col" role="main" style="margin-bottom:40px;">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3>Create Policy</h3>
              </div>

              <div class="title_right">
                <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
                  <div class="input-group">
                    <input type="text" class="form-control" placeholder="Search for...">
                    <span class="input-group-btn">
                      <button class="btn btn-default" type="button">Go!</button>
                    </span>
                  </div>
                </div>
              </div>
            </div>
            <div class="clearfix"></div>
           <!-- Form Section -->
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Policy Form<small></small></h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <br />



<script src="<?php echo base_url(); ?>js/jquery-3.0.0.min.js" type="text/javascript"></script>
<?php if (!empty($error_message)) { echo $error_message . "<br>"; } ?>
<form action='<?php echo $action_url; ?>' method='POST'>
<input type='hidden' name='<?php echo $csrf['name']; ?>' value='<?php echo $csrf['value']; ?>'><br>
<input type='hidden' name='plan_id' value='<?php echo $plan_id; ?>'>
<input type='hidden' name='product_short' value='<?php echo $product_short; ?>'>
Apply Date: <?php echo empty($apply_time) ? "N/A" : $apply_time; ?><br>
<?php if (empty($plan_id) || empty(status_id)) { ?>
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
Apply Date: <input type='date' name='apply_date' value='<?php echo $apply_date; ?>'><br>
Arrival Date: <input type='date' name='arrival_date' value='<?php echo $arrival_date; ?>'><?php if (!empty($error_arrival_date)) { echo $error_arrival_date; } ?><br>
Effective Date: <input type='date' name='effective_date' name='effective_date' class='setpremium' value='<?php echo $effective_date; ?>'<?php if (!empty($error_effective_date)) { echo $error_effective_date; } ?>><br>
Expiry Date: <input type='date' name='expiry_date' name='expiry_date' class='setpremium' value='<?php echo $expiry_date; ?>'><?php if (!empty($error_expiry_date)) { echo $error_expiry_date; } ?><br>
=========================================================================<br />
Beneficiary: <input type='text' name='beneficiary' value='<?php echo $beneficiary; ?>'><?php if (!empty($error_beneficiary)) { echo $error_beneficiary; } ?><br>
<!-- Medical History Options: <input type='checkbox' name='isfamilyplan' id='isfamilyplan' <?php echo empty($isfamilyplan) ? "" : "checked"; ?>><br> -->
Is Family Plan: <input type='checkbox' class='setpremium' name='isfamilyplan' id='isfamilyplan' <?php echo empty($isfamilyplan) ? "" : "checked"; ?>><br>
Sum Insured (CAD): <div id='sum_insured_div'></div>
Deductible amount (CAD):  <div id='deductiable_amount_div'></div>
<?php if (empty($disable_stable_condition)) { ?>
With stable pre-existion condition coverage <input type='radio' class='setpremium' name='stable_condition' value='1' <?php echo (empty($stable_condition) || ($stable_condition != 2 )) ? "checked" : ""; ?>><br>
Without stable pre-existion condition coverage <input type='radio' class='setpremium' name='stable_condition' value='2' <?php echo (!empty($stable_condition) && ($stable_condition == 2 )) ? "checked" : ""; ?>><br>
<?php } ?>
=========================================================================<br />
<input type='hidden' name='customer_id' value='<?php echo !empty($customer_id) ? $customer_id : 0; ?>'>Custmer Name:<br>
Last Name: <input type='text' name='firstname' value='<?php echo !empty($firstname) ? $firstname : ''; ?>'><?php if (!empty($error_firstname)) { echo $error_firstname; } ?><br>
First Name: <input type='text' name='lastname' value='<?php echo !empty($lastname) ? $lastname : ''; ?>'><?php if (!empty($error_lastname)) { echo $error_lastname; } ?><br>
Birth Date: <input type='date' class='setpremium' name='brithday' value='<?php echo !empty($brithday) ? $brithday : ''; ?>'><?php if (!empty($error_brithday)) { echo $error_brithday; } ?><br>
Gender: <select name='gender'>
<option value='M' <?php echo (empty($gender) || ($gender != 'F')) ? "selected" : ""; ?>>Male</option>
<option value='F' <?php echo (!empty($gender) && ($gender == 'F')) ? "selected" : ""; ?>>Female</option>
</select><br>
=========================================================================<br />
<div id='family_member' style='display:none'>
<?php for ($i = 1; $i < 9; $i++) { ?>
<input type='hidden' name='customer_id_<?php echo $i; ?>' value='<?php echo !empty(${'customer_id_'.$i}) ? ${'customer_id_'.$i} : 0; ?>'>Family member <?php echo $i; ?> :<br>
Last Name: <input type='text' name='firstname_<?php echo $i; ?>' value='<?php echo !empty(${'firstname_'.$i}) ? ${'firstname_'.$i} : ''; ?>'><br>
First Name: <input type='text' name='lastname_<?php echo $i; ?>' value='<?php echo !empty(${'lastname_'.$i}) ? ${'lastname_'.$i} : ''; ?>'><br>
Birth Date: <input type='date' class='setpremium' name='brithday_<?php echo $i; ?>' value='<?php echo !empty(${'brithday_'.$i}) ? ${'brithday_'.$i} : ''; ?>'><br>
Gender: <select name='gender_<?php echo $i; ?>'>
<option value='M' <?php echo (empty(${'gender_'.$i}) || (${'gender_'.$i} != 'F')) ? "selected" : ""; ?>>Male</option>
<option value='F' <?php echo (!empty(${'gender_'.$i}) && (${'gender_'.$i} == 'F')) ? "selected" : ""; ?>>Female</option>
</select><br>
=========================================================================<br />
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
<?php if ($user_group_id <= 3) { ?>
Premium: <input type='input' name='premium' id='premium' value='<?php echo $premium; ?>'><br>
<?php } else { ?>
Premium: <input type='hidden' name='premium' id='premium' value='<?php echo $premium; ?>'><br>
<?php } ?>
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
        	$('#family_member').hide();
        	$("input[name^='firstname_']").each(function() {
            	$(this).val('');
        	});
        	$("input[name^='lastname_']").each(function() {
            	$(this).val('');
        	});
        }
    });

	$('.setpremium').change(get_premium); 
});

function get_premium() {
	var product_short = $('input[name="product_short"]').val();
	var apply_date = $('input[name="apply_date"]').val();
	var effective_date = $('input[name="effective_date"]').val();
	var expiry_date = $('input[name="expiry_date"]').val();
	var isfamilyplan = $('input[name="isfamilyplan"]').is(':checked');	// checkbox
	var sum_insured = $('select[name="sum_insured"]').val();	// select
	var deductiable_amount = $('select[name="deductiable_amount"]').val();	// select
	var stable_condition = $('input[name="stable_condition"]:checked').val();	// radio
	var brithday = $('input[name="brithday"]').val();	// 
	if (new Date(brithday) < new Date($('input[name="brithday_1"]').val())) {
		brithday = $('input[name="brithday_1"]').val();
	} 
	if (new Date(brithday) < new Date($('input[name="brithday_2"]').val())) {
		brithday = $('input[name="brithday_2"]').val();
	} 
	if (new Date(brithday) < new Date($('input[name="brithday_3"]').val())) {
		brithday = $('input[name="brithday_3"]').val();
	} 
	if (new Date(brithday) < new Date($('input[name="brithday_4"]').val())) {
		brithday = $('input[name="brithday_4"]').val();
	} 
	if (new Date(brithday) < new Date($('input[name="brithday_5"]').val())) {
		brithday = $('input[name="brithday_5"]').val();
	} 
	if (new Date(brithday) < new Date($('input[name="brithday_6"]').val())) {
		brithday = $('input[name="brithday_6"]').val();
	} 
	if (new Date(brithday) < new Date($('input[name="brithday_7"]').val())) {
		brithday = $('input[name="brithday_7"]').val();
	} 
	if (new Date(brithday) < new Date($('input[name="brithday_8"]').val())) {
		brithday = $('input[name="brithday_8"]').val();
	}
	console.log('product_short: ' + product_short);
	console.log('apply_date: ' + apply_date);
	console.log('effective_date: ' + effective_date);
	console.log('expiry_date: ' + expiry_date);
	console.log('isfamilyplan: ' + isfamilyplan);
	console.log('sum_insured: ' + sum_insured);
	console.log('deductiable_amount: ' + deductiable_amount);
	console.log('stable_condition: ' + stable_condition);
	console.log('brithday: ' + brithday);
	if (effective_date && expiry_date && sum_insured && brithday) {
		console.log('Call: ');
		$.ajax({
			url: '<?php echo $premium_url; ?>',
			type: 'post',
			data: {
				product_short: product_short,
				apply_date: apply_date,
				effective_date: effective_date,
				expiry_date: expiry_date,
				isfamilyplan: isfamilyplan,
				sum_insured: sum_insured,
				deductiable_amount: deductiable_amount,
				stable_condition: stable_condition,
				brithday: brithday},
			success: function(data, textStatus, jqXHR) {
				if (data['status'] == 'OK') {
	        		$('#premium').val(data['premium']);
				} else {
					alert('Unavailable');
				}
	    	},
		});
	}
} 
</script>

				</div>
                </div>
              </div>
            </div><!-- End Form -->
            </div>
        </div>
        <!-- /page content -->