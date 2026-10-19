<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pemberitahuan extends Model
{
    use HasFactory;

    protected $table = 'pemberitahuan';

    protected $fillable = [
        'nomor',
        'pta_id',
        'tanggal_pemberitahuan',
        'judul',
        'isi_pesan',
        'nama_file_pdf',
        'path_pdf',
        'status_pesan',
        'status_pdf',
        'waktu_kirim_pesan',
        'waktu_kirim_pdf',
        'created_by',
    ];

    protected $casts = [
        'tanggal_pemberitahuan' => 'date',
        'waktu_kirim_pesan' => 'datetime',
        'waktu_kirim_pdf' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    /**
     * PTA pada pemberitahuan.
     *
     * Catatan:
     * Saat ini tabel pemberitahuan masih memiliki pta_id.
     * Kita pertahankan relationship ini agar sesuai dengan database.
     */
    public function pta(): BelongsTo
    {
        return $this->belongsTo(
            PTA::class,
            'pta_id'
        );
    }

    /**
     * User pembuat pemberitahuan.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'created_by'
        );
    }

    /**
     * Detail pemberitahuan.
     */
    public function details(): HasMany
    {
        return $this->hasMany(
            PemberitahuanDetail::class,
            'pemberitahuan_id'
        );
    }

    /**
     * Semua PTA penerima.
     *
     * PTA sebenarnya berasal dari pemberitahuan_details.
     */
    public function ptas(): BelongsToMany
    {
        return $this->belongsToMany(
            PTA::class,
            'pemberitahuan_details',
            'pemberitahuan_id',
            'pta_id'
        )->withPivot([
            'mutu_d_id',
            'status',
            'sent_at',
            'response',
            'message_id',
            'error_message',
        ]);
    }

    /**
     * Semua Mutu D yang ada dalam pemberitahuan.
     */
    public function mutuDs(): BelongsToMany
    {
        return $this->belongsToMany(
            MutuD::class,
            'pemberitahuan_details',
            'pemberitahuan_id',
            'mutu_d_id'
        )->withPivot([
            'pta_id',
            'status',
            'sent_at',
            'response',
            'message_id',
            'error_message',
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /**
     * Pemberitahuan draft.
     */
    public function scopeDraft(
        Builder $query
    ): Builder {
        return $query->where(
            'status_pesan',
            'draft'
        );
    }

    /**
     * Pemberitahuan sedang diproses.
     */
    public function scopeProcessing(
        Builder $query
    ): Builder {
        return $query->where(
            'status_pesan',
            'processing'
        );
    }

    /**
     * Pemberitahuan sudah terkirim.
     */
    public function scopeSent(
        Builder $query
    ): Builder {
        return $query->where(
            'status_pesan',
            'terkirim'
        );
    }

    /**
     * Pemberitahuan gagal.
     */
    public function scopeFailed(
        Builder $query
    ): Builder {
        return $query->where(
            'status_pesan',
            'gagal'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | STATUS HELPERS
    |--------------------------------------------------------------------------
    */

    /**
     * Apakah masih draft?
     */
    public function isDraft(): bool
    {
        return $this->status_pesan === 'draft';
    }

    /**
     * Apakah sedang diproses?
     */
    public function isProcessing(): bool
    {
        return $this->status_pesan === 'processing';
    }

    /**
     * Apakah sudah terkirim?
     */
    public function isSent(): bool
    {
        return $this->status_pesan === 'terkirim';
    }

    /**
     * Apakah gagal?
     */
    public function isFailed(): bool
    {
        return $this->status_pesan === 'gagal';
    }

    /**
     * Apakah PDF sudah tersedia?
     */
    public function isPdfAvailable(): bool
    {
        return $this->status_pdf === 'tersedia';
    }

    /**
     * Apakah PDF gagal dibuat?
     */
    public function isPdfFailed(): bool
    {
        return $this->status_pdf === 'gagal';
    }
}