<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Area extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'nombre', 'slug', 'icono', 'descripcion_corta', 'orden', 'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function secciones(): HasMany
    {
        return $this->hasMany(Seccion::class)->whereNull('parent_id')->orderBy('orden');
    }

    public function documentos(): HasMany
    {
        return $this->hasMany(Documento::class)->orderBy('orden');
    }

    public function editores(): BelongsToMany
    {
        return $this->belongsToMany(\App\Models\User::class, 'user_areas');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * URL de la imagen de forma flexible para evitar que devuelva null.
     */
    public function getIconoUrlAttribute(): ?string
    {
        if (empty($this->icono)) {
            return null;
        }

        // Si ya es una URL completa
        if (str_starts_with($this->icono, 'http://') || str_starts_with($this->icono, 'https://')) {
            return $this->icono;
        }

        // Si ya incluye la ruta de storage
        if (str_starts_with($this->icono, 'storage/')) {
            return asset($this->icono);
        }

        // Para cualquier ruta relativa guardada en el disco public (ej. iconos/foto.png)
        return asset('storage/' . ltrim($this->icono, '/'));
    }

    /**
     * Trazo SVG guardado de la forma antigua.
     */
    public function getIconoSvgPathAttribute(): ?string
    {
        if (!empty($this->icono) && !str_contains($this->icono, '/') && !str_ends_with($this->icono, '.png') && !str_ends_with($this->icono, '.jpg') && !str_ends_with($this->icono, '.webp') && !str_ends_with($this->icono, '.svg')) {
            return $this->icono;
        }

        return null;
    }
}