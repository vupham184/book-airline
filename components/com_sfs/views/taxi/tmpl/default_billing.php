<?php
defined('_JEXEC') or die;
?>
<div class="fieldset-title float-left">
	<span class="fs-16"><?php echo JText::_('COM_SFS_TAXI_BILLING_DETAIL');?> </span>
</div>
<div class="fieldset-fields float-left" style="width: 584px; padding-top: 35px;">
	<div class="register-field clear floatbox">
		<label><?php echo JText::_('COM_SFS_REGISTERED_NAME');?> </label>
		<?php echo $this->item->billing_registed_name   ;?>
	</div>
	<div class="register-field clear floatbox">
		<label><?php echo JText::_('COM_SFS_ADDRESS');?> </label>
		<?php echo $this->item->billing_address;?>
	</div>
	<div class="register-field clear floatbox">
		<label><?php echo JText::_('COM_SFS_CITY');?> </label>
		<?php echo $this->item->billing_city ;?>
	</div>

	<div class="register-field clear floatbox">
		<label><?php echo JText::_('COM_SFS_ZIP_CODE');?> </label>
		<?php echo $this->item->billing_zipcode ;?>
	</div>
	<div class="register-field clear floatbox">
		<label><?php echo JText::_('COM_SFS_COUNTRY');?> </label>
		<?php echo SfsHelperField::getCountryName( $this->item->billing_country_id ) ;?>
	</div>
	<div class="register-field clear floatbox">
		<label><?php echo JText::_('COM_SFS_TVA_NUMBER');?> </label>
		<?php echo $this->item->billing_vat_number;?>
	</div>
</div>
