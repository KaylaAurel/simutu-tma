@props([
    'title' => 'Total Data',
    'value' => 0,
    'icon' => '📊',
    'color' => 'green',
    'description' => null,
    'link' => null,
])

@php
    $colorClasses = [
        'green' => [
            'bg' => 'stat-card-green',
            'icon' => 'stat-icon-green',
        ],
        'yellow' => [
            'bg' => 'stat-card-yellow',
            'icon' => 'stat-icon-yellow',
        ],
        'blue' => [
            'bg' => 'stat-card-blue',
            'icon' => 'stat-icon-blue',
        ],
        'red' => [
            'bg' => 'stat-card-red',
            'icon' => 'stat-icon-red',
        ],
    ];

    $selectedColor = $colorClasses[$color] ?? $colorClasses['green'];
@endphp

<div class="statistic-card {{ $selectedColor['bg'] }}">

    <div class="statistic-card-content">

        <div class="statistic-card-text">
            <span class="statistic-card-title">
                {{ $title }}
            </span>

            <span class="statistic-card-value">
                {{ $value }}
            </span>

            @if($description)
                <span class="statistic-card-description">
                    {{ $description }}
                </span>
            @endif
        </div>

        <div class="statistic-card-icon {{ $selectedColor['icon'] }}">
            {{ $icon }}
        </div>

    </div>

    @if($link)
        <a href="{{ $link }}" class="statistic-card-link">
            Lihat detail
            <span>→</span>
        </a>
    @endif

</div>