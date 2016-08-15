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
		if (task == 'grouptransport.cancel' || document.formvalidator.isValid(document.id('adminForm'))) {			
			Joomla.submitform(task, document.getElementById('adminForm'));
		} else {
			alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
		}
	}
</script>

<style type="text/css">
option.rowRate_c{
	background: #dfdfdf;
	padding: 5px;
}
option.rowRate_l{
	padding: 5px;
}
</style>

<form action="<?php echo JRoute::_('index.php'); ?>" method="post" class="form-validate" id="adminForm">
	
<div class="width-60 fltlft">			
	<fieldset class="adminform">
		<legend>Bus Details</legend>		
		<ul class="adminformlist">
			<li>
				<label><?php echo JText::_('Company Name');?></label>
				<input type="text" name="busdetails[name]" id="name" class="required" value="<?php echo isset($this->groupTransport)? $this->groupTransport->name : ''; ?>" size="30" />
			</li>
			<li>
				<label>Address</label>
				<input type="text" name="busdetails[address]" id="address" class="required" value="<?php echo isset($this->groupTransport)? $this->groupTransport->address:''; ?>" size="50" />
			</li>
			<li>
				<label>City</label>
				<input type="text" name="busdetails[city]" id="city" class="required" value="<?php echo isset($this->groupTransport)?  $this->groupTransport->city:''; ?>" size="30" />
			</li>
			<li>
			<label>Country:</label>	
				<select name="busdetails[country_id]" class="inputbox required">
					<option value="">Select Country</option>
					<?php echo JHtml::_('select.options', SfsHelper::getCountryOptions(), 'value', 'text', $this->groupTransport->country_id);?>
				</select>						
			</li>
			<li>
				<label>Direct office telephone</label>
				<input type="text" name="busdetails[telephone]" id="telephone" class="required" value="<?php echo isset($this->groupTransport)?  $this->groupTransport->telephone:''; ?>" size="20" />
			</li>
			<li>
				<label>Direct office fax</label>
				<input type="text" name="busdetails[fax]" id="fax" class="required" value="<?php echo isset($this->groupTransport)?  $this->groupTransport->fax:''; ?>" size="20" />
			</li>
			<li>
				<label>Status</label>							
				<select name="published" class="required">
					<option value="">Select</option>
					<?php if( isset($this->groupTransport) ) : ?>
					<option value="1"<?php if(isset($this->groupTransport) && $this->groupTransport->published == 1) echo ' selected="selected"'; ?>>Published</option>
					<option value="0"<?php if(isset($this->groupTransport) && $this->groupTransport->published == 0) echo ' selected="selected"'; ?>>Disabled</option>
					<option value="-2"<?php if(isset($this->groupTransport) && $this->groupTransport->published == -2) echo ' selected="selected"'; ?>>Trashed</option>
					<?php else: ?>								
					<option value="1" selected="selected">Published</option>
					<option value="0">Disabled</option>
					<option value="-2">Trashed</option>
					<?php endif;?>
				</select>								
			</li>	
			<li>
				<?php if($this->groupTransport->id && !$this->groupTransport->approved):?>
				<a href="#" onclick="Joomla.submitbutton('grouptransport.approve')" style="display:block;width:70px;background:green;float:left;color:#fff;font-size:12px; text-align:center;padding:5px 0;">
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
				<label>Name of head of accounting</label>
				<input type="text" name="busdetails[billing_name]" id="billing_name" class="required" value="<?php echo isset($this->groupTransport)? $this->groupTransport->billing_name : ''; ?>" size="30" />
			</li>
			<li>
				<label>Address</label>
				<input type="text" name="busdetails[billing_address]" id="billing_address" class="required" value="<?php echo isset($this->groupTransport)? $this->groupTransport->billing_address:''; ?>" size="50" />
			</li>
			<li>
				<label>City</label>
				<input type="text" name="busdetails[billing_city]" id="billing_city" class="required" value="<?php echo isset($this->groupTransport)?  $this->groupTransport->billing_city:''; ?>" size="30" />
			</li>
			<li>
			<label>Country:</label>	
				<select name="busdetails[billing_country_id]" class="inputbox ">
					<option value="">Select Country</option>
					<?php echo JHtml::_('select.options', SfsHelper::getCountryOptions(), 'value', 'text', $this->groupTransport->billing_country_id);?>
				</select>						
			</li>		
			<li>
				<label>Telephone</label>
				<input type="text" name="busdetails[billing_telephone]" id="billing_telephone" value="<?php echo isset($this->groupTransport)?  $this->groupTransport->billing_telephone:''; ?>" size="20" />
			</li>
			<li>
				<label>Fax</label>
				<input type="text" name="busdetails[billing_fax]" id="billing_fax" value="<?php echo isset($this->groupTransport)?  $this->groupTransport->billing_fax:''; ?>" size="20" />
			</li>
			<li>
				<label>VAT number</label>
				<input type="text" name="busdetails[billing_tva_number]" id="billing_tva_number" class="required" value="<?php echo isset($this->groupTransport)?  $this->groupTransport->billing_tva_number:''; ?>" size="30" />
			</li>		
		</ul>
	</fieldset>
			
