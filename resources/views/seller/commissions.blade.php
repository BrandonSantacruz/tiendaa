@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="mb-8 flex justify-between items-start">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Mis Comisiones</h1>
                <p class="text-gray-600 mt-2">Historial de comisiones por ventas</p>
            </div>
            <a href="{{ route('seller.dashboard') }}" class="text-blue-600 hover:underline">← Volver al Panel</a>
        </div>

        <!-- Filtros -->
        <div class="bg-white rounded-lg shadow p-6 mb-8">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Estado</label>
                    <select name="status" id="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="">Todos los estados</option>
                        <option value="pending" @selected(request('status') === 'pending')>Pendiente</option>
                        <option value="approved" @selected(request('status') === 'approved')>Aprobado</option>
                        <option value="paid" @selected(request('status') === 'paid')>Pagado</option>
                        <option value="refunded" @selected(request('status') === 'refunded')>Reembolsado</option>
                    </select>
                </div>

                <div>
                    <label for="date_from" class="block text-sm font-medium text-gray-700 mb-2">Desde</label>
                    <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label for="date_to" class="block text-sm font-medium text-gray-700 mb-2">Hasta</label>
                    <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="flex items-end gap-2">
                    <button type="submit" class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        Filtrar
                    </button>
                    <a href="{{ route('seller.commissions') }}" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                        Limpiar
                    </a>
                </div>
            </form>
        </div>

        <!-- Tabla de Comisiones -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Producto</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Venta</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Tasa</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Comisión</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Estado</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Fecha</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-700 uppercase">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @forelse($commissions as $commission)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $commission->product->name ?? 'Producto Eliminado' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900">${{ number_format($commission->sale_amount, 2) }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $commission->commission_rate }}%</td>
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
                                <td class="px-6 py-4 text-center">
                                    <a href="{{ route('seller.commission.detail', $commission) }}" class="text-blue-600 hover:underline text-sm">
                                        Ver Detalles
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                    <svg class="h-12 w-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    No hay comisiones
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            <div class="px-6 py-4 border-t">
                {{ $commissions->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
