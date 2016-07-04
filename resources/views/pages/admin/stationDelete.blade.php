@extends('layouts.admin') @section('title', 'Station Delete')

@section('content')
<style>
    #table td { cursor: pointer; cursor: hand; }
</style>

<div class="container">
    <div class="row">
        <div class="col-md-4">
        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @elseif(count($errors) == 0)
            <div class="alert alert-warning alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <strong>Warning!</strong> Deleting a station will result in deletion of every
                measurement at that station. Proceed with conscious!!!
            </div>
        @else
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
        @if (count($stations) > 0)
            <h3>Measurement Stations</h3>
            <table id="table" class="table table-hover">
                <tr><th>ID</th><th>Name</th><th>Lat</th><th>Lng</th></tr>
                @foreach($stations as $station)
                    <tr><td>{{ $station->id }}</td><td>{{ $station->name }}</td><td>{{ $station->latitude }}</td><td>{{ $station->longitude }}</td></tr>
                @endforeach
            </table>
        @else
            No entries to show :'(
        @endif
        <form action="station-delete" method="post" role="form" id="del_form">
            {{ csrf_field() }}
            <input type="hidden" name="_method" value="DELETE">
            <input type="hidden" name="st_code" value="" id="st_code">
        </form>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
        {!! $stations->render() !!}
        </div>
    </div>
</div>
<!-- Verify Modal goes here -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Danger</h4>
            </div>
            <div class="modal-body">
                Are you sure you want to delete station "<span id="dialog-st-name"></span>"?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" id="verify-submit" class="btn btn-danger">Delete station</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('opt-scripts')
<script>
$(document).ready(function(){
    $('#table').find('tr').click( function(){
        var id = $(this).find('td:first').text();
        var name = $(this).find('td:nth-child(2)').text();
        if (id !== '') {
            $('#st_code').val(id);
            $('#dialog-st-name').html(name);
            $('#myModal').modal();
        }
    });

    $('#verify-submit').click(function() {
        $('#del_form').submit();
    })

});
</script>
@endsection