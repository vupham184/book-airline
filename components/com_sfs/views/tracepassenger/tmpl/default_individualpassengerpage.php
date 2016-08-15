<?php
defined('_JEXEC') or die();
JHtml::_('behavior.keepalive');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.modal');
$airline = SFactory::getAirline();
$airplusparams= $airline->airplusparams;
$passenger = $this->item;
$passengerNex = $passenger->isgroupPNR;
$passenger_id = JRequest::getVar('passenger_id');
$airline_current = SAirline::getInstance()->getCurrentAirport();
$airport_current_code = $airline_current->code;
$ssr = json_decode($passenger->ssrs);
$rebooked_fltno = $passenger->rebook;
$startime = SfsHelperDate::getSelectTimeField();
$nameAirport = $this->airport_of_airline;
$AirportGroup = $this->AirportGroup;
$document = JFactory::getDocument();
$document->addScript(JURI::base().'components/com_sfs/assets/jquery-alert-dialogs/js/jquery.ui.draggable.js');
$document->addScript(JURI::base().'components/com_sfs/assets/jquery-alert-dialogs/js/jquery.alerts.js');
$document->addStyleSheet(JURI::base().'components/com_sfs/assets/jquery-alert-dialogs/css/jquery.alerts.css');
$contentSubService = json_decode($passenger->subServiceOther->content);
$link_Img = JURI::root().'media/media/images/select-pass-icons/';
?>
<script>
	jQuery.noConflict();    
	jQuery(function($){
		loadReservationHotel();

		$('.fancybox').fancybox(
		{
			'width': 380,
			'height': 'auto'
		}
		);

        // add css for class feature
        
        $('.feature_edit').on('click', function() {
        	$(this).hide();
        	$('.save_passenger').css({
        		display: 'block',
        		width: '54px',
        		'margin-right':'5px'
        	});
        	$('.passenger_email').hide();
        	$('.input_email').show();
        	$('.passenger_phone').hide();
        	$('.input_phone').show();
			$('.new_pnr').show();
			$('.passenger_new_pnr').hide();
			$('.input_new_pnr').show();

			$('.guest_relations').show();
			$('.passenger_guest_relations').hide();
			$('.input_guest_relations').show();
        });

        //feature save passenger with ajax
        $('.save_passenger').on('click', function() {
        	var email_val = $('.input_email').val();
        	var phone_val = $('.input_phone').val();
        	var new_pnr = $('.input_new_pnr').val();
        	var guest_relations_id = $('.input_guest_relations').val();
        	var passenger_id = '<?php echo $passenger_id?>';
            //console.log passenger_id;
            $.ajax({
            	url: 'index.php?option=com_sfs&task=ajax.updatePassenger&format=raw',
            	type: 'POST',
            	data: {
            		email_val: email_val,
            		phone_val:phone_val,
            		passenger_id: passenger_id,
            		new_pnr:new_pnr,
            		guest_relations_id: guest_relations_id
            	},
            })
            .done(function(response) {
            	if(response){
            		$('.save_passenger').hide();
            		$('.feature_edit').show();
            		$('.input_email').hide();
            		$('.passenger_email').text(email_val).show();
            		$('.input_phone').text(phone_val).hide();
            		$('.passenger_phone').text(phone_val).show();
            		$('.send_SMS_text_message').attr('data-tel', phone_val);
            		$('#email').val(email_val);
            		$('#tel').val(phone_val);
            		$('.input_new_pnr').hide();
            		if(new_pnr!=''){
	            		$('.passenger_new_pnr').html(new_pnr);
	            		$('.passenger_new_pnr').show();	
            		}else{
            			$('.new_pnr').hide();
            		}
            		$('.input_guest_relations').hide();
            		if(guest_relations_id!=''){
            			$('.passenger_guest_relations').html(guest_relations_id);
	            		$('.passenger_guest_relations').show();
            		}else{
            			$('.guest_relations').hide();
            		}
            	}
            	else{
            		alert("Unsuccess");
            	}
            })
            
        });

        $('.a-confirmation-letter').click(function(e) {
        	var obj = $(this).offset();
        	var objD = $(document);
        	var obj_w = $('.canx-confirmation-letter').width();
        	var h = $('.canx-confirmation-letter').height();
        	$('.canx-confirmation-letter').css({
        		'display':'block', 
        		'top': (obj.top - (h/2))+ 'px',
        		'left': ( (objD.width() - obj_w) /2) + 'px'
        	});
        });

    });
	
	function hhover(obj){
		var p = jQuery(obj).offset();
		var t = p.top+20;
		var l = p.left;		
		jQuery('.irreg-notification-content').css({'display':'block', 'top':t + 'px', 'left': l + 'px'});
		
		var ph = jQuery( "p.irreg-content" ).height()
		if( ph > 170 ){
			jQuery('.irreg-notification-content').addClass('scroll-y');
		}
		else {
			jQuery('.irreg-notification-content').removeClass('scroll-y');
		}
	}


	function sfsPopupCenter2(pageURL,title,w,h) {
		var left = (screen.width/2)-(w/2);
		var top = (screen.height/2)-(h/2);
		win = window.open (pageURL, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
	}

	jQuery(function ($) {
	
		$('.view_card_transferUrl').click(function(){
			SqueezeBox.open('<?php echo $view_card_transferUrl;?>', {handler: 'iframe', size: {x: 730, y: 700} });
		});

		$('.view_card_mealplanUrl').click( function(){
			SqueezeBox.open('<?php echo $view_card_mealplanUrl;?>', {handler: 'iframe', size: {x: 730, y: 700} });
		});

		$('.save-comment').click(function(e) {
			var da = {'internal_comment': $('#internal_comment').val(), 'passenger_id':$('#passenger_id').val()}
		//{$( 'form' ).serializeArray()
		$.post("index.php?option=com_sfs&task=internalcomment.save",da,
			function(data, status){
				document.location.reload(true);
			///alert("Data: " + data + "\nStatus: " + status);
		});
	});

		var nt_alert = 0;
		$('#sms_text_message').keyup(function(e) {
			var l = parseInt( $(this).val().length );
		//var vmax = $('#count-characters').attr('data-max-length');
		var nl  = 150 - l;

		$('#count-characters').text( nl );
		if( nl < 0 && nt_alert == 0 ){
			nt_alert = 1;
			$('.send_SMS_text_message').attr('disabled', true);
			//backspace == 8, 46 == del
			if(e.keyCode != 8 && e.keyCode != 46 )
				jAlert('Max 150 characters');//alert(' Max 150 characters');
		}
		else {
			nt_alert = 0;
			$('.send_SMS_text_message').removeAttr('disabled');
		}
	});

		$('.send_SMS_text_message').click(function(e) {

			var okButton = 'YES SEND IT';
			var cancelButton = 'NO';
			var global_img_alert_icon_big = '<table style="vertical-align:middle;"><tr><td style="vertical-align:middle;"><img style="width:60px;" src="<?php echo JURI::base().'components/com_sfs/assets/jquery-alert-dialogs/images/alert_icon_big.png';?>" />';

			var t = $('#sms_text_message').val();	
			var data_tel = 	$(this).attr('data-tel');
			if( t.length > 150 ) {
				jAlert(global_img_alert_icon_big + ' </td><td style="vertical-align:middle;">Max 150 characters</td></tr></table>', "");
			//alert(' Max 150 characters');
		}
		else{
			var da = {'text_message': t, 'data_tel': data_tel,
			'flight_number' : '<?php echo $rebooked_fltno->carrier;?><?php echo $rebooked_fltno->flight_no;?>',
			'std' : '<?php echo $rebooked_fltno->std;?>',
			'etd' : '<?php echo $rebooked_fltno->etd;?>'
		}

		jConfirmCustom(okButton, cancelButton, 'Are you sure you want to send the message to the passenger?', 'Confirm', function(r) {
			if( r == true) {

					//{$( 'form' ).serializeArray()
					$.post("index.php?option=com_sfs&task=internalcomment.sendSMS",da,
						function(data, status){				
							if( data.successful == 1 ){
								jAlert(global_img_alert_icon_big + ' </td><td style="vertical-align:middle;">' + data.messages.message.errormessage +'</td></tr></table>', "");
							///jAlert(data.messages.message.errormessage);
							//alert(data.messages.message.errormessage);
							$('#sms_text_message').val("");
						}
						else if( data.errorcode == 'maxl' ) {
							jAlert(global_img_alert_icon_big + ' </td><td style="vertical-align:middle;">' + data.errormessage +'</td></tr></table>', "");
							///jAlert(data.errormessage);
							///alert(data.errormessage);
						}
						else if( data.errorcode != 0 ) {
							jAlert(global_img_alert_icon_big + ' </td><td style="vertical-align:middle;">' + data.errormessage +'</td></tr></table>', "");
							///jAlert(data.errormessage);
							//alert(data.errormessage);
						}
					}, 'json');

				}//End if if( r == true)
				
			});//End jConfirmCustom

		}//End else
	});

	});

	function closeIrregMessage(){
		jQuery('.irreg-notification-content').css({
			'display':'none'
		});
	}
	function showIssueVoucherList( obj, service_id ) {	

	//jQuery('.obj-service').css('display', 'none');
	var obj = jQuery(obj).offset();
	var objD = jQuery(document);
	var obj_w = jQuery('.list-service').width();
	var h = jQuery('.list-service').height();
	jQuery('.list-service').css({
		'display':'block', 
		'top': (obj.top - (h/2))+ 'px',
		'left': ( (objD.width() - obj_w) /2) + 'px'
	});
	if(service_id == 9)
		service_id == 8;

	jQuery('#service' + service_id ).css('display', 'block');

	var taxi_name = "<?php echo $passenger->taxCpnName; ?>";
	var taxi_option = "<?php echo $passenger->option_taxi ?>";
	var taxiFromAddress = "<?php echo $passenger->taxFromAndress ?>";
	var taxiToAddress = "<?php echo $passenger->taxToAddress ?>";
	var taxiToAddress = "<?php echo $passenger->taxToAddress ?>";
	var taxDistance = "<?php echo $passenger->taxDistance ?>";
	var taxTotalPrice = "<?php echo $passenger->taxTotalPrice ?>";
	var taxWayOption = "<?php echo $passenger->taxWayOption ?>";

	if (taxi_name != '') {
		jQuery('.info-service-header-taxi .taxi_name').html(taxi_name);
		jQuery('.info-service-header-taxi .taxi_status').attr('src', '<?php echo JURI::root().'media/media/images/VoucherOK.png' ?>');
		jQuery('.taxi_option[data-option = "option'+taxi_option+'"]').attr('checked', true);
		jQuery('#from-address option[value = "'+taxiFromAddress+'"]').attr('selected',true);
		jQuery('#to-address').val(taxiToAddress);
		jQuery('.taxi-content .location_name').text('To '+taxiToAddress);
		jQuery('.taxi-content .total_distance').text(taxDistance);
		jQuery('.taxi-content .total_price').text(taxTotalPrice);
		// jQuery('.btnTaxiWay[value = "'+taxWayOption+'"]').trigger('click');
	}
	
}
function closeListService(){
	//jQuery('.list-service').css('display', 'none');
	//location.reload();
	window.location ='<?php echo JURI::base().'index.php?option=com_sfs&view=tracepassenger&layout=default_individualpassengerpage&passenger_id='.JRequest::getVar('passenger_id'); ?>';
}

function addInternalService( obj ){
	var obj = jQuery(obj).offset();
	var objD = jQuery(document);
	var obj_w = jQuery('.issue-voucher-list').width();
	var h = jQuery('.issue-voucher-list').height();
	jQuery('.issue-voucher-list').css({
		'display':'block', 
		'top': (obj.top - (h/2))+ 'px',
		'left': ( (objD.width() - obj_w) /3) + 'px'
	});
}

function closeIssueVoucher(){
	jQuery('.issue-voucher-list').css({
		'display':'none'
	});
	document.location.reload(true);
}


//open and close for case sub service other


</script>
<style type="text/css">
	table.tableInfo{
		border-collapse: collapse;margin: 20px 50px;color: #8c523f;
	}

	table.tableInfo, .tableInfo td, .tableInfo th{
		border: 1px solid #cdcdcd;
	}

	.tableInfo th{height: 40px; background-color: #cdcdcd}

	.tableInfo td{
		padding: 10px 0 10px 15px;
	}

	.left_center{
		vertical-align: top;display: table-cell; border: 5px solid #D5E2E8;padding-left: 20px; font-size: 14px;}
		.right_center{
			vertical-align: middle;display: table-cell; border: 5px solid #D5E2E8; border-left: none;padding-left:20px; font-size: 14px!important; color:#8c523f;}
			.mainBottoom{
				width: 100%; border: 10px solid #82adf1;}
				table p{margin : 10px 0;}
				.info_right{float: left; width: 50%}

				.pt7{
					padding-top:7px;
				}
				.pr7{
					padding-right:7px;
				}
				.pb7{
					padding-bottom:7px;
				}
				.pl7{
					padding-left:7px;
				}
				.borderB2{
					border-bottom:4px solid #D5E2E8;
				}
				.pl20{
					padding-left:20px;
				}
				.pl15{
					padding-left:15px;
				}
				.pl10{
					padding-left:10px;
				}
				.height250{
					height:250px;
				}
				.height300{
					height:300px;
				}
				.height450{
					height:450px;
				}
				.height400{
					height:400px;
				}
				.overflow-h{
					overflow:hidden;
				}
				.padding7{
					padding:7px;
				}
				.padding2{
					padding:2px;
				}
				.padding5{
					padding:5px;
				}
				.bg-ddd{
					background-color:#ddd;
				}
				.mgt10{
					margin-top:10px;
				}
				.btn.sm.cc1f2f{
					background-color:#cc1f2f;
					color:#fff;
					text-transform:uppercase;
					/*font-weight:bold;*/
					min-width:100px;
					text-align:center;
					width:100px;
				}
				.irreg-notification-content{
					position:absolute;
					z-index:10;
					border:1px solid #ffcd8c;
					background-color:#ffefbf;
					padding:15px;
					width:250px;
					height:200px;
				}
				.scroll-y{
					overflow-y: scroll;
				}

				.irreg-message-close{
					padding:3px 7px;
					border-radius:10px 20px 10px 20px;
				}

				/* Custom dialog styles */
				#popup_container{
					background:#ffdfbf;
					border:1px solid #ff9e37;
					width:600px !important;

				}
				#popup_container #popup_content{
					overflow:hidden;
					padding:20px 10px;
				}
				#popup_title{
					color: #f00;
					text-align: left;
				}
				#popup_container.style_1 {
					font-family: Georgia, serif;
					color: #A4C6E2;
					background: #005294;
					border-color: #113F66;
				}

				#popup_container.style_1 #popup_title {
					color: #FFF;
					font-weight: normal;
					text-align: left;
					background: #76A5CC;
					border: solid 1px #005294;
					padding-left: 1em;
				}

				#popup_container #popup_content {
					background: none;
				}

				#popup_container #popup_panel {
					padding-left: 0em;
					float:left;
					width:30%;
					margin:0px;
				}
				#popup_container #popup_message {
					float:left;
					width:70%;
					margin:0px;
					padding-left: 0em;
				}

				#popup_container INPUT[type='button'] {
					border: 0px #76A5CC;
					color: #fff;
					background: #ff9e37;
					float:left;
					width:59%;
					margin-right:2px;
				}
				#popup_container INPUT[type='button']#popup_cancel{
					width:38%;
					margin-right:0px;
				}
				.alert-is-hidden-title{
					display:none;
				}
				#popup_container .alert-is-show-title{
					background:none !important;
					border: 0px !important;
					padding-left:7px;
					font-size:16px;
				}
				#popup_container .confirm{
					padding-top:3px !important;
				}
				#popup_container{
					border-radius:2px;
				}
				.canx-confirmation-letter,
				.issue-voucher-list, 
				.list-service {
					background-color: #fff;
					border: 10px solid #97d6e0;
					box-shadow: 9px 9px 3px -4px rgba(0, 0, 0, 0.75);
					padding: 10px;
					position: absolute;
					z-index: 10;
					width:40%;
					margin:auto;
				}
				.alert-col{
					float:left;
				}
				.issue-voucher-list {
					width:60%;
				}
				.list-service{
					width:60%;
				}
				.add_sub_services {
					background-color: #fff;
					border: 10px solid #97d6e0;
					box-shadow: 9px 9px 3px -4px rgba(0, 0, 0, 0.75);
					padding: 10px;
					position: absolute;
					width:40%;
					margin:auto;
				}
				.general-trace li{
					margin-left: 0px;
				}
				.flag_passenger{
					float: left;
					padding: 5px;
					background: #cc2030;
					color: #fff;
					margin-right: 5px;
				}
				.btn-add-flag-pass{
					background: url('<?php echo $link_Img;?>manual_flag_icons.png');
					background-position:0 0px;
					width: 30px;
				    float: right;
				    height: 30px;
				    margin-right: 10px;
				}
				.btn-add-flag-pass:hover{
					background-position:63px 0px;
				}
				.btn-add-flag-fi{
					background: url('<?php echo $link_Img;?>manual_flag_icons.png');
					background-position:0 0px;
					width: 30px;
				    float: right;
				    height: 30px;
				    margin-right: 10px;	
				}
				.btn-add-flag-fi:hover{
					background-position:30px 0px;
				}
			</style>
			<div class="heading-block descript clearfix">
				<div class="heading-block-wrap">
					<h3>Guest Details</h3>
				</div>
			</div>
			<div id="sfs-wrapper" class="main individual-passenger-page" style="padding-top:10px;">
				<form>
					<div class="sfs-above-main search-results-title title-border" style="background-color: #6799C8">
						<h3 class="pull-left">Guest Details</h3>
					</div>
					<table cellpadding="0" cellspacing="0" border="0" width="100%" style="vertical-align:text-top">
						<tr>
							<td width="50%" class="left_center" style="vertical-align:text-top">
								<p style="font-size: 25px; margin-bottom: 0px">Name: <span ><?php echo $passenger->first_name;?>/<?php echo $passenger->last_name;?> <?php echo $passenger->title;?></span>
									<img class="feature_edit" style="float: right;padding-right: 10px; cursor: pointer;" src="<?php echo JURI::base().'images/edit-small-icon.png'; ?>" alt="">
									<button type="button" class="small-button save_passenger" style="float: right; display: none">Save</button>
								</p>
								<p style="font-size: 25px; margin: 0px; height: 55px; overflow: hidden; text-overflow: ellipsis;"> pnr: <span style="color:#01b2c3; text-transform:uppercase;"> 
									<?php echo $passenger->pnrN;?> </span>
									<?php if ( !empty($passengerNex) ) :?>
										<?php
                    /*foreach ( $passengerNex as $p) :
                    $printLinkView = 'index.php?option=com_sfs&view=tracepassenger&layout=default_individualpassengerpage&passenger_id='.$p->id;
                    ?>
                    <a style="font-size: 13px"href="<?php echo $printLinkView;?>">
                        (view PNR detail)
                    </a>
                    <?php
                    endforeach;*/
                    ?>
                <?php endif;?>
            </p>
            <p class="new_pnr" style="<?php echo ($passenger->new_pnr=='')? 'display:none;' : ''; ?>">
            	New pnr: <span class="passenger_new_pnr"><?php echo ($passenger->new_pnr) ? $passenger->new_pnr : "&nbsp;";?></span> <input type="text" style=" width: 175px;margin-left:6px;<?php echo ($passenger->new_pnr!='')? 'display: none':'';  ?>" class="input_new_pnr" value="<?php echo $passenger->new_pnr; ?>">
            </p>
            <p> 
            	Email: <span class="passenger_email"><?php echo ($passenger->email_address) ? $passenger->email_address : "&nbsp;";?></span>
            	<input type="text" style="display: none; width: 200px;margin-left:6px;" class="input_email" value="<?php echo ($passenger->email_address) ? $passenger->email_address : "&nbsp;";?>"><br>
            	Phone: <span class="passenger_phone"><?php echo $passenger->phone_number;?></span>
            	<input class="input_phone" style=" width: 200px; display: none"value="<?php echo $passenger->phone_number;?>">
            </p>
            <p class="guest_relations" style="<?php echo ($passenger->guest_relations_id=='0')? 'display:none;' : ''; ?>">
            	Guest relations ID: <span class="passenger_guest_relations"><?php echo ($passenger->guest_relations_id) ? $passenger->guest_relations_id : "&nbsp;";?></span> <input type="text" id="filter_guest_relations" style=" width: 175px;margin-left:6px;<?php echo ($passenger->guest_relations_id!='')? 'display: none':'';  ?>" class="input_guest_relations" value="<?php if($passenger->guest_relations_id) echo $passenger->guest_relations_id; ?>">
            </p>
            <div style="padding-left:10px;">
            	<p>
            		passenger recieved 
            		<a class="irreg-notification" onmouseover="hhover(this);" onmouseout="closeIrregMessage()"   href="javascript:void(0);" style="color:#01b2c3; text-decoration:underline;"> 
            			this irreg notification
            		</a>
            	</p>
            	<?php 
            	//print_r($this->user);
            	//print_r($this->user->groups);
            	//die;
            	if($this->user->groups[19]){?>
				<p>
					<div class="btn-add-flag-pass"></div>
            	</p>
				<?php
            		} ?>
            	
            	<?php 
            	if($passenger->irreg_reason){
            		$irreg_reason=explode(",",$passenger->irreg_reason);
	            		foreach ($irreg_reason as $key => $value) {
	            			?>
							<div class="flag_passenger" style="margin-bottom:5px;"><?php echo $value; ?></div>
	            			<?php
	            		}
            		} 
            		if($passenger->invoicing_flag){
            			$invoicing_flag = explode("_",$passenger->invoicing_flag);
            			foreach ($invoicing_flag as $key => $value) {
	            			?>
							<div class="flag-item-pass" style="margin-top:0; margin-bottom:5px; margin-left:5px;"><?php echo $value; ?></div>
	            			<?php
	            		}
            		}
            		?>

            	<div style="clear:both;" ></div>	
            	
            	<p>
            		<strong>TOPBONUS status: GOLD <br />
            			<span> Frequent Flyernumber: <?php echo ($passenger->fqtv_number != '' ) ? $passenger->fqtv_number : "N/A";?></span>
            			<br />
            			<span>Program: <?php echo ($passenger->fqtv_program != '' ) ? $passenger->fqtv_program : "N/A"?></span>
            			<br />
            			<span> ticketStatus: <?php echo ($passenger->ticket_status != '' ) ? $passenger->ticket_status : "N/A"?></span><br />
            			<span>bookingClass: <?php echo ($passenger->booking_class != '' ) ? $passenger->booking_class : ""?></span>
            			<br />
            			<span>cabinClass: <?php echo ($passenger->cabin_class != '' ) ? $passenger->cabin_class : "N/A"?></span>
            			<br />
            			<span>Booked through: <br />Touroperator <?php echo ($passenger->tour_operator != '' ) ? $passenger->tour_operator : "N/A"?></span></strong>
            		</p>
            		<p>
            			<span><strong>SEAT: reservation</strong></span>
            			<span style="padding-left:15px">fare: DEAL</span>
            			<br />
            			<?php foreach ($ssr as $value): ?>
            				<?php foreach ($value as $key => $data): ?>
            					<span>SSR:<?php echo $data->type.'  '.$data->key.'  '.$data->type.$data->key?> <!-- ATTN AGT/AB APPLIES MOST RESTRICTIVE TTL --></span>
            					<br />

            				<?php endforeach ?>
            			<?php endforeach ?>
            			<!--<span>SSR:OTHS: FreeText: OK CANCELS IF TKT ADVISED BY 150CT 2359UTC</span>
            			<br />-->
            		</p>
            	</div>

            	<p>
            		<?php if ( !empty($passengerNex) ) :?>
            			<table>
            				<tr>
            					<td style="vertical-align:text-top; padding:0px;font-size: 17px ">
            						Travelling with (group members):
            					</td>
            					<td style="padding-top:0px;">
            						<div style="height: 60px; overflow: hidden;">
            							<?php
            							foreach ( $passengerNex as $p) :
            								$printLinkView = 'index.php?option=com_sfs&view=tracepassenger&layout=default_individualpassengerpage&passenger_id='.$p->id;
            							?>
            							<a href="<?php echo $printLinkView;?>">
            								<?php echo $p->first_name;?>/<?php echo $p->last_name;?> <?php echo $p->title;?>
            							</a>
            							<br />
            							<?php
            							endforeach;
            							?></div>
            						</td>
            					</tr>
            				</table>
            			<?php else:?>
            				&nbsp;                    
            			<?php endif;?>
            		</p>
            	</td>
            	<td width="50%" class="right_center" style="vertical-align:text-top; color: #7F7F7F">
            		<div style="padding-left:20px;"> Flight information:</div>
            		<table style="width:100%;" cellpadding="0" cellspacing="0">
            			<tr>
            				<td width="50%" style="border-right:5px solid #D3E1E7;height: 450px;float: left;width: 250px">
            					<div class="borderB2 pt7 pr7 pb7 pl20">Original:</div>
            					<div class="pl7 pr7 height450 overflow-h">
            						<?php 
            						$connections=json_decode($passenger->connections);    
            						//print_r($connections);die;
            						if( $connections ){

            							foreach ( $connections as $con ) {		
            								if($con->inboundconnection){
            									if(!is_object($con->inboundconnection->dep) && !is_object($con->inboundconnection->dep) ){
            									$pnr=explode("-",$con->inboundconnection->flightref);
            									$style_std='';
            									$style_arr='';
            									if($airport_current_code==$con->inboundconnection->dep){
            										$style_std='style="color:#f00; "';
            									}
            									if($airport_current_code==$con->inboundconnection->arr){
            										$style_arr='style="color:#f00; "';
            									}
            									?>
            									<p style="font-size: 12px;margin:5px 0;" ><span>
	            										STD <?php if($con->inboundconnection->Std){
	            											echo substr($con->inboundconnection->Std,-8,5);
	            										}  
	            										if($con->inboundconnection->std){
	            											echo substr($con->inboundconnection->std,-8,5);
	            										}  
	            										?>
	            									</span>
	            									<span>
	            										<span <?php echo $style_std; ?>><?php echo $con->inboundconnection->dep; ?></span>-<span><?php echo $pnr[0]; ?></span>-<span <?php echo $style_arr; ?>><?php echo $con->inboundconnection->arr ?></span>
	            									</span>
	            									<span>
	            										STA <?php 
	            										if($con->inboundconnection->Sta){
	            											echo substr($con->inboundconnection->Sta,-8,5);
	            										}
	            										if($con->inboundconnection->sta){
	            											echo substr($con->inboundconnection->sta,-8,5);
	            										}
	            										 ?>
	            									</span>
	            								</p>
            								<?php }
            								}
            								
            							}
            						}?>
            						<div style="clear:both"></div>
            						<p class="">
            							
            							From: 
            							<span style="margin-left: 50px">
            								<span style="color:#f00; ">
            									<?php echo $passenger->rebook[0]->dep; ?>
            								</span>
            								<!--<span style="color:#000; ">
            									<?php //echo (isset($passenger->dep) && $passenger->dep != '' ) ? '->' . $passenger->dep . ' ->' : ''; ?>
            								</span>-->

            								<?php echo ($passenger->rebook[0]->arr) ? '->'.$passenger->rebook[0]->arr : '';?>

            								<?php 
            								echo $passenger->airport_code . ' ' . $passenger->distance . ' ' . $passenger->distance_unit;?>

            							</span>
            							<br />
            					
            							Flightnumber: <?php echo $passenger->carrier;?>
            							<?php echo $passenger->flight_no;?>
            							<br />
            							Dep scheduled:<small><?php echo $passenger->std?></small> <br />
            							Arr scheduled:<small><?php echo $passenger->etd?> </small><br />
            							<span style="color:#01b2c3; font-size:10px;">all times are local times</span>

            							<?php if($this->user->groups[19]){ ?>
            							<div style="clear:both;"></div>
						            	<p>
						            		<div class="btn-add-flag-fi">
						            			
						            		</div>
						            	</p>
						            	<?php }?>
            							<?php if($passenger->irreg_reason_fi){
            								$irreg_reason_fi=explode(",",$passenger->irreg_reason_fi);
            								foreach ($irreg_reason_fi as $key => $value) {
            									echo '<div class="flag_passenger" style="margin-bottom:5px;">'.$value.'</div>';
            								}            								
            							} ?>
            							<?php if($passenger->rebook[0]->invoicing_flag){
            								$invoicing_flag=explode(",",$passenger->rebook[0]->invoicing_flag);
            								foreach ($invoicing_flag as $key => $value) {
            									echo '<div class="flag-item-fi" style="margin-bottom:5px;">'.$value.'</div>';
            								}            								
            							} ?>
            							<div style="clear:both;"></div>
            						</p>
            						<?php 
            						if( $connections ){            							
            							foreach ( $connections as $out ) {	
            								if($out->outboundconnection){
            									if(!is_object($out->outboundconnection->dep) && !is_object($out->outboundconnection->arr)){
            									$pnr=explode("-",$out->outboundconnection->flightref);
            									$style_std='';
            									$style_arr='';
            									if($airport_current_code==$out->outboundconnection->dep){
            										$style_std='style="color:#f00; "';
            									}
            									if($airport_current_code==$out->outboundconnection->arr){
            										$style_arr='style="color:#f00; "';
            									}
            									?>
            									<p style="font-size: 12px;margin:5px 0;" ><span>
            										STD <?php echo substr($out->outboundconnection->std,-8,5) ?>
            									</span>
            									<span>
            										<span <?php echo $style_std; ?>><?php echo $out->outboundconnection->dep ?></span>-<span><?php echo $pnr[0]; ?></span>-<span <?php echo $style_arr; ?>><?php echo $out->outboundconnection->arr ?></span>
            									</span>
            									<span>
            										STA <?php echo substr($out->outboundconnection->sta,-8,5) ?>
            									</span>
            								</p>
            								<?php }
            								}
            								
            							}
            						}
            						?>
            						<?php             							
										 if (strlen(strstr($passenger->irreg_reason, 'MAAS')) > 0) {
										    ?>
										    <p class=" overflow-h" style="clear:both;">
		            							<a class="btn cc1f2f sm pull-left">meet and assist</a>
		            						</p>
										    <?php
										  } 
            						?>

            						
            						<p class="bg-ddd padding5 mgt10" style="clear:both;">
            							DEPARTURE INFORMATION <?php echo $passenger->airport_code;?> Aiport <?php echo $passenger->flight_number;?><br />
            							<!--DEP: Terminal 4<br />-->
            							GATE: <?php echo $passenger->gate_info;?>
            						</p>
            						<p>
            							DELAY <!--CAUSED BY-->:<br />
            							<?php
            							if( $passenger->delay != '' ) :
            								$delays = json_decode($passenger->delay);
            							foreach ( $delays as $delay ) :?>
            							<span>Code: <?php echo $delay->DelayCodes;?> Time: <?php echo $delay->DelayTime;?> Min</span><br />
            						<?php endforeach; endif; ?>
            						<a class="a-confirmation-letter" href="javascript:void(0);" style="color:#01b2c3; text-decoration:underline;">Print Delay/Canx confirmation letter</a>
            					</p>

            				</div>
            			</td>
            			<td width="50%" style="padding-right:20px;">
            				<div class="borderB2 padding7" style="margin-left: -6px"> Rebook to:</div>
            				<div class="pl7 pr7 height450 overflow-h">
							<?php if($rebooked_fltno): 

								//foreach($rebooked_fltno as $rebooked):
									for ($i=1; $i < count($rebooked_fltno) ; $i++) {
									 ?>
									<p class="">
										From: 
	            						<span style="margin-left: 50px">	            		<span style="color:#f00; ">
            									<?php echo $airport_current_code; ?>
            								</span>
	            							<span style="color:#000; ">
	            								<?php echo (isset($rebooked_fltno[$i]->arr) && $rebooked_fltno[$i]->arr != '' ) ? '->' . $rebooked_fltno[$i]->arr : ''; ?>
	            							</span>

	            							<?php //echo ($rebooked->arr) ? $rebooked->arr : '';?>

	            							<?php 
	            							//echo $rebooked->airport_code . ' ' . $rebooked->distance . ' ' . $rebooked->distance_unit;?>

	            						</span>
	            						<br />
	            						Flightnumber: <?php echo $rebooked_fltno[$i]->carrier;?>
	            						<?php echo $rebooked_fltno[$i]->flight_no;?>
	            						<br />
	            						Dep scheduled:<small><?php echo str_replace("T"," ",$rebooked_fltno[$i]->std); ?> </small><br />
	            						Arr scheduled:<small><?php echo str_replace("T"," ",$rebooked_fltno[$i]->etd);?> </small><br />
	            						<span style="color:#01b2c3; font-size:10px;">all times are local times</span>
									</p>
									<?php
								}
								//endfor;
							endif; ?>

            				<?php /*if($rebooked_fltno->carrier!='' ): ?>
            					<p class="">
            						From: 
            						<span style="margin-left: 50px">
            							<span style="color:#f00; ">
            								<?php echo $airport_current_code; ?>
            							</span>
            							<span style="color:#000; ">
            								<?php echo (isset($passenger->dep) && $passenger->dep != '' ) ? '->' . $passenger->dep . ' ->' : ''; ?>
            							</span>

            							<?php echo ($passenger->arr) ? $passenger->arr : '';?>

            							<?php 
            							echo $passenger->airport_code . ' ' . $passenger->distance . ' ' . $passenger->distance_unit;?>

            						</span>
            						<br />
            						Flightnumber: <?php echo $rebooked_fltno->carrier;?>
            						<?php echo $rebooked_fltno->flight_no;?>
            						<br />
            						Dep scheduled:<small><?php echo $rebooked_fltno->std ?> </small><br />
            						Arr scheduled:<small><?php echo $rebooked_fltno->etd?> </small><br />
            						<span style="color:#01b2c3; font-size:10px;">all times are local times</span>
            					</p>
            					<p class="bg-ddd padding5 mgt10" style="clear:both; margin-top: 68px; width: 226px">
            						DEPARTURE INFORMATION <?php echo $passenger->airport_code;?> Aiport <?php echo $passenger->flight_number;?><br />
            						<!--DEP: Terminal 4<br />-->
            						GATE: <?php echo $passenger->gate_info;?>
            					</p>
            				<?php endif; */?>
            				</div>

            			</td>
            		</tr>
            	</table>
            </td>
        </tr>
    </table>

    <div class="mainBottoom">        
    	<table cellpadding="0" cellspacing="0" border="0" width="90%" id="tableInfo" class="tableInfo">
    		<tr>
    			<th width="14%">Service</th>
    			<th width="28%">Details</th>
    			<th width="28%">Voucher</th>
    			<th width="30%">Communication / Comments</th>
    		</tr>
    		<?php 
			//print_r($passenger);die();

    		foreach($passenger->services_of_passenger as $service ):?>
    		<tr>

    			<td width="14%" align="center" style="vertical-align: center; padding: 10px;">
    				<?php 
    				// $val_irreg_reason = (int)$passenger->irreg_reason;
    				// 	if($val_irreg_reason != 0){
    				// 		if ($val_irreg_reason == 8) {
    				// 			echo "MAAS";
    				// 		}
    				// 		else{
    				// 			echo "WAAS";
    				// 		}
    				// 	}
    				// 	else{
    				// 		echo $service->name_service;
    				// 	}
    				echo $service->name_service;
    				?>
    			</td>
    			<td width="28%" style="vertical-align: top;">
    				<?php 
                    //  Hotel
    				if($service->id==1 && $passenger->hotel_id){
    					?>
    					<div class="detail-hotel">
    						<p>
    							<span>Hotel:</span><?php echo $passenger->hotel_name; ?><br/>
    							<span>Phone:</span><?php echo $passenger->hotel_phone; ?>
    						</p>											
    						<p>
    							Breakfast: <?php echo ((int)$passenger->breakfast==1)?"Yes":"No";?><br />
    							Lunch: <?php echo ((int)$passenger->lunch==1)?"Yes":"No";?><br />
    							Dinner: <?php echo ((int)$passenger->mealplan==1)?"Yes":"No";?><br />
    						</p>
    						<?php //echo date('d-M-Y', strtotime( $passenger->from_date ) ) ;?> 
    						<?php //echo date('d-M-Y', strtotime($passenger->end_date ) ) ;?>
    					</p>	                            
    				</div>
    				<?php
    			}
                    //Refreshment
    			if($service->id==2){
    				echo '<p>'.round($passenger->refreshment_amount,2).' '.$passenger->refreshment_currency.' mealplan voucher</p>';
    			}
                    //Bus Transfer
    			if($service->id==3){
    				if( !empty( $this->item->group_transport_company ) ) : ?>
    					<p>Connection 
    						<?php echo $this->item->group_transport_company->group_transportations->name;?></p>
    						<p><span>Phone: 
    							<?php echo $this->item->group_transport_company->group_transportations->mobile;?></span></p>
    						<?php endif;?>
    						<p><?php foreach ( $this->item->group_transport_company->types as $vArrSub){
    							if($vArrSub->id == $this->item->group_transportation_types_id){
    								echo $vArrSub->name.' bus';
    							}
    						}
    						?></p>
    						<p>Pick up time: <?php echo $this->item->date_expire_time ?> </p>
    						<p>Pick up at: <?php foreach ($nameAirport as $key => $value) {
    							if($value->id==$this->item->airline_airport_id){
    								echo $value->name; break;
    							}
    						}

    						?></p>
    						<p>To: <span class="location_to"></span></p>
    						<?php
    					}
                    //Taxi Transfer
    					if($service->id==4){
    						if($passenger->taxi_id!=''){?>
    							<p>
    								Taxi: <?php echo $passenger->taxCpnName; ?>
    								<br/>
    								Phone: <?php echo $passenger->taxCpnTelephone;?>
    								<br/>
    								<?php
    								if (!(empty($passenger->taxCpnMobile_phone))) {
    									echo "Mobile Phone: ".$passenger->taxCpnMobile_phone;
    									echo "<br/>";
    								}
    								?>

    							</p>
    							<?php
    							echo $infoWayTaxi = ($passenger->option_taxi==1)?'<p>From Airport To Hotel</p>':'';
    							echo $infoWayTaxi = ($passenger->option_taxi==2)?'<p>Return to airport as well</p>':'';
    							echo $infoWayTaxi = ($passenger->option_taxi==3)?'<p>To Hotel </p>':'';
    							echo $infoWayTaxi = ($passenger->option_taxi==4)?'<p>Other Location</p>':'';                  	
    							echo "To: ".$passenger->taxFromAndress."<br/>";
    							echo "Distance to ".$passenger->taxToAddress.' '.round($passenger->taxDistance, 1)." Km<br/>";
    							$wayNumber = ($passenger->taxWayOption == 2)? 'two':'one';
    							echo "Estimated taxi costs ".$wayNumber." way trip: ".round($passenger->taxTotalPrice,2)." Euro<br/>";
    						}

    					}
                    //Train service
    					if($service->id==5){                    	
    						?>
    						<p>From Transtation: <?php 
    							foreach ($this->airlinetrains as $airlinetrain):
    								if($airlinetrain->id==$passenger->id_from_trainstation){
    									echo $airlinetrain->cityname.' '.$airlinetrain->stationname;
    									break;
    								}
    								endforeach;?> </p>
    								<p>To Transtation: <?php 
    									foreach ($this->airlinetrains as $airlinetrain):
    										if($airlinetrain->id==$passenger->id_to_trainstation){
    											echo $airlinetrain->cityname.' '.$airlinetrain->stationname;
    											break;
    										}
    										endforeach;?></p>
    										<p>Valid for Travel on: <?php echo $passenger->travel_date; ?></p>						
    										<?php
    									}
                    //Rental car
    									if($service->id==6){
    										?>
    										<p><span>Local office: <?php echo $passenger->name_company.' '.$passenger->address_company.', '.$passenger->zipcode_company.' '.$passenger->city_company; ?></span></p>
    										<p><span>Phone:<?php echo $passenger->telephone_company; ?></span></p>
    										<p>Pick up location:<?php 
    											if($this->rentalcars){
    												foreach($this->rentalcars as $ren){
    													foreach($ren->rentallocation as $lo){
    														if($passenger->pick_up == $lo->id){
    															echo  $lo->name_code.' '.$lo->city.' '.$lo->name_airport; 
    															break;
    														}
    													}
    												}
    											}
    											?></p>
    											<p>
    												Drop off location:<?php 
    												if($this->rentalcars){
    													foreach($this->rentalcars as $ren){
    														foreach($ren->rentallocation as $lo){
    															if($passenger->drop_off == $lo->id){
    																echo  $lo->name_code.' '.$lo->city.' '.$lo->name_airport; 
    																break;
    															}
    														}
    													}
    												}
    												?>
    											</p>
    											<?php

    										}
                    //Other
    										if($service->id==8 || $service->id== 9){
    											?>
    											<p>Status: <?php echo (int)$service->status ? "Active":"Not Active" ?></p>
    											<?php
    										}?>


    										<?php if ($service->id ==10): ?>
    											<p>Status: <?php echo (int)$contentSubService[0]->status_info_ss_snackbags ? 'Active':'No active' ?></p>
    											<p>Internal Comment:<?php echo (string)$contentSubService[0]->internal_comment ?></p>
    										<?php endif ?>


    										<?php if ($service->id ==11): ?>
    											<p>Description: <?php echo (string)$contentSubService[0]->description?></p>

    											<p>Amount: <?php echo $contentSubService[0]->inputamount.' '.$contentSubService[0]->currency_code ?></p>

    											<p>Internal Comment: <?php echo $contentSubService[0]->internal_comment ?></p>

    										<?php endif ?>

    										<?php if ($service->id ==12): ?>
    											<p>Amount: <?php echo $contentSubService[0]->inputamount.' '.$contentSubService[0]->currency_code ?></p>

    											<p>Internal Comment: <?php echo $contentSubService[0]->internal_comment ?></p>

    										<?php endif ?>

    										<?php if ($service->id ==14): ?>
    											<p>Title: <?php echo $contentSubService[0]->title ?></p>

    											<p>Description: <?php echo $contentSubService[0]->description ?></p>

    											<p>Amount: <?php echo $contentSubService[0]->inputamount.' '.$contentSubService[0]->currency_code ?></p>

    											<p>Internal Comment: <?php echo $contentSubService[0]->internal_comment ?></p>

    										<?php endif ?>

    									</td>
    									<td width="28%"  style="vertical-align: top;">
    										<button onclick="showIssueVoucherList(this, '<?php echo $service->id;?>')" type="button" data-id="<?php echo $service->id;?>" class="small-button issue-voucher <?php if($service->id==1) echo 'service-hotel'; ?>"><?php echo ($this->item->status_issuevoucher==0)? 'issue voucher':'Show issued voucher' ?></button>
    									</td>
    									<td width="30%"  style="vertical-align: top;">
    										<?php 
    										if($service->id==1 && $passenger->hotel_id){
    											if($passenger->blockcode){?>
    												<p><span>blockcode: </span><?php echo $passenger->blockcode; ?></p>
    												<p><span>vouchercode:</span><?php echo $passenger->voucher_code ?></p>
    											<?php }
    										}else{
    											if($service->id){
    												if($service->block_code){
    													?>
    												<p><span>blockcode: </span><?php echo $service->block_code; ?></p>
    												
    												<?php
    												}    												
    											}
    										}
    										?>
    									</td>
    								</tr>
    							<?php endforeach;?>

    							<tr>

    								<td width="14%" align="center" style="vertical-align: center; padding: 10px;">
    									General
    								</td>
    								<td width="28%" style="vertical-align: top;" colspan="3">
    									<?php /*?><p>
    										SMS: <?php echo date("d-m-Y");?> user: <?php 
    										$airline	= SFactory::getAirline();
    										$sender_title = $airline->params['sender_title'];
    										echo $sender_title;?>
    									</p><?php */?>
    									<ul class="general-trace">
    										<li style="list-style-type:none;">
    											<span>Internal comment:</span>
    										</li>
    										<?php foreach ( $passenger->internal_comment as $comment ) :?>
    											<li style="list-style-type:none;">                        
    												<?php echo date("d/m/Y", strtotime( $comment->created_date ) ). ' ' . $comment->name;?>:<br />
    												<?php echo $comment->comment;?>
    											<?php endforeach;?>
    										</ul>
                    <!--<p>
                    	Your onward flight 
						<?php echo $rebooked_fltno->carrier;?><?php echo $rebooked_fltno->flight_no;?> to Berlin is rescheduled to Departure: <?php echo $rebooked_fltno->std ?> from Praag airport Ruzyne
					</p>-->
				</td>
			</tr>

			<tr>
				<td width="14%" align="center" style="vertical-align: center; padding: 10px;">
					Internal Comment
				</td>
				<td  style="vertical-align: top;" colspan="2">
					<table width="100%" style="border:0px;" cellpadding="0" cellspacing="0" >
						<tr>
							<td style="border:0px;padding-left:0px!important;">
								<textarea role="30" name="internal_comment" id="internal_comment" cols="4" style="width:100%;"><?php //echo ($passenger->comment);?></textarea>
							</td>
							<td width="15%" style="border:0px; padding-right:15px;">
								<button type="button" class="small-button save-comment">Save</button>
							</td>
						</tr>
					</table>
				</td>
				
				<td width="30%" style="vertical-align: middle; text-align:center;" align="center">
					<?php if($this->user->groups[11]==11):?>
						<a class=" small-button " onclick="addInternalService(this);" href="javascript:void(0);" style="width: 150px;">Add Service</a><!--fancybox fancybox.iframe-->
						<?php endif; ?>

				</td>
			</tr>
		</table>  
		<?php if($this->user->groups[11]==11):?>
		<table cellpadding="0" cellspacing="0" border="0" width="90%" class="tableInfo">
			<tr>
				<th align="left" colspan="3" class="pl15">Guest contact options</th>
			</tr>
			<tr>
				<td width="15%" style="border:0px;">SMS Text message</td>
				<td style="border:0px;">
					<textarea name="sms_text_message" id="sms_text_message" role="30" cols="4" style="width:100%;"></textarea>
					<span class="pull-right">max <span id="count-characters">150</span> characters</span>
				</td>
				<td width="15%" style="border:0px; padding-right:15px;">
					<button type="button" data-tel="<?php echo $passenger->phone_number;?>" class="small-button send_SMS_text_message">Send SMS Text message</button>

				</td>
			</tr>
		</table>
		<?php endif; ?>

		<div class="passenger-button" style="margin-left: 50px;">
			<?php
			if( (int)$passenger->status != 3 ) :
				$printLink  = 'index.php?option=com_sfs&view=tracepassenger&layout=additional_services&airplus_id='.$passenger->airplus_id.'&tmpl=component';
			?>
			<div style="padding-bottom: 10px; display: block; overflow:hidden;">
				<!--index.php?option=com_sfs&view=tracepassenger&Itemid=136-->
				<a href="javascript:history.go(-1);" class="btn orange sm pull-left">Back</a>
				<a class="fancybox fancybox.iframe small-button pull-right" href="<?php echo $printLink;?>" style="width: 100px; display: none">Add Service</a>
			</div>
		<?php endif;?>                    
	</div>      
