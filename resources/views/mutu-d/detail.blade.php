@extends('layouts.app')

@section('title', 'Detail Mutu D')
@section('page-title', 'Detail Mutu D')

@section('content')

<style>
    /* =========================================================
       MUTU D DETAIL PAGE
    ========================================================= */

    .mutu-detail-page {
        width: 100%;
        max-width: 1480px;
        margin: 0 auto;
        padding: 8px 28px 60px;
        box-sizing: border-box;
    }

    /* =========================================================
       HEADER
    ========================================================= */

    .mutu-detail-page .page-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        gap: 25px;
        margin-bottom: 28px;
    }

    .mutu-detail-header-left {
        min-width: 0;
    }

    .mutu-detail-page .breadcrumb {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 10px;
        font-size: 12px;
        color: #87938c;
    }

    .mutu-detail-page .breadcrumb a {
        color: #31804f;
        text-decoration: none;
        font-weight: 700;
    }

    .mutu-detail-page .breadcrumb a:hover {
        text-decoration: underline;
    }

    .mutu-detail-page .breadcrumb i {
        font-size: 10px;
        color: #a4aea8;
    }

    .mutu-detail-page .page-header h2 {
        margin: 0 0 7px;
        color: #1d3929;
        font-size: 28px;
        line-height: 1.25;
        font-weight: 800;
        letter-spacing: -.4px;
    }

    .mutu-detail-page .page-header p {
        margin: 0;
        color: #78857d;
        font-size: 13px;
    }

    .mutu-back-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        min-height: 42px;
        padding: 0 17px;
        border: 1px solid #dce6df;
        border-radius: 11px;
        background: #fff;
        color: #365141;
        text-decoration: none;
        font-size: 13px;
        font-weight: 700;
        white-space: nowrap;
        box-shadow: 0 4px 14px rgba(31, 70, 46, .04);
        transition: .2s ease;
    }

    .mutu-back-button:hover {
        color: #247142;
        border-color: #bdd8c5;
        background: #f8fcf9;
        transform: translateY(-1px);
    }

    /* =========================================================
       HERO
    ========================================================= */

    .mutu-detail-hero {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 25px;
        padding: 27px 30px;
        margin-bottom: 22px;
        border: 1px solid #dcebe1;
        border-radius: 22px;
        background:
            radial-gradient(circle at 100% 0%, rgba(70, 151, 94, .08), transparent 34%),
            linear-gradient(135deg, #f2faf4 0%, #ffffff 70%);
        box-shadow: 0 9px 28px rgba(30, 73, 46, .055);
        overflow: hidden;
    }

    .mutu-detail-hero::after {
        content: "";
        position: absolute;
        width: 190px;
        height: 190px;
        right: -85px;
        bottom: -105px;
        border-radius: 50%;
        background: rgba(56, 135, 81, .055);
        pointer-events: none;
    }

    .mutu-detail-main {
        display: flex;
        align-items: center;
        gap: 18px;
        min-width: 0;
        position: relative;
        z-index: 1;
    }

    .mutu-detail-avatar {
        width: 68px;
        height: 68px;
        min-width: 68px;
        border-radius: 19px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(145deg, #378452, #246b3e);
        color: #fff;
        font-size: 26px;
        font-weight: 800;
        box-shadow: 0 8px 20px rgba(42, 111, 65, .20);
    }

    .mutu-detail-label {
        display: block;
        margin-bottom: 5px;
        color: #57906b;
        font-size: 10px;
        font-weight: 800;
        letter-spacing: 1.7px;
    }

    .mutu-detail-main h3 {
        margin: 0;
        color: #1d3929;
        font-size: 22px;
        font-weight: 800;
        line-height: 1.25;
    }

    .mutu-detail-main p {
        margin: 6px 0 0;
        color: #718078;
        font-size: 13px;
    }

    .mutu-detail-main p strong {
        color: #34503e;
        font-weight: 700;
    }

    .mutu-detail-grade {
        position: relative;
        z-index: 2;
        min-width: 135px;
        padding: 16px 20px;
        border: 1px solid #f0d8d5;
        border-radius: 17px;
        background: linear-gradient(145deg, #fff6f4, #fffafa);
        text-align: center;
    }

    .mutu-detail-grade span {
        display: block;
        margin-bottom: 3px;
        color: #a07874;
        font-size: 9px;
        font-weight: 800;
        letter-spacing: 1.4px;
    }

    .mutu-detail-grade strong {
        display: block;
        color: #c04c45;
        font-size: 30px;
        line-height: 1.1;
        font-weight: 900;
    }

    .mutu-detail-grade small {
        display: block;
        margin-top: 2px;
        color: #9b7773;
        font-size: 11px;
    }

    /* =========================================================
       GENERAL CARD
    ========================================================= */

    .mutu-detail-card {
        background: #fff;
        border: 1px solid #e4ebe7;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 7px 25px rgba(28, 63, 42, .045);
    }

    .mutu-detail-card + .mutu-detail-card {
        margin-top: 22px;
    }

    .mutu-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        padding: 20px 24px;
        border-bottom: 1px solid #edf1ee;
    }

    .mutu-card-header-left {
        min-width: 0;
    }

    .mutu-card-eyebrow {
        display: block;
        margin-bottom: 5px;
        color: #378153;
        font-size: 10px;
        font-weight: 800;
        letter-spacing: 1.6px;
    }

    .mutu-card-header h3 {
        margin: 0;
        color: #203d2c;
        font-size: 17px;
        line-height: 1.3;
        font-weight: 800;
    }

    .mutu-card-header p {
        margin: 5px 0 0;
        color: #829087;
        font-size: 12px;
    }

    .mutu-count-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 42px;
        height: 30px;
        padding: 0 10px;
        border-radius: 9px;
        background: #edf7f0;
        color: #348052;
        font-size: 11px;
        font-weight: 800;
        white-space: nowrap;
    }

    /* =========================================================
       MAIN CONTENT GRID
    ========================================================= */

    .mutu-main-grid {
        display: grid;
        grid-template-columns: minmax(0, 1.55fr) minmax(330px, .75fr);
        gap: 22px;
        align-items: start;
    }

    /* =========================================================
       INFORMATION CARD
    ========================================================= */

    .mutu-information-body {
        padding: 22px 24px 24px;
    }

    .detail-information-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 0;
        border: 1px solid #edf1ee;
        border-radius: 15px;
        overflow: hidden;
    }

    .detail-information-item {
        min-width: 0;
        padding: 16px 18px;
        background: #fff;
        border-right: 1px solid #edf1ee;
        border-bottom: 1px solid #edf1ee;
    }

    .detail-information-item:nth-child(2n) {
        border-right: none;
    }

    .detail-information-item:nth-last-child(-n+2) {
        border-bottom: none;
    }

    .detail-information-item span.label {
        display: block;
        margin-bottom: 6px;
        color: #89958e;
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .6px;
    }

    .detail-information-item strong.value {
        display: block;
        color: #294535;
        font-size: 13px;
        font-weight: 700;
        line-height: 1.45;
        overflow-wrap: anywhere;
    }

    .detail-information-item strong.value.monospace {
        font-family: "Courier New", monospace;
        font-size: 12px;
        color: #355245;
    }

    .detail-information-item.highlight {
        background: #fbfdfb;
    }

    /* =========================================================
       NOTE
    ========================================================= */

    .mutu-detail-note {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        margin-top: 18px;
        padding: 15px 17px;
        border: 1px solid #dfece3;
        border-radius: 13px;
        background: #f5faf6;
    }

    .mutu-detail-note > i {
        margin-top: 2px;
        color: #378153;
        font-size: 17px;
    }

    .mutu-detail-note strong {
        display: block;
        margin-bottom: 3px;
        color: #31523e;
        font-size: 12px;
    }

    .mutu-detail-note p {
        margin: 0;
        color: #718078;
        font-size: 12px;
        line-height: 1.55;
    }

    /* =========================================================
       SKORSING
    ========================================================= */

    .skorsing-card-body {
        padding: 22px;
    }

    .skorsing-status {
        display: flex;
        align-items: center;
        gap: 13px;
        padding: 16px;
        border-radius: 15px;
        border: 1px solid;
    }

    .skorsing-status.active {
        background: #fff9e9;
        border-color: #f3e3af;
    }

    .skorsing-status.success {
        background: #f0faf3;
        border-color: #d7eadc;
    }

    .skorsing-status.neutral {
        background: #f7f9f8;
        border-color: #e5ebe7;
    }

    .skorsing-icon {
        width: 44px;
        height: 44px;
        min-width: 44px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        font-size: 19px;
    }

    .skorsing-status.active .skorsing-icon {
        background: #fff0c7;
        color: #b78614;
    }

    .skorsing-status.success .skorsing-icon {
        background: #dff3e5;
        color: #31804f;
    }

    .skorsing-status.neutral .skorsing-icon {
        background: #e9efeb;
        color: #738179;
    }

    .skorsing-status span {
        display: block;
        margin-bottom: 3px;
        color: #89958e;
        font-size: 9px;
        font-weight: 800;
        letter-spacing: 1.2px;
    }

    .skorsing-status strong {
        display: block;
        color: #294535;
        font-size: 14px;
    }

    .skorsing-dates {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
        margin-top: 13px;
    }

    .skorsing-date-item {
        padding: 14px;
        border: 1px solid #e9eeeb;
        border-radius: 13px;
        background: #fbfcfb;
    }

    .skorsing-date-item span {
        display: block;
        margin-bottom: 5px;
        color: #8a968f;
        font-size: 10px;
        font-weight: 700;
    }

    .skorsing-date-item strong {
        display: block;
        color: #304c3b;
        font-size: 13px;
    }

    .skorsing-duration {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-top: 12px;
        padding: 12px 14px;
        border-radius: 12px;
        background: #f7faf8;
        border: 1px solid #e5ece7;
    }

    .skorsing-duration i {
        color: #378153;
        font-size: 17px;
    }

    .skorsing-duration span {
        color: #87928b;
        font-size: 11px;
    }

    .skorsing-duration strong {
        margin-left: auto;
        color: #2d4c39;
        font-size: 13px;
    }

    .skorsing-form {
        margin-top: 14px;
    }

    .skorsing-warning {
        display: flex;
        align-items: flex-start;
        gap: 9px;
        margin-top: 12px;
        color: #8b7b50;
        font-size: 10px;
        line-height: 1.5;
    }

    .skorsing-warning i {
        margin-top: 1px;
    }

    .mutu-primary-button {
        width: 100%;
        min-height: 45px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        border: none;
        border-radius: 11px;
        background: linear-gradient(135deg, #378452, #2a7242);
        color: #fff;
        font-size: 12px;
        font-weight: 800;
        cursor: pointer;
        box-shadow: 0 7px 18px rgba(44, 116, 67, .16);
        transition: .2s ease;
    }

    .mutu-primary-button:hover {
        background: linear-gradient(135deg, #2f7948, #23673a);
        transform: translateY(-1px);
        box-shadow: 0 9px 20px rgba(44, 116, 67, .21);
    }

    /* =========================================================
       NOTIFICATION
    ========================================================= */

    .notification-body {
        padding: 22px 24px 24px;
    }

    .notification-status-layout {
        display: grid;
        grid-template-columns: 1.1fr .9fr;
        gap: 14px;
    }

    .notification-status-box {
        display: flex;
        align-items: center;
        gap: 13px;
        min-height: 78px;
        padding: 15px 17px;
        border: 1px solid #e7ece9;
        border-radius: 15px;
        background: #fbfcfb;
    }

    .notification-status-icon {
        width: 43px;
        height: 43px;
        min-width: 43px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
    }

    .notification-status-icon.success {
        background: #e5f5ea;
        color: #2f8050;
    }

    .notification-status-icon.warning {
        background: #fff5d8;
        color: #b68716;
    }

    .notification-status-icon.danger {
        background: #fdeaea;
        color: #c44b47;
    }

    .notification-status-icon.neutral {
        background: #edf1ee;
        color: #748078;
    }

    .notification-status-text span {
        display: block;
        margin-bottom: 4px;
        color: #89958e;
        font-size: 9px;
        font-weight: 800;
        letter-spacing: 1px;
        text-transform: uppercase;
    }

    .notification-status-text strong {
        display: block;
        color: #294535;
        font-size: 13px;
    }

    .notification-meta-box {
        padding: 15px 17px;
        border: 1px solid #e7ece9;
        border-radius: 15px;
        background: #fbfcfb;
    }

    .notification-meta-box span {
        display: block;
        margin-bottom: 5px;
        color: #89958e;
        font-size: 9px;
        font-weight: 800;
        letter-spacing: 1px;
        text-transform: uppercase;
    }

    .notification-meta-box strong {
        display: block;
        color: #304c3b;
        font-size: 13px;
    }

    .notification-time {
        margin-top: 5px;
        color: #87938b;
        font-size: 11px;
    }

    /* =========================================================
       STATUS BADGES
    ========================================================= */

    .mutu-status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        width: fit-content;
        padding: 6px 10px;
        border-radius: 8px;
        font-size: 10px;
        font-weight: 800;
        white-space: nowrap;
    }

    .mutu-status-badge.success {
        background: #e8f6ed;
        color: #2e8050;
    }

    .mutu-status-badge.warning {
        background: #fff5d9;
        color: #ad8214;
    }

    .mutu-status-badge.danger {
        background: #fdeaea;
        color: #c24a46;
    }

    .mutu-status-badge.neutral {
        background: #edf1ee;
        color: #718078;
    }

    /* =========================================================
       PHOTO GALLERY
    ========================================================= */

    .photo-gallery {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 14px;
        padding: 22px 24px 25px;
    }

    .photo-gallery-item {
        position: relative;
        aspect-ratio: 4 / 3;
        overflow: hidden;
        border-radius: 14px;
        background: #f1f4f2;
        border: 1px solid #e5ebe7;
    }

    .photo-gallery-item img {
        width: 100%;
        height: 100%;
        display: block;
        object-fit: cover;
        transition: transform .3s ease;
    }

    .photo-gallery-item:hover img {
        transform: scale(1.045);
    }

    .photo-overlay {
        position: absolute;
        inset: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(20, 48, 31, .38);
        opacity: 0;
        transition: .2s ease;
    }

    .photo-gallery-item:hover .photo-overlay {
        opacity: 1;
    }

    .photo-overlay a {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 11px;
        background: rgba(255, 255, 255, .95);
        color: #2e7044;
        text-decoration: none;
        font-size: 16px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, .15);
    }

    /* =========================================================
       EMPTY STATE
    ========================================================= */

    .mutu-empty-state {
        padding: 42px 20px;
        text-align: center;
        color: #87928b;
    }

    .mutu-empty-icon {
        width: 55px;
        height: 55px;
        margin: 0 auto 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 15px;
        background: #f0f4f1;
        color: #809087;
        font-size: 23px;
    }

    .mutu-empty-state h4 {
        margin: 0 0 5px;
        color: #53635a;
        font-size: 14px;
    }

    .mutu-empty-state p {
        margin: 0;
        font-size: 11px;
    }

    /* =========================================================
       RESPONSIVE
    ========================================================= */

    @media (max-width: 1200px) {

        .mutu-main-grid {
            grid-template-columns: minmax(0, 1fr);
        }

        .photo-gallery {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }

    }

    @media (max-width: 850px) {

        .mutu-detail-page {
            padding: 8px 18px 45px;
        }

        .mutu-detail-page .page-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .mutu-back-button {
            width: 100%;
        }

        .mutu-detail-hero {
            align-items: flex-start;
            flex-direction: column;
            padding: 22px;
        }

        .mutu-detail-grade {
            width: 100%;
            box-sizing: border-box;
        }

        .notification-status-layout {
            grid-template-columns: 1fr;
        }

        .photo-gallery {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

    }

    @media (max-width: 600px) {

        .mutu-detail-page {
            padding: 5px 13px 35px;
        }

        .mutu-detail-page .page-header h2 {
            font-size: 22px;
        }

        .mutu-detail-hero {
            border-radius: 17px;
        }

        .mutu-detail-avatar {
            width: 56px;
            height: 56px;
            min-width: 56px;
            border-radius: 15px;
            font-size: 21px;
        }

        .mutu-detail-main h3 {
            font-size: 18px;
        }

        .mutu-card-header {
            padding: 17px;
        }

        .mutu-information-body,
        .notification-body,
        .skorsing-card-body {
            padding: 17px;
        }

        .detail-information-grid {
            grid-template-columns: 1fr;
        }

        .detail-information-item,
        .detail-information-item:nth-child(2n) {
            border-right: none;
            border-bottom: 1px solid #edf1ee;
        }

        .detail-information-item:last-child {
            border-bottom: none;
        }

        .skorsing-dates {
            grid-template-columns: 1fr;
        }

        .photo-gallery {
            grid-template-columns: 1fr 1fr;
            padding: 17px;
            gap: 10px;
        }

    }

    @media (max-width: 400px) {

        .photo-gallery {
            grid-template-columns: 1fr;
        }

        .mutu-detail-main {
            align-items: flex-start;
        }

    }
</style>


<div class="mutu-detail-page">

    {{-- =====================================================
        HEADER
    ====================================================== --}}

    <div class="page-header">

        <div class="mutu-detail-header-left">

            <div class="breadcrumb">

                <a href="{{ route('mutu-d.index') }}">
                    Data Mutu D
                </a>

                <i class="bi bi-chevron-right"></i>

                <span>
                    Detail
                </span>

            </div>

            <h2>
                Detail Data Mutu D
            </h2>

            <p>
                Informasi lengkap data tebu dengan kategori Mutu D.
            </p>

        </div>


        <a href="{{ route('mutu-d.index') }}"
           class="mutu-back-button">

            <i class="bi bi-arrow-left"></i>

            Kembali

        </a>

    </div>


    {{-- =====================================================
        DATA RELATION
    ====================================================== --}}

    @php

        /*
        |--------------------------------------------------------------------------
        | PTA
        |--------------------------------------------------------------------------
        */

        $namaPta = '-';

        if ($mutuD->pta) {

            $namaPta =
                $mutuD->pta->nama_pta
                ?? $mutuD->pta->nama
                ?? $mutuD->pta->name
                ?? '-';

        }


        /*
        |--------------------------------------------------------------------------
        | TANGGAL MEMO
        |--------------------------------------------------------------------------
        */

        $tanggalMemo =
            $mutuD->memoProdis?->tanggal_memo;


        /*
        |--------------------------------------------------------------------------
        | PEMBERITAHUAN TERBARU
        |--------------------------------------------------------------------------
        */

        $latestPemberitahuan =
            $mutuD->latestPemberitahuan;


        /*
        |--------------------------------------------------------------------------
        | STATUS SKORSING
        |--------------------------------------------------------------------------
        */

        $statusSkorsing =
            $mutuD->status_skorsing;


        /*
        |--------------------------------------------------------------------------
        | STATUS PEMBERITAHUAN
        |--------------------------------------------------------------------------
        */

        $pemberitahuanStatus =
            $mutuD->status_pemberitahuan;


        /*
        |--------------------------------------------------------------------------
        | HELPER FORMAT TANGGAL
        |--------------------------------------------------------------------------
        */

        $formatDate = function ($date, $format = 'd/m/Y') {

            if (!$date) {
                return '-';
            }

            try {

                return \Carbon\Carbon::parse($date)->format($format);

            } catch (\Throwable $e) {

                return '-';

            }

        };


        /*
        |--------------------------------------------------------------------------
        | STATUS PEMBERITAHUAN DISPLAY
        |--------------------------------------------------------------------------
        */

        if ($pemberitahuanStatus === 'sudah_diberitahukan') {

            $notificationLabel = 'Sudah Diberitahukan';
            $notificationClass = 'success';
            $notificationIcon = 'bi-check-circle-fill';

        } elseif ($pemberitahuanStatus === 'diproses') {

            $notificationLabel = 'Diproses';
            $notificationClass = 'warning';
            $notificationIcon = 'bi-hourglass-split';

        } elseif ($pemberitahuanStatus === 'gagal') {

            $notificationLabel = 'Gagal';
            $notificationClass = 'danger';
            $notificationIcon = 'bi-exclamation-circle-fill';

        } else {

            $notificationLabel = 'Belum Diberitahukan';
            $notificationClass = 'neutral';
            $notificationIcon = 'bi-clock';

        }


        /*
        |--------------------------------------------------------------------------
        | DETAIL PENGIRIMAN
        |--------------------------------------------------------------------------
        */

        if ($latestPemberitahuan) {

            if ($latestPemberitahuan->status === 'sent') {

                $detailPengiriman = 'Terkirim';

            } elseif ($latestPemberitahuan->status === 'failed') {

                $detailPengiriman = 'Gagal';

            } elseif ($latestPemberitahuan->status === 'partial') {

                $detailPengiriman = 'Sebagian Berhasil';

            } else {

                $detailPengiriman =
                    ucfirst($latestPemberitahuan->status ?? '-');

            }

        } else {

            $detailPengiriman = 'Belum ada data';

        }

    @endphp


    {{-- =====================================================
        HERO
    ====================================================== --}}

    <div class="mutu-detail-hero">

        <div class="mutu-detail-main">

            <div class="mutu-detail-avatar">

                {{ strtoupper(
                    substr(
                        trim($mutuD->nama_petani ?? 'P'),
                        0,
                        1
                    )
                ) }}

            </div>


            <div>

                <span class="mutu-detail-label">
                    DATA PETANI
                </span>

                <h3>
                    {{ $mutuD->nama_petani ?? '-' }}
                </h3>

                <p>
                    No. Petani:
                    <strong>
                        {{ $mutuD->no_petani ?? '-' }}
                    </strong>
                </p>

            </div>

        </div>


        <div class="mutu-detail-grade">

            <span>
                KUALITAS TEBU
            </span>

            <strong>
                {{ $mutuD->kualitas ?? 'D' }}
            </strong>

            <small>
                Kategori Mutu D
            </small>

        </div>

    </div>


    {{-- =====================================================
        MAIN GRID
    ====================================================== --}}

    <div class="mutu-main-grid">


        {{-- =================================================
            INFORMASI DATA
        ================================================== --}}

        <div class="mutu-detail-card">

            <div class="mutu-card-header">

                <div class="mutu-card-header-left">

                    <span class="mutu-card-eyebrow">
                        INFORMASI DATA
                    </span>

                    <h3>
                        Detail Data Tebu
                    </h3>

                </div>

            </div>


            <div class="mutu-information-body">

                <div class="detail-information-grid">


                    {{-- NAMA PETANI --}}
                    <div class="detail-information-item highlight">

                        <span class="label">
                            Nama Petani
                        </span>

                        <strong class="value">
                            {{ $mutuD->nama_petani ?? '-' }}
                        </strong>

                    </div>


                    {{-- NO PETANI --}}
                    <div class="detail-information-item highlight">

                        <span class="label">
                            No. Petani
                        </span>

                        <strong class="value">
                            {{ $mutuD->no_petani ?? '-' }}
                        </strong>

                    </div>


                    {{-- KONTRAK --}}
                    <div class="detail-information-item">

                        <span class="label">
                            Nomor Kontrak
                        </span>

                        <strong class="value monospace">
                            {{ $mutuD->no_kontrak ?? '-' }}
                        </strong>

                    </div>


                    {{-- SPTA --}}
                    <div class="detail-information-item">

                        <span class="label">
                            Nomor SPTA
                        </span>

                        <strong class="value monospace">
                            {{ $mutuD->no_spta ?? '-' }}
                        </strong>

                    </div>


                    {{-- NOPOL --}}
                    <div class="detail-information-item">

                        <span class="label">
                            Nomor Polisi
                        </span>

                        <strong class="value">
                            {{ $mutuD->nopol ?? '-' }}
                        </strong>

                    </div>


                    {{-- PTA --}}
                    <div class="detail-information-item">

                        <span class="label">
                            Wilayah / PTA
                        </span>

                        <strong class="value">
                            {{ $namaPta }}
                        </strong>

                    </div>


                    {{-- AFDELING --}}
                    <div class="detail-information-item">

                        <span class="label">
                            Kode Afdeling
                        </span>

                        <strong class="value">
                            {{ $mutuD->kode_afdeling ?? '-' }}
                        </strong>

                    </div>


                    {{-- ASMAN --}}
                    <div class="detail-information-item">

                        <span class="label">
                            Nama Asman
                        </span>

                        <strong class="value">
                            {{ $mutuD->nama_asman ?? '-' }}
                        </strong>

                    </div>


                    {{-- BLOK --}}
                    <div class="detail-information-item">

                        <span class="label">
                            Deskripsi Blok
                        </span>

                        <strong class="value">
                            {{ $mutuD->deskripsi_blok ?? '-' }}
                        </strong>

                    </div>


                    {{-- TANGGAL MEMO --}}
                    <div class="detail-information-item">

                        <span class="label">
                            Tanggal Memo
                        </span>

                        <strong class="value">
                            {{ $formatDate($tanggalMemo) }}
                        </strong>

                    </div>


                    {{-- TANGGAL SELEKTOR --}}
                    <div class="detail-information-item">

                        <span class="label">
                            Tanggal Selektor
                        </span>

                        <strong class="value">
                            {{ $formatDate($mutuD->tanggal_selektor) }}
                        </strong>

                    </div>


                    {{-- JAM SELEKTOR --}}
                    <div class="detail-information-item">

                        <span class="label">
                            Jam Selektor
                        </span>

                        <strong class="value">
                            {{ $mutuD->jam_selektor ?? '-' }}
                        </strong>

                    </div>


                    {{-- TANGGAL TIMBANG --}}
                    <div class="detail-information-item">

                        <span class="label">
                            Tanggal Timbang
                        </span>

                        <strong class="value">
                            {{ $formatDate($mutuD->tanggal_timbang) }}
                        </strong>

                    </div>


                    {{-- JAM TIMBANG --}}
                    <div class="detail-information-item">

                        <span class="label">
                            Jam Timbang
                        </span>

                        <strong class="value">
                            {{ $mutuD->jam_timbang ?? '-' }}
                        </strong>

                    </div>


                    {{-- TANGGAL MEJA --}}
                    <div class="detail-information-item">

                        <span class="label">
                            Tanggal Meja Tebu
                        </span>

                        <strong class="value">
                            {{ $formatDate($mutuD->tanggal_meja_tebu) }}
                        </strong>

                    </div>


                    {{-- JAM MEJA --}}
                    <div class="detail-information-item">

                        <span class="label">
                            Jam Meja Tebu
                        </span>

                        <strong class="value">
                            {{ $mutuD->jam_meja_tebu ?? '-' }}
                        </strong>

                    </div>

                </div>


                {{-- INFO MUTU --}}

                <div class="mutu-detail-note">

                    <i class="bi bi-info-circle-fill"></i>

                    <div>

                        <strong>
                            Keterangan Mutu
                        </strong>

                        <p>
                            Data ini tercatat sebagai tebu dengan
                            kategori kualitas
                            <strong>
                                Mutu {{ $mutuD->kualitas ?? 'D' }}
                            </strong>.
                        </p>

                    </div>

                </div>

            </div>

        </div>


        {{-- =================================================
            SKORSING
        ================================================== --}}

        <div class="mutu-detail-card">

            <div class="mutu-card-header">

                <div class="mutu-card-header-left">

                    <span class="mutu-card-eyebrow">
                        TINDAK LANJUT
                    </span>

                    <h3>
                        Status Skorsing
                    </h3>

                </div>

            </div>


            <div class="skorsing-card-body">


                {{-- AKTIF --}}
                @if($statusSkorsing === 'aktif')

                    <div class="skorsing-status active">

                        <div class="skorsing-icon">
                            <i class="bi bi-hourglass-split"></i>
                        </div>

                        <div>

                            <span>
                                STATUS
                            </span>

                            <strong>
                                Skorsing Aktif
                            </strong>

                        </div>

                    </div>


                    <div class="skorsing-dates">

                        <div class="skorsing-date-item">

                            <span>
                                Mulai
                            </span>

                            <strong>
                                {{ $formatDate($mutuD->tanggal_skorsing_mulai, 'd M Y') }}
                            </strong>

                        </div>


                        <div class="skorsing-date-item">

                            <span>
                                Selesai
                            </span>

                            <strong>
                                {{ $formatDate($mutuD->tanggal_skorsing_selesai, 'd M Y') }}
                            </strong>

                        </div>

                    </div>


                    @if($mutuD->durasi_skorsing)

                        <div class="skorsing-duration">

                            <i class="bi bi-calendar-check"></i>

                            <span>
                                Durasi Skorsing
                            </span>

                            <strong>
                                {{ $mutuD->durasi_skorsing }} hari
                            </strong>

                        </div>

                    @endif


                {{-- SELESAI --}}
                @elseif($statusSkorsing === 'selesai')

                    <div class="skorsing-status success">

                        <div class="skorsing-icon">
                            <i class="bi bi-check-circle-fill"></i>
                        </div>

                        <div>

                            <span>
                                STATUS
                            </span>

                            <strong>
                                Skorsing Selesai
                            </strong>

                        </div>

                    </div>


                    <div class="skorsing-dates">

                        <div class="skorsing-date-item">

                            <span>
                                Mulai
                            </span>

                            <strong>
                                {{ $formatDate($mutuD->tanggal_skorsing_mulai, 'd M Y') }}
                            </strong>

                        </div>


                        <div class="skorsing-date-item">

                            <span>
                                Selesai
                            </span>

                            <strong>
                                {{ $formatDate($mutuD->tanggal_skorsing_selesai, 'd M Y') }}
                            </strong>

                        </div>

                    </div>


                    @if($mutuD->durasi_skorsing)

                        <div class="skorsing-duration">

                            <i class="bi bi-calendar-check"></i>

                            <span>
                                Durasi Skorsing
                            </span>

                            <strong>
                                {{ $mutuD->durasi_skorsing }} hari
                            </strong>

                        </div>

                    @endif


                {{-- BELUM --}}
                @else

                    <div class="skorsing-status neutral">

                        <div class="skorsing-icon">
                            <i class="bi bi-clock"></i>
                        </div>

                        <div>

                            <span>
                                STATUS
                            </span>

                            <strong>
                                Belum Diberikan Skorsing
                            </strong>

                        </div>

                    </div>


                    <form
                        action="{{ route('mutu-d.skorsing', $mutuD->id) }}"
                        method="POST"
                        class="skorsing-form"
                    >

                        @csrf

                        <button
                            type="submit"
                            class="mutu-primary-button"
                        >

                            <i class="bi bi-shield-exclamation"></i>

                            Terapkan Skorsing 3 Hari

                        </button>

                    </form>


                    <div class="skorsing-warning">

                        <i class="bi bi-info-circle"></i>

                        <span>
                            Pastikan data Mutu D sudah benar sebelum
                            menerapkan skorsing.
                        </span>

                    </div>

                @endif

            </div>

        </div>

    </div>


    {{-- =====================================================
        PEMBERITAHUAN
    ====================================================== --}}

    <div class="mutu-detail-card">

        <div class="mutu-card-header">

            <div class="mutu-card-header-left">

                <span class="mutu-card-eyebrow">
                    KOMUNIKASI
                </span>

                <h3>
                    Status Pemberitahuan
                </h3>

                <p>
                    Informasi pemberitahuan terkait data Mutu D.
                </p>

            </div>

            <i
                class="bi bi-whatsapp"
                style="font-size:22px;color:#3a8054;"
            ></i>

        </div>


        <div class="notification-body">

            <div class="notification-status-layout">


                {{-- STATUS --}}
                <div class="notification-status-box">

                    <div class="notification-status-icon {{ $notificationClass }}">

                        <i class="bi {{ $notificationIcon }}"></i>

                    </div>

                    <div class="notification-status-text">

                        <span>
                            Status Pemberitahuan
                        </span>

                        <strong>
                            {{ $notificationLabel }}
                        </strong>

                    </div>

                </div>


                {{-- PENGIRIMAN --}}
                <div class="notification-meta-box">

                    <span>
                        Detail Pengiriman
                    </span>

                    <strong>
                        {{ $detailPengiriman }}
                    </strong>

                    @if($latestPemberitahuan && $latestPemberitahuan->sent_at)

                        <div class="notification-time">

                            <i class="bi bi-clock"></i>

                            {{ $formatDate(
                                $latestPemberitahuan->sent_at,
                                'd/m/Y H:i'
                            ) }}

                        </div>

                    @endif

                </div>

            </div>

        </div>

    </div>


    {{-- =====================================================
        FOTO DOKUMENTASI
    ====================================================== --}}

    <div class="mutu-detail-card">

        <div class="mutu-card-header">

            <div class="mutu-card-header-left">

                <span class="mutu-card-eyebrow">
                    DOKUMENTASI
                </span>

                <h3>
                    Foto Mutu D
                </h3>

                <p>
                    Dokumentasi foto terkait data Mutu D.
                </p>

            </div>


            <span class="mutu-count-badge">

                {{ $mutuD->photos?->count() ?? 0 }}

                foto

            </span>

        </div>


        @if($mutuD->photos && $mutuD->photos->count())

            <div class="photo-gallery">

                @foreach($mutuD->photos as $photo)

                    @php

                        $photoUrl =
                            \Illuminate\Support\Facades\Storage::disk('public')
                                ->url($photo->file_path);

                    @endphp

                    <div class="photo-gallery-item">

                        <img
                            src="{{ $photoUrl }}"
                            alt="Dokumentasi Mutu D - {{ $mutuD->nama_petani ?? 'Petani' }}"
                            loading="lazy"
                        >


                        <div class="photo-overlay">

                            <a
                                href="{{ $photoUrl }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                title="Lihat foto"
                            >

                                <i class="bi bi-arrows-fullscreen"></i>

                            </a>

                        </div>

                    </div>

                @endforeach

            </div>

        @else

            <div class="mutu-empty-state">

                <div class="mutu-empty-icon">

                    <i class="bi bi-camera"></i>

                </div>

                <h4>
                    Belum ada dokumentasi
                </h4>

                <p>
                    Foto dokumentasi Mutu D belum tersedia.
                </p>

            </div>

        @endif

    </div>

</div>

@endsection