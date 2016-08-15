<?php 
defined('_JEXEC') or die;
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

<div class="info-ss-miscellaneous info_sub_service" style="display: none;">
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
							<p style="margin-top: -100px;" class="color-text">Iternal Comment</p><textarea class="internal_comment " rows="4" cols="39"></textarea>
						</div>
						<div  style="float: right; padding-right: 30px; "><a style="cursor:pointer;" class="button_issue save_ss_miscellaneous" >Save</a></div>
					</div><div style= "display:none" class="validate"></div>
				</div>
			</div>
		</td>
	</tr>
</table>
</div>
<script type="text/javascript">
	jQuery(function($){
		
	});
</script>