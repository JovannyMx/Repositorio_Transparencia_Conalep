<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\Seccion;
use Illuminate\Http\Request;

class SeccionController extends Controller
{
    /**
     * Muestra el árbol de secciones de un área específica.
     */
    public function index(Area $area)
    {
        $secciones = $area->secciones()->with('childrenRecursive')->get();

        return view('admin.secciones.index', compact('area', 'secciones'));
    }

    public function create(Area $area)
    {
        // Todas las secciones del área, para elegir el "padre" en el select
        $todasLasSecciones = Seccion::where('area_id', $area->id)
            ->orderBy('nombre')
            ->get();

        return view('admin.secciones.create', compact('area', 'todasLasSecciones'));
    }

    public function store(Request $request, Area $area)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'tipo' => 'nullable|string|in:anio,periodo,categoria,libre',
            'parent_id' => 'nullable|exists:secciones,id',
            'orden' => 'nullable|integer|min:0',
        ]);

        $validated['area_id'] = $area->id;
        $validated['orden'] = $validated['orden'] ?? 0;

        Seccion::create($validated);

        return redirect()->route('admin.areas.secciones.index', $area)
            ->with('success', 'Sección creada correctamente.');
    }

    public function edit(Area $area, Seccion $seccion)
    {
        $todasLasSecciones = Seccion::where('area_id', $area->id)
            ->where('id', '!=', $seccion->id)
            ->orderBy('nombre')
            ->get();

        return view('admin.secciones.edit', compact('area', 'seccion', 'todasLasSecciones'));
    }

   public function update(Request $request, Area $area, Seccion $seccion)
{
    $validated = $request->validate([
        'nombre' => 'required|string|max:255',
        'tipo' => 'nullable|string|in:anio,periodo,categoria,libre',
        'parent_id' => 'nullable|exists:secciones,id',
        'orden' => 'nullable|integer|min:0',
    ]);

    // Evitar que una sección sea su propio padre, o padre de uno de sus descendientes
    if (!empty($validated['parent_id']) && $this->creariaCiclo($seccion, (int) $validated['parent_id'])) {
        return back()
            ->withInput()
            ->withErrors(['parent_id' => 'No puedes elegir esa sección como padre: crearía un ciclo (una sección no puede ser descendiente de sí misma).']);
    }

    $validated['orden'] = $validated['orden'] ?? 0;

    $seccion->update($validated);

    return redirect()->route('admin.areas.secciones.index', $area)
        ->with('success', 'Sección actualizada correctamente.');
}

/**
 * Verifica si asignar $nuevoParentId como padre de $seccion crearía un ciclo,
 * es decir, si $nuevoParentId es la propia sección o uno de sus descendientes.
 */
private function creariaCiclo(Seccion $seccion, int $nuevoParentId): bool
{
    if ($nuevoParentId === $seccion->id) {
        return true;
    }

    // Revisa todos los descendientes de $seccion; si el nuevo padre está entre ellos, es un ciclo
    $descendientes = $seccion->childrenRecursive()->get();

    foreach ($descendientes as $hijo) {
        if ($hijo->id === $nuevoParentId || $this->esDescendiente($hijo, $nuevoParentId)) {
            return true;
        }
    }

    return false;
}

private function esDescendiente(Seccion $nodo, int $id): bool
{
    foreach ($nodo->childrenRecursive as $hijo) {
        if ($hijo->id === $id || $this->esDescendiente($hijo, $id)) {
            return true;
        }
    }

    return false;
}

    public function destroy(Area $area, Seccion $seccion)
    {
        $seccion->delete();

        return redirect()->route('admin.areas.secciones.index', $area)
            ->with('success', 'Sección eliminada (junto con sus subsecciones y documentos).');
    }
}