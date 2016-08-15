<?php
defined('_JEXEC') or die;
$toolTipArray = array('className'=>'tooltip-custom');
JHTML::_('behavior.tooltip', '.hasTip2', $toolTipArray);
$link_Img = JURI::root().'media/media/images/select-pass-icons';
$app = JFactory::getApplication();

require_once JPATH_SITE.'/modules/mod_sfs_change_airport/helper.php';

?>
<style type="text/css">
	.head-select-passenger{
		border-top: 3px solid #97D6E0;
		background-color: #EFF8FF;
	}
	.title-feature td{
		padding: 0px 10px;
		font-weight: bold;  
	}
	button.bg-button-none{
		background-color:transparent;
	}
	#sbox-overlay{
		opacity: 0.7 !important;
	}
	#sbox-window{
		border: solid 1px #01B2C3;
		top: 20px !important;
		position: fixed !important;
	} 
	.none{
		display:none;
	}
	
	.box-s{
		border:1px solid #97d6e0;
		position:absolute;
		background-color:#eff8ff;
		z-index:10;
		padding:10px;
		
/*		-webkit-box-shadow: 9px 9px 3px -4px rgba(0,0,0,0.75);
-moz-box-shadow: 9px 9px 3px -4px rgba(0,0,0,0.75);*/
box-shadow: 2px 2px 2px #999;
}
.send-sms-loading, .save-comment-loading{
	display:none;
	position:absolute;
	right:150px;
	top:-10px;
}
.save-comment-loading{
	right: 159px;
	top: 53px;
}
.pnr{
	padding-left:25px;
	display:inline-block;
}
.pas-name{
	width:150px;
	display:inline-block;
}
div{
	text-align:left;
}
.f-passengersimport{
	position: relative;
}
.sfs-white-wrapper{
	position: relative;
}

