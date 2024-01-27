<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>PDF File</title>
  <?php echo $style; ?>
</head>

<body>
  <header>
    <!--p class="rh">JF Group</p-->
  </header>
  <div class="container" style="padding: 0 40px;">
    <div class="row" style="padding-top: 60px;">
      <div class="col-sm-12 text-left">
        <p class="topp" style="font-weight:bold;">Confirmation of Coverage</p>
        <p class="topp"><?php echo date("Y-m-d"); ?></p>
      </div>
      <div class="col-sm-1 text-left">
        &nbsp;
      </div>
      <div class="col-sm-12 text-left">
        <h4>RE: Confirmation of Coverage for <span><?php echo htmlspecialchars($customer['firstname'] . " " . $customer['lastname']); ?></span></h4>
      </div>
      <div class="col-sm-12 text-left">
        <h4>Policy Number: <span><?php echo $plan['policy']; ?></span></h4>
      </div>
      <div class="col-sm-12 text-left">
        <h4>To Whom It May Concern:</h4>
      </div>
    </div>
    <div class="row">
      <div class="col-sm-12 nopm">
        <p class="small" style="margin-top:2px;">
          Please be advised that <?php echo htmlspecialchars($customer['firstname'] . " " . $customer['lastname']); ?> has purchased JF
          Canadian Travel Insurance policy number <?php echo $plan['policy']; ?> effective <?php echo $plan['effective_date']; ?> to
          <?php echo $plan['expiry_date']; ?>.
          The policy is administered by JF Insurance Agency Group Inc., and underwritten by Berkley Insurance Company (Berkley),
          Berkley has rated A+ (Superior) by A.M. Best Company and A+ (Strong) by Standard & Poor’s.
        </p>
        <p class="small" style="margin-top:2px;">
          Emergency Medical coverage is provided while traveling worldwide outside of the insured person’s Country of
          Residence, per policy provisions.
          The maximum limit of coverage per period of insurance is $5,000,000 CAD. Coverage includes the Schengen states
          per the policy provisions.
        </p>
        <p class="small" style="margin-top:2px;">
          Emergency evacuation (also known as Repatriation) is provided up to a maximum benefit of 250,000 CAD and
          Return of Mortal Remains benefits up to a maximum of $5,000 CAD are included. Coronavirus (COVID-19) and
          related complications coverage is up to a maximum limit of $100,000 CAD.
        </p>
        <p class="small" style="margin-top:2px;">
          A copy of the policy wording, which provides an outline of the plan’s coverage, limitations, and maximum
          benefits may be presented as required.
          This information will verify that Eligible Expenses, including Hospitalization expenses, are subject to a 0.00
          CAD annual deductible.
        </p>
        <p class="small" style="margin-top:2px;">
          If you need further information, please feel free to contact our office at the number/email listed below.
          Thank you.
        </p>
        <p class="small" style="margin-top:2px;">
          Sincerely,
        </p>
        <p class="small" style="margin-top:2px;">
          JF Insurance Agency Group Inc.
        </p>
        <p class="small" style="margin-top:2px;">
          905-707-1512
        </p>
        <p class="small" style="margin-top:2px;">
          info@jfgroup.ca
        </p>
      </div>
    </div>
  </div>
</body>
</html>