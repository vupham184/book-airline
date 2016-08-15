<?php
defined('_JEXEC') or die;
$airport_id 	 = JRequest::getInt('airport_id' , 0);
$numberI = JRequest::getInt('numberI' , 0);
$airport = SfsHelperField::getAirportDefaultLocation( $airport_id );
?>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=geometry&key=AIzaSyDOs1LBadUSitSiSVqzytcXgBAoN1Ux_aU&sensor=false"></script>

<script type="text/javascript">	
  
    jQuery.noConflict();
    jQuery(function ($) {		
		var map;
		var marker;		
		var directionsService = new google.maps.DirectionsService;
		function updateMarkerPosition(latLng) {
		  /*document.getElementById('latlongclicked').innerHTML = [
			latLng.lat(),
			latLng.lng()
		  ].join(', ');*/
		  
		  setLaLog(latLng.lat(), latLng.lng());
		}
		
		function mapa()
		{
			var po = new google.maps.LatLng(<?php echo $airport->geo_lat?>, <?php echo $airport->geo_lon?>);
		  var opts = {
			  'center': po, 
			  'zoom':8 //, 
			  //'mapTypeId': google.maps.MapTypeId.TERRAIN, 
			 // draggableCursor: 'crosshair'
			}
		  
		  map = new google.maps.Map(document.getElementById("map-canvas"), opts);
		  placeMarker( po );
		  setLaLog('<?php echo $airport->geo_lat?>', '<?php echo $airport->geo_lon?>');
		  
		  google.maps.event.addListener(map,'click',function(event) {
			  setLaLog(event.latLng.lat(), event.latLng.lng());
			  placeMarker(event.latLng);
		  });
		
		  google.maps.event.addListener(map,'mousemove',function(event) {
			///document.getElementById('latspan').innerHTML = event.latLng.lat();
			///document.getElementById('lngspan').innerHTML = event.latLng.lng();
			///document.getElementById('latlong').innerHTML = event.latLng.lat() + ', ' +     event.latLng.lng();
		  });
		
		  google.maps.event.addListener(marker,'dragend', function(event) {
			updateMarkerPosition(marker.getPosition());
		  });
		
		}
		
		function placeMarker(location) {
		  if ( marker ) {
			marker.setPosition(location);
		  } else {
			marker = new google.maps.Marker({
			  position: location,
			  map: map,
			  animation: google.maps.Animation.DROP,
			  draggable: true
			});
		  }
		  getKM( location );
		}
		google.maps.event.addDomListener(window, 'load', mapa);
		
		function setLaLog(la, lg){
			$("#geo_location_latitude").val( la );
			$("#geo_location_longitude").val( lg );
			var txtGeo_location_latitude = self.parent.document.getElementById("geo_location_latitude<?php echo $numberI;?>");
			txtGeo_location_latitude.value = la;
			var txtgeo_location_longitude = self.parent.document.getElementById("geo_location_longitude<?php echo $numberI;?>");
			txtgeo_location_longitude.value = lg;
		}
		
		function codeAddress( address ) {
		  var geocoder = new google.maps.Geocoder();
		  geocoder.geocode( { 'address': address}, function(results, status) {
			if (status == google.maps.GeocoderStatus.OK) {
				var endlatlon = results[0].geometry.location.toString().replace("(","").replace(")","");
				var latLonArr = endlatlon.split(",");				
				var po = new google.maps.LatLng(latLonArr[0], latLonArr[1]);
				  var opts = {
					  'center': po, 
					  'zoom':8 //, 
					  //'mapTypeId': google.maps.MapTypeId.TERRAIN, 
					 // draggableCursor: 'crosshair'
					}
				  marker = null;
				  map = new google.maps.Map(document.getElementById("map-canvas"), opts);
				  placeMarker( po );
				  setLaLog(latLonArr[0], latLonArr[1]);
					
				  google.maps.event.addListener(map,'click',function(event) {
					  setLaLog(event.latLng.lat(), event.latLng.lng());					
					  placeMarker(event.latLng);
				  });
				  google.maps.event.addListener(marker,'dragend', function(event) {
					updateMarkerPosition(marker.getPosition());					
				  });
			  
			} else {
			  alert('Geocode was not successful for the following reason: ' + status);
			}
		  });
		}
		
		function getKM( to_address ){
			
			directionsService.route({
                origin: <?php echo $airport->geo_lat?> + "," + <?php echo $airport->geo_lon?>,
                destination: to_address,
                travelMode: google.maps.TravelMode.DRIVING
            }, function(response, status) {
                if (status === google.maps.DirectionsStatus.OK) {
					var leng = $('.block-group .block', self.parent.document).length;
					///alert( leng +' ==');
                    // Display the distance:
					///console.log( response.routes[0].legs[0] );
                    ///var distance = response.routes[0].legs[0].distance.value/1000;
					for(i = 0; i < leng; i++) { <?php //echo $numberI;?>
						$('#airport' + i + 'distance_unit option[value="mi"]',self.parent.document).removeAttr('selected');
						$('#airport' + i + 'distance_unit option[value="km"]',self.parent.document).removeAttr('selected');
						
						var distance = response.routes[0].legs[0].distance.text.toString().split(" ");
						if (distance[1] == 'm') {
							$('#airport' + i + 'distance_unit option[value="mi"]',self.parent.document).attr('selected', true);
						}
						else if (distance[1] == 'km') {
							
							$('#airport' + i + 'distance_unit option[value="km"]',self.parent.document).attr('selected', true);
						}
						
						$('.distance-unit' + i,self.parent.document).val(distance[0].replace(",", "."));
						
						if (i > 0 ) {
							//$('#airport'+i+'code option',self.parent.document).attr('selected', false);
							$('#airport'+i+'code option[value="<?php echo $airport_id;?>"]',self.parent.document).attr('selected', true);
							
						}
					}
					
                } else {
                    window.alert('Directions request failed due to ' + status);
                }
            });
		}
		
		$('#to-address').on('change', function(){
			var to_address = $(this).val();
			if(to_address != ''){
				codeAddress( to_address );
			}else{
				alert("Please input address!");
				mapa();
			}
		});
		
		$('.insert-geolocation').click(function(e) {
			var dataA = $('#insertgeolocationForm').serializeArray();
            $.ajax({
				type: "POST",
				url: "",
				data: dataA,
				success: function(server_response){
					alert( "Insert geolocation successful " );
					closeA();
					///$('.insert-geolocation').attr('disabled', true);
				}
  			});   //$.ajax ends here
        });
    });
	
	function closeA(){
		//document.location.reload(true);
		//self.parent.location.reload(true)
		window.parent.SqueezeBox.close();
	}
