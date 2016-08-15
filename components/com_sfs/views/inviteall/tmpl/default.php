<?php
defined('_JEXEC') or die();

$airline = SFactory::getAirline();
$airlineName = '';
if($airline->grouptype == 3) {
	$selectedAirline = $airline->getSelectedAirline();
	$airlineName = 	$selectedAirline->name;
}

?>
<div class="heading-block clearfix">
    <div class="heading-block-wrap">
        <h3><?php if($airlineName) echo $airlineName.': ';?><?php echo JText::_('COM_SFS_AIRLINE_MATCH_PAGE_TITLE');?></h3>
        <div class="clear floatbox largemargintop" style="text-align:center;color:#fff;">
        	<?php if($this->prevNight):?>
        	<a href="<?php echo $this->prevNightUrl?>" class="match-prev-night" data-step="8" data-intro="<?php echo SfsHelper::getTooltipTextEsc('match_prev_night', '&lt;&lt; Previous night', 'airline');?>"><?php echo SfsHelper::htmlTooltip('match_prev_night', '&lt;&lt; Previous night', 'airline');?></a>
        	<?php else:?>
        	<span class="match-prev-night"><?php echo SfsHelper::htmlTooltip('match_prev_night', '&lt;&lt; Previous night', 'airline');?></span>
        	<?php endif;?>
        	<a href="<?php echo $this->nextNightUrl?>" class="match-next-night" data-step="10" data-intro="<?php echo SfsHelper::getTooltipTextEsc('match_next_night', 'Next night &gt;&gt;', 'airline');?>"><?php echo SfsHelper::htmlTooltip('match_next_night', 'Next night &gt;&gt;', 'airline');?></a>
        	
        	<div class="sfs-match-title-desc<?php if($this->todayDate != $this->night) echo ' sfs-match-title-desc-warning';?>" data-step="9" data-intro="<?php echo SfsHelper::getTooltipTextEsc('match_night_range', $text, 'airline');?>">
        		<?php
    	    	$text = 'For the night starting: '.JFactory::getDate($this->night)->format('d M Y').' ending: '.JFactory::getDate($this->nextNight)->format('d M Y');
    	    	echo SfsHelper::htmlTooltip('match_night_range', $text, 'airline');
    	    	?>
        	</div>
        </div>
    </div>
</div>

<div class="main">
<table id="search-result" class="search-result">
<?php
$user = JFactory::getUser();
$enable_add_hotel = 0;
if( SFSAccess::isAirline($user) ) {
    $airline = SFactory::getAirline();
    $enable_invite_load_rooms = (int)$airline->params["enable_invite_load_rooms"];
}
if($enable_invite_load_rooms && count($this->hotelsNoRoomLoading)):?>
    <tbody id="hotelsNoRoomLoading">
    <tr>
        <td colspan="4">
            <h3>Hotels which have not loaded rooms yet for today</h3>
            <div style="overflow: hidden; padding: 15px; background: #82adf1; color: #FFFFFF">
                <div class="floatbox clear midmarginbottom">
                                <span class="noavail-hotel float-left inviteHotel" id="selectAllHotelNoRoom" >
                                    <input id="checkAll" type="checkbox" style="float: left; margin-right: 10px;" checked="checked">
                                    <label for="checkAll">Select all</label>
                                </span>
                    <button type="button" id="sendRequestLoadingRooms" class="small-button" style="width: 280px">Invite hotels to load rooms</button>
                </div>
                <?php foreach ($this->hotelsNoRoomLoading as $item) :?>
                    <div class="floatbox clear midmarginbottom">
                                    <span class="noavail-hotel float-left inviteHotel">
                                        <input type="checkbox" name="inviteHotel[]" value="<?php echo $item->id;?>" style="float: left; margin-right: 10px;" checked="checked" id="invite<?php echo $item->id?>">
                                        <label for="invite<?php echo $item->id?>"><?php echo $item->name?>
                                            <?php for($i = 0; $i<(int)$item->star; $i++):?>
                                                <i class="star icon"></i>
                                            <?php endfor;?>
                                        </label>
                                        <span style="margin-left: 15px" class="tracking"><?php if($item->date) echo "Invited at ".JFactory::getDate($item->date, 'UTC')->format("H:i", false, false);?></span>
                                    </span>
                    </div>
                <?php endforeach;?>
            </div>
        </td>
    </tr>
    </tbody>
<?php endif;?>
</table>
</div>