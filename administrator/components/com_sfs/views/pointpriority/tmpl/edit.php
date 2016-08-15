<?php
defined('_JEXEC') or die;
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
//print_r($this->item);
?>
<script type="text/javascript">
	Joomla.submitbutton = function(task) {
		if (task == 'pointpriority.cancel' || document.formvalidator.isValid(document.id('item-form'))) {
            Joomla.submitform(task, document.getElementById('item-form'));
        } else {
            alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
        }
	}
</script>
<form action="<?php echo JRoute::_('index.php?option=com_sfs&view=pointpriority'); ?>" method="post" class="form-validate" id="item-form" enctype="multipart/form-data">	
		
		<div style="overflow:hidden;padding:20px;background-color: #FFFFFF;box-shadow: none;clear: both;">


			<ul class="adminformlist">
				<li>
					<?php echo $this->form->getLabel('name'); ?>
					<?php echo $this->form->getInput('name'); ?>
				</li>
				<li>
					<?php echo $this->form->getLabel('type_group'); ?>
					<?php echo $this->form->getInput('type_group'); ?>
				</li>
				
				<li>
					<?php echo $this->form->getLabel('point'); ?>
					<?php echo $this->form->getInput('point'); ?>					
				</li>
				
			</ul>
		</div>
		<input type="hidden" name="id" value="<?php if($this->item){ echo $this->item->id;} ?>" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="return" value="<?php echo JRequest::getCmd('return');?>" />
		<?php echo JHtml::_('form.token'); ?>
		<div class="clr"></div>
</form>