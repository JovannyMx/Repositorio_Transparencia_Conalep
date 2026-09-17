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
        $documentos = $area->documentos()->with('seccion')->get();

        return view('admin.documentos.index', compact('area', 'documentos'));
    }

    public function create(Area $area)
    {
        $secciones = Seccion::where('area_id', $area->id)->orderBy('nombre')->get();

        return view('admin.documentos.create', compact('area', 'secciones'));
    }

    public function store(Request $request, Area $area)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'seccion_id' => 'nullable|exists:secciones,id',
            'orden' => 'nullable|integer|min:0',
            'fecha_publicacion' => 'nullable|date',
            'archivo' => 'required|file|mimes:pdf,doc,docx,xls,xlsx|max:51200', // 50 MB máx
        ]);

        $documento = Documento::create([
            'area_id' => $area->id,
            'seccion_id' => $validated['seccion_id'] ?? null,
            'nombre' => $validated['nombre'],
            'orden' => $validated['orden'] ?? 0,
            'fecha_publicacion' => $validated['fecha_publicacion'] ?? now(),
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
            'archivo' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:51200', // se puso para que sea de maximo de 50 megabytes la subida de los archivos por si las moscas
            
        ]);

        $documento->update([
            'seccion_id' => $validated['seccion_id'] ?? null,
            'nombre' => $validated['nombre'],
            'orden' => $validated['orden'] ?? 0,
            'fecha_publicacion' => $validated['fecha_publicacion'] ?? $documento->fecha_publicacion,
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