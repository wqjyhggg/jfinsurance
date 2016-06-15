<?php if (isset($menu) && is_array($menu)) { ?>
<ul>
<?php foreach ($menu as $m) { ?>
<li> <?php echo $m; ?> </li>
<?php } ?>
</ul>
<?php } ?>