<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
Dear <?php echo htmlspecialchars($customer['firstname'] . " " . $customer['lastname']); ?>,

We are contacting you regarding your insurance policy <?php echo $plan['polcy']; ?>, purchased on <?php echo $plan['apply_date']; ?>.

Our records indicate that payment for this policy was not received and we were not able to contact with you. As a result, your policy has been terminated as of <?php echo $plan['expiry_date']; ?>.

If you believe this termination is in error or if you have any questions, please contact your agent immediately to discuss possible options.

To restore your coverage, please respond to this message or call us directly at <b>(905) 707-1512</b>. You may also email us at info@jfgroup.ca.

Thank you for your immediate attention to this matter.


Sincerely,



JF Insurance Agency Group Inc.
15 Wertheim Court, Suite #501
Richmond Hill, ON L4B 3H7
Tel: 905-707-1512 Fax: 905-707-1513
Email: Info@jfgroup.ca
Website: www.jfgroup.ca
