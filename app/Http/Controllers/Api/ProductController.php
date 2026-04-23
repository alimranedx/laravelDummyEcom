<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Get a paginated list of products
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $query = Product::query()->with(['category', 'brand', 'images']);

        // Optional basic search filter
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
        }

        // Only return products that are active or available
        if (\Illuminate\Support\Facades\Schema::hasColumn('products', 'status')) {
            $query->where('status', 'active'); // Example status column config
        }

        $perPage = $request->get('per_page', 12);
        
        // Safety cap to prevent server overload
        $perPage = min((int)$perPage, 100);

        $products = $query->latest()->paginate($perPage);


        return response()->json($products);
    }

    /**
     * Get single product details
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $product = Product::with(['category', 'brand', 'images'])->find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        return response()->json($product);
    }
}
