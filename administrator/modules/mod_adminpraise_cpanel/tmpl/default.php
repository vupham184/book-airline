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
?>

<div id="cpanel">
<?php
	$link = 'index.php?option=com_adminpraise&view=settings';
	modAdminpraiseCpanelHelper::quickiconButton( $link, 'icon-48-config.png', JText::_( 'MOD_ADMINPRAISE_CPANEL_SETTINGS' ) );
	$link = 'index.php?option=com_adminpraise&view=adminitems';
	modAdminpraiseCpanelHelper::quickiconButton( $link, 'icon-48-menus.png', JText::_( 'MOD_ADMINPRAISE_CPANEL_MENU_MANAGER' ) );
	$link = 'index.php?option=com_adminpraise&view=logs';
	modAdminpraiseCpanelHelper::quickiconButton( $link, 'icon-48-log.png', JText::_( 'MOD_ADMINPRAISE_CPANEL_LOGS' ) );
	$link = 'index.php?option=com_adminpraise&view=help';
	modAdminpraiseCpanelHelper::quickiconButton( $link, 'icon-48-help.png', JText::_( 'MOD_ADMINPRAISE_CPANEL_HELP' ) );
	// Adding ALU live update icon
	echo LiveUpdate::getIcon();
?>
</div>
