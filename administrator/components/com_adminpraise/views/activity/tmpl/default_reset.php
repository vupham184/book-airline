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

if($this->success) {
	$status = 'success';
	foreach($this->data as $key => $value) {
		$row->action_title = JText::_($value->action_title);
		$row->action_title = str_replace('{user}', "<a href='index.php?option=com_users&view=user&task=edit&cid[]=$value->user_id'>$value->name</a>", $value->action_title);

		$messages[] = $row->action_title;
	}
} else {
	$status = 'failure';
	$messages[] = JText::_('MOD_ACTIVITY_LOG_FAILURE_CLEARING_LOG');
}
	
$output = array (
	'status' => $status,
	'message' => implode($messages, '<br />')
);
	
	echo json_encode($output);
?>

