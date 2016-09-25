@extends('layouts.app') @section('title', '- Home')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h3> Welcome {{ $user->name }} </h3>
            <hr>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <h4>API usage for your key: {{ $user->api_token }} </h4>
            <div id="req_types" style="height:250px"> </div>
        </div>
    </div>
</div>
@endsection
@section('opt-scripts')
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">
<script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>
<script>
    var donutChart;

    function drawDonutChart( contents ) {

        if ( contents.length == 0 ) {
            $('#req_types').html('<p>No data available :(</p>');
        }

        var data = [];
        $.each(contents, function( index, value ) {
            data.push({ label : value.description,
                value : value.requests
            });
        });
        donutChart = Morris.Donut({
            element: 'req_types',
            data: data
        });
    }
    $(document).ready(drawDonutChart({!! json_encode($stats) !!}));
</script>
@endsection