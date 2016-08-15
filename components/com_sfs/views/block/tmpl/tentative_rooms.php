<?php
defined('_JEXEC') or die;
$estimateChargedRooms = $this->picked_rooms[1]+$this->picked_rooms[2] + $this->picked_rooms[3] + $this->picked_rooms[4];
if( !empty($this->guaranteeVoucher) && (int)$this->guaranteeVoucher->issued > 0 ){
 	$estimateChargedRooms = $estimateChargedRooms + (int)$this->guaranteeVoucher->issued;
}
?>
<div data-step="5" data-intro="<?php echo SfsHelper::getTooltipTextEsc('blocked_rooms', $text, 'hotel'); ?>">
    <table cellpadding="0" cellspacing="0" width="100%" class="blocked-rooms">
    	<tr>
    		<th class="br-c1">Rooms</th>
            <th class="br-c2">Gross rates</th>
            <th class="br-c3">Picked up rooms</th>
            <th class="br-c4">Initial rooms</th>
        </tr>
    	<?php
    	$class = null;
    	if($this->reservation->s_room):
    		$class = 'first';
    	?>
    	<tr class="<?php echo $class?>">
    		<td>Single price:</td>
    		<td><?php echo $this->hotel->currency; ?> <?php echo $this->reservation->s_rate;?></td>        
            <td><span class="v-pd"><?php echo $this->picked_rooms[1]?></span></td>
            <td><span class="v-pd"><?php echo $this->initial_rooms[1];?></span></td>
    	</tr>
    	<?php endif;?>    
        <tr class="<?php echo $class==null?'first':'';?>">
        	<td>Single/Double price:</td>
            <td><?php echo $this->hotel->currency; ?> <?php echo $this->reservation->sd_rate;?></td>
            <td><span class="v-pd"><?php echo $this->picked_rooms[2];?></span></td>
            <td><span class="v-pd"><?php echo $this->initial_rooms[2] ;?></span></td>
        </tr>
        <tr>
        	<td>Triple price:</td>
            <td><?php echo $this->hotel->currency; ?> <?php echo $this->reservation->t_rate;?></td>
            <td><span class="v-pd"><?php echo $this->picked_rooms[3];?></span></td>
            <td><span class="v-pd"><?php echo $this->initial_rooms[3] ;?></span></td>
        </tr>
        <?php if($this->reservation->q_room): ?>
        <tr>
        	<td>Quad price:</td>
            <td><?php echo $this->hotel->currency; ?> <?php echo $this->reservation->q_rate;?></td>        
            <td><span class="v-pd"><?php echo $this->picked_rooms[4];?></span></td>
            <td><span class="v-pd"><?php echo $this->initial_rooms[4] ;?></span></td>
        </tr>   
        <?php endif;?> 
    </table>

    <div class="rooms-total floatbox">
        <div class="float-left" style="width:60%">Total initial blocked rooms:</div>
        <div class="float-left">
            <?php
                $initial_rooms = 0;
                foreach ($this->initial_rooms as $value) :
                    $initial_rooms += $value;
                endforeach;
                echo $initial_rooms;
            ?>
        </div>
        <div class="clear"></div>
        <div class="float-left" style="width:60%">Total picked up (used) rooms:</div>
        <div class="float-left">
            <?php
                $picked_rooms = 0;
                foreach ($this->picked_rooms as $value) :
                    $picked_rooms += $value;
                endforeach;
                echo $picked_rooms;
            ?>
        </div>
        <div class="clear"></div>
        <div class="float-left" style="width:60%">Total estimated charged rooms:</div>
        <div class="float-left">
            <?php
                echo $estimateChargedRooms;
            ?>
        </div>
        
        <div class="clear"></div>
        
        <div class="floatbox" style="margin-top:15px;">
    	    <div class="float-left" style="width:60%">free release percentage:</div>
    	    <div class="float-left">
    	        <?php
    	            echo (int)$this->hotel->getTaxes()->percent_release_policy.'%';
    	        ?>
    	    </div>
        </div>
    </div>
</div>

