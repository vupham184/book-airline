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

class AdminpraiseMenuHelper {

	/**
	 * clean system cache
	 */
	public function cleanCache() {
		$mainframe = JFactory::getApplication();

		if ($mainframe->getCfg('caching')) {
			// clean system cache
			$cache = & JFactory::getCache('_system');
			$cache->clean();

			// clean mod_mainmenu cache
			$cache2 = & JFactory::getCache('mod_adminpraise_menu');
			$cache2->clean();
		}
	}

	/**
	 * Get a list of the menutypes
	 * @return array
	 */
	public function getMenuTypes() {
		$db = &JFactory::getDBO();
		$query = 'SELECT * ' .
				' FROM #__adminpraise_menu_types';
		$db->setQuery($query);
		return $db->loadObjectList();
	}

	/**
	 * Get a list of the menu_types records
	 * @return array An array of records as objects
	 */
	public function getMenuTypeList() {
		$db = &JFactory::getDBO();
		$query = 'SELECT a.*, SUM(b.home) AS home' .
				' FROM #__menu_types AS a' .
				' LEFT JOIN #__menu AS b ON b.menutype = a.menutype' .
				' GROUP BY a.id';
		$db->setQuery($query);
		return $db->loadObjectList();
	}

	public function getDynamicLinks() {
		jimport('joomla.filesystem');
		$dynMenusLocation = JPATH_ADMINISTRATOR . DS . 'modules' . DS . 'mod_adminpraise_menu' . DS . 'dynamic';
		$exclude = array('.svn', 'CVS', 'index.html');
		$files = JFolder::files($dynMenusLocation, '.', false, false, $exclude);
		$links = array();
		foreach($files as $key => $file) {
			$name = str_replace('.php', '', $file);
			$links[$name] = array(
				'name' => ucfirst($name),
				'link' => 'modules/mod_adminpraise_menu/dynamic/'.$file,
				'description' => JText::_('COM_ADMINPRAISE_DYNAMIC_'.  strtoupper($name))
			);
		}

		return $links;
	}

	/**
	 * Based on the libraries/joomla/html/html/grid.php access function
	 * 
	 * @param object $row
	 * @param int $i
	 * @return string 
	 */
	public function access(&$row, $i) {
//		if (!$row->access) {
//			$color_access = 'style="color: green;"';
//			$task_access = 'accessAdministrator';
//		} else if ($row->access == 1) {
//			$color_access = 'style="color: red;"';
//			$task_access = 'accessSuperUser';
//		} else {
//			$color_access = 'style="color: black;"';
//			$task_access = 'accessManager';
//		}
//
//		$groups = array(
//			'0' => 'Manager',
//			'1' => 'Administrator',
//			'2' => 'Super Administrator'
//		);
//
//		$href = '
//		<a href="javascript:void(0);" onclick="return listItemTask(\'cb' . $i . '\',\'' . $task_access . '\')" ' . $color_access . '>
//		' . JText::_($groups[$row->access]) . '</a>'
//		;
//
//		return $href;
	}

	/**
	 * Build the select list for access level
	 */
	public function accessLevel(&$row) {
		$manager = new JObject();
		$manager->set('value', 0);
		$manager->set('text', 'Manager');
		
		$administrator = new JObject();
		$administrator->set('value', 1);
		$administrator->set('text', 'Administrator');
		
		$superAdmin = new JObject();
		$superAdmin->set('value', 2);
		$superAdmin->set('text', 'Super Administrator');
		
		$groups = array(
			'0' => $manager,
			'1' => $administrator,
			'2' => $superAdmin
		);
		$access = JHTML::_('select.genericlist', $groups, 'access', 'class="inputbox" size="3"', 'value', 'text', intval($row->access), '', 1);

		return $access;
	}

