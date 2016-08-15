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
defined('_JEXEC') or die('Restricted access');
require_once(JPATH_COMPONENT_ADMINISTRATOR . '/helpers/menu.php' );


class AdminpraiseModelMenu extends JModel {

	private $_table = null;
	private $_state = null;

	/**
	 * This function tries to get an existing menu type and returns the id of the first element. 
	 * It is called each time
	 * the url doesn't have a menutype element
	 * @return mixed 
	 */
	public function getAMenuType() {
		$table = $this->getTable('MenuTypes', 'AdminpraiseTable');
		$allMenuTypes = $table->getAllMenuTypes();
		
		if(count($allMenuTypes)) {
			return $allMenuTypes[0]->id;
		}
		return 0;
	}
	
	public function getAllMenuTypes() {
		$table = $this->getTable('MenuTypes', 'AdminpraiseTable');
		
		return $table->getAllMenuTypes();
	}

	
	public function getMenuType() {
		static $menuItem;
		if (isset($menuItem)) {
			return $menuItem;
		}

		$table = $this->getTable('MenuTypes', 'AdminpraiseTable');

		// Load the current item if it has been defined
		$edit = JRequest::getVar('edit', true);
		$cid = JRequest::getVar('cid', array(0), '', 'array');
		JArrayHelper::toInteger($cid, array(0));
		if ($edit) {
			$table->load($cid[0]);
		}
		$menuItem = $table;
		return $menuItem;
		
	}

}
