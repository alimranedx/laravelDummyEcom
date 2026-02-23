<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Enums\OrderStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $recentOrders = $user->orders()
            ->latest()
            ->take(5)
            ->get();

        $stats = [
            'total_orders' => $user->orders()->count(),
            'pending_orders' => $user->orders()->where('status', OrderStatus::PENDING)->count(),
            'completed_orders' => $user->orders()->where('status', OrderStatus::COMPLETED)->count(),
        ];

        return view('dashboard', compact('user', 'recentOrders', 'stats'));
    }
}
