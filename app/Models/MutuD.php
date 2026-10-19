<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class MutuD extends Model
{
    use HasFactory;

    protected $table = 'mutu_d';

    protected $fillable = [

        'memo_prodis_id',
        'pta_id',

        'no_spta',
        'no_kontrak',
        'kode_afdeling',
        'nama_asman',
        'no_petani',
        'deskripsi_blok',
        'nama_petani',

        'nopol',
        'kualitas',

        'tanggal_selektor',
        'jam_selektor',

        'tanggal_timbang',
        'jam_timbang',

        'tanggal_meja_tebu',
        'jam_meja_tebu',

        'durasi_skorsing',
        'tanggal_skorsing_mulai',
        'tanggal_skorsing_selesai',

        'status_pemberitahuan',
    ];

    protected $casts = [
    'tanggal_selektor' => 'date',
    'tanggal_timbang' => 'date',
    'tanggal_meja_tebu' => 'date',
    'tanggal_skorsing_mulai' => 'date',
    'tanggal_skorsing_selesai' => 'date',
    'durasi_skorsing' => 'integer',
];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    public function memoProdis(): BelongsTo
    {
        return $this->belongsTo(
            MemoProdis::class,
            'memo_prodis_id'
        );
    }

    public function pta(): BelongsTo
    {
        return $this->belongsTo(
            PTA::class,
            'pta_id'
        );
    }
public function syncPtaFromNama(): bool
{
    if ($this->pta_id) {
        return true;
    }

    if (!$this->nama_petani) {
        return false;
    }

    $normalize = function ($value) {
        return preg_replace(
            '/\s+/',
            ' ',
            strtoupper(trim($value))
        );
    };

    $namaPetani = $normalize($this->nama_petani);

    $pta = PTA::query()
        ->get()
        ->first(function ($pta) use ($namaPetani, $normalize) {

            return $normalize($pta->nama_pta) === $namaPetani;
        });

    if (!$pta) {
        return false;
    }

    $this->pta_id = $pta->id;
    $this->save();

    return true;
}
    public function fotoMutuD(): HasMany
    {
        return $this->hasMany(
            FotoMutuD::class,
            'mutu_d_id'
        )->latest();
    }

    public function photos(): HasMany
    {
        return $this->fotoMutuD();
    }

    public function pemberitahuanDetails(): HasMany
    {
        return $this->hasMany(
            PemberitahuanDetail::class,
            'mutu_d_id'
        );
    }

    public function pemberitahuan()
    {
        return $this->belongsToMany(
            Pemberitahuan::class,
            'pemberitahuan_details',
            'mutu_d_id',
            'pemberitahuan_id'
        )->withTimestamps();
    }

    public function latestPemberitahuan(): HasOne
    {
        return $this->hasOne(
            PemberitahuanDetail::class,
            'mutu_d_id'
        )->latestOfMany();
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    public function scopeMutuD(
        Builder $query
    ): Builder {

        return $query->where(
            'kualitas',
            'D'
        );
    }

    public function scopeMemoDate(
        Builder $query,
        $date
    ): Builder {

        return $query->whereHas(
            'memoProdis',
            function (Builder $query) use ($date) {

                $query->whereDate(
                    'tanggal_memo',
                    $date
                );
            }
        );
    }

    public function scopeMejaDate(
        Builder $query,
        $date
    ): Builder {

        return $query->whereDate(
            'tanggal_meja_tebu',
            $date
        );
    }

    public function scopeSkorsingAktif(
        Builder $query
    ): Builder {

        return $query
            ->whereNotNull(
                'tanggal_skorsing_mulai'
            )
            ->whereNotNull(
                'tanggal_skorsing_selesai'
            )
            ->whereDate(
                'tanggal_skorsing_mulai',
                '<=',
                today()
            )
            ->whereDate(
                'tanggal_skorsing_selesai',
                '>=',
                today()
            );
    }

    /*
    |--------------------------------------------------------------------------
    | HELPERS
    |--------------------------------------------------------------------------
    */

    public function isSkorsingAktif(): bool
    {
        if (
            !$this->tanggal_skorsing_mulai ||
            !$this->tanggal_skorsing_selesai
        ) {
            return false;
        }

        return
            $this->tanggal_skorsing_mulai
                ->lessThanOrEqualTo(today())
            &&
            $this->tanggal_skorsing_selesai
                ->greaterThanOrEqualTo(today());
    }

    public function sudahDiberitahukan(): bool
    {
        return $this
            ->pemberitahuanDetails()
            ->where(
                'status',
                'sent'
            )
            ->exists();
    }

    public function getStatusPemberitahuanLabelAttribute(): string
    {
        return match (
            $this->status_pemberitahuan
        ) {

            'sudah_diberitahukan' =>
                'Sudah Diberitahukan',

            'diproses' =>
                'Diproses',

            'gagal' =>
                'Gagal',

            default =>
                'Belum Diberitahukan',
        };
    }

    public function getStatusSkorsingAttribute(): string
    {
        if (
            $this->isSkorsingAktif()
        ) {

            return 'aktif';
        }

        if (
            $this->tanggal_skorsing_selesai &&
            $this->tanggal_skorsing_selesai
                ->lessThan(today())
        ) {

            return 'selesai';
        }

        return 'belum';
    }
}