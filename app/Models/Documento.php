<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Documento extends Model implements HasMedia
{
    use InteractsWithMedia, Prunable;

    protected $fillable = [
        'area_id', 'seccion_id', 'nombre', 'archivo_path', 'extension',
        'tamano', 'liga_publica', 'orden', 'fecha_publicacion',
        'fecha_actualizacion', 'activo', 'fecha_vencimiento',
    ];

    protected $casts = [
        'activo' => 'boolean',
        'fecha_publicacion' => 'date',
        'fecha_actualizacion' => 'date',
        'fecha_vencimiento' => 'date',
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

    /**
     * Determina la consulta para eliminar automáticamente los documentos vencidos.
     * Se ejecuta con: php artisan model:prune
     */
    public function prunable(): Builder
    {
        return static::whereNotNull('fecha_vencimiento')
                     ->whereDate('fecha_vencimiento', '<', now());
    }

    /**
     * Limpia los archivos físicos justo antes de que el registro sea eliminado por Prunable.
     */
    protected function pruning(): void
    {
        // Si guardas el archivo mediante la columna 'archivo_path' en el disco 'public'
        if ($this->archivo_path && Storage::disk('public')->exists($this->archivo_path)) {
            Storage::disk('public')->delete($this->archivo_path);
        }

        // Nota: Los archivos en la colección 'archivo' de Spatie MediaLibrary 
        // se eliminan automáticamente al dispararse el evento delete del modelo.
    }

    /**
     * Scope para consultar únicamente documentos que NO han vencido.
     * Uso en controlador: Documento::vigentes()->get();
     */
    public function scopeVigentes(Builder $query): Builder
    {
        return $query->where(function (Builder $q) {
            $q->whereNull('fecha_vencimiento')
              ->orWhereDate('fecha_vencimiento', '>=', now());
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