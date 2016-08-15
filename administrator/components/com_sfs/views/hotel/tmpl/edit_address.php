<?php
// No direct access.
defined('_JEXEC') or die;?>
<legend>Address Information</legend>
<ul class="adminformlist">		
	<li>
		<label>Web Address:</label>
		<input type="text" size="60" class="inputbox " value="<?php echo $this->item->web_address;?>" id="web_address" name="address[web_address]">
	</li>			
	<li>
		<label>Main telephone:</label>
		<input type="text" size="30" class="inputbox " value="<?php echo $this->item->telephone;?>" id="main_telephone" name="address[telephone]">
	</li>		
	<li>
		<label>Main fax:</label>
		<input type="text" size="30" class="inputbox " value="<?php echo $this->item->fax;?>" id="main_fax" name="address[fax]">
	</li>															
	<li> 	
		<label>Address:</label>
		<input type="text" size="60" class="inputbox " value="<?php echo $this->item->address;?>" id="address" name="address[address]">
	</li>
	<li> 	
		<label>Address 1:</label>
		<input type="text" size="60" class="inputbox" value="<?php echo $this->item->address1;?>" id="address1" name="address[address1]">
	</li>
	<li> 	
		<label>Address 2:</label>
		<input type="text" size="60" class="inputbox" value="<?php echo $this->item->address2;?>" id="address2" name="address[address2]">
	</li>
	<li>
		<label>Zip code:</label>
		<input type="text" size="30" class="inputbox " value="<?php echo $this->item->zipcode;?>" id="zip" name="address[zipcode]">
	</li>	
	<li>
		<label>City:</label>
		<input type="text" size="30" class="inputbox " value="<?php echo $this->item->city;?>" id="city" name="address[city]">
	</li>		
	<li>
		<label>State:</label>		
		<select name="address[state_id]" class="inputbox">
			<option value="">Select State</option>
			<?php echo JHtml::_('select.options', SfsHelper::getStateOptions(), 'value', 'text', $this->item->state_id);?>
		</select>	
	</li>				
	<li>
		<label>Country:</label>	
		<select name="address[country_id]" class="inputbox ">
			<option value="">Select Country</option>
			<?php echo JHtml::_('select.options', SfsHelper::getCountryOptions(), 'value', 'text', $this->item->country_id);?>
		</select>						
	</li>

	<li>
		<label>Time zone:</label>	
		<?php echo SfsHelperField::getTimeZone($this->item->time_zone, 'address[time_zone]');?>				
	</li>
																													
</ul>
<div class="clr"></div>	

