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

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.plugin.plugin');

class plgAdminpraisesearchAdmin_menu extends JPlugin {
	public function __construct(&$subject, $config){
		parent::__construct($subject, $config);
	}
	
	public function onSearch($word) {
		$db = JFactory::getDBO();
		$limit = $this->params->get( 'search_limit', 50 );
		$query = $this->buildQuery($word);
		
		$db->setQuery( $query, 0, $limit );
		$rows = $db->loadObjectList();
		
		$count = count( $rows );
		for ( $i = 0; $i < $count; $i++ ) {
			$rows[$i]->name = $rows[$i]->name;
			$rows[$i]->href = JRoute::_('index.php?option=com_adminpraise&view=menu&task=edit&menutype='.$rows[$i]->menutype.'&cid[]='.$rows[$i]->id);
			$rows[$i]->elementType	= JText::_( 'Admin Menu' );
		}
		
		return $rows;
	}
	
	private function buildQuery($word) {
		$db = JFactory::getDBO();
		
		$text	= $db->Quote( '%'.$db->escape( $word, true ).'%', false );
		$query = 'SELECT id, title as name , menutype FROM ' . $db->quoteName('#__adminpraise_menu')
				. ' WHERE title LIKE ' . $text . ' OR link LIKE' . $text;
		
		return $query;		
	}
}