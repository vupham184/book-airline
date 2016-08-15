<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

//Init tooltip
JHtml::_('behavior.tooltip');
JHtml::_('behavior.modal');

//Get some variables
$user		= JFactory::getUser();
$userId		= $user->get('id');
$listOrder	= $this->state->get('list.ordering');
$listDirn	= $this->state->get('list.direction');
$canOrder	= $user->authorise('core.edit.state', 'com_sfs.country');
$ordering	= $listOrder == 'ordering';

//lchung
$options = array();
$db = JFactory::getDbo();			
$db->setQuery('SELECT code AS value, name AS text FROM #__sfs_iatacodes WHERE type=2 ORDER BY code ASC');
$options = $db->loadObjectList();
foreach ($options as & $opt)
{
	$opt->text = $opt->value . ' - ' . $opt->text;
}
//End lchung
//minhtran
if(intval($this->state->get('filter.country'))){
	$options_state = array();
	$db->setQuery('SELECT city AS value, city AS text FROM #__sfs_hotel where country_id="'.intval($this->state->get('filter.country')).'" GROUP BY city ORDER BY city ASC');
	$options_state = $db->loadObjectList();
	foreach ($options_state as & $opt)
	{
		$opt->text = $opt->text;
	}
}

$options_airport = array();
$db = JFactory::getDbo();			
$db->setQuery('SELECT id AS value,code, name AS text FROM #__sfs_iatacodes WHERE type=2 ORDER BY code ASC');
$options_airport = $db->loadObjectList();
foreach ($options_airport as & $opt)
{
	$opt->text = $opt->code . ' - ' . $opt->text;
}
//minhtran
?>

<script type="text/javascript">
	jQuery(function($) {
		jQuery('.btn-assign-to-airport').click(function(){			
			document.getElementById("task").value='hotels.assigntoairport';
			this.form.submit();
		});
	});

</script>
<style type="text/css">
	.assign-to-airport{
		float: right;
	    position: absolute;
	    z-index: 100;
	    right: 10px;
	    top: -35px;
	}
	#ap-mainbody{
		position: relative;
	}
