<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Mostrar dashboard del Vendedor
     */
    public function index()
    {
        $user = Auth::user();
        $totalProducts = 0; // Cuando tengamos tabla de productos
        $totalOrders = 0;   // Cuando tengamos tabla de órdenes
        $totalRevenue = 0;  // Cuando tengamos tabla de ventas

        return view('seller.dashboard', compact(
            'user',
            'totalProducts',
            'totalOrders',
            'totalRevenue'
        ));
    }
}
