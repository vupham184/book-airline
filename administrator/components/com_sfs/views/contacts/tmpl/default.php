<?php
defined('_JEXEC') or die();

JHtml::_('behavior.tooltip');
JHtml::_('behavior.modal');

//Get some variables
$user		= JFactory::getUser();
$userId		= $user->get('id');
$listOrder	= $this->state->get('list.ordering');
$listDirn	= $this->state->get('list.direction');
$canOrder	= $user->authorise('core.edit.state', 'com_sfs.contact');
$ordering	= $listOrder == 'ordering';

?>
<form action="<?php echo JRoute::_('index.php?option=com_sfs&view=contacts'); ?>" method="post" name="adminForm">
	<fieldset id="filter-bar">
		<div class="filter-search fltlft">
			<label class="filter-search-lbl" for="filter_search"><?php echo JText::_('JSEARCH_FILTER_LABEL'); ?></label>
			<input type="text" name="filter_search" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('COM_SEARCH_IN_TITLE'); ?>" />
			<button type="submit"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
			<button type="button" onclick="document.id('filter_search').value='';this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>
		</div>
		
		<div class="filter-select fltrt">
			<?php
			$filter_airline_id = $this->state->get('filter.airline_id',0); 
			?>
			<select name="filter_airline_id" class="inputbox" onchange="this.form.submit()">
				<option value="">Select Airline</option>
				<?php foreach ($this->airlines as $airline) : ?>
					<option value="<?php echo $airline->id?>"<?php echo (int)$filter_airline_id == (int)$airline->id ? ' selected="selected"':''?>>
						<?php echo (!empty($airline->name)) ? $airline->name : $airline->company_name;?>
					</option>
				<?php endforeach;?>				
			</select>			
			<?php
			$filter_hotel_id = $this->state->get('filter.hotel_id',0); 
			?>
			<select name="filter_hotel_id" class="inputbox" onchange="this.form.submit()">
				<option value="">Select Hotel</option>
				<?php foreach ($this->hotels as $hotel) : ?>
					<option value="<?php echo $hotel->id?>"<?php echo (int)$filter_hotel_id == (int)$hotel->id ? ' selected="selected"':''?>><?php echo $hotel->name;?></option>
				<?php endforeach;?>				
			</select>
		</div>
		
	</fieldset>
	<div class="clr"> </div>
	
	<table class="adminlist">
		<!-- Header -->
		<thead>
			<tr>				
				<th width="10">
					<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->items); ?>);" />
				</th>										
				<th>
					<?php echo JHtml::_('grid.sort', 'Name', 'a.name', $listDirn, $listOrder); ?>
				</th>
				<th width="30%">
					<?php echo JHtml::_('grid.sort', 'Username', 'a.username', $listDirn, $listOrder); ?>
				</th>
				<th width="15%">
					<?php echo JHtml::_('grid.sort', 'Email', 'u.email', $listDirn, $listOrder); ?>
				</th>
				<th width="8%">
					<?php echo JHtml::_('grid.sort', 'Contact Type', 'a.grouptype', $listDirn, $listOrder); ?>
				</th>											               
				<th width="10%">
					<?php echo JHtml::_('grid.sort', 'Job Title', 'a.job_title', $listDirn, $listOrder); ?>
				</th>				
				<th width="10%">
					<?php echo JHtml::_('grid.sort', 'Telephone', 'a.telephone', $listDirn, $listOrder); ?>
				</th>													
			</tr>
		</thead>
		
		<!-- Body -->
		<tbody>
		<?php foreach($this->items as $i => $item): 						
		?>
			<tr class="row<?php echo $i % 2; ?>">				
				<td align="center">
					<?php echo JHtml::_('grid.id', $i, $item->id); ?>
				</td>			
				<td>					
					<a href="<?php echo JRoute::_('index.php?option=com_sfs&task=contact.edit&id='.(int)$item->id); ?>">
						<?php echo $item->name.' '.$item->surname; ?>
					</a>					
				</td> 
				<td>										
					<?php echo $item->username; ?><br />	
					<a href="index.php?option=com_sfs&view=contact&layout=edit&id=<?php echo (int)$item->id?>&tmpl=component" class="modal" rel="{handler: 'iframe', size: {x: 850, y: 550}, onClose: function() {}}">
						<?php if($item->secret_key) : ?>
							<?php echo $item->secret_key; ?>
						<?php else :?>					
							add secret key
						<?php endif;?>
					</a>									
				</td>  
				<td>					
					<a href="mailto:<?php echo $item->email ?>">
						<?php echo $item->email ?>
					</a>					
				</td>         
				<td align="left">
					<?php					
					if($item->grouptype==1) {
						echo 'Hotel';
					} else if($item->grouptype==2) {
						echo 'Airline';
					} else if($item->grouptype==3) {
						echo 'Ground';
					} 
					?>
				</td>      
				<td align="center"><?php echo $item->job_title; ?></td>
				<td align="center"><?php echo $item->telephone ?></td>				
			</tr>
		<?php endforeach; ?>
		</tbody>
		
		<!-- Footer -->
		<tfoot>
			<tr>
				<td colspan="9"><?php echo $this->pagination->getListFooter(); ?></td>
			</tr>
		</tfoot>
		
	</table>
		
	
	<input type="hidden" name="task" value="" />
    <input type="hidden" name="boxchecked" value="0" />		
	<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
	<?php echo JHtml::_('form.token'); ?>
	
</form>
