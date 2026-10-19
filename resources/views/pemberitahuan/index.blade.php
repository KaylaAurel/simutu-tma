@extends('layouts.app')

@section('title', 'Pemberitahuan')
@section('page-title', 'Pemberitahuan')

@section('content')

<style>

    /* =========================================================
       PAGE
    ========================================================= */

    .pemberitahuan-page {
        width: 100%;
        max-width: 1500px;
        margin: 0 auto;
        padding: 8px 24px 50px;
    }

    .pemberitahuan-page-header {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 24px;
    }

    .pemberitahuan-header-info {
        min-width: 0;
    }

    .pemberitahuan-title-row {
        display: flex;
        align-items: center;
        gap: 13px;
        margin-top: 12px;
    }

    .pemberitahuan-title-icon {
        width: 48px;
        height: 48px;
        min-width: 48px;
        border-radius: 14px;
        background: #e8f5ec;
        color: #317a4d;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }

    .pemberitahuan-title-row h2 {
        margin: 0 0 5px;
        color: #193c2a;
        font-size: 27px;
        font-weight: 800;
    }

    .pemberitahuan-title-row p {
        margin: 0;
        color: #7c8982;
        font-size: 13px;
    }


    /* =========================================================
       CREATE BUTTON
    ========================================================= */

    .pemberitahuan-create-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 11px 16px;
        border-radius: 10px;
        background: #347d50;
        border: 1px solid #347d50;
        color: #fff;
        text-decoration: none;
        font-size: 12px;
        font-weight: 700;
        white-space: nowrap;
        transition: .2s ease;
    }

    .pemberitahuan-create-button:hover {
        background: #286940;
        border-color: #286940;
        color: #fff;
        transform: translateY(-1px);
    }

    .pemberitahuan-create-button i {
        font-size: 13px;
    }


    /* =========================================================
       FILTER
    ========================================================= */

    .pemberitahuan-filter-card {
        padding: 0;
        overflow: hidden;
        margin-bottom: 22px;
    }

    .pemberitahuan-filter-form {
        display: grid;
        grid-template-columns: minmax(300px, 1fr) 210px auto;
        gap: 15px;
        align-items: end;
        padding: 20px 22px;
    }

    .pemberitahuan-search {
        position: relative;
    }

    .pemberitahuan-search > i {
        position: absolute;
        left: 13px;
        top: 50%;
        transform: translateY(-50%);
        color: #849189;
        font-size: 13px;
        pointer-events: none;
    }

    .pemberitahuan-search input,
    .pemberitahuan-filter-item select {
        width: 100%;
        height: 43px;
        border: 1px solid #dfe7e2;
        border-radius: 10px;
        background: #fff;
        color: #34483b;
        font-size: 12px;
        outline: none;
        transition: .2s ease;
    }

    .pemberitahuan-search input {
        padding: 0 38px;
    }

    .pemberitahuan-search input:focus,
    .pemberitahuan-filter-item select:focus {
        border-color: #70aa84;
        box-shadow: 0 0 0 3px rgba(67, 139, 88, .08);
    }

    .pemberitahuan-search-clear {
        position: absolute;
        right: 11px;
        top: 50%;
        transform: translateY(-50%);
        width: 24px;
        height: 24px;
        border-radius: 7px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #89958e;
        text-decoration: none;
        background: #f3f6f4;
    }

    .pemberitahuan-filter-item label {
        display: block;
        margin-bottom: 7px;
        color: #647169;
        font-size: 11px;
        font-weight: 700;
    }

    .pemberitahuan-filter-item select {
        padding: 0 12px;
    }

    .pemberitahuan-filter-button {
        height: 43px;
        padding: 0 18px;
        border: none;
        border-radius: 10px;
        background: #347d50;
        color: #fff;
        font-size: 12px;
        font-weight: 700;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        white-space: nowrap;
    }

    .pemberitahuan-filter-button:hover {
        background: #286940;
    }


    /* =========================================================
       HISTORY CARD
    ========================================================= */

    .pemberitahuan-history-card {
        overflow: hidden;
        padding: 0;
    }

    .pemberitahuan-history-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        padding: 20px 22px;
        border-bottom: 1px solid #edf1ee;
    }

    .pemberitahuan-history-title {
        display: flex;
        align-items: center;
        gap: 13px;
    }

    .pemberitahuan-history-icon {
        width: 42px;
        height: 42px;
        min-width: 42px;
        border-radius: 12px;
        background: #eaf6ee;
        color: #347d50;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 17px;
    }

    .pemberitahuan-history-title .card-eyebrow {
        display: block;
        margin-bottom: 4px;
        color: #829087;
        font-size: 9px;
        font-weight: 800;
        letter-spacing: 1px;
    }

    .pemberitahuan-history-title h3 {
        margin: 0 0 3px;
        color: #203d2c;
        font-size: 17px;
        font-weight: 800;
    }

    .pemberitahuan-history-title p {
        margin: 0;
        color: #89958e;
        font-size: 11px;
    }

    .pemberitahuan-total {
        min-width: 65px;
        padding: 8px 12px;
        border-radius: 11px;
        background: #f2f8f4;
        border: 1px solid #e1ece4;
        text-align: center;
    }

    .pemberitahuan-total span {
        display: block;
        color: #2e7248;
        font-size: 17px;
        font-weight: 800;
        line-height: 1;
        margin-bottom: 3px;
    }

    .pemberitahuan-total small {
        color: #8a968f;
        font-size: 9px;
        font-weight: 700;
    }


    /* =========================================================
       TABLE
    ========================================================= */

    .pemberitahuan-table-responsive {
        overflow-x: auto;
    }

    .pemberitahuan-table {
        width: 100%;
        min-width: 1050px;
        border-collapse: collapse;
    }

    .pemberitahuan-table thead th {
        padding: 13px 17px;
        background: #f8faf9;
        border-bottom: 1px solid #e7ece9;
        color: #78857d;
        font-size: 10px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: .7px;
        text-align: left;
        white-space: nowrap;
    }

    .pemberitahuan-table tbody td {
        padding: 15px 17px;
        border-bottom: 1px solid #edf1ee;
        background: #fff;
        color: #536159;
        font-size: 12px;
        vertical-align: middle;
    }

    .pemberitahuan-table tbody tr:hover td {
        background: #fbfdfb;
    }

    .pemberitahuan-table tbody tr:last-child td {
        border-bottom: none;
    }


    /* =========================================================
       NOMOR
    ========================================================= */

    .pemberitahuan-number {
        display: flex;
        align-items: center;
        gap: 9px;
        min-width: 190px;
    }

    .pemberitahuan-number-icon {
        width: 34px;
        height: 34px;
        min-width: 34px;
        border-radius: 9px;
        background: #eef6f1;
        color: #478260;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
    }

    .pemberitahuan-number strong {
        color: #294637;
        font-size: 11px;
        font-family: monospace;
        font-weight: 700;
        word-break: break-word;
    }


    /* =========================================================
       TANGGAL
    ========================================================= */

    .pemberitahuan-date {
        display: flex;
        align-items: center;
        gap: 7px;
        min-width: 120px;
    }

    .pemberitahuan-date i {
        color: #609171;
        font-size: 13px;
    }

    .pemberitahuan-date span {
        color: #43564a;
        font-size: 11px;
        font-weight: 600;
        white-space: nowrap;
    }


    /* =========================================================
       NUMBER PILL
    ========================================================= */

    .number-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 7px 9px;
        border-radius: 8px;
        font-size: 11px;
        font-weight: 800;
        white-space: nowrap;
    }

    .number-pill i {
        font-size: 11px;
    }

    .number-pill small {
        font-size: 9px;
        font-weight: 600;
    }

    .number-pill.blue {
        color: #356a9b;
        background: #eef5fb;
        border: 1px solid #dce9f5;
    }

    .number-pill.yellow {
        color: #947328;
        background: #fff8df;
        border: 1px solid #f4e9bd;
    }


    /* =========================================================
       STATUS
    ========================================================= */

    .pemberitahuan-table .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 6px 9px;
        border-radius: 7px;
        font-size: 10px;
        font-weight: 700;
        white-space: nowrap;
    }

    .pemberitahuan-table .status-badge i {
        font-size: 10px;
    }

    .status-badge.success {
        color: #287047;
        background: #eaf7ee;
        border: 1px solid #d6ebdc;
    }

    .status-badge.warning {
        color: #9a7626;
        background: #fff7df;
        border: 1px solid #f2e6bc;
    }

    .status-badge.danger {
        color: #a44c4c;
        background: #fdf0f0;
        border: 1px solid #f2dada;
    }

    .status-badge.neutral {
        color: #6f7b74;
        background: #f3f5f4;
        border: 1px solid #e5e9e6;
    }


    /* =========================================================
       DIKIRIM
    ========================================================= */

    .pemberitahuan-sent {
        display: flex;
        align-items: center;
        gap: 6px;
        min-width: 145px;
    }

    .pemberitahuan-sent > i {
        color: #39805a;
        font-size: 13px;
    }

    .pemberitahuan-sent span {
        color: #405348;
        font-size: 11px;
        font-weight: 600;
        white-space: nowrap;
    }

    .pemberitahuan-sent small {
        color: #89958e;
        font-size: 9px;
    }

    .not-sent {
        color: #9aa49e;
        font-size: 10px;
        font-style: italic;
        white-space: nowrap;
    }


    /* =========================================================
       ACTION
    ========================================================= */

    .pemberitahuan-table .action-column {
        width: 80px;
        text-align: right !important;
    }

    .pemberitahuan-detail-button {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #f1f8f3;
        border: 1px solid #dcebe0;
        color: #32774b;
        text-decoration: none;
        transition: .2s ease;
    }

    .pemberitahuan-detail-button i {
        font-size: 12px;
        transition: transform .2s ease;
    }

    .pemberitahuan-detail-button:hover {
        background: #e5f3e9;
        border-color: #c9dfcf;
        color: #25653e;
    }

    .pemberitahuan-detail-button:hover i {
        transform: translateX(2px);
    }


    /* =========================================================
       EMPTY
    ========================================================= */

    .pemberitahuan-empty {
        padding: 65px 20px;
        text-align: center;
    }

    .pemberitahuan-empty-icon {
        width: 62px;
        height: 62px;
        margin: 0 auto 15px;
        border-radius: 17px;
        background: #f0f5f2;
        color: #7b9082;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 25px;
    }

    .pemberitahuan-empty h4 {
        margin: 0 0 7px;
        color: #3b5144;
        font-size: 15px;
    }

    .pemberitahuan-empty p {
        margin: 0;
        color: #8a968f;
        font-size: 11px;
    }


    /* =========================================================
       PAGINATION
    ========================================================= */

    .pemberitahuan-pagination {
        display: flex;
        justify-content: flex-end;
        padding: 16px 20px;
        border-top: 1px solid #edf1ee;
        background: #fcfdfc;
    }

    .pemberitahuan-pagination nav {
        display: flex;
        align-items: center;
    }

    .pemberitahuan-pagination nav > div:first-child {
        display: none;
    }

    .pemberitahuan-pagination nav > div:last-child {
        display: flex;
        align-items: center;
    }

    .pemberitahuan-pagination nav span,
    .pemberitahuan-pagination nav a {
        width: 32px !important;
        height: 32px !important;
        min-width: 32px !important;
        margin: 0 2px;
        border-radius: 8px !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        font-size: 11px !important;
        line-height: 1 !important;
    }

    .pemberitahuan-pagination nav svg {
        width: 13px !important;
        height: 13px !important;
        max-width: 13px !important;
        max-height: 13px !important;
    }


    /* =========================================================
       RESPONSIVE
    ========================================================= */

    @media (max-width: 1000px) {

        .pemberitahuan-filter-form {
            grid-template-columns: 1fr 1fr;
        }

        .pemberitahuan-search {
            grid-column: 1 / -1;
        }

        .pemberitahuan-filter-button {
            width: 100%;
        }

    }


    @media (max-width: 700px) {

        .pemberitahuan-page {
            padding: 6px 14px 35px;
        }

        .pemberitahuan-page-header {
            flex-direction: column;
            align-items: stretch;
        }

        .pemberitahuan-create-button {
            width: 100%;
        }

        .pemberitahuan-filter-form {
            grid-template-columns: 1fr;
        }

        .pemberitahuan-search {
            grid-column: auto;
        }

        .pemberitahuan-history-header {
            align-items: flex-start;
        }

    }

</style>


<div class="pemberitahuan-page">

    {{-- =========================================================
         HEADER
    ========================================================== --}}

    <div class="pemberitahuan-page-header">

        <div class="pemberitahuan-header-info">

            <div class="breadcrumb">

                <span>Monitoring TMA</span>

                <i class="bi bi-chevron-right"></i>

                <span>Pemberitahuan</span>

            </div>


            <div class="pemberitahuan-title-row">

                <div class="pemberitahuan-title-icon">
                    <i class="bi bi-megaphone-fill"></i>
                </div>

                <div>

                    <h2>
                        Pemberitahuan Mutu D
                    </h2>

                    <p>
                        Kelola dan pantau riwayat pemberitahuan kepada PTA.
                    </p>

                </div>

            </div>

        </div>


        <a
            href="{{ route('pemberitahuan.create') }}"
            class="pemberitahuan-create-button"
        >

            <i class="bi bi-plus-lg"></i>

            Buat Pemberitahuan

        </a>

    </div>



    {{-- =========================================================
         FILTER
    ========================================================== --}}

    <div class="simutu-card pemberitahuan-filter-card">

        <form
            method="GET"
            action="{{ route('pemberitahuan.index') }}"
            class="pemberitahuan-filter-form"
        >

            {{-- SEARCH --}}

            <div class="pemberitahuan-search">

                <i class="bi bi-search"></i>

                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Cari nomor atau judul pemberitahuan..."
                >

                @if(request('search'))

                    <a
                        href="{{ route('pemberitahuan.index') }}"
                        class="pemberitahuan-search-clear"
                    >

                        <i class="bi bi-x"></i>

                    </a>

                @endif

            </div>


            {{-- STATUS --}}

            <div class="pemberitahuan-filter-item">

                <label>
                    Status
                </label>

                <select name="status">

                    <option value="">
                        Semua Status
                    </option>

                    <option
                        value="draft"
                        @selected(request('status') === 'draft')
                    >
                        Draft
                    </option>

                    <option
                        value="processing"
                        @selected(request('status') === 'processing')
                    >
                        Diproses
                    </option>

                    <option
                        value="sent"
                        @selected(request('status') === 'sent')
                    >
                        Terkirim
                    </option>

                    <option
                        value="failed"
                        @selected(request('status') === 'failed')
                    >
                        Gagal
                    </option>

                </select>

            </div>


            {{-- BUTTON --}}

            <button
                type="submit"
                class="pemberitahuan-filter-button"
            >

                <i class="bi bi-funnel-fill"></i>

                Filter

            </button>

        </form>

    </div>



    {{-- =========================================================
         HISTORY
    ========================================================== --}}

    <div class="simutu-card pemberitahuan-history-card">


        {{-- HEADER --}}

        <div class="pemberitahuan-history-header">

            <div class="pemberitahuan-history-title">

                <div class="pemberitahuan-history-icon">
                    <i class="bi bi-clock-history"></i>
                </div>

                <div>

                    <span class="card-eyebrow">
                        RIWAYAT AKTIVITAS
                    </span>

                    <h3>
                        Daftar Pemberitahuan
                    </h3>

                    <p>
                        Riwayat pengiriman pemberitahuan Mutu D kepada PTA.
                    </p>

                </div>

            </div>


            <div class="pemberitahuan-total">

                <span>
                    {{ $pemberitahuans->total() }}
                </span>

                <small>
                    Data
                </small>

            </div>

        </div>



        {{-- =====================================================
             TABLE
        ====================================================== --}}

        <div class="table-responsive pemberitahuan-table-responsive">

            <table class="simutu-table pemberitahuan-table">

                <thead>

                    <tr>

                        <th>
                            Nomor
                        </th>

                        <th>
                            Tanggal
                        </th>

                        <th>
                            Total PTA
                        </th>

                        <th>
                            Mutu D
                        </th>

                        <th>
                            Status
                        </th>

                        <th>
                            Dikirim
                        </th>

                        <th class="action-column">
                            Aksi
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($pemberitahuans as $item)

                        @php

                            /*
                            |--------------------------------------------------------------------------
                            | TOTAL PTA
                            |--------------------------------------------------------------------------
                            */

                            $totalPta = $item->details
                                ->pluck('pta_id')
                                ->filter()
                                ->unique()
                                ->count();


                            /*
                            |--------------------------------------------------------------------------
                            | TOTAL MUTU D
                            |--------------------------------------------------------------------------
                            */

                            $totalMutuD = $item->details
                                ->pluck('mutu_d_id')
                                ->filter()
                                ->unique()
                                ->count();


                            /*
                            |--------------------------------------------------------------------------
                            | STATUS UTAMA
                            |--------------------------------------------------------------------------
                            */

                            $statusPesan =
                                strtolower(
                                    trim(
                                        $item->status_pesan ?? 'draft'
                                    )
                                );


                            /*
                            |--------------------------------------------------------------------------
                            | WAKTU KIRIM
                            |--------------------------------------------------------------------------
                            |
                            | Prioritas:
                            |
                            | 1. waktu_kirim_pesan dari tabel pemberitahuan
                            | 2. sent_at terbaru dari detail
                            |
                            */

                            $waktuKirim =
                                $item->waktu_kirim_pesan
                                ?? $item->details
                                    ->pluck('sent_at')
                                    ->filter()
                                    ->sortDesc()
                                    ->first();


                            /*
                            |--------------------------------------------------------------------------
                            | TANGGAL
                            |--------------------------------------------------------------------------
                            */

                            $tanggal =
                                $item->tanggal_pemberitahuan;


                        @endphp


                        <tr>


                            {{-- =================================================
                                 NOMOR
                            ================================================== --}}

                            <td>

                                <div class="pemberitahuan-number">

                                    <div class="pemberitahuan-number-icon">

                                        <i class="bi bi-file-earmark-text"></i>

                                    </div>

                                    <strong>

                                        {{ $item->nomor ?? '-' }}

                                    </strong>

                                </div>

                            </td>



                            {{-- =================================================
                                 TANGGAL
                            ================================================== --}}

                            <td>

                                <div class="pemberitahuan-date">

                                    <i class="bi bi-calendar3"></i>

                                    <span>

                                        @if($tanggal)

                                            {{ $tanggal->locale('id')->translatedFormat('d M Y') }}

                                        @else

                                            -

                                        @endif

                                    </span>

                                </div>

                            </td>



                            {{-- =================================================
                                 TOTAL PTA
                            ================================================== --}}

                            <td>

                                <span class="number-pill blue">

                                    <i class="bi bi-people-fill"></i>

                                    {{ $totalPta }}

                                    <small>
                                        PTA
                                    </small>

                                </span>

                            </td>



                            {{-- =================================================
                                 MUTU D
                            ================================================== --}}

                            <td>

                                <span class="number-pill yellow">

                                    <i class="bi bi-file-earmark-bar-graph-fill"></i>

                                    {{ $totalMutuD }}

                                    <small>
                                        Data
                                    </small>

                                </span>

                            </td>



                            {{-- =================================================
                                 STATUS
                            ================================================== --}}

                            <td>

                                @if($statusPesan === 'terkirim' || $statusPesan === 'sent')

                                    <span class="status-badge success">

                                        <i class="bi bi-check-circle-fill"></i>

                                        Terkirim

                                    </span>

                                @elseif($statusPesan === 'processing')

                                    <span class="status-badge warning">

                                        <i class="bi bi-hourglass-split"></i>

                                        Diproses

                                    </span>

                                @elseif($statusPesan === 'gagal' || $statusPesan === 'failed')

                                    <span class="status-badge danger">

                                        <i class="bi bi-x-circle-fill"></i>

                                        Gagal

                                    </span>

                                @else

                                    <span class="status-badge neutral">

                                        <i class="bi bi-file-earmark"></i>

                                        Draft

                                    </span>

                                @endif

                            </td>



                            {{-- =================================================
                                 DIKIRIM
                            ================================================== --}}

                            <td>

                                @if($waktuKirim)

                                    <div class="pemberitahuan-sent">

                                        <i class="bi bi-send-check"></i>

                                        <span>

                                            {{ $waktuKirim->locale('id')->translatedFormat('d M Y') }}

                                        </span>

                                        <small>

                                            {{ $waktuKirim->format('H:i') }}

                                        </small>

                                    </div>

                                @else

                                    <span class="not-sent">

                                        Belum dikirim

                                    </span>

                                @endif

                            </td>



                            {{-- =================================================
                                 DETAIL
                            ================================================== --}}

                            <td class="action-column">

                                <a
                                    href="{{ route('pemberitahuan.detail', $item->id) }}"
                                    class="pemberitahuan-detail-button"
                                    title="Lihat detail"
                                >

                                    <i class="bi bi-arrow-right"></i>

                                </a>

                            </td>

                        </tr>


                    @empty

                        <tr>

                            <td colspan="7">

                                <div class="pemberitahuan-empty">

                                    <div class="pemberitahuan-empty-icon">

                                        <i class="bi bi-megaphone"></i>

                                    </div>

                                    <h4>
                                        Belum ada pemberitahuan
                                    </h4>

                                    <p>
                                        Riwayat pemberitahuan kepada PTA
                                        akan muncul di sini.
                                    </p>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>



        {{-- =====================================================
             PAGINATION
        ====================================================== --}}

        @if($pemberitahuans->hasPages())

            <div class="pemberitahuan-pagination">

                {{ $pemberitahuans->links() }}

            </div>

        @endif

    </div>

</div>

@endsection