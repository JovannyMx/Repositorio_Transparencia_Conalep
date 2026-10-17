<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Area extends Model
{
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
     * URL de la imagen subida desde el formulario, o null si no hay.
     * Uso en vistas: $area->icono_url
     */
    public function getIconoUrlAttribute(): ?string
    {
        if ($this->icono
            && str_starts_with($this->icono, 'iconos/')
            && Storage::disk('public')->exists($this->icono)) {
            return asset('storage/' . $this->icono);
        }

        return null;
    }

    /**
     * Trazo SVG guardado de la forma antigua (texto del atributo "d"), o null.
     * Uso en vistas: $area->icono_svg_path
     */
    public function getIconoSvgPathAttribute(): ?string
    {
        if ($this->icono && ! str_starts_with($this->icono, 'iconos/')) {
            return $this->icono;
        }

        return null;
    }
}