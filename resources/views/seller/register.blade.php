@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-gray-50 to-white py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-2xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Conviértete en Vendedor</h1>
            <p class="text-lg text-gray-600">Completa tu registro y comenzar a vender tus productos en nuestro marketplace</p>
        </div>

        <!-- Form Container -->
        <div class="bg-white rounded-lg shadow-lg p-8">
            @if ($errors->any())
                <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                    <h3 class="text-red-800 font-semibold mb-2">Por favor corrige los siguientes errores:</h3>
                    <ul class="list-disc list-inside text-red-700 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('seller.register') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                @csrf

                <!-- Sección 1: Datos Personales -->
                <div>
                    <h2 class="text-xl font-bold text-gray-900 mb-6 pb-4 border-b">Datos Personales</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Nombre -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nombre Completo *</label>
                            <input 
                                type="text" 
                                id="name" 
                                name="name" 
                                value="{{ old('name') }}"
                                required 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="Juan Pérez"
                            >
                            @error('name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Correo Electrónico *</label>
                            <input 
                                type="email" 
                                id="email" 
                                name="email" 
                                value="{{ old('email') }}"
                                required 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="juan@example.com"
                            >
                            @error('email')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Teléfono -->
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Teléfono *</label>
                            <input 
                                type="tel" 
                                id="phone" 
                                name="phone" 
                                value="{{ old('phone') }}"
                                required 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="+34 123 456 789"
                            >
                            @error('phone')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Contraseña -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Contraseña *</label>
                            <input 
                                type="password" 
                                id="password" 
                                name="password" 
                                required 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="••••••••"
                            >
                            <p class="text-gray-500 text-xs mt-1">Mínimo 8 caracteres, incluyendo mayúsculas, números y caracteres especiales</p>
                            @error('password')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Confirmar Contraseña -->
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirmar Contraseña *</label>
                            <input 
                                type="password" 
                                id="password_confirmation" 
                                name="password_confirmation" 
                                required 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="••••••••"
                            >
                            @error('password_confirmation')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Sección 2: Información de Empresa -->
                <div>
                    <h2 class="text-xl font-bold text-gray-900 mb-6 pb-4 border-b">Información de la Empresa</h2>
                    
                    <div class="space-y-6">
                        <!-- Nombre de Empresa -->
                        <div>
                            <label for="company_name" class="block text-sm font-medium text-gray-700 mb-2">Nombre de la Empresa *</label>
                            <input 
                                type="text" 
                                id="company_name" 
                                name="company_name" 
                                value="{{ old('company_name') }}"
                                required 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="Mi Empresa S.A."
                            >
                            @error('company_name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Descripción -->
                        <div>
                            <label for="company_description" class="block text-sm font-medium text-gray-700 mb-2">Descripción de la Empresa *</label>
                            <textarea 
                                id="company_description" 
                                name="company_description" 
                                rows="4"
                                required 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="Cuéntanos sobre tu empresa..."
                            >{{ old('company_description') }}</textarea>
                            @error('company_description')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tax ID -->
                        <div>
                            <label for="tax_id" class="block text-sm font-medium text-gray-700 mb-2">RFC / NIF / Tax ID *</label>
                            <input 
                                type="text" 
                                id="tax_id" 
                                name="tax_id" 
                                value="{{ old('tax_id') }}"
                                required 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="ABC123456DEF"
                            >
                            <p class="text-gray-500 text-xs mt-1">Número de identificación fiscal único de tu empresa</p>
                            @error('tax_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Logo -->
                        <div>
                            <label for="company_logo" class="block text-sm font-medium text-gray-700 mb-2">Logo de la Empresa</label>
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-500 transition">
                                <input 
                                    type="file" 
                                    id="company_logo" 
                                    name="company_logo" 
                                    accept="image/*"
                                    class="hidden"
                                    onchange="updateFileName(this)"
                                >
                                <label for="company_logo" class="cursor-pointer">
                                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-2" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20a4 4 0 004 4h24a4 4 0 004-4V20m-8-8l-3-3m0 0L21 7m4 0h8v8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                    </svg>
                                    <p class="text-gray-600">Haz clic o arrastra tu logo aquí</p>
                                    <p class="text-gray-500 text-xs mt-1">PNG, JPG, GIF hasta 2MB (mín. 200x200px)</p>
                                </label>
                            </div>
                            @error('company_logo')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Sección 3: Ubicación -->
                <div>
                    <h2 class="text-xl font-bold text-gray-900 mb-6 pb-4 border-b">Ubicación del Negocio</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Dirección -->
                        <div class="md:col-span-2">
                            <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Dirección *</label>
                            <input 
                                type="text" 
                                id="address" 
                                name="address" 
                                value="{{ old('address') }}"
                                required 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="Calle Principal 123"
                            >
                            @error('address')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Ciudad -->
                        <div>
                            <label for="city" class="block text-sm font-medium text-gray-700 mb-2">Ciudad *</label>
                            <input 
                                type="text" 
                                id="city" 
                                name="city" 
                                value="{{ old('city') }}"
                                required 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="Madrid"
                            >
                            @error('city')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Código Postal -->
                        <div>
                            <label for="postal_code" class="block text-sm font-medium text-gray-700 mb-2">Código Postal *</label>
                            <input 
                                type="text" 
                                id="postal_code" 
                                name="postal_code" 
                                value="{{ old('postal_code') }}"
                                required 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="28001"
                            >
                            @error('postal_code')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- País -->
                        <div>
                            <label for="country" class="block text-sm font-medium text-gray-700 mb-2">País *</label>
                            <input 
                                type="text" 
                                id="country" 
                                name="country" 
                                value="{{ old('country') }}"
                                required 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="España"
                            >
                            @error('country')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Términos y Condiciones -->
                <div class="flex items-start">
                    <input 
                        type="checkbox" 
                        id="terms" 
                        name="terms" 
                        required
                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                    >
                    <label for="terms" class="ml-3 text-sm text-gray-700">
                        Acepto los <a href="#" class="text-blue-600 hover:underline">términos y condiciones</a> y la <a href="#" class="text-blue-600 hover:underline">política de privacidad</a> *
                    </label>
                </div>

                <!-- Botones -->
                <div class="flex gap-4 pt-6 border-t">
                    <a href="{{ route('home') }}" class="flex-1 px-6 py-3 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition text-center">
                        Cancelar
                    </a>
                    <button 
                        type="submit" 
                        class="flex-1 px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition"
                    >
                        Registrarme como Vendedor
                    </button>
                </div>
            </form>
        </div>

        <!-- Info Box -->
        <div class="mt-12 grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-blue-50 rounded-lg p-6">
                <div class="flex items-center mb-4">
                    <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <h3 class="ml-3 font-semibold text-gray-900">Aprobación Rápida</h3>
                </div>
                <p class="text-sm text-gray-600">Tu solicitud será revisada en 24-48 horas</p>
            </div>

            <div class="bg-green-50 rounded-lg p-6">
                <div class="flex items-center mb-4">
                    <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <h3 class="ml-3 font-semibold text-gray-900">Comisiones Justas</h3>
                </div>
                <p class="text-sm text-gray-600">Comisión estándar del 10% sobre cada venta</p>
            </div>

            <div class="bg-purple-50 rounded-lg p-6">
                <div class="flex items-center mb-4">
                    <svg class="h-6 w-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                    <h3 class="ml-3 font-semibold text-gray-900">Panel Completo</h3>
                </div>
                <p class="text-sm text-gray-600">Acceso a tu panel de control y analíticas</p>
            </div>
        </div>
    </div>
</div>

<script>
function updateFileName(input) {
    if (input.files && input.files[0]) {
        const fileName = input.files[0].name;
        const label = input.nextElementSibling;
        label.querySelector('p:first-child').textContent = `Archivo seleccionado: ${fileName}`;
    }
}
</script>
@endsection
