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
                    for (i in data)
                    {
                        infowindow = new google.maps.InfoWindow({
                            content: '<div style="width: 350px; height: 100px;" class="data"> Name: ' + data[i].name + '<br>Lat: ' + data[i].lat + '<br>Lng: ' + data[i].lng + '<br>Region: ' + data[i].region + '<br>Distance: ' + parseFloat(data[i].distance).toFixed(3) + 'km</div>'
                        });
                        locationMarker = new google.maps.LatLng(data[i].lat, data[i].lng);
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
        </style>
    </head>
    <body>
        <div id="map"></div>
    </body>
</html>
