<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PTA extends Model
{
    use HasFactory;

    protected $table = 'ptas';

    protected $fillable = [
        'kode_pta',
        'nama_pta',
        'no_whatsapp',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'created_at' =>
                'datetime',

            'updated_at' =>
                'datetime',
        ];
    }

    public function pemberitahuanDetails(): HasMany
    {
        return $this->hasMany(
            PemberitahuanDetail::class,
            'pta_id'
        );
    }

    public function pemberitahuan()
    {
        return $this->belongsToMany(
            Pemberitahuan::class,
            'pemberitahuan_details',
            'pta_id',
            'pemberitahuan_id'
        )->withPivot([
            'mutu_d_id',
            'status',
            'sent_at',
            'response',
            'message_id',
            'error_message',
        ]);
    }

    public function scopeActive($query)
    {
        return $query->where(
            'status',
            'aktif'
        );
    }
}