<?php
defined('_JEXEC') or die;
JHtml::_('behavior.keepalive');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.modal');
?>

<script type="text/javascript">
window.addEvent('domready', function(){
	// The elements used.
	var taxiDetailsForm = document.id('taxiDetailsForm');
	  // 	Labels over the inputs.
	taxiDetailsForm.getElements('[type=text], select').each(function(el){
    	new OverText(el);
	});
	// Validation.
	new Form.Validator(taxiDetailsForm);

	$('newTaxiButton').addEvent('click', function(e){
		e.stop();

		var random1 = Number.random(10, 5000);
		var random2 = Number.random(10, 5000);
		var random3 = Number.random(100, 1000);

		var key = random1 + random2 + random3;

		var fieldDiv    = new Element('div', {'class': 'register-field clear floatbox largemargintop'});
		var fieldLable  = new Element('label');
		var fieldInput  = new Element('input', {type: 'text', name: 'companies[t'+key+'][name]', value:''});
		fieldLable.set('text','<?php echo JText::_('COM_SFS_TAXI_COMPANY_NAME');?>');
		fieldLable.inject(fieldDiv, 'top');
		fieldInput.inject(fieldDiv, 'bottom');
		fieldDiv.inject($('taxies'), 'bottom');

		var emailFieldDiv    = new Element('div', {'class': 'register-field clear floatbox'});
		var emailFieldLable  = new Element('label', {'class': 'textindent25'});
		var emailFieldInput  = new Element('input', {type: 'text', name: 'companies[t'+key+'][email]', value:''});
		emailFieldLable.set('text','<?php echo JText::_('COM_SFS_TAXI_COMPANY_EMAIL');?>');
		emailFieldLable.inject(emailFieldDiv, 'top');
		emailFieldInput.inject(emailFieldDiv, 'bottom');
		emailFieldDiv.inject($('taxies'), 'bottom');

		
		var telephoneFieldDiv    = new Element('div', {'class': 'register-field clear floatbox'});
		var telephoneFieldLable  = new Element('label', {'class': 'textindent25'});
		var telephoneFieldInput  = new Element('input', {type: 'text', name: 'companies[t'+key+'][telephone]', value:''});
		telephoneFieldLable.set('text','<?php echo JText::_('COM_SFS_TAXI_COMPANY_PHONE');?>');
		telephoneFieldLable.inject(telephoneFieldDiv, 'top');
		telephoneFieldInput.inject(telephoneFieldDiv, 'bottom');
		telephoneFieldDiv.inject($('taxies'), 'bottom');

		var faxFieldDiv    = new Element('div', {'class': 'register-field clear floatbox'});
		var faxFieldLable  = new Element('label', {'class': 'textindent25'});
		var faxFieldInput  = new Element('input', {type: 'text', name: 'companies[t'+key+'][fax]', value:''});
		faxFieldLable.set('text','<?php echo JText::_('COM_SFS_TAXI_COMPANY_FAX');?>');
		faxFieldLable.inject(faxFieldDiv, 'top');
		faxFieldInput.inject(faxFieldDiv, 'bottom');
		faxFieldDiv.inject($('taxies'), 'bottom');
	});
	
});
</script>

<div class="heading-block descript clearfix">
    <div class="heading-block-wrap">
        <h3><?php echo JText::_('COM_SFS_TAXI_DETAILS');?></h3>
        <div class="descript-txt"></div>
    </div>
</div>

<div id="sfs-wrapper" class="main">

	<form id="taxiDetailsForm" name="taxiDetailsForm" action="<?php echo JRoute::_('index.php'); ?>" method="post" class="form-validate">

		<div class="">
			<div class="sfs-orange-wrapper">
				<div class="sfs-white-wrapper floatbox" style="margin-bottom: 25px;">
					<fieldset>
						<?php echo $this->loadTemplate('taxies');?>
					</fieldset>
				</div>
				<div class="sfs-white-wrapper floatbox" style="margin-bottom: 25px;">
					<fieldset>
						<?php echo $this->loadTemplate('address');?>
					</fieldset>
				</div>

				<div class="sfs-white-wrapper floatbox">
					<fieldset>
						<?php echo $this->loadTemplate('billing');?>
					</fieldset>
				</div>
			</div>

		</div>

		<div class="sfs-below-main">
			<div class="s-button">
		        <a href="<?php echo JRoute::_('index.php?option=com_sfs&view=taxi&Itemid='.JRequest::getInt('Itemid')) ?>" class="s-button"><?php echo JText::_('COM_SFS_BACK');?></a>
	        </div>
			<div class="s-button float-right">
				<input type="submit" class="validate s-button"	value="<?php echo JText::_('JSAVE');?>">
			</div>
		</div>
		<input type="hidden" name="task" value="taxi.save" />
		<input type="hidden" name="option" value="com_sfs" />
		<input type="hidden" name="taxidetails[id]" value="<?php echo is_object($this->item) ? (int)$this->item->id : '0'; ?>" />
		
		<input type="hidden" name="Itemid" value="<?php echo JRequest::getInt('Itemid');?>" />
		<?php echo JHtml::_('form.token'); ?>
		
	</form>

</div>
