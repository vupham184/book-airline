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

// Check to ensure this file is within the rest of the framework
defined('_JEXEC') or die('Restricted access');

/**
 * Renders a theme preview element
 *
 * Please note this element is only meant to be used within templates as it expect to be inside the default params group.
 * If you're using this elsewhere and have another value than "_default" in your group="" attribute in the parent <params>
 * element, this particular script wont work.
 *
 * @package 	AdminPraise.Framework
 * @subpackage		Parameter
 * @since		1.5
 */

class JElementThemes extends JElement
{
	/**
	* Themes
	*
	* @access	protected
	* @var		string
	*/
	var	$_name = 'Themes';

	function fetchElement($name, $value, &$node, $control_name, $i=0, $element=null, $elements=array())
	{
		if ($value) {
/*
			foreach($this->_parent->_xml['theme']->children() as $key => $param)
			{
				if($param->attributes('name')==$value)
				{
					$i = $key; 
					$element = $param;
				}
			}
		
			$uri = JFactory::getURI();
			foreach($element->children() as $child)
			{
				$uri->setVar('templateTheme', $child->attributes('value'));
				$style = explode('/', $child->data());
				/*Experimental code below, uncomment to apply styling on each link resembling its theme*/
//				$style[0] = $style[0] ? $style[0] : 'transparent';
//				$style[1] = $style[1] ? $style[1] : 'inherit';
//				$return[] = '<a href="'.$uri->toString().'" style="bordercolor:'.($child->attributes('color')?$child->attributes('color'):$style[1]).';background-color:'.($child->attributes('bgcolor')?$child->attributes('bgcolor'):$style[0]).';">'.$child->data().'</a>';
/*
				$return[] = '<a href="'.$uri->toString().'"'.(JRequest::getCmd('templateTheme')===$child->attributes('value')?' style="font-weight:bold;"' : null).'>'.JText::_($child->data()).'</a>';
				$uri->delVar('templateTheme');
			}
			if($node->attributes('resetlink')) 
			{
				$uri->setVar('templateTheme', null);
				$return[] = '<a href="'.$uri->toString().'">'.JText::_($node->attributes('resettext')?$node->attributes('resettext'):'Reset').'</a>';
				$uri->delVar('templateTheme');
			}
			
			return implode(' - ', $return);
*/
		} else {
			return '<h4>Notice: no value defined.</h4></ br><h5>You need to enter the name of the parameter containing the list of available themes in the default="" attribute.'."\n".'Example:</h5><pre>&lt;param name="@themes" type="themes" default="templateTheme" /&gt;</pre></ br><h5>Example of a parameter listing themes:</h5><pre>&lt;param name="templateTheme" type="list" default="1"&gt;
	&lt;option value="theme1"&gt;Theme 1 - Beige&lt;/option&gt;
	&lt;option value="theme2"&gt;Theme 2 - Dark&lt;/option&gt;
	&lt;option value="theme3"&gt;Theme 3 - Gray&lt;/option&gt;
	&lt;option value="theme4"&gt;Theme 4 - White&lt;/option&gt;
&lt;/param&gt;</pre>';
		}
	}
}
