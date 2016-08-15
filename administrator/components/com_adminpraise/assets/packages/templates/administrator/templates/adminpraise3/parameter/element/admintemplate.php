<?php
/**
 * @package		AdminPraise3
 * @author		AdminPraise http://www.adminpraise.com
 * @copyright	Copyright (c) 2008 - 2011 Pixel Praise LLC. All rights reserved.
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
// no direct access
defined('_JEXEC') or die('Restricted access');

class JElementAdminTemplate extends JElement
{

	var	$_name = 'admintemplate';

	function fetchElement($name, $value, &$node, $control_name) {
		//include_once(JPATH_ADMINISTRATOR.'/components/com_templates/helpers/template.php');
    
		$template_path = JPATH_ADMINISTRATOR.'/templates';
		//$templates = TemplatesHelper::parseXMLTemplateFiles($template_path);
		
		$options = array();
		$options[] = JHTML::_('select.option', '', JText::_('DEFAULT'));
		foreach($templates as $t) :
			$options[] = JHTML::_('select.option', $t->directory, $t->name);
		endforeach;

		return JHTML::_('select.genericlist',  $options, ''.$control_name.'['.$name.'][]', 'class="inputbox"', 'value', 'text', $value );
		
	}
	
}
