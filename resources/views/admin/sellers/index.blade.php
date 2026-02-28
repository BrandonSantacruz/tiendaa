@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Gestión de Vendedores</h1>
            <p class="text-gray-600 mt-2">Aprueba, rechaza o suspende solicitudes de vendedores</p>
        </div>

        <!-- Tabs -->
        <div class="mb-6 border-b border-gray-200">
            <nav class="flex gap-8" aria-label="Tabs">
                <button onclick="showTab('pending')" class="tab-btn active px-1 py-4 text-sm font-medium text-gray-900 border-b-2 border-blue-500">
                    Pendientes <span class="ml-2 bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full text-xs">{{ $pendingSellers->total() }}</span>
                </button>
                <button onclick="showTab('approved')" class="tab-btn px-1 py-4 text-sm font-medium text-gray-500 border-b-2 border-transparent hover:border-gray-300 hover:text-gray-700">
                    Aprobados <span class="ml-2 bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">{{ $approvedSellers->total() }}</span>
                </button>
                <button onclick="showTab('rejected')" class="tab-btn px-1 py-4 text-sm font-medium text-gray-500 border-b-2 border-transparent hover:border-gray-300 hover:text-gray-700">
                    Rechazados <span class="ml-2 bg-red-100 text-red-800 px-2 py-1 rounded-full text-xs">{{ $rejectedSellers->total() }}</span>
                </button>
            </nav>
        </div>

        <!-- Tab: Pendientes -->
        <div id="pending" class="tab-content">
            @if($pendingSellers->count() > 0)
                <div class="grid gap-6 mb-8">
                    @foreach($pendingSellers as $seller)
                        <div class="bg-white rounded-lg shadow overflow-hidden">
                            <div class="p-6">
                                <div class="flex items-start justify-between mb-4">
                                    <div class="flex-1">
                                        <h3 class="text-lg font-semibold text-gray-900">{{ $seller->company_name }}</h3>
                                        <p class="text-sm text-gray-600">{{ $seller->name }} • {{ $seller->email }}</p>
                                    </div>
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">Pendiente</span>
                                </div>

                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 py-4 border-y border-gray-200">
                                    <div>
                                        <p class="text-xs text-gray-600 uppercase">Teléfono</p>
                                        <p class="text-sm font-medium text-gray-900">{{ $seller->phone }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-600 uppercase">RFC/NIF</p>
                                        <p class="text-sm font-medium text-gray-900">{{ $seller->tax_id }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-600 uppercase">Ubicación</p>
                                        <p class="text-sm font-medium text-gray-900">{{ $seller->city }}, {{ $seller->country }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-600 uppercase">Solicitud</p>
                                        <p class="text-sm font-medium text-gray-900">{{ $seller->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>

                                <p class="text-sm text-gray-700 my-4">{{ $seller->company_description }}</p>

                                <div class="flex gap-3 pt-4">
                                    <a href="{{ route('admin.sellers.show', $seller) }}" class="flex-1 px-4 py-2 bg-blue-600 text-white text-center rounded-lg hover:bg-blue-700 transition">
                                        Ver Detalles
                                    </a>
                                    <form action="{{ route('admin.sellers.approve', $seller) }}" method="POST" style="flex: 1;" onsubmit="return confirm('¿Aprobar este vendedor?')">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                                            Aprobar
                                        </button>
                                    </form>
                                    <button onclick="openRejectModal({{ $seller->id }})" class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                                        Rechazar
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                {{ $pendingSellers->links() }}
            @else
                <div class="bg-white rounded-lg shadow p-12 text-center">
                    <svg class="h-12 w-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-gray-600">No hay solicitudes pendientes</p>
                </div>
            @endif
        </div>

        <!-- Tab: Aprobados -->
        <div id="approved" class="tab-content hidden">
            @if($approvedSellers->count() > 0)
                <div class="grid gap-6 mb-8">
                    @foreach($approvedSellers as $seller)
                        <div class="bg-white rounded-lg shadow overflow-hidden">
                            <div class="p-6">
                                <div class="flex items-start justify-between mb-4">
                                    <div class="flex-1">
                                        <h3 class="text-lg font-semibold text-gray-900">{{ $seller->company_name }}</h3>
                                        <p class="text-sm text-gray-600">{{ $seller->name }} • {{ $seller->email }}</p>
                                    </div>
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">Aprobado</span>
                                </div>

                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 py-4 border-y border-gray-200">
                                    <div>
                                        <p class="text-xs text-gray-600 uppercase">Productos</p>
                                        <p class="text-sm font-medium text-gray-900">{{ $seller->products()->count() }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-600 uppercase">Comisión</p>
                                        <p class="text-sm font-medium text-gray-900">{{ $seller->commission_rate }}%</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-600 uppercase">Aprobado</p>
                                        <p class="text-sm font-medium text-gray-900">{{ $seller->seller_approved_at->format('d/m/Y') }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-600 uppercase">Total Pagado</p>
                                        <p class="text-sm font-medium text-gray-900">${{ number_format($seller->payouts()->where('status', 'completed')->sum('amount'), 2) }}</p>
                                    </div>
                                </div>

                                <div class="flex gap-3 pt-4">
                                    <a href="{{ route('admin.sellers.show', $seller) }}" class="flex-1 px-4 py-2 bg-blue-600 text-white text-center rounded-lg hover:bg-blue-700 transition">
                                        Ver Detalles
                                    </a>
                                    <button onclick="openUpdateCommissionModal({{ $seller->id }}, {{ $seller->commission_rate }})" class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                                        Actualizar Comisión
                                    </button>
                                    <button onclick="openSuspendModal({{ $seller->id }})" class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                                        Suspender
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                {{ $approvedSellers->links() }}
            @else
                <div class="bg-white rounded-lg shadow p-12 text-center">
                    <svg class="h-12 w-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-gray-600">No hay vendedores aprobados</p>
                </div>
            @endif
        </div>

        <!-- Tab: Rechazados -->
        <div id="rejected" class="tab-content hidden">
            @if($rejectedSellers->count() > 0)
                <div class="grid gap-6 mb-8">
                    @foreach($rejectedSellers as $seller)
                        <div class="bg-white rounded-lg shadow overflow-hidden">
                            <div class="p-6">
                                <div class="flex items-start justify-between mb-4">
                                    <div class="flex-1">
                                        <h3 class="text-lg font-semibold text-gray-900">{{ $seller->company_name }}</h3>
                                        <p class="text-sm text-gray-600">{{ $seller->name }} • {{ $seller->email }}</p>
                                    </div>
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">Rechazado</span>
                                </div>

                                <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-4">
                                    <p class="text-sm text-red-800"><strong>Razón:</strong> {{ $seller->seller_rejection_reason }}</p>
                                </div>

                                <div class="flex gap-3">
                                    <a href="{{ route('admin.sellers.show', $seller) }}" class="flex-1 px-4 py-2 bg-blue-600 text-white text-center rounded-lg hover:bg-blue-700 transition">
                                        Ver Detalles
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                {{ $rejectedSellers->links() }}
            @else
                <div class="bg-white rounded-lg shadow p-12 text-center">
                    <svg class="h-12 w-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-gray-600">No hay vendedores rechazados</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal: Rechazar Vendedor -->
<div id="rejectModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-lg max-w-md w-full mx-4 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Rechazar Solicitud</h3>
        <form id="rejectForm" method="POST">
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
        <form id="suspendForm" method="POST">
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
        <form id="commissionForm" method="POST">
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
function showTab(tab) {
    // Ocultar todos los tabs
    document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
    document.querySelectorAll('.tab-btn').forEach(el => {
        el.classList.remove('active', 'text-gray-900', 'border-blue-500');
        el.classList.add('text-gray-500', 'border-transparent');
    });

    // Mostrar tab seleccionado
    document.getElementById(tab).classList.remove('hidden');
    event.target.classList.add('active', 'text-gray-900', 'border-blue-500');
    event.target.classList.remove('text-gray-500', 'border-transparent');
}

function openRejectModal(sellerId) {
    const form = document.getElementById('rejectForm');
    form.action = '/admin/sellers/' + sellerId + '/reject';
    document.getElementById('rejectModal').classList.remove('hidden');
}

function openSuspendModal(sellerId) {
    const form = document.getElementById('suspendForm');
    form.action = '/admin/sellers/' + sellerId + '/suspend';
    document.getElementById('suspendModal').classList.remove('hidden');
}

function openUpdateCommissionModal(sellerId, currentRate) {
    const form = document.getElementById('commissionForm');
    form.action = '/admin/sellers/' + sellerId + '/commission';
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
