<?php
defined('_JEXEC') or die;?>
<?php 
$total_picked_up_rooms = $this->picked_rooms[1];
$total_picked_up_rooms += $this->picked_rooms[2];
$total_picked_up_rooms += $this->picked_rooms[3];
$total_picked_up_rooms += $this->picked_rooms[4];

$total_initial_rooms = $this->initial_rooms[1];
$total_initial_rooms += $this->initial_rooms[2];
$total_initial_rooms += $this->initial_rooms[3];
$total_initial_rooms += $this->initial_rooms[4];

if ( $total_initial_rooms > $total_picked_up_rooms) :?>
<span class="add-right top">
    <a href="javascript:void(0);" class="add-vouchers">
    	<span class="icon-16-add"></span>
    </a>
</span> 
<?php endif;?>

<?php
	$printed = "";
	$commentYN = 'No';
	$str_comment = '';
	$str_passenger_email = '';
	foreach ($this->vouchers as $item) :
		if(SfsHelper::getATDate($item->printed_date,'H:i') != "00:00"){
			$printed = SfsHelper::getATDate($item->printed_date,'H:i');
			if($item->comment){
				$commentYN = 'Yes';
				$str_comment = $item->comment;
			}
			if($item->passenger_email){
				$str_passenger_email = '<span style="color:blue">'.$item->passenger_email.'</span>';
			}
		}
		
?>

<?php endforeach;?>
	

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
            // if( $item->vgroup && count($item->individualVouchers) )
            // {
            //     $this->voucher_item = $item;
            //     echo $this->loadTemplate('vouchers_individual');
            //     continue;
            // }
		?>
		<tr>			
			<td><input type="hidden" name="vouchers-<?php echo $item->code;?>"  value="<?php echo $item->seats;?>" />
				<?php echo $item->code; ?>								
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
					$str = "";
					$arrList=array('Single'=>$item->sroom, 'Double'=>$item->sdroom,
									'Triple'=>$item->troom,'Quad'=>$item->qroom);					

					foreach ($arrList as $key => $value) {
						if($value > 0){
							$str = $str . $value . " " . $key . ", ";
						}
					}	

					$subStr = substr($str,0,-2);	
					echo($subStr);				
				?>
			</td>
			
			<td>
				<span class="hasTip" title="<?php echo $str_comment;?>">
				<?php 
				echo $commentYN ;?>
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
				<?php echo $printed ; ?>
				<?php echo $str_passenger_email; ?>			
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