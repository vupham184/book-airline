<?php
defined('_JEXEC') or die;
JHtml::_('behavior.keepalive');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.modal');
$app = JFactory::getApplication();
$airline = SFactory::getAirline();
$airplusparams= $airline->airplusparams;
$enableSMS = (int)$airline->params['send_sms_message'];
$sender_title = $airline->params['sender_title'];
$sess 	 = JFactory::getSession();
if( !$sess->has('single_voucher') )
{
    $tmpVoucher = SfsHelper::createRandomString(2).$seats;
    $tmpVoucher = JString::strtoupper($tmpVoucher);
    $sess->set('single_voucher',$tmpVoucher);
}
$voucherCode = $sess->get('single_voucher');
if($this->reservation->q_room != 0)
{
    $room_type = " Quadra room";
}
if($this->reservation->t_room != 0)
{
    $room_type = "Triple room";
}
if($this->reservation->sd_room != 0)
{
    $room_type = "Double room";
}
if($this->reservation->q_room != 0)
{
    $room_type = "Single room";
}

$params = JComponentHelper::getParams('com_sfs');
$url_code = SfsUtil::getRandomString(5);

$textSMS = "View your booking details on ".JUri::root()."mobile/?code=".$url_code." :".$this->reservation->name." ".$this->reservation->city ." :". $this->reservation->room_date." code:".$voucherCode." :1 night"." :1 ".$room_type;
if( ! isset($this->reservation) )
{
	return;
}

$closeUrl = JURI::base().'index.php?option=com_sfs&view=search&Itemid=119&rooms=1&date_start='.$this->reservation->blockdate.'&date_end=';
$closeUrl .= SfsHelperDate::getNextDate('Y-m-d', $this->reservation->blockdate);

$printUrl  = $closeUrl.'&reservation_id='.$this->reservation->id.'&print=1';

$wsPrintUrl = $closeUrl.'&reservation_id='. $this->reservation->id . '&singlevoucherpreview=1';
$airport_current = $airline->getCurrentAirport();
$distance = SfsHelper::getDistanceHotelAirport($this->reservation->hotel_id, $airport_current->id);
$ap_taxi_value = SfsHelper::calculateTaxiValue($this->reservation->hotel_id);
$ap_meal_first_value = $airplusparams['meal_first_limit'];
$ap_meal_second_value = $airplusparams['meal_second_limit'];
?>
<style>
<!--
body.contentpane,iframe{
	border:none !important;
	padding: 0 !important;
	margin: 0 !important;	
}
.sfs-white-wrapper{
	background:#FFFFFF;
	padding:20px;
	overflow:hidden;
}
table, th, td{
    border: 5px solid #82adf1;
}
table.match-passengers,table.match-passengers th, table.match-passengers td{
    border: 0;
}

.sfs-white-wrapper{
    background:#FFFFFF;
    padding:20px;
    overflow:hidden;
}
#wsRequestSpinner{
	width: 16px;
	height: 16px;
	display: block;
	margin-right: 5px;
}
.singlevoucher-passenger-title{
	width: 50px !important;
}
.ws-booking-loading{
	text-align: center;
	font-weight: bold;
}
.ws-booking-spinner{
	margin: 0 auto;
	display: block;
}
#sbox-window {
    left: 0px !important;
    top:0px !important;
}
-->
#voucherPrintForm input[type="text"]{
    height: 30px !important;
}

select#returnflightdate {
    height: 30px !important;
}

#ws-booking-loading{
	background-color: #fff;
	display: none;
	left: 0;
	position: absolute;
	text-align: center;
	top: 0;
	width: 100%;
}
#system-message-container{
	display:none;
}
</style>

