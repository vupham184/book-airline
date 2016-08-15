<?php 
// no direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.modal');
JHTML::_('script','system/multiselect.js',false,true);
$user		= JFactory::getUser();
$userId		= $user->get('id');
$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
$saveOrder	= $listOrder == 'a.name';
?>
<form action="<?php echo JRoute::_('index.php?option=com_sfs&view=trainlists');?>" method="post" name="adminForm" id="adminForm">
	<fieldset id="filter-bar">
		<div class="filter-search fltlft">
			<label class="filter-search-lbl" for="filter_search"><?php echo JText::_('JSEARCH_FILTER_LABEL'); ?></label>
			<input type="text" name="filter_search" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" />
			<button type="submit" class="btn"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
			<button type="button" onclick="document.id('filter_search').value='';this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>
		</div>
	</fieldset>
	<div class="clr"> </div>
	<table class="adminlist">
		<thead>
			<tr>
				<th width="4%"><input type="checkbox" name="checkall-toggle" value="" onclick="checkAll(this)" /></th>
				<th width="20%"><a>Iata Airportcode</a></th>
				<th width="15%"><a>Stationname</a></th>
				<th width="15%"></th>
				<th width="20%" class="nowrap"><a>Cityname</a></th>
				<th width="18%" class="nowrap"><a>Country</a></th>
				<th width="8%" class="nowrap"><a>Status</a></th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="7">
					<?php echo $this->pagination->getListFooter();?>
				</td>
			</tr>
		</tfoot>
		
		<tbody>
		<?php foreach ($this->items as $i => $item) :					
			?>
			<tr class="row<?php echo $i % 2; ?>">
				<td class="center">
					<?php echo JHtml::_('grid.id', $i, $item->id); ?>
				</td>
				<td>
					<?php echo $this->escape($item->iata_airportcode.' - '.$item->airlineName); ?>					
				</td>
				<td>								
					<?php echo $item->stationname; ?>					
				</td>
				<td>
					<a href="index.php?option=com_sfs&view=trainlist&layout=company&airline_trains_id=<?php echo $item->id?>&tmpl=component" style="text-decoration:underline" class="modal" rel="{handler: 'iframe', size: {x: 450, y: 350}, onClose: function() {}}">
						Edit Train
					</a>					
				</td>								
				<td>								
					<?php echo $item->cityname; ?>					
				</td>
				<td>								
					<?php echo $item->country; ?>					
				</td>											
				<td align="center">
					<?php if( (int) $item->status == 1  ) : ?>
						<a href="#" class="grid_true state publish"></a>
					<?php else : ?>	
						<a href="#" class="grid_false state unpublish"></a>
					<?php endif;?>
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	<div>
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>