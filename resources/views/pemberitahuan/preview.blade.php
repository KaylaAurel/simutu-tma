@extends('layouts.app')

@section('title', 'Preview Pemberitahuan')
@section('page-title', 'Preview Pemberitahuan')

@section('content')

<style>

    .preview-page {
        width: 100%;
        max-width: 1480px;
        margin: 0 auto;
        padding: 8px 24px 50px;
    }

    .preview-page .page-header {
        margin-bottom: 28px;
    }

    .preview-layout {
        display: grid;
        grid-template-columns: 430px minmax(0, 1fr);
        gap: 26px;
        align-items: start;
    }

    /* =========================================================
       PHONE
    ========================================================= */

    .phone-section {
        position: sticky;
        top: 25px;
    }

    .phone-label {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 12px;
        color: #3c7e53;
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 1.5px;
    }

    .phone-label::before {
        content: "";
        width: 24px;
        height: 3px;
        border-radius: 10px;
        background: #3b8055;
    }

    .phone-frame {
        width: 100%;
        max-width: 390px;
        margin: 0 auto;
        border: 8px solid #202823;
        border-radius: 38px;
        overflow: hidden;
        background: #e9f1eb;
        box-shadow:
            0 25px 50px rgba(22, 49, 33, .18),
            0 8px 20px rgba(22, 49, 33, .08);
    }

    .phone-notch {
        width: 105px;
        height: 22px;
        background: #202823;
        border-radius: 0 0 16px 16px;
        margin: 0 auto;
        position: relative;
        z-index: 3;
    }

    .phone-header {
        display: flex;
        align-items: center;
        gap: 11px;
        padding: 12px 16px;
        background: #f8fbf8;
        border-bottom: 1px solid #dbe6de;
    }

    .phone-avatar {
        width: 39px;
        height: 39px;
        border-radius: 50%;
        background: #e6f3e9;
        color: #2e7b4a;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .phone-header strong {
        display: block;
        color: #243d2d;
        font-size: 13px;
    }

    .phone-header span {
        display: block;
        margin-top: 2px;
        color: #8a968e;
        font-size: 10px;
    }

    .phone-chat {
        min-height: 600px;
        max-height: 700px;
        overflow-y: auto;
        padding: 22px 15px;
        background:
            radial-gradient(
                circle at 15% 20%,
                rgba(255,255,255,.65) 0 2px,
                transparent 3px
            ),
            radial-gradient(
                circle at 70% 60%,
                rgba(255,255,255,.5) 0 2px,
                transparent 3px
            ),
            #e8f0ea;
        background-size:
            70px 70px,
            90px 90px,
            auto;
        display: flex;
        align-items: flex-end;
    }

    .whatsapp-message {
        width: 100%;
        max-width: 94%;
        margin-left: auto;
        padding: 11px 12px 7px;
        background: #dcf8d7;
        border-radius: 12px 3px 12px 12px;
        box-shadow:
            0 2px 5px rgba(24, 60, 37, .08);
    }

    .whatsapp-message-text {
        color: #263b2d;
        font-size: 12px;
        line-height: 1.65;
        word-break: break-word;
        white-space: normal;
    }

    .whatsapp-message-time {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 4px;
        margin-top: 4px;
        color: #829487;
        font-size: 9px;
    }

    .whatsapp-message-time i {
        color: #3a82c6;
        font-size: 13px;
    }

    .phone-input {
        display: flex;
        align-items: center;
        gap: 9px;
        padding: 10px 12px;
        background: #f7faf7;
        color: #9aa49e;
        font-size: 12px;
    }

    .phone-input span {
        flex: 1;
        background: #edf2ee;
        padding: 9px 12px;
        border-radius: 18px;
    }

    /* =========================================================
       CARD
    ========================================================= */

    .preview-card {
        background: #fff;
        border: 1px solid #e4ebe6;
        border-radius: 20px;
        overflow: hidden;
        box-shadow:
            0 7px 25px rgba(28, 63, 42, .05);
        margin-bottom: 20px;
    }

    .preview-card-header {
        padding: 20px 22px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 1px solid #edf1ee;
    }

    .preview-card-eyebrow {
        display: block;
        color: #378153;
        font-size: 10px;
        font-weight: 800;
        letter-spacing: 1.5px;
        margin-bottom: 5px;
    }

    .preview-card-header h3 {
        margin: 0;
        color: #203d2c;
        font-size: 17px;
    }

    .draft-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 7px 11px;
        border-radius: 20px;
        background: #fff5da;
        color: #a87908;
        font-size: 10px;
        font-weight: 800;
    }

    .draft-badge::before {
        content: "";
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: currentColor;
    }

    /* =========================================================
       SUMMARY
    ========================================================= */

    .notification-summary {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1px;
        background: #edf1ee;
        border-bottom: 1px solid #edf1ee;
    }

    .summary-item {
        padding: 18px 20px;
        background: #fff;
    }

    .summary-item span {
        display: block;
        color: #859088;
        font-size: 11px;
        margin-bottom: 6px;
    }

    .summary-item strong {
        display: block;
        color: #24402f;
        font-size: 15px;
    }

    /* =========================================================
       WARNING
    ========================================================= */

    .notification-warning {
        margin: 20px;
        padding: 15px 16px;
        border-radius: 14px;
        display: flex;
        gap: 12px;
        background: #fff8e6;
        border: 1px solid #f4e4b5;
        color: #7e651d;
    }

    .notification-warning > i {
        font-size: 20px;
        margin-top: 1px;
    }

    .notification-warning strong {
        display: block;
        font-size: 13px;
        margin-bottom: 4px;
    }

    .notification-warning p {
        margin: 0;
        font-size: 12px;
        line-height: 1.6;
    }

    /* =========================================================
       ACTION
    ========================================================= */

    .preview-actions {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 10px;
        padding: 0 20px 20px;
    }

    .preview-actions form {
        margin: 0;
    }

    .whatsapp-button {
        background: #2f7d4d !important;
    }

    .whatsapp-button:hover {
        background: #256b40 !important;
    }

    /* =========================================================
       RECIPIENT
    ========================================================= */

    .recipient-list {
        padding: 8px 12px;
    }

    .recipient-item {
        display: flex;
        align-items: center;
        gap: 13px;
        padding: 14px 10px;
        border-bottom: 1px solid #edf1ee;
    }

    .recipient-item:last-child {
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
    }

    .recipient-info {
        flex: 1;
        min-width: 0;
    }

    .recipient-info strong {
        display: block;
        color: #264131;
        font-size: 13px;
        margin-bottom: 3px;
    }

    .recipient-info span {
        color: #839088;
        font-size: 11px;
    }

    .recipient-count {
        padding: 6px 10px;
        border-radius: 20px;
        background: #f0f6f2;
        color: #398055;
        font-size: 10px;
        font-weight: 700;
        white-space: nowrap;
    }

    /* =========================================================
       DATA MUTU
    ========================================================= */

    .preview-data-list {
        padding: 8px 12px;
    }

    .preview-data-item {
        display: grid;
        grid-template-columns: 42px minmax(0, 1fr) auto;
        align-items: center;
        gap: 13px;
        padding: 14px 10px;
        border-bottom: 1px solid #edf1ee;
    }

    .preview-data-item:last-child {
        border-bottom: none;
    }

    .preview-data-number {
        width: 38px;
        height: 38px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f0f6f2;
        color: #3b8055;
        font-size: 12px;
        font-weight: 800;
    }

    .preview-data-info strong {
        display: block;
        color: #263f30;
        font-size: 13px;
    }

    .preview-data-info span {
        display: block;
        color: #87928b;
        font-size: 11px;
        margin-top: 3px;
    }

    .preview-data-badge {
        padding: 6px 9px;
        background: #fff4d6;
        color: #a8790b;
        border-radius: 8px;
        font-size: 9px;
        font-weight: 800;
    }

    /* =========================================================
       EMPTY
    ========================================================= */

    .empty-state {
        padding: 30px 20px;
        text-align: center;
        color: #87928b;
        font-size: 12px;
    }

    /* =========================================================
       RESPONSIVE
    ========================================================= */

    @media (max-width: 1100px) {

        .preview-layout {
            grid-template-columns: 1fr;
        }

        .phone-section {
            position: static;
        }

        .phone-frame {
            max-width: 420px;
        }
    }

    @media (max-width: 650px) {

        .preview-page {
            padding: 5px 14px 35px;
        }

        .notification-summary {
            grid-template-columns: 1fr;
        }

        .preview-actions {
            flex-direction: column-reverse;
        }

        .preview-actions a,
        .preview-actions form,
        .preview-actions button {
            width: 100%;
        }

        .preview-data-item {
            grid-template-columns: 38px minmax(0, 1fr);
        }

        .preview-data-badge {
            display: none;
        }
    }

