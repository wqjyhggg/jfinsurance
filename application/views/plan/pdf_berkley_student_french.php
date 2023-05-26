<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>PDF File</title>
  <?php echo $style; ?>
</head>

<body>
  <div class="container">
    <div class="row">
      <?php if ($withlogo) { ?>
        <?php if (empty($user['pdf_logo']) || !in_array($plan['product_short'], $pdf_enable)) { ?>
          <div style="float:left;width:90px;">
            <img class="img-responsive" style="width:80px;" src="<?php echo base_url(); ?>image/jf_logo.jpg" />
          </div>
        <?php } else { ?>
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
          <?php } else { ?>
            <p class="topp" style="font-weight:bold;"> JF Agent - <span style="text-transform: capitalize;font-weight:bold;">Johnson Fu</span></p>
            <p class="topp"><?php echo ($user) ? htmlspecialchars($user['address'] . ', ' . $user['city'] . ' ' . $user['province2'] . ' ' . $user['postcode']) : ''; ?></p>
            <p class="topp"><?php echo ($user) ? htmlspecialchars($user['business_phone']) : ''; ?></p>
          <?php } ?>
        </div>
      <?php } ?>
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
        <h4>Assuré: <span><?php echo htmlspecialchars($customer['firstname'] . " " . $customer['lastname']); ?></span></h4>
        <h4>Date de naissance: <span><?php echo $customer['birthday']; ?></span>
        </h4>
        <h4>Adresse: <span><?php if (!empty($plan['suite_number'])) {
                              echo  "Suite " . htmlspecialchars($plan['suite_number']) . " - ";
                            } ?><?php echo htmlspecialchars($plan['street_number'] . ' ' . $plan['street_name']) . ' ' . htmlspecialchars($plan['city'] . ', ' . $plan['province2'] . ' ' . $plan['postcode']); ?></span>
        </h4>
        <h4>Numéro de téléphone: <span><?php echo htmlspecialchars($plan['phone1']); ?></span>
        </h4>
        <h4>Courriel: <span><?php echo htmlspecialchars($plan['contact_email']); ?></span>
        </h4>
      </div>
      <div class="col-sm-1 nopadding">
        &nbsp;
      </div>
      <div class="col-sm-5 nopadding2">
        <h4>Numéro de <?php if ($plan['status_id'] < 2) { ?>Quote<?php } else { ?>Policy<?php } ?> : <span><?php echo $plan['policy']; ?></span></h4>
        <h4>Date d'application: <span><?php echo $plan['apply_date']; ?></span>
        </h4>
        <h4>Date d’entrée en vigueur: <span><?php echo $plan['effective_date']; ?></span>
        </h4>
        <h4>Date d'échéance: <span><?php echo $plan['expiry_date']; ?></span>
        </h4>
        <h4>Nombre de jours: <span><?php echo $plan['totaldays']; ?></span>
        </h4>
        <h4><br /><br /></h4>
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
        <h4>Régime d'assurance:<br />&nbsp;&nbsp;&nbsp;<span><?php echo $plan_full_name; ?></span></h4>
        <h4>Type de régime: <span><?php if ($plan['isfamilyplan'] == 1) { echo "Family"; } else { echo "Individual";} ?></span></h4>
        <h4>Montant assuré: <span>$<?php echo number_format($plan['sum_insured'], 2); ?></span></h4>
        <h4>Bénéﬁciaire: <span><?php echo htmlspecialchars($plan['beneficiary']); ?></span></h4>
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

    <?php if ($plan['isfamilyplan']) { ?>
      <div class="row">
        <div class="col-sm-12" style="padding:0;">
          <p><u><?php echo ($plan['isfamilyplan'] == 1) ? 'Family' : 'Group'; ?>Members</u>
            <?php if (($plan['product_short'] == 'JFR') || ($plan['product_short'] == 'OPL')) { ?>
              &nbsp;&nbsp;&nbsp;&nbsp; ( Coverage is per person per trip )
            <?php } ?>
          </p>
        </div>
      </div>
      <div class="row" style="margin-top: -35px;margin-bottom: 0">
        <?php for ($i = 0; $i < sizeof($customers); $i++) { ?>
          <?php if (empty($customers[$i]['lastname']) && empty($customers[$i]['firstname'])) continue; ?>
          <div class="col-sm-4" style="padding:0;">
            <p style="margin-bottom: 0;"><span><?php echo htmlspecialchars($customers[$i]['firstname']  . " " . $customers[$i]['lastname']); ?></span>&nbsp;&nbsp;&nbsp;&nbsp;<span><?php echo $customers[$i]['birthday']; ?></span></p>
          </div>
        <?php } ?>
      </div>
    <?php } ?>
    <!-- End Family Member -->
    <div class="row">
      <div class="col-sm-12 nopm special-note">
        <h4 style="border-bottom:1px solid #777;">Remarque spécialee</h4>
      </div>
    </div>
    <div class="row">
      <div class="col-sm-12 nopm">
        <p class="small" style="margin-top:2px;">Toutes les personnes assurées sont soumises aux termes et conditions ci-dessous.</p>
        <p class="small" style="margin-top:2px;">Le présent document ne constitue pas l'intégralité de la police d'assurance et il a été conseillé à l'assuré de lire l'intégralité des détails de la couverture et des exclusions de la police.</p>
        <p class="small" style="margin-top:2px;">Les conditions d'admissibilité du régime demandé sont importantes pour le risque pour lequel l'assurance est demandée. Si l'assuré ne satisfait pas aux conditions d'admissibilité du régime choisi, ou s'il y a fausse déclaration, dissimulation ou omission de divulguer des faits ou des questions concernant l'assuré et faisant l'objet du formulaire de demande, aucune couverture d'assurance ne sera fournie.</p>
        <p class="small" style="margin-top:2px;">L'assuré est conscient que cette assurance couvre les urgences (telles que déﬁnies dans la police) et peut ne pas couvrir les frais encourus après que l'assuré ait pu rentrer chez lui pour être soigné. L'assuré sait que les affections préexistantes (telles que déﬁnies dans la police) sont exclues dans certaines circonstances et que de plus amples détails sont fournis dans la police.</p>
        <p class="small" style="margin-top:2px;">EN CAS D'URGENCE MÉDICALE OU DE SINISTRE POUVANT NÉCESSITER OU ENTRAÎNER UNE HOSPITALISATION, APPELEZ ONTIME CARE WORLDWIDE INC. IMMÉDIATEMENT</p>
        <br />
        <p class="small" style="margin-top:2px;">Si vous remarquez des erreurs dans les renseignements ci-dessus ou si vous avez des questions, veuillez communiquer avec JF Insurance Agency Group Inc.</p>
      </div>
    </div>
    <br />
    <div class="row">
      <div class="col-sm-4 nopm">
        <p class="small">Ontario:<br />
          15 Wertheim Court, Suite 501,<br />
          Richmond Hill, ON, Canada L4B 3H7<br />
          Phone: 905-707-1512 Or 1-877-832-5541</p>
      </div>
      <div class="col-sm-2 nopm">
      </div>
      <div class="col-sm-4 nopm">
        <p class="small">British Columbia:<br />
          128 - 6061 No. 3 Road<br />
          Richmond, BC, Canada V6Y 282<br />
          Phone: 604-232-0896 Or 1-877-232-0896</p>
      </div>
    </div>
  </div>
 </body>

</html>