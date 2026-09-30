<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Area;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AreaController extends Controller
{
   public function index()
    {
    $user = auth()->user();

    if ($user->hasRole('admin')) {
        $areas = Area::orderBy('orden')->get();
    } else {
        $areas = $user->areas()->orderBy('orden')->get();
    }

    return view('admin.areas.index', compact('areas'));
    }

    public function create()
    {
        return view('admin.areas.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion_corta' => 'nullable|string|max:500',
            'orden' => 'nullable|integer|min:0',
            'activo' => 'nullable|boolean',
        ]);

        $validated['slug'] = Str::slug($validated['nombre']);
        $validated['orden'] = $validated['orden'] ?? 0;
        $validated['activo'] = $request->has('activo');

        Area::create($validated);

        return redirect()->route('admin.areas.index')
            ->with('success', 'Área creada correctamente.');
    }

    public function edit(Area $area)
    {
        return view('admin.areas.edit', compact('area'));
    }

    public function update(Request $request, Area $area)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion_corta' => 'nullable|string|max:500',
            'orden' => 'nullable|integer|min:0',
            'activo' => 'nullable|boolean',
        ]);

        $validated['slug'] = Str::slug($validated['nombre']);
        $validated['orden'] = $validated['orden'] ?? 0;
        $validated['activo'] = $request->has('activo');

        $area->update($validated);

        return redirect()->route('admin.areas.index')
            ->with('success', 'Área actualizada correctamente.');
    }

    public function destroy(Area $area)
    {
        $area->delete();

        return redirect()->route('admin.areas.index')
            ->with('success', 'Área eliminada correctamente.');
    }
}