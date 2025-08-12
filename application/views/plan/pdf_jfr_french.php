<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
+<!DOCTYPE html>
+<html>
<head>
  <meta charset="UTF-8" />
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>PDF File</title>
  <?php echo $style; ?>
</head>

<body>
  <header>
    <!--p class="rh">JF Group</p-->
  </header>
  <div class="container">
    <div class="row">
      <?php if ($withlogo) { ?>
        <div style="float:left;width:90px;">
          <img class="img-responsive" style="width:80px;" src="<?php echo base_url('agent/img') . '/' . $user['pdf_logo']; ?>" />
        </div>
      <?php } ?>
      <div style="float:left;width:400px;">
        <?php if ($user['user_group_id'] > 100) { ?>
          <p class="topp" style="font-weight:bold;"><?php echo empty($user['business']) ? 'JF Agent' : htmlspecialchars($user['business']); ?> - <span style="text-transform: capitalize;font-weight:bold;"><?php echo ($user) ? htmlspecialchars($user['firstname'] . " " . $user['lastname']) : ''; ?></span></p>
          <p class="topp"><?php echo ($user) ? htmlspecialchars($user['address'] . ', ' . $user['city'] . ' ' . $user['province2'] . ' ' . $user['postcode']) : ''; ?></p>
          <p class="topp"><?php echo ($user) ? htmlspecialchars($user['business_phone']) : ''; ?></p>
          <?php if (!empty($user['website'])) { ?>
            <p class="topp"><?php echo htmlspecialchars($user['website']); ?></p>
          <?php } ?>
        <?php } ?>
      </div>
    </div>
    <div class="row">
      <div class="col-sm-12 text-center">
        <h2 style="margin:-15px 0 0;"><?php if ($plan['status_id'] < 2) { ?>Quote<?php } else { ?>Confirmation<?php } ?> d’assurance</h2>
      </div>
    </div>
    <div class="row" style="margin-top: -15px;">
      <div class="col-sm-12 nopadding">
        <h4><u>Détails de la police</u></h4>
      </div>
    </div>
    <div class="row" style="margin-top: -15px;">
      <div class="col-sm-6 nopadding">
        <h4>Assuré:: <span><?php echo htmlspecialchars($customer['firstname'] . " " . $customer['lastname']); ?></span></h4>
        <h4>Date de naissance: <span><?php echo $customer['birthday']; ?></span></h4>
        <h4>Adresse: <span><?php if (!empty($plan['suite_number'])) {
                              echo  "Suite " . htmlspecialchars($plan['suite_number']) . " - ";
                            } ?><?php echo htmlspecialchars($plan['street_number'] . ' ' . $plan['street_name']) . ' ' . htmlspecialchars($plan['city'] . ', ' . $plan['province2'] . ' ' . $plan['postcode']); ?></span>
        </h4>
        <h4>Numéro de téléphone: <span><?php echo htmlspecialchars($plan['phone1']); ?></span></h4>
        <h4>Courriel: <span><?php echo htmlspecialchars($plan['contact_email']); ?></span></h4>
      </div>
      <div class="col-sm-1 nopadding">
        &nbsp;
      </div>
      <div class="col-sm-5 nopadding2">
        <h4>Numéro de <?php if ($plan['status_id'] < 2) { ?>Quote<?php } else { ?>Policy<?php } ?>: <span><?php echo $plan['policy']; ?></span></h4>
        <h4>Date d'application: <span><?php echo $plan['apply_date']; ?></span></h4>
        <h4>Date d’entrée en vigueur: <span><?php echo $plan['effective_date']; ?></span></h4>
        <h4>Date d'échéance: <span><?php echo $plan['expiry_date']; ?></span></h4>
        <h4>Nombre de jours: <span><?php echo $plan['totaldays']; ?></span></h4>
        <h4><br/><br/></h4>
      </div>
    </div>
    <!-- end policy detail -->
    <div style="display:block;">
	    <hr style="margin:3px auto;" />
    </div>

    <!-- Coverage and payment Details-->
    <div class="row" style="margin-top: -15px;">
      <div class="col-sm-6 nopadding">
        <h4><u>Détails de la couverture</u></h4>
        <h4>Régime d'assurance::<br />&nbsp;&nbsp;&nbsp;<span><?php echo $plan_full_name; ?></span></h4>
        <h4>Type de régime: <span><?php if ($plan['isfamilyplan'] == 1) { echo "Family"; } else { echo "Individual";} ?></span></h4>
        <h4>Montant assuré: <span>$<?php echo number_format($plan['sum_insured'], 2); ?></span></h4>
        <h4>Déductible: <span>$<?php echo number_format($plan['deductible_amount'], 2); ?></span></h4>
        <h4>Bénéficiaire: <span><?php echo htmlspecialchars($plan['beneficiary']); ?></span></h4>
      </div>
      <div class="col-sm-1 nopadding2">
        &nbsp;
      </div>
      <div class="col-sm-5 nopadding">
        <?php if ($withprice) { ?>
          <h4><u>Détails de paiement</u></h4>
          <h4>Prime Totale: <span>$<?php echo number_format($plan['premium'], 2, '.', ','); ?></span></h4>
          <h4>Prime: <span>$<?php echo number_format((float)$plan['premium'] - (float)$plan['tax'], 2, '.', ','); ?></span></h4>
          <h4>Impôt: <span>$<?php echo number_format($plan['tax'], 2, '.', ','); ?></span></h4>
          <h4>Date de paiement: <span><?php echo (($plan['status_id'] >= 2) && isset($payment['added'])) ? date('Y-m-d', strtotime($payment['added'])) : ''; ?></span></h4>
          <h4>Mode de paiement: <span><?php echo (($plan['status_id'] >= 2) && isset($payment['pay_mothed'])) ? $payment['pay_mothed'] : ''; ?></span></h4>
        <?php } ?>
      </div>
    </div>

    <div class="row">
      <div class="col-sm-12 nopm special-note">
        <h4 style="border-bottom:1px solid #777;">Remarque spécialee</h4>
      </div>
    </div>
    <div class="row">
      <div class="col-sm-12 nopm">
        <p class="small" style="margin-top:2px;"><b>Période d'attente.</b> - Lorsque la couverture est souscrite à n'importe quel moment après votre arrivée au Canada :<br />
          a. Si vous êtes âgé de 86 ans ou plus, vous n'aurez pas droit à un remboursement pour une maladie ou des symptômes qui se sont manifestés ou qui ont été contractés ou traités dans les 15 jours suivant la date d'entrée en vigueur de la présente police<br />
          b. Si vous avez 85 ans ou moins, vous n'avez pas droit au remboursement des maladies ou symptômes qui se sont manifestés ou qui ont été contractés ou traités dans les 48 heures suivant la date d'entrée en vigueur de la présente police.<br />
          c. Pour tous les âges, si la police a été souscrite 30 jours ou plus après l'arrivée au Canada, en ce qui concerne la maladie, vous n'aurez pas droit à un
            remboursement pour une maladie ou des symptômes qui se sont manifestés ou qui ont été contractés ou traités dans les sept jours suivant la date
            d'entrée en vigueur de la présente police. Le délai d'attente peut être supprimé par la compagnie administratrice sous certaines conditions avant la
            souscription de cette police. Vous devez recevoir une confirmation écrite de la part de la compagnie administratrice que le délai d'attente a été
            supprimé. Veuillez-vous référer à la section IV : Convention d'assurance de la police pour plus de détails. Si vous remarquez des erreurs dans les
            informations ci-dessus ou si vous avez des questions, veuillez contacter JF Insurance Agency Group Inc.<br />
        </p>
        <p class="small" style="margin-top:2px;"><b>Veuillez conserver cette confirmation comme reçu.</b><br />
        Veuillez lire et comprendre le document ci-joint, qui explique en détail les termes, conditions, limitations et exclusions qui font partie de votre police.
        Si votre état de santé change, y compris vos médicaments, entre la date de la souscription et la date d'entrée en vigueur de la police, vous devez nous contacter pour vous
        assurer que vous restez éligible à cette assurance. Ontime Care Worldwide Inc. doit être avisé avant toute intervention chirurgicale ou dans les 24 heures suivant
        l'admission à l'hôpital. Tout manquement à cette obligation, sans motif raisonnable, entraînera une réduction des montants éligibles payables.<br />
        EN CAS D'URGENCE, CONTACTEZ ONTIME CARE WORLDWIDE INC IMMÉDIATEMENT :<br />
        SANS FRAIS AU CANADA/AUX ÉTATS-UNIS : 1-888-988-3268 SI VOUS NE POUVEZ PAS NOUS CONTACTER SANS FRAIS, VEUILLEZ APPELER À FRAIS VIRÉS : 905-707-9555<br />
        </p>
        <p class="small" style="margin-top:2px;">
        Si vous remarquez des erreurs dans les renseignements ci-dessus ou si vous avez des questions, veuillez communiquer avec JF Insurance Agency Group Inc.<br />
        </p>
      </div><br />
    </div>
    <div class="row">
      <div class="col-sm-12 nopm">
        <p class="small">Ontario:<br />
          15 Wertheim Court, Suite 501,<br />
          Richmond Hill, ON, Canada L4B 3H7<br />
          Phone: 905-707-1512 Or 1-877-832-5541</p>
      </div>
      <!-- <div class="col-sm-2 nopm">
      </div>
      <div class="col-sm-4 nopm">
        <p class="small">British Columbia:<br />
          128 - 6061 No. 3 Road<br />
          Richmond, BC, Canada V6Y 282<br />
          Phone: 604-232-0896 Or 1-877-232-0896</p>
      </div> -->
    </div>
  </div><!-- End Container -->
</body>
</html>