</div>   
<input type="hidden" id="passenger_id" name="passenger_id" value="<?php echo $passenger->id;?>" />
<input type="hidden" id="reservationid" name="reservationid" value="<?php echo $passenger->reservationid; ?>" />
<input type="hidden" id="blockdate" name="blockdate" value="<?php echo $passenger->blockdate; ?>" />
<input type="hidden" id="hotel_name" name="hotel_name" value="<?php echo $passenger->hotel_name; ?>" />
<input type="hidden" id="passsenger_name" name="passsenger_name" value="<?php echo $passenger->title;?> <?php echo $passenger->first_name;?> / <?php echo $passenger->last_name;?> " />

<input type="hidden" id="isvIdTitleAirline" name="isvIdTitleAirline" value="<?php echo $passenger->isvIdTitleAirline; ?>" />
<input type="hidden" id="isvDescription" name="isvDescription" value="<?php echo $passenger->isvDescription; ?>" />
<input type="hidden" id="isvNumberCosts" name="isvNumberCosts" value="<?php echo $passenger->isvNumberCosts; ?>" />
<input type="hidden" id="isvCodeCurrency" name="isvCodeCurrency" value="<?php echo $passenger->isvCodeCurrency; ?>" />
<input type="hidden" id="isvInternalComment" name="isvInternalComment" value="<?php echo $passenger->isvInternalComment; ?>" />
<input type="hidden" id="isvTitleAirline" name="isvTitleAirline" value="<?php echo $passenger->isvTitleAirline; ?>" />
</form>

