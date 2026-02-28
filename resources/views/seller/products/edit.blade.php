@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Editar Producto</h1>
            <p class="text-gray-600 mt-2">Actualiza la información del producto</p>
        </div>

        <!-- Formulario -->
        <div class="bg-white rounded-lg shadow p-8">
            <form action="{{ route('seller.products.update', $product) }}" method="POST" enctype="multipart/form-data">
                @csrf @method('PUT')

                <!-- Información Básica -->
                <div class="mb-8">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Información Básica</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Nombre -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nombre del Producto *</label>
                            <input type="text" name="name" value="{{ old('name', $product->name) }}" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror">
                            @error('name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- SKU -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">SKU *</label>
                            <input type="text" name="sku" value="{{ old('sku', $product->sku) }}" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('sku') border-red-500 @enderror">
                            @error('sku') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Categoría -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Categoría *</label>
                            <select name="category_id" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('category_id') border-red-500 @enderror">
                                <option value="">Seleccionar categoría</option>
                                @foreach ($categories as $id => $name)
                                    <option value="{{ $id }}" {{ old('category_id', $product->category_id) == $id ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                            @error('category_id') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Tipo de Producto -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tipo *</label>
                            <select name="type" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('type') border-red-500 @enderror">
                                <option value="simple" {{ old('type', $product->type) == 'simple' ? 'selected' : '' }}>Simple</option>
                                <option value="variable" {{ old('type', $product->type) == 'variable' ? 'selected' : '' }}>Variable (con variantes)</option>
                            </select>
                            @error('type') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <!-- Descripción -->
                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Descripción *</label>
                        <textarea name="description" rows="4" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('description') border-red-500 @enderror">{{ old('description', $product->description) }}</textarea>
                        @error('description') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Precios e Inventario -->
                <div class="mb-8">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Precios e Inventario</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Precio Público -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Precio Público *</label>
                            <input type="number" name="price" step="0.01" min="0" value="{{ old('price', $product->price) }}" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('price') border-red-500 @enderror">
                            @error('price') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Precio Mayorista -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Precio Mayorista</label>
                            <input type="number" name="wholesale_price" step="0.01" min="0" value="{{ old('wholesale_price', $product->wholesale_price) }}" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('wholesale_price') border-red-500 @enderror">
                            @error('wholesale_price') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Stock -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Stock *</label>
                            <input type="number" name="stock" min="0" value="{{ old('stock', $product->stock) }}" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('stock') border-red-500 @enderror">
                            @error('stock') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <!-- Imágenes Actuales -->
                @if ($product->images->count() > 0)
                    <div class="mb-8">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">Imágenes Actuales</h2>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            @foreach ($product->images as $image)
                                <div class="relative bg-gray-100 rounded-lg overflow-hidden group">
                                    <img src="{{ asset('storage/' . $image->image_path) }}" alt="" class="w-full h-32 object-cover">
                                    <button type="button" onclick="deleteImage({{ $image->id }}, event)" class="absolute top-1 right-1 bg-red-500 text-white p-1 rounded opacity-0 group-hover:opacity-100 transition">
                                        <i class="fas fa-trash text-sm"></i>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Agregar Nuevas Imágenes -->
                <div class="mb-8">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Agregar Nuevas Imágenes</h2>
                    
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center cursor-pointer hover:border-blue-500 transition" id="imageDropZone">
                        <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-3"></i>
                        <p class="text-gray-600 mb-2">Arrastra imágenes aquí o haz click para seleccionar</p>
                        <p class="text-sm text-gray-500">Máximo 2MB por imagen</p>
                        <input type="file" name="images[]" multiple accept="image/*" class="hidden" id="imageInput">
                    </div>

                    <div id="imagePreview" class="mt-4 grid grid-cols-2 md:grid-cols-4 gap-4"></div>
                    @error('images') <p class="text-red-500 text-sm mt-2">{{ $message }}</p> @enderror
                </div>

                <!-- Estado -->
                <div class="mb-8">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Estado</h2>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Estado del Producto *</label>
                        <select name="status" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('status') border-red-500 @enderror">
                            <option value="active" {{ old('status', $product->status) == 'active' ? 'selected' : '' }}>Activo</option>
                            <option value="inactive" {{ old('status', $product->status) == 'inactive' ? 'selected' : '' }}>Inactivo</option>
                        </select>
                        @error('status') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Botones -->
                <div class="flex gap-4">
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                        <i class="fas fa-save mr-2"></i> Guardar Cambios
                    </button>
                    <a href="{{ route('seller.products.show', $product) }}" class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Scripts -->
<script>
    const dropZone = document.getElementById('imageDropZone');
    const imageInput = document.getElementById('imageInput');
    const imagePreview = document.getElementById('imagePreview');

    dropZone.addEventListener('click', () => imageInput.click());

    dropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropZone.classList.add('border-blue-500', 'bg-blue-50');
    });

    dropZone.addEventListener('dragleave', () => {
        dropZone.classList.remove('border-blue-500', 'bg-blue-50');
    });

    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropZone.classList.remove('border-blue-500', 'bg-blue-50');
        imageInput.files = e.dataTransfer.files;
        handleImagePreview();
    });

    imageInput.addEventListener('change', handleImagePreview);

    function handleImagePreview() {
        imagePreview.innerHTML = '';
        const files = Array.from(imageInput.files);

        files.forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = (e) => {
                const div = document.createElement('div');
                div.className = 'relative bg-gray-100 rounded-lg overflow-hidden';
                div.innerHTML = `
                    <img src="${e.target.result}" alt="Preview" class="w-full h-32 object-cover">
                    <p class="text-xs text-gray-600 p-2 text-center">${file.name}</p>
                `;
                imagePreview.appendChild(div);
            };
            reader.readAsDataURL(file);
        });
    }

    function deleteImage(imageId, event) {
        event.preventDefault();
        if (confirm('¿Eliminar esta imagen?')) {
            fetch(`/seller/products/images/${imageId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            }).then(response => location.reload());
        }
    }
</script>
@endsection
