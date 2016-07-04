var map, marker, geocoder;

function initMap() {

    geocoder = new google.maps.Geocoder();

    var latLng;
    var lat = document.getElementById('st_lat'),
        lng = document.getElementById('st_lng');
    if ( lat.value === '' && lng.value == '') {
        // Default first location
        latLng = new google.maps.LatLng(38.2848733501699,
            21.7881073760987);
    } else {
        latLng = new google.maps.LatLng(lat.value,
            lng.value);
    }

    map = new google.maps.Map(document.getElementById('map_canvas'), {
        zoom: 9,
        center: latLng,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    });

    marker = new google.maps.Marker({
        position: latLng,
        draggable : true,
        map: map,
    });


    updateMarkerPosition(latLng);
    geocodePosition(latLng);

    // Add dragging event listeners.
    marker.addListener('dragstart', function() {
        updateMarkerAddress('Dragging...');
    });

    marker.addListener('drag', function() {
        updateMarkerPosition(marker.getPosition());
    });

    marker.addListener('dragend', function() {
        geocodePosition(marker.getPosition());
        updateMarkerPosition(marker.getPosition());
    });
}


function geocodePosition(pos) {
    geocoder.geocode({
        latLng: pos
    }, function(response) {
        if (response && response.length > 0) {
            updateMarkerAddress(response[0].formatted_address);
        } else {
            updateMarkerAddress('Cannot determine address at this location.');
        }
    });
}

function updateMarkerPosition(latLng) {
    document.getElementById('st_lat').value = latLng.lat();
    document.getElementById('st_lng').value = latLng.lng();
}

function updateMarkerAddress(address) {
    document.getElementById('st_address').innerHTML = address;
}


function moveMarkerToInput() {
    marker.setPosition(new google.maps.LatLng(
        document.getElementById('st_lat').value,
        document.getElementById('st_lng').value)
    );
    map.panTo(marker.getPosition());
    geocodePosition(marker.getPosition());
}

// 2-way binding change on lat-lng changes marker posstion
$('#st_lat').on('change', moveMarkerToInput);
$('#st_lng').on('change', moveMarkerToInput);