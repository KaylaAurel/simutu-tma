@extends('layouts.app')

@section('title', 'Preview Import')
@section('page-title', 'Preview Import')

@section('content')

{{-- ============================================================= --}}
{{-- HEADER --}}
{{-- ============================================================= --}}

<div class="page-header">

    <div>

        <div class="breadcrumb">

            <span>
                Import Prodis
            </span>

            <i class="bi bi-chevron-right"></i>

            <span>
                Preview
            </span>

        </div>

        <h2>
            Preview Data Prodis
        </h2>

        <p>
            Periksa file Prodis sebelum data diproses ke dalam sistem.
        </p>

    </div>

    <a
        href="{{ route('import.index') }}"
        class="secondary-button"
    >

        <i class="bi bi-arrow-left"></i>

        Kembali

    </a>

</div>



{{-- ============================================================= --}}
{{-- ALERT --}}
{{-- ============================================================= --}}

@if(session('success'))

    <div class="import-info-box success">

        <i class="bi bi-check-circle-fill"></i>

        <p>
            {{ session('success') }}
        </p>

    </div>

@endif


@if(session('error'))

    <div class="import-info-box danger">

        <i class="bi bi-exclamation-triangle-fill"></i>

        <p>
            {{ session('error') }}
        </p>

    </div>

@endif



{{-- ============================================================= --}}
{{-- INFO FILE --}}
{{-- ============================================================= --}}

<div class="simutu-card">

    <div class="card-header">

        <div>

            <span class="card-eyebrow">
                FILE IMPORT
            </span>

            <h3>
                Informasi File
            </h3>

        </div>

        <i class="bi bi-file-earmark-excel card-header-icon"></i>

    </div>


    <div class="import-file-info">

        <strong>

            {{ $import->nama_file
                ?? $import->file_name
                ?? 'File Prodis'
            }}

        </strong>


        <span>

            Diunggah:

            {{ optional($import->created_at)
                ->format('d M Y, H:i')
            }}

        </span>

    </div>

</div>



{{-- ============================================================= --}}
{{-- PREVIEW --}}
{{-- ============================================================= --}}

@if(isset($preview))


{{-- ============================================================= --}}
{{-- SUMMARY --}}
{{-- ============================================================= --}}

<div class="preview-summary-grid">


    {{-- TOTAL DATA --}}

    <div class="preview-summary-card green">

        <span>
            Total Data
        </span>

        <strong>
            {{ $preview['total_rows'] ?? 0 }}
        </strong>

        <i class="bi bi-database-fill"></i>

    </div>


    {{-- MUTU D --}}

    <div class="preview-summary-card yellow">

        <span>
            Data Mutu D
        </span>

        <strong>
            {{ $preview['mutu_d_rows'] ?? 0 }}
        </strong>

        <i class="bi bi-exclamation-triangle-fill"></i>

    </div>


    {{-- DATA LAINNYA --}}

    <div class="preview-summary-card blue">

        <span>
            Data Lainnya
        </span>

        <strong>
            {{ $preview['non_mutu_d_rows'] ?? 0 }}
        </strong>

        <i class="bi bi-check-circle-fill"></i>

    </div>


    {{-- TANGGAL MEMO --}}

    <div class="preview-summary-card purple">

        <span>
            Tanggal Memo
        </span>

        <strong>
            {{ count($preview['memo_dates'] ?? []) }}
        </strong>

        <i class="bi bi-calendar3"></i>

    </div>

</div>



{{-- ============================================================= --}}
{{-- INFORMASI TANGGAL MEMO --}}
{{-- ============================================================= --}}

@if(!empty($preview['memo_dates']))

    <div class="simutu-card preview-date-card">

        <div class="card-header">

            <div>

                <span class="card-eyebrow">
                    INFORMASI MEMO
                </span>

                <h3>
                    Tanggal Memo Terdeteksi
                </h3>

            </div>

            <i class="bi bi-calendar-check card-header-icon"></i>

        </div>


        <div class="memo-date-list">

            @foreach($preview['memo_dates'] as $date)

                @php

                    $displayDate = '-';

                    $dateString = trim(
                        (string) $date
                    );

                    if (
                        preg_match(
                            '/^\d{4}-\d{2}-\d{2}$/',
                            $dateString
                        )
                    ) {

                        try {

                            $displayDate =
                                \Carbon\Carbon::createFromFormat(
                                    'Y-m-d',
                                    $dateString
                                )->translatedFormat('d M Y');

                        } catch (\Throwable $e) {

                            $displayDate = '-';

                        }

                    }

                @endphp


                @if($displayDate !== '-')

                    <span class="memo-date-chip">

                        <i class="bi bi-calendar-check"></i>

                        {{ $displayDate }}

                    </span>

                @endif

            @endforeach

        </div>


        <div class="import-info-box">

            <i class="bi bi-lightbulb-fill"></i>

            <p>

                SIMUTU-TMA menggunakan
                <strong>
                    tanggal memo dari data Prodis
                </strong>
                sebagai dasar identifikasi data Mutu D,
                bukan tanggal saat file di-upload.

            </p>

        </div>

    </div>

