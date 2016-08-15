<?php
defined('_JEXEC') or die;
$link_Img = JURI::root().'media/media/images/select-pass-icons';
 ?>
  <style type="text/css">
	.info-service-header-hotel.service-active{
		background: #eeeeee;	
	}
	.info-service-header-hotel.service-active .tableInfo td{
		border-bottom: 2px solid #eeeeee!important;
	}
	.content-message-hotel{
		width: 410px;
		height: 98px;
		border: 1px solid #f5a755;
		background: #fdefbe;
		margin: 0 auto;
		padding: 30px 20px;
		font-weight: normal;
	}
	.icon-warnning{
		float: left;
	    width: 25px;
	    height: 50px;
	    padding-top: 4px;
	}
	
	.info-service-hotel,.info-service-header-hotel.service-active .tableInfo
	{
		background: #eeeeee;
	}
	.info-service-hotel .tableInfo{
		width: 96%;
		margin-left: 2%;
	}
	.info-service-hotel .tableInfo td
	{
		border: 2px solid #e4f3fa;
	}
	.btn-save-hotel{

	}
	#save-hotel{
		background: #ff8806;
	    padding: 7px 25px;
	    color: #ffffff;
	    float: right;
	    margin: 10px 20px;
	}
	#book-hotel{
		background: #ff8806;
	    padding: 5px 10px;
	    color: #ffffff;	  
	    margin-top: 10px;  
	}
	.list-hotel-content span{
		font-weight: normal;
    	color: #000;
	}
	.tbl-list-hotel{
		width: 100%;
	}
	.title-hotel span{
		color: #01b2c3;
	}
	.tbl-list-hotel td{
		border: 0!important;
		font-weight: normal;
	}
	.list-passenger-hotel{
		width: 90%;
	    position: absolute;
	    margin-right: auto;
	    border: 5px solid #18b2b0;
	    margin-left: auto;
	    left: 5%;
	    background: #fff;	    
	    top: -80%;
	    padding: 20px;
	    z-index: 10;
	}
	.list-passenger-hotel span{
		color: #000;
	}
	.warnning-share-room{
		width: 20px;
	    height: 50px;
	    float: left;
	    margin-right: 5px;
	    padding-top: 27px;
	}
	.tbl-share-room {
		margin-left: 40px;
	}
	.tbl-share-room th{
		color: #000;
		padding-left: 10px;
    	padding-right: 10px;
	}
	.tbl-share-room td{
		padding: 5px;
	}
	#save-share-room{
		background: #ff8806;
	    padding: 7px 25px;
	    color: #ffffff;
	    float: right;
	    margin: 10px 20px;
	}
	.btn-close-share{
		float: right;
		font-weight: normal;
		color: #000!important;
	}
	.tbl-share-room th{
		background: #fff;
    	border: 0px;
    	color: #000;
	}
</style>
<div style="clear: both;"></div>
 <div class="info-service-header-hotel">
 	<table class="tableInfo" cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td width="40%">Hotel accommodation</td>
			<td width="10%">
				<img class="img_status_hotel" src="<?php echo JURI::root().'media/media/images/VoucherNOK.png' ?>">
			</td>	
			<td width="40%" class="title-header-hotel">Please book or select hotel</td>
			<td  width="10%"><div id="btn-hotel" class="btn-open"><span>Open<span><img src="<?php echo JURI::root().'media/media/images/select-pass-icons/arrow-down.png' ?>"/></div></td>
		</tr>				
	</table>				
