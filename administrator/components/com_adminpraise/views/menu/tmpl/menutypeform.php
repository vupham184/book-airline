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
//<!--
	Joomla.submitbutton = function(pressbutton) {
		var form = document.adminForm;

		if (pressbutton == 'savemenu') {
			if ( form.menutype.value == '' ) {
				alert( '<?php echo JText::_( 'COM_ADMINPRAISE_ENTER_MENU_NAME', true ); ?>' );
				form.menutype.focus();
				return;
			}
			var r = new RegExp("[\']", "i");
			if ( r.exec(form.menutype.value) ) {
				alert( '<?php echo JText::_( 'COM_ADMINPRAISE_MENU_CANNOT_CONTAIN', true ); ?>' );
				form.menutype.focus();
				return;
			}
			Joomla.submitform( 'savemenu' );
		} else {
			Joomla.submitform( pressbutton );
		}
	}
//-->
</script>
<form action="index.php" method="post" name="adminForm">

<table class="adminform">
	<tr>
		<td width="100" >
			<label for="title">
				<strong><?php echo JText::_( 'COM_ADMINPRAISE_TITLE' ); ?>:</strong>
			</label>
		</td>
		<td>
			<input class="inputbox" type="text" name="title" id="title" size="30" maxlength="255" value="<?php echo $this->escape($this->row->title); ?>" />
			<?php echo JHTML::_('tooltip',  JText::_( 'COM_ADMINPRAISE_PROPER_TITLE' ) ); ?>
		</td>
	</tr>
	<tr>
		<td width="100" >
			<label for="description">
				<strong><?php echo JText::_( 'COM_ADMINPRAISE_DESCRIPTION' ); ?>:</strong>
			</label>
		</td>
		<td>
			<input class="inputbox" type="text" name="description" id="description" size="30" maxlength="255" value="<?php echo $this->escape($this->row->description); ?>" />
			<?php echo JHTML::_('tooltip',  JText::_( 'COM_ADMINPRAISE_DESCRIPTION_MENU' ) ); ?>
		</td>
	</tr>
</table>

	<input type="hidden" name="id" value="<?php echo $this->row->id; ?>" />
	<input type="hidden" name="option" value="com_adminpraise" />
	<input type="hidden" name="view" value="menu" />
	<input type="hidden" name="task" value="savemenutype" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>