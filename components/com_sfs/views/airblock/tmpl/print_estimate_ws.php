<?php
defined('_JEXEC') or die;
//if($this->reservation->status == 'T' ) $this->reservation->status = 'C';
$room = unserialize($this->reservation->ws_room_type);
$wsRoomType = Ws_Do_Search_RoomTypeResult::fromString($room[0]["roomType"]);
$totalInvoiceCharge = $this->initial_rooms[1]*$this->reservation->s_rate
    +$this->initial_rooms[2]*$this->reservation->sd_rate
    + $this->initial_rooms[3]*$this->reservation->t_rate
    + $this->initial_rooms[4]*$this->reservation->q_rate;
?>

<table cellpadding="0" cellspacing="0" border="0">
    <tr valign="top">
        <td width="180" class="smallpaddingbottom">Flight Number:</td>
        <td class="smallpaddingbottom">
            <?php echo $this->reservation->_vouchers[0]->flight_code;?>
        </td>
    </tr>
	<tr valign="top">
    	<td width="180" class="smallpaddingbottom">Roomblock code:</td>
        <td class="smallpaddingbottom">
        	<?php echo $this->reservation->blockcode;?>
        </td>
    </tr>
	<tr valign="top">
    	<td class="smallpaddingbottom">Roomblock Status:</td>
        <td>
     	   <?php echo SFSCore::$blockStatus[$this->reservation->status];?>
        </td>
    </tr>
	<tr valign="top">
    	<td class="smallpaddingbottom">Date:</td>
        <td class="smallpaddingbottom">
        	<?php echo JHTML::_('date', $this->reservation->booked_date, JText::_('DATE_FORMAT_LC3') );?>
        </td>
    </tr>        
</table>



<div class="estimated-charges">

<?php if($this->reservation->payment_type == 'passenger' ):?>
<div>
	<strong>Below charges have been paid directly by passenger to Hotel.</strong>
</div>
<?php endif;?>

<div style="font-weight:bold" class="smallpaddingbottom">Estimated charges</div>

<table cellpadding="0" cellspacing="0" border="0">
	<tr valign="top">
    	<td class="smallpaddingbottom">Estimated total gross invoice charge:</td>
        <td class="smallpaddingbottom hugepaddingleft">
        	<?php echo $this->hotel->currency; ?> <?php echo $totalInvoiceCharge ;?>
        </td>
    </tr>
</table>    
</div>
 
<table cellpadding="0" cellspacing="0" width="100%">
    <tr>
        <th style="text-align: left">Mealplans</th>
        <td class="smallpaddingbottom hugepaddingleft"><?php echo $wsRoomType->MealBasisName?></td>
    </tr>
</table>