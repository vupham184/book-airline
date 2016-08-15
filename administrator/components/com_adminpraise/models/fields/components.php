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

class JFormFieldComponents extends JFormFieldList
{

	protected $type = 'Components';

    protected function getOptions()
    {
        $options = array();
        $lang = JFactory::getLanguage();

        $options[] = JHtml::_('select.option', '0', JText::_('JNONE'));

        foreach ($this->_getComponents() as $component)
        {
            $lang->load($component->element.'.sys', JPATH_ADMINISTRATOR, null, false, false)
							||	$lang->load($component->element.'.sys', JPATH_ADMINISTRATOR.'/components/'.$component->element, null, false, false)
							||	$lang->load($component->element.'.sys', JPATH_ADMINISTRATOR, $lang->getDefault(), false, false)
							||	$lang->load($component->element.'.sys', JPATH_ADMINISTRATOR.'/components/'.$component->element, $lang->getDefault(), false, false);
			$options[] = JHtml::_('select.option', $component->extension_id, JText::_($component->name));
		}

        return $options;
    }

	protected function _getComponents()
	{
        $lang = JFactory::getLanguage();
		// Get the list of components.
		$db = JFactory::getDBO();
		$db->setQuery(
			'SELECT extension_id, name, element ' .
			' FROM #__extensions' .
			' WHERE type = '.$db->quote('component') .
			' AND enabled = 1' .
			' ORDER BY name'
		);
		$components = $db->loadObjectList();

        //TODO: this is bad code - needs to be fixed...
        foreach($components as $value) {
            $output[$value->extension_id] = $value->element;
        }

        $document = JFactory::getDocument();
        $document->addScriptDeclaration('var components=' . json_encode($output));

        $components = JArrayHelper::sortObjects($components, 'name', 1, true, $lang->getLocale());

		return $components;
	}
}
