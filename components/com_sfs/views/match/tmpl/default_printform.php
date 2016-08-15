<?php
defined('_JEXEC') or die;
$app = JFactory::getApplication();
$airline  = SFactory::getAirline();
$airplusparams= $airline->airplusparams;
$is_return = '';
$hotel_id = 0;
$taxi_voucher_id = '';
$taxi_id = '';
$vgroup = 0;
foreach ( $this->voucher as $ks => $vs) {
$voucher_id[] = $vs->id;
$voucher_code[] = $vs->code;
$is_return = $vs->is_return;
$hotel_id = $vs->hotel_id;
$taxi_voucher_id = $vs->taxi_voucher_id;
$taxi_id = $vs->taxi_id;
$vgroup = $this->vgroup;
}
$printUrl =  JURI::base().'index.php?option=com_sfs&view=voucher&voucher_id='.implode(",", $voucher_id).'&voucher_groups_id=' . $this->voucher_groups->id. '&tmpl=component';
$airport_current = $airline->getCurrentAirport();
$distance = SfsHelper::getDistanceHotelAirport($hotel_id, $airport_current->id);
$ap_taxi_value = SfsHelper::calculateTaxiValue($hotel_id);
$ap_meal_first_value = $airplusparams['meal_first_limit'];
$ap_meal_second_value = $airplusparams['meal_second_limit'];
?>
<link rel="stylesheet" href="<?php echo JURI::base() ?>/templates/<?php echo $app->getTemplate();?>/css/checkbox.css" type="text/css" />
<script src="<?php echo JURI::base() ?>/templates/<?php echo $app->getTemplate();?>/js/checkbox.js" type="text/javascript"></script>
<style>
    #voucherPrintForm table, #voucherPrintForm th, #voucherPrintForm td{
        border: 5px solid #82adf1;
    }
    #voucherPrintForm table.match-passengers,#voucherPrintForm table.match-passengers th, #voucherPrintForm table.match-passengers td{
        border: 0;
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

</style>

