@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="mb-8 flex justify-between items-start">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Mis Pagos</h1>
                <p class="text-gray-600 mt-2">Historial de pagos y transferencias</p>
            </div>
            <a href="{{ route('seller.dashboard') }}" class="text-blue-600 hover:underline">← Volver al Panel</a>
        </div>

        <!-- Estadísticas de Pagos -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-gray-600 text-sm">Total Pagado</p>
                <p class="text-3xl font-bold text-gray-900">${{ number_format($payoutStats['total_paid'], 2) }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-gray-600 text-sm">Pendientes de Procesar</p>
                <p class="text-3xl font-bold text-gray-900">${{ number_format($payoutStats['pending_amount'], 2) }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-gray-600 text-sm">En Proceso</p>
                <p class="text-3xl font-bold text-gray-900">${{ number_format($payoutStats['processing_amount'], 2) }}</p>
            </div>
        </div>

        <!-- Filtros -->
        <div class="bg-white rounded-lg shadow p-6 mb-8">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Estado</label>
                    <select name="status" id="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="">Todos los estados</option>
                        <option value="pending" @selected(request('status') === 'pending')>Pendiente</option>
                        <option value="processing" @selected(request('status') === 'processing')>En Proceso</option>
                        <option value="completed" @selected(request('status') === 'completed')>Completado</option>
                        <option value="failed" @selected(request('status') === 'failed')>Fallido</option>
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
                    <a href="{{ route('seller.payouts') }}" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                        Limpiar
                    </a>
                </div>
            </form>
        </div>

        <!-- Tabla de Pagos -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Período</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Monto</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Comisiones</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Método</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Estado</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Fecha</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-700 uppercase">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @forelse($payouts as $payout)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    {{ $payout->period_start->format('d/m/Y') }} - {{ $payout->period_end->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4 text-sm font-semibold text-gray-900">${{ number_format($payout->amount, 2) }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $payout->commission_count }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ ucfirst(str_replace('_', ' ', $payout->payment_method)) }}</td>
                                <td class="px-6 py-4 text-sm">
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold
                                        @if($payout->status === 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($payout->status === 'processing') bg-blue-100 text-blue-800
                                        @elseif($payout->status === 'completed') bg-green-100 text-green-800
                                        @else bg-red-100 text-red-800
                                        @endif
                                    ">
                                        {{ ucfirst($payout->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    @if($payout->processed_at)
                                        {{ $payout->processed_at->format('d/m/Y') }}
                                    @else
                                        —
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <a href="{{ route('seller.payout.detail', $payout) }}" class="text-blue-600 hover:underline text-sm">
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
                                    No hay pagos
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            <div class="px-6 py-4 border-t">
                {{ $payouts->links() }}
            </div>
        </div>

        <!-- Nota de Información -->
        <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
            <div class="flex gap-4">
                <svg class="h-6 w-6 text-blue-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 5v8a2 2 0 01-2 2h-5l-5 4v-4H4a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2zm-11-1a1 1 0 11-2 0 1 1 0 012 0zM8 9a1 1 0 100-2 1 1 0 000 2zm5 0a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"></path>
                </svg>
                <div>
                    <h3 class="text-sm font-medium text-blue-900">Información de Pagos</h3>
                    <p class="text-sm text-blue-700 mt-1">Los pagos se procesan semanalmente. Las comisiones aprobadas se envían según tu método de pago seleccionado.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
