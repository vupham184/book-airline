<?php 
$app = JFactory::getApplication();
$link_Img = JURI::root().'media/media/images/select-pass-icons';
$document = JFactory::getDocument();
$curAirline = SFactory::getAirline();
$currency_code = $curAirline->currency_code;
$passenger = $this->item;
$services = $this->item->services_passenger;
$services_of_passenger = $this->item->services_of_passenger;
$arr_sub_service = array();
foreach ($services as $key => $value) {
	if ((int)$value->parent_id != 0) {
		$temp["id"] = $value->id;
		$temp["parent_id"] = $value->parent_id;
		$temp["name_service"] = $value->name_service;

		array_push($arr_sub_service, $temp);
	}
}
$checked5 = '';
$checked10 = '';
$checked = '';
if ( round($passenger->refreshment_amount ) == 5 ){
	$checked5 = 'checked';
}
elseif ( round($passenger->refreshment_amount ) == 10 ){
	$checked10 = 'checked';
}
elseif ( round($passenger->refreshment_amount ) != 10  && round($passenger->refreshment_amount ) != 5 ){
	$checked = 'checked';
}
?>
<link rel="stylesheet" href="<?php echo JURI::base() ?>/templates/<?php echo $app->getTemplate();?>/css/checkbox.css" type="text/css" />
<script src="<?php echo JURI::base() ?>/templates/<?php echo $app->getTemplate();?>/js/checkbox.js" type="text/javascript"></script>
<script type="text/javascript">
	jQuery(function ($) {
		$(document).ready(function() {
			$(".ui.checkbox").checkbox();  
			switchSubService();// switch and show sub service details
			addSubService(); //show sub service other
			refreshment_Show_Hide();  
			switch_option_refreshment();
			save_Option_Other_Refreshment();
		});
		
		<?php 
		if(count($services_of_passenger)) :
			foreach($services_of_passenger as $service_of_passenger):
				?>
			var service_pass_id = <?php echo (int)$service_of_passenger->id;?>;
			$('#chk-add-service-'+ service_pass_id).attr('checked', true);
			if (service_pass_id == 2) {
				$('.all-services .option_refreshment').show();
				var checked_other = '<?php echo $checked; ?>';
				if (checked_other ) {
					$('.price_of_refreshment_other ').show();
					$('.price_of_refreshment_other .amount').attr('value', '<?php echo $passenger->refreshment_amount; ?>');
					$('.price_of_refreshment_other .currency').val('<?php echo (string)$passenger->refreshment_currency; ?>')
				}
			}
			if (service_pass_id > 7) {
				$('#chk-add-service-7').attr('checked', true);
				$('.sub_service_7').show();
			}
			<?php  
			endforeach;
			endif; //
			?>	


			<?php if(count($services)){
				foreach($services as $service){
					if((int)$service->id != 7){
					?>
					$('#add-service-<?php echo $service->id; ?>').on('change',function(e) {	
						var pas_id = [];		
						pas_id.push('<?php echo $_GET['passenger_id'];?>');
						var status=0;
						if($('#add-service-<?php echo $service->id; ?>').hasClass('checked')){
							status=1;
						}	
						
						$.ajax({
							url:"<?php echo JURI::base().'index.php?option=com_sfs&task=ajax.addServicePassenger&format=raw'; ?>",
							type:"POST",
							data:{
								pas_ids:pas_id,
								service_id:<?php echo $service->id; ?>,
								status:status
							},
							dataType: 'json',
							success:function(response){  			                	        
							}
						});	
					}); //End click
					<?php
				}//end if
			} //End foreach
		} //End if ?>

		function addSubService(){
			$('.all-services .checkbox').on('change', function() {
				var valSubService = $(this).children('input').first().attr('value');
				var testCheckedOther = $('#add-service-7').hasClass('checked');	
				if (!testCheckedOther) {	
					var id = $('.sub_service_7 .checkbox.checked').attr('id');
					var service_id = $('#'+id).attr('value');
					$('#'+id).removeClass('checked').children('input').prop('checked', false);
						var pas_id = [];		
						pas_id.push('<?php echo $_GET['passenger_id'];?>');
						var status=0;
						$.ajax({
							url:"<?php echo JURI::base().'index.php?option=com_sfs&task=ajax.addServicePassenger&format=raw'; ?>",
							type:"POST",
							data:{
								pas_ids:pas_id,
								service_id:service_id,
								status:status
							},
							dataType: 'json',
							success:function(response){  			                	        
							}	
					});
				}
				$('.sub_service_'+valSubService).toggle('slow');
			});
		}

		function switchSubService(){

			$('.all-services .checkbox').on('change', function() {

				var t = $('.issue-voucher-list ').offset().top;
				var l = $('.issue-voucher-list ').offset().left;
				var id = $(this).attr('value');
				
				var parrentObj = $(this).parents('.sub_service_7').attr('class');
				if (parrentObj) {
					$('.sub_service_id').val(($(this).attr('value')));
					$('.'+parrentObj+' input').prop('checked', false);
					$(this).children('input').prop('checked', true);
					showSubService(id);
					$('.list-service').css({
						'display': 'block',
						'z-index': '30',
						"top":(t+50)+"px", 
						"left":(l+50)+"px"
					});
				}
				
			});
		}
//begin show and hide option service Refreshment
function refreshment_Show_Hide(){
	$('.all-services #add-service-2').on('change', function() {
	
		if ($(this).hasClass('checked')) {
			$('.all-services .option_refreshment').show('slow');

		}
		else{
			$('.all-services .option_refreshment').hide('slow');
			$('.option_refreshment td .checkbox').removeClass('checked');
			$('.option_refreshment td .checkbox').children('input').prop('checked', false);
			$('.all-services .price_of_refreshment_other').hide();
		}	
		
	});
	
}

function switch_option_refreshment(){
	$('.all-services .option_refreshment').on('change', function() {
		
		if ($('.all-services #add-service-2').hasClass('checked')) {
			//disable option refreshment
			$('.option_refreshment td .checkbox').removeClass('checked');
			$('.option_refreshment td .checkbox').children('input').prop('checked', false);
			
			//enable option refreshment
			$(this).find('.checkbox').addClass('checked');
			$(this).find('input').prop('checked', true);

			var val = $(this).find('.checkbox').attr('value'); 
			if(val == -1){
				$('.all-services .price_of_refreshment_other').show();
				
			}else{
				$('.all-services .price_of_refreshment_other').hide();

				var valamount 	= $(this).find('.checkbox').attr('value');
				var currency 	= '<?php echo $currency_code; ?>';
				
				save_Service_Refreshment(valamount,currency);
			}
			

		}
	});
}

function save_Option_Other_Refreshment(){
	$('.price_of_refreshment_other .amount').on('blur', function(event) {
		var valamount 	= $('.price_of_refreshment_other .amount').val();
		var currency 	= $('.price_of_refreshment_other .currency').val();
		if(valamount)
			save_Service_Refreshment(valamount,currency);
		else
			alert('Please enter Amount');
	});

	$('.price_of_refreshment_other .currency').on('change', function(event) {
		var valamount 	= $('.price_of_refreshment_other .amount').val();
		var currency 	= $('.price_of_refreshment_other .currency').val();
		if(valamount)
			save_Service_Refreshment(valamount,currency);
		else
			alert('Please enter Amount');
	});
	
}

function save_Service_Refreshment(valamount,currency){
	var passenger_ids = [];
	passenger_ids.push(<?php echo $passenger->id ?>);
	var flight_num = [];
	$.ajax({
		url: 'index.php?option=com_sfs&task=passengersimport.saveservicerefreshment',
		type: 'POST',
		data: { 
			amount: valamount,
			currency: currency,
			flight_num: flight_num,
			delayrefesh: '',
			passenger_id: passenger_ids,
			textfresh: ''
		},
	})
	.done(function(data) {
		if(data > 0)
			alert("Save Success");
		else
			alert("Save Failed");

	});
}
$('.price_of_refreshment_other .amount').keypress(function (event) {
	return isNumber(event, this)
});
	

function isNumber(evt, element) {
	var charCode = (evt.which) ? evt.which : event.keyCode;

	if ((charCode != 46 || $(element).val().indexOf('.') != -1) && (charCode < 48 || charCode > 57))
		return false;

	return true;
} 
//end show and hide option service Refreshment

		function showSubService(id){
			$('.sub_service_other').hide();
			switch(parseInt(id)){
				case 8: 	
					
					$('.info_service #service8').show(); break;
				case 9: 	
					
					$('.info_service #service8').show(); break;
				case 10: 	
					
					$('.info_service #service'+id).show(); break;
				case 11: 	
					
					$('.info_service #service'+id).show(); break;
				case 12: 	
					
					$('.info_service #service'+id).show(); break;
				case 14: 	
					
					$('.info_service #service'+id).show(); break;
			}
		}

	});
