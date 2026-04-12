<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Get user dashboard data
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function dashboard()
    {
        $user = auth('api')->user();

        // Retrieve latest orders for the dashboard
        $latestOrders = $user->orders()->latest()->take(5)->get();
        $totalOrders = $user->orders()->count();

        return response()->json([
            'user' => $user,
            'statistics' => [
                'total_orders' => $totalOrders,
            ],
            'latest_orders' => $latestOrders
        ]);
    }

    /**
     * Get all orders of the user
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function orders(Request $request)
    {
        $user = auth('api')->user();
        $orders = $user->orders()->latest()->paginate(10);

        return response()->json($orders);
    }
}
