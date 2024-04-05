<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<br />
Dear <?php echo htmlspecialchars($customer['firstname'] . " " . $customer['lastname']); ?>,
<br />
<br />
This is a confirmation of your recent purchased insurance,
please
<span style="color:red;">
DO NOT REPLY
</span> to this email.
<br />
<br />
Thank you for purchasing medical insurance from
JF Insurance Agency Group Inc.
<br />
<br />
Please refer to the attached documents for your policy confirmation,
details of your coverage and claim form.
Please refer to the claim form for procedures on how to claim.
<br />
<br />
If you notice any errors in your policy confirmation
or need to change your trip before the effective date,
please inform the agent/school/agency you purchased
the insurance from immediately.
<br />
<br />
Should you have any trouble viewing the attached document,
please download the latest version of Adobe Reader :
http://get.adobe.com/reader.
<br />
<br />
<br />
Regards,
<br />
<br />
<?php if (empty($asagent)) { ?>
JF Insurance Agency Group Inc.
<br />
15 Wertheim Court, Suite #501
<br />
Richmond Hill, ON L4B 3H7
<br />
Tel: 905-707-1512 Fax: 905-707-1513
<br />
Email: Info@jfgroup.ca
<br />
Website: www.jfgroup.ca
<?php } else { 
echo $beuser["business"]."<br />";
echo $beuser["address"]."<br />";
echo $beuser["city"]." ".$beuser["province2"]." ".$beuser["[postcode]"]."<br />";
echo "Tel: ".$beuser["business_phone"]."<br />";
echo $this->lang->line("Email").": ".$beuser["email"]."<br />";
echo $beuser["website"]."<br />";
} ?>