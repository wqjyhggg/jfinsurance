<select name='sum_insured' id='sum_insured'>
<?php foreach ($plist as $key => $value) { ?>
<option value='<?php echo $key; ?>' <?php echo $value['selected']; ?>><?php echo $value['name']; ?></option>
<?php } ?>
</select>
