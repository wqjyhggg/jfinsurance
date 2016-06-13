<select name='country2' id='country2'>
<?php foreach ($country as $key => $value) { ?>
<option value='<?php echo $key; ?>' <?php echo $value['selected']; ?>><?php echo $value['name']; ?></option>
<?php } ?>
</select>
<script>
$('#country2').on('change', function() {
	$.ajax({
		url: '<?php echo $country_url; ?>' + $(this).val(),
		type: 'GET',
		success: function(data, textStatus, jqXHR) {
        	$('#province2_div').html(data);
    	},
	});
});
</script>
