<?php
defined('_JEXEC') or die;?>


<div class="fieldset-title float-left">
    <span style="line-height:120%;" class="fs-16"><?php echo JText::_("COM_SFS_BILLING_DETAIL");?></span> 
</div>    

<div class="fieldset-fields float-left fs-14" style="width:584px; padding-top:35px;">

    <div class="register-field clear floatbox">
    	<label for="billing_registered_name">Registered company name :</label>
        <?php echo $this->billing->name; ?>
    </div>
    
    <div class="register-field clear floatbox">
   		<label for="billing_address"><?php echo JText::_('COM_SFS_ADDRESS');?> :</label>
	    <?php echo $this->billing->address; ?>	
    </div>
    
        
    <div class="register-field clear floatbox">
    	<label for="billing_city"><?php echo JText::_('COM_SFS_CITY');?> :</label>
        <?php echo $this->billing->city; ?>
    </div>
    
    <div class="register-field clear floatbox">
    	<label for="billing_zip"><?php echo JText::_('COM_SFS_ZIP_CODE');?> :</label>
        <?php echo $this->billing->zipcode; ?>
    </div>
    
    <div class="register-field clear floatbox">
    	<label><?php echo JText::_('COM_SFS_COUNTRY');?> :</label>
        <?php echo $this->billing->country_name; ?>
    </div>
  
    <div class="register-field clear floatbox">
    	<label for="tva"><?php echo JText::_('COM_SFS_TVA_NUMBER');?> :</label>
        <?php echo $this->billing->tva_number; ?>
    </div>
    
    <div class="register-field clear floatbox fs-12">This address will be used for invoice purpose only.</div>
    
</div>