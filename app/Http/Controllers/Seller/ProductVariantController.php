<?php

namespace App\Http\Controllers\Seller;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductVariantController extends Controller
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->middleware('role:vendedor,super-admin');
    }

    /**
     * Mostrar listado de variantes del producto
     */
    public function index(Product $product)
    {
        // Autorización
        $this->authorize('update', $product);

        $variants = $product->variants()->paginate(10);

        return view('seller.variants.index', compact('product', 'variants'));
    }

    /**
     * Mostrar formulario para crear variante
     */
    public function create(Product $product)
    {
        // Solo productos tipo "variable" pueden tener variantes
        if (!$product->isVariable()) {
            return redirect()->route('seller.products.show', $product)
                ->with('error', 'Solo los productos variables pueden tener variantes.');
        }

        $this->authorize('update', $product);

        return view('seller.variants.create', compact('product'));
    }

    /**
     * Guardar nueva variante
     */
    public function store(Request $request, Product $product)
    {
        // Autorización
        $this->authorize('update', $product);

        // Validar
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|unique:product_variants',
            'price' => 'required|numeric|min:0',
            'wholesale_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'attributes' => 'required|json', // JSON con atributos
            'status' => 'required|in:active,inactive',
            'image' => 'nullable|image|max:2048',
        ]);

        // Procesar imagen si existe
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('variants', 'public');
            $validated['image_path'] = $path;
        }

        // Crear variante
        $product->variants()->create($validated);

        return redirect()->route('seller.variants.index', $product)
            ->with('success', 'Variante creada exitosamente.');
    }

    /**
     * Mostrar formulario para editar variante
     */
    public function edit(Product $product, ProductVariant $variant)
    {
        // Validar que la variante pertenece al producto
        if ($variant->product_id !== $product->id) {
            abort(404);
        }

        $this->authorize('update', $product);

        return view('seller.variants.edit', compact('product', 'variant'));
    }

    /**
     * Actualizar variante
     */
    public function update(Request $request, Product $product, ProductVariant $variant)
    {
        // Autorización
        if ($variant->product_id !== $product->id) {
            abort(404);
        }
        $this->authorize('update', $product);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|unique:product_variants,sku,' . $variant->id,
            'price' => 'required|numeric|min:0',
            'wholesale_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'attributes' => 'required|json',
            'status' => 'required|in:active,inactive',
            'image' => 'nullable|image|max:2048',
        ]);

        // Procesar imagen si existe
        if ($request->hasFile('image')) {
            if ($variant->image_path) {
                \Storage::disk('public')->delete($variant->image_path);
            }
            $path = $request->file('image')->store('variants', 'public');
            $validated['image_path'] = $path;
        }

        // Actualizar
        $variant->update($validated);

        return redirect()->route('seller.variants.index', $product)
            ->with('success', 'Variante actualizada exitosamente.');
    }

    /**
     * Eliminar variante
     */
    public function destroy(Product $product, ProductVariant $variant)
    {
        // Autorización
        if ($variant->product_id !== $product->id) {
            abort(404);
        }
        $this->authorize('update', $product);

        // Eliminar imagen
        if ($variant->image_path) {
            \Storage::disk('public')->delete($variant->image_path);
        }

        $variant->delete();

        return redirect()->route('seller.variants.index', $product)
            ->with('success', 'Variante eliminada exitosamente.');
    }
}
