@extends('layouts.app')

@section('title', 'Edit PTA')
@section('page-title', 'Edit PTA')

@section('content')

<div class="page-header pta-page-header">
    <div>

        <div class="breadcrumb">

            <a href="{{ route('pta.index') }}">
                Data PTA
            </a>

            <i class="bi bi-chevron-right"></i>

            <span>
                Edit
            </span>

        </div>

        <h2>
            Edit Data PTA
        </h2>

        <p>
            Perbarui informasi PTA dan status penerima pemberitahuan.
        </p>

    </div>

</div>


<div class="pta-form-layout">


    {{-- FORM --}}
    <div class="simutu-card pta-form-card">


        <div class="pta-form-heading">

            <div class="pta-form-heading-icon edit">

                <i class="bi bi-pencil-square"></i>

            </div>


            <div>

                <span class="card-eyebrow">
                    PERUBAHAN DATA
                </span>

                <h3>
                    {{ $pta->nama_pta }}
                </h3>

                <p>
                    Perbarui data penerima pemberitahuan.
                </p>

            </div>

        </div>



        <form action="{{ route('pta.update', $pta->id) }}"
              method="POST">

            @csrf

            @method('PUT')


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
                               value="{{ old('nama_pta', $pta->nama_pta) }}"
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
                               value="{{ old('kode_pta', $pta->kode_pta) }}"
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
                               value="{{ old('no_whatsapp', $pta->no_whatsapp) }}"
                               required>

                    </div>


                    <small class="form-help">
                        Pastikan nomor WhatsApp masih aktif.
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
                                Tentukan apakah PTA masih menerima pemberitahuan Mutu D.
                            </span>

                        </div>


                        <select name="status"
                                class="pta-status-select">

                            <option value="aktif"
                                @selected(old('status', $pta->status) === 'aktif')>

                                Aktif

                            </option>

                            <option value="nonaktif"
                                @selected(old('status', $pta->status) === 'nonaktif')>

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

                    Simpan Perubahan

                </button>

            </div>

        </form>

    </div>



    {{-- CURRENT STATUS --}}
    <div class="pta-info-panel">


        <div class="pta-info-icon">

            @if($pta->status === 'aktif')

                <i class="bi bi-person-check-fill"></i>

            @else

                <i class="bi bi-person-x-fill"></i>

            @endif

        </div>


        <span class="pta-info-label">
            STATUS SAAT INI
        </span>


        <h3>
            {{ $pta->nama_pta }}
        </h3>


        <p>
            PTA dengan kode
            <strong>{{ $pta->kode_pta }}</strong>
            terdaftar sebagai penerima pemberitahuan.
        </p>



        <div class="pta-current-status">


            @if($pta->status === 'aktif')

                <span class="pta-status active">

                    <span class="status-dot"></span>

                    Aktif

                </span>


                <p>
                    PTA saat ini aktif dan dapat menerima
                    pemberitahuan Mutu D.
                </p>


            @else

                <span class="pta-status inactive">

                    <span class="status-dot"></span>

                    Nonaktif

                </span>


                <p>
                    PTA saat ini tidak menerima pemberitahuan
                    otomatis.
                </p>

            @endif

        </div>



        <div class="pta-info-note">

            <i class="bi bi-clock-history"></i>

            <span>

                Terakhir diperbarui:

                <strong>
                    {{ optional($pta->updated_at)->format('d M Y, H:i') }}
                </strong>

            </span>

        </div>

    </div>

</div>

@endsection