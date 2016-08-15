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
<script type="text/javascript" src="<?php echo JURI::root().'media/media/js/jquery-1.10.2.js' ?>"></script>
<script type="text/javascript">
	function changeAirport(){		
		Joomla.submitform(document.getElementById('adminForm'));
	}	
</script>

<form action="<?php echo JRoute::_('index.php?option=com_sfs&view=grouptransportlist');?>" method="post" name="adminForm" id="adminForm">
	<fieldset id="filter-bar">
		<div class="filter-search fltlft">
			<label class="filter-search-lbl" for="filter_search"><?php echo JText::_('JSEARCH_FILTER_LABEL'); ?></label>
			<input type="text" name="filter_search" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" />
			<button type="submit" class="btn"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
			<button type="button" onclick="document.id('filter_search').value='';this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>
		</div>
		<div class="filter-select fltrt">
			<select name="filter_published" class="inputbox" onchange="this.form.submit()">
				<option value=""><?php echo JText::_('JOPTION_SELECT_PUBLISHED');?></option>
				<?php echo JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), 'value', 'text', $this->state->get('filter.published'), true);?>
			</select>
		</div>
		<div class="filter-select fltrt">
			<select id="filterAirport" name="airportFilter" style="width: 150px;" onchange="changeAirport()">
				<option>Filter Airport</option>
				<?php foreach ($this->airport as $value) : ?>
    				<option value="<?php echo $value->id; ?>"><?php echo $value->code ." - ". $value->name;?></option>
    			<?php endforeach; ?>
			</select>
			
		</div>
		
	</fieldset>
	<div class="clr"> </div>

	<table class="adminlist">
		<thead>
			<tr>
				<th width="1%"><input type="checkbox" name="checkall-toggle" value="" onclick="checkAll(this)" /></th>
				<th width="22%" class="nowrap"><a>Group Transport Company</a></th>
				<th width="18%" class="nowrap">&#160;</th>	
				<th width="10%" class="nowrap"><a>AirportCode</a></th>			
				<th width="10%" class="nowrap"><a>Phone number</a></th>
				<th width="10%" class="nowrap"><a>Fax number</a></th>
				<th width="8%" class="nowrap"><a>Send Booking Email</a></th>	
				<th width="8%" class="nowrap"><a>Send Booking Fax</a></th>
				<th width="8%" class="nowrap"><a>Send Booking Sms</a></th>									
				<th width="4%" class="nowrap"><a>Enable</a></th>				
				<th width="1%" class="nowrap"><a>ID</a></th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="8">
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
		<tbody>
		<div>
		<?php if($this->FilterAirport){$this->items = $this->FilterAirport;} ?>
		
		<?php foreach ($this->items as $i => $item) :
			$editCompanyLink = 'index.php?option=com_sfs&view=grouptransport&layout=edit&id='.$item->id;
			$addTypeLink = 'index.php?option=com_sfs&view=grouptransport&layout=types&id='.$item->id.'&tmpl=component';	
			$addTypeFixed = 'index.php?option=com_sfs&view=grouptransport&layout=types_fixed&id='.$item->id.'&tmpl=component';						
		?>
		<tr class="row<?php echo $i % 2; ?>">
			<td class="center">
				<?php echo JHtml::_('grid.id', $i, $item->id); ?>
			</td>								
			<td>
				<span style="float:left; width:30%;">
					<a href="<?php echo $editCompanyLink?>" class="text-underline"><?php echo $this->escape($item->name); ?></a>
				</span>	
				<span style="float:left; width:60%;">
					<a href="<?php echo $addTypeLink;?>" style="color:green" class="modal" rel="{handler: 'iframe', size: {x: 700, y: 450}}">Add Types Distance</a><br />
					<a href="<?php echo $addTypeFixed;?>" style="color:green" class="modal" rel="{handler: 'iframe', size: {x: 700, y: 450}}">Add Types Fixed</a>
				</span>
			</td>	
			<td>	
				<?php
				if(count($item->types)):
				foreach ($item->types as $type): 
					$editRateLink = 'index.php?option=com_sfs&view=grouptransport&layout=rates&id='.$item->id.'&type_id='.$type->id.'&tmpl=component';
					$editRateLinkFixed = 'index.php?option=com_sfs&view=grouptransport&layout=rates_fixed&id='.$item->id.'&type_id='.$type->id.'&tmpl=component';
				?>		

					<div>
						<?php echo $type->name?> 
						-
						<?php if(empty($type->rate) ) :  ?>
							<a href="<?php echo $editRateLinkFixed?>" style="color:green" class="modal" rel="{handler: 'iframe', size: {x: 850, y: 600}}">Edit Type / Rates (Fixed)</a>
						<?php else: ?>
							<a href="<?php echo $editRateLink?>" style="color:green" class="modal" rel="{handler: 'iframe', size: {x: 850, y: 600}}">Edit Type / Rates</a>
						<?php endif; ?>
					</div>				
				<?php
				endforeach;
				endif; 
				?>									
			</td>
			<td>
				<?php $strAirport = array(); if(count($item->airport)): ?>
					<?php
						foreach ($item->airport as $key => $value) {
							array_push($strAirport, $value->code);
						}
					?>						
				<?php endif; ?>	
				<?php echo implode(", ",$strAirport); ?>
			</td>										
			<td>								
				<?php echo $this->escape($item->telephone); ?>					
			</td>
			<td>								
				<?php echo $this->escape($item->fax); ?>					
			</td>	
			<td align="center">
				<?php if( (int) $item->sendMail == 1  ) : ?>
					<a href="#" class="grid_true state publish"></a>
				<?php else : ?>	
					<a href="#" class="grid_false state unpublish"></a>
				<?php endif;?>
			</td> 
			<td align="center">
				<?php if( (int) $item->sendFax == 1  ) : ?>
					<a href="#" class="grid_true state publish"></a>
				<?php else : ?>	
					<a href="#" class="grid_false state unpublish"></a>
				<?php endif;?>
			</td> 
			
			<td align="center">
				<?php if( (int) $item->sendSMS  == 1  ) : ?>
					<a href="#" class="grid_true state publish"></a>
				<?php else : ?>	
					<a href="#" class="grid_false state unpublish"></a>
				<?php endif;?>
			</td> 			
									
			<td align="center">
				<?php if( (int) $item->published == 1  ) : ?>
					<a href="#" class="grid_true state publish"></a>
				<?php else : ?>	
					<a href="#" class="grid_false state unpublish"></a>
				<?php endif;?>
			</td>				
			<td class="center">
				<?php echo (int) $item->id; ?>
			</td>
		</tr>
		<?php endforeach; ?>
		</div>
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

