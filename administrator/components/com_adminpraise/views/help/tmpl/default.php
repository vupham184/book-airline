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

<div class="main help-main">
	<div class="padding">
		<h2>AdminPraise Help</h2>
		<?php
		jimport('joomla.html.pane');
		
		$pane =& JPane::getInstance('tabs', '');
		echo $pane->startPane( 'pane' );
		
		
		echo $pane->startPanel(JText::_('COM_ADMINPRAISE_SETTINGS'), "Settings");
		require_once('settings.php');
		echo $pane->endPanel();
		
		echo $pane->startPanel(JText::_('COM_ADMINPRAISE_MENU'), "Menu");
		require_once('menu.php');
		echo $pane->endPanel();
		
		echo $pane->startPanel(JText::_('COM_ADMINPRAISE_LOGS'), "Logs");
		require_once('logs.php');
		echo $pane->endPanel();
		
		echo $pane->startPanel(JText::_('COM_ADMINPRAISE_UPDATE'), "Update");
		require_once('update.php');
		echo $pane->endPanel();
		
		echo $pane->startPanel(JText::_('COM_ADMINPRAISE_MODULES'), "Modules");
		require_once('modules.php');
		echo $pane->endPanel();
		
		
		echo $pane->endPane();
		?>
	</div>
</div>

<div class="right help-right">
	<div class="padding">
		<h2>Helpful Links</h2>
		<ul>
			<li><a href="http://www.adminpraise.com/documentation/templates/adminpraise-3.php">AP3 Documentation</a></li>
			<li><a href="http://forum.pixelpraise.com/viewforum.php?f=210">AP3 Forums</a></li>
			<li><a href="http://vimeo.com/album/1550880">AP3 Videos</a></li>
			<li><a href="http://forum.pixelpraise.com/viewtopic.php?f=210&t=7133">AP3 Quickstart</a></li>
		</ul>
	</div>
</div>

