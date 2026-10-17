<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAreaAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        $area = $request->route('area');

        if ($user->hasRole('admin')) { //aqui es donde el usuario admin tiene el acceso a todas las areas.
            return $next($request);
        }

        if (!$area || !$user->areas()->where('areas.id', $area->id)->exists()) { // Y el editor solo tien acceso a las areas que le fueron asignadas.
            abort(403, 'No tienes permiso para gestionar esta área.');
        }

        return $next($request);
    }
}