@else

    {{-- ========================================================= --}}
    {{-- TANGGAL MEMO TIDAK TERBACA --}}
    {{-- ========================================================= --}}

    <div class="simutu-card">

        <div class="import-info-box danger">

            <i class="bi bi-exclamation-triangle-fill"></i>

            <p>

                <strong>
                    Tanggal memo belum terbaca.
                </strong>

                Sistem berhasil membaca
                <strong>
                    {{ $preview['total_rows'] ?? 0 }}
                </strong>
                baris data, tetapi belum menemukan tanggal memo
                dari struktur Excel.

            </p>

        </div>

    </div>

@endif



{{-- ============================================================= --}}
{{-- DATA EXCEL --}}
{{-- ============================================================= --}}

<div class="simutu-card">

    <div class="card-header">

        <div>

            <span class="card-eyebrow">
                DATA EXCEL
            </span>

            <h3>
                Data Hasil Pembacaan
            </h3>

        </div>


        <span class="table-count">

            {{ count($preview['rows'] ?? []) }}

            baris

        </span>

    </div>



    <div class="table-responsive">

        <table class="simutu-table preview-table">

            <thead>

                <tr>

                    <th>
                        #
                    </th>

                    <th>
                        Nama Petani
                    </th>

                    <th>
                        Kode Petani
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
                        Mutu
                    </th>

                </tr>

            </thead>


            <tbody>

                @forelse($preview['rows'] ?? [] as $index => $row)

                    @php

                        /*
                        |--------------------------------------------------------------------------
                        | Nama Petani
                        |--------------------------------------------------------------------------
                        */

                        $namaPetani =
                            trim(
                                (string) (
                                    $row['nama_petani']
                                    ?? ''
                                )
                            );


                        /*
                        |--------------------------------------------------------------------------
                        | Kode Petani
                        |--------------------------------------------------------------------------
                        */

                        $kodePetani =
                            trim(
                                (string) (
                                    $row['kode_petani']
                                    ?? ''
                                )
                            );


                        /*
                        |--------------------------------------------------------------------------
                        | SPTA
                        |--------------------------------------------------------------------------
                        */

                        $nomorSpta =
                            trim(
                                (string) (
                                    $row['nomor_spta']
                                    ?? ''
                                )
                            );


                        /*
                        |--------------------------------------------------------------------------
                        | Wilayah
                        |--------------------------------------------------------------------------
                        */

                        $wilayah =
                            trim(
                                (string) (
                                    $row['wilayah']
                                    ?? ''
                                )
                            );


                        /*
                        |--------------------------------------------------------------------------
                        | Tanggal Memo
                        |--------------------------------------------------------------------------
                        */

                        $tanggalMemo =
                            trim(
                                (string) (
                                    $row['tanggal_memo']
                                    ?? ''
                                )
                            );

                        $tanggalMemoDisplay = '-';

                        /*
                        | Hanya proses kalau benar-benar format Y-m-d.
                        */

                        if (
                            preg_match(
                                '/^\d{4}-\d{2}-\d{2}$/',
                                $tanggalMemo
                            )
                        ) {

                            try {

                                $tanggalMemoDisplay =
                                    \Carbon\Carbon::createFromFormat(
                                        'Y-m-d',
                                        $tanggalMemo
                                    )->translatedFormat('d M Y');

                            } catch (\Throwable $e) {

                                $tanggalMemoDisplay = '-';

                            }

                        }


                        /*
                        |--------------------------------------------------------------------------
                        | Mutu
                        |--------------------------------------------------------------------------
                        */

                        $mutu =
                            strtoupper(
                                trim(
                                    (string) (
                                        $row['jenis_mutu']
                                        ?? $row['kualitas']
                                        ?? ''
                                    )
                                )
                            );

                    @endphp


                    <tr>


                        {{-- ================================================= --}}
                        {{-- NOMOR --}}
                        {{-- ================================================= --}}

                        <td>

                            {{ $index + 1 }}

                        </td>



                        {{-- ================================================= --}}
                        {{-- NAMA PETANI --}}
                        {{-- ================================================= --}}

                        <td>

                            @if($namaPetani !== '')

                                <strong>
                                    {{ $namaPetani }}
                                </strong>

                            @else

                                -

                            @endif

                        </td>



                        {{-- ================================================= --}}
                        {{-- KODE PETANI --}}
                        {{-- ================================================= --}}

                        <td>

                            {{ $kodePetani !== ''
                                ? $kodePetani
                                : '-'
                            }}

                        </td>



                        {{-- ================================================= --}}
                        {{-- SPTA --}}
                        {{-- ================================================= --}}

                        <td>

                            {{ $nomorSpta !== ''
                                ? $nomorSpta
                                : '-'
                            }}

                        </td>



                        {{-- ================================================= --}}
                        {{-- WILAYAH --}}
                        {{-- ================================================= --}}

                        <td>

                            {{ $wilayah !== ''
                                ? $wilayah
                                : '-'
                            }}

                        </td>



                        {{-- ================================================= --}}
                        {{-- TANGGAL MEMO --}}
                        {{-- ================================================= --}}

                        <td>

                            @if($tanggalMemoDisplay !== '-')

                                {{ $tanggalMemoDisplay }}

                            @else

                                -

                            @endif

                        </td>



                        {{-- ================================================= --}}
                        {{-- MUTU --}}
                        {{-- ================================================= --}}

                        <td>

                            @if($mutu === 'D')

                                <span class="mutu-d-badge">
                                    D
                                </span>

                            @elseif($mutu !== '')

                                <span class="status-badge neutral">
                                    {{ $mutu }}
                                </span>

                            @else

                                <span class="status-badge neutral">
                                    -
                                </span>

                            @endif

                        </td>


                    </tr>

                @empty


                    {{-- ================================================= --}}
                    {{-- DATA KOSONG --}}
                    {{-- ================================================= --}}

                    <tr>

                        <td colspan="7">

                            <div class="empty-state">

                                <div class="empty-icon">

                                    <i class="bi bi-database-x"></i>

                                </div>


                                <h4>
                                    Tidak ada data
                                </h4>


                                <p>
                                    Tidak ditemukan data pada file Prodis.
                                </p>

                            </div>

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>



    {{-- ============================================================= --}}
    {{-- ACTION --}}
    {{-- ============================================================= --}}

    <div class="preview-actions">


        {{-- BATAL --}}

        <a
            href="{{ route('import.index') }}"
            class="secondary-button"
        >

            <i class="bi bi-x-circle"></i>

            Batalkan

        </a>



        {{-- PROSES --}}

        <form
            action="{{ route('import.process', $import->id) }}"
            method="POST"
            id="processImportForm"
        >

            @csrf


            <button
                type="submit"
                class="primary-button"
                id="processButton"
            >

                <i class="bi bi-database-add"></i>

                Simpan & Proses Data

            </button>

        </form>


    </div>