</script>
<style>
	.all-services{
		margin: auto;
		width: 850px;
		padding-top: 40px;
	}
	#sbox-window{
		border: 2px solid #01B2C3 !important;
	}
	.main-services{
		width: 400px;
		height: 340px;
		border-left: 5px solid #86CFDA;
	}
	.fix-right{
		float: right;
	}
	.fix-left{
		float: left;
	}
	.clear{
		clear: both;
	}
	.add-services{
		/*background-image: url('<?php echo $link_Img.'/add-services.png' ?>');
		background-repeat: no-repeat;*/
		height: 60px;
	}
	.services{
		margin: 0px 30px;
		border-bottom: 3px solid #DDF1F8;
		padding: 5px 0px;
	}
	.col-md-4{
		width: 33.33%;
	}
	.col-md-8{
		width: 66.66%;
	}
	.refreshment{
		height: 63px;
		background-position: 0px -62px;
	}
	.bus{
		background-position: 0px -136px;
	}
	.taxi{
		background-position: 0px -200px;
	}
	/*span{
		color: #35B2BC;
	}*/
	.services .toggle{
		background-color:#00B200;
		border-radius: 500rem;
		width: 3.5rem;
		height: 1.5rem;
		position: relative;
	}
	.services .toggle .circle{
		width: 1.3rem;
		border-radius: 50%;
		height: 1.3rem;
		background-color: #fff;
		position: absolute;
		z-index: 2;
		top: 1.5px;
		left: 1px;
	}
	.services .toggle .ui-click{
		border-radius: 500rem;
		width: 3.5rem;
		height: 1.5rem;
		position: absolute;
		z-index: 3;
	}
	.push-right{
		margin-left: 35px;
	}
	.push-left{
		margin-right: 35px;
	}
	.color-text{
		color:#35b2bc;
		font-size:12px;
		font-weight:700;
	}
	.w62{
		width:62px;
	}
	.w200{
		width:200px;
	}
	.w150{
		width:150px;
	}
	.w80P{
		width:80%;
	}
	.borderb{
		border-bottom:1px solid #86cfda;
	}
	.service-right td{
		height: 38px;
	}
	.service-right{
		height: 275px;
	}
	.add-services.train {
		height: 63px;
		background-position: 0px -275px;
	}
	.add-services.train {
		height: 63px;
		background-position: 0px -275px;
	}
	.add-services.rental-car{
		height: 74px;
		background-position: 0px -338px;
	}
	.add-services.other{
		height: 63px;
		background-position: 0px -420px;	
	}
	.content-service{
		z-index: 20;
	}
	.custom.ui.toggle.checkbox label:before{
		width: 3rem;
		height: 1rem;
	}
	.custom.ui.toggle.checkbox label:after{
		width: 1rem;
		height: 1rem;
	}
