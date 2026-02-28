@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-2xl mx-auto px-4">
        <!-- Header -->
        <div class="mb-8">
            <a href="{{ route('seller.variants.index', $product) }}" class="text-blue-600 hover:text-blue-900 mb-4 inline-block">← Volver</a>
            <h1 class="text-3xl font-bold text-gray-900">Nueva Variante</h1>
            <p class="text-gray-600 mt-2">Crear variante para: <strong>{{ $product->name }}</strong></p>
        </div>

        <!-- Formulario -->
        <div class="bg-white rounded-lg shadow p-8">
            <form action="{{ route('seller.variants.store', $product) }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Información Básica -->
                <div class="mb-8">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Información Básica</h2>
                    
                    <div class="space-y-6">
                        <!-- Nombre -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nombre de la Variante *</label>
                            <input type="text" name="name" value="{{ old('name') }}" placeholder="Ej: Rojo - Talla M" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror">
                            <p class="text-sm text-gray-500 mt-1">Describa la variante (color, tamaño, etc)</p>
                            @error('name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- SKU -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">SKU *</label>
                            <input type="text" name="sku" value="{{ old('sku') }}" placeholder="Ej: PROD-RED-M" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('sku') border-red-500 @enderror">
                            @error('sku') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <!-- Atributos -->
                <div class="mb-8">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Atributos</h2>
                    <p class="text-gray-600 text-sm mb-4">Agregue los atributos de esta variante (color, tamaño, etc)</p>
                    
                    <div id="attributesContainer" class="space-y-3">
                        <!-- Los atributos se agregan dinámicamente con JavaScript -->
                    </div>

                    <button type="button" onclick="addAttribute()" class="mt-4 text-blue-600 hover:text-blue-900 text-sm font-medium">
                        <i class="fas fa-plus mr-1"></i> Agregar Atributo
                    </button>
                </div>

                <!-- Precios -->
                <div class="mb-8">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Precios</h2>
                    
                    <div class="grid grid-cols-2 gap-6">
                        <!-- Precio -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Precio *</label>
                            <input type="number" name="price" step="0.01" min="0" value="{{ old('price') }}" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('price') border-red-500 @enderror">
                            @error('price') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Precio Mayorista -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Precio Mayorista</label>
                            <input type="number" name="wholesale_price" step="0.01" min="0" value="{{ old('wholesale_price') }}" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('wholesale_price') border-red-500 @enderror">
                            @error('wholesale_price') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <!-- Stock -->
                <div class="mb-8">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Stock</h2>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Cantidad en Stock *</label>
                        <input type="number" name="stock" min="0" value="{{ old('stock', 0) }}" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('stock') border-red-500 @enderror">
                        @error('stock') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Imagen -->
                <div class="mb-8">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Imagen (Opcional)</h2>
                    
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center cursor-pointer hover:border-blue-500 transition" id="imageDropZone">
                        <i class="fas fa-image text-4xl text-gray-400 mb-3"></i>
                        <p class="text-gray-600 mb-2">Arrastra una imagen aquí o haz click</p>
                        <p class="text-sm text-gray-500">Máximo 2MB</p>
                        <input type="file" name="image" accept="image/*" class="hidden" id="imageInput">
                    </div>

                    <div id="imagePreview" class="mt-4"></div>
                    @error('image') <p class="text-red-500 text-sm mt-2">{{ $message }}</p> @enderror
                </div>

                <!-- Estado -->
                <div class="mb-8">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Estado</h2>
                    
                    <select name="status" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('status') border-red-500 @enderror">
                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Activo</option>
                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactivo</option>
                    </select>
                    @error('status') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Botones -->
                <div class="flex gap-4">
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                        <i class="fas fa-save mr-2"></i> Crear Variante
                    </button>
                    <a href="{{ route('seller.variants.index', $product) }}" class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Scripts para manejo dinámico de atributos e imágenes -->
<script>
    let attributeCount = 0;

    function addAttribute() {
        const container = document.getElementById('attributesContainer');
        const div = document.createElement('div');
        div.className = 'flex gap-2';
        div.innerHTML = `
            <input type="text" name="attributes_key[]" placeholder="Nombre del atributo (Ej: Color)" class="flex-1 px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
            <input type="text" name="attributes_value[]" placeholder="Valor (Ej: Rojo)" class="flex-1 px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
            <button type="button" onclick="this.parentElement.remove()" class="text-red-600 hover:text-red-900">
                <i class="fas fa-trash"></i>
            </button>
        `;
        container.appendChild(div);
        attributeCount++;
    }

    // Convertir atributos al formato JSON
    document.querySelector('form').addEventListener('submit', function(e) {
        const keys = document.querySelectorAll('input[name="attributes_key[]"]');
        const values = document.querySelectorAll('input[name="attributes_value[]"]');
        
        const attributes = {};
        keys.forEach((key, index) => {
            if (key.value) {
                attributes[key.value] = values[index].value;
            }
        });

        // Crear input oculto con JSON
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'attributes';
        input.value = JSON.stringify(attributes);
        this.appendChild(input);

        // Eliminar inputs temporales
        keys.forEach(key => key.remove());
        values.forEach(value => value.remove());
    });

    // Manejo de imagen
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
        const file = imageInput.files[0];

        if (file) {
            const reader = new FileReader();
            reader.onload = (e) => {
                const div = document.createElement('div');
                div.className = 'relative bg-gray-100 rounded-lg overflow-hidden';
                div.innerHTML = `
                    <img src="${e.target.result}" alt="Preview" class="w-full h-48 object-cover">
                    <p class="text-sm text-gray-600 p-2 text-center">${file.name}</p>
                `;
                imagePreview.appendChild(div);
            };
            reader.readAsDataURL(file);
        }
    }
</script>
@endsection
