<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
Dear <?php echo htmlspecialchars($customer['firstname'] . " " . $customer['lastname']); ?>,

We are contacting you regarding your insurance policy <?php echo $plan['policy']; ?>, purchased on <?php echo $plan['apply_date']; ?>.

Our records indicate that payment for this policy was not received and we were not able to contact with you. As a result, your policy has been terminated as of <?php echo $plan['expiry_date']; ?>.

If you believe this termination is in error or if you have any questions, please contact your agent immediately to discuss possible options.

Please contact us at (905) 707-1512 or by email at info@jfgroup.ca

Thank you for your immediate attention to this matter.


Sincerely,



JF Insurance Agency Group Inc.
15 Wertheim Court, Suite #501
Richmond Hill, ON L4B 3H7
Tel: 905-707-1512 Fax: 905-707-1513
Email: Info@jfgroup.ca
Website: www.jfgroup.ca




Cher(e) <?php echo htmlspecialchars($customer['firstname'] . " " . $customer['lastname']); ?>,

Nous vous contactons au sujet de votre police d'assurance <?php echo $plan['policy']; ?>, souscrite le <?php echo $plan['apply_date']; ?>.

Nos dossiers indiquent que le paiement de cette police n'a pas été reçu et que nous n'avons pas réussi à communiquer avec vous. Par conséquent, votre police a été résiliée à compter du <?php echo $plan['expiry_date']; ?>.

Si vous estimez que cette résiliation est une erreur ou si vous avez des questions, veuillez contacter immédiatement votre agent pour discuter des options possibles.

Veuillez nous contacter au (905) 707-1512 ou par courriel à l'adresse info@jfgroup.ca.

Nous vous remercions de l'attention immédiate que vous porterez à cette question.

Cordialement,

JF Insurance Agency Group Inc.
15 Wertheim Court, Suite #501
Richmond Hill, ON L4B 3H7