</style>


<div class="preview-page">

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
                    Preview
                </span>

            </div>

            <h2>
                Preview Pemberitahuan
            </h2>

            <p>
                Periksa kembali isi dan penerima sebelum pemberitahuan dikirim.
            </p>

        </div>

    </div>


    {{-- =====================================================
         ALERT SUCCESS
    ====================================================== --}}

    @if(session('success'))

        <div class="alert alert-success mb-4">

            <i class="bi bi-check-circle-fill"></i>

            {{ session('success') }}

        </div>

    @endif


    {{-- =====================================================
         ALERT ERROR
    ====================================================== --}}

    @if($errors->any())

        <div class="alert alert-danger mb-4">

            <strong>
                Terjadi kesalahan:
            </strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    <div class="preview-layout">


        {{-- =================================================
             PHONE
        ================================================== --}}

        <div class="phone-section">

            <div class="phone-label">
                PREVIEW WHATSAPP
            </div>


            <div class="phone-frame">

                <div class="phone-notch"></div>


                <div class="phone-header">

                    <div class="phone-avatar">

                        <i class="bi bi-megaphone-fill"></i>

                    </div>


                    <div>

                        <strong>
                            SIMUTU-TMA
                        </strong>

                        <span>
                            Pesan pemberitahuan
                        </span>

                    </div>

                </div>


                <div class="phone-chat">

                    <div class="whatsapp-message">

                        <div class="whatsapp-message-text">

                            @if($pemberitahuan->isi_pesan)

                                {!! nl2br(
                                    e($pemberitahuan->isi_pesan)
                                ) !!}

                            @else

                                <span style="color:#89958d;">
                                    Isi pesan belum tersedia.
                                </span>

                            @endif

                        </div>


                        <div class="whatsapp-message-time">

                            {{ now()->format('H:i') }}

                            <i class="bi bi-check2-all"></i>

                        </div>

                    </div>

                </div>


                <div class="phone-input">

                    <i class="bi bi-emoji-smile"></i>

                    <span>
                        Pesan
                    </span>

                    <i class="bi bi-mic-fill"></i>

                </div>

            </div>

        </div>


        {{-- =================================================
             RIGHT CONTENT
        ================================================== --}}

        <div>


            {{-- =================================================
                 SUMMARY
            ================================================== --}}

            <div class="preview-card">

                <div class="preview-card-header">

                    <div>

                        <span class="preview-card-eyebrow">
                            RINGKASAN
                        </span>

                        <h3>
                            Informasi Pemberitahuan
                        </h3>

                    </div>


                    <span class="draft-badge">

                        {{ strtoupper(
                            $pemberitahuan->status_pesan
                        ) }}

                    </span>

                </div>


                <div class="notification-summary">


                    {{-- NOMOR --}}

                    <div class="summary-item">

                        <span>
                            Nomor Pemberitahuan
                        </span>

                        <strong>
                            {{ $pemberitahuan->nomor }}
                        </strong>

                    </div>


                    {{-- TANGGAL --}}

                    <div class="summary-item">

                        <span>
                            Tanggal
                        </span>

                        <strong>

                            {{ $pemberitahuan
                                ->tanggal_pemberitahuan
                                ?->format('d F Y')
                            }}

                        </strong>

                    </div>


                    {{-- PTA --}}

                    <div class="summary-item">

                        <span>
                            Total PTA
                        </span>

                        <strong>
                            {{ $totalPta }} PTA
                        </strong>

                    </div>


                    {{-- MUTU D --}}

                    <div class="summary-item">

                        <span>
                            Total Mutu D
                        </span>

                        <strong>
                            {{ $totalMutuD }} Data
                        </strong>

                    </div>

                </div>


                {{-- WARNING --}}

                <div class="notification-warning">

                    <i class="bi bi-exclamation-triangle-fill"></i>

                    <div>

                        <strong>
                            Pastikan data sudah benar
                        </strong>

                        <p>
                            Setelah tombol kirim ditekan,
                            sistem akan meneruskan pemberitahuan
                            kepada PTA melalui WhatsApp.
                        </p>

                    </div>

                </div>


                {{-- ACTION --}}

                <div class="preview-actions">


                    <a
                        href="{{ route('pemberitahuan.index') }}"
                        class="secondary-button"
                    >

                        <i class="bi bi-arrow-left"></i>

                        Kembali

                    </a>


                    @if(
                        in_array(
                            $pemberitahuan->status_pesan,
                            ['draft', 'gagal']
                        )
                    )

                       <form
    action="{{ route('pemberitahuan.send', $pemberitahuan->id) }}"
    method="POST"
    style="margin: 0;"
