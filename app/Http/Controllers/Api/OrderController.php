<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Enums\OrderStatus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    /**
     * Process a new order (Checkout).
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'shipping_address' => 'required|string',
            'payment_method' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $user = auth('api')->user();

        try {
            return DB::transaction(function () use ($request, $user) {
                $totalPrice = 0;
                $orderItems = [];

                foreach ($request->items as $item) {
                    $product = Product::lockForUpdate()->find($item['product_id']);

                    if ($product->stock < $item['quantity']) {
                        throw new \Exception("Product '{$product->name}' is out of stock or insufficient quantity.");
                    }

                    $unitPrice = $product->price;
                    $subTotal = $unitPrice * $item['quantity'];
                    $totalPrice += $subTotal;

                    $orderItems[] = [
                        'product_id' => $product->id,
                        'quantity' => $item['quantity'],
                        'unit_price' => $unitPrice,
                    ];

                    // Update stock
                    $product->decrement('stock', $item['quantity']);
                }

                $order = Order::create([
                    'user_id' => $user->id,
                    'total_price' => $totalPrice,
                    'status' => OrderStatus::PENDING,
                    'payment_status' => 'pending',
                    'payment_method' => $request->payment_method,
                    'shipping_address' => $request->shipping_address,
                ]);

                foreach ($orderItems as $orderItem) {
                    $orderItem['order_id'] = $order->id;
                    OrderItem::create($orderItem);
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Order processed successfully',
                    'data' => $order->load('items.product')
                ], 201);
            });
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Get details of a single order.
     */
    public function show($id)
    {
        $user = auth('api')->user();
        $order = Order::with('items.product')
            ->where('user_id', $user->id)
            ->find($id);

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $order
        ]);
    }

    /**
     * Cancel a pending order.
     */
    public function cancel($id)
    {
        $user = auth('api')->user();
        $order = Order::where('user_id', $user->id)
            ->find($id);

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found'
            ], 404);
        }

        if ($order->status !== OrderStatus::PENDING) {
            return response()->json([
                'success' => false,
                'message' => "Order cannot be cancelled as it is already {$order->status->value}"
            ], 400);
        }

        $order->update(['status' => OrderStatus::CANCELLED]);

        return response()->json([
            'success' => true,
            'message' => 'Order cancelled successfully',
            'data' => $order
        ]);
    }
}
