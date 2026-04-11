<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::with(['brand'])->withCount('products')->latest()->paginate(15);
        return view('admin.categories.index', compact('categories'));
    }

    public function getCategoriesByBrands(Request $request)
    {
        $brandIds = $request->input('brand_ids', []);
        
        if (empty($brandIds)) {
            $categories = Category::select('id', 'name', 'brand_id')->get();
        } else {
            $categories = Category::whereIn('brand_id', $brandIds)->select('id', 'name', 'brand_id')->get();
        }
        
        return response()->json($categories);
    }

    public function create()
    {
        $brands = Brand::all();
        return view('admin.categories.create', compact('brands'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'brand_id' => 'nullable|exists:brands,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        Category::create($validated);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category created successfully.');
    }

    public function show(Category $category)
    {
        $category->load(['products', 'brand']);
        return view('admin.categories.show', compact('category'));
    }

    public function edit(Category $category)
    {
        $brands = Brand::all();
        return view('admin.categories.edit', compact('category', 'brands'));
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'brand_id' => 'nullable|exists:brands,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        $category->update($validated);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category deleted successfully.');
    }
}
