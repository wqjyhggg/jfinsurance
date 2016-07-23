<select class="form-control" name='<?php echo (empty($myid) ? '' : $myid); ?>country2' id='<?php echo (empty($myid) ? '' : $myid); ?>country2' >
<?php foreach ($country as $key => $value) { ?>
<option value='<?php echo $key; ?>' <?php echo $value['selected']; ?>><?php echo $value['name']; ?></option>
<?php } ?>
</select>
<script>
$('#<?php echo (empty($myid) ? '' : $myid); ?>country2').on('change', function() {
	$.ajax({
		url: '<?php echo $country_url; ?>' + $(this).val() + '?myid=' + '<?php echo (empty($myid) ? '' : $myid); ?>',
		type: 'GET',
		success: function(data, textStatus, jqXHR) {
        	$('#<?php echo (empty($myid) ? '' : $myid); ?>province2_div').html(data);
    	},
	});
});
</script>
