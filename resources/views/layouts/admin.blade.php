<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield('title', config('app.name'))</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    @yield('css')

    <!-- Custom Background -->
    <style>
        body {
            background-color: #E5E5E5 !important;
        }
        .content-wrapper {
            background-color: #E5E5E5 !important;
        }
        .card, .table {
            background-color: #ffffff;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }
        .alert {
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }
    </style>
</head>

<body class="hold-transition sidebar-mini">

    <div class="wrapper">
        @include('layouts.partials.navbar')
        @include('layouts.partials.sidebar')

        <div class="content-wrapper">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>@yield('content-header')</h1>
                        </div>
                        <div class="col-sm-6 text-right">
                            @yield('content-actions')
                        </div>
                    </div>
                </div>
            </section>

            <section class="content">
                @include('layouts.partials.alert.success')
                @include('layouts.partials.alert.error')
                @yield('content')
            </section>
        </div>

        @include('layouts.partials.footer')
    </div>

    @yield('js')
    @yield('model')

    <!-- ========================================== -->
    <!-- ✅ SWEETALERT2 (MODERN DELETE CONFIRMATION) -->
    <!-- ========================================== -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {

            const deleteForms = document.querySelectorAll(".delete-form");

            deleteForms.forEach(form => {
                form.addEventListener("submit", function (e) {
                    e.preventDefault();

                    Swal.fire({
                        title: "Hapus Produk?",
                        text: "Apa kamu yakin ingin menghapus produk ini?",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonText: "Ya, Hapus",
                        cancelButtonText: "Batal",
                        confirmButtonColor: "#d33",
                        cancelButtonColor: "#3085d6",
                    }).then((result) => {
                        if (result.isConfirmed) form.submit();
                    });
                });
            });

        });
    </script>


</body>
</html>
