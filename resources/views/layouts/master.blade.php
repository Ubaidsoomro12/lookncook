<!DOCTYPE html>
<html lang="en">

<head>

    @include('partials.head')

</head>

<body>

    <!-- NAVBAR -->

    @include('partials.navbar')

    <!-- PAGE CONTENT -->

    @yield('content')

    <!-- FOOTER -->

    @include('partials.footer')
    @include('components.review_form')

    <!-- SCRIPTS -->

    @include('partials.scripts')

</body>

</html>