<?php

namespace App\Http\Controllers\Admin;

use App\Common\Services\ProductService;
use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $data = [
            'page_title' => 'Products',
            'base_page' => 'Dashboard',
            'breadcrum_navigator' => 'Products',
        ];
        $data['brands'] = Brand::all();
        $data['categories'] = Category::all();
        $data['route'] = route('admin.products.index');
        $data['per_page'] = $request->input('per_page', 10);
        $productServiceObj = new ProductService;
        $data['filterData'] = $productServiceObj->prepareFilters($request->all());
        $data['products'] = $productServiceObj->getByFilters($data['filterData']);

        return view('admin.products.index', $data);
    }

    public function create()
    {
        $categories = Category::all();
        $brands = Brand::all();

        return view('admin.products.create', compact('categories', 'brands'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'nullable|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'images' => 'nullable|array|max:10',
            'images.*' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        $product = Product::create($validated);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('products', 'public');
                
                // Set the first image as the primary image for the product
                if ($index === 0) {
                    $product->update(['image_path' => $path]);
                }

                $product->images()->create([
                    'image_path' => $path,
                ]);
            }
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Product created successfully.');
    }

    public function show(Product $product)
    {
        $product->load(['category', 'brand']);

        return view('admin.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        $brands = Brand::all();

        return view('admin.products.edit', compact('product', 'categories', 'brands'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'category_id' => 'nullable|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'images' => 'nullable|array|max:10',
            'images.*' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'deleted_images' => 'nullable|array',
            'deleted_images.*' => 'exists:product_images,id'
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        $product->update($validated);

        // Handle deleted images
        if ($request->has('deleted_images')) {
            foreach ($request->input('deleted_images') as $imageId) {
                $image = $product->images()->find($imageId);
                if ($image) {
                    if ($image->image_path) {
                        \Illuminate\Support\Facades\Storage::disk('public')->delete($image->image_path);
                    }
                    $image->delete();
                }
            }
        }

        // Handle new images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');
                $product->images()->create([
                    'image_path' => $path,
                ]);
            }
        }

        // Update primary image if empty and we have images
        if (!$product->image_path && $product->images()->exists()) {
            $product->update(['image_path' => $product->images()->first()->image_path]);
        } else if ($product->image_path && !$product->images()->where('image_path', $product->image_path)->exists() && $product->images()->exists()) {
            // If primary image was deleted from gallery, set another one as primary
            $product->update(['image_path' => $product->images()->first()->image_path]);
        } else if (!$product->images()->exists()) {
            // No images left
            $product->update(['image_path' => null]);
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        // Delete related images from storage
        foreach ($product->images as $image) {
            if ($image->image_path) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($image->image_path);
            }
        }
        
        // Delete related image records
        $product->images()->delete();

        // Delete primary image from storage
        if ($product->image_path) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($product->image_path);
        }

        // Force delete to ensure complete removal from DB
        $product->forceDelete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully.');
    }
}
