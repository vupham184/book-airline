<?php
/**
 * @package        AdminPraise3
 * @author        AdminPraise http://www.adminpraise.com
 * @copyright    Copyright (c) 2008 - 2011 Pixel Praise LLC. All rights reserved.
 * @license        GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
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

$language = JFactory::getLanguage();
$language->load('com_adminpraise.menu', JPATH_ADMINISTRATOR, null, true);

$view = JRequest::getCmd('view', 'Cpanel');

$subMenus = array(
    'home' => 'COM_ADMINPRAISE_HOME',
    'settings' => 'COM_ADMINPRAISE_SETTINGS',
    'adminitems' => 'COM_ADMINPRAISE_MENU',
    'logs' => 'COM_ADMINPRAISE_LOGS',
    'help' => 'COM_ADMINPRAISE_HELP',
    'liveupdate' => 'COM_ADMINPRAISE_LIVEUPDATE'
);

foreach ($subMenus as $key => $name) {
    $active = ($view == $key);
    if ($key != 'home') {
        JSubMenuHelper::addEntry(JText::_($name), 'index.php?option=com_adminpraise&view=' . $key, $active);
    } else {
        JSubMenuHelper::addEntry(JText::_($name), 'index.php?option=com_adminpraise', $active);
    }
}
?>
