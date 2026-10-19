@props([
    'notification' => null,
    'status' => null,
])

@php

    $currentStatus = $status;

    if (!$currentStatus && $notification) {
        $currentStatus =
            $notification->status
            ?? $notification->notification_status
            ?? 'pending';
    }

    $currentStatus = strtolower($currentStatus ?? 'pending');

    $configs = [

        'pending' => [
            'class' => 'notification-pending',
            'icon' => '⏳',
            'title' => 'Menunggu',
            'description' => 'Pemberitahuan belum dikirim.',
        ],

        'processing' => [
            'class' => 'notification-processing',
            'icon' => '↻',
            'title' => 'Mengirim',
            'description' => 'Sedang mengirim melalui WhatsApp.',
        ],

        'sent' => [
            'class' => 'notification-sent',
            'icon' => '✓',
            'title' => 'Terkirim',
            'description' => 'Pemberitahuan berhasil dikirim.',
        ],

        'success' => [
            'class' => 'notification-sent',
            'icon' => '✓',
            'title' => 'Terkirim',
            'description' => 'Pemberitahuan berhasil dikirim.',
        ],

        'failed' => [
            'class' => 'notification-failed',
            'icon' => '!',
            'title' => 'Gagal',
            'description' => 'Pemberitahuan gagal dikirim.',
        ],

    ];

    $config = $configs[$currentStatus] ?? $configs['pending'];

@endphp


<div class="notification-status {{ $config['class'] }}">

    <div class="notification-status-icon">
        {{ $config['icon'] }}
    </div>


    <div class="notification-status-content">

        <strong>
            {{ $config['title'] }}
        </strong>

        <span>
            {{ $config['description'] }}
        </span>

        @if($notification)

            @php
                $sentAt =
                    $notification->sent_at
                    ?? $notification->delivered_at
                    ?? null;
            @endphp

            @if($sentAt)

                <small>
                    {{ \Carbon\Carbon::parse($sentAt)->format('d/m/Y H:i') }}
                </small>

            @endif

        @endif

    </div>

</div>