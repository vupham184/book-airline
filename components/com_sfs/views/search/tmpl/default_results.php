<style>
    .erratum, .special-offer{
        display: none !important;
        color: white;
        background-color: #01B2C3;
        border-radius: 10px;
        padding: 5px 5px 5px 5px;
        margin-right: 10px
    }
    .special-offer{
        width: 50%;
        display: table;
    }
    .result-row-room-price, .result-row-room-total{
        font-size: 18px;
    }
</style>
<?php
defined('_JEXEC') or die;
$airline = SFactory::getAirline();
jimport( 'joomla.application.module.helper' );
$module = JModuleHelper::getModule( 'mod_sfs_change_currency' );
$mod_change_currency = JModuleHelper::renderModule( $module );

if(JRequest::getInt('ordering')){
    $ordering = JRequest::getInt('ordering');    
}
else{
    if($airline->params['default_sort_order']){
        $ordering = $airline->params['default_sort_order'];   
    }else{
        $ordering =0;
    }
}
$wsCached = $this->wsCached;

$session = JFactory::getSession();
$rooms = json_decode($session->get("rooms_search"), true);
$total_people = 0;
$roomSpecific = 0;
foreach($rooms as $room)
{
    if((int)$room["num_adults"] != 0)
    {
        $roomSpecific ++;
    }
    $total_people += (int)$room["num_adults"]+(int)$room["num_children"];
}
$total_available_rooms = 0;
foreach($this->result as $hotel)
{
    $total_available_rooms += ($hotel->s_room_total + $hotel->sd_room_total + $hotel->t_room_total + $hotel->q_room_total);
}
?>

<style type="text/css">
    .search-result .toggle-close{
        display: none;
    }
    .search-result tr.ws-room-toggle-opened .toggle-close{
        display: block;
    }
    .search-result tr.ws-room-toggle-opened .toggle-open{
        display: none;
    }
    .search-result tr.ws-room-toggle-opened{
        display: none;
    }
    .search-result tr.ws-room-item{
        display: none;
    }
    .search-result tr.ws-room-item-opened{
        display: table-row;
    }
    .search-result .ws-book-now{
        display: none;
    }
    .search-result .bookingbutton {
        margin-bottom: 30px;
    }
    .search-result .ws-book-now-opened{
        display: table-row;
    }
    .search-result tr.hotel-row-space td{
        font-size: 0;
        height: 7px;
        padding: 0;
    }
    .room-item-last-cell .arrow-right {
        width: 0;
        height: 0;
        border-top: 4px solid transparent;
        border-bottom: 4px solid transparent;
        border-left: 6px solid #01B2C3;
        position: absolute;
        margin-left: -8px;
        margin-top: 20px;
        display: none;
    }
    .room-item-last-cell .booking-one-ws {
        position: absolute;
        color: white;
        background-color: #01B2C3;
        border-radius: 10px;
        padding: 5px 5px 5px 10px;
        width: 205px;
        margin-left: -215px;
        margin-top: -3px;
        display: none;
    }

    #hotelsNoRoomLoading .noavail-hotel {
        margin-left: 20px;
    }

    #hotelsNoRoomLoading .star.icon {
        background: none;
        margin: 0;
    }

    .inviteHotel label{
        display: block;
        margin-right: 15px;
        float: left;
        overflow: hidden;
    }

	/*lchung*/
	table thead th img {
		cursor: pointer;
		position: absolute;
		right: 12px;
		top: 20px;
	}
	img {
		border: 0 none;
	}
	
    .DataTable_input{
        width: 100% !important;
        border: 1px solid #82adf1;
    }
	/*End lchung*/
    .fix_overflow{
        overflow: inherit;
        height: 80px;
    }
</style>

