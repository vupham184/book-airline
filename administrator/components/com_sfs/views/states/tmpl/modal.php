<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

//Init tooltip
JHtml::_('behavior.tooltip');

//Get some variables
$function	= JRequest::getCmd('function', 'jSelectState');
$listOrder	= $this->state->get('list.ordering');
$listDirn	= $this->state->get('list.direction');
$ordering	= $listOrder == 'ordering';
?>
<form action="<?php echo JRoute::_('index.php?option=com_sfs&view=states&tmpl=component&layout=modal'); ?>" method="post" name="adminForm">

	<fieldset id="filter-bar">
		<div class="filter-search fltlft">
			<label class="filter-search-lbl" for="filter_search"><?php echo JText::_('JSEARCH_FILTER_LABEL'); ?></label>
			<input type="text" name="filter_search" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('COM_SEARCH_IN_TITLE'); ?>" />
			<button type="submit"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
			<button type="button" onclick="document.id('filter_search').value='';this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>
		</div>
		<div class="filter-select fltrt">
			<select name="filter_country_id" class="inputbox" onchange="this.form.submit()">
				<option value=""><?php echo JText::_('COM_SFS_SELECT_COUNTRY');?></option>
				<?php echo JHtml::_('select.options', JFormFieldCountrySelectList::getOptions(), 'value', 'text', $this->state->get('filter.country_id'));?>
			</select>
			<select name="filter_state" class="inputbox" onchange="this.form.submit()">
				<option value=""><?php echo JText::_('JOPTION_SELECT_PUBLISHED');?></option>
				<?php echo JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), 'value', 'text', $this->state->get('filter.state'), true);?>
			</select>
			<select name="filter_language" class="inputbox" onchange="this.form.submit()">
				<option value=""><?php echo JText::_('JOPTION_SELECT_LANGUAGE');?></option>
				<?php echo JHtml::_('select.options', JHtml::_('contentlanguage.existing', true, true), 'value', 'text', $this->state->get('filter.language'));?>
			</select>
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
				<th>
					<?php echo JHtml::_('grid.sort', 'COM_SFS_STATE_FIELD_NAME_LABEL', 'a.name', $listDirn, $listOrder); ?>
				</th>
				<th>
					<?php echo JHtml::_('grid.sort', 'COM_SFS_STATE_COUNTRY_NAME', 'country_name', $listDirn, $listOrder); ?>
				</th>
				<th width="8%">
					<?php echo JHtml::_('grid.sort', 'JFIELD_ALIAS_LABEL', 'a.alias', $listDirn, $listOrder); ?>
				</th>
				<th width="5%">
					<?php echo JHtml::_('grid.sort', 'JPUBLISHED', 'state', $listDirn, $listOrder); ?>
				</th>
			</tr>
		</thead>
		
		<!-- Body -->
		<tbody>
		<?php foreach($this->items as $i => $item): 
			$country_link 	  = JRoute::_('index.php?option=com_sfs&view=country&layout=edit&id='.$item->country_id);
		?>
			<tr class="row<?php echo $i % 2; ?>">
				<td align="center">
					<?php echo $item->id; ?>
				</td>
				<td>
					<a class="pointer" onclick="if (window.parent) window.parent.<?php echo $function;?>('<?php echo $item->id; ?>', '<?php echo $item->country_id; ?>', '<?php echo $this->escape(addslashes($item->name.', '.$item->country_name)); ?>');">
						<?php echo $this->escape($item->name); ?>
					</a>
				</td>
				<td>
					<?php echo $this->escape($item->alias); ?>
				</td>
				<td>
					<?php echo $this->escape($item->country_name); ?>
				</td>
				<td class="center">
					<?php echo JHtml::_('jgrid.published', $item->state, $i, 'states.', 0);?>
				</td>
			</tr>
		<?php endforeach; ?>
		</tbody>
		
		<!-- Footer -->
		<tfoot>
			<tr>
				<td colspan="7"><?php echo $this->pagination->getListFooter(); ?></td>
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