<?php 
defined('_JEXEC') or die;
$contentSubService = json_decode($this->item->subServiceOther->content);
// echo "<pre>";
// print_r($this->airline);
// echo "</pre>";
?>
<style type="text/css">	
	.info-service-other.service-active{
		background: #eeeeee;	
	}
	.info-service-header-other.service-active .tableInfo td{
		border-bottom: 2px solid #eeeeee!important;
	}
	.info-service-header-other.service-active  .tableInfo,.info-service-other.service-active .tableInfo{
		background: #eeeeee;	
	}
	.service-list.content-other{
		width: 99%;
		background: #fff;
		padding: 5px;
	}
</style>
<div class="info-ss-miscellaneous info_sub_service" >
	<div class="info-ss-header-maas" style="">	
		<table class="tableInfo" cellpadding="0" cellspacing="0" border="0">
			<tr>			
				<td width="40%" class="ss-service">Miscellaneous</td>
				<td width="10%">
					<img class="icon_train" src="<?php echo JURI::root().'media/media/images/VoucherNOK.png' ?>">
				</td>	
				<td width="40%" class="ss-title">Miscellaneous Enabled</td>
				<td  width="10%"><div id="" class="btn-open"><span>Open<span><img src="<?php echo JURI::root().'media/media/images/select-pass-icons/arrow-down.png' ?>"/></div></td>
			</tr>	
			<tr>
				
			</tr>
		</table>	
	</div>
	<div class="sub-services-miscellaneous" style="position: relative; display: none;">
		<table class="tableInfo" cellpadding="0" cellspacing="0" border="0">
			<tr id="rental-car-content">	
				<td>				
					<div class="">
						<div class="service-list content-other" >
							<div>
								<p class="color-text">Title</p>
								<input type="text" style="width: 300px;"  class="title"></input>
							</div>

							<div>
								<p class="color-text">Description</p>
								<input type="text" style="width: 500px;" id="description" class="description"></input>
							</div>
							<div>
								<span class="color-text sub_service " style="padding-right:40px">Cost</span>
								<input type="text" style="width:100px;margin-left: 32px;" class="inputamount">
								<select style="width:100px" class="currency_code">
									<?php if ($airline->currency_code != 'EUR'): ?>
										<option value="EUR">EUR</option>
										<option value="<?php echo $airline->currency_code; ?>"><?php echo $airline->currency_code; ?></option>
									<?php else: ?>
										<option value="EUR">EUR</option>
									<?php endif ?>
								</select>
							</div>
							<div>
								<p style="margin-top: -100px; vertical-align: middle;" class="color-text">Iternal Comment</p><textarea class="internal_comment " rows="4" cols="39"></textarea>
							</div>
							<div  style="float: right; padding-right: 30px; "><a style="cursor:pointer;" class="button_issue save_ss_miscellaneous" >Save</a></div>
						</div><div style= "display:none" class="validate"></div>
					</div>
				</div>
			</td>
		</tr>
	</table>
</div>
</div>
<script type="text/javascript">
var title 		= '<?php echo $contentSubService[0]->title ?>';
var description = '<?php echo $contentSubService[0]->description ?>';
var amount 		= '<?php echo $contentSubService[0]->inputamount?>';
var code 		= '<?php echo $contentSubService[0]->currency_code ; ?>';
var comment 	= '<?php echo $contentSubService[0]->internal_comment ?>';


	jQuery(function($){

		$('.info-ss-miscellaneous .title').val(title);
		$('.info-ss-miscellaneous .description').val(description);
		$('.info-ss-miscellaneous .internal_comment').val(comment);
		$('.info-ss-miscellaneous .inputamount ').val(amount);
		$('.info-ss-miscellaneous .currency_code option').text(code);
		$('.info-ss-header-maas .icon_train').attr('src', '<?php echo JURI::root().'media/media/images/VoucherOK.png' ?>');


		$( ".info-ss-miscellaneous .btn-open" ).on('click',function() { 
			if($( ".info-ss-miscellaneous .btn-open span" ).text()=="Open"){    			
				$( ".info-ss-miscellaneous" ).addClass('service-active');
				$( ".sub-services-miscellaneous" ).addClass('service-active');    		
				$( ".info-ss-miscellaneous .btn-open" ).html('<span>Close<span><img src="<?php echo JURI::root().'media/media/images/select-pass-icons/arrow-up.png' ?>"/>');


			}else{
				$( ".info-ss-miscellaneous" ).removeClass('service-active');    			
				$( ".sub-services-miscellaneous" ).removeClass('service-active');    			
				$( ".info-ss-miscellaneous .btn-open" ).html('<span>Open<span><img src="<?php echo JURI::root().'media/media/images/select-pass-icons/arrow-down.png' ?>"/>');
			}
			$(".sub-services-miscellaneous").slideToggle( "slow" );	
		});
	});

</script>