@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div id="map_canvas" style="height:600px;"></div>
        </div>
    </div>
</div>

<!-- Modal -->
<div id="modalw" class="modal fade" role="dialog">
<div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Insert search data</h4>
        </div>
        <form id="modal-body" class="modal-body">
            <!-- Forms body goes here -->
        </form>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button id="submit-modal" class="btn btn-primary">Make request</button>
        </div>
    </div>
</div>
</div>

<!-- Forms for the api calls are here -->
<div id="req_2" style="display:none">
    <div class="form-group">
        <label for="hourInput">Hour input (24h system)</label>
        <input type="number" name="hour" class="form-control" id="hourInput" min="1" max="24">
    </div>
    <div class="form-group">
        <label for="dateInput">Date input</label>
        <input type="date" name="date" class="form-control" id="dateInput">
    </div>

    <div class="form-group">
        <label for="st_codeInput">Station Code</label>
        <select class="form-control" name="st_code" id="st_codeInput">
            <option selected value="">-</option>
        </select>
    </div>

    <div class="form-group">
        <label for="pol_typeInput">Pollution Type</label>
        <select class="form-control" name="pol_type" id="pol_typeInput">
        @foreach($pol_types as $pt)
            <option value="{{ $pt }}">{{ $pt }}</option>
        @endforeach
        </select>
    </div>
</div>

<div id="req_3" style="display:none">
    <div class="form-group">
        <label for="sdateInput">Starting Day</label>
        <input type="date" name="sdate" class="form-control" id="sdateInput">
    </div>

    <div class="form-group">
        <label for="fdateInput">Final Day</label>
        <input type="date" name="fdate" class="form-control" id="fdateInput">
    </div>

    <div class="form-group">
        <label for="st_codeInput">Station Code</label>
        <select class="form-control" name="st_code" id="st_codeInput">
            <option selected value="">-</option>
        </select>
    </div>

    <div class="form-group">
        <label for="pol_typeInput">Pollution Type</label>
        <select class="form-control" name="pol_type" id="pol_typeInput">
        @foreach($pol_types as $pt)
            <option value="{{ $pt }}">{{ $pt }}</option>
        @endforeach
        </select>
    </div>
</div>
<!-- Forms for the api calls end here -->
@endsection

@section('opt-scripts')
<script src="{{ asset('/js/demo.js') }}"></script>
<script async defer
        src="https://maps.googleapis.com/maps/api/js?key={{ $gmap_key }}&libraries=visualization&callback=initMap">
</script>
@endsection

{{-- TODO: Kanonikopo;ihsh metrhsewn opws leei sto forum --}}