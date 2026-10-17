<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

        <!-- Librería SweetAlert2 Global -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <!-- Script Global para Confirmación de Eliminación -->
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Usamos delegación de eventos para capturar cualquier envío de formulario en todo el sistema
                document.body.addEventListener('submit', function (e) {
                    
                    // Verificamos si el formulario que se está enviando tiene la clase 'form-eliminar'
                    if (e.target && e.target.classList.contains('form-eliminar')) {
                        e.preventDefault(); // Detenemos el envío inmediato
                        
                        const form = e.target;

                        Swal.fire({
                            title: '¿Estás seguro?',
                            text: "Esta acción eliminará el registro de forma permanente y no se puede deshacer.",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#ef4444', // Tailwind red-500
                            cancelButtonColor: '#64748b',  // Tailwind slate-500
                            confirmButtonText: 'Sí, eliminar',
                            cancelButtonText: 'Cancelar',
                            customClass: {
                                popup: 'rounded-2xl',
                            }
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // Si el usuario confirma, enviamos el formulario
                                form.submit();
                            }
                        });
                    }
                });
            });
        </script>
    </body>
</html>