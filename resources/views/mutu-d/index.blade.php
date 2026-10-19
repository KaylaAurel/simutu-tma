@extends('layouts.app')

@section('title', 'Data Mutu D')
@section('page-title', 'Data Mutu D')

@section('content')

<style>

    /* =========================================================
       PAGE
    ========================================================= */

    .mutu-d-page {
        width: 100%;
        max-width: 1500px;
        margin: 0 auto;
        padding: 8px 24px 50px;
    }

    .mutu-d-page-header {
        margin-bottom: 24px;
    }

    .mutu-d-title-row {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        gap: 25px;
    }

    .mutu-d-title-row h2 {
        margin: 10px 0 6px;
        color: #193c2a;
        font-size: 28px;
        font-weight: 800;
    }

    .mutu-d-title-row p {
        margin: 0;
        color: #718078;
        font-size: 14px;
    }


    /* =========================================================
       HEADER BADGE
    ========================================================= */

    .mutu-d-header-badge {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 15px;
        background: #f5faf7;
        border: 1px solid #dfece3;
        border-radius: 14px;
        min-width: 145px;
    }

    .mutu-d-header-icon {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        background: #e5f4ea;
        color: #2f8050;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 19px;
    }

    .mutu-d-header-badge span {
        display: block;
        color: #8a968f;
        font-size: 9px;
        font-weight: 800;
        letter-spacing: 1.4px;
        margin-bottom: 2px;
    }

    .mutu-d-header-badge strong {
        color: #28523a;
        font-size: 13px;
        letter-spacing: .4px;
    }


    /* =========================================================
       FILTER CARD
    ========================================================= */

    .mutu-d-filter-card {
        margin-bottom: 22px;
        padding: 0;
        overflow: hidden;
    }

    .mutu-d-filter-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        padding: 18px 22px;
        border-bottom: 1px solid #edf1ee;
        background: #fcfefd;
    }

    .mutu-d-filter-title {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .mutu-d-filter-icon {
        width: 39px;
        height: 39px;
        border-radius: 11px;
        background: #e8f5ec;
        color: #327b4d;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 15px;
    }

    .mutu-d-filter-title strong {
        display: block;
        color: #254332;
        font-size: 14px;
        margin-bottom: 3px;
    }

    .mutu-d-filter-title span {
        display: block;
        color: #89948d;
        font-size: 11px;
    }

    .mutu-d-reset-button {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 8px 12px;
        border: 1px solid #e1e8e3;
        border-radius: 9px;
        background: #fff;
        color: #68766e;
        text-decoration: none;
        font-size: 11px;
        font-weight: 600;
        transition: .2s ease;
    }

    .mutu-d-reset-button:hover {
        color: #2d7b4b;
        border-color: #bcd9c7;
        background: #f6fbf7;
    }

    .mutu-d-filter-form {
        display: grid;
        grid-template-columns:
            minmax(250px, 1.5fr)
            minmax(180px, .8fr)
            minmax(180px, .8fr)
            auto;

        gap: 15px;
        align-items: end;
        padding: 20px 22px;
    }

    .mutu-d-search {
        min-height: 43px;
        position: relative;
    }

    .mutu-d-search input {
        width: 100%;
        height: 43px;
        border: 1px solid #dfe7e2;
        border-radius: 10px;
        background: #fff;
        color: #33463a;
        font-size: 12px;
        padding: 0 13px 0 38px;
        outline: none;
    }

    .mutu-d-search > i {
        position: absolute;
        left: 13px;
        top: 50%;
        transform: translateY(-50%);
        color: #7b8a81;
        font-size: 14px;
        z-index: 2;
        pointer-events: none;
    }

    .mutu-d-filter-form .filter-item label {
        display: block;
        margin-bottom: 7px;
        color: #647169;
        font-size: 11px;
        font-weight: 700;
    }

    .mutu-d-input-wrapper {
        position: relative;
    }

    .mutu-d-input-wrapper > i {
        position: absolute;
        left: 13px;
        top: 50%;
        transform: translateY(-50%);
        color: #7b8a81;
        font-size: 14px;
        z-index: 2;
        pointer-events: none;
    }

    .mutu-d-input-wrapper input,
    .mutu-d-input-wrapper select {
        width: 100%;
        height: 43px;
        border: 1px solid #dfe7e2;
        border-radius: 10px;
        background: #fff;
        color: #33463a;
        font-size: 12px;
        padding: 0 13px 0 38px;
        outline: none;
        transition: .2s ease;
    }

    .mutu-d-input-wrapper select {
        appearance: auto;
    }

    .mutu-d-input-wrapper input:focus,
    .mutu-d-input-wrapper select:focus,
    .mutu-d-search input:focus {
        border-color: #70aa84;
        box-shadow: 0 0 0 3px rgba(67, 139, 88, .08);
    }

    .mutu-d-filter-button {
        height: 43px;
        padding: 0 18px;
        white-space: nowrap;
        border-radius: 10px;
        font-size: 12px;
    }


    /* =========================================================
       TABLE CARD
    ========================================================= */

    .mutu-d-table-card {
        overflow: hidden;
        padding: 0;
    }

    .mutu-d-table-header {
        padding: 20px 22px;
        min-height: 82px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
    }

    .mutu-d-table-heading {
        display: flex;
        align-items: center;
        gap: 13px;
    }

    .mutu-d-table-icon {
        width: 42px;
        height: 42px;
        min-width: 42px;
        border-radius: 12px;
        background: #eaf6ee;
        color: #347f50;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 17px;
    }

    .mutu-d-table-heading .card-eyebrow {
        display: block;
        margin-bottom: 4px;
    }

    .mutu-d-table-heading h3 {
        margin: 0 0 3px;
        color: #203d2c;
        font-size: 17px;
        font-weight: 800;
    }

    .mutu-d-table-heading p {
        margin: 0;
        color: #8a958e;
        font-size: 11px;
    }

    .mutu-d-count {
        min-width: 65px;
        padding: 8px 12px;
        border-radius: 11px;
        background: #f2f8f4;
        text-align: center;
        border: 1px solid #e1ece4;
    }

    .mutu-d-count strong {
        display: block;
        color: #2e7248;
        font-size: 17px;
        line-height: 1;
        margin-bottom: 3px;
    }

    .mutu-d-count span {
        color: #8a968f;
        font-size: 9px;
        font-weight: 700;
    }


    /* =========================================================
       TABLE
    ========================================================= */

    .mutu-d-table-responsive {
        overflow-x: auto;
    }

    .mutu-d-table {
        width: 100%;
        min-width: 1100px;
        border-collapse: collapse;
    }

    .mutu-d-table thead th {
        padding: 13px 17px;
        background: #f8faf9;
        border-top: 1px solid #edf1ee;
        border-bottom: 1px solid #e7ece9;
        color: #78857d;
        font-size: 10px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: .8px;
        white-space: nowrap;
        text-align: left;
    }

    .mutu-d-table tbody td {
        padding: 15px 17px;
        border-bottom: 1px solid #edf1ee;
        color: #536159;
        font-size: 12px;
        vertical-align: middle;
        background: #fff;
    }

    .mutu-d-table tbody tr {
        transition: .18s ease;
    }

    .mutu-d-table tbody tr:hover td {
        background: #fbfdfb;
    }

    .mutu-d-table tbody tr:last-child td {
        border-bottom: none;
    }


    /* =========================================================
       PETANI
    ========================================================= */

    .mutu-d-petani {
        min-width: 210px;
    }

    .mutu-d-avatar {
        width: 39px !important;
        height: 39px !important;
        min-width: 39px !important;
        border-radius: 11px !important;
        font-size: 14px !important;
        font-weight: 800;
    }

    .mutu-d-petani strong {
        display: block;
        color: #243f30;
        font-size: 13px;
        font-weight: 750;
        margin-bottom: 4px;
    }

    .mutu-d-petani span {
        display: block;
        color: #8a958e;
        font-size: 10px;
        white-space: nowrap;
    }


    /* =========================================================
       SPTA
    ========================================================= */

    .mutu-d-spta {
        min-width: 155px;
    }

    .mutu-d-spta strong {
        display: block;
        color: #355043;
        font-family: monospace;
        font-size: 11px;
        background: #f5f7f6;
        border: 1px solid #e8ece9;
        border-radius: 6px;
        padding: 5px 7px;
        width: fit-content;
        max-width: 100%;
        margin-bottom: 6px;
    }

    .mutu-d-spta span {
        display: flex;
        align-items: center;
        gap: 5px;
        color: #87938c;
        font-size: 10px;
    }

    .mutu-d-spta span i {
        color: #609171;
    }


    /* =========================================================
       WILAYAH / PTA
    ========================================================= */

    .mutu-d-wilayah {
        display: flex;
        align-items: center;
        gap: 8px;
        min-width: 150px;
        color: #405449;
        font-size: 12px;
        font-weight: 600;
    }

    .mutu-d-location-icon {
        width: 28px;
        height: 28px;
        min-width: 28px;
        border-radius: 8px;
        background: #eef7f1;
        color: #4b8b62;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
    }


    /* =========================================================
       DATE
    ========================================================= */

    .mutu-d-date {
        min-width: 115px;
    }

    .mutu-d-date strong {
        display: block;
        color: #354b3d;
        font-size: 12px;
        margin-bottom: 4px;
        white-space: nowrap;
    }

    .mutu-d-date span {
        display: block;
        color: #929d96;
        font-size: 10px;
    }


    /* =========================================================
       STATUS
    ========================================================= */

    .mutu-d-status-wrapper {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        gap: 5px;
    }

    .mutu-d-table .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 6px 9px;
        border-radius: 7px;
        font-size: 10px;
        font-weight: 700;
        white-space: nowrap;
    }

    .mutu-d-table .status-badge i {
        font-size: 10px;
    }

    .status-date {
        color: #929c96;
        font-size: 9px;
        padding-left: 2px;
    }


    /* =========================================================
       PEMBERITAHUAN
    ========================================================= */

    .mutu-d-pemberitahuan {
        min-width: 120px;
    }

    .mutu-d-pemberitahuan .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 6px 9px;
        border-radius: 7px;
        font-size: 10px;
        font-weight: 700;
        white-space: nowrap;
    }

    .mutu-d-pemberitahuan-date {
        display: block;
        margin-top: 5px;
        padding-left: 2px;
        color: #929c96;
        font-size: 9px;
    }


    /* =========================================================
       ACTION
    ========================================================= */

    .action-column {
        width: 105px;
        text-align: right !important;
    }

    .mutu-d-detail-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        padding: 8px 11px;
        border-radius: 9px;
        background: #f1f8f3;
        border: 1px solid #dcebe0;
        color: #32774b;
        text-decoration: none;
        font-size: 10px;
        font-weight: 700;
        transition: all .2s ease;
    }

    .mutu-d-detail-button i {
        font-size: 11px;
        transition: transform .2s ease;
    }

    .mutu-d-detail-button:hover {
        background: #e5f3e9;
        border-color: #c9dfcf;
        color: #25653e;
    }

    .mutu-d-detail-button:hover i {
        transform: translateX(2px);
    }


    /* =========================================================
       EMPTY STATE
    ========================================================= */

    .mutu-d-empty-state {
        padding: 60px 20px !important;
        text-align: center;
    }

    .mutu-d-empty-icon {
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

    .mutu-d-empty-state h4 {
        margin: 0 0 7px;
        color: #3b5144;
        font-size: 15px;
    }

    .mutu-d-empty-state p {
        margin: 0 0 18px;
        color: #8a968f;
        font-size: 11px;
    }


    /* =========================================================
       PAGINATION
    ========================================================= */

    .mutu-d-pagination {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        padding: 16px 20px;
        border-top: 1px solid #edf1ee;
        background: #fcfdfc;
    }

    .mutu-d-pagination-info {
        color: #8a958e;
        font-size: 11px;
    }

    .mutu-d-pagination-info strong {
        color: #4b5f52;
        font-weight: 700;
    }

    .mutu-d-pagination-nav {
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .mutu-d-pagination-nav a,
    .mutu-d-pagination-nav span {
        width: 32px;
        height: 32px;
        min-width: 32px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        border: 1px solid #e1e8e3;
        background: #fff;
        color: #65736b;
        font-size: 11px;
        font-weight: 700;
        line-height: 1;
        box-sizing: border-box;
    }

    .mutu-d-pagination-nav a {
        transition: all .18s ease;
    }

    .mutu-d-pagination-nav a:hover {
        color: #2f784a;
        background: #f1f8f3;
        border-color: #cfe2d4;
    }

    .mutu-d-pagination-nav .active {
        background: #347d50;
        border-color: #347d50;
        color: #fff;
    }

    .mutu-d-pagination-nav .disabled {
        color: #c0c8c3;
        background: #f7f9f8;
        cursor: not-allowed;
    }

    .mutu-d-pagination-nav i {
        font-size: 12px !important;
        line-height: 1 !important;
    }

    .mutu-d-pagination-nav svg {
        width: 13px !important;
        height: 13px !important;
        max-width: 13px !important;
        max-height: 13px !important;
    }

    .mutu-d-pagination-nav svg path {
        stroke-width: 2;
    }


    /* =========================================================
       RESPONSIVE
    ========================================================= */

    @media (max-width: 1150px) {

        .mutu-d-filter-form {
            grid-template-columns: 1fr 1fr;
        }

        .mutu-d-search {
            grid-column: 1 / -1;
        }

        .mutu-d-filter-button {
            width: 100%;
        }

    }


    @media (max-width: 800px) {

        .mutu-d-page {
            padding: 6px 14px 35px;
        }

        .mutu-d-title-row {
            flex-direction: column;
            align-items: flex-start;
        }

        .mutu-d-header-badge {
            display: none;
        }

        .mutu-d-filter-form {
            grid-template-columns: 1fr;
        }

        .mutu-d-search {
            grid-column: auto;
        }

        .mutu-d-filter-header {
            align-items: flex-start;
        }

        .mutu-d-table-header {
            align-items: flex-start;
        }

        .mutu-d-pagination {
            flex-direction: column;
            align-items: flex-start;
        }

    }


    @media (max-width: 500px) {

        .mutu-d-title-row h2 {
            font-size: 23px;
        }

        .mutu-d-filter-header {
            flex-direction: column;
        }

        .mutu-d-reset-button {
            width: 100%;
            justify-content: center;
        }

    }

</style>


<div class="mutu-d-page">

    {{-- =====================================================
        PAGE HEADER
    ====================================================== --}}
    <div class="page-header mutu-d-page-header">

        <div>

            <div class="breadcrumb">

                <span>Monitoring TMA</span>

                <i class="bi bi-chevron-right"></i>

                <span>Data Mutu D</span>

            </div>


            <div class="mutu-d-title-row">

                <div>

                    <h2>
                        Data Mutu D
                    </h2>

                    <p>
                        Kelola dan pantau data tebu dengan kategori Mutu D.
                    </p>

                </div>


                <div class="mutu-d-header-badge">

                    <div class="mutu-d-header-icon">
                        <i class="bi bi-clipboard-data"></i>
                    </div>

                    <div>

                        <span>
                            DATABASE
                        </span>

                        <strong>
                            MUTU D
                        </strong>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =====================================================
        FILTER
    ====================================================== --}}
    <div class="simutu-card filter-card mutu-d-filter-card">

        <div class="mutu-d-filter-header">

            <div class="mutu-d-filter-title">

                <div class="mutu-d-filter-icon">
                    <i class="bi bi-funnel-fill"></i>
                </div>

                <div>

                    <strong>
                        Filter Data
                    </strong>

                    <span>
                        Gunakan filter untuk menemukan data Mutu D.
                    </span>

                </div>

            </div>


            @if(request()->hasAny([
                'search',
                'tanggal',
                'pta',
                'status_skorsing'
            ]))

                <a href="{{ route('mutu-d.index') }}"
                   class="mutu-d-reset-button">

                    <i class="bi bi-arrow-counterclockwise"></i>

                    Reset Filter

                </a>

            @endif

        </div>


        <form method="GET"
              action="{{ route('mutu-d.index') }}"
              class="filter-form mutu-d-filter-form">


            {{-- SEARCH --}}
            <div class="filter-search mutu-d-search">

                <i class="bi bi-search"></i>

                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Cari nama petani, SPTA, kontrak, nopol..."
                >

            </div>


            {{-- TANGGAL MEMO --}}
            <div class="filter-item">

                <label>
                    Tanggal Memo
                </label>

                <div class="mutu-d-input-wrapper">

                    <i class="bi bi-calendar3"></i>

                    <input
                        type="date"
                        name="tanggal"
                        value="{{ request('tanggal') }}"
                    >

                </div>

            </div>


            {{-- STATUS SKORSING --}}
            <div class="filter-item">

                <label>
                    Status Skorsing
                </label>

                <div class="mutu-d-input-wrapper">

                    <i class="bi bi-hourglass-split"></i>

                    <select name="status_skorsing">

                        <option value="">
                            Semua Status
                        </option>

                        <option
                            value="belum"
                            @selected(request('status_skorsing') === 'belum')
                        >
                            Belum
                        </option>

                        <option
                            value="aktif"
                            @selected(request('status_skorsing') === 'aktif')
                        >
                            Aktif
                        </option>

                        <option
                            value="selesai"
                            @selected(request('status_skorsing') === 'selesai')
                        >
                            Selesai
                        </option>

                    </select>

                </div>

            </div>


            {{-- BUTTON --}}
            <button
                type="submit"
                class="primary-button mutu-d-filter-button"
            >

                <i class="bi bi-funnel-fill"></i>

                Terapkan Filter

            </button>

        </form>

    </div>


    {{-- =====================================================
        DATABASE MUTU D
    ====================================================== --}}
    <div class="simutu-card mutu-d-table-card">


        {{-- HEADER TABLE --}}
        <div class="card-header mutu-d-table-header">

            <div class="mutu-d-table-heading">

                <div class="mutu-d-table-icon">
                    <i class="bi bi-database-fill"></i>
                </div>

                <div>

                    <span class="card-eyebrow">
                        DATABASE MUTU
                    </span>

                    <h3>
                        Daftar Mutu D
                    </h3>

                    <p>
                        Data tebu dengan kategori mutu D yang telah
                        tercatat dalam sistem.
                    </p>

                </div>

            </div>


            <div class="mutu-d-count">

                <strong>
                    {{ $mutuDs->total() }}
                </strong>

                <span>
                    Data
                </span>

            </div>

        </div>


        {{-- =================================================
            TABLE
        ================================================== --}}
        <div class="table-responsive mutu-d-table-responsive">

            <table class="simutu-table mutu-d-table">

                <thead>

                    <tr>

                        <th>
                            Petani
                        </th>

                        <th>
                            SPTA
                        </th>

                        <th>
                            Wilayah / PTA
                        </th>

                        <th>
                            Tanggal Memo
                        </th>

                        <th>
                            Skorsing
                        </th>

                        <th>
                            Pemberitahuan
                        </th>

                        <th class="action-column">
                            Aksi
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($mutuDs as $mutu)

                        @php

                            /*
                            |--------------------------------------------------------------------------
                            | MEMO
                            |--------------------------------------------------------------------------
                            */

                            $memo = $mutu->memoProdis;

                            $tanggalMemo =
                                $memo?->tanggal_memo;


                            /*
                            |--------------------------------------------------------------------------
                            | PTA
                            |--------------------------------------------------------------------------
                            */

                            $namaPta =
                                $mutu->pta?->nama_pta
                                ?? $mutu->pta?->nama
                                ?? $mutu->pta?->nama_pta_tma
                                ?? '-';


                            /*
                            |--------------------------------------------------------------------------
                            | PEMBERITAHUAN TERBARU
                            |--------------------------------------------------------------------------
                            |
                            | Menggunakan accessor/relation yang sebelumnya
                            | sudah dipakai pada blade Mutu D.
                            |
                            */

                            $detailPemberitahuan =
                                $mutu->latestPemberitahuan
                                ?? null;


                            /*
                            |--------------------------------------------------------------------------
                            | NAMA PETANI
                            |--------------------------------------------------------------------------
                            */

                            $namaPetani =
                                trim($mutu->nama_petani ?? '');


                            /*
                            |--------------------------------------------------------------------------
                            | INITIAL AVATAR
                            |--------------------------------------------------------------------------
                            */

                            $initial =
                                $namaPetani !== ''
                                    ? strtoupper(
                                        substr($namaPetani, 0, 1)
                                    )
                                    : 'P';


                            /*
                            |--------------------------------------------------------------------------
                            | STATUS PEMBERITAHUAN
                            |--------------------------------------------------------------------------
                            */

                            $statusPemberitahuan =
                                $detailPemberitahuan?->status
                                ?? null;


                            /*
                            |--------------------------------------------------------------------------
                            | WAKTU PEMBERITAHUAN
                            |--------------------------------------------------------------------------
                            */

                            $waktuPemberitahuan =
                                $detailPemberitahuan?->sent_at
                                ?? null;

                        @endphp


                        <tr>


                            {{-- =================================================
                                PETANI
                            ================================================== --}}
                            <td>

                                <div class="table-user mutu-d-petani">

                                    <div class="table-avatar green mutu-d-avatar">

                                        {{ $initial }}

                                    </div>

                                    <div>

                                        <strong>
                                            {{ $mutu->nama_petani ?? '-' }}
                                        </strong>

                                        <span>

                                            @if($mutu->no_petani)

                                                No. Petani:
                                                {{ $mutu->no_petani }}

                                            @else

                                                Nomor petani tidak tersedia

                                            @endif

                                        </span>

                                    </div>

                                </div>

                            </td>


                            {{-- =================================================
                                SPTA
                            ================================================== --}}
                            <td>

                                <div class="mutu-d-spta">

                                    <strong>
                                        {{ $mutu->no_spta ?? '-' }}
                                    </strong>

                                    <span>

                                        <i class="bi bi-truck"></i>

                                        {{ $mutu->nopol ?? 'Nopol tidak tersedia' }}

                                    </span>

                                </div>

                            </td>


                            {{-- =================================================
                                PTA
                            ================================================== --}}
                            <td>

                                <div class="mutu-d-wilayah">

                                    <span class="mutu-d-location-icon">

                                        <i class="bi bi-person-badge-fill"></i>

                                    </span>

                                    <span>
                                        {{ $namaPta }}
                                    </span>

                                </div>

                            </td>


                            {{-- =================================================
                                TANGGAL MEMO
                            ================================================== --}}
                            <td>

                                <div class="mutu-d-date">

                                    @if($tanggalMemo)

                                        @php
                                            $carbonTanggalMemo =
                                                \Carbon\Carbon::parse($tanggalMemo)
                                                    ->locale('id');
                                        @endphp

                                        <strong>
                                            {{ $carbonTanggalMemo->translatedFormat('d M Y') }}
                                        </strong>

                                        <span>
                                            {{ $carbonTanggalMemo->translatedFormat('l') }}
                                        </span>

                                    @else

                                        <strong>
                                            -
                                        </strong>

                                        <span>
                                            Tanggal belum tersedia
                                        </span>

                                    @endif

                                </div>

                            </td>


                            {{-- =================================================
                                SKORSING
                            ================================================== --}}
                            <td>

                                @if($mutu->status_skorsing === 'aktif')

                                    <div class="mutu-d-status-wrapper">

                                        <span class="status-badge warning">

                                            <i class="bi bi-hourglass-split"></i>

                                            Aktif

                                        </span>

                                        @if($mutu->tanggal_skorsing_selesai)

                                            <small class="status-date">

                                                s/d
                                                {{ \Carbon\Carbon::parse(
                                                    $mutu->tanggal_skorsing_selesai
                                                )->format('d/m/Y') }}

                                            </small>

                                        @endif

                                    </div>


                                @elseif($mutu->status_skorsing === 'selesai')

                                    <div class="mutu-d-status-wrapper">

                                        <span class="status-badge success">

                                            <i class="bi bi-check-circle-fill"></i>

                                            Selesai

                                        </span>

                                        @if($mutu->tanggal_skorsing_selesai)

                                            <small class="status-date">

                                                {{ \Carbon\Carbon::parse(
                                                    $mutu->tanggal_skorsing_selesai
                                                )->format('d/m/Y') }}

                                            </small>

                                        @endif

                                    </div>


                                @else

                                    <span class="status-badge neutral">

                                        <i class="bi bi-dash-circle"></i>

                                        Belum

                                    </span>

                                @endif

                            </td>


                            {{-- =================================================
                                PEMBERITAHUAN
                            ================================================== --}}
                            <td>

                                <div class="mutu-d-pemberitahuan">

                                    @if(
                                        $detailPemberitahuan
                                        && in_array(
                                            $statusPemberitahuan,
                                            ['sent', 'delivered'],
                                            true
                                        )
                                    )

                                        <span class="status-badge success">

                                            <i class="bi bi-check-circle-fill"></i>

                                            Terkirim

                                        </span>

                                        @if($waktuPemberitahuan)

                                            <small class="mutu-d-pemberitahuan-date">

                                                {{ \Carbon\Carbon::parse(
                                                    $waktuPemberitahuan
                                                )->format('d/m/Y H:i') }}

                                            </small>

                                        @endif


                                    @elseif(
                                        $detailPemberitahuan
                                        && in_array(
                                            $statusPemberitahuan,
                                            ['failed', 'gagal'],
                                            true
                                        )
                                    )

                                        <span class="status-badge danger">

                                            <i class="bi bi-x-circle-fill"></i>

                                            Gagal

                                        </span>


                                    @elseif(
                                        $detailPemberitahuan
                                        && in_array(
                                            $statusPemberitahuan,
                                            [
                                                'processing',
                                                'pending',
                                                'diproses',
                                                'partial'
                                            ],
                                            true
                                        )
                                    )

                                        <span class="status-badge warning">

                                            <i class="bi bi-hourglass-split"></i>

                                            Diproses

                                        </span>


                                    @else

                                        <span class="status-badge neutral">

                                            <i class="bi bi-clock"></i>

                                            Belum

                                        </span>

                                    @endif

                                </div>

                            </td>


                            {{-- =================================================
                                AKSI
                            ================================================== --}}
                            <td class="action-column">

                                <a
                                    href="{{ route('mutu-d.detail', $mutu->id) }}"
                                    class="mutu-d-detail-button"
                                    title="Lihat detail"
                                >

                                    <span>
                                        Detail
                                    </span>

                                    <i class="bi bi-arrow-right"></i>

                                </a>

                            </td>

                        </tr>


                    @empty

                        <tr>

                            <td colspan="7">

                                <div class="empty-state mutu-d-empty-state">

                                    <div class="mutu-d-empty-icon">

                                        <i class="bi bi-clipboard-x"></i>

                                    </div>

                                    <h4>
                                        Data Mutu D tidak ditemukan
                                    </h4>

                                    <p>
                                        Belum ada data Mutu D yang sesuai
                                        dengan filter yang dipilih.
                                    </p>


                                    @if(request()->hasAny([
                                        'search',
                                        'tanggal',
                                        'pta',
                                        'status_skorsing'
                                    ]))

                                        <a
                                            href="{{ route('mutu-d.index') }}"
                                            class="secondary-button"
                                        >

                                            <i class="bi bi-arrow-counterclockwise"></i>

                                            Tampilkan Semua Data

                                        </a>

                                    @endif

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
        @if($mutuDs->hasPages())

            <div class="mutu-d-pagination">

                <div class="mutu-d-pagination-info">

                    Menampilkan

                    <strong>
                        {{ $mutuDs->firstItem() ?? 0 }}
                    </strong>

                    sampai

                    <strong>
                        {{ $mutuDs->lastItem() ?? 0 }}
                    </strong>

                    dari

                    <strong>
                        {{ $mutuDs->total() }}
                    </strong>

                    data

                </div>


                <div class="mutu-d-pagination-nav">

                    {{-- PREVIOUS --}}
                    @if($mutuDs->onFirstPage())

                        <span class="disabled">

                            <i class="bi bi-chevron-left"></i>

                        </span>

                    @else

                        <a
                            href="{{ $mutuDs->previousPageUrl() }}"
                            aria-label="Halaman sebelumnya"
                            title="Halaman sebelumnya"
                        >

                            <i class="bi bi-chevron-left"></i>

                        </a>

                    @endif


                    {{-- PAGE NUMBERS --}}
                    @foreach(
                        $mutuDs->getUrlRange(
                            max(1, $mutuDs->currentPage() - 2),
                            min(
                                $mutuDs->lastPage(),
                                $mutuDs->currentPage() + 2
                            )
                        )
                        as $page => $url
                    )

                        @if($page == $mutuDs->currentPage())

                            <span class="active">
                                {{ $page }}
                            </span>

                        @else

                            <a href="{{ $url }}">
                                {{ $page }}
                            </a>

                        @endif

                    @endforeach


                    {{-- NEXT --}}
                    @if($mutuDs->hasMorePages())

                        <a
                            href="{{ $mutuDs->nextPageUrl() }}"
                            aria-label="Halaman berikutnya"
                            title="Halaman berikutnya"
                        >

                            <i class="bi bi-chevron-right"></i>

                        </a>

                    @else

                        <span class="disabled">

                            <i class="bi bi-chevron-right"></i>

                        </span>

                    @endif

                </div>

            </div>

        @endif

    </div>

</div>

@endsection