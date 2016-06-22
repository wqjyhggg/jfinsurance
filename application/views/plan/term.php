Term & condition.
babababababababa.

<?php if (!empty($message)) { echo $message . "<br>"; } ?>

<form action='<?php echo $action_url; ?>' method='POST'>
<input type='hidden' name='<?php echo $csrf['name']; ?>' value='<?php echo $csrf['value']; ?>'><br>
<input type='hidden' name='plan_id' value='<?php echo $plan_id; ?>'>
Agree: <input type='checkbox' name='agree' id='agree'><br>
<input type='submit' name='submit' value='<?php echo $submit; ?>'><br>
</form> 
