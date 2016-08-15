<?php
defined('_JEXEC') or die();
JHTML::_('behavior.tooltip');
$airline = SFactory::getAirline();
$airport = array("name" => $airline->airport_name, "code" => $airline->airport_code, "logo" => $airline->logo, "geo_location_latitude" => $airline->geo_lat, "geo_location_longitude" => $airline->geo_lon);
$latlng_airport = $airport["geo_location_latitude"] . "," . $airport["geo_location_longitude"];
$airport["geo_location_latitude"] != null ? $check_map = true : $check_map = false;
$airport["logo"] = $this->baseurl . "images/" . $airport["logo"];
if (isset($airline->airport_ring_1_mile))
    $ring1 = $airline->airport_ring_1_mile;
else
    $ring1 = 0;
if (isset($airline->airport_ring_2_mile))
    $ring2 = $airline->airport_ring_2_mile;
else
    $ring2 = 0;
$airport_json = json_encode($airport);
//var_dump($this->result);
$hotels_json = json_encode($this->result);
$date_start = JRequest::getVar('date_start');
$date_end = JRequest::getVar('date_end');
$app = JFactory::getApplication();
$menu = $app->getMenu();
$menuItem = $menu->getItems( 'link', 'index.php?option=com_sfs&view=handler&layout=search', true );
?>

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
    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=geometry&key=AIzaSyDOs1LBadUSitSiSVqzytcXgBAoN1Ux_aU&sensor=false"></script>
    <script type="text/javascript" src="<?php echo $this->baseurl; ?>/templates/sfs_j16_hdwebsoft/js/geomap/markerclusterer.js"></script>
    <script type="text/javascript" src="<?php echo $this->baseurl; ?>/templates/sfs_j16_hdwebsoft/js/geomap/markerwithlabel.js"></script>
    <script src="<?php echo $this->baseurl; ?>/templates/sfs_j16_hdwebsoft/js/geomap/geomap.js"
            type="text/javascript"></script>
<?php if ($check_map): ?>
    <div id="content" class="medium">
        <div id="panel">
            <label>From: <?php echo JHtml::_('date', $date_start, JText::_('DATE_FORMAT_LC3'), false) ?></label><br/>
            <label>Until: &nbsp;<?php echo JHtml::_('date', $date_end, JText::_('DATE_FORMAT_LC3'), false) ?></label><br/>
            <div style="margin: 10px 10px 10px 40px">
                <a href="<?php echo JRoute::_('index.php?&Itemid='.$menuItem->id)?>" class="small-button">New Search</a>
            </div>
<!--            <label>Show only hotels:</label><br/>-->
<!--            <form name="requestSearchFrom" action="--><?php //echo JRoute::_('index.php?option=com_sfs&view=search');?><!--" method="post" class="sfs-form form-horizone">-->
<!--                <label for="show_all_rooms"><input type="checkbox" name="show_all_rooms" id="show_all_rooms" value="1" --><?php //if($this->state->get('filter.show_all_rooms')) echo 'checked="checked"';?><!-- /> That can take all the rooms</label>-->
<!--                <br/>-->
<!--                <label for="transport_included"><input type="checkbox" name="transport_included" id="transport_included" value="1" --><?php //if($this->state->get('filter.transport_included')) echo 'checked="checked"';?><!-- />With transport included</label>-->
<!--                <br/>-->
<!--                <label for="offer_meal_plans"><input type="checkbox" name="offer_meal_plans" id="offer_meal_plans" value="1" --><?php //if($this->state->get('filter.offer_meal_plans')) echo 'checked="checked"';?><!-- />With offer lunch and dinner</label>-->
<!--                <br/>-->
<!---->
<!--                <label for="filter_hotel_star">-->
<!--                    <input type="checkbox" name="filter_hotel_star" id="filter_hotel_star" value="1" --><?php //if( $this->state->get('filter.hotel_star') ) echo 'checked="checked"';?><!-- />-->
<!--                    <select name="hotel_star" class="thin-size" style="padding:1px; width:150px;">-->
<!--                        <option value="3"--><?php //if( (int)$this->state->get('filter.hotel_star') == 3 ) echo ' selected="selected"';?><!-->
<!--                        <option value="4"--><?php //if( (int)$this->state->get('filter.hotel_star') == 4 ) echo ' selected="selected"';?><!-->
<!--                        <option value="5"--><?php //if( (int)$this->state->get('filter.hotel_star') == 5 ) echo ' selected="selected"';?><!-->
<!--                    </select>-->
<!--                </label>-->
<!--                <br/>-->
<!---->
<!--                <div style="text-align: right; margin: 10px;">-->
<!--                    <button onClick="this.form.submit()" name="B1" class="small-button" style="display: inline">Update</button>-->
<!--                </div>-->
<!---->
<!---->
<!--                <input type="hidden" name="rooms" value="--><?php //echo $this->state->get('filter.rooms');?><!--" />-->
<!--                <input type="hidden" name="adults" value="--><?php //echo $this->state->get('filter.adults');?><!--" />-->
<!--                <input type="hidden" name="children" value="--><?php //echo $this->state->get('filter.children');?><!--" />-->
<!--                <input type="hidden" name="date_start" value="--><?php //echo $this->state->get('filter.date_start');?><!--" />-->
<!--                <input type="hidden" name="date_end" value="--><?php //echo $this->state->get('filter.date_end');?><!--" />-->
<!--                <input type="hidden" name="hour_start" value="--><?php //echo $this->state->get('filter.hour_start');?><!--" />-->
<!--                <input type="hidden" name="hour_end" value="--><?php //echo $this->state->get('filter.hour_start');?><!--" />-->
<!--                <input type="hidden" name="extend" value="--><?php //echo JRequest::getInt('extend');?><!--" />-->
<!--                <input type="hidden" name="ordering" value="" />-->
<!--                <input type="hidden" name="task" value="search.search" />-->
<!--                <input type="hidden" name="Itemid" value="--><?php //echo JRequest::getInt('Itemid'); ?><!--" />-->
<!--            </form>-->
        </div>
        <span class="hasTip" title="click here to recenter the map to the airport location" id="center">
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
        </span>
        <div id="map-canvas" data-step="2" data-intro="<?php echo SfsHelper::getTooltipTextEsc('geo_map', 'help-icon', 'airline', false) ?>"></div>
    </div>