</style>
<script>
	var pas_id = "";
	var pas_id_com = "";
	var v_option_taxi = '';
	var v_taxi_id = '';//use default to in file default_service_taxi.php
	var v_voucher_id = '';
	var v_voucher_groups_id = '';
	var v_flight_number = '';
	jQuery(function( $ ){
		setTimeout(function(){
			checkpassengersamegroup();
		}, 2000);
		
		$('.open-form-content-group').click(function(e) {	
			getPassengerAddGroup( $ );  
			var t = $('.open-form-content-group').offset().top/2;
			//var l = $('.open-form-content-group').offset().left;
			var l = parseInt($('.sfs-main-wrapper').offset().left-$('#content-group').offset().left);
			$('.box-s').css({"display":"none"});
			$('#content-group').css({"display":"block", "top":(t+50)+"px", "left":(l/2)+"px"});  			
			/*var pas_id_group = getPassengerCheck($);		
			$.ajax({
                url:"<?php echo JURI::base().'index.php?option=com_sfs&task=ajax.createGroupPassenger&format=raw'; ?>",
                type:"POST",
                data:{
                	pas_id_group:pas_id_group,
                },
                dataType: 'json',
                success:function(response){  
                	$('#group-service').val(response['group_id']);  
                	        
                }
            });	*/		
        });
		
		$('.open-form-content-service').click(function(e) {

			var list_name_passenger = '';
			pas_id = getPassengerCheck($);
			if(pas_id.length>0){
				var flag=true;
				if($('.content-passenger-import input[type="checkbox"]').length>pas_id.length){
					checkpassengersamegroup($('#'+pas_id[0]).attr('data-group'));
					pas_id = getPassengerCheck($);
					var data_group_id=$('#'+pas_id[0]).attr('data-group');
					var curren_group = 0;
					pas_id.each(function(index,element){
						if(curren_group!=jQuery('#'+index).attr('data-group')){
							checkpassengersamegroup(jQuery('#'+index).attr('data-group'));
							curren_group = jQuery('#'+index).attr('data-group');
						}						
						// if(data_group_id!=jQuery('#'+index).attr('data-group')){				
						// 	flag=false;
						// }
					}); 
					pas_id.each(function(index,element) {
						list_name_passenger+=$('#'+index).attr('data-name')+'<br/>';
						$('.add_sub_services .list-passenger').html(list_name_passenger);
						$('#issue_sub_other .info_other_service').val($('#'+index).attr('data-other-service-content'));

						$('#issue_sub_other .info_other_service_id').val($('#'+index).attr('data-other-service-id'));
					});
				}
				
				if(flag==true){
					if(pas_id.length==1){
						var list_service='';
						if($('#pass-service-'+pas_id[0]).val()!=''){
							list_service=$('#pass-service-'+pas_id[0]).val().split(',');
						}
						if(list_service.length>0){
							$.each(list_service, function( index, value ) {
								$('#add-service-'+value).trigger( "click" );
								if(value == 2){
									var refreshmentAmount = $('#'+pas_id[0]).attr('data-refreshmentAmount');
									var refreshmentCurrency = $('#'+pas_id[0]).attr('data-refreshmentCurrency');
									if (refreshmentAmount == 5) {
										$('.option_refreshment .checkbox[value = "5"]').addClass('checked').children('input').prop('checked', true);
									}else if(refreshmentAmount == 5){
										$('.option_refreshment .checkbox[value = "10"]').addClass('checked').children('input').prop('checked', true);
									}else{
										$('.option_refreshment .checkbox[value = "-1"]').addClass('checked').children('input').prop('checked', true);
										$('.price_of_refreshment_other').show();
										$('.price_of_refreshment_other .amount').val(refreshmentAmount);
										$('.price_of_refreshment_other .currency').val(refreshmentCurrency);
										
									}
								}

								if (value > 7) {
									jQuery('#add-service-'+value).addClass('checked');
									jQuery('#add-service-7 input').prop('checked', true);
									jQuery('.all-services .sub_service_7').show();
								}
							});
						}
					}								
					var t = $(this).offset().top/2;
					var l = parseInt($('.sfs-main-wrapper').offset().left-$('#content-service').offset().left);
					//var l = $(this).offset().left;
					$('.box-s').css({"display":"none"});
					$('#content-service').css({"display":"block", "top":(t+50)+"px", "left":(l/4)+"px"});
				}else{
					alert('Please passengers sample group');
				}				
			}
			else{
				alert('Please select one or multiple passengers');
			}            
		});

		$('.open-form-content-messge').click(function(e) {			
			var t = $(this).offset().top/2;
			var l = parseInt($('.sfs-main-wrapper').offset().left-$('#content-messge').offset().left);
			//var l = $(this).offset().left;
			$('.box-s').css({"display":"none"});
			$('#content-messge').css({"display":"block", "top":(t+50)+"px", "left":(l/2)+"px"});
		});
		
		$('.open-form-content-assign').click(function(e) {
			var t = $(this).offset().top/2;
			var l = parseInt($('.sfs-main-wrapper').offset().left-$('#content-assign').offset().left);
			//var l = $(this).offset().left;

			$('.box-s').css({"display":"none"});
			$('#content-assign').css({"display":"block", "top":(t+50)+"px", "left":(l/2)+"px"});
		});
		
		$('.open-form-content-comment').click(function(e) {
			pas_id_com = getPassengerCheck($);
			if(pas_id_com.length>0){
				$('.box-s').css({"display":"none"});
				checkpassengersamegroup($('#'+pas_id_com[0]).attr('data-group'))
				pas_id_com = getPassengerCheck($);
				var t = $(this).offset().top/2;							
				var l = parseInt($('.sfs-main-wrapper').offset().left-$('#content-comment').offset().left);
				//var l = $(this).offset().left;
				$('#content-comment').css({"display":"block", "top":(t+50)+"px", "left":(l/2)+"px"});
			}else{
				alert('Please select one or multiple passengers');
			}			
		});

		var arrCheckname 	= [];
		var arrCheckid 		= [];
		var html = "";
		$(':checkbox').click(function(){ 
			var id = $(this).attr('id');
			$("#"+id).change(function(event) {
				var name = $(this).attr('data-name');
				var checkedval = $("#"+id).is(":checked");
				if(checkedval){
					arrCheckname.push(name);
					arrCheckid.push(id);
				}else{
					arrCheckname = $.grep(arrCheckname, function(value) { 
						return value != name;
					});
					arrCheckid = $.grep(arrCheckid, function(value) { 
						return value != id;
					});
				}				
			});						
		});



		$('.issue-voucher-passenger').click(function(e){
			$('.content-passenger-import input[type="checkbox"]').each(function(index, element) {
				$(this).prop( "checked", false );
			});			
			$('#'+$(this).attr('data-id')).prop( "checked", true );
			
			var t = $(this).offset().top-$('#content-issue').parents('tr').offset().top;
			var l = $(this).offset().left; 
			showFormIssue(t,l);

		});

		$('.open-form-content-issue').click(function(e) {
			var t = $(this).offset().top/2;
			var l = $(this).offset().left; 
			showFormIssue(t-150,l);   	            
		});

		function showFormIssue(t,l){
			var pas_id_group = getPassengerCheck($);

			//checkpassengersamegroup($('#'+pas_id_group[0]).attr('data-group'))
			pas_id_group = getPassengerCheck($);
			
			var data_group_id=$('#'+pas_id_group[0]).attr('data-group_id');
			var flag=true;
			var curren_group = 0;
			pas_id_group.each(function(index,element){	
				if(curren_group!=jQuery('#'+index).attr('data-group')){
							checkpassengersamegroup(jQuery('#'+index).attr('data-group'));
							curren_group = jQuery('#'+index).attr('data-group');
						}					
				// if(data_group_id!=$('#'+index).attr('data-group_id')){
				// 	flag=false;
				// }
			}); 
			if(pas_id_group.length == 0 || flag==false){
				if(pas_id_group.length==0)
					alert("Please, choose passenger.");  
				else if(flag==false){
					alert("Passenger sample group.");  
				}      		
			}else{
        		// get firstname lastname
        		var list_service_select=$('#pass-service-'+pas_id_group[0]).val(); 
        		var list_pass_info=Array();
        		$('#tel').val($('#'+pas_id_group[0]).attr('data-tel'));        		
        		$('#email').val($('#'+pas_id_group[0]).attr('data-email'));
        		$('#'+pas_id_group[0]).attr('data-tel');
        		
				//lchung
				var id_radio = $('#'+pas_id_group[0]).attr('data-gtcompany');
				var date_expire_time = $('#'+pas_id_group[0]).attr('data-date_expire_time');
				
				var gtc_airport_id = $('#'+pas_id_group[0]).attr('data-gtc_airport_id');
				var airline_airport_id = $('#'+pas_id_group[0]).attr('data-airline_airport_id');
				var passenger_group_transport_company_id = $('#'+pas_id_group[0]).attr('data-passenger_group_transport_company_id');
				var gtc_comment =  $('#'+pas_id_group[0]).attr('data-gtc_comment');
				var gtc_hotel_address =  $('#'+pas_id_group[0]).attr('data-gtc_hotel_address');
				var gtc_amount_address =  $('#'+pas_id_group[0]).attr('data-gtc_amount_address');
				var gtc_amount_price =  $('#'+pas_id_group[0]).attr('data-gtc_amount_price');
				
				v_option_taxi = $('#'+pas_id_group[0]).attr('data-option_taxi');
				v_taxi_id = $('#'+pas_id_group[0]).attr('data-taxi_id');
				v_taxiFromAddress = $('#'+pas_id_group[0]).attr('data-taxiFromAddress');
				v_taxiToAddress = $('#'+pas_id_group[0]).attr('data-taxiToAddress');
				v_taxDistance = $('#'+pas_id_group[0]).attr('data-taxDistance');
				v_taxTotalPrice = $('#'+pas_id_group[0]).attr('data-taxTotalPrice');
				v_taxCpnName = $('#'+pas_id_group[0]).attr('data-taxCpnName');
				v_taxWayOption = $('#'+pas_id_group[0]).attr('data-taxWayOption');
				v_taxHotelId = $('#'+pas_id_group[0]).attr('data-taxHotelId');
				v_voucher_id = $('#'+pas_id_group[0]).attr('data-voucher_id');
				v_voucher_groups_id = $('#'+pas_id_group[0]).attr('data-voucher_groups_id');
				v_flight_number = $('#'+pas_id_group[0]).attr('data-flight_number');
				v_isvIdTitleAirline = $('#'+pas_id_group[0]).attr('data-isvIdTitleAirline');
				v_isvDescription = $('#'+pas_id_group[0]).attr('data-isvDescription');
				v_isvNumberCosts = $('#'+pas_id_group[0]).attr('data-isvNumberCosts');
				v_isvCodeCurrency = $('#'+pas_id_group[0]).attr('data-isvCodeCurrency');
				v_isvInternalComment = $('#'+pas_id_group[0]).attr('data-isvInternalComment');
				v_isvTitleAirline = $('#'+pas_id_group[0]).attr('data-isvTitleAirline');
				v_otherServiceContent = $('#'+pas_id_group[0]).attr('data-other-service-content');

				if( id_radio != '' ) {
					$('.service-bus-transfer .img-status1').css('display', 'block');
					$('.service-bus-transfer .img-status0').css('display', 'none');
					$('#radio' + id_radio).attr('checked', true);
					$('.change-text-the-selected').html('Departure ' + date_expire_time.substr(11, 5) );
					$('.change-text-the-selected_active').html( date_expire_time.substr(0, 16) );
					$('.grouptransport_comment_company_active').html( gtc_comment );
					$('.grouptransport_hotel_address_active').html( gtc_hotel_address );
					
					var date = date_expire_time.substr(0, 10);
					var hour = date_expire_time.substr(11, 2);
					var minut = date_expire_time.substr(14, 2);
					$('#mdivaddgroup #date_expire option[value="' + date + '"]').attr('selected', true);
					$('#grouptransport_hourseater option[value="' + hour + '"]').attr('selected', true);
					$('#grouptransport_minutseater option[value="' + minut + '"]').attr('selected', true);
					
					$('#grouptransport_seaterAirport option[value="' + airline_airport_id + '"]').attr('selected', true);
					$('#grouptransport_airportseater option[value="' + gtc_airport_id + '"]').attr('selected', true);
					$('#group_transportation_types_id').val( id_radio );
					$('#passenger_group_transport_company_id').val( passenger_group_transport_company_id );
					$('#grouptransport_comment_company').val( gtc_comment );
					$('#grouptransport_hotel_address').val( gtc_hotel_address );
					$('#group_amount_address').val( gtc_amount_address );
					$('#grouptransport_priceseater').val( gtc_amount_price );
					$('.viewDetail_active').css('display', 'block');
					$('.viewDetail').css('display', 'none');
				}
				//End lchung

				// begin CPhuc
				if( v_taxi_id != '' ){
					$('.info-service-header-taxi .taxi_status').attr('src', '<?php echo JURI::root().'media/media/images/VoucherOK.png' ?>');
					$('.taxi_name').text(v_taxCpnName);
					$('.taxi_option[data-option = "option'+v_option_taxi+'"]').attr('checked', true);
					$('#from-address option[value = "'+v_taxiFromAddress+'"]').attr('selected',true);
					$('#to-address').val(v_taxiToAddress);
					$('.taxi-content .location_name').text('To '+v_taxiToAddress);
					$('.taxi-content .total_distance').text(v_taxDistance);
					$('.taxi-content .total_price').text(v_taxTotalPrice);
					if(v_option_taxi < 4){
						$('.location_name').text(v_taxiToAddress);
						$('.total_distance').attr('data-distance', v_taxDistance).text(v_taxDistance+'Km');
						$('.total_price').text(v_taxTotalPrice);

					}
					
				}
				else {
					$('.info-service-header-taxi .taxi_status').attr('src', '<?php echo JURI::root().'media/media/images/VoucherNOK.png' ?>');
					$('.taxi_choice_defaul').prop('checked', true);
					$('.taxi_name').text('Please select Taxi');
				}
				// end CPhuc

				if(pas_id_group.length==1){
					$('.list-passenger').html($('#'+pas_id_group[0]).attr('data-name'));
					list_service_select=list_service_select.split(',');
					list_service_select.each(function(index,element){
						activeservice($,index);	
					});        			
					list_pass_info.push($('#'+pas_id_group[0]).attr('data-info'));
				}else{
					var flag=true;       
					var list_name_passenger = '';
					var list_more_name_passenger = '<table><tr>';
					$('.list-passenger').html('');
					$('.service-issue-voucher').css('display','none');

					var countPass = 0;
					pas_id_group.each(function(index, element){        				
						if(list_service_select!=$('#pass-service-'+index).val()){     
							flag=false;       					
						}
						if (countPass < 3) {
							
							list_name_passenger+=$('#'+index).attr('data-name')+'<br/>';
							// list_more_name_passenger += '<p>' + $('#'+index).attr('data-name')+'</p>';
						}
						else{
							var countCols = countPass - 2;
							if (countCols % 3 == 0) {
								list_more_name_passenger += '<td>' + $('#'+index).attr('data-name')+'</td>';
								list_more_name_passenger += '</tr><tr>';
							}else{
								list_more_name_passenger += '<td>' + $('#'+index).attr('data-name')+'</td>';

							}

						}
						list_pass_info.push($('#'+pas_id_group[0]).attr('data-info'));
						countPass++;
					});
					list_more_name_passenger += '</tr></table>';
					if(flag==false){
						alert('As the passengers you have selected have different services appointed to them you cannot issue all vouchers at once');        			
						return false;
					}
					else{
						list_service_select=list_service_select.split(',');
						$('.list-passenger').html(list_name_passenger);

						//disable Send Voucher if once service Refreshment is selected
						if (list_service_select == 2 && list_service_select.length){
							$('.main-issue #issue_top .top_center').css({
								'background-color': '#fff',
								'opacity': '0.3'
							}).find('input').val('').attr('disabled','disabled');
							$('.main-issue #issue_top .top_center').find('a').off().removeProp('onclick');
							
							
						}
						//end disable
						
						if (countPass >= 3) {
							disableSendVoucher(list_service_select);
							$('.main-issue .countPass').html('<i class="fa fa-plus" aria-hidden="true" style = "color: #1DA9B8"></i>'+(countPass - 3)+' more Passengers').show();
							$('.main-issue .list_more_name_passenger').html(list_more_name_passenger);
							showMoreNamePass();
						}

						list_service_select.each(function(index,element){
							activeservice($,index); 	
						});
					}        			
					
				}
				
				$('#sendsmsvoucher').attr("data-flight-number", $('#'+pas_id_group[0]).attr('data-flight_number') );
				$('#sendsmsvoucher').attr("data-std", $('#'+pas_id_group[0]).attr('data-std_') );
				$('#sendsmsvoucher').attr("data-etd", $('#'+pas_id_group[0]).attr('data-etd_') );
				
				$('#sendsmsvoucher').attr( 'data-passengers',list_pass_info.toString() );
				
				 		
				$.ajax({
					url:"<?php echo JURI::base().'index.php?option=com_sfs&task=passengersimport.dataCkeckIssueVoucher'; ?>",
					type:"POST",
					data:{
						pas_id_group:pas_id_group,
					},
					dataType: 'json',
					success:function(response){
						$('.box-s').css({"display":"none"});
						$('#content-issue').css({"display":"block", "top":(t)+"px", "left":(l/20)+"px","width": "830px"});
						var html = "";
						var Convresponse = $.map(response, function(el) { return el });
						var delay_;
						$.each(response,function(key, value) {
							if(key != "group" && key != "refreshment_amount"){								
								var fltref = value.rebook[0].fltref.split('-')[0];								
								var delay = caldatatodate(value.rebook[0].std, value.rebook[0].etd);

								var str_STD_ETD = value.rebook[0].std.split('T')[1];
								var std = '', etd = '';
								if( str_STD_ETD != '' ){
									std = str_STD_ETD.substr(0, 5);
									etd = std;
								}								

								delay_ = parseFloat(delay.split(':')[0]);
								
								if(Convresponse.length > 2){
									html += '<div style="float:left; width:100%; margin-bottom: 20px;"><span class="namefresh">'+value.dep+'</span> - <span class="flight_num">'+fltref+'</span><span> - '+value.arr+'</span><br />';
									html += '<div class="timeDate">STD '+std+' <br />ETD '+etd+' <br /></div>';
									html += '<div class="delayrefesh"  data-rate="'+delay_+'" data="'+delay+'">Delay time: '+delay+'</div></div>';

								}else{
									html += '<span class="namefresh">'+value.dep+'</span> - <span class="flight_num">'+fltref+'</span><span> - '+value.arr+'</span><br />';
									html += '<div class="timeDate">STD '+std+' <br />ETD '+etd+' <br /></div>';
									html += '<div class="delayrefesh" data-rate="'+delay_+'" data="'+delay+'">Delay time: '+delay+'</div>';
								}
								
							}
						});

						if(delay_ < 2){										
							html += '<div class="textfresh">As the delay time is less then 2 hours passengers are normally not eligable for an refreshment voucher.</div>';
						}

						if( 2 <= delay_  && delay_ <= 6 ){										
							html += '<div class="textfresh">As the delay time is more then 2 hours the passenegers are eligable for a 5 Euro refreshment voucher.</div>';
						}

						if(delay_ > 6){										
							html += '<div class="textfresh">As the delay time is loner then 6:00 hours passengers are eligable for 10 Euro refreshment voucher.</div>';
						}
						

						$(".infoPassenger").html(html);

						if(response.refreshment_amount){
							if(response.refreshment_amount[0]){
								$('.cash_title').find('.img_status_freshment').attr('src','<?php echo JURI::root().'media/media/images/VoucherOK.png' ?>');
								$('.cash_title').find('.eurocash').text('Euro '+response.refreshment_amount[0]);

							}
						}
						if(response[0].stationname){
							$('.destination_train').text(response[0].stationname);
							$('.from_transtation').val(response[0].from_trainstation);
							$('.to_transtation').val(response[0].to_trainstation);
							$('.info-service-trainTicket #date_expire').val(response[0].train_travel_date);
						}
					}
				});	
			} 
		}
		$('.close-content').click(function(e) {					
			//$('.reset').click();
			$('.box-s').css({"display":"none"});			
			//location.reload();					
			window.location ='<?php echo JURI::base().'index.php?option=com_sfs&view=passengersimport' ?>';
		});


		$('.add-comment').click(function(e) {
			pas_id = $(this).attr('data-id');
			$("#"+pas_id).prop( "checked", true );
			$(document).scrollTop(280);
			$(".tooltip-s").css("display", "none");
			$('.open-form-content-comment').click();

		});

		$('.add-services-list').click(function(e) {
			var list_name_passenger = ''; 
			$('.content-passenger-import input[type="checkbox"]').each(function(index, element) {
				$(this).prop( "checked", false );
			});
			$('#'+$(this).attr('data-id')).prop( "checked", true );

			$(document).scrollTop(280);
			var pas_id_group = getPassengerCheck($);

			pas_id_group.each(function(index,element) {
				list_name_passenger+=$('#'+index).attr('data-name')+'<br/>';
				$('.add_sub_services .list-passenger').html(list_name_passenger);
				$('#issue_sub_other .info_other_service').val($('#'+index).attr('data-other-service-content'));
				$('#issue_sub_other .info_other_service_id').val($('#'+index).attr('data-other-service-id'));
			});
			$('.open-form-content-service').trigger("click"); 			
		});

		$('.save-comment').click(function(e) {
			$('.save-comment-loading').css({"display":"block"});	
			pas_id_com='';
			$('input[type="checkbox"]').each(function(index, element) {
				if ( $(this).is(':checked') ) {
					pas_id_com += "," + $(this).attr('id');
				}
			});			
			if($('#internal_comment').val()!=''){
				var da = {'internal_comment': $('#internal_comment').val(), 'passenger_id': pas_id_com}			
				$.post("index.php?option=com_sfs&task=internalcomment.saveComment",da,
					function(data, status){
						$('.save-comment-loading').css({"display":"none"});
						if( data.successful == "0" ) {
							alert(data.errormessage);
						}
						else if( data.successful == "1" ) {						
							document.location.reload(true);
						}				
					},'json');			
			}
			else{
				alert('Internal comment empty!');
				$('.save-comment-loading').css({"display":"none"});
			}			
			return false;
		});

		$('#internal_comment').keyup(function(e) {
			var l = parseInt( $(this).val().length );
			$('#count-characters-comment').text( l );
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
					alert(' Max 150 characters');
			}
			else {
				nt_alert = 0;
				$('.send_SMS_text_message').removeAttr('disabled');
			}
		});

		$('.send_SMS_text_message').click(function(e) {
			var t = $('#sms_text_message').val();
			var text_tel = $('#telphone').val();
			if( t.length > 150 ) {
				alert(' Max 150 characters');
			}
			else if( t.length == 0 ) {
				alert('Please enter message');
			}
			else{
				$('.send-sms-loading').css({"display":"inline-block"});
				var da = {'text_message': t, 'text_tel' : text_tel}
				//{$( 'form' ).serializeArray()
				$.post("index.php?option=com_sfs&task=internalcomment.sendSMS",da,
					function(data, status){	
						$('.send-sms-loading').css({"display":"none"});			
						if( data.successful == 1 ){
							alert(data.messages.message.errormessage);
							$('#sms_text_message').val("");
						}
						else if( data.errorcode == 'maxl' ) {
							alert(data.errormessage);
						}
						else if( data.errorcode != 0 ) {
							alert(data.errormessage);
						}
					}, 'json');
			}
			return false;
		});//End send_SMS_text_message	
	});


