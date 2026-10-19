@props([
    'status' => 'pending',
    'label' => null,
])

@php

    $statusConfig = [
        'pending' => [
            'class' => 'status-warning',
            'icon' => '⏳',
            'label' => 'Menunggu',
        ],

        'waiting' => [
            'class' => 'status-warning',
            'icon' => '⏳',
            'label' => 'Menunggu',
        ],

        'process' => [
            'class' => 'status-info',
            'icon' => '↻',
            'label' => 'Diproses',
        ],

        'processing' => [
            'class' => 'status-info',
            'icon' => '↻',
            'label' => 'Diproses',
        ],

        'sent' => [
            'class' => 'status-success',
            'icon' => '✓',
            'label' => 'Terkirim',
        ],

        'success' => [
            'class' => 'status-success',
            'icon' => '✓',
            'label' => 'Berhasil',
        ],

        'failed' => [
            'class' => 'status-danger',
            'icon' => '!',
            'label' => 'Gagal',
        ],

        'error' => [
            'class' => 'status-danger',
            'icon' => '!',
            'label' => 'Error',
        ],

        'active' => [
            'class' => 'status-success',
            'icon' => '✓',
            'label' => 'Aktif',
        ],

        'inactive' => [
            'class' => 'status-neutral',
            'icon' => '−',
            'label' => 'Tidak Aktif',
        ],

        'mutu_d' => [
            'class' => 'status-danger',
            'icon' => '!',
            'label' => 'Mutu D',
        ],

        'completed' => [
            'class' => 'status-success',
            'icon' => '✓',
            'label' => 'Selesai',
        ],
    ];

    $config = $statusConfig[strtolower($status)] ?? [
        'class' => 'status-neutral',
        'icon' => '•',
        'label' => ucfirst($status),
    ];

@endphp

<span class="status-badge {{ $config['class'] }}">

    <span class="status-badge-icon">
        {{ $config['icon'] }}
    </span>

    <span>
        {{ $label ?? $config['label'] }}
    </span>

</span>