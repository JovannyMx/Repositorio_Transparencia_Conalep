<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Area;

class AreaController extends Controller
{
    public function index()
    {
        $areas = Area::where('activo', true)
        ->orderBy('orden')
        ->withCount('documentos') // Contar documentos relacionados
        ->get();
        return view('public.areas.index', compact('areas'));
    }    

    public function show(Area $area)
    {
        abort_unless($area->activo, 404);

        $secciones = $area->secciones()->with('childrenRecursive.documentos', 'documentos')->get();
        $documentosSueltos = $area->documentos()->whereNull('seccion_id')->orderBy('orden')->get();

        return view('public.areas.show', compact('area', 'secciones', 'documentosSueltos'));
    }

}
