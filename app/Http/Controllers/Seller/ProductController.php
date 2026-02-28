<?php

namespace App\Http\Controllers\Seller;

use App\Models\Product;
use App\Models\Category;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    /**
     * Constructor - Aplicar middleware
     */
    public function __construct()
    {
        // Los vendedores solo pueden ver y gestionar sus propios productos
        $this->middleware('role:vendedor,super-admin');
    }

    /**
     * Mostrar listado de productos del vendedor
     */
    public function index()
    {
        $user = Auth::user();
        
        // Si es super admin, mostrar todos; si es vendedor, solo sus productos
        $query = $user->hasRole('super-admin') 
            ? Product::query() 
            : Product::where('vendor_id', $user->id);

        $products = $query->with(['category', 'vendor', 'images'])
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('seller.products.index', compact('products'));
    }

    /**
     * Mostrar formulario para crear producto
     */
    public function create()
    {
        $categories = Category::active()->pluck('name', 'id');
        
        return view('seller.products.create', compact('categories'));
    }

    /**
     * Guardar nuevo producto
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|unique:products',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'wholesale_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'type' => 'required|in:simple,variable',
            'status' => 'required|in:active,inactive',
            'images.*' => 'image|max:2048',
        ]);

        // Asignar vendedor
        $validated['vendor_id'] = Auth::id();

        // Crear producto
        $product = Product::create($validated);

        // Procesar imágenes
        if ($request->hasFile('images')) {
            $this->storeImages($product, $request->file('images'));
        }

        return redirect()->route('seller.products.show', $product)
            ->with('success', 'Producto creado exitosamente.');
    }

    /**
     * Mostrar detalle del producto
     */
    public function show(Product $product)
    {
        // Autorización: solo el vendedor o super admin pueden ver
        $this->authorize('view', $product);

        $product->load(['category', 'vendor', 'images', 'variants']);

        return view('seller.products.show', compact('product'));
    }

    /**
     * Mostrar formulario para editar producto
     */
    public function edit(Product $product)
    {
        // Autorización
        $this->authorize('update', $product);

        $categories = Category::active()->pluck('name', 'id');
        $product->load('images');

        return view('seller.products.edit', compact('product', 'categories'));
    }

    /**
     * Actualizar producto
     */
    public function update(Request $request, Product $product)
    {
        // Autorización
        $this->authorize('update', $product);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|unique:products,sku,' . $product->id,
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'wholesale_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'type' => 'required|in:simple,variable',
            'status' => 'required|in:active,inactive',
            'images.*' => 'image|max:2048',
        ]);

        // Actualizar producto
        $product->update($validated);

        // Procesar nuevas imágenes
        if ($request->hasFile('images')) {
            $this->storeImages($product, $request->file('images'));
        }

        return redirect()->route('seller.products.show', $product)
            ->with('success', 'Producto actualizado exitosamente.');
    }

    /**
     * Eliminar producto
     */
    public function destroy(Product $product)
    {
        // Autorización
        $this->authorize('delete', $product);

        // Eliminar imágenes
        foreach ($product->images as $image) {
            \Storage::disk('public')->delete($image->image_path);
        }

        $product->delete();

        return redirect()->route('seller.products.index')
            ->with('success', 'Producto eliminado exitosamente.');
    }

    /**
     * Eliminar imagen de producto
     */
    public function deleteImage(ProductImage $image)
    {
        $product = $image->product;
        
        // Autorización
        $this->authorize('update', $product);

        // Eliminar archivo
        \Storage::disk('public')->delete($image->image_path);
        
        $image->delete();

        return response()->json(['message' => 'Imagen eliminada']);
    }

    /**
     * Guardar imágenes del producto
     */
    private function storeImages(Product $product, array $images)
    {
        foreach ($images as $key => $image) {
            $path = $image->store('products', 'public');
            
            ProductImage::create([
                'product_id' => $product->id,
                'image_path' => $path,
                'is_primary' => $key === 0, // Primera imagen como principal
                'sort_order' => $key,
            ]);
        }
    }
}
