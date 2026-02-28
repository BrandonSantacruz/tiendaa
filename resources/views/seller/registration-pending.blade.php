@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-gray-50 to-white flex items-center justify-center px-4">
    <div class="max-w-md w-full">
        <!-- Animation -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center h-24 w-24 rounded-full bg-blue-100 mb-6 animate-bounce">
                <svg class="h-12 w-12 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>

            <h1 class="text-3xl font-bold text-gray-900 mb-2">Solicitud Pendiente</h1>
            <p class="text-gray-600 mb-6">Tu registro ha sido enviado para revisión</p>
        </div>

        <!-- Info Card -->
        <div class="bg-white rounded-lg shadow-lg p-8 mb-6">
            <div class="mb-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">¿Qué sigue?</h2>
                
                <ol class="space-y-4">
                    <li class="flex items-start">
                        <span class="flex-shrink-0 flex items-center justify-center h-8 w-8 rounded-full bg-blue-600 text-white font-semibold mr-4">1</span>
                        <div>
                            <h3 class="text-sm font-medium text-gray-900">Revisión de tu solicitud</h3>
                            <p class="text-sm text-gray-600 mt-1">Nuestro equipo revisará tu información</p>
                        </div>
                    </li>
                    <li class="flex items-start">
                        <span class="flex-shrink-0 flex items-center justify-center h-8 w-8 rounded-full bg-gray-300 text-gray-600 font-semibold mr-4">2</span>
                        <div>
                            <h3 class="text-sm font-medium text-gray-900">Confirmación por email</h3>
                            <p class="text-sm text-gray-600 mt-1">Te enviaremos el resultado en 24-48 horas</p>
                        </div>
                    </li>
                    <li class="flex items-start">
                        <span class="flex-shrink-0 flex items-center justify-center h-8 w-8 rounded-full bg-gray-300 text-gray-600 font-semibold mr-4">3</span>
                        <div>
                            <h3 class="text-sm font-medium text-gray-900">Acceso al panel</h3>
                            <p class="text-sm text-gray-600 mt-1">Una vez aprobado, accede a tu panel de vendedor</p>
                        </div>
                    </li>
                </ol>
            </div>

            <div class="border-t pt-6">
                <h3 class="text-sm font-medium text-gray-900 mb-3">Tu información de contacto</h3>
                <div class="space-y-2 text-sm text-gray-600">
                    <p><strong>Email:</strong> {{ auth()->user()->email }}</p>
                    <p><strong>Empresa:</strong> {{ auth()->user()->company_name }}</p>
                </div>
            </div>
        </div>

        <!-- Notification Checkbox -->
        <div class="bg-blue-50 rounded-lg p-4 mb-6">
            <div class="flex items-start">
                <svg class="h-5 w-5 text-blue-600 mt-0.5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 5v8a2 2 0 01-2 2h-5l-5 4v-4H4a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2zm-11-1a1 1 0 11-2 0 1 1 0 012 0zM8 9a1 1 0 100-2 1 1 0 000 2zm5-1a1 1 0 11-2 0 1 1 0 012 0z" clip-rule="evenodd"></path>
                </svg>
                <div>
                    <p class="text-sm font-medium text-blue-900">Te notificaremos por email</p>
                    <p class="text-xs text-blue-700 mt-1">Revisa tu bandeja de entrada y la carpeta de spam</p>
                </div>
            </div>
        </div>

        <!-- Check Status Button -->
        <button 
            onclick="checkStatus()" 
            class="w-full px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition mb-4"
        >
            Verificar Estado
        </button>

        <!-- Back Button -->
        <a href="{{ route('home') }}" class="w-full px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition text-center block">
            Volver a Inicio
        </a>

        <!-- FAQ Section -->
        <div class="mt-8 pt-8 border-t">
            <h3 class="text-sm font-semibold text-gray-900 mb-4">Preguntas Frecuentes</h3>
            
            <div class="space-y-4">
                <details class="group">
                    <summary class="flex cursor-pointer items-center justify-between rounded-lg bg-gray-50 px-4 py-3 text-gray-900 hover:bg-gray-100">
                        <span class="text-sm font-medium">¿Cuánto tarda la revisión?</span>
                        <span class="transition group-open:rotate-180">
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                            </svg>
                        </span>
                    </summary>
                    <p class="mt-2 px-4 text-sm text-gray-600">Normalmente revisamos las solicitudes en 24-48 horas. En periodos de alta demanda puede tomar un poco más.</p>
                </details>

                <details class="group">
                    <summary class="flex cursor-pointer items-center justify-between rounded-lg bg-gray-50 px-4 py-3 text-gray-900 hover:bg-gray-100">
                        <span class="text-sm font-medium">¿Qué ocurre si mi solicitud es rechazada?</span>
                        <span class="transition group-open:rotate-180">
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                            </svg>
                        </span>
                    </summary>
                    <p class="mt-2 px-4 text-sm text-gray-600">Si tu solicitud es rechazada, recibirás un email con la razón. Podrás realizar correcciones y reenviar tu solicitud.</p>
                </details>

                <details class="group">
                    <summary class="flex cursor-pointer items-center justify-between rounded-lg bg-gray-50 px-4 py-3 text-gray-900 hover:bg-gray-100">
                        <span class="text-sm font-medium">¿Puedo cambiar mis datos?</span>
                        <span class="transition group-open:rotate-180">
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                            </svg>
                        </span>
                    </summary>
                    <p class="mt-2 px-4 text-sm text-gray-600">Contacta a nuestro equipo de soporte para realizar cambios en tu información antes de que sea aprobada.</p>
                </details>
            </div>
        </div>
    </div>
</div>

<script>
function checkStatus() {
    fetch('{{ route("seller.registration.status") }}')
        .then(response => response.json())
        .then(data => {
            if (data.is_approved) {
                window.location.href = '{{ route("seller.dashboard") }}';
            } else if (data.is_rejected) {
                alert('Tu solicitud ha sido rechazada. Razón: ' + data.rejection_reason);
                window.location.href = '{{ route("seller.register") }}';
            } else {
                alert('Tu solicitud sigue en revisión. Por favor intenta más tarde.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al verificar el estado. Intenta más tarde.');
        });
}

// Verificar estado automáticamente cada 30 segundos
setInterval(function() {
    fetch('{{ route("seller.registration.status") }}')
        .then(response => response.json())
        .then(data => {
            if (data.is_approved || data.is_rejected) {
                location.reload();
            }
        })
        .catch(error => console.log('Verificación de estado en background'));
}, 30000);
</script>
@endsection
