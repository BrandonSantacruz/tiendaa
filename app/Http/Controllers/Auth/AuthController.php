<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Mostrar formulario de login
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Procesar login del usuario
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ], [
            'email.required' => 'El email es requerido',
            'email.email' => 'El email debe ser válido',
            'password.required' => 'La contraseña es requerida',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Redirigir según el rol del usuario
            $user = Auth::user();
            $role = $user->getPrimaryRole();

            if ($role) {
                return match ($role->slug) {
                    'super-admin' => redirect()->route('admin.dashboard'),
                    'vendedor' => redirect()->route('seller.dashboard'),
                    'cliente' => redirect()->route('client.dashboard'),
                    default => redirect()->route('dashboard'),
                };
            }

            return redirect()->route('dashboard');
        }

        throw ValidationException::withMessages([
            'email' => 'Las credenciales no son válidas',
        ]);
    }

    /**
     * Mostrar formulario de registro
     */
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    /**
     * Procesar registro de usuario
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
        ], [
            'name.required' => 'El nombre es requerido',
            'email.required' => 'El email es requerido',
            'email.unique' => 'Este email ya está registrado',
            'password.required' => 'La contraseña es requerida',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres',
            'password.confirmed' => 'Las contraseñas no coinciden',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        // Asignar rol de cliente por defecto
        $clientRole = \App\Models\Role::where('slug', 'cliente')->first();
        if ($clientRole) {
            $user->roles()->attach($clientRole);
        }

        Auth::login($user);

        return redirect()->route('client.dashboard')->with('success', 'Registro exitoso');
    }

    /**
     * Cerrar sesión
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Sesión cerrada exitosamente');
    }

    /**
     * Mostrar formulario de recuperación de contraseña
     */
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Enviar link de recuperación de contraseña
     */
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.required' => 'El email es requerido',
            'email.exists' => 'Este email no está registrado',
        ]);

        // Aquí iría la lógica para enviar el email
        // Por ahora, solo mostramos un mensaje

        return back()->with('success', 'Se ha enviado un link de recuperación a tu email');
    }

    /**
     * Mostrar formulario de reseteo de contraseña
     */
    public function showResetForm($token)
    {
        return view('auth.reset-password', ['token' => $token]);
    }

    /**
     * Procesar reseteo de contraseña
     */
    public function resetPassword(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:8|confirmed',
            'token' => 'required',
        ]);

        // Aquí iría la lógica para resetear la contraseña
        // Por ahora, solo mostramos un mensaje

        return redirect()->route('login')->with('success', 'Contraseña actualizada exitosamente');
    }
}
