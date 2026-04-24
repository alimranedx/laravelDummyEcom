<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $per_page = $request->input('per_page', 10);
        $q = $request->input('q');
        $date_range = $request->input('date_range');
        
        $query = Order::with('user')->latest();
        
        if (!empty($date_range)) {
            [$from_date, $to_date] = (new \App\Common\Services\Utility\DateTime)->gateFormatedDateFromDateRange($date_range);
            if (!empty($from_date) && !empty($to_date)) {
                $query->whereBetween('created_at', [$from_date, $to_date]);
            }
        }
        
        if (!empty($q)) {
            $query->where(function ($qBuilder) use ($q) {
                $qBuilder->where('id', 'like', '%' . $q . '%')
                      ->orWhereHas('user', function($userQuery) use ($q) {
                          $userQuery->where('name', 'like', '%' . $q . '%');
                      });
            });
        }
        
        $orders = $query->paginate($per_page)->withQueryString();
        $route = route('admin.orders.index');
        
        return view('admin.orders.index', compact('orders', 'route', 'per_page'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'items.product']);
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled,refunded',
        ]);

        $order->update($validated);

        return redirect()->route('admin.orders.show', $order)
            ->with('success', 'Order status updated successfully.');
    }
}
