<?php
defined('_JEXEC') or die;?>

<fieldset class="adminform">
	<legend>Vouchers</legend>	
		
	<table class="adminlist" width="100%">
		<tr>
			<th>Voucher number</th>
			<th>Flight number</th>
			<th>New flight/Date</th>			
			<th>Transport Ref</th>
			<th>Seats</th>						
			<th>Mealplan</th>
			<th>Room Type</th>
			<th>Comment</th>		
			<th>Creation</th>
			<th>Creation Rep</th>			
			<th>Print/Email</th>
		</tr>
		<?php foreach ($this->vouchers as $item) :
		if($item->status==1 || $item->status==2||$item->status==0) :
            if( $item->vgroup && count($item->individualVouchers) )
            {
                $this->voucher_item = $item;
                echo $this->loadTemplate('vouchers_individual');
                continue;
            }
		?>
		<tr>			
			<td>
				<?php echo $item->code;?>
				<?php
    			echo (int) $item->vgroup == 1 ? '<br />(group voucher)':'';  		
    			?>
			</td>
			<td>
				<?php
    			echo $item->flight_code;  		
    			?>
			</td>
			<td>
				<?php
    			if($item->return_flight_number) {
    				echo $item->return_flight_number.'<br/>'.$item->return_flight_date;
    			}  		
    			?>
			</td>
			<td>
				<?php
				if( $item->taxi_voucher_code ) {
					echo 'Taxi: '.$item->taxi_voucher_code;
				} else if( $item->bus_reference_number ) {
					echo 'Bus: '.$item->bus_reference_number;
				} else {
					//echo 'N/A';
				}	    		
    			?>
			</td>
			<td>
				<?php echo $item->seats;?>
			</td>
			<td>
				
				<?php
					 $toolTip = '';
					 $hasTip  = false;
					 $toolTip = '<table class="tooltiptable"><tr><th>Breakfast</th><th>Lunch</th><th>Dinner</th></tr>';
	
					 $toolTip .= '<tr><td>';
					 if($item->breakfast){
					 	$hasTip = true;	
					 	$toolTip .='Yes';					 	
					 } else {
					 	$toolTip .='No';
					 }						 
					 $toolTip .= '</td><td>';
					 if($item->lunch){
					 	$hasTip = true;		
					 	$toolTip .='Yes';				 	
					 } else {
					 	$toolTip .='No';
					 }					
					 $toolTip .= '</td><td>';		
					 if($item->mealplan){
					 	$hasTip = true;							 	
					 	$toolTip .= $this->reservation->course_type.'-course';					 	
					 } else {
					 	$toolTip .='No';
					 }	 
					 $toolTip .= '</td></tr>';
					 $toolTip .= '</table>';
				?>
				<span <?php if($hasTip):?>class="underline-text hasTip" title="<?php echo $this->escape($toolTip);?>"<?php endif;?>>
					<?php
					if($hasTip) {
						if($item->breakfast){ echo 'B ';} 
						?>
						<?php
						if($item->lunch) {echo 'L ';} 
						?>
						<?php
						if($item->mealplan) {echo 'D ';}						
					}else {
						echo 'No';
					}
					?>
				</span>
				
			</td>
			
			<td>
				<?php
				switch ($item->room_type) {
					case 1:
						echo 'Single'; 
						break;
					case 2: 
						echo 'Double';
						break;
					case 3: 
						echo 'Triple';
						break;
					case 4: 
						echo 'Quad';
						break;
					default:
						$array = array();
						if( (int)$item->sroom > 0 ) {
							$array[] = $item->sroom.' Single';
						}
						if( (int)$item->sdroom > 0 ) {
							$array[] = $item->sdroom.' Double';
						}
						if( (int)$item->troom > 0 ) {
							$array[] = $item->troom.' Triple';
						}
						if( (int)$item->qroom > 0 ) {
							$array[] = $item->qroom.' Quad';
						}
						if(count($array))
						{
							echo implode(', ', $array);
						}
						break;				
				}
				?>
			</td>
			
			<td>
				<span class="hasTip" title="<?php echo $item->comment;?>">
				<?php if($item->comment) : ?>
					Yes				
				<?php else : ?>
					No
				<?php endif;?>
				</span>
			</td>	
							
			<td>
				<?php 				
				echo SfsHelper::getATDate($item->created,'H:i');
				?>
			</td>
			<td>
				<?php 
				$u = JUser::getInstance($item->created_by);
				echo $u->name;
				?>
			</td>							
			<td>
				<?php 
					if($item->printed){						
						echo SfsHelper::getATDate($item->printed_date,'H:i').'<br />';
					}
					if($item->passenger_email)
					{
						echo '<span style="color:blue">'.$item->passenger_email.'</span>';
					}				
				?>			
			</td>												
		</tr>
		<?php 
		else: 
			$cancel_count++;
		endif;
		endforeach;?>
	</table>
    <?php
    if($this->fakeVoucher):
        ?>

        <h3 style="margin-top: 20px;font-weight:bold;">Minimum guarantee voucher number</h3>
        <table>
            <tr>
                <td>
                    Remaining Rooms
                </td>
                <td>
                    <?php echo $this->fakeVoucher->rooms;?>
                </td>
            </tr>
            <tr>
                <td>
                    Voucher number
                </td>
                <td>
                    <?php echo $this->fakeVoucher->code;?>
                </td>
            </tr>
        </table>
    <?php endif;?>

</fieldset>