<?php
// No direct access.
defined('_JEXEC') or die;
// Load the tooltip behavior.
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');
?>
<script type="text/javascript">
	Joomla.submitbutton = function(task) {
		if (task == 'delaycode.cancel' || document.formvalidator.isValid(document.id('item-form'))) {	
			Joomla.submitform(task, document.getElementById('item-form'));
		} else {
			alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
		}
	}
</script>

<form action="<?php echo JRoute::_('index.php?option=com_sfs&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="item-form" class="form-validate">
	<div class="width-100">
		<fieldset class="adminform">
			<legend><?php echo empty($this->item->id) ? JText::_('New delaycode') : JText::sprintf('Edit delaycode', $this->item->id); ?></legend>
			<ul class="adminformlist">	
					
				<li><?php echo $this->form->getLabel('code'); ?>
				<?php echo $this->form->getInput('code'); ?></li>
				
				<li><?php echo $this->form->getLabel('description'); ?>
				<?php echo $this->form->getInput('description'); ?></li>
				
				<li><?php echo $this->form->getLabel('id'); ?>
				<?php echo $this->form->getInput('id'); ?></li>
			</ul>			
		</fieldset>
	</div>	
	<div class="clr"></div>
	<div>
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="return" value="<?php echo JRequest::getCmd('return');?>" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>

