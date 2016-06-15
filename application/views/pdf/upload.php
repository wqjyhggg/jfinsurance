<!DOCTYPE html>
<html>
<head>
<title>Upload Form</title>
</head>
<body>
<?php if (!empty($errormsg)) { ?>
<?php echo $errormsg;?>
<?php } ?>
<form action="<?php $action_url; ?>" method="post" enctype="multipart/form-data">
<input type='hidden' name='<?php echo $csrf['name']; ?>' value='<?php echo $csrf['value']; ?>'>
<input type="file" name="userfile" size="20" />
<br /><br />
<input type="submit" value="upload" />
</form>
</body>
</html>