<?php
defined('_JEXEC') or die();
$airline = SFactory::getAirline();
$currency_symbol = $this->hotel->getTaxes()->currency_symbol;

$sdroom = JRequest::getInt('sdroom',0);
$troom = JRequest::getInt('troom',0);
$sroom = JRequest::getInt('sroom',0);
$qroom = JRequest::getInt('qroom',0);
$wsRoom = JRequest::getInt('ws-num-room');

$totalRooms = $sdroom + $troom + $sroom + $qroom;

$breakfast = JRequest::getInt('breakfast',0);
$lunch = JRequest::getInt('lunch',0);
$dinner = JRequest::getInt('dinner',0);
$course = JRequest::getInt('course',0);

$date_start = JRequest::getVar('date_start');
$date_end = JRequest::getVar('date_end');
$rooms = JRequest::getVar('rooms');

$percent_release_policy = JRequest::getInt('percent_release_policy',0);
?>
<script type="text/javascript">
    jQuery.noConflict();
    jQuery(function($){
        $(document).ready(function(){
            var form = $('form[name="bookingForm"]');
            $("#booking1, #booking3").on("click", function(){
                $("[name='issuesinglevoucher']").val(1);
                form.submit();
                this.disabled = true;
            });

            $("#booking2, #booking4").on("click", function(){
                form.submit();
                this.disabled = true;
            });
        });

    });
</script>

<div id="booking-form-wrapper">
    <form action="<?php echo JRoute::_('index.php?option=com_sfs')?>" name="bookingForm" method="post" >
        <?php if($this->isWS) : ?>
            <?php echo $this->loadTemplate('form_info_ws');?>
                <div class="floatbox" style="margin-top: 80px; padding-left: 15px; padding-right: 180px">
                    <?php else:?>
                    <?php echo $this->loadTemplate('form_info_normal');?>
                    <div class="floatbox" style="padding-left: 15px;>
                    <?php endif;?>
                    <?php if((int)$totalRooms==1): ?>
                        <div class="floatbox">
                    
                    <?php if(JRequest::getVar('pass_issue_hotel')==''){
                        ?>
                        <div class="mid-button float-right" style="margin-bottom: 10px;">
                            <button type="button" style="text-indent:22px;" id="booking1">
                                Yes, and issue a voucher now
                            </button>
                         </div>
                        <?php } ?>                        
                   
                </div>
                <div class="floatbox">
                    <div class="mid-button float-left">
                        <button type="button" onClick="window.parent.SqueezeBox.close();" style="text-indent:22px;">No, please cancel</button>
                    </div>
                    <div class="mid-button float-right">
                        <button type="button" style="text-indent:22px;" id="booking2"><?php if(JRequest::getVar('pass_issue_hotel')!='') echo 'YES, BOOK HOTEL AND ISSUE VOUCHER'; else echo 'Yes, book now and i will issue a voucher later'; ?></button>
                    </div>
                </div>
        <?php else : ?>
                <?php if($this->isWS) : ?>
                    <div class="floatbox">
                        <div class="mid-button float-left">
                            <button type="button" onClick="window.parent.SqueezeBox.close();" style="text-indent:22px;">No, please cancel</button>
                        </div>
                        <div class="mid-button float-right" style="margin-bottom: 10px;">
                            <button type="button" style="text-indent:22px;" id="booking3">
                                Yes, book now
                            </button>
                        </div>
                    </div>
                <?php else : ?>
                    <div class="floatbox">
                        <div class="mid-button float-left">
                            <button type="button" onClick="window.parent.SqueezeBox.close();" style="text-indent:22px;">No, please cancel</button>
                        </div>
                        <div class="mid-button float-right" style="margin-right: 200px">
                            <button type="button" style="text-indent:22px;" id="booking4">Yes, book now</button>
                        </div>
                    </div>
                <?php endif;?>
            <?php endif;?>
        </div>
        <div class="floatbox fs-12" style="text-align:center;margin-top: 20px;">
            <?php if(!empty($this->isWS)) : ?>
                <?php echo JText::_('COM_SFS_BOOKING_NOTE_WS')?>
            <?php else : ?>
                <?php echo JText::_('COM_SFS_BOOKING_NOTE')?>
            <?php endif;?>
        </div>
        <input type="hidden" name="hotel_id" value="<?php echo $this->hotel->id;?>" />
        <input type="hidden" name="room_id" value="<?php echo $this->inventory->id;?>" />
        <input type="hidden" name="association_id" value="<?php echo $this->associationId;?>" />
        <input type="hidden" name="sd_room" value="<?php echo $sdroom;?>" />
        <input type="hidden" name="t_room" value="<?php echo $troom;?>" />
        <input type="hidden" name="s_room" value="<?php echo $sroom;?>" />
        <input type="hidden" name="q_room" value="<?php echo $qroom;?>" />
        <input type="hidden" name="breakfast" value="<?php echo $breakfast;?>" />
        <input type="hidden" name="lunch" value="<?php echo $lunch;?>" />
        <input type="hidden" name="mealplan" value="<?php echo $dinner;?>" />
        <input type="hidden" name="course" value="<?php echo $course;?>" />
        <input type="hidden" name="date_start" value="<?php echo $date_start;?>" />
        <input type="hidden" name="date_end" value="<?php echo $date_end;?>" />
        <input type="hidden" name="rooms" value="<?php echo $rooms;?>" />
        <input type="hidden" name="percent_release_policy" value="<?php echo $percent_release_policy;?>" />
        <input type="hidden" name="issuesinglevoucher" value="0" />
        <?php if($this->isWS) : ?>
            <input type="hidden" name="ws_rooms" value="<?php echo htmlspecialchars(serialize($this->wsRoomTypes))?>">
            <input type="hidden" name="ws_prebooking" value="<?php echo $this->wsPreBookString?>">
        <?php endif;?>
        <input type="hidden" name="task" value="booking.process" />
        <input type="hidden" name="pass_issue_hotel" value="<?php if(JRequest::getString('pass_issue_hotel')){
        echo JRequest::getString('pass_issue_hotel');
    } ?>" />
    <input type="hidden" name="room_book" value="<?php if(JRequest::getString('room_book')){
        echo JRequest::getString('room_book');
    } ?>" />
<!--    <input type="hidden" name="tb_share_room" value="<?php if(JRequest::getString('tb_share_room')){
        echo JRequest::getString('tb_share_room');
    } ?>" />-->
    <textarea name="tb_share_room" style="display:none;"><?php if(JRequest::getString('tb_share_room')){
        echo JRequest::getString('tb_share_room');
    } ?></textarea>
    <input type="hidden" name="pass_detail_hotel" value="<?php if(JRequest::getString('pass_detail_hotel')){
        echo JRequest::getString('pass_detail_hotel');
    } ?>" />
        <?php echo JHtml::_('form.token'); ?>
    </form>
</div>

