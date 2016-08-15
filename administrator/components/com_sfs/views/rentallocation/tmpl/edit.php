<?php
defined('_JEXEC') or die;
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
//print_r($this->item);
?>
<script type="text/javascript">
	Joomla.submitbutton = function(task) {
		if (task == 'rentallocation.cancel' || document.formvalidator.isValid(document.id('item-form'))) {			
			Joomla.submitform(task, document.getElementById('item-form'));
		} else {
			alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
		}
	}
</script>
<form action="<?php echo JRoute::_('index.php?option=com_sfs&view=rentalcar'); ?>" method="post" class="form-validate" id="item-form" enctype="multipart/form-data">	
		
		<div style="overflow:hidden;padding:20px;background-color: #FFFFFF;box-shadow: none;clear: both;">


			<ul class="adminformlist">
				<li>
					<?php echo $this->form->getLabel('locationname'); ?>
					<?php echo $this->form->getInput('locationname'); ?>
				</li>
				<li>
					<?php echo $this->form->getLabel('type'); ?>
					<?php echo $this->form->getInput('type'); ?>
				</li>
				<li>
					<?php echo $this->form->getLabel('agency'); ?>
					<?php echo $this->form->getInput('agency'); ?>
				</li>
				
				<li>
					<?php echo $this->form->getLabel('airportcode'); ?>
					<?php echo $this->form->getInput('airportcode'); ?>
				</li>		
				<li>
					<?php echo $this->form->getLabel('address'); ?>
					<?php echo $this->form->getInput('address'); ?>										
				</li>
				<li>
					<?php echo $this->form->getLabel('zipcode'); ?>
					<?php echo $this->form->getInput('zipcode'); ?>										
				</li>
				<li>
					<?php echo $this->form->getLabel('city'); ?>
					<?php echo $this->form->getInput('city'); ?>										
				</li>
				<!--<li>
					<?php echo $this->form->getLabel('country'); ?>
					<?php echo $this->form->getInput('country'); ?>										
				</li>-->
				<li>
					<?php echo $this->form->getLabel('phone'); ?>
					<?php echo $this->form->getInput('phone'); ?>										
				</li>
				<li>
					<?php echo $this->form->getLabel('email'); ?>
					<?php echo $this->form->getInput('email'); ?>										
				</li>	
			</ul>
		</div>
		<input type="hidden" name="id" value="<?php if($this->item){ echo $this->item->id;} ?>" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="return" value="<?php echo JRequest::getCmd('return');?>" />
		<?php echo JHtml::_('form.token'); ?>
		<div class="clr"></div>
</form>