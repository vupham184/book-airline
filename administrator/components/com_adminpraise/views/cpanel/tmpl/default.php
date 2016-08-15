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
$document	= &JFactory::getDocument();
$renderer	= $document->loadRenderer('module');
$attribs 	= array();
$attribs['style'] = 'xhtml';
?>
<div class="adminpraise-cpanel">
	<div class="ap-cpanel-main">
	<?php foreach ( @$this->modules['main'] as $mod ) : ?>
		<?php echo $renderer->render($mod, $attribs); ?>
	<?php endforeach; ?>
	</div>
	<div class="clr"></div>
	<div class="ap-cpanel-right">
	<?php foreach (@$this->modules['right'] as $mod) : ?>
		<?php echo $renderer->render($mod, $attribs); ?>
	<?php endforeach; ?>
	</div>
</div>
