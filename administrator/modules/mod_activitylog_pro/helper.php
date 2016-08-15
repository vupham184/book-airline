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

class modActivityLogHelperHelper {
	public static $db;
	public static $reset;
	public static $limit; 
	public static $confName;
	public static $dformat;
	
	public function initializeParams($params) {
		self::$db = &JFactory::getDBO();
		self::$dformat  = $params->get( 'dateformat' );
		self::$confName = $params->get( 'conf_name' );
		self::$limit = (int)$params->get('limit');
		self::$reset = (int) JRequest::getVar('ualog_reset');
	}
	
	public function getUsers() {
		$query = 'SELECT DISTINCT ' . self::$db->quoteName('ualog.user_id') . ',' 
									. self::$db->quoteName('users.id') . ',' 
									. self::$db->quoteName('users.name') . ','
									. self::$db->quoteName('users.username') 
				. ' FROM  ' . self::$db->quoteName('#__ualog') . ' AS  ualog '
				. ' LEFT JOIN ' . self::$db->quoteName('#__users') . ' AS users '
				. '	ON ualog.user_id = users.id ' 
				. ' ORDER BY users.name,users.username ASC';
		self::$db->setQuery($query);
		return self::$db->loadObjectList();
	}
	
	public function getOptions() {
		$query = 'SELECT ' . self::$db->quoteName('option') 
				. ' FROM  ' . self::$db->quoteName('#__ualog')
				. ' GROUP BY ' . self::$db->quoteName('option')
				. ' ORDER BY ' . self::$db->quoteName('option') . ' ASC';
        self::$db->setQuery($query);
        return self::$db->loadResultArray();
	}
	
	public function getActivity() {
		require_once(JPATH_ADMINISTRATOR. '/components/com_adminpraise/models/activity.php');
		$adminpraiseModel = new AdminpraiseModelActivity();
		return $adminpraiseModel->getData(self::$confName, self::$limit);
	}
	
	public function prepareData($activities) {
		foreach($activities as $key => $value) {
			JFilterOutput::objectHTMLSafe($value);
			
			$user = "<a href='index.php?option=com_users&task=user.edit&id=$value->user_id'>$value->name</a>";
			$count = substr_count(JText::_($value->action_title), '%s');
			if($count == 0) {
				$action_title = JText::_($value->action_title);
			} elseif($count == 1) {
				
				$action_title = JText::sprintf($value->action_title, $user);
			} elseif ($count == 2) {
				$link = "<a href='$value->action_link'>$value->item_title</a>";
				$action_title = JText::sprintf($value->action_title, $user, $link);
			}
			
			$activities[$key]->action_title = $action_title;

			$activities[$key]->crdate = date(self::$dformat, $value->cdate);
		}
		
		return $activities;
	}

}

?>
