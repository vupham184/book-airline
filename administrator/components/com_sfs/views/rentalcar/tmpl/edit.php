<?php
defined('_JEXEC') or die;
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
//print_r($this->item);
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
<form action="<?php echo JRoute::_('index.php?option=com_sfs&view=rentalcar'); ?>" method="post" class="form-validate" id="item-form" enctype="multipart/form-data">	
		
		<div style="overflow:hidden;padding:20px;background-color: #FFFFFF;box-shadow: none;clear: both;">


			<ul class="adminformlist">
				<li>
					<?php echo $this->form->getLabel('company'); ?>
					<?php echo $this->form->getInput('company'); ?>
				</li>
				<li>
					<?php echo $this->form->getLabel('airport_code'); ?>
					<?php echo $this->form->getInput('airport_code'); ?>
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
					<?php echo $this->form->getLabel('location_name'); ?>
					<?php echo $this->form->getInput('location_name'); ?>					
				</li>
				<li>
					<?php echo $this->form->getLabel('address'); ?>
					<?php echo $this->form->getInput('address'); ?>										
				</li>
				<li>
					<?php echo $this->form->getLabel('city'); ?>
					<?php echo $this->form->getInput('city'); ?>										
				</li>
				<li>
					<?php echo $this->form->getLabel('telephone'); ?>
					<?php echo $this->form->getInput('telephone'); ?>										
				</li>
				<li>
					<?php echo $this->form->getLabel('location_code'); ?>
					<?php echo $this->form->getInput('location_code'); ?>										
				</li>	
				<li>
					<?php echo $this->form->getLabel('zipcode'); ?>
					<?php echo $this->form->getInput('zipcode'); ?>										
				</li>	
				<li>
					<?php echo $this->form->getLabel('price_default'); ?>
					<?php echo $this->form->getInput('price_default'); ?>										
				</li>			
				<li>
					<?php echo $this->form->getLabel('currency_id'); ?>
					<?php echo $this->form->getInput('currency_id'); ?>										
				</li>	
			</ul>
		</div>
		<input type="hidden" name="id" value="<?php if($this->item){ echo $this->item->id;} ?>" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="return" value="<?php echo JRequest::getCmd('return');?>" />
		<?php echo JHtml::_('form.token'); ?>
		<div class="clr"></div>
</form>