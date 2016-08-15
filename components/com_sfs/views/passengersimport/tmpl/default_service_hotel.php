<?php
defined('_JEXEC') or die;
$link_Img = JURI::root().'media/media/images/select-pass-icons';
$reservation_id = JRequest::getInt('reservation_id');
$tb_share_room = JRequest::getVar('tb_share_room');
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
	    top: 5%;
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
	#save-share-room, #btn-booking-hotel{
		background: #ff8806;
	    padding: 7px 25px;
	    color: #ffffff;
	    float: right;
	    margin: 10px 20px;
	}
	.btn-close-share{
		float: right;
		font-weight: normal;
		line-height: 12px;
		border-bottom: 1px solid #000;
		color: #000!important;
	}
	.btn-save-share-room{
		position: relative;
    	height: 40px;
	}
	.btn-save-share-room span{
		float: right!important;
		top:0px!important;
		right: 0!important;
	}
	.tip-wrap{
		z-index: 100;
	}
	.list-passenger-share-room td{
		padding-right: 5px;
	}
</style>
<div style="clear: both;"></div>
 <div class="info-service-header-hotel" style="display: none;">
 	<table class="tableInfo" cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td width="40%">Hotel accommodation</td>
			<td width="10%">
				<img class="img_status_hotel" src="<?php echo JURI::root().'media/media/images/VoucherNOK.png' ?>">
			</td>	
			<td width="40%"><div class="title-header-hotel">Please book or select hotel</div></td>
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
					There have already been hotels blocked for your convienience, please select one of the hotels to issue a voucher
					<br/>
					Or if you need another hotel, click here.
				</div>
				<div style="clear: both;"></div>
				<a href="javascript:void(0)" style="float: right;margin-right:5px; " id="book-hotel" href="#">BOOK HOTELS</a>
			</td>
			<td width="75%">
				<div class="content-message-hotel" >
					<div class="icon-warnning">
						<img src="<?php echo JURI::root().'media/media/images/select-pass-icons/alert-small.png' ?>" alt="">
					</div>
					<div class="desciption-message-hotel">
						No hotels yet please book one first so you can issue the voucher. 
					</div>					
				</div>
				<div class="list-hotel-content"></div>
				<input type="hidden" name="hotel_book" id="hotel_book" value="" />
				<input type="hidden" name="reservationid" id="reservationid" value="" />	
				<input type="hidden" name="blockdate" id="blockdate" value="" />			
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
					<div class="btn-save-share-room" id="btn-save-share-room" ><span class="loading pull-right save-comment-loading"></span><a href="javascript:void(0)" id="save-share-room">SAVE</a></div>
					<div class="btn-save-share-room" id="btn-book-hotel" style="display:none;" ><span class="loading pull-right save-comment-loading"></span><a href="javascript:void(0)" id="btn-booking-hotel">BOOK HOTEL</a></div>
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
    			<?php 
				if(JRequest::getInt('reservation_id',0) && JRequest::getVar('pass_issue_hotel','') && JRequest::getVar('tb_share_room','')){
						?>
						jQuery('.list-passenger-hotel').css('display','block');	
						//jQuery('#save-share-room').trigger('click');
					<?php }
    			?>
    			
    		}else{
    			$( ".info-service-header-hotel" ).removeClass('service-active');    			
    			$( ".info-service-hotel" ).removeClass('service-active');    			
    			$( "#btn-hotel" ).html('<span>Open<span><img src="<?php echo JURI::root().'media/media/images/select-pass-icons/arrow-down.png' ?>"/>');
    		}
		  	$(".info-service-hotel").slideToggle( "slow" );	
		});	
		$('#book-hotel').click(function(){	
			var pass_ids=getPassengerCheck($);
			if(pass_ids.length>0){
				$('#btn-book-hotel').show();
				$('#btn-save-share-room').hide();
				if(pass_ids.length==1){					
					createlistpassroom(0,1);
				}else{
					createlistpassroom(0,0);
					$('.list-passenger-hotel').show();
				}
				
				// var url = '<?php 
				// $datetime = new DateTime('tomorrow');
				// echo JRoute::_('index.php?date_start='.date('Y-m-d').'&date_end='.$datetime->format('Y-m-d').'&Itemid=119&option=com_sfs&view=search');?>&rooms='+pass_ids.length;
				// var pass_issue_hotel=pass_ids.join('_');
				// location.href = url+'&pass_issue_hotel='+pass_issue_hotel;	
			}
			
			/*if(jQuery('#hotel_book').val()=='' && jQuery('#reservationid').val()=='' && jQuery('#blockdate').val()==''){
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
			}*/			
		});
		$('#save-share-room').click(function(){
			if(jQuery('#hotel_book').val()=='' && jQuery('#reservationid').val()=='' && jQuery('#blockdate').val()==''){
				updateshareroom($('.tbl-list-hotel .ui.toggle.checkbox.checked input[type="checkbox"]').attr('data-resid'));			
			}			
		});

		$('.btn-close-share').click(function(){			
			if($('#reservationid').val()!='' && $('#blockdate').val()!=''){
				jQuery.ajax({
			        url:"<?php echo JURI::base().'index.php?option=com_sfs&task=ajax.searchHotel&format=raw'; ?>",
			        type:"POST",
			        data:{reservationid:$('#reservationid').val(),
			        blockdate:$('#blockdate').val()},
			        dataType: 'text',
			        success:function(response){
			            $('.list-hotel-content').html(response);			            
			        }
			    });
			}			
			$('.list-passenger-hotel').css('display','none');
		});
		$('#btn-booking-hotel').click(function(){				
				bookhotel($);
		});
		<?php if(JRequest::getString('pass_issue_hotel')){?>
			setTimeout(function(){
  				$('.open-form-content-issue').trigger('click');
  				$('#btn-hotel').trigger('click');
			}, 1000);
		<?php } ?>
    

    });
    function createlistpassroom(reservation_id,book_hotel){   
    	var pass_ids=[];
    	var pass_id_nt=[];
    	jQuery('.page_passenger_import input[type="checkbox"]').each(function(index, element) {
				if ( jQuery(this).is(':checked') ) {
					pass_ids.push(jQuery(this).attr('id'));					
				}
		});
		<?php if(JRequest::getInt('reservation_id',0) && JRequest::getVar('pass_issue_hotel','') ){/*
			$room_book_old = json_decode(JRequest::getVar('room_book',''));
			?>
				if(reservation_id==<?php echo JRequest::getInt('reservation_id',0) ?>){
					var tb_share_room_book = jQuery('#tb_share_room_book').val();			
					var ls_share_room_book = tb_share_room_book.split('_');		
					var s_room = <?php echo intval($this->detail_reservation->s_room); ?>;
					var sd_room = <?php echo intval($this->detail_reservation->sd_room); ?>;
					var t_room = <?php echo intval($this->detail_reservation->t_room); ?>;
					var q_room = <?php echo intval($this->detail_reservation->q_room); ?>;
					
				}
			<?php			
			$flag = true;
			if($room_book_old){
				if($room_book_old[1]!=$this->detail_reservation->s_room){
					$flag = false;
				}
				if($room_book_old[2]!=$this->detail_reservation->sd_room){
					$flag = false;
				}
				if($room_book_old[2]!=$this->detail_reservation->t_room){
					$flag = false;
				}
				if($room_book_old[2]!=$this->detail_reservation->q_room){
					$flag = false;
				}
			}
		*/} ?>
    	
    	if(pass_ids.length>0){    		
    		var number_room = 0;
    		var num_chk = 0;
    		var cur_sd_room = 0;
    		var cur_t_room = 0;
    		var cur_q_room = 0;
    		var html_list_room ='';
    		var group_passernger = FillterPasssengerByGroup();
    		if(group_passernger.length){
    			// group_passernger.each(function(value, group_id){     				
    			// 	if(value.length==1){    					
    			// 		pass_id_nt.push(value[0]);
    			// 	}    				
    			// });  
    			// if(pass_id_nt.length>0){
    			// 	html_list_room += createlistroombygroup(pass_id_nt,num_chk,cur_sd_room,cur_t_room,cur_q_room,value.length,'not-together');		
    			// }
    			group_passernger.each(function(value, group_id){ 
    				//if(value.length>1){
    					html_list_room += createlistroombygroup(value,num_chk,cur_sd_room,cur_t_room,cur_q_room,value.length,group_id);		
    				//}    				
    			});    		
    			

    			console.log(pass_id_nt);
    		}
    		<?php 
				if(JRequest::getInt('reservation_id',0) && JRequest::getVar('pass_issue_hotel','') ){
						?>							
						html_list_room = getCookie('tb_share_room');
						html_list_room = decodeBase64(html_list_room);
						jQuery('.list-passenger-share-room').html(html_list_room).promise().done(function(){
					        jQuery('#save-share-room').trigger('click');	
					    });
						jQuery('.list-passenger-hotel').css('display','none');	
									
					<?php }else{
						?>
						jQuery('.list-passenger-share-room').html(html_list_room);   
						jQuery('.list-passenger-hotel').css('display','block');	
						<?php
					}
    			?>
    		 		
    		// if(number_room>1){
    			
    		// }else{
    			
    		// 	// if(book_hotel!=1){
    		// 	// 	//jQuery('#save-share-room').trigger('click');	
    		// 	// }
    		// 	// if(book_hotel==1){
    		// 	// 	bookhotel($);
    		// 	// }
    		// }
    	}
    }

	function createlistroombygroup(pass_ids,num_chk,cur_sd_room,cur_t_room,cur_q_room,number_room,group_id){
		var i=1;
		var html_header='';
		var html_body='';
		html_header+='<tr><th></th>';
		jQuery.each(pass_ids,function(index, element) {
    			var style='border-right: 0!important;border-bottom: 0!important;';
					if(i>1){
						style+='border-top: 0!important;';
					}
				var chk_df = '';				
				if(number_room==1){
					chk_df = 'checked="checked"';
				}

				html_header+='<th style="border-top: 0!important;border-right: 0!important;font-weight: normal;">Room '+i+'</th>';
				html_body+='<tr><td style="'+style+'border-left: 0!important;font-weight: normal;">'+jQuery('#'+element).attr('data-name')+'</td>';
				<?php 
					// xu ly tu dong check phong theo so phong duoc book
					/*if(JRequest::getInt('reservation_id',0) && JRequest::getVar('pass_issue_hotel','') && JRequest::getVar('tb_share_room','')){
						if($flag==false){
							?>
							if(s_room >0 ){
								num_chk = i;
								s_room -- ;
							}else{
								if(sd_room > 0){					 
									 if(cur_sd_room==1){
									 	num_chk ++ ;
									 	cur_sd_room++;
									 }else{
										 if(cur_sd_room==2){
										 	cur_sd_room = 1;
										 	sd_room--;
										 }	
									 }
									 
								}else{
									if(t_room>0){
										if(cur_t_room==1){
											num_chk ++ ;
											cur_t_room++;
										}else{
											if(cur_t_room<3){
												cur_t_room ++;
											}else{
												if(cur_t_room==3){
													cur_t_room=1;
													t_room--;
												}
											}
										}
									}else{
										if(q_room){
											if(cur_q_room==1){
												num_chk ++ ;
												cur_q_room++;
											}else{
												if(cur_q_room<4){
													cur_q_room++;
												}else{
													if(cur_q_room==4){
														cur_q_room=1;
														q_room--;
													}
												}
											}
										}
									}
								}
							}
							<?php
						}
					}*/
				?>
				for(var h=1;h<=number_room;h++){	

					<?php /*if(JRequest::getInt('reservation_id',0) && JRequest::getVar('pass_issue_hotel','') && JRequest::getVar('tb_share_room','')){
						if($flag==true){
							//truong hop so luong phong book bang voi so luong chon truoc do
						?>
							if(reservation_id==<?php echo JRequest::getInt('reservation_id',0) ?>){								
								if(number_room>1){
									chk_df='';
									jQuery.each(ls_share_room_book,function(key, value){
										var info = value.split(':');
										if(info[0]==element){
											if(h==info[1]){
												chk_df='checked="checked"';
											}
										}
									});
								}
							}
						<?php
						}else{
							?>
							chk_df='';
							if(num_chk==h){
								chk_df='checked="checked"';
							}
							<?php
						}
					}*/ ?>

					html_body+='<td style="'+style+'text-align: center;"><input  type="radio" name="room-'+element+'" value="'+h+'" data-id="'+element+'" '+chk_df+' /></td>';
				}

				html_body+='</tr>';
				i++;
			});
    		html_header+='</tr>';
    		var show ='';
    		// if(number_room==1){
    		// 	var show = 'style="display:none"';
    		// }
    		return html_header='<table class="tbl-share-room group-tb-'+group_id+'" cellpadding="0" cellspacing="0" border="0" '+show+' >'+html_header+html_body+'</table>';  
    }

    function updateshareroom(reservation_id){
    	var group_pass = FillterPasssengerByGroup();
    	if(group_pass.length>0){
    		group_pass.each(function(element,index){
    			var pas_id_chk=[];	
    			var share_room=[];
    			pas_id_chk = element;
    			// begin 
				if(pas_id_chk.length>0){

							if(jQuery('.tbl-share-room.group-tb-'+index+' input[type="radio"]:checked').length==pas_id_chk.length){
								var number_room=pas_id_chk.length;
								for(var h=1;h<=number_room;h++){
									share_room[h]=[];
									var list_pass_share='';
									jQuery('.tbl-share-room.group-tb-'+index+' input[type="radio"]:checked').each(function(index, element){						
										if(h==jQuery(this).val()){
											share_room[h].push(jQuery(this).attr('data-id'));				
										}
									});					
								}
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
								
								jQuery('.save-comment-loading').css('display','block');
								jQuery('#save-share-room').css('display','none');
								jQuery.ajax({ url:'<?php echo JURI::base();?>index.php?option=com_sfs&task=ajax.addHotelPassenger&format=raw',
									type:'POST',
									data:{
									pass_ids:pas_id_chk,
									hotel_id:jQuery('#reservation-'+reservation_id).attr('data-hotel'),
									nightdate:jQuery('#nightdate').val(),
									share_room:share_room,
									reservationid: jQuery('#reservation-'+reservation_id).attr('data-resid'),
									//s_number_room:jQuery('#s_number_room'+reservation_id).val(),
									//sd_number_room:jQuery('#sd_number_room'+reservation_id).val(),
									//t_number_room:jQuery('#t_number_room'+reservation_id).val(),
									//q_number_room:jQuery('#q_number_room'+reservation_id).val(),
									mealplan:mealplan,
									lunch:lunch,
									breakfast:breakfast,
									},
									dataType:'json',
				                    success:function(response){                    	
				                    	if(response.hotel_id){                
				                    		jQuery('#hotel_book').val(response.hotel_id);    
				                    		jQuery('#blockdate').val(response.blockdate);		
				                    		jQuery('#reservationid').val(response.reservationid);
				                    		jQuery('.save-comment-loading').css('display','none')
				                    		jQuery('#save-share-room').css('display','none');
				                    		jQuery('.title-header-hotel').html(response.name_hotel);
				                    		jQuery('.img_status_hotel').attr("src","<?php echo JURI::root().'media/media/images/VoucherOK.png'; ?>");
				                    		//alert('Save success!');
				                    		jQuery('body').trigger('hotelsaved', response.hotel_id);
				                    		// jQuery('.info-service-header-taxi').css('display', 'block');
				                    		
				                    	}
				                    	else if(response.successful==0){
				                    		alert('The number of rooms is not enough!');
				                    		jQuery('.save-comment-loading').css('display','none');
				                    		jQuery('#save-share-room').css('display','block');
				                    	}
				                    }
								});				
							}	
							else{
								alert('Please select room!');
							}		
						}

    			//end
    		});
    	}
		jQuery('.page_passenger_import input[type="checkbox"]').each(function(index, element) {
				if ( jQuery(this).is(':checked') ) {
					pas_id_chk.push(jQuery(this).attr('id'));					
				}
		});
		
    }
	function bookhotel($){
		var pas_id_chk=[];		
    	var tb_share_room = '';
		jQuery('.page_passenger_import input[type="checkbox"]').each(function(index, element) {
				if ( jQuery(this).is(':checked') ) {
					pas_id_chk.push(jQuery(this).attr('id'));					
				}
		});
		if(pas_id_chk.length>0){
			var room_book = [];
			room_book[1] = 0;
			room_book[2] = 0;
			room_book[3] = 0;
			room_book[4] = 0;
			var group_passernger = FillterPasssengerByGroup();
    		if(group_passernger.length){
    			group_passernger.each(function(value, group_id){ 
    				var share_room=[];
    				if(jQuery('.tbl-share-room.group-tb-'+group_id+' input[type="radio"]:checked').length==value.length){
    					var number_room = value.length;
    					for(var h=1;h<=number_room;h++){
    						share_room[h]=[];
    						var list_pass_share='';
							jQuery('.tbl-share-room.group-tb-'+group_id+' input[type="radio"]:checked').each(function(index, element){
								if(h==jQuery(this).val()){
									share_room[h].push(jQuery(this).attr('data-id'));
									jQuery(this).attr('checked', true);	
								}
							});
    					}
    				}
    				for(var i= 1; i<=number_room;i++){
						if(share_room[i].length==1){
							room_book[1] = room_book[1] + 1;
						}
						if(share_room[i].length == 2){
							room_book[2] = room_book[2] + 1;	
						}
						if(share_room[i].length == 3){
							room_book[3] = room_book[3] + 1;
						}
						if(share_room[i].length == 4){
							room_book[4] = room_book[4] + 1;
						}					
					}

    			}); 
    		}

			//if(jQuery('.tbl-share-room input[type="radio"]:checked').length==pas_id_chk.length){
				
				// var number_room = pas_id_chk.length;
				// for(var h=1;h<=number_room;h++){
				// 	share_room[h]=[];
				// 	var list_pass_share='';
				// 	jQuery('.tbl-share-room input[type="radio"]:checked').each(function(index, element){						
				// 		if(h==jQuery(this).val()){
				// 			share_room[h].push(jQuery(this).attr('data-id'));	
				// 			//tb_share_room[jQuery(this).attr('name')].push(jQuery(this).val());
				// 			//tb_share_room.push({jQuery(this).attr('name'): jQuery(this).val()});
				// 			if(tb_share_room == ''){
				// 				tb_share_room += jQuery(this).attr('data-id')+':'+jQuery(this).val();
				// 			}else{
				// 				tb_share_room += '_' + jQuery(this).attr('data-id')+':'+jQuery(this).val();
				// 			}
				// 		}
				// 	});					
				// }
				
				
				//console.log(tb_share_room);
				tb_list_passenger_share_room = encodeBase64(jQuery('.list-passenger-share-room').html()); 
				setCookie('tb_share_room',tb_list_passenger_share_room)
				//tb_list_passenger_share_room = decodeBase64(tb_list_passenger_share_room);
				//alert(tb_list_passenger_share_room);
				var url = '<?php 
				$datetime = new DateTime('tomorrow');
				echo JRoute::_('index.php?date_start='.date('Y-m-d').'&date_end='.$datetime->format('Y-m-d').'&Itemid=119&option=com_sfs&view=search');?>&rooms='+pas_id_chk.length;
				var pass_issue_hotel=pas_id_chk.join('_');

				location.href = encodeURI(url+'&pass_issue_hotel='+pass_issue_hotel+'&room_book='+JSON.stringify(room_book));	
				
			//}
		}

    }

    function FillterPasssengerByGroup(){    	
    	var group_passenger = new Array();    
    	var i = 0;	
    	jQuery('.page_passenger_import input[type="checkbox"]').each(function(index, element) {
			if ( jQuery(this).is(':checked') ) {
				//group_passenger[jQuery(this).attr('group_id')][jQuery(this).attr('id')] = jQuery(this).attr('id');	
				if(typeof group_passenger[jQuery(this).attr('data-group_id')] !== 'undefined'){
					if(jQuery(this).attr('data-voucher_id')=='' ||  jQuery(this).attr('data-voucher_id')=='0'){						
						group_passenger[jQuery(this).attr('data-group_id')][i] = jQuery(this).attr('id');
						i++;
					}else{
						jQuery(this).attr('checked', false);	
					}
				}else{
					if(jQuery(this).attr('data-voucher_id')==''  ||  jQuery(this).attr('data-voucher_id')=='0'){
						group_passenger[jQuery(this).attr('data-group_id')] = new  Array();
						i = 0;
						group_passenger[jQuery(this).attr('data-group_id')][i] = jQuery(this).attr('id');
						i++;
					}else{
						jQuery(this).attr('checked', false);	
					}
				}
			}
		});				
    	return group_passenger;   
    }
