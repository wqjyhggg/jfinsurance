<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
Dear <?php echo htmlspecialchars($customer['firstname'] . " " . $customer['lastname']); ?>,

This is a confirmation of your recent insurance quotation.
Please DO NOT REPLY to this email.

Thank you for requesting a medical insurance quotation
 from JF Insurance Agency Group Inc.

Please refer to the attached quotation for the details 
of your requested coverage and premium.

This quotation is for reference only and does not provide 
insurance coverage. No insurance coverage is in effect 
until the application has been approved, payment has been 
received, and a policy has been issued.


If you notice any errors in your quotation or need to 
make any changes, please contact the agent/school/agency 
through whom you requested the quotation immediately.

Should you have any trouble viewing the attached document, 
please download the latest version of Adobe Reader:
http://get.adobe.com/reader.


Regards,


<?php if (empty($asagent)) { ?>
JF Insurance Agency Group Inc.
15 Wertheim Court, Suite #501
Richmond Hill, ON L4B 3H7
Tel: 905-707-1512 Fax: 905-707-1513
Email: Info@jfgroup.ca
Website: www.jfgroup.ca
<?php } else { 
echo $beuser["business"];
echo $beuser["address"];
echo $beuser["city"]." ".$beuser["province2"]." ".$beuser["[postcode]"];
echo "Tel: ".$beuser["business_phone"];
echo $this->lang->line("Email").": ".$beuser["email"];
echo $beuser["website"];
} ?>