<div class="sfs-above-main search-results-title fix_overflow">
    
    <h3 class="pull-left">Your Result(s) <?php echo count($this->result) > 10 ? " Total " . count($this->result) . " hotels" : "";  ?></h3>
    <div class="pull-right field-no-form vertical" data-step="3" data-intro="<?php echo SfsHelper::getTooltipTextEsc('search_sort_by', 'help-icon', 'airline', false) ?>">
        <div class="form-group">
            <label>Sort by:</label>
            <form action="#" method="POST" id="form_sort">
                <select name="ordering" onchange="changeDropDown(this)" id="select_sort">
                    <option value="0">Select</option>
                    <option value="1"<?php if($ordering==1)	echo ' selected="selected"';?>>Star</option>
                    <option value="2"<?php if($ordering==2)	echo ' selected="selected"';?>>Price of hotel</option>
                    <option value="3"<?php if($ordering==3)	echo ' selected="selected"';?>>Distance to airport</option>
                    <option value="4"<?php if($ordering==4)	echo ' selected="selected"';?>>Hotel shuttle available</option>
                    <option value="5"<?php if($ordering==5)	echo ' selected="selected"';?>>Total calculated price</option>
                </select>
                <input type="hidden" id="ws_only" name="ws_only" value="<?php echo intval($this->wshoteladd==1)  ?>">

            </form>
        </div>
    </div>
    <?php echo $mod_change_currency; ?>
</div>

<script type="text/javascript">
    function changeDropDown(dropdown){
        var location = dropdown.options[dropdown.selectedIndex].value;
        //jQuery('form#form_sort').get(0).setAttribute('action', '<?php echo $this->ordering_url;?>'+location);
        //jQuery('form#form_sort').submit();
    }
</script>

<script type="text/javascript">

var bookingModalFormUrl = '<?php echo JURI::base()?>index.php?option=com_sfs&view=booking&layout=form&tmpl=component';

