/** supporter http://www.hdwebsoft.com **/

var googleMapFactory = function ($) {

    'use strict';

    var geocoder = new google.maps.Geocoder();
    var bounds = new google.maps.LatLngBounds();
    var infoWindow = new google.maps.InfoWindow({
        enableEventPropagation: true,
        maxHeight: 300,
        minWidth: 600
    });
    var map = null;
    var markerCluster = null;
    var center = [0, 0];
    var mapOptions = {
        zoom: 13,
        center: center,
        mapTypeId: google.maps.MapTypeId.TERRAIN,
        zoomControl: false,
        linksControl: false,
        panControl: false,
        mapTypeControl: false,
        streetViewControl: false
    };
    var circles = [];


    var element,
        markers = [];

    var scope = {};

    scope.element = function (x) {
        if (!arguments.length) return element;
        element = x;
        return scope;
    };

    scope.center = function (x) {
        if (!arguments.length) return center;
        center = x;
        return scope;
    };

    scope.markers = function (x) {
        if (!arguments.length) return markers;
        markers = x;
        return scope;
    };

    scope.minPrice = function (markers)
    {
        var min_price = Number.POSITIVE_INFINITY, symbol;
        markers.map(function (m) {
            if(m.data.sd_room_rate && m.data.sd_room_rate != 0)
            {
                min_price = Math.min(min_price,m.data.sd_room_rate);
                symbol = m.data.currency_symbol;
                if(!symbol)
                    symbol = "";
            }
            if(m.data.s_room_rate && m.data.s_room_rate != 0)
            {
                min_price = Math.min(min_price,m.data.s_room_rate);
                symbol = m.data.currency_symbol;
                if(!symbol)
                    symbol = "";
            }
            if (m.data.t_room_rate && m.data.t_room_rate != 0)
            {
                min_price = Math.min(min_price,m.data.t_room_rate);
                symbol = m.data.currency_symbol;
                if(!symbol)
                    symbol = "";
            }
            if (m.data.q_room_rate && m.data.q_room_rate != 0)
            {
                min_price = Math.min(min_price,m.data.q_room_rate);
                symbol = m.data.currency_symbol;
                if(!symbol)
                    symbol = "";
            }
            if(min_price == Number.POSITIVE_INFINITY)
                min_price = 0;
        });
        return [symbol,Math.round(min_price,0)];
    };


    scope.createRing1 = function (miles) {
        var ringOption = {
            path: google.maps.SymbolPath.CIRCLE,
            strokeColor: '#4C6CAB',
            strokeOpacity: 0.8,
            strokeWeight: 5,
            fillColor: '#AAAAAA',
            fillOpacity: 0.35,
            map: map,
            center: center,
            radius: Math.sqrt(miles) * 1609.344,
            visible: false
        };
        return circles[0] = new google.maps.Circle(ringOption);
    };

    scope.createRing2 = function (miles) {
        var ringOption = {
            path: google.maps.SymbolPath.CIRCLE,
            strokeColor: '#4C6CAB',
            strokeOpacity: 0.8,
            strokeWeight: 5,
            fillColor: '#AAAAAA',
            fillOpacity: 0.35,
            map: map,
            center: center,
            radius: Math.sqrt(miles) * 1609.344,
            visible: false
        };
        return circles[1] = new google.maps.Circle(ringOption);
    };

    scope.map = function () {
        return map;
    };

    scope.calDistance = function (pointA, pointB) {
        return google.maps.geometry.spherical.computeDistanceBetween(pointA, pointB);
    };

    var markerHTMLContent = function (marker) {
        return null;
    };

    var markerHTMLContentCallback = function (element, marker) {
        return;
    };

    scope.markerHTMLContent = function (x) {
        if (!arguments.length) return markerHTMLContent;
        markerHTMLContent = x;
        return scope;
    };

    scope.markerHTMLContentCallback = function (x) {
        if (!arguments.length) return markerHTMLContentCallback;
        markerHTMLContentCallback = x;
        return scope;
    };

    scope.init = function () {
        if (!element) {
            throw "DOM element is required to setup a map";
        }
        if (!center) {
            throw "center point is required to setup a map";
        }
        mapOptions.center = center;
        map = new google.maps.Map(element, mapOptions);
        scope.extendBound(center);
        markers.map(function (marker) {
            scope.extendBound(marker.position);
            scope.initMarker(marker);
        });
        map.fitBounds(bounds);
        return scope;
    };

    scope.markerCluster = function(markers){
        if (markerCluster != null) {
            markerCluster.clearMarkers();
        }
        var MarkerClustererOptions = {
            gridSize:40,
            ignoreHidden: true,
            styles: [{
                url:'templates/sfs_j16_hdwebsoft/js/geomap/images/multiple_map_icons_fw.png',
                width: 45,
                height: 47,
                textColor: "white",
                fontStyle: "italic",
                textSize: 9
            }]
        };
        var hotel_marker = [];
        markers.map(function (m) {
            if(m.type == 'hotel')
                hotel_marker.push(m);
        });

        markerCluster = new MarkerClusterer(map, hotel_marker, MarkerClustererOptions);
        markerCluster.setCalculator(function(hotel_marker, numStyles){
            var index = 0,
                count = hotel_marker.length,
                total = count,
                minPrice = scope.minPrice(hotel_marker);

            while (total !== 0) {
                total = parseInt(total / 5, 10);
                index++;
            }
            index = Math.min(index, numStyles);
            return {
                text: minPrice[0]+ "" + minPrice[1],
                index: index
            };
        });
        markerCluster.setMaxZoom(14);
        return markerCluster;
    };

    scope.initMarker = function(marker){
        var div = document.createElement('div');
        marker.setMap(map);
        google.maps.event.addListener(marker, 'click', (function (marker, infoWindow) {
            return function () {
                infoWindow.close();
                var html = markerHTMLContent(marker);
                if (html == null) {
                    return;
                }
                $(div).find('.popout-container').remove();
                $(div).html('<div class="popout-container"></div>');
                $(div).find('.popout-container').append(html);
                infoWindow.setContent(div);
                infoWindow.setPosition(marker.position);
                infoWindow.open(map, marker);
                markerHTMLContentCallback(div, marker);
            }
        })(marker, infoWindow));
    };
    scope.extendBound = function(LatLng){
        bounds.extend(LatLng);
    };

    scope.createLatLon = function (lat, lon) {
        return new google.maps.LatLng(lat, lon);
    };

    scope.expand = function (type, callback) {
        var new_center = map.getCenter();
        map.fitBounds(bounds);
        switch (type) {
            case "large" :
                $(".main").css("margin-top", "500px");
                break;
            case "small" :
                $(".main").css("margin-top", "0");
                break;
            default :
                $(".main").css("margin-top", "180px");
        }
        google.maps.event.trigger(map, 'resize');
        map.setCenter(new_center);
        callback();
    };
    scope.reset = function () {
        map.setCenter(center);
        map.fitBounds(bounds);
    };

    scope.zoomin = function () {
        map.setZoom(parseInt(map.getZoom()) + 1);
    };

    scope.zoomout = function () {
        map.setZoom(parseInt(map.getZoom()) - 1);
    };

    scope.zoomInit = function ($number) {
        if($number) {
            google.maps.event.addListenerOnce(map, 'bounds_changed', function (event) {
                this.setZoom($number);
            });
        }
    };

    return scope;
};