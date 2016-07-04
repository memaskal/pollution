<!DOCTYPE html>
<html lang="en">
<head>
@include('includes.head')
<!-- Bootstrap Core CSS -->
<link href="{{ asset('/css/bootstrap.min.css') }}" rel="stylesheet">
</head>
<body>
<!-- Navigation -->
@include('includes.nav_demo')

<!-- Page Content -->
@yield('content')

<!-- Page Footer -->
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

<!-- Script Section -->
<script src="{{ asset('js/jquery.js') }}"></script>
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
@yield('opt-scripts', '')
</body>
</html>