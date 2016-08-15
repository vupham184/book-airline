<?php 
defined('_JEXEC') or die; 
?>

<ul class="adminformlist">
	<li>
		<label><?php echo JText::_('Address');?></label>
		<input type="text" name="taxidetails[address]" id="address" class="required" value="<?php echo $this->item->address; ?>" size="40" />
	</li>
	
	<li>
		<label><?php echo JText::_('City');?> </label> 
		<input type="text" name="taxidetails[city]" id="city" class="required" value="<?php echo $this->item->city; ?>" />
	</li>
	
	<li>
		<label><?php echo JText::_('State');?> </label>
		<?php echo SfsHelperField::getStateField( 'taxidetails[state_id]' , $this->item->state_id ); ?>
	</li>
	
	<li>
		<label><?php echo JText::_('Zipcode');?> </label> 
		<input type="text" name="taxidetails[zipcode]" id="zipcode" class="required" value="<?php echo $this->item->zipcode; ?>" />	
	</li>
	
	<li>
		<label><?php echo JText::_('Country');?> </label>
		<?php echo SfsHelperField::getCountryField( 'taxidetails[country_id]' , $this->item->country_id ); ?>
	</li>
	
	<li>
		<label><?php echo JText::_('Telephone')?></label>
		<div class="float-left">
			<input type="text" name="taxidetails[phone_code]" class="validate-numeric required smaller-size" value="<?php echo sfsHelper::formatPhone( $this->item->telephone , 1)?>" />&nbsp;
			<input type="text" name="taxidetails[phone_number]" class="validate-numeric required short-size" value="<?php echo sfsHelper::formatPhone( $this->item->telephone , 2)?>" />		
		</div>
	</li>

</ul>
