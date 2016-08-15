<?php
defined('_JEXEC') or die;
?>

<?php
foreach ($this->voucher_item->individualVouchers as $individualVoucher) : 
	if($individualVoucher->status ==3 ) {	
		continue;
	}
?>
<tr>			
    <td>
        <?php echo $this->voucher_item->code.' - '.$individualVoucher->code;?>
        <?php
        echo (int) $this->voucher_item->vgroup == 1 ? '<br />(group/individual)':'';  		
        ?>
    </td>
    <td>
        <?php
        echo $this->voucher_item->flight_code;  		
        ?>
    </td>
    <td>
        <?php
        if($this->voucher_item->return_flight_number) {
            echo $this->voucher_item->return_flight_number.'<br/>'.$this->voucher_item->return_flight_date;
        }  		
        ?>
    </td>
    <td>
        <?php
        if( $this->voucher_item->taxi_voucher_code ) {
            echo 'Taxi: '.$this->voucher_item->taxi_voucher_code;
        } else if( $this->voucher_item->bus_reference_number ) {
            echo 'Bus: '.$this->voucher_item->bus_reference_number;
        } else {
            //echo 'N/A';
        }	    		
        ?>
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
             if($this->voucher_item->breakfast){
                $hasTip = true;	
                $toolTip .='Yes';					 	
             } else {
                $toolTip .='No';
             }						 
             $toolTip .= '</td><td>';
             if($this->voucher_item->lunch){
                $hasTip = true;		
                $toolTip .='Yes';				 	
             } else {
                $toolTip .='No';
             }					
             $toolTip .= '</td><td>';		
             if($this->voucher_item->mealplan){
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
                if($this->voucher_item->breakfast){ echo 'B ';} 
                ?>
                <?php
                if($this->voucher_item->lunch) {echo 'L ';} 
                ?>
                <?php
                if($this->voucher_item->mealplan) {echo 'D ';}						
            }else {
                echo 'No';
            }
            ?>
        </span>
        
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
        <span class="hasTip" title="<?php echo $this->voucher_item->comment;?>">
        <?php if($this->voucher_item->comment) : ?>
            Yes				
        <?php else : ?>
            No
        <?php endif;?>
        </span>
    </td>	
                    
    <td>
        <?php 				
        echo SfsHelper::getATDate($this->voucher_item->created,'H:i');
        ?>
    </td>
    <td>
        <?php 
        $u = JUser::getInstance($this->voucher_item->created_by);
        echo $u->name;
        ?>
    </td>							
    <td>
        <?php 
            if($this->voucher_item->printed){						
                echo SfsHelper::getATDate($this->voucher_item->printed_date,'H:i').'<br />';
            }
            if($this->voucher_item->passenger_email)
            {
                echo '<span style="color:blue">'.$this->voucher_item->passenger_email.'</span>';
            }				
        ?>			
    </td>												
</tr>

<?php endforeach;?>