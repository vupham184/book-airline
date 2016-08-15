<?php 
$curList = SFactory::getCurrency();
$curAirline = SFactory::getAirline();
?>

<script type="text/javascript">
	jQuery(document).ready(function($) {
		$(document).on('click','#openRefresh', function() {
			var name = $("#openRefresh span").text();
			var delaytime = $('.delayrefesh').data('rate');
			var flat = 0;
			if(name=="Open"){
				$("#openRefresh").html('<span>Close</span><img src="<?php echo JURI::root().'media/media/images/select-pass-icons/arrow-up.png' ?>"/>');
				var passenger_id = [];
				$('.trContent input:checked').each(function() {
					passenger_id.push($(this).attr('id'));			   
				});	
				$.ajax({
					url: 'index.php?option=com_sfs&task=passengersimport.loadservicerefreshment',
					type: 'POST',
					data: { 
						passenger_id: passenger_id,
					},
				})
				.done(function(data) {
					if (data == '') {
						if (delaytime >=2 && delaytime < 6) {
							$("#choose_first").prop('checked', true);
						}
						else if(delaytime >6){
							$("#choose_second").prop('checked', true);
						}
						else{
							$("#choose_first").prop('checked', false);
							$("#choose_second").prop('checked', false);
						}
						var valamount = 0;
						var currency =0;
						
						if (valamount=='') {
							if( $('input[name="choose_refreshment"]').is(':checked') ) {
								valamount = 5;
								currency = '<?php echo $curAirline->currency_code; ?>';

							}
							if( $("#choose_second").is(':checked') ) {
								valamount = 10;
								currency = '<?php echo $curAirline->currency_code; ?>';
							}
						}
						currency 	= $('.selectCurrency').val();
						saveRefreshment(valamount,currency);
					}else{ 
						if(parseInt(data)== 5){
							$("#choose_first").prop('checked', true);
						}
						if(parseInt(data)== 10){
							$("#choose_second").prop('checked', true);
						}
						if(parseInt(data)>10){
							$("#choose_three").prop('checked', true);
							$('.ortherfresh').hide();
							$('.freshment').css('display','block');
							
							$("span.eurocash").html('Euro ' + data);				
							$('.img_status_freshment').attr("src", "<?php echo JURI::root().'media/media/images/VoucherOK.png' ?>");
							$('.freshment input.inputamount').val(data);
						}
						if(parseInt(data)<5){
							$(".choose_refreshment_cash").prop('checked', false);
						}
					}
					
				});
				$('.header-ref').addClass('service-active');

			}
			else{
					$("#openRefresh").html('<span>Open</span><img src="<?php echo JURI::root().'media/media/images/select-pass-icons/arrow-down.png' ?>"/>');
					$('.header-ref').removeClass('service-active');
			}
			$('#mdivrefresh').slideToggle("slow");
			$("#showrefesh").slideToggle("slow");
		});
		
		$(".inputamount").on('blur', function(event) {
			var three = $("#choose_three").is(':checked')? 1 : 0;
			var valamount = 0;
			var currency =0;
			valamount 	= $('.inputamount').val();
			currency 	= $('.selectCurrency').val();	
			saveRefreshment(valamount,currency);
		});
		
		$(".choose_refreshment_cash").on('change', function(event) {
			var valamount = 0;
			var currency =0;
			if( $('input[name="choose_refreshment"]').is(':checked') ) {
				valamount = 5;
				currency = '<?php echo $curAirline->currency_code; ?>';

			}
			if( $("#choose_second").is(':checked') ) {
				valamount = 10;
				currency = '<?php echo $curAirline->currency_code; ?>';
			}
			saveRefreshment(valamount,currency);
			
		});
		function saveRefreshment(valamount,currency){
			var flight_num = [];
			$('.flight_num').each(function() {
				flight_num.push($(this).text());			   
			});
			var passenger_id = [];
			$('.trContent input:checked').each(function() {
				passenger_id.push($(this).attr('id'));			   
			});
			// if(name == 'Open')
			// {
				
				$.ajax({
					url: 'index.php?option=com_sfs&task=passengersimport.saveservicerefreshment',
					type: 'POST',
					data: { 
						amount: valamount,
						currency: currency,
						flight_num: flight_num,
						delayrefesh: $('.delayrefesh').attr('data'),
						passenger_id: passenger_id,
						textfresh: $('.textfresh').text()
					},
				})
				.done(function(data) {
					if (data > 0) {
						$('.img_status_freshment').attr("src", "<?php echo JURI::root().'media/media/images/VoucherOK.png' ?>");
						$("#openRefresh").html('<span>Close</span><img src="<?php echo JURI::root().'media/media/images/select-pass-icons/arrow-down.png' ?>"/>');
					}else{ 
						$('.img_status_freshment').attr("src", "<?php echo JURI::root().'media/media/images/VoucherNOK.png' ?>");
					}
				});
			// }
		}
		$("#choose_first").on('change',function() {
			if($(this).is(":checked")){
				$("#choose_second").prop('checked', false);
				$("#choose_three").prop('checked', false);
				$('.ortherfresh').css('display','block');
				$('.freshment').hide();
				document.getElementsByName("amount")[0].value = "";

				$('.img_status_freshment').attr("src", "<?php echo JURI::root().'media/media/images/VoucherOK.png' ?>");
				$("span.eurocash").html("Euro 5");
			}
			
		});

		$("#choose_second").on('change',function() {
			if($(this).is(":checked")){
				$("#choose_first").prop('checked', false);
				$("#choose_three").prop('checked', false);
				$('.ortherfresh').css('display','block');
				$('.freshment').hide();
				document.getElementsByName("amount")[0].value = "";

				$('.img_status_freshment').attr("src", "<?php echo JURI::root().'media/media/images/VoucherOK.png' ?>");
				$("span.eurocash").html("Euro 10");
			}
			
		});

		$("#choose_three").on('change',function() {
			if($(this).is(":checked")){
				$("#choose_first").prop('checked', false);
				$("#choose_second").prop('checked', false);
				$('.ortherfresh').hide();
				$('.freshment').css('display','block');
				
				$("span.eurocash").html("Euro Cash");				
				$('.img_status_freshment').attr("src", "<?php echo JURI::root().'media/media/images/VoucherNOK.png' ?>");
			}else{
				document.getElementsByName("amount")[0].value = "";
				$('.ortherfresh').css('display','block');
				$('.freshment').hide();
				$("span.eurocash").html("Euro Cash");				
				$('.img_status_freshment').attr("src", "<?php echo JURI::root().'media/media/images/VoucherNOK.png' ?>");
			}		
		});

		$('.inputamount').keypress(function (event) {
			return isNumber(event, this)
		});
		
	});

function isNumber(evt, element) {
	var charCode = (evt.which) ? evt.which : event.keyCode;

	if ((charCode != 46 || $(element).val().indexOf('.') != -1) && (charCode < 48 || charCode > 57))
		return false;

	return true;
}    

function changeNameAmount(){
	var curr = jQuery(".selectCurrency :selected").text();

	if(curr == "EUR"){
		curr = "Euro"
	}
	jQuery("span.eurocash").html(curr +" "+ jQuery('.inputamount').val());	

	jQuery('.img_status_freshment').attr("src", "<?php echo JURI::root().'media/media/images/VoucherOK.png' ?>");		
}

function changeNameCurr(){    	
	var curr = jQuery(".selectCurrency :selected").text();
	var amount = jQuery('.inputamount').val();

	if(amount != ""){
		if(curr == "EUR"){
			curr = "Euro"
		}
		jQuery("span.eurocash").html(curr +" "+amount);	

		jQuery('.img_status_freshment').attr("src", "<?php echo JURI::root().'media/media/images/VoucherOK.png' ?>");
	}
}


</script>

<style type="text/css">
	span.namefresh{color: #f93639;}
	div.delayrefesh{float: left;width:70%;color: #f93639; padding-top: 10px; margin-top: 10px;}
	div.timeDate{float: left;width: 30%;clear: right; margin-top: 10px;}
	div.textfresh{float: left; width: 90%; margin-top: 10px;}
	input.inputamount{width: 100px;}
	select.selectCurrency{width: 100px;}
	div.ortherchoose{float: left; width: 100%; padding: 20px 0;border-left: 2px solid #E3F1F8;}
	div.tenEur{
		float: left; width: 100%; padding: 20px; 
		border-bottom: 2px solid #E3F1F8;border-left: 2px solid #E3F1F8;}
		table.refreshmentTable td{border-bottom: none;}
		span.ortherfresh{padding-top: 27px; color: #000000; padding-left: 15px; margin-left: 120px;}
		span.eurocash{color:#000000;}
		.header-ref.service-active{
			background: #eeeeee;
		}
	</style>

	<tr class="cash_title header-ref"  style="display: none;">
		<td style="width: 312px;">Refreshment voucher</td>
		<td style="width: 78px;">
			<img class="img_status_freshment" src="<?php echo JURI::root().'media/media/images/VoucherNOK.png' ?>">
		</td>
		<td style="width: 312px;"><span class="eurocash">Euro Cash</span></td>
		<td id="openRefresh" value="" style="cursor: pointer;width: 78px;">
			<span>Open</span>
			<img src="<?php echo JURI::root().'media/media/images/select-pass-icons/arrow-down.png' ?>"/>
		</td>
	</tr>

	<tr id="mdivrefresh" style="display: none;" class="cash_service" height="100px">
		<!-- <td colspan="1">Cash Reimbursement</td> -->
		<td colspan="4">
			<div id="showrefesh" style="display: none;">	
				<table class="refreshmentTable" cellspacing="0" cellpadding="0" border="0" width="100%">
					<tr>
						<td class="infoPassenger" width="50%" style="vertical-align: top;">

						</td>
						<td width="50%">
							<div class="tenEur">
								<div class="fix-left col-md-4">
									<div id="firstCheck" class="ui toggle checkbox checked ">
										<input name="choose_refreshment" type="radio" id="choose_first" class="choose_refreshment_cash" value="0">
										<input name="" type="hidden" id="" value="0">
									</div>
								</div>
								5 EUR
							</div>
							<div class="tenEur">
								<div class="fix-left col-md-4">
									<div id="secondCheck" class="ui toggle checkbox checked ">
										<input name="choose_refreshment" type="radio" id="choose_second" class="choose_refreshment_cash" value="1">
										<input name="" type="hidden" id="" value="1">
									</div>
								</div>
								10 EUR
							</div>
							<div class="ortherchoose">
								<span style="float:left; width: 32%;">
									<div class="fix-left col-md-4">
										<div  class="ui toggle checkbox " style="margin: 25px 0 0 20px;">
											<input name="choose_refreshment" type="radio" id="choose_three" class="choose_refreshment_cash" value="2">
											<input name="" type="hidden" id="" value="2">
										</div>
									</div>
								</span>
								<span class="ortherfresh" style="display:block;">Other</span>
								<span class="freshment" style="display:none;float:left; width: 32%;">
									Amount<br />
									<input type="text" class="inputamount" onchange="changeNameAmount()" value="" name="amount"><br />
								</span>

								<span class="freshment" style="display:none;float:left; width: 30%;">
									Currency<br />
									<select class="selectCurrency" name="currencyfresh" onchange="changeNameCurr()">
										<?php if (!empty($curAirline->currency_code) && isset($curAirline->currency_code)): ?>
											<?php if ($curAirline->currency_code == 'EUR'): ?>
												<option value="<?php echo $curAirline->currency_code; ?>">
													<?php echo $curAirline->currency_code; ?>
												</option>
											<?php else: ?>
												<option value="EUR">
													EUR
												</option>
												<option value="<?php echo $curAirline->currency_code; ?>">
													<?php echo $curAirline->currency_code; ?>
												</option>
											<?php endif ?>
										<?php else: ?>
											<?php foreach ($curList as $key => $value): ?>
												<?php if($value->numeric_code == $curAirline->currency_numeric_code) : ?>
													<option value="<?php echo $value->code; ?>" selected>
														<?php echo $value->code; ?>
													</option>
												<?php else: ?>
													<option value="<?php echo $value->code; ?>">
														<?php echo $value->code; ?>
													</option>
												<?php endif; ?>
											<?php endforeach ?>
										<?php endif ?>									
									</select>
								</span>						
							</div>
							<div style="cursor:pointer;float: right;margin: 10px 30px 0px 0px;"><a  class="button_issue" id="saverefreshment">Save</a>
								<?php 
						// echo "<pre>";
						// print_r($curList);
						// echo "</pre>";
								?></div>
							</td>
						</tr>
					</table>
				</div>	
			</td>	
		</tr>
