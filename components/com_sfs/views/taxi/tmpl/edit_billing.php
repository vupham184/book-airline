<?php 
defined('_JEXEC') or die; 
?>
<div class="fieldset-title float-left">
	<span class="fs-16"><?php echo JText::_('COM_SFS_TAXI_BILLING_DETAIL');?> </span>
</div>

<div class="fieldset-fields float-left" style="width: 584px; padding-top: 35px;">
	<div class="register-field clear floatbox">
		<label><?php echo JText::_('COM_SFS_REGISTERED_NAME');?> </label>
		<input type="text" name="taxidetails[billing_registed_name]" id="billing_name" class="required" value="<?php echo $this->item->billing_registed_name ; ?>" />
	</div>
	<div class="register-field clear floatbox">
		<label><?php echo JText::_('COM_SFS_ADDRESS');?> </label>
		<div class="float-left">
			<div style="margin-bottom: 7px;">
				<input type="text" name="taxidetails[billing_address]" id="billing_address" size="40" class="required" value="<?php echo $this->item->billing_address; ?>" />
			</div>									
		</div>
	</div>
	<div class="register-field clear floatbox">
		<label><?php echo JText::_('COM_SFS_CITY');?> </label> 
		<input type="text" name="taxidetails[billing_city]" id="billing_city" class="required" value="<?php echo $this->item->billing_city; ?>" />
	</div>
	<div class="register-field clear floatbox" style="display:none;">
		<label><?php echo JText::_('COM_SFS_STATE');?> </label>
		<?php //echo SfsHelperField::getStateField( 'taxidetails[billing_state_id]' , $this->item->billing_state_id ); ?>
	</div>
	<div class="register-field clear floatbox">
		<label><?php echo JText::_('COM_SFS_ZIP_CODE');?> </label> 
		<input type="text" name="taxidetails[billing_zipcode]" id="billing_zipcode" class="required" value="<?php echo $this->item->billing_zipcode; ?>" />
	</div>
	<div class="register-field clear floatbox">
		<label><?php echo JText::_('COM_SFS_COUNTRY');?> </label>
		<?php echo SfsHelperField::getCountryField( 'taxidetails[billing_country_id]' , $this->item->billing_country_id ); ?>
	</div>
	<div class="register-field clear floatbox">
		<label><?php echo JText::_('COM_SFS_TVA_NUMBER');?> </label> 
		<input type="text" name="taxidetails[billing_vat_number]" id="billing_tvanumber" class="required" value="<?php echo $this->item->billing_vat_number; ?>" />
	</div>
</div>
