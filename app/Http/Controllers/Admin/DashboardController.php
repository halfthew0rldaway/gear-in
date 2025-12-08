<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        // Get current date/time in application timezone
        // Laravel will automatically convert to UTC when querying database
        $now = Carbon::now(config('app.timezone'));
        $today = $now->format('Y-m-d');
        $todayStart = $now->copy()->startOfDay();
        $todayEnd = $now->copy()->endOfDay();
        $thisMonthStart = $now->copy()->startOfMonth();
        $thisMonthEnd = $now->copy()->endOfMonth();
        
        $stats = [
            // Orders today - Laravel handles timezone conversion automatically
            'orders_today' => Order::whereDate('created_at', $today)->count(),
            // Orders this month
            'orders_this_month' => Order::whereBetween('created_at', [$thisMonthStart, $thisMonthEnd])->count(),
            'total_orders' => Order::count(),
            'pending_orders' => Order::where('status', Order::STATUS_PENDING)->count(),
            'completed_orders' => Order::where('status', Order::STATUS_COMPLETED)->count(),
            'products' => Product::count(),
            'active_products' => Product::where('is_active', true)->count(),
            'low_stock_products' => Product::where('stock', '<', 10)->where('stock', '>', 0)->count(),
            'out_of_stock' => Product::where('stock', 0)->count(),
            'customers' => User::where('role', User::ROLE_CUSTOMER)->count(),
            // Revenue includes all orders except cancelled (pending, paid, shipped, completed)
            'revenue' => Order::whereIn('status', [
                Order::STATUS_PENDING,
                Order::STATUS_PAID,
                Order::STATUS_SHIPPED,
                Order::STATUS_COMPLETED
            ])->sum('total'),
            'revenue_this_month' => Order::whereBetween('created_at', [$thisMonthStart, $thisMonthEnd])
                ->whereIn('status', [
                    Order::STATUS_PENDING,
                    Order::STATUS_PAID,
                    Order::STATUS_SHIPPED,
                    Order::STATUS_COMPLETED
                ])
                ->sum('total'),
        ];

        $recentOrders = Order::latest('created_at')->with('user')->take(5)->get();
        $lowStockProducts = Product::where('stock', '<', 10)->where('stock', '>', 0)->latest('updated_at')->take(5)->get();

        // Chart data - Last 7 days
        $chartData = [];
        $chartLabels = [];
        $chartRevenue = [];
        $chartOrders = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = $now->copy()->subDays($i);
            $dateStr = $date->format('Y-m-d');
            $dayStart = $date->copy()->startOfDay();
            $dayEnd = $date->copy()->endOfDay();
            
            $chartLabels[] = $date->format('d M');
            
            $dayRevenue = Order::whereDate('created_at', $dateStr)
                ->whereIn('status', [
                    Order::STATUS_PENDING,
                    Order::STATUS_PAID,
                    Order::STATUS_SHIPPED,
                    Order::STATUS_COMPLETED
                ])
                ->sum('total');
            $chartRevenue[] = (float) $dayRevenue;
            
            $dayOrders = Order::whereDate('created_at', $dateStr)->count();
            $chartOrders[] = $dayOrders;
        }

        // Monthly revenue for last 6 months
        $monthlyLabels = [];
        $monthlyRevenue = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $date = $now->copy()->subMonths($i);
            $monthStart = $date->copy()->startOfMonth();
            $monthEnd = $date->copy()->endOfMonth();
            
            $monthlyLabels[] = $date->format('M Y');
            
            $monthRevenue = Order::whereBetween('created_at', [$monthStart, $monthEnd])
                ->whereIn('status', [
                    Order::STATUS_PENDING,
                    Order::STATUS_PAID,
                    Order::STATUS_SHIPPED,
                    Order::STATUS_COMPLETED
                ])
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
        // Get current date/time in application timezone
        // Laravel will automatically convert to UTC when querying database
        $now = Carbon::now(config('app.timezone'));
        $today = $now->format('Y-m-d');
        $todayStart = $now->copy()->startOfDay();
        $todayEnd = $now->copy()->endOfDay();
        $thisMonthStart = $now->copy()->startOfMonth();
        $thisMonthEnd = $now->copy()->endOfMonth();
        
        $stats = [
            'orders_today' => Order::whereDate('created_at', $today)->count(),
            'orders_this_month' => Order::whereBetween('created_at', [$thisMonthStart, $thisMonthEnd])->count(),
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
            // Revenue includes all orders except cancelled (pending, paid, shipped, completed)
            'revenue' => Order::whereIn('status', [
                Order::STATUS_PENDING,
                Order::STATUS_PAID,
                Order::STATUS_SHIPPED,
                Order::STATUS_COMPLETED
            ])->sum('total'),
            'revenue_this_month' => Order::whereBetween('created_at', [$thisMonthStart, $thisMonthEnd])
                ->whereIn('status', [
                    Order::STATUS_PENDING,
                    Order::STATUS_PAID,
                    Order::STATUS_SHIPPED,
                    Order::STATUS_COMPLETED
                ])
                ->sum('total'),
            'revenue_today' => Order::whereDate('created_at', $today)
                ->whereIn('status', [
                    Order::STATUS_PENDING,
                    Order::STATUS_PAID,
                    Order::STATUS_SHIPPED,
                    Order::STATUS_COMPLETED
                ])
                ->sum('total'),
        ];

        // Last 7 days revenue breakdown
        $dailyRevenue = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = $now->copy()->subDays($i);
            $dateStr = $date->format('Y-m-d');
            
            $dayRevenue = Order::whereDate('created_at', $dateStr)
                ->whereIn('status', [
                    Order::STATUS_PENDING,
                    Order::STATUS_PAID,
                    Order::STATUS_SHIPPED,
                    Order::STATUS_COMPLETED
                ])
                ->sum('total');
            $dayOrders = Order::whereDate('created_at', $dateStr)->count();
            $dailyRevenue[] = [
                'date' => $date->format('d M Y'),
                'revenue' => (float) $dayRevenue,
                'orders' => $dayOrders,
            ];
        }

        // Last 6 months revenue breakdown
        $monthlyRevenue = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = $now->copy()->subMonths($i);
            $monthStart = $date->copy()->startOfMonth();
            $monthEnd = $date->copy()->endOfMonth();
            
            $monthRevenue = Order::whereBetween('created_at', [$monthStart, $monthEnd])
                ->whereIn('status', [
                    Order::STATUS_PENDING,
                    Order::STATUS_PAID,
                    Order::STATUS_SHIPPED,
                    Order::STATUS_COMPLETED
                ])
                ->sum('total');
            $monthOrders = Order::whereBetween('created_at', [$monthStart, $monthEnd])->count();
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
            ->whereIn('orders.status', [
                Order::STATUS_PENDING,
                Order::STATUS_PAID,
                Order::STATUS_SHIPPED,
                Order::STATUS_COMPLETED
            ])
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
