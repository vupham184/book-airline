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

<script type="text/javascript">
		Joomla.submitbutton = function(pressbutton) {
			var form = document.adminForm;
			if (pressbutton == 'cancelItem') {
				Joomla.submitform( pressbutton );
				return;
			}

			// do field validation
			if (!getSelectedValue( 'adminForm', 'menu' )) {
				alert( "<?php echo JText::_( 'COM_ADMINPRAISE_SELECT_MENU_FROM_THE_LIST', true ); ?>" );
			} else {
				Joomla.submitform( pressbutton );
			}
		}
		</script>

<form action="index.php" method="post" name="adminForm">
	<table class="adminform">
		<tr>
			<td width="3%"></td>
			<td  valign="top" width="30%">
			<strong><?php echo JText::_( 'COM_ADMINPRAISE_COPY_TO_MENU' ); ?>:</strong>
			<br />
			<?php echo $this->MenuList; ?>
			<br /><br />
			</td>
			<td  valign="top">
			<strong><?php echo JText::_( 'COM_ADMINPRAISE_MENU_ITEMS_BEING_COPIED' ); ?>:</strong>
			<br />
			<ol>
				<?php foreach ( $this->items as $item ) : ?>
				<li><?php echo $item->title; ?></li>
				<?php endforeach; ?>
			</ol>
			</td>
		</tr>
	</table>

	<input type="hidden" name="option" value="com_adminpraise" />
	<input type="hidden" name="view" value="menu" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="menutype" value="<?php echo $this->menutype; ?>" />
	<input type="hidden" name="task" value="" />
<?php foreach ( $this->items as $item ) : ?>
	<input type="hidden" name="cid[]" value="<?php echo $item->id; ?>" />
<?php endforeach; ?>
	<?php echo JHTML::_( 'form.token' ); ?>
</form>