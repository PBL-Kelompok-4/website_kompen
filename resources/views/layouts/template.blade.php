<!DOCTYPE html>
<html lang="en">
<head>

    <style>

        .sidebar,
        .main-sidebar,
        .main-sidebar .brand-link,
        .main-sidebar .nav-link {
            background-color: #1a2b48 !important;
            color: #ffffff !important;
        }

        .sidebar .nav-link.active {
            background-color: #3A6D8C !important;
            /* slightly darker for active items */
            color: #ffffff !important;
        }

        .sidebar a,
        .sidebar i,
        .sidebar p {
            color: #ffffff !important;
        }


    </style>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'JTI KOMPEN') }}</title>
    <link rel="icon" href="{{ url('/') }}/image/jti_polinema.png" type="image/x-icon">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- untuk mengirimkan token Laravel CSRF pada setiap request ajax -->

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ url('/') }}/plugins/fontawesome-free/css/all.min.css">
    {{-- DataTables --}}
    <link rel="stylesheet" href="{{ url('/') }}/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="{{ url('/') }}/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="{{ url('/') }}/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <link rel="stylesheet" href="{{ url('/') }}/plugins/fontawesome-free-6.6.0-web/css/all.min.css">
    {{-- <link rel="stylesheet" href="{{ url('/') }}/plugins/bootstrap-icons.min.css"> --}}
    <!-- Font Awesome 6.6.0 -->
    <script src="https://kit.fontawesome.com/7bd5045ab4.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="{{ url('/') }}/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ url('/') }}/plugins/adminlte.min.css">
    <!-- css tambahan -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    {{-- <link rel="stylesheet" href="../resources/css/style.css"> --}}

    @stack('css') <!-- Digunakan untuk memanggil custom css dari perintah push('css') pada masing-masing view -->
</head>

<body class="hold-transition sidebar-mini">
    <!-- Site wrapper -->
    <div class="wrapper">
        <!-- Navbar -->
        @include('layouts.header')
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Sidebar -->
            @if (auth()->user()->level->kode_level == "ADM")
                @include('layouts.sidebar_admin')
            @elseif (auth()->user()->level->kode_level == "DSN" || auth()->user()->level->kode_level == "TDK")
                @include('layouts.sidebar_dosen_tendik')
            @elseif (auth()->user()->level->kode_level == "MHS")
                @include('layouts.sidebar_mahasiswa')
            @endif
            <!-- /.sidebar -->
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            @include('layouts.breadcrumb')

            <!-- Main content -->
            <section class="content">
                @yield('content')
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        @include('layouts.footer')
    </div>
    <!-- ./wrapper -->

    <!-- jQuery -->
    <script src="{{ url('/') }}/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ url('/') }}/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    {{-- DataTables & Plugins --}}
    <script src="{{ url('/') }}/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="{{ url('/') }}/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="{{ url('/') }}/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="{{ url('/') }}/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
    <script src="{{ url('/') }}/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
    <script src="{{ url('/') }}/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
    <script src="{{ url('/') }}/plugins/jszip/jszip.min.js"></script>
    <script src="{{ url('/') }}/plugins/pdfmake/pdfmake.min.js"></script>
    <script src="{{ url('/') }}/plugins/pdfmake/vfs_fonts.js"></script>
    <script src="{{ url('/') }}/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
    <script src="{{ url('/') }}/plugins/datatables-buttons/js/buttons.print.min.js"></script>
    <script src="{{ url('/') }}/plugins/datatables-buttons/js/buttons.colvis.min.js"></script>

    <!-- jquery-validation -->
    <script src="{{ url('/') }}/plugins/jquery-validation/jquery.validate.min.js"></script>
    <script src="{{ url('/') }}/plugins/jquery-validation/additional-methods.min.js"></script>

    <!-- SweetAlert2 -->
    <script src="{{ url('/') }}/plugins/sweetalert2/sweetalert2.min.js"></script>
    {{-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> --}}
    <!-- AdminLTE App -->
    <script src="{{ url('/') }}/plugins/adminlte.min.js"></script>
    <script>
        // untuk mengirikan token laravel CSRF pada setiap request ajax
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
    @stack('js') <!-- Digunakan untuk memanggil custom js dari perintah push('js') pada masing-masing view -->
    {{-- <!-- AdminLTE for demo purposes -->
<script src="{{ asset('adminlte/dist/js/demo.js') }}"></script> --}}
</body>

</html>
