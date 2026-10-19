@extends('layouts.app')

@section('title', 'Import Prodis')
@section('page-title', 'Import Prodis')

@section('content')

<div class="page-header import-page-header">

    <div>

        <div class="breadcrumb">
            <span>Data & Proses</span>
            <i class="bi bi-chevron-right"></i>
            <strong>Import Prodis</strong>
        </div>

        <div class="page-title-with-icon">

            <div class="page-title-icon green">
                <i class="bi bi-file-earmark-spreadsheet-fill"></i>
            </div>

            <div>

                <h2>
                    Import Data Prodis
                </h2>

                <p>
                    Upload hasil export Prodis untuk membaca dan
                    mengidentifikasi data Mutu D secara otomatis.
                </p>

            </div>

        </div>

    </div>

</div>


<div class="import-layout">


    {{-- ========================================================= --}}
    {{-- UPLOAD FILE --}}
    {{-- ========================================================= --}}

    <div class="simutu-card">

        <div class="card-header">

            <div>

                <span class="card-eyebrow">
                    IMPORT DATA
                </span>

                <h3>
                    Upload File Prodis
                </h3>

            </div>

            <div class="excel-icon">
                <i class="bi bi-file-earmark-excel-fill"></i>
            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- FORM UPLOAD --}}
        {{-- ===================================================== --}}

        <form
            action="{{ route('import.store') }}"
            method="POST"
            enctype="multipart/form-data"
            id="prodisImportForm"
        >

            @csrf


            <label
                for="prodis_file"
                class="file-dropzone"
                id="fileDropzone"
            >

                <input
                    type="file"
                    name="file"
                    id="prodis_file"
                    accept=".xlsx,.xls"
                    hidden
                    required
                >


                <div class="file-upload-icon">

                    <i class="bi bi-cloud-arrow-up-fill"></i>

                </div>


                <h4>
                    Pilih atau tarik file Excel
                </h4>


                <p>

                    Format yang didukung:

                    <strong>
                        .xlsx, .xls
                    </strong>

                </p>


                <span class="file-upload-button">
                    Pilih File
                </span>


                <div
                    class="selected-file"
                    id="selectedFile"
                ></div>

            </label>


            {{-- ================================================= --}}
            {{-- INFO --}}
            {{-- ================================================= --}}

            <div class="import-info-box">

                <i class="bi bi-info-circle-fill"></i>

                <div>

                    <strong>
                        Perhatian
                    </strong>

                    <p>

                        Pastikan file yang diupload merupakan
                        hasil export data Prodis dan memiliki
                        kolom tanggal memo serta data kualitas
                        mutu tebu.

                    </p>

                </div>

            </div>


            {{-- ================================================= --}}
            {{-- ERROR VALIDATION --}}
            {{-- ================================================= --}}

            @if ($errors->any())

                <div class="import-info-box danger">

                    <i class="bi bi-exclamation-triangle-fill"></i>

                    <div>

                        <strong>
                            Upload gagal
                        </strong>

                        <ul style="margin-bottom: 0;">

                            @foreach ($errors->all() as $error)

                                <li>
                                    {{ $error }}
                                </li>

                            @endforeach

                        </ul>

                    </div>

                </div>

            @endif


            {{-- ================================================= --}}
            {{-- BUTTON --}}
            {{-- ================================================= --}}

            <button
                type="submit"
                class="primary-button full"
                id="previewButton"
            >

                <i class="bi bi-search"></i>

                Preview Data

            </button>

        </form>

    </div>



    {{-- ========================================================= --}}
    {{-- RIWAYAT IMPORT --}}
    {{-- ========================================================= --}}

    <div class="simutu-card">

        <div class="card-header">

            <div>

                <span class="card-eyebrow">
                    RIWAYAT
                </span>

                <h3>
                    Import Terakhir
                </h3>

            </div>

            <i class="bi bi-clock-history card-header-icon"></i>

        </div>


        @if(isset($imports) && $imports->count())


            <div class="import-history">

                @foreach($imports as $import)

                    <div class="import-history-item">


                        <div class="import-file-icon">

                            <i class="bi bi-file-earmark-excel"></i>

                        </div>


                        <div class="import-file-info">

                            <strong>

                                {{ $import->nama_file
                                    ?? $import->file_name
                                    ?? 'File Prodis'
                                }}

                            </strong>


                            <span>

                                {{ optional($import->created_at)
                                    ->format('d M Y, H:i')
                                }}

                            </span>

                        </div>


                        <div>

                            @if($import->status === 'success')

                                <span class="status-badge success">
                                    Berhasil
                                </span>

                            @elseif($import->status === 'partial')

                                <span class="status-badge warning">
                                    Sebagian
                                </span>

                            @elseif($import->status === 'processing')

                                <span class="status-badge info">
                                    Diproses
                                </span>

                            @elseif($import->status === 'uploaded')

                                <span class="status-badge info">
                                    Menunggu Proses
                                </span>

                            @else

                                <span class="status-badge danger">
                                    Gagal
                                </span>

                            @endif

                        </div>


                    </div>

                @endforeach

            </div>


            {{-- Pagination --}}

            @if(method_exists($imports, 'links'))

                <div style="margin-top: 20px;">
                    {{ $imports->links() }}
                </div>

            @endif


        @else


            <div class="empty-state compact">

                <div class="empty-icon">

                    <i class="bi bi-folder2-open"></i>

                </div>

                <h4>
                    Belum ada riwayat
                </h4>

                <p>
                    Riwayat import akan muncul di sini.
                </p>

            </div>


        @endif

    </div>

</div>

@endsection


@push('scripts')

<script>

document.addEventListener('DOMContentLoaded', function () {

    const input =
        document.getElementById('prodis_file');

    const selectedFile =
        document.getElementById('selectedFile');

    const dropzone =
        document.getElementById('fileDropzone');

    const form =
        document.getElementById('prodisImportForm');

    const button =
        document.getElementById('previewButton');


    if (!input) {
        return;
    }


    /*
    |--------------------------------------------------------------------------
    | File dipilih
    |--------------------------------------------------------------------------
    */

    input.addEventListener('change', function () {

        if (this.files.length > 0) {

            const file = this.files[0];


            selectedFile.innerHTML = `
                <i class="bi bi-file-earmark-excel-fill"></i>
                <span>${file.name}</span>
            `;


            dropzone.classList.add('has-file');

        }

    });


    /*
    |--------------------------------------------------------------------------
    | Submit
    |--------------------------------------------------------------------------
    */

    if (form) {

        form.addEventListener('submit', function () {

            if (button) {

                button.disabled = true;

                button.innerHTML = `
                    <span
                        class="spinner-border spinner-border-sm"
                        role="status"
                    ></span>

                    Membaca File...
                `;

            }

        });

    }

});

</script>

@endpush