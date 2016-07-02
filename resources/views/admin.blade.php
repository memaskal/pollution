<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <link rel="shortcut icon" type="image/png" href="/favicon.png"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Envioromental Monitoring</title>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <style>
        body{
            /* Required padding for .navbar-fixed-top. Remove if using .navbar-static-top. Change if height of navigation changes. */
            padding-top: 70px;
        }

        .row {
            margin-bottom: 2%;
        }

        #map_canvas {
            height: 380px;
        }

        #footer {
            height: 4%;
            margin-top:20px;
        }
    </style>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>
<body>
<!-- Navigation -->


<!-- Page Content -->
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

            <form action="admin/upload" method="post" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                {{--<div class="form-group">
                    <label for="year">Year</label>
                    <input type="number" name="year" class="form-control" id="year" min="1987" value="{{ old('year') }}">
                </div>--}}

                <div class="form-group">
                    <label for="pol_typeInput">Pollution Type</label>
                    <select class="form-control" name="pol_type" id="pol_typeInput">
                    @foreach($pol_types as $pt)
                        <option value="{{ $pt }}">{{ $pt }}</option>
                    @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="st_codeInput">Station Code</label>
                    <select class="form-control" name="st_code" id="st_codeInput">
                    @foreach($stations as $station)
                        <option value="{{ $station->id }}">{{ $station->name }}</option>
                    @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="file">File Upload</label>
                    <input class="form-control-file" type="file" id="file" name="file" required>
                </div>

                <button type="submit" class="btn btn-primary">Upload</button>
            </form>
        </div>
    </div>

    <!-- Insert map form -->
    <div class="row">
        <div class="col-md-4">
            <form action="admin/station" method="post">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

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
            <div id="map_canvas"></div>
        </div>
    </div>

</div>


<footer id="footer">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                Developers API here
            </div>
            <div class="col-lg-6">

            </div>
        </div>
    </div>
</footer>

<script src="js/jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
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
</body>
</html>