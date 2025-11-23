<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'orders_today' => Order::whereDate('created_at', now()->toDateString())->count(),
            'total_orders' => Order::count(),
            'products' => Product::count(),
            'customers' => User::where('role', User::ROLE_CUSTOMER)->count(),
            'revenue' => Order::sum('total'),
        ];

        $recentOrders = Order::latest()->with('user')->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentOrders'));
    }
}
