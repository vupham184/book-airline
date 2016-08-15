<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

//Init tooltip
JHtml::_('behavior.tooltip');

$listOrder	= $this->state->get('list.ordering');
$listDirn	= $this->state->get('list.direction');
$ordering	= $listOrder == 'ordering';
?>
<form action="<?php echo JRoute::_('index.php?option=com_sfs&view=currencies'); ?>" method="post" name="adminForm">

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
				<th width="12">
					<?php echo JHtml::_('grid.sort', 'JGLOBAL_FIELD_ID_LABEL', 'a.id', $listDirn, $listOrder); ?>
				</th>
				<th width="10">
					<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->items); ?>);" />
				</th>			
				<th >
					<?php echo JHtml::_('grid.sort', 'COM_SFS_STATE_FIELD_NAME_LABEL', 'a.name', $listDirn, $listOrder); ?>
				</th>
				<th width="15%">
					<?php echo JHtml::_('grid.sort', 'COM_SFS_FIELD_CURRENCY_NUMERIC_CODE_LABEL', 'a.numeric_code', $listDirn, $listOrder); ?>
				</th>
				<th width="8%">
					<?php echo JHtml::_('grid.sort', 'JFIELD_CODE_LABEL', 'a.code', $listDirn, $listOrder); ?>
				</th>
				<th width="15%">
					<?php echo JHtml::_('grid.sort', 'Exchange Rate to EUR', 'a.exchange_rate', $listDirn, $listOrder); ?>
				</th>
				<th width="8%">
					<?php echo JHtml::_('grid.sort', 'Symbol', 'a.symbol', $listDirn, $listOrder); ?>
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
					<a href="<?php echo JRoute::_('index.php?option=com_sfs&task=currency.edit&id=' . (int)$item->id); ?>">
						<?php echo $this->escape($item->name); ?></a>		
				</td>
				<td align="center"><?php echo $item->numeric_code; ?></td>
				<td align="center"><?php echo $item->code; ?></td>
				<td align="center"><?php echo $item->exchange_rate; ?></td>
				<td align="center"><?php echo $item->symbol; ?></td>				
			</tr>
		<?php endforeach; ?>
		</tbody>
		
		<!-- Footer -->
		<tfoot>
			<tr>
				<td colspan="6"><?php echo $this->pagination->getListFooter(); ?></td>
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
