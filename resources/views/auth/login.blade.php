<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Iniciar Sesión - Tienda Online</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-br from-blue-500 to-blue-700 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md p-8 bg-white rounded-lg shadow-2xl">
        <!-- Logo -->
        <div class="flex justify-center mb-8">
            <div class="w-16 h-16 bg-blue-600 rounded-lg flex items-center justify-center">
                <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4z"></path>
                    <path fill-rule="evenodd" d="M3 10a1 1 0 011-1h12a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zm11 4a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
            </div>
        </div>

        <!-- Title -->
        <h1 class="text-3xl font-bold text-center text-gray-900 mb-2">Tienda Online</h1>
        <p class="text-center text-gray-600 mb-8">Accede a tu cuenta</p>

        <!-- Mostrar errores -->
        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                @foreach ($errors->all() as $error)
                    <p class="text-sm">{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <!-- Form -->
        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    value="{{ old('email') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                    placeholder="tu@email.com"
                    required
                >
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Contraseña</label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                    placeholder="••••••••"
                    required
                >
            </div>

            <!-- Remember Me -->
            <div class="flex items-center">
                <input 
                    type="checkbox" 
                    id="remember" 
                    name="remember" 
                    class="w-4 h-4 text-blue-600 rounded focus:ring-blue-500"
                >
                <label for="remember" class="ml-2 text-sm text-gray-600">Recuérdame</label>
            </div>

            <!-- Submit Button -->
            <button 
                type="submit" 
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition duration-200 ease-in-out transform hover:scale-105"
            >
                Iniciar Sesión
            </button>
        </form>

        <!-- Divider -->
        <div class="mt-6 flex items-center">
            <div class="flex-1 border-t border-gray-300"></div>
            <div class="px-3 text-gray-500 text-sm">o</div>
            <div class="flex-1 border-t border-gray-300"></div>
        </div>

        <!-- Forgot Password & Register Links -->
        <div class="mt-6 space-y-3 text-center">
            <p class="text-sm text-gray-600">
                <a href="{{ route('forgot-password') }}" class="text-blue-600 hover:underline">¿Olvidaste tu contraseña?</a>
            </p>
            
            <p class="text-sm text-gray-600">
                ¿No tienes cuenta? 
                <a href="{{ route('register') }}" class="text-blue-600 hover:underline font-semibold">Regístrate aquí</a>
            </p>
        </div>

        <!-- Demo Credentials (para desarrollo) -->
        <div class="mt-8 p-4 bg-blue-50 border border-blue-200 rounded-lg text-sm text-gray-700">
            <p class="font-semibold mb-2">Credenciales de Prueba:</p>
            <p><strong>Super Admin:</strong> admin@tienda.com / password</p>
            <p><strong>Vendedor:</strong> vendedor@tienda.com / password</p>
            <p><strong>Cliente:</strong> cliente@tienda.com / password</p>
        </div>
    </div>
</body>
</html>