</div>	

<div class="width-40 fltrt">		
	<fieldset class="adminform" style="width: 200px; float: left;">
		<legend>Airlines</legend>	
		<select name="airlines[]" multiple="multiple" size="20">
			<option value="">Select Airlines</option>
			<?php 
			foreach ($this->iataAirlines as $airline): 
				$selected = '';

				foreach ($this->groupTransport->airlines as $value) {					
					$id = $value->airline_id;
					if($airline->id == $id){
						$selected = 'selected="selected"';
					}
				}			
				// if( count($this->groupTransport->airlines) && in_array($airline->id, $this->groupTransport->airlines) )
				// {
				// 	$selected = 'selected="selected"';
				// }
			?>
			<option value="<?php echo $airline->id;?>" <?php echo $selected;?>><?php echo $airline->name;?> - <?php echo $airline->code;?></option>	
			<?php endforeach;?>
		</select>
	</fieldset>	
	
	<fieldset class="adminform" style="width: 200px; float: left;">
		<legend>Airports</legend>	
		<select name="airports[]" multiple="multiple" size="20">
			<option value="">Select Airports</option>
			<?php 
			foreach ($this->iataAirports as $airport): 
				$selected = '';

				foreach ($this->groupTransport->airports as $value) {					
					$id = $value->airport_id; print_r($id);
					if($airport->id == $id){
						$selected = 'selected="selected"';
					}
				}			
			
			?>
			<option value="<?php echo $airport->id;?>" <?php echo $selected;?>><?php echo $airport->code;?> - <?php echo $airport->name;?></option>	
			<?php endforeach;?>
		</select>
	</fieldset>	

	<fieldset class="adminform">
		<legend>Rates</legend>	
		<?php if(count($this->rateEdit)) : ?>
		<select name="rate[]" multiple="multiple" size="20" style="height: 150px;">
			<?php foreach ($this->rateEdit as $key => $rate): ?>
				<?php if($key%2 == 0) : ?>
					<option class="rowRate_c"><?php echo $rate->hotelname; ?> - <?php echo $rate->day_fare; ?></option>
				<?php else: ?>
					<option class="rowRate_l"><?php echo $rate->hotelname; ?> - <?php echo $rate->day_fare; ?></option>
				<?php endif; ?>
				
			<?php endforeach; ?>
		</select>
		<?php endif; ?>
	</fieldset>
	
	<?php
	$notification['email'] = $notification['fax'] = $notification['mobile'] = array(); 
	if($this->groupTransport->notification){
		$registry = new JRegistry();
		$registry->loadString($this->groupTransport->notification);
		$array = $registry->toArray();
		if( count($array) )
		{
			$notification = $array;
		}
	} 
	?>
	
	<?php if($this->groupTransport->id) :?>
	<fieldset class="adminform">
		<legend>Users</legend>
		<?php if( count($this->users) ):?>
			<?php foreach ($this->users as $u):?>
			<div>
				<a href="index.php?option=com_users&task=user.edit&id=<?php echo $u->user_id ?>" target="_blank">
					<?php echo $u->name;?>
				</a>
			</div>	
			<?php endforeach;?>
		<?php else:?>
			<div>
			No user available for this account
			</div>
		<?php endif;?>
		<div style="border-top:1px solid #ccc;margin-top:10px;padding-top:10px;">	
			<a href="<?php echo JRoute::_('index.php?option=com_sfs&view=grouptransport&layout=newuser&tmpl=component&id='.$this->groupTransport->id);?>" class="modal" rel="{handler: 'iframe', size: {x: 600, y: 440}, onClose: function() {}}">New user</a>
		</div>			
	</fieldset>
	<?php endif;?>
	
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
				if( isset($this->groupTransport) && (int)$this->groupTransport->sendMail == 0 ) {
					$checked = '';
				}
				?>
				<input type="checkbox" <?php echo $checked;?> name="sendMail" id="sendMail" value="1" size="40" />
			</li>
			<li>
				<?php
				$checked = 'checked=checked'; 
				if( isset($this->groupTransport) && (int)$this->groupTransport->sendFax == 0 ) {
					$checked = '';
				}
				?>
				<label>Sending booking fax</label>
				<input type="checkbox" <?php echo $checked ?> name="sendFax" id="sendFax" value="1" size="40" />
			</li>
			<li>
				<?php
				$checked = 'checked=checked'; 
				if( isset($this->groupTransport) && (int)$this->groupTransport->sendSMS == 0 ) {
					$checked = '';
				}
				?>
				<label>Sending booking SMS</label>
				<input type="checkbox" <?php echo $checked ?> name="sendSMS" id="sendSMS" value="1" size="40" />
			</li>			
		</ul>					
	</fieldset>
</div>
			
		
<input type="hidden" name="task" value="" /> 
<input type="hidden" name="option" value="com_sfs" />		
<input type="hidden" name="id" value="<?php echo JRequest::getInt('id')?>" />
		
<?php echo JHtml::_('form.token'); ?>
<div class="clr"></div>

</form>	
			
</div>

