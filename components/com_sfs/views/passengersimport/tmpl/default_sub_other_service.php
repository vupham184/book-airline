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
	#issue_sub_other{float: left;width: 100%;}
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
	.main-sub-service{
		width: 750px;
	}
</style>
<div>
	<!-- <a href="javascript:void(0);" class="sfs-button pull-right close-sub-service" style="font: inherit; font-size: 14px; margin-top: -3px; margin-right: 5px;position: absolute;right: 0;z-index: 10;">Close</a> -->
	<a href="javascript:void(0);" class="sfs-button pull-right close-content close-tplsub-service" style="font: inherit; font-size: 14px; margin-top: -3px; margin-right: 5px;position: absolute;right: 0;z-index: 10;">Close</a>
</div>
<div style="margin-left: 10px;" class="main-sub-service">	
	<div style="margin-left: 10px;">	
		<div class="issue_head">
			<h3>Other Service</h3>
		</div>

		<div id="issue_sub_other">
			<div class="top_left">
				<img src="<?php  echo $link_Img.'/group.png' ?>" alt="" width="25px"><br/>
				<div class="list-passenger"></div>
				<span type="hidden" class="sub_service_id" value=""></span>
				<input type="hidden" class="info_other_service" value="">
				<input type="hidden" class="info_other_service_id" value="">
				<input type="hidden" id="id_group_voucher" value="2">
			</div>

			<div class="info_service">
				<div class="info-service-header">
					<table class="tableInfo" cellpadding="0" cellspacing="0" border="0">
						<tr>
							<th width="40%">Service</th>
							<th width="10%">Status</th>
							<th width="40%">Details</th>
							<th width="10%"></th>
						</tr>
					</table>
				</div>
				<div class="info-ss-header-maas" style="">	
				<table class="tableInfo" cellpadding="0" cellspacing="0" border="0">
					<tr>			
						<td width="40%" class="ss-service">MAAS</td>
						<td width="10%">
							<img class="icon_train" src="<?php echo JURI::root().'media/media/images/VoucherNOK.png' ?>">
						</td>	
						<td width="40%" class="ss-title">MAAS Enabled</td>
						<td  width="10%"><div id="" class="btn-open"><span>Close<span><img src="<?php echo JURI::root().'media/media/images/select-pass-icons/arrow-up.png' ?>"/></div></td>
					</tr>	
					<tr>
					
					</tr>
				</table>	
			</div>
				<?php  echo $this->loadTemplate('ss_other_maas');?>
				<?php echo $this->loadTemplate('ss_other_phonecard'); ?>
				<?php echo $this->loadTemplate('ss_other_miscellaneous'); ?>
				<?php echo $this->loadTemplate('ss_other_cash'); ?>
				<?php echo $this->loadTemplate('ss_other_snackbags'); ?>
			</div>
		</div>
	</div>

	<script type="text/javascript">
		jQuery.noConflict();
		jQuery(function($){
			jQuery(document).ready(function($) {
				$('.close-sub-service').on('click', function() {
					$('.add_sub_services').hide('slow');
				});

				$('.inputamount').keypress(function (event) {
					return isNumber(event, this)
				});
			});
			//case if add service show

			$( ".add_sub_services .info-ss-header-maas .btn-open" ).off().on('click',function() {
				if($(".add_sub_services .info-ss-header-maas .btn-open" ).children('span').text()=="Open"){    			
					$( ".add_sub_services .info-ss-header-maas" ).addClass('service-active');
					$( ".info-ss-maas" ).addClass('service-active');   
					$(".add_sub_services .info-ss-header-maas .btn-open" ).html('<span>Close<span><img src="<?php echo JURI::root().'media/media/images/select-pass-icons/arrow-up.png' ?>"/>');	
					
				}else{
					$( ".add_sub_services .info-ss-header-maas" ).removeClass('service-active');    			
					$( ".info-ss-maas" ).removeClass('service-active');    
					$(".add_sub_services .info-ss-header-maas .btn-open" ).html('<span>Open<span><img src="<?php echo JURI::root().'media/media/images/select-pass-icons/arrow-down.png' ?>"/>'); 				
					
				}
				$(".add_sub_services .info_service .active").slideToggle( "slow" );
			});	



			

		//begin script for ss_other_snackbags
			//status when user choice active
			// var sub_service_id = $('#issue_sub_other .sub_service_id').attr('value');
			var status_info_ss_snackbags = 1;
			$('.add_sub_services .info-ss-snackbags .content-other .checkbox').on('change', function() {
				var sub_service_id = $('.add_sub_services #issue_sub_other .sub_service_id').attr('value');
				if($(this).hasClass('checked')){
					$('.add_sub_services .info-ss-snackbags .sub_service').text('Enabled');
					status_info_ss_snackbags = 1;
				}
				else{
					$('.add_sub_services .info-ss-snackbags .sub_service').text('Disable');
					status_info_ss_snackbags = 0;
				}

				var internal_comment = $('.add_sub_services .info-ss-snackbags .internal_comment').val();
				var passenger_ids = getPassengerCheck($);

				var data_content = {
					status_info_ss_snackbags: status_info_ss_snackbags,
					internal_comment: internal_comment
				}

				saveOtherServices(passenger_ids,sub_service_id,data_content);

			});

			$('.add_sub_services .info-ss-snackbags .save_ss_snackbags').on('click', function() {
				var sub_service_id = $('.add_sub_services #issue_sub_other .sub_service_id').attr('value');
				var internal_comment = $('.add_sub_services .info-ss-snackbags .internal_comment').val();
				var passenger_ids = getPassengerCheck($);

				var data_content = {
					status_info_ss_snackbags: status_info_ss_snackbags,
					internal_comment: internal_comment
				}

				saveOtherServices(passenger_ids,sub_service_id,data_content);
				
				
			});


		//end script 

		//begin script for ss_other_phonecard

		$('.add_sub_services .info-ss-phonecard .save_ss_phonecard').on('click', function() {
			var sub_service_id 		= $('.add_sub_services #issue_sub_other .sub_service_id').attr('value');
			var description 		= $('.add_sub_services .info-ss-phonecard .description').val();
			var inputamount 		= $('.add_sub_services .info-ss-phonecard .inputamount').val();
			var currency_code 		= $('.add_sub_services .info-ss-phonecard .currency_code').val();
			var internal_comment 	= $('.add_sub_services .info-ss-phonecard .internal_comment').val();

			var passenger_ids = getPassengerCheck($);

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

		$('.add_sub_services .info-ss-cash .save_ss_cash').on('click', function() {
			var sub_service_id 		= $('.add_sub_services #issue_sub_other .sub_service_id').attr('value');
			var inputamount 		= $('.add_sub_services .info-ss-cash .inputamount').val();
			var currency_code 		= $('.add_sub_services .info-ss-cash .currency_code').val();
			var internal_comment 	= $('.add_sub_services .info-ss-cash .internal_comment').val();

			var passenger_ids = getPassengerCheck($);

			var data_content = {
				inputamount: inputamount,
				currency_code:currency_code,
				internal_comment:internal_comment
			}

			saveOtherServices(passenger_ids,sub_service_id,data_content);


		});

		//end script 


		//begin script for ss-cash

		$('.add_sub_services .info-ss-miscellaneous .save_ss_miscellaneous').on('click', function() {
			var sub_service_id 		= $('.add_sub_services #issue_sub_other .sub_service_id').attr('value');
			var title 		 		= $('.add_sub_services .info-ss-miscellaneous .title').val();
			var description 		= $('.add_sub_services .info-ss-miscellaneous .description').val();
			var inputamount 		= $('.add_sub_services .info-ss-miscellaneous .inputamount').val();
			var currency_code 		= $('.add_sub_services .info-ss-miscellaneous .currency_code').val();
			var internal_comment 	= $('.add_sub_services .info-ss-miscellaneous .internal_comment').val();

			var passenger_ids = getPassengerCheck($);

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

		//case if click issue voucher show
		$( ".issue_sub_other_service .info-ss-header-maas .btn-open" ).off().on('click',function() {
				if($(".issue_sub_other_service .info-ss-header-maas .btn-open" ).children('span').text()=="Open"){    			
					$( ".issue_sub_other_service .info-ss-header-maas" ).addClass('service-active');
					$( ".info-ss-maas" ).addClass('service-active');    			
					$(".issue_sub_other_service .info-ss-header-maas .btn-open" ).html('<span>Close<span><img src="<?php echo JURI::root().'media/media/images/select-pass-icons/arrow-up.png' ?>"/>');
				}else{
					$( ".issue_sub_other_service .info-ss-header-maas" ).removeClass('service-active');    			
					$( ".info-ss-maas" ).removeClass('service-active');    			
					$(".issue_sub_other_service .info-ss-header-maas .btn-open" ).html('<span>Open<span><img src="<?php echo JURI::root().'media/media/images/select-pass-icons/arrow-down.png' ?>"/>');
				}
				$(".issue_sub_other_service .info_service .active").slideToggle( "slow" );
			});	



			

		//begin script for ss_other_snackbags
			//status when user choice active
			// var sub_service_id = $('#issue_sub_other .sub_service_id').attr('value');
			var status_info_ss_snackbags = 1;
			$('.issue_sub_other_service .info-ss-snackbags .content-other .checkbox').on('change', function() {
				var sub_service_id = $('.issue_sub_other_service #issue_sub_other .sub_service_id').attr('value');
				if($(this).hasClass('checked')){
					$('.issue_sub_other_service .info-ss-snackbags .sub_service').text('Enabled');
					status_info_ss_snackbags = 1;
				}
				else{
					$('.issue_sub_other_service .info-ss-snackbags .sub_service').text('Disable');
					status_info_ss_snackbags = 0;
				}

				var internal_comment = $('.issue_sub_other_service .info-ss-snackbags .internal_comment').val();
				var passenger_ids = getPassengerCheck($);

				var data_content = {
					status_info_ss_snackbags: status_info_ss_snackbags,
					internal_comment: internal_comment
				}

				saveOtherServices(passenger_ids,sub_service_id,data_content);

			});

			$('.issue_sub_other_service .info-ss-snackbags .save_ss_snackbags').on('click', function() {
				var sub_service_id = $('.issue_sub_other_service #issue_sub_other .sub_service_id').attr('value');
				var internal_comment = $('.issue_sub_other_service .info-ss-snackbags .internal_comment').val();
				var passenger_ids = getPassengerCheck($);

				var data_content = {
					status_info_ss_snackbags: status_info_ss_snackbags,
					internal_comment: internal_comment
				}

				saveOtherServices(passenger_ids,sub_service_id,data_content);
				
				
			});


		//end script 

		//begin script for ss_other_phonecard

		$('.issue_sub_other_service .info-ss-phonecard .save_ss_phonecard').on('click', function() {
			var sub_service_id 		= $('.issue_sub_other_service #issue_sub_other .sub_service_id').attr('value');
			var description 		= $('.issue_sub_other_service .info-ss-phonecard .description').val();
			var inputamount 		= $('.issue_sub_other_service .info-ss-phonecard .inputamount').val();
			var currency_code 		= $('.issue_sub_other_service .info-ss-phonecard .currency_code').val();
			var internal_comment 	= $('.issue_sub_other_service .info-ss-phonecard .internal_comment').val();

			var passenger_ids = getPassengerCheck($);

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

		$('.issue_sub_other_service .info-ss-cash .save_ss_cash').on('click', function() {
			var sub_service_id 		= $('.issue_sub_other_service #issue_sub_other .sub_service_id').attr('value');
			var inputamount 		= $('.issue_sub_other_service .info-ss-cash .inputamount').val();
			var currency_code 		= $('.issue_sub_other_service .info-ss-cash .currency_code').val();
			var internal_comment 	= $('.issue_sub_other_service .info-ss-cash .internal_comment').val();

			var passenger_ids = getPassengerCheck($);

			var data_content = {
				inputamount: inputamount,
				currency_code:currency_code,
				internal_comment:internal_comment
			}

			saveOtherServices(passenger_ids,sub_service_id,data_content);


		});

		//end script 


		//begin script for ss-cash

		$('.issue_sub_other_service .info-ss-miscellaneous .save_ss_miscellaneous').on('click', function() {
			var sub_service_id 		= $('.issue_sub_other_service #issue_sub_other .sub_service_id').attr('value');
			var title 		 		= $('.issue_sub_other_service .info-ss-miscellaneous .title').val();
			var description 		= $('.issue_sub_other_service .info-ss-miscellaneous .description').val();
			var inputamount 		= $('.issue_sub_other_service .info-ss-miscellaneous .inputamount').val();
			var currency_code 		= $('.issue_sub_other_service .info-ss-miscellaneous .currency_code').val();
			var internal_comment 	= $('.issue_sub_other_service .info-ss-miscellaneous .internal_comment').val();

			var passenger_ids = getPassengerCheck($);

			var data_content = {
				title:title,
				description:description,
				inputamount: inputamount,
				currency_code:currency_code,
				internal_comment:internal_comment
			}

			saveOtherServices(passenger_ids,sub_service_id,data_content);


		});

function saveOtherServices(passenger_ids,sub_service_id,data_content){
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
			$('.add_sub_services .info-ss-header-maas .icon_train').attr("src","<?php echo JURI::root().'media/media/images/VoucherOK.png' ?>");
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
