
<?php header('Access-Control-Allow-Origin: *'); ?>
<?php
defined('_JEXEC') or die();
JHtml::_('behavior.keepalive');
JHtml::_('behavior.formvalidation');
$airline = SFactory::getAirline();
$enableSMS = (int)$airline->params['send_sms_message'];
$sender_title = $airline->params['sender_title'];

$textSMS = "View your booking details on ".JUri::root()."v/".$this->reservation->url_code." :".$this->hotel->name." ".$this->hotel->city." :". $this->reservation->room_date." code:".$this->voucher->code." :1 night"." :1 ".$this->wsRoomTypes[0]->Name;
$printUrl  = JURI::base().'index.php?option=com_sfs&view=voucher&tmpl=component&reprint=1';
$printUrl .= '&voucher_id='.$this->voucher->id;

$view_card_transferUrl  = JURI::base().'index.php?option=com_sfs&view=voucher&tmpl=component&reprint=1';
$view_card_transferUrl .= '&voucher_id='.$this->voucher->id . '&pvcard=cardtransfer';

$view_card_mealplanUrl  = JURI::base().'index.php?option=com_sfs&view=voucher&tmpl=component&reprint=1';
$view_card_mealplanUrl .= '&voucher_id='.$this->voucher->id . '&pvcard=cardmealplan';

$app = JFactory::getApplication();
$airline = SFactory::getAirline();
$airport_current = $airline->getCurrentAirport();
$airplusparams= $airline->airplusparams;

$airline_current = SAirline::getInstance()->getCurrentAirport();
$time_zone = $airline_current->time_zone;
		
