<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Wishlist;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;

class WishlistController extends Controller
{
    /**
     * Get all wishlist items for the authenticated user.
     */
    public function index()
    {
        $user = auth('api')->user();
        $wishlist = Wishlist::with('product')->where('user_id', $user->id)->get();

        return response()->json([
            'success' => true,
            'data' => $wishlist
        ]);
    }

    /**
     * Add a product to the wishlist.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $user = auth('api')->user();
        
        $wishlist = Wishlist::firstOrCreate([
            'user_id' => $user->id,
            'product_id' => $request->product_id
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Product added to wishlist successfully',
            'data' => $wishlist
        ], 201);
    }

    /**
     * Remove a product from the wishlist.
     */
    public function destroy($productId)
    {
        $user = auth('api')->user();
        
        $deleted = Wishlist::where('user_id', $user->id)
            ->where('product_id', $productId)
            ->delete();

        if ($deleted) {
            return response()->json([
                'success' => true,
                'message' => 'Product removed from wishlist successfully'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Product not found in your wishlist'
        ], 404);
    }
}
