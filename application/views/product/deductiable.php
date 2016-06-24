<select class="form-control" name='deductiable_amount' onchange='get_premium()' id='deductiable_amount'>
<?php foreach ($plist as $key => $value) { ?>
<option value='<?php echo $key; ?>' <?php echo $value['selected']; ?>><?php echo $value['name']; ?></option>
<?php } ?>
</select>