</style>
<div class="content" style="z-index: 20;">
	<div>
		<h3>
			<strong>Add/Change service</strong>
			<a href="javascript:void(0);" class="sfs-button pull-right" style="font: inherit; font-size: 14px; margin-top: -3px; margin-right: 5px" onClick="closeIssueVoucher();">Close</a>
		</h3>
	</div>
	<div>
		<img src="<?php echo $link_Img.'/add-service.png' ?>" alt="" class="fix-left push-left">
		<div class="fix-right push-left">
			<h3>When guests want to make other arrangements then the default select<br> you can make the selection of eligable services here</h3>
			<i>Please note all selected guests will only be eligable for the services selected<br> here, the previous selected will automatically be deselected</i>
		</div>
	</div>
	<div class="clear"></div>
	<div class="all-services w80P" style="margin:auto; padding-bottom:20px;">
		<table style="width:100%;" cellpadding="0" cellspacing="0">
			<tr class="borderb">
				<?php  $po = 0; 
				if(count($services)>0):
					?>
				<td style="width:50%; border-left:5px solid #86cfda; text-align:left">

					<!-- colum left-->
					<table>
						<?php foreach($services as $service){ ?>
							<tr>
								<td>							
									<table style="width:100%" class="borderb">
										<tr>
											<td class="w62">
												<div class="add-services w62"><?php if($service->logo){echo '<img src="'.JURI::root().$service->logo.'" />';} ?></div>
											</td>
											<td class="w150">
												<span class="color-text"><?php echo $service->name_service; ?></span>
											</td>
											<td>
												<div class="col-md-4">
													<div id="add-service-<?php echo $service->id ?>" class="ui toggle checkbox ">
														<input name="chk-add-service-<?php echo $service->id ?>" type="checkbox" id="chk-add-service-<?php echo $service->id ?>">      
													</div>
												</div>
											</td>
										</tr>
										<?php if($service->id == 2):?>
											<tr style = "display:none" class = "option_refreshment">
												<td class="w62"></td>
												<td class="w150">
													<span class="color-text ">5 <?php echo $currency_code;?></span>
												</td>
												<td>
													<div class="col-md-4">
														<div class="custom ui toggle checkbox " value = "5">
															<input <?php echo $checked5 ;?> type="checkbox" >      
														</div>
													</div>
												</td>
											</tr>
											<tr style = "display:none" class = "option_refreshment">
												<td class="w62"></td>
												<td class="w150">
													<span class="color-text">10 <?php echo $currency_code;?></span>
												</td>
												<td>
													<div class="col-md-4">
														<div class="custom ui toggle checkbox " value = "10">
															<input <?php echo $checked10; ?> type="checkbox" >      
														</div>
													</div>
												</td>
											</tr>
											<tr style = "display:none" class = "option_refreshment" >
												<td class="w62"></td>
												<td class="w150">
													<span class="color-text">Other</span>
												</td>
												<td>
													<div class="col-md-4">
														<div class="custom ui toggle checkbox " value = "-1">
															<input <?php echo $checked ;?> type="checkbox"  >      
														</div>
													</div>
												</td>
											</tr>
											<tr style = "display:none;" class = "price_of_refreshment_other">
												<td class="w62"><span class="color-text">Amount</span></td>
												<td class="w150">
													<input class = "amount" style="padding:0px" type="text" style="width:100px" >
												</td>
												<td>
													<select class = "currency">
														<?php if($currency_code != "EUR"): ?>
															<option value = "Euro">Euro</option>
															<option value = "<?php echo $currency_code;?>"><?php echo $currency_code;?></option>
														<?php else:?>
															<option value = "<?php echo $currency_code;?>"><?php echo $currency_code;?></option>
														<?php endif;?>
													</select>
												</td>
											</tr>
										<?php endif;?>
									</table>
								</td>
							</tr>
							<?php	$po++; if($po==4) break; } ?>

						</table>

						<!-- colum left-->
					</td>
					<td style="width:50%; border-left:5px solid #86cfda; text-align:left">
						<!-- colum right-->
						<table class="service-right">						
							<?php //print_r($this->services);die(); ?>
							<?php for($i=$po; $i<count($services); $i++){
								?>
								<?php if((int)$services[$i]->parent_id == 0):?>
								<tr>
									<td class="borderb">
										<table style="width:100%" >
											<tr>
												<td class="w62">
													<div  class="add-services w62"><?php if($services[$i]->logo){echo '<img src="'.JURI::root().$services[$i]->logo.'" />';} ?></div>
												</td>
												<td class="w150">
													<span class="color-text"><?php echo $services[$i]->name_service; ?></span>
												</td>
												<td>
													<div class="col-md-4">
														<div id="add-service-<?php echo $services[$i]->id ?>" class="ui toggle checkbox  add-service" value= "<?php echo $services[$i]->id ?>">
															<input name="chk-add-service-<?php echo $services[$i]->id ?>" type="checkbox" id="chk-add-service-<?php echo $services[$i]->id ?>" value="<?php echo $services[$i]->id ?>" >              
														</div>
													</div>
												</td>
											</tr>

										</td>
									</tr>
									<?php foreach ($arr_sub_service as $key => $value): ?>
									<?php if((int)$value['parent_id'] == (int)$services[$i]->id): ?>
									<tr style = "display:none" class = "sub_service_<?php echo (int)$services[$i]->id;?>">
										<td class="w62"></td>
										<td class="w150">
											<div class="col-md-4">
												<div name = "<?php echo $value['name_service'] ?>" id="add-service-<?php echo $value['id'] ?>" class="custom ui toggle checkbox  add-service" value = "<?php echo $value['id'] ?>" >
													<input name="chk-add-service-<?php echo $value['id'] ?>" type="checkbox" id="chk-add-service-<?php echo $value['id'] ?>" data-service-id ="<?php echo $value['id'] ?>">      
												</div>
											</div>
										</td>
										<td >
											<span class="color-text sub_service_name"><?php echo $value['name_service']; ?></span>
										</td>
									</tr>
								<?php endif; ?>
							<?php endforeach ?>
						</table>
					</td>
				</tr>
			<?php endif;?>
			<?php
		} ?>

	</table>
	<!-- colum right-->
