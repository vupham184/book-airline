<?php
defined('_JEXEC') or die;
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
print_r($this->item);
?>
<script type="text/javascript">
	Joomla.submitbutton = function(task) {
		if (task == 'rentalcar.cancel' || document.formvalidator.isValid(document.id('item-form'))) {			
			Joomla.submitform(task, document.getElementById('item-form'));
		} else {
			alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
		}
	}
</script>
<form action="<?php echo JRoute::_('index.php?option=com_sfs&view=rentalcar'); ?>" method="post" class="form-validate" id="item-form"  enctype="multipart/form-data">	
		
		<div style="overflow:hidden;padding:20px;background-color: #FFFFFF;box-shadow: none;clear: both;">
			<ul class="adminformlist">
				<li>
					<label><?php echo JText::_('Company Name');?></label>
					<input type="text" name="company" id="company" class="required" value="" size="40" />
				</li>
		        <li><label>Logo </label>
    	            <input name="logo" type="file" />
	            </li>
				<li>
					<label><?php echo JText::_('Airport Code');?></label>
					<?php echo  JHTML::_('select.genericlist', $this->options_code, 'airport_code', 'class="inputbox"', 'value', 'text', '');?>
				</li>
				<li>
					<label><?php echo JText::_('Location Name');?></label>
					<input type="text" name="location_name" id="location_name" class="required" value="" size="40" />
				</li>
				<li>
					<label><?php echo JText::_('Address');?></label>
					<input type="text" name="address" id="address" class="required" value="" size="40" />
				</li>
				<li>
					<label><?php echo JText::_('Zipcode');?></label>
					<input type="text" name="zipcode" id="zipcode" class="required" value="" size="40" />
				</li>
				<li>
					<label><?php echo JText::_('city');?></label>
					<input type="text" name="city" id="city" class="required" value="" size="40" />
				</li>
				<li>
					<label><?php echo JText::_('Telephone');?></label>
					<input type="text" name="telephone" id="telephone" class="required" value="" size="40" />
				</li>
				<li>
					<label><?php echo JText::_('Location Code');?></label>
					<input type="text" name="location_code" id="location_code" class="required" value="" size="40" />
				</li>
			</ul>
		</div>
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="return" value="<?php echo JRequest::getCmd('return');?>" />
		<?php echo JHtml::_('form.token'); ?>
		<div class="clr"></div>
</form>