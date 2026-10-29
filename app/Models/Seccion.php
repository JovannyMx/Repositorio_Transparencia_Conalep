<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Seccion extends Model
{
    use SoftDeletes;

    protected $table = 'secciones';

    protected $casts = [
        'activo' => 'boolean',
        'publico' => 'boolean',
    ];
    
    protected $fillable = [
        'area_id', 'parent_id', 'nombre', 'tipo', 'orden', 'modo_vista', 'activo', 'publico',
    ];

    /**
     * Accessor para asegurar que 'activo' responda false si la sección 
     * fue marcada como 'no visible al público' o deshabilitada.
     */
    public function getActivoAttribute($value): bool
    {
        // Si existe la columna 'publico' en la BD y está en false, la sección se considera inactiva
        if (array_key_exists('publico', $this->attributes) && !is_null($this->attributes['publico'])) {
            if (!(bool) $this->attributes['publico']) {
                return false;
            }
        }

        return (bool) $value;
    }

    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Seccion::class, 'parent_id');
    }

    // Recorrido recursivo HACIA ARRIBA (para validar que los padres estén activos)
    public function parentRecursive(): BelongsTo
    {
        return $this->parent()->with('parentRecursive');
    }

    // Subsecciones directas (un nivel hacia abajo)
    public function children(): HasMany
    {
        return $this->hasMany(Seccion::class, 'parent_id')->orderBy('orden');
    }

    // Carga recursiva de todo el árbol de descendientes
    public function childrenRecursive(): HasMany
    {
        return $this->children()->with('childrenRecursive');
    }

    public function documentos(): HasMany
    {
        return $this->hasMany(Documento::class)->orderBy('orden');
    }
}