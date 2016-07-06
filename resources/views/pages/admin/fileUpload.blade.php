@extends('layouts.app') @section('title', ' - File Upload')
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

            <form action="file-upload" method="post" enctype="multipart/form-data" role="form">
                {{ csrf_field() }}

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
</div>
@endsection
@section('opt-scripts')
<script>
    // Helper function to automatically set
    // the select fields to the file input value
    $('#file').on('change', function() {
        var filename = this.value;
        $('#pol_typeInput option').each(function() {
            var pollutionType = this.value;
            var pattern = new RegExp('^' + pollutionType + '.*', 'gi');
            if (filename.match(pattern)) {
                $(this).parent().val(pollutionType);
                return;
            }
        });
        $('#st_codeInput option').each(function() {
            var stationCode = this.value;
            var pattern = new RegExp('.*' + stationCode + '[0-9]{4}', 'gi');
            if (filename.match(pattern)) {
                $(this).parent().val(stationCode);
                return;
            }
        });
    });
</script>
@endsection