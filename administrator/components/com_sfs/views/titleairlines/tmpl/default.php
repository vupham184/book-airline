<?php
// no direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.modal');
JHTML::_('script','system/multiselect.js',false,true);

$user		= JFactory::getUser();
$userId		= $user->get('id');
/*$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
$saveOrder	= $listOrder == 'a.name';*/

?>
<form action="<?php echo JRoute::_('index.php?option=com_sfs&view=titleairlines');?>" method="post" name="adminForm" id="adminForm">
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
				<th width="1%"><input type="checkbox" name="checkall-toggle" value="" onclick="checkAll(this)" /></th>
				<th width="35%"><a>Title</a></th>
				<th width="15%" class="nowrap"><a>Name Airline</a></th>
				<th width="15%" class="nowrap"><a>Option</a></th>
				<th width="10%" class="nowrap"><a>Status</a></th>
				<th width="15%" class="nowrap"><a>Edit</a></th>
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
		<?php foreach ($this->items as $i => $item) :					
			?>
			<tr class="row<?php echo $i % 2; ?>">
				<td class="center">
					<?php echo JHtml::_('grid.id', $i, $item->id); ?>
				</td>								
				<td>								
					<?php echo $this->escape($item->title); ?>					
				</td>
				<td>								
					<?php echo $this->escape($item->name); ?>					
				</td>
				<td>
					<?php echo $item->val_opt;?>
				</td>
				<td>								
					<?php echo $this->escape($item->status ? 'Enable': 'Disable'); ?>					
				</td>								
				<td>
				<a href="index.php?option=com_sfs&view=titleairlines&layout=airline_title&title_id=<?php echo $item->id?>&tmpl=component" style="text-decoration:underline" class="modal" rel="{handler: 'iframe', size: {x: 550, y: 350}, onClose: function() {}}">
						Edit Title Airline
					</a>
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
