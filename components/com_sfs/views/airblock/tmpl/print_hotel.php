<?php
defined('_JEXEC') or die;
?>
<div class="billing-information">
    <div><?php echo $this->hotel->name; ?></div>
    <div>Registered Name: <?php echo $this->hotel->billing_name; ?></div>
    <div><?php echo $this->hotel->billing_address; ?>,</div>
    <div><?php echo $this->hotel->billing_zipcode; ?>,</div>
    <div><?php echo $this->hotel->billing_city; ?>, <?php echo !empty($this->hotel->billing_state) ? $this->hotel->billing_state.',':''; ?> <?php echo $this->hotel->billing_country; ?></div>
    <div>Ph: <?php echo $this->hotel->telephone ; ?></div>
    <div>Fax: <?php echo $this->hotel->fax 	; ?></div>
    <div>TVA number: <?php echo $this->hotel->tva_number; ?></div>
</div>

<div class="sales-contact">
    <div>Sales contact: <?php echo $this->contact->gender.' '.$this->contact->name.' '.$this->contact->surname ?></div>
    <div>Direct Ph: <?php echo $this->contact->telephone; ?></div>
    <div>Email: <?php echo $this->contact->email; ?></div>
</div>