jQuery(function(){
    var searchMoreURL = '<?php echo JRoute::_("index.php?rooms=1&date_start=".JRequest::getVar('date_start')."&date_end=".JRequest::getVar('date_end')."&option=com_sfs&view=search&tmpl=component&ws_only=1&Itemid=".JRequest::getInt('Itemid'))?>';
    var searchMoreURL2 = '<?php echo JRoute::_("index.php?rooms=1&date_start=".JRequest::getVar('date_start')."&date_end=".JRequest::getVar('date_end')."&option=com_sfs&view=search&tmpl=component&ws_only=1&ws_map=1&Itemid=".JRequest::getInt('Itemid'))?>';
    var informationUrl = '<?php echo JURI::base()?>index.php?option=com_sfs&view=hoteldetail&tmpl=component';
    var resultTable = jQuery('#search-result');
    var body = jQuery('body');
    var total_avalable_rooms = <?php echo $total_available_rooms?>;
    var xx = <?php echo (int)$airline->partner_limit_for_extra_search;?>;

    // external search
    var extendSearch = function(){
        jQuery('#search-ws-result').show();
        jQuery("#noHotel").hide();
        jQuery('#search_priority_div').hide();
        jQuery.ajax({
            url: searchMoreURL,
            dataType: 'html',
            success: function(html){
                //jQuery('#search-result-head').after(html);
                if(jQuery("#noAvailability").length)
                {
                    jQuery("#noAvailability").before(html);
                }
                else
                {
                    jQuery('#search-ws-result').parent().before(html);
                    jQuery('table#search-result').find("button.toggle-open:first").click();

                }
                //jQuery('#search-ws-result').parent().remove();
            },
            complete: function(){
                jQuery.ajax({
                    url: searchMoreURL2,
                    dataType: 'json',
                    success: function (data) {
                        if(data.length == 0){
                            jQuery("#noHotel").show();
                            jQuery('#search_priority_null').show();
                        }else{
                            jQuery('#search_priority_null').hide();
                        }

                        jQuery('body').trigger('ws-external-hotels-finished', [data]);
                        jQuery('#search-ws-result').hide();
                        checkDisplaySearchMore();
                        limit_ws_hotel();
                        update_all_estimate();
                        jQuery('.id-filter.sfs-form').show();
                    }
                });
            }
        });
    };

    // Search Priority
    var count_times_priority = <?php echo SfsWs::getAirportNumberOfPriorities()?>;
    var priority = 1;
    <?php if ($wsCached){?>
    priority = 2;
    if(total_avalable_rooms == 0)
    {
        jQuery('table#search-result').find("button.toggle-open:first").click();
    }
    <?php }?>
    var temp_searchMoreURL = searchMoreURL;
    var temp_searchMoreURL2 = searchMoreURL2;
    jQuery('#search_priority_button').on('click', function()
    {
        searchMoreURL = temp_searchMoreURL+'&priority='+priority;
        searchMoreURL2 = temp_searchMoreURL2+'&priority='+priority;
        extendSearch();
        jQuery('#search-ws-result').show();
    });
    var checkDisplaySearchMore = function(){
        if (priority == count_times_priority)
        {
            jQuery('#search_priority_div').hide();
        }else
        {
            jQuery('#search_priority_div').show();
            priority++;
        }
    };

    <?php if(!$wsCached) : ?>

    if(total_avalable_rooms>=xx) {
        if (xx == 0)
        {
            extendSearch();
            jQuery('table#search-result').find("button.toggle-open:first").click();
        }
        else
        {
            jQuery('#extend-search-result-wrap').show();
            jQuery('#extend-search-result').on('click', function(){
                extendSearch();
                jQuery('#ws_only').val('1');
                jQuery('#extend-search-result-wrap').hide();
            });
        }
    } else {
        extendSearch();
    }

    <?php endif;?>


    body.on('click', '#search-result input.mealplan, #search-result-popout input.mealplan', function(){
        var tr = jQuery(this).closest('tr');
        var val = jQuery(this).attr('value');
        tr.find('.mealplan-' + val).toggle(jQuery(this).is(':checked'));
    });

    //ws input behavior

    body.on('keyup', '#search-result input.ws_num_room_input, #search-result-popout input.ws_num_room_input', function(){
        var val = parseInt(jQuery(this).val());
        var itemID = jQuery(this).attr('data-item-id');
        var $all = jQuery(this).closest('table').find('.ws_num_room_input');
        if(val || !jQuery.isNumeric(val)) {
            if(parseInt(jQuery(this).val()) != 1)
            {
                jQuery(this).parent().find("span").fadeIn(500).delay(5000).fadeOut(500);
            }
            $all.val(0);
            jQuery(this).val(1);

        }
    });

    body.on('click', '#search-result tr .toggle-room, #search-result tr .toggle-open, #search-result tr .toggle-close, #search-result-popout tr .toggle-room, #search-result-popout tr .toggle-open, #search-result-popout tr .toggle-close', function(e){
        var tr = jQuery(this).closest('tr'),
            id = tr.attr('data-id'),
            trItems = jQuery(this).closest('table').find('.ws-room-item-' + id),
            bookNow = jQuery(this).closest('.search-result').find('.ws-book-now-' + id);

        tr.toggleClass('ws-room-toggle-opened');
        var isOpened = tr.hasClass('ws-room-toggle-opened');

        trItems.toggleClass('ws-room-item-opened', isOpened);
        bookNow.toggleClass('ws-book-now-opened', isOpened);

        return false;
    });

    body.on("click", "#search-result .button-information, #search-result-popout .button-information", function(e){
        e.stopPropagation();
        e.preventDefault();
        var hotelid = jQuery(this).attr("rel");
        var url = informationUrl+'&id='+hotelid;
        SqueezeBox.open(url, {handler: 'iframe', size: {x: 800, y: 560} });
    });
    var requestRunning = false;
    body.on('click', '#search-result button.bookingbutton, #search-result-popout button.bookingbutton', function(e) {
        if (requestRunning) {
            return;
        }

        requestRunning = true;
        e.stopPropagation();
        e.preventDefault();
        var el = jQuery(this);
        var table = jQuery(this).closest('.search-result');

        var $form = jQuery(this).closest('tbody.sfs-form');

        var roomid = parseInt($form.find('[name="room_id"]').val()) || 0;
        var association_id = parseInt($form.find('[name="association_id"]').val()) || 0;

        var url = bookingModalFormUrl+'&roomid='+roomid;

        if(association_id > 0){
            url = url+'&association_id='+association_id;
        }

        var sdRoom = $form.find('[name="sd_room"]').val() || 0;
        var tRoom = $form.find('[name="t_room"]').val() || 0;

        url = url+'&sdroom='+sdRoom;
        url = url+'&troom='+tRoom;

        var sRoom = $form.find('[name="s_room"]').val() || 0;
        var qRoom = $form.find('[name="q_room"]').val() || 0;

        if( sRoom ) {
            url = url+'&sroom='+sRoom;
        }
        if( qRoom ) {
            url = url+'&qroom='+qRoom;
        }


        var hasBreakfast = $form.find('.breakfast'+roomid).is(':checked');

        if(hasBreakfast)
        {
            url = url+'&breakfast=1';
        }

        var hasLunch = $form.find('.lunch'+roomid).is(':checked');

        if(hasLunch){
            url = url+'&lunch=1';
        }

        var hasDinner = $form.find('.dinner'+roomid).is(':checked');
        if(hasDinner){
            course = $form.find('input[name=course]:checked').val();
            url = url+'&dinner=1&course='+course;
        }

        var date_start = $form.find('[name=date_start]').val();
        var date_end =  $form.find('[name=date_end]').val();
        var rooms = $form.find('[name=rooms]').val();

        url = url+'&date_start='+date_start+'&date_end='+date_end+'&rooms='+rooms;

        var percent_release_policy = $form.find('[name=percent_release_policy]').val();
        url = url+'&percent_release_policy='+percent_release_policy+'<?php if(JRequest::getString('pass_issue_hotel')){
        echo '&pass_issue_hotel='.JRequest::getString('pass_issue_hotel').'&tb_share_room='.JRequest::getString('tb_share_room').'&room_book='.JRequest::getString('room_book');
    } ?><?php if(JRequest::getString('pass_detail_hotel')){
        echo '&pass_detail_hotel='.JRequest::getString('pass_detail_hotel');
    } ?>'

        // add ws booking
        var $inputs = $form.find('input.ws_num_room_input');
        var isWS = $inputs.length > 0,
            wsRoomType, wsRoomNumber,
            wsPost = { rooms : []};
        $inputs.each(function(){
            var val = parseInt(jQuery(this).val());
            if(val) {
                isWS = true;
                wsRoomType =  jQuery(this).attr('data-ws');
                wsRoomNumber = val;
                wsPost.rooms.push({
                    roomType: wsRoomType,
                    number: wsRoomNumber
                });
            }
        });

        if(isWS) {
            if(!parseInt(wsRoomNumber)) {
                alert('Please book a room');
                return;
            }
        }
        jQuery("#spinner"+roomid).show();

        if(isWS) {
            var wsPreBookUrl = '<?php echo JURI::base()?>index.php?option=com_sfs&task=booking.wsPreBookSession&tmpl=component';
            jQuery.ajax({
                url: wsPreBookUrl,
                type: 'post',
                dataType: 'json',
                data: wsPost,
                success: function(json) {
                    if(json.error) {
                        alert(json.message);
                    } else {
                        url += '&ws_prebooking_token=' + json.prebooking_token;
                        SqueezeBox.open(url, {handler: 'iframe', size: {x: 800, y: 400} });
                    }
                },
                complete: function() {
                    jQuery("#spinner"+roomid).hide();
                    requestRunning = false;
                },
                error: function(){
                    alert('Unknown error');
                }
            });
        } else {
            SqueezeBox.open(url, {handler: 'iframe', size: {x: 800, y: 400} });
            jQuery("#spinner"+roomid).hide();
            requestRunning = false;
        }

    });
    //Calculate Estimated Rooms && Estimated MealPlan
    body.on('keyup change', '#search-result input[name="sd_room"], #search-result input[name="s_room"], #search-result input[name="t_room"], #search-result input[name="q_room"], #search-result input.checkbox, #search-result input.mealplan, #search-result input[name="course"], #search-result input.ws_num_room_input', function(){
        update_all_estimate();
    });   

    body.on('click', '.breakfast_click', function(){
        var id = jQuery(this).attr('data-id');
        var mealtype = '';
        var array = [];
        var minth = 0;
        jQuery('.ws-room-item-' + id).addClass('ws-room-item-opened');
        jQuery('.ws-room-toggle-' + id).addClass('ws-room-toggle-opened')
        jQuery('.ws-book-now-' + id).show();

        var count_mealplan=0;
        jQuery('input[name=breakfast_click_'+id+']').each(function () {
            mealtype = jQuery(this).attr('mealtype');
            if(this.checked){
                jQuery('.'+mealtype+'_' + id).show();
                jQuery('.input_'+mealtype+'_' + id).attr('flag', 1);
                count_mealplan++;
            }else{
                jQuery('.'+mealtype+'_' + id).hide();
                jQuery('.input_'+mealtype+'_' + id).attr('flag', 0);
            }
        });

        if (count_mealplan==0) {
            jQuery('.mealplan_' + id).show();
            jQuery('input.ws_num_room_input').attr('flag', 1);
        }

        var $inputs_ws = jQuery("input.ws_num_room_input[data-id=\'"+id+"\']");
        jQuery.each($inputs_ws, function(){
            jQuery(this).val(0);

            if (count_mealplan==0) {
                array.push(jQuery(this).attr('data-minth'));
            }else {
                if (jQuery(this).attr('flag') == 1) {
                    array.push(jQuery(this).attr('data-minth'));
                }
            }
        });

        if(array.length > 0) {
            Array.prototype.min = function () {
                return Math.min.apply(null, this);
            };
            minth = array.min();
        }
        jQuery('input[data-minth='+minth+'][data-id='+id+'][flag=1]:first').val(1);
        update_all_estimate();
    });
    String.prototype.trim = function() {
        return this.replace(/^\s+|\s+$/g, "");
    };
    body.on('change', '#select_sort', function(){
        var value = parseInt(jQuery(this).val());

        var data_hidden = {};
        jQuery('input.hidden_sort').each(function() {
            var value = jQuery(this).val();
            var id = jQuery(this).attr('id');
            data_hidden[id] = value;
        });
        data_hidden = encodeURIComponent(JSON.stringify(data_hidden));

        var link = '<?php echo $this->ordering_url;?>'+value+'&tmpl=component&ws_only='+jQuery('#ws_only').val()+'&select_sort=1&data_hidden='+data_hidden;

        jQuery( "#search-result tbody.sfs-form" ).fadeOut( 1000);

        jQuery('#search-result tbody.sfs-form').promise().done(function(){
            jQuery('#search-result tbody.sfs-form').remove();

            jQuery.ajax({
                url: link,
                dataType: 'html',
                beforeSend: function(){
                    jQuery('#search-ws-result').show();
                },
                success: function(html){
                    html = html.trim();
                    var check = html.substring(0,6);
                    if (check == '<tbody') {                        
                        jQuery("#search-result .id-filter.sfs-form.form-vertical").remove();
                        jQuery('#search-result-head').after(html);
                        jQuery('#search-result tbody#noAvailability:first').remove();
                        jQuery('#search-result tbody#hotelsNoRoomLoading:first').remove();
                        //jQuery("#search-result tbody.sfs-form").hide();
                        jQuery("#search-result tbody.sfs-form").fadeIn(1000);
                        jQuery('.id-filter.sfs-form').show();
                    }else{
                        jQuery('#search_priority_null').show();
                    }
                },
                complete: function(){
                    jQuery('#search-ws-result').hide();
                    //checkDisplaySearchMore();
                    limit_ws_hotel();
                    update_all_estimate();
                    jQuery('.id-filter.sfs-form').show();
                }
            });
        });
    });

    jQuery(document).ready(function()
    {
        limit_ws_hotel();
        update_all_estimate();
    });

    //Function: limit book 1 room for the ws hotel
    function limit_ws_hotel()
    {
        var $inputs_ws = jQuery("#search-result input.ws_num_room_input, #search-result-popout input.ws_num_room_input");
        jQuery.each($inputs_ws, function(){
            var val = parseInt(jQuery(this).val());
            if(val) {
                jQuery(this).val(1);
            }
        });
    }

    function update_all_estimate()
    {
        jQuery.each(jQuery("#search-result tbody.sfs-form"), function () {
            var $form = jQuery(this);
            if($form.find("input.ws_num_room_input").length )
            {
                calculateEstimatedRooms($form, 1);
                calculateEstimatedTransport($form, 1);
                updateTotalCharge($form, 1);
            }
            else
            {
                calculateEstimatedRooms($form, 0);
                calculateEstimatedMealplan($form);
                calculateEstimatedTransport($form, 0);
                updateTotalCharge($form, 0);
            }
        });

        jQuery('label#total_charge').each(function(index)
        {
            var value = jQuery(this).text();
            var id = jQuery(this).attr('data-hotel');

            if (jQuery( "input[name='total_sort["+id+"]']" ).length == 0)
            {
                jQuery('<input>').attr({
                    type: 'hidden',
                    value: value,
                    class: 'hidden_sort',
                    id: id,
                    name: 'total_sort['+id+']'
                }).appendTo('form#form_sort');
            }

            if(jQuery( ".hotel_id_"+id ).length > 1)
            {
                jQuery(".hotel_id_"+id+":last").remove();
            }
        });
    }

    function changeNeedwithAvailable($form){        
        var room_sd = parseInt($form.find('[name=sd_room]').val()),
            room_s = parseInt($form.find('[name=s_room]').val()),
            room_t = parseInt($form.find('[name=t_room]').val()),
            room_q = parseInt($form.find('[name=q_room]').val());
        var max_room_sd = parseInt($form.find('[name=sd_room_need]').val()),
            max_room_s = parseInt($form.find('[name=s_room_need]').val()),
            max_room_t = parseInt($form.find('[name=t_room_need]').val()),
            max_room_q = parseInt($form.find('[name=q_room_need]').val());
        //check num need with num available            
        room_sd>max_room_sd?room_sd=max_room_sd:room_sd;
        room_s>max_room_s?room_s=max_room_s:room_s;
        room_t>max_room_t?room_t=max_room_t:room_t;
        room_q>max_room_q?room_q=max_room_q:room_q; 
        var arrList = [room_s,room_sd,room_t,room_q]; 

        return arrList;
    }

    function calculateEstimatedRooms($form, ws)
    {        
        var total_estimated_rooms = 0;
        if(ws == 1)
        {
            var $rooms = $form.find("input.ws_num_room_input");
            jQuery.each($rooms, function(){
                total_estimated_rooms += parseInt(jQuery(this).attr("data-minth"))*jQuery(this).val();
            });
        }
        else
        {
            var s_room, sd_room, t_room, q_room,
                s_room_rate, sd_room_rate, t_room_rate, q_room_rate;
            
            var resultRoom = changeNeedwithAvailable($form);                                

            //Calculate number of each rooms
            $form.find('[name=s_room]').length?s_room =resultRoom[0]:s_room=0;
            $form.find('[name=sd_room]').length?sd_room =resultRoom[1]:sd_room=0;
            $form.find('[name=t_room]').length?t_room =resultRoom[2]:t_room=0;
            $form.find('[name=q_room]').length?q_room =resultRoom[3]:q_room=0;

            //Calculate rate of each rooms
            $form.find('[name=s_room_rate]').length?s_room_rate = parseFloat($form.find('[name=s_room_rate]').val()):s_room_rate=0;
            $form.find('[name=sd_room_rate]').length?sd_room_rate = parseFloat($form.find('[name=sd_room_rate]').val()):sd_room_rate=0;
            $form.find('[name=t_room_rate]').length?t_room_rate = parseFloat($form.find('[name=t_room_rate]').val()):t_room_rate=0;
            $form.find('[name=q_room_rate]').length?q_room_rate = parseFloat($form.find('[name=q_room_rate]').val()):q_room_rate=0;

            total_estimated_rooms = s_room*s_room_rate+sd_room*sd_room_rate+t_room*t_room_rate+q_room*q_room_rate;
        }
        $form.find("label#estimated_rooms").text(total_estimated_rooms.toFixed(2));
    }
    function calculateEstimatedMealplan($form)
    {
        var breakfast_price, lunch_price, dinner_price,estimated_mealplan,
            s_room, sd_room, t_room, q_room,
            total_people,total_room_book, total_room_specific,total_people_book, total_people_specific;

        total_room_specific = <?php echo $roomSpecific?>;
        total_people_specific = <?php echo $total_people?>;


        //Calculate total rooms
        $form.find('[name=s_room]').length?s_room = parseInt($form.find('[name=s_room]').val()):s_room=0;
        $form.find('[name=sd_room]').length?sd_room = parseInt($form.find('[name=sd_room]').val()):sd_room=0;
        $form.find('[name=t_room]').length?t_room = parseInt($form.find('[name=t_room]').val()):t_room=0;
        $form.find('[name=q_room]').length?q_room = parseInt($form.find('[name=q_room]').val()):q_room=0;
        total_room_book = s_room+sd_room+t_room+q_room;
        total_people_book = s_room+sd_room*2+t_room*3+q_room*4;

        if(total_room_specific == 0)
        {
            total_people = total_people_book;
        }
        else
        {
            if (total_room_book <= total_room_specific) {
                total_people = Math.min(total_people_book, total_people_specific);
            }
            else
            {

                for (var i = 1; i <= total_room_specific; i++) {
                    var rand_array = [];
                    if(s_room  != 0) rand_array.push(1);
                    if(sd_room != 0) rand_array.push(2);
                    if(t_room  != 0) rand_array.push(3);
                    if(q_room  != 0) rand_array.push(4);
                    var rand_room = rand_array[Math.floor(Math.random() * rand_array.length)];
                    switch (rand_room) {
                        case 1:
                            s_room -= 1;
                            total_people_book -= 1;
                            break;
                        case 2:
                            sd_room -= 1;
                            total_people_book -= 2;
                            break;
                        case 3:
                            t_room -= 1;
                            total_people_book -= 3;
                            break;
                        default :
                            q_room -= 1;
                            total_people_book -= 4;
                            break;
                    }
                }
                total_people = total_people_specific + total_people_book;
            }
        }

        breakfast_price = parseInt($form.find('[name=breakfast_price]').val());
        lunch_price = parseInt($form.find('[name=lunch_price]').val());

        estimated_mealplan = 0;
        if($form.find('[name=breakfast]').is(":checked"))
        {
            estimated_mealplan += total_people*breakfast_price;
        }
        if($form.find('[name=lunch]').is(":checked"))
        {
            estimated_mealplan += total_people*lunch_price;
        }
        if($form.find('[name=mealplan]').is(":checked"))
        {
            var course = parseInt($form.find('[name=course]:checked').val());
            switch (course)
            {
                case 1:
                    dinner_price = parseFloat($form.find('[name=course1]').val());
                    break;
                case 2:
                    dinner_price = parseFloat($form.find('[name=course2]').val());
                    break;
                default :
                    dinner_price = parseFloat($form.find('[name=course3]').val());
                    break;
            }
            estimated_mealplan += total_people*dinner_price;
        }
        $form.find("label#estimated_mealplan").text(estimated_mealplan.toFixed(2));
    }

    function calculateEstimatedTransport($form, ws)
    {
        var taxi_cost,total_room_book = 0, total_transport,
            s_room, sd_room, t_room, q_room;

        $form.find('#taxi_cost').length?taxi_cost = parseInt($form.find('#taxi_cost').val()):taxi_cost=0;

        if(ws == 1)
        {
            var $rooms = $form.find("input.ws_num_room_input");
            jQuery.each($rooms, function(){
                total_room_book += parseInt(jQuery(this).val());
            });
        }
        else
        {
            //Calculate number of each rooms
            $form.find('[name=s_room]').length?s_room = parseInt($form.find('[name=s_room]').val()):s_room=0;
            $form.find('[name=sd_room]').length?sd_room = parseInt($form.find('[name=sd_room]').val()):sd_room=0;
            $form.find('[name=t_room]').length?t_room = parseInt($form.find('[name=t_room]').val()):t_room=0;
            $form.find('[name=q_room]').length?q_room = parseInt($form.find('[name=q_room]').val()):q_room=0;
            total_room_book = s_room+sd_room+t_room+q_room;
        }
        total_transport = taxi_cost * total_room_book;


        $form.find("label#estimated_transport").text(total_transport.toFixed(2));
    }

    function updateTotalCharge($form, ws)
    {

        var estimated_rooms, estimated_mealplan, estimated_transport;
        estimated_rooms = parseFloat($form.find("label#estimated_rooms").text());
        if(ws == 1)
            estimated_mealplan = 0;
        else
            estimated_mealplan = parseFloat($form.find("label#estimated_mealplan").text());
        if($form.find("label#estimated_transport").length)
            estimated_transport = parseFloat($form.find("label#estimated_transport").text());
        else
            estimated_transport = 0;
        $form.find("label#total_charge").text((estimated_rooms+estimated_mealplan+estimated_transport).toFixed(2));
    }

    jQuery("#checkAll").on('click',function(){
        if(jQuery(this).is(":checked"))
        {
            jQuery(':checkbox[name^="inviteHotel"]').each(function(){
                this.checked=true;
            });
        }
        else
        {
            jQuery(':checkbox[name^="inviteHotel"]').each(function(){
                this.checked=false;
            });
        }
    });

    jQuery('#sendRequestLoadingRooms').on("click", function(){
        var button = jQuery(this);
        var hotels = new Array();
        jQuery(':checkbox[name^="inviteHotel"]:checked').each(function(){
            jQuery(this).parent("span").children('.waitInvited').html('<span class="waiting"></span>');
            hotels.push(jQuery(this).val());
        });
        
        jQuery.ajax({
            url:"<?php echo JURI::base().'index.php?option=com_sfs&task=ajax.inviteHotelsLoadingRoom&format=raw'; ?>",
            type:"POST",
            dataType: 'text',
            data:{hotels:hotels},
            success:function(response){

                if(response == 1){
                    jQuery(':checkbox[name^="inviteHotel"]:checked').each(function(){
                        jQuery(".waitInvited").html('');
                        var span_track = jQuery(this).parent().find("span.tracking");
                        span_track.html('<?php echo 'Invited at '.JFactory::getDate('now', 'UTC')->format("H:i", false, false);?>');
                    });
                    alert("Send request successfully!");                    
                    setTimeout(function(){
                        button.removeAttr("disabled");
                    },60000);
                }
                else
                {
                    button.removeAttr("disabled");
                    jQuery(".waitInvited").html('');   
                    alert("Failed!");   

                }
            }
        })
    })
});

