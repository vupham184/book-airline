<?php
// no direct access
defined('_JEXEC') or die;
JHtml::_('behavior.tooltip');
JHtml::_('behavior.modal');
$status_array = array(
            	'O' => 'Open',
                'P' => 'Pending',
                'T' => 'Definite',
                'C' => 'Challenged',
                'A' => 'Approved',
                'R' => 'Archived'
                );
?>

<div class="clr"> </div>

<table class="adminlist">
	<thead>
		<tr>				
			<th width="15%" style="text-align:left; text-indent:5px;">
				<a href="#">Voucher Number</a>
			</th>				
			<th width="10%" style="text-align:left;text-indent:5px;">
				<a href="#">Blockcode</a>
			</th>					
			<th style="text-align:left;text-indent:5px;">
				<a href="#">Booked By</a>
			</th>
			<th style="text-align:left;text-indent:5px;">
				<a href="#">Hotel</a>
			</th>									
			<th width="7%" style="text-align:left;text-indent:5px;">
				<a href="#" style="text-align:left;text-indent:5px;">Initial rooms</a>
			</th>	
			<th width="7%" style="text-align:left;text-indent:5px;">
				<a href="#">Claimed rooms</a>
			</th>	
			<th width="7%" style="text-align:left;text-indent:5px;">
				<a href="#" style="text-align:left;text-indent:5px;">S/D price</a>
			</th>	
			<th width="7%" style="text-align:left;text-indent:5px;">
				<a href="#">T price</a>
			</th>						
			<th width="5%" style="text-align:left;text-indent:5px;">
				<a href="#">Status</a>
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
	<?php foreach ($this->items as $i => $item) : ?>
		<tr class="row<?php echo $i % 2; ?>">
			<td>
				<?php echo JHtml::_('grid.id', $i, $item->id); ?>
			</td>			
			<td>
				<?php echo $item->blockcode;?>&nbsp;&nbsp;&nbsp;[<a class="modal" rel="{handler: 'iframe', size: {x: 875, y: 550}, onClose: function() {}}" href="index.php?option=com_sfs&view=booking&id=<?php echo $item->id;?>&layout=vouchers&tmpl=component">Vouchers</a>]
			</td>									
			<td>
				<?php echo JHtml::_('date',$item->booked_date,JText::_('DATE_FORMAT_LC2'));?>
			</td>					
			<td>
				<a href="index.php?option=com_users&task=user.edit&id=<?php echo $item->booked_by;?>">
					<?php echo $item->booked_name;?>
				</a>
				-
				<a href="index.php?option=com_sfs&task=airline.edit&id=<?php echo $item->airline_id;?>">
				 (<?php echo $item->airline_code.', '.$item->airline_name.' - '.$item->city.', '.$item->country_name;?>)
				</a>
			</td>
			<td>
				<a href="index.php?option=com_sfs&task=hotel.edit&id=<?php echo $item->hotel_id;?>">
					<?php echo $item->hotel_name;?> 
				</a>				    
			</td>					
			<td>
				<?php
				$roomloadingTip  = 'Room date: '.JHtml::_('date',$item->room_date,JText::_('DATE_FORMAT_LC3')).'::Transport included: ';
				$roomloadingTip .= $item->transport_included ? 'Yes':'No'.'<br />SD Room: ';
				$roomloadingTip .= $item->sd_room_total.'<br />SD Room rate: '.$item->sd_room_rate.'<br />T Room: ';
				$roomloadingTip .= $item->t_room_total.'<br />T Room rate: '.$item->t_room_rate;
				?>									
				<span class="hasTip" title="<?php echo $roomloadingTip;?>"><?php echo (int)$item->sd_room+(int)$item->t_room;?> ?</span>
			</td>	
			<td>
				<?php echo $item->claimed_rooms;?>
			</td>	
			<td>
				<?php echo $item->sd_rate;?>
			</td>	
			<td>
				<?php echo $item->t_rate;?>
			</td>																						
			<td>
				<span class="<?php JString::strtolower($status_array[$item->status])?>">
					<?php echo $status_array[$item->status];?>
				</span>
			</td>	
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>
