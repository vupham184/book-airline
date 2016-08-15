<?php
// No direct access.
defined('_JEXEC') or die;

$biling = $this->item->getBillingDetail();
?>
<legend>Billing details</legend>
<ul class="adminformlist">	
	<li>
		<label for="">Registered name:</label>
		<input type="text" size="30" class="inputbox " value="<?php echo $biling->name;?>" id="billing_name" name="billing[name]">
	</li>								
	<li>
		<label for="">Address:</label>
		<input type="text" size="60" class="inputbox " value="<?php echo $biling->address;?>" id="billing_address" name="billing[address]">
	</li>
	<li>
		<label for="">Address 1:</label>
		<input type="text" size="60" class="inputbox" value="<?php echo $biling->address1;?>" id="billing_address1" name="billing[address1]">
	</li>
	<li>
		<label for="">Address 2:</label>
		<input type="text" size="60" class="inputbox" value="<?php echo $biling->address2;?>" id="billing_address2" name="billing[address2]">
	</li>
	<li>
		<label for="">City:</label>
		<input type="text" size="30" class="inputbox " value="<?php echo $biling->city;?>" id="billingcity" name="billing[city]">
	</li>		
	<li>
		<label for="">State:</label>		
		<select name="billing[state_id]" class="inputbox">
			<option value="">Select State</option>
			<?php echo JHtml::_('select.options', SfsHelper::getStateOptions(), 'value', 'text', $biling->state_id);?>
		</select>	
	</li>
	<li>
		<label>Country:</label>	
		<select name="billing[country_id]" class="inputbox ">
			<option value="">Select Country</option>
			<?php echo JHtml::_('select.options', SfsHelper::getCountryOptions(), 'value', 'text', $this->item->country_id);?>
		</select>						
	</li>
	<li>
		<label for="">Zip code:</label>
		<input type="text" size="30" class="inputbox " value="<?php echo $biling->zipcode;?>" id="billing_zipcode" name="billing[zipcode]">
	</li>	
	
	<li>
		<label for="">TVA number:</label>
		<input type="text" size="30" class="inputbox " value="<?php echo $biling->tva_number;?>" id="billing_tvanumber" name="billing[tva_number]">
	</li>																																		
</ul>
<input type="hidden" value="<?php echo $biling->id;?>" name="billing[id]">
<div class="clr"></div>	