</td>
<?php endif; ?>
</tr>	
</table>


<div class="main-services fix-left" style="display:none;">
	<div class="services" style="display:none;">
		<div class="add-services fix-left col-md-4"></div>
		<div class="fix-left col-md-4"><h3><span>Hotel</span></h3></div>
		<div class="fix-left col-md-4">
			<div class="ui toggle checkbox checked">
				<input name="" type="checkbox" id="" >
				<input name="" type="hidden" id="" value="">
			</div>
		</div>
		<div class="clear"></div>
	</div>
	<div class="services" style="display:none;">
		<div class="add-services fix-left col-md-4 refreshment"></div>
		<div class="fix-left col-md-4">
			<h3><span>Refreshment</span></h3>
			<p><span>5 Euro</span></p>
			<p><span>10 Euro</span></p>
		</div>
		<div class="fix-left col-md-4">
			<div class="ui toggle checkbox checked">
				<input name="" type="checkbox" id="" >
				<input name="" type="hidden" id="" value="">
			</div>
		</div>
		<div class="clear"></div>
	</div>
	<div class="services" style="display:none;">
		<div class="add-services fix-left col-md-4 bus"></div>
		<div class="fix-left col-md-4"><h3><span>Bus Transfer</span></h3></div>
		<div class="fix-left col-md-4">
			<div class="ui toggle checkbox checked">
				<input name="" type="checkbox" id="" >
				<input name="" type="hidden" id="" value="">
			</div>
		</div>
		<div class="clear"></div>
	</div>
	<div class="services" style="display:none;">
		<div class="add-services fix-left col-md-4 taxi"></div>
		<div class="fix-left col-md-4"><h3><span>Taxi Transfer</span></h3></div>
		<div class="fix-left col-md-4">
			<div class="ui toggle checkbox checked">
				<input name="" type="checkbox" id="" >
				<input name="" type="hidden" id="" value="">
			</div>
		</div>
		<div class="clear"></div>
	</div>
	<div class="clear"></div>
