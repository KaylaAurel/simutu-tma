<header class="simutu-navbar">

    {{-- LEFT --}}
    <div class="navbar-left">

        {{-- Mobile Menu --}}
        <button type="button"
                class="mobile-menu-button"
                id="sidebarToggle"
                aria-label="Buka menu">

            <i class="bi bi-list"></i>

        </button>


        {{-- Page Identity --}}
        <div class="navbar-page">

            <div class="navbar-page-meta">

                <span class="navbar-page-brand">
                    SIMUTU
                </span>

                <span class="navbar-page-divider"></span>

                <span class="navbar-page-section">
                    TMA
                </span>

            </div>

            <h1>
                @yield('page-title', 'Dashboard')
            </h1>

        </div>

    </div>



    {{-- RIGHT --}}
    <div class="navbar-right">


        {{-- Notification --}}
        <a href="{{ route('pemberitahuan.index') }}"
           class="navbar-icon-button navbar-notification"
           title="Pemberitahuan"
           aria-label="Pemberitahuan">

            <i class="bi bi-bell"></i>

            @if(isset($unreadNotifications) && $unreadNotifications > 0)

                <span class="notification-dot">

                    {{ $unreadNotifications > 9 ? '9+' : $unreadNotifications }}

                </span>

            @endif

        </a>



        {{-- Vertical Divider --}}
        <div class="navbar-divider"></div>



        {{-- User --}}
        <div class="navbar-user">

            <div class="navbar-avatar">

                {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}

            </div>


            <div class="navbar-user-info">

                <strong>
                    {{ auth()->user()->name ?? 'User' }}
                </strong>

                <span>
                    {{ ucfirst(str_replace('_', ' ', auth()->user()->role ?? 'user')) }}
                </span>

            </div>


            <i class="bi bi-chevron-down navbar-user-chevron"></i>

        </div>



        {{-- Logout --}}
        <form action="{{ route('logout') }}"
              method="POST"
              class="navbar-logout-form">

            @csrf

            <button type="submit"
                    class="navbar-logout"
                    title="Keluar"
                    aria-label="Keluar dari sistem">

                <i class="bi bi-box-arrow-right"></i>

            </button>

        </form>

    </div>

</header>