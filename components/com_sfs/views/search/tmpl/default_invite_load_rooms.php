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
                                        <span style="margin-left: 15px" class="tracking">                                           
                                            <?php if($item->date) echo "Invited at ".JFactory::getDate($item->date, 'UTC')->format("H:i", false, false);?>
                                        </span>
                                        <span class="waitInvited" style="float:left; margin:0px;"></span>
                                    </span>
                    </div>
                <?php endforeach;?>
            </div>
        </td>
    </tr>
    </tbody>
<?php endif;?>