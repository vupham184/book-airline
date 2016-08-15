<?php
// No direct access
defined('_JEXEC') or die;
?>
<div style="padding-top: 0px;">

<fieldset class="adminform" style="background: none;">

	<h2 style="padding: 0; margin:0 0 15px">Roomblocks for today</h2>

	<table width="100%" class="adminlist">

		<tr>
			<th>Block Code</th>
			<th>Status</th>
			<th>Airline</th>
			<th>Hotel</th>
			<th>Initial</th>
			<th>Claimed</th>
			<th>S Rate</th>			
			<th>D Rate</th>
			<th>T Rate</th>
			<th>Q Rate</th>
		</tr>		
		
		<?php 
		if( count($this->reservations) ) :
			foreach ($this->reservations as $reservation) : ?>			
			<tr>			
				<td>
					<a href="index.php?option=com_sfs&view=reservation&id=<?php echo $reservation->id?>">
					<?php echo $reservation->blockcode;?>
					</a>
				</td>
				<td><?php echo $reservation->status;?></td>
				<td>
					<?php $tip = '';?>					
					<?php if(!empty($reservation->company_name)) : ?>					
						<?php $tip = $reservation->company_name.' - '.$reservation->city.', '.$reservation->country_name;?>					
					<?php else : ?>					
						<?php $tip =  $reservation->airline_code.', '.$reservation->airline_name.' - '.$reservation->city.', '.$reservation->country_name;?>					
					<?php endif; ?>	
					<?php if(!empty($reservation->company_name)) : ?>					
						<?php echo $reservation->company_name?>					
					<?php else : ?>					
						<?php echo $reservation->airline_name?>					
					<?php endif; ?>	
				</td>
				<td><?php echo $reservation->hotel_name;?></td>
				<td><?php echo (int)$reservation->sd_room+(int)$reservation->t_room+(int)$reservation->s_room+(int)$reservation->q_room;?></td>
				<td><?php echo $reservation->claimed_rooms;?></td>
				<td><?php echo floatval($reservation->s_rate);?></td>
				<td><?php echo floatval($reservation->sd_rate);?></td>
				<td><?php echo floatval($reservation->t_rate);?></td>
				<td><?php echo floatval($reservation->q_rate);?></td>
				
			</tr>
			
			<?php 
			endforeach;
		else :
		?>
		<tr><td colspan="8">No blocks</td></tr>		
		<?php endif;?>

	</table>
</fieldset>

</div>