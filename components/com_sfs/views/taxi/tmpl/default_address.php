<?php
defined('_JEXEC') or die;
?>
<div class="fieldset-title float-left">
	<span class="fs-16"><?php echo JText::_('COM_SFS_TAXI_SERVICES_AND_INQUIRIES');?></span>
</div>

<div class="fieldset-fields float-left" style="width: 584px; padding-top: 35px;">
	<div class="register-field clear floatbox">
		<label><?php echo JText::_('COM_SFS_ADDRESS');?> </label>		
		<?php echo $this->item->address ;?>		
	</div>
	<div class="register-field clear floatbox">
		<label><?php echo JText::_('COM_SFS_CITY');?> </label>
		<?php echo $this->item->city ;?>
	</div>
	<div class="register-field clear floatbox">
		<label><?php echo JText::_('COM_SFS_ZIP_CODE');?> </label>
		<?php echo $this->item->zipcode ;?>
	</div>
	<div class="register-field clear floatbox">
		<label><?php echo JText::_('COM_SFS_COUNTRY');?> </label>
		<?php echo SfsHelperField::getCountryName( $this->item->country_id ) ;?>
	</div>
	<div class="register-field clear floatbox">
		<label><?php echo JText::_('COM_SFS_DIRECT_OFFICE_TELEPHONE')?> </label>
		<?php echo $this->item->telephone  ;?>
	</div>
</div>