</div>
<?php //endif;?>

<div class="canx-confirmation-letter" style="display:none;">
	<?php echo $this->loadTemplate('confirmation-letter');?>
</div>
<div class="irreg-notification-content" style="display:none;">
	<div style="position:absolute; right:10px; top:10px;">
		<a class="btn orange irreg-message-close" onclick="closeIrregMessage();" href="javascript:void(0);">Close</a>
	</div>
	<p class="irreg-content">
		<?php echo $passenger->irreg_message;?>
	</p>
</div>
<div class="issue-voucher-list" style="display:none;">
	<?php echo $this->loadTemplate('issuevoucher');?>
	
</div>
<?php if($this->user->groups[19]){?>
<div class="popup-add-flag" style="display:none;">
	<?php echo $this->loadTemplate('addflagpassenger');?>
	<?php echo $this->loadTemplate('addflagflightinfo');?>
	<div style="clear:both;"></div>
</div>
<?php }?>
<!-- <div class="add_sub_services" style="display:none;">
	<?php //echo $this->loadTemplate('sub_other_service');?>
	</div>
</div> -->

<div class="list-service" style="display:none;">
	<div class="none box-s content-comment" id="content-issue">
		<?php echo $this->loadTemplate('list-service');?>
	</div>
</div>



<style type="text/css">
	.small-button.issue-voucher {
		width: 150px!important;
	}
	.popup-add-flag{
		position: absolute;
	    top: 100px;
	    background: #fff;
	    padding: 10px;
	    border: 5px solid #fdd04f;
	    -webkit-border-radius: 5px;
		-moz-border-radius: 5px;
		border-radius: 5px;
	}
	.btn-clode-add-flag{
		float: right;
	    background: #ffa54d;
	    color: #fff;
	    padding: 5px 15px;
	}
	.btn-add-flag-fi-icon{
		background:  url('<?php echo $link_Img; ?>manual_flag_icons.png');
	    background-position: 30px 0px;
	    width: 30px;
	    height: 30px;
	    margin-right: 10px;
	    float: left;
	    cursor:pointer;
	}
	.list-info-flag-fi-title
	{
		float: left;
	    height: 30px;
	    font-weight: bold;
	    line-height: 30px;  
	}
	.content-add-flag-fi{
		width: 700px;
	}
	.list-info-flag-fi{
		margin-top: 50px;
		padding-left: 50px;
	}
	.title-fi-second{
		font-weight: bold;
	}
	.flag-item-fi{
		background: #db01db;
	    color: #fff;
	    width: 60px;
	    padding: 5px 5px;
	    text-align: center;	    
	    float: left;
	    margin-right: 5px;

	}
	.info-flag-fi-left{
		float: left;
		margin-right: 10px;
	}
	.info-flag-fi-right{
		float: left;
	}
	.tb-list-aircraft td,.tb-list-aircraft th{		
    	padding-right: 15px;
	}
	.tb-list-aircraft td
	{
		padding-bottom: 10px;
	}
	.btn-add-flag-pass-icon{
		background:  url('<?php echo $link_Img; ?>manual_flag_icons.png');
	    background-position: 63px 0px;
	    width: 30px;
	    height: 30px;
	    margin-right: 10px;
		float: left;
		cursor:pointer;
	}
	.list-info-flag-pass-title{
		float: left;
	    height: 30px;
	    font-weight: bold;
	    line-height: 30px;    
	}
	.flag-item-pass{
		background: #6699c8;
	    color: #fff;
	    width: 70px;
	    padding: 5px 5px;
	    text-align: center;
	    margin-top: 20px;
	    float: left;
	}
	.content-add-flag-pass{
		width: 600px;
	}
	.list-info-flag-pass{
		margin-top: 50px;
		padding-left: 50px;
	}
