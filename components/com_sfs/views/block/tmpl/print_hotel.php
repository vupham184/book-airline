<?php
defined('_JEXEC') or die;
$billing = $this->airline->billing_details;
?>
<div class="billing-information">
	<div><?php echo !empty($this->airline->company_name) ? $this->airline->company_name : $this->airline->airline_name ; ?></div>
	<div>Registered Name: <?php echo $billing->name; ?></div>
	<div><?php echo $billing->address; ?>, </div>
	<div><?php echo $billing->zipcode; ?>, </div>
	<div><?php echo $billing->city; ?>, <?php echo !empty($billing->state_name) ? $billing->state_name.',':''; ?><?php echo $billing->country_name; ?></div>
	<div>Ph: <?php echo $this->airline->telephone ; ?></div>     
	<div>TVA number: <?php echo $billing->tva_number; ?></div>
</div>

<div class="sales-contact">
    <div>Sales contact: <?php echo $this->contact->gender.' '.$this->contact->name.' '.$this->contact->surname ?></div>
    <div>Direct Ph: <?php echo $this->contact->telephone; ?></div>
    <div>Email: <?php echo $this->contact->email; ?></div>
</div>