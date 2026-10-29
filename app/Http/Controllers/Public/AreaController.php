<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Area;
use App\Models\Documento;
use App\Models\Seccion;
use Carbon\Carbon;

class AreaController extends Controller
{
    public function index()
    {
        $areas = Area::where('activo', true)
            ->orderBy('orden')
            ->withCount(['documentos' => function ($query) {
                // Solo cuenta para la vista pública los documentos activos y vigentes
                $query->where('activo', true)
                      ->where(function ($q) {
                          $q->whereNull('fecha_vencimiento')
                            ->orWhere('fecha_vencimiento', '>=', Carbon::today());
                      });
            }])
            ->get();

        return view('public.areas.index', compact('areas'));
    }

    public function show(Area $area)
    {
        // 1. Validar que el Área esté activa
        abort_unless($area->activo, 404);

        // 2. Cargar las secciones RAÍZ (parent_id = null) que estén ACTIVAS y NO ELIMINADAS,
        // junto con su árbol completo de subsecciones activas.
        $secciones = $area->secciones()
            ->whereNull('parent_id')
            ->where('activo', true)
            ->whereNull('deleted_at')
            ->with(['childrenRecursive' => function ($query) {
                $query->where('activo', true)->whereNull('deleted_at');
            }, 'documentos' => function ($query) {
                // Filtramos también los documentos en la relación directa de secciones
                $this->filtrarDocumentosVigentes($query);
            }])
            ->orderBy('orden')
            ->get();

        // 3. Función recursiva para recolectar únicamente los IDs de secciones activas
        $obtenerIdsValidos = function ($coleccion) use (&$obtenerIdsValidos) {
            $ids = [];
            foreach ($coleccion as $seccion) {
                if ((bool) $seccion->activo) {
                    $ids[] = $seccion->id;

                    if ($seccion->relationLoaded('childrenRecursive') && $seccion->childrenRecursive->isNotEmpty()) {
                        $ids = array_merge($ids, $obtenerIdsValidos($seccion->childrenRecursive));
                    }
                }
            }
            return $ids;
        };

        $idsSeccionesActivas = $obtenerIdsValidos($secciones);

        // 4. Traer los documentos del área que pertenezcan a secciones válidas O que no tengan sección,
        // asegurando que estén ACTIVOS y NO VENCIDOS (sin borrar nada de la BD).
        $documentos = Documento::query()
            ->where('area_id', $area->id)
            ->where(function ($query) use ($idsSeccionesActivas) {
                $query->whereIn('seccion_id', $idsSeccionesActivas)
                      ->orWhereNull('seccion_id'); // Incluye documentos directos del área
            });

        // Aplicar filtros de visibilidad pública
        $this->filtrarDocumentosVigentes($documentos);

        $documentos = $documentos->with(['seccion.parentRecursive'])
            ->orderBy('orden')
            ->get();

        return view('public.areas.show', compact('area', 'secciones', 'documentos'));
    }

    /**
     * Aplica el filtro público: solo documentos activos y cuya fecha de vencimiento no haya pasado.
     * Los eliminados (deleted_at) Laravel los descarta en automático vía SoftDeletes.
     */
    private function filtrarDocumentosVigentes($query): void
    {
        $query->where('activo', true)
              ->where(function ($q) {
                  $q->whereNull('fecha_vencimiento')
                    ->orWhere('fecha_vencimiento', '>=', Carbon::today());
              });
    }
}