<?php
defined('_JEXEC') or die;
$airline = SFactory::getAirline();
$airplusparams= $airline->airplusparams;
$airports = $airline->getAirlineAirportData();

?>
<style type="text/css">
	.info-service-header-taxi.service-active{
		background: #eeeeee;	
	}
	.info-service-header-taxi.service-active .tableInfo td{
		border-bottom: 2px solid #eeeeee!important;
	}
	.push-left{
		float: left;
		clear: both;
		margin: 5px 10px;
	}
	.fix-left{
		float: left;
		clear: both;
		margin: 0px 40px;
	}
	#taxi-content td{
		border-right: 2px solid #83D0CA;
	}
	#taxi-content .taxi_cpn:last-child{
		border-bottom:0px; 
	}
	.taxi_cpn{
		float: left;
		clear: both;
		padding: 20px 0px;
		border-bottom: 2px solid #83D0CA;
		margin: 0px;
		width: 100%;
	}
	.taxi_cpn_content{
		height: 194px;
		overflow-y: scroll;
	}
	.taxi_cpn .taxi_check{
		float: left;
		margin-left: 40px; 
	}
	.taxi_cpn .taxi_details{
		float: left;
	}
	.taxi_cpn .taxi_details span small{
		max-width: 100px;
		overflow: hidden;
	}
	.other-taxi-choice{
		display: none;
		border-bottom: 0px !important;
	}
	.taxi_services_details span{
		color: #000000;
	}
</style>

<div class="info-service-header-taxi" style="display:none;">
	<table class="tableInfo" cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td width="40%">Taxi transfer</td>
			<td width="10%">
				<img src="<?php echo JURI::root().'media/media/images/VoucherOK.png' ?>" class='taxi_status'>
			</td>	
			<td width="40%" class="taxi_name">Please select taxi</td>
			<td  width="10%"><div id="btn-taxi" class="btn-open"><span>Open<span><img src="<?php echo JURI::root().'media/media/images/select-pass-icons/arrow-down.png' ?>"/></div></td>
		</tr>				
	</table>
</div>
<div class="info-service-taxi" style="display: none;">
	<table class="tableInfo" cellpadding="0" cellspacing="0" border="0">
		<tr id="taxi-content">
			<td width="60%">
				<div class="taxi_services">
					<p class="push-left" >
						<input type="radio" name='taxi_option' class="taxi_option" data-option="option1" checked>From Airport To Hotel <!-- (<span class="location_name">default</span>) -->
					</p>
					<p class="push-left" >
						<input type="radio" name='taxi_option' class="taxi_option" data-option="option2">Return to airport as well
					</p>
					<p class="push-left" >
						<input type="radio" name='taxi_option' class="taxi_option" data-option="option3">Only from Hotel to airport
					</p>
					<div class="taxi_services_details">
						<p class="fix-left location_name"></p>
						<p class="fix-left" >Distance to airport <span class="total_distance"></span></p>
						<p class="fix-left">Estimated taxi costs one way trip <span class="total_price"></span> Euro</p>
					</div>
					<p class="push-left">
						<input type="radio" name='taxi_option' class="taxi_option" data-option="option4">Other Location
					</p>
				</div>
			</td>	
			<td width="40%" style="padding: 0px; border-right: none;">
				<div class="taxi_cpn_content">
					<?php foreach ($this->taxiCompany as $key => $value): ?>
						<div class="taxi_cpn" >
							<div class="taxi_check col-md-4">
								<div  class="ui toggle checkbox checked ">
									<input name="choose_taxi_name" type="radio" class="taxi_choice taxi_choice_defaul" value="<?php echo $value->id; ?>" data-taxi="<?php echo $value->name; ?>">
									<input name="" type="hidden" id="" value="">
								</div>
							</div>
							<div class="taxi_details">
								<span class="text-taxi"><?php echo $value->name; ?></span><br>
								<span><small>Phone: <?php echo $value->telephone; ?></small></span><br>
								<?php if (!empty($value->mobile_phone)): ?>
									<span><small>Mobile Phone: <?php echo $value->mobile_phone; ?></small></span>
								<?php endif ?>
							</div>
						</div>
					<?php endforeach ?>

				</div>
			</td>
		</tr>
		<tr>
			<td colspan="2" class="other-taxi-choice">
				<div class="form-group">
					<label>
						<?php echo "From Address"; ?>:
					</label>
					<select class="required smaller-size" name="from-address" id="from-address" style="width: 200px">
						<?php foreach($airports as $airport):?>
							<?php if($airline->airport_code == $airport->code):?>
								<option value="<?php echo $airport->geo_lat." ,".$airport->geo_lon;?>" selected="selected" data-id="<?php echo $airport->id;?>"><?php echo $airport->name;?></option>
							<?php else:?>
								<option value="<?php echo $airport->geo_lat." ,".$airport->geo_lon;?>" data-id="<?php echo $airport->id;?>"><?php echo $airport->name;?></option>
							<?php endif;?>
						<?php endforeach;?>
					</select>
				</div>

				<div class="form-group ">
					<label>
						<?php echo "To Address"; ?>:
					</label>
					<input type="text"  size="1" name="to-address" id="to-address" class="required" required="true"
					style="width:400px;margin-left: 15px"/>
					<span id="distance_label" style="margin-left: 15px"></span>
					
				</div>
				<div class="form-group" id="googleMap" style="width:759px;height:380px;"></div>
				<div id="directionsPanel" style="float:right;width:30%;height: 100%;"></div>
				<div><p id="esitmate_total"  style="font-weight: bold; margin-left: 5px"></p></div>
				<div>
					<div style="cursor:pointer;float: right;margin: 10px 30px 0px 0px;" class="btnTaxiWay" value ="2"><a  class="button_issue">Issue Two Way Taxi Voucher</a></div>
					<div style="cursor:pointer;float: right;margin: 10px 30px 0px 0px;" class="btnTaxiWay" value ="1"><a  class="button_issue" >Issue One Way Taxi Voucher</a></div>
					
				</div>
			</td>
		</tr>
		<tr>
			<td colspan="2"><div style="cursor:pointer;float: right;margin: 10px 30px 0px 0px;"><a  class="button_issue" id="saveTaxi">Save</a></div></td>
		</tr>
	</td>
