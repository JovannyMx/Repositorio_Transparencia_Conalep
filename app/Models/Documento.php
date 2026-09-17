<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Documento extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'area_id', 'seccion_id', 'nombre', 'archivo_path', 'extension',
        'tamano', 'liga_publica', 'orden', 'fecha_publicacion',
        'fecha_actualizacion', 'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
        'fecha_publicacion' => 'date',
        'fecha_actualizacion' => 'date',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($documento) {
            if (empty($documento->liga_publica)) {
                $documento->liga_publica = Str::uuid()->toString();
            }
        });
    }

    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class);
    }

    public function seccion(): BelongsTo
    {
        return $this->belongsTo(Seccion::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('archivo')->singleFile();
    }
}