</div><!-- end main-services fix-left-->



<div class="main-services fix-left" style="display:none;">
	<div class="services" style="display:none;">
		<div class="col-md-4 fix-left">
			<div class="ui toggle checkbox checked">
				<input name="" type="checkbox" id="" >
				<input name="" type="hidden" id="" value="">
			</div>
		</div>
		<div class="col-md-8 fix-left">
			<h3><span>Telephone card Euro 10</span></h3>
		</div>
		<div class="clear"></div>
	</div>
	<div class="services" style="display:none;">
		<div class="col-md-4 fix-left">
			<div class="ui toggle checkbox checked">
				<input name="" type="checkbox" id="" >
				<input name="" type="hidden" id="" value="">
			</div>
		</div>
		<div class="col-md-8 fix-left">
			<h3><span>Train</span></h3>
		</div>
		<div class="clear"></div>
	</div>
	<div class="services" style="display:none;">
		<div class="col-md-4 fix-left">
			<div class="ui toggle checkbox checked">
				<input name="" type="checkbox" id="" >
				<input name="" type="hidden" id="" value="">
			</div>
		</div>
		<div class="col-md-8 fix-left">
			<h3><span>Rental card</span></h3>
		</div>
		<div class="clear"></div>
	</div>
	<div class="services" style="display:none;">
		<div class="col-md-4 fix-left">
			<div class="ui toggle checkbox checked">
				<input name="" type="checkbox" id="" >
				<input name="" type="hidden" id="" value="">
			</div>
		</div>
		<div class="col-md-8 fix-left">
			<h3><span>Fernbus</span></h3>
		</div>
		<div class="clear"></div>
	</div>
	<div class="services" style="display:none;">
		<div class="col-md-4 fix-left">
			<div class="ui toggle checkbox checked">
				<input name="" type="checkbox" id="" >
				<input name="" type="hidden" id="" value="">
			</div>
		</div>
		<div class="col-md-8 fix-left">
			<h3><span>Cash reimbursement</span></h3>
		</div>
		<div class="clear"></div>
	</div>
	<div class="services" style="display:none;">
		<div class="col-md-4 fix-left">
			<div class="ui toggle checkbox checked">
				<input name="" type="checkbox" id="" >
				<input name="" type="hidden" id="" value="">
			</div>
		</div>
		<div class="col-md-8 fix-left">
			<h3><span>Other</span></h3>
		</div>
		<div class="clear"></div>
	</div>

</div><!-- end main-services fix-left-->

</div><!-- end all-services-->

</div><!-- end content-->