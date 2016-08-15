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

<h3><?php echo JText::_( 'CREATE NEW' );?></h3>
<ul>
	<li><a href="index.php?option=com_content&task=add"><?php echo JText::_( 'NEW ARTICLE' );?></a></li>
	<li><a href="index.php?option=com_modules&task=add"><?php echo JText::_( 'NEW MODULE' );?></a></li>
	<li><a href="index.php?option=com_sections&scope=content&task=add"><?php echo JText::_( 'NEW SECTION' );?></a></li>
	<li><a href="index.php?option=com_categories&scope=content&task=add"><?php echo JText::_( 'NEW CATEGORY' );?></a></li>
</ul>