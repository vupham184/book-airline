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
defined('_JEXEC') or die('Restricted access');

$user = JFactory::getUser();
$conf	= JFactory::getConfig();

$myEditor = $user->getParam('editor'); 

if ( $myEditor == '' ) {
	$myEditor = $conf->get('editor');
}
?>

<script type="text/javascript">
	function updateEditor()
	{
		var editor = document.getElementById('myeditor_selection');

		var request = new Request( {
			url: 'index.php?option=com_adminpraise&format=raw&task=ajax.changeDefaultEditor',
			method: 'get',
			data: 'editor='+editor.value,
			onComplete: function( response ) {
				location.reload();
			}
		}).send();

		colorSelectBox(true);
	}

	function showUpdateSuccess(req)
	{
		setTimeout('colorSelectBox(false)', 1000);
	}

	function colorSelectBox(set)
	{
		var editor=document.getElementById('myeditor_selection');
		if (set)
			editor.setAttribute("style", "border: 3px solid #3AC521");
		else
			editor.removeAttribute("style");
	}
</script>

<select id="myeditor_selection" onChange="javascript:updateEditor()">
<?php 
	foreach ($editors as $editor) {
		if ($myEditor == $editor->element) {
			echo '<option value="'.$editor->element.'" selected="selected">'.$editor->text.'</option>';
		} else {
			echo '<option value="'.$editor->element.'">'.$editor->text.'</option>';
		}
	}
?>
</select>
