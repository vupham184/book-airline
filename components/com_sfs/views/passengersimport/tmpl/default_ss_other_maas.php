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
	.info-ss-maas .push-right div{
		vertical-align: middle;
	}
	.info-ss-maas .push-right{
		margin: 0px;
	}
</style>

<div class="info-ss-maas info_sub_service" style="display:none">
	<table class="tableInfo" cellpadding="0" cellspacing="0" border="0">
		<tr >
			<td >				
				<div >
					<div class="service-list content-other" >
						<div class="push-left" style="width:250px">
							<img src="<?php echo JURI::base().'media/media/images/maas.png'; ?>" style="width:90px" alt="">
						</div>
						<div class="push-right" style="padding-right: 275px;">
							<div id="" class="ui toggle checkbox checked" >
								<input name="service-maas" type="checkbox" id="service-maas" class="" value="MAAS">   
							</div>
							<p class="color-text sub_service">MAAS</p>
						</div>
						<div class="push-right" style="padding-right: 275px;">   
							<div id="" class="ui toggle checkbox checked" >
								<input name="service-waas" type="checkbox" id="service-waas" class="" value="WAAS"> 
							</div>
							<p class="color-text sub_service">WAAS</p>     
						</div>
						<div class="push-right" style="padding-right: 120px;">   
							<p class="color-text " style="vertical-align:top">Internal Comment</p>     
							<textarea rows="6" cols="30" class=" internal_comment"></textarea>
						</div>
					</div>
					<div class="clear"></div>
					<div  style="float: right; padding-right: 30px; "><a style="cursor:pointer;" class="button_issue save_ss_maas" >Save</a></div><div class="clear"></div>
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
	
	

	function switchSSMaas(){
		$('.info-ss-maas input').on('change', function() {
			$('.info-ss-maas input').prop('checked', false);
			$(this).prop('checked', true);
			$('#issue_sub_other .ss-service').text($(this).val());
			$('#issue_sub_other .ss-title').text($(this).val()+' Enable');

			saveIrregReason();
		});
	}


	 function saveIrregReason(){
	 	var selected = [];
		var irreg_reason = $('#issue_sub_other .sub_service_id').attr('value');
		$('.trContent input:checked').each(function() {
				selected.push($(this).attr('id'));			   
			});
			$.ajax({
				url: 'index.php?option=com_sfs&task=passengersimport.saveIrregReason',
				type: 'GET',
				data: {
					irreg_reason:irreg_reason,
					ids:selected.toString()
				}
			})
			.done(function(success) {
				// console.log(success);
				if (success>0) {
					$('.icon_train').attr("src","<?php echo JURI::root().'media/media/images/VoucherOK.png' ?>");
				}
			})
			.fail(function() {
				console.log("error");
			});	
	 }


	function saveSSMaas(){
		$('.save_ss_maas').on('click', function() {
			var internal_comment = $('.info-ss-maas .internal_comment').val();
			var selected = [];
			var user_id = '<?php echo $user->id; ?>';
			var airline_id = '<?php echo $airline->id; ?>';
			$('.trContent input:checked').each(function() {
				selected.push($(this).attr('id'));			   
			});
			if (internal_comment) {
				saveIrregReason();
				$.ajax({
					url: 'index.php?option=com_sfs&task=passengersimport.saveSSMaas',
					type: 'GET',
					data: {
						commnet:internal_comment,
						user_id:user_id,
						airline_id:airline_id,
						ids:selected.toString(),

					}
				})
				.done(function(success) {
					// console.log(success);
					if (success>0) {
					$('.icon_train').attr("src","<?php echo JURI::root().'media/media/images/VoucherOK.png' ?>");
				}
				})
				.fail(function() {
					console.log("error");
				});
			}
			else{
				alert('Please add comment!');
			}
			
		});	
	}
		jQuery(document).ready(function($) {
			saveSSMaas();
			switchSSMaas();
	});
	});
</script>