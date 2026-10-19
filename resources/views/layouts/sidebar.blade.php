@php
    $role = auth()->user()->role ?? null;
@endphp


<aside class="simutu-sidebar" id="simutuSidebar">


    {{-- BRAND --}}
    <div class="sidebar-brand">

        <div class="brand-logo">

            <img src="{{ asset('assets/images/logosgn.png') }}"
                 alt="Logo PT Sinergi Gula Nusantara">

        </div>


        <div class="brand-text">

            <strong>
                SIMUTU
            </strong>

            <span>
                TMA
            </span>

        </div>


        <button type="button"
                class="sidebar-close"
                id="sidebarClose"
                aria-label="Tutup menu">

            <i class="bi bi-x-lg"></i>

        </button>

    </div>



    {{-- SYSTEM INFO --}}
    <div class="sidebar-system-label">

        <span>
            SISTEM INFORMASI
        </span>

        <div class="system-status">

            <span class="system-status-dot"></span>

            <span>
                Sistem Aktif
            </span>

        </div>

    </div>



    {{-- NAVIGATION --}}
    <nav class="sidebar-navigation">


        {{-- DASHBOARD --}}
        <a href="{{ route('dashboard') }}"
           class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">

            <span class="sidebar-icon">

                <i class="bi bi-grid-1x2-fill"></i>

            </span>

            <span class="sidebar-link-text">
                Dashboard
            </span>

        </a>



        {{-- ========================= --}}
        {{-- ADMIN TMA --}}
        {{-- ========================= --}}

        @if(in_array($role, ['admin', 'administrator', 'admin_tma']))

            <div class="sidebar-section">

                <span>
                    DATA & PROSES
                </span>

            </div>


            {{-- IMPORT PRODIS --}}
            <a href="{{ route('import.index') }}"
               class="sidebar-link {{ request()->routeIs('import.*') ? 'active' : '' }}">

                <span class="sidebar-icon">

                    <i class="bi bi-file-earmark-spreadsheet-fill"></i>

                </span>

                <span class="sidebar-link-text">
                    Import Prodis
                </span>

            </a>


            {{-- MUTU D --}}
            <a href="{{ route('mutu-d.index') }}"
               class="sidebar-link {{ request()->routeIs('mutu-d.*') ? 'active' : '' }}">

                <span class="sidebar-icon">

                    <i class="bi bi-clipboard2-pulse-fill"></i>

                </span>

                <span class="sidebar-link-text">
                    Data Mutu D
                </span>

            </a>


            {{-- PTA --}}
            <a href="{{ route('pta.index') }}"
               class="sidebar-link {{ request()->routeIs('pta.*') ? 'active' : '' }}">

                <span class="sidebar-icon">

                    <i class="bi bi-person-badge-fill"></i>

                </span>

                <span class="sidebar-link-text">
                    Data PTA
                </span>

            </a>



            {{-- PEMEBERITAHUAN --}}
            <div class="sidebar-section">

                <span>
                    KOMUNIKASI & MONITORING
                </span>

            </div>


            {{-- PEMBERITAHUAN --}}
            <a href="{{ route('pemberitahuan.index') }}"
               class="sidebar-link {{ request()->routeIs('pemberitahuan.*') ? 'active' : '' }}">

                <span class="sidebar-icon">

                    <i class="bi bi-megaphone-fill"></i>

                </span>

                <span class="sidebar-link-text">
                    Pemberitahuan
                </span>

            </a>


            {{-- MONITORING --}}
            <a href="{{ route('monitoring.index') }}"
               class="sidebar-link {{ request()->routeIs('monitoring.*') ? 'active' : '' }}">

                <span class="sidebar-icon">

                    <i class="bi bi-bar-chart-fill"></i>

                </span>

                <span class="sidebar-link-text">
                    Monitoring
                </span>

            </a>

        @endif



        {{-- ========================= --}}
        {{-- ASMAN TMA --}}
        {{-- ========================= --}}

        @if($role === 'asman_tma')

            <div class="sidebar-section">

                <span>
                    MONITORING TMA
                </span>

            </div>


            {{-- MUTU D --}}
            <a href="{{ route('mutu-d.index') }}"
               class="sidebar-link {{ request()->routeIs('mutu-d.*') ? 'active' : '' }}">

                <span class="sidebar-icon">

                    <i class="bi bi-clipboard2-pulse-fill"></i>

                </span>

                <span class="sidebar-link-text">
                    Data Mutu D
                </span>

            </a>


            {{-- PEMBERITAHUAN --}}
            <a href="{{ route('pemberitahuan.index') }}"
               class="sidebar-link {{ request()->routeIs('pemberitahuan.*') ? 'active' : '' }}">

                <span class="sidebar-icon">

                    <i class="bi bi-megaphone-fill"></i>

                </span>

                <span class="sidebar-link-text">
                    Pemberitahuan
                </span>

            </a>


            {{-- MONITORING --}}
            <a href="{{ route('monitoring.index') }}"
               class="sidebar-link {{ request()->routeIs('monitoring.*') ? 'active' : '' }}">

                <span class="sidebar-icon">

                    <i class="bi bi-bar-chart-fill"></i>

                </span>

                <span class="sidebar-link-text">
                    Monitoring
                </span>

            </a>

        @endif

    </nav>



    {{-- SIDEBAR BOTTOM --}}
    <div class="sidebar-bottom">


        {{-- User Mini Profile --}}
        <div class="sidebar-user-mini">


            <div class="user-mini-avatar">

                {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}

            </div>


            <div class="user-mini-info">

                <strong>
                    {{ auth()->user()->name ?? 'User' }}
                </strong>

                <span>
                    {{ ucfirst(str_replace('_', ' ', $role ?? 'user')) }}
                </span>

            </div>


            <div class="user-online-indicator"></div>

        </div>


        {{-- SGN Footer --}}
        <div class="sidebar-footer-brand">

            <span>
                PT Sinergi Gula Nusantara
            </span>

            <small>
                SIMUTU-TMA
            </small>

        </div>

    </div>

</aside>



{{-- MOBILE OVERLAY --}}
<div class="sidebar-overlay"
     id="sidebarOverlay">
</div>