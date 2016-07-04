@extends('layouts.home')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Welcome</div>

                <div class="panel-body">
                    Your Application's Landing Page.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('opt-scripts')
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">
<script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>
<script>

    var donutChart = undefined;
    var barChart = undefined;

    function drawDonutChart( contents ) {
        var data = [];
        $.each(contents, function( index, value ) {
            data.push({ label : value.description,
                value : value.requests
            });
        });
        if (donutChart === undefined) {
            donutChart = Morris.Donut({
                element: 'req_types',
                data: data
            });
        }
        else {
            // forces redraw
            donutChart.setData(data);
        }
    }

    function subNames (str, size) {
        var subN = str.substr(0,size);
        if (str.length > size) {
            subN += '...';
        }
        return subN;
    }

    function drawBarChart( contents ) {
        var data = [];
        $.each(contents, function( index, value ) {
            data.push({ key : subNames(value.api_token, 10),
                requests : value.total
            });
        });
        if (barChart === undefined) {
            barChart = Morris.Bar({
                element: 'top_ten',
                data: data,
                xkey: 'key',
                ykeys: ['requests'],
                labels: ['Requests'],
                barRatio: 0.4,
                xLabelAngle: 35,
                hideHover: 'auto'
            });
        }
        else {
            // forces redraw
            barChart.setData(data);
        }
    }

    function updateCounter( contents ) {
        $('#total_keys').html(contents);
    }

    function pageRefresher () {
        // fetch data
        fetchData('/admin/stats', function (data) {
            drawDonutChart(data.total_req);
            drawBarChart(data.top_ten);
            updateCounter(data.total_keys);
            setTimeout(pageRefresher, 15000);
        });
    };

    function fetchData(url, callback) {
        $.getJSON(url, function( response ) {
            if (response.status != 'OK') alert("Error " + response.status);
            callback(response);
        });
    }

    // Load on document ready
    $(document).ready(pageRefresher());
</script>
@endsection