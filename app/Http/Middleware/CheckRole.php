<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // Si el usuario no está autenticado, redirigir al login
        if (!$request->user()) {
            return redirect()->route('login');
        }

        // Verificar si el usuario tiene uno de los roles requeridos
        if (!$request->user()->hasAnyRole($roles)) {
            abort(403, 'No autorizado para acceder a este recurso');
        }

        return $next($request);
    }
}
