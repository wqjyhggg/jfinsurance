<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
Dear <?php echo htmlspecialchars($customer['firstname'] . " " . $customer['lastname']); ?>,

This is a confirmation of your recent purchased insurance,
please DO NOT REPLY to this email.

Thank you for purchasing medical insurance from
JF Insurance Agency Group Inc.

Please refer to the attached documents for your policy confirmation,
details of your coverage and claim form.
Please refer to the claim form for procedures on how to claim.

If you notice any errors in your policy confirmation
or need to change your trip before the effective date,
please inform the agent/school/agency you purchased
the insurance from immediately.

Should you have any trouble viewing the attached document,
please download the latest version of Adobe Reader :
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
