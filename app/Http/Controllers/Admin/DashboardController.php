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

    public function printable(): View
    {
        $stats = [
            'orders_today' => Order::whereDate('created_at', now()->toDateString())->count(),
            'orders_this_month' => Order::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
            'total_orders' => Order::count(),
            'pending_orders' => Order::where('status', Order::STATUS_PENDING)->count(),
            'paid_orders' => Order::where('status', Order::STATUS_PAID)->count(),
            'shipped_orders' => Order::where('status', Order::STATUS_SHIPPED)->count(),
            'completed_orders' => Order::where('status', Order::STATUS_COMPLETED)->count(),
            'cancelled_orders' => Order::where('status', Order::STATUS_CANCELLED)->count(),
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
            'revenue_today' => Order::whereDate('created_at', now()->toDateString())
                ->where('status', '!=', Order::STATUS_CANCELLED)
                ->sum('total'),
        ];

        // Last 7 days revenue breakdown
        $dailyRevenue = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $dayRevenue = Order::whereDate('created_at', $date->toDateString())
                ->where('status', '!=', Order::STATUS_CANCELLED)
                ->sum('total');
            $dayOrders = Order::whereDate('created_at', $date->toDateString())->count();
            $dailyRevenue[] = [
                'date' => $date->format('d M Y'),
                'revenue' => (float) $dayRevenue,
                'orders' => $dayOrders,
            ];
        }

        // Last 6 months revenue breakdown
        $monthlyRevenue = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthRevenue = Order::whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->where('status', '!=', Order::STATUS_CANCELLED)
                ->sum('total');
            $monthOrders = Order::whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->count();
            $monthlyRevenue[] = [
                'month' => $date->format('M Y'),
                'revenue' => (float) $monthRevenue,
                'orders' => $monthOrders,
            ];
        }

        // Top 10 products by revenue
        $topProducts = \App\Models\OrderItem::select('product_id', 'product_name')
            ->selectRaw('SUM(line_total) as total_revenue')
            ->selectRaw('SUM(quantity) as total_sold')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.status', '!=', Order::STATUS_CANCELLED)
            ->groupBy('product_id', 'product_name')
            ->orderByDesc('total_revenue')
            ->take(10)
            ->get();

        // Revenue by status
        $revenueByStatus = [
            'completed' => Order::where('status', Order::STATUS_COMPLETED)->sum('total'),
            'shipped' => Order::where('status', Order::STATUS_SHIPPED)->sum('total'),
            'paid' => Order::where('status', Order::STATUS_PAID)->sum('total'),
            'pending' => Order::where('status', Order::STATUS_PENDING)->sum('total'),
        ];

        return view('admin.dashboard.printable', compact(
            'stats',
            'dailyRevenue',
            'monthlyRevenue',
            'topProducts',
            'revenueByStatus'
        ));
    }
}
