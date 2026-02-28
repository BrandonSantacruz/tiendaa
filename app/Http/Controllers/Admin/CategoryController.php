<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    /**
     * Constructor - Aplicar middleware
     */
    public function __construct()
    {
        $this->middleware('role:super-admin'); // Solo super admin puede gestionar categorías
    }

    /**
     * Mostrar listado de categorías
     */
    public function index()
    {
        $categories = Category::mainCategories()
            ->with('children')
            ->orderBy('name')
            ->paginate(15);

        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Mostrar formulario para crear categoría
     */
    public function create()
    {
        // Cargar solo categorías principales para asignar como padre
        $parentCategories = Category::mainCategories()
            ->active()
            ->pluck('name', 'id');

        return view('admin.categories.create', compact('parentCategories'));
    }

    /**
     * Guardar nueva categoría
     */
    public function store(Request $request)
    {
        // Validar datos
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id',
            'status' => 'required|in:active,inactive',
            'image' => 'nullable|image|max:2048',
        ]);

        // Procesar imagen si existe
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('categories', 'public');
            $validated['image'] = $path;
        }

        // Crear categoría
        Category::create($validated);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Categoría creada exitosamente.');
    }

    /**
     * Mostrar formulario para editar categoría
     */
    public function edit(Category $category)
    {
        // Cargar categorías disponibles como padres (excluir la actual y sus hijos)
        $parentCategories = Category::mainCategories()
            ->where('id', '!=', $category->id)
            ->active()
            ->pluck('name', 'id');

        return view('admin.categories.edit', compact('category', 'parentCategories'));
    }

    /**
     * Actualizar categoría
     */
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id',
            'status' => 'required|in:active,inactive',
            'image' => 'nullable|image|max:2048',
        ]);

        // Procesar imagen si existe
        if ($request->hasFile('image')) {
            // Eliminar imagen anterior si existe
            if ($category->image) {
                \Storage::disk('public')->delete($category->image);
            }
            $path = $request->file('image')->store('categories', 'public');
            $validated['image'] = $path;
        }

        // Actualizar categoría
        $category->update($validated);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Categoría actualizada exitosamente.');
    }

    /**
     * Eliminar categoría
     */
    public function destroy(Category $category)
    {
        // Validar que no tenga productos
        if ($category->products()->count() > 0) {
            return redirect()->route('admin.categories.index')
                ->with('error', 'No se puede eliminar una categoría con productos.');
        }

        // Eliminar imagen si existe
        if ($category->image) {
            \Storage::disk('public')->delete($category->image);
        }

        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Categoría eliminada exitosamente.');
    }
}
