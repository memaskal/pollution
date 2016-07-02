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
            height: 100%;
            margin: 0;
            /* Required padding for .navbar-fixed-top. Remove if using .navbar-static-top. Change if height of navigation changes. */
            padding-top: 70px;
        }

        #map_canvas {
            height: 600px;
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
<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">Envioromental Monitoring</a>
        </div>
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li>
                    <a href="#" id="showAbsValue">Absolute pollution values</a>
                </li>
                <li>
                    <a href="#" id="showAvgValue">Average pollution values</a>
                </li>
            </ul>
        </div>
        <!-- /.navbar-collapse -->
    </div>
    <!-- /.container -->
</nav>

<!-- Page Content -->
<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <div id="map_canvas"></div>
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
<script src="js/main.js"></script>
<script async defer
        src="https://maps.googleapis.com/maps/api/js?key={{ $gmap_key }}&libraries=visualization&callback=initMap">
</script>
</body>
</html>