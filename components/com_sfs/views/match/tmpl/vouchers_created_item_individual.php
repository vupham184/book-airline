<?php
defined('_JEXEC') or die;
$airline = SFactory::getAirline();
#var_dump($this->item->individualVouchers);
?>
<?php
foreach ($this->item->individualVouchers as $individualVoucher) :
	$invididualVoucherID = $individualVoucher->voucher_id;
	if($individualVoucher->status ==3 ) {
		$this->cancel_count++;
		continue;
	}
	?>
	<tr>
		<td>
			<?php echo $this->item->flight_code;?>
		</td>
		<td>
			<?php echo $this->item->blockcode;?><br />
			<?php
			$toolTip = '';
			$phoneNumbers = array();
			if(count($this->item->passengers))
			{
				$rooms = array();
				foreach ($this->item->passengers as $passenger)
				{
					#var_dump($invididualVoucherID);
					#var_dump($passenger);die();
					if($invididualVoucherID != $passenger->individual_voucher) {
						continue;
					}
					$room = $passenger->individual_voucher;
					$name = $passenger->first_name." ".$passenger->last_name ;
					$rooms[$room][] = trim($name);
					if($passenger->phone_number && !in_array($passenger->phone_number, $phoneNumbers))
					{
						$phoneNumbers[] = $passenger->phone_number;
					}
				}
			}

			if( count($rooms) ){
				$i = 0;
				foreach($rooms as $room)
				{
					$list_passengers = implode(', ', $room);
					$toolTip .= '<strong>Names on voucher '.($i+1).'</strong>'.'<br/>'.$list_passengers.'<br/>';
					if(count($phoneNumbers)){
						$toolTip .= '<strong>Phone Number</strong>'.'<br/>'. $phoneNumbers[$i] .'</p>';
					}
					$i++;
				}
			}
			if($this->item->comment){
				$toolTip .= '<strong>Comment on voucher</strong>'.'<p>'.$this->item->comment.'</p>';
			}
			if($toolTip) :
				?>
				<span class="hasTip2 underline-text" title="<?php echo $toolTip;?>"><?php echo $individualVoucher->code;?></span>
			<?php else :?>
				<span><?php echo $individualVoucher->code;?></span>
			<?php endif;?>

		</td>
		<td>
			<?php echo $individualVoucher->room_type;?>
		</td>
		<td>
			<?php
			$toolTip = '';
			$hasTip  = false;
			$toolTip = '<table class="tooltiptable"><tr><th>Breakfast</th><th>Lunch</th><th>Dinner</th></tr>';

			$toolTip .= '<tr><td>';
			if($this->item->breakfast){
				$hasTip = true;
				$toolTip .='Yes';
			} else {
				$toolTip .='No';
			}
			$toolTip .= '</td><td>';
			if($this->item->lunch){
				$hasTip = true;
				$toolTip .='Yes';
			} else {
				$toolTip .='No';
			}
			$toolTip .= '</td><td>';
			if($this->item->mealplan){
				$hasTip = true;
				$toolTip .= $this->item->course_type.'-course';
			} else {
				$toolTip .='No';
			}
			$toolTip .= '</td></tr>';
			$toolTip .= '</table>';
			?>
			<span <?php if($hasTip):?>class="underline-text hasTip2" title="<?php echo SfsHelper::escape($toolTip);?>"<?php endif;?>>
		<?php
		if($hasTip) {
			if($this->item->breakfast){ echo 'B ';}
			?>
			<?php
			if($this->item->lunch) {echo 'L ';}
			?>
			<?php
			if($this->item->mealplan) {echo 'D ';}
		}else {
			echo 'No';
		}
		?>
		</span>
		</td>

		<?php
		if( isset($airline->params['enable_passenger_payment']) && (int)$airline->params['enable_passenger_payment'] == 1 ) :
			?>
			<td>
				<?php echo $this->item->payment_type == 'passenger'? 'Passenger': $airline->code;?>
			</td>
		<?php endif;?>

		<td>
			<?php echo $this->item->created_name;?>
		</td>
		<td>
			<?php
			if($individualVoucher->room_type==1){
				echo 'Single';
			} else if($individualVoucher->room_type==2){
				echo 'Double';
			} else if($individualVoucher->room_type==3){
				echo 'Triple';
			} else if($individualVoucher->room_type==4){
				echo 'Quad';
			}
			?>
		</td>
		<td>
			<?php
			echo ($this->item->vgroup > 0 ) ? 'Yes ' : 'No';
			?>
		</td>
		<?php if( $airline->allowTaxiVoucher() ) : ?>
			<td>
				<?php
				if($this->item->taxi_printed) {
					echo 'airp - hotel';
				}
				if($this->item->taxi_return_printed) {
					echo '<br />hotel - airp';
				}
				if( (int)$this->item->busreservation_id > 0)
				{
					echo 'group<br/>transfer';
				}
				?>
			</td>
		<?php endif;?>
		<td>
			<?php
			if( $this->item->telephone ) :
				$toolTip ='<div class="fs-14">Hotel Phone Number<br/>'.$this->item->telephone.'</div>';
				?>
				<span class="hasTip2 underline-text" title="<?php echo SfsHelper::escape($toolTip);?>">
						<?php echo $this->item->name; ?>
					</span>
			<?php endif;?>
		</td>
		<td>
			<?php
			echo JHtml::_('date',$this->item->created,'H:i').'<br/>';
			if( (int)$this->item->printed == 1) {
				echo JHtml::_('date',$this->item->printed_date,'H:i').'<br/>';
			}
			if( !empty($individualVoucher->passenger_email)){
				echo str_replace(',','<br />', $individualVoucher->passenger_email);
			}
			?>
		</td>
		<td width="120">
			<?php if( (int)$this->item->status != 3 ) :
				$printLink  = 'index.php?option=com_sfs&view=match&layout=sendform&voucherid='.$this->item->id.'&blockcode='.$this->item->blockcode.'&tmpl=component&individual_voucher='.$individualVoucher->voucher_id;
				$cancelLink = 'index.php?option=com_sfs&view=match&layout=cancelvoucher&id='.$this->item->id.'&blockcode='.$this->item->blockcode.'&tmpl=component&Itemid='.JRequest::getInt('Itemid').'&individual_voucher='.$individualVoucher->voucher_id;;
				?>
				<div style="padding-bottom: 5px;">
					<a rel="{handler: 'iframe', size: {x: 450,y: 280}}" class="modal small-button" href="<?php echo $printLink;?>">Hotel Voucher</a>
				</div>
				<?php if( $this->item->taxi_voucher_id ) :
				$taxiVoucherLink = 'index.php?option=com_sfs&view=match&layout=taxivoucherform&tmpl=component&taxi_id='.$this->item->taxi_id.'&taxi_voucher_id='.$this->item->taxi_voucher_id;
				?>
				<div style="padding-bottom: 5px;">
					<a href="<?php echo $taxiVoucherLink?>" rel="{handler: 'iframe', size: {x: 350,y: 180}}" class="modal small-button">
						Taxi Voucher
					</a>
				</div>
			<?php endif;?>
				<a rel="{handler: 'iframe', size: {x: 500,y: 360}}" class="modal small-button" href="<?php echo $cancelLink;?>">Cancel</a>

			<?php endif;?>
		</td>
	</tr>

<?php
endforeach;
?>