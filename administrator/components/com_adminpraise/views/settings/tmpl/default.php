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



<form name="adminForm" class="adminFrom" method="post" action="index.php">
	
<?php
jimport('joomla.html.pane');

$pane =& JPane::getInstance('tabs', '');
echo $pane->startPane( 'pane' );


echo $pane->startPanel(JText::_('COM_ADMINPRAISE_LAYOUT'), "Layout");
	require_once('layout.php');
echo $pane->endPanel();

//echo $pane->startPanel(JText::_('COM_ADMINPRAISE_LOGO'), "Logo");
//	require_once('logo.php');
//echo $pane->endPanel();
//
//echo $pane->startPanel(JText::_('COM_ADMINPRAISE_MENUS'), "Menu");
//	require_once('menu.php');
//echo $pane->endPanel();


echo $pane->endPane();
?>
<?php echo JHTML::_( 'form.token' ); ?>


<input type="hidden" name="option" value="com_adminpraise" />
	<input type="hidden" name="controller" value="settings" />
	<input type="hidden" name="view" value="settings" />
	<input type="hidden" name="task" value="" />
</form>

