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

class modAdminpraiseCpanelHelper {

	public function quickiconButton( $link, $image, $text ) {
		$lang		=& JFactory::getLanguage();
		$imgPath = JURI::root().'/media/mod_adminpraise_cpanel/images/';
		?>
		<div style="float:<?php echo ($lang->isRTL()) ? 'right' : 'left'; ?>;">
			<div class="icon">
				<a href="<?php echo JRoute::_($link); ?>">
					<img src="<?php echo $imgPath.$image ?>" alt="<?php echo $text; ?>" />
					<span><?php echo $text; ?></span></a>
			</div>
		</div>
		<?php
	}

}

?>
