<?php 
defined('_JEXEC') or die;
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
	.info-ss-snackbags .push-right div{
		vertical-align: middle;
	}
	.info-ss-snackbags .push-right{
		margin: 0px;
	}
</style>

<div class="info-ss-snackbags info_sub_service" >
	<div class="info-ss-header-maas" style="">	
		<table class="tableInfo" cellpadding="0" cellspacing="0" border="0">
			<tr>			
				<td width="40%" class="ss-service">Snackbags</td>
				<td width="10%">
					<img class="icon_train" src="<?php echo JURI::root().'media/media/images/VoucherNOK.png' ?>">
				</td>	
				<td width="40%" class="ss-title">Snackbags Enabled</td>
				<td  width="10%"><div id="" class="btn-open"><span>Open<span><img src="<?php echo JURI::root().'media/media/images/select-pass-icons/arrow-down.png' ?>"/></div></td>
			</tr>	
			<tr>
				
			</tr>
		</table>	
	</div>
	<div class="sub-services-snackbags" style="position: relative; display: none;">
	<table class="tableInfo" cellpadding="0" cellspacing="0" border="0">
		<tr >
			<td >				
				<div >
					<div class="service-list content-other" >
						<div class="push-left" style="width:250px">
							<img src="<?php echo JURI::base().'media/media/images/Snackbag.png'; ?>" style="width:90px" alt="">
						</div>
						<div class="" >
							<div id="" class="ui toggle checkbox checked" >
								<input name="" type="checkbox" id="snackbags" class="" value="snackbags">   
							</div>
							<p class="color-text sub_service" style="vertical-align: top;">Enable</p>
						</div>
						<div class="clear"></div>
						<div class="push-right" style="padding-right: 125px;">   
							<p class="color-text " style="vertical-align:top">Internal Comment</p>     
							<textarea rows="6" cols="30" class=" internal_comment"></textarea>
						</div>
					</div>
					<div class="clear"></div>
					<div  style="float: right; padding-right: 30px; "><a style="cursor:pointer;" class="button_issue save_ss_snackbags" >Save</a></div><div class="clear"></div>
				</div><div style= "display:none" class="validate"></div>
			</div>
		</div>
	</td>
</tr>
</table>
</div>
</div>
<script type="text/javascript">

var status 		= '<?php echo (int)$contentSubService[0]->status_info_ss_snackbags ; ?>';
var comment 	= '<?php echo $contentSubService[0]->internal_comment ?>';


	jQuery(function($){

		$('.info-ss-snackbags .internal_comment').val(comment);
		if (status == 1) {
			$('.info-ss-snackbags .checkbox').addClass('checked');
			$('.info-ss-snackbags .checkbox input').prop('checked', true);
		}
		$('.info-ss-header-maas .icon_train').attr('src', '<?php echo JURI::root().'media/media/images/VoucherOK.png' ?>');


		$( ".info-ss-snackbags .btn-open" ).on('click',function() { 
			if($( ".info-ss-snackbags .btn-open span" ).text()=="Open"){    			
				$( ".info-ss-snackbags" ).addClass('service-active');
				$( ".sub-services-snackbags" ).addClass('service-active');    		
				$( ".info-ss-snackbags .btn-open" ).html('<span>Close<span><img src="<?php echo JURI::root().'media/media/images/select-pass-icons/arrow-up.png' ?>"/>');


			}else{
				$( ".info-ss-snackbags" ).removeClass('service-active');    			
				$( ".sub-services-snackbags" ).removeClass('service-active');    			
				$( ".info-ss-snackbags .btn-open" ).html('<span>Open<span><img src="<?php echo JURI::root().'media/media/images/select-pass-icons/arrow-down.png' ?>"/>');
			}
			$(".sub-services-snackbags").slideToggle( "slow" );	
		});
	});

</script>