</div>
<div style="clear: both;"></div>
<div class="info-service-hotel" style="display: none; position: relative;">
	<table class="tableInfo" cellpadding="0" cellspacing="0" border="0">
		<tr id="hotel-content">	
			<td width="25%" style="font-weight: normal;">
				<div class="message-left-one" >
					There are no prebooked hotel, so you will have to book one for these passengers please click on the button below to go to the search hotels page.	
				</div>
				<div class="message-left-two" style="display: none;">
					Please book or select a hotel for your passengers
				</div>
				<div style="clear: both;"></div>
				<a href="<?php echo JRoute::_('index.php?option=com_sfs&view=handler&layout=search&Itemid=119');?>&pass_detail_hotel=<?php echo JRequest::getVar('passenger_id');?>" style="float: right;margin-right:5px; " id="book-hotel" href="#">BOOK HOTELS</a>
			</td>
			<td width="75%">
				<div class="content-message-hotel" >
					<div class="icon-warnning">
						<img src="<?php echo JURI::base(); ?>media/media/images/select-pass-icons/alert-small.png" alt="">
					</div>
					<div class="desciption-message-hotel">
						No hotels yet please book one first so you can issue the voucher. 
					</div>					
				</div>
				<div class="list-hotel-content"></div>
				<input type="hidden" name="hotel_book" id="hotel_book" value="<?php echo $this->item->hotel_id; ?>" />		
				<div class="list-passenger-hotel" style="display: none;" >
					<a href="javascript:void(0)" class="btn-close-share">X close</a>
					<div class="title-share-room">Share a room</div>
					<div class="left-share-room">
						<img style="float: left;" src="<?php  echo $link_Img.'/group.png' ?>" alt="" width="50px">
					</div>					
					<div class="right-share-room">
						<div class="warnning-share-room">
							<img style="float: left;" src="<?php  echo $link_Img.'/alert-small.png' ?>" alt="" width="20px">							
						</div>
						<span style="font-weight: normal;">People travelling together in one group often share a room</span><br/>
						<span style="font-weight: normal;">
							<i style="color: #7d7d7d;">Below guests are in the same group please select if they are sharing<br/> a room together</i>
						</span>
					</div>
					<div style="clear: both;"></div>					
					<div class="list-passenger-share-room"></div>
					<div class="btn-save-share-room"><a href="javascript:void(0)" id="save-share-room">SAVE</a></div>
				</div>
			</td>
		</tr>
	</table>
	<div class="btn-save-hotel"><a href="javascript:void(0)" id="save-hotel" href="#">SAVE</a></div>
	<div style="clear: both;"></div>
