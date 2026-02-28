@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Panel del Vendedor</h1>
            <p class="text-gray-600 mt-2">Bienvenido {{ auth()->user()->name }}, aquí puedes gestionar tu negocio</p>
        </div>

        <!-- Estadísticas principales -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total de Productos -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-medium">Productos</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['total_products'] }}</p>
                    </div>
                    <div class="bg-blue-100 rounded-full p-3">
                        <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Ventas Totales -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-medium">Ventas Totales</p>
                        <p class="text-3xl font-bold text-gray-900">${{ number_format($stats['total_sales'], 2) }}</p>
                    </div>
                    <div class="bg-green-100 rounded-full p-3">
                        <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Comisiones Pendientes -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-medium">Comisiones Pendientes</p>
                        <p class="text-3xl font-bold text-gray-900">${{ number_format($stats['pending_commissions'], 2) }}</p>
                    </div>
                    <div class="bg-yellow-100 rounded-full p-3">
                        <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Ingresos del Mes -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-medium">Este Mes</p>
                        <p class="text-3xl font-bold text-gray-900">${{ number_format($stats['monthly_earnings'], 2) }}</p>
                    </div>
                    <div class="bg-purple-100 rounded-full p-3">
                        <svg class="h-6 w-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detalles adicionales -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Información de Comisiones</h3>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Tasa de Comisión:</span>
                        <span class="font-semibold text-gray-900">{{ $stats['commission_rate'] }}%</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Comisiones Aprobadas:</span>
                        <span class="font-semibold text-gray-900">${{ number_format($stats['approved_commissions'], 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Comisiones Pagadas:</span>
                        <span class="font-semibold text-gray-900">${{ number_format($stats['paid_commissions'], 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Total de Ingresos:</span>
                        <span class="font-semibold text-gray-900">${{ number_format($stats['total_commissions'], 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Acciones Rápidas</h3>
                <div class="space-y-2">
                    <a href="{{ route('products.create') }}" class="block w-full px-4 py-2 bg-blue-600 text-white text-center rounded-lg hover:bg-blue-700 transition">
                        Agregar Producto
                    </a>
                    <a href="{{ route('seller.commissions') }}" class="block w-full px-4 py-2 border border-gray-300 text-gray-700 text-center rounded-lg hover:bg-gray-50 transition">
                        Ver Comisiones
                    </a>
                    <a href="{{ route('seller.payouts') }}" class="block w-full px-4 py-2 border border-gray-300 text-gray-700 text-center rounded-lg hover:bg-gray-50 transition">
                        Ver Pagos
                    </a>
                </div>
            </div>

            <!-- Información de Cuenta -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Mi Cuenta</h3>
                <div class="space-y-3 text-sm">
                    <div>
                        <span class="text-gray-600">Empresa:</span>
                        <p class="font-semibold text-gray-900">{{ auth()->user()->company_name }}</p>
                    </div>
                    <div>
                        <span class="text-gray-600">Email:</span>
                        <p class="font-semibold text-gray-900">{{ auth()->user()->email }}</p>
                    </div>
                    <a href="{{ route('seller.profile') }}" class="inline-block text-blue-600 hover:underline text-sm font-medium">
                        Editar Perfil →
                    </a>
                </div>
            </div>
        </div>

        <!-- Gráfico de ingresos -->
        <div class="bg-white rounded-lg shadow p-6 mb-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">Ingresos por Mes (últimos 12 meses)</h3>
            <div class="overflow-x-auto">
                <div class="flex gap-4 pb-4">
                    @foreach($monthlyEarnings as $data)
                        <div class="flex-shrink-0 flex flex-col items-center">
                            <div class="h-32 w-12 bg-gray-100 rounded-t-lg flex items-end overflow-hidden">
                                <div 
                                    class="w-full bg-blue-600 rounded-t-lg transition-all duration-300" 
                                    style="height: {{ min(($data['earnings'] / 1000) * 100, 100) }}%"
                                    title="${{ number_format($data['earnings'], 2) }}"
                                ></div>
                            </div>
                            <p class="text-xs text-gray-600 mt-2 text-center">{{ $data['month'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Comisiones recientes -->
        <div class="bg-white rounded-lg shadow overflow-hidden mb-8">
            <div class="px-6 py-4 border-b flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-900">Comisiones Recientes</h3>
                <a href="{{ route('seller.commissions') }}" class="text-blue-600 hover:underline text-sm">Ver todas →</a>
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
                        @forelse($recentCommissions as $commission)
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
                                    No hay comisiones aún
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagos recientes -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-900">Pagos Recientes</h3>
                <a href="{{ route('seller.payouts') }}" class="text-blue-600 hover:underline text-sm">Ver todos →</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Período</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Monto</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Comisiones</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Método</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Estado</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @forelse($recentPayouts as $payout)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $payout->period_start->format('d/m/Y') }} - {{ $payout->period_end->format('d/m/Y') }}</td>
                                <td class="px-6 py-4 text-sm font-semibold text-gray-900">${{ number_format($payout->amount, 2) }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $payout->commission_count }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ ucfirst($payout->payment_method) }}</td>
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
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                    No hay pagos aún
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
