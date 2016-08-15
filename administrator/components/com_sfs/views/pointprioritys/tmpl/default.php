<?php
defined('_JEXEC') or die;

JHtml::_('behavior.tooltip');
JHtml::_('behavior.modal');
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js">/*jquery*/</script>
<form action="<?php echo JRoute::_('index.php?option=com_sfs&view=pointprioritys');?>" method="post" name="adminForm" id="adminForm">

	<fieldset id="filter-bar">
		<div class="filter-search fltlft">
			<label class="filter-search-lbl" for="filter_search"><?php echo JText::_('JSEARCH_FILTER_LABEL'); ?></label>
			
			<input type="text" name="filter_search" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" />
			<select name="type_group" style="float:left">
				<option value="">All</option>
				<option <?php if(JRequest::getInt('type_group')==1) echo 'selected'; ?> value="1">fqtvStatus</option>
				<option <?php if(JRequest::getInt('type_group')==2) echo 'selected'; ?> value="2">IrregReason</option>
				<option <?php if(JRequest::getInt('type_group')==3) echo 'selected'; ?> value="3">SSR</option>
			</select>

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
					<a href="#">Name</a>
				</th>
				<th width="10%" style="text-align:left; text-indent:5px;">
					<a href="#">Type Group</a>
				</th>
				<th width="10%" style="text-align:left; text-indent:5px;">
					<a href="#">Point</a>
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
		<?php 
		$list_group = array();
		$list_group[1]= 'fqtvStatus';
		$list_group[2]= 'IrregReason';
		$list_group[3]= 'SSR';
		foreach ($this->items as $i => $item) : ?>
			<tr class="row<?php echo $i % 2; ?>">
				<td>
					<?php echo JHtml::_('grid.id', $i, $item->id); ?>
				</td>	
				<td>
					<a href="index.php?option=com_sfs&task=pointpriority.edit&id=<?php echo $item->id?>">
						<?php echo $item->name;?>
					</a>					
				</td>				
				<td>												
						<?php echo $list_group[$item->type_group];?>					
				</td>
				<td>												
						<?php echo $item->point;?>					
				</td>
			</tr>
		<?php endforeach; ?>
		</tbody>

	</table>
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />		
		<?php echo JHtml::_('form.token'); ?>
</form>
