<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Favicon icon-->
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('/') }}assets/images/favicon/favicon.ico">
    <!-- Libs CSS -->
    <link href="{{ asset('/') }}assets/libs/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('/') }}assets/libs/dropzone/dist/dropzone.css" rel="stylesheet">
    <link href="{{ asset('/') }}assets/libs/@mdi/font/css/materialdesignicons.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.0/css/all.min.css"
        integrity="sha512-9xKTRVabjVeZmc+GUW8GgSmcREDunMM+Dt/GrzchfN8tkwHizc5RP4Ok/MXFFy5rIjJjzhndFScTceq5e6GvVQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.0/css/fontawesome.min.css"
        integrity="sha512-OYtfQLIh44db+qhIErkWKrmij09a8CNwItEH4yiRIY0gZhRj2sKNIIsRrvEPdqMbgzT1opjSAJXsteTfxuJjOQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Theme CSS -->
    <title>{{ $pageTitle ?? 'Page' }} | PT Wiratama</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-light">
    <!-- container -->
    <div class="container d-flex flex-column">
        {{-- {{ $slot }} --}}
        @yield('content')
    </div>
    <!-- Scripts -->
    <!-- Libs JS -->
    <script src="{{ asset('/') }}assets/libs/jquery/dist/jquery.min.js"></script>
    <script src="{{ asset('/') }}assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('/') }}assets/libs/jquery-slimscroll/jquery.slimscroll.min.js"></script>
    <script src="{{ asset('/') }}assets/libs/feather-icons/dist/feather.min.js"></script>
    <script src="{{ asset('/') }}assets/libs/prismjs/prism.js"></script>
    <script src="{{ asset('/') }}assets/libs/apexcharts/dist/apexcharts.min.js"></script>
    <script src="{{ asset('/') }}assets/libs/dropzone/dist/min/dropzone.min.js"></script>
    <script src="{{ asset('/') }}assets/libs/prismjs/plugins/toolbar/prism-toolbar.min.js"></script>
    <script src="{{ asset('/') }}assets/libs/prismjs/plugins/copy-to-clipboard/prism-copy-to-clipboard.min.js"></script>

</body>

</html>