$distance = SfsHelper::getDistanceHotelAirport($this->voucher->hotel_id, $airport_current->id);
$ap_taxi_value = SfsWs::getHotelDistance($this->reservation->hotel_id, $airplusparams['taxi_fee']);
$ap_meal_first_value = $airplusparams['meal_first_limit'];
$ap_meal_second_value = $airplusparams['meal_second_limit'];
if($this->voucher->return_flight_number){
    $end_date = SfsHelperDate::getDate($this->voucher->return_flight_date,'Y-m-d', $time_zone);
}else{
    $end_date = SfsHelperDate::getNextDate('Y-m-d',$this->reservation->blockdate);
}
$expired_date = JHTML::_('date', $end_date , JText::_('DATE_FORMAT_LC3'), false );
?>
<?php /* @var $wsBooking Ws_Do_Book_Response */?>
<?php $wsBooking = @$this->wsBooking?>
<link rel="stylesheet" href="<?php echo JURI::base() ?>/templates/<?php echo $app->getTemplate();?>/css/checkbox.css" type="text/css" />
<script src="<?php echo JURI::base() ?>/templates/<?php echo $app->getTemplate();?>/js/checkbox.js" type="text/javascript"></script>
<script type="text/javascript">
    jQuery(function ($) {
        $(document).ready(function() {
            jQuery(".ui.checkbox").checkbox();
        });

		var frame = jQuery('#sbox-window iframe', window.top.document);
		if(!frame.length) {
			return;
		}
		var height = jQuery('body').outerHeight();

		frame.height(height+18);

		window.top.SqueezeBox && window.top.SqueezeBox.resize({y: height-2});

		setTimeout(function(){
			var framebox = jQuery('#sbox-window', window.top.document);
			if(framebox.length) {
				var top = parseInt(framebox.css('top'));
				if(top < 0) {
					framebox.css('top', 'px');
				}
			}

		}, 1000);
		jQuery(".midmargintop table").css("padding-bottom","15px");

    	$('#printRequest').on('click', function(){
    		sfsPopupCenter2('<?php echo $printUrl;?>', 'PrintVoucher',730,690);//745,690
        });

		var $mess = $('#messages-box');
    	var $form = $("#voucherPrintForm");
        $form.on('submit', function(){
            $.ajax({
					url: $form.attr('action'),
					data: $form.serialize(),
					type: 'post',
					dataType: 'json',
					beforeSend: function(){
						$('#wsRequestSpinner').show();
            		},
            		complete: function(){
            			$('#wsRequestSpinner').hide();
            		},
					success: function(json) {
    					$mess.toggleClass('uk-alert', true);
    					$mess.toggleClass('uk-alert-danger', json.code != 0);
    					$mess.toggleClass('uk-alert-success', json.code == 0);
						$mess.html(json.message);
						iframeModalAutoSize();
        			}
                });
			return false;
        });
        $("#sendSMS").on("click", function(e){
//			var infoOfCard = $('#ap-meal-response').val();
            var ok = true,
                phone_sms = $("#phone_sms").val(),
                regex = /\(?([0-9]{3})\)?([ .-]?)([0-9]{3})\2([0-9]{4})/,
                button = jQuery(this);
            if(!regex.test(phone_sms)) {
                $('#phone_sms').css('border-color','#ff0000');
                $('#phone_sms').css('background-color','#ffdddd');
                ok = false;
            }
            if (ok) {
                button.attr("disabled", "disabled");
                $.ajax({
                    url:"<?php echo JURI::base().'index.php?option=com_sfs&task=ajax.sendMessage&format=raw'; ?>",
                    type:"POST",
                    data:{
                        phone: phone_sms,
                        text: "<?php echo $textSMS;?>",
                        sender: "<?php echo $sender_title;?>"
                    },
                    dataType: 'text',
                    success:function(response){
                        if(response.charAt(0) == 1){
                            $('#phone_sms').css('border-color','');
                            $('#phone_sms').css('background-color','');
                            $mess.toggleClass('uk-alert', true);
                            $mess.toggleClass('uk-alert-success', true);
                            $mess.html("The voucher has been sent to " + phone_sms);
                            iframeModalAutoSize();
                            setTimeout(function(){
                                button.removeAttr("disabled");
                            },60000);
                        }
                        else
                        {
                            button.removeAttr("disabled");
                            alert("Failed!");
                        }
                    }
                })
            }
        });
        $("#ap-taxi").on('change', function(){
        	updateAirplusServices();
        });
        $("#ap-meal-first").on("change", function(){
            if($(this).is(":checked")){
                $("#ap-meal-second").prop('checked', false);
            }
            updateAirplusServices();
        });
        $("#ap-meal-second").on("change", function(){
            if($(this).is(":checked")){
                $("#ap-meal-first").prop('checked', false);
            }
            updateAirplusServices();
        });
    });

    function updateAirplusServices(){
        var $ = jQuery;
        var meal;
        var taxi            = jQuery("#ap-taxi").is(':checked') ? 1 : 0;
        var mealfirst       = jQuery("#ap-meal-first").is(':checked') ? 1 : 0;
        var mealsecond      = jQuery("#ap-meal-second").is(':checked') ? 1 : 0;
        var enddate         = "<?php echo $end_date?>";
        if(mealfirst == 1){
            meal = 1;
        }else{
            if(mealsecond == 1){
                meal = 2;
            }else{
                meal = 0;
            }
        }
        jQuery.ajax({
            url:"<?php echo JURI::base().'index.php?option=com_sfs&task=ajax.updateAirplusServices&format=raw'; ?>",
            type:"post",
            beforeSend: function(){
                $('button').prop('disabled', true);
                $('#ap-meal-first, #ap-meal-second, #ap-taxi').prop('disabled', true);
            },
            data: {
                voucher_id: <?php echo (int)$this->voucher->id?>,
                'ap-meal': meal,
                'ap-taxi': taxi,
                enddate: enddate
            },
            dataType:"text",
            success: function(response){
                if(response == '0'){
                    alert("ERROR!");
                }
            },
            complete: function(){
            	$('button').prop('disabled', false);
                $('#ap-meal-first, #ap-meal-second, #ap-taxi').prop('disabled', false);
            }
        })
    }

    function sfsPopupCenter2(pageURL,title,w,h) {
        var left = (screen.width/2)-(w/2);
        var top = (screen.height/2)-(h/2);
        win = window.open (pageURL, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
    }

    function getUrlVars() {
        var vars = {};
        var parts = window.parent.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
            vars[key] = value;
        });
        return vars;
    }

    // var uri = window.parent.location.href;
    var Itemid = getUrlVars()["Itemid"];
