<?php 
$startDateList = SfsHelperDate::getSearchDate('expire', 'class="inputbox fs-13" style="width:120px;"');
$startime = SfsHelperDate::getSelectTimeField();
$nameAirport = $this->airport_of_airline;//modSfsChangeAirportHelper::getAirlineAirportData();
$airline = SFactory::getAirline();
$arrBus = $this->airportBus;

?>
<script src="http://maps.googleapis.com/maps/api/js">/*map*/</script>
<script type="text/javascript">
	var directionsService = new google.maps.DirectionsService;
	var directionsDisplay = new google.maps.DirectionsRenderer;
	var onChangeHandler_com = function(lat_lon_from,lat_lon_to) {		
		calculateAndDisplayRoute_com(directionsService, directionsDisplay,lat_lon_from,lat_lon_to);
	};
	var lat_lon_from = <?php echo $nameAirport[0]->geo_lat; ?> + "," + <?php echo $nameAirport[0]->geo_lon; ?>;		

	jQuery(document).ready(function($) {

		$(document).on('click','#openAddGroup', function() {	
			var seateramount = $('#grouptransport_airportseater').val();
			var name = $("#openAddGroup span").text();	
			var datalon = $("#grouptransport_hotel_address").val();
			var dataOther = $('#group_amount_address').val();

			if(name=="Open"){
				$("#openAddGroup").html('<span>Close</span><img src="<?php echo JURI::root().'media/media/images/select-pass-icons/arrow-up.png' ?>"/>');
			}else{
				$("#openAddGroup").html('<span>Open</span><img src="<?php echo JURI::root().'media/media/images/select-pass-icons/arrow-down.png' ?>"/>');
			}
			$('#mdivaddgroup').slideToggle("slow");//.animate({height:300},700);
			$("#showaddgroup").slideToggle("slow");			
			
			$('#mdiv-add-group').css('display', 'none');//.animate({height:300},700);
			$('#show-add-group').css('display', 'none');			

			if(parseInt(seateramount) == -1){
				$('.infoRow.grouptransport-amount').css('display', 'block');
				initMap_com(dataOther);
			}else{
				
				initMap_com(datalon);
			}

		});
		
		$(document).on('click','#open-add-group', function() {
			$('#mdiv-add-group').slideToggle("slow");//.animate({height:300},700);
			$('#show-add-group').slideToggle("slow");
			
			$('#mdivaddgroup').css('display', 'none');
			$("#showaddgroup").css('display', 'none');
			
		});
		

		$("#seaterAirport").on('change', function(event) {
			var id = $("#seaterAirport option:selected").val();
			getDefaultAirportGroup( id );
		});
		
		$('#add-group-transport-company').click(function(e) {
			var passenger_idArr = [];
			$('.trContent').each(function(index, element) {
                var id = $(this).children().find('input[type="checkbox"]:checked').attr('id');
				if ( id != undefined ) {
					passenger_idArr.push( id );
				}
            });
			var booked_by_phone = $("#booked-by-phone:checked").val();
            var name_company = $("#name-company").val();
			var phone = $("#phone-company").val();
			var numseater = $("#numseater").val();
			var priceseater = $("#priceseater").val();
			var currency_id = $("#currency_id option:selected").val();
			var date_expire_time = $("#date_expire option:selected").val() + " " + $("#hourseater option:selected").val() + ':' + $("#minutseater option:selected").val() + ":00";
			var airline_airport_id = $("#seaterAirport option:selected").val();
			var airport_id = $("#airportseater option:selected").val();
			var comment = $("#comment-company").val();
			
			if( airport_id == '0' ) {
				alert('Please choose a To Location Airport Hotel');
				return false;
			}
			var dataArr = {
				'airline_id': '<?php echo $airline->id;?>',
				'booked_by_phone': booked_by_phone,
				'name_company': name_company,
				'phone': phone,
				'numseater': numseater,
				'priceseater': priceseater,
				'currency_id': currency_id,
				'date_expire_time': date_expire_time,
				'airline_airport_id': airline_airport_id,				
				'airport_id': airport_id,
				'comment': comment,
				'passenger_idArr': passenger_idArr.toString()				
			};
			jQuery.ajax({
                url:"<?php echo JURI::base().'index.php?option=com_sfs&task=passengersimport.insertGroupTransportCompany'; ?>",
                type:"POST",
                data:{data:dataArr},
                dataType: 'json',
                success:function(data){
					// document.location.reload( true );
                   console.log( data);
                }
            });
        });
		//jos_sfs_group_transport_company
		
		
		$(".group-transport").on('change',function() {
			var id = $(this).attr('data-id');
			$('.infoRow.grouptransport-amount').css('display', 'none');
			$('#grouptransport_airportseater option').attr('selected', false);
			$('#grouptransport_seaterAirport option').attr('selected', false);

			if($('#radio'+id).is(":checked")){
				var d = $('#radio'+id).attr('data-infotr');
				eval( "var dArr=" + d + ";");
				$('#grouptransport_airportseater option[value="' + dArr.airport_id + '"]').prop('selected', true);
				$('#grouptransport_seaterAirport option[value="' + dArr.airline_airport_id + '"]').prop('selected', true);
				$('#group_transportation_types_id').val( $('#radio'+id).val() );
			}
			
		});
		
		//lay default
		var id = $("#seaterAirport option:selected").val();
		getDefaultAirportGroup( id );
		
		$('#grouptransport_airportseater').change(function(e) {
            var v = $('#grouptransport_airportseater option:selected').val();
			if( v == -1 ) {
				$('.infoRow.grouptransport-amount').css('display', 'block');
			}
			else {
				$('.infoRow.grouptransport-amount').css('display', 'none');
			}
        });
		
		$('#group_transport').click(function(e) {
            var v = $('#grouptransport_airportseater option:selected').val();
			var grouptransport_priceseater = $('#grouptransport_priceseater').val();
			var group_transportation_types_id = $('#group_transportation_types_id').val();
			var status = 0;
			if( v == -1 && grouptransport_priceseater == '') {
				alert( 'Please enter Amount' );
			}
			else {
				
				var passenger_idArr = [];
				$('.trContent').each(function(index, element) {
					var id = $(this).children().find('input[type="checkbox"]:checked').attr('id');
					if ( id != undefined ) {
						passenger_idArr.push( id );
					}
				});
				var grouptransport_seaterAirport = $('#grouptransport_seaterAirport').val();
				var group_transport_company_id = $('#group_transport_company_id').val();
				var grouptransport_priceseater = $('#grouptransport_priceseater').val();
				var grouptransport_currency_id = $('#grouptransport_currency_id').val();
				var passenger_group_transport_company_id = $('#passenger_group_transport_company_id').val();
				
				
				var comment = $('#grouptransport_comment_company').val();
				var date_expire_time = $("#mdivaddgroup #date_expire option:selected").val() + " " + $("#grouptransport_hourseater option:selected").val() + ':' + $("#grouptransport_minutseater option:selected").val() + ":00";
				var hotel_address = $("#grouptransport_hotel_address").val();
				var distance_comp = $("#distance_comp").val();
				var amount_address = $("#group_amount_address").val();
				
			///if($('#radio'+id).is(":checked")){
				if(v != -1){
					grouptransport_priceseater = 0;
				}
				var dataArr = {
				'airline_id': '<?php echo $airline->id;?>',
				'group_transportation_types_id': group_transportation_types_id,
				'passenger_group_transport_company_id': passenger_group_transport_company_id,
				'currency_id': grouptransport_currency_id,
				'airline_airport_id': grouptransport_seaterAirport,				
				'airport_id': v,
				'comment': comment,
				'price': grouptransport_priceseater,
				'date_expire_time': date_expire_time,
				'passenger_idArr': passenger_idArr.toString(),
				'hotel_address' : hotel_address,
				'distance_comp' : distance_comp,
				'amount_address' : amount_address
			};
			jQuery.ajax({
                url:"<?php echo JURI::base().'index.php?option=com_sfs&task=passengersimport.insertGroupTransportCompanyOtherPrice'; ?>",
                type:"POST",
                data:{data:dataArr},
                dataType: 'json',
                success:function(data){
					document.location.reload( true );
					//console.log(data);
                }
            });
				
			}
        });				

		

		$('#grouptransport_airportseater').on('change', function(){			
			var O_location = $('#grouptransport_airportseater option:selected').val();
			var to_address = $(this).val();

			if(parseInt(O_location) == -1){
				return false;
			}else{
				jQuery.ajax({

                url:"<?php echo JURI::base().'index.php?option=com_sfs&task=passengersimport.getLocationHotel'; ?>",
                type:"POST",
                data:{id:document.getElementById('grouptransport_airportseater').value},
                dataType: 'text',
                success:function(data){                	

					if(to_address != ''){						
						var namehotel = data.split("'").toString();						
						
						var lat_lon_to 	= data;
						var html = "<input type='hidden' id='grouptransport_hotel_address' name='name_hotel' value='"+namehotel+"' >";
						$(".showDataHotel").html(html);
						onChangeHandler_com(lat_lon_from,lat_lon_to);
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
		            }

	            });		
			}

					
		});


		$('.grouptransport-amount .search').on('click', function(event) {
			var locationAmount = $("#group_amount_address").val();
			onChangeHandler_com(lat_lon_from,locationAmount);
		});

	});	
		
	
	function getDefaultAirportGroup( id ){		
		jQuery.ajax({
			url:"<?php echo JURI::base().'index.php?option=com_sfs&task=passengersimport.changeAirportGroup'; ?>",
			type:"POST",
			data:{id: id},
			dataType: 'json',
			success:function(data){
			   var html = "";
			 //   html += "<option value=\"0\">Location Airport Hotel</option>";
			 //   jQuery.each(data, function( index, value ) {
				//   html += "<option value=" + value.id + ">" +value.name+ "</option>";
				// });
				html += '<option>Select</option>';
				html += '<optgroup label="Other Location">';
				html += '<option value="-1">Specific address</option>';
				html += '</optgroup>';

				html += '<optgroup label="Other Airport">';

				<?php foreach ($arrBus as $key => $value) :?>
					html += '<option value="<?php echo $value->airport; ?>"><?php echo $value->airport; ?></option>';
				<?php endforeach; ?>							
				html += '</optgroup>';

				html += '<optgroup label="Location Airport Hotel">';
					jQuery.each(data, function( index, value ) {
					  html += "<option value=" + value.id + ">" +value.name+ "</option>";
					});
				html += '</optgroup>';

			   jQuery("#airportseater").html(html);
			   jQuery("#grouptransport_airportseater").html(html);

			   // jQuery("#airportseater").html(html);
			   // jQuery("#grouptransport_airportseater").html(html + '<option value="-1" >Other Location</option>');
			   
			}
		});
	}
	
	function initMap_com(datalon) {		
		// var directionsService = new google.maps.DirectionsService;
		// var directionsDisplay = new google.maps.DirectionsRenderer;
		var map = new google.maps.Map(document.getElementById('googleMap_test'), {
			zoom: 11,
			center: {lat: 52.5588327, lng: 13.2884374}
		});
		directionsDisplay.setMap(map);
		if(datalon != ""){
			onChangeHandler_com(lat_lon_from,datalon);
		}	

	}	
	

	function calculateAndDisplayRoute_com(directionsService, directionsDisplay,lat_lon_from,lat_lon_to) {
			var airport_id	= jQuery('#grouptransport_airportseater option:selected').val();

			directionsService.route({
				origin:  lat_lon_from,
				destination: lat_lon_to + ", " + "<?php echo $airline->country_n?>",
				travelMode: google.maps.TravelMode.DRIVING
			}, function(response, status) {
				if (status === google.maps.DirectionsStatus.OK) {
                    var distance = response.routes[0].legs[0].distance.value/1000;
                    var html = "<b>" + parseInt(distance) + " Km</b>";
                    html += "<input type='hidden' id='distance_comp' name='distance_' value='"+distance+"' >";
                    
                    if(parseInt(airport_id) != -1){
                    	jQuery(".refreshmentTable .distance").children().remove();
                    	jQuery("#showkm").show().html(html);
                    }
                    else{
                    	jQuery("#showkm").children().remove();
                    	jQuery(".refreshmentTable .distance").show().html(html);
                    	
                    }
                    
                    
                   	directionsDisplay.setDirections(response);
                   	jQuery.ajax({
                   		url: "index.php?option=com_sfs&task=ajax.estimateTotalOneWayTaxiFee&format=raw",
                   		type: "POST",
                   		data: {
                   			//airport_id:airport_id,
                   			distance: distance
                   		},
                   		success: function(response){ 
                   			console.log(response);
                   			// alert(response);
                   		}
                   	})
                   } else {
                   	window.alert('Directions request failed due to ' + status);
                   }
               });
		}
</script>


<style type="text/css">
	.titleGroup{color:#1da9b8;}
	input.inputInfo{margin-top:  30px;}
	div.group-company{margin-top: 20px;}
	div.infoRow{float: left; width: 100%; margin-top: 10px;}
	table.refreshmentTable td.border-right{
		border-right:5px solid #eeeeee;
	}
	#mdivaddgroup label, #mdiv-add-group label{
		width:70px;
		float:left;
	}
	.img-status1{
		display:none;
	}
	.viewDetail_active{display:none;}
	#showkm {float:right; width: 150px; padding-top: 10px; color:#1da9b8;}
	.price_amount{float: left; width: 100%; margin-top: 20px; margin-left: 70px;}
	.refreshmentTable .distance {
		float: left;
	    max-width: 54px;
	    width: 54px;
	}
</style>

<tr class="tr-color service-bus-transfer" style="display: none;">
	<td style="width: 312px; border-bottom:0px;">Group transport</td>
	<td style="width: 78px; border-bottom:0px;">
    <img src="<?php echo JURI::root().'media/media/images/VoucherNOK.png';?>" class="img-status0">
    <img src="<?php echo JURI::root().'media/media/images/VoucherOK.png' ?>" class="img-status1">
    </td>
	<td class="change-text-the-selected" style="width: 312px; border-bottom:0px;">Please book a group transport</td>
	<td id="openAddGroup" value="" style="cursor: pointer;width: 78px; border-bottom:0px;">
		<span>Open</span>
		<img src="<?php echo JURI::root().'media/media/images/select-pass-icons/arrow-down.png' ?>"/>
	</td>
</tr>

<tr id="mdivaddgroup" style="display: none;" class="cash_service" height="100px">	
	<td colspan="4" style="border-bottom:0px; border-top:0px; padding:0px;">
		<div class="viewDetail_active">
			<!-- <div id="showaddgroup" style="display: none;"> -->
				<table cellspacing="0" cellpadding="0" border="0" width="100%">
					<tr>
						<td style="vertical-align:top;">
		                	<?php $name_bus = ''; $seats = 0; $seats_used = 0;
		                		foreach ( $this->group_transport_company->types as $vArrSub) :?>
							
								<?php if( (int)$vArrSub->airport_id > 0  && 
										  (int)$vArrSub->airline_airport_id > 0) :?>
		                	
		                    	<div style="float:left;">
		                            <div data-id="<?php echo $vArrSub->id;?>" class="group-transport ui toggle checkbox checked ">
		                                <input id="radio<?php echo $vArrSub->id;?>" class="select-tran" data-infotr="<?php echo str_replace('"', "'", json_encode( $vArrSub ) );?>" type="radio" name="group_transport" value="<?php echo $vArrSub->id;?>" />
		                            </div>
		                        </div>
		                        <?php $name_bus = $vArrSub->name; $seats=$vArrSub->seats; $seats_used = $vArrSub->seat_used; ?>
		                        <br style="clear:both;" />
		                   
		                    <?php endif; endforeach;?>
						</td>
						<td style="vertical-align:top;">
							<?php if( !empty( $this->group_transport_company ) ) : ?>						
		                    	<p style="color:#000; font-size:14px;"><strong> 
								<?php echo $this->group_transport_company->group_transportations[0]->name;?></strong></p>
								<br />
		                        <p><span>Phone: 
		                        	<?php echo $this->group_transport_company->group_transportations[0]->telephone; ?>
								</span></p>
		                    <?php endif;?>
						</td>
						<td>					
							<?php echo $name_bus;?> bus, <?php echo $seats-$seats_used; ?> left <br />
							Commment: <span class="grouptransport_comment_company_active"></span> <br />
							Pick up time <span class="change-text-the-selected_active"></span> <br />
							Pick up at: <?php echo $airline->airport_code; ?><br />
							To: <span class="grouptransport_hotel_address_active"></span>
						</td>
					</tr>
				</table>
			<!-- </div> -->
		</div>
		<div class="viewDetail">	
		    
		    	<table class="refreshmentTable" cellspacing="0" cellpadding="0" border="0" width="100%">
					<tr>
						
						<td width="30%" class="border-right" style="vertical-align:top">
							<?php if( !empty( $this->group_transport_company ) ) : ?>						
		                    	<p style="color:#000; font-size:14px;"><strong>Connection:  
								<?php echo $this->group_transport_company->group_transportations[0]->name;?></strong></p>
								
		                        <br /><br />
		                        <p><span>Phone: 
		                        	<?php echo $this->group_transport_company->group_transportations[0]->telephone; ?>
								</span></p>
		                    <?php endif;?>
						</td>
						<td width="50%" style="vertical-align: text-top;">
		                	<input type="hidden" id="group_transportation_types_id" value="" />
		                    <input type="hidden" id="passenger_group_transport_company_id" value="" />
		                    
							<div class="infoRow">
		                    	<ul>
		                        	<?php foreach ( $this->group_transport_company->types as $vArrSub) :?>
		                        	<li style="list-style:none; margin-bottom:10px;">
		                            	<div style="float:left;">
		                                    <div data-id="<?php echo $vArrSub->id;?>" class="group-transport ui toggle checkbox checked ">
		                                        <input id="radio<?php echo $vArrSub->id;?>" class="select-tran" data-infotr="<?php echo str_replace('"', "'", json_encode( $vArrSub ) );?>" type="radio" name="group_transport" value="<?php echo $vArrSub->id;?>" />
		                                    </div>
		                                </div>
		                                <div style="float:left;">
		                                <?php echo $vArrSub->name;?> bus
		                                </div>
		                                <br style="clear:both;" />
		                            </li>
		                            	<?php endforeach;?>
		                        </ul>
		                    </div>
							<div class="infoRow">
								<label>Pick up time</label>
								<?php echo $startDateList; ?>							
								<select name="grouptransport_hourseater" id="grouptransport_hourseater" style="width:60px; margin-left: 20px;">
		                        	 <option value="">hh</option>
									<?php echo $startime[0]->html; ?>
								</select>	
								<select name="grouptransport_minutseater" id="grouptransport_minutseater" style="width:60px;">
		                        	 <option value="">mm</option>
									<?php echo $startime[1]->html; ?>
								</select>				
							</div>
							<div class="infoRow">
								<label>Pick up at</label>
								<select id="grouptransport_seaterAirport" name="grouptransport_seaterAirport" style="width: 200px;">
		                        <!-- <option value="">--</option> -->
									<?php foreach ($nameAirport as $key => $value) : ?>
										<option value="<?php echo $value->id;?>" ><?php echo $value->name; ?></option>
									<?php endforeach; ?>
								</select>	
							</div>
							<div class="showDataHotel">
								<input type="hidden" id="grouptransport_hotel_address" value="">
							</div>
							<div class="infoRow showhotelairport">
								<label>To</label>
								<select name="grouptransport_airportseater_" id="grouptransport_airportseater" style="width: 200px;"></select>	

								<div id="showkm"></div>
							</div>
		                    
		                    <div class="infoRow grouptransport-amount" style="display:none;">
		                    	<label>Address</label>
		                    	<input type="text"  value="" name="amount_address" id="group_amount_address" style="width: 250px;">
								<div style="cursor:pointer;float: right;margin: 10px 85px 0px 0px;"><a  class="button_issue search" >Search</a></div>
		                    	<br />
		                    	<div class="price_amount">
		                    		<span class="distance" style="margin-top: 10px;"></span>
		                    		<input type="text" value="" name="grouptransport_priceseater" id="grouptransport_priceseater" style="width: 120px; float: left;"> 
									<select name="grouptransport_currency_id" id="grouptransport_currency_id" style="width: 65px; float:left; margin-left: 10px">
										<option value="2">EUR</option>
									</select>
									<label for="" style="margin-left: 15px; margin-top:10px;">Total Price</label>	 
		                    	</div>
								
							</div>
		                    
		                    <div class="infoRow showhotelairport">
		                    	<label>&#160;</label>
		                        <textarea name="grouptransport_comment" style="width: 250px;font-size:12px;" placeholder="Comment" id="grouptransport_comment_company"></textarea>
							</div>
							<div style="cursor:pointer;float: right;margin: 10px 30px 0px 0px;"><a  class="button_issue" id="group_transport">Save</a></div>
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<div class="form-group" id="googleMap_test" style="width:759px;height:380px;"></div>
							<div id="directionsPanel" style="float:right;width:30%;height: 100%;"></div>
						</td>
					</tr>
				</table>
		        
	    </div>
	</td>
</tr>



<tr class="tr-color service-bus-transfer" style="display: none;">
	<td style="width: 312px; cursor: pointer; border:0px; text-decoration:underline" id="open-add-group" >
    Add Group transport company
    </td>
	<td style="width: 78px; border:0px;"></td>
	<td style="width: 312px; border:0px;"></td>
	<td value="" style="width: 78px; border:0px;">
	</td>
</tr>

<tr id="mdiv-add-group" class="cash_service" height="100px" style="display: none;">
	<td colspan="4" style="border:0px; padding:0px;">
	<div id="show-add-group" style="display: none;">	
		<table class="refreshmentTable" cellspacing="0" cellpadding="0" border="0" width="100%">
			<tr>
				<td width="20%"  class="border-right" style="vertical-align: top;">
					<div class="titleGroup">Booked by phone</div>
					<div class="fix-left col-md-4 group-company">
						<div id="firstCheck" class="ui toggle checkbox checked ">
							<input name="booked-by-phone" id="booked-by-phone" checked="checked" type="checkbox" value="1" >
						</div>
					</div>
				</td>
				<td width="30%" style="vertical-align: text-top;">
					<div class="titleGroup">Bus company contact info </div>
					<input type="text" name="name-company" id="name-company" class="inputInfo" >
					<input type="text" name="phone-company" id="phone-company" class="inputInfo" >
				</td>
				<td width="50%" style="vertical-align: text-top;">
					<div>For a special price / quotation</div>
					<div class="infoRow">
						<input type="number" maxlength="2" value="" name="numseater" id="numseater" style="width: 120px;"> Seater Bus	
					</div>
					<div class="infoRow">
						<input type="text" value="" name="priceseater" id="priceseater" style="width: 120px; float: left;"> 
						<select name="currency_id" id="currency_id" style="width: 120px; float:left; margin-left: 10px">
							<option value="2">EUR</option>
						</select>
						Total Price
					</div>
					<div class="infoRow">
						<label>Pick up time </label>
						<?php echo $startDateList; ?>	
						<select name="hourseater" id="hourseater" style="width:60px; margin-left: 20px;">
							<?php echo $startime[0]->html; ?>
						</select>	
						<select name="minutseater" id="minutseater" style="width:60px;">
							<?php echo $startime[1]->html; ?>
						</select>				
					</div>
					<div class="infoRow">
						<label>Pick up at </label>
						<select id="seaterAirport" name="airportseater" style="width: 200px;">
							<?php foreach ($nameAirport as $key => $value) : ?>
								<option value="<?php echo $value->id;?>" ><?php echo $value->name; ?></option>
							<?php endforeach; ?>							
						</select>	
					</div>

					<div class="infoRow showhotelairport">
						<label>To</label>
						<select name="airportseater" id="airportseater" style="width: 200px;">
							<option value="0">Location Airport Hotel</option>							
						</select>	
					</div>
                    <div class="infoRow showhotelairport">
                        <textarea name="comment" id="comment-company" placeholder="Comment"></textarea>
					</div>
					<div style="cursor:pointer;float: right;margin: 10px 30px 0px 0px;"><a  class="button_issue" id="add-group-transport-company">Save</a></div>
				</td>
			</tr>
		</table>
	</div>	
	</td>	
</tr>
