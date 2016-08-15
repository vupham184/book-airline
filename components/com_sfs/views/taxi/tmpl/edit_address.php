<?php 
defined('_JEXEC') or die; 
?>

<div class="fieldset-title float-left">
	<span class="fs-16"><?php echo JText::_('COM_SFS_TAXI_SERVICES_AND_INQUIRIES');?></span>
</div>

<div class="fieldset-fields float-left" style="width: 584px; padding-top: 35px;">
	<div class="register-field clear floatbox">
		<label><?php echo JText::_('COM_SFS_ADDRESS');?></label>
		<div class="float-left">
			<div style="margin-bottom: 7px">
				<input type="text" name="taxidetails[address]" id="address" class="required" value="<?php echo $this->item->address; ?>" size="40" />
			</div>
		</div>
	</div>
	<div class="register-field clear floatbox">
		<label><?php echo JText::_('COM_SFS_CITY');?> </label> 
		<input type="text" name="taxidetails[city]" id="city" class="required" value="<?php echo $this->item->city; ?>" />
	</div>
	<div class="register-field clear floatbox" style="display:none;">
		<label><?php echo JText::_('COM_SFS_STATE');?> </label>
		<?php //echo SfsHelperField::getStateField( 'taxidetails[state_id]' , $this->item->state_id ); ?>
	</div>
	<div class="register-field clear floatbox">
		<label><?php echo JText::_('COM_SFS_ZIP_CODE');?> </label> 
		<input type="text" name="taxidetails[zipcode]" id="zipcode" class="required" value="<?php echo $this->item->zipcode; ?>" />
	</div>
	<div class="register-field clear floatbox">
		<label><?php echo JText::_('COM_SFS_COUNTRY');?> </label>
		<?php echo SfsHelperField::getCountryField( 'taxidetails[country_id]' , $this->item->country_id ); ?>
	</div>
	<div class="register-field clear floatbox">
		<label><?php echo JText::_('COM_SFS_DIRECT_OFFICE_TELEPHONE')?></label>
		<div class="float-left">
			<input type="text" name="taxidetails[phone_code]" class="validate-numeric required smaller-size" value="<?php echo SfsHelper::formatPhone( $this->item->telephone , 1)?>" />&nbsp;
			<input type="text" name="taxidetails[phone_number]" class="validate-numeric required short-size" value="<?php echo SfsHelper::formatPhone( $this->item->telephone , 2)?>" />
			<br />
			<div class="clear" style="padding: 2px 0 2px;">
				<span class="field-note"><?php echo JText::_('COM_SFS_INT_CODE_EXAMPLE')?></span>
			</div>
		</div>
	</div>
</div>