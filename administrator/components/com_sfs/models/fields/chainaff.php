<?php
defined('JPATH_BASE') or die;

jimport('joomla.html.html');
jimport('joomla.form.formfield');

/**
 * Supports an HTML select list of contacts
 *
 * @package		Joomla.Administrator
 * @subpackage	com_sfs
 * @since		1.6
 */
class JFormFieldChainaff extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var		string
	 * @since	1.6
	 */
	protected $type = 'Chainaff';

	/**
	 * Method to get the field input markup.
	 *
	 * @return	string	The field input markup.
	 * @since	1.6
	 */
	protected function getInput()
	{
		// Initialize variables.
		$html = array();
		$attr = '';

		// Initialize some field attributes.
		$attr .= $this->element['class'] ? ' class="'.(string) $this->element['class'].'"' : '';
		$attr .= ((string) $this->element['disabled'] == 'true') ? ' disabled="disabled"' : '';
		$attr .= $this->element['size'] ? ' size="'.(int) $this->element['size'].'"' : '';

		// Initialize JavaScript field attributes.
		$attr .= $this->element['onchange'] ? ' onchange="'.(string) $this->element['onchange'].'"' : '';

		// Get some field values from the form.
		$id	= (int) $this->form->getValue('id');
		$categoryId	= (int) $this->form->getValue('catid');

		// Build the query for the ordering list.
		$query = 'SELECT `id` AS `value`, `name` AS `text` ' .
				' FROM `#__sfs_hotel_chains` ' .
				' ORDER BY `ordering` ';
				
		$db =& JFactory::getDBO();
		$db->setQuery($query);
		$data = $db->loadObjectList();

		$html[] = JHTML::_('select.genericlist', $data, $this->name, 'class="inputbox" size="1"', 'value', 'text', $this->value );

		return implode($html);
	}
}