<?php 
$app = JFactory::getApplication();
$link_Img = JURI::root().'media/media/images/select-pass-icons';
$document = JFactory::getDocument();
$curAirline = SFactory::getAirline();
$currency_code = $curAirline->currency_code;
$arr_sub_service = [];
foreach ($this->services as $key => $value) {
	if ((int)$value->parent_id != 0) {
		$temp["id"] = $value->id;
		$temp["parent_id"] = $value->parent_id;
		$temp["name_service"] = $value->name_service;

		array_push($arr_sub_service, $temp);
	}
}

// echo "<pre>";
// print_r($curAirline);
// echo "</pre>";
?>
<link rel="stylesheet" href="<?php echo JURI::base() ?>/templates/<?php echo $app->getTemplate();?>/css/checkbox.css" type="text/css" />
<script src="<?php echo JURI::base() ?>/templates/<?php echo $app->getTemplate();?>/js/checkbox.js" type="text/javascript"></script>
<script type="text/javascript">
jQuery.noConflict();
	jQuery(function ($) {
		$(document).ready(function() {
			$(".ui.checkbox").checkbox();
			switchSubService();
			addSubService();
			refreshment_Show_Hide();  
			switch_option_refreshment();
			save_Option_Other_Refreshment();
		});



function switchSubService(){

			$('.all-services .checkbox').on('change', function() {
				var checkOther = $('#add-service-7').hasClass('checked'); 
				var val_checkOther = $(this).val(); 
				var ss_id = $('#issue_sub_other .info_other_service_id').val();
				var sub_service_content = $('#issue_sub_other .info_other_service').val();

				
				var t = $('#content-service').offset().top;
				var l = $('#content-service').offset().left;
				var parrentObj = $(this).parents('.sub_service_7').attr('class');
				
				if (parrentObj ) { 	
					$('#add-service-7').addClass('checked');
					$('.'+parrentObj+' input').prop('checked', false);
					$(this).children('input').prop('checked', true);

					var sub_service_id = $(this).children('input').attr('data-service-id');

					$('#issue_sub_other .sub_service_id').attr('value', sub_service_id);
					
					$('.info-ss-header-maas .ss-service').text($(this).attr('name'));
					$('.info-ss-header-maas .ss-title').text($(this).attr('name')+' Enable');

					$('.info_service .info_sub_service').css('display', 'none').removeClass('active');
					
					switch(parseInt(sub_service_id)){ 

						case 8:

						$('.info_service .info-ss-maas').css('display', 'block').addClass('active');
						$('.info_service .info_sub_service #service-maas').prop('checked', true);
						$('.info_service .info_sub_service #service-waas').prop('checked', false);
						break;
						case 9:
						$('.info_service .info-ss-maas').css('display', 'block').addClass('active');
						$('.info_service .info_sub_service #service-waas').prop('checked', true);
						$('.info_service .info_sub_service #service-maas').prop('checked', false);

						break;
						case 10: 
						$('.info_service .info-ss-snackbags').css('display','block').addClass('active');

						if (parseInt(ss_id) == 10) {
							$('.info-ss-snackbags .internal_comment').text(sub_service_content[0].internal_comment);
							if (sub_service_content[0].status_info_ss_snackbags == 1) {
								$('.info_service .info-ss-snackbags #snackbags').prop('checked', true);
								$('.info-ss-header-maas .icon_train').attr('src', '<?php echo JURI::root().'media/media/images/VoucherOK.png' ?>');
							}
							else{
								$('.info_service .info-ss-snackbags #snackbags').prop('checked', false);
								$('.info-ss-header-maas .icon_train').attr('src', '<?php echo JURI::root().'media/media/images/VoucherNOK.png' ?>');
							}
						}
						else{
							$('.info_service .info-ss-snackbags #snackbags').prop('checked', true);
							$('.info-ss-header-maas .icon_train').attr('src', '<?php echo JURI::root().'media/media/images/VoucherNOK.png' ?>');
						}
						break;
						case 11:
						$('.info_service .info-ss-phonecard').css('display', 'block').addClass('active');
						if (parseInt(ss_id) == 11) {
							$('.info-ss-phonecard .description').val(sub_service_content[0].description);
							$('.info-ss-phonecard .internal_comment').val(sub_service_content[0].internal_comment);
							$('.info-ss-phonecard .inputamount ').val(sub_service_content[0].inputamount);
							$('.info-ss-phonecard .currency_code option').text(sub_service_content[0].currency_code);
							$('.info-ss-header-maas .icon_train').attr('src', '<?php echo JURI::root().'media/media/images/VoucherOK.png' ?>');
						}
						else{
							$('.info-ss-header-maas .icon_train').attr('src', '<?php echo JURI::root().'media/media/images/VoucherOK.png' ?>');
						}
						break;
						case 12:
						$('.info_service .info-ss-cash').css('display', 'block').addClass('active');
						if (parseInt(ss_id) == 12) {
							$('.info-ss-cash .internal_comment').val(sub_service_content[0].internal_comment);
							$('.info-ss-cash .inputamount ').val(sub_service_content[0].inputamount);
							$('.info-ss-header-maas .icon_train').attr('src', '<?php echo JURI::root().'media/media/images/VoucherOK.png' ?>');
						}
						else{
							$('.info-ss-header-maas .icon_train').attr('src', '<?php echo JURI::root().'media/media/images/VoucherNOK.png' ?>');
						}
						break;
						case 13:
						break;
						case 14:
						$('.info_service .info-ss-miscellaneous').css('display', 'block').addClass('active');

						if (parseInt(ss_id) == 14 ) {
							$('.info-ss-miscellaneous .title').val(sub_service_content[0].title);
							$('.info-ss-miscellaneous .description').val(sub_service_content[0].description);
							$('.info-ss-miscellaneous .internal_comment').val(sub_service_content[0].internal_comment);
							$('.info-ss-miscellaneous .inputamount ').val(sub_service_content[0].inputamount);
							$('.info-ss-miscellaneous .currency_code option').text(sub_service_content[0].currency_code);
							$('.info-ss-header-maas .icon_train').attr('src', '<?php echo JURI::root().'media/media/images/VoucherOK.png' ?>');
						}
						else{
							$('.info-ss-header-maas .icon_train').attr('src', '<?php echo JURI::root().'media/media/images/VoucherNOK.png' ?>');
						}
						break;
						default: 
						// $('.info_service .info_sub_service').css('display', 'none');
						break;

					}
					$('.add_sub_services').css({
						'display': 'block',
						'z-index': '30',
						"top":(t/2)+"px", 
						"left":(l/5)+"px"
					});
				}

			});
}

//remove service if this service other


function addSubService(){
	$('.all-services .checkbox').on('change', function() {
		var valSubService 		= $(this).children('input').first().attr('value');	
		var testCheckedOther 	= $('#add-service-7').hasClass('checked');

		if (!testCheckedOther) {
			// alert(testCheckedOther);
			var id = $('.sub_service_7 .checkbox.checked').attr('id');
			var service_id = $('#'+id).attr('value');
			$('#'+id).removeClass('checked').children('input').prop('checked', false);
				var pas_id = getPassengerCheck($);
				var status=0;
				var max=0;		            
				if($('.content-passenger-import input[type="checkbox"]').length==pas_ids.length){
					max=1;
				}
				$.ajax({
					url:"<?php echo JURI::base().'index.php?option=com_sfs&task=ajax.addServicePassenger&format=raw'; ?>",
					type:"POST",
					data:{
						pas_ids:pas_id,
						service_id:service_id,
						group_id:jQuery('#'+pas_ids[0]).attr('data-group_id'),
						status:status,
						max:max
					},
					dataType: 'json',
					success:function(response){  			                	        
					}	
			});
		}
		$('.sub_service_'+valSubService).toggle('slow');
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
	var passenger_ids = getPassengerCheck($);
	var flight_num = getFlightNumberCheck($);
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


<?php if(count($this->services)){
	foreach($this->services as $service){
		?>
		$('#add-service-<?php echo $service->id; ?>').on('change',function(e) {			
			pas_ids = getPassengerCheck($);
			if(pas_ids.length>0){	
				var max=0;		            
				if($('.content-passenger-import input[type="checkbox"]').length==pas_ids.length){
					max=1;
				}
				var status=0;
				if($('#add-service-<?php echo $service->id; ?>').hasClass('checked')){
					status=1;
				}	
				var service_id = <?php echo $service->id; ?>;
				
				if (service_id != 7) {
					$.ajax({
						url:"<?php echo JURI::base().'index.php?option=com_sfs&task=ajax.addServicePassenger&format=raw'; ?>",
						type:"POST",
						data:{
							pas_ids:pas_ids,
							service_id:<?php echo $service->id; ?>,
							status:status,
							group_id:jQuery('#'+pas_ids[0]).attr('data-group_id'),
							max:max
						},
						dataType: 'json',
						success:function(response){  			                	        
						}
					});	
					
				}
			}
		});
		<?php
	}
} ?>

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
			<a href="javascript:void(0);" class="sfs-button pull-right close-content" style="font: inherit; font-size: 14px; margin-top: -3px; margin-right: 5px">Close</a>
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
				if(count($this->services)>0):
					?>
				<td style="width:50%; border-left:5px solid #86cfda; text-align:left">

					<!-- colum left-->
					<table>
						<?php foreach($this->services as $service){ if($service->id==7){break;} ?>
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
													<div id="add-service-<?php echo $service->id ?>" class="ui toggle checkbox add-service" value = "<?php echo $service->id ?>">
														<input name="chk-add-service-<?php echo $service->id ?>" type="checkbox" id="chk-add-service-<?php echo $service->id ?>" >      
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
															<input type="checkbox" >      
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
															<input type="checkbox" >      
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
															<input type="checkbox" >      
														</div>
													</div>
												</td>
											</tr>
											<tr style = "display:none;" class = "price_of_refreshment_other">
												<td class="w62"><span class="color-text">Amount</span></td>
												<td class="w150">
													<input class = "amount" style="padding:0px" type="text" style="width:100px">
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
							<?php for($i=$po;$i<count($this->services);$i++){
								?>
								<?php if((int)$this->services[$i]->parent_id == 0):?>
								<tr>
									<td class="borderb">
										<table style="width:100%" >
											<tr>
												<td class="w62">
													<div  class="add-services w62"><?php if($this->services[$i]->logo){echo '<img src="'.JURI::root().$this->services[$i]->logo.'" />';} ?></div>
												</td>
												<td class="w150">
													<span class="color-text"><?php echo $this->services[$i]->name_service; ?></span>
												</td>
												<td>
													<div id="add-service-<?php echo $this->services[$i]->id ?>" class="ui toggle checkbox add-service" value= "<?php echo $this->services[$i]->id ?>">
													<input name="chk-add-service-<?php echo $this->services[$i]->id ?>" type="checkbox" id="chk-add-service-<?php echo $this->services[$i]->id ?>" value="<?php echo $this->services[$i]->id ?>" >              
												</div>
										</td>
									</tr>
									<?php foreach ($arr_sub_service as $key => $value): ?>
									<?php if((int)$value['parent_id'] == (int)$this->services[$i]->id): ?>
									<tr style = "display:none" class = "sub_service_<?php echo (int)$this->services[$i]->id;?>">
										<td class="w62"></td>
										<td class="w150">
											<div class="col-md-4">
													<div name = "<?php echo $value['name_service'] ?>" id="add-service-<?php echo $value['id'] ?>" class="custom ui toggle checkbox add-service" value = "<?php echo $value['id'] ?>" >
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
