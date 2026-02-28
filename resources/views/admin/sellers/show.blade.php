@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <a href="{{ route('admin.sellers.index') }}" class="text-blue-600 hover:underline text-sm">← Volver a Vendedores</a>
            <h1 class="text-3xl font-bold text-gray-900 mt-4">{{ $user->company_name }}</h1>
            <p class="text-gray-600 mt-2">Vendedor: {{ $user->name }}</p>
        </div>

        <!-- Status Badge -->
        <div class="mb-8">
            <span class="px-4 py-2 rounded-full text-sm font-semibold
                @if($user->seller_status === 'pending') bg-yellow-100 text-yellow-800
                @elseif($user->seller_status === 'approved') bg-green-100 text-green-800
                @elseif($user->seller_status === 'rejected') bg-red-100 text-red-800
                @else bg-gray-100 text-gray-800
                @endif
            ">
                {{ ucfirst($user->seller_status) }}
            </span>
        </div>

        <!-- Información General -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
            <!-- Datos Personales -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Información Personal</h3>
                <div class="space-y-3 text-sm">
                    <div>
                        <span class="text-gray-600">Nombre:</span>
                        <p class="font-medium text-gray-900">{{ $user->name }}</p>
                    </div>
                    <div>
                        <span class="text-gray-600">Email:</span>
                        <p class="font-medium text-gray-900">{{ $user->email }}</p>
                    </div>
                    <div>
                        <span class="text-gray-600">Teléfono:</span>
                        <p class="font-medium text-gray-900">{{ $user->phone }}</p>
                    </div>
                    <div>
                        <span class="text-gray-600">RFC/NIF:</span>
                        <p class="font-medium text-gray-900">{{ $user->tax_id }}</p>
                    </div>
                </div>
            </div>

            <!-- Información de Empresa -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Información de Empresa</h3>
                <div class="space-y-3 text-sm">
                    <div>
                        <span class="text-gray-600">Empresa:</span>
                        <p class="font-medium text-gray-900">{{ $user->company_name }}</p>
                    </div>
                    <div>
                        <span class="text-gray-600">Comisión:</span>
                        <p class="font-medium text-gray-900">{{ $user->commission_rate }}%</p>
                    </div>
                    <div>
                        <span class="text-gray-600">Ubicación:</span>
                        <p class="font-medium text-gray-900">{{ $user->address }}, {{ $user->city }}, {{ $user->country }}</p>
                    </div>
                    <div>
                        <span class="text-gray-600">Código Postal:</span>
                        <p class="font-medium text-gray-900">{{ $user->postal_code }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Descripción de Empresa -->
        <div class="bg-white rounded-lg shadow p-6 mb-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Descripción de Empresa</h3>
            <p class="text-gray-700">{{ $user->company_description }}</p>
        </div>

        <!-- Logo -->
        @if($user->company_logo)
            <div class="bg-white rounded-lg shadow p-6 mb-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Logo de Empresa</h3>
                <img src="{{ Storage::url($user->company_logo) }}" alt="{{ $user->company_name }}" class="h-24 w-24 object-contain">
            </div>
        @endif

        <!-- Estadísticas -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-gray-600 text-sm">Productos</p>
                <p class="text-3xl font-bold text-gray-900">{{ $stats['total_products'] }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-gray-600 text-sm">Comisiones Totales</p>
                <p class="text-3xl font-bold text-gray-900">${{ number_format($stats['total_commissions'], 2) }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-gray-600 text-sm">Pendientes</p>
                <p class="text-3xl font-bold text-gray-900">${{ number_format($stats['pending_commissions'], 2) }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-gray-600 text-sm">Pagadas</p>
                <p class="text-3xl font-bold text-gray-900">${{ number_format($stats['paid_commissions'], 2) }}</p>
            </div>
        </div>

        <!-- Comisiones Recientes -->
        <div class="bg-white rounded-lg shadow overflow-hidden mb-8">
            <div class="px-6 py-4 border-b">
                <h3 class="text-lg font-semibold text-gray-900">Comisiones Recientes</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Producto</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Venta</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Comisión</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Estado</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Fecha</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @forelse($stats['recent_commissions'] as $commission)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $commission->product->name ?? 'Producto Eliminado' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900">${{ number_format($commission->sale_amount, 2) }}</td>
                                <td class="px-6 py-4 text-sm font-semibold text-gray-900">${{ number_format($commission->commission_amount, 2) }}</td>
                                <td class="px-6 py-4 text-sm">
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold
                                        @if($commission->status === 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($commission->status === 'approved') bg-blue-100 text-blue-800
                                        @elseif($commission->status === 'paid') bg-green-100 text-green-800
                                        @else bg-red-100 text-red-800
                                        @endif
                                    ">
                                        {{ ucfirst($commission->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $commission->sale_date->format('d/m/Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                    No hay comisiones
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Acciones -->
        @if($user->seller_status === 'pending')
            <div class="flex gap-4">
                <form action="{{ route('admin.sellers.approve', $user) }}" method="POST" style="flex: 1;" onsubmit="return confirm('¿Aprobar este vendedor?')">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="w-full px-6 py-3 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition">
                        Aprobar Vendedor
                    </button>
                </form>
                <button onclick="openRejectModal()" class="flex-1 px-6 py-3 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 transition">
                    Rechazar
                </button>
            </div>
        @elseif($user->seller_status === 'approved')
            <div class="flex gap-4">
                <button onclick="openUpdateCommissionModal({{ $user->commission_rate }})" class="flex-1 px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition">
                    Actualizar Comisión
                </button>
                <button onclick="openSuspendModal()" class="flex-1 px-6 py-3 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 transition">
                    Suspender
                </button>
            </div>
        @elseif($user->seller_status === 'suspended')
            <form action="{{ route('admin.sellers.reactivate', $user) }}" method="POST" onsubmit="return confirm('¿Reactivar este vendedor?')">
                @csrf
                @method('PATCH')
                <button type="submit" class="w-full px-6 py-3 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition">
                    Reactivar Vendedor
                </button>
            </form>
        @endif
    </div>
</div>

<!-- Modal: Rechazar Vendedor -->
<div id="rejectModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-lg max-w-md w-full mx-4 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Rechazar Solicitud</h3>
        <form id="rejectForm" action="{{ route('admin.sellers.reject', $user) }}" method="POST">
            @csrf
            @method('PATCH')
            <div class="mb-4">
                <label for="reason" class="block text-sm font-medium text-gray-700 mb-2">Razón del rechazo *</label>
                <textarea id="reason" name="reason" rows="4" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent" placeholder="Explica por qué rechazas esta solicitud..."></textarea>
            </div>
            <div class="flex gap-3">
                <button type="button" onclick="closeModal('rejectModal')" class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                    Cancelar
                </button>
                <button type="submit" class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                    Rechazar
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal: Suspender Vendedor -->
<div id="suspendModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-lg max-w-md w-full mx-4 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Suspender Vendedor</h3>
        <form id="suspendForm" action="{{ route('admin.sellers.suspend', $user) }}" method="POST">
            @csrf
            @method('PATCH')
            <div class="mb-4">
                <label for="suspend_reason" class="block text-sm font-medium text-gray-700 mb-2">Razón de la suspensión *</label>
                <textarea id="suspend_reason" name="reason" rows="4" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent" placeholder="Explica por qué suspendes este vendedor..."></textarea>
            </div>
            <div class="flex gap-3">
                <button type="button" onclick="closeModal('suspendModal')" class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                    Cancelar
                </button>
                <button type="submit" class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                    Suspender
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal: Actualizar Comisión -->
<div id="commissionModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-lg max-w-md w-full mx-4 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Actualizar Comisión</h3>
        <form id="commissionForm" action="{{ route('admin.sellers.update-commission', $user) }}" method="POST">
            @csrf
            @method('PATCH')
            <div class="mb-4">
                <label for="commission_rate" class="block text-sm font-medium text-gray-700 mb-2">Tasa de Comisión (%) *</label>
                <div class="flex gap-2">
                    <input type="number" id="commission_rate" name="commission_rate" min="0" max="100" step="0.1" required class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <span class="px-4 py-2 bg-gray-100 rounded-lg text-gray-700 font-medium">%</span>
                </div>
            </div>
            <div class="flex gap-3">
                <button type="button" onclick="closeModal('commissionModal')" class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                    Cancelar
                </button>
                <button type="submit" class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    Actualizar
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openRejectModal() {
    document.getElementById('rejectModal').classList.remove('hidden');
}

function openSuspendModal() {
    document.getElementById('suspendModal').classList.remove('hidden');
}

function openUpdateCommissionModal(currentRate) {
    document.getElementById('commission_rate').value = currentRate;
    document.getElementById('commissionModal').classList.remove('hidden');
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
}

// Cerrar modales al hacer clic fuera de ellos
document.querySelectorAll('[id$="Modal"]').forEach(modal => {
    modal.addEventListener('click', function(e) {
        if (e.target === this) {
            this.classList.add('hidden');
        }
    });
});
</script>
@endsection
