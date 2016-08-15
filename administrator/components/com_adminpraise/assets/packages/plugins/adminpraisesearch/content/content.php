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
defined('_JEXEC') or die('Restricted access');

jimport('joomla.plugin.plugin');

class plgAdminpraisesearchContent extends JPlugin {

	public function __construct(&$subject, $config) {
		parent::__construct($subject, $config);
	}

	public function onSearch($word) {

		$db = JFactory::getDBO();
		$limit = $this->params->get('search_limit', 50);

		$text = trim($word);
		if ($text == '') {
			return array();
		}

		$wheres = array();

		$text = $db->Quote('%' . $db->escape($text, true) . '%', false);
		$wheres2 = array();
		$wheres2[] = 'a.title LIKE ' . $text;
		$wheres2[] = 'a.introtext LIKE ' . $text;
		$wheres2[] = 'a.fulltext LIKE ' . $text;
		$wheres2[] = 'a.metakey LIKE ' . $text;
		$wheres2[] = 'a.metadesc LIKE ' . $text;
		$where = '(' . implode(') OR (', $wheres2) . ')';

		$query = 'SELECT id, a.title AS name,'
				. ' CONCAT(a.introtext, a.fulltext) AS text'
				. ' FROM #__content AS a'
				. ' WHERE ( ' . $where . ' )'
				. ' GROUP BY a.id'
				. ' ORDER BY a.title ASC';
		$db->setQuery($query, 0, $limit);
		$list = $db->loadObjectList();

		if (isset($list)) {
			foreach ($list as $key => $item) {
				$list[$key]->name = $item->name;
				$list[$key]->href = JRoute::_('index.php?option=com_content&task=article.edit&id=' . $item->id, false);
				$list[$key]->elementType = JText::_('Articles');
			}
		}

		return $list;
	}

}