<?php
defined('_JEXEC') or die();
?>
	<tr class="r-heading">
		<td width="40%"><span class="r-heading roomtype">rooms</span></td>
        <td width="25%"><span class="r-heading roomtype">price</span></td>
		<td width="20%"><span class="r-heading roomtype">available</span></td>
        <td width="15%"><span class="r-heading roomtype">needed</span></td>
	</tr>

	<?php #print_r($this->item->wsData);?>
	<?php /* @var $rt Ws_Do_Search_RoomTypeResult */?>
	<?php $i = 0;?>
	<tr class="ws-room-toggle ws-room-toggle-<?php echo $this->item->id?>" data-id="<?php echo $this->item->id?>">
		<td colspan="4">
			<a href="#" class="toggle-room">Room starting from <strong><?php echo $this->item->currency_symbol.$this->item->wsData->StandardRate; ?></strong></a>
			
			<span class="toggle-button-lay">
				<button type="button" class="btn orange sm pull-right toggle-open">Open</button>
			</span>
		</td>
	</tr>

    <?php
        $array_price = array();
        $flag_mealplan = '';
        $count_breakfast = 0;
        $count_lunch = 0;
        $count_dinner = 0;
    ?>

	<?php foreach($this->item->wsData->RoomTypes as $rt) : ?>
		<?php $i++?>
		<?php $price = number_format(ceil(floatval($rt->Total)),2);?>
        <?php
            $array_price[$i] = $rt->Total;

            if (strpos($rt->MealBasisName, 'breakfast') !== false or strpos($rt->MealBasisName, 'Breakfast') !== false){
                $count_breakfast++;
                $flag_mealplan = 'mealplan_breakfast';
            }

            if (strpos($rt->MealBasisName, 'lunch') !== false){
                $count_lunch++;
                $flag_mealplan = 'mealplan_lunch';
            }

            if (strpos($rt->MealBasisName, 'dinner') !== false){
                $count_dinner++;
                $flag_mealplan = 'mealplan_dinner';
            }
        ?>
		<tr class="room-item ws-room-item ws-room-item-<?php echo $this->item->id?> <?php echo $flag_mealplan?>_<?php echo $this->item->id?> mealplan_<?php echo $this->item->id?>"  data-id="<?php echo $this->item->id?>">

			<td><?php echo $rt->Name?> (<?php echo $rt->NumAdultsPerRoom?> person)
				<div style="font-style: italic"><?php echo $rt->MealBasisName?></div>
			</td>
			<td class="result-row-room-price"><?php echo $this->item->currency_symbol." ".$price; ?></td>
            <td class="result-row-room-total"><?php echo $rt->TotalRoomAvailable; ?></td>
			<td class="room-item-last-cell" style="position: relative">
                <span class="booking-one-ws">
                    For this hotel it is only possible to reserve 1 room per booking
                </span>
                <span class="arrow-right"></span>
				<input type="text" class="ws_num_room_input input_<?php echo $flag_mealplan?>_<?php echo $this->item->id?>" style="width: 100%"
					name="ws_num_room_<?php echo $i?>"
                    data-minth="<?php echo $rt->Total?>"
					data-price="<?php echo $price?>"
                    data-id="<?php echo $this->item->id?>"
					data-ws="<?php echo $rt->toString()?>" value="0" size="1" class="smaller-size" />
			</td>
		</tr>
	<?php endforeach;?>
<script>
    var value_default =1;
    var minth = <?php echo min($array_price);?>;
    jQuery('input[data-minth='+minth+'][data-id="<?php echo $this->item->id?>"]:first').val(value_default);
    <?php
        if ($count_breakfast == 0 AND $count_lunch == 0){
            ?>
                jQuery('#show_breakfast_<?php echo $this->item->id;?>').html('N/A');
            <?php
        }
        if ($count_breakfast>0){
            ?>
                jQuery('#show_breakfast_<?php echo $this->item->id;?>').html('<label><input type="radio" name="breakfast_click_<?php echo $this->item->id?>" data-id="<?php echo $this->item->id?>" class="checkbox breakfast_click" mealtype="mealplan_breakfast"><b>Breakfast</b></label>');
            <?php
        }
        if ($count_lunch>0){
            ?>
                jQuery('#show_lunch_<?php echo $this->item->id;?>').html('<br/><label><input type="radio" name="breakfast_click_<?php echo $this->item->id?>" data-id="<?php echo $this->item->id?>" class="checkbox breakfast_click"  mealtype="mealplan_lunch"><b>Lunch</b></label>');
            <?php
        }
        if ($count_dinner>0){
            ?>
            jQuery('#show_dinner_<?php echo $this->item->id;?>').html('<br/><label><input type="radio" name="breakfast_click_<?php echo $this->item->id?>" data-id="<?php echo $this->item->id?>" class="checkbox breakfast_click"  mealtype="mealplan_dinner"><b>Dinner</b></label>');
            <?php
        }
?>
</script>

	 