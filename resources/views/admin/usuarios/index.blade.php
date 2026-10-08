<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <h2 class="font-garet font-bold text-2xl text-slate-800 leading-tight flex items-center gap-2">
                <div class="p-2 bg-conalep-primary/10 rounded-lg">
                    <svg class="w-6 h-6 text-conalep-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
                Gestión de <span class="text-conalep-primary">Usuarios</span>
            </h2>
            
            <a href="{{ route('admin.usuarios.create') }}"
               class="inline-flex items-center justify-center bg-conalep-primary hover:bg-conalep-dark text-white px-5 py-2.5 rounded-xl text-sm font-bold shadow-md shadow-conalep-primary/30 transition-all duration-200 hover:-translate-y-0.5 active:scale-95 focus:ring-4 focus:ring-conalep-primary/20">
                <svg class="w-5 h-5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                </svg>
                Nuevo usuario
            </a>
        </div>
    </x-slot>

    <div class="py-8 bg-conalep-gray/20 min-h-[calc(100vh-4rem)]">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Mensaje de éxito -->
            @if (session('success'))
                <div class="mb-8 flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-800 px-5 py-4 rounded-xl shadow-sm animate-fade-in-down" role="alert">
                    <div class="p-1.5 bg-emerald-100 rounded-full shrink-0">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <p class="font-semibold text-sm">{{ session('success') }}</p>
                </div>
            @endif

            <!-- Contenedor de la Tabla -->
            <div class="bg-white shadow-xl shadow-slate-200/40 rounded-2xl border-t-4 border-conalep-primary overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-100">
                        <thead class="bg-slate-50">
                            <tr>
                                <th scope="col" class="px-6 py-4 text-left text-[11px] font-bold text-slate-500 uppercase tracking-wider">Nombre</th>
                                <th scope="col" class="px-6 py-4 text-left text-[11px] font-bold text-slate-500 uppercase tracking-wider">Correo</th>
                                <th scope="col" class="px-6 py-4 text-left text-[11px] font-bold text-slate-500 uppercase tracking-wider">Rol</th>
                                <th scope="col" class="px-6 py-4 text-left text-[11px] font-bold text-slate-500 uppercase tracking-wider">Áreas asignadas</th>
                                <th scope="col" class="px-6 py-4 text-right text-[11px] font-bold text-slate-500 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-slate-100">
                            @forelse ($usuarios as $usuario)
                                <tr class="hover:bg-slate-50/80 transition-colors group">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="h-8 w-8 rounded-full bg-conalep-primary/10 text-conalep-primary flex items-center justify-center font-bold text-sm border border-conalep-primary/20 shrink-0">
                                                {{ substr($usuario->name, 0, 1) }}
                                            </div>
                                            <div class="ml-3">
                                                <div class="text-sm font-bold text-slate-800">{{ $usuario->name }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-slate-500">{{ $usuario->email }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex flex-wrap gap-1.5">
                                            @foreach ($usuario->roles as $rol)
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-wide bg-conalep-primary/10 text-conalep-dark border border-conalep-primary/20">
                                                    {{ $rol->name }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-600">
                                        @if($usuario->areas->count() > 0)
                                            <span class="line-clamp-2" title="{{ $usuario->areas->pluck('nombre')->join(', ') }}">
                                                {{ $usuario->areas->pluck('nombre')->join(', ') }}
                                            </span>
                                        @else
                                            <span class="text-slate-400 italic">—</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex items-center justify-end gap-3">
                                            <a href="{{ route('admin.usuarios.edit', $usuario) }}"
                                               class="text-slate-400 hover:text-conalep-primary transition-colors p-1" title="Editar usuario">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                            </a>
                                            
                                            @if ($usuario->id !== auth()->id())
                                                <!-- Formulario actualizado con la clase form-eliminar -->
                                                <form action="{{ route('admin.usuarios.destroy', $usuario) }}" method="POST" class="inline form-eliminar">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-slate-400 hover:text-red-500 transition-colors p-1" title="Eliminar usuario">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                        </svg>
                                                    </button>
                                                </form>
                                            @else
                                                <!-- Espaciador para alinear si es el usuario actual -->
                                                <span class="w-7"></span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center justify-center text-slate-500">
                                            <svg class="w-12 h-12 text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                            </svg>
                                            <p class="text-sm font-semibold">No se encontraron usuarios.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            
        </div>
    </div>
</x-app-layout>