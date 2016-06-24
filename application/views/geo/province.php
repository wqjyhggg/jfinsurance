<select class="form-control" name='province2' id='province2'>
<?php foreach ($province as $key => $value) { ?>
<option value='<?php echo $key; ?>' <?php echo $value['selected']; ?>><?php echo $value['name']; ?></option>
<?php } ?>
</select>
