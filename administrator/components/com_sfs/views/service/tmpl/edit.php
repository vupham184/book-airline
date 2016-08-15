<?php
defined('_JEXEC') or die;
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
//print_r($this->item);
?>
<script type="text/javascript">
	Joomla.submitbutton = function(task) {
		if (task == 'service.cancel' || document.formvalidator.isValid(document.id('item-form'))) {
            Joomla.submitform(task, document.getElementById('item-form'));
        } else {
            alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
        }
	}
</script>
<form action="<?php echo JRoute::_('index.php?option=com_sfs&view=service'); ?>" method="post" class="form-validate" id="item-form" enctype="multipart/form-data">	
		
		<div style="overflow:hidden;padding:20px;background-color: #FFFFFF;box-shadow: none;clear: both;">


			<ul class="adminformlist">
				<li>
					<?php echo $this->form->getLabel('name_service'); ?>
					<?php echo $this->form->getInput('name_service'); ?>
				</li>
				<li>
					<?php echo $this->form->getLabel('parent_id'); ?>
					<?php echo $this->form->getInput('parent_id'); ?>
				</li>
				<li>
					<?php echo $this->form->getLabel('logo'); ?>
					<input type="file" name="logo" size="150" />
					<?php if($this->item && $this->item->logo!=''){
						?>
						<br/>
						<br/>
						<img src="../<?php echo $this->item->logo ?>" style="width: 50px;" />
						<?php } ?>
				</li>
				<li>
					<?php echo $this->form->getLabel('icon_service'); ?>
					<input type="file" name="icon_service" size="150" />
					<?php if($this->item && $this->item->icon_service!=''){
						?>
						<br/>
						<br/>
						<img src="../<?php echo $this->item->icon_service ?>" style="width: 50px;" />
						<?php } ?>
				</li>
				<li>
					<?php echo $this->form->getLabel('details'); ?>
					<?php echo $this->form->getInput('details'); ?>
				</li>
				<li>
					<?php echo $this->form->getLabel('order_by'); ?>
					<?php echo $this->form->getInput('order_by'); ?>
				</li>
				<li>
					<?php echo $this->form->getLabel('status'); ?>
					<?php echo $this->form->getInput('status'); ?>
				</li>	
			</ul>
		</div>
		<?php echo $this->form->getInput('create_date'); ?>		
		<input type="hidden" name="id" value="<?php if($this->item){ echo $this->item->id;} ?>" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="return" value="<?php echo JRequest::getCmd('return');?>" />
		<?php echo JHtml::_('form.token'); ?>
		<div class="clr"></div>
</form>