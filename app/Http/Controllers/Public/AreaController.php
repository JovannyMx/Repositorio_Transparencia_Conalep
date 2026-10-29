<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Area;
use App\Models\Documento;
use App\Models\Seccion;

class AreaController extends Controller
{
    /**
     * Elimina automáticamente los documentos cuya fecha de vencimiento ya pasó.
     */
    private function limpiarVencidos(): void
    {
        Documento::whereNotNull('fecha_vencimiento')
            ->whereDate('fecha_vencimiento', '<', now())
            ->get()
            ->each->delete();
    }

    public function index()
    {
        $this->limpiarVencidos();

        $areas = Area::where('activo', true)
            ->orderBy('orden')
            ->withCount('documentos')
            ->get();

        return view('public.areas.index', compact('areas'));
    }

    public function show(Area $area)
    {
        // 1. Validar que el Área esté activa
        abort_unless($area->activo, 404);

        // 2. Limpiar documentos vencidos
        $this->limpiarVencidos();

        // 3. Cargar las secciones RAÍZ (parent_id = null) que estén ACTIVAS y NO ELIMINADAS, 
        // junto con su árbol completo de subsecciones activas.
        $secciones = $area->secciones()
            ->whereNull('parent_id')
            ->where('activo', true)
            ->whereNull('deleted_at')
            ->with(['childrenRecursive' => function ($query) {
                $query->where('activo', true)->whereNull('deleted_at');
            }, 'documentos'])
            ->orderBy('orden')
            ->get();

        // 4. Función recursiva para recolectar únicamente los IDs de secciones 
        // cuyas secciones padre también estén totalmente activas de la raíz hacia abajo.
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

        // 5. Traer ÚNICAMENTE los documentos asignados a las secciones válidas
        // e incluir 'seccion.parentRecursive' para que Blade pueda validar ancestros sin peticiones extra SQL.
        $documentos = Documento::query()
            ->where('area_id', $area->id)
            ->whereIn('seccion_id', $idsSeccionesActivas)
            ->with(['seccion.parentRecursive'])
            ->orderBy('orden')
            ->get();

        return view('public.areas.show', compact('area', 'secciones', 'documentos'));
    }
}