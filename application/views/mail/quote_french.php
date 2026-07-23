<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
Cher/Chère <?php echo htmlspecialchars($customer['firstname'] . " " . $customer['lastname']); ?>,


La présente confirme l'émission de votre récente soumission 
d'assurance. Veuillez ne pas répondre à ce courriel.


Nous vous remercions d'avoir choisi JF Insurance Agency Group 
Inc. pour votre demande de soumission d'assurance médicale.

Vous trouverez ci-joint votre soumission, qui présente les détails 
de la couverture demandée ainsi que la prime applicable.

Veuillez noter que cette soumission est fournie à titre informatif 
seulement et ne constitue en aucun cas une couverture d'assurance. 
Aucune protection d'assurance n'entre en vigueur tant que votre 
demande n'a pas été approuvée, que le paiement de la prime n'a 
pas été reçu et qu'une police d'assurance n'a pas été émise.

Nous vous invitons à examiner attentivement les renseignements 
figurant dans votre soumission. Si vous constatez une erreur ou 
souhaitez apporter des modifications, veuillez communiquer sans 
délai avec l'agent, l'établissement d'enseignement ou l'organisme 
par l'intermédiaire duquel vous avez effectué votre demande.

Si vous éprouvez des difficultés à ouvrir le document ci-joint, 
nous vous invitons à télécharger la plus récente version d'Adobe 
Reader à l'adresse suivante :
http://get.adobe.com/reader. 

Nous vous remercions de votre confiance.

Veuillez agréer, Cher/Chère <?php echo htmlspecialchars($customer['firstname'] . " " . $customer['lastname']); ?>, 
l'expression de nos salutations distinguées.



<?php if (empty($asagent)) { ?>
JF Insurance Agency Group Inc.
15 Wertheim Court, Suite #501
Richmond Hill, ON L4B 3H7
Téléphone: 905-707-1512
Télécopieur: 905-707-1513
Courriel: Info@jfgroup.ca
Site Web: www.jfgroup.ca
<?php } else { 
echo $beuser["business"]."\r\n";
echo $beuser["address"]."\r\n";
echo $beuser["city"]." ".$beuser["province2"]." ".$beuser["postcode"]."\r\n";
echo "Tel: ".$beuser["business_phone"]."\r\n";
echo $this->lang->line("Email").": ".$beuser["email"]."\r\n";
echo $beuser["website"]."\r\n";
} ?>
