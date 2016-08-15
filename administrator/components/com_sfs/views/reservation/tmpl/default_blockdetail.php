<?php
defined('_JEXEC') or die;
$status_array = array('O' => 'Open','P' => 'Pending','T' => 'Tentative','C' => 'Challenged','A' => 'Approved','R' => 'Archived','D' => 'Deleted');
?>

<fieldset class="adminform">
	<legend>Block Details</legend>		
	<table class="adminlist">
		<tbody>
			<tr>
				<td><h3>Blockcode</h3></td>
				<td>
					<strong><?php echo $this->reservation->blockcode;?></strong>
					&nbsp;&nbsp;&nbsp;
					<span class="<?php echo JString::strtolower($status_array[$this->reservation->status])?>-status">
						<strong style="color:green;"><?php echo $status_array[$this->reservation->status];?></strong>
					</span>
					&nbsp;&nbsp;&nbsp;
					<a style="color:#06F; text-decoration:underline;" href="index.php?option=com_sfs&view=reservation&id=<?php echo $this->reservation->id?>&layout=editstatus&tmpl=component" class="modal" rel="{handler: 'iframe', size: {x: 450,y: 350}, onClose: function() {}}">
						Update Status
					</a>
				</td>
			</tr>
			<tr>
				<td><h3>Date</h3></td>
				<td><?php echo JHTML::_('date', $this->reservation->room_date, JText::_('DATE_FORMAT_LC3') );?></td>
			</tr>
			<tr>
				<td><h3>Invoice for</h3></td>
				<td>
				<?php
					 echo JString::ucfirst($this->reservation->payment_type);
				?>
				</td>
			</tr>
            <?php if(!$this->hotel->ws_id):?>
			<tr>
				<td><h3>Fax Communication</h3></td>
				<td>
					<div style="width:120px;float:right"><a style="color:#06F; text-decoration:underline;" href="<?php echo JURI::root().'media/sfs/attachments/faxblock'.$this->reservation->id.'.html'?>" class="modal" rel="{handler: 'iframe', size: {x: 800, y: 600}}">Open block fax</a></div>
					Fax originally sent on: <?php echo sfsHelper::getATDate($this->reservation->booked_date,'Y-m-d H:i')?>
					
					<div class="clr"></div>
					
					<div style="width:120px;float:right"><a style="color:#06F; text-decoration:underline;" href="index.php?option=com_sfs&view=reservation&id=<?php echo $this->reservation->id?>&layout=faxform&tmpl=component" class="modal" rel="{handler: 'iframe', size: {x: 800, y: 600}}">Resend block fax</a></div>
					<?php if($this->faxTrack):?>		
					Fax resend by <?php echo $this->faxTrack->name?> on: <?php echo sfsHelper::getATDate($this->faxTrack->date,'Y-m-d H:i')?>
					<?php endif;?>	
				</td>
			</tr>
            <?php endif;?>
			<tr>
				<?php
				$tooltip  = 'Registered: '.$this->hotel->billing_name.'<br />';
				$tooltip .= $this->hotel->address.', '.$this->hotel->city.'<br />';
				$tooltip .= 'Ph: '.$this->hotel->telephone.'<br />';
				$tooltip .= 'Fax: '.$this->hotel->fax.'<br />';
				$tooltip .= 'TVA: '.$this->hotel->tva_number.'<br /><br />';
				
				$tooltip .= 'Sales contact: '.$this->hotelcontact->gender.' '.$this->hotelcontact->name.' '.$this->hotelcontact->surname.'<br />';
				$tooltip .= 'Ph: '.$this->hotelcontact->telephone.'<br />';
				$tooltip .= $this->hotelcontact->email.'<br />';
				
				$tooltip = htmlspecialchars($tooltip, ENT_COMPAT, 'UTF-8');
				?>
				<td><h3>Hotel</h3></td>
				<td>
				<?php echo $this->hotel->name; ?> <span class="hasTip" title="<?php echo $this->hotel->name; ?>::<?php echo $tooltip?>"><img src="<?php echo JURI::root().'components/com_sfs/assets/images/info16.png';?>" /></span>
				<?php if($this->reservation->transport==1):?>&nbsp;&nbsp;&nbsp;--&nbsp;&nbsp;&nbsp;Transport Included<?php endif;?>
				</td>
			</tr>
			<tr>
				<?php
				$tooltip  = 'Registered: '.$this->airline->billing->name.'<br />';
				$tooltip .= $this->airline->billing->address.', '.$this->airline->billing->city.'<br />';
				$tooltip .= 'Ph: '.$this->airline->telephone.'<br />';				
				$tooltip .= 'TVA: '.$this->airline->billing->tva_number.'<br /><br />';
				
				$tooltip .= 'Sales contact: '.$this->airlinecontact->gender.' '.$this->airlinecontact->name.' '.$this->airlinecontact->surname.'<br />';
				$tooltip .= 'Ph: '.$this->airlinecontact->telephone.'<br />';
				$tooltip .= $this->airlinecontact->email.'<br />';
				
				$tooltip = htmlspecialchars($tooltip, ENT_COMPAT, 'UTF-8');
				?>
				<td><h3>Airline</h3></td>
				<td><?php echo $this->airline->airline_name; ?> <span class="hasTip" title="<?php echo $this->airline->airline_name; ?>::<?php echo $tooltip?>"><img src="<?php echo JURI::root().'components/com_sfs/assets/images/info16.png';?>" /></span></td>
			</tr>
            <tr>
                <td><h3>WS or Partner</h3></td>
                <td>
                    <?php if($this->reservation->ws_room > 0){?>
                        WS hotel
                    <?php }else{?>
                        Partner hotel
                    <?php }?>
                </td>
            </tr>
            <?php
                if($this->reservation->ws_room > 0):
                    require_once '../ws/lib/Ws/Do/Book/Response.php';
                    require_once '../ws/lib/Ws/Do/Book/PropertyBookingResponse.php';
                    $wsBooking = Ws_Do_Book_Response::fromString($this->reservation->ws_booking);
            ?>
            <tr>
                <td style="width: 120px"><h3>WS confirmation</h3></td>
                <td>
                    Booking reference: <?php echo $wsBooking->BookingReference;?><br/>
                    This reservation is booked and payable by <?php echo $wsBooking->PropertyBookings[0]->Supplier?>
                    with reference <?php echo $wsBooking->PropertyBookings[0]->SupplierReference?> under no circumstances should
                    the customer be charged for this booking.<br/>
                </td>
            </tr>
            <?php endif?>
		</tbody>
	</table>				
</fieldset>	
