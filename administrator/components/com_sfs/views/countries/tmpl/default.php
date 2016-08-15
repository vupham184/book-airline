<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

//Init tooltip
JHtml::_('behavior.tooltip');

//Get some variables
$user		= JFactory::getUser();
$userId		= $user->get('id');
$listOrder	= $this->state->get('list.ordering');
$listDirn	= $this->state->get('list.direction');

$ordering	= $listOrder == 'ordering';
?>
<form action="<?php echo JRoute::_('index.php?option=com_sfs&view=countries'); ?>" method="post" name="adminForm">

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
					<?php echo JHtml::_('grid.sort', 'COM_SFS_COUNTRY_HEADING_ID', 'a.id', $listDirn, $listOrder); ?>
				</th>
				<th width="10">
					<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->items); ?>);" />
				</th>			
				<th>
					<?php echo JHtml::_('grid.sort', 'COM_SFS_COUNTRY_HEADING_NAME', 'a.name', $listDirn, $listOrder); ?>
				</th>
				<th width="15%">
					<?php echo JHtml::_('grid.sort', 'COM_SFS_COUNTRY_NUMERIC', 'a.country_numeric', $listDirn, $listOrder); ?>
				</th>
				<th width="8%">
					<?php echo JHtml::_('grid.sort', 'COM_SFS_FIELD_CURRENCY_DEFAULT_CURRENCY_LABEL', 'a.default_currency', $listDirn, $listOrder); ?>
				</th>
				<th width="8%">
					<?php echo JHtml::_('grid.sort', 'Code', 'a.code', $listDirn, $listOrder); ?>
				</th>
				<th width="8%">
					<?php echo JHtml::_('grid.sort', 'Code 3', 'a.code_3', $listDirn, $listOrder); ?>
				</th>	
				<th width="8%">
					<?php echo JHtml::_('grid.sort', 'Flag', 'a.flag', $listDirn, $listOrder); ?>
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
					<a href="<?php echo JRoute::_('index.php?option=com_sfs&task=country.edit&id=' . (int)$item->id); ?>">
						<?php echo $this->escape($item->name); ?>
					</a>
				</td>
				<td align="center"><?php echo $item->country_numeric; ?></td>
				<td align="center"><?php echo $item->default_currency; ?></td>
				<td align="center"><?php echo $item->code; ?></td>
				<td align="center"><?php echo $item->code_3; ?></td>
				<td align="center">
					<?php 
					if($item->flag){
						echo '<img src="'.JURI::root().$item->flag.'" alt="'.$item->name.'" />';	
					}					 
					?>
				</td>												
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
