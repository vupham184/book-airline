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

<script language="javascript" type="text/javascript">
<!--
function submitbutton(pressbutton) {
	var form = document.adminForm;
	var type = form.type.value;

	if (pressbutton == 'cancel') {
		submitform( pressbutton );
		return;
	}
	if ( (type != "separator") && (trim( form.name.value ) == "") ){
		alert( "<?php echo JText::_( 'COM_ADMINPRAISE_ITEM_MUST_HAVE_TITLE', true ); ?>" );
	}
	
	submitform( pressbutton );
	
}
//-->
</script>
<form action="index.php" method="post" name="adminForm">
	<table class="admintable" width="100%">
		<tr valign="top">
			<td width="60%">
				<!-- Menu Item Type Section -->
				<fieldset>
					<legend>
						<?php echo JText::_( 'COM_ADMINPRAISE_MENU_ITEM_TYPE' ); ?>
					</legend>
					<div style="float:right">
						<button type="button" onclick="location.href='index.php?option=com_adminpraise&amp;view=menu&task=type&amp;cid[]=<?php echo $this->item->id; ?>';">
							<?php echo JText::_( 'COM_ADMINPRAISE_CHANGE_TYPE' ); ?></button>
					</div>
					<h2><?php echo $this->name; ?></h2>
					<div>
						<?php echo $this->description; ?>
					</div>
				</fieldset>
				<!-- Menu Item Details Section -->
				<fieldset>
					<legend>
						<?php echo JText::_( 'COM_ADMINPRAISE_MENU_ITEM_DETAILS' ); ?>
					</legend>
					<table width="100%">
						<?php if ($this->item->id) { ?>
						<tr>
							<td class="key" width="20%" align="right">
								<?php echo JText::_( 'COM_ADMINPRAISE_ID' ); ?>:
							</td>
							<td width="80%">
								<strong><?php echo $this->item->id; ?></strong>
							</td>
						</tr>
						<?php } ?>
						<tr>
							<td class="key" align="right">
								<?php echo JText::_( 'COM_ADMINPRAISE_TITLE' ); ?>:
							</td>
							<td>
								<input class="inputbox" type="text" name="title" size="50" maxlength="255" value="<?php echo $this->item->title; ?>" />
							</td>
						</tr>
						<tr>
							<td class="key" align="right">
								<?php echo JText::_( 'COM_ADMINPRAISE_NOTE' ); ?>:
							</td>
							<td>
								<input class="inputbox" type="text" name="note" size="50" maxlength="255" value="<?php echo $this->item->note; ?>" <?php echo $this->lists->disabled;?> />
							</td>
						</tr>
						<tr>
							<td class="key" align="right">
								<?php echo JText::_( 'COM_ADMINPRAISE_LINK' ); ?>:
							</td>
							<td>
								<input class="inputbox" type="text" name="link" size="50" maxlength="255" value="<?php echo $this->item->link; ?>" <?php echo $this->lists->disabled;?> />
							</td>
						</tr>
						
						<tr>
							<td class="key" align="right">
								<?php echo JText::_( 'COM_ADMINPRAISE_DISPLAY_IN' ); ?>:
							</td>
							<td>
								<?php echo JHTML::_('select.genericlist',   $this->menuTypes, 'menutype', 'class="inputbox" size="1"', 'id', 'title', $this->item->menutype );?>
							</td>
						</tr>
						
						<tr>
							<td class="key" align="right" valign="top">
								<?php echo JText::_( 'COM_ADMINPRAISE_PARENT_ITEM' ); ?>:
							</td>
							<td>
								<?php echo AdminpraiseMenuHelper::parentItem( $this->item ); ?>
							</td>
						</tr>
						<tr>
							<td class="key" valign="top" align="right">
								<?php echo JText::_( 'COM_ADMINPRAISE_PUBLISHED' ); ?>:
							</td>
							<td>
								<?php echo $this->lists->published ?>
							</td>
						</tr>
						<tr>
							<td class="key" valign="top" align="right">
								<?php echo JText::_( 'COM_ADMINPRAISE_ORDERING' ); ?>:
							</td>
							<td>
								<?php echo AdminpraiseMenuHelper::ordering($this->item, $this->item->id);//JHTML::_('menu.ordering', , $this->item->id ); ?>
							</td>
						</tr>
						<tr>
							<td class="key" valign="top" align="right">
								<?php echo JText::_( 'COM_ADMINPRAISE_ACCESS_LEVEL' ); ?>:
							</td>
							<td>
								<?php

								echo JHTML::_('access.usergroups', 'access', explode(',',$this->item->access))
								
								?>
							</td>
						</tr>
						<?php if ($this->item->type != "menulink") : ?>
						<tr>
							<td class="key" valign="top" align="right">
								<?php echo JText::_( 'COM_ADMINPRAISE_ON_CLICK_OPEN_IN' ); ?>:
							</td>
							<td>
								<?php echo AdminpraiseMenuHelper::target( $this->item ); ?>
							</td>
						</tr>
						<?php endif; ?>
					</table>
				</fieldset>
			</td>
			<!-- Menu Item Parameters Section -->
			<td width="40%">
				<?php
					echo $this->pane->startPane("menu-pane");
					echo $this->pane->startPanel(JText :: _('COM_ADMINPRAISE_BASIC_PARAMS'), "param-page");
					if(count($this->params->getParams('params'))) :
						echo $this->params->render('params');
					endif;

				?>
			</td>
		</tr>
	</table>
	
	<input type="hidden" name="option" value="com_adminpraise" />
	<input type="hidden" name="id" value="<?php echo $this->item->id; ?>" />
	<input type="hidden" name="type" value="<?php echo $this->item->type; ?>" />
	<input type="hidden" name="view" value="menu" />
	<input type="hidden" name="task" value="" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>
