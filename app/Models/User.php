<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Field yang boleh diisi secara mass assignment.
     */
    protected $fillable = [
        'name',
        'username',
        'password',
        'role',
        'is_active',
    ];

    /**
     * Field yang disembunyikan ketika model
     * diubah menjadi array / JSON.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casting tipe data.
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Import Prodis yang dilakukan user.
     */
    public function imports(): HasMany
    {
        return $this->hasMany(
            ImportProdis::class,
            'imported_by'
        );
    }

    /**
     * Foto Mutu D yang diupload user.
     */
    public function uploadedPhotos(): HasMany
    {
        return $this->hasMany(
            FotoMutuD::class,
            'uploaded_by'
        );
    }

    /**
     * Pemberitahuan yang dibuat user.
     */
    public function pemberitahuan(): HasMany
    {
        return $this->hasMany(
            Pemberitahuan::class,
            'created_by'
        );
    }

    /**
     * Helper untuk mengecek role.
     */
    public function hasRole(string|array $roles): bool
    {
        $roles = is_array($roles)
            ? $roles
            : [$roles];

        return in_array(
            $this->role,
            $roles,
            true
        );
    }

    /**
     * Cek apakah user admin.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Cek apakah user Asman TMA.
     */
    public function isAsmanTma(): bool
    {
        return $this->role === 'asman_tma';
    }

    /**
     * Cek apakah user Asman Wilayah.
     */
    public function isAsmanWilayah(): bool
    {
        return $this->role === 'asman_wilayah';
    }

    /**
     * Cek apakah user TU Hasil.
     */
    public function isTuHasil(): bool
    {
        return $this->role === 'tu_hasil';
    }
}