</script>

<style>
<!--
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
	display: none;
}
.singlevoucher-passenger-title{
	width: 50px !important;
}

.preview-number{
	color: green;
	border: 1px solid green;
	padding: 5px;
	border-radius: 5px;
	display: inline-block;
}
#sfs-wrapper .ui.celled.grid{
	box-shadow: none;
}
#sfs-wrapper .ui.celled.grid .row{
	box-shadow: none;
}
#sfs-wrapper .ui.celled.grid .column{
	box-shadow: none;
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
-->
</style>
							
<form action="<?php echo JRoute::_('index.php?option=com_sfs')?>" method="post" name="voucherPrintForm" id="voucherPrintForm" class="form-validate">
    <div id="messages-box"></div>
    <table cellpadding="0" cellspacing="0" border="0" width="100%">
        <tr valign="top">
            <td width="50%" style="padding-left: 15px;vertical-align: top;">
                <h2>Accommodation</h2>
            </td>
            <td width="50%" style="padding-left: 15px;vertical-align: top;">
                <h2>Add additional services</h2>
            </td>
        </tr>
        <tr valign="top">
            <td width="50%" style="padding-left: 15px;">
                <div class="sfs-row">
                    <div class="sfs-column-left" style="width:140px;">
                        Flight number *
                    </div>
                    <?php echo JString::strtoupper($this->voucher->flight_code);?>
                </div>
                <div class="sfs-row">
                    <div class="sfs-column-left" style="width:140px;">
                        IATA stranded code *
                    </div>
                    <?php echo JString::strtoupper($this->voucher->flight_delay_code);?>
                </div>
            </td>
            <td width="50%" style="padding-left: 15px;vertical-align: top;" <?php if($this->voucher->comment){ echo 'rowspan="2"';}?>>
                <div><h3>Transfer to Hotel</h3></div>
                <div style="margin-top: 15px;">
                    <?php if((int)$airplusparams['taxi_enabled'] == 1):?>
                        <div style="width: 20%; float: left">
                            <div class="ui toggle checkbox">
                                <input name="ap-taxi" type="checkbox" id="ap-taxi" >
                            </div>
                        </div>
                        <div style="width: 80%; float: left">
                            Distance to airport <?php echo $this->hotel->distance." ".$this->hotel->distance_unit;?><br/>
                            Estimated taxi costs return trip <?php echo $ap_taxi_value;?> Euro<br/>
                            Expiry date <?php echo $expired_date?>
                        </div>
                    <?php else:?>
                        <div style="width: 20%; float: left">
                            <div class="ui toggle checkbox disabled">
                                <input name="ap-taxi" type="checkbox" id="ap-taxi" >
                            </div>
                        </div>
                        <div style="width: 80%; float: left; opacity: 0.35;">
                            Distance to airport <?php echo $this->hotel->distance." ".$this->hotel->distance_unit;?><br/>
                            Estimated taxi costs return trip <?php echo $ap_taxi_value;?> Euro<br/>
                            Expiry date <?php echo $expired_date?>
                        </div>
                    <?php endif;?>
                </div>
            </td>
        </tr>
        <?php if($this->voucher->comment) : ?>
        <tr valign="top">
            <td width="50%" style="padding-left: 15px;vertical-align: top;">
                <div class="sfs-white-wrapper sfs-voucher-print-box floatbox midmarginbottom">
                    <?php echo JText::_('COM_SFS_AIRLINE_FLIGHT_NUMBER_ADD_COMMENT');?>:<br />
                    <?php echo $this->voucher->comment?>
                </div>
            </td>
        </tr>
        <?php endif; ?>
        <tr valign="top">
            <td width="50%" style="padding-left: 15px;vertical-align: top;">
                <div>Passengers</div>
                <div class="fs-11" style="padding-top: 5px; line-height:13px;">When you insert names it will be easier for you to trace the passengers at a later stage.</div>

                <div class="midmargintop">
                    <?php $i = -1;?>
                    <?php foreach($this->wsRoomTypes as $wsRoomType) : ?>
                        <?php for($k = 0; $k< $wsRoomType->NumberOfRooms; $k++) : ?>
                            <h3><?php echo $wsRoomType->Name?> - <?php echo ($k + 1)?> (<?php echo $wsRoomType->NumAdultsPerRoom?> person)</h3>
                            <table border="0" width="100%" class="match-passengers">
                                <thead>
                                <tr>
                                    <th></th>
                                    <th>Title</th>
                                    <th>First name</th>
                                    <th>Last name</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php 
								$i = 0;
								$t = 0;
								$phone_number = '';
								foreach($this->trace_passengers as $ps) : ?>
                                <?php  //print_r( $this->trace_passengers );
								//for($t= 0; $t < $wsRoomType->NumAdultsPerRoom; $t++) : 
								foreach ($ps as $p) :
									if( $phone_number == '' ) {
										$phone_number = $p->phone_number;
									}
								?>
                                    
                                    <?php //$p = $this->trace_passengers[$i][$i];?>
                                    <tr>
                                        <td>Passenger <?php echo $t + 1?></td>
                                        <td>
                                            <?php echo $p->title;?>
                                        </td>
                                        <td>
                                            <?php echo $p->first_name;?>
                                        </td>
                                        <td>
                                            <?php echo $p->last_name;?>
                                        </td>
                                    </tr>
                                    <?php $t++;?>
                                <?php endforeach; endforeach; //endfor;?>
                                <tr>
                                    <td>Phone number</td>
                                    <td colspan="2">
                                        <?php 
										echo $phone_number;
										// =  @$this->trace_passengers[$k]->phone_number
										?>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        <?php endfor;?>
                    <?php endforeach;?>
                </div>
            </td>
            <td width="50%" style="padding-left: 15px;vertical-align: top;" <?php if($this->voucher->return_flight_number){echo 'rowspan="2"';}?>>
                <div><h3>Meal plan voucher</h3></div>
                <div style="margin-top: 15px;">
                    <?php if((int)$airplusparams['meal_enabled'] == 1):?>
                        <?php if($ap_meal_first_value && $ap_meal_first_value >0 ):?>
                        <div style="width: 20%; float: left">
                            <div class="ui toggle checkbox">
                                <input name="ap-meal-first" type="checkbox" id="ap-meal-first">
                            </div>
                        </div>
                        <div style="width: 80%; float: left">
                            Issue <?php echo $ap_meal_first_value;?> Euro mealplan voucher per person <br/>
                            Expiry date <?php echo $expired_date?>
                        </div>
                        <?php endif;?>
                        <?php if($ap_meal_second_value && $ap_meal_second_value >0 ):?>
                        <div style="width: 20%; float: left">
                            <div class="ui toggle checkbox">
                                <input name="ap-meal-second" type="checkbox" id="ap-meal-second">
                            </div>
                        </div>
                        <div style="width: 80%; float: left">
                            Issue <?php echo $ap_meal_second_value;?> Euro mealplan voucher per person <br/>
                            Expiry date <?php echo $expired_date?>
                        </div>
                        <?php endif;?>
                    <?php else:?>
                        <?php if($ap_meal_first_value && $ap_meal_first_value >0 ):?>
                        <div style="width: 20%; float: left;">
                            <div class="ui toggle checkbox disabled">
                                <input name="ap-meal-first" type="checkbox" id="ap-meal-first">
                            </div>
                        </div>
                        <div style="width: 80%; float: left; opacity: 0.35;">
                            Issue <?php echo $ap_meal_first_value;?> Euro mealplan voucher per person <br/>
                            Expiry date <?php echo $expired_date?>
                            <p>
                            <a style="display:none;" id="ViewCardMealplanRequest" href="javascript:void(0);" class="small-button" style="margin-top:0;width: 50px;">View</a>
                           	</p>
                        </div>
                        <?php endif;?>
                        <?php if($ap_meal_second_value && $ap_meal_second_value >0 ):?>
                        <div style="width: 20%; float: left;">
                            <div class="ui toggle checkbox disabled">
                                <input name="ap-second-meal" type="checkbox" id="ap-second-meal">
                            </div>
                        </div>
                        <div style="width: 80%; float: left; opacity: 0.35;">
                            Issue <?php echo $ap_meal_second_value;?> Euro mealplan voucher per person <br/>
                            Expiry date <?php echo $expired_date?>
                        </div>
                        <?php endif;?>
                    <?php endif;?>
                </div>
            </td>
        </tr>
        <?php if($this->voucher->return_flight_number) : ?>
            <tr valign="top">
                <td width="50%" style="padding-left: 15px;vertical-align: top;">
                    <div class="sfs-white-wrapper sfs-voucher-print-box floatbox midmarginbottom" style="min-height:50px;">
                        <div class="sfs-row">
                            <div class="sfs-column-left" style="width:120px;">
                                Return flight number
                            </div>
                            <?php echo $this->voucher->return_flight_number?>
                        </div>
                        <div class="sfs-row">
                            <div class="sfs-column-left" style="width:120px;">
                                Return flight date
                            </div>
                            <?php echo JHTML::_('date', $this->voucher->return_flight_date , JText::_('DATE_FORMAT_LC3'), false )?>
                        </div>
                    </div>
                </td>
            </tr>
        <?php endif; ?>
        <tr valign="top">
            <td colspan="2">
                <div class="sixteen wide column">
                    <div class="sfs-white-wrapper sfs-voucher-print-box floatbox">
                        <div class="ui four column grid">
                            <div class="four column wide"></div>
                            <div class="four column wide right aligned"><!--style="float:left; margin-right:15px; width:45%;"-->
                                <?php if($enableSMS):?>
                                    <div>
                                        <input type="text" name="phone_sms" id="phone_sms" value="<?php if(count($this->wsRoomTypes) == 1) echo $phone_number;?>" />
                                    </div>
                                    <br/>
                                <?php endif;?>
                                <div>
                                    <input type="text" name="email" value="@" class="validate-email required" />
                                </div>
                                <br/>
                                <div class="preview-number" style="margin-top: 2px">
                                    Your booking number is: <?php echo $wsBooking->BookingReference?>
                                </div>
                            </div>
                            <div class="four column wide" style="float:left; width:50%;">
                                <span id="wsRequestSpinner" class="ajax-Spinner"></span>
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
                            <div class="four column wide"></div>
                        </div>
                    </div>
                </div>
            </td>
        </tr>
        <tr valign="top">
            <td colspan="2">
                <div class="sixteen wide column">
                    <div class="sfs-white-wrapper sfs-voucher-print-box floatbox">
                        <div class="ui three column celled grid" style="box-shadow: none">
                            <div class="row" style="box-shadow: none">
                                <div class="four column wide" style="box-shadow: none">
                                    <a style="margin-top:0;width: 100%" class="small-button" onclick='window.top.location.href = "index.php?option=com_sfs&view=dashboard&Itemid="+Itemid;'>Close and <br/>i am done</a>
                                </div>
                                <div class="four column wide" style="box-shadow: none">
                                </div>
                                <div class="four column wide" style="box-shadow: none">
                                    <a style="margin-top:0;width: 100%;" class="small-button" onclick="window.parent.SqueezeBox.close();">Close and <br/>book another room</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </td>
        </tr>
    </table>

<div id="sfs-wrapper" class="match">
	<div id="messages-box"></div>
	<div class="ui celled grid">
		<div class="row">

		</div>
		<div class="row">

		</div>
	</div> <?php # end .singlevoucherlayout-ws?>
</div>

	<input type="hidden" name="voucherid" value="<?php echo $this->voucher->id?>" />
	<input type="hidden" name="blockcode" value="<?php echo $this->voucher->blockcode?>" />
	<input type="hidden" name="task" value="match.sendVoucherJSON" />
	<?php echo JHtml::_( 'form.token' ); ?>
</form>	