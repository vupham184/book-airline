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
$options = array();
$db = JFactory::getDbo();			
$db->setQuery('SELECT code AS value, name AS text FROM #__sfs_iatacodes WHERE type=2 ORDER BY code ASC');
$options = $db->loadObjectList();
foreach ($options as & $opt)
{
	$opt->text = $opt->value . ' - ' . $opt->text;
}
?>
<form action="<?php echo JRoute::_('index.php?option=com_sfs&view=taxilist');?>" method="post" name="adminForm" id="adminForm">
	<fieldset id="filter-bar">
		<div class="filter-search fltlft">
			<label class="filter-search-lbl" for="filter_search"><?php echo JText::_('JSEARCH_FILTER_LABEL'); ?></label>
			<input type="text" name="filter_search" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" />
			<button type="submit" class="btn"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
			<button type="button" onclick="document.id('filter_search').value='';this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>
		</div>
		<div class="filter-select fltrt">
			<select name="filter_code" class="inputbox" onchange="this.form.submit()">
				<option value=""><?php echo JText::_('Select Airport');?></option>
				<?php echo JHtml::_('select.options', $options, 'value', 'text', $this->state->get('filter.code'));?>
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
				<th width="1%"><input type="checkbox" name="checkall-toggle" value="" onclick="checkAll(this)" /></th>
				<th width="20%"><a>Transport company 1 rate agreement</a></th>
				<th width="5%"><a>Airport</a></th>
				<th width="70"><a>Account Type</a></th>
				<th width="145"></th>
				<th width="145"></th>
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
					<?php echo $this->escape($item->name); ?>					
				</td>
				<td>								
					<?php echo $this->escape($item->airport_code); ?>					
				</td>
				<td>
					<?php
					if( $item->profile_type == 'taxi' ) {
						echo 'Taxi';
					} 
					elseif ($item->profile_type == 'taxiAirport') {
						echo "taxiAirport";
					}
					else {
						echo 'Airline';
						//echo !empty($item->airline_name) ? $item->airline_name : $item->company_name;	
					}					 
					?>
				</td>				
				<td>
					<?php
					if( $item->profile_type == 'taxi' ) : ?>
					<a href="index.php?option=com_sfs&view=taxi&layout=edittaxi&taxi_id=<?php echo $item->id?>" style="text-decoration:underline">
						edit company details
					</a>
				
				<?php else :?>
					<a href="index.php?option=com_sfs&view=taxi&layout=company&airline_id=<?php echo $item->airline_id?>&airport_id=<?php echo $item->airport_id?>&taxi_id=<?php echo $item->id?>&tmpl=component" style="text-decoration:underline" class="modal" rel="{handler: 'iframe', size: {x: 750, y: 550}, onClose: function() {}}">
						edit company details
					</a>
				<?php endif;?>
				
			</td>
			
			<td>
				<a href="index.php?option=com_sfs&view=taxi&layout=rate&airline_id=<?php echo $item->airline_id?>&airport_id=<?php echo $item->airport_id?>&taxi_id=<?php echo $item->id?>&tmpl=component" style="text-decoration:underline" class="modal" rel="{handler: 'iframe', size: {x: 900, y: 650}, onClose: function() {}}">
					edit rate agreement
				</a>
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
