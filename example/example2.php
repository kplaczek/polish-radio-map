<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Example 2</title>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
        <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
        <script>
            var map;
            var placemarker;
            var markersArray = [];
            var infowindowArray = [];
            var locationMarker = null;
            var infowindow = null;
            $(document).ready(function() {
                load();
            });
            function load() {
                map = new google.maps.Map(document.getElementById("map"), {
                    center: new google.maps.LatLng(52.086264, 19.104079),
                    zoom: 7,
                    mapTypeId: 'roadmap'
                });

                google.maps.event.addListener(map, 'click', function(event) {
                    readMarkers(event.latLng);
                });

            }
            function readMarkers(location)
            {
                if (placemarker)
                    placemarker.setPosition(location);
                else {
                    placemarker = new google.maps.Marker({
                        position: location,
                        map: map
                    });
                }

                $.ajax({
                    url: 'ajax.php',
                    type: 'POST',
                    dataType: 'JSON',
                    data: {lat: location.lat(), lng: location.lng()}
                }).done(function(data) {
                    deleteOverlays();
                    deleteOverlays();
                    $('#stations').html('')
                    for (i in data[1]){
                        $('<li>').html(data[1][i].frequency + ' - '+data[1][i].name).appendTo('#stations');
                    }

                    for (i in data[0])
                    {
                        infowindow = new google.maps.InfoWindow({
                            content: '<div style="width: 350px; height: 100px;" class="data"> Name: ' + data[0][i].name + '<br>Lat: ' + data[0][i].lat + '<br>Lng: ' + data[0][i].lng + '<br>Region: ' + data[0][i].region + '<br>Distance: ' + parseFloat(data[0][i].distance).toFixed(3) + 'km</div>'
                        });
                        locationMarker = new google.maps.LatLng(data[0][i].lat, data[0][i].lng);
                        infowindowArray.push(infowindow);
                        marker = new google.maps.Marker({
                            position: locationMarker,
                            map: map,
                            icon: 'transmitter.png',
                        });
                        markersArray.push(marker);
                        listenMarker(markersArray[i], i);
                    }
                });
            }
            function deleteOverlays() {
                if (markersArray) {
                    for (i in markersArray) {
                        markersArray[i].setMap(null);
                        infowindowArray[i] = null;
                    }
                    markersArray.length = 0;
                    infowindowArray.length = 0;
                }
            }
            function listenMarker(marker, x)
            {
                google.maps.event.addListener(marker, 'click', function() {
                    for (var i = 0; i < infowindowArray.length; i++)
                    {
                        infowindowArray[i].close(map);
                    }
                    infowindowArray[x].open(map, marker);
                });
            }
        </script>
        <style>
            html,body { height:100%; margin: 0; padding: 0;}
            #map {width: 100%; height: 100%;}
            #infoBox {font-size: 10pt; font-family: verdana, sans-serif; overflow: auto;  background: none repeat scroll 0 0 white; bottom: 0; display: block; height: 200px; left: 0; position: fixed; width: 450px; z-index: 10000;}
        </style>
    </head>
    <body>
        <div id="infoBox">
            <ul id="stations"></ul>
        </div>
        <div id="map"></div>
    </body>
</html>
