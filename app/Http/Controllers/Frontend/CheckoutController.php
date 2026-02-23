<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('home')->with('error', 'Your cart is empty!');
        }
        return view('checkout.index', compact('cart'));
    }

    public function placeOrder(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'phone' => 'required',
            'address' => 'required',
            'payment_method' => 'required',
        ]);

        $cart = session()->get('cart', []);

        DB::beginTransaction();
        try {
            $order = Order::create([
                'user_id' => auth()->id(), // can be null for guests
                'total_price' => array_reduce($cart, function ($total, $item) {
                    return $total + ($item['price'] * $item['quantity']);
                }, 0),
                'status' => 'pending',
                'payment_status' => 'pending',
                'payment_method' => $request->payment_method,
                'shipping_address' => $request->address . ' | Phone: ' . $request->phone,
            ]);

            foreach ($cart as $id => $details) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $id,
                    'quantity' => $details['quantity'],
                    'unit_price' => $details['price'],
                ]);
            }

            DB::commit();
            session()->forget('cart');

            return redirect()->route('home')->with('success', 'Order placed successfully! Order ID: ' . $order->id);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }
}
