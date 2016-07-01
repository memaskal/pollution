var activeWindow = '';
var map, heatmap, points;
var markerInfo = {};

function initMap() {
    map = new google.maps.Map(document.getElementById('map_canvas'), {
        zoom: 7,
        center: {lat: 39.0777683, lng: 23.3476132},
        mapTypeId: google.maps.MapTypeId.ROADMAP
    });

    points = new google.maps.MVCArray();

    heatmap = new google.maps.visualization.HeatmapLayer({
        data: points,
        map: map,
        radius: 100,
        maxIntensity: 20,
        opacity : 0.5,
    });

    // Load all the stations
    getStations();
}


function getStations() {
    fetchData("demo/reqStations", function(data) {
        var dropDown = '';
        $.each( data , function( key, val ) {
            // create a marker  + info window for each station
            createMarker(val.name, val.id, val.latitude, val.longitude);

            // create station selectors
            dropDown = dropDown + '<option value="'+ val.id +'">'+ val.name + '</option>';
        });
        // append the station dropdown to each form
        $("select[name='st_code']").each(function() {
            $(this).append(dropDown);
        });
    });
}

function createMarker(name, id, latitude, longitude) {
    var marker = new google.maps.Marker({
        position: {lat: parseFloat(latitude), lng: parseFloat(longitude)},
        map: map,
        title: name,
        id: id,
    });

    var pindx = points.getLength();
    points.push({location: new google.maps.LatLng(parseFloat(latitude), parseFloat(longitude)), weight: 1});
    marker.pindx = pindx;

    var content = 'Station Name : ' + name + '<br>'
            + 'Station Code : ' + id + '<br>'
            + 'Absolute Value : -<br>'
            + 'Avarage Value : -<br>'
            + 'Stddev Value : -'
        ;

    var infowindow = new google.maps.InfoWindow({
        content: content
    });

    // Create an index for every marker
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

    // Construct the link to the api
    var url = $('#modal-body').serialize();
    $('#modalw').modal('toggle');
    if (activeWindow === 'req_2') {
        url = '/demo/reqAbsValue?' + url;
    } else {
        url = '/demo/reqAvgValue?' + url;
    }

    // Make the actual request
    fetchData(url, function(data) {
        $.each(data , function( key, val ) {
            var infoWindow = markerInfo[val.latitude + val.longitude];
            var marker = infoWindow.marker;
            var content = 'Station Name : ' + marker.title + '<br>'
                    + 'Station Code : ' + marker.id + '<br>'
                    + 'Absolute Value : ' + val.abs + '<br>'
                    + 'Avarage Value : ' + val.avg + '<br>'
                    + 'Stddev Value : ' + val.s
                ;
            infoWindow.setContent(content);
            var heatPoint = points.removeAt(marker.pindx);
            heatPoint.weight = (val.abs !== undefined) ? val.abs : val.avg;
            points.insertAt(marker.pindx, heatPoint);
        });
    });
});

function fetchData(url, callback) {
    $.getJSON(url, function( response ) {
        if (response.status != 'OK') alert("Error " + response.status);
        callback(response.values);
    });
}
