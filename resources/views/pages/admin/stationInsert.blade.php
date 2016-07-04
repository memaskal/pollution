@extends('layouts.admin') @section('title', 'Station Insert')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif

                @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="station-insert" method="post">
                    {{ csrf_field() }}

                    <div class="form-group">
                        <label for="st_codeInput">Station Code</label>
                        <input type="text" class="form-control" name="st_code" id="st_codeInput"
                               maxlength="5" placeholder="PAT" required value="{{ old('st_code') }}"/>
                    </div>

                    <div class="form-group">
                        <label for="st_nameInput">Station Name</label>
                        <input type="text" class="form-control" name="st_name" id="st_nameInput"
                               maxlength="30" placeholder="Σταθμός Πάτρας (ΠΑ.ΠΑ.)" required value="{{ old('st_name') }}"/>
                    </div>

                    <div class="form-group">
                        <label for="st_lat">Station Location (Lat - Lng)</label>
                        <input type="text" class="form-control" name="st_lat" id="st_lat"
                               required value="{{ old('st_lat') }}"/>
                        <input type="text" class="form-control" name="st_lng" id="st_lng"
                               required value="{{ old('st_lng') }}"/>
                    </div>

                    <div class="form-group">
                        <label for="st_address">Station Address</label>
                        <div id="st_address"></div>
                    </div>

                    <button type="submit" class="btn btn-primary">Insert</button>
                </form>
            </div>
            <div class="col-md-8">
                <div id="map_canvas" style="height:400px"></div>
            </div>
        </div>
    </div>
@endsection

@section('opt-scripts')
<script>
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

</script>
<script async defer
        src="https://maps.googleapis.com/maps/api/js?key={{ $gmap_key }}&libraries=visualization&callback=initMap">
</script>
@endsection