<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
Dear <?php echo htmlspecialchars($customer['firstname'] . " " . $customer['lastname']); ?>,
\r\n
\r\n
This is a confirmation of your recent purchased insurance,
please DO NOT REPLY to this email.
\r\n
\r\n
Thank you for purchasing medical insurance from
JF Insurance Agency Group Inc.
\r\n
\r\n
Please refer to the attached documents for your policy confirmation,
details of your coverage and claim form.
Please refer to the claim form for procedures on how to claim.
\r\n
\r\n
If you notice any errors in your policy confirmation
or need to change your trip before the effective date,
please inform the agent/school/agency you purchased
the insurance from immediately.
\r\n
\r\n
Should you have any trouble viewing the attached document,
please download the latest version of Adobe Reader :
http://get.adobe.com/reader.
\r\n
\r\n
\r\n
Regards,
\r\n
\r\n
<?php if (empty($asagent)) { ?>
JF Insurance Agency Group Inc.
\r\n
15 Wertheim Court, Suite #501
\r\n
Richmond Hill, ON L4B 3H7
\r\n
Tel: 905-707-1512 Fax: 905-707-1513
\r\n
Email: Info@jfgroup.ca
\r\n
Website: www.jfgroup.ca
<?php } else { 
echo $beuser["business"]."\r\n\n";
echo $beuser["address"]."\r\n\n";
echo $beuser["city"]." ".$beuser["province2"]." ".$beuser["[postcode]"]."\r\n\n";
echo "Tel: ".$beuser["business_phone"]."\r\n\n";
echo $this->lang->line("Email").": ".$beuser["email"]."\r\n\n";
echo $beuser["website"]."\r\n";
} ?>
