<?php
defined('_JEXEC') or die;
if($this->reservation->status == 'T' ) {
	//$status = 'C';
	$status = $this->reservation->status ;
} else {
	$status = $this->reservation->status ;
}
$room = unserialize($this->reservation->ws_room_type);
$wsRoomType = Ws_Do_Search_RoomTypeResult::fromString($room[0]["roomType"]);
$totalInvoiceCharge = $this->initial_rooms[1]*$this->reservation->s_rate
    +$this->initial_rooms[2]*$this->reservation->sd_rate
    + $this->initial_rooms[3]*$this->reservation->t_rate
    + $this->initial_rooms[4]*$this->reservation->q_rate;
?>

<div class="blockcode-information floatbox">
    <div class="floatbox clear">
        <span class="l-title">Flight Number:</span><span><?php echo $this->reservation->_vouchers[0]->flight_code;?></span>
    </div>
    <div class="floatbox clear">
	    <span class="l-title">Roomblock code:</span><span><?php echo $this->reservation->blockcode;?></span>
    </div>    
	<div class="floatbox clear">
	    <span class="l-title">Roomblock Status:</span><span><?php echo SFSCore::$blockStatus[$status];?></span>
    </div>
	<div class="floatbox clear">
    	<span class="l-title">Date</span><span><?php echo JHTML::_('date', $this->reservation->blockdate, JText::_('DATE_FORMAT_LC3') );?></span>
    </div>        
</div>

<?php if($this->reservation->payment_type == 'passenger' ):?>
<div style="padding-bottom:10px">
	<strong>Below charges have been paid directly by passenger to Hotel.</strong>
</div>
<?php endif;?>

<div class="estimate-information">

    <div class="estimate-title">Estimated charges</div>
    
    <div class="estimate-detail floatbox">
        <div class="floatbox clear">
            <span class="l-title">Estimated total gross invoice charge:</span><span><?php echo $this->hotel->currency; ?> <?php echo $totalInvoiceCharge?></span>
        </div>
    </div>
</div>
 
<table cellpadding="0" cellspacing="0" width="100%" class="blocked-rooms">
	<tr>
		<th class="br-c1">Mealplans</th>
        <td><?php echo $wsRoomType->MealBasisName?></td>
    </tr>
</table>  
