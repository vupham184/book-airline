<?php
// No direct access
defined('_JEXEC') or die('Restricted access');

JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');

$params = $this->form->getFieldsets('params');
$canDo	= $this->canDo;
?>
<form action="<?php echo JRoute::_('index.php?option=com_sfs&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="airport-form" class="form-validate">
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

	<div class="width-40 fltrt">
		<?php 
		echo JHtml::_('sliders.start', 'airport-slider');
		foreach ($params as $name => $fieldset)
		{
			echo JHtml::_('sliders.panel', JText::_($fieldset->label), $name.'-params');
			if (isset($fieldset->description) && trim($fieldset->description))
			{
				echo '<p class="tip">' ;
				echo $this->escape(JText::_($fieldset->description));
				echo '</p>';
			}
			
			echo '<fieldset class="panelform"><ul class="adminformlist">';
			foreach ($this->form->getFieldset($name) as $field)
			{
				echo '<li>';
				echo $field->label.$field->input;
				echo '</li>';
			}
			echo '</ul></fieldset>';
		}
		echo JHtml::_('sliders.end'); 
		?>
	</div>	
	
	<div class="clr"></div>
	<?php if ($canDo->get('core.admin')): ?>
		<div class="width-100 fltlft">
			<?php echo JHtml::_('sliders.start','permissions-sliders-'.$this->item->id, array('useCookie'=>1)); ?>

				<?php echo JHtml::_('sliders.panel',JText::_('COM_SFS_FIELDSET_RULES'), 'access-rules'); ?>

				<fieldset class="panelform">
					<?php echo $this->form->getLabel('rules'); ?>
					<?php echo $this->form->getInput('rules'); ?>
				</fieldset>

			<?php echo JHtml::_('sliders.end'); ?>
		</div>
	<?php endif; ?>
	
	<div>
		<input type="hidden" name="task" value="airport.edit" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>

