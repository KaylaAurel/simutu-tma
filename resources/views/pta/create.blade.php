@extends('layouts.app')

@section('title', 'Tambah PTA')
@section('page-title', 'Tambah PTA')

@section('content')

<div class="page-header pta-page-header">

    <div>

        <div class="breadcrumb">

            <a href="{{ route('pta.index') }}">
                Data PTA
            </a>

            <i class="bi bi-chevron-right"></i>

            <span>
                Tambah
            </span>

        </div>

        <h2>
            Tambah Data PTA
        </h2>

        <p>
            Tambahkan PTA baru sebagai penerima otomatis pemberitahuan Mutu D.
        </p>

    </div>

</div>


<div class="pta-form-layout">


    {{-- FORM --}}
    <div class="simutu-card pta-form-card">


        <div class="pta-form-heading">

            <div class="pta-form-heading-icon">
                <i class="bi bi-person-plus-fill"></i>
            </div>

            <div>

                <span class="card-eyebrow">
                    DATA PENERIMA
                </span>

                <h3>
                    Informasi PTA
                </h3>

                <p>
                    Lengkapi data PTA yang akan menerima notifikasi.
                </p>

            </div>

        </div>


        <form action="{{ route('pta.store') }}"
              method="POST">

            @csrf


            <div class="pta-form-grid">


                {{-- NAMA --}}
                <div class="form-group">

                    <label>
                        Nama PTA
                        <span>*</span>
                    </label>

                    <div class="pta-input-wrapper">

                        <i class="bi bi-person-badge"></i>

                        <input type="text"
                               name="nama_pta"
                               value="{{ old('nama_pta') }}"
                               placeholder="Contoh: PTA Budi"
                               required>

                    </div>

                    @error('nama_pta')

                        <small class="form-error">
                            {{ $message }}
                        </small>

                    @enderror

                </div>



                {{-- KODE --}}
                <div class="form-group">

                    <label>
                        Kode PTA
                        <span>*</span>
                    </label>

                    <div class="pta-input-wrapper">

                        <i class="bi bi-upc-scan"></i>

                        <input type="text"
                               name="kode_pta"
                               value="{{ old('kode_pta') }}"
                               placeholder="Contoh: BD"
                               required>

                    </div>

                    @error('kode_pta')

                        <small class="form-error">
                            {{ $message }}
                        </small>

                    @enderror

                </div>



                {{-- WHATSAPP --}}
                <div class="form-group pta-full-width">

                    <label>
                        Nomor WhatsApp
                        <span>*</span>
                    </label>

                    <div class="pta-input-wrapper whatsapp">

                        <i class="bi bi-whatsapp"></i>

                        <input type="text"
                               name="no_whatsapp"
                               value="{{ old('no_whatsapp') }}"
                               placeholder="Contoh: 081234567890"
                               required>

                    </div>

                    <small class="form-help">
                        Gunakan nomor WhatsApp aktif yang terhubung dengan PTA.
                    </small>

                    @error('no_whatsapp')

                        <small class="form-error">
                            {{ $message }}
                        </small>

                    @enderror

                </div>



                {{-- STATUS --}}
                <div class="form-group pta-full-width">

                    <div class="pta-status-setting">

                        <div class="pta-status-setting-icon">

                            <i class="bi bi-bell-fill"></i>

                        </div>


                        <div class="pta-status-setting-info">

                            <strong>
                                Status Penerima
                            </strong>

                            <span>
                                Aktifkan agar PTA dapat menerima pemberitahuan Mutu D.
                            </span>

                        </div>


                        <select name="status"
                                class="pta-status-select">

                            <option value="aktif"
                                @selected(old('status', 'aktif') === 'aktif')>

                                Aktif

                            </option>

                            <option value="nonaktif"
                                @selected(old('status') === 'nonaktif')>

                                Nonaktif

                            </option>

                        </select>

                    </div>

                    @error('status')

                        <small class="form-error">
                            {{ $message }}
                        </small>

                    @enderror

                </div>

            </div>



            <div class="form-actions">

                <a href="{{ route('pta.index') }}"
                   class="secondary-button">

                    <i class="bi bi-arrow-left"></i>

                    Batal

                </a>


                <button type="submit"
                        class="primary-button">

                    <i class="bi bi-check-lg"></i>

                    Simpan PTA

                </button>

            </div>

        </form>

    </div>



    {{-- INFO --}}
    <div class="pta-info-panel">


        <div class="pta-info-icon">
            <i class="bi bi-whatsapp"></i>
        </div>


        <span class="pta-info-label">
            INTEGRASI NOTIFIKASI
        </span>


        <h3>
            WhatsApp Notification
        </h3>


        <p>
            PTA yang berstatus aktif akan menjadi penerima
            pemberitahuan Mutu D melalui integrasi WhatsApp.
        </p>



        <div class="pta-info-list">


            <div>

                <span class="pta-info-check">
                    <i class="bi bi-check-lg"></i>
                </span>

                <span>
                    Nomor WhatsApp harus aktif.
                </span>

            </div>


            <div>

                <span class="pta-info-check">
                    <i class="bi bi-check-lg"></i>
                </span>

                <span>
                    Gunakan nomor Indonesia yang valid.
                </span>

            </div>


            <div>

                <span class="pta-info-check">
                    <i class="bi bi-check-lg"></i>
                </span>

                <span>
                    PTA aktif menerima notifikasi otomatis.
                </span>

            </div>

        </div>



        <div class="pta-info-note">

            <i class="bi bi-info-circle-fill"></i>

            <span>
                Pastikan nomor yang dimasukkan benar agar
                pemberitahuan dapat diterima.
            </span>

        </div>

    </div>

</div>

@endsection