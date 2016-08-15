<?php
// No direct access
defined('_JEXEC') or die('Restricted access');

JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');

$params = $this->form->getFieldsets('params');

?>
<form action="<?php echo JRoute::_('index.php?option=com_sfs&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="currency-form" class="form-validate">
	<div class="width-60 fltlft">
		<fieldset class="adminform">
			<legend><?php echo JText::_( 'COM_SFS_DETAILS' ); ?></legend>
			<ul class="adminformlist">
				<?php 
				foreach($this->form->getFieldset('details') as $key => $field)
				{
					if( ($field->name != 'jform[rules]') &&  ( ($field->name != 'jform[state]') || ($field->name == 'jform[state]' && $canDo->get('core.edit.state') == 1) ) )
					{
						if ($key == 'jform_exchange_rate') {
							echo '<li>';
							echo $field->label.$field->input;
							echo "  This is the exchange rate to EUR so how many Local currency is 1 EURO worth so 1 EUR = X Local currency";
							echo '</li>';
						}
						else{
							echo '<li>';
							echo $field->label.$field->input;
							echo '</li>';
							
						}
					}
				}
				?>
			</ul>
		</fieldset>
	</div>

	
	<div class="clr"></div>

	<div>
		<input type="hidden" name="task" value="currency.edit" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>