<script type="text/javascript">	
<!--
window.addEvent('domready', function() {
	<?php if( $taxi_voucher_id ) :
	$printTaxiUrl = JURI::base().'index.php?option=com_sfs&view=taxi&layout=voucher&tmpl=component&is_return=0&taxi_id='.$this->voucher->taxi_id.'&taxi_voucher_id='.$taxi_voucher_id;
	$printTaxiUrl2 = JURI::base().'index.php?option=com_sfs&view=taxi&layout=voucher&tmpl=component&is_return=1&taxi_id='.$taxi_id.'&taxi_voucher_id='.$taxi_voucher_id; 
	?>	
	var printTaxiUrl = '<?php echo $printTaxiUrl;?>';
	var printTaxiUrl2 = '<?php echo $printTaxiUrl2;?>';	
	<?php endif;?>
	
	var printLink = '<?php echo $printUrl;?>';		
	
	var printVoucherForm  = document.id('voucherPrintForm');	
	
	var printVoucherFormRequest = new Form.Request(printVoucherForm, $('testre'),  {					
	    requestOptions: {
	    	useSpinner: false
	    },		   
	    onComplete: function(responseText){
//            jQuery("#voucherPrintForm").html(responseText);
		   	if(printVoucherForm.printtype.value =='print'){
		   		$('printRequestSpinner').removeClass('ajax-Spinner');
                var infoOfCardApMeal = jQuery('#ap-meal-response').val();
                var infoOfCardApTaxi = jQuery('#ap-taxi-response').val();
                printLink = printLink;
		   		SqueezeBox.open(printLink, {handler: 'iframe',size: {x: 954, y: 700}});
		   	} else if(printVoucherForm.printtype.value =='email'){
		   		$('emailRequestSpinner').removeClass('ajax-Spinner');
			} else if(printVoucherForm.printtype.value =='taxi'){
		   		$('taxiRequestSpinner').removeClass('ajax-Spinner');
		   		SqueezeBox.open(printTaxiUrl, {handler: 'iframe',size: {x: 800, y: 700}});
		   	} else if(printVoucherForm.printtype.value =='returntaxi'){
		   		$('taxiRequestSpinner2').removeClass('ajax-Spinner');
		   		SqueezeBox.open(printTaxiUrl2, {handler: 'iframe',size: {x: 800, y: 700}});
		   	}
			   		    	 	    	
	    },
	    resetForm : false				    
	});
   	
	$('printRequest').addEvent('click', function(e){
		e.stop();		
		$('printRequestSpinner').addClass('ajax-Spinner');
		printVoucherForm.printtype.value = 'print';
		printVoucherFormRequest.setTarget($('testre'));
		printVoucherFormRequest.send();
	});	
	$('emailRequest').addEvent('click', function(e){
		e.stop();	
		$('emailRequestSpinner').addClass('ajax-Spinner');		
		printVoucherForm.printtype.value = 'email';
		printVoucherFormRequest.setTarget($('emailFormResult'));
		printVoucherFormRequest.send();								
	});	

	<?php if( $taxi_voucher_id ) : ?>	
	$('taxiPrintRequest').addEvent('click', function(e){
		e.stop();	
		$('taxiRequestSpinner').addClass('ajax-Spinner');	
		printVoucherForm.printtype.value = 'taxi';	
		printVoucherFormRequest.setTarget($('testre'));
		printVoucherFormRequest.send();									
	});
	<?php endif;?>

	<?php if( $taxi_voucher_id && $is_return) : ?>	
	$('returnTaxiPrintRequest').addEvent('click', function(e){
		e.stop();							
		$('taxiRequestSpinner2').addClass('ajax-Spinner');		
		printVoucherForm.printtype.value = 'returntaxi';	
		printVoucherFormRequest.setTarget($('testre'));
		printVoucherFormRequest.send();				
	});
	<?php endif;?>	
	
	$('closeVoucherPrintForm').addEvent('click', function(e){
		e.stop();			
		$('sfs-voucher-print-form').destroy();	
		window.parent.location='<?php echo JURI::base().'index.php?option=com_sfs&view=match&nightdate='.JRequest::getVar('nightdate').'&Itemid='.JRequest::getInt('Itemid');?>';
	});

	var sfsOverlay  = new Element('div', {id: 'sfs-box-overlay'});
	sfsOverlay.inject($('bd'));							    	
	ssize = document.getScrollSize();									    
	sfsOverlay.setStyles({
		width: ssize.x + 'px',
		height: ssize.y + 'px'										
	});							    	
	sfsOverlay.setStyle('z-index','1999');
	sfsOverlay.tween('opacity', 0.7);
	
});
jQuery.noConflict();
jQuery(function($){
    $(document).ready(function() {
        $(".ui.checkbox").checkbox();
        $("#return_taxi").html(getDateFormatted($("#returnflightdate").val()));
        $("#return_meal_first").html(getDateFormatted($("#returnflightdate").val()));
        $("#return_meal_second").html(getDateFormatted($("#returnflightdate").val()));
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
    });
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
-->	
</script>

<form id="voucherPrintForm" name="voucherPrintForm" action="<?php echo JRoute::_('index.php')?>" method="post">	

	<div id="testre"></div>

    <table cellpadding="0" cellspacing="0" border="0" width="500">
        <tr valign="top">
            <td width="50%" style="padding-left: 15px;vertical-align: top;">
                <h2>Accommodation</h2>
            </td>
            <!--<td width="50%" style="padding-left: 15px;vertical-align: top;">
                <h2>Add additional services</h2>
            </td>-->
        </tr>

        <tr valign="top">
            <?php if( isset($airline->params['show_vouchercomment']) && (int)$airline->params['show_vouchercomment'] == 1 ):?>
                <td width="50%" style="padding-left: 15px;vertical-align: top;">
                    <div class="sfs-white-wrapper floatbox midmarginbottom" data-step="1" data-intro="<?php echo SfsHelper::getTooltipTextEsc('popup_add_comment', $text, 'airline'); ?>">
                        <?php echo JText::_('COM_SFS_AIRLINE_FLIGHT_NUMBER_ADD_COMMENT');?>:<br />
                        <textarea name="comment" id="vouchercomment" style="width:98%;height:90px;"><?php if($this->voucher->flight_comment) echo $this->voucher->flight_comment;?></textarea>
                    </div>
                </td>
            <?php else : ?>
            	<td width="50%" style="padding-left: 15px;vertical-align: top;">
            	</td>
            <?php endif; ?>
            <!--<td width="50%" style="padding-left: 15px;vertical-align: top;" <?php if( $this->voucher->taxi_voucher_id ){ echo 'rowspan="2"';}?>>
                <div><h3>Transfer to Hotel</h3></div>
                <div style="margin-top: 15px;">
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
            </td>-->
        </tr>

        <?php if( $taxi_voucher_id ) : ?>
            <tr valign="top">
                <td width="50%" style="padding-left: 15px;">

                    <div class="sfs-white-wrapper floatbox midmarginbottom" style="padding: 2px 15px;min-height:10px;">
                        <div class="voucher-hotel-icon">
                            Hotel Voucher
                        </div>
                    </div>

                </td>
            </tr>
        <?php endif;?>

        <tr valign="top">
            <td width="50%" style="padding-left: 15px;vertical-align: top;">
                <?php if( (int)$vgroup==0 ): ?>
                    <div class="sfs-white-wrapper floatbox midmarginbottom" data-step="2" data-intro="<?php echo SfsHelper::getTooltipTextEsc('popup_insert_name', $text, 'airline'); ?>">
                        <div>Insert names (optional)</div>
                        <div class="fs-11" style="padding-top: 5px; line-height:13px;">When you insert names it will be easier for you to trace the passengers at a later stage.</div>

                        <div class="midmargintop">
                            <table border="0" width="100%" class="match-passengers">
                                
                                <tbody>
                                <?php
								$sd = 0;
								$t = 0;
								$q = 0;
								$ii = 0;
								//lchung
								$s_rooms = JRequest::getInt('s_rooms');
								$sd_rooms = JRequest::getInt('sd_rooms');
								$t_rooms = JRequest::getInt('t_rooms');
								$q_rooms = JRequest::getInt('q_rooms');
								
                                $seats = (int) $this->voucher[0]->seats;
								
								$ck_s = $s_rooms*1;
								$ck_sd = $sd_rooms*2;
								$ck_t = $t_rooms*3;
								$ck_q = $q_rooms*4;
								
								$seats_S = 0;
								$seats_SD = $ck_sd;
								$seats_T = 0;
								$seats_Q = 0;
								if ( $s_rooms > 0 ){
									$seats_S = $ck_s;
									$seats = $seats - $seats_S;
									$seats_SD = $seats;
								}
								else if( $s_rooms == 0 && $sd_rooms == 0 && $t_rooms == 0 && $q_rooms == 0 ){
									$seats_S = 1;
								}
								
								if ( $sd_rooms > 0 && $t_rooms > 0 && $q_rooms > 0 ) {
									$seats_SD = $ck_sd;
									$seats_T = $seats - $seats_SD;
									if ( $seats_T > $ck_t ) {
										$seats_T = $ck_t;
									}
									if ( $seats_T > 0 )
										$seats_Q = $seats - ($seats_T + $seats_SD);
								}
								elseif( $sd_rooms > 0 && $t_rooms > 0 && $q_rooms == 0 ){
									$seats_SD = $ck_sd;
									$seats_T = $seats - $seats_SD;
									$seats_Q = 0;
								}
								elseif( $sd_rooms > 0 && $t_rooms == 0 && $q_rooms > 0 ){
									$seats_SD = $ck_sd;
									$seats_T = 0;
									$seats_Q = $seats - $seats_SD;
								}
								elseif( $sd_rooms > 0 && $t_rooms == 0 && $q_rooms == 0 ){
									$seats_SD = $ck_sd;
									if ( $seats_SD > $seats ) {
										$seats_SD = $seats;
									}
									$seats_T = 0;
									$seats_Q = 0;
								}
								elseif( $sd_rooms == 0 && $t_rooms > 0 && $q_rooms > 0 ){
									$seats_SD = 0;
									$seats_T = $ck_t;
									$seats_Q = $seats - $seats_T;
								}
								elseif( $sd_rooms == 0 && $t_rooms == 0 && $q_rooms > 0 ){
									$seats_SD = 0;
									$seats_T = 0;
									$seats_Q = $seats;
								}
								elseif( $sd_rooms == 0 && $t_rooms > 0 && $q_rooms == 0 ){
									$seats_SD = 0;
									$seats_T = $seats;
									$seats_Q = 0;
								}
								echo w_htmlSingle( $seats_S, $ii );
								echo w_htmlSingleDouble( $seats_SD, $ii );
								echo w_htmlTriple( $seats_T, $ii );
								echo w_htmlQuadruple( $seats_Q, $ii );
								?>
                                <tr>
                                    <td>Phone number</td>
                                    <td colspan="2">
                                        country ext <input type="text" name="passenger_mobile_ext" class="match-passenger-mobile-ext" style="width: 40px">
                                        <input type="text" name="passenger_mobile" class="match-passenger-mobile">
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php endif;?>
            </td>
            <!--<td width="50%" style="padding-left: 15px;vertical-align: top; display:none;" <?php if( isset($airline->params['enable_return_flight']) && (int)$airline->params['enable_return_flight'] == 1 ){echo 'rowspan="2"';}?>>
                <div><h3>Meal plan voucher</h3></div>
                <div style="margin-top: 15px;">
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
            </td>-->
        </tr>

        <?php if( isset($airline->params['enable_return_flight']) && (int)$airline->params['enable_return_flight'] == 1 ) : ?>
            <tr valign="top">
                <td width="50%" style="padding-left: 15px;vertical-align: top;  display:none;">
                    <div class="sfs-white-wrapper floatbox midmarginbottom" style="min-height:50px;" data-step="3" data-intro="<?php echo SfsHelper::getTooltipTextEsc('popup_flight_number', $text, 'airline'); ?>">
                        <div class="sfs-row">
                            <div class="sfs-column-left" style="width:120px;">
                                New flight number
                            </div>
                            <input type="text" name="returnflight" value="" style="width:130px;" />
                        </div>
                        <div class="sfs-row">
                            <div class="sfs-column-left" style="width:120px;">
                                New flight date
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
                            <div class="four column wide" style="width:10%;"></div>
                            <div class="four column wide right aligned" style="width:40%;">
                                <div>
                                    <input type="text" name="email" value="@" class="validate-email" style="width:160px;" />
                                </div>
                                <br/>
                                <div class="preview-number" style="margin-top: 2px">
                                    <input type="text" name="vouchercode" value="<?php echo $this->voucher_groups->code;?>" readonly="readonly" style="width:160px;" />
                                    <input type="hidden" name="voucher_id" value="<?php echo implode(",", $voucher_id)?>" />
                                </div>
                            </div>
                            <div class="four column wide" style="width:40%;">
                                <div class="mid-button" >
                                    <button type="button" id="emailRequest" style="width:152px;">
                                        Email<br/>hotelvoucher
                                    </button>
                                </div>
                                <br/>
                                <div class="mid-button" style="position:relative;">
                                    <div id="ws-booking-loading" class="ws-booking-loading">
                                        <span class="ws-booking-spinner ajax-Spinner48"></span>
                                    </div>
                                    <button type="button" id="printRequest" style="width:152px;">
                                        Print<br/>hotelvoucher
                                    </button>
                                </div>
                            </div>
                            <div class="four column wide"  style="padding-left: 0; width:10%;">
                                <div id="emailRequestSpinner" class="float-left" style="margin-top: 10px"></div>
                                <div id="printRequestSpinner" class="float-left" style="margin-top: 75px; margin-left: -20px"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php if( $taxi_voucher_id ) : ?>
                    <div class="sfs-main-wrapper sfs-voucher-print-box sfs-print-voucher-column">
                        <div class="sfs-white-wrapper floatbox midmarginbottom" style="padding: 2px 15px;min-height:10px;">
                            <div class="voucher-taxi-icon">
                                Taxi Voucher
                            </div>
                        </div>
                        <?php if( $vgroup==0) : ?>
                            <div class="sfs-white-wrapper floatbox midmarginbottom">
                                Add comment on the outgoing taxi voucher to accommodation:<br />
                                <textarea id="taxicomment" name="taxicomment" style="width:98%;height:123px;"></textarea>
                                <div style="overflow: hidden; margin: 10px 10px 10px 10px;">
                                    <div class="mid-button float-right" >
                                        <button type="button" id="taxiPrintRequest" style="text-indent:22px;">
                                            Print airp to hotel taxivoucher
                                        </button>
                                    </div>
                                    <div id="taxiRequestSpinner" class="float-right"></div>
                                </div>
                            </div>

                            <?php if( $is_return) :
                                $taxiDetails = $airline->getTaxiDetails();
                                ?>
                                <div class="sfs-white-wrapper floatbox">
                                    Add comment on the return trip taxi voucher:<br />
                                    <textarea id="taxireturncomment" name="taxireturncomment" style="width:98%;height:123px;">Please contact <?php echo $this->voucher->taxi_name;?> taxi to schedule  your return transfer, per phone on Tel.<?php echo trim($taxiDetails->telephone);?>. &#13;&#10;Or if possible, through the front desk of your accommodation.</textarea>
                                    <div style="overflow: hidden; margin: 10px 10px 10px 10px;">
                                        <div class="mid-button float-right" >
                                            <button type="button" id="returnTaxiPrintRequest" style="text-indent:22px;">
                                                Print hotel to airp taxivoucher
                                            </button>
                                        </div>
                                        <div id="taxiRequestSpinner2" class="float-right"></div>
                                    </div>
                                </div>
                            <?php endif;?>
                        <?php else: ?>
                            <div class="sfs-white-wrapper floatbox midmarginbottom">
                                No taxi's available for group bookings, please book a separate group transportation.
                            </div>
                        <?php endif;?>
                    </div>
                    <div class="floatbox" style="clear:both;margin-top:15px; margin-bottom:15px; margin-right:50px;">
                        <button type="button" id="closeVoucherPrintForm" class="small-button float-right" style="margin-top:5px;">Close</button>
                    </div>
                    <input type="hidden" name="taxi_voucher_id" value="<?php echo $taxi_voucher_id;?>" />
                    <input type="hidden" name="taxi_id" value="<?php echo $taxi_id?>" />
                <?php endif;?>
                <input type="hidden" name="printtype" value="" />
                <input type="hidden" name="option" value="com_sfs" />
                <input type="hidden" name="format" value="raw" />
                <input type="hidden" name="task" value="match.processPrintVoucher" />
                <?php echo JHtml::_('form.token'); ?>
            </td>
        </tr>
        <?php if( !$taxi_voucher_id ) : ?>
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
<?php 

function w_htmlSingle( $seats, &$ii)
{
	if ( $seats == 0)
		return'';
		
	$str = "";
	$h = 0;
	for ($i = 0; $i<$seats; $i++) {
		if( $h == 0 ) {
			$h = 1;
			$str .='<tr><td colspan="2"><h2>Single</h2></td></tr>
			<tr>
				<th></th>
				<th>first name</th>
				<th>last name</th>
			</tr>';
		} 
		$t = ($ii+1);
		$str .='<tr>
			<td>Passenger ' . $t . '</td>
			<td><input type="text" name="passengers[' . $ii . '][firstname]" id="firstname' . $t . '" class="passenger-input match-passenger-firstname"></td>
			<td><input type="text" name="passengers[' . $ii . '][lastname]" id="lastname' . $t . '" class="passenger-input match-passenger-lastname"></td>
		</tr>';
		
		$ii++;
	}
	return $str;
}

function w_htmlSingleDouble( $seats, &$ii)
{
	if ( $seats == 0)
		return'';
		
	$str = "";
	$h = 0;
	for ($i = 0; $i<$seats; $i++) {
		if( $h == 0 ) {
			$h = 1;
			$str .='<tr><td colspan="2"><h2>Single/Double</h2></td></tr>
			<tr>
				<th></th>
				<th>first name</th>
				<th>last name</th>
			</tr>';
		} 
		$t = ($ii+1);
		$str .='<tr>
			<td>Passenger ' . $t . '</td>
			<td><input type="text" name="passengers[' . $ii . '][firstname]" id="firstname' . $t . '" class="passenger-input match-passenger-firstname"></td>
			<td><input type="text" name="passengers[' . $ii . '][lastname]" id="lastname' . $t . '" class="passenger-input match-passenger-lastname"></td>
		</tr>';
		
		$ii++;
	}
	return $str;
}

function w_htmlTriple( $seats, &$ii)
{
	if ( $seats == 0)
		return'';
		
	$str = "";
	$h = 0;
	for ($i = 0; $i<$seats; $i++) {
		if( $h == 0 ) {
			$h = 1;
			$str .='<tr><td colspan="2"><h2>Triple</h2></td></tr>
			<tr>
				<th></th>
				<th>first name</th>
				<th>last name</th>
			</tr>';
		} 
		$t = $ii+1;
		$str .='<tr>
			<td>Passenger ' . $t . '</td>
			<td><input type="text" name="passengers[' . $ii . '][firstname]" id="firstname' . $t . '" class="passenger-input match-passenger-firstname"></td>
			<td><input type="text" name="passengers[' . $ii . '][lastname]" id="lastname' . $t . '" class="passenger-input match-passenger-lastname"></td>
		</tr>';
		
		$ii++;
	}
	return $str;
}


function w_htmlQuadruple( $seats, &$ii)
{
	if ( $seats == 0)
		return'';
		
	$str = "";
	$h = 0;
	for ($i = 0; $i<$seats; $i++) {
		if( $h == 0 ) {
			$h = 1;
			$str .='<tr><td colspan="2"><h2>Quadruple</h2></td></tr>
			<tr>
				<th></th>
				<th>first name</th>
				<th>last name</th>
			</tr>';
		} 
		$t = $ii+1;
		$str .='<tr>
			<td>Passenger ' . $t . '</td>
			<td><input type="text" name="passengers[' . $ii . '][firstname]" id="firstname' . $t . '" class="passenger-input match-passenger-firstname"></td>
			<td><input type="text" name="passengers[' . $ii . '][lastname]" id="lastname' . $t . '" class="passenger-input match-passenger-lastname"></td>
		</tr>';
		
		$ii++;
	}
	return $str;
}
?>

