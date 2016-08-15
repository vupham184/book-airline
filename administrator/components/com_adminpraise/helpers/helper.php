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

class AdminpraiseHelper {
	
	public function checkLogin() {
		$mainframe = JFactory::getApplication();
		DEFINE('GOTOSTARTPAGE_COOKIE', 'ap_gotostartpage');
		DEFINE('LOGINPAGELOCATION_COOKIE', 'ap_loginpagelocation');
		DEFINE('STARTPAGE_COOKIE', 'ap_startpage');

		$gotostartpage = @$_COOKIE[GOTOSTARTPAGE_COOKIE];

		if ($gotostartpage) {
			setcookie(GOTOSTARTPAGE_COOKIE, 0);

			$uri = JFactory::getURI();
			$url = $uri->toString();
			$loginpagelocation = @$_COOKIE[LOGINPAGELOCATION_COOKIE];
			$loginpagelocationuri = new JURI($loginpagelocation);
			$query = $loginpagelocationuri->getQuery();
			if ($query && strpos($query, 'com_login') === FALSE) {
				if ($loginpagelocation && $url != $loginpagelocation) {
					$mainframe->redirect($loginpagelocation);
				}
			} else {
				$startpage = @$_COOKIE[STARTPAGE_COOKIE];
				if ($startpage && $url != $startpage) {
					$mainframe->redirect($startpage);
				}
			}
		}
	}

	/**
	 * build the select list for target window
	 */
	public function published(&$row) {
		$put[] = JHTML::_('select.option', '0', JText::_('No'));
		$put[] = JHTML::_('select.option', '1', JText::_('Yes'));

		// If not a new item, trash is not an option
		if (!$row->id) {
			$row->published = 1;
		}
		$published = JHTML::_('select.radiolist', $put, 'published', '', 'value', 'text', $row->published);
		return $published;
	}
	
	/**
	 *
	 * @return boolean - true if enabled
	 */
	public static function isTemplateEnabled() {
		$db = JFactory::getDBO();
		$query = 'SELECT template FROM ' . $db->quoteName('#__template_styles')
				. ' WHERE template=' . $db->Quote('adminpraise3')
				. ' AND client_id = ' . $db->Quote(1)
				. ' AND home = ' . $db->Quote(1);
		$db->setQuery($query);
		if ($db->loadObject()) {
			return true;
		}
		return false;
	}

	public static function turnAdminpraiseOn() {
		if (!self::isTemplateEnabled()) {
			$link = JRoute::_('index.php?option=com_adminpraise&task=settings.publishTemplate&' . JUtility::getToken() . '=1');
			echo '<div class="adminpraise-warning">';
			echo '<dl id="system-message"><dd class="message fade notice"><ul><li>';
			echo JText::_('COM_ADMINPRAISE_TURN_TEMPLATE_ON');
			echo '<a href="' . $link . '" class="button adminpraise-enable">';
			echo '<span>' . JText::_('COM_ADMINPRAISE_ENABLE_TEMPLATE') . '</span>';
			echo '</a>';
			echo '</li></ul></dd></dl>';
			echo '</div>';
		}
	}
	
	/**
	 * This function is going to be called from the template
	 */
	public function checkAdminMenuHealth() {
		$menuItems = self::checkAdminMenuItems();
		$menuTypes = self::checkAdminMenuTypes();
		$menuModules = self::checkAdminMenuModules();
		
		if(!$menuItems || !$menuTypes || !$menuModules) {
			//make sure that com_adminpraise language is loaded
			$lang = JFactory::getLanguage();
			$lang->load('com_adminpraise', JPATH_BASE, null, true);
			
			echo '<div class="adminpraise-admin-menu-warning">';
			if(!$menuItems) {
				echo '<div class="adminpraise-admin-menu-items-warning">';
				$link = JRoute::_('index.php?option=com_adminpraise&view=adminitems');
				echo JText::sprintf('COM_ADMINPRAISE_NO_ITEMS_WARNING', $link);
				echo '</div>';
			}
			
//			if(!$menuTypes) {
//				echo '<div class="adminpraise-admin-menu-types-warning">';
//				$link = JRoute::_('index.php?option=com_adminpraise&view=adminitems&task=newMenuType');
//				echo JText::sprintf('COM_ADMINPRAISE_NO_TYPES_WARNING', $link);
//				echo '</div>';
//			}
			
			if(!$menuModules) {
				echo '<div class="adminpraise-admin-menu-modules-warning">';
				$link = JRoute::_('index.php?option=com_modules&client=1');
				echo JText::sprintf('COM_ADMINPRAISE_NO_MODULES_WARNING', $link);
				echo '</div>';
			}
			
			echo '<div class="adminpraise-admin-menu-reset-warning">';
			$link = JRoute::_('index.php?option=com_adminpraise&task=adminitems.reset&'. JUtility::getToken() . '=1');
			echo JText::sprintf('COM_ADMINPRAISE_RUBBISH_RESET', $link);
			echo '</div>';
			
			echo '</div>';
		}
	}
	
	/**
	 * If we don't don't have menu items, then something is definetly wrong
	 * @return boolean
	 */
	private function checkAdminMenuItems() {
		$db = JFactory::getDBO();
		$query = 'SELECT count(id) AS count FROM ' . $db->quoteName('#__adminpraise_menu');
		$db->setQuery($query);
		
		if($db->loadObject()->count) {
			return true;
		}
		
		return false;
	}
	
	/**
	 * If we don't have a single menu type, then something is wrong
	 * @return boolean
	 */
	private function checkAdminMenuTypes() {
		$db = JFactory::getDBO();
		$query = 'SELECT count(id) AS count FROM ' . $db->quoteName('#__adminpraise_menu_types');
		$db->setQuery($query);
		
		if($db->loadObject()->count) {
			return true;
		}
		return false;
	}
	
	/**
	 * If we don't have at least 1 published module from type adminpraise_menu
	 * then something is most probably wrong
	 * @return boolean
	 */
	private function checkAdminMenuModules() {
		$db = JFactory::getDBO();
		$query = 'SELECT count(id) AS count FROM ' . $db->quoteName('#__modules')
				. ' WHERE module = ' . $db->Quote('mod_adminpraise_menu')
				. ' AND published = ' . $db->Quote(1);
		$db->setQuery($query);
		
		if($db->loadObject()->count) {
			return true;
		}
		return false;
	}

}

?>