<?php else: ?>
    <div class="uk-alert uk-alert-danger" style="position: absolute; top: 200px; ">
        Could not show your hotels in the map. Please contact administrator to be able to setup hotels in the map view.
    </div>
<?php endif; ?>

<?php if ($check_map): ?>

    <script type="text/javascript">

        function minRate(data) {
            var min_price = Number.POSITIVE_INFINITY;


            if(data['ws_id'] == null){

                if(data["s_room_rate"] && data["s_room_rate"] != 0)
                {
					min_price = Math.min(min_price,data["s_room_rate"]);
					if( data["convert_s_room_rate"] != 0 )
                    	min_price = Math.min(min_price,data["convert_s_room_rate"]);
                }
                else if(data["sd_room_rate"] && data["sd_room_rate"] != 0)
                {
					min_price = Math.min(min_price,data["sd_room_rate"]);
					if( data["convert_sd_room_rate"] != 0 )
                    	min_price = Math.min(min_price,data["convert_sd_room_rate"]);
                }
                else if (data["t_room_rate"] && data["t_room_rate"] != 0)
                {
					min_price = Math.min(min_price,data["t_room_rate"]);
					if( data["convert_t_room_rate"] != 0 )
                    	min_price = Math.min(min_price,data["convert_t_room_rate"]);
                }
                else if (data["q_room_rate"] && data["q_room_rate"] != 0)
                {
					min_price = Math.min(min_price,data["q_room_rate"]);
					if( data["convert_q_room_rate"] != 0 )
                    	min_price = Math.min(min_price,data["convert_q_room_rate"]);
                }
                if(min_price == Number.POSITIVE_INFINITY)
                    min_price = 0;
            }else{
                
                if(data["s_room_rate"] && data["s_room_rate"] != 0)
                {
                    min_price = Math.min(min_price,data["s_room_rate"]);
                }
                if(data["sd_room_rate"] && data["sd_room_rate"] != 0)
                {
                    min_price = Math.min(min_price,data["sd_room_rate"]);
                }
                if (data["t_room_rate"] && data["t_room_rate"] != 0)
                {
                    min_price = Math.min(min_price,data["t_room_rate"]);
                }
                if (data["q_room_rate"] && data["q_room_rate"] != 0)
                {
                    min_price = Math.min(min_price,data["q_room_rate"]);
                }
                if(min_price == Number.POSITIVE_INFINITY)
                    min_price = 0;
            }

            
            return min_price.toFixed(2);
        }

        jQuery.noConflict();
        jQuery(function ($) {
            var center = new google.maps.LatLng(<?php echo $latlng_airport?>);
            var myMap = new googleMapFactory($);
            var markers = [];
            markers.push(new MarkerWithLabel({
                position: center,
                map: null,
                icon: {
                    url: 'templates/sfs_j16_hdwebsoft/js/geomap/images/Airport_icon_fw.png',
                    scaledSize: new google.maps.Size(32, 37)
                },
                animation: google.maps.Animation.DROP
            }));

            var hotels = <?php echo $hotels_json?>;

            //TODO: hide this after debug
            window.hotels = hotels;

            //add icon calculate price
            hotels.map(function (hotel) {
                if(hotel != null){
                    if((hotel['s_room_total'] && hotel['s_room_total']!=0) || (hotel['sd_room_total'] && hotel['sd_room_total']!=0) || (hotel['t_room_total'] && hotel['t_room_total']!=0) || (hotel['q_room_total'] && hotel['q_room_total']!=0))
                    {
                        var LatLng = new google.maps.LatLng(hotel["geo_location_latitude"], hotel["geo_location_longitude"]);
                        var min_rate = minRate(hotel);
                        if(min_rate!='0.00'){
                            markers.push(new MarkerWithLabel({
                                position: LatLng,
                                map: null,
                                icon: {
                                    url: 'templates/sfs_j16_hdwebsoft/js/geomap/images/map_icon_fw.png',
                                    scaledSize: new google.maps.Size(38, 40)
                                },
                                animation: google.maps.Animation.DROP,
                                labelAnchor: new google.maps.Point(20, 30),
                                labelContent: hotel["currency_symbol"]+min_rate,
                                labelClass: "info_label",
                                labelStyle: {opacity: 1},
                                labelVisible: true,
                                data: hotel,
                                type: 'hotel'
                            }));
                        }

                        
                    }
                }
                
            });

            jQuery("body").on("ws-external-hotels-finished", function (e, newHotels) {
                newHotels.map(function (hotel) {
                    var LatLng =  new google.maps.LatLng(hotel["geo_location_latitude"], hotel["geo_location_longitude"]);
                    var min_rate = minRate(hotel);
                    if(min_rate!='0.00'){
                        var marker = new MarkerWithLabel({
                            position: LatLng,
                            map: null,
                            icon: {
                                url: 'templates/sfs_j16_hdwebsoft/js/geomap/images/map_icon_fw.png',
                                scaledSize: new google.maps.Size(38, 40)
                            },
                            animation: google.maps.Animation.DROP,
                            labelAnchor: new google.maps.Point(20, 30),
                            labelContent: hotel["currency_symbol"]+min_rate,
                            labelClass: "info_label",
                            labelStyle: {opacity: 1},
                            labelVisible: true,
                            data: hotel,
                            type: 'hotel'
                        });
                        markers.push(marker);
                        myMap.extendBound(LatLng);
                        myMap.initMarker(marker);
                        myMap.markerCluster(markers);
                        myMap.reset();
                    }
                    
                });

            });

            var markerHTMLContent = function (marker) {
                'use strict';
                if (marker.type == 'hotel') {
                    var hotel = marker.data;
                    var id = marker.data.hotel_id;
                    var html = '<table class="search-result search-result-popout" id="search-result-popout">';
                    html += '<thead>';
                    html += '<tbody class="sfs-form filter-hotel-' + id + '">';
                    html += $('#filter-hotel-' + id).html();
                    html += '</tbody>';
                    html += '</thead>';
                    html += '</table>';
                    var html = $(html);
                    return html[0];
                }
                return null;
            };


            myMap.center(center)
                .element($('#map-canvas')[0])
                .markers(markers)
                .markerHTMLContent(markerHTMLContent);
            myMap.init();
            myMap.markerCluster(markers);
            myMap.expand("medium", function () {
            });


            $("#expand").click(function () {
                if ($('#content').hasClass("medium")) {
                    $('#content').removeClass("medium");
                    $('#content').addClass("large");
                    myMap.expand("large", function () {
                        $("#panel").show();
                        $("#panel").css("left", "2%");
                        $("#expand").hide();
                    });
                }
                else {
                    $('#content').addClass("medium");
                    $('#content').removeClass("small");
                    myMap.expand("medium", function () {
                        $("#panel").show();
                        $("#panel").css("left", "");
                        $("#collapse").show();
                    });
                }
            });

            $("#collapse").click(function () {
                if ($('#content').hasClass("large")) {
                    $('#content').removeClass("large");
                    $('#content').addClass("medium");
                    myMap.expand("medium", function () {
                        $("#panel").show();
                        $("#panel").css("left", "");
                        $("#expand").show();
                    });
                }
                else {
                    $('#content').removeClass("medium");
                    $('#content').addClass("small");
                    myMap.expand("small", function () {
                        $("#panel").hide();
                        $("#collapse").hide();
                    });
                }
            });

            $("#center").click(function () {
                myMap.reset();
            });
            $("#zoomin").click(function () {
                myMap.zoomin();
            });
            $("#zoomout").click(function () {
                myMap.zoomout();
            })

            <?php
                $data_hotels = json_decode($hotels_json);
                if ($data_hotels == null){
            ?>
                myMap.zoomInit(12);
            <?php }?>
        })
    </script>
<?php endif; ?>