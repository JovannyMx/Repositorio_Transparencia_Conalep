<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Seccion extends Model
{

    protected $table = 'secciones';
    protected $fillable = [
        'area_id', 'parent_id', 'nombre', 'tipo', 'orden',
    ];

    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Seccion::class, 'parent_id');
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