</script>

<p>Search and select your hotel on the map click the "insert geolocation" button to select the geolocation.<a style="font: inherit; font-size: 14px; margin-top: -3px; margin-right: 5px" class="sfs-button float-right" onclick="closeA();">Close</a></p>




<form id="insertgeolocationForm" action="" method="post" class="form-validate sfs-form form-vertical register-form">            
    <div class="block-group">
        <div class="clearfix">
            <div class="col w100 pull-left">
                <!--Lchung-->
                <input type="hidden" name="task" value="hotelprofile.InsertGeolocation" />
                <input type="hidden" name="from-address" id="from-address" value="<?php echo $airport->geo_lat?> ,<?php echo $airport->geo_lon?>" />
                <input type="hidden" name="hotel_id" id="hotel_id" value="<?php echo $this->hotel->id?>" />
                
                <div class="form-group">
                    <label style="margin-top:20px; width:auto;">
                       <strong>
                           Geolocation :
                        </strong>
                    </label>
                    <div class="col w80">
                        <div class="col w20" style="padding-left:0px;">
                            <label style="margin-top:0px;"><?php echo JText::_('Lat');?> :</label>
                            <input type="text" value="" name="geo_location_latitude" id="geo_location_latitude" class="validate-numeric"  />
                        </div>
                        <div class="col w30">
                            <label style="margin-top:0px;"><?php echo JText::_('Lon');?> :</label>
                            <input type="text" value="" name="geo_location_longitude" id="geo_location_longitude"  class="validate-numeric"  />
                            
                        </div>
                        <div class="col w40" style="padding-left:0px; padding-right:0px; width:auto;">
                        	<label style="margin:0px; width:auto; margin-top:20px;">
                            <button type="button" class="btn orange lg insert-geolocation">insert geolocation</button>
                            <!--<a href="javascript:void(0)" class="btn orange lg insert-geolocation">insert geolocation</a>-->
                        	</label>
                        </div>
                    </div>          
                </div>
                
                <div class="form-group">
                    <label style="width:auto;">
                       <strong>
                           To Address :
                        </strong>
                    </label>
                    <div class="col w80">
                        <div class="col w60">
                            <input type="text" value="" size="1" name="to-address" id="to-address" />
                        </div>
                   </div>
                </div>
                
                
                
                <div id="content" class="medium">
        
                <!--<span class="hasTip" title="click here to recenter the map to the airport location" id="center">
                    <img src="<?php echo $this->baseurl; ?>/templates/sfs_j16_hdwebsoft/js/geomap/images/center.png"/>
                </span>
                <span class="hasTip" title="click here to zoom in on the map" id="zoomin">
                    <img src="<?php echo $this->baseurl; ?>/templates/sfs_j16_hdwebsoft/js/geomap/images/zoomin.png"/>
                </span>
                <span class="hasTip" title="click here to zoom out on the map" id="zoomout">
                    <img src="<?php echo $this->baseurl; ?>/templates/sfs_j16_hdwebsoft/js/geomap/images/zoomout.png"/>
                </span>
                <span class="hasTip" title="click here to expand the map area on this page" id="expand">
                    <img src="<?php echo $this->baseurl; ?>/templates/sfs_j16_hdwebsoft/js/geomap/images/expand.png"/>
                </span>
                <span class="hasTip" title="click here to reduce the map area on this page" id="collapse">
                    <img src="<?php echo $this->baseurl; ?>/templates/sfs_j16_hdwebsoft/js/geomap/images/collapse.png"/>
                </span>-->
                <div id="map-canvas" data-step="2"></div>
            </div>
            
                <!--<div class="form-group" id="googleMap" style="width:800px;height:380px;"></div>-->
                <!--End lchung-->
                
                
            </div>
        </div>
	</div>
