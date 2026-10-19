<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MemoProdis extends Model
{
    use HasFactory;

    protected $table = 'memo_prodis';

    protected $fillable = [
        'import_prodis_id',
        'nomor_memo',
        'tanggal_memo',
        'keterangan',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_memo' =>
                'date',
        ];
    }

    public function import(): BelongsTo
    {
        return $this->belongsTo(
            ImportProdis::class,
            'import_prodis_id'
        );
    }

    public function mutuDs(): HasMany
    {
        return $this->hasMany(
            MutuD::class,
            'memo_prodis_id'
        );
    }

    public function scopeOnDate(
        $query,
        $date
    ) {

        return $query->whereDate(
            'tanggal_memo',
            $date
        );
    }
}