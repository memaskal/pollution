<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <link rel="shortcut icon" type="image/png" href="/favicon.png"/>
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
        <div class="col-lg-4">

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
                    <input type="file" id="file" name="file">
                </div>

                <input type="submit" class="btn btn-info pull-right" value="Upload">
            </form>

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
</body>
</html>