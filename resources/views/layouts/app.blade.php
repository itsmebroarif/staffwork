<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'StaffWork Dashboard')</title>

    <!-- Custom fonts for this template-->
    <link href="{{ asset('template/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">

    <!-- Google Fonts dimuat async: tidak memblokir render halaman -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet" media="print" onload="this.media='all'">
    <noscript>
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
            rel="stylesheet">
    </noscript>

    <!-- Custom styles for this template-->
    <link href="{{ asset('template/css/sb-admin-2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('template/css/app-ui.css') }}" rel="stylesheet">

    <!-- Animasi saat konten muncul (AOS.js) -->
    {{-- <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet"> --}}

    <meta name="theme-color" content="#4e73df">

    <!-- Independent scroll layout: sidebar scroll sendiri, navbar + konten scroll bersama -->
    <style>
        html,
        body {
            height: 100%;
        }

        body {
            overflow: hidden;
        }

        #wrapper {
            height: 100vh;
            overflow: hidden;
        }

        .sidebar {
            height: 100vh;
            flex-shrink: 0;
            overflow-y: auto;
        }

        /* Mode ikon (sidebar ditutup): flyout menu absolute harus tetap terlihat */
        .sidebar.toggled {
            overflow: visible;
        }

        #content-wrapper {
            height: 100vh;
            min-width: 0;
            display: flex;
            flex-direction: column;
        }

        /* Navbar dan konten berada di satu area scroll; footer tetap menempel di bawah */
        #content {
            flex: 1 1 auto;
            min-height: 0;
            overflow-y: auto;
            overflow-x: hidden;
        }

        /* Scrollbar tipis & rapi */
        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: transparent;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, .25);
            border-radius: 3px;
        }

        .sidebar::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, .4);
        }

        #content::-webkit-scrollbar {
            width: 8px;
        }

        #content::-webkit-scrollbar-track {
            background: #f1f2f6;
        }

        #content::-webkit-scrollbar-thumb {
            background: #c3c6d1;
            border-radius: 4px;
        }

        #content::-webkit-scrollbar-thumb:hover {
            background: #a9adb9;
        }

        /* Layar kecil: kembali ke perilaku scroll halaman normal */
        @media (max-width: 768px) {
            html,
            body {
                height: auto;
            }

            body {
                overflow: visible;
                overflow-x: hidden;
            }

            #wrapper {
                height: auto;
                overflow: visible;
            }

            .sidebar {
                height: auto;
                min-height: 100vh;
                overflow: visible;
            }

            #content-wrapper {
                height: auto;
                display: block;
            }

            #content {
                overflow: visible;
            }
        }
    </style>
    @stack('styles')
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        @include('components.sidebar')
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                @include('components.navbar')
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    @include('components.simulation-banner')

                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    @yield('content')
                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Hak Cipta &copy; StaffWork {{ date('Y') }}</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Navigasi bawah (mobile) -->
    @include('components.mobile-nav')

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="logoutModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="logoutModalLabel">Konfirmasi Keluar</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Pilih tombol "Keluar" di bawah jika Anda ingin mengakhiri sesi akun saat ini.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-primary">Keluar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- SweetAlert2 & AOS (CDN) -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    {{-- <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script> --}}

    <!-- Bootstrap core JavaScript-->
    <script src="{{ asset('template/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('template/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Core plugin JavaScript-->
    <script src="{{ asset('template/vendor/jquery-easing/jquery.easing.min.js') }}"></script>

    <!-- Custom scripts for all pages-->
    <script src="{{ asset('template/js/sb-admin-2.min.js') }}"></script>
    <script>
        // Konfirmasi aksi memakai SweetAlert2 (fallback ke confirm bawaan bila CDN gagal).
        function askConfirm(message, onConfirm) {
            if (typeof Swal === 'undefined') {
                if (window.confirm(message)) {
                    onConfirm();
                }

                return;
            }

            Swal.fire({
                title: 'Konfirmasi',
                text: message,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, lanjutkan',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                reverseButtons: true
            }).then(function (result) {
                if (result.isConfirmed) {
                    onConfirm();
                }
            });
        }

        document.addEventListener('submit', function (event) {
            var form = event.target;
            var message = form.getAttribute('data-confirm');

            if (!message || form.dataset.confirmed === '1') {
                return;
            }

            event.preventDefault();

            askConfirm(message, function () {
                form.dataset.confirmed = '1';
                form.submit();
            });
        });

        // Tombol submit yang membawa data-confirm sendiri (mis. hapus permanen).
        document.addEventListener('click', function (event) {
            var trigger = event.target.closest('[data-confirm]');

            if (!trigger || trigger.tagName === 'FORM') {
                return;
            }

            var form = trigger.closest('form');

            if (!form || form.dataset.confirmed === '1') {
                return;
            }

            event.preventDefault();

            askConfirm(trigger.getAttribute('data-confirm'), function () {
                form.dataset.confirmed = '1';
                form.submit();
            });
        });

        // AOS: area scroll utama berada di #content, jadi posisi animasi perlu disegarkan.
        document.addEventListener('DOMContentLoaded', function () {
            if (typeof AOS === 'undefined') {
                return;
            }

            AOS.init({
                duration: 600,
                easing: 'ease-out-cubic',
                once: true,
                offset: 60,
                disable: window.matchMedia('(prefers-reduced-motion: reduce)').matches
            });

            var contentArea = document.getElementById('content');

            if (contentArea) {
                var refreshTimer;

                contentArea.addEventListener('scroll', function () {
                    clearTimeout(refreshTimer);
                    refreshTimer = setTimeout(function () {
                        AOS.refresh();
                    }, 150);
                });
            }
        });

        // Area scroll utama ada di #content, jadi tombol "ke atas" diarahkan ke sana.
        $(document).on('click', '.scroll-to-top', function (event) {
            event.preventDefault();

            var content = document.getElementById('content');

            if (content) {
                content.scrollTo({ top: 0, behavior: 'smooth' });
            }

            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    </script>
    @stack('scripts')
</body>

</html>

