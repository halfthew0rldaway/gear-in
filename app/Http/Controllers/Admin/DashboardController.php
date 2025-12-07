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
            'orders_this_month' => Order::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
            'total_orders' => Order::count(),
            'pending_orders' => Order::where('status', Order::STATUS_PENDING)->count(),
            'products' => Product::count(),
            'active_products' => Product::where('is_active', true)->count(),
            'low_stock_products' => Product::where('stock', '<', 10)->where('stock', '>', 0)->count(),
            'out_of_stock' => Product::where('stock', 0)->count(),
            'customers' => User::where('role', User::ROLE_CUSTOMER)->count(),
            'revenue' => Order::where('status', '!=', Order::STATUS_CANCELLED)->sum('total'),
            'revenue_this_month' => Order::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->where('status', '!=', Order::STATUS_CANCELLED)
                ->sum('total'),
        ];

        $recentOrders = Order::latest()->with('user')->take(5)->get();
        $lowStockProducts = Product::where('stock', '<', 10)->where('stock', '>', 0)->latest()->take(5)->get();

        // Chart data - Last 7 days
        $chartData = [];
        $chartLabels = [];
        $chartRevenue = [];
        $chartOrders = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $chartLabels[] = $date->format('d M');
            
            $dayRevenue = Order::whereDate('created_at', $date->toDateString())
                ->where('status', '!=', Order::STATUS_CANCELLED)
                ->sum('total');
            $chartRevenue[] = (float) $dayRevenue;
            
            $dayOrders = Order::whereDate('created_at', $date->toDateString())->count();
            $chartOrders[] = $dayOrders;
        }

        // Monthly revenue for last 6 months
        $monthlyLabels = [];
        $monthlyRevenue = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthlyLabels[] = $date->format('M Y');
            
            $monthRevenue = Order::whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->where('status', '!=', Order::STATUS_CANCELLED)
                ->sum('total');
            $monthlyRevenue[] = (float) $monthRevenue;
        }

        return view('admin.dashboard', compact(
            'stats', 
            'recentOrders', 
            'lowStockProducts',
            'chartLabels',
            'chartRevenue',
            'chartOrders',
            'monthlyLabels',
            'monthlyRevenue'
        ));
    }
}