<link rel="stylesheet" href="<?php echo JURI::base() ?>/templates/<?php echo $app->getTemplate();?>/css/checkbox.css" type="text/css" />
<script src="<?php echo JURI::base() ?>/templates/<?php echo $app->getTemplate();?>/js/checkbox.js" type="text/javascript"></script>
<script type="text/javascript">	
<!--
jQuery(function ($) {
    $(document).ready(function() {
        $(".ui.checkbox").checkbox();
        $("#return_taxi").html(getDateFormatted($("#returnflightdate").val()));
        $("#return_meal_first").html(getDateFormatted($("#returnflightdate").val()));
        $("#return_meal_second").html(getDateFormatted($("#returnflightdate").val()));
    });
    $("#ap-meal-first").on("change", function(){
        if($(this).is(":checked")){
            $("#ap-meal-second").prop('checked', false);
        }
    });
    $("#ap-meal-second").on("change", function(){
        if($(this).is(":checked")){
            $("#ap-meal-first").prop('checked', false);
        }
    });
    $("#returnflightdate").on("change", function(){
        $("#return_taxi").html(getDateFormatted($("#returnflightdate").val()));
        $("#return_meal_first").html(getDateFormatted($("#returnflightdate").val()));
        $("#return_meal_second").html(getDateFormatted($("#returnflightdate").val()));
    });
	iframeModalAutoSize();

    function getDateFormatted(str){
        var date = new Date(str);
        var day = date.getDate();
        var month = date.getMonth();
        var mon = new Array();
        mon[0] = "January";
        mon[1] = "February";
        mon[2] = "March";
        mon[3] = "April";
        mon[4] = "May";
        mon[5] = "June";
        mon[6] = "July";
        mon[7] = "August";
        mon[8] = "September";
        mon[9] = "October";
        mon[10] = "November";
        mon[11] = "December";
        month = mon[month];
        var year = date.getFullYear();
        return day + " " + month + " " + year;
    }

	
});
window.addEvent('domready', function() {

	var isWS = <?php echo $this->isWS ? 'true' : 'false'?>;
	var wsNumAdults = <?php echo (int)$wsRoomType->NumAdultsPerRoom?>;
	
	var printLink = '<?php echo $printUrl;?>';		
	var closeLink = "<?php echo $closeUrl?>";
	var wsPrintLink = "<?php echo $wsPrintUrl?>";
	
	var printVoucherForm  = document.id('voucherPrintForm');

	var validateWSPassengers = function(){
		var ok = true;
		for(var i =1; i<= wsNumAdults; i++) {
			var title = $('title' + i).getSelected().get('value'),
				firstname = $('firstname' + i).get('value'),
				lastname = $('lastname' + i).get('value');

			if(!title) {
				$('title' + i).setStyle('border-color','#ff0000');
				$('title' + i).setStyle('background-color','#ffdddd');
				ok = false;
			} else {
				$('title' + i).setStyle('border-color','');
				$('title' + i).setStyle('background-color','');
			}

			if(!firstname) {
				$('firstname' + i).setStyle('border-color','#ff0000');
				$('firstname' + i).setStyle('background-color','#ffdddd');
				ok = false;
			} else {
				$('firstname' + i).setStyle('border-color','');
				$('firstname' + i).setStyle('background-color','');
			}

			if(!lastname) {
				$('lastname' + i).setStyle('border-color','#ff0000');
				$('lastname' + i).setStyle('background-color','#ffdddd');
				ok = false;
			} else {
				$('lastname' + i).setStyle('border-color','');
				$('lastname' + i).setStyle('background-color','');
			}
		}
		return ok;
	};

	var validateFlightCode = function(){
		var ok = true;
		var flight_code = $('flight_code').get('value');
		if(!flight_code) {
			$('flight_code').setStyle('border-color','#ff0000');
			$('flight_code').setStyle('background-color','#ffdddd');
			ok = false;
		} else {
			$('flight_code').setStyle('border-color','#909bb1');
			$('flight_code').setStyle('background-color','#ffffff');
		}
		var iata_stranged_code = $('iata_stranged_code').get('value');
		if(!iata_stranged_code) {
			$('iata_stranged_code').setStyle('border-color','#ff0000');
			$('iata_stranged_code').setStyle('background-color','#ffdddd');
			ok = false;
		} else {
			$('iata_stranged_code').setStyle('border-color','#909bb1');
			$('iata_stranged_code').setStyle('background-color','#ffffff');
		}

		return ok;
	};
	
	var printVoucherFormRequest = new Form.Request(printVoucherForm, $('testre'),  {					
	    requestOptions: {
	    	useSpinner: false,
	    	evalScripts: true
	    },		   
	    onComplete: function(responseText){
//            jQuery("#voucherPrintForm").html(responseText);
	    	$('printRequestSpinner') && $('printRequestSpinner').removeClass('ajax-Spinner');
	    	$('emailRequestSpinner') && $('emailRequestSpinner').removeClass('ajax-Spinner');

	    	jQuery('#ws-booking-loading').hide();
			jQuery('#ws-booking-buttons').show();

	    	// show error if has
		    if(jQuery(responseText).find('.uk-alert-danger').length) {
				return;
			}

		    if(printVoucherForm.printtype.value =='print'){
		   		$('sfs-wrapper').destroy();
		   		window.parent.location.href=printLink;
		   		window.parent.SqueezeBox.close();
		   	} else if(printVoucherForm.printtype.value =='email'){
			   	window.parent.location.href=closeLink;
			}else if(printVoucherForm.printtype.value =='sendSMS'){
                window.parent.location.href=closeLink;
            }
            else if(printVoucherForm.printtype.value =='ws') {
				window.top.location.href = wsPrintLink;
			}
	    },
	    resetForm : false				    
	});
   	
	$('printRequest') && $('printRequest').addEvent('click', function(e){
		e.stop();

		var ok = validateFlightCode();

		if(isWS) {
			ok = validateWSPassengers() && ok;
		}
		
		if(ok) {		
			$('printRequestSpinner').addClass('ajax-Spinner');
			printVoucherForm.printtype.value = 'print';
			printVoucherFormRequest.setTarget($('testre'));
			printVoucherFormRequest.send();
		}
	});	

	$('wsRequest') && $('wsRequest').addEvent('click', function(e){
		e.stop();

		var ok = validateFlightCode();

		if(isWS) {
			ok = validateWSPassengers() && ok;
		}
		
		if(ok) {
			jQuery('#ws-booking-loading').show();
			jQuery('#ws-booking-buttons').hide();
			printVoucherForm.printtype.value = 'ws';
			printVoucherFormRequest.setTarget($('testre'));
			printVoucherFormRequest.send();

			iframeModalAutoSize();
		}
	});	
	
	$('emailRequest') && $('emailRequest').addEvent('click', function(e){
		e.stop();	

		var ok = validateFlightCode(),
		    emailValue = $('email').get('value'),
		    regex=/^[a-zA-Z0-9._-]+(\+[a-zA-Z0-9._-]+)*@([a-zA-Z0-9.-]+\.)+[a-zA-Z0-9.-]{2,4}$/;

		if(!regex.test(emailValue)) {
			$('email').setStyle('border-color','#ff0000');
			$('email').setStyle('background-color','#ffdddd');
			ok = false;
		} else {
			$('email').setStyle('border-color','#909bb1');
			$('email').setStyle('background-color','#ffffff');
		}

		if(isWS) {
			ok = validateWSPassengers() && ok;
		}
		
		if(ok) {			
			$('emailRequestSpinner').addClass('ajax-Spinner');		
			printVoucherForm.printtype.value = 'email';
			printVoucherFormRequest.setTarget($('testre'));
			printVoucherFormRequest.send();
		}			
					
	});	

	
	$('closeVoucherPrintForm').addEvent('click', function(e){
		e.stop();		
		window.parent.location.href="<?php echo $closeUrl?>";	
		window.parent.SqueezeBox.close();
	});

    jQuery("body").on("keyup change", ".match-passenger-mobile-ext,.match-passenger-mobile", function(){
        var mobile_ext = jQuery(".match-passenger-mobile-ext").val(),
            mobile = jQuery(".match-passenger-mobile").val();
        jQuery("#phone_sms").val('+'+mobile_ext+mobile);
    });

    jQuery("body").on("click","#sendSMS", function(e){
        var ok = validateFlightCode(),
            regex = /\(?([0-9]{3})\)?([ .-]?)([0-9]{3})\2([0-9]{4})/,
            phone_sms = $('phone_sms').get('value');
			//first_name = $('first_name').get('value'),
			//last_name = $('last_name').get('value');
			var fields = jQuery("#voucherPrintForm").serializeArray();
			///var fields = $( ":input" ).serializeArray();
        if(!regex.test(phone_sms)) {
            $('phone_sms').setStyle('border-color','#ff0000');
            $('phone_sms').setStyle('background-color','#ffdddd');
            ok = false;
        }
        if(ok) {
            jQuery.ajax({
                url:"<?php echo JURI::base().'index.php?option=com_sfs&task=ajax.sendMessage&format=raw'; ?>",
                type:"POST",
                data:{
                    phone: phone_sms,
					text: "<?php echo $textSMS;?>",
                    fields: fields,
					code:"<?php echo $url_code;?>",
                    sender: "<?php echo $sender_title;?>"
                },
                dataType: 'text',
                success:function(response){
                    if(response.charAt(0) == 1){
                        $('sendSMSSpinner').addClass('ajax-Spinner');
                        printVoucherForm.printtype.value = 'sendSMS';
                        printVoucherFormRequest.setTarget($('testre'));
                        printVoucherFormRequest.send();
//                            alert("Send SMS successfully!");
                    }
                    else
                    {
                        alert("Failed!");
                    }
                }
            })
        }
    });
});		

