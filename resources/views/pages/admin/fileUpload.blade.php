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
</div>
@endsection