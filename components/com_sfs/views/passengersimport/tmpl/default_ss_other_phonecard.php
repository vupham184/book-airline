<?php 
defined('_JEXEC') or die;
$startDateList = SfsHelperDate::getSearchDate('expire', 'class="inputbox fs-13" style="width:155px;"');
$airline = SFactory::getAirline();
$user = JFactory::getUser();
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

<div class="info-ss-phonecard info_sub_service" style="display:none">

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
						<div class="push-right" style="padding-right: 151px;">   
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

<script type="text/javascript">
jQuery.noConflict();
	jQuery(function($){
			
	});
</script>