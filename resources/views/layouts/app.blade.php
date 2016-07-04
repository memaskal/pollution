<!DOCTYPE html>
<html lang="en">
<head>
@include('includes.head')
<!-- Bootstrap Core CSS -->
<link href="{{ asset('/css/bootstrap.min.css') }}" rel="stylesheet">
</head>
<body>
<!-- Navigation -->
@if (Request::is('admin*'))
    @include('includes.nav_admin')
@elseif (Request::is('demo*'))
    @include('includes.nav_demo')
@else
    @include('includes.nav_home')
@endif

<!-- Page Content -->
@yield('content')

<!-- Page Footer -->
<footer id="footer">
@include('includes.footer')
</footer>

<!-- Script Section -->
<script src="{{ asset('js/jquery.js') }}"></script>
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
@yield('opt-scripts', '')
</body>
</html>