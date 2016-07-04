@extends('layouts.app') @section('title', '- Dashboard')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h3>Welcome to the admin Dashboard</h3>
                <hr>
                <p>
                    This is the admin dashboard. From this point you can manage and monitor the API 's usage. Fill free
                    to upload new files to the API 's database, or insert/delete a station. Bellow you can find some useful
                    statistics about the API usage. The contents of this page are refreshed automatically. This API counts
                    by now <span id="total_keys"> - </span> active developers.
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <h4>Requests total per type</h4>
                <div id="req_types" style="height:250px"></div>
            </div>
            <div class="col-md-6">
                <h4>Ten most used API keys</h4>
                <div id="top_ten" style="height:250px"></div>
            </div>
        </div>
    </div>
@endsection
@section('opt-scripts')
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">
<script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>
<script src="{{ asset('/js/admin_stats.js') }}"></script>
@endsection