<?php
// No direct access
defined('_JEXEC') or die('Restricted access');

JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');

$params = $this->form->getFieldsets('params');
$canDo	= $this->canDo;
?>
<form action="<?php echo JRoute::_('index.php?option=com_sfs&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="location-form" class="form-validate">
	<div class="width-60 fltlft">
		<fieldset class="adminform">
			<legend><?php echo JText::_( 'COM_SFS_DETAILS' ); ?></legend>
			<ul class="adminformlist">
				<?php 
				foreach($this->form->getFieldset('details') as $field)
				{
					if( ($field->name != 'jform[rules]') &&  ( ($field->name != 'jform[state]') || ($field->name == 'jform[state]' && $canDo->get('core.edit.state') == 1) ) )
					{
						echo '<li>';
						echo $field->label.$field->input;
						echo '</li>';
					}
				}
				?>
			</ul>
		</fieldset>
	</div>


	
	<div class="clr"></div>
	
	<div>
		<input type="hidden" name="task" value="location.edit" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>

