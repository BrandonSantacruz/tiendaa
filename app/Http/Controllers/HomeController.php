<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Mostrar la página de inicio
     */
    public function index()
    {
        // Si no está autenticado, mostrar página pública
        if (!Auth::check()) {
            return view('home');
        }

        // Si está autenticado, redirigir según su rol
        $user = Auth::user();
        $role = $user->getPrimaryRole();

        if ($role) {
            return match ($role->slug) {
                'super-admin' => redirect()->route('admin.dashboard'),
                'vendedor' => redirect()->route('seller.dashboard'),
                'cliente' => redirect()->route('client.dashboard'),
                default => view('home'),
            };
        }

        return view('home');
    }
}
