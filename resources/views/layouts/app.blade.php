<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <meta name="csrf-token"
          content="{{ csrf_token() }}">

    <title>
        @yield('title', 'Dashboard') | SIMUTU-TMA
    </title>

    <meta name="description"
          content="Sistem Monitoring Mutu Tebu - TMA">

    {{-- =========================================================
        VITE
        Mengikuti vite.config.js:
        resources/css/app.css
        resources/js/app.js
    ========================================================== --}}
    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])

    {{-- =========================================================
        BOOTSTRAP ICONS
    ========================================================== --}}
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    @stack('styles')
</head>


<body class="simutu-body">

    <div class="simutu-wrapper">

        {{-- =====================================================
            SIDEBAR
        ====================================================== --}}
        @include('layouts.sidebar')


        {{-- =====================================================
            MAIN AREA
        ====================================================== --}}
        <div class="simutu-main">

            {{-- =================================================
                NAVBAR
            ================================================== --}}
            @include('layouts.navbar')


            {{-- =================================================
                CONTENT
            ================================================== --}}
            <main class="simutu-content">

                {{-- =============================================
                    FLASH SUCCESS
                ============================================== --}}
                @if(session('success'))

                    <div class="simutu-alert simutu-alert-success">

                        <div class="alert-icon">
                            <i class="bi bi-check-circle-fill"></i>
                        </div>

                        <div class="alert-content">

                            <strong>Berhasil</strong>

                            <p>
                                {{ session('success') }}
                            </p>

                        </div>

                        <button type="button"
                                class="alert-close"
                                onclick="this.parentElement.remove()"
                                aria-label="Tutup notifikasi">

                            <i class="bi bi-x-lg"></i>

                        </button>

                    </div>

                @endif


                {{-- =============================================
                    FLASH ERROR
                ============================================== --}}
                @if(session('error'))

                    <div class="simutu-alert simutu-alert-danger">

                        <div class="alert-icon">
                            <i class="bi bi-exclamation-circle-fill"></i>
                        </div>

                        <div class="alert-content">

                            <strong>Terjadi Kesalahan</strong>

                            <p>
                                {{ session('error') }}
                            </p>

                        </div>

                        <button type="button"
                                class="alert-close"
                                onclick="this.parentElement.remove()"
                                aria-label="Tutup notifikasi">

                            <i class="bi bi-x-lg"></i>

                        </button>

                    </div>

                @endif


                {{-- =============================================
                    VALIDATION ERRORS
                ============================================== --}}
                @if($errors->any())

                    <div class="simutu-alert simutu-alert-danger">

                        <div class="alert-icon">
                            <i class="bi bi-exclamation-triangle-fill"></i>
                        </div>

                        <div class="alert-content">

                            <strong>
                                Periksa kembali data
                            </strong>

                            <ul>

                                @foreach($errors->all() as $error)

                                    <li>
                                        {{ $error }}
                                    </li>

                                @endforeach

                            </ul>

                        </div>

                        <button type="button"
                                class="alert-close"
                                onclick="this.parentElement.remove()"
                                aria-label="Tutup notifikasi">

                            <i class="bi bi-x-lg"></i>

                        </button>

                    </div>

                @endif


                {{-- =============================================
                    PAGE CONTENT
                ============================================== --}}
                @yield('content')

            </main>


          
        </div>

    </div>


    {{-- =========================================================
        PAGE SPECIFIC SCRIPTS
    ========================================================== --}}
    @stack('scripts')

</body>

</html>