//lchung
jQuery(function($){
	
	$("body").on("click", "img.remove_filter" , function() {
		var id = $(this).attr('id')
		var columns = $('#hotel_name_fin');
		if (id ){
			$.trim( $('#'+id).val("") );
			seach( $, "" );
		}
	});
	
	$('.DataTable_input').after('<img href=\"javascript:void(0);\" class=\"remove_filter\" id=hotel_name columns=1 src="<?php echo JURI::base()?>components/com_sfs/assets/images/image_close.png"/>');
	
	$("input.DataTable_input").on("keyup", function() {
		var value = $.trim($(this).val()).toLowerCase();
		seach( $, value );
	});

});
 
function seach( $, value ) {
	$("table#search-result tbody.id-filter").each(function(index) {
		$row = $(this);
		
		var id = $.trim( $row.find(".hotel-name").text() ).toLowerCase();

		if ( id.search( value ) == -1 && value != "" ){
			$(this).hide();
		}
		else {
			$(this).show();
		}
	});
}

//End lchung
</script>

<!-- Begin show results -->
<table class="search-result" id="search-result" style="width:100%;">
    <thead id="search-result-head">
    <tr>
        <th id="hotel_name_fin" class="main-head" style ="width: 25%; position:relative;"><input class="DataTable_input" id="hotel_name" type="text" placeholder="Hotel name" /></th>
        <th class="main-head" style ="width: 15%;">Mealplan</th>
        <th class="main-head">
            <table width="100%">
                <thead>
                <tr>
                    <th class="main-head">Rooms</th>
                    <th class="main-head">price</th>
                    <th class="main-head">available</th>
                    <th class="main-head">needed</th>
                </tr>
                </thead>
            </table>
        </th>
        <th class="main-head" style ="width: 20%;"></th>
    </tr>
    </thead>

    <?php echo $this->loadTemplate('results_body')?>



    <tbody>
    <tr id="search-ws-result" style="display: none">
        <td colspan="7">
            <span class="hotel-search-loader-title"><?php echo JTEXT::_('COM_SFS_ROOMLOADING_SEARCHING')?></span>
            <br/>
            <span class="hotel-search-loader"></span>
        </td>
    </tr>
    <tr id="extend-search-result-wrap" style="display: none">
        <td colspan="7">
            <button id="extend-search-result" class="small-button" style="width: 100%">Need more result? Click here to extend the search area</button>
        </td>
    </tr>
    </tbody>

    <?php

    if(SfsWs::getAirportNumberOfPriorities() > 1 && $wsCached) {
        ?>
        <script>
            jQuery(function(){
                jQuery('#search_priority_div').show();
            });
        </script>
    <?php
    }
    ?>
    <tbody id="search_priority_null" style="display: none">
    <tr>
        <td colspan="7">
            <p style="width:100%"><strong>No data</strong></p>
        </td>
    </tr>
    </tbody>

    <tbody id="search_priority_div" style="display: none">
    <!--        <tr>-->
    <!--            <td colspan="7">-->
    <!--                <span class="hotel-search-loader-title">-->
    <!--                    --><?php //echo JTEXT::_('COM_SFS_ROOMLOADING_SEARCHING')?><!--</span>-->
    <!--                <br/>-->
    <!--                <span class="hotel-search-loader"></span>-->
    <!--            </td>-->
    <!--        </tr>-->
    <tr>
        <td colspan="7">
            <button id="search_priority_button" class="small-button" style="width:100%">Need more result? Click here to extend the search area
            </button>
        </td>
    </tr>
    </tbody>

    <?php echo $this->loadTemplate('invite_load_rooms')?>
</table>
<!-- End show results-->



<div class="main-bottom-block clearfix">
    <a href="<?php echo JRoute::_('index.php?option=com_sfs&view=handler&layout=search&Itemid='.JRequest::getInt('Itemid'));?>" class="btn orange sm pull-left">Back</a>
    <a href="<?php echo JRoute::_('index.php?option=com_sfs&view=dashboard&Itemid='.JRequest::getInt('Itemid'))?>"  class="btn orange sm pull-right">Close</a>
</div>