function caldatatodate(first,second){
	var std = new Date(first);
	var etd = new Date(second);
	var stdCon = std.getTime();
	var etdCon = etd.getTime();
	
	var difference_ms = etdCon - stdCon;
	var delay =  new Date(difference_ms).toISOString().slice(11, -8);

	return delay;
}

function getPassengerTheChecked($){
	var pasName = '', telphone = "";
	$('input[type="checkbox"]').each(function(index, element) {
		if ( $(this).is(':checked') ) {
			var n = $(this).attr('data-name');
			if( n != undefined) {
				pasName +='<li class="mg0 liststyle-none">' + n + '</li>';
				if( $(this).attr('data-tel') != '' )
					telphone += ',' + $(this).attr('data-tel');
			}
		}
	});
	$('#telphone').val("").val( telphone )
	$('.content-list-passengers').html("").html( pasName );
}

function getPassengerAddGroup($){
	var pasName = '', telphone = "";
	$('input[type="checkbox"]').each(function(index, element) {
		if ( $(this).is(':checked') ) {
			var id = $(this).attr("id");
			var n = $(this).attr('data-name');
			var pnr = $(this).attr('data-pnr');
			if( n != undefined) {
					/*pasName +='<li class="mg0 liststyle-none">\
					<label>' + 				
					'<input checked class="check-group" value="' + id + '" type="checkbox">\
					<span class="pas-name">' + n + '</span>'+
					'<span class="pnr">PNR: <b style="color:#65c0cf;">'+ pnr + '</b>\
					</span></label>\
				</li>';*/
				pasName +='<tr id="tr-'+id+'"><td>'+'<input id="chk-create-'+id+'" checked class="check-group" value="' + id + '" type="checkbox">'+'<span class="pas-name">' + n + '</span></td><td class="td-center">'+'<span class="pnr">PNR: '+ pnr + '</td><td class="td-center">'+'<img id="icon-tr-to-'+id+'" src="<?php echo $link_Img.'/travel_together_group_icon.png' ?>" />'+'</td><td></td></tr>';
			}
		}
	});
	$('.ul-pas').html("").html( pasName );
}
//begin CPhuc
function showMoreNamePass(){
	jQuery('.main-issue .countPass').on('click', function() {
		var offset = jQuery(this).position();
		var t = offset.top;
		var l = offset.left;
		// alert(t+'----'+l);
		jQuery('.main-issue .list_more_name_passenger').toggle().css({
			left : l,
			top : t+50
		});
	});
}

