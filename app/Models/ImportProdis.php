<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ImportProdis extends Model
{
    use HasFactory;

    protected $table = 'import_prodis';

    protected $fillable = [
        'nama_file',
        'path_file',
        'tanggal_import',
        'jumlah_data',
        'jumlah_memo',
        'status',
        'imported_by',
    ];

    protected $casts = [
        'tanggal_import' => 'date',
        'jumlah_data' => 'integer',
        'jumlah_memo' => 'integer',
    ];

    /**
     * User yang melakukan import.
     */
    public function importedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'imported_by'
        );
    }

    /**
     * Alias jika ada kode lama menggunakan importer().
     */
    public function importer(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'imported_by'
        );
    }

    /**
     * Memo dari import ini.
     */
    public function memos(): HasMany
    {
        return $this->hasMany(
            MemoProdis::class,
            'import_prodis_id'
        );
    }

    public function scopeSuccess($query)
    {
        return $query->where(
            'status',
            'success'
        );
    }

    public function scopeFailed($query)
    {
        return $query->where(
            'status',
            'failed'
        );
    }
}