function setCookie(key, value) {
            var expires = new Date();
            expires.setTime(expires.getTime() + (1 * 24 * 60 * 60 * 1000));
            document.cookie = key + '=' + value + ';expires=' + expires.toUTCString();
        }

        function getCookie(key) {
            var keyValue = document.cookie.match('(^|;) ?' + key + '=([^;]*)(;|$)');
            return keyValue ? keyValue[2] : null;
        }
function encodeBase64(text){
	var Base64={_keyStr:"ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",encode:function(e){var t="";var n,r,i,s,o,u,a;var f=0;e=Base64._utf8_encode(e);while(f<e.length){n=e.charCodeAt(f++);r=e.charCodeAt(f++);i=e.charCodeAt(f++);s=n>>2;o=(n&3)<<4|r>>4;u=(r&15)<<2|i>>6;a=i&63;if(isNaN(r)){u=a=64}else if(isNaN(i)){a=64}t=t+this._keyStr.charAt(s)+this._keyStr.charAt(o)+this._keyStr.charAt(u)+this._keyStr.charAt(a)}return t},decode:function(e){var t="";var n,r,i;var s,o,u,a;var f=0;e=e.replace(/[^A-Za-z0-9+/=]/g,"");while(f<e.length){s=this._keyStr.indexOf(e.charAt(f++));o=this._keyStr.indexOf(e.charAt(f++));u=this._keyStr.indexOf(e.charAt(f++));a=this._keyStr.indexOf(e.charAt(f++));n=s<<2|o>>4;r=(o&15)<<4|u>>2;i=(u&3)<<6|a;t=t+String.fromCharCode(n);if(u!=64){t=t+String.fromCharCode(r)}if(a!=64){t=t+String.fromCharCode(i)}}t=Base64._utf8_decode(t);return t},_utf8_encode:function(e){e=e.replace(/rn/g,"n");var t="";for(var n=0;n<e.length;n++){var r=e.charCodeAt(n);if(r<128){t+=String.fromCharCode(r)}else if(r>127&&r<2048){t+=String.fromCharCode(r>>6|192);t+=String.fromCharCode(r&63|128)}else{t+=String.fromCharCode(r>>12|224);t+=String.fromCharCode(r>>6&63|128);t+=String.fromCharCode(r&63|128)}}return t},_utf8_decode:function(e){var t="";var n=0;var r=c1=c2=0;while(n<e.length){r=e.charCodeAt(n);if(r<128){t+=String.fromCharCode(r);n++}else if(r>191&&r<224){c2=e.charCodeAt(n+1);t+=String.fromCharCode((r&31)<<6|c2&63);n+=2}else{c2=e.charCodeAt(n+1);c3=e.charCodeAt(n+2);t+=String.fromCharCode((r&15)<<12|(c2&63)<<6|c3&63);n+=3}}return t}}
		return Base64.encode(text);
}
function decodeBase64(text){
	var Base64={_keyStr:"ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",encode:function(e){var t="";var n,r,i,s,o,u,a;var f=0;e=Base64._utf8_encode(e);while(f<e.length){n=e.charCodeAt(f++);r=e.charCodeAt(f++);i=e.charCodeAt(f++);s=n>>2;o=(n&3)<<4|r>>4;u=(r&15)<<2|i>>6;a=i&63;if(isNaN(r)){u=a=64}else if(isNaN(i)){a=64}t=t+this._keyStr.charAt(s)+this._keyStr.charAt(o)+this._keyStr.charAt(u)+this._keyStr.charAt(a)}return t},decode:function(e){var t="";var n,r,i;var s,o,u,a;var f=0;e=e.replace(/[^A-Za-z0-9+/=]/g,"");while(f<e.length){s=this._keyStr.indexOf(e.charAt(f++));o=this._keyStr.indexOf(e.charAt(f++));u=this._keyStr.indexOf(e.charAt(f++));a=this._keyStr.indexOf(e.charAt(f++));n=s<<2|o>>4;r=(o&15)<<4|u>>2;i=(u&3)<<6|a;t=t+String.fromCharCode(n);if(u!=64){t=t+String.fromCharCode(r)}if(a!=64){t=t+String.fromCharCode(i)}}t=Base64._utf8_decode(t);return t},_utf8_encode:function(e){e=e.replace(/rn/g,"n");var t="";for(var n=0;n<e.length;n++){var r=e.charCodeAt(n);if(r<128){t+=String.fromCharCode(r)}else if(r>127&&r<2048){t+=String.fromCharCode(r>>6|192);t+=String.fromCharCode(r&63|128)}else{t+=String.fromCharCode(r>>12|224);t+=String.fromCharCode(r>>6&63|128);t+=String.fromCharCode(r&63|128)}}return t},_utf8_decode:function(e){var t="";var n=0;var r=c1=c2=0;while(n<e.length){r=e.charCodeAt(n);if(r<128){t+=String.fromCharCode(r);n++}else if(r>191&&r<224){c2=e.charCodeAt(n+1);t+=String.fromCharCode((r&31)<<6|c2&63);n+=2}else{c2=e.charCodeAt(n+1);c3=e.charCodeAt(n+2);t+=String.fromCharCode((r&15)<<12|(c2&63)<<6|c3&63);n+=3}}return t}}
	return Base64.decode(text);
}

</script>