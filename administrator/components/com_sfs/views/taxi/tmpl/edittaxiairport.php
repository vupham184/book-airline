<?php
defined('_JEXEC') or die;
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');
JHtml::_('behavior.modal');
?>
<div>

<script type="text/javascript">
	Joomla.submitbutton = function(task) {
		if (task == 'taxi.cancel' || document.formvalidator.isValid(document.id('adminForm'))) {			
			Joomla.submitform(task, document.getElementById('adminForm'));
		} else {
			alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
		}
	}
</script>
	
<form action="<?php echo JRoute::_('index.php'); ?>" method="post" class="form-validate" id="adminForm">
	
<div class="width-60 fltlft">			
	<fieldset class="adminform">
		<legend>Taxi Details</legend>		
		<ul class="adminformlist">
			<li>
				<label><?php echo JText::_('Company Name');?></label>
				<input type="text" name="taxidetails[name]" id="name" class="required" value="<?php echo isset($this->taxi)? $this->taxi->name : ''; ?>" size="30" />
			</li>
			<li>
				<label>Contact Taxi</label>
				<input type="text" name="taxidetails[contact_name]" id="contact_name" class="required" value="<?php echo isset($this->taxi)? $this->taxi->contact_name:''; ?>" size="50" />
			</li>
			<li>
				<label>Address</label>
				<input type="text" name="taxidetails[address]" id="address" class="required" value="<?php echo isset($this->taxi)? $this->taxi->address:''; ?>" size="50" />
			</li>
			<li>
				<label>Address 2</label>
				<input type="text" name="taxidetails[address2]" id="address2" class="required" value="<?php echo isset($this->taxi)? $this->taxi->address2:''; ?>" size="50" />
			</li>
			<li>
				<label>Zipcode</label>
				<input type="text" name="taxidetails[zipcode]" id="zipcode" value="<?php echo isset($this->taxi)?  $this->taxi->zipcode:''; ?>" size="30" />
			</li>
			<li>
				<label>City</label>
				<input type="text" name="taxidetails[city]" id="city" class="required" value="<?php echo isset($this->taxi)?  $this->taxi->city:''; ?>" size="30" />
			</li>
			<li>
			<label>Country:</label>	
				<select name="taxidetails[country_id]" class="inputbox required">
					<option value="">Select Country</option>
					<?php echo JHtml::_('select.options', SfsHelper::getCountryOptions(), 'value', 'text', $this->taxi->country_id);?>
				</select>						
			</li>
			<li>
				<label>Direct office telephone</label>
				<input type="text" name="taxidetails[telephone]" id="telephone" class="required" value="<?php echo isset($this->taxi)?  $this->taxi->telephone:''; ?>" size="30" />
			</li>
			<li>
				<label>Mobile telephone</label>
				<input type="text" name="taxidetails[mobile_phone]" id="mobile_phone" class="required" value="<?php echo isset($this->taxi)?  $this->taxi->mobile_phone:''; ?>" size="30" />
			</li>
			<li>
				<label>Direct office fax</label>
				<input type="text" name="taxidetails[fax]" id="fax" class="required" value="<?php echo isset($this->taxi)?  $this->taxi->fax:''; ?>" size="40" />
			</li>
			<li>
				<label>Email address</label>
				<input type="text" name="taxidetails[email]" id="email" class="required" value="<?php echo isset($this->taxi)?  $this->taxi->email:''; ?>" size="40" />
			</li>
			<li>
				<label>VAT number</label>
				<input type="text" name="taxidetails[billing_vat_number]" id="billing_vat_number" class="required" value="<?php echo isset($this->taxi)?  $this->taxi->billing_vat_number:''; ?>" size="20" />
			</li>
			<li>
				<label>Status</label>							
				<select name="published" class="required">
					<option value="">Select</option>
					<?php if( isset($this->taxi) ) : ?>
					<option value="1"<?php if(isset($this->taxi) && $this->taxi->published == 1) echo ' selected="selected"'; ?>>Published</option>
					<option value="0"<?php if(isset($this->taxi) && $this->taxi->published == 0) echo ' selected="selected"'; ?>>Disabled</option>
					<option value="-2"<?php if(isset($this->taxi) && $this->taxi->published == -2) echo ' selected="selected"'; ?>>Trashed</option>
					<?php else: ?>								
					<option value="1" selected="selected">Published</option>
					<option value="0">Disabled</option>
					<option value="-2">Trashed</option>
					<?php endif;?>
				</select>								
			</li>	
			<li>
				<?php if($this->taxi->id && !$this->taxi->approved):?>
				<a href="#" onclick="Joomla.submitbutton('taxi.approve')" style="display:block;width:70px;background:green;float:left;color:#fff;font-size:12px; text-align:center;padding:5px 0;">
					Approve
				</a>
				<?php endif;?>
				
				<div class="clr"></div>			
			</li>					
		</ul>
	</fieldset>	
	
	<fieldset class="adminform">
		<legend>Billing Details</legend>		
		<ul class="adminformlist">
			<li>
				<label>Billing contact name</label>
				<input type="text" name="taxidetails[billing_registed_name]" id="billing_name" class="required" value="<?php echo isset($this->taxi)? $this->taxi->billing_registed_name : ''; ?>" size="30" />
			</li>
			<li>
				<label>Billing address</label>
				<input type="text" name="taxidetails[billing_address]" id="billing_address" class="required" value="<?php echo isset($this->taxi)? $this->taxi->billing_address:''; ?>" size="50" />
			</li>
			<li>
				<label>Address</label>
				<input type="text" name="taxidetails[billing_address_name1]" id="billing_address_name1" class="required" value="<?php echo isset($this->taxi)?  $this->taxi->billing_address_name1:''; ?>" size="30" />
			</li>
			<li>
				<label>Address 2</label>
				<input type="text" name="taxidetails[billing_address_name2]" id="billing_address_name2" class="required" value="<?php echo isset($this->taxi)?  $this->taxi->billing_address_name2:''; ?>" size="30" />
			</li>
			<li>
				<label>Zipcode</label>
				<input type="text" name="taxidetails[billing_zipcode]" id="billing_zipcode" value="<?php echo isset($this->taxi)?  $this->taxi->billing_zipcode:''; ?>" size="30" />
			</li>
			<li>
				<label>City</label>
				<input type="text" name="taxidetails[billing_city]" id="billing_city" value="<?php echo isset($this->taxi)?  $this->taxi->billing_city:''; ?>" size="30" />
			</li>
			<li>
			<label>Country:</label>	
				<select name="taxidetails[billing_country_id]" class="inputbox ">
					<option value="">Select Country</option>
					<?php echo JHtml::_('select.options', SfsHelper::getCountryOptions(), 'value', 'text', $this->taxi->billing_country_id);?>
				</select>						
			</li>		
			<li>
				<label>Telephone</label>
				<input type="text" name="taxidetails[billing_telephone]" id="billing_telephone" value="<?php echo isset($this->taxi)?  $this->taxi->billing_telephone:''; ?>" size="20" />
			</li>
			<li>
				<label>Mobile Phone</label>
				<input type="text" name="taxidetails[billing_mobile_phone]" id="billing_mobile_phone" value="<?php echo isset($this->taxi)?  $this->taxi->billing_mobile_phone:''; ?>" size="20" />
			</li>
			<li>
				<label>Fax</label>
				<input type="text" name="taxidetails[billing_fax]" id="billing_fax" value="<?php echo isset($this->taxi)?  $this->taxi->billing_fax:''; ?>" size="20" />
			</li>
			<li>
				<label>Email Address</label>
				<input type="text" name="taxidetails[billing_mail]" id="billing_mail" class="required" value="<?php echo isset($this->taxi)?  $this->taxi->billing_mail:''; ?>" size="30" />
			</li>		
		</ul>
	</fieldset>
			
