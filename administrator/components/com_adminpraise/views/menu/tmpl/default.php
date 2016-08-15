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
JHTML::_('script', 'menu.js' ,  JURI::root().'media/com_adminpraise/js/', true);
	
?>


<div class="menu-types" >
	<span class="menu-types-label"> <?php echo JText::_('COM_ADMINPRAISE_MENUS') ?> :</span>

	<form name="adminMenuForm" class="admin-menu-form">
	<select name="adminMenuFormSelect" class="filter_admin_menu" onchange="location 
	= document.adminMenuForm.adminMenuFormSelect.options [document.adminMenuForm.adminMenuFormSelect.selectedIndex].value;">
	     <option> - <?php echo JText::_( 'COM_ADMINPRAISE_SELECT_ADMIN_MENU' );?> - </option>
	     <?php foreach($this->types as $key => $value) : ?>
	     <option value="<?php echo JRoute::_('index.php?option=com_adminpraise&view=menu&menutype='.$value->id); ?>"><?php echo $value->title; ?></option>
	     <?php endforeach; ?>
	</select>
	</form> 	
</div>
<div class="clear"></div>
<form action="index.php?option=com_adminpraise&amp;menutype=<?php echo $this->menutype; ?>" method="post" name="adminForm">

	<table>
		<tr>
			<td align="left" width="100%">
				<?php echo JText::_( 'COM_ADMINPRAISE_FILTER' ); ?>:
				<input type="text" name="search" id="search" value="<?php echo htmlspecialchars($this->lists['search']);?>" class="text_area" onchange="document.adminForm.submit();" />
				<button onclick="this.form.submit();"><?php echo JText::_( 'COM_ADMINPRAISE_GO' ); ?></button>
				<button onclick="document.getElementById('search').value='';this.form.getElementById('levellimit').value='10';this.form.getElementById('filter_state').value='';this.form.submit();"><?php echo JText::_( 'COM_ADMINPRAISE_RESET' ); ?></button>
			</td>
			<td nowrap="nowrap">
				<?php
				echo JText::_( 'COM_ADMINPRAISE_MAX_LEVELS' );
				echo $this->lists['levellist'];
				echo $this->lists['state'];
				?>
			</td>
		</tr>
	</table>

<table class="adminlist">
	<thead>
		<tr>
			<th width="20">
				<?php echo JText::_( 'COM_ADMINPRAISE_NUM' ); ?>
			</th>
			<th width="20">
				<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->items); ?>);" />
			</th>
			<th class="title">
				<?php echo JHTML::_('grid.sort',   'Menu Item', 'm.title', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
			</th>
			<th width="5%" nowrap="nowrap">
				<?php echo JHTML::_('grid.sort',   'Published', 'm.published', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
			</th>
			<th width="8%" nowrap="nowrap">
				<?php echo JHTML::_('grid.sort',   'Order by', 'm.ordering', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
				<?php if ($this->ordering) echo JHTML::_('grid.order',  $this->items ); ?>
			</th>
			<th width="20%" class="title">
				<?php echo JHTML::_('grid.sort',   'Type', 'm.type', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
			</th>
			<th width="1%" nowrap="nowrap">
				<?php echo JHTML::_('grid.sort',   'Itemid', 'm.id', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
			</th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<td colspan="12">
				<?php echo $this->pagination->getListFooter(); ?>
			</td>
		</tr>
	</tfoot>
	<tbody>
	<?php
	$k = 0;
	$i = 0;
	$n = count( $this->items );
	$rows = &$this->items;
	foreach ($rows as $row) :
//		$access 	= AdminpraiseMenuHelper::access($row, $i );

		$checked 	= JHTML::_('grid.checkedout',   $row, $i );
		$published 	= JHTML::_('grid.published', $row, $i );
		?>
		<tr class="<?php echo "row$k"; ?>">
			<td>
				<?php echo $i + 1 + $this->pagination->limitstart;?>
			</td>
			<td>
				<?php echo $checked; ?>
			</td>
			<td nowrap="nowrap">

				<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_ADMINPRAISE_EDIT_MENU' );?>::<?php echo $row->treename; ?>">
				<a href="<?php echo JRoute::_('index.php?option=com_adminpraise&view=menu&task=edit&menutype='.$row->menutype.'&cid[]=' . $row->id); ?>"><?php echo $row->treename; ?></a></span>

			</td>
			<td align="center">
				<?php echo $published;?>
			</td>
			<td class="order" nowrap="nowrap">
				<span><?php echo $this->pagination->orderUpIcon( $i, $row->parent_id == 0 || $row->parent_id == @$rows[$i-1]->parent_id, 'orderup', 'Move Up', $this->ordering); ?></span>
				<span><?php echo $this->pagination->orderDownIcon( $i, $n, $row->parent_id == 0 || $row->parent_id == @$rows[$i+1]->parent_id, 'orderdown', 'Move Down', $this->ordering ); ?></span>
				<?php $disabled = $this->ordering ?  '' : 'disabled="disabled"'; ?>
				<input type="text" name="order[]" size="5" value="<?php echo $row->ordering; ?>" <?php echo $disabled ?> class="text_area" style="text-align: center" />
			</td>
			<td>
				<span class="editlinktip" style="text-transform:capitalize"><?php echo ($row->type == 'component') ? $row->view : $row->type; ?></span>
			</td>
			<td align="center">
				<?php echo $row->id; ?>
			</td>
		</tr>
		<?php
		$k = 1 - $k;
		$i++;
		?>
	<?php endforeach; ?>
	</tbody>
	</table>

	<input type="hidden" name="option" value="com_adminpraise" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="view" value="menu" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>
