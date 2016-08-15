<?php
defined('_JEXEC') or die;
$billingDetail = $this->hotel->getBillingDetail();
?>

<legend><span class="text_legend"><?php echo JText::_("COM_SFS_BILLING_DETAIL");?></span></legend>

<div class="col w80 pull-left p20">

	<div class="form-group">
    	<label><?php echo JText::_('COM_SFS_REGISTERED_COMPANY_NAME');?>:</label>
        <div class="col w60">
            <input type="text" class="required" value="<?php echo is_object($billingDetail) ? $billingDetail->name : ''; ?>" name="billing[name]" id="billing_registered_name">
        </div>
    </div>
    
	<div class="form-group">
   		<label><?php echo JText::_('COM_SFS_ADDRESS');?> :</label>
        <div class="col w60">
	       <input type="text" class="required" value="<?php echo is_object($billingDetail) ? $billingDetail->address : ''; ?>" name="billing[address]" id="billing_address">	
        </div>
    </div>
    
	<div class="form-group">
    	<label>&nbsp;</label>
        <div class="col w60">
            <input type="text" value="<?php echo is_object($billingDetail) ? $billingDetail->address1 : ''; ?>" name="billing[address1]" id="billing_address1">
        </div>
    </div>
    
	<div class="form-group">
    	<label>&nbsp;</label>
        <div class="col w60">
            <input type="text" value="<?php echo is_object($billingDetail) ? $billingDetail->address2 : ''; ?>" name="billing[address2]" id="billing_address2">
        </div>
    </div>
        
	<div class="form-group">
    	<label><?php echo JText::_('COM_SFS_CITY');?> :</label>
        <div class="col w60">
            <input type="text" class="required" value="<?php echo is_object($billingDetail) ? $billingDetail->city : ''; ?>" name="billing[city]" id="billing_city">
        </div>
    </div>
    
    <div class="form-group">
    	<label><?php echo JText::_('COM_SFS_ZIP_CODE');?>:</label>
        <div class="col w60">
            <input type="text" class="required" value="<?php echo is_object($billingDetail) ? $billingDetail->zipcode : ''; ?>" name="billing[zipcode]" id="billing_zipcode">
        </div>
    </div>
    
	<div class="form-group">
    	<label><?php echo JText::_('COM_SFS_COUNTRY');?> :</label>
        <div class="col w60">
            <?php echo is_object($billingDetail) ? SfsHelperField::getCountryField('billing[country_id]', (int) $billingDetail->country_id) : SfsHelperField::getCountryField('billing[country_id]'); ?>
        </div>
    </div>    
    
	<div class="form-group">
    	<label for="billing_tva_number"><?php echo JText::_('COM_SFS_TVA_NUMBER');?> :</label>
        <div class="col w60">
            <input class="required" type="text" value="<?php echo is_object($billingDetail) ? $billingDetail->tva_number : ''; ?>" name="billing[tva_number]" id="billing_tva_number">
        </div>
    </div>
    
    <div class="clear floatbox">
    	<p><?php echo JText::_('COM_SFS_TVA_NUMBER_DESC');?></p>
    	<input type="hidden" name="billing[id]" value="<?php echo is_object($billingDetail) ? $billingDetail->id : '0'; ?>" />
    </div>
</div>
