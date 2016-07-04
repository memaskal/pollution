@extends('layouts.app') @section('title', ' - Station Insert')

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
<script src="{{ asset('/js/admin_mapInsert.js') }}"></script>
<script async defer
        src="https://maps.googleapis.com/maps/api/js?key={{ $gmap_key }}&libraries=visualization&callback=initMap">
</script>
@endsection