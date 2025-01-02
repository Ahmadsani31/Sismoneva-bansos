<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Favicon icon-->
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('/') }}assets/images/favicon/favicon.ico">
    <title>{{ $pageTitle ?? 'Page' }} | Sistem Monitoring dan Evaluasi Program Bantuan Sosial</title>
    <!-- Libs CSS -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="base-url" content="{{ url('/') }}">

    <link href="{{ asset('/') }}assets/libs/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('/') }}assets/libs/dropzone/dist/dropzone.css" rel="stylesheet">
    <link href="{{ asset('/') }}assets/libs/@mdi/font/css/materialdesignicons.min.css" rel="stylesheet" />
    <link href="{{ asset('/') }}assets/libs/prismjs/themes/prism-okaidia.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.2.0/css/buttons.dataTables.css">
    <style>
        .text-primary {
            color: #624bff;
        }

        .text-secondary {
            color: #637381;
        }

        .text-success {
            color: #198754;
        }

        .text-warning {
            color: #f59e0b;
        }

        .text-info {
            color: #0ea5e9;
        }

        .text-dark {
            color: #212b36;
        }


        .text-danger {
            color: #dc3545;
        }

        .select2-container .select2-selection--single {
            box-sizing: border-box;
            cursor: pointer;
            display: block;
            height: 40px;
            user-select: none;
            -webkit-user-select: none;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #444;
            line-height: 39px;
        }

        .select2-container .select2-selection--single .select2-selection__rendered {
            display: block;
            padding-left: 17px;
            padding-right: 20px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 39px;
            position: absolute;
            top: 1px;
            right: 1px;
            width: 20px;
        }
    </style>
    <!-- Theme CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-light">
    <div id="page-pre-loader" style="display: none">
        <div id="loader-circle"></div>
    </div>
    <div id="db-wrapper">
        <!-- navbar vertical -->
        <!-- Sidebar -->
        <x-sidebar-layout />

        <!-- Page content -->
        <div id="page-content">
            <div class="header @@classList">
                <!-- navbar -->
                <x-navbar-layout />

            </div>
            <!-- Container fluid -->
            {{-- {{ $slot }} --}}
            @yield('content')
        </div>
    </div>
    <x-modal />


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
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.js"></script>


    <script src="https://cdn.datatables.net/buttons/3.2.0/js/dataTables.buttons.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.2.0/js/buttons.dataTables.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.2.0/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.2.0/js/buttons.print.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    @stack('scripts')

</body>

</html>
