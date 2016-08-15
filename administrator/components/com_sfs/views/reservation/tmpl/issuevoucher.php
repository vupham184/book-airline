<?php
defined('_JEXEC') or die;

// Load the tooltip behavior.
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');
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
<style>
<!--
	h3{padding:4px 0;margin:0}
	.approved{color:green;}
-->
</style>

<form action="<?php echo JRoute::_('index.php?option=com_sfs'); ?>" method="post" name="adminForm" id="issuevoucher-form">

<fieldset>
	<div class="fltrt">
		<?php if ( !isset($this->fakeVoucher)) :?>
		<button type="submit">Issue Voucher</button>			
		<?php endif;?>
		<button onclick="window.parent.SqueezeBox.close();" type="button">Close</button>
	</div>
	<div class="configuration">
		Issue soft block code
	</div>
</fieldset>

<div class="width-60 fltlft">
	<fieldset class="adminform">
		<legend>Block Details</legend>		
		<table class="adminlist">
			<tbody>
				<tr>
					<td><h3>Blockcode</h3></td>
					<td><?php echo $this->reservation->blockcode;?></td>
				</tr>
				<tr>
					<td><h3>Transport Included</h3></td>
					<td><?php echo $this->reservation->transport==1 ? 'Yes':'No';?></td>
				</tr>
				<tr>
					<td><h3>Status</h3></td>
					<td>
						<span class="<?php echo JString::strtolower($status_array[$this->reservation->status])?>">
							<strong><?php echo $status_array[$this->reservation->status];?></strong>
						</span>
					</td>
				</tr>
				<tr>
					<td><h3>Date</h3></td>
					<td><?php echo JHTML::_('date', $this->reservation->room_date, JText::_('DATE_FORMAT_LC3') );?></td>
				</tr>
			</tbody>
		</table>				
	</fieldset>	
	
<!--	--><?php
//	$sVIssued = 0;
//	$sdVIssued = 0;
//	$tVIssued = 0;
//	$qVIssued = 0;
//	$issuedRooms = 0;
//	if( isset($this->vouchers) && count($this->vouchers) ) :
//		foreach ( $this->vouchers as $v ) {
//
//			switch ( (int)$v->room_type ) {
//				case 1:
//					$sVIssued++;
//					break;
//				case 2:
//					$sdVIssued++;
//					break;
//				case 3:
//					$tVIssued++;
//					break;
//				case 4:
//					$qVIssued++;
//					break;
//				default:
//					$array = array();
//					if( (int)$v->sroom > 0 ) {
//						$sVIssued += $v->sroom;
//					}
//					if( (int)$v->sdroom > 0 ) {
//						$sdVIssued += $v->sdroom;
//					}
//					if( (int)$v->troom > 0 ) {
//						$tVIssued += $v->troom;
//					}
//					if( (int)$v->qroom > 0 ) {
//						$qVIssued += $v->qroom;
//					}
//					break;
//			}
//		}
//		$issuedRooms = $sVIssued + $sdVIssued + $tVIssued + $qVIssued;
//	endif;?>
	
	<fieldset class="adminform">
		<legend>Rooms Details</legend>		
		<table class="adminlist">
			<thead>
				<tr>
					<th><strong>Rooms</strong></th>
					<th><strong>Nett rates</strong></th>
					<th><strong>Vouchers issued</strong></th>
					<th><strong>Picked up rooms</strong></th>
					<th><strong>Initial rooms</strong></th>				
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>Single price:</td>
					<td><?php echo $this->reservation->s_rate;?></td>
					<td><?php echo $sVIssued?></td>
					<td><?php echo $this->picked_rooms[1]?></td>
					<td><?php echo $this->initial_rooms[1];?></td>
				</tr>
				<tr>
					<td>Double price:</td>
					<td><?php echo $this->reservation->sd_rate;?></td>
					<td><?php echo $sdVIssued?></td>
					<td><?php echo $this->picked_rooms[2];?></td>
					<td><?php echo $this->initial_rooms[2];?></td>
				</tr>
				<tr>
					<td>Triple price:</td>
					<td><?php echo $this->reservation->t_rate;?></td>
					<td><?php echo $tVIssued?></td>
					<td><?php echo $this->picked_rooms[3];?></td>
					<td><?php echo $this->initial_rooms[3];?></td>
				</tr>	
				<tr>
					<td>Quad price:</td>
					<td><?php echo $this->reservation->q_rate;?></td>
					<td><?php echo $qVIssued?></td>
					<td><?php echo $this->picked_rooms[4];?></td>
					<td><?php echo $this->initial_rooms[4];?></td>
				</tr>			
			</tbody>
		</table>		
		<br />
		<?php 
            $initial_rooms = 0;
            foreach ($this->initial_rooms as $value) :
                $initial_rooms += $value;
            endforeach;
            echo '<h3>Total initial blocked rooms: '.$initial_rooms.'</h3>';
		?>  	
		<?php 
            $picked_rooms = 0;
            foreach ($this->picked_rooms as $value) :
                $picked_rooms += $value;
            endforeach;
            echo '<h3>Total picked up (used) rooms: '.$picked_rooms.'</h3>';;
        ?> 	
	</fieldset>	
</div>


<div class="width-40 fltlft">
<fieldset class="adminform">
		<legend>Estimated Charges</legend>		
		<table class="adminlist">
			<tbody>
				<tr>
					<td><h3>Estimated room charge</h3></td>
					<td><?php echo isset($this->total_room_charge) ? $this->total_room_charge : '0';?></td>
				</tr>
				<tr>
					<td><h3>Estimated mealplan charge</h3></td>
					<td><?php echo isset($this->total_mealplan_charge) ? $this->total_mealplan_charge : '0';?></td>
				</tr>
				<tr>
					<td><h3>Estimated invoice charge</h3></td>
					<td><?php echo isset($this->total_invoice_charge) ? $this->total_invoice_charge : '0';?></td>
				</tr>
				<tr>
					<td><h3>Currency</h3></td>
					<td><?php echo $this->hotel->currency;?></td>
				</tr>
				
				
			</tbody>
		</table>				
	</fieldset>	
	
	<fieldset class="adminform">
		<legend>Issue Voucher</legend>	
		<table class="adminlist">
			<?php
						
			$freeRooms = ( $initial_rooms * (int)$this->reservation->percent_release_policy) / 100;
			$freeRooms = (int)$freeRooms;
			$remainingRooms = $initial_rooms - $picked_rooms - $freeRooms;
			?>
			<tbody>
				<tr>
					<td><h3>free release percentage</h3></td>
					<td><?php echo (int) $this->reservation->percent_release_policy;?>%</td>
				</tr>
				<tr>
					<td><h3>Remaining Rooms</h3></td>
					<td><?php echo $remainingRooms ;?></td>
				</tr>
				<?php if( isset($this->fakeVoucher) && !empty($this->fakeVoucher->code) ) :?>
				<tr>
					<td><h3>Issued Voucher</h3></td>
					<td><?php echo $this->fakeVoucher->code;?></td>
				</tr>
				<?php endif;?>
			</tbody>
		</table>
			 
		<?php if ( !isset($this->fakeVoucher)) :?> 							 			
			<input type="hidden" name="task" value="reservation.issuevoucher" />
			<input type="hidden" name="id" value="<?php echo $this->reservation->id?>" />
			<input type="hidden" name="rooms" value="<?php echo $remainingRooms;?>" />
			<input type="hidden" name="return" value="<?php echo JRequest::getCmd('return');?>" />			
			<?php echo JHtml::_('form.token'); ?>		
		<?php endif;?>
		
	</fieldset>
</div>

</form>


