<?php
/**
 * @package		AdminPraise3
 * @author		AdminPraise http://www.adminpraise.com
 * @copyright	Copyright (c) 2008 - 2012 Pixel Praise LLC. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

/**
 *    This file is part of AdminPraise.
 *
 *    AdminPraise is distributed in the hope that it will be useful,
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *    GNU General Public License for more details.
 *
 *    You should have received a copy of the GNU General Public License
 *    along with AdminPraise.  If not, see <http://www.gnu.org/licenses/>.
 *
 **/

/**
 * @package     Square One
 * @link        www.squareonecms.org
 * @copyright   Copyright 2011 Square One and Open Source Matters. All Rights Reserved.
 */

defined('JPATH_BASE') or die;

jimport('joomla.html.html');
jimport('joomla.form.formfield');
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

/**
 * Form Field class for the Joomla Framework.
 *
 * @package		Joomla.Administrator
 * @subpackage	com_menus
 * @since		1.6
 */
class JFormFieldAdminMenuOrdering extends JFormFieldList
{
	/**
	 * The form field type.
	 *
	 * @var		string
	 * @since	1.7
	 */
	protected $type = 'AdminMenuOrdering';

	/**
	 * Method to get the list of siblings in a menu.
	 * The method requires that parent be set.
	 *
	 * @return	array	The field option objects or false if the parent field has not been set
	 * @since	1.7
	 */
	protected function getOptions()
	{
		// Initialize variables.
		$options = array();

		// Get the parent
		$parent_id = $this->form->getValue('parent_id', 0);

		$db = JFactory::getDbo();
        $app = JFactory::getApplication();
		$query = $db->getQuery(true);

		$query->select('a.id AS value, a.title AS text');
		$query->from('#__adminpraise_menu AS a');

		$query->where('a.published >= 0');
		$query->where('a.parent_id =' . (int) $parent_id);
        $query->where('a.client_id = 1');
        $menuType = $app->getUserState('filter.menutype', 'main');
        $query->where('a.menutype = '.$db->quote($menuType));

		$query->order('a.lft ASC');

		// Get the options.
		$db->setQuery($query);

		$options = $db->loadObjectList();
        
        // Translate the items
		for ($i = 0, $n = count($options); $i < $n; $i++) {
			$options[$i]->text = JText::_($options[$i]->text);
		}

		// Check for a database error.
		if ($db->getErrorNum()) {
			JError::raiseWarning(500, $db->getErrorMsg());
		}
		// Merge any additional options in the XML definition.
		$options = array_merge(parent::getOptions(), $options);

		return $options;
	}

	/**
	 * Method to get the field input markup
	 *
	 * @return  string  The field input markup.
	 * @since   1.7
	 */
	protected function getInput()
	{
		if ($this->form->getValue('id',0) == 0)
		{
			return '<span class="readonly">' . JText::_('COM_ADMINPRAISE_ITEM_FIELD_ORDERING_TEXT') . '</span>';
		}
		else
		{
			return parent::getInput();
		}
	}
}