	public function getUserLink($task = null, $filterType = null, $filterLogged = null) {
		$db = &JFactory::getDBO();

		$query = "SELECT link AS admin_menu_link FROM #__menu WHERE link LIKE '%option=com_comprofiler%' AND title = 'User Management'";
		$db->setQuery($query);
		$r = $db->loadResult();

		if ($r) {
			$link = 'index.php?option=com_comprofiler';

			if (!$task) {
				$task = 'showusers';
			} else if ($task == 'add') {
				$task = 'new';
			}
		} else {
			$link = 'index.php?option=com_users';
		}

		if ($task) {
			$link .= '&task=' . $task;
		}
		if ($filterType) {
			$link .= '&filter_type=' . $filterType;
		}
		if ($filterLogged) {
			$link .= '&filter_logged=' . $filterLogged;
		}

		return $link;
	}

	/**
	 * Build the select list for parent menu item
	 */
	public function parentItem(&$row) {
		$db = & JFactory::getDBO();

		// If a not a new item, lets set the menu item id
		if ($row->id) {
			$id = ' AND id != ' . (int) $row->id;
		} else {
			$id = null;
		}

		// In case the parent was null
		if (!$row->parent_id) {
			$row->parent_id = 0;
		}

		// get a list of the menu items
		// excluding the current menu item and its child elements
		$query = 'SELECT m.*' .
				' FROM #__adminpraise_menu m' .
				' WHERE published != -2' .
				$id .
				' AND menutype = ' . JRequest::getInt('menutype') .
				' ORDER BY parent_id, ordering';
		$db->setQuery($query);
		$mitems = $db->loadObjectList();

		// establish the hierarchy of the menu
		$children = array();

		if ($mitems) {
			// first pass - collect children
			foreach ($mitems as $v) {
				$pt = $v->parent_id;
				$list = @$children[$pt] ? $children[$pt] : array();
				array_push($list, $v);
				$children[$pt] = $list;
			}
		}

		// second pass - get an indent list of the items
		$list = JHTML::_('menu.treerecurse', 0, '', array(), $children, 9999, 0, 0);

		// assemble menu items to the array
		$mitems = array();
		$mitems[] = JHTML::_('select.option', '0', JText::_('Top'));

		foreach ($list as $item) {
			$mitems[] = JHTML::_('select.option', $item->id, '&nbsp;&nbsp;&nbsp;' . $item->treename);
		}

		$output = JHtml::_('select.genericlist', $mitems, 'parent_id', array(
				'option.text.toHtml' => false ,
				'list.attr' => 'class="inputbox" size="10" multiple="multiple" ',
				'option.text' => 'text' ,
				'option.key' => 'value',
				'list.select' => $row->parent_id,
		)) ;
		return $output;
	}

	public function ordering(&$row, $id) {
		$db = & JFactory::getDBO();

		if ($id) {
			$query = 'SELECT ordering AS value, title AS text'
					. ' FROM #__adminpraise_menu'
					. ' WHERE parent_id = ' . (int) $row->parent_id
					. ' AND published != -2'
					. ' ORDER BY ordering';
			
			$order = JHTML::_('list.genericordering', $query);
			$ordering = JHtml::_('select.genericlist', $order, 'ordering', 'class="inputbox" size="1"', 'value', 'text', intval($row->ordering));
		} else {
			$ordering = '<input type="hidden" name="ordering" value="' . $row->ordering . '" />' . JText::_('DESCNEWITEMSLAST');
		}
		return $ordering;
	}

	/**
	 * build the select list for target window
	 */
	public function target(&$row) {
		$click[] = JHTML::_('select.option', '0', JText::_('Parent Window With Browser Navigation'));
		$click[] = JHTML::_('select.option', '1', JText::_('New Window With Browser Navigation'));
		$click[] = JHTML::_('select.option', '2', JText::_('New Window Without Browser Navigation'));
		$target = JHTML::_('select.genericlist', $click, 'browserNav', 'class="inputbox" size="4"', 'value', 'text', intval($row->browserNav));

		return $target;
	}
	
}

?>
