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
defined( '_JEXEC' ) or die( 'Restricted access' );
?>

<h3><?php echo JText::_( 'MANAGERS' );?></h3>
<ul>
	<li><a href="index.php?option=com_content"><?php echo JText::_( 'ARTICLE MANAGER' );?></a></li>
	<li><a href="index.php?option=com_sections&scope=content"><?php echo JText::_( 'SECTION MANAGER' );?></a></li>
	<li><a href="index.php?option=com_categories&scope=content"><?php echo JText::_( 'CATEGORY MANAGER' );?></a></li>
	<li><a href="index.php?option=com_users"><?php echo JText::_( 'USER MANAGER' );?></a></li>
</ul>