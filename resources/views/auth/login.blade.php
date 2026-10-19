<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Login | SIMUTU-TMA</title>

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])

    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link rel="preconnect"
          href="https://fonts.gstatic.com"
          crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap"
          rel="stylesheet">

    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body class="login-body">

<div class="login-wrapper">

    {{-- =====================================================
        LEFT : BRANDING
    ====================================================== --}}

    <div class="login-brand-panel">

        <div class="login-brand-content">

            {{-- LOGO --}}
            <img
                src="{{ asset('assets/images/logosgn.png') }}"
                alt="Logo SGN"
                class="login-logo"
            >

            <h1>
                SIMUTU<span>-TMA</span>
            </h1>

            <p>
                Sistem Monitoring Mutu Tebu untuk membantu proses
                monitoring, pengelolaan, dan pemberitahuan hasil
                identifikasi Mutu D pada bagian Tebang, Muat, dan Angkut.
            </p>


           {{-- FEATURE CARDS --}}
    <div class="login-feature-list">


        {{-- FEATURE 1 --}}
        <div class="login-feature-item">

            <div class="login-feature-icon">
                <i class="bi bi-bar-chart-line-fill"></i>
            </div>

            <div class="login-feature-text">

                <strong>
                    Monitoring Mutu D
                </strong>

                <span>
                    Pantau data dan status Mutu D
                    secara terpusat.
                </span>

            </div>

        </div>


        {{-- FEATURE 2 --}}
        <div class="login-feature-item">

            <div class="login-feature-icon">
                <i class="bi bi-database-check"></i>
            </div>

            <div class="login-feature-text">

                <strong>
                    Integrasi Data Prodis
                </strong>

                <span>
                    Kelola hasil import data Prodis
                    dengan lebih terstruktur.
                </span>

            </div>

        </div>


        {{-- FEATURE 3 --}}
        <div class="login-feature-item">

            <div class="login-feature-icon whatsapp-icon">
                <i class="bi bi-whatsapp"></i>
            </div>

            <div class="login-feature-text">

                <strong>
                    Pemberitahuan WhatsApp
                </strong>

                <span>
                    Kirim pemberitahuan kepada PTA
                    melalui WhatsApp.
                </span>

            </div>

        </div>

    </div>

        </div>

    </div>


    {{-- =====================================================
        RIGHT : LOGIN FORM
    ====================================================== --}}

    <div class="login-form-panel">

        <div class="login-form-container">

            <div class="login-header">

                <div class="small-title">
                    PT Sinergi Gula Nusantara PG Ngadiredjo
                </div>

                <h2>
                    Selamat Datang
                </h2>

                <p>
                    Masuk ke SIMUTU-TMA menggunakan akun yang
                    telah terdaftar.
                </p>

            </div>


            {{-- ERROR LOGIN --}}

            @if ($errors->any())

                <div class="login-alert">

                    <i class="bi bi-exclamation-circle"></i>

                    {{ $errors->first() }}

                </div>

            @endif


            {{-- FORM LOGIN --}}

            <form method="POST"
                  action="{{ route('login.process') }}">

                @csrf


                {{-- USERNAME --}}

                <div class="login-form-group">

                    <label
                        for="username"
                        class="login-form-label"
                    >
                        Username
                    </label>

                    <div class="login-input-wrapper">

                        <i class="bi bi-person login-input-icon"></i>

                        <input
                            type="text"
                            id="username"
                            name="username"
                            class="login-input"
                            placeholder="Masukkan username"
                            value="{{ old('username') }}"
                            autocomplete="username"
                            required
                        >

                    </div>

                </div>


                {{-- PASSWORD --}}

                <div class="login-form-group">

                    <label
                        for="password"
                        class="login-form-label"
                    >
                        Password
                    </label>

                    <div class="login-input-wrapper">

                        <i class="bi bi-lock login-input-icon"></i>

                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="login-input"
                            placeholder="Masukkan password"
                            autocomplete="current-password"
                            required
                        >

                        <button
                            type="button"
                            class="password-toggle"
                            onclick="togglePassword()"
                            aria-label="Tampilkan password"
                        >
                            <i
                                class="bi bi-eye"
                                id="passwordIcon"
                            ></i>
                        </button>

                    </div>

                </div>


                {{-- OPTIONS --}}

                <div class="login-options">

                    <label class="remember-me">

                        <input
                            type="checkbox"
                            name="remember"
                            value="1"
                        >

                        <span>
                            Ingat saya
                        </span>

                    </label>

                </div>


                {{-- BUTTON --}}

                <button
                    type="submit"
                    class="login-button"
                >

                    <span>
                        Masuk ke Sistem
                    </span>

                    <i class="bi bi-arrow-right"></i>

                </button>

            </form>


            {{-- FOOTER --}}

            <div class="login-footer">

                SIMUTU-TMA &bull;

                <strong>
                    PT Sinergi Gula Nusantara PG Ngadiredjo
                </strong>

            </div>

        </div>

    </div>

</div>


<script>

function togglePassword() {

    const password =
        document.getElementById('password');

    const icon =
        document.getElementById('passwordIcon');

    if (password.type === 'password') {

        password.type = 'text';

        icon.classList.remove('bi-eye');

        icon.classList.add('bi-eye-slash');

    } else {

        password.type = 'password';

        icon.classList.remove('bi-eye-slash');

        icon.classList.add('bi-eye');

    }

}

</script>

</body>

</html>