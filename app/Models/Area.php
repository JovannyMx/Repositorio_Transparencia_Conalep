<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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
}