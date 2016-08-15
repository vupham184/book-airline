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

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

jimport( 'joomla.application.component.model' );
jimport('joomla.application.module.helper');

class AdminpraiseModelActivity extends JModel
{
	public function reset() {
		$db = JFactory::getDBO();
		
		$query = 'TRUNCATE ' . $db->quoteName('#__ualog');
	    $db->setQuery($query);
	    $db->query();

		if($db->getErrorMsg()) {
			return false;
		}
		
		return true;
		
	}

	public function save() {
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		if(function_exists('ualog_save')) {
			$item   = $db->quote( "" );
			$alink  = $db->quote( "" );
			$atitle = $db->quote( $user->username . " cleared the activity log" );
			ualog_save( $alink, $atitle, $item );
			
			if($db->getErrorMsg()) {
				return false;
			}
			
			return true;
		}
	}
	
	public function getData($confName = null, $limit = null) {
		$db = JFactory::getDBO();

		$where = '';		
		$userId       = JRequest::getInt('ualog_filter_id');
		$filterOption = JRequest::getCmd('ualog_filter_option');
		if($confName == null) {
			$confName =  JRequest::getString('conf_name', 'u.name' );
		}
		if($limit == null) {
			$limit = JRequest::getInt('limit');
		}
		
		
		if($userId) {
			$where[] = ' u.id = ' . $db->Quote($userId);
		}

		if($filterOption) {
			$where[] = ' l.option =' . $db->Quote($filterOption);
		}
		
		if(is_array($where)) {
			$where = ' WHERE ' . implode(' AND ', $where);
		}
		
		$query = "SELECT l.*, ".$confName." FROM #__ualog AS l"
       . "\n RIGHT JOIN #__users AS u ON u.id = l.user_id"
       . $where
       . "\n ORDER BY l.cdate DESC LIMIT ".$limit;
       $db->setQuery($query);

       return $this->findAction($db->loadObjectList());
	
	}
	
	private function findAction($actions = array()) {
		foreach ($actions as $key => $action) {
			if(preg_match("/\bupdated\b/i", $action->action_title)) {
				$actions[$key]->type = 'updated';
			} else 	if(preg_match("/\bpublished\b/i", $action->action_title)) {
				$actions[$key]->type = 'published';
			} else if(preg_match("/\bunpublished\b/i", $action->action_title)) {
				$actions[$key]->type = 'unpublished';
			} else if(preg_match("/\bcreated\b/i", $action->action_title)) {
				$actions[$key]->type = 'created';
			} else if(preg_match("/\bdeleted\b/i", $action->action_title)) {
				$actions[$key]->type = 'deleted';
			} else if(preg_match("/\bcleared\b/i", $action->action_title)) {
				$actions[$key]->type = 'cleared';
			} else if(preg_match("/\blogged\b/i", $action->action_title)) {
				$actions[$key]->type = 'logged';
			} else {
				$actions[$key]->type = '';
			}
			
		}
		
		return $actions;
	}
}
