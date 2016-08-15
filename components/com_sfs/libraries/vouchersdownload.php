<style>
table.report-detail-table .tableheader { padding: 5px 5px 5px 5px; background-color: #738a98; border-bottom: 1px solid #b4b4b4; border-left: 1px solid #666; font-weight: bold; }

tr.sectiontableentry1 td { padding: 5px 5px 5px 5px; background-color: #fafafa; border-bottom: 1px solid #666; border-left: 1px solid #666; }
tr.sectiontableentry2 td { padding: 5px 5px 5px 5px; background-color: #e6e5e3; border-bottom: 1px solid #666; border-left: 1px solid #666; }

td.sectiontableentry1 { padding: 5px 5px 5px 5px; background-color: #fff; border-bottom: 1px solid #666; }
td.sectiontableentry2 { padding: 5px 5px 5px 5px; background-color: #e6e5e3; border-bottom: 1px solid #666; }

table.tableinvoice { border-bottom: 1px solid #ccc; border-right: 1px solid #ccc; background-color: #fff; }
table.tableinvoice td { border: 1px solid #ccc; padding: 5px; border-bottom: none; border-right: none; }

tr.header td { font-size: 11px; }

.tdblue { color: white; background-color: #538dd5; }

.tdgreen { color: white; background-color: #00b050; }

.report-chart-wrap-1 { width: 350px; overflow: auto; }
</style>
<?php  global $reservations; 
function get_hotel( $association_id, $hotel_id )
{
		$db = &JFactory::getDbo();	
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

?><br/><br/>
<table class="tableinvoice" width="100%" border="0" cellspacing="0" cellpadding="0">
<tr valign="bottom" class="header" style="font-size: 11px;">
	<td style="padding-bottom:10px; color: white; background-color: #538dd5;" class="tdblue" height="25" width="110">Airportcode</td>
	<td class="tdblue" style="color: white; background-color: #538dd5;" height="25" width="270">Blockcode</td>
	<td class="tdblue" style="color: white; background-color: #538dd5;" height="25" width="270">Date</td>
	<td class="tdblue" style="color: white; background-color: #538dd5;" height="25" width="170">Airline</td>
	<td class="tdblue" style="color: white; background-color: #538dd5;" height="25" width="350">Hotel</td>
	<td class="tdblue" style="color: white; background-color: #538dd5;" height="25" width="100">WS</td>
	<td class="tdblue" style="color: white; background-color: #538dd5;" height="25" width="100">Initial Rooms</td>
    <td class="tdblue" style="color: white; background-color: #538dd5;" height="25" width="100">Flight number</td>	
    <td class="tdblue" style="color: white; background-color: #538dd5;" height="25" width="130">Booked name</td>
    <td class="tdblue" style="color: white; background-color: #538dd5;" height="25" width="100">Iata stranded code</td>
	<td class="tdgreen" style="color: white; background-color: #00b050;" height="25" width="100">Claimed Rooms</td>	
	<td class="tdgreen" style="color: white; background-color: #00b050;" height="25" width="100">Total estomated charges</td>
    <td class="tdgreen" style="color: white; background-color: #00b050;" height="25" width="100">Total nett charges WS</td>
    </tr>
    <?php foreach ($reservations['result'] as $i => $item) : //print_r( $item );die;?>
<tr>
	<td width="110"><?php echo $item->airport_code;?></td>
	<td width="270"><?php echo $item->blockcode;?></td>
	<td width="270">
    <?php echo sfsHelper::getATDate($item->booked_date,JText::_('DATE_FORMAT_LC2')); ?>
    </td>
	<td width="170">
		<?php echo $item->airline_name;?>
	</td>
	<td width="350">
		<?php if($item->association_id==0):?>
            <?php echo $item->hotel_name;?> 
        <?php endif;?>
    </td>
	<td width="100">	
        WS hotel
	</td>
	<td style="text-align:right" width="100">
		<?php echo (int)$item->sd_room+(int)$item->t_room+(int)$item->s_room+(int)$item->q_room;?>
	</td>
    
    <td style="text-align:right" width="100">
        <?php echo $item->flight_code;?>
	</td>
    <td style="text-align:right" width="130">
		<?php echo $item->booked_name;?>
	</td>
    <td style="text-align:right" width="100">
		<?php echo $item->delay_code;?>
	</td>
    
	<td style="text-align:right" width="100"><!--background:#8db4e2; -->
		<?php echo $item->claimed_rooms;?>	
	</td>	
	<td width="100"><?php
	
	$hotel = get_hotel( $item->association_id, $item->hotel_id );
	if ( $hotel ) {
		//echo $hotel->currency . ' ';
	}		
	echo $this->items['reservation']['k' . $item->id]['total_room_charge'];
	
		?>
	</td>
    <td style="text-align:right" width="100"><!--background:#8db4e2; -->
		<?php echo $reservations['reservation']['k' . $item->id]['total_nett_charges'];?>	
	</td>	
</tr>
<?php endforeach;?>
</table>