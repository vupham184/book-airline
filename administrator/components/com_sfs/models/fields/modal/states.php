<?php
/**
 * @version		$Id: states.php 0 2011-03-11 00:00:00 anhld $
 * @copyright	Copyright (C) 2011 AnhLD
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;

jimport('joomla.form.formfield');

/**
 * Supports a modal state picker.
 *
 * @package		Joomla.Administrator
 * @subpackage	com_sfs
 * @since		1.6
 */
class JFormFieldModal_States extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var		string
	 * @since	1.6
	 */
	protected $type = 'Modal_States';

	/**
	 * Method to get the field input markup.
	 *
	 * @return	string	The field input markup.
	 * @since	1.6
	 */
	protected function getInput()
	{
		// Load the javascript
		JHtml::_('behavior.framework');
		JHTML::_('behavior.modal', 'a.modal');

		// Build the script.
		$script = array();
		$script[] = '	function jSelectChart_'.$this->id.'(state_id, country_id, name, object) {';
		$script[] = '		document.id("'.$this->id.'_id").value = state_id;';
		$script[] = '		document.id("'.$this->id.'_name").value = name;';
		$script[] = '		document.id("jform_country_id").value = country_id;';
		$script[] = '		SqueezeBox.close();';
		$script[] = '	}';

		// Add the script to the document head.
		JFactory::getDocument()->addScriptDeclaration(implode("\n", $script));

		// Get the title of the linked chart
		$db = JFactory::getDBO();
		$db->setQuery(
			'SELECT name' .
			' FROM #__sfs_states' .
			' WHERE id = '.(int) $this->value
		);
		$title = $db->loadResult();

		if ($error = $db->getErrorMsg()) {
			JError::raiseWarning(500, $error);
		}

		if (empty($title)) {
			$title = JText::_('COM_SFS_SELECT_A_STATE');
		}

		$link = 'index.php?option=com_sfs&amp;view=states&amp;layout=modal&amp;tmpl=component&amp;function=jSelectChart_'.$this->id;

		$html = "\n".'<div class="fltlft"><input size="60" type="text" id="'.$this->id.'_name" value="'.htmlspecialchars($title, ENT_QUOTES, 'UTF-8').'" disabled="disabled" /></div>';
		$html .= '<div class="button2-left"><div class="blank"><a class="modal" title="'.JText::_('COM_SFS_SELECT_STATE_BUTTON').'"  href="'.$link.'" rel="{handler: \'iframe\', size: {x: 800, y: 450}}">'.JText::_('COM_SFS_SELECT_STATE_BUTTON').'</a></div></div>'."\n";
		// The active contact id field.
		if (0 == (int)$this->value) {
			$value = '';
		} else {
			$value = (int)$this->value;
		}

		// class='required' for client side validation
		$class = '';
		if ($this->required) {
			$class = ' class="required modal-value"';
		}

		$html .= '<input type="hidden" id="'.$this->id.'_id"'.$class.' name="'.$this->name.'" value="'.$value.'" />';

		return $html;
	}
}
