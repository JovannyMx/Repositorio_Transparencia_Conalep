<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Area;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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
            'icono' => 'nullable|file|mimes:png,jpg,jpeg,webp,svg|max:1024',
        ]);

        $validated['slug'] = Str::slug($validated['nombre']);
        $validated['orden'] = $validated['orden'] ?? 0;
        $validated['activo'] = $request->has('activo');

        if ($request->hasFile('icono')) {
            $validated['icono'] = $request->file('icono')->store('iconos', 'public');
        } else {
            unset($validated['icono']);
        }

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
            'icono' => 'nullable|file|mimes:png,jpg,jpeg,webp,svg|max:1024',
            'quitar_icono' => 'nullable|boolean',
        ]);

        $validated['slug'] = Str::slug($validated['nombre']);
        $validated['orden'] = $validated['orden'] ?? 0;
        $validated['activo'] = $request->has('activo');

        if ($request->hasFile('icono')) {
            // Imagen nueva: borra la anterior y guarda la nueva
            $this->borrarIcono($area);
            $validated['icono'] = $request->file('icono')->store('iconos', 'public');
        } elseif ($request->boolean('quitar_icono')) {
            // Casilla "quitar icono actual"
            $this->borrarIcono($area);
            $validated['icono'] = null;
        } else {
            // Sin cambios: conserva el icono que ya tenía
            unset($validated['icono']);
        }

        unset($validated['quitar_icono']);

        $area->update($validated);

        return redirect()->route('admin.areas.index')
            ->with('success', 'Área actualizada correctamente.');
    }

    public function destroy(Area $area)
    {
        $this->borrarIcono($area);
        $area->delete();

        return redirect()->route('admin.areas.index')
            ->with('success', 'Área eliminada correctamente.');
    }

    /**
     * Borra del disco la imagen del icono, solo si es un archivo subido
     * desde el formulario (vive dentro de la carpeta "iconos/").
     */
    private function borrarIcono(Area $area): void
    {
        if ($area->icono && str_starts_with($area->icono, 'iconos/')) {
            Storage::disk('public')->delete($area->icono);
        }
    }
}