@props([
    'mutuD' => null,
    'farmerName' => null,
    'farmerCode' => null,
    'memoNumber' => null,
    'memoDate' => null,
    'ptaName' => null,
    'location' => null,
    'status' => 'mutu_d',
])

<div class="farmer-card">

    <div class="farmer-card-header">

        <div class="farmer-avatar">
            {{ strtoupper(substr($farmerName ?? 'P', 0, 1)) }}
        </div>

        <div class="farmer-main-info">

            <h3>
                {{ $farmerName ?? '-' }}
            </h3>

            @if($farmerCode)
                <span class="farmer-code">
                    ID Petani: {{ $farmerCode }}
                </span>
            @endif

        </div>

        <x-status-badge :status="$status" />

    </div>


    <div class="farmer-card-divider"></div>


    <div class="farmer-card-body">

        <div class="farmer-info-item">

            <span class="farmer-info-label">
                Nomor Memo
            </span>

            <span class="farmer-info-value">
                {{ $memoNumber ?? '-' }}
            </span>

        </div>


        <div class="farmer-info-item">

            <span class="farmer-info-label">
                Tanggal Memo
            </span>

            <span class="farmer-info-value">

                @if($memoDate)
                    {{ \Carbon\Carbon::parse($memoDate)->format('d F Y') }}
                @else
                    -
                @endif

            </span>

        </div>


        <div class="farmer-info-item">

            <span class="farmer-info-label">
                PTA / Wilayah
            </span>

            <span class="farmer-info-value">
                {{ $ptaName ?? '-' }}
            </span>

        </div>


        <div class="farmer-info-item">

            <span class="farmer-info-label">
                Lokasi
            </span>

            <span class="farmer-info-value">
                {{ $location ?? '-' }}
            </span>

        </div>

    </div>


    @if($mutuD)

        <div class="farmer-card-footer">

            <a
                href="{{ route('mutu-d.detail', $mutuD->id) }}"
                class="btn btn-sm btn-outline-green"
            >
                Lihat Detail
            </a>

        </div>

    @endif

</div>