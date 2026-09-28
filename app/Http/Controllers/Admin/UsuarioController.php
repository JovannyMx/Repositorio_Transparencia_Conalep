<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UsuarioController extends Controller
{
    public function index()
    {
        $usuarios = User::with('roles', 'areas')->get();

        return view('admin.usuarios.index', compact('usuarios'));
    }

    public function create()
    {
        $roles = Role::all();
        $areas = Area::orderBy('nombre')->get();

        return view('admin.usuarios.create', compact('roles', 'areas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'role' => 'required|exists:roles,name',
            'areas' => 'nullable|array',
            'areas.*' => 'exists:areas,id',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $user->assignRole($validated['role']);

        if ($validated['role'] === 'editor') {
            $user->areas()->sync($validated['areas'] ?? []);
        }

        return redirect()->route('admin.usuarios.index')
            ->with('success', 'Usuario creado correctamente.');
    }

    public function edit(User $usuario)
    {
        $roles = Role::all();
        $areas = Area::orderBy('nombre')->get();
        $usuario->load('roles', 'areas');

        return view('admin.usuarios.edit', compact('usuario', 'roles', 'areas'));
    }

    public function update(Request $request, User $usuario)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $usuario->id,
            'password' => 'nullable|string|min:8',
            'role' => 'required|exists:roles,name',
            'areas' => 'nullable|array',
            'areas.*' => 'exists:areas,id',
        ]);

        $usuario->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        if (!empty($validated['password'])) {
            $usuario->update(['password' => Hash::make($validated['password'])]);
        }

        $usuario->syncRoles([$validated['role']]);

        $usuario->areas()->sync($validated['role'] === 'editor' ? ($validated['areas'] ?? []) : []);

        return redirect()->route('admin.usuarios.index')
            ->with('success', 'Usuario actualizado correctamente.');
    }

    public function destroy(User $usuario)
    {
        if ($usuario->id === auth()->id()) {
            return back()->withErrors(['usuario' => 'No puedes eliminar tu propia cuenta.']);
        }

        $usuario->delete();

        return redirect()->route('admin.usuarios.index')
            ->with('success', 'Usuario eliminado correctamente.');
    }
}