>
    @csrf

    <button
        type="submit"
        class="primary-button whatsapp-button"
        onclick="return confirm('Kirim pemberitahuan kepada PTA melalui WhatsApp sekarang?')"
    >
        <i class="bi bi-whatsapp"></i>
        Kirim via WhatsApp
    </button>
</form>

                    @else

                        <button
                            type="button"
                            class="secondary-button"
                            disabled
                        >

                            <i class="bi bi-check-circle"></i>

                            Sudah Diproses

                        </button>

                    @endif

                </div>

            </div>


            {{-- =================================================
                 PTA RECIPIENT
            ================================================== --}}

            <div class="preview-card">

                <div class="preview-card-header">

                    <div>

                        <span class="preview-card-eyebrow">
                            PENERIMA
                        </span>

                        <h3>
                            PTA Penerima
                        </h3>

                    </div>


                    <span class="table-count">
                        {{ $totalPta }}
                    </span>

                </div>


                <div class="recipient-list">

                    @forelse(
                        $ptaGroups
                        as $ptaId => $details
                    )

                        @php
                            $pta = $details
                                ->first()
                                ->pta;
                        @endphp


                        @if($pta)

                            <div class="recipient-item">


                                <div class="recipient-avatar">

                                    <i class="bi bi-person-badge"></i>

                                </div>


                                <div class="recipient-info">

                                    <strong>
                                        {{ $pta->nama_pta }}
                                    </strong>

                                    <span>

                                        <i class="bi bi-whatsapp"></i>

                                        {{ $pta->no_whatsapp ?? '-' }}

                                    </span>

                                </div>


                                <span class="recipient-count">

                                    {{ $details->count() }}

                                    Mutu D

                                </span>

                            </div>

                        @endif

                    @empty

                        <div class="empty-state">

                            <i
                                class="bi bi-person-x"
                                style="font-size:25px;"
                            ></i>

                            <br><br>

                            Belum ada PTA penerima.

                        </div>

                    @endforelse

                </div>

            </div>


            {{-- =================================================
                 DATA MUTU D
            ================================================== --}}

            <div class="preview-card">

                <div class="preview-card-header">

                    <div>

                        <span class="preview-card-eyebrow">
                            DATA MUTU
                        </span>

                        <h3>
                            Data Mutu D yang Dipilih
                        </h3>

                    </div>


                    <span class="table-count">
                        {{ $totalMutuD }}
                    </span>

                </div>


                <div class="preview-data-list">

                    @forelse(
                        $pemberitahuan->details
                        as $index => $detail
                    )

                        @php
                            $mutu = $detail->mutuD;
                        @endphp


                        @if($mutu)

                            <div class="preview-data-item">


                                <div class="preview-data-number">

                                    {{ $index + 1 }}

                                </div>


                                <div class="preview-data-info">

                                    <strong>

                                        {{ $mutu->nama_petani ?? '-' }}

                                    </strong>


                                    <span>

                                        SPTA:
                                        {{ $mutu->no_spta ?? '-' }}

                                        &nbsp; • &nbsp;

                                        Nopol:
                                        {{ $mutu->nopol ?? '-' }}

                                    </span>

                                </div>


                                <span class="preview-data-badge">

                                    MUTU D

                                </span>

                            </div>

                        @endif

                    @empty

                        <div class="empty-state">

                            <i
                                class="bi bi-inbox"
                                style="font-size:25px;"
                            ></i>

                            <br><br>

                            Belum ada data Mutu D.

                        </div>

                    @endforelse

                </div>

            </div>


        </div>

    </div>

</div>

@endsection