</style>

<!-- code save subservice other -->
<script type="text/javascript">	
jQuery.noConflict();

		jQuery(function($){
			jQuery('.btn-add-flag-fi').click(function($){
				var top = jQuery(this).offset().top+30;
				var left = jQuery(this).offset().left/2;
				jQuery('.popup-add-flag').css('top',top);
				jQuery('.popup-add-flag').css('left',left);
				jQuery('.popup-add-flag').css('display','block');
				jQuery('.content-add-flag-pass').css('display','none');
				jQuery('.content-add-flag-fi').css('display','block');
			});
			jQuery('.btn-add-flag-pass').click(function($){
				var top = jQuery(this).offset().top+30;
				var left = jQuery(this).offset().left/2;
				jQuery('.popup-add-flag').css('top',top);
				jQuery('.popup-add-flag').css('left',left);
				jQuery('.popup-add-flag').css('display','block');
				jQuery('.content-add-flag-pass').css('display','block');
				jQuery('.content-add-flag-fi').css('display','none');
			});
			jQuery('.btn-clode-add-flag').click(function($){
				jQuery('.popup-add-flag').css('display','none');
				jQuery('.content-add-flag-pass').css('display','none');
				jQuery('.content-add-flag-fi').css('display','none');
				document.location.reload(true);
			});
			jQuery("#filter_guest_relations").keydown(function (e) {
		        // Allow: backspace, delete, tab, escape, enter and .
		        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
		             // Allow: Ctrl+A, Command+A
		            (e.keyCode == 65 && ( e.ctrlKey === true || e.metaKey === true ) ) || 
		             // Allow: home, end, left, right, down, up
		            (e.keyCode >= 35 && e.keyCode <= 40)) {
		                 // let it happen, don't do anything
		                 return;
		        }
		        // Ensure that it is a number and stop the keypress
		        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
		            e.preventDefault();
		        }
	    	});


			jQuery(document).ready(function($) {
				$('.sub_service_id').val('<?php echo (int)$passenger->idSubService->service_id; ?>');

				$('.inputamount').keypress(function (event) {
					return isNumber(event, this)
				});
			});

		//begin script for ss_other_snackbags
			//status when user choice active
			// var sub_service_id = ;
			var status_info_ss_snackbags = 1;
			$('.info-ss-snackbags .checkbox').on('change', function() {
				var sub_service_id = $('.sub_service_id').val();
				if($(this).hasClass('checked')){
					$('.info-ss-snackbags .ss-title').text('Snackbags Enabled');
					status_info_ss_snackbags = 1;
				}
				else{
					$('.info-ss-snackbags .ss-title').text('Snackbags Disable');
					status_info_ss_snackbags = 0;
				}

				var internal_comment = $('.info-ss-snackbags .internal_comment').val();
				var passenger_ids = <?php echo (int)$passenger->id; ?>;

				var data_content = {
					status_info_ss_snackbags: status_info_ss_snackbags,
					internal_comment: internal_comment
				}

				saveOtherServices(passenger_ids,sub_service_id,data_content);

			});

			$('.info-ss-snackbags .save_ss_snackbags').on('click', function() {
				var sub_service_id = $('.sub_service_id').val();
				var internal_comment = $('.info-ss-snackbags .internal_comment').val();
				var passenger_ids = <?php echo (int)$passenger->id; ?>;

				var data_content = {
					status_info_ss_snackbags: status_info_ss_snackbags,
					internal_comment: internal_comment
				}

				saveOtherServices(passenger_ids,sub_service_id,data_content);
				
				
			});


		//end script 

		//begin script for ss_other_phonecard

		$('.info-ss-phonecard .save_ss_phonecard').on('click', function() {
			var sub_service_id 		= $('.sub_service_id').val();
			var description 		= $('.info-ss-phonecard .description').val();
			var inputamount 		= $('.info-ss-phonecard .inputamount').val();
			var currency_code 		= $('.info-ss-phonecard .currency_code').val();
			var internal_comment 	= $('.info-ss-phonecard .internal_comment').val();

			var passenger_ids = <?php echo (int)$passenger->id; ?>;

			var data_content = {
				description: description,
				inputamount: inputamount,
				currency_code:currency_code,
				internal_comment:internal_comment
			}

			saveOtherServices(passenger_ids,sub_service_id,data_content);


		});

		//end script 

		//begin script for ss-cash

		$('.info-ss-cash .save_ss_cash').on('click', function() {
			var sub_service_id 		= $('.sub_service_id').val();
			var inputamount 		= $('.info-ss-cash .inputamount').val();
			var currency_code 		= $('.info-ss-cash .currency_code').val();
			var internal_comment 	= $('.info-ss-cash .internal_comment').val();

			var passenger_ids = <?php echo (int)$passenger->id; ?>;

			var data_content = {
				inputamount: inputamount,
				currency_code:currency_code,
				internal_comment:internal_comment
			}

			saveOtherServices(passenger_ids,sub_service_id,data_content);


		});

		//end script 


		//begin script for ss-cash

		$('.info-ss-miscellaneous .save_ss_miscellaneous').on('click', function() {
			var sub_service_id 		= $('.sub_service_id').val();
			var title 		 		= $('.info-ss-miscellaneous .title').val();
			var description 		= $('.info-ss-miscellaneous .description').val();
			var inputamount 		= $('.info-ss-miscellaneous .inputamount').val();
			var currency_code 		= $('.info-ss-miscellaneous .currency_code').val();
			var internal_comment 	= $('.info-ss-miscellaneous .internal_comment').val();

			var passenger_ids =	<?php echo (int)$passenger->id; ?>;

			var data_content = {
				title:title,
				description:description,
				inputamount: inputamount,
				currency_code:currency_code,
				internal_comment:internal_comment
			}

			saveOtherServices(passenger_ids,sub_service_id,data_content);


		});

		//end script 

function saveOtherServices(passenger_ids,sub_service_id,data_content){
	// alert(sub_service_id);
	$.ajax({
		url: '<?php echo JURI::base().'index.php?option=com_sfs&task=Passengersimport.saveOtherServices'; ?>',
		type: 'GET',
		data: {
			passenger_ids: passenger_ids.toString(),
			sub_service_id:sub_service_id,
			data_content : data_content
		},
	})
	.done(function(success) {
		if (success >0) {
			$('.info-ss-header-maas .icon_train').attr("src","<?php echo JURI::root().'media/media/images/VoucherOK.png' ?>");
		}
	})
	.fail(function() {
		console.log("error");
	});
}



function isNumber(evt, element) {
	var charCode = (evt.which) ? evt.which : event.keyCode;

	if ((charCode != 46 || $(element).val().indexOf('.') != -1) && (charCode < 48 || charCode > 57))
		return false;

	return true;
}    

	});
</script>
