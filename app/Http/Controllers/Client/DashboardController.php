<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Mostrar dashboard del Cliente
     */
    public function index()
    {
        $user = Auth::user();
        $totalOrders = 0;     // Cuando tengamos tabla de órdenes
        $totalPurchases = 0;  // Total gastado
        $recentOrders = [];   // Órdenes recientes

        return view('client.dashboard', compact(
            'user',
            'totalOrders',
            'totalPurchases',
            'recentOrders'
        ));
    }
}
