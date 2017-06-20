<select class="form-control check_premium" name='<?php echo (empty($myid) ? '' : $myid); ?>province2' id='<?php echo (empty($myid) ? '' : $myid); ?>province2' >
<?php foreach ($province as $key => $value) { ?>
<option value='<?php echo $key; ?>' <?php echo $value['selected']; ?>><?php echo $value['name']; ?></option>
<?php } ?>
</select>
<?php if (!empty($myid)) { ?>
<script>
$('#province2').change(function() {
	if ($('#issame').is(':checked')) {
		$('#<?php echo $myid; ?>province2').val($('#province2').val());
	}
});
</script>
<?php } ?>