</div>	

<div class="width-40 fltrt">		
	<fieldset class="adminform">
		<legend>Airport</legend>	
		<select name="airport[]" multiple="multiple" style="width:50%; height:200px;">
			<option value="">Select Airport</option>
			<?php foreach ($this->airport as $key => $value): ?>
				<?php if ($value->id == $this->taxi->airport->airport_id): ?>
					<option value="<?php echo $value->id; ?>" selected>
						<?php echo $value->code.'-'.$value->name; ?>
					</option>
				<?php else: ?>
					<option value="<?php echo $value->id; ?>" >
						<?php echo $value->code.'-'.$value->name; ?>
					</option>
				<?php endif ?>
			<?php endforeach ?>
			
		</select>
	</fieldset>	
	
	<?php
	$notification['email'] = $notification['fax'] = $notification['mobile'] = array(); 
	if($this->taxi->notification){
		$registry = new JRegistry();
		$registry->loadString($this->taxi->notification);
		$array = $registry->toArray();
		if( count($array) )
		{
			$notification = $array;
		}
	} 
	?>
	
	<fieldset class="adminform">
		<legend>Notification Setting</legend>
		<ul class="adminformlist">										
			<li>
				<label>Email</label>	
				<input type="text" name="notifications[email]" value="<?php echo implode(';', $notification['email'])?>" style="width:80%">											
			</li>
			<li>
				<label>Fax</label>	
				<input type="text" name="notifications[fax]" value="<?php echo implode(';', $notification['fax'])?>"  style="width:80%">											
			</li>
			<li>
				<label>Mobile</label>	
				<input type="text" name="notifications[mobile]" value="<?php echo implode(';', $notification['mobile'])?>"  style="width:80%">
			</li>			
			<li>
				<label>Sending booking email</label>
				<?php
				$checked = 'checked=checked'; 
				if( isset($this->taxi) && (int)$this->taxi->sendMail == 0 ) {
					$checked = '';
				}
				?>
				<input type="checkbox" <?php echo $checked;?> name="sendMail" id="sendMail" value="1" size="40" />
			</li>
			<li>
				<?php
				$checked = 'checked=checked'; 
				if( isset($this->taxi) && (int)$this->taxi->sendFax == 0 ) {
					$checked = '';
				}
				?>
				<label>Sending booking fax</label>
				<input type="checkbox" <?php echo $checked ?> name="sendFax" id="sendFax" value="1" size="40" />
			</li>
			<li>
				<?php
				$checked = 'checked=checked'; 
				if( isset($this->taxi) && (int)$this->taxi->sendSMS == 0 ) {
					$checked = '';
				}
				?>
				<label>Sending booking SMS</label>
				<input type="checkbox" <?php echo $checked ?> name="sendSMS" id="sendSMS" value="1" size="40" />
			</li>			
		</ul>					
	</fieldset>
	
	<fieldset class="adminform">
		<legend>Params</legend>
		<ul class="adminformlist">
			<li>
				<label>Enable night fare:</label>		
				<fieldset class="radio">			
					<input type="radio" <?php echo (int)$this->taxi->params['enable_night_fare']==0 ? 'checked="checked"':'';?> value="0" name="params[enable_night_fare]">
					<label>No</label>
					<input type="radio" <?php echo (int)$this->taxi->params['enable_night_fare']==1 ? 'checked="checked"':'';?> value="1" name="params[enable_night_fare]">
					<label>Yes</label>
				</fieldset>
			</li>
			<li>
				<label>Enable weekend fare:</label>		
				<fieldset class="radio">			
					<input type="radio" <?php echo (int)$this->taxi->params['enable_weekend_fare']==0 ? 'checked="checked"':'';?> value="0" name="params[enable_weekend_fare]">
					<label>No</label>
					<input type="radio" <?php echo (int)$this->taxi->params['enable_weekend_fare']==1 ? 'checked="checked"':'';?> value="1" name="params[enable_weekend_fare]">
					<label>Yes</label>
				</fieldset>
			</li>
		</ul>	
	</fieldset>	
</div>
			
		
<input type="hidden" name="task" value="" /> 
<input type="hidden" name="option" value="com_sfs" />		
<input type="hidden" name="taxi_id" value="<?php echo isset($this->taxi)?  $this->taxi->id:'0'; ?>" />
<input type="hidden" name="airport_id" value="<?php echo isset($this->taxi)?  $this->taxi->airport->airport_id:'0'; ?>" />
		
<?php echo JHtml::_('form.token'); ?>
<div class="clr"></div>

</form>	
			
</div>

