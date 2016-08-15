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

class modMyEditorHelper
{
	function getEditors()
	{
		$dbo = JFactory::getDBO();

		$query = 'SELECT element, name text '.
			'FROM #__extensions '.
			'WHERE folder = "editors" '.
			'AND enabled = 1 '.
			'ORDER BY ordering, name';

		$dbo->setQuery($query);
		$editors = $dbo->loadObjectList();

		//echo $dbo->getErrorNum();

		return $editors;
	}

}