</form>

<style>


        #panel {
            margin-top: 60px;
            position: absolute;
            z-index: 5;
            background-color: #dedede;
            padding: 5px;
            border: 1px solid #999;
            font-size: 12px;
        }

        #expand, #collapse, #center, #zoomin, #zoomout {
            position: absolute;
            z-index: 5;
            background-color: #fff;
            padding: 5px;
            border: 1px solid #999;
            right: 8%;
        }

        .infobox-inner {
            -moz-box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.2);
            -webkit-box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.2);
            background: #fff;
            font-size: 12px;
            position: relative;
            margin-bottom: 50px;
            min-width: 400px;
            max-height: 400px;
        }

        .infobox-name a {
            color: #073855;
            font-size: 14px;
            font-weight: bold;
            text-decoration: none;
        }

        .infobox-input {
            width: 30px;
            height: 20px;

        }

        #map-canvas {
            left: 0;
            width: 100%;
            position: absolute;
        }

        #content.large #map-canvas {
            height: 100%;
        }

        #content.large #center {
            margin-top: 40px;
        }

        #content.large #zoomin {
            margin-top: 120px;
        }

        #content.large #zoomout {
            margin-top: 200px;
        }

        #content.large #collapse {
            margin-top: 280px;
        }



        #content.large #collapse img, #content.large #center img,
        #content.large #zoomin img , #content.large #zoomout img  {
            height: 48px;
            width: 48px;
            cursor: pointer;
        }

        #content.medium #map-canvas {
            height: 330px;
        }

        #content.medium #center {
            margin-top: 30px;
        }

        #content.medium #zoomin {
            margin-top: 70px;
        }

        #content.medium #zoomout {
            margin-top: 110px;
        }

        #content.medium #expand {
            margin-top: 150px;
        }

        #content.medium #collapse {
            margin-top: 190px;
        }

        #content.medium #expand img, #content.medium #collapse img, #content.medium #center img,
        #content.medium #zoomin img,#content.medium #zoomout img{
            height: 24px;
            width: 24px;
            cursor: pointer;
        }

        #content.small #map-canvas {
            height: 175px;
        }

        #content.small #center {
            margin-top: 20px;
        }

        #content.small #zoomin {
            margin-top: 50px;
        }

        #content.small #zoomout {
            margin-top: 80px;
        }

        #content.small #expand {
            margin-top: 110px;
        }

        #content.small #expand img, #content.small #center img,
        #content.small #zoomin img, #content.small #zoomout img{
            height: 16px;
            width: 16px;
            cursor: pointer;
        }

        .gm-style-iw {
            overflow-y: auto;
        }
        .info_label {
            color: white;
            font-family: "Lucida Grande", "Arial", sans-serif;
            font-size: 10px;
            font-style: italic;
            text-align: center;
            width: 40px;
            white-space: nowrap;
        }

        #map-canvas .bookingbutton {
            margin-bottom: 15px;
        }

    </style>