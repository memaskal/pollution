var activeWindow = '';
var map, heatmap, points;
var markerInfo = {};

/**
*  Values found in this link
*  http://www.airqualitynow.eu/about_indices_definition.php
**/
var dangerZones = {
    'NO2'   : [0, 50, 100, 200, 400],
    'CO'    : [0, 5, 7.5, 10, 20],
    'O3'    : [0, 60, 120, 180, 240],
    'SO2'   : [0, 50, 100, 350, 500],
};


function initMap() {
    // The map object
    map = new google.maps.Map(document.getElementById('map_canvas'), {
        zoom: 7,
        center: {lat: 39.0777683, lng: 23.3476132},
        mapTypeId: google.maps.MapTypeId.ROADMAP
    });

    // The heatmap object
    points = new google.maps.MVCArray();
    heatmap = new google.maps.visualization.HeatmapLayer({
        data: points,
        map: map,
        radius: 100,
        opacity : 0.5,   
        maxIntensity: 100,
    });

    // Load all the stations
    getStations();
}


function getStations() {
    fetchData("/demo/reqStations", function(data) {
        var dropDown = '';
        $.each( data , function( key, val ) {
            // create a marker  + info window for each station
            createMarker(val.name, val.id, val.latitude, val.longitude);

            // create station selectors
            dropDown += '<option value="'+ val.id +'">'+ val.name + '</option>';
        });
        // append the station dropdown to each form
        $("select[name='st_code']").each(function() {
            $(this).append(dropDown);
        });
    });
}


function createMarker(name, id, latitude, longitude) {
    var marker = new google.maps.Marker({
        position: new google.maps.LatLng(latitude, longitude),
        id: id,
        map: map,
        title: name,
    });

    // Create a heatmap point at the same location as the marker
    // save index in marker itself
    marker.pindx = points.getLength();
    points.push({location: new google.maps.LatLng(latitude, longitude), weight: 0});

    // InfoWindow content
    var content = 'Station Name : ' + name + '<br>'
                + 'Station Code : ' + id + '<br>'
                ;

    var infowindow = new google.maps.InfoWindow({
        content: content
    });

    // Create an index for every marker based on ther location
    // not great but works
    markerInfo[latitude + longitude] = infowindow;
    infowindow.marker = marker;

    marker.addListener('click', function() {
        infowindow.open(map, marker);
    });
}


$('#showAbsValue').click(function() {
    $('#modal-body').html($('#req_2').html());
    $("#modalw").modal();
    activeWindow = 'req_2';
});


$('#showAvgValue').click(function() {
    $('#modal-body').html($('#req_3').html());
    $("#modalw").modal();
    activeWindow = 'req_3';
});


$('#submit-modal').click(function() {
    // Get selected pollution type value
    var polType = $('#modalw').find('#pol_typeInput').val();

    // Construct the link to the api
    var params = '?' + $('#modal-body').serialize(),
        url = '/demo/' + ((activeWindow === 'req_2') ? 'reqAbsValue' : 'reqAvgValue') + params;
  
    // Make the actual request
    fetchData(url, function(data) {
        $.each(data , function( key, val ) {

            var infoWindow = markerInfo[val.latitude + val.longitude];
            var marker = infoWindow.marker;

            var content = 'Station Name : ' + marker.title + '<br>'
                        + 'Station Code : ' + marker.id + '<br>';

            if (activeWindow === 'req_2') {
                content += 'Absolute Value : ' + val.abs;
            }
            else {
                content += 'Avarage Value : ' + val.avg + '<br>'
                        + 'Stddev Value : ' + val.s;
            }
            // set the new content       
            infoWindow.setContent(content);

            // draw the new heatmap's point weight either with
            // the absolute value or the average
            setPointWeight(marker, polType, ((activeWindow === 'req_2') ? val.abs : val.avg));
        });
    });

     // Close the modal
    $('#modalw').modal('toggle');
});


function setPointWeight(marker, polType, value) {

    var index = marker.pindx;
    // Get the point in pos: index
    var heatPoint = points.removeAt(index);
    var zone = dangerZones[polType];

    if (zone === undefined) {
        // If the zone for this pollution type is 
        // undefined we draw the weight value as is
        heatPoint.weight = value;
    } 
    else if ( value > zone[zone.length - 1] ) {
        heatPoint.weight = heatmap.maxIntensity;
    }
    else {
        // Determine the zone we fall in and calculate the 
        // weight for this point
        for (var i = 0; i < zone.length; ++i) {
            if ( value <= zone[i] ) {
                // Split intensity to equal color zones
                heatPoint.weight = i * (heatmap.maxIntensity / zone.length);
                break;
            }
        }
    }
    // Save back the heatPoint
    points.insertAt(index, heatPoint);
}


function fetchData(url, callback) {
    $.getJSON(url, function( response ) {
        if (response.status != 'OK') {
            alert("Error " + response.status);
            return response.status;
        }
        callback(response.values);
    });
}
