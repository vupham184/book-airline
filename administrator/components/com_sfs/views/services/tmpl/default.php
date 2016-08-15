<?php
defined('_JEXEC') or die;

JHtml::_('behavior.tooltip');
JHtml::_('behavior.modal');
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js">/*jquery*/</script>
<form action="<?php echo JRoute::_('index.php?option=com_sfs&view=services');?>" method="post" name="adminForm" id="adminForm">

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
				<th width="1%">
					<input type="checkbox" name="checkall-toggle" value="" onclick="checkAll(this)" />
				</th>				
				<th width="10%" style="text-align:left; text-indent:5px;">
					<a href="#">Name Service</a>
				</th>
				<th width="10%" style="text-align:left; text-indent:5px;">
					<a href="#">Create date</a>
				</th>
				<th width="10%" style="text-align:left; text-indent:5px;">
					<a href="#">Status</a>
				</th>
				<th width="10%" style="text-align:left; text-indent:5px;">
					<a href="#">Order</a>
				</th>
				<th width="10%" style="text-align:left; text-indent:5px;">
					<a href="#">Parent ID</a>
				</th>					
			</tr>

		</thead>
		<tfoot>
			<tr>
				<td colspan="13">
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
		<tbody>
		<?php foreach ($this->items as $i => $item) : ?>
			<tr class="row<?php echo $i % 2; ?>">
				<td>
					<?php echo JHtml::_('grid.id', $i, $item->id); ?>
				</td>	
				<td>
					<a href="index.php?option=com_sfs&task=service.edit&id=<?php echo $item->id?>">
						<?php echo $item->name_service;?>
					</a>					
				</td>				
				<td>												
						<?php echo $item->create_date;?>					
				</td>
				<td>	
					<?php if(!$item->status):?>
					<span class="state unpublish"><span class="text">Unpublish</span></span>
					<?php else:?>
					<span class="state publish"><span class="text">Publish</span></span>
					<?php endif;?>										
										
				</td>			
				<td>	
					<?php echo $item->order_by;?>
				</td>
				<td>
					<?php echo ($item->parent_id > 0) ? $item->parent_id : ''; ?>
				</td>		
			</tr>
		<?php endforeach; ?>
		</tbody>

	</table>
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />		
		<?php echo JHtml::_('form.token'); ?>
</form>
