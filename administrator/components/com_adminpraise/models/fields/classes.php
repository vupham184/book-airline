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

class JFormFieldClasses extends JFormFieldList
{

	protected $type = 'Components';
    
    protected function getInput()
    {
        $html = '<input type="text" name="'.$this->name.'" value="'.$this->value.'" id="'.$this->id.'" />';
        $this->id = 'img_class_list';
        $this->name = 'img_class_list';
        $html .= parent::getInput();
        
        $doc = JFactory::getDocument();
        
        $doc->addScriptDeclaration("
        window.addEvent('domready', function() {
            $('img_class_list').addEvent('change', function() {
                $('jform_img').set('value', this.get('value'));
            });
        });
        ");
        
        return $html;
    }
    
    protected function getOptions()
    {
        $options = array();
        $lang = JFactory::getLanguage();
        
        $options[] = JHtml::_('select.option', '', JText::_('COM_ADMINPRAISE_USE_CLASS'));
        
        foreach ($this->_getClasses() as $class) 
        {
			$options[] = JHtml::_('select.option', 'class:'.$class, $class);
		}
        
        return $options;
    }

	protected function _getClasses()
	{
		$css = file_get_contents(JPATH_ADMINISTRATOR.'/templates/bluestork/css/template.css');

        preg_match_all('/\n\.icon-16-(.*?)\s/i', $css, $classes);

		return $classes[1];
	}
}
