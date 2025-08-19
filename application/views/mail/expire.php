<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<br />
Dear <?php echo htmlspecialchars($user['firstname'] . " " . $user['lastname']); ?>,
Dear [Agency Name/Partner],

<br />
<br />
We hope this message finds you well.
<br />
<br />
Please be advised that the following policies under your account 
are approaching their expiry dates. We kindly ask that you contact 
your clients to remind them of the upcoming expirations and assist 
with additional insurance policy purchase if they wish to maintain 
continuous coverage.
<br />
<br />
<b>Policies Expiring Soon:</b>
<table>
  <tr>
    <td>
      Client Name
    </td>
    <td>
      Policy Number
    </td>
    <td>
      Effective Date
    </td>
    <td>
      <b>Expiry Date</b>
    </td>
  </tr>
  <?php foreach ($plans as $plan) { ?>
  <tr>
    <td>
      <?php echo $plan["firstname"] . " " . $plan["lastname"]; ?>
    </td>
    <td>
      <?php echo $plan["policy"]; ?>
    </td>
    <td>
      <?php echo $plan["effective_date"]; ?>
    </td>
    <td>
      <?php echo $plan["expiry_date"]; ?>
    </td>
  </tr>
  <?php } ?>
</table>
<br />
<br />
Thank you for your continued partnership and support.
<br />
<br />
Best regards,
<br />
<br />
JF Insurance Agency Group Inc.
<br />
15 Wertheim Court, Suite #501
<br />
Richmond Hill, ON L4B 3H7
<br />
Tel: 905-707-1512 | Fax: 905-707-1513
<br />
Website: www.jfgroup.ca
