<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
	<div class="row">
		<div class="col-sm-12 nopm small">
			<P style="padding-bottom: -1em;"><B>We wish you a safe and pleasant trip!</B></P>
			<P style="padding-bottom: -1em;"><B>Please retain this confirmation as your receipt.</B></P>
			<P style="padding-bottom: -1em;"><B>Please read your policy before traveling as your coverage may include certain limitation and exclusion.</B></P>
<?php if ($plan['product_short'] != 'TOPN') { ?>
			<P style="padding-bottom: -1em;">For Trip Cancellation & Interruption Plans, coverage is not provided for any pre-existing medical condition for you, your friend, a family member, a Travelling companion or travelling companion’s family member, or a key employee, that was not stable within the 90 days (age 0-59), 180 days (age 60-74) immediately precedes the application date.</P>
<?php } ?>
			<P style="padding-bottom: -1em;">Coverage can be extended by contacting JF Insurance provided that a claim has not been made under this policy, that you remain eligible for insurance and that the required premium is charge to your credit card. Should you note any discrepancies in the above information, or if you have any question, please do not hesitate to contact your Travel Agent.</P>
			<P style="padding-bottom: -1em;">Please read and understand the enclosed, which fully explains the terms, conditions, limitations and exclusions that are part of your policy.</P>
			<P style="padding-top: 1em; padding-bottom: -1em;"><B><U>In the event of an emergency, contact Ontime Care Worldwide Inc: Toll free Canada/USA: 1-888-988-3268 or call collect 905-707-9555</U></B></P>
			<P style="padding-bottom: -1em;">Ontime Care Worldwide Inc. must be notified prior to any surgery being performed or within 24 hours of admission to a hospital. Failure to do so, without reasonable cause, will result in the reduction of eligible benefit amounts payable.</P>
			<P style="padding-bottom: -1em;">Underwritten by Berkley Canada</P>
<?php if ($plan['product_short'] == 'TOPN') { ?>
			<P style="padding-top: 1em; padding-bottom: -1em;"><B><U>Important Notice</B></P>
			<P style="padding-bottom: -1em;">This policy does not cover pre-existing medical conditions - sickness, injury or medical conditions that existed prior to the effective date</P>
<?php } else { ?>
			<P style="padding-top: 1em; padding-bottom: -1em;"><B><U>EXCLUSIONS</B></P>
			<P style="padding-bottom: -1em;"><B>Emergency Hospital and Medical does not cover losses or expenses caused directly or indirectly, in whole or in part, by:</B></P>
			<P style="padding-bottom: -1em;">Any sickness, injury or medical condition (other than a minor ailment) that was not stable:</P>
			<P style="padding-bottom: -1em; padding-left: 4em;">a) In the 90 days prior to the effective date if you are less than 60 years of age; or</P>
			<P style="padding-bottom: -1em; padding-left: 4em;">b) In the 180 days prior to the effective date if you are between 60 and 84 years of age.</P>
<?php } ?>
		</div>
	</div>
