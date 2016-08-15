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
 * This element creates an upload field
 *
 * @package 	AdminPraise.Framework
 * @subpackage		Parameter
 * @since		1.5
 */

class JElementUpload extends JElement
{
	/**
	* Themes
	*
	* @access	protected
	* @var		string
	*/
	var	$_name = 'Upload';

	function fetchElement($name, $value, &$node, $control_name, $i=0, $element=null, $elements=array())
	{

		$prefix = explode('-',$name);
		$prefix = $prefix[0]; 
		
		$html = array();
		$html[] = '<table class="adminform">';
		$html[] = '<tbody>';					
		$html[] = '<tr>';
		$html[] = '<td width="120" valign="top">';
		$html[] = '<label for="'.$prefix.'adminpraise-image">' . JText::_('COM_ADMINPRAISE_UPLOADIMAGE') . '</label>';
		$html[] = '</td>';
		$html[] = '<td>';
		$html[] = '<input type="file" name="'.$prefix.'adminpraise-image" id="'.$prefix.'adminpraise-image" class="input_box">';
		$html[] = '<input type="button" onclick=" window.prefix = \''.$prefix.'\'; startUpload();" value="' . JText::_('COM_ADMINPRAISE_UPLOAD').'" class="button">';
		$html[] = '<span id="'.$prefix.'upload-process" class="upload-loading" style="display: none;">';
		$html[] = 	'<img src="' . JURI::root().'media/com_adminpraise/images/loading-small.gif' . '" alt="'. JText::_("COM_ADMINPRAISE_LOADING") .'" style="float: left"/>';
		$html[] = '</span>';
		$html[] = '<p class="error" id="'.$prefix.'error-myfile"></p>';
		$html[] = '</td>';
		$html[] = '</tr>';
		$html[] = '</tbody>';
		$html[] = '</table>';
		$html[] = '<iframe id="'.$prefix.'upload-target" name="'.$prefix.'upload-target"  src="" style="width:0px; height:0px; border:0px solid #fff;"></iframe>';

		return implode('',$html);

	}
}
