<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PemberitahuanDetail extends Model
{
    use HasFactory;

    protected $table = 'pemberitahuan_details';

    protected $fillable = [
        'pemberitahuan_id',
        'pta_id',
        'mutu_d_id',

        'status',
        'sent_at',
        'response',
        'message_id',
        'error_message',
    ];

    protected function casts(): array
    {
        return [
            'sent_at' => 'datetime',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    /**
     * Pemberitahuan induk.
     */
    public function pemberitahuan(): BelongsTo
    {
        return $this->belongsTo(
            Pemberitahuan::class,
            'pemberitahuan_id'
        );
    }

    /**
     * PTA penerima.
     */
    public function pta(): BelongsTo
    {
        return $this->belongsTo(
            PTA::class,
            'pta_id'
        );
    }

    /**
     * Data Mutu D yang diberitahukan.
     */
    public function mutuD(): BelongsTo
    {
        return $this->belongsTo(
            MutuD::class,
            'mutu_d_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /**
     * Detail yang masih pending.
     */
    public function scopePending(
        Builder $query
    ): Builder {
        return $query->where(
            'status',
            'pending'
        );
    }

    /**
     * Detail yang berhasil dikirim.
     */
    public function scopeSent(
        Builder $query
    ): Builder {
        return $query->where(
            'status',
            'sent'
        );
    }

    /**
     * Detail yang sudah delivered.
     */
    public function scopeDelivered(
        Builder $query
    ): Builder {
        return $query->where(
            'status',
            'delivered'
        );
    }

    /**
     * Detail yang gagal.
     */
    public function scopeFailed(
        Builder $query
    ): Builder {
        return $query->where(
            'status',
            'failed'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | STATUS HELPERS
    |--------------------------------------------------------------------------
    */

    /**
     * Apakah masih pending?
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Apakah berhasil dikirim?
     */
    public function isSent(): bool
    {
        return in_array(
            $this->status,
            [
                'sent',
                'delivered',
            ],
            true
        );
    }

    /**
     * Apakah sudah delivered?
     */
    public function isDelivered(): bool
    {
        return $this->status === 'delivered';
    }

    /**
     * Apakah gagal?
     */
    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }
}