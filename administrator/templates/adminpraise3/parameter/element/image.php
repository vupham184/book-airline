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
 * @package 	AdminPraise.Framework
 * @subpackage		Parameter
 * @since		1.5
 */

class JElementImage extends JElement
{
	/**
	* @access	protected
	* @var		string
	*/
	public $_name = 'Image';

	public function fetchElement($name, $value, &$node, $control_name, $i=0, $element=null, $elements=array()) {
		$prefix = explode('-', $name);
		$prefix = $prefix[0];
		$imgSrc = $this->get('_parent')->get($prefix);
		
		if(strstr($imgSrc, 'http://') === false) {
			$imgSrc = JURI::root() . $imgSrc;
		}
		$image = '<img src="'.$imgSrc.'" alt="'.$name.'" id="'.$name.'"/>';
		
		return $image;
	}
}
