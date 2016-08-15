<?php
defined('_JEXEC') or die;

JHtml::_('behavior.tooltip');
JHtml::_('behavior.modal');

function get_hotel( $association_id, $hotel_id )
{
		if( (int) $reservation->association_id > 0 )
		{
			$association = SFactory::getAssociation($reservation->association_id);
			$db = $association->db;	
		} else {
			
			$db = JFactory::getDbo();	
			
		}
		$query = $db->getQuery(true);
		
		$query->select('a.*, b.name AS country');
		$query->from('#__sfs_hotel AS a');
		
		$query->leftJoin('#__sfs_country AS b ON b.id=a.country_id');
		
		$query->leftJoin('#__sfs_hotel_taxes AS t ON t.hotel_id=a.id');
		
		$query->select('d.code AS currency');
		$query->leftJoin('#__sfs_currency AS d ON d.id=t.currency_id');
		
		$query->select('e.name AS billing_name,e.tva_number');
		$query->leftJoin('#__sfs_billing_details AS e ON e.id=a.billing_id');
		
		$query->where('a.id='.$hotel_id);
		
		$db->setQuery($query);
		
		$hotel = $db->loadObject();
		
		if( !$hotel ) {			
			//JError::raiseError(400, 'Hotel was not found');
			//return false;
		}
	return $hotel;
}

?>
<div class="width-100 fltlft">

<form action="<?php echo JRoute::_('index.php?option=com_sfs&view=makereport'); ?>" method="post" name="adminForm" id="item-form" class="form-validate">
<?php if (count($this->items) ) : ?>
<table class="tableinvoice" width="100%" border="0" cellspacing="0" cellpadding="0">
<tr valign="bottom" class="header">
	<td style="padding-bottom:10px;" class="tdblue">Airportcode</td>
	<td class="tdblue">Blockcode</td>
	<td class="tdblue">Date</td>
	<td class="tdblue">Airline</td>
	<td class="tdblue">Hotel</td>
	<td class="tdblue">WS or Partner</td>
	<td class="tdblue">Initial Rooms</td>	
	<td class="tdgreen">Claimed Rooms</td>	
	<td class="tdgreen">Total estomated charges</td>
    <td class="tdgreen">Total nett charges WS</td>
    <?php foreach ($this->items['result'] as $i => $item) : ?>
<tr>
	<td><?php echo $item->airport_code;?></td>
	<td><?php echo $item->blockcode;?></td>
	<td>
    <?php echo sfsHelper::getATDate($item->booked_date,JText::_('DATE_FORMAT_LC2')); ?>
    </td>
	<td>
		<?php echo $item->airline_name;?>
	</td>
	<td>
		<?php if($item->association_id==0):?>
        <!--<a href="index.php?option=com_sfs&task=hotel.edit&id=<?php echo $item->hotel_id;?>">-->
            <?php echo $item->hotel_name;?> 
        <!--</a>-->				    
        <?php endif;?>
    </td>
	<td>	
		<?php if($item->ws_room > 0){?>
            WS hotel
        <?php }else{?>
            Partner hotel
        <?php }?>
	</td>
	<td style="text-align:right">
		<?php echo (int)$item->sd_room+(int)$item->t_room+(int)$item->s_room+(int)$item->q_room;?>
	</td>
	<td style="text-align:right"><!--background:#8db4e2; -->
		<?php echo $item->claimed_rooms;?>	
	</td>	
	<td><?php
	
	$hotel = get_hotel( $item->association_id, $item->hotel_id );
	if ( $hotel ) {
		//echo $hotel->currency . ' ';
	}		
	echo $this->items['reservation']['k' . $item->id]['total_room_charge'];
	
		?>
	</td>
    <td style="text-align:right"><!--background:#8db4e2; -->
		<?php echo $this->items['reservation']['k' . $item->id]['total_nett_charges'];?>	
	</td>	
</tr>
<?php endforeach;?>
	
</tr>
</table>
<div style="margin-top: 15px;">
    <button onClick="Joomla.submitbutton('makereport.export')" type="button" style="margin-left:5px;background:green;color:white;padding:5px 20px;border:none; font-size:12px;">
        Export to Excel
    </button>
</div>
<?php endif;?>
	<div>
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="filter_search" value="<?php echo JRequest::getString('filter_search')?>" />	
        <input type="hidden" name="date_start" value="<?php echo JRequest::getString('date_start')?>" />
        <input type="hidden" name="date_end" value="<?php echo JRequest::getString('date_end')?>" />
        <input type="hidden" name="ws_room" value="<?php echo JRequest::getString('ws_room')?>" />
        <input type="hidden" name="airline_id" value="<?php echo JRequest::getInt('airline_id')?>" />
        <input type="hidden" name="hotel_id" value="<?php echo JRequest::getInt('hotel_id')?>" />
        <input type="hidden" name="block_status" value="<?php echo JRequest::getString('block_status')?>" />        		
		<?php echo JHtml::_('form.token'); ?>
	</div>

</form>

</div>