</style>
<form action="<?php echo JRoute::_('index.php?option=com_sfs&view=hotels'); ?>" method="post" name="adminForm">
	<div class="assign-to-airport">
		<button type="button" class="btn-assign-to-airport">ASSIGN TO AIRPORT</button>
		<select style="width:100px;" id="code_assign_to_airport" name="code_assign_to_airport" class="inputbox">
				<option value=""><?php echo JText::_('Select Airport code');?></option>
				<?php echo JHtml::_('select.options', $options_airport, 'value', 'text', $this->state->get('filter.code'));?>
		</select>
	</div>
	<div style="clear:both"></div>
	<fieldset id="filter-bar">
		<div class="filter-search fltlft">
			<label class="filter-search-lbl" for="filter_search"><?php echo JText::_('JSEARCH_FILTER_LABEL'); ?></label>
			<input type="text" name="filter_search" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('COM_SEARCH_IN_TITLE'); ?>" />
			<button type="submit"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
			<button type="button" onclick="document.id('filter_search').value='';this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>
		</div>
		<div class="filter-select fltrt">
        	<?php
			$filter_ws_room = $this->state->get('filter.ws_room',""); 
			if ( $filter_ws_room == '' ){
				$filter_ws_room = 'Partner';
			}
			?>
            <select name="filter_ws_room" class="inputbox" onchange="this.form.submit()">
				<option value="all" <?php echo ( $filter_ws_room == 'all' ) ? 'selected="selected"' : "";?>>All Hotel</option>
				<option value="Partner" <?php echo ( $filter_ws_room == 'Partner' ) ? 'selected="selected"' : "";?>>Partner hotel</option>	
                <option value="WS" <?php echo ( $filter_ws_room == 'WS' ) ? 'selected="selected"' : "";?>>WS hotel</option>
			</select>
            
			
            
			<select name="filter_state" class="inputbox" onchange="this.form.submit()">
				<option value=""><?php echo JText::_('JOPTION_SELECT_PUBLISHED');?></option>
                <option <?php if($this->state->get('filter.state') == -4 ):?> selected="selected"<?php endif;?> value="-4"><?php echo JText::_('Invited');?></option>
				<?php echo JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), 'value', 'text', $this->state->get('filter.state'), true);?>
                
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
					<?php echo JHtml::_('grid.sort', 'Hotel Name', 'a.name', $listDirn, $listOrder); ?>
				</th>
				<th width="5%" class="nowrap">
				<select style="width:100px;" name="filter_ring" class="inputbox" onchange="this.form.submit()">
				<option value=""><?php echo JText::_('Select Ring');?></option>
				<?php echo JHtml::_('select.options', SfsHelper::getRingOptions(), 'value', 'text', $this->state->get('filter.ring'));?>
				</select>
				Ring
				</th>
				<th width="5%" class="nowrap">Invoice</th>
				<th width="20%" class="nowrap">Loaded room today <?php echo $this->totalLoadedRooms;?></th>				
				<th width="15%">Address</th>
				<th width="8%">
					<select style="width:100px;" name="filter_country" class="inputbox" onchange="this.form.submit()">
						<option value="">Select Country</option>
						<?php echo JHtml::_('select.options', SfsHelper::getCountryOptions(), 'value', 'text', $this->state->get('filter.country'));?>
					</select>
				Country
				</th>
				<th width="8%">
				<select name="filter_city" class="inputbox" onchange="this.form.submit()">
					<option value="">Select city</option>
					<?php echo JHtml::_('select.options', $options_state, 'value', 'text', $this->state->get('filter.city'));?>
				</select>	
				City
				</th>
				
				<th width="8%">Phone</th>
				<th width="8%">Admin</th>
				<th width="8%">Created Date</th>		
				<th width="5%">Enable</th>		
				<th width="5%">WS</th>
                <th width="5%">
                <select style="width:100px;" name="filter_code" class="inputbox" onchange="this.form.submit()">
				<option value=""><?php echo JText::_('Select Airport code');?></option>
				<?php echo JHtml::_('select.options', $options, 'value', 'text', $this->state->get('filter.code'));?>
				</select>
                Airportcode
                </th>
				<th width="12">
					<?php echo JHtml::_('grid.sort', 'COM_SFS_HEADING_ID', 'a.id', $listDirn, $listOrder); ?>
				</th>				
			</tr>
		</thead>
		
		<!-- Body -->
		<tbody>
		<?php foreach($this->items as $i => $item): //print_r( $item );die;
			$next       = isset($this->items[$i+1]->ordering) ? true : false;
			$prev       = isset($this->items[$i-1]->ordering) ? true : false;
			
			$canEdit	= $user->authorise('core.edit',			'com_sfs.hotel.'.$item->id);
			$canCheckin	= $user->authorise('core.manage',		'com_checkin') || $item->checked_out == $userId || $item->checked_out == 0;
			$canChange	= $user->authorise('core.edit.state',	'com_sfs.hotel.'.$item->id) && $canCheckin;
			$canEditOwn	= $user->authorise('core.edit.own',		'com_sfs.hotel.'.$item->id) && $item->created_by == $userId;
		?>
			<tr class="row<?php echo $i % 2; ?>">				
				<td align="center">
					<?php echo JHtml::_('grid.id', $i, $item->id); ?>
				</td>
				<td>	
				<?php if(!empty($item->ws_id)) : ?>	

					<a style="float:left;" href="<?php echo JRoute::_('index.php?option=com_sfs&task=hotel.editws&id=' . (int)$item->id); ?>">
						<?php echo $this->escape($item->name); ?>
					</a>
					<?php
					for ($j=0; $j < (int)$item->star; $j++ ) {
						echo '<img style="float:left;" src="templates/bluestork/images/menu/icon-16-default.png" />';
					} 
					?>	
				<?php else : ?>
					<a style="float:left;" href="<?php echo JRoute::_('index.php?option=com_sfs&task=hotel.edit&id=' . (int)$item->id); ?>">
						<?php echo $this->escape($item->name); ?>
					</a>
					<?php
					for ($j=0; $j < (int)$item->star; $j++ ) {
						echo '<img style="float:left;" src="templates/bluestork/images/menu/icon-16-default.png" />';
					} 
					?>
				<?php endif; ?>					
				</td>
				<td class="center">
					<?php echo $item->ring; ?>
				</td>
				<td class="center">
					<a style="color:green;" href="<?php echo JRoute::_('index.php?option=com_sfs&view=invoice&hotel_id='.$item->id);?>">
						Invoice
					</a>
				</td>
				<td>					
					<?php if($item->total_loaded_room || $item->total_booked_room):?>
						<strong>Yes total <?php echo $item->total_loaded_room+$item->total_booked_room;?></strong>		
						<?php
						if( $item->sender ) : 							
						?>
						<br />
						<span style=""><i><?php echo $item->sender;?></i></span>
						
						<?php endif;?>							
					<?php else : ?>
					No <a rel="{handler: 'iframe', size: {x: 750, y: 650}, onClose: function() {}}" href="index.php?option=com_sfs&layout=edit&view=hotel&tmpl=component&id=<?php echo $item->id?>" class="modal">
						Send
					</a>
					<?php
						if( $item->sender ) : 							
						?>
						<br />
						<span style=""><i><?php echo $item->sender;?></i></span>
						
						<?php endif;?>
					<?php endif;?>
				</td>
				<td>
					<?php echo $item->address; ?>
				</td>
				<td>
					<?php echo $item->country;?>
				</td>
				<td>
					<?php echo $item->city; ?>
				</td>
				
				<td>
					<?php echo $item->telephone;?>
				</td>				
				<td align="center">					
                    <a href="<?php echo JRoute::_('index.php?option=com_users&task=user.edit&id='. (int)$item->user_id); ?>">                    
                    	<?php echo $item->fullname;?>
                    </a>
                </td>                
				<td>
					<?php echo JHTML::_('date',$item->created_date, JText::_('DATE_FORMAT_LC4')); ?>
				</td>      
				<td align="center">
					<?php if( (int) $item->block == 1  ) : ?>
						<a href="#" class="grid_false state unpublish"></a>
					<?php else : ?>	
						<a href="#" class="grid_true state publish"></a>
					<?php endif;?>
				</td>  
				<td align="center">
					<?php if(!empty($item->ws_type)) : ?>
						<?php echo $item->ws_type; ?><br/><b><?php echo $item->ws_id?></b>
					<?php else: ?>
                    	<?php $airlineSent = SfsHelper::getAirlineSendNotification($item->id);
						if(count($airlineSent) > 0 ) :?>
                        <i style="color: #000; font-weight:bold;">INVITED</i>
						<?php else:?>
						<i style="color: #777">None</i>
                        <?php endif;?>
					<?php endif;?>
				</td> 
                <td align="center">
					<?php echo $item->code;?>
				</td>      
				<td align="center">
					<?php echo $item->id; ?>
				</td>     								
			</tr>
		<?php endforeach; ?>
		</tbody>
		
		<!-- Footer -->
		<tfoot>
			<tr>
				<td colspan="15"><?php echo $this->pagination->getListFooter(); ?></td>
			</tr>
		</tfoot>
		
	</table>
	
	
	<div>
		<input type="hidden" id="task" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />				
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>
