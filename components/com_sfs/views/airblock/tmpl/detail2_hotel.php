<?php
defined('_JEXEC') or die;
?>
<div class="customer-information">
    <div class="billing-detail">
        <span><?php echo $this->hotel->name; ?></span>
        <span>Registered Name: <?php echo $this->hotel->billing_name; ?></span>
        <span><?php if($this->hotel->billing_address) echo $this->hotel->billing_address.","; ?></span>
        <span><?php if($this->hotel->billing_zipcode) echo $this->hotel->billing_zipcode.","; ?></span>
        <span><?php if($this->hotel->billing_city) echo $this->hotel->billing_city.","; ?><?php echo !empty($this->hotel->billing_state) ? $this->hotel->billing_state.',':''; ?> <?php echo $this->hotel->billing_country; ?></span>
        <span>Ph: <?php echo $this->hotel->telephone ; ?></span>
        <span>Fax: <?php echo $this->hotel->fax 	; ?></span>
        <span>TVA number: <?php echo $this->hotel->tva_number; ?></span>
    </div>
    <div class="contact-information">
        <span>Sales contact: <?php echo $this->contact->gender.' '.$this->contact->name.' '.$this->contact->surname ?></span>
        <span>Direct Ph: <?php echo $this->contact->telephone; ?></span>
        <span>Email: <?php echo $this->contact->email; ?></span>
    </div>
</div>