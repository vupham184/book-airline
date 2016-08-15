<?php
// no direct access
defined('_JEXEC') or die;

JHtml::_('behavior.tooltip');

$saveOrder	= $listOrder == 'a.name';
?>
<form action="<?php echo JRoute::_('index.php?option=com_sfs&view=iatacodes');?>" method="post" name="adminForm" id="adminForm">
	<fieldset id="filter-bar">
		<div class="filter-search fltlft">
			<label class="filter-search-lbl" for="filter_search"><?php echo JText::_('JSEARCH_FILTER_LABEL'); ?></label>
			<input type="text" name="filter_search" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('COM_CONTENT_FILTER_SEARCH_DESC'); ?>" />

			<button type="submit" class="btn"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
			<button type="button" onclick="document.id('filter_search').value='';this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>
		</div>
		<div class="filter-select fltrt">
		
			<select name="filter_type" class="inputbox" onchange="this.form.submit()">									
				<option value="">Select Type</option>
				<option value="1"<?php if( (int) $this->state->get('filter.type') == 1 ) echo ' selected="selected"';?>>Airline</option>
				<option value="2"<?php if( (int) $this->state->get('filter.type') == 2 ) echo ' selected="selected"';?>>Airport</option>		
				<option value="3"<?php if( (int) $this->state->get('filter.type') == 3 ) echo ' selected="selected"';?>>Terminal</option>
			</select>
			<select name="filter_published" class="inputbox" onchange="this.form.submit()">
				<option value=""><?php echo JText::_('JOPTION_SELECT_PUBLISHED');?></option>
				<?php echo JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), 'value', 'text', $this->state->get('filter.published'), true);?>
			</select>

		</div>
	</fieldset>
	<div class="clr"> </div>

	<table class="adminlist">
		<thead>
			<tr>
				<th width="1%">
					<input type="checkbox" name="checkall-toggle" value="" onclick="checkAll(this)" />
				</th>
				<th>
					<?php echo JHtml::_('grid.sort', 'JGLOBAL_TITLE', 'a.title', $listDirn, $listOrder); ?>
				</th>
				<th width="5%">
					<?php echo JHtml::_('grid.sort', 'Code', 'a.code', $listDirn, $listOrder); ?>
				</th>
				<th width="5%">
					<?php echo JHtml::_('grid.sort', 'Country', 'country', $listDirn, $listOrder); ?>
				</th>
				<th width="5%">
					<?php echo JHtml::_('grid.sort', 'IATA Type', 'a.type', $listDirn, $listOrder); ?>
				</th>
				<th width="5%">
					<?php echo JHtml::_('grid.sort', 'JPUBLISHED', 'a.state', $listDirn, $listOrder); ?>
				</th>								
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="15">
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
		<tbody>
		<?php foreach ($this->items as $i => $item) :
			$item->max_ordering = 0; //??
			$ordering	= ($listOrder == 'a.name');
			?>
			<tr class="row<?php echo $i % 2; ?>">
				<td class="center">
					<?php echo JHtml::_('grid.id', $i, $item->id); ?>
				</td>
				<td>
					<a href="<?php echo JRoute::_('index.php?option=com_sfs&task=iatacode.edit&id='.$item->id);?>">
						<?php echo $this->escape($item->name); ?>
					</a>
				</td>
				<td class="center">
					<?php echo $item->code; ?>
				</td>
				<td class="center">
					<?php echo $item->country; ?>
				</td>
				<td class="center">
					<?php 
						$item->type = intval($item->type);
						if($item->type == 1) {
							echo 'Airline';
						} elseif ($item->type == 2) {
							echo 'Airport';
						} elseif ($item->type == 3) {
							echo 'Terminal';
						}											
					?>
				</td>				
				<td class="center">
					<?php echo JHtml::_('jgrid.published', $item->state, $i, 'iatacodes.', 1, 'cb'); ?>
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
