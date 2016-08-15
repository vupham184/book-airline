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


class AdminpraiseModelAjax extends JModel {

	public function changePosition($id_from, $id_to) {

		jimport('joomla.database.table');
		jimport('joomla.database.table.module');

		$db = JFactory::getDBO();

		/*
		 * Getting the 'from' module data
		 */ 
		$table_from = JTable::getInstance('Module', 'JTable', array('dbo' => $db));
		$table_from->load($id_from);

		$from_position = $table_from->position;
		$from_ordering = $table_from->ordering;

		/*
		 * Getting the 'to' module data
		 */ 
		$table_to = JTable::getInstance('Module', 'JTable', array('dbo' => $db));
		$table_to->load($id_to);

		$to_position = $table_to->position;
		$to_ordering = $table_to->ordering;

		/*
		 * Fix if both ordering is the same
		 */ 
		if ($from_ordering == $to_ordering) {
			$to_ordering = $to_ordering+1;
		}

		/*
		 * Setting the 'from' module data with 'to' data
		 */ 
		$table_from->position = $to_position;
		$table_from->ordering = $to_ordering;

		if (!$table_from->store()) {
			JError::raiseError(500, $table_from->getError() );
		}

		/*
		 * Setting the 'from' module data with 'to' data
		 */ 
		$table_to->position = $from_position;
		$table_to->ordering = $from_ordering;

		if (!$table_to->store()) {
			JError::raiseError(500, $table_to->getError() );
		}

		return true;
	}
	
	public function unpublishModule($moduleId) {
		$db = JFactory::getDBO();
		
		$query = 'UPDATE ' . $db->quoteName('#__modules')
				. ' SET published = 0'
				. ' WHERE id = ' . $db->Quote($moduleId);
		$db->setQuery($query);
		return $db->query();
	}
}
