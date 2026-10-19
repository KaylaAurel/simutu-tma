@extends('layouts.app')

@section('title', 'Dashboard Admin TMA')

@section('content')

<div class="dashboard-page">

    {{-- =====================================================
        HEADER
    ====================================================== --}}
    <div class="dashboard-header">

        <div class="dashboard-header-left">

            <div class="breadcrumb">
                <span>SIMUTU-TMA</span>
                <i class="bi bi-chevron-right"></i>
                <strong>Dashboard</strong>
            </div>

            <h1>
                Selamat datang,
                <span>{{ Auth::user()->name }}</span> 👋
            </h1>

            <p>
                Pantau aktivitas dan data Mutu D TMA
                secara terpusat dalam satu sistem.
            </p>

        </div>


        <div class="dashboard-header-date">

            <div class="date-icon">
                <i class="bi bi-calendar3"></i>
            </div>

            <div>
                <small>Hari ini</small>

                <strong>
                    {{ now()->translatedFormat('d F Y') }}
                </strong>
            </div>

        </div>

    </div>


    {{-- =====================================================
        STATISTIC CARDS
    ====================================================== --}}
    <div class="dashboard-stat-grid">


        {{-- TOTAL MUTU D --}}
        <div class="dashboard-stat-card green-card">

            <div class="stat-card-top">

                <div class="stat-icon">
                    <i class="bi bi-bar-chart-fill"></i>
                </div>

                <span class="stat-label">
                    DATA MUTU D
                </span>

            </div>

            <div class="stat-value">
                {{ $stats['total_mutu_d'] ?? 0 }}
            </div>

            <div class="stat-description">
                <i class="bi bi-database-check"></i>
                Total data Mutu D tercatat
            </div>

            <div class="stat-decoration"></div>

        </div>


        {{-- MUTU D HARI INI --}}
        <div class="dashboard-stat-card blue-card">

            <div class="stat-card-top">

                <div class="stat-icon">
                    <i class="bi bi-calendar-check-fill"></i>
                </div>

                <span class="stat-label">
                    HARI INI
                </span>

            </div>

            <div class="stat-value">
                {{ $stats['mutu_d_hari_ini'] ?? 0 }}
            </div>

            <div class="stat-description">
                <i class="bi bi-clock-history"></i>
                Mutu D berdasarkan memo hari ini
            </div>

            <div class="stat-decoration"></div>

        </div>


        {{-- SKORSING --}}
        <div class="dashboard-stat-card yellow-card">

            <div class="stat-card-top">

                <div class="stat-icon">
                    <i class="bi bi-hourglass-split"></i>
                </div>

                <span class="stat-label">
                    SKORSING AKTIF
                </span>

            </div>

            <div class="stat-value">
                {{ $stats['skorsing_aktif'] ?? 0 }}
            </div>

            <div class="stat-description">
                <i class="bi bi-shield-exclamation"></i>
                Sedang dalam masa skorsing
            </div>

            <div class="stat-decoration"></div>

        </div>


        {{-- PEMBERITAHUAN --}}
        <div class="dashboard-stat-card navy-card">

            <div class="stat-card-top">

                <div class="stat-icon">
                    <i class="bi bi-send-check-fill"></i>
                </div>

                <span class="stat-label">
                    PEMBERITAHUAN
                </span>

            </div>

            <div class="stat-value">
                {{ $stats['total_pemberitahuan'] ?? 0 }}
            </div>

            <div class="stat-description">
                <i class="bi bi-whatsapp"></i>
                Notifikasi berhasil diproses
            </div>

            <div class="stat-decoration"></div>

        </div>

    </div>


    {{-- =====================================================
        MAIN CONTENT
    ====================================================== --}}
    <div class="dashboard-main-grid">


        {{-- =================================================
            MUTU D TERBARU
        ================================================== --}}
        <div class="dashboard-panel">

            <div class="panel-header">

                <div class="panel-title">

                    <div class="panel-icon green-panel-icon">
                        <i class="bi bi-activity"></i>
                    </div>

                    <div>
                        <h3>Mutu D Terbaru</h3>

                        <p>
                            Data identifikasi Mutu D terbaru
                        </p>
                    </div>

                </div>


                <a
                    href="{{ route('mutu-d.index') }}"
                    class="panel-link"
                >
                    Lihat semua
                    <i class="bi bi-arrow-right"></i>
                </a>

            </div>


            <div class="panel-body">

                @forelse($mutuDTerbaru as $mutu)

                    <div class="mutu-list-item">

                        <div class="mutu-status-dot"></div>

                        <div class="mutu-info">

                            <strong>
                                {{ $mutu->nama_petani ?? '-' }}
                            </strong>

                            <span>
                                SPTA:
                                {{ $mutu->no_spta ?? '-' }}
                            </span>

                        </div>


                        <div class="mutu-meta">

                            <span class="quality-badge">
                                {{ $mutu->kualitas ?? 'D' }}
                            </span>

                            <small>
                                {{ $mutu->nopol ?? '-' }}
                            </small>

                        </div>

                    </div>

                @empty

                    <div class="empty-state">

                        <div class="empty-icon">
                            <i class="bi bi-inbox"></i>
                        </div>

                        <strong>
                            Belum ada data Mutu D
                        </strong>

                        <span>
                            Data akan muncul setelah import Prodis.
                        </span>

                    </div>

                @endforelse

            </div>

        </div>


        {{-- =================================================
            QUICK ACCESS
        ================================================== --}}
        <div class="dashboard-panel">

            <div class="panel-header">

                <div class="panel-title">

                    <div class="panel-icon blue-panel-icon">
                        <i class="bi bi-grid-fill"></i>
                    </div>

                    <div>
                        <h3>Akses Cepat</h3>

                        <p>
                            Menu yang sering digunakan
                        </p>
                    </div>

                </div>

            </div>


            <div class="quick-menu-grid">


                <a
                    href="{{ route('import.index') }}"
                    class="quick-menu-item"
                >

                    <div class="quick-menu-icon blue-quick">
                        <i class="bi bi-file-earmark-spreadsheet-fill"></i>
                    </div>

                    <div>
                        <strong>Import Prodis</strong>

                        <span>
                            Upload data Excel
                        </span>
                    </div>

                    <i class="bi bi-chevron-right"></i>

                </a>


                <a
                    href="{{ route('mutu-d.index') }}"
                    class="quick-menu-item"
                >

                    <div class="quick-menu-icon green-quick">
                        <i class="bi bi-search"></i>
                    </div>

                    <div>
                        <strong>Data Mutu D</strong>

                        <span>
                            Lihat data mutu
                        </span>
                    </div>

                    <i class="bi bi-chevron-right"></i>

                </a>


                <a
                    href="{{ route('pta.index') }}"
                    class="quick-menu-item"
                >

                    <div class="quick-menu-icon yellow-quick">
                        <i class="bi bi-people-fill"></i>
                    </div>

                    <div>
                        <strong>Data PTA</strong>

                        <span>
                            Kelola data PTA
                        </span>
                    </div>

                    <i class="bi bi-chevron-right"></i>

                </a>


                <a
                    href="{{ route('pemberitahuan.index') }}"
                    class="quick-menu-item"
                >

                    <div class="quick-menu-icon navy-quick">
                        <i class="bi bi-whatsapp"></i>
                    </div>

                    <div>
                        <strong>Pemberitahuan</strong>

                        <span>
                            Kelola notifikasi
                        </span>
                    </div>

                    <i class="bi bi-chevron-right"></i>

                </a>

            </div>

        </div>

    </div>


    {{-- =====================================================
        BOTTOM GRID
    ====================================================== --}}
    <div class="dashboard-bottom-grid">


        {{-- IMPORT TERBARU --}}
        <div class="dashboard-panel">

            <div class="panel-header">

                <div class="panel-title">

                    <div class="panel-icon yellow-panel-icon">
                        <i class="bi bi-cloud-arrow-up-fill"></i>
                    </div>

                    <div>
                        <h3>Import Prodis Terbaru</h3>

                        <p>
                            Riwayat data yang diimport
                        </p>
                    </div>

                </div>

            </div>


            <div class="panel-body">

                @forelse($importTerbaru as $import)

                    <div class="import-list-item">

                        <div class="file-icon">
                            <i class="bi bi-file-earmark-excel-fill"></i>
                        </div>

                        <div class="import-info">

                            <strong>
                                {{ $import->nama_file }}
                            </strong>

                            <span>
                                {{ $import->tanggal_import
                                    ? \Carbon\Carbon::parse($import->tanggal_import)->format('d M Y')
                                    : '-' }}
                            </span>

                        </div>

                        <span class="status-badge status-success">
                            {{ ucfirst($import->status ?? 'Selesai') }}
                        </span>

                    </div>

                @empty

                    <div class="empty-state compact">

                        <i class="bi bi-folder2-open"></i>

                        <span>
                            Belum ada riwayat import.
                        </span>

                    </div>

                @endforelse

            </div>

        </div>


        {{-- PEMBERITAHUAN --}}
        <div class="dashboard-panel">

            <div class="panel-header">

                <div class="panel-title">

                    <div class="panel-icon navy-panel-icon">
                        <i class="bi bi-bell-fill"></i>
                    </div>

                    <div>
                        <h3>Pemberitahuan Terbaru</h3>

                        <p>
                            Aktivitas notifikasi PTA
                        </p>
                    </div>

                </div>

            </div>


            <div class="panel-body">

                @forelse($pemberitahuanTerbaru as $notification)

                    <div class="notification-list-item">

                        <div class="notification-icon">
                            <i class="bi bi-whatsapp"></i>
                        </div>

                        <div class="notification-info">

                            <strong>
                                {{ $notification->judul ?? 'Pemberitahuan Mutu D' }}
                            </strong>

                            <span>
                                {{ $notification->pta->nama_pta ?? 'PTA' }}
                            </span>

                        </div>

                        <span class="status-badge status-success">
                            {{ ucfirst($notification->status_pesan ?? 'Selesai') }}
                        </span>

                    </div>

                @empty

                    <div class="empty-state compact">

                        <i class="bi bi-bell-slash"></i>

                        <span>
                            Belum ada pemberitahuan.
                        </span>

                    </div>

                @endforelse

            </div>

        </div>

    </div>


    {{-- =====================================================
        FOOTER INFO
    ====================================================== --}}
    <div class="dashboard-footer">

        <div class="footer-brand">

            <span class="footer-green">
                SIMUTU
            </span>

            <span class="footer-blue">
                -TMA
            </span>

        </div>

        <span>
            Sistem Monitoring Mutu Tebu
        </span>

        <span class="footer-dot">•</span>

        <span>
            PT Sinergi Gula Nusantara
        </span>

    </div>

</div>

@endsection