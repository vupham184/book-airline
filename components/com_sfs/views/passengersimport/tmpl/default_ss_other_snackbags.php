<?php 
defined('_JEXEC') or die;

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

<div class="info-ss-snackbags info_sub_service" style="display:none">

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
