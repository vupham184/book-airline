<?php 
defined('_JEXEC') or die;
$startDateList = SfsHelperDate::getSearchDate('expire', 'class="inputbox fs-13" style="width:155px;"');
$airline = SFactory::getAirline();
$user = JFactory::getUser();
$contentSubService = json_decode($this->item->subServiceOther->content);

?>
<style type="text/css">	
	.info-service-trainTicket.service-active{
		background: #eeeeee;	
	}
	.info-service-header-trainTicket.service-active .tableInfo td{
		border-bottom: 2px solid #eeeeee!important;
	}
	.info-service-header-trainTicket.service-active  .tableInfo,.info-service-trainTicket.service-active .tableInfo{
		background: #eeeeee;	
	}
	.service-list.content-other{
		width: 99%;
		background: #fff;
		padding: 5px;
	}
	.push-right{
		float: right;
	}
	.push-left{
		float: left;
	}
	.clear{
		clear: both;
	}
	.info-ss-phonecard .push-right div,input,select{
		vertical-align: middle;
	}
	.info-ss-phonecard .push-right{
		margin: 0px;
	}
</style>

<div class="info-ss-phonecard info_sub_service" >
	<div class="info-ss-header-maas" style="">	
		<table class="tableInfo" cellpadding="0" cellspacing="0" border="0">
			<tr>			
				<td width="40%" class="ss-service">Phonecard</td>
				<td width="10%">
					<img class="icon_train" src="<?php echo JURI::root().'media/media/images/VoucherNOK.png' ?>">
				</td>	
				<td width="40%" class="ss-title">Phonecard Enabled</td>
				<td  width="10%"><div id="" class="btn-open"><span>Open<span><img src="<?php echo JURI::root().'media/media/images/select-pass-icons/arrow-down.png' ?>"/></div></td>
			</tr>	
			<tr>
				
			</tr>
		</table>	
	</div>
	<div class="sub-services-phonecard" style="position: relative; display: none;">
	<table class="tableInfo" cellpadding="0" cellspacing="0" border="0">
		<tr >
			<td >				
				<div >
					<div class="service-list content-other" >
						<div class="push-left" style="width:250px">
							<img src="<?php echo JURI::base().'media/media/images/phonecard.png'; ?>" style="width:90px" alt="">
						</div>
						<div class="">
							<span class="color-text sub_service " style="padding-right:4px">Description</span>
							<input type="text" style="width:300px" class="description">
						</div>
						<div class="" >
							<span class="color-text sub_service " style="padding-right:40px">Cost</span>
							<input type="text" style="width:100px" class="inputamount">
							<select style="width:100px" class="currency_code">
								<?php if ($airline->currency_code != 'EUR'): ?>
									<option value="EUR">EUR</option>
									<option value="<?php echo $airline->currency_code; ?>"><?php echo $airline->currency_code; ?></option>
								<?php else: ?>
									<option value="EUR">EUR</option>
								<?php endif ?>
							</select>
						</div>
						<div class="push-right" style="padding-right: 142px;">   
							<p class="color-text" style="vertical-align:top;width:70px">Internal Comment</p>     
							<textarea rows="6" cols="30" class="internal_comment"></textarea>
						</div>
					</div>
					<div class="clear"></div>
					<div  style="float: right; padding-right: 30px; "><a style="cursor:pointer;" class="button_issue save_ss_phonecard" >Save</a></div><div class="clear"></div>
				</div><div style= "display:none" class="validate"></div>
			</div>
		</div>
	</td>
</tr>
</table>
</div>
</div>

<script type="text/javascript">
var description = '<?php echo $contentSubService[0]->description ?>';
var amount 		= '<?php echo $contentSubService[0]->inputamount?>';
var code 		= '<?php echo $contentSubService[0]->currency_code ; ?>';
var comment 	= '<?php echo $contentSubService[0]->internal_comment ?>';


	jQuery(function($){

		$('.info-ss-phonecard .title').val(title);
		$('.info-ss-phonecard .description').val(description);
		$('.info-ss-phonecard .internal_comment').val(comment);
		$('.info-ss-phonecard .inputamount ').val(amount);
		$('.info-ss-phonecard .currency_code option').text(code);
		$('.info-ss-header-maas .icon_train').attr('src', '<?php echo JURI::root().'media/media/images/VoucherOK.png' ?>');


		$( ".info-ss-phonecard .btn-open" ).on('click',function() { 
			if($( ".info-ss-phonecard .btn-open span" ).text()=="Open"){    			
				$( ".info-ss-phonecard" ).addClass('service-active');
				$( ".sub-services-phonecard" ).addClass('service-active');    		
				$( ".info-ss-phonecard .btn-open" ).html('<span>Close<span><img src="<?php echo JURI::root().'media/media/images/select-pass-icons/arrow-up.png' ?>"/>');


			}else{
				$( ".info-ss-phonecard" ).removeClass('service-active');    			
				$( ".sub-services-phonecard" ).removeClass('service-active');    			
				$( ".info-ss-phonecard .btn-open" ).html('<span>Open<span><img src="<?php echo JURI::root().'media/media/images/select-pass-icons/arrow-down.png' ?>"/>');
			}
			$(".sub-services-phonecard").slideToggle( "slow" );	
		});
	});

</script>