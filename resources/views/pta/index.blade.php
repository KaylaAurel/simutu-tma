@extends('layouts.app')

@section('title', 'Data PTA')
@section('page-title', 'Data PTA')

@section('content')

<div class="pta-page-shell">

    {{-- =====================================================
         PAGE HEADER
    ====================================================== --}}
    <div class="page-header pta-page-header">

        <div class="pta-header-content">

            <div class="breadcrumb">
                <span>Data & Proses</span>
                <i class="bi bi-chevron-right"></i>
                <span>PTA</span>
            </div>

            <div class="pta-title-row">

                <div class="pta-title-icon">
                    <i class="bi bi-person-badge-fill"></i>
                </div>

                <div>
                    <h2>Data PTA</h2>

                    <p>
                        Kelola daftar PTA sebagai penerima pemberitahuan
                        Mutu D melalui WhatsApp.
                    </p>
                </div>

            </div>

        </div>

        <a href="{{ route('pta.create') }}"
           class="primary-button pta-add-button">

            <i class="bi bi-person-plus-fill"></i>

            <span>Tambah PTA</span>

        </a>

    </div>


    {{-- =====================================================
         SUMMARY
    ====================================================== --}}
    <div class="pta-summary-grid">

        {{-- TOTAL --}}
        <div class="pta-summary-card total-card">

            <div class="pta-summary-icon total">
                <i class="bi bi-people-fill"></i>
            </div>

            <div class="pta-summary-content">

                <span>Total PTA</span>

                <strong>
                    {{ $ptas->total() ?? $ptas->count() }}
                </strong>

                <small>
                    PTA terdaftar dalam sistem
                </small>

            </div>

            <div class="pta-summary-decoration">
                <i class="bi bi-people"></i>
            </div>

        </div>


        {{-- ACTIVE --}}
        <div class="pta-summary-card active-card">

            <div class="pta-summary-icon active">
                <i class="bi bi-person-check-fill"></i>
            </div>

            <div class="pta-summary-content">

                <span>PTA Aktif</span>

                <strong>
                    {{ \App\Models\PTA::where('status', 'aktif')->count() }}
                </strong>

                <small>
                    Siap menerima pemberitahuan
                </small>

            </div>

            <div class="pta-summary-decoration">
                <i class="bi bi-person-check"></i>
            </div>

        </div>


        {{-- WHATSAPP --}}
        <div class="pta-summary-card whatsapp-card">

            <div class="pta-summary-icon whatsapp">
                <i class="bi bi-whatsapp"></i>
            </div>

            <div class="pta-summary-content">

                <span>Integrasi WhatsApp</span>

                <strong class="integration-active">
                    Aktif
                </strong>

                <small>
                    Kanal notifikasi tersedia
                </small>

            </div>

            <div class="pta-summary-decoration">
                <i class="bi bi-chat-dots"></i>
            </div>

        </div>

    </div>


    {{-- =====================================================
         TABLE CARD
    ====================================================== --}}
    <div class="simutu-card pta-table-card">

        {{-- CARD HEADER --}}
        <div class="card-header pta-card-header">

            <div class="pta-card-title">

                <div class="pta-card-title-icon">
                    <i class="bi bi-person-lines-fill"></i>
                </div>

                <div>

                    <span class="card-eyebrow">
                        PENERIMA NOTIFIKASI
                    </span>

                    <h3>
                        Daftar PTA
                    </h3>

                    <p class="card-description">
                        Daftar Petugas Tebang Angkut yang terdaftar sebagai
                        penerima pemberitahuan Mutu D.
                    </p>

                </div>

            </div>

            <div class="pta-header-total">

                <i class="bi bi-person-badge-fill"></i>

                <span>
                    {{ $ptas->total() ?? $ptas->count() }} PTA
                </span>

            </div>

        </div>


        {{-- TABLE --}}
        <div class="table-responsive">

            <table class="simutu-table pta-table">

                <thead>

                    <tr>
                        <th>PTA</th>
                        <th>Kode</th>
                        <th>WhatsApp</th>
                        <th>Status</th>
                        <th>Terakhir Diubah</th>
                        <th class="text-center">Aksi</th>
                    </tr>

                </thead>


                <tbody>

                    @forelse($ptas as $pta)

                        <tr>

                            {{-- PTA --}}
                            <td>

                                <div class="pta-user">

                                    <div class="pta-avatar">

                                        <i class="bi bi-person-badge-fill"></i>

                                    </div>

                                    <div class="pta-user-info">

                                        <strong>
                                            {{ $pta->nama_pta }}
                                        </strong>

                                        <span>
                                            Petugas Tebang Angkut
                                        </span>

                                    </div>

                                </div>

                            </td>


                            {{-- KODE --}}
                            <td>

                                <span class="pta-code">

                                    <i class="bi bi-hash"></i>

                                    {{ $pta->kode_pta }}

                                </span>

                            </td>


                            {{-- WHATSAPP --}}
                            <td>

                                <div class="pta-whatsapp">

                                    <div class="pta-whatsapp-icon">

                                        <i class="bi bi-whatsapp"></i>

                                    </div>

                                    <div class="pta-whatsapp-info">

                                        <strong>
                                            {{ $pta->no_whatsapp }}
                                        </strong>

                                        <span>
                                            Nomor pemberitahuan
                                        </span>

                                    </div>

                                </div>

                            </td>


                            {{-- STATUS --}}
                            <td>

                                @if($pta->status === 'aktif')

                                    <span class="pta-status active">

                                        <span class="status-dot"></span>

                                        Aktif

                                    </span>

                                @else

                                    <span class="pta-status inactive">

                                        <span class="status-dot"></span>

                                        Nonaktif

                                    </span>

                                @endif

                            </td>


                            {{-- UPDATED --}}
                            <td>

                                <div class="pta-updated">

                                    <strong>
                                        {{ optional($pta->updated_at)->format('d M Y') }}
                                    </strong>

                                    <span>
                                        {{ optional($pta->updated_at)->format('H:i') }} WIB
                                    </span>

                                </div>

                            </td>


                            {{-- ACTION --}}
                            <td>

                                <div class="pta-actions">

                                    <a href="{{ route('pta.edit', $pta->id) }}"
                                       class="pta-action edit"
                                       title="Edit PTA">

                                        <i class="bi bi-pencil-square"></i>

                                    </a>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="6">

                                <div class="pta-empty-state">

                                    <div class="pta-empty-icon">

                                        <i class="bi bi-person-x-fill"></i>

                                    </div>

                                    <h4>
                                        Belum ada data PTA
                                    </h4>

                                    <p>
                                        Belum ada PTA yang terdaftar sebagai
                                        penerima pemberitahuan Mutu D.
                                    </p>

                                    <a href="{{ route('pta.create') }}"
                                       class="primary-button">

                                        <i class="bi bi-person-plus-fill"></i>

                                        Tambah PTA

                                    </a>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- PAGINATION --}}
        @if(method_exists($ptas, 'links'))

            <div class="pagination-wrapper">
                {{ $ptas->links() }}
            </div>

        @endif

    </div>

</div>

@endsection