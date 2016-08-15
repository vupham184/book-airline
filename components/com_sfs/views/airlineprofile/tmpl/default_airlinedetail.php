<?php
defined('_JEXEC') or die;?>
<div class="register-field clear floatbox">
	<label><?php echo JText::_('COM_SFS_AIRLINE_REGISTER_2LETTER_CODE');?>
	</label>
	<?php echo $this->airline->code; ?>
</div>


<div class="register-field clear floatbox">
	<label><?php echo JText::_('COM_SFS_AIRLINE_REGISTER_COMPANY_NAME');?>
	</label>
	<div id="company_name">
	<?php echo $this->airline->name; ?>
	</div>
</div>


<div class="register-field clear floatbox">
	<label><?php echo JText::_('COM_SFS_AIRLINE_REGISTER_AFFILIATION_CODE');?>
	</label>
	<?php echo $this->airline->affiliation_code; ?>
</div>


<div class="register-field clear floatbox">
	<label><?php echo JText::_('COM_SFS_AIRLINE_REGISTER_AIRLINE_ALLIANCE');?>
	</label>
	<?php echo $this->airline->airline_alliance ; ?>
</div>


<div class="register-field clear floatbox">
	<label><?php echo JText::_('COM_SFS_AIRLINE_REGISTER_AIRPORT_LOCATION_CODE');?>
	</label>
	<?php echo SfsHelperField::getAirportName( $this->airline->airport_id ); ?>
</div>
<div class="register-field clear floatbox">
	<label><?php echo JText::_('COM_SFS_TIME_ZONE');?> </label>
	<?php echo $this->airline->time_zone ;?>
</div>