</tr>
<input type="hidden" name="distance" value="" id="distance"/>
<input type="hidden" name="objdistance" value="" id="objdistance"/>
<input type="hidden" name="total_price_other" value="" id="total_price_other"/>
</table>
</div>
<script type="text/javascript">
	var not_click = true;
	var way =0;
	var load = 1;

	jQuery.noConflict();
	jQuery(function($){

		<?php echo $script_check; ?> 
		var hotel_id; 
		$('body').on('hotelsaved.taxi', function(e, id){
			hotel_id = id;
			checkLoadData();
		})
		var checkLoadData = function() {
			if($( "#btn-taxi span" ).text()=="Open"){
				if (load==1) {
					way =  v_taxWayOption;
				}

				hotel_id = hotel_id || $('.trContent input:checked').attr('data-hotel');
				
				if( hotel_id > 0 ) {
					loadDistanceTaxi(hotel_id,load);
				 	
				 }
				 else{
				 	$('.taxi_services').children().css({
				 		display: 'none'
				 	});

				 	$('.taxi_option').attr('checked', false);
				 	$('.taxi_option[data-option="option4"]').prop('checked', true).parent("p").css({
				 		display: 'block'
				 	});

				 	$('.other-taxi-choice').show();
				 	$('.taxi_services_details').hide();

				 	initMap(way);
				 }


				 $( ".info-service-header-taxi" ).addClass('service-active');
				 $( ".info-service-taxi" ).addClass('service-active');    			
				 $( "#btn-taxi" ).html('<span>Close<span><img src="<?php echo JURI::root().'media/media/images/select-pass-icons/arrow-up.png' ?>"/>');
				 switchTaxiCheck();

				 displayPriceOptionOther();
				 if($('#to-address').val() && load == 1){
				 	$('.btnTaxiWay[value = "'+v_taxWayOption+'"]').trigger('click');
				 }
				 load++;
				 
				 $('.taxi_choice').on("change",function() {
				 	data_option_taxi = $('.taxi_option:checked').attr('data-option');
				 	$('.taxi_name').text(($(this).attr('data-taxi')));

				 	if (data_option_taxi == 'option4') {
				 		var distance = $('#distance_label').attr('data-distance');
				 		var total_price = $('#esitmate_total').attr('data-totalPrice');
				 		saveTaxiInfo(distance,total_price,way);
				 	}
				 	else{
				 		var distance= $('.total_distance').attr('data-distance');
				 		var total_price = $('.total_price').text();
						saveTaxiInfo(distance,total_price,way);//lchung
					}
				});

				}else{
					v_taxCpnName = $('.taxi_name').text();
					$( ".info-service-header-taxi" ).removeClass('service-active');    			
					$( ".info-service-taxi" ).removeClass('service-active');    			
					$( "#btn-taxi" ).html('<span>Open<span><img src="<?php echo JURI::root().'media/media/images/select-pass-icons/arrow-down.png' ?>"/>');
					// $(".info-service-taxi").slideToggle( "slow" );
				}

			}

			$("#btn-taxi").off().on('click', function() {
				checkLoadData();
				$(".info-service-taxi").slideToggle( "slow" );
			});
			// $( "#btn-taxi" ).click(checkLoadData);	
			function initMap(way) {
				var directionsService = new google.maps.DirectionsService;
				var directionsDisplay = new google.maps.DirectionsRenderer;
				var map = new google.maps.Map(document.getElementById('googleMap'), {
					zoom: 11,
					center: {lat: <?php echo $airline->geo_lat?>, lng: <?php echo $airline->geo_lon?>}
				});
				directionsDisplay.setMap(map);

				var onChangeHandler = function() {
					calculateAndDisplayRoute(directionsService, directionsDisplay,way);
				};

				var to_address = $('#to-address').val();
				if(to_address != ''){
					var airport_latlon = $('#from-address').val().split(',');
					map.setCenter(new google.maps.LatLng(parseFloat(airport_latlon[0]), parseFloat(airport_latlon[1])));
					onChangeHandler()
				}
				$('#from-address').on('change', function(){
					airport_latlon = $(this).val().split(',');
					map.setCenter(new google.maps.LatLng(parseFloat(airport_latlon[0]), parseFloat(airport_latlon[1])));
					$("#to-address").val("");
					$("#distance_label").html("");
					$("#distance").val("");
					$("#esitmate_total").html("");
					if(directionsDisplay != null) {
						directionsDisplay.setMap(null);
					}
				});
				$('#to-address').off('blur').on('blur', function(){
					to_address = $(this).val();
					if(to_address != ''){
						calculateAndDisplayRoute(directionsService, directionsDisplay,way);
					}else{
						$("#distance_label").html("");
						$("#distance").val("");
						$("#esitmate_total").html("");
						alert("Please input address!");
						if(directionsDisplay != null) {
							directionsDisplay.setMap(null);
						}
						directionsDisplay = new google.maps.DirectionsRenderer;
						directionsDisplay.setMap(map);

					}
				});
			}
			function calculateAndDisplayRoute(directionsService, directionsDisplay,way) {
				directionsService.route({
					origin:  document.getElementById('from-address').value,
					destination: document.getElementById('to-address').value + ", " + "<?php echo $airline->country_n?>",
					travelMode: google.maps.TravelMode.DRIVING
				}, function(response, status) {
					if (status === google.maps.DirectionsStatus.OK) {
                    // Display the distance:
                    var airport_id = $("#from-address").find('option:selected').attr("data-id");
                    var distance = response.routes[0].legs[0].distance.value/1000;
                    document.getElementById('distance_label').innerHTML = distance + " Km";
                    document.getElementById('distance').value = distance;
                    directionsDisplay.setDirections(response);
                    $.ajax({
                    	url: "index.php?option=com_sfs&task=ajax.estimateTotalOneWayTaxiFee&format=raw",
                    	type: "POST",
                    	data: {
                    		airport_id:airport_id,
                    		distance: distance,
                    		way:way
                    	},
                    	success: function(response){ 
                    		if (way== 2) {
                    			$("#esitmate_total").html("Estimated total two way taxi fee will be "+response+" EUR - Two way");
                    		}
                    		else{
                    			$("#esitmate_total").html("Estimated total one way taxi fee will be "+response+" EUR - One way");
                    		}

                    		$("#esitmate_total").attr('data-totalPrice',response);
                    		$("#distance_label").attr('data-distance',distance);
                    		$('#total_price_other').val(response);

                    		saveTaxiInfo(distance,response,way);
                    	}
                    })
                } else {
                	window.alert('Directions request failed due to ' + status);
                }
            });
			}
			function loadDistanceTaxi(dt,load){
				var data = dt;
				$.ajax({
					url: 'index.php?option=com_sfs&task=passengersimport.loadDistanceTaxi',
					type: 'post',
					dataType: 'json',
					data: {
						hotelId: data
					},
					success:function(data){
					var option = $('.taxi_option:checked').attr('data-option')
					autoLoad(data,option);
					

				//Case click services		
				$('.taxi_option').off('click').on('click', function() {
					// alert(distance_later);
					var data_option_taxi = $(this).attr('data-option');
					
					autoLoad(data,data_option_taxi);
					
				});

			}
		});
			}

			function autoLoad(data,data_option_taxi){
				var distance_unit = data.distance_unit;
				var hotel_name = data.name;
				var airport_name = '<?php echo $airline->airport_name; ?>';
				var distance_tx = parseFloat(data.distance);
				var distance_later =distance_tx;
				var km_rate = parseFloat(data.km_rate);
				var starting_tariff = parseFloat(data.starting_tariff);
				var taxi_fee = parseFloat(<?php echo $airplusparams['taxi_fee'] ;?>);
				var total_price = (km_rate * distance_tx + starting_tariff) * 1.1 + taxi_fee;
				$('#objdistance').attr({
					km_rate:km_rate,
					distance_tx:distance_tx,
					starting_tariff:starting_tariff,
					taxi_fee:taxi_fee

				});

				if (data_option_taxi == 'option1') {
					$('.other-taxi-choice').hide();
					$('.taxi_services_details').show();
					distance_later =distance_tx;
					$('.location_name').text('To Hotel ' + hotel_name);
					total_price = (km_rate * distance_later + starting_tariff) * 1.1 + taxi_fee;
					$('.total_distance').text(distance_later + ' ' + distance_unit);
					$('.total_distance').attr('data-distance', distance_later);
					$('.total_price').text(total_price.toFixed(2)); 
					saveTaxiInfo(distance_later,total_price.toFixed(2));
				}
				if (data_option_taxi == 'option2') {
					$('.other-taxi-choice').hide();
					$('.taxi_services_details').show();
					distance_later =distance_tx *2;
					$('.location_name').text('Return to Airport' + airport_name);
					total_price = (km_rate * distance_later + starting_tariff) * 1.1 + taxi_fee;
					$('.total_distance').text(distance_later + ' ' + distance_unit);
					$('.total_distance').attr('data-distance', distance_later);
					$('.total_price').text(total_price.toFixed(2)); 
					saveTaxiInfo(distance_later,total_price.toFixed(2));
				}
				if (data_option_taxi == 'option3') {
					$('.other-taxi-choice').hide(); 
					$('.taxi_services_details').show();
					distance_later =distance_tx;
					$('.location_name').text('To Airport ' + airport_name);
					total_price = (km_rate * distance_later + starting_tariff) * 1.1 + taxi_fee;
					$('.total_distance').text(distance_later + ' ' + distance_unit);
					$('.total_distance').attr('data-distance', distance_later);
					$('.total_price').text(total_price.toFixed(2)); 
					saveTaxiInfo(distance_later,total_price.toFixed(2));
				}
				if (data_option_taxi == 'option4') { 
					$('.other-taxi-choice').show();
					$('.taxi_services_details').hide();
					initMap(way);
				}
			}
			function switchTaxiCheck(){

				if( not_click == true ) {
					$('input[name="choose_taxi_name"]').attr('checked', false);
					var obj = $('input[value="' + v_taxi_id + '"]');
					obj.prop('checked', true);
					var objs = $('input[data-option="option' + v_option_taxi + '"]');
					objs.prop('checked', true);
					not_click = false;
				}
				
				if($('.taxi_option:checked').attr('data-option') == 'option4'){
					$('.other-taxi-choice').show();
					$('.taxi_services_details').hide();
				}
				
			}
			function displayPriceOptionOther(){
				$('.btnTaxiWay').on('click', function() {
					way = $(this).attr('value');
					initMap(way);
				});
			}
			function saveTaxiInfo(distance,total_price,way){
				var from_andress= '';
				var to_address = '';
				var airport_id = <?php echo $airline->airport_id; ?>;
				var id_passengers = [];
				$('.trContent input:checked').each(function() {
					id_passengers.push($(this).attr('id'));			   
				});
				var data_option_taxi = $('.taxi_services input:checked').attr('data-option');
				var hotel_id = $('#hotel_book').val();
				if (data_option_taxi == 'option1') {
					var from_andress = "<?php echo $airline->airport_name; ?>";
					var to_address = $('.title-hotel:first-child').text();
				}
				if (data_option_taxi =='option2') {
					var from_andress = "<?php echo $airline->airport_name; ?>";
					var to_address =  "<?php echo $airline->airport_name; ?>";
				}
				if (data_option_taxi == 'option3') {
					var from_andress = $('.title-hotel:first-child').text();
					var to_address =  "<?php echo $airline->airport_name; ?>";
				}
				if (data_option_taxi == 'option4') {
					total_price = total_price;
					hotel_id = '';
					from_andress = $('#from-address option:selected').text();
					to_address = $('#to-address').val();
					distance = distance;
				}
				var taxi_selected = $('.taxi_cpn input:checked').attr('value');

				$.ajax({
					url: 'index.php?option=com_sfs&task=passengersimport.saveIssueTaxi',
					type: 'post',
					data: {
						airport_id: airport_id,
						id_passengers: id_passengers.toString(),
						data_option_taxi:data_option_taxi,
						total_price:total_price,
						hotel_id:hotel_id,
						taxi_selected:taxi_selected,
						distance: distance,
						from_andress:from_andress,
						to_address:to_address,
						way:way
					},
				})
				.done(function(re_data) {
					if (re_data>0) {
						$('.info-service-header-taxi .taxi_status').attr('src', '<?php echo JURI::root().'media/media/images/VoucherOK.png' ?>');
					}
					else{
						$('.info-service-header-taxi .taxi_status').attr('src', '<?php echo JURI::root().'media/media/images/VoucherNOK.png' ?>');

					}
				})
				.fail(function() {
					console.log("error");
				});
			}

		});


	</script>