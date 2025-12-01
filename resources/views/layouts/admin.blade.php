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
    /* GLOBAL LOADING OVERLAY */
    #global-loader {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.78);
        backdrop-filter: blur(4px);
        -webkit-backdrop-filter: blur(4px);
        display: none;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        z-index: 999999;
    }

    .loader-spinner {
        width: 65px;
        height: 65px;
        border: 8px solid #ddd;
        border-top-color: #3498db;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin-bottom: 15px;
    }

    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }

    #global-loader p {
        font-size: 18px;
        font-weight: 600;
        color: #333;
    }
</style>


</head>
<!-- GLOBAL LOADING OVERLAY -->
<div id="global-loader">
    <div class="loader-spinner"></div>
    <p>Mohon tunggu... sedang diproses</p>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {

    const globalLoader = document.getElementById("global-loader");

    // TAMPILKAN LOADING SAAT SUBMIT SEMUA FORM
    document.querySelectorAll("form").forEach(form => {
        form.addEventListener("submit", function () {
            globalLoader.style.display = "flex";
        });
    });

    // TAMPILKAN LOADING SAAT KLIK TOMBOL YG REDIRECT
    const loadingButtons = document.querySelectorAll(
        "button[type=submit], a.btn, .btn"
    );

    loadingButtons.forEach(btn => {
        btn.addEventListener("click", function(e) {

            // Abaikan tombol swal, toggle, sidebar, dll
            if (
                btn.classList.contains("swal2-confirm") ||
                btn.classList.contains("swal2-cancel") ||
                btn.closest(".sidebar") ||
                btn.closest(".navbar")
            ) return;

            // Abaikan tombol detail yang pakai target blank
            if (btn.getAttribute("target") === "_blank") return;

            // Tampilkan loader
            globalLoader.style.display = "flex";
        });
    });

    // Loader otomatis hilang setelah halaman selesai load
    window.addEventListener("load", function () {
        globalLoader.style.display = "none";
    });

});
</script>


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
