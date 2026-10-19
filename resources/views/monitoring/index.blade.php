@extends('layouts.app')

@section('title', 'Monitoring')
@section('page-title', 'Monitoring')

@section('content')

<style>

    /* =========================================================
       PAGE
    ========================================================= */

    .monitoring-page {
        width: 100%;
        max-width: 1500px;
        margin: 0 auto;
        padding: 8px 24px 50px;
    }


    /* =========================================================
       HEADER
    ========================================================= */

    .monitoring-page-header {
        margin-bottom: 26px;
    }

    .monitoring-breadcrumb {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 14px;
        color: #7c8981;
        font-size: 11px;
    }

    .monitoring-breadcrumb i {
        font-size: 9px;
        color: #a6b0aa;
    }

    .monitoring-page-header h2 {
        margin: 0 0 7px;
        color: #203d2d;
        font-size: 28px;
        font-weight: 800;
    }

    .monitoring-page-header p {
        margin: 0;
        color: #738078;
        font-size: 13px;
    }


    /* =========================================================
       SYSTEM STATUS
    ========================================================= */

    .monitoring-overview {
        margin-bottom: 22px;
    }

    .monitoring-overview-card {
        display: flex;
        align-items: center;
        gap: 17px;
        padding: 22px 25px;
        border: 1px solid #e2ebe5;
        border-radius: 17px;
        background: #fff;
        box-shadow: 0 5px 20px rgba(38, 70, 51, .035);
    }

    .monitoring-overview-icon {
        width: 54px;
        height: 54px;
        min-width: 54px;
        border-radius: 15px;
        background: #eaf6ee;
        color: #347d50;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
    }

    .monitoring-overview-content {
        flex: 1;
    }

    .monitoring-overview-label {
        display: block;
        margin-bottom: 4px;
        color: #819087;
        font-size: 9px;
        font-weight: 800;
        letter-spacing: 1px;
    }

    .monitoring-overview-content h3 {
        margin: 0 0 4px;
        color: #244432;
        font-size: 19px;
        font-weight: 800;
    }

    .monitoring-overview-content p {
        margin: 0;
        color: #87938c;
        font-size: 11px;
    }

    .monitoring-online-badge {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 9px 13px;
        border-radius: 20px;
        background: #f0faf3;
        border: 1px solid #dcefe1;
        color: #347d50;
        font-size: 10px;
        font-weight: 800;
    }

    .monitoring-online-badge span {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #50ad68;
    }


    /* =========================================================
       STATISTICS
    ========================================================= */

    .monitoring-stat-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 17px;
        margin-bottom: 27px;
    }

    .monitoring-stat-card {
        display: flex;
        align-items: center;
        gap: 14px;
        min-height: 104px;
        padding: 18px 19px;
        border: 1px solid #e5ebe7;
        border-radius: 15px;
        background: #fff;
        box-shadow: 0 5px 18px rgba(38, 70, 51, .03);
    }

    .monitoring-stat-icon {
        width: 46px;
        height: 46px;
        min-width: 46px;
        border-radius: 13px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 17px;
    }

    .monitoring-stat-card.total .monitoring-stat-icon {
        background: #eaf6ee;
        color: #347d50;
    }

    .monitoring-stat-card.success .monitoring-stat-icon {
        background: #eaf7ef;
        color: #3f8b58;
    }

    .monitoring-stat-card.partial .monitoring-stat-icon {
        background: #fff6df;
        color: #b58722;
    }

    .monitoring-stat-card.failed .monitoring-stat-icon {
        background: #fceeee;
        color: #bf5b58;
    }

    .monitoring-stat-content {
        min-width: 0;
    }

    .monitoring-stat-content > span {
        display: block;
        margin-bottom: 3px;
        color: #78867d;
        font-size: 10px;
        font-weight: 700;
    }

    .monitoring-stat-content strong {
        display: block;
        margin-bottom: 2px;
        color: #243e2e;
        font-size: 24px;
        line-height: 1;
        font-weight: 800;
    }

    .monitoring-stat-content small {
        color: #9aa49e;
        font-size: 9px;
    }


    /* =========================================================
       ACTIVITY CARD
    ========================================================= */

    .monitoring-activity-card {
        padding: 0;
        overflow: hidden;
    }

    .monitoring-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        padding: 22px 24px;
        border-bottom: 1px solid #edf1ee;
    }

    .monitoring-card-header .card-eyebrow {
        display: block;
        margin-bottom: 4px;
        color: #718d7a;
        font-size: 9px;
        font-weight: 800;
        letter-spacing: 1.2px;
    }

    .monitoring-card-header h3 {
        margin: 0 0 4px;
        color: #213e2d;
        font-size: 18px;
        font-weight: 800;
    }

    .monitoring-card-description {
        margin: 0;
        color: #8a958e;
        font-size: 11px;
    }

    .monitoring-live-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 7px 11px;
        border-radius: 20px;
        background: #f0faf3;
        border: 1px solid #dcefe1;
        color: #347d50;
        font-size: 9px;
        font-weight: 800;
    }

    .monitoring-live-badge span {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: #50ad68;
    }


    /* =========================================================
       ACTIVITY LIST
    ========================================================= */

    .monitoring-list {
        width: 100%;
    }

    .monitoring-item {
        display: grid;
        grid-template-columns: 48px minmax(250px, 1fr) auto 125px 25px;
        align-items: center;
        gap: 15px;
        padding: 17px 24px;
        border-bottom: 1px solid #edf1ee;
        color: inherit;
        text-decoration: none;
        transition: .16s ease;
    }

    .monitoring-item:last-child {
        border-bottom: 0;
    }

    .monitoring-item:hover {
        background: #fbfdfb;
    }


    /* =========================================================
       ITEM ICON
    ========================================================= */

    .monitoring-item-icon {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
    }

    .monitoring-item-icon.sent {
        background: #eaf7ef;
        color: #43865a;
    }

    .monitoring-item-icon.partial {
        background: #fff6df;
        color: #b48621;
    }

    .monitoring-item-icon.failed {
        background: #fceeee;
        color: #bd5a58;
    }

    .monitoring-item-icon.processing {
        background: #edf5fb;
        color: #4a7698;
    }

    .monitoring-item-icon.draft {
        background: #f1f5f3;
        color: #7d8b83;
    }


    /* =========================================================
       ITEM INFO
    ========================================================= */

    .monitoring-item-info {
        min-width: 0;
    }

    .monitoring-item-title {
        display: block;
        margin-bottom: 6px;
        color: #294735;
        font-size: 12px;
        font-weight: 800;
    }

    .monitoring-item-subinfo {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 7px;
        color: #89958e;
        font-size: 10px;
    }

    .monitoring-item-subinfo span {
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .monitoring-item-subinfo i {
        color: #73917d;
        font-size: 10px;
    }

    .activity-separator {
        color: #b1bab4;
    }


    /* =========================================================
       STATUS
    ========================================================= */

    .monitoring-item-status {
        white-space: nowrap;
    }

    .monitoring-status {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 6px 9px;
        border-radius: 8px;
        font-size: 9px;
        font-weight: 800;
        white-space: nowrap;
    }

    .monitoring-status.success {
        background: #eaf7ef;
        border: 1px solid #dceee2;
        color: #3f8054;
    }

    .monitoring-status.warning {
        background: #fff7e2;
        border: 1px solid #f3e7c0;
        color: #9b7626;
    }

    .monitoring-status.danger {
        background: #fceeee;
        border: 1px solid #f2dddd;
        color: #b85a57;
    }

    .monitoring-status.info {
        background: #edf5fb;
        border: 1px solid #dfeaf2;
        color: #4d7593;
    }

    .monitoring-status.neutral {
        background: #f2f5f3;
        border: 1px solid #e6ebe8;
        color: #78867e;
    }


    /* =========================================================
       TIME
    ========================================================= */

    .monitoring-item-time {
        text-align: right;
        white-space: nowrap;
    }

    .monitoring-item-time-main {
        display: block;
        color: #718078;
        font-size: 10px;
    }

    .monitoring-item-time-date {
        display: block;
        margin-top: 3px;
        color: #a0aaa4;
        font-size: 9px;
    }


    /* =========================================================
       ARROW
    ========================================================= */

    .monitoring-item-arrow {
        width: 30px;
        height: 30px;
        border-radius: 8px;
        background: #f3f8f4;
        border: 1px solid #e0ebe3;
        color: #6b9277;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
    }


    /* =========================================================
       EMPTY
    ========================================================= */

    .monitoring-empty-state {
        padding: 65px 20px;
        text-align: center;
    }

    .monitoring-empty-icon {
        width: 60px;
        height: 60px;
        margin: 0 auto 15px;
        border-radius: 16px;
        background: #f1f5f3;
        color: #7e9085;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 23px;
    }

    .monitoring-empty-state h4 {
        margin: 0 0 7px;
        color: #40564a;
        font-size: 15px;
    }

    .monitoring-empty-state p {
        max-width: 430px;
        margin: 0 auto;
        color: #8b968f;
        font-size: 11px;
        line-height: 1.6;
    }


    /* =========================================================
       PAGINATION
    ========================================================= */

    .monitoring-pagination {
        padding: 15px 22px;
        border-top: 1px solid #edf1ee;
        background: #fcfdfc;
    }

    .monitoring-pagination nav {
        display: flex;
        justify-content: flex-end;
    }

    .monitoring-pagination svg {
        width: 13px !important;
        height: 13px !important;
    }

    .monitoring-pagination a,
    .monitoring-pagination span {
        font-size: 11px !important;
    }


    /* =========================================================
       RESPONSIVE
    ========================================================= */

    @media(max-width: 1100px) {

        .monitoring-stat-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .monitoring-item {
            grid-template-columns: 48px minmax(200px, 1fr) auto 100px 25px;
        }
    }


    @media(max-width: 800px) {

        .monitoring-page {
            padding: 8px 14px 35px;
        }

        .monitoring-overview-card {
            align-items: flex-start;
        }

        .monitoring-online-badge {
            display: none;
        }

        .monitoring-item {
            grid-template-columns: 44px minmax(0, 1fr) 25px;
            gap: 12px;
        }

        .monitoring-item-status,
        .monitoring-item-time {
            display: none;
        }
    }


    @media(max-width: 560px) {

        .monitoring-stat-grid {
            grid-template-columns: 1fr;
        }

        .monitoring-page-header h2 {
            font-size: 23px;
        }

        .monitoring-card-header {
            padding: 18px;
        }

        .monitoring-item {
            padding: 15px 18px;
        }

        .monitoring-item-subinfo {
            gap: 5px;
        }

    }

</style>


<div class="monitoring-page">


    {{-- =========================================================
         PAGE HEADER
    ========================================================== --}}

    <div class="monitoring-page-header">

        <div class="monitoring-breadcrumb">

            <span>Monitoring TMA</span>

            <i class="bi bi-chevron-right"></i>

            <span>Monitoring</span>

        </div>


        <h2>
            Monitoring Pemberitahuan
        </h2>

        <p>
            Pantau status pengiriman pemberitahuan Mutu D kepada PTA.
        </p>

    </div>



    {{-- =========================================================
         SYSTEM STATUS
    ========================================================== --}}

    <div class="monitoring-overview">

        <div class="monitoring-overview-card">

            <div class="monitoring-overview-icon">

                <i class="bi bi-broadcast-pin"></i>

            </div>


            <div class="monitoring-overview-content">

                <span class="monitoring-overview-label">
                    STATUS SISTEM
                </span>

                <h3>
                    Monitoring Aktif
                </h3>

                <p>
                    Sistem siap memantau proses pemberitahuan Mutu D.
                </p>

            </div>


            <div class="monitoring-online-badge">

                <span></span>

                Online

            </div>

        </div>

    </div>



    {{-- =========================================================
         STATISTICS
    ========================================================== --}}

    <div class="monitoring-stat-grid">


        {{-- TOTAL --}}

        <div class="monitoring-stat-card total">

            <div class="monitoring-stat-icon">

                <i class="bi bi-megaphone-fill"></i>

            </div>


            <div class="monitoring-stat-content">

                <span>
                    Total Pemberitahuan
                </span>

                <strong>
                    {{ $statistics['total'] ?? 0 }}
                </strong>

                <small>
                    Seluruh pemberitahuan
                </small>

            </div>

        </div>



        {{-- BERHASIL --}}

        <div class="monitoring-stat-card success">

            <div class="monitoring-stat-icon">

                <i class="bi bi-check-circle-fill"></i>

            </div>


            <div class="monitoring-stat-content">

                <span>
                    Berhasil
                </span>

                <strong>
                    {{ $statistics['sent'] ?? 0 }}
                </strong>

                <small>
                    Pengiriman berhasil
                </small>

            </div>

        </div>



        {{-- SEBAGIAN --}}

        <div class="monitoring-stat-card partial">

            <div class="monitoring-stat-icon">

                <i class="bi bi-exclamation-circle-fill"></i>

            </div>


            <div class="monitoring-stat-content">

                <span>
                    Sebagian
                </span>

                <strong>
                    {{ $statistics['partial'] ?? 0 }}
                </strong>

                <small>
                    Sebagian berhasil
                </small>

            </div>

        </div>



        {{-- GAGAL --}}

        <div class="monitoring-stat-card failed">

            <div class="monitoring-stat-icon">

                <i class="bi bi-x-circle-fill"></i>

            </div>


            <div class="monitoring-stat-content">

                <span>
                    Gagal
                </span>

                <strong>
                    {{ $statistics['failed'] ?? 0 }}
                </strong>

                <small>
                    Pengiriman gagal
                </small>

            </div>

        </div>

    </div>



    {{-- =========================================================
         ACTIVITY
    ========================================================== --}}

    <div class="simutu-card monitoring-activity-card">


        {{-- HEADER --}}

        <div class="monitoring-card-header">

            <div>

                <span class="card-eyebrow">
                    REAL-TIME MONITORING
                </span>

                <h3>
                    Aktivitas Pemberitahuan
                </h3>

                <p class="monitoring-card-description">
                    Riwayat aktivitas pengiriman pemberitahuan kepada PTA.
                </p>

            </div>


            <span class="monitoring-live-badge">

                <span></span>

                LIVE

            </span>

        </div>



        {{-- LIST --}}

        <div class="monitoring-list">


            @forelse($pemberitahuans as $item)


                @php

                    /*
                    |--------------------------------------------------------------------------
                    | STATUS
                    |--------------------------------------------------------------------------
                    */

                    $status = $item->display_status ?? 'draft';


                    /*
                    |--------------------------------------------------------------------------
                    | NOMOR
                    |--------------------------------------------------------------------------
                    */

                    $nomor = $item->nomor
                        ?: $item->judul
                        ?: 'Pemberitahuan #' . $item->id;


                    /*
                    |--------------------------------------------------------------------------
                    | TOTAL PTA
                    |--------------------------------------------------------------------------
                    */

                    $totalPta = $item->display_total_pta
                        ?? $item->total_pta
                        ?? 0;


                    /*
                    |--------------------------------------------------------------------------
                    | TOTAL MUTU D
                    |--------------------------------------------------------------------------
                    */

                    $totalMutuD = $item->display_total_mutu_d
                        ?? $item->total_mutu_d
                        ?? 0;


                    /*
                    |--------------------------------------------------------------------------
                    | WAKTU
                    |--------------------------------------------------------------------------
                    */

                    $activityTime =
                        $item->display_time
                        ?? $item->waktu_kirim_pesan
                        ?? $item->sent_at
                        ?? $item->updated_at
                        ?? $item->created_at;

                @endphp



                <a
                    href="{{ route('monitoring.detail', $item->id) }}"
                    class="monitoring-item"
                >


                    {{-- ICON --}}

                    <div class="monitoring-item-icon {{ $status }}">

                        @if($status === 'sent')

                            <i class="bi bi-check-lg"></i>

                        @elseif($status === 'failed')

                            <i class="bi bi-x-lg"></i>

                        @elseif($status === 'partial')

                            <i class="bi bi-exclamation-lg"></i>

                        @elseif($status === 'processing')

                            <i class="bi bi-hourglass-split"></i>

                        @else

                            <i class="bi bi-clock"></i>

                        @endif

                    </div>



                    {{-- INFO --}}

                    <div class="monitoring-item-info">


                        <strong class="monitoring-item-title">

                            {{ $nomor }}

                        </strong>


                        <div class="monitoring-item-subinfo">


                            <span>

                                <i class="bi bi-people-fill"></i>

                                {{ $totalPta }}

                                PTA

                            </span>


                            <span class="activity-separator">
                                •
                            </span>


                            <span>

                                <i class="bi bi-file-earmark-bar-graph-fill"></i>

                                {{ $totalMutuD }}

                                Mutu D

                            </span>


                        </div>

                    </div>



                    {{-- STATUS --}}

                    <div class="monitoring-item-status">


                        @if($status === 'sent')

                            <span class="monitoring-status success">

                                <i class="bi bi-check-circle-fill"></i>

                                Berhasil

                            </span>


                        @elseif($status === 'partial')

                            <span class="monitoring-status warning">

                                <i class="bi bi-exclamation-circle-fill"></i>

                                Sebagian

                            </span>


                        @elseif($status === 'failed')

                            <span class="monitoring-status danger">

                                <i class="bi bi-x-circle-fill"></i>

                                Gagal

                            </span>


                        @elseif($status === 'processing')

                            <span class="monitoring-status info">

                                <i class="bi bi-hourglass-split"></i>

                                Diproses

                            </span>


                        @else

                            <span class="monitoring-status neutral">

                                <i class="bi bi-file-earmark"></i>

                                Draft

                            </span>

                        @endif

                    </div>



                    {{-- TIME --}}

                    <div class="monitoring-item-time">


                        @if($activityTime)

                            <span class="monitoring-item-time-main">

                                {{ \Carbon\Carbon::parse($activityTime)
                                    ->locale('id')
                                    ->diffForHumans() }}

                            </span>


                            <span class="monitoring-item-time-date">

                                {{ \Carbon\Carbon::parse($activityTime)
                                    ->locale('id')
                                    ->translatedFormat('d M Y H:i') }}

                            </span>

                        @else

                            <span class="monitoring-item-time-main">
                                -
                            </span>

                        @endif

                    </div>



                    {{-- ARROW --}}

                    <div class="monitoring-item-arrow">

                        <i class="bi bi-chevron-right"></i>

                    </div>


                </a>


            @empty


                {{-- EMPTY --}}

                <div class="monitoring-empty-state">

                    <div class="monitoring-empty-icon">

                        <i class="bi bi-broadcast"></i>

                    </div>


                    <h4>
                        Belum ada aktivitas
                    </h4>


                    <p>
                        Aktivitas pemberitahuan akan tampil di sini setelah
                        pemberitahuan dibuat.
                    </p>

                </div>


            @endforelse

        </div>



        {{-- =====================================================
             PAGINATION
        ====================================================== --}}

        @if($pemberitahuans->hasPages())

            <div class="monitoring-pagination">

                {{ $pemberitahuans->withQueryString()->links() }}

            </div>

        @endif


    </div>

</div>

@endsection