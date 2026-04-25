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
     * Handles both authenticated users and guests.
     */
    public function store(Request $request)
    {
        $user = auth('api')->user();

        $rules = [
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'shipping_address' => 'required|string',
            'payment_method' => 'required|string',
        ];

        // If guest, require phone number
        if (!$user) {
            $rules['guest_phone'] = 'required|string|min:7|max:20';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

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
                    'user_id' => $user ? $user->id : null,
                    'guest_phone' => $user ? null : $request->guest_phone,
                    'payment_id' => Order::generateUniquePaymentId(),
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

                $message = $user ? 'Order processed successfully' : 'Order placed successfully! We will contact you on ' . $request->guest_phone . ' to confirm delivery.';

                return response()->json([
                    'success' => true,
                    'message' => $message,
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
     * Track order by payment_id (Public).
     */
    public function track($payment_id)
    {
        $order = Order::with('items.product')
            ->where('payment_id', $payment_id)
            ->first();

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'No order found with this Payment ID'
            ], 404);
        }

        // Clean up data for public tracking if necessary (optional)
        // $order->makeHidden(['user_id', 'shipping_address']); 

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
        
        try {
            return DB::transaction(function () use ($user, $id) {
                $order = Order::with('items.product')
                    ->where('user_id', $user->id)
                    ->lockForUpdate()
                    ->find($id);

                if (!$order) {
                    throw new \Exception('Order not found', 404);
                }

                if ($order->status !== OrderStatus::PENDING) {
                    throw new \Exception("Order cannot be cancelled as it is already {$order->status->value}", 400);
                }

                // Update order status
                $order->update(['status' => OrderStatus::CANCELLED]);

                // Restore stock for each item
                foreach ($order->items as $item) {
                    if ($item->product) {
                        $item->product->increment('stock', $item->quantity);
                    }
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Order cancelled successfully and stock restored',
                    'data' => $order->fresh('items.product')
                ]);
            });
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], $e->getCode() ?: 400);
        }
    }
}
