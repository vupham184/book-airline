<?php
defined('_JEXEC') or die;
$estimateChargedRooms = $this->picked_rooms[1]+$this->picked_rooms[2] + $this->picked_rooms[3] + $this->picked_rooms[4];
if( !empty($this->guaranteeVoucher) && (int)$this->guaranteeVoucher->issued > 0 ){
 	$estimateChargedRooms = $estimateChargedRooms + (int)$this->guaranteeVoucher->issued;
}
?>

<div class="print-rooms">
<table cellpadding="0" cellspacing="0"  >
	<tr>
		<td>Rooms</td>
        <td class="smallpaddingbottom hugepaddingleft">Gross rates</td>
        <td class="smallpaddingbottom hugepaddingleft">Picked up rooms</td>
        <td class="smallpaddingbottom hugepaddingleft">Initial rooms</td>
    </tr>
	<?php if($this->reservation->s_room): ?>
	<tr>
    	<td>Single price:</td>
        <td class="smallpaddingbottom hugepaddingleft"><?php echo $this->hotel->currency; ?> <?php echo $this->reservation->s_rate;?></td>        
        <td class="smallpaddingbottom hugepaddingleft"><span class="v-pd"><?php echo $this->picked_rooms[1];?></span></td>
        <td class="smallpaddingbottom hugepaddingleft"><span class="v-pd"><?php echo $this->initial_rooms[1];?></span></td>
    </tr>  
	<?php endif;?>    
    <tr>
    	<td>Single/Double price:</td>
        <td class="smallpaddingbottom hugepaddingleft"><?php echo $this->hotel->currency; ?> <?php echo $this->reservation->sd_rate;?></td>        
        <td class="smallpaddingbottom hugepaddingleft"><span class="v-pd"><?php echo $this->picked_rooms[2];?></span></td>
        <td class="smallpaddingbottom hugepaddingleft"><span class="v-pd"><?php echo $this->initial_rooms[2];?></span></td>
    </tr>  
    <tr>
    	<td>Triple price:</td>
        <td class="smallpaddingbottom hugepaddingleft"><?php echo $this->hotel->currency; ?> <?php echo $this->reservation->t_rate;?></td>        
        <td class="smallpaddingbottom hugepaddingleft"><span class="v-pd"><?php echo $this->picked_rooms[3];?></span></td>
        <td class="smallpaddingbottom hugepaddingleft"><span class="v-pd"><?php echo $this->initial_rooms[3] ;?></span></td>
    </tr>  
    <?php if($this->reservation->q_room): ?>
	<tr>
    	<td>Quad price:</td>
        <td class="smallpaddingbottom hugepaddingleft"><?php echo $this->hotel->currency; ?> <?php echo $this->reservation->q_rate;?></td>        
        <td class="smallpaddingbottom hugepaddingleft"><span class="v-pd"><?php echo $this->picked_rooms[4];?></span></td>
        <td class="smallpaddingbottom hugepaddingleft"><span class="v-pd"><?php echo $this->initial_rooms[4];?></span></td>
    </tr>  
	<?php endif;?>       
</table>
</div>

<table cellpadding="0" cellspacing="0"  >
	<tr>
		<td>Total initial blocked rooms:</td>
        <td class="smallpaddingbottom hugepaddingleft">
			<?php 
                $initial_rooms = 0;
                foreach ($this->initial_rooms as $value) :
                    $initial_rooms += $value;
                endforeach;
                echo $initial_rooms;
            ?>    
            <?php if ( (int)$this->reservation->percent_release_policy >0 ) :?>
                &nbsp;&nbsp;&nbsp;&nbsp;free release percentage <?php echo (int)$this->reservation->percent_release_policy;?>%
            <?php endif;?>  
        </td>
    </tr>
    <tr>
    	<td>Total picked up (used) rooms:</td>            
        <td class="smallpaddingbottom hugepaddingleft">
         <?php 
            $picked_rooms = 0;
            foreach ($this->picked_rooms as $value) :
                $picked_rooms += $value;
            endforeach;
            echo $picked_rooms;
        ?> 
        </td>
    </tr>
    <tr>
    	<td>Total estimated charged rooms:</td>            
        <td class="smallpaddingbottom hugepaddingleft">
        <?php
            echo $estimateChargedRooms;
        ?>
        </td>
    </tr>
</table>
