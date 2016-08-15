<?php 
defined('_JEXEC') or die; 
?>
<ul class="adminformlist">
	<li>
		<label><?php echo JText::_('Registered name');?> </label>
		<input type="text" name="taxidetails[billing_registed_name]" id="billing_name" class="required" value="<?php echo $this->item->billing_registed_name ; ?>" />
	</li>
	<li>
		<label><?php echo JText::_('Address');?> </label>
		<input type="text" name="taxidetails[billing_address]" id="billing_address" size="40" class="required" value="<?php echo $this->item->billing_address; ?>" />			
	</li>
	<li>
		<label><?php echo JText::_('City');?> </label> 
		<input type="text" name="taxidetails[billing_city]" id="billing_city" class="required" value="<?php echo $this->item->billing_city; ?>" />
	</li>
	<li>
		<label><?php echo JText::_('State');?> </label>
		<?php echo SfsHelperField::getStateField( 'taxidetails[billing_state_id]' , $this->item->billing_state_id ); ?>
	</li>
	<li>
		<label><?php echo JText::_('Zipcode');?> </label> 
		<input type="text" name="taxidetails[billing_zipcode]" id="billing_zipcode" class="required" value="<?php echo $this->item->billing_zipcode; ?>" />
	</li>
	<li>
		<label><?php echo JText::_('Country');?> </label>
		<?php echo SfsHelperField::getCountryField( 'taxidetails[billing_country_id]' , $this->item->billing_country_id ); ?>
	</li>
	<li>
		<label><?php echo JText::_('VAT number');?> </label> 
		<input type="text" name="taxidetails[billing_vat_number]" id="billing_tvanumber" class="required" value="<?php echo $this->item->billing_vat_number; ?>" />
	</li>
</ul>
