<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class SellerRegistrationController extends Controller
{
    /**
     * Mostrar formulario de registro de vendedor
     */
    public function showRegistrationForm()
    {
        // Solo mostrar si el usuario no es ya vendedor
        if (auth()->check() && auth()->user()->hasRole('vendedor')) {
            return redirect()->route('seller.dashboard');
        }

        return view('seller.register');
    }

    /**
     * Registrar nuevo vendedor
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            // Datos personales
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users'],
            'phone' => ['required', 'string', 'regex:/^[0-9\-\+\(\)]+$/', 'min:10'],
            'password' => ['required', Password::defaults()],
            'password_confirmation' => ['required', 'same:password'],

            // Información de empresa
            'company_name' => ['required', 'string', 'max:255'],
            'company_description' => ['required', 'string', 'min:10', 'max:1000'],
            'tax_id' => ['required', 'string', 'unique:users,tax_id', 'regex:/^[A-Z0-9\-]+$/i'],

            // Ubicación
            'address' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:100'],
            'country' => ['required', 'string', 'max:100'],
            'postal_code' => ['required', 'string', 'max:20'],

            // Logo de empresa (opcional)
            'company_logo' => ['nullable', 'image', 'max:2048', 'dimensions:min_width=200,min_height=200'],
        ]);

        try {
            // Procesar logo si existe
            $logoPath = null;
            if ($request->hasFile('company_logo')) {
                $logoPath = $request->file('company_logo')->store('company-logos', 'public');
            }

            // Crear nuevo usuario vendedor
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'password' => Hash::make($validated['password']),
                'company_name' => $validated['company_name'],
                'company_description' => $validated['company_description'],
                'company_logo' => $logoPath,
                'tax_id' => $validated['tax_id'],
                'address' => $validated['address'],
                'city' => $validated['city'],
                'country' => $validated['country'],
                'postal_code' => $validated['postal_code'],
                'seller_status' => 'pending',
                'commission_rate' => 10, // 10% por defecto
            ]);

            // Asignar rol de vendedor
            $user->assignRole('vendedor');

            return redirect()->route('seller.registration.pending')
                ->with('success', 'Tu solicitud de registro ha sido enviada. Por favor espera la aprobación del administrador.');

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Error al registrar el vendedor: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar página de solicitud pendiente
     */
    public function showPending()
    {
        return view('seller.registration-pending');
    }

    /**
     * Verificar estado de solicitud (AJAX)
     */
    public function checkStatus()
    {
        if (!auth()->check()) {
            return response()->json(['status' => 'not_authenticated'], 401);
        }

        $user = auth()->user();

        return response()->json([
            'seller_status' => $user->seller_status,
            'is_approved' => $user->isApprovedSeller(),
            'is_rejected' => $user->isRejectedSeller(),
            'rejection_reason' => $user->seller_rejection_reason,
            'approved_at' => $user->seller_approved_at,
        ]);
    }
}
