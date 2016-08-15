<?php
defined('_JEXEC') or die;
?>
<div class="passengers">
    <div class="p-title">
        <span class="order">&nbsp;</span>
        <span>First name</span>
        <span>Last name</span>
        <span>Phone number</span>
        <span>Voucher number</span>
        <span>MealPlans</span>
    </div>
    <?php
    $room = unserialize($this->reservation->ws_room_type);
    $wsRoomType = Ws_Do_Search_RoomTypeResult::fromString($room[0]["roomType"]);
    $mealPlan = $wsRoomType->MealBasisName;
    $i = 0;
    if( count( $this->passengers) ) :
    foreach ( $this->passengers as $item ) : ?>
    <div class="passenger">
        <span class="order"><?php echo ++$i; ;?></span>
        <span><?php echo $item->first_name;?></span>
        <span><?php echo $item->last_name ;?></span>
        <span><?php echo $item->phone_number ;?></span>
        <span><?php echo $item->code ;?></span>
        <span><?php echo $mealPlan;?></span>
    </div>			
    <?php 
    endforeach ; 
    endif;
    ?>
    
    <?php
    if( !empty($this->guaranteeVoucher) && (int)$this->guaranteeVoucher->issued > 0 )
    {
    	$j=0;	
    	while ($j < $this->guaranteeVoucher->issued)
    	{
    		?>
    		<div class="passenger">
		        <span class="order"><?php echo ++$i; ;?></span>
		        <span>No show</span>
		        <span>No show</span>
		        <span><?php echo $this->guaranteeVoucher->code ;?></span>
		    </div>
    		<?php 
    		$j++;
    	}
    }
    ?>
</div>