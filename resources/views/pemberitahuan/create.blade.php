@extends('layouts.app')

@section('title', 'Buat Pemberitahuan')
@section('page-title', 'Buat Pemberitahuan')

@section('content')

<div class="pemberitahuan-create-page">

    {{-- ============================================================= --}}
    {{-- PAGE HEADER --}}
    {{-- ============================================================= --}}

    <div class="page-header">

        <div>

            <div class="breadcrumb">

                <span>
                    Komunikasi & Monitoring
                </span>

                <i class="bi bi-chevron-right"></i>

                <strong>
                    Pemberitahuan
                </strong>

            </div>


            <div class="pemberitahuan-title-wrap">

                <div class="pemberitahuan-title-icon">

                    <i class="bi bi-megaphone-fill"></i>

                </div>


                <div>

                    <h2>
                        Buat Pemberitahuan
                    </h2>

                    <p>
                        Pilih tanggal data Mutu D yang akan diberitahukan kepada PTA.
                    </p>

                </div>

            </div>

        </div>

    </div>



    {{-- ============================================================= --}}
    {{-- STEP INDICATOR --}}
    {{-- ============================================================= --}}

    <div class="pemberitahuan-steps">

        <div class="pemberitahuan-step active">

            <div class="step-number">
                1
            </div>

            <div>

                <strong>
                    Pilih Tanggal
                </strong>

                <span>
                    Tentukan tanggal data Mutu D
                </span>

            </div>

        </div>


        <div class="step-line"></div>


        <div class="pemberitahuan-step">

            <div class="step-number">
                2
            </div>

            <div>

                <strong>
                    Pilih Data
                </strong>

                <span>
                    Pilih data Mutu D
                </span>

            </div>

        </div>


        <div class="step-line"></div>


        <div class="pemberitahuan-step">

            <div class="step-number">
                3
            </div>

            <div>

                <strong>
                    Preview
                </strong>

                <span>
                    Periksa sebelum dikirim
                </span>

            </div>

        </div>

    </div>



    {{-- ============================================================= --}}
    {{-- LANGKAH 1 — PILIH TANGGAL --}}
    {{-- ============================================================= --}}

    <div class="simutu-card tanggal-filter-card">

        <div class="card-header">

            <div>

                <span class="card-eyebrow">
                    LANGKAH 1
                </span>

                <h3>
                    Pilih Tanggal Data Mutu D
                </h3>

                <p class="card-description">
                    Pilih tanggal memo untuk menampilkan data Mutu D pada tanggal tersebut.
                </p>

            </div>


            <div class="calendar-header-icon">

                <i class="bi bi-calendar3"></i>

            </div>

        </div>


        <form
            action="{{ route('pemberitahuan.create') }}"
            method="GET"
            id="tanggalForm"
        >

            {{-- DATE SELECTOR --}}

            <div class="tanggal-selector">

                <div class="tanggal-selector-icon">

                    <i class="bi bi-calendar-date"></i>

                </div>


                <div class="tanggal-selector-content">

                    <label for="tanggal">
                        Tanggal Memo
                    </label>


                    <div class="tanggal-input-row">

                        <input
                            type="date"
                            name="tanggal"
                            id="tanggal"
                            class="tanggal-input"
                            value="{{ $tanggal }}"
                            required
                        >


                        <button
                            type="submit"
                            class="primary-button tanggal-submit-button"
                        >

                            <i class="bi bi-search"></i>

                            Tampilkan Data

                        </button>

                    </div>


                    <small>
                        Sistem hanya akan menampilkan data Mutu D yang memiliki tanggal memo sesuai pilihan.
                    </small>

                </div>

            </div>



            {{-- TANGGAL TERSEDIA --}}

            @if($tanggalTersedia->count())

                <div class="tanggal-tersedia-section">

                    <div class="tanggal-section-title">

                        <div>

                            <strong>

                                <i class="bi bi-calendar-check"></i>

                                Tanggal yang memiliki data Mutu D

                            </strong>


                            <span>
                                Klik tanggal untuk melihat datanya.
                            </span>

                        </div>


                        <span class="tanggal-total-badge">

                            {{ $tanggalTersedia->count() }}

                            tanggal

                        </span>

                    </div>



                    <div class="tanggal-chip-list">

                        @foreach($tanggalTersedia as $date)

                            <a
                                href="{{ route(
                                    'pemberitahuan.create',
                                    ['tanggal' => $date]
                                ) }}"
                                class="tanggal-option
                                    {{ $tanggal === $date ? 'active' : '' }}"
                            >

                                <div class="tanggal-option-icon">

                                    <i class="bi bi-calendar2-check"></i>

                                </div>


                                <div class="tanggal-option-text">

                                    <strong>

                                        {{ \Carbon\Carbon::parse($date)->translatedFormat('d F Y') }}

                                    </strong>


                                    <span>

                                        @if($tanggal === $date)

                                            Sedang dipilih

                                        @else

                                            Lihat data

                                        @endif

                                    </span>

                                </div>


                                @if($tanggal === $date)

                                    <i class="bi bi-check-circle-fill tanggal-active-icon"></i>

                                @else

                                    <i class="bi bi-chevron-right tanggal-arrow"></i>

                                @endif

                            </a>

                        @endforeach

                    </div>

                </div>

            @endif

        </form>

    </div>



    {{-- ============================================================= --}}
    {{-- LANGKAH 2 — DATA MUTU D --}}
    {{-- ============================================================= --}}

    <div class="simutu-card data-mutu-card">

        <div class="card-header data-mutu-header">

            <div>

                <span class="card-eyebrow">
                    LANGKAH 2
                </span>


                <h3>

                    Data Mutu D

                    <span class="tanggal-heading">

                        —
                        {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') }}

                    </span>

                </h3>


                @if($mutuDs->count())

                    <p class="data-summary-text">

                        Ditemukan

                        <strong>
                            {{ $mutuDs->count() }} data
                        </strong>

                        Mutu D yang belum diberitahukan untuk tanggal ini.

                    </p>

                @else

                    <p class="data-summary-text empty">

                        Tidak ada data Mutu D yang belum diberitahukan untuk tanggal ini.

                    </p>

                @endif

            </div>


            @if($mutuDs->count())

                <div class="selected-count-wrapper">

                    <div class="selected-count-badge">

                        <span id="selectedCount">
                            0
                        </span>

                        <span>
                            dipilih
                        </span>

                    </div>

                </div>

            @endif

        </div>



        @if($mutuDs->count())


            {{-- ===================================================== --}}
            {{-- FORM PILIH DATA --}}
            {{-- ===================================================== --}}

            <form
                action="{{ route('pemberitahuan.store') }}"
                method="POST"
                id="pemberitahuanForm"
            >

                @csrf


                <input
                    type="hidden"
                    name="tanggal"
                    value="{{ $tanggal }}"
                >



                {{-- ================================================= --}}
                {{-- TOOLBAR --}}
                {{-- ================================================= --}}

                <div class="data-selection-toolbar">

                    <label
                        class="select-all-label"
                        for="selectAll"
                    >

                        <span class="custom-checkbox">

                            <input
                                type="checkbox"
                                id="selectAll"
                            >

                            <span class="checkbox-ui"></span>

                        </span>


                        <span>

                            <strong>
                                Pilih Semua
                            </strong>

                            <small>
                                Pilih seluruh data Mutu D tanggal ini
                            </small>

                        </span>

                    </label>


                    <div class="toolbar-info">

                        <i class="bi bi-info-circle"></i>

                        <span>

                            Data hanya berasal dari tanggal

                            {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') }}

                        </span>

                    </div>

                </div>



                {{-- ================================================= --}}
                {{-- LIST DATA --}}
                {{-- ================================================= --}}

                <div class="mutu-d-list">

                    @foreach($mutuDs as $mutuD)

                        <label
                            class="mutu-d-select-item"
                            for="mutu_{{ $mutuD->id }}"
                        >

                            {{-- CHECKBOX --}}

                            <span class="custom-checkbox mutu-checkbox-wrapper">

                                <input
                                    type="checkbox"
                                    name="mutu_ids[]"
                                    value="{{ $mutuD->id }}"
                                    id="mutu_{{ $mutuD->id }}"
                                    class="mutu-checkbox"
                                >

                                <span class="checkbox-ui"></span>

                            </span>



                            {{-- AVATAR --}}

                            <div class="mutu-d-avatar">

                                {{ strtoupper(
                                    substr(
                                        $mutuD->nama_petani ?? 'M',
                                        0,
                                        1
                                    )
                                ) }}

                            </div>



                            {{-- DATA PETANI --}}

                            <div class="mutu-d-main">

                                <div class="mutu-d-name-row">

                                    <strong class="mutu-d-name">

                                        {{ $mutuD->nama_petani ?? '-' }}

                                    </strong>


                                    <span class="mutu-d-status">
                                        MUTU D
                                    </span>

                                </div>


                                <div class="mutu-d-meta">

                                    <span>

                                        <i class="bi bi-person-vcard"></i>

                                        No. Petani:

                                        <strong>
                                            {{ $mutuD->no_petani ?? '-' }}
                                        </strong>

                                    </span>


                                    <span>

                                        <i class="bi bi-receipt"></i>

                                        SPTA:

                                        <strong>
                                            {{ $mutuD->no_spta ?? '-' }}
                                        </strong>

                                    </span>


                                    @if($mutuD->pta)

                                        <span>

                                            <i class="bi bi-person-badge"></i>

                                            PTA:

                                            <strong>
                                                {{ $mutuD->pta->nama ?? '-' }}
                                            </strong>

                                        </span>

                                    @else

                                        <span class="pta-empty">

                                            <i class="bi bi-person-badge"></i>

                                            PTA:

                                            <strong>
                                                Belum tersedia
                                            </strong>

                                        </span>

                                    @endif

                                </div>

                            </div>



                            {{-- KENDARAAN --}}

                            <div class="mutu-d-right">

                                @if($mutuD->nopol)

                                    <div class="mutu-d-truck">

                                        <i class="bi bi-truck"></i>

                                        <span>
                                            {{ $mutuD->nopol }}
                                        </span>

                                    </div>

                                @endif


                                <i class="bi bi-chevron-right mutu-d-arrow"></i>

                            </div>

                        </label>

                    @endforeach

                </div>



                {{-- ================================================= --}}
                {{-- ACTION FOOTER --}}
                {{-- ================================================= --}}

                <div class="data-action-footer">

                    <div class="action-help">

                        <i class="bi bi-shield-check"></i>

                        <span>
                            Pastikan data yang dipilih sudah sesuai sebelum membuat preview.
                        </span>

                    </div>


                    <div class="action-buttons">

                        <a
                            href="{{ route('pemberitahuan.index') }}"
                            class="secondary-button"
                        >

                            <i class="bi bi-x-circle"></i>

                            Batal

                        </a>


                        <button
                            type="submit"
                            class="primary-button preview-button"
                            id="previewButton"
                            disabled
                        >

                            <i class="bi bi-eye"></i>

                            Preview Pemberitahuan

                            <span class="button-arrow">

                                <i class="bi bi-arrow-right"></i>

                            </span>

                        </button>

                    </div>

                </div>

            </form>


        @else


            {{-- ===================================================== --}}
            {{-- EMPTY STATE --}}
            {{-- ===================================================== --}}

            <div class="empty-date-state">

                <div class="empty-date-icon">

                    <i class="bi bi-calendar-x"></i>

                </div>


                <h4>
                    Tidak Ada Data Mutu D
                </h4>


                <p>

                    Tidak ditemukan data Mutu D yang belum diberitahukan pada tanggal

                    <strong>

                        {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') }}

                    </strong>.

                </p>


                @if($tanggalTersedia->count())

                    <div class="empty-date-hint">

                        <i class="bi bi-lightbulb-fill"></i>

                        <span>
                            Silakan pilih salah satu tanggal yang tersedia di atas.
                        </span>

                    </div>

                @else

                    <div class="empty-date-hint">

                        <i class="bi bi-info-circle-fill"></i>

                        <span>
                            Belum tersedia data Mutu D yang dapat diberitahukan.
                        </span>

                    </div>

                @endif

            </div>

        @endif

    </div>



    {{-- ============================================================= --}}
    {{-- ALUR SISTEM --}}
    {{-- ============================================================= --}}

    <div class="pemberitahuan-flow">

        <div class="flow-icon">

            <i class="bi bi-lightbulb-fill"></i>

        </div>


        <div class="flow-content">

            <strong>
                Alur Pemberitahuan Mutu D
            </strong>

            <p>

                Pilih tanggal →

                <strong>
                    Pilih Semua
                </strong>

                →

                <strong>
                    Preview Pemberitahuan
                </strong>

                →

                periksa data →

                kirim pemberitahuan kepada PTA.

            </p>

        </div>

    </div>

</div>

@endsection



{{-- ============================================================= --}}
{{-- CSS --}}
{{-- ============================================================= --}}

@push('styles')

<style>

/* ================================================================
   JARAK DARI SIDEBAR
   ================================================================ */

.pemberitahuan-create-page {
    /*
     * Ini bagian utama yang diperbaiki.
     * Tampilan/card tidak diubah.
     * Hanya memberikan ruang antara sidebar dan isi halaman.
     */
    padding-left: 28px;
    padding-right: 24px;
    box-sizing: border-box;
}


/* ================================================================
   HEADER
================================================================ */

.pemberitahuan-page-header {
    margin-bottom: 22px;
}

.pemberitahuan-title-wrap {
    display: flex;
    align-items: center;
    gap: 16px;
}

.pemberitahuan-title-icon {
    width: 52px;
    height: 52px;
    border-radius: 16px;
    background: linear-gradient(135deg, #eaf7ef, #d9f0e2);
    color: #2f7d4f;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    flex-shrink: 0;
}

.pemberitahuan-title-wrap h2 {
    margin: 0 0 4px;
}

.pemberitahuan-title-wrap p {
    margin: 0;
    color: #74817a;
}



/* ================================================================
   STEP INDICATOR
================================================================ */

.pemberitahuan-steps {
    display: flex;
    align-items: center;
    padding: 18px 22px;
    background: #ffffff;
    border: 1px solid #e7eee9;
    border-radius: 16px;
    margin-bottom: 20px;
    box-shadow: 0 4px 16px rgba(31, 64, 45, 0.04);
}

.pemberitahuan-step {
    display: flex;
    align-items: center;
    gap: 10px;
    min-width: 150px;
}

.step-number {
    width: 34px;
    height: 34px;
    border-radius: 50%;
    background: #edf2ee;
    color: #77847c;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 13px;
    flex-shrink: 0;
}

.pemberitahuan-step.active .step-number {
    background: #2f7d4f;
    color: #ffffff;
    box-shadow: 0 5px 12px rgba(47, 125, 79, 0.25);
}

.pemberitahuan-step strong {
    display: block;
    font-size: 13px;
    color: #28352e;
}

.pemberitahuan-step span {
    display: block;
    margin-top: 2px;
    color: #8b9790;
    font-size: 11px;
}

.step-line {
    flex: 1;
    height: 1px;
    background: #e4ebe6;
    margin: 0 18px;
}



/* ================================================================
   CARD
================================================================ */

.tanggal-filter-card,
.data-mutu-card {
    overflow: hidden;
}

.card-description {
    margin: 5px 0 0;
    color: #829087;
    font-size: 13px;
}

.calendar-header-icon {
    width: 44px;
    height: 44px;
    border-radius: 13px;
    background: #eef8f1;
    color: #2f7d4f;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 19px;
}



/* ================================================================
   DATE SELECTOR
================================================================ */

.tanggal-selector {
    margin: 0 22px 20px;
    padding: 20px;
    border: 1px solid #e2ebe5;
    background: linear-gradient(135deg, #f8fcf9, #ffffff);
    border-radius: 16px;
    display: flex;
    align-items: center;
    gap: 16px;
}

.tanggal-selector-icon {
    width: 48px;
    height: 48px;
    border-radius: 14px;
    background: #2f7d4f;
    color: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    flex-shrink: 0;
    box-shadow: 0 7px 16px rgba(47, 125, 79, 0.18);
}

.tanggal-selector-content {
    flex: 1;
}

.tanggal-selector-content label {
    display: block;
    font-size: 12px;
    font-weight: 700;
    color: #536159;
    margin-bottom: 7px;
}

.tanggal-input-row {
    display: flex;
    align-items: center;
    gap: 10px;
}

.tanggal-input {
    height: 44px;
    min-width: 210px;
    border: 1px solid #d7e3da;
    border-radius: 10px;
    padding: 0 13px;
    color: #29372f;
    background: #ffffff;
    font-weight: 600;
    outline: none;
    transition: 0.2s;
}

.tanggal-input:focus {
    border-color: #2f7d4f;
    box-shadow: 0 0 0 3px rgba(47, 125, 79, 0.10);
}

.tanggal-submit-button {
    height: 44px;
    white-space: nowrap;
}

.tanggal-selector-content small {
    display: block;
    margin-top: 7px;
    color: #8a9690;
    font-size: 11px;
}



/* ================================================================
   TANGGAL TERSEDIA
================================================================ */

.tanggal-tersedia-section {
    margin: 0 22px 22px;
    padding-top: 18px;
    border-top: 1px solid #edf1ee;
}

.tanggal-section-title {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 15px;
    margin-bottom: 13px;
}

.tanggal-section-title strong {
    display: block;
    font-size: 13px;
    color: #435149;
}

.tanggal-section-title strong i {
    color: #2f7d4f;
    margin-right: 5px;
}

.tanggal-section-title span {
    display: block;
    margin-top: 3px;
    font-size: 11px;
    color: #8b9790;
}

.tanggal-total-badge {
    background: #f1f7f3;
    color: #2f7d4f !important;
    border: 1px solid #dbeade;
    border-radius: 20px;
    padding: 6px 11px;
    font-size: 11px !important;
    font-weight: 700;
    white-space: nowrap;
}

.tanggal-chip-list {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(190px, 1fr));
    gap: 9px;
}

.tanggal-option {
    display: flex;
    align-items: center;
    gap: 9px;
    padding: 10px 11px;
    border: 1px solid #e1e9e3;
    background: #ffffff;
    border-radius: 12px;
    text-decoration: none;
    color: inherit;
    transition: all 0.2s ease;
    min-width: 0;
}

.tanggal-option:hover {
    border-color: #9bc8aa;
    background: #f8fcf9;
    transform: translateY(-1px);
}

.tanggal-option.active {
    border-color: #2f7d4f;
    background: #eef8f1;
    box-shadow: 0 4px 12px rgba(47, 125, 79, 0.08);
}

.tanggal-option-icon {
    width: 31px;
    height: 31px;
    border-radius: 9px;
    background: #f1f6f2;
    color: #5f7567;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    font-size: 13px;
}

.tanggal-option.active .tanggal-option-icon {
    background: #d9efdf;
    color: #2f7d4f;
}

.tanggal-option-text {
    flex: 1;
    min-width: 0;
}

.tanggal-option-text strong {
    display: block;
    color: #3b4c42;
    font-size: 12px;
    white-space: nowrap;
}

.tanggal-option-text span {
    display: block;
    margin-top: 2px;
    color: #929d96;
    font-size: 10px;
}

.tanggal-option.active .tanggal-option-text span {
    color: #2f7d4f;
    font-weight: 600;
}

.tanggal-active-icon {
    color: #2f7d4f;
    font-size: 15px;
}

.tanggal-arrow {
    color: #a6b0aa;
    font-size: 12px;
}



/* ================================================================
   DATA MUTU HEADER
================================================================ */

.data-mutu-header {
    align-items: center;
}

.tanggal-heading {
    color: #2f7d4f;
}

.data-summary-text {
    margin: 6px 0 0;
    color: #7b8880;
    font-size: 13px;
}

.data-summary-text strong {
    color: #2f7d4f;
}

.data-summary-text.empty {
    color: #9a817d;
}

.selected-count-wrapper {
    flex-shrink: 0;
}

.selected-count-badge {
    display: flex;
    align-items: center;
    gap: 5px;
    padding: 8px 13px;
    background: #eef8f1;
    border: 1px solid #dcece0;
    border-radius: 22px;
    color: #2f7d4f;
    font-size: 12px;
    font-weight: 600;
}

.selected-count-badge #selectedCount {
    font-weight: 800;
    font-size: 15px;
}



/* ================================================================
   SELECTION TOOLBAR
================================================================ */

.data-selection-toolbar {
    min-height: 67px;
    padding: 12px 22px;
    border-top: 1px solid #edf2ee;
    border-bottom: 1px solid #e8eee9;
    background: #fafcfb;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
}

.select-all-label {
    display: flex;
    align-items: center;
    gap: 11px;
    cursor: pointer;
    user-select: none;
}

.select-all-label > span:last-child {
    display: flex;
    flex-direction: column;
}

.select-all-label strong {
    color: #34453b;
    font-size: 13px;
}

.select-all-label small {
    margin-top: 2px;
    color: #909b95;
    font-size: 10px;
}

.toolbar-info {
    display: flex;
    align-items: center;
    gap: 7px;
    color: #89958e;
    font-size: 11px;
}

.toolbar-info i {
    color: #6f9b7c;
    font-size: 14px;
}



/* ================================================================
   CUSTOM CHECKBOX
================================================================ */

.custom-checkbox {
    width: 21px;
    height: 21px;
    position: relative;
    display: inline-flex;
    flex-shrink: 0;
}

.custom-checkbox input {
    position: absolute;
    opacity: 0;
    cursor: pointer;
    width: 100%;
    height: 100%;
    margin: 0;
    z-index: 2;
}

.checkbox-ui {
    width: 21px;
    height: 21px;
    border: 1.5px solid #cbd8ce;
    background: #ffffff;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.18s ease;
}

.custom-checkbox input:checked + .checkbox-ui {
    background: #2f7d4f;
    border-color: #2f7d4f;
}

.custom-checkbox input:checked + .checkbox-ui::after {
    content: "\f26e";
    font-family: "bootstrap-icons";
    color: #ffffff;
    font-size: 12px;
    font-weight: 700;
}

.custom-checkbox input:focus + .checkbox-ui {
    box-shadow: 0 0 0 3px rgba(47, 125, 79, 0.12);
}



/* ================================================================
   MUTU D LIST
================================================================ */

.mutu-d-list {
    padding: 8px 16px 10px;
    background: #ffffff;
}

.mutu-d-select-item {
    display: flex;
    align-items: center;
    gap: 14px;
    width: 100%;
    padding: 14px 12px;
    margin: 5px 0;
    border: 1px solid transparent;
    border-radius: 14px;
    cursor: pointer;
    transition: all 0.18s ease;
}

.mutu-d-select-item:hover {
    background: #f7fbf8;
    border-color: #e2ebe4;
}

.mutu-d-select-item:has(.mutu-checkbox:checked) {
    background: #f0f9f3;
    border-color: #b9d9c3;
    box-shadow: 0 3px 10px rgba(47, 125, 79, 0.05);
}

.mutu-d-avatar {
    width: 40px;
    height: 40px;
    border-radius: 12px;
    background: linear-gradient(135deg, #e9f5ed, #dceee2);
    color: #2f7d4f;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    font-weight: 800;
    flex-shrink: 0;
}

.mutu-d-main {
    flex: 1;
    min-width: 0;
}

.mutu-d-name-row {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 5px;
}

.mutu-d-name {
    color: #293a30;
    font-size: 14px;
    line-height: 1.3;
}

.mutu-d-status {
    padding: 3px 7px;
    border-radius: 5px;
    background: #fff8df;
    border: 1px solid #f0df9f;
    color: #9b7714;
    font-size: 8px;
    font-weight: 800;
    letter-spacing: 0.4px;
    white-space: nowrap;
}

.mutu-d-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 6px 15px;
    color: #929d96;
    font-size: 10px;
}

.mutu-d-meta span {
    white-space: nowrap;
}

.mutu-d-meta i {
    margin-right: 3px;
    color: #a0aaa4;
}

.mutu-d-meta strong {
    color: #738078;
    font-weight: 600;
}

.mutu-d-meta .pta-empty strong {
    color: #b18a82;
}

.mutu-d-right {
    min-width: 95px;
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 14px;
}

.mutu-d-truck {
    display: flex;
    align-items: center;
    gap: 5px;
    color: #6f7e75;
    font-size: 11px;
    font-weight: 600;
}

.mutu-d-truck i {
    color: #87958c;
}

.mutu-d-arrow {
    color: #b1bbb5;
    font-size: 11px;
}

.mutu-d-select-item:hover .mutu-d-arrow {
    color: #2f7d4f;
}



/* ================================================================
   ACTION FOOTER
================================================================ */

.data-action-footer {
    padding: 17px 22px;
    border-top: 1px solid #e8eee9;
    background: #fbfcfb;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
}

.action-help {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #8b9790;
    font-size: 11px;
}

.action-help i {
    color: #2f7d4f;
    font-size: 15px;
}

.action-buttons {
    display: flex;
    align-items: center;
    gap: 9px;
}

.preview-button {
    min-width: 205px;
}

.preview-button:disabled {
    opacity: 0.5;
    cursor: not-allowed;
    transform: none !important;
    box-shadow: none !important;
}

.button-arrow {
    margin-left: 6px;
}



/* ================================================================
   EMPTY STATE
================================================================ */

.empty-date-state {
    padding: 65px 30px;
    text-align: center;
}

.empty-date-icon {
    width: 68px;
    height: 68px;
    border-radius: 20px;
    background: #f4f7f5;
    color: #9aa69f;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 17px;
    font-size: 27px;
}

.empty-date-state h4 {
    margin: 0 0 7px;
    color: #48564e;
}

.empty-date-state p {
    max-width: 520px;
    margin: 0 auto;
    color: #8c9891;
    font-size: 13px;
    line-height: 1.7;
}

.empty-date-state p strong {
    color: #2f7d4f;
}

.empty-date-hint {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    margin-top: 18px;
    padding: 9px 13px;
    border-radius: 9px;
    background: #fff9e9;
    border: 1px solid #f1e5bd;
    color: #8b762e;
    font-size: 11px;
}

.empty-date-hint i {
    font-size: 13px;
}



/* ================================================================
   FLOW
================================================================ */

.pemberitahuan-flow {
    margin-top: 18px;
    padding: 16px 19px;
    border: 1px solid #f0e6c9;
    background: linear-gradient(
        135deg,
        #fffdf6,
        #fffaf0
    );
    border-radius: 14px;
    display: flex;
    align-items: center;
    gap: 13px;
}

.flow-icon {
    width: 38px;
    height: 38px;
    border-radius: 11px;
    background: #fff1c7;
    color: #b18825;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.flow-content strong {
    display: block;
    color: #6e5a22;
    font-size: 12px;
}

.flow-content p {
    margin: 4px 0 0;
    color: #8d7b4c;
    font-size: 11px;
    line-height: 1.6;
}

.flow-content p strong {
    display: inline;
    color: #6f5b24;
}



/* ================================================================
   RESPONSIVE
================================================================ */

@media (max-width: 1200px) {

    .pemberitahuan-create-page {
        padding-left: 24px;
        padding-right: 20px;
    }

}


@media (max-width: 900px) {

    .pemberitahuan-create-page {
        padding-left: 20px;
        padding-right: 18px;
    }

    .pemberitahuan-steps {
        overflow-x: auto;
    }

    .pemberitahuan-step {
        min-width: 145px;
    }

    .step-line {
        min-width: 25px;
    }

    .tanggal-chip-list {
        grid-template-columns: repeat(
            auto-fill,
            minmax(170px, 1fr)
        );
    }

    .toolbar-info {
        display: none;
    }

    .data-action-footer {
        align-items: flex-start;
        flex-direction: column;
    }

    .action-buttons {
        width: 100%;
        justify-content: flex-end;
    }

}



@media (max-width: 650px) {

    .pemberitahuan-create-page {
        padding-left: 14px;
        padding-right: 14px;
    }

    .pemberitahuan-title-wrap {
        align-items: flex-start;
    }

    .pemberitahuan-title-icon {
        width: 44px;
        height: 44px;
        border-radius: 13px;
    }

    .pemberitahuan-steps {
        padding: 13px;
    }

    .pemberitahuan-step span {
        display: none;
    }

    .step-line {
        margin: 0 8px;
    }

    .tanggal-selector {
        margin: 0 14px 16px;
        align-items: flex-start;
    }

    .tanggal-input-row {
        flex-direction: column;
        align-items: stretch;
    }

    .tanggal-input {
        width: 100%;
    }

    .tanggal-submit-button {
        width: 100%;
    }

    .tanggal-tersedia-section {
        margin: 0 14px 16px;
    }

    .tanggal-section-title {
        align-items: flex-start;
        flex-direction: column;
    }

    .tanggal-chip-list {
        grid-template-columns: 1fr;
    }

    .data-selection-toolbar {
        padding: 12px 15px;
    }

    .mutu-d-list {
        padding: 7px 9px;
    }

    .mutu-d-select-item {
        align-items: flex-start;
        padding: 13px 8px;
    }

    .mutu-d-right {
        min-width: auto;
    }

    .mutu-d-truck {
        display: none;
    }

    .mutu-d-meta {
        flex-direction: column;
        gap: 3px;
    }

    .mutu-d-name-row {
        flex-wrap: wrap;
    }

    .data-action-footer {
        padding: 15px;
    }

    .action-buttons {
        width: 100%;
    }

    .action-buttons a,
    .action-buttons button {
        flex: 1;
        justify-content: center;
    }

    .pemberitahuan-flow {
        align-items: flex-start;
    }

}

</style>

@endpush



{{-- ============================================================= --}}
{{-- JAVASCRIPT --}}
{{-- ============================================================= --}}

@push('scripts')

<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {

        const selectAll =
            document.getElementById('selectAll');

        const checkboxes =
            document.querySelectorAll('.mutu-checkbox');

        const selectedCount =
            document.getElementById('selectedCount');

        const previewButton =
            document.getElementById('previewButton');

        const form =
            document.getElementById('pemberitahuanForm');


        /*
        |--------------------------------------------------------------------------
        | UPDATE SELECTION
        |--------------------------------------------------------------------------
        */

        function updateSelectedCount() {

            const checked =
                document.querySelectorAll(
                    '.mutu-checkbox:checked'
                ).length;


            /*
            |--------------------------------------------------------------
            | Jumlah data
            |--------------------------------------------------------------
            */

            if (selectedCount) {

                selectedCount.textContent =
                    checked;

            }


            /*
            |--------------------------------------------------------------
            | Aktif / nonaktif preview
            |--------------------------------------------------------------
            */

            if (previewButton) {

                previewButton.disabled =
                    checked === 0;

            }


            /*
            |--------------------------------------------------------------
            | Checkbox pilih semua
            |--------------------------------------------------------------
            */

            if (selectAll) {

                selectAll.checked =
                    checkboxes.length > 0 &&
                    checked === checkboxes.length;

                selectAll.indeterminate =
                    checked > 0 &&
                    checked < checkboxes.length;

            }

        }


        /*
        |--------------------------------------------------------------------------
        | PILIH SEMUA
        |--------------------------------------------------------------------------
        */

        if (selectAll) {

            selectAll.addEventListener(
                'change',
                function () {

                    checkboxes.forEach(
                        function (checkbox) {

                            checkbox.checked =
                                selectAll.checked;

                        }
                    );


                    updateSelectedCount();

                }
            );

        }


        /*
        |--------------------------------------------------------------------------
        | CHECKBOX INDIVIDUAL
        |--------------------------------------------------------------------------
        */

        checkboxes.forEach(
            function (checkbox) {

                checkbox.addEventListener(
                    'change',
                    function () {

                        updateSelectedCount();

                    }
                );

            }
        );


        /*
        |--------------------------------------------------------------------------
        | SUBMIT PREVIEW
        |--------------------------------------------------------------------------
        */

        if (form) {

            form.addEventListener(
                'submit',
                function (event) {

                    const checked =
                        document.querySelectorAll(
                            '.mutu-checkbox:checked'
                        ).length;


                    /*
                    |----------------------------------------------------------
                    | Jangan submit jika belum ada yang dipilih
                    |----------------------------------------------------------
                    */

                    if (checked === 0) {

                        event.preventDefault();

                        return;

                    }


                    /*
                    |----------------------------------------------------------
                    | Loading state
                    |----------------------------------------------------------
                    */

                    if (previewButton) {

                        previewButton.disabled =
                            true;


                        previewButton.innerHTML = `

                            <span
                                class="spinner-border spinner-border-sm"
                                role="status"
                            ></span>

                            Membuat Preview...

                        `;

                    }

                }
            );

        }


        /*
        |--------------------------------------------------------------------------
        | INITIAL STATE
        |--------------------------------------------------------------------------
        */

        updateSelectedCount();

    }

);

</script>

@endpush