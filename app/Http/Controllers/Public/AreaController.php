<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Area;
use App\Models\Documento;

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
            ->withCount('documentos') // Contar documentos relacionados vigentes
            ->get();

        return view('public.areas.index', compact('areas'));
    }    

    public function show(Area $area)
    {
        abort_unless($area->activo, 404);

        $this->limpiarVencidos();

        $secciones = $area->secciones()->with('childrenRecursive.documentos', 'documentos')->get();
        $documentosSueltos = $area->documentos()->whereNull('seccion_id')->orderBy('orden')->get();

        return view('public.areas.show', compact('area', 'secciones', 'documentosSueltos'));
    }
}