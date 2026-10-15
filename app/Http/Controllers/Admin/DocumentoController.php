<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\Documento;
use App\Models\Seccion;
use Illuminate\Http\Request;

class DocumentoController extends Controller
{
    public function index(Area $area)
    {
        $documentos = $area->documentos()->with('seccion')->orderBy('orden')->get();

        return view('admin.documentos.index', compact('area', 'documentos'));
    }

    public function create(Area $area)
    {
        $secciones = Seccion::where('area_id', $area->id)->orderBy('nombre')->get();
        
        // Calcular el siguiente número de orden (el máximo actual + 1)
        $nextOrden = $area->documentos()->max('orden') + 1;

        return view('admin.documentos.create', compact('area', 'secciones', 'nextOrden'));
    }

    public function store(Request $request, Area $area)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'seccion_id' => 'nullable|exists:secciones,id',
            'orden' => 'nullable|integer|min:0',
            'fecha_publicacion' => 'nullable|date',
            'fecha_vencimiento' => 'nullable|date|after_or_equal:today',
            'archivo' => 'required|file|mimes:pdf,doc,docx,xls,xlsx|max:51200', // 50 MB máx
        ]);

        $documento = Documento::create([
            'area_id' => $area->id,
            'seccion_id' => $validated['seccion_id'] ?? null,
            'nombre' => $validated['nombre'],
            'orden' => $validated['orden'] ?? 0,
            'fecha_publicacion' => $validated['fecha_publicacion'] ?? now(),
            'fecha_vencimiento' => $validated['fecha_vencimiento'] ?? null,
            'activo' => true,
            'extension' => $request->file('archivo')->getClientOriginalExtension(),
            'tamano' => $request->file('archivo')->getSize(),
        ]);

        $documento->addMediaFromRequest('archivo')->toMediaCollection('archivo');

        return redirect()->route('admin.areas.documentos.index', $area)
            ->with('success', 'Documento cargado correctamente.');
    }

    public function edit(Area $area, Documento $documento)
    {
        $secciones = Seccion::where('area_id', $area->id)->orderBy('nombre')->get();

        return view('admin.documentos.edit', compact('area', 'documento', 'secciones'));
    }

    public function update(Request $request, Area $area, Documento $documento)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'seccion_id' => 'nullable|exists:secciones,id',
            'orden' => 'nullable|integer|min:0',
            'fecha_publicacion' => 'nullable|date',
            'fecha_vencimiento' => 'nullable|date|after_or_equal:today',
            'archivo' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:51200',
        ]);

        $documento->update([
            'seccion_id' => $validated['seccion_id'] ?? null,
            'nombre' => $validated['nombre'],
            'orden' => $validated['orden'] ?? 0,
            'fecha_publicacion' => $validated['fecha_publicacion'] ?? $documento->fecha_publicacion,
            'fecha_vencimiento' => $validated['fecha_vencimiento'] ?? null,
            'fecha_actualizacion' => now(),
        ]);

        // Si subieron un archivo nuevo, reemplaza el anterior
        if ($request->hasFile('archivo')) {
            $documento->update([
                'extension' => $request->file('archivo')->getClientOriginalExtension(),
                'tamano' => $request->file('archivo')->getSize(),
            ]);
            $documento->addMediaFromRequest('archivo')->toMediaCollection('archivo');
        }

        return redirect()->route('admin.areas.documentos.index', $area)
            ->with('success', 'Documento actualizado correctamente.');
    }

    public function destroy(Area $area, Documento $documento)
    {
        $documento->delete();

        return redirect()->route('admin.areas.documentos.index', $area)
            ->with('success', 'Documento eliminado correctamente.');
    }
}