function disableSendVoucher(obj){
	if (obj.lenght == 1) {
		alert(obj);
	}
}
// end Cphuc


function getPassengerCheck($){
	var pas_id_chk=[];		
	$('.content-passenger-import input[type="checkbox"]').each(function(index, element) {
		if ( $(this).is(':checked') ) {
			pas_id_chk.push($(this).attr('id'));					
		}
	});
	return pas_id_chk;
}
function getFlightNumberCheck($){
	var pas_flight_number_chk=[];		
	$('.content-passenger-import input[type="checkbox"]').each(function(index, element) {
		if ( $(this).is(':checked') ) {
			pas_flight_number_chk.push($(this).attr('data-flight_number'));					
		}
	});
	return pas_flight_number_chk;
}
function activeservice($,id){		
	if(id==1){
		$('.info-service-header-hotel').css('display','block');
		var pass_ids=getPassengerCheck($);
		if(pass_ids.length>0){
			var hotel_id=$('#'+pass_ids[0]).attr('data-hotel');
			var reservationid=$('#'+pass_ids[0]).attr('data-reservationid');
			var blockdate=$('#'+pass_ids[0]).attr('data-blockdate');
			var ws_id=$('#'+pass_ids[0]).attr('data-ws-id');
			var flag=true;
			pass_ids.each(function(index,element){					
				if(hotel_id!=$('#'+index).attr('data-hotel')){
					flag=false;
				}
			});
			if(flag==true){					
				if(reservationid){
						/*$('.content-message-hotel').css('display','none');
						$('.title-header-hotel').html($('#'+pass_ids[0]).attr('data-hotelname'));
						$('.list-hotel-content').html($('#'+pass_ids[0]).attr('data-hotelname'));
						$('.list-hotel-content').css('text-align','center');
						$('.img_status_hotel').attr("src","<?php echo JURI::root().'media/media/images/VoucherOK.png' ?>");*/
						jQuery('#hotel_book').val(hotel_id);
						jQuery('#reservationid').val(reservationid);
						jQuery('#blockdate').val(blockdate);
						jQuery.ajax({
							url:"<?php echo JURI::base().'index.php?option=com_sfs&task=ajax.searchHotel&format=raw'; ?>",
							type:"POST",
							data:{reservationid:reservationid,blockdate:blockdate},
							dataType: 'text',
							success:function(response){
								jQuery('.title-header-hotel').html($('#'+pass_ids[0]).attr('data-hotelname'));
								jQuery('.list-hotel-content').html(response);
								jQuery('.content-message-hotel').css('display','none');
								jQuery('.message-left-one').css('display','none');
								jQuery('.message-left-two').css('display','block');
								jQuery('.img_status_hotel').attr("src","<?php echo JURI::root().'media/media/images/VoucherOK.png' ?>");
								jQuery('#book-hotel').css('display','none');
							}
						});
						if(ws_id!=''){
							var html='';
							html += '<div><p>There has been a 3rd party hotel booked for this passenger which cannot be cancelled.</p><br/><br/><p>Your hotel is booked throught our partner '+$('#'+pass_ids[0]).attr('data-supplier')+' and your reference number is '+ $('#'+pass_ids[0]).attr('data-supplierreference') +' </p></div>';							
							jQuery('.message-left-two').html(html);
						}else{
							var html='';
							var hotel_explain="<b>This is a SFS certified hotel</b><br/><div style='font-size: 12px;'>SFS partner hotels are hotels that have specific options for stranded passengers, like the option to book and handle large groups of passengers, they often have specific mealplans and partner hotels are familiar with the SFS system.</div>";

							html += '<div><span class="hasTip" title="'+hotel_explain+'"><img src="<?php echo JURI::base();?>templates/<?php echo $app->getTemplate();?>/images/sfs-certified.png" style="display: block;margin: 20px 0 0 30px;" /></span></div>';

							jQuery('.message-left-two').html(html);
						}
					}	
					else{	
						var reservationid_book='<?php echo JRequest::getInt('reservation_id');?>';			
						jQuery.ajax({
							url:"<?php echo JURI::base().'index.php?option=com_sfs&task=ajax.searchHotel&format=raw'; ?>",
							type:"POST",
							data:{<?php if(JRequest::getInt('reservation_id',0) && JRequest::getVar('pass_issue_hotel','')) echo 'reservationid_book:reservationid_book'; ?>},
							dataType: 'text',
							success:function(response){
								if(response!='No Data' && response!=''){
									$('.list-hotel-content').html(response);
									$('.content-message-hotel').css('display','none');
									$('.message-left-one').css('display','none');
									$('.message-left-two').css('display','block');	
								}				                    
							}
						});
					}		
				}
			}
			
		}
		if(id==2){
			$('.cash_title').css('display','block');
		}
		if(id==3){
			//alert('sssss');
			$('.service-bus-transfer').css('display','block');
		}
		if(id==4){
			$('.info-service-header-taxi').css('display','block');
		}
		if(id==5){
			$('.info-service-header-trainTicket').css('display','block');
		}
		if(id==6){
			$('.info-service-header-rental').css('display','block');
			var pass_ids=getPassengerCheck($);
			if(pass_ids.length>0){
				var rental_id=$('#'+pass_ids[0]).attr('data-rental_id');
			}
			var flag=true;
			pass_ids.each(function(index,element){					
				if(rental_id!=$('#'+index).attr('data-rental_id')){
					flag=false;
				}
			});  
			if(flag==true){				
				$("#rental-"+rental_id).prop('checked', true);
				$("#rental-location-"+rental_id).css('display', 'block');
				$('#pick-up-'+rental_id).val($('#'+pass_ids[0]).attr('data-pick_up'));
				$('#drop-off-'+rental_id).val($('#'+pass_ids[0]).attr('data-drop_off'));
				jQuery('.message-rental').css('display','none');
				$('.title-header-rental').html($("#name-rental-"+rental_id).val());
				if(rental_id!=''){
					$("#rental_car").attr("src", "<?php echo JURI::root(); ?>media/media/images/VoucherOK.png");
				}
			}
		}
		
		if (id > 7) {
			$('.issue_sub_other_service').css('display', 'block');
			$('.issue_sub_other_service .info-ss-header-maas .tableInfo').css('margin-top', '-20px');
			$('.info-service-header').css('display', 'none');
			$('.close-tplsub-service').css('display', 'none');
			$('.issue_head').css('display', 'none');
			$('.main-sub-service').css('margin-left', '0px');
			$('.main-sub-service').children('div').css('margin-left', '0px');
			$('#issue_sub_other').css('width', '105%');
			$('#issue_sub_other .top_left').css('display', 'none');
			$('.sub_service_id').attr('value', id);
			active_SS_Other(id);
			$(".issue_sub_other_service .info_service .active").css('display', 'none');
			$(".issue_sub_other_service .info-ss-header-maas .btn-open" ).html('<span>Open<span><img src="<?php echo JURI::root().'media/media/images/select-pass-icons/arrow-down.png' ?>"/>');
			// console.log(v_otherServiceContent);
		}


		
	}
	function active_SS_Other(id){
		if (v_otherServiceContent != '') {
			var sub_service_content = jQuery.parseJSON(v_otherServiceContent);
		}
		else{
			sub_service_content = '';
		}

		switch(parseInt(id)){ 
			case 8:
			jQuery('.info_service .info-ss-maas').css('display', 'block').addClass('active');
			jQuery('.info-ss-header-maas .ss-service').text('MAAS');
			jQuery('.info-ss-header-maas .ss-title').text('MAAS Enable');
			jQuery('.info_service .info_sub_service #service-maas').prop('checked', true);
			jQuery('.info_service .info_sub_service #service-waas').prop('checked', false);
			jQuery('.info-ss-header-maas .icon_train').attr('src', '<?php echo JURI::root().'media/media/images/VoucherOK.png' ?>');


			break;
			case 9:
			jQuery('.info_service .info-ss-maas').css('display', 'block').addClass('active');
			jQuery('.info-ss-header-maas .ss-service').text('WAAS');
			jQuery('.info-ss-header-maas .ss-title').text('WAAS Enable');
			jQuery('.info_service .info_sub_service #service-waas').prop('checked', true);
			jQuery('.info_service .info_sub_service #service-maas').prop('checked', false);
			jQuery('.info-ss-header-maas .icon_train').attr('src', '<?php echo JURI::root().'media/media/images/VoucherOK.png' ?>');
			break;
			case 10: 
			jQuery('.info_service .info-ss-snackbags').css('display','block').addClass('active');
			jQuery('.info-ss-header-maas .ss-service').text('Snackbags');
			jQuery('.info-ss-header-maas .ss-title').text('Snackbags Enable');
			if (sub_service_content != '') {
				if (sub_service_content[0].status_info_ss_snackbags == 1) {
					jQuery('.info-ss-snackbags .internal_comment').text(sub_service_content[0].internal_comment);
					jQuery('.info_service .info-ss-snackbags #snackbags').prop('checked', true);
					jQuery('.info-ss-header-maas .icon_train').attr('src', '<?php echo JURI::root().'media/media/images/VoucherOK.png' ?>');
				}
			}
			else{
				jQuery('.info_service .info-ss-snackbags #snackbags').prop('checked', false);
				jQuery('.info-ss-header-maas .icon_train').attr('src', '<?php echo JURI::root().'media/media/images/VoucherNOK.png' ?>');
			}
			break;
			case 11:
			jQuery('.info_service .info-ss-phonecard').css('display', 'block').addClass('active');
			jQuery('.info-ss-header-maas .ss-service').text('Phonecards');
			jQuery('.info-ss-header-maas .ss-title').text('Phonecards Enable');
			if (sub_service_content != '') {
				jQuery('.info-ss-phonecard .description').val(sub_service_content[0].description);
				jQuery('.info-ss-phonecard .internal_comment').val(sub_service_content[0].internal_comment);
				jQuery('.info-ss-phonecard .inputamount ').val(sub_service_content[0].inputamount);
				jQuery('.info-ss-phonecard .currency_code option').text(sub_service_content[0].currency_code);
			}
			jQuery('.info-ss-header-maas .icon_train').attr('src', '<?php echo JURI::root().'media/media/images/VoucherOK.png' ?>');
			break;
			case 12:
			jQuery('.info_service .info-ss-cash').css('display', 'block').addClass('active');
			jQuery('.info-ss-header-maas .ss-service').text('Cash');
			jQuery('.info-ss-header-maas .ss-title').text('Cash Enable');
			if (sub_service_content != '') {
				jQuery('.info-ss-cash .internal_comment').val(sub_service_content[0].internal_comment);
				jQuery('.info-ss-cash .inputamount ').val(sub_service_content[0].inputamount);
				jQuery('.info-ss-header-maas .icon_train').attr('src', '<?php echo JURI::root().'media/media/images/VoucherOK.png' ?>');
				
			}
			break;
			case 13:
			break;
			case 14:
			jQuery('.info_service .info-ss-miscellaneous').css('display', 'block').addClass('active');
			jQuery('.info-ss-header-maas .ss-service').text('Miscellaneous');
			jQuery('.info-ss-header-maas .ss-title').text('Miscellaneous Enable');
			if (sub_service_content != '') {
				jQuery('.info-ss-miscellaneous .title').val(sub_service_content[0].title);
				jQuery('.info-ss-miscellaneous .description').val(sub_service_content[0].description);
				jQuery('.info-ss-miscellaneous .internal_comment').val(sub_service_content[0].internal_comment);
				jQuery('.info-ss-miscellaneous .inputamount ').val(sub_service_content[0].inputamount);
				jQuery('.info-ss-miscellaneous .currency_code option').text(sub_service_content[0].currency_code);
				
			}
			jQuery('.info-ss-header-maas .icon_train').attr('src', '<?php echo JURI::root().'media/media/images/VoucherOK.png' ?>');
			break;
			default: 
						// $('.info_service .info_sub_service').css('display', 'none');
						break;

					}
				}
				function checkpassengersamegroup(data_group){		
					jQuery('.page_passenger_import input[data-group="'+data_group+'"]').each(function(index, element) {
						jQuery(this).prop( "checked", true );
					});
				}
			</script>

			<div class="heading-block descript clearfix">
				<div class="heading-block-wrap">
					<h3><?php echo JText::sprintf('COM_SFS_PASSENGER_IMPORT_PAGE_TITLE',"")?></h3>
					<div class="descript-txt"><?php echo JText::_('COM_SFS_PASSENGER_IMPORT_SHORT_DESC')?></div>
				</div>
			</div>
			<div class="main">

				<form id="f-passengersimport" action="<?php echo JRoute::_('index.php?option=com_sfs&view=passengersimport'); ?>" method="post">
					<div class="sfs-main-wrapper" style="padding:0 1px 0 1px ; margin-bottom:15px;">
						<div class="sfs-orange-wrapper head-select-passenger">
							<h3><strong>Actions</strong></h3>
							<div class="sfs-white-wrapper">
								<table cellpadding="0" cellspacing="0" border="0" class="fs-14 title-feature " style="width:100%;">
									<tr>
										<td class="text-center">Create Group</td>
										<?php if($this->user->groups[11]==11): ?>
											<td class="text-center">Add/change service</td>
										<?php endif; ?>
										<td class="text-center">Send Message to guests</td>
										<?php if($this->user->groups[11]==11): ?>
											<td class="text-center">Assign to local station</td>
										<?php endif; ?>
										<td class="text-center">Add an internal comment</td>
										<td class="text-center" style="border-left:5px solid #12A4B4; padding-left:10px;">Issue Voucher</td>
									</tr>   
									<tr>
										<td class="midpaddingleft midpaddingbottom text-center">
											<!--<a rel="{handler: 'iframe', size: {x: 900,y:250}}" class="modal" href="index.php?option=com_sfs&amp;view=passengersimport&amp;layout=group&amp;tmpl=component" >-->
											<a class="open-form-content-group" href="javascript:void(0);" >
												<button type="button" class="small-button bg-button-none" style="margin:0 auto;">
													<img src="<?php  echo $link_Img.'/group.png' ?>" alt="">
												</button>                                  
											</a>
											<div class="none box-s content-group" id="content-group">
												<?php  echo $this->loadTemplate('group');?>
											</div>                                
										</td>
										
										<td class="midpaddingleft midpaddingbottom text-center"
										<?php if(empty($this->user->groups[11])): ?>
											style="display:none;"
										<?php endif; ?>
										>
										<!--<a rel="{handler: 'iframe', size: {x: 900,y: 500}}" class="modal" href="index.php?option=com_sfs&amp;view=passengersimport&amp;layout=add_service&amp;tmpl=component">-->
										<a class="open-form-content-service" href="javascript:void(0);">
											<button type="button" class="small-button bg-button-none" style="margin:0 auto;">
												<img src="<?php  echo $link_Img.'/add-service.png' ?>" alt="">
											</button> 
										</a>

									</td>

									<td class="midpaddingleft midpaddingbottom text-center">
										<!--<a rel="{handler: 'iframe', size: {x: 900,y: 500}}" class="modal" href="index.php?option=com_sfs&amp;view=passengersimport&amp;layout=sendMessager&amp;tmpl=component">-->
										<a class="open-form-content-messge" href="javascript:void(0);">
											<button type="button" class="small-button bg-button-none" style="margin:0 auto;">
												<img src="<?php echo $link_Img.'/messge.png' ?>" alt="">
											</button>
										</a>
										<div class="none box-s content-messge" id="content-messge">
											<?php  echo $this->loadTemplate('send_messager');?>
										</div>
									</td>

									<td class="midpaddingleft midpaddingbottom text-center" <?php if(empty($this->user->groups[11])): ?>
										style="display:none;"
									<?php endif; ?>
									>
									<!--<a rel="{handler: 'iframe', size: {x: 950,y: 500}}" class="modal" href="index.php?option=com_sfs&amp;view=passengersimport&amp;layout=assign&amp;tmpl=component">-->
									<a class="open-form-content-assign" href="javascript:void(0);">
										<button type="button" class="small-button bg-button-none" style="margin:0 auto;">
											<img src="<?php  echo $link_Img.'/assign.png' ?>" alt="">
										</button> 
									</a>
									<div class="none box-s content-assign" id="content-assign">
										<?php  echo $this->loadTemplate('assign');?>
									</div>
								</td>

								<td class="midpaddingleft midpaddingbottom text-center">
									<!--<a rel="{handler: 'iframe', size: {x: 900,y: 250}}" class="modal" href="index.php?option=com_sfs&amp;view=passengersimport&amp;layout=comment&amp;tmpl=component" >-->
									<a class="open-form-content-comment" href="javascript:void(0);" >
										<button type="button" class="small-button bg-button-none" style="margin:0 auto;">
											<img src="<?php  echo $link_Img.'/comment.png' ?>" alt="">
										</button> 
									</a>
									<div class="none box-s content-comment" id="content-comment">
										<?php  echo $this->loadTemplate('comment');?>
									</div>
								</td>
								<td class="text-center" style="border-left:5px solid #12A4B4; padding-left:10px;"> 
									<a class="open-form-content-issue" href="javascript:void(0);">
										<button type="button" class="small-button tracepassenger-export-to-excel bg-button-none" style="margin:0 auto;">
											<img src="<?php  echo $link_Img.'/vouchers.png' ?>" alt="">
										</button>
									</a>

									<div class="none box-s content-comment" id="content-issue">
										<?php  echo $this->loadTemplate('issue_voucher');?>
									</div>
								</td>
								<td class="text-center"> 
									<div class="none box-s content-service" id="content-service">
										<?php  echo $this->loadTemplate('services');?>
									</div>
									<div class="none box-s content-comment add_sub_services" >
										<?php  echo $this->loadTemplate('sub_other_service');?>
										
									</div>
								</td>
								
							</tr>
						</table>


					</div>
				</div>
			</div>

			<div class="sfs-main-wrapper" style="padding:10px">
				<div class="floatbox sfs-white-wrapper">
					<?php  echo $this->loadTemplate('list');?>
				</div>
			</div>
			<input type="reset" class="reset" style="display:none;" />
			<input type="hidden" name="task" value="" />

			<input type="hidden" name="Itemid" value="<?php echo JRequest::getInt('Itemid');?>" />

			<?php echo JHtml::_('form.token'); ?>
		</form>

	</div>
	<div id="services"></div>