</div>
<script type="text/javascript">
	var type_room;
	jQuery(function($){
    	<?php echo $script_check; ?>    
    	$( "#btn-hotel" ).click(function() {
    		if($( "#btn-hotel span" ).text()=="Open"){    			
    			$( ".info-service-header-hotel" ).addClass('service-active');
    			$( ".info-service-hotel" ).addClass('service-active');    			
    			$( "#btn-hotel" ).html('<span>Close<span><img src="<?php echo JURI::root().'media/media/images/select-pass-icons/arrow-up.png' ?>"/>');
    			
    		}else{
    			$( ".info-service-header-hotel" ).removeClass('service-active');    			
    			$( ".info-service-hotel" ).removeClass('service-active');    			
    			$( "#btn-hotel" ).html('<span>Open<span><img src="<?php echo JURI::root().'media/media/images/select-pass-icons/arrow-down.png' ?>"/>');
    		}
		  	$(".info-service-hotel").slideToggle( "slow" );	
		});	
		$('#book-hotel').click(function(){	
			if(jQuery('#hotel_book').val()=='' && jQuery('#reservationid').val()=='' && jQuery('#blockdate').val()==''){
				jQuery.ajax({
	                url:"<?php echo JURI::base().'index.php?option=com_sfs&task=ajax.searchHotel&format=raw'; ?>",
	                type:"POST",
	                data:{},
	                dataType: 'text',
	                success:function(response){
	                    $('.list-hotel-content').html(response);
	                    $('.content-message-hotel').css('display','none');
	                    $('.message-left-one').css('display','none');
	                    $('.message-left-two').css('display','block');
	                }
	            });
			}
			
		});
		jQuery('#save-share-room').click(function(){
			updateshareroom(jQuery('.tbl-list-hotel .ui.toggle.checkbox.checked input[type="checkbox"]').attr('data-resid'));			
		});
		jQuery('.btn-close-share').click(function(){
			
			if(jQuery('#reservationid').val()!='' && jQuery('#blockdate').val()!=''){
				jQuery.ajax({
			        url:"<?php echo JURI::base().'index.php?option=com_sfs&task=ajax.searchHotel&format=raw'; ?>",
			        type:"POST",
			        data:{reservationid:jQuery('#reservationid').val(),
			        blockdate:jQuery('#blockdate').val()},
			        dataType: 'text',
			        success:function(response){
			            jQuery('.list-hotel-content').html(response);	
			        }
			    });
			}
			jQuery('.list-passenger-hotel').css('display','none')
		});
		<?php if(JRequest::getString('show')=='hotel'){?>
			jQuery('.issue-voucher.service-hotel').trigger('click');
			setTimeout(function(){ jQuery('#btn-hotel').trigger('click'); }, 1000);
		<?php } ?>
    });
    function createlistpassroom(reservation_id){    	    	
    	var pass_ids=[];
    	pass_ids.push(jQuery('#passenger_id').val());		
    	/*jQuery('.page_passenger_import input[type="checkbox"]').each(function(index, element) {
				if ( jQuery(this).is(':checked') ) {
					pass_ids.push(jQuery(this).attr('id'));					
				}
		});*/
    	var html_header='';
    	if(pass_ids.length>0){
    		html_header+='<tr><th></th>';
    		var html_body='';
    		var i=1;
    		var number_room=pass_ids.length;
    		//jQuery.each(pass_ids,function(index, element) {
    			var style='border-right: 0!important;border-bottom: 0!important;';
				html_header+='<th style="border-top: 0!important;border-right: 0!important;font-weight: normal;color:#000;">Room 1</th>';
				html_body+='<tr><td style="'+style+'border-left: 0!important;font-weight: normal;">'+jQuery('#passsenger_name').val()+'</td>';
					html_body+='<td style="'+style+'text-align: center;"><input id="room-1"  type="radio" name="room-'+jQuery('#passenger_id').val()+'" value="1" data-id="'+jQuery('#passenger_id').val()+'" /></td>';
			
				html_body+='</tr>';
				i++;
			//});
    		html_header+='</tr>';
    		html_header='<table class="tbl-share-room" cellpadding="0" cellspacing="0" border="0">'+html_header+html_body+'</table>';    		
    		//alert(html_header);
    		jQuery('.list-passenger-share-room').html(html_header);
    	}
    }
    function updateshareroom(reservation_id){    	
    	var pas_id_chk=[];		
    	var share_room=[];
		pas_id_chk.push(jQuery('#passenger_id').val());	
		share_room[1]=[];
		share_room[1].push(jQuery('#passenger_id').val());		
		if(pas_id_chk.length>0){
			if(jQuery("#room-1").is(':checked')){
				var mealplan=0;
				var lunch=0;
				var breakfast=0;
				if(jQuery('#mealplan'+reservation_id).is(':checked')){
					mealplan=1;
				}
				if(jQuery('#lunch'+reservation_id).is(':checked')){
					lunch=1;
				}
				if(jQuery('#breakfast'+reservation_id).is(':checked')){
					breakfast=1;
				}	
				jQuery('#save-share-room').css('display','none') ;
				jQuery.ajax({ url:'<?php echo JURI::base();?>index.php?option=com_sfs&task=ajax.addHotelPassenger&format=raw',
					type:'POST',
					data:{
					pass_ids:pas_id_chk,
					hotel_id:jQuery('#reservation-'+reservation_id).attr('data-hotel'),
					nightdate:jQuery('#nightdate').val(),
					share_room:share_room,
					reservationid: jQuery('#reservation-'+reservation_id).attr('data-resid'),
					s_number_room:jQuery('#s_number_room'+reservation_id).val(),
					sd_number_room:jQuery('#sd_number_room'+reservation_id).val(),
					t_number_room:jQuery('#t_number_room'+reservation_id).val(),
					q_number_room:jQuery('#q_number_room'+reservation_id).val(),
					mealplan:mealplan,
					lunch:lunch,
					breakfast:breakfast,
					},
					dataType:'json',
                    success:function(response){                    	
                    	if(response.hotel_id){ 
                    		jQuery('#blockdate').val(response.blockdate);	
                    		jQuery('.title-header-hotel').html(response.name_hotel);	
                    		jQuery('#reservationid').val(response.reservationid);
                    		jQuery('.img_status_hotel').attr("src","<?php echo JURI::root().'media/media/images/VoucherOK.png' ?>");
                    		jQuery('#hotel_book').val(response.hotel_id);
                    		jQuery('#blockdate').val(response.blockdate);    

                    		alert('Save success!');
                    		jQuery('body').trigger('hotelsaved', response.hotel_id);
                    		$('#service4').show();
                    		$('#service4 .info-service-taxi').show();


                    	}if(response.successful==0){
                    		alert('The number of rooms is not enough!');                    		
                    		jQuery('#save-share-room').css('display','block');
                    	}
                    }
				});				
			}	
			else{
				alert('Please select room!');
			}		
		}
    }
    function loadReservationHotel(){
		var reservationid=jQuery('#reservationid').val();
		var blockdate=jQuery('#blockdate').val();
		jQuery.ajax({
	        url:"<?php echo JURI::base().'index.php?option=com_sfs&task=ajax.searchHotel&format=raw'; ?>",
	        type:"POST",
	        data:{reservationid:reservationid,blockdate:blockdate},
	        dataType: 'text',
	        success:function(response){
	        	if(jQuery('#hotel_name').val()!=''){	        		
	        		jQuery('.title-header-hotel').html(jQuery('#hotel_name').val());
	        		jQuery('.img_status_hotel').attr("src","<?php echo JURI::root().'media/media/images/VoucherOK.png' ?>");	
	        	}
	            jQuery('.list-hotel-content').html(response);
	            jQuery('.content-message-hotel').css('display','none');
	            jQuery('.message-left-one').css('display','none');
	            jQuery('.message-left-two').css('display','block');
	            
	        }
	    });
	}
</script>