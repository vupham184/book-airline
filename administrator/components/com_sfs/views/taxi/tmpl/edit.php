<?php
defined('_JEXEC') or die;
JHtml::_('behavior.keepalive');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.modal');

require_once JPATH_ROOT.'/components/com_sfs/helpers/field.php';
?>

<script type="text/javascript">
window.addEvent('domready', function(){
	// The elements used.
	var taxiDetailsForm = document.id('adminForm');
	  // 	Labels over the inputs.
	taxiDetailsForm.getElements('[type=text], select').each(function(el){
    	new OverText(el);
	});
	// Validation.
	new Form.Validator(taxiDetailsForm); 


	$('newTaxiButton').addEvent('click', function(e){
		e.stop();

		var fieldDiv    = new Element('div', {'class': 'register-field clear floatbox'});
		var fieldLable  = new Element('label');		
		var fieldInput  = new Element('input', {type: 'text', name: 'companies[]', value:''});
		

		fieldLable.set('text','<?php echo JText::_('COM_SFS_TAXI_COMPANY_NAME');?>');
		fieldLable.inject(fieldDiv, 'top');

		fieldInput.inject(fieldDiv, 'bottom');
		
		fieldDiv.inject($('taxies'), 'bottom');
		
	});
	
});
</script>
<?php
$airlineName = !empty($this->airline->airline_name) ? $this->airline->airline_name : $this->airline->company_name; 
?>

<form id="adminForm" name="adminForm" action="<?php echo JRoute::_('index.php'); ?>" method="post" class="form-validate">
	<fieldset>
		<div class="fltrt">
			<button type="submit">
				Save
			</button>			
			<button onclick="window.parent.SqueezeBox.close();" type="button">
				Close
			</button>
		</div>
		<div class="configuration">
			<?php echo $airlineName .': Edit Taxi Details';?>
		</div>
	</fieldset>
	
	<div style="overflow:hidden;padding:20px;background-color: #FFFFFF;box-shadow: none;clear: both;">

		<div style="width:50%;float:left;">				
			<fieldset>
				<legend><?php echo JText::_('COM_SFS_TAXI_SERVICES_AND_INQUIRIES');?></legend>
				<?php echo $this->loadTemplate('address');?>
			</fieldset>
		</div>
	
		<div style="width:50%;float:left;">				
			<fieldset>
				<legend><?php echo JText::_('COM_SFS_TAXI_BILLING_DETAIL');?></legend>
				<?php echo $this->loadTemplate('billing');?>						
			</fieldset>
		</div>
	
	</div>
		

	<input type="hidden" name="task" value="taxi.apply" /> 
	<input type="hidden" name="option" value="com_sfs" />		
	<input type="hidden" name="taxidetails[id]" value="<?php echo is_object($this->item) ? (int)$this->item->id : '0'; ?>" />
	<input type="hidden" name="airline_id" value="<?php echo JRequest::getInt('airline_id')?>" />
	
	<?php echo JHtml::_('form.token'); ?>
	
</form>

