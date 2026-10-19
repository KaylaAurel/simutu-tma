@extends('layouts.app')

@section('title', 'Detail Monitoring')
@section('page-title', 'Detail Monitoring')

@section('content')

<style>
    .monitoring-detail-page {
        width: 100%;
        max-width: 1500px;
        margin: 0 auto;
        padding: 8px 24px 50px;
    }

    /* =========================================================
       HEADER
    ========================================================== */

    .monitoring-detail-header {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 24px;
    }

    .monitoring-detail-breadcrumb {
        display: flex;
        align-items: center;
        gap: 9px;
        margin-bottom: 14px;
        color: #7b8981;
        font-size: 11px;
    }

    .monitoring-detail-breadcrumb a {
        color: #668073;
        text-decoration: none;
    }

    .monitoring-detail-breadcrumb a:hover {
        color: #347d50;
    }

    .monitoring-detail-breadcrumb i {
        color: #aab4ae;
        font-size: 9px;
    }

    .monitoring-detail-title-row {
        display: flex;
        align-items: center;
        gap: 13px;
    }

    .monitoring-detail-title-icon {
        width: 46px;
        height: 46px;
        min-width: 46px;
        border-radius: 13px;
        background: #eaf6ee;
        color: #347d50;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
    }

    .monitoring-detail-title-row h2 {
        margin: 0 0 5px;
        color: #193c2a;
        font-size: 27px;
        font-weight: 800;
    }

    .monitoring-detail-title-row p {
        margin: 0;
        color: #7c8981;
        font-size: 12px;
    }

    .monitoring-back-button {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 14px;
        border: 1px solid #dfe9e2;
        border-radius: 9px;
        background: #fff;
        color: #587064;
        text-decoration: none;
        font-size: 11px;
        font-weight: 700;
        transition: .18s ease;
    }

    .monitoring-back-button:hover {
        background: #f4f9f5;
        color: #347d50;
        border-color: #cfe0d4;
    }

    /* =========================================================
       STATUS HERO
    ========================================================== */

    .monitoring-status-hero {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 20px 22px;
        margin-bottom: 20px;
        border-radius: 14px;
        border: 1px solid #e3ebe6;
        background: #fff;
    }

    .monitoring-status-hero.success {
        background: #f7fbf8;
        border-color: #dcebe0;
    }

    .monitoring-status-hero.warning {
        background: #fffaf0;
        border-color: #f0e5c6;
    }

    .monitoring-status-hero.danger {
        background: #fff7f7;
        border-color: #f0dddd;
    }

    .monitoring-status-hero.neutral {
        background: #f9faf9;
        border-color: #e5eae7;
    }

    .monitoring-status-icon {
        width: 50px;
        height: 50px;
        min-width: 50px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }

    .monitoring-status-hero.success .monitoring-status-icon {
        background: #e6f4ea;
        color: #3c8957;
    }

    .monitoring-status-hero.warning .monitoring-status-icon {
        background: #fff2cf;
        color: #b18122;
    }

    .monitoring-status-hero.danger .monitoring-status-icon {
        background: #fbe9e9;
        color: #bd5a5a;
    }

    .monitoring-status-hero.neutral .monitoring-status-icon {
        background: #eef2f0;
        color: #73837a;
    }

    .monitoring-status-content {
        flex: 1;
        min-width: 0;
    }

    .monitoring-status-label {
        display: block;
        margin-bottom: 4px;
        color: #87948c;
        font-size: 9px;
        font-weight: 800;
        letter-spacing: 1px;
    }

    .monitoring-status-content h3 {
        margin: 0 0 4px;
        color: #203d2d;
        font-size: 19px;
        font-weight: 800;
    }

    .monitoring-status-content p {
        margin: 0;
        color: #78867e;
        font-size: 11px;
        line-height: 1.5;
    }

    .monitoring-status-meta {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 7px;
        white-space: nowrap;
    }

    .monitoring-status-meta span {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        color: #66766d;
        font-size: 10px;
    }

    .monitoring-status-meta i {
        color: #5b906d;
    }

    /* =========================================================
       SUMMARY
    ========================================================== */

    .monitoring-summary-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 22px;
    }

    .monitoring-summary-card {
        display: flex;
        align-items: center;
        gap: 13px;
        padding: 18px;
        border-radius: 13px;
        border: 1px solid #e5ebe7;
        background: #fff;
    }

    .monitoring-summary-icon {
        width: 42px;
        height: 42px;
        min-width: 42px;
        border-radius: 11px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
    }

    .monitoring-summary-icon.blue {
        background: #edf5fd;
        color: #4779b2;
    }

    .monitoring-summary-icon.yellow {
        background: #fff7df;
        color: #aa8127;
    }

    .monitoring-summary-icon.green {
        background: #eaf6ee;
        color: #3d8856;
    }

    .monitoring-summary-icon.red {
        background: #fceeee;
        color: #ba5a5a;
    }

    .monitoring-summary-card span {
        display: block;
        margin-bottom: 4px;
        color: #819087;
        font-size: 9px;
        font-weight: 800;
        letter-spacing: .7px;
    }

    .monitoring-summary-card strong {
        display: block;
        margin-bottom: 2px;
        color: #213d2d;
        font-size: 21px;
        line-height: 1;
        font-weight: 800;
    }

    .monitoring-summary-card small {
        color: #9aa49f;
        font-size: 9px;
    }

    /* =========================================================
       CARD
    ========================================================== */

    .monitoring-detail-card {
        margin-bottom: 20px;
        padding: 0;
        overflow: hidden;
    }

    .monitoring-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        padding: 19px 22px;
        border-bottom: 1px solid #edf1ee;
    }

    .monitoring-card-eyebrow {
        display: block;
        margin-bottom: 4px;
        color: #839188;
        font-size: 9px;
        font-weight: 800;
        letter-spacing: 1px;
    }

    .monitoring-card-header h3 {
        margin: 0 0 4px;
        color: #203d2d;
        font-size: 17px;
        font-weight: 800;
    }

    .monitoring-card-header p {
        margin: 0;
        color: #89958e;
        font-size: 10px;
    }

    /* =========================================================
       TIMELINE
    ========================================================== */

    .monitoring-timeline {
        padding: 22px 25px 25px;
    }

    .monitoring-timeline-item {
        position: relative;
        display: flex;
        gap: 14px;
        min-height: 88px;
    }

    .monitoring-timeline-item:not(:last-child)::after {
        content: "";
        position: absolute;
        left: 17px;
        top: 38px;
        bottom: 0;
        width: 1px;
        background: #dfe7e2;
    }

    .monitoring-timeline-marker {
        position: relative;
        z-index: 2;
        width: 35px;
        height: 35px;
        min-width: 35px;
        border-radius: 10px;
        background: #eaf6ee;
        color: #347d50;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
    }

    .monitoring-timeline-item.pending .monitoring-timeline-marker {
        background: #f1f4f2;
        color: #89958e;
    }

    .monitoring-timeline-content {
        flex: 1;
        padding-bottom: 22px;
    }

    .monitoring-timeline-content > div {
        display: flex;
        align-items: center;
        gap: 9px;
        margin-bottom: 4px;
    }

    .monitoring-timeline-content strong {
        color: #30483a;
        font-size: 12px;
        font-weight: 750;
    }

    .monitoring-timeline-content p {
        margin: 0 0 7px;
        color: #7f8c85;
        font-size: 10px;
        line-height: 1.5;
    }

    .monitoring-timeline-content small {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        color: #9aa49f;
        font-size: 9px;
    }

    .timeline-complete,
    .timeline-pending {
        display: inline-flex;
        padding: 4px 7px;
        border-radius: 6px;
        font-size: 8px;
        font-weight: 700;
    }

    .timeline-complete {
        background: #eaf6ee;
        color: #398152;
    }

    .timeline-pending {
        background: #f1f3f2;
        color: #89948e;
    }

    /* =========================================================
       PTA
    ========================================================== */

    .monitoring-pta-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 15px;
        padding: 20px 22px 22px;
    }

    .monitoring-pta-card {
        padding: 16px;
        border: 1px solid #e5ebe7;
        border-radius: 12px;
        background: #fff;
    }

    .monitoring-pta-header {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 13px;
    }

    .monitoring-pta-avatar {
        width: 38px;
        height: 38px;
        min-width: 38px;
        border-radius: 10px;
        background: #edf6f0;
        color: #4b8860;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
    }

    .monitoring-pta-info {
        min-width: 0;
        flex: 1;
    }

    .monitoring-pta-info strong {
        display: block;
        margin-bottom: 3px;
        color: #30483a;
        font-size: 11px;
        font-weight: 750;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .monitoring-pta-info span {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        color: #8b9790;
        font-size: 9px;
    }

    .monitoring-pta-info i {
        color: #5b906d;
    }

    .monitoring-pta-status {
        margin-bottom: 14px;
    }

    .monitoring-status-pill {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 5px 8px;
        border-radius: 7px;
        font-size: 9px;
        font-weight: 700;
    }

    .monitoring-status-pill.success {
        color: #357b4c;
        background: #eaf6ee;
    }

    .monitoring-status-pill.warning {
        color: #9b7623;
        background: #fff5d8;
    }

    .monitoring-status-pill.danger {
        color: #b45252;
        background: #fcecec;
    }

    .monitoring-status-pill.neutral {
        color: #78867e;
        background: #f1f4f2;
    }

    .monitoring-pta-stat-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 7px;
        margin-bottom: 14px;
    }

    .monitoring-pta-stat-grid > div {
        padding: 9px;
        border-radius: 8px;
        background: #f8faf9;
        text-align: center;
    }

    .monitoring-pta-stat-grid span {
        display: block;
        margin-bottom: 3px;
        color: #8a968f;
        font-size: 8px;
    }

    .monitoring-pta-stat-grid strong {
        color: #354d40;
        font-size: 14px;
    }

    .monitoring-pta-stat-grid .success-text {
        color: #398152;
    }

    .monitoring-pta-stat-grid .danger-text {
        color: #b85a5a;
    }

    .monitoring-progress-wrapper {
        margin-bottom: 13px;
    }

    .monitoring-progress-header {
        display: flex;
        justify-content: space-between;
        gap: 10px;
        margin-bottom: 6px;
    }

    .monitoring-progress-header span {
        color: #89958e;
        font-size: 8px;
    }

    .monitoring-progress-header strong {
        color: #4d6557;
        font-size: 9px;
    }

    .monitoring-progress {
        width: 100%;
        height: 6px;
        overflow: hidden;
        border-radius: 99px;
        background: #edf1ee;
    }

    .monitoring-progress span {
        display: block;
        height: 100%;
        border-radius: inherit;
        background: #4e9464;
    }

    .monitoring-pta-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        padding-top: 11px;
        border-top: 1px solid #edf1ee;
        color: #89958e;
        font-size: 8px;
    }

    .monitoring-pta-footer span {
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .monitoring-pta-footer i {
        color: #619174;
    }

    /* =========================================================
       MESSAGE
    ========================================================== */

    .monitoring-message-box {
        margin: 20px 22px 22px;
        border: 1px solid #e2e9e4;
        border-radius: 11px;
        overflow: hidden;
    }

    .monitoring-message-header {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 13px 15px;
        background: #f7faf8;
        border-bottom: 1px solid #e5ebe7;
    }

    .monitoring-message-icon {
        width: 34px;
        height: 34px;
        border-radius: 9px;
        background: #e7f5eb;
        color: #398453;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
    }

    .monitoring-message-header strong {
        display: block;
        margin-bottom: 2px;
        color: #3c5145;
        font-size: 10px;
    }

    .monitoring-message-header span {
        color: #929d96;
        font-size: 8px;
    }

    .monitoring-message-content {
        padding: 17px;
        color: #536159;
        font-size: 11px;
        line-height: 1.7;
        white-space: normal;
        word-break: break-word;
    }

    /* =========================================================
       EMPTY
    ========================================================== */

    .monitoring-empty-state {
        grid-column: 1 / -1;
        padding: 50px 20px;
        text-align: center;
    }

    .monitoring-empty-icon {
        width: 56px;
        height: 56px;
        margin: 0 auto 13px;
        border-radius: 15px;
        background: #f0f5f2;
        color: #819188;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
    }

    .monitoring-empty-state h4 {
        margin: 0 0 6px;
        color: #40564a;
        font-size: 14px;
    }

    .monitoring-empty-state p {
        margin: 0;
        color: #8c9891;
        font-size: 10px;
    }

    /* =========================================================
       RESPONSIVE
    ========================================================== */

    @media(max-width: 1100px) {

        .monitoring-summary-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .monitoring-pta-grid {
            grid-template-columns: 1fr;
        }

    }

    @media(max-width: 800px) {

        .monitoring-detail-page {
            padding: 8px 14px 35px;
        }

        .monitoring-detail-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .monitoring-status-hero {
            align-items: flex-start;
            flex-wrap: wrap;
        }

        .monitoring-status-meta {
            width: 100%;
            align-items: flex-start;
            padding-left: 66px;
        }

    }

    @media(max-width: 600px) {

        .monitoring-summary-grid {
            grid-template-columns: 1fr;
        }

        .monitoring-detail-title-row h2 {
            font-size: 22px;
        }

        .monitoring-status-content h3 {
            font-size: 17px;
        }

        .monitoring-card-header {
            align-items: flex-start;
            flex-direction: column;
        }

    }
</style>


@php

    /*
    |--------------------------------------------------------------------------
    | DETAILS
    |--------------------------------------------------------------------------
    */

    $details = $pemberitahuan->details ?? collect();


    /*
    |--------------------------------------------------------------------------
    | TOTAL PTA
    |--------------------------------------------------------------------------
    */

    $totalPta = $details
        ->pluck('pta_id')
        ->filter()
        ->unique()
        ->count();


    /*
    |--------------------------------------------------------------------------
    | TOTAL MUTU D
    |--------------------------------------------------------------------------
    */

    $totalMutuD = $details
        ->pluck('mutu_d_id')
        ->filter()
        ->unique()
        ->count();


    /*
    |--------------------------------------------------------------------------
    | STATUS DETAIL
    |--------------------------------------------------------------------------
    */

    $sentCount = $details
        ->whereIn('status', ['sent', 'terkirim', 'delivered'])
        ->count();

    $failedCount = $details
        ->whereIn('status', ['failed', 'gagal'])
        ->count();

    $pendingCount = $details
        ->whereNotIn(
            'status',
            [
                'sent',
                'terkirim',
                'delivered',
                'failed',
                'gagal'
            ]
        )
        ->count();


    /*
    |--------------------------------------------------------------------------
    | STATUS UTAMA
    |--------------------------------------------------------------------------
    */

    $status = $pemberitahuan->status_pesan
        ?? 'draft';

    $status = strtolower(trim($status));


    /*
    |--------------------------------------------------------------------------
    | NORMALISASI STATUS
    |--------------------------------------------------------------------------
    */

    if (
        in_array(
            $status,
            ['sent', 'terkirim', 'delivered']
        )
    ) {

        $statusClass = 'success';

        $statusIcon = 'bi-check-circle-fill';

        $statusTitle = 'Pengiriman Berhasil';

        $statusDescription =
            'Pemberitahuan berhasil dikirim kepada PTA.';

    } elseif (
        in_array(
            $status,
            ['partial', 'sebagian']
        )
    ) {

        $statusClass = 'warning';

        $statusIcon = 'bi-exclamation-circle-fill';

        $statusTitle = 'Sebagian Berhasil';

        $statusDescription =
            'Sebagian pemberitahuan berhasil dikirim dan sebagian lainnya belum berhasil.';

    } elseif (
        in_array(
            $status,
            ['failed', 'gagal']
        )
    ) {

        $statusClass = 'danger';

        $statusIcon = 'bi-x-circle-fill';

        $statusTitle = 'Pengiriman Gagal';

        $statusDescription =
            'Pemberitahuan belum berhasil dikirim kepada PTA.';

    } elseif (
        in_array(
            $status,
            ['processing', 'pending', 'diproses']
        )
    ) {

        $statusClass = 'warning';

        $statusIcon = 'bi-hourglass-split';

        $statusTitle = 'Sedang Diproses';

        $statusDescription =
            'Pemberitahuan sedang diproses oleh sistem.';

    } else {

        $statusClass = 'neutral';

        $statusIcon = 'bi-clock-fill';

        $statusTitle = 'Draft';

        $statusDescription =
            'Pemberitahuan belum dikirim melalui WhatsApp.';

    }


    /*
    |--------------------------------------------------------------------------
    | TANGGAL PEMBERITAHUAN
    |--------------------------------------------------------------------------
    */

    $tanggal = $pemberitahuan->tanggal
        ?? $pemberitahuan->tanggal_pemberitahuan
        ?? $pemberitahuan->created_at;


    /*
    |--------------------------------------------------------------------------
    | WAKTU KIRIM
    |--------------------------------------------------------------------------
    |
    | Ambil sent_at terbaru dari detail.
    |
    */

    $latestSentAt = $details
        ->pluck('sent_at')
        ->filter()
        ->sortDesc()
        ->first();


    /*
    |--------------------------------------------------------------------------
    | FALLBACK SENT AT PARENT
    |--------------------------------------------------------------------------
    */

    if (!$latestSentAt) {

        $latestSentAt =
            $pemberitahuan->sent_at
            ?? $pemberitahuan->tanggal_kirim
            ?? null;

    }


    /*
    |--------------------------------------------------------------------------
    | PESAN
    |--------------------------------------------------------------------------
    */

    $message =
        $pemberitahuan->isi
        ?? $pemberitahuan->isi_pesan
        ?? $pemberitahuan->pesan
        ?? null;

@endphp


<div class="monitoring-detail-page">

    {{-- =========================================================
         HEADER
    ========================================================== --}}

    <div class="monitoring-detail-header">

        <div>

            <div class="monitoring-detail-breadcrumb">

                <a href="{{ route('monitoring.index') }}">
                    Monitoring
                </a>

                <i class="bi bi-chevron-right"></i>

                <span>
                    Detail
                </span>

            </div>


            <div class="monitoring-detail-title-row">

                <div class="monitoring-detail-title-icon">
                    <i class="bi bi-broadcast-pin"></i>
                </div>

                <div>

                    <h2>
                        {{ $pemberitahuan->nomor ?? 'Pemberitahuan' }}
                    </h2>

                    <p>
                        Detail status pengiriman pemberitahuan Mutu D kepada PTA.
                    </p>

                </div>

            </div>

        </div>


        <a
            href="{{ route('monitoring.index') }}"
            class="monitoring-back-button"
        >

            <i class="bi bi-arrow-left"></i>

            Kembali

        </a>

    </div>


    {{-- =========================================================
         STATUS HERO
    ========================================================== --}}

    <div class="monitoring-status-hero {{ $statusClass }}">

        <div class="monitoring-status-icon">

            <i class="bi {{ $statusIcon }}"></i>

        </div>


        <div class="monitoring-status-content">

            <span class="monitoring-status-label">
                STATUS PENGIRIMAN
            </span>

            <h3>
                {{ $statusTitle }}
            </h3>

            <p>
                {{ $statusDescription }}
            </p>

        </div>


        <div class="monitoring-status-meta">

            <span>

                <i class="bi bi-calendar3"></i>

                @if($tanggal)

                    {{ \Carbon\Carbon::parse($tanggal)
                        ->locale('id')
                        ->translatedFormat('d M Y') }}

                @else

                    -

                @endif

            </span>


            <span>

                <i class="bi bi-clock"></i>

                @if($latestSentAt)

                    {{ \Carbon\Carbon::parse($latestSentAt)
                        ->format('H:i') }}

                @else

                    Belum dikirim

                @endif

            </span>

        </div>

    </div>


    {{-- =========================================================
         SUMMARY
    ========================================================== --}}

    <div class="monitoring-summary-grid">

        {{-- TOTAL PTA --}}
        <div class="monitoring-summary-card">

            <div class="monitoring-summary-icon blue">
                <i class="bi bi-people-fill"></i>
            </div>

            <div>

                <span>
                    TOTAL PTA
                </span>

                <strong>
                    {{ $totalPta }}
                </strong>

                <small>
                    Penerima pemberitahuan
                </small>

            </div>

        </div>


        {{-- TOTAL MUTU D --}}
        <div class="monitoring-summary-card">

            <div class="monitoring-summary-icon yellow">
                <i class="bi bi-clipboard2-pulse-fill"></i>
            </div>

            <div>

                <span>
                    TOTAL MUTU D
                </span>

                <strong>
                    {{ $totalMutuD }}
                </strong>

                <small>
                    Data yang diberitahukan
                </small>

            </div>

        </div>


        {{-- BERHASIL --}}
        <div class="monitoring-summary-card">

            <div class="monitoring-summary-icon green">
                <i class="bi bi-check-circle-fill"></i>
            </div>

            <div>

                <span>
                    BERHASIL
                </span>

                <strong>
                    {{ $sentCount }}
                </strong>

                <small>
                    Data berhasil dikirim
                </small>

            </div>

        </div>


        {{-- GAGAL --}}
        <div class="monitoring-summary-card">

            <div class="monitoring-summary-icon red">
                <i class="bi bi-x-circle-fill"></i>
            </div>

            <div>

                <span>
                    GAGAL
                </span>

                <strong>
                    {{ $failedCount }}
                </strong>

                <small>
                    Data tidak terkirim
                </small>

            </div>

        </div>

    </div>


    {{-- =========================================================
         TIMELINE
    ========================================================== --}}

    <div class="simutu-card monitoring-detail-card">

        <div class="monitoring-card-header">

            <div>

                <span class="monitoring-card-eyebrow">
                    TIMELINE
                </span>

                <h3>
                    Riwayat Proses
                </h3>

                <p>
                    Perjalanan data sejak dipilih hingga proses pengiriman.
                </p>

            </div>

        </div>


        <div class="monitoring-timeline">

            {{-- STEP 1 --}}
            <div class="monitoring-timeline-item completed">

                <div class="monitoring-timeline-marker">

                    <i class="bi bi-database-check"></i>

                </div>


                <div class="monitoring-timeline-content">

                    <div>

                        <strong>
                            Data Mutu D dipilih
                        </strong>

                        <span class="timeline-complete">
                            Selesai
                        </span>

                    </div>

                    <p>
                        Data Mutu D dipilih untuk dibuat menjadi pemberitahuan.
                    </p>

                    <small>

                        <i class="bi bi-clock"></i>

                        @if($pemberitahuan->created_at)

                            {{ \Carbon\Carbon::parse($pemberitahuan->created_at)
                                ->locale('id')
                                ->translatedFormat('d M Y, H:i') }}

                        @else

                            -

                        @endif

                    </small>

                </div>

            </div>


            {{-- STEP 2 --}}
            <div class="monitoring-timeline-item completed">

                <div class="monitoring-timeline-marker">

                    <i class="bi bi-file-earmark-text-fill"></i>

                </div>


                <div class="monitoring-timeline-content">

                    <div>

                        <strong>
                            Pemberitahuan dibuat
                        </strong>

                        <span class="timeline-complete">
                            Selesai
                        </span>

                    </div>

                    <p>
                        Sistem membuat draft pemberitahuan berdasarkan data Mutu D yang dipilih.
                    </p>

                    <small>

                        <i class="bi bi-clock"></i>

                        @if($pemberitahuan->created_at)

                            {{ \Carbon\Carbon::parse($pemberitahuan->created_at)
                                ->locale('id')
                                ->translatedFormat('d M Y, H:i') }}

                        @else

                            -

                        @endif

                    </small>

                </div>

            </div>


            {{-- STEP 3 --}}
            <div class="monitoring-timeline-item {{ $latestSentAt ? 'completed' : 'pending' }}">

                <div class="monitoring-timeline-marker">

                    @if($latestSentAt)

                        <i class="bi bi-whatsapp"></i>

                    @else

                        <i class="bi bi-clock-fill"></i>

                    @endif

                </div>


                <div class="monitoring-timeline-content">

                    <div>

                        <strong>
                            Pengiriman WhatsApp
                        </strong>

                        @if($latestSentAt)

                            <span class="timeline-complete">
                                Selesai
                            </span>

                        @else

                            <span class="timeline-pending">
                                Menunggu
                            </span>

                        @endif

                    </div>


                    <p>

                        @if($latestSentAt)

                            Pemberitahuan telah diproses untuk dikirim kepada PTA.

                        @else

                            Pemberitahuan belum dikirim melalui WhatsApp.

                        @endif

                    </p>


                    <small>

                        <i class="bi bi-clock"></i>

                        @if($latestSentAt)

                            {{ \Carbon\Carbon::parse($latestSentAt)
                                ->locale('id')
                                ->translatedFormat('d M Y, H:i') }}

                        @else

                            Belum dikirim

                        @endif

                    </small>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
         PTA STATUS
    ========================================================== --}}

    <div class="simutu-card monitoring-detail-card">

        <div class="monitoring-card-header">

            <div>

                <span class="monitoring-card-eyebrow">
                    DETAIL PENERIMA
                </span>

                <h3>
                    Status Setiap PTA
                </h3>

                <p>
                    Pantau keberhasilan pengiriman untuk masing-masing PTA.
                </p>

            </div>

            <div class="monitoring-live-indicator"
                 style="
                    display:inline-flex;
                    align-items:center;
                    gap:6px;
                    padding:6px 9px;
                    border-radius:8px;
                    background:#edf8f0;
                    color:#438255;
                    font-size:9px;
                    font-weight:700;
                 ">

                <span style="
                    width:6px;
                    height:6px;
                    border-radius:50%;
                    background:#53a565;
                    display:block;
                 "></span>

                Monitoring

            </div>

        </div>


        <div class="monitoring-pta-grid">

            @forelse($details->groupBy('pta_id') as $ptaDetails)

                @php

                    $pta = $ptaDetails->first()->pta;

                    $success = $ptaDetails
                        ->whereIn(
                            'status',
                            [
                                'sent',
                                'terkirim',
                                'delivered'
                            ]
                        )
                        ->count();

                    $failed = $ptaDetails
                        ->whereIn(
                            'status',
                            [
                                'failed',
                                'gagal'
                            ]
                        )
                        ->count();

                    $pending = $ptaDetails
                        ->whereNotIn(
                            'status',
                            [
                                'sent',
                                'terkirim',
                                'delivered',
                                'failed',
                                'gagal'
                            ]
                        )
                        ->count();

                    $total = $ptaDetails->count();

                    $percentage = $total > 0
                        ? ($success / $total) * 100
                        : 0;

                @endphp


                <div class="monitoring-pta-card">

                    {{-- HEADER PTA --}}
                    <div class="monitoring-pta-header">

                        <div class="monitoring-pta-avatar">

                            <i class="bi bi-person-badge-fill"></i>

                        </div>


                        <div class="monitoring-pta-info">

                            <strong>

                                {{ $pta->nama_pta
                                    ?? $pta->nama
                                    ?? 'PTA Tidak Diketahui' }}

                            </strong>

                            <span>

                                <i class="bi bi-whatsapp"></i>

                                {{ $pta->no_whatsapp
                                    ?? $pta->nomor_whatsapp
                                    ?? '-' }}

                            </span>

                        </div>

                    </div>


                    {{-- STATUS PTA --}}
                    <div class="monitoring-pta-status">

                        @if($success === $total && $total > 0)

                            <span class="monitoring-status-pill success">

                                <i class="bi bi-check-circle-fill"></i>

                                Berhasil

                            </span>

                        @elseif($failed > 0 && $success > 0)

                            <span class="monitoring-status-pill warning">

                                <i class="bi bi-exclamation-circle-fill"></i>

                                Sebagian

                            </span>

                        @elseif($failed === $total && $total > 0)

                            <span class="monitoring-status-pill danger">

                                <i class="bi bi-x-circle-fill"></i>

                                Gagal

                            </span>

                        @else

                            <span class="monitoring-status-pill neutral">

                                <i class="bi bi-clock-fill"></i>

                                Pending

                            </span>

                        @endif

                    </div>


                    {{-- STAT --}}
                    <div class="monitoring-pta-stat-grid">

                        <div>

                            <span>
                                Total
                            </span>

                            <strong>
                                {{ $total }}
                            </strong>

                        </div>


                        <div>

                            <span>
                                Berhasil
                            </span>

                            <strong class="success-text">
                                {{ $success }}
                            </strong>

                        </div>


                        <div>

                            <span>
                                Gagal
                            </span>

                            <strong class="danger-text">
                                {{ $failed }}
                            </strong>

                        </div>

                    </div>


                    {{-- PROGRESS --}}
                    <div class="monitoring-progress-wrapper">

                        <div class="monitoring-progress-header">

                            <span>
                                Tingkat keberhasilan
                            </span>

                            <strong>
                                {{ round($percentage) }}%
                            </strong>

                        </div>


                        <div class="monitoring-progress">

                            <span
                                style="width: {{ min(100, max(0, $percentage)) }}%"
                            ></span>

                        </div>

                    </div>


                    {{-- FOOTER --}}
                    <div class="monitoring-pta-footer">

                        <span>

                            <i class="bi bi-clipboard-check"></i>

                            {{ $total }} data Mutu D

                        </span>


                        @if($pending > 0)

                            <span>
                                {{ $pending }} pending
                            </span>

                        @elseif($failed > 0)

                            <span>
                                {{ $failed }} gagal
                            </span>

                        @else

                            <span>
                                Semua diproses
                            </span>

                        @endif

                    </div>

                </div>

            @empty

                <div class="monitoring-empty-state">

                    <div class="monitoring-empty-icon">

                        <i class="bi bi-people"></i>

                    </div>

                    <h4>
                        Belum ada data penerima
                    </h4>

                    <p>
                        Detail PTA penerima akan tampil di sini.
                    </p>

                </div>

            @endforelse

        </div>

    </div>


    {{-- =========================================================
         MESSAGE
    ========================================================== --}}

    <div class="simutu-card monitoring-detail-card">

        <div class="monitoring-card-header">

            <div>

                <span class="monitoring-card-eyebrow">
                    PESAN
                </span>

                <h3>
                    Isi Pemberitahuan
                </h3>

                <p>
                    Pesan yang digunakan dalam proses pemberitahuan.
                </p>

            </div>

        </div>


        <div class="monitoring-message-box">

            <div class="monitoring-message-header">

                <div class="monitoring-message-icon">

                    <i class="bi bi-whatsapp"></i>

                </div>


                <div>

                    <strong>
                        Pesan WhatsApp
                    </strong>

                    <span>
                        SIMUTU-TMA
                    </span>

                </div>

            </div>


            <div class="monitoring-message-content">

                @if($message)

                    {!! nl2br(e($message)) !!}

                @else

                    <span style="color:#929d96;">
                        Belum ada isi pemberitahuan.
                    </span>

                @endif

            </div>

        </div>

    </div>

</div>

@endsection