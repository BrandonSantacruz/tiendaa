@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4">
        <!-- Breadcrumb -->
        <div class="mb-6">
            <a href="{{ route('seller.products.index') }}" class="text-blue-600 hover:text-blue-900">← Volver a Productos</a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Imágenes -->
            <div class="md:col-span-1">
                <div class="bg-white rounded-lg shadow p-4">
                    @if ($product->mainImage)
                        <img src="{{ asset('storage/' . $product->mainImage) }}" alt="{{ $product->name }}" class="w-full h-80 object-cover rounded-lg mb-4">
                    @else
                        <div class="w-full h-80 bg-gray-200 rounded-lg mb-4 flex items-center justify-center">
                            <i class="fas fa-image text-gray-400 text-4xl"></i>
                        </div>
                    @endif

                    <!-- Galería de miniaturas -->
                    @if ($product->images->count() > 0)
                        <div class="grid grid-cols-4 gap-2">
                            @foreach ($product->images as $image)
                                <img src="{{ asset('storage/' . $image->image_path) }}" alt="" class="w-full h-20 object-cover rounded cursor-pointer hover:opacity-80">
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- Información del Producto -->
            <div class="md:col-span-2">
                <!-- Información Básica -->
                <div class="bg-white rounded-lg shadow p-6 mb-6">
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $product->name }}</h1>
                    
                    <div class="flex items-center gap-4 mb-4">
                        <span class="inline-block px-3 py-1 rounded-full text-sm font-medium {{ $product->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ ucfirst($product->status) }}
                        </span>
                        <span class="inline-block px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                            {{ $product->type === 'simple' ? 'Producto Simple' : 'Producto Variable' }}
                        </span>
                    </div>

                    <!-- Detalles -->
                    <div class="grid grid-cols-2 gap-4 mb-6 border-t border-b border-gray-200 py-4">
                        <div>
                            <p class="text-sm text-gray-600">SKU</p>
                            <p class="font-bold text-gray-900">{{ $product->sku }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Categoría</p>
                            <p class="font-bold text-gray-900">{{ $product->category->name ?? 'N/A' }}</p>
                        </div>
                    </div>

                    <!-- Precios -->
                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div class="p-4 bg-blue-50 rounded-lg">
                            <p class="text-sm text-gray-600">Precio Público</p>
                            <p class="text-2xl font-bold text-blue-600">${{ number_format($product->price, 2) }}</p>
                        </div>
                        @if ($product->wholesale_price)
                            <div class="p-4 bg-green-50 rounded-lg">
                                <p class="text-sm text-gray-600">Precio Mayorista</p>
                                <p class="text-2xl font-bold text-green-600">${{ number_format($product->wholesale_price, 2) }}</p>
                            </div>
                        @endif
                    </div>

                    <!-- Stock -->
                    <div class="p-4 {{ $product->stock > 0 ? 'bg-green-50' : 'bg-red-50' }} rounded-lg mb-6">
                        <p class="text-sm {{ $product->stock > 0 ? 'text-green-600' : 'text-red-600' }}">Stock Disponible</p>
                        <p class="text-2xl font-bold {{ $product->stock > 0 ? 'text-green-700' : 'text-red-700' }}">{{ $product->stock }} unidades</p>
                    </div>

                    <!-- Descripción -->
                    <div class="mb-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-2">Descripción</h3>
                        <p class="text-gray-700">{{ $product->description }}</p>
                    </div>

                    <!-- Acciones -->
                    <div class="flex gap-4">
                        <a href="{{ route('seller.products.edit', $product) }}" class="flex-1 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition text-center">
                            <i class="fas fa-edit mr-2"></i> Editar
                        </a>
                        <form action="{{ route('seller.products.destroy', $product) }}" method="POST" class="flex-1" onsubmit="return confirm('¿Confirmar eliminación?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="w-full bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition">
                                <i class="fas fa-trash mr-2"></i> Eliminar
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Variantes (si es producto variable) -->
                @if ($product->isVariable())
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-bold text-gray-900">Variantes</h3>
                            <a href="{{ route('seller.variants.create', $product) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition text-sm">
                                <i class="fas fa-plus mr-1"></i> Nueva Variante
                            </a>
                        </div>

                        @if ($product->variants->count() > 0)
                            <div class="space-y-2">
                                @foreach ($product->variants as $variant)
                                    <div class="border rounded-lg p-3 hover:bg-gray-50 transition flex justify-between items-center">
                                        <div>
                                            <p class="font-medium text-gray-900">{{ $variant->name }}</p>
                                            <p class="text-sm text-gray-600">SKU: {{ $variant->sku }} | Stock: {{ $variant->stock }}</p>
                                        </div>
                                        <div class="flex gap-2">
                                            <a href="{{ route('seller.variants.edit', [$product, $variant]) }}" class="text-blue-600 hover:text-blue-900">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('seller.variants.destroy', [$product, $variant]) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar variante?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-600 text-center py-8">No hay variantes creadas aún</p>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
