<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Usuarios</h2>
            <a href="{{ route('admin.usuarios.create') }}"
               class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                + Nuevo usuario
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded-md">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white shadow rounded-lg overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nombre</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Correo</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rol</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Áreas asignadas</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($usuarios as $usuario)
                            <tr>
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $usuario->name }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $usuario->email }}</td>
                                <td class="px-6 py-4 text-sm">
                                    @foreach ($usuario->roles as $rol)
                                        <span class="text-xs bg-indigo-100 text-indigo-700 px-2 py-1 rounded-full">{{ $rol->name }}</span>
                                    @endforeach
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    {{ $usuario->areas->pluck('nombre')->join(', ') ?: '—' }}
                                </td>
                                <td class="px-6 py-4 text-right text-sm space-x-2">
                                    <a href="{{ route('admin.usuarios.edit', $usuario) }}"
                                       class="text-indigo-600 hover:text-indigo-900">Editar</a>
                                    @if ($usuario->id !== auth()->id())
                                        <form action="{{ route('admin.usuarios.destroy', $usuario) }}" method="POST" class="inline"
                                              onsubmit="return confirm('¿Eliminar este usuario?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">Eliminar</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>