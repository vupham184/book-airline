<?php  $passenger = $this->item;?>
<?php 
$curList = SFactory::getCurrency();
$curAirline = SFactory::getAirline();

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
<script type="text/javascript">
	jQuery(document).ready(function($) {
		var pas_id_group=[];		
		pas_id_group.push(<?php echo $passenger->id ?>);
		var delaytime = $('.delayrefesh').data('rate');
		$.ajax({
					url:"<?php echo JURI::base().'index.php?option=com_sfs&task=passengersimport.dataCkeckIssueVoucher'; ?>",
					type:"POST",
					data:{
						pas_id_group:pas_id_group,
					},
					dataType: 'json',
					success:function(response){
						if(response.refreshment_amount){
							if(response.refreshment_amount[0]){								
								//$('.cash_title').find('.img_status_freshment').attr('src','<?php echo JURI::root().'media/media/images/VoucherOK.png' ?>');
								//$('.cash_title').find('.eurocash').text('Euro '+response.refreshment_amount[0]+',-');

							}
						}
					}					
				});	

		$(document).on('click','#openRefresh', function() {
			var name = $("#openRefresh span").text();			
			if(name=="Open"){
				$("#openRefresh").html('<span>Close</span><img src="<?php echo JURI::root().'media/media/images/select-pass-icons/arrow-up.png' ?>"/>');
				$('.header-ref').addClass('service-active');

				$.ajax({
					url:"<?php echo JURI::base().'index.php?option=com_sfs&task=passengersimport.dataCkeckIssueVoucher'; ?>",
					type:"POST",
					data:{
						pas_id_group:pas_id_group,
					},
					dataType: 'json',
					success:function(response){
					if (response.refreshment_amount == 0) {
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
						if(parseInt(response.refreshment_amount)== 5){
							$("#choose_first").prop('checked', true);
						}
						if(parseInt(response.refreshment_amount)== 10){
							$("#choose_second").prop('checked', true);
						}
						if(parseInt(response.refreshment_amount) != 10 && parseInt(response.refreshment_amount) != 5){
							$("#choose_three").prop('checked', true);
							$('.ortherfresh').hide();
							$('.freshment').css('display','block');
							
							$("span.eurocash").html("Euro Cash");				
							$('.img_status_freshment').attr("src", "<?php echo JURI::root().'media/media/images/VoucherNOK.png' ?>");
							$('.freshment input.inputamount').val(response.refreshment_amount);
						}
						
						/*if(parseInt(response.refreshment_amount)<5){
							$(".choose_refreshment_cash").prop('checked', false);
						}*/
						
						if(response.refreshment_amount){
							if(response.refreshment_amount[0]){								
								$('.cash_title').find('.img_status_freshment').attr('src','<?php echo JURI::root().'media/media/images/VoucherOK.png' ?>');
								$('.cash_title').find('.eurocash').text('Euro '+response.refreshment_amount[0]);

							}
						}

					}
					var html = "";
						var Convresponse = $.map(response, function(el) { return el });
						var delay_;
						$.each(response,function(key, value) {
							if(key != "group" && key != "refreshment_amount"){								
								var fltref = value.fltref.split('-')[0];
								var delay = caldatatodate(value.std, value.etd);
								var std = value.std.split('T')[1].slice(0,-3);
								var etd = value.etd.split('T')[1].slice(0,-3);

								delay_ = parseFloat(delay.split(':')[0]);
								
								if(Convresponse.length > 2){
									html += '<div style="float:left; width:100%; margin-bottom: 20px;"><span class="namefresh">'+value.dep+'</span> - <span class="flight_num">'+fltref+'</span><span> - '+value.arr+'</span><br />';
									html += '<div class="timeDate">STD '+std+' <br />ETD '+etd+' <br /></div>';
									html += '<div class="delayrefesh"  data-rate="'+delay_+'" data="'+delay+'">Delay time: '+delay+'</div></div>';

								}else{
									html += '<span class="namefresh">'+value.dep+'</span> - <span class="flight_num">'+fltref+'</span><span> - '+value.arr+'</span><br />';
									html += '<div class="timeDate">STD '+std+' <br />ETD '+etd+' <br /></div>';
									html += '<div class="delayrefesh" data-rate="'+delay_+'" data="'+delay+'">Delay time: '+delay+'</div>';
								}
								
							}
						});

						if(delay_ < 2){										
							html += '<div class="textfresh">As the delay time is less then 2 hours passengers are normally not eligable for an refreshment voucher.</div>';
						}

						if( 2 <= delay_  && delay_ <= 6 ){										
							html += '<div class="textfresh">As the delay time is more then 2 hours the passenegers are eligable for a 5 Euro refreshment voucher.</div>';
						}

						if(delay_ > 6){										
							html += '<div class="textfresh">As the delay time is loner then 6:00 hours passengers are eligable for 10 Euro refreshment voucher.</div>';
						}

						$(".infoPassenger").html(html);
						if(response[0].stationname){
							$('.destination_train').text(response[0].stationname);
							$('.from_transtation').val(response[0].from_trainstation);
							$('.to_transtation').val(response[0].to_trainstation);
							$('.info-service-trainTicket #date_expire').val(response[0].train_travel_date);
						}
					}
				});	

			}else{
				$("#openRefresh").html('<span>Open</span><img src="<?php echo JURI::root().'media/media/images/select-pass-icons/arrow-down.png' ?>"/>');
				$('.header-ref').removeClass('service-active');
			}
			$('#mdivrefresh').slideToggle("slow");//.animate({height:300},700);
			$("#showrefesh").slideToggle("slow");
		});
		

		
		$("#saverefreshment").click(function(){			
			var first = $("#choose_first").is(':checked')? 1 : 0;
			var second = $("#choose_second").is(':checked')? 1 : 0;
			var three = $("#choose_three").is(':checked')? 1 : 0;
			
			if(first == 1 || second == 1 || three == 1){	
				if(first == 1){
					$("span.eurocash").html("Euro 5");
				}

				if(second == 1){
					$("span.eurocash").html("Euro 10");
				}

				if(three == 1){	
					var curr = $(".selectCurrency :selected").text();
					if(curr == "EUR"){
						curr = "Euro"
					}
					$("span.eurocash").html(curr +" "+ $('.inputamount').val());						
				}
				

				$('#mdivrefresh').hide(600);
				$('.img_status_freshment').attr("src", "<?php echo JURI::root().'media/media/images/VoucherOK.png' ?>");
				$("#openRefresh").html('<span>Open</span><img src="<?php echo JURI::root().'media/media/images/select-pass-icons/arrow-down.png' ?>"/>');
			}else{				
				$("span.eurocash").html("Euro Cash");
				$('#mdivrefresh').hide(600);
				$('.img_status_freshment').attr("src", "<?php echo JURI::root().'media/media/images/VoucherNOK.png' ?>");
				$("#openRefresh").html('<span>Open</span><img src="<?php echo JURI::root().'media/media/images/select-pass-icons/arrow-down.png' ?>"/>');
			}

			// if(first == 0 && second == 0 && three == 0){				
			// 	$('#mdivrefresh').hide(600);
				
			// }
			
			if(three == 1){
				var valamount 	= document.getElementsByName("amount")[0].value;
				var currency 	= document.getElementsByName("currencyfresh")[0].value;
			}

			$.ajax({
				url: "<?php echo 'index.php?option=com_sfs&task=passengersimport.saveservicerefreshment'; ?>",
				type: 'POST',
				dataType: 'json',
				data: {first: first, second: second, three: three, amount: valamount, currency: currency },
			})
			.done(function(data) {
				console.log(data);
			});
			

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
			
			var passenger_id = [];
			passenger_id.push(<?php echo $passenger->id ?>);
			
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
        var charCode = (evt.which) ? evt.which : event.keyCode

        if ((charCode != 46 || $(element).val().indexOf('.') != -1) && (charCode < 48 || charCode > 57))
            return false;

        return true;
    }    

    function changeNameAmount(){
    	var curr = jQuery(".selectCurrency :selected").text();

		if(curr == "EUR"){
			curr = "Euro"
		}
		jQuery("span.eurocash").html(curr +" "+ jQuery('.inputamount').val()+",-");	

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
		
	function caldatatodate(first,second){
	var std = new Date(first);
	var etd = new Date(second);
	var stdCon = std.getTime();
	var etdCon = etd.getTime();
	
	var difference_ms = etdCon - stdCon;
	var delay =  new Date(difference_ms).toISOString().slice(11, -8);

	return delay;
}
</script>

<style type="text/css">
	span.namefresh{color: #f93639;}
	div.delayrefesh{float: left;width:70%;color: #f93639; padding-top: 10px; margin-top: 10px;}
	div.timeDate{float: left;width: 30%;clear: right; margin-top: 10px;color: #000;}
	div.textfresh{float: left; width: 90%; margin-top: 10px;color: #000;}
	input.inputamount{width: 100px;}
	select.selectCurrency{width: 100px;}
	div.ortherchoose{float: left; width: 100%; padding: 20px 0;border-left: 2px solid #E3F1F8;}
	div.tenEur{
		float: left; width: 100%; padding: 20px; 
		border-bottom: 2px solid #E3F1F8;border-left: 2px solid #E3F1F8;}
	table.refreshmentTable td{border-bottom: none;}
	span.ortherfresh{padding-top: 27px; color: #000000; padding-left: 15px; margin-left: 120px;}
	span.eurocash{color:#000000;}
	.header-ref.service-active {
	    background: #eeeeee;
	}
	#saverefreshment
	{
		background: #ff8806;
    	padding: 7px 25px;
    	color: #ffffff;
    	float: right;
    	margin-right: 10px;
	}
	.refreshmentTable .fix-left{
		float: left;
		clear: both;
		margin: 0px 25px;
	}
	.refreshmentTable td{
		border: 0px!important;
	}
</style>

<tr class="cash_title  header-ref ">
	<td style="width: 312px;">Refreshment voucher</td>
	<td style="width: 78px;">
		<?php 
			if ($this->item->refreshment_amount > 0) : ?>
		<img class="img_status_freshment" src="<?php echo JURI::root().'media/media/images/VoucherOK.png' ?>">
	<?php else: ?>
		<img class="img_status_freshment" src="<?php echo JURI::root().'media/media/images/VoucherNOK.png' ?>">
	<?php endif; ?>
	</td>
	<td style="width: 312px;"><span class="eurocash">

	<?php echo round($passenger->refreshment_amount,2).' '.$passenger->refreshment_currency ;?>
	 Cash</span></td>
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
										<input <?php echo $checked5 ?> name="choose_refreshment" type="radio" id="choose_first" class="choose_refreshment_cash" value="0" >
										<input name="" type="hidden" id="" value="0">
									</div>
								</div>
								5 EUR
							</div>
							<div class="tenEur">
								<div class="fix-left col-md-4">
									<div id="secondCheck" class="ui toggle checkbox checked ">
										<input <?php echo $checked10 ?> name="choose_refreshment" type="radio" id="choose_second" class="choose_refreshment_cash" value="1">
										<input name="" type="hidden" id="" value="1">
									</div>
								</div>
								10 EUR
							</div>
							<div class="ortherchoose">
								<span style="float:left; width: 32%;">
									<div class="fix-left col-md-4">
										<div  class="ui toggle checkbox " style="margin: 25px 0 0 20px;">
											<input <?php echo $checked ?> name="choose_refreshment" type="radio" id="choose_three" class="choose_refreshment_cash" value="2">
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
