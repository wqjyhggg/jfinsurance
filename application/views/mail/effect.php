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
Please be informed that the following Super Visa policies under your account are approaching their effective dates.
<br />
<br />
Kindly verify with your clients if they are planning to arrive as scheduled.
<br />
<br />
<ul>
  <li>If your client is <b>arriving on time</b>, no further action is needed.</li>
  <li>If your client <b>will not be arriving as planned</b>, please contact us to arrange for a policy date change. You can reach us at tripchange@jfgroup.ca for all policy date modifications.</li>
</ul>
<br />
<br />
<b>Super Visa Policies with Upcoming Effective Dates:</b>
<table>
  <tr>
    <td>
      <b>Client Name</b>
    </td>
    <td>
      <b>Policy Number</b>
    </td>
    <td>
      <b>Effective Date</b>
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
  </tr>
  <?php } ?>
</table>
<br />
<br />
Thank you for your attention to this matter and your ongoing partnership.
<br />
<br />
Best regards,
<br />
<br />
<b>JF Insurance Agency Group Inc.</b>
<br />
15 Wertheim Court, Suite #501
<br />
Richmond Hill, ON L4B 3H7
<br />
Tel: 905-707-1512 | Fax: 905-707-1513
<br />
Website: www.jfgroup.ca
