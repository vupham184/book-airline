<?php
defined('_JEXEC') or die;
$toolTipArray = array('className'=>'tooltip-custom');
JHTML::_('behavior.tooltip', '.hasTip2', $toolTipArray);
$link_Img = JURI::root().'media/media/images/select-pass-icons';
$airline = SFactory::getAirline();
$url_code = SfsUtil::getRandomString(5);
$link_mobile = JUri::root(). 'mobile/?code=' . $url_code . '&tmp=' . $airline->id;
$textSMS = 'View your booking details on \\n' . $link_mobile;
$user = JFactory::getUser();
?>
<style type="text/css">
	body.contentpane{background: #F0F8FF;}
	.issue_head h3 { color: #1DA9B8; }
	#issue_top{float: left;width: 100%;}
	.top_left{float: left;width: 32%; background: #ffffff; height: 116px; padding:10px 0 10px 10px;}
	.top_right{float: left;width: 22%; margin-left: 7px; height: 116px; background: #ffffff; text-align: center;}
	.top_center{float: left; width: 42%; margin-left: 15px;}
	.center_on, .center_down{float: left; width: 100%; height: 56px; background-color: #ffffff; padding:10px;}
	.center_down{margin-top: 3px;}
	.input_center{width:160px; height: 32px; border-color: #25ABB8;}
	.top_right button{margin-top: 40px;}
	.button_issue{
		border:none;
		padding: 8px 10px;
		background-color: #ff8806;
		color: #ffffff;
		font-weight: 600;
	}
	.info_service{float: left; width: 99%; margin-top: 20px;}
	.tableInfo{float: left; width: 100%;background-color: #ffffff;}
	.tableInfo th{
		color: #1DA9B8; text-align: left; padding: 20px 0 10px 5px;
		font-size: 13px !important; font-weight: 600;
	}
	.tableInfo td{
		border-bottom: 2px solid #1DA9B8; padding: 10px 0 10px 10px;font-size: 12px !important;
		font-weight: 600;
	}

	#sendemailvoucher, #printvoucher, #sendsmsvoucher{background: #ff8806; padding: 7px 9px; color:#ffffff;}
	#printvoucher{position: relative; top: 40%;}
	.header-info-service{
		width: 100%;
		float: left;
	}
	/* .service-list{ float: left; } */
	.service-list p {width: 100px; display: inline-block;} 
	.service-list div{
		margin: 10px 0px;
	}
	.tr-color{
		background-color: #EEEEEE;
	}
	.title_other{width: 200px; margin:-4px 0px 0px 30px; display: none;}
	span{color: #1DA9B8;}
	.list-passenger{
		width: 100%;
	}

	.countPass,.list_more_name_passenger{
		display: none;
	}
	.countPass{
		cursor: pointer;
	}
	.list_more_name_passenger{
		background-color: #fff;
	    border: 1px solid #1DA9B8;
	    position: absolute;
	    z-index: 10;
	    width: 700px;
	    max-height: 300px;
	    min-height: 150px;
	    overflow-y: scroll; 
	}
	.list_more_name_passenger p{
		padding: 0px 10px;
	}

</style>

<div>
	    <a href="javascript:void(0);" class="sfs-button pull-right close-content" style="font: inherit; font-size: 14px; margin-top: -3px; margin-right: 5px;position: absolute;right: 0;z-index: 10;">Close</a>
	</div>
<div style="margin-left: 10px;" class="main-issue">	
<div class="list_more_name_passenger"></div>
<div style="margin-left: 10px;">	
	<div class="issue_head">
		<h3>Issue vouchers</h3>
	</div>
	
	<div id="issue_top">
		<div class="top_left">
			<img src="<?php  echo $link_Img.'/group.png' ?>" alt="" width="25px"><br/>
			<div class="list-passenger"></div>
			<u class="countPass" ></u>
			
			<input type="hidden" style="display: none;" id="id_group_voucher" value="2">
		</div>
		<div class="top_center">
			<div class="center_on">
				<input type="text" id="email" name="email" class="input_center">
				<!-- <button id="sendemailvoucher" class="button_issue">EMAIL VOUCHERS</button> -->
				<a id="sendemailvoucher" href="javascript:void(0);">EMAIL VOUCHERS</a>
			</div>
			<div class="center_down" style="position:relative;">
				<input type="text" id="tel" name="sms" class="input_center">
				<!-- <button  class="button_issue">SMS VOUCHERS</button> -->
				<a id="sendsmsvoucher" data-flight-number="" data-std="" data-etd = "" data-passengers="" data-url-code="<?php echo $url_code;?>" onclick="sendsmsvoucher(this);" href="javascript:void(0);">SMS VOUCHERS</a>
                <span class="ajx-loading loading" style="display:none; position:absolute; left:150px; top:0px;">&nbsp;</span>
			</div>
		</div>
		<div class="top_right">
			<!-- <button class="button_issue">PRINT VOUCHERS</button> -->
			<a id="printvoucher" href="javascript:void(0);">PRINT VOUCHERS</a>
		</div>
		<div class="info_service">
			<div class="info-service-header">
				<table class="tableInfo" cellpadding="0" cellspacing="0" border="0">
					<tr>
						<th width="40%">Service</th>
						<th width="10%">Status</th>
						<th width="40%">Details</th>
						<th width="10%">Details</th>
					</tr>
				</table>
			</div>
			<!--<div class="info-service-header-hotel " style="display: none;">
				<table class="tableInfo" cellpadding="0" cellspacing="0" border="0">
					<tr>
						<td width="40%">Hotel accommodation</td>
						<td width="10%">
							<img src="<?php echo JURI::root().'media/media/images/VoucherOK.png' ?>">
						</td>
						<td width="40%">Econtel Berlin</td>
						<td width="10%"><span>Open</span></td>
					</tr>
				</table>				
			</div>
			<div class="info-service-header-taxi" style="display: none;">
				<table class="tableInfo" cellpadding="0" cellspacing="0" border="0">			
					<tr >
						<td width="40%">Taxi transfer</td>
						<td width="10%">
							<img src="<?php echo JURI::root().'media/media/images/VoucherOK.png' ?>">
						</td>
						<td width="40%">Taxi blue Berlin</td>
						<td width="10%"><span>Open</span></td>
					</tr>
				</table>
			</div>-->
			<?php  //echo $this->loadTemplate('service_rental_car');?>			
			<?php //echo $this->loadTemplate('issue_voucher_cash_refunds'); ?>
			<?php //echo $this->loadTemplate('issue_voucher_refreshment'); ?>

			<?php  echo $this->loadTemplate('service_train_ticket');?>
			
			<?php  echo $this->loadTemplate('service_hotel');?>
			
			<?php  echo $this->loadTemplate('service_taxi');?>

			<?php  echo $this->loadTemplate('service_rental_car');?>

			

			<?php //echo $this->loadTemplate('issue_voucher_cash_refunds'); ?>
				<!--<tr><td colspan="4" style="border:none;"></td></tr>-->
			<table class="tableInfo" cellpadding="0" cellspacing="0" border="0">
				<?php echo $this->loadTemplate('issue_voucher_refreshment'); ?>
				<?php echo $this->loadTemplate('issue_group_transport_company'); ?>
			</table>
			<div class="issue_sub_other_service" style="display:none">
				<?php echo $this->loadTemplate('sub_other_service'); ?>
			</div>
		</div>
	</div>
</div>

<script src="<?php echo JURI::base()?>media/system/js/modal-uncompressed.js" type="text/javascript"></script>
<script type="text/javascript">
	
	
	
	function sendsmsvoucher(obj){
		var data_tel = jQuery('#tel').val();
		var data_passengers = jQuery(obj).attr("data-passengers");
		var data_url_code = jQuery(obj).attr("data-url-code");
		var data_flight_number = jQuery(obj).attr("data-flight-number");
		var data_std = jQuery(obj).attr("data-std");
		var data_etd = jQuery(obj).attr("data-etd");
		jQuery('.ajx-loading').css('display', 'block');
		if( data_tel == '' ) {
			alert("We can not find the Phone number to the passenger Mrs Candy, we can not issue the vouchers per SMS per this passenger.");
			jQuery('.ajx-loading').css('display', 'none');
			return false;
		}
		var pass_ids = getPassengerCheck(jQuery);
		var da = {
			'text_message': '<?php echo $textSMS;?>', 
			'data_tel': data_tel, 
			'data_passengers' : data_passengers,
			'data_url_code' : data_url_code,
			'flight_number' : data_flight_number,
			'std' : data_std,
			'etd' : data_etd,
			'pass_ids':pass_ids
		}
			

			jQuery.post("index.php?option=com_sfs&task=internalcomment.sendSMS",da,
			function(data, status){		
				jQuery('.ajx-loading').css('display', 'none');		
				if( data.successful == 1 ){
					var v_pas_id_com = getPassengerCheck(jQuery);
					//truong hop gui SMS
					var da = {'printtype':'', 'voucher_id': v_voucher_id,'passenger_id': v_pas_id_com[0],'email': ''};
					jQuery.post("index.php?option=com_sfs&task=passengersimport.updateStatusVoucher",da,
					function(data, status){		
						//
						jQuery('.ajx-loading').css('display', 'none');
					}, 'json');
					
					var dacommentPassenger = {'internal_comment':'Voucher send by text SMS on <?php echo date('d-M-Y H:i:s'); ?> by <?php echo $user->name;?>', 'passenger_id': v_pas_id_com[0] };
					jQuery.post("index.php?option=com_sfs&task=internalcomment.save", dacommentPassenger,
					function(data, status){		
						//
						document.location.reload(true);
						jQuery('.ajx-loading').css('display', 'none');
					}, 'json');
					
					alert(data.messages.message.errormessage);
					document.location.reload( true );
					
				}
				else if( data.errorcode == 'maxl' ) {
					alert(data.errormessage);
					document.location.reload( true );
				}
				else if( data.errorcode != 0 ) {
					alert(data.errormessage);
					document.location.reload( true );
				}
			}, 'json');
				
				
	}
	
	//lchung
	
	jQuery(function( $ ){
		$('#content-issue #printvoucher').on('click',function(){
			var v_pas_id_com = getPassengerCheck($);
			var reservationid = $('#hotel-content #reservationid').val();
			if(v_voucher_id == 0 ){
				v_voucher_id = 1;
			}
			// if (v_voucher_id == 0 ) {
			// 	alert('Please choose a service Hotel');
			// }
			// else {
				
				<?php if( (int)$airline->id == 41): ?>
					SqueezeBox.open('<?php echo 'index.php?option=com_sfs&view=voucher&layout=default_single_specific&tpl_issue_voucher=1&tmpl=component';?>&id='+v_pas_id_com+'&voucher_id=' + v_voucher_id + '&voucher_groups_id=' + v_voucher_groups_id +'&reservation_id= '+ reservationid  + '&flight_number=' + v_flight_number, {handler: 'iframe', size: {x: 730, y: 540} });
				<?php else: ?>
					SqueezeBox.open('<?php echo 'index.php?option=com_sfs&view=voucher&tmpl=component';?>&voucher_id=' + v_voucher_id + '&voucher_groups_id=' + v_voucher_groups_id +'&reservation_id= '+ reservationid  + '&flight_number=' + v_flight_number, {handler: 'iframe', size: {x: 730, y: 600} });
				<?php endif; ?>


				var da = {'printtype':'print', 'voucher_id': v_voucher_id, 'passenger_id': v_pas_id_com[0],'pass_ids':v_pas_id_com};
				$.post("index.php?option=com_sfs&task=passengersimport.updateStatusVoucher",da,
				function(data, status){		
					//
				}, 'json');
				
				var dacommentPassenger = {'internal_comment':'Voucher printed on <?php echo date('d-M-Y H:i:s'); ?> by <?php echo $user->name;?>', 'passenger_id': v_pas_id_com[0] };
				$.post("index.php?option=com_sfs&task=internalcomment.save", dacommentPassenger,
				function(data, status){		
					//
					document.location.reload( true );
				}, 'json');
				
			// }
		});
		
		$('#sendemailvoucher').click( function(){
			var v_pas_id_com = getPassengerCheck($);
			var vemail = $('#content-issue input[id="email"]').val();
			//get Services 
			if (v_pas_id_com) {
				var id = v_pas_id_com[0];
				var service_IDs = $("#pass-service-"+id).val();
				alert(id);
			}
			if( vemail == '' ){
				alert('Please enter a email');
				return false;
			}

			if (v_voucher_id == 0 ) {
				if ($('#content-issue .info-service-header-taxi').is(':visible')) {
					sendMail(vemail);
				}
				else{
					alert('Please choose Hotel');
				}
			}
			else {
				
				var da = {'printtype':'email', 'voucher_id': v_voucher_id,'passenger_id': v_pas_id_com[0],'email': vemail,'pass_ids':v_pas_id_com};
				
				$.ajax({	            
					url: "index.php?option=com_sfs&task=passengersimport.sendemailvoucher",
					type: "POST",
					data:da,
					dataType:"json",        
					success: function(data){
						if( data.successful == 0 ) {
							alert(data.errormessage);
							$('#email').focus();
						}
						else {
							alert(data.errormessage);
							$('.ajx-loading').css('display', 'block');				
							
							$.post("index.php?option=com_sfs&task=passengersimport.updateStatusVoucher",da,
							function(data, status){		
								//
								$('.ajx-loading').css('display', 'none');
							}, 'json');
							
							var dacommentPassenger = {'internal_comment':'Voucher send by email to ' + vemail + ' on <?php echo date('d-M-Y H:i:s'); ?> by <?php echo $user->name;?>', 'passenger_id': v_pas_id_com[0] };
							$.post("index.php?option=com_sfs&task=internalcomment.save", dacommentPassenger,
							function(data, status){		
								//
								document.location.reload(true);
								$('.ajx-loading').css('display', 'none');
							}, 'json');
							
						}//End else of if( data.successful == 0 ) {
						
					}//End success:
					
				});
				
			}//End else of if (v_voucher_id == 0
			
		});//End $('#sendemailvoucher').click

		//function send mail if voucher ID not exist
		function sendMail(mailAddress){
			$.ajax({
				url: 'index.php?option=com_sfs&task=passengersimport.sendMail',
				type: 'POST',
				data: {
					mailAddress : mailAddress
				},
			})
			.done(function(success) {
				if (success > 0)
					alert('success');
				else
					alert('fail')
			})
			.fail(function() {
				console.log("error");
			});
			
		}
		
	});
	//End lchung
</script>
