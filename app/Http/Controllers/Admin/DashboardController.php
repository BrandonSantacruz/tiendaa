<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Mostrar dashboard del Super Admin
     */
    public function index()
    {
        $totalUsers = User::count();
        $totalVendors = User::whereHas('roles', function ($query) {
            $query->where('slug', 'vendedor');
        })->count();
        $totalClients = User::whereHas('roles', function ($query) {
            $query->where('slug', 'cliente');
        })->count();
        $roles = Role::all();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalVendors',
            'totalClients',
            'roles'
        ));
    }
}
