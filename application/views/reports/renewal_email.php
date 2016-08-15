<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<p>
Period: <?php echo $period; ?>
</p>
<table>
	<thead>
	   <tr><th>Policy Number</th><th>Effective Date</th><th>Expiry Date</th><th>Customer Name</th><th>Province</th><th>Phone Number</th><th>Email Address</th></tr>
	</thead>
	<tbody>
<?php foreach ($records as $record) : ?>
       <tr><td><?=$record['policy'] ?></td><td><?=$record['effective_date'] ?></td><td><?=$record['expiry_date'] ?></td><td><?=$record['customer_name'] ?></td><td><?=$record['province'] ?></td><td><?=$record['phone'] ?></td><td><?=$record['email'] ?></td></tr>
<?php endforeach; ?>
    </tbody>
</table>
