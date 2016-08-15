<?php 
defined('_JEXEC') or die;
$startDateList = SfsHelperDate::getSearchDate('expire', 'class="inputbox fs-13" style="width:155px;"');
$airline = SFactory::getAirline();
$user = JFactory::getUser();
$passenger = $this->item;

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

<div class="info-ss-maas info_sub_service" >
	<div class="info-ss-header-maas" style="">	
		<table class="tableInfo" cellpadding="0" cellspacing="0" border="0">
			<tr>			

				<td width="40%" class="ss-service">MAAS</td>
				<td width="10%">
					<img class="icon_train" src="<?php echo JURI::root().'media/media/images/VoucherNOK.png' ?>">
				</td>	
				<td width="40%" class="ss-title">MAAS Enabled</td>
				<td  width="10%"><div id="" class="btn-open"><span>Open<span><img src="<?php echo JURI::root().'media/media/images/select-pass-icons/arrow-down.png' ?>"/></div></td>
			</tr>	
			<tr>
				
			</tr>
		</table>	
	</div>
	<div class="sub-services-maas" style="position: relative; display: none;">
		<table class="tableInfo " cellpadding="0" cellspacing="0" border="0">
			<tr >
				<td >				
					<div >
						<div class="service-list content-other" >
							<div class="push-left" style="width:250px">
								<img src="<?php echo JURI::base().'media/media/images/maas.png'; ?>" style="width:90px" alt="">
							</div>
							<div class="push-right" style="padding-right: 260px;">
								<div id="" class="ui toggle checkbox checked" >
									<input name="service-maas" type="checkbox" id="service-maas" class="" value="8" data-name='MAAS'>   
								</div>
								<p class="color-text sub_service">MAAS</p>
							</div>
							<div class="push-right" style="padding-right: 260px;">   
								<div id="" class="ui toggle checkbox checked" >
									<input name="service-waas" type="checkbox" id="service-waas" class="" value="9" data-name='WAAS'> 
								</div>
								<p class="color-text sub_service">WAAS</p>     
							</div>
							<div class="push-right" style="padding-right: 120px;">   
								<p class="color-text " style="vertical-align:top">Internal Comment</p>     
								<textarea rows="6" cols="30" class=" internal_comment"></textarea>
							</div>
						</div>
						<div class="clear">
								<?php 
									// echo "<pre>";
									// print_r($passenger);
									// echo "</pre>";
								 ?>

						</div>
						<div  style="float: right; padding-right: 30px; "><a style="cursor:pointer;" class="button_issue save_ss_maas" >Save</a></div><div class="clear"></div>
					</div><div style= "display:none" class="validate"></div>
				</div>
			</div>
		</td>
	</tr>
</table>
</div>
</div>

<script type="text/javascript">
	jQuery.noConflict();
	jQuery(function($){
		
		

		function switchSSMaas(){
			$('.info-ss-maas input').on('change', function() {
				$('.checkbox').removeClass('checked');
				$(this).parent().addClass('checked');
				$('.info-ss-maas input').prop('checked', false);
				$(this).prop('checked', true);
				$('.info-ss-maas .ss-service').text($(this).attr('data-name'));
				$('.info-ss-maas .ss-title').text($(this).attr('data-name')+' Enable');

				saveIrregReason();
			});
		}


		function saveIrregReason(){
			var selected = [];
			var irreg_reason = $('.info-ss-maas .checked input').attr('value');

			selected.push('<?php echo (int)$passenger->id; ?>');
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
				selected.push('<?php echo (int)$passenger->id; ?>');

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

		function loadDataMaas(){

		}

		jQuery(document).ready(function($) {
			saveSSMaas();
			switchSSMaas();
			var status = <?php echo (int)$passenger->irreg_reason; ?>;

			if (status == 8) {
				$('.sub-services-maas #service-maas').prop('checked', true);
				$('.info-ss-maas .ss-service').text('MAAS');
				$('.info-ss-maas .ss-title').text('MAAS');
				$('.sub-services-maas #service-maas').parent().addClass('checked');

			}
			else{
				$('.sub-services-maas #service-waas').prop('checked', true);
				$('.info-ss-maas .ss-service').text('WAAS');
				$('.info-ss-maas .ss-title').text('WAAS Enable');
				$('.sub-services-maas #service-waas').parent().addClass('checked');
			}

			$('.info-ss-maas .icon_train').attr("src","<?php echo JURI::root().'media/media/images/VoucherOK.png' ?>");


			$( ".info-ss-maas .btn-open" ).on('click',function() { 
				if($( ".info-ss-maas .btn-open span" ).text()=="Open"){    			
					$( ".info-ss-maas" ).addClass('service-active');
					$( ".sub-services-maas" ).addClass('service-active');    			
					$( ".info-ss-maas .btn-open" ).html('<span>Close<span><img src="<?php echo JURI::root().'media/media/images/select-pass-icons/arrow-up.png' ?>"/>');


				}else{
					$( ".info-ss-maas" ).removeClass('service-active');    			
					$( ".sub-services-maas" ).removeClass('service-active');    			
					$( ".info-ss-maas .btn-open" ).html('<span>Open<span><img src="<?php echo JURI::root().'media/media/images/select-pass-icons/arrow-down.png' ?>"/>');
				}
				$(".sub-services-maas").slideToggle( "slow" );	
			});
		});
	});
</script>