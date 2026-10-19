<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FotoMutuD extends Model
{
    use HasFactory;

    protected $table = 'foto_mutu_d';

    protected $fillable = [
        'mutu_d_id',
        'file_path',
        'caption',
        'uploaded_by',
    ];

    /**
     * Mutu D pemilik foto.
     */
    public function mutuD(): BelongsTo
    {
        return $this->belongsTo(
            MutuD::class,
            'mutu_d_id'
        );
    }

    /**
     * User yang mengupload foto.
     */
    public function uploader(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'uploaded_by'
        );
    }

    /**
     * URL foto untuk frontend.
     */
    public function getUrlAttribute(): string
    {
        return asset(
            'storage/' . $this->file_path
        );
    }
}