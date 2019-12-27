<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<p>Good day:</p>
<p>Thank you for choosing Johnson Fu Insurance Agency to cover the needs of your clients.</p><br />
<p>Please find attached renewal report for your existing clients whose coverage is due for renewal.</p><br />
<p>Please note that for renewal policy issued after the policy expiry date, new waiting period may apply.</p><br />
<p>Should you have any questions, please do not hesitate to contact us.</p><br />
<br /><br />
<p>Kind regards,</p>
<p style="font-size: 14px;color:#2472a4;">
	JF Insurance Agency Inc.<br />
	15 Wertheim Court, Suite #501<br />
	Richmond Hill, ON L4B 3H7<br />
	Tel: 905-707-1512  Fax: 905-707-1513<br />
	<span style="color:red;">
		"This message, including any attachments, is privileged and intended only for the person(s) named above. This material may contain confidential or personal information, which may be subject to the provisions of the Freedom of Information & Protection Act. Any other distribution, copying or disclosure is strictly prohibited. If you are the intended recipient or have received this message in error, please notify us immediately by telephone, fax or email, and permanently delete the original transmission from us, including any attachments, without making a copy."
	</span>
</p>

<br /><br />

<hr />
<hr />
<br />
<p>
<!--Period: <?php //echo $period; ?> -->
REPORT LIST:
</p>
<table style="font-size: 14px;">
	<thead>
	   <tr><th>Policy Number</th><th>Effective Date</th><th>Expiry Date</th><th>Customer Name</th><th>Province</th><th>Phone Number</th><th>Email Address</th></tr>
	</thead>
	<tbody>
<?php foreach ($records as $record) : ?>
       <tr><td><?=$record['policy'] ?></td><td><?=$record['effective_date'] ?></td><td><?=$record['expiry_date'] ?></td><td><?=$record['customer_name'] ?></td><td><?=$record['province'] ?></td><td><?=$record['phone'] ?></td><td><?=$record['email'] ?></td></tr>
<?php endforeach; ?>
    </tbody>
</table>