-->
</script>
<?php
$user = &JFactory::getUser();
$enable_extra_data_on_voucher = 0;
$extra_data_on_voucher_title = '';
if( SFSAccess::isAirline($user) ) {
	$airline = SFactory::getAirline();
	if(isset($airline->params["enable_extra_data_on_voucher"]))
	{
		$enable_extra_data_on_voucher = (int)$airline->params["enable_extra_data_on_voucher"];
		$extra_data_on_voucher_title = $airline->params["extra_data_on_voucher_title"];
	}
}
?>

<div id="sfs-wrapper" class="match">
<div id="sfs-voucher-print-form">
<form id="voucherPrintForm" name="voucherPrintForm" action="<?php echo JRoute::_('index.php')?>" method="post">
    <table cellpadding="0" cellspacing="0" border="0" width="100%">
        <tr valign="top">
            <td width="50%" style="padding-left: 10px;vertical-align: top;">
                <h2>Accommodation</h2>
            </td>
            <td width="50%" style="padding-left: 10px;vertical-align: top;">
                <h2>Add additional services</h2>
            </td>
        </tr>
        <tr valign="top">
            <td width="50%" style="padding-left: 10px;padding-top: 15px">
                <div class="sfs-row">
                    <div class="sfs-column-left" style="width:140px;">
                        Flight number *
                    </div>
                    <input type="text" name="flight_code" id="flight_code" value="" style="width:130px;" />
                </div>
                <div class="sfs-row">
                    <div class="sfs-column-left" style="width:140px;">
                        IATA stranded code *
                    </div>
                    <input type="text" name="iata_stranged_code" id="iata_stranged_code" value="" style="width:130px;" />
                    <a href="<?php echo JRoute::_('index.php?option=com_sfs&view=article&id='.$this->params->get('article_delay_code').'&tmpl=component&Itemid='.JRequest::getInt('Itemid'));?>" rel="{handler: 'iframe', size: {x: 350, y: 270}}" class="modal button" style="float:none;text-decoration:none;"><?php echo SfsHelper::htmlTooltip('flight_delay_code', 'help-icon', 'airline');?></a>
                </div>
            </td>
            <td width="50%" style="padding-left: 10px;vertical-align: top;" <?php  if( isset($airline->params['show_vouchercomment']) && (int)$airline->params['show_vouchercomment'] == 1 ) echo 'rowspan="2"'?>>
                <div><h3>Transfer to Hotel</h3></div>
                <div style="margin-top: 15px; font-size: 13px;">
                    <?php if((int)$airplusparams['taxi_enabled'] == 1):?>
                        <div style="width: 20%; float: left">
                            <div class="ui toggle checkbox">
                                <input name="ap-taxi" type="checkbox" id="ap-taxi" >
                            </div>
                        </div>
                        <div style="width: 80%; float: left">
                            Distance to airport <?php echo $distance->distance." ".$distance->distance_unit;?><br/>
                            Estimated taxi costs return trip <?php echo $ap_taxi_value?> Euro<br/>
                            Expiry date <span id="return_taxi"></span>
                        </div>
                    <?php else:?>
                        <div style="width: 20%; float: left">
                            <div class="ui toggle checkbox disabled">
                                <input name="ap-taxi" type="checkbox" id="ap-taxi" >
                            </div>
                        </div>
                        <div style="width: 80%; float: left; opacity: 0.35;">
                            Distance to airport <?php echo $distance->distance." ".$distance->distance_unit;?><br/>
                            Estimated taxi costs return trip <?php echo $ap_taxi_value?> Euro<br/>
                            Expiry date <span id="return_taxi"></span>
                        </div>
                    <?php endif;?>
                </div>
            </td>
        </tr>
        <?php if( isset($airline->params['show_vouchercomment']) && (int)$airline->params['show_vouchercomment'] == 1 ) :?>
        <tr valign="top">
            <td width="50%" style="padding-left: 10px;vertical-align: top;">
                <div class="sfs-white-wrapper sfs-voucher-print-box floatbox midmarginbottom" style="padding: 0">
                    <?php echo JText::_('COM_SFS_AIRLINE_FLIGHT_NUMBER_ADD_COMMENT');?>, max
                    <input style="color:red;font-size:12pt;font-style:italic;width:40px;border: 0px" readonly type="text" id='comment_length' name="comment_length" size="3" maxlength="3" value="500">
                    characters left<br />
                    <textarea name="comment" id="vouchercomment" style="width:98%;height:50px;border: 1px solid #909bb1"
                              onKeyDown="textCounter(this, 'comment_length', 500);"
                              onKeyUp="textCounter(this,'comment_length' ,500)"></textarea>
                    <script>
                        function textCounter(field,cnt, maxlimit)
                        {
                            var cntfield = document.getElementById(cnt)
                            if (field.value.length > maxlimit)
                                field.value = field.value.substring(0, maxlimit);
                            else
                                cntfield.value = maxlimit - field.value.length;
                        }
                    </script>
                </div>
            </td>
        </tr>
        <?php endif;?>
        <tr valign="top">
            <td width="50%" style="padding-left: 15px;vertical-align: top;">
                <div class="sfs-white-wrapper sfs-voucher-print-box floatbox midmarginbottom">
                    <div>Insert names</div>
                    <div class="fs-11" style="padding-top: 5px; line-height:13px;">When you insert names it will be easier for you to trace the passengers</div>

                    <div class="midmargintop">
                        <?php
                        $session = JFactory::getSession();
                        $rooms = json_decode($session->get("rooms_search"), true);
                        $adults = $rooms[0]['num_adults'];
                        $children = $rooms[0]['num_children'];
                        ?>

                        <table border="0" width="100%" class="match-passengers">
                            <thead>
                            <tr>
                                <th></th>
                                <th>First name</th>
                                <th>Last name</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            if($adults == 0):

                                $seats = 1;
                                if( $this->reservation->sd_room ){
                                    $seats = 2;
                                }
                                if( $this->reservation->t_room ){
                                    $seats = 3;
                                }
                                if( $this->reservation->q_room ){
                                    $seats = 4;
                                }
                                for ($i=0;$i<$seats; $i++) :?>
                                    <tr>
                                        <td>Passenger <?php echo $i+1;?></td>
                                        <td><input type="text" name="passengers[<?php echo $i?>][firstname]" id="firstname<?php echo $i+1;?>" class="passenger-input match-passenger-firstname"></td>
                                        <td><input type="text" name="passengers[<?php echo $i?>][lastname]" id="lastname<?php echo $i+1;?>" class="passenger-input match-passenger-lastname"></td>
                                        <input type="hidden" name="passengers[<?php echo $i?>][type]" value="0">
                                    </tr>
                                <?php endfor;?>
                                <input type="hidden" name="stranded_seats" value="<?php echo $seats?>" />
                            <?php else:
                                for ($i=0;$i<$adults+$children; $i++):
                                    if($i < $adults):
                                        ?>
                                        <tr>
                                            <td>Adult</td>
                                            <td><input type="text" name="passengers[<?php echo $i?>][firstname]" id="firstname<?php echo $i+1;?>" class="passenger-input match-passenger-firstname"></td>
                                            <td><input type="text" name="passengers[<?php echo $i?>][lastname]" id="lastname<?php echo $i+1;?>" class="passenger-input match-passenger-lastname"></td>
                                            <input type="hidden" name="passengers[<?php echo $i?>][type]" value="0">
                                        </tr>
                                    <?php else:?>
                                        <tr>
                                            <td>Child</td>
                                            <td><input type="text" name="passengers[<?php echo $i?>][firstname]" id="firstname<?php echo $i+1;?>" class="passenger-input match-passenger-firstname"></td>
                                            <td><input type="text" name="passengers[<?php echo $i?>][lastname]" id="lastname<?php echo $i+1;?>" class="passenger-input match-passenger-lastname"></td>
                                            <input type="hidden" name="passengers[<?php echo $i?>][type]" value="1">
                                        </tr>
                                    <?php endif;?>
                                <?php endfor;?>
                                <input type="hidden" name="stranded_seats" value="<?php echo $adults+$children?>" />
                            <?php endif;?>
                            </tbody>
                        </table>
                        <table border="0" width="100%" class="match-passengers">
                            <tr>
                                <td>Phone number</td>
                                <td colspan="2">
                                    country ext <input type="text" name="passenger_mobile_ext" class="match-passenger-mobile-ext" style="width: 40px">
                                    <input type="text" name="passenger_mobile" class="match-passenger-mobile">
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </td>
            <td width="50%" style="padding-left: 15px;vertical-align: top;" <?php if( isset($airline->params['enable_return_flight']) && (int)$airline->params['enable_return_flight'] == 1 ) echo 'rowspan="2"'; ?>>
                <div><h3>Meal plan voucher</h3></div>
                <div style="font-size: 13px;">
                    <?php if((int)$airplusparams['meal_enabled'] == 1):?>
                        <?php if($ap_meal_first_value && $ap_meal_first_value >0 ):?>
                        <div style="width: 20%; float: left;margin-top: 15px">
                            <div class="ui toggle checkbox">
                                <input name="ap-meal-first" type="checkbox" id="ap-meal-first">
                            </div>
                        </div>
                        <div style="width: 80%; float: left; margin-top: 15px">
                            Issue <?php echo $ap_meal_first_value?> Euro mealplan voucher per person <br/>
                            Expiry date <span id="return_meal_first"></span>
                        </div>
                        <?php endif;?>
                        <?php if($ap_meal_second_value && $ap_meal_second_value >0 ):?>
                        <div style="width: 20%; float: left; margin-top: 15px">
                            <div class="ui toggle checkbox">
                                <input name="ap-meal-second" type="checkbox" id="ap-meal-second">
                            </div>
                        </div>
                        <div style="width: 80%; float: left; margin-top: 15px">
                            Issue <?php echo $ap_meal_second_value?> Euro mealplan voucher per person <br/>
                            Expiry date <span id="return_meal_second"></span>
                        </div>
                        <?php endif;?>
                    <?php else:?>
                        <?php if($ap_meal_first_value && $ap_meal_first_value >0 ):?>
                        <div style="width: 20%; float: left; margin-top: 15px">
                            <div class="ui toggle checkbox disabled">
                                <input name="ap-meal-first" type="checkbox" id="ap-meal-first">
                            </div>
                        </div>
                        <div style="width: 80%; float: left; opacity: 0.35; margin-top: 15px">
                            Issue <?php echo $ap_meal_first_value?> Euro mealplan voucher per person <br/>
                            Expiry date <span id="return_meal_first"></span>
                        </div>
                        <?php endif;?>
                        <?php if($ap_meal_first_value && $ap_meal_first_value >0 ):?>
                        <div style="width: 20%; float: left; margin-top: 15px">
                            <div class="ui toggle checkbox disabled">
                                <input name="ap-meal-second" type="checkbox" id="ap-meal-second">
                            </div>
                        </div>
                        <div style="width: 80%; float: left; opacity: 0.35;">
                            Issue <?php echo $ap_meal_second_value?> Euro mealplan voucher per person <br/>
                            Expiry date <span id="return_meal_second"></span>
                        </div>
                        <?php endif;?>
                    <?php endif;?>
                </div>
            </td>
        </tr>
        <?php if( isset($airline->params['enable_return_flight']) && (int)$airline->params['enable_return_flight'] == 1 ) : ?>
        <tr valign="top">
            <td width="50%" style="padding-left: 15px;vertical-align: top;">
                <div class="sfs-white-wrapper sfs-voucher-print-box floatbox midmarginbottom" style="min-height:50px;">
                    <div class="sfs-row">
                        <div class="sfs-column-left" style="width:120px;">
                            Return flight number
                        </div>
                        <input type="text" name="returnflight" value="" style="width:130px;" />
                    </div>
                    <div class="sfs-row">
                        <div class="sfs-column-left" style="width:120px;">
                            Return flight date
                        </div>
                        <?php
                        $endDateList 	= SfsHelperDate::getSearchDate('end','class="inputbox"','returnflightdate');
                        echo $endDateList;
                        ?>
                    </div>
                </div>
            </td>
        </tr>
        <?php endif; ?>
        <tr valign="top">
            <td colspan="2">
                <div id="testre"></div>
                <div class="sixteen wide column">
                    <div class="sfs-white-wrapper sfs-voucher-print-box floatbox">
                        <div class="ui four column grid">
                            <div class="four column wide"></div>
                            <div class="four column wide right aligned">
                                <?php if($enableSMS):?>
                                    <div>
                                        <input type="text" name="phone_sms" id="phone_sms" style="width:160px;" />
                                    </div>
                                    <br/>
                                <?php endif;?>
                                <div>
                                    <input type="text" name="email" id="email" value="@" style="width:160px;" />
                                </div>
                                <br/>
                                <div class="preview-number" style="margin-top: 2px">
                                    <input type="text" name="voucher_code" value="<?php echo $voucherCode;?>" readonly="readonly" style="width:160px;" />
                                </div>
                            </div>
                            <div class="four column wide">
                                <?php if($enableSMS): ?>
                                    <div class="mid-button" >
                                        <button type="button" id="sendSMS" >
                                            Send Text Message
                                        </button>
                                    </div>
                                    <br/>
                                <?php endif;?>
                                <div class="mid-button" >
                                    <button type="submit" id="emailRequest" class="validate" >
                                        Email hotelvoucher
                                    </button>
                                </div>
                                <br/>
                                <div class="mid-button" style="position:relative;">
                                	<div id="ws-booking-loading" class="ws-booking-loading">
                                            <span class="ws-booking-spinner ajax-Spinner48"></span>
                                    </div>
                                    <button type="button" id="printRequest">
                                        Print hotelvoucher
                                    </button>
                                </div>
                            </div>
                            <div class="four column wide">
                                <br/>
                                <br/>
                                <div id="sendSMSSpinner" class="float-left"></div>
                                <br/>
                                <br/>
                                <div id="emailRequestSpinner" class="float-left"></div>
                                <br/>
                                <br/>
                                <br/>
                                <div id="printRequestSpinner" class="float-left"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="reservation_id" value="<?php echo $this->reservation->id?>" />
                <input type="hidden" name="printtype" value="" />
                <input type="hidden" name="option" value="com_sfs" />
                <input type="hidden" name="format" value="raw" />
                <input type="hidden" name="task" value="match.processPrintSingleVoucher" />
                <input type="hidden" name="url_code" value="<?php echo $url_code;?>" />
                <?php echo JHtml::_('form.token'); ?>
            </td>
        </tr>
        <?php if( !$this->voucher->taxi_voucher_id ) : ?>
        <tr>
            <td colspan="2">
                <div class="sixteen wide column">
                    <div class="sfs-white-wrapper sfs-voucher-print-box floatbox">
                        <div class="ui three column celled grid" style="box-shadow: none">
                            <div class="row" style="box-shadow: none">
                                <div class="four column wide" style="box-shadow: none">
                                    <button type="button" id="closeVoucherPrintForm" class="small-button" style="margin-top:10px;">Close</button>
                                </div>
                                <div class="four column wide" style="box-shadow: none">
                                </div>
                                <div class="four column wide" style="box-shadow: none">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </td>
        </tr>
        <?php endif;?>
    </table>
</form>
</div>
</div>    	   

<div id="voucherUpdateElement"></div> 	