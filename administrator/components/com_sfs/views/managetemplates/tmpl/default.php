<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

//Init tooltip
JHtml::_('behavior.tooltip');

$listOrder	= $this->state->get('list.ordering');
$listDirn	= $this->state->get('list.direction');
$ordering	= $listOrder == 'ordering';

$db = JFactory::getDbo();

// $query = 'SELECT c.user_id,c.group_id,c.telephone,c.fax,u.name as fullname,u.username,'
//     . 'u.email,i.name,i.currency_code,i.code'
//     . ' FROM #__sfs_contacts AS c'
//     . ' INNER JOIN #__users AS u ON u.id = c.user_id'
//     . ' INNER JOIN #__sfs_airline_user_map AS m ON m.user_id = c.user_id'
//     . ' INNER JOIN #__sfs_airline_details AS d ON d.id = m.airline_id'
//     . ' INNER JOIN #__sfs_iatacodes AS i ON i.id = d.iatacode_id'       
//     . ' WHERE c.grouptype IN (2,3) AND i.type = 1';

$query = 'SELECT a.id, b.name'
	. ' FROM  #__sfs_airline_details AS a'
	. ' INNER JOIN #__sfs_iatacodes AS b ON b.id = a.iatacode_id'
	. ' WHERE b.type = 1';

$db->setQuery($query);
$result = $db->loadObjectList();
?>
<form action="<?php echo JRoute::_('index.php?option=com_sfs&view=managetamplates'); ?>" method="post" name="adminForm">

	<fieldset id="filter-bar">
		<div class="filter-search fltlft">
			<label class="filter-search-lbl" for="filter_search"><?php echo JText::_('JSEARCH_FILTER_LABEL'); ?></label>
			<input type="text" name="filter_search" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('COM_SEARCH_IN_TITLE'); ?>" />
			<button type="submit"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
			<button type="button" onclick="document.id('filter_search').value='';this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>
		</div>
	</fieldset>
	<div class="clr"> </div>
	
	<table class="adminlist">
		<!-- Header -->
		<thead>
			<tr>
				<th width="2%" style="text-align: center">
					<?php echo JHtml::_('grid.sort', 'JGLOBAL_FIELD_ID_LABEL', 'a.id', $listDirn, $listOrder); ?>
				</th>
				<th width="2%">
					<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->items); ?>);" />
				</th>			
				<th style="text-align: left" width="10%">
					Name Airline
				</th>
				<th width="15%">
					Logo desktop
				</th>
				<th width="15%">
					Header desktop
				</th>
				<th width="15%">
					Logo header mobile
				</th>
				<th width="15%">
					Logo voucher mobile
				</th>
				<th width="20%">
					Logo creditcard mobile
				</th>
			</tr>
		</thead>
		
		<!-- Body -->
		<tbody>
		<?php foreach($this->items as $i => $item): 
		?>
			<tr class="row<?php echo $i % 2; ?>">
				<td align="center">
					<?php echo $item->id; ?>
				</td>
				<td align="center">
					<?php echo JHtml::_('grid.id', $i, $item->id); ?>
				</td>
				<td>
					<a href="<?php echo JRoute::_('index.php?option=com_sfs&task=managetemplate.edit&id=' . (int)$item->id); ?>">
						<?php 							
							foreach ($result as $value) {
								if($value->id == $this->escape($item->name_airline)){
									echo $value->name;
								}						
							}												
						?>
					</a>		
				</td>
				<td align="center"><?php echo substr($item->logo_airline_desktop, 34); ?></td>
				<td align="center"><?php echo substr($item->header_airline_desktop, 34); ?></td>	
				<td align="center"><?php echo substr($item->logo_header_mobile, 34); ?></td>	
				<td align="center"><?php echo substr($item->logo_voucher_mobile, 34); ?></td>	
				<td align="center"><?php echo substr($item->logo_creditcard_mobile, 34); ?></td>	
							
			</tr>
		<?php endforeach; ?>
		</tbody>
		
		<!-- Footer -->
		<tfoot>
			<tr>
				<td colspan="8"><?php echo $this->pagination->getListFooter(); ?></td>
			</tr>
		</tfoot>
		
	</table>
	
	
	<div>
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>
