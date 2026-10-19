@extends('layouts.app')

@section('title', 'Dashboard Asman TMA')
@section('page-title', 'Dashboard')

@section('content')

<style>
    /* =========================================================
       ASMAN TMA DASHBOARD
       ========================================================= */

    .asman-dashboard {
        --asman-green: #16804f;
        --asman-green-dark: #0f6a41;
        --asman-green-soft: #eaf7f0;

        --asman-blue: #2563eb;
        --asman-blue-soft: #eff6ff;

        --asman-yellow: #d89b00;
        --asman-yellow-soft: #fff8df;

        --asman-red: #dc3545;
        --asman-red-soft: #fff1f2;

        --asman-purple: #7c3aed;
        --asman-purple-soft: #f5f3ff;

        --asman-text: #17211b;
        --asman-muted: #718078;
        --asman-border: #e8eee9;
        --asman-bg: #f7faf8;

        /*
        |--------------------------------------------------------------------------
        | JARAK DARI SIDEBAR
        |--------------------------------------------------------------------------
        */
        margin-left: 32px;
        margin-right: 32px;

        padding-bottom: 40px;

        /*
        |--------------------------------------------------------------------------
        | SUPAYA UKURAN TIDAK MELUBER
        |--------------------------------------------------------------------------
        */
        box-sizing: border-box;
    }

    .asman-dashboard *,
    .asman-dashboard *::before,
    .asman-dashboard *::after {
        box-sizing: border-box;
    }

    /* =========================================================
       HEADER
       ========================================================= */

    .asman-dashboard .dashboard-header {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        gap: 24px;
        margin-bottom: 28px;
    }

    .asman-dashboard .dashboard-header-left {
        min-width: 0;
    }

    .asman-dashboard .breadcrumb {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 10px;
        color: #8a968f;
        font-size: 13px;
        font-weight: 500;
    }

    .asman-dashboard .breadcrumb i {
        font-size: 10px;
    }

    .asman-dashboard .breadcrumb .active {
        color: var(--asman-green);
        font-weight: 600;
    }

    .asman-dashboard .dashboard-title {
        margin: 0;
        color: var(--asman-text);
        font-size: 30px;
        line-height: 1.2;
        font-weight: 750;
        letter-spacing: -0.5px;
    }

    .asman-dashboard .dashboard-subtitle {
        margin: 8px 0 0;
        color: var(--asman-muted);
        font-size: 14px;
        line-height: 1.6;
    }

    .asman-dashboard .dashboard-subtitle strong {
        color: var(--asman-text);
        font-weight: 650;
    }

    .asman-dashboard .dashboard-date {
        display: inline-flex;
        align-items: center;
        gap: 9px;
        flex-shrink: 0;
        padding: 11px 15px;
        background: #ffffff;
        border: 1px solid var(--asman-border);
        border-radius: 12px;
        color: #5e6a63;
        font-size: 13px;
        font-weight: 600;
        box-shadow: 0 3px 12px rgba(21, 44, 31, 0.04);
    }

    .asman-dashboard .dashboard-date i {
        color: var(--asman-green);
        font-size: 16px;
    }

    /* =========================================================
       IMPORTANT NOTICE
       ========================================================= */

    .asman-dashboard .important-notice {
        position: relative;
        display: flex;
        align-items: center;
        gap: 16px;
        margin-bottom: 24px;
        padding: 17px 18px;
        overflow: hidden;
        background: linear-gradient(
            135deg,
            #fff9e7 0%,
            #fffdf5 100%
        );
        border: 1px solid #f4e1a7;
        border-radius: 16px;
        box-shadow: 0 5px 18px rgba(145, 106, 0, 0.06);
    }

    .asman-dashboard .important-notice::before {
        content: "";
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 4px;
        background: #e1a900;
    }

    .asman-dashboard .notice-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 44px;
        height: 44px;
        flex: 0 0 44px;
        background: #fff0bd;
        color: #b27b00;
        border-radius: 12px;
        font-size: 19px;
    }

    .asman-dashboard .notice-content {
        flex: 1;
        min-width: 0;
    }

    .asman-dashboard .notice-content strong {
        display: block;
        margin-bottom: 3px;
        color: #725300;
        font-size: 14px;
        font-weight: 700;
    }

    .asman-dashboard .notice-content p {
        margin: 0;
        color: #92733b;
        font-size: 13px;
        line-height: 1.5;
    }

    .asman-dashboard .notice-content b {
        color: #6d4c00;
    }

    .asman-dashboard .notice-button {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        flex-shrink: 0;
        padding: 10px 14px;
        background: #d89b00;
        color: #ffffff;
        border-radius: 10px;
        font-size: 12px;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .asman-dashboard .notice-button:hover {
        background: #bd8500;
        color: #ffffff;
        transform: translateY(-1px);
    }

    /* =========================================================
       STATISTICS
       ========================================================= */

    .asman-dashboard .stat-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 16px;
        margin-bottom: 28px;
    }

    .asman-dashboard .stat-card {
        position: relative;
        display: flex;
        flex-direction: column;
        min-height: 148px;
        padding: 20px;
        overflow: hidden;
        background: #ffffff;
        border: 1px solid var(--asman-border);
        border-radius: 17px;
        box-shadow: 0 5px 20px rgba(21, 44, 31, 0.045);
        transition:
            transform 0.2s ease,
            box-shadow 0.2s ease;
    }

    .asman-dashboard .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 28px rgba(21, 44, 31, 0.08);
    }

    .asman-dashboard .stat-card::after {
        content: "";
        position: absolute;
        right: -28px;
        bottom: -35px;
        width: 105px;
        height: 105px;
        border-radius: 50%;
        opacity: 0.55;
    }

    .asman-dashboard .stat-card.green::after {
        background: var(--asman-green-soft);
    }

    .asman-dashboard .stat-card.yellow::after {
        background: var(--asman-yellow-soft);
    }

    .asman-dashboard .stat-card.blue::after {
        background: var(--asman-blue-soft);
    }

    .asman-dashboard .stat-card.red::after {
        background: var(--asman-red-soft);
    }

    .asman-dashboard .stat-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
    }

    .asman-dashboard .stat-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 43px;
        height: 43px;
        border-radius: 12px;
        font-size: 18px;
    }

    .asman-dashboard .stat-card.green .stat-icon {
        background: var(--asman-green-soft);
        color: var(--asman-green);
    }

    .asman-dashboard .stat-card.yellow .stat-icon {
        background: var(--asman-yellow-soft);
        color: var(--asman-yellow);
    }

    .asman-dashboard .stat-card.blue .stat-icon {
        background: var(--asman-blue-soft);
        color: var(--asman-blue);
    }

    .asman-dashboard .stat-card.red .stat-icon {
        background: var(--asman-red-soft);
        color: var(--asman-red);
    }

    .asman-dashboard .stat-label {
        color: #7b8780;
        font-size: 12px;
        font-weight: 650;
        letter-spacing: 0.2px;
    }

    .asman-dashboard .stat-value {
        position: relative;
        z-index: 2;
        margin-top: 16px;
        color: var(--asman-text);
        font-size: 30px;
        line-height: 1;
        font-weight: 750;
        letter-spacing: -0.5px;
    }

    .asman-dashboard .stat-description {
        position: relative;
        z-index: 2;
        margin-top: 8px;
        color: #909b94;
        font-size: 11px;
    }

    /* =========================================================
       MAIN GRID
       ========================================================= */

    .asman-dashboard .dashboard-grid {
        display: grid;
        grid-template-columns: minmax(0, 1.75fr) minmax(300px, 0.85fr);
        gap: 20px;
        align-items: stretch;
    }

    .asman-dashboard .simutu-card {
        background: #ffffff;
        border: 1px solid var(--asman-border);
        border-radius: 18px;
        box-shadow: 0 5px 20px rgba(21, 44, 31, 0.045);
    }

    /* =========================================================
       LATEST MUTU D CARD
       ========================================================= */

    .asman-dashboard .dashboard-main-card {
        min-width: 0;
        overflow: hidden;
    }

    .asman-dashboard .card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        padding: 20px 22px;
        border-bottom: 1px solid #edf1ee;
    }

    .asman-dashboard .card-eyebrow {
        display: block;
        margin-bottom: 4px;
        color: var(--asman-green);
        font-size: 10px;
        font-weight: 800;
        letter-spacing: 1.1px;
    }

    .asman-dashboard .card-header h3 {
        margin: 0;
        color: var(--asman-text);
        font-size: 17px;
        font-weight: 700;
    }

    .asman-dashboard .card-link {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        color: var(--asman-green);
        font-size: 12px;
        font-weight: 700;
        text-decoration: none;
        white-space: nowrap;
    }

    .asman-dashboard .card-link:hover {
        color: var(--asman-green-dark);
    }

    /* =========================================================
       MUTU D LIST
       ========================================================= */

    .asman-dashboard .mutu-list {
        display: flex;
        flex-direction: column;
    }

    .asman-dashboard .mutu-item {
        display: grid;
        grid-template-columns: 44px minmax(0, 1fr) auto 18px;
        align-items: center;
        gap: 13px;
        padding: 16px 22px;
        color: inherit;
        text-decoration: none;
        border-bottom: 1px solid #f0f3f1;
        transition: background 0.18s ease;
    }

    .asman-dashboard .mutu-item:last-child {
        border-bottom: 0;
    }

    .asman-dashboard .mutu-item:hover {
        background: #fafcfb;
    }

    .asman-dashboard .mutu-avatar {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 44px;
        height: 44px;
        border-radius: 13px;
        background: linear-gradient(
            135deg,
            #e7f7ee,
            #d5f0e1
        );
        color: var(--asman-green);
        font-size: 15px;
        font-weight: 800;
        text-transform: uppercase;
    }

    .asman-dashboard .mutu-info {
        min-width: 0;
    }

    .asman-dashboard .mutu-name {
        display: block;
        overflow: hidden;
        color: var(--asman-text);
        font-size: 13px;
        font-weight: 700;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .asman-dashboard .mutu-location {
        display: flex;
        align-items: center;
        gap: 5px;
        margin-top: 5px;
        overflow: hidden;
        color: #89948e;
        font-size: 11px;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .asman-dashboard .mutu-location i {
        color: #a0aaa4;
        font-size: 10px;
    }

    .asman-dashboard .mutu-extra {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 5px;
        min-width: 130px;
    }

    .asman-dashboard .mutu-date {
        color: #818d86;
        font-size: 10px;
        white-space: nowrap;
    }

    .asman-dashboard .mutu-time {
        color: #a0aaa4;
        font-size: 10px;
        white-space: nowrap;
    }

    /* =========================================================
       BADGES
       ========================================================= */

    .asman-dashboard .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        width: fit-content;
        padding: 5px 8px;
        border-radius: 7px;
        font-size: 9px;
        font-weight: 800;
        letter-spacing: 0.25px;
        white-space: nowrap;
    }

    .asman-dashboard .status-badge.mutu-d {
        background: #edf8f2;
        color: var(--asman-green);
    }

    .asman-dashboard .status-badge.waiting {
        background: #fff7df;
        color: #9a7100;
    }

    .asman-dashboard .status-badge.processing {
        background: #eef4ff;
        color: #3367c8;
    }

    .asman-dashboard .status-badge.sent {
        background: #eaf8f1;
        color: #198754;
    }

    .asman-dashboard .status-badge.failed {
        background: #fff0f1;
        color: #c73443;
    }

    .asman-dashboard .status-badge i {
        font-size: 9px;
    }

    .asman-dashboard .mutu-arrow {
        color: #b4bdb7;
        font-size: 12px;
    }

    /* =========================================================
       EMPTY STATE
       ========================================================= */

    .asman-dashboard .empty-state {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-height: 280px;
        padding: 30px;
        text-align: center;
    }

    .asman-dashboard .empty-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 58px;
        height: 58px;
        margin-bottom: 14px;
        border-radius: 17px;
        background: var(--asman-green-soft);
        color: var(--asman-green);
        font-size: 24px;
    }

    .asman-dashboard .empty-state h4 {
        margin: 0;
        color: var(--asman-text);
        font-size: 15px;
        font-weight: 700;
    }

    .asman-dashboard .empty-state p {
        max-width: 320px;
        margin: 7px 0 0;
        color: #929d96;
        font-size: 12px;
        line-height: 1.6;
    }

    /* =========================================================
       ACTION CARD
       ========================================================= */

    .asman-dashboard .action-card {
        position: relative;
        display: flex;
        flex-direction: column;
        padding: 23px;
        overflow: hidden;
        background:
            radial-gradient(
                circle at 100% 0%,
                rgba(22, 128, 79, 0.14),
                transparent 35%
            ),
            linear-gradient(
                145deg,
                #ffffff 0%,
                #f9fcfa 100%
            );
    }

    .asman-dashboard .action-card::after {
        content: "";
        position: absolute;
        right: -45px;
        bottom: -50px;
        width: 150px;
        height: 150px;
        border: 25px solid rgba(22, 128, 79, 0.045);
        border-radius: 50%;
        pointer-events: none;
    }

    .asman-dashboard .action-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
    }

    .asman-dashboard .action-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 48px;
        height: 48px;
        border-radius: 14px;
        background: var(--asman-green-soft);
        color: var(--asman-green);
        font-size: 21px;
    }

    .asman-dashboard .action-label {
        padding: 6px 9px;
        background: #eef8f3;
        border-radius: 7px;
        color: var(--asman-green);
        font-size: 9px;
        font-weight: 800;
        letter-spacing: 0.8px;
    }

    .asman-dashboard .action-card h3 {
        position: relative;
        z-index: 2;
        margin: 24px 0 8px;
        color: var(--asman-text);
        font-size: 20px;
        font-weight: 750;
        letter-spacing: -0.2px;
    }

    .asman-dashboard .action-card p {
        position: relative;
        z-index: 2;
        margin: 0 0 23px;
        color: #78847d;
        font-size: 12px;
        line-height: 1.75;
    }

    .asman-dashboard .action-divider {
        height: 1px;
        margin-bottom: 18px;
        background: #e8eee9;
    }

    .asman-dashboard .action-stat {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 18px;
        padding: 11px 12px;
        background: #f7faf8;
        border: 1px solid #edf2ee;
        border-radius: 10px;
    }

    .asman-dashboard .action-stat-label {
        display: flex;
        align-items: center;
        gap: 7px;
        color: #7f8b84;
        font-size: 11px;
    }

    .asman-dashboard .action-stat-label i {
        color: var(--asman-yellow);
    }

    .asman-dashboard .action-stat-value {
        color: var(--asman-text);
        font-size: 13px;
        font-weight: 750;
    }

    /* =========================================================
       BUTTONS
       ========================================================= */

    .asman-dashboard .dashboard-button {
        position: relative;
        z-index: 3;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        width: 100%;
        min-height: 42px;
        border-radius: 10px;
        font-size: 12px;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .asman-dashboard .dashboard-button.primary {
        background: var(--asman-green);
        color: #ffffff;
        box-shadow: 0 5px 14px rgba(22, 128, 79, 0.17);
    }

    .asman-dashboard .dashboard-button.primary:hover {
        background: var(--asman-green-dark);
        color: #ffffff;
        transform: translateY(-1px);
        box-shadow: 0 7px 18px rgba(22, 128, 79, 0.22);
    }

    .asman-dashboard .dashboard-button.secondary {
        margin-top: 9px;
        background: #ffffff;
        color: #4f5c54;
        border: 1px solid #dfe7e1;
    }

    .asman-dashboard .dashboard-button.secondary:hover {
        background: #f7faf8;
        color: var(--asman-green);
        border-color: #cbdacf;
    }

    /* =========================================================
       QUICK INFO
       ========================================================= */

    .asman-dashboard .quick-info {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 10px;
        margin-top: 20px;
    }

    .asman-dashboard .quick-info-item {
        padding: 12px;
        background: #ffffff;
        border: 1px solid var(--asman-border);
        border-radius: 12px;
        text-align: center;
    }

    .asman-dashboard .quick-info-number {
        display: block;
        color: var(--asman-text);
        font-size: 17px;
        font-weight: 750;
    }

    .asman-dashboard .quick-info-label {
        display: block;
        margin-top: 3px;
        color: #929d96;
        font-size: 9px;
    }

    /* =========================================================
       RESPONSIVE
       ========================================================= */

    @media (max-width: 1200px) {

        .asman-dashboard {
            margin-left: 24px;
            margin-right: 24px;
        }

    }

    @media (max-width: 1100px) {

        .asman-dashboard .stat-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .asman-dashboard .dashboard-grid {
            grid-template-columns: 1fr;
        }

        .asman-dashboard .action-card {
            min-height: auto;
        }

    }

    @media (max-width: 768px) {

        .asman-dashboard {
            margin-left: 12px;
            margin-right: 12px;
            padding-bottom: 30px;
        }

        .asman-dashboard .dashboard-header {
            align-items: flex-start;
            flex-direction: column;
            margin-bottom: 22px;
        }

        .asman-dashboard .dashboard-title {
            font-size: 25px;
        }

        .asman-dashboard .dashboard-date {
            width: fit-content;
        }

        .asman-dashboard .important-notice {
            align-items: flex-start;
            flex-wrap: wrap;
        }

        .asman-dashboard .notice-content {
            flex: 1 1 calc(100% - 70px);
        }

        .asman-dashboard .notice-button {
            width: 100%;
            justify-content: center;
            margin-left: 60px;
        }

        .asman-dashboard .stat-grid {
            gap: 12px;
        }

        .asman-dashboard .stat-card {
            min-height: 135px;
            padding: 16px;
        }

        .asman-dashboard .stat-value {
            font-size: 26px;
        }

        .asman-dashboard .mutu-item {
            grid-template-columns: 40px minmax(0, 1fr) 16px;
            gap: 10px;
            padding: 14px 16px;
        }

        .asman-dashboard .mutu-avatar {
            width: 40px;
            height: 40px;
        }

        .asman-dashboard .mutu-extra {
            display: none;
        }

        .asman-dashboard .card-header {
            padding: 17px 16px;
        }

        .asman-dashboard .action-card {
            padding: 20px;
        }

    }

    @media (max-width: 480px) {

        .asman-dashboard {
            margin-left: 8px;
            margin-right: 8px;
        }

        .asman-dashboard .stat-grid {
            grid-template-columns: 1fr 1fr;
        }

        .asman-dashboard .stat-card {
            min-height: 125px;
        }

        .asman-dashboard .stat-label {
            font-size: 10px;
        }

        .asman-dashboard .stat-value {
            margin-top: 13px;
            font-size: 24px;
        }

        .asman-dashboard .stat-description {
            font-size: 9px;
        }

        .asman-dashboard .important-notice {
            padding: 14px;
        }

        .asman-dashboard .notice-button {
            margin-left: 0;
        }

        .asman-dashboard .quick-info {
            grid-template-columns: repeat(3, 1fr);
        }

    }
</style>


<div class="asman-dashboard">

    {{-- =====================================================
         HEADER
         ====================================================== --}}
    <div class="dashboard-header">

        <div class="dashboard-header-left">

            <div class="breadcrumb">
                <span>SIMUTU-TMA</span>
                <i class="bi bi-chevron-right"></i>
                <span class="active">Dashboard</span>
            </div>

            <h1 class="dashboard-title">
                Monitoring Mutu Tebu
            </h1>

            <p class="dashboard-subtitle">
                Selamat datang kembali,
                <strong>{{ auth()->user()->name ?? 'Asman TMA' }}</strong>.
                Berikut ringkasan monitoring mutu tebu hari ini.
            </p>

        </div>

        <div class="dashboard-date">
            <i class="bi bi-calendar3"></i>
            <span>{{ now()->translatedFormat('l, d F Y') }}</span>
        </div>

    </div>


    {{-- =====================================================
         IMPORTANT NOTICE
         ====================================================== --}}
    @if(($statistics['belum_diberitahukan'] ?? 0) > 0)

        <div class="important-notice">

            <div class="notice-icon">
                <i class="bi bi-exclamation-triangle-fill"></i>
            </div>

            <div class="notice-content">

                <strong>
                    Ada data Mutu D yang belum diberitahukan
                </strong>

                <p>
                    Terdapat
                    <b>{{ $statistics['belum_diberitahukan'] ?? 0 }}</b>
                    data yang membutuhkan tindak lanjut.
                </p>

            </div>

            <a
                href="{{ route('pemberitahuan.create') }}"
                class="notice-button"
            >
                Buat Pemberitahuan
                <i class="bi bi-arrow-right"></i>
            </a>

        </div>

    @endif


    {{-- =====================================================
         STATISTICS
         ====================================================== --}}
    <div class="stat-grid">

        {{-- Mutu D --}}
        <div class="stat-card green">

            <div class="stat-top">

                <div class="stat-label">
                    Mutu D Hari Ini
                </div>

                <div class="stat-icon">
                    <i class="bi bi-clipboard2-pulse-fill"></i>
                </div>

            </div>

            <div class="stat-value">
                {{ $statistics['total'] ?? 0 }}
            </div>

            <div class="stat-description">
                Data hasil identifikasi
            </div>

        </div>


        {{-- Skorsing --}}
        <div class="stat-card yellow">

            <div class="stat-top">

                <div class="stat-label">
                    Skorsing Aktif
                </div>

                <div class="stat-icon">
                    <i class="bi bi-hourglass-split"></i>
                </div>

            </div>

            <div class="stat-value">
                {{ $statistics['skorsing_aktif'] ?? 0 }}
            </div>

            <div class="stat-description">
                Petani dalam masa skorsing
            </div>

        </div>


        {{-- Terkirim --}}
        <div class="stat-card blue">

            <div class="stat-top">

                <div class="stat-label">
                    Terkirim
                </div>

                <div class="stat-icon">
                    <i class="bi bi-whatsapp"></i>
                </div>

            </div>

            <div class="stat-value">
                {{ $statistics['sudah_diberitahukan'] ?? 0 }}
            </div>

            <div class="stat-description">
                Pemberitahuan berhasil
            </div>

        </div>


        {{-- Menunggu --}}
        <div class="stat-card red">

            <div class="stat-top">

                <div class="stat-label">
                    Menunggu
                </div>

                <div class="stat-icon">
                    <i class="bi bi-bell-fill"></i>
                </div>

            </div>

            <div class="stat-value">
                {{ $statistics['belum_diberitahukan'] ?? 0 }}
            </div>

            <div class="stat-description">
                Perlu ditindaklanjuti
            </div>

        </div>

    </div>


    {{-- =====================================================
         MAIN CONTENT
         ====================================================== --}}
    <div class="dashboard-grid">


        {{-- =================================================
             MUTU D TERBARU
             ================================================== --}}
        <div class="simutu-card dashboard-main-card">

            <div class="card-header">

                <div>

                    <span class="card-eyebrow">
                        MONITORING
                    </span>

                    <h3>
                        Mutu D Terbaru
                    </h3>

                </div>

                <a
                    href="{{ route('mutu-d.index') }}"
                    class="card-link"
                >
                    Selengkapnya
                    <i class="bi bi-arrow-right"></i>
                </a>

            </div>


            @if(isset($latestMutuD) && $latestMutuD->count())

                <div class="mutu-list">

                    @foreach($latestMutuD as $mutu)

                        @php

                            /*
                            |--------------------------------------------------------------------------
                            | Nama Petani
                            |--------------------------------------------------------------------------
                            */
                            $namaPetani = $mutu->nama_petani ?? 'Petani';

                            $initial = function_exists('mb_substr')
                                ? mb_substr(trim($namaPetani), 0, 1)
                                : substr(trim($namaPetani), 0, 1);

                            $initial = strtoupper($initial);


                            /*
                            |--------------------------------------------------------------------------
                            | PTA
                            |--------------------------------------------------------------------------
                            */
                            $namaPta = optional($mutu->pta)->nama_pta
                                ?? 'PTA belum tersedia';


                            /*
                            |--------------------------------------------------------------------------
                            | Tanggal Memo
                            |--------------------------------------------------------------------------
                            */
                            $tanggalMemo = optional($mutu->memoProdis)->tanggal_memo;

                            if ($tanggalMemo) {
                                try {
                                    $tanggalMemoText = \Carbon\Carbon::parse($tanggalMemo)
                                        ->translatedFormat('d M Y');
                                } catch (\Throwable $e) {
                                    $tanggalMemoText = '-';
                                }
                            } else {
                                $tanggalMemoText = '-';
                            }


                            /*
                            |--------------------------------------------------------------------------
                            | Tanggal Meja Tebu
                            |--------------------------------------------------------------------------
                            */
                            $tanggalMeja = $mutu->tanggal_meja_tebu;

                            if ($tanggalMeja) {
                                try {
                                    $tanggalMejaText = \Carbon\Carbon::parse($tanggalMeja)
                                        ->translatedFormat('d M Y');
                                } catch (\Throwable $e) {
                                    $tanggalMejaText = '-';
                                }
                            } else {
                                $tanggalMejaText = '-';
                            }


                            /*
                            |--------------------------------------------------------------------------
                            | Jam Meja Tebu
                            |--------------------------------------------------------------------------
                            */
                            $jamMeja = $mutu->jam_meja_tebu ?? null;

                            if ($jamMeja) {
                                try {
                                    $jamMejaText = \Carbon\Carbon::parse($jamMeja)
                                        ->format('H:i');
                                } catch (\Throwable $e) {
                                    $jamMejaText = $jamMeja;
                                }
                            } else {
                                $jamMejaText = '-';
                            }


                            /*
                            |--------------------------------------------------------------------------
                            | Status Pemberitahuan
                            |--------------------------------------------------------------------------
                            */
                            $status = $mutu->status_pemberitahuan ?? null;

                            switch ($status) {

                                case 'sudah_diberitahukan':
                                case 'terkirim':
                                case 'sent':
                                    $statusClass = 'sent';
                                    $statusLabel = 'Terkirim';
                                    $statusIcon = 'bi-check-circle-fill';
                                    break;

                                case 'diproses':
                                case 'processing':
                                    $statusClass = 'processing';
                                    $statusLabel = 'Diproses';
                                    $statusIcon = 'bi-arrow-repeat';
                                    break;

                                case 'gagal':
                                case 'failed':
                                    $statusClass = 'failed';
                                    $statusLabel = 'Gagal';
                                    $statusIcon = 'bi-x-circle-fill';
                                    break;

                                default:
                                    $statusClass = 'waiting';
                                    $statusLabel = 'Menunggu';
                                    $statusIcon = 'bi-clock-fill';
                                    break;
                            }

                        @endphp


                        <a
                            href="{{ route('mutu-d.detail', $mutu->id) }}"
                            class="mutu-item"
                        >

                            {{-- Avatar --}}
                            <div class="mutu-avatar">
                                {{ $initial }}
                            </div>


                            {{-- Informasi utama --}}
                            <div class="mutu-info">

                                <span class="mutu-name">
                                    {{ $namaPetani }}
                                </span>

                                <span class="mutu-location">
                                    <i class="bi bi-person-badge"></i>
                                    {{ $namaPta }}
                                </span>

                                @if($mutu->no_spta)
                                    <span class="mutu-location">
                                        <i class="bi bi-file-earmark-text"></i>
                                        SPTA: {{ $mutu->no_spta }}
                                    </span>
                                @endif

                            </div>


                            {{-- Meta --}}
                            <div class="mutu-extra">

                                <span class="status-badge mutu-d">
                                    <i class="bi bi-droplet-fill"></i>
                                    MUTU D
                                </span>

                                <span class="status-badge {{ $statusClass }}">
                                    <i class="bi {{ $statusIcon }}"></i>
                                    {{ $statusLabel }}
                                </span>

                                @if($tanggalMejaText !== '-')
                                    <span class="mutu-date">
                                        <i class="bi bi-calendar3"></i>
                                        {{ $tanggalMejaText }}
                                    </span>
                                @endif

                                @if($jamMejaText !== '-')
                                    <span class="mutu-time">
                                        <i class="bi bi-clock"></i>
                                        {{ $jamMejaText }}
                                    </span>
                                @endif

                            </div>


                            {{-- Arrow --}}
                            <i class="bi bi-chevron-right mutu-arrow"></i>

                        </a>

                    @endforeach

                </div>

            @else

                <div class="empty-state">

                    <div class="empty-icon">
                        <i class="bi bi-check2-circle"></i>
                    </div>

                    <h4>
                        Tidak ada Mutu D baru
                    </h4>

                    <p>
                        Belum terdapat data Mutu D yang perlu
                        ditindaklanjuti hari ini.
                    </p>

                </div>

            @endif

        </div>


        {{-- =================================================
             ACTION CARD
             ================================================== --}}
        <div class="simutu-card action-card">

            <div class="action-top">

                <div class="action-icon">
                    <i class="bi bi-megaphone-fill"></i>
                </div>

                <span class="action-label">
                    PEMBERITAHUAN
                </span>

            </div>


            <h3>
                Informasikan Mutu D
            </h3>

            <p>
                Buat dan kirim pemberitahuan kepada PTA
                melalui WhatsApp secara cepat dan terorganisir.
            </p>


            <div class="action-divider"></div>


            {{-- Pending count --}}
            <div class="action-stat">

                <span class="action-stat-label">
                    <i class="bi bi-bell-fill"></i>
                    Menunggu pemberitahuan
                </span>

                <span class="action-stat-value">
                    {{ $statistics['belum_diberitahukan'] ?? 0 }}
                </span>

            </div>


            {{-- Button --}}
            <a
                href="{{ route('pemberitahuan.create') }}"
                class="dashboard-button primary"
            >
                <i class="bi bi-plus-circle-fill"></i>
                Buat Pemberitahuan
            </a>


            <a
                href="{{ route('pemberitahuan.index') }}"
                class="dashboard-button secondary"
            >
                <i class="bi bi-clock-history"></i>
                Lihat Riwayat Pemberitahuan
            </a>


            {{-- Quick info --}}
            <div class="quick-info">

                <div class="quick-info-item">

                    <span class="quick-info-number">
                        {{ $statistics['total'] ?? 0 }}
                    </span>

                    <span class="quick-info-label">
                        Mutu D
                    </span>

                </div>


                <div class="quick-info-item">

                    <span class="quick-info-number">
                        {{ $statistics['skorsing_aktif'] ?? 0 }}
                    </span>

                    <span class="quick-info-label">
                        Skorsing
                    </span>

                </div>


                <div class="quick-info-item">

                    <span class="quick-info-number">
                        {{ $statistics['sudah_diberitahukan'] ?? 0 }}
                    </span>

                    <span class="quick-info-label">
                        Terkirim
                    </span>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection