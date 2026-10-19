@extends('layouts.app')

@section('title', 'Detail Pemberitahuan')
@section('page-title', 'Detail Pemberitahuan')

@section('content')

<style>
    .notification-page {
        width: 100%;
        max-width: 1480px;
        margin: 0 auto;
        padding: 8px 24px 50px;
    }

    .notification-page .page-header {
        margin-bottom: 28px;
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        gap: 20px;
    }

    .notification-page .page-header h2 {
        margin: 10px 0 6px;
    }

    .notification-page .page-header p {
        margin: 0;
        color: #6b7a70;
    }

    .notification-page .page-header-actions {
        display: flex;
        gap: 10px;
        align-items: center;
    }

    /* =========================================================
       STATUS HERO
    ========================================================= */

    .notification-status-hero {
        display: flex;
        align-items: center;
        gap: 18px;
        padding: 24px 28px;
        margin-bottom: 24px;
        border-radius: 20px;
        background: linear-gradient(135deg, #eef8f1, #ffffff);
        border: 1px solid #dcece1;
        box-shadow: 0 8px 25px rgba(31, 74, 47, .06);
    }

    .notification-status-icon {
        width: 58px;
        height: 58px;
        min-width: 58px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 27px;
    }

    .notification-status-icon.success {
        background: #e6f5eb;
        color: #287b48;
    }

    .notification-status-icon.warning {
        background: #fff5d8;
        color: #b88308;
    }

    .notification-status-icon.danger {
        background: #fdeaea;
        color: #c53d3d;
    }

    .notification-status-icon.processing {
        background: #eaf3ff;
        color: #3379c8;
    }

    .notification-status-content span {
        display: block;
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 1.4px;
        color: #6f8175;
        margin-bottom: 5px;
    }

    .notification-status-content h3 {
        margin: 0;
        font-size: 22px;
        color: #193c2a;
    }

    .notification-status-content p {
        margin: 5px 0 0;
        color: #748078;
        font-size: 13px;
    }

    /* =========================================================
       STATISTICS
    ========================================================= */

    .notification-stat-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 18px;
        margin-bottom: 24px;
    }

    .notification-stat-card {
        background: #fff;
        border: 1px solid #e5ebe7;
        border-radius: 18px;
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 15px;
        box-shadow: 0 6px 20px rgba(28, 63, 42, .045);
    }

    .notification-stat-icon {
        width: 48px;
        height: 48px;
        min-width: 48px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 21px;
    }

    .notification-stat-icon.blue {
        background: #eaf3ff;
        color: #3379c8;
    }

    .notification-stat-icon.yellow {
        background: #fff5d9;
        color: #b98815;
    }

    .notification-stat-icon.green {
        background: #e8f6ed;
        color: #2e8050;
    }

    .notification-stat-icon.danger {
        background: #fdeaea;
        color: #c74a4a;
    }

    .notification-stat-label {
        display: block;
        color: #7b887f;
        font-size: 12px;
        margin-bottom: 3px;
    }

    .notification-stat-value {
        display: block;
        font-size: 23px;
        font-weight: 800;
        color: #203d2c;
    }

    .notification-stat-description {
        font-size: 11px;
        color: #94a098;
    }

    /* =========================================================
       MAIN GRID
    ========================================================= */

    .notification-detail-grid {
        display: grid;
        grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
        gap: 22px;
        margin-bottom: 24px;
    }

    .notification-card {
        background: #fff;
        border: 1px solid #e5ebe7;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 7px 24px rgba(28, 63, 42, .045);
    }

    .notification-card-header {
        padding: 20px 22px;
        border-bottom: 1px solid #edf1ee;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .notification-card-eyebrow {
        display: block;
        font-size: 10px;
        font-weight: 800;
        letter-spacing: 1.5px;
        color: #378153;
        margin-bottom: 5px;
    }

    .notification-card-header h3 {
        margin: 0;
        font-size: 17px;
        color: #203d2c;
    }

    .table-count {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 6px 10px;
        border-radius: 9px;
        background: #eef7f1;
        color: #317c4e;
        font-size: 11px;
        font-weight: 700;
    }

    /* =========================================================
       RECIPIENT
    ========================================================= */

    .recipient-status-list {
        padding: 8px 12px;
    }

    .recipient-status-item {
        display: flex;
        align-items: center;
        gap: 13px;
        padding: 15px 10px;
        border-bottom: 1px solid #f0f3f1;
    }

    .recipient-status-item:last-child {
        border-bottom: none;
    }

    .recipient-avatar {
        width: 43px;
        height: 43px;
        min-width: 43px;
        border-radius: 13px;
        background: #edf7f0;
        color: #328052;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
    }

    .recipient-status-info {
        min-width: 0;
        flex: 1;
    }

    .recipient-status-info strong {
        display: block;
        color: #254332;
        font-size: 14px;
        margin-bottom: 4px;
    }

    .recipient-status-info span {
        display: flex;
        align-items: center;
        gap: 5px;
        color: #7d8981;
        font-size: 12px;
    }

    .recipient-status-info span i {
        color: #25a55a;
    }

    /* =========================================================
       MESSAGE
    ========================================================= */

    .message-preview-box {
        margin: 20px;
        padding: 20px;
        border-radius: 15px;
        background: #f6faf7;
        border: 1px solid #e1eee5;
        color: #294334;
        font-size: 14px;
        line-height: 1.75;
        white-space: normal;
        max-height: 500px;
        overflow-y: auto;
    }

    /* =========================================================
       DATA TABLE
    ========================================================= */

    .notification-data-card {
        margin-bottom: 24px;
    }

    .notification-table-wrapper {
        overflow-x: auto;
    }

    .notification-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 850px;
    }

    .notification-table th {
        padding: 14px 18px;
        background: #f8faf9;
        color: #738078;
        font-size: 10px;
        text-transform: uppercase;
        letter-spacing: .8px;
        text-align: left;
        border-bottom: 1px solid #e9eeeb;
    }

    .notification-table td {
        padding: 16px 18px;
        border-bottom: 1px solid #edf1ee;
        color: #506057;
        font-size: 13px;
        vertical-align: middle;
    }

    .notification-table tbody tr:hover {
        background: #fbfdfb;
    }

    .farmer-name {
        color: #203d2c;
        font-weight: 700;
    }

    .spta-code {
        font-family: monospace;
        font-size: 12px;
        background: #f3f6f4;
        padding: 5px 8px;
        border-radius: 7px;
    }

    /* =========================================================
       STATUS BADGE
    ========================================================= */

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 6px 10px;
        border-radius: 8px;
        font-size: 11px;
        font-weight: 700;
        white-space: nowrap;
    }

    .status-badge.success {
        background: #e8f6ed;
        color: #2d7e4c;
    }

    .status-badge.danger {
        background: #fdeaea;
        color: #c44747;
    }

    .status-badge.neutral {
        background: #f1f3f2;
        color: #718078;
    }

    .status-badge.processing {
        background: #eaf3ff;
        color: #3379c8;
    }

    /* =========================================================
       EMPTY
    ========================================================= */

    .notification-empty {
        padding: 35px 20px;
        text-align: center;
        color: #849087;
    }

    /* =========================================================
       ACTION
    ========================================================= */

    .detail-actions {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 10px;
        margin-top: 20px;
    }

    .detail-actions form {
        margin: 0;
    }

    /* =========================================================
       RESPONSIVE
    ========================================================= */

    @media (max-width: 1100px) {
        .notification-stat-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .notification-detail-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 700px) {
        .notification-page {
            padding: 5px 14px 35px;
        }

        .notification-page .page-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }

        .notification-page .page-header-actions {
            width: 100%;
        }

        .notification-page .page-header-actions form {
            width: 100%;
        }

        .notification-page .page-header-actions button {
            width: 100%;
            justify-content: center;
        }

        .notification-stat-grid {
            grid-template-columns: 1fr;
        }

        .notification-status-hero {
            padding: 20px;
        }

        .notification-status-content h3 {
            font-size: 18px;
        }

        .detail-actions {
            flex-direction: column-reverse;
            align-items: stretch;
        }

        .detail-actions form,
        .detail-actions a {
            width: 100%;
        }

        .detail-actions button,
        .detail-actions a {
            justify-content: center;
        }
    }
</style>


<div class="notification-page">

    {{-- =====================================================
         HEADER
    ====================================================== --}}

    <div class="page-header">

        <div>

            <div class="breadcrumb">

                <a href="{{ route('pemberitahuan.index') }}">
                    Pemberitahuan
                </a>

                <i class="bi bi-chevron-right"></i>

                <span>
                    Detail
                </span>

            </div>

            <h2>
                {{ $pemberitahuan->nomor }}
            </h2>

            <p>
                Detail dan riwayat pemberitahuan Mutu D kepada PTA.
            </p>

        </div>


        {{-- GENERATE PDF --}}

        <div class="page-header-actions">

            <form
                action="{{ route('pemberitahuan.generate-pdf', $pemberitahuan->id) }}"
                method="POST"
            >

                @csrf

                <button
                    type="submit"
                    class="secondary-button"
                >

                    <i class="bi bi-file-earmark-pdf"></i>

                    Dokumentasi PDF

                </button>

            </form>

        </div>

    </div>


    {{-- =====================================================
         STATUS
    ====================================================== --}}

    @php

        $status = $pemberitahuan->status_pesan;

        $statusClass = match ($status) {

            'terkirim' =>
                'success',

            'gagal' =>
                'danger',

            'processing' =>
                'processing',

            default =>
                'warning',

        };

    @endphp


    <div class="notification-status-hero">

        <div class="notification-status-icon {{ $statusClass }}">

            @if($status === 'terkirim')

                <i class="bi bi-check-circle-fill"></i>

            @elseif($status === 'gagal')

                <i class="bi bi-x-circle-fill"></i>

            @elseif($status === 'processing')

                <i class="bi bi-arrow-repeat"></i>

            @else

                <i class="bi bi-exclamation-circle-fill"></i>

            @endif

        </div>


        <div class="notification-status-content">

            <span>
                STATUS PEMBERITAHUAN
            </span>


            <h3>

                @if($status === 'terkirim')

                    Berhasil Dikirim

                @elseif($status === 'gagal')

                    Pengiriman Gagal

                @elseif($status === 'processing')

                    Sedang Diproses

                @else

                    Draft

                @endif

            </h3>


            <p>

                @if($pemberitahuan->waktu_kirim_pesan)

                    Dikirim pada
                    {{ \Carbon\Carbon::parse($pemberitahuan->waktu_kirim_pesan)->format('d F Y, H:i') }}

                @elseif($status === 'processing')

                    Pemberitahuan sedang diproses melalui WhatsApp.

                @elseif($status === 'gagal')

                    Pemberitahuan gagal dikirim melalui WhatsApp.

                @else

                    Pemberitahuan belum dikirim melalui WhatsApp.

                @endif

            </p>

        </div>

    </div>


    {{-- =====================================================
         STATISTICS
    ====================================================== --}}

    @php

        $totalPta =
            $pemberitahuan->details
                ->pluck('pta_id')
                ->filter()
                ->unique()
                ->count();

        $totalMutuD =
            $pemberitahuan->details
                ->pluck('mutu_d_id')
                ->filter()
                ->unique()
                ->count();

        $sentTotal =
            $pemberitahuan->details
                ->whereIn(
                    'status',
                    ['sent', 'delivered']
                )
                ->count();

        $failedTotal =
            $pemberitahuan->details
                ->where(
                    'status',
                    'failed'
                )
                ->count();

    @endphp


    <div class="notification-stat-grid">


        {{-- TOTAL PTA --}}

        <div class="notification-stat-card">

            <div class="notification-stat-icon blue">

                <i class="bi bi-people-fill"></i>

            </div>

            <div>

                <span class="notification-stat-label">
                    Total PTA
                </span>

                <strong class="notification-stat-value">
                    {{ $totalPta }}
                </strong>

                <span class="notification-stat-description">
                    Penerima
                </span>

            </div>

        </div>


        {{-- TOTAL MUTU D --}}

        <div class="notification-stat-card">

            <div class="notification-stat-icon yellow">

                <i class="bi bi-clipboard2-pulse-fill"></i>

            </div>

            <div>

                <span class="notification-stat-label">
                    Total Mutu D
                </span>

                <strong class="notification-stat-value">
                    {{ $totalMutuD }}
                </strong>

                <span class="notification-stat-description">
                    Data tebu
                </span>

            </div>

        </div>


        {{-- BERHASIL --}}

        <div class="notification-stat-card">

            <div class="notification-stat-icon green">

                <i class="bi bi-check-circle-fill"></i>

            </div>

            <div>

                <span class="notification-stat-label">
                    Berhasil
                </span>

                <strong class="notification-stat-value">
                    {{ $sentTotal }}
                </strong>

                <span class="notification-stat-description">
                    Terkirim
                </span>

            </div>

        </div>


        {{-- GAGAL --}}

        <div class="notification-stat-card">

            <div class="notification-stat-icon danger">

                <i class="bi bi-x-circle-fill"></i>

            </div>

            <div>

                <span class="notification-stat-label">
                    Gagal
                </span>

                <strong class="notification-stat-value">
                    {{ $failedTotal }}
                </strong>

                <span class="notification-stat-description">
                    Tidak terkirim
                </span>

            </div>

        </div>

    </div>


    {{-- =====================================================
         PTA + MESSAGE
    ====================================================== --}}

    <div class="notification-detail-grid">


        {{-- =================================================
             PTA
        ================================================== --}}

        <div class="notification-card">

            <div class="notification-card-header">

                <div>

                    <span class="notification-card-eyebrow">
                        PENERIMA
                    </span>

                    <h3>
                        Status PTA
                    </h3>

                </div>

                <span class="table-count">
                    {{ $totalPta }}
                </span>

            </div>


            <div class="recipient-status-list">

                @forelse(
                    $pemberitahuan->details->groupBy('pta_id')
                    as $details
                )

                    @php

                        $pta =
                            $details->first()->pta;

                        $allSent =
                            $details->every(
                                fn ($detail) =>
                                    in_array(
                                        $detail->status,
                                        ['sent', 'delivered']
                                    )
                            );

                        $hasFailed =
                            $details->contains(
                                fn ($detail) =>
                                    $detail->status === 'failed'
                            );

                        $isProcessing =
                            $details->contains(
                                fn ($detail) =>
                                    $detail->status === 'processing'
                            );

                    @endphp


                    @if($pta)

                        <div class="recipient-status-item">

                            <div class="recipient-avatar">

                                <i class="bi bi-person-badge"></i>

                            </div>


                            <div class="recipient-status-info">

                                <strong>
                                    {{ $pta->nama_pta }}
                                </strong>

                                <span>

                                    <i class="bi bi-whatsapp"></i>

                                    {{ $pta->no_whatsapp ?? '-' }}

                                </span>

                            </div>


                            <div>

                                @if($allSent)

                                    <span class="status-badge success">

                                        <i class="bi bi-check-circle"></i>

                                        Terkirim

                                    </span>

                                @elseif($hasFailed)

                                    <span class="status-badge danger">

                                        <i class="bi bi-x-circle"></i>

                                        Gagal

                                    </span>

                                @elseif($isProcessing)

                                    <span class="status-badge processing">

                                        <i class="bi bi-arrow-repeat"></i>

                                        Diproses

                                    </span>

                                @else

                                    <span class="status-badge neutral">

                                        Pending

                                    </span>

                                @endif

                            </div>

                        </div>

                    @endif


                @empty

                    <div class="notification-empty">

                        Belum ada penerima PTA.

                    </div>

                @endforelse

            </div>

        </div>


        {{-- =================================================
             MESSAGE
        ================================================== --}}

        <div class="notification-card">

            <div class="notification-card-header">

                <div>

                    <span class="notification-card-eyebrow">
                        PESAN
                    </span>

                    <h3>
                        Isi Pemberitahuan
                    </h3>

                </div>

                <i
                    class="bi bi-chat-left-text"
                    style="font-size:20px;color:#3b8055;"
                ></i>

            </div>


            <div class="message-preview-box">

                {!! nl2br(
                    e(
                        $pemberitahuan->isi_pesan
                        ?? ''
                    )
                ) !!}

            </div>

        </div>

    </div>


    {{-- =====================================================
         DATA MUTU D
    ====================================================== --}}

    <div class="notification-card notification-data-card">

        <div class="notification-card-header">

            <div>

                <span class="notification-card-eyebrow">
                    DATA TERKAIT
                </span>

                <h3>
                    Mutu D yang Diberitahukan
                </h3>

            </div>

            <span class="table-count">
                {{ $totalMutuD }} Data
            </span>

        </div>


        <div class="notification-table-wrapper">

            <table class="notification-table">

                <thead>

                    <tr>

                        <th>
                            Petani
                        </th>

                        <th>
                            No. SPTA
                        </th>

                        <th>
                            PTA
                        </th>

                        <th>
                            No. Polisi
                        </th>

                        <th>
                            Meja Tebu
                        </th>

                        <th>
                            Status
                        </th>

                        <th>
                            Dikirim
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse(
                        $pemberitahuan->details
                        as $detail
                    )

                        @php

                            $mutu =
                                $detail->mutuD;

                        @endphp


                        @if($mutu)

                            <tr>


                                {{-- PETANI --}}

                                <td>

                                    <span class="farmer-name">

                                        {{ $mutu->nama_petani ?? '-' }}

                                    </span>

                                </td>


                                {{-- SPTA --}}

                                <td>

                                    <span class="spta-code">

                                        {{ $mutu->no_spta ?? '-' }}

                                    </span>

                                </td>


                                {{-- PTA --}}

                                <td>

                                    {{ $detail->pta->nama_pta ?? '-' }}

                                </td>


                                {{-- NOPOL --}}

                                <td>

                                    {{ $mutu->nopol ?? '-' }}

                                </td>


                                {{-- MEJA TEBU --}}

                                <td>

                                    @if($mutu->tanggal_meja_tebu)

                                        {{ \Carbon\Carbon::parse(
                                            $mutu->tanggal_meja_tebu
                                        )->format('d/m/Y') }}

                                        @if($mutu->jam_meja_tebu)

                                            <br>

                                            <small>

                                                {{ $mutu->jam_meja_tebu }}

                                            </small>

                                        @endif

                                    @else

                                        -

                                    @endif

                                </td>


                                {{-- STATUS --}}

                                <td>

                                    @if(
                                        in_array(
                                            $detail->status,
                                            ['sent', 'delivered']
                                        )
                                    )

                                        <span class="status-badge success">

                                            <i class="bi bi-check-circle"></i>

                                            Terkirim

                                        </span>

                                    @elseif(
                                        $detail->status === 'failed'
                                    )

                                        <span class="status-badge danger">

                                            <i class="bi bi-x-circle"></i>

                                            Gagal

                                        </span>

                                    @elseif(
                                        $detail->status === 'processing'
                                    )

                                        <span class="status-badge processing">

                                            <i class="bi bi-arrow-repeat"></i>

                                            Diproses

                                        </span>

                                    @else

                                        <span class="status-badge neutral">

                                            Pending

                                        </span>

                                    @endif

                                </td>


                                {{-- WAKTU KIRIM --}}

                                <td>

                                    @if($detail->sent_at)

                                        {{ \Carbon\Carbon::parse(
                                            $detail->sent_at
                                        )->format('d/m/Y H:i') }}

                                    @else

                                        -

                                    @endif

                                </td>


                            </tr>

                        @endif


                    @empty

                        <tr>

                            <td colspan="7">

                                <div class="notification-empty">

                                    Belum ada data Mutu D.

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>


    {{-- =====================================================
         BOTTOM ACTION
    ====================================================== --}}

    <div class="detail-actions">


        {{-- KEMBALI --}}

        <a
            href="{{ route('pemberitahuan.index') }}"
            class="secondary-button"
        >

            <i class="bi bi-arrow-left"></i>

            Kembali

        </a>


        {{-- =================================================
             KIRIM WHATSAPP
             WAJIB POST
        ================================================== --}}

        @if(in_array($pemberitahuan->status_pesan, ['draft', 'gagal']))

    <form action="{{ route('pemberitahuan.send', $pemberitahuan->id) }}"
          method="POST"
          style="display:inline;"
          onsubmit="return confirm('Yakin ingin mengirim pemberitahuan ini melalui WhatsApp?');">

        @csrf

        <button type="submit"
                class="primary-button">

            <i class="bi bi-whatsapp"></i>
            Kirim via WhatsApp

        </button>

    </form>

@endif
       


    </div>

</div>

@endsection