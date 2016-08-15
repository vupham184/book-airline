<?php
// No direct access
defined('_JEXEC') or die('Restricted access');

JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');

?>
<form action="<?php echo JRoute::_('index.php?option=com_sfs&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="state-form" class="form-validate">
	<div class="width-100 fltlft">
		<fieldset class="adminform">
			<legend><?php echo JText::_( 'COM_SFS_DETAILS' ); ?></legend>
			<ul class="adminformlist">
				<?php 
				foreach($this->form->getFieldset('details') as $field)
				{					
					echo '<li>';
					echo $field->label.$field->input;
					echo '</li>';					
				}
				?>
			</ul>
		</fieldset>
	</div>

	
	<div class="clr"></div>

	<div>
		<input type="hidden" name="task" value="state.edit" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>

