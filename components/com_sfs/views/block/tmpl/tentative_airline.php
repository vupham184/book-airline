<?php
defined('_JEXEC') or die;
$billing = $this->airline->billing_details;
?>
<div class="customer-information" data-step="2" data-intro="<?php echo SfsHelper::getTooltipTextEsc('customer_information', $text, 'hotel'); ?>">
	<div class="billing-detail">
		<span><?php echo !empty($this->airline->company_name) ? $this->airline->company_name : $this->airline->airline_name ; ?></span> 
		<span>Registered Name: <?php echo $billing->name; ?> </span> 
		<span><?php echo $billing->address; ?>,</span> 
		<span><?php echo $billing->zipcode; ?>, </span> 
		<span><?php echo $billing->city; ?>, <?php echo !empty($billing->state_name) ? $billing->state_name.',':''; ?>
		<?php echo $billing->country_name; ?> </span> 
		<span>Ph: <?php echo $this->airline->telephone ; ?></span> 
		<span>TVA number: <?php echo $billing->tva_number; ?> </span>
	</div>
	<div class="contact-information">
		<span>Sales contact: <?php echo $this->contact->gender.' '.$this->contact->name.' '.$this->contact->surname ?>
		</span> <span>Direct Ph: <?php echo $this->contact->telephone; ?> </span>
		<span>Email: <?php echo $this->contact->email; ?> </span>
	</div>
</div>
