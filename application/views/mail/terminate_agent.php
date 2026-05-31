<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
Dear <?php echo htmlspecialchars($agent['firstname'] . " " . $agent['lastname']); ?>,

We are reaching out regarding the insurance policy held by your client <?php echo htmlspecialchars($customer['firstname'] . " " . $customer['lastname']); ?>, 
policy number <?php echo $plan['policy']; ?>, purchased on <?php echo $plan['apply_date']; ?>.

Our records show that payment for this policy has not been received, resulting in the termination of the client's policy as of <?php echo $plan['expiry_date']; ?>.

If you believe this termination is incorrect or if you have any questions, please contact us immediately to discuss possible options.

To assist your client, please respond to this message or contact us directly at (905) 707-1512. You can also reach us via email at info@jfgroup.ca.

Thank you for your immediate attention to this matter.


Sincerely,



JF Insurance Agency Group Inc.
15 Wertheim Court, Suite #501
Richmond Hill, ON L4B 3H7
Tel: 905-707-1512 Fax: 905-707-1513
Email: Info@jfgroup.ca
Website: www.jfgroup.ca