</div>


@else


{{-- ============================================================= --}}
{{-- PREVIEW TIDAK TERSEDIA --}}
{{-- ============================================================= --}}

<div class="simutu-card">

    <div class="empty-state">

        <div class="empty-icon">

            <i class="bi bi-file-earmark-x"></i>

        </div>


        <h4>
            Preview tidak tersedia
        </h4>


        <p>
            Data preview file Prodis belum tersedia.
        </p>


        <a
            href="{{ route('import.index') }}"
            class="primary-button"
        >

            <i class="bi bi-arrow-left"></i>

            Kembali ke Import

        </a>

    </div>

</div>

@endif


@endsection



{{-- ============================================================= --}}
{{-- SCRIPT --}}
{{-- ============================================================= --}}

@push('scripts')

<script>

document.addEventListener('DOMContentLoaded', function () {

    const form =
        document.getElementById(
            'processImportForm'
        );

    const button =
        document.getElementById(
            'processButton'
        );


    if (!form || !button) {
        return;
    }


    form.addEventListener(
        'submit',
        function () {

            button.disabled = true;

            button.innerHTML = `
                <span
                    class="spinner-border spinner-border-sm"
                    role="status"
                    aria-hidden="true"
                ></span>

                Memproses Data...
            `;

        }
    );

});

</script>

@endpush