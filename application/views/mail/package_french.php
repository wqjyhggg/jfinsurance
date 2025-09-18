<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
\r\n
Madame, Monsieur <?php echo htmlspecialchars($customer['firstname'] . " " . $customer['lastname']); ?>,
\r\n
\r\n
Ceci est une confirmation de l'assurance que vous avez récemment
souscrite. NE RÉPONDEZ PAS à ce courriel.
\r\n
\r\n
Nous vous remercions d'avoir souscrit une assurance médicale
auprès de JF Insurance Agence Group Inc.
\r\n
\r\n
Veuillez-vous référer aux documents ci-joints pour la
confirmation de votre police, les détails de votre 
couverture et le formulaire
de demande de remboursement.
Veuillez-vous référer au formulaire de demande de 
remboursement
pour connaître les procédures à suivre. 
\r\n
\r\n
Si vous constatez des erreurs dans votre confirmation 
de police ou
si vous voulez modifier votre voyage avant la date 
d'entrée
en vigueur, 
veuillez informer immédiatement l'agent, 
l'école ou l'agence
auprès de laquelle vous avez souscrit l'assurance. 
\r\n
\r\n
Si vous rencontrez des difficultés à visualiser le 
document ci-joint,
veuillez télécharger la dernière version d'Adobe Reader
http://get.adobe.com/reader. 
\r\n
\r\n
\r\n
Cordialement,
\r\n
\r\n
<?php if (empty($asagent)) { ?>
JF Insurance Agency Group Inc.
\r\n
15 Wertheim Court, Suite #501
\r\n
Richmond Hill, ON L4B 3H7
\r\n
Tél: 905-707-1512 Fax: 905-707-1513
\r\n
Courriel: Info@jfgroup.ca
\r\n
Site Internet: www.jfgroup.ca
<?php } else { 
echo $beuser["business"]."\r\n";
echo $beuser["address"]."\r\n";
echo $beuser["city"]." ".$beuser["province2"]." ".$beuser["[postcode]"]."\r\n";
echo "Tel: ".$beuser["business_phone"]."\r\n";
echo $this->lang->line("Email").": ".$beuser["email"]."\r\n";
echo $beuser["website"]."\r\n";
} ?>
