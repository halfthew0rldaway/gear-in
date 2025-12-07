<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard & Revenue Report - gear-in</title>
    <style>
        @media print {
            body { margin: 0; padding: 20px; }
            .no-print { display: none !important; }
            @page { margin: 1cm; size: A4 landscape; }
            .page-break { page-break-after: always; }
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px 20px;
            background: white;
            color: #1f1f1f;
            font-size: 12px;
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #1f1f1f;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            font-size: 32px;
            font-weight: 600;
            margin: 0 0 10px 0;
            letter-spacing: -0.5px;
        }
        .header p {
            font-size: 14px;
            color: #6f6f6f;
            margin: 5px 0;
        }
        .print-date {
            text-align: right;
            font-size: 11px;
            color: #6f6f6f;
            margin-bottom: 20px;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            margin-bottom: 30px;
        }
        .stat-card {
            border: 1px solid #dfdfdf;
            border-radius: 8px;
            padding: 15px;
            background: #f9f9f9;
        }
        .stat-label {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: #6f6f6f;
            margin-bottom: 8px;
            font-weight: 500;
        }
        .stat-value {
            font-size: 20px;
            font-weight: 600;
            color: #1f1f1f;
        }
        .section {
            margin-bottom: 30px;
            page-break-inside: avoid;
        }
        .section-title {
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: #1f1f1f;
            margin-bottom: 15px;
            font-weight: 600;
            border-bottom: 2px solid #1f1f1f;
            padding-bottom: 8px;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
            font-size: 11px;
        }
        .data-table thead {
            background: #1f1f1f;
            color: white;
        }
        .data-table th {
            text-align: left;
            padding: 10px;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            font-weight: 600;
        }
        .data-table td {
            padding: 10px;
            border-bottom: 1px solid #dfdfdf;
        }
        .data-table tbody tr:hover {
            background: #f9f9f9;
        }
        .data-table tbody tr:last-child td {
            border-bottom: none;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .revenue-highlight {
            color: #065f46;
            font-weight: 600;
        }
        .no-print {
            text-align: center;
            margin: 20px 0;
            padding: 20px;
            background: #f9f9f9;
            border-radius: 8px;
        }
        .no-print button {
            background: #1f1f1f;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            margin: 0 10px;
        }
        .no-print button:hover {
            background: #000;
        }
        .two-column {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
        }
        .summary-box {
            border: 1px solid #dfdfdf;
            border-radius: 8px;
            padding: 15px;
            background: #f9f9f9;
        }
        .summary-box h3 {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            margin: 0 0 10px 0;
            color: #6f6f6f;
        }
        .summary-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #dfdfdf;
        }
        .summary-item:last-child {
            border-bottom: none;
            font-weight: 600;
            font-size: 14px;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="no-print">
        <button onclick="window.print()">🖨️ Cetak Laporan</button>
        <a href="{{ route('admin.dashboard') }}" style="display: inline-block; padding: 12px 24px; background: #6f6f6f; color: white; text-decoration: none; border-radius: 6px; margin-left: 10px;">← Kembali ke Dashboard</a>
    </div>

    <div class="header">
        <h1>gear-in</h1>
        <p>Dashboard & Revenue Report</p>
        <p>Laporan Pendapatan & Statistik</p>
    </div>

    <div class="print-date">
        Dicetak pada: {{ now()->format('d F Y, H:i') }} WIB
    </div>

    <!-- Statistik Utama -->
    <div class="section">
        <div class="section-title">Statistik Utama</div>
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-label">Total Revenue</div>
                <div class="stat-value revenue-highlight">{{ 'Rp '.number_format($stats['revenue'], 0, ',', '.') }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Revenue Bulan Ini</div>
                <div class="stat-value revenue-highlight">{{ 'Rp '.number_format($stats['revenue_this_month'], 0, ',', '.') }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Revenue Hari Ini</div>
                <div class="stat-value revenue-highlight">{{ 'Rp '.number_format($stats['revenue_today'], 0, ',', '.') }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Total Pesanan</div>
                <div class="stat-value">{{ $stats['total_orders'] }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Pesanan Hari Ini</div>
                <div class="stat-value">{{ $stats['orders_today'] }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Pesanan Bulan Ini</div>
                <div class="stat-value">{{ $stats['orders_this_month'] }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Total Produk</div>
                <div class="stat-value">{{ $stats['products'] }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Total Pelanggan</div>
                <div class="stat-value">{{ $stats['customers'] }}</div>
            </div>
        </div>
    </div>

    <!-- Status Pesanan -->
    <div class="section">
        <div class="section-title">Status Pesanan</div>
        <div class="two-column">
            <div class="summary-box">
                <h3>Jumlah Pesanan per Status</h3>
                <div class="summary-item">
                    <span>Pending</span>
                    <span>{{ $stats['pending_orders'] }}</span>
                </div>
                <div class="summary-item">
                    <span>Paid</span>
                    <span>{{ $stats['paid_orders'] }}</span>
                </div>
                <div class="summary-item">
                    <span>Shipped</span>
                    <span>{{ $stats['shipped_orders'] }}</span>
                </div>
                <div class="summary-item">
                    <span>Completed</span>
                    <span>{{ $stats['completed_orders'] }}</span>
                </div>
                <div class="summary-item">
                    <span>Cancelled</span>
                    <span>{{ $stats['cancelled_orders'] }}</span>
                </div>
            </div>
            <div class="summary-box">
                <h3>Revenue per Status</h3>
                <div class="summary-item">
                    <span>Completed</span>
                    <span class="revenue-highlight">{{ 'Rp '.number_format($revenueByStatus['completed'], 0, ',', '.') }}</span>
                </div>
                <div class="summary-item">
                    <span>Shipped</span>
                    <span class="revenue-highlight">{{ 'Rp '.number_format($revenueByStatus['shipped'], 0, ',', '.') }}</span>
                </div>
                <div class="summary-item">
                    <span>Paid</span>
                    <span class="revenue-highlight">{{ 'Rp '.number_format($revenueByStatus['paid'], 0, ',', '.') }}</span>
                </div>
                <div class="summary-item">
                    <span>Pending</span>
                    <span class="revenue-highlight">{{ 'Rp '.number_format($revenueByStatus['pending'], 0, ',', '.') }}</span>
                </div>
                <div class="summary-item">
                    <span>Total Revenue</span>
                    <span class="revenue-highlight">{{ 'Rp '.number_format($stats['revenue'], 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Revenue 7 Hari Terakhir -->
    <div class="section">
        <div class="section-title">Revenue 7 Hari Terakhir</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th class="text-right">Revenue</th>
                    <th class="text-center">Jumlah Pesanan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($dailyRevenue as $day)
                <tr>
                    <td>{{ $day['date'] }}</td>
                    <td class="text-right revenue-highlight">{{ 'Rp '.number_format($day['revenue'], 0, ',', '.') }}</td>
                    <td class="text-center">{{ $day['orders'] }}</td>
                </tr>
                @endforeach
                <tr style="background: #f9f9f9; font-weight: 600;">
                    <td>Total</td>
                    <td class="text-right revenue-highlight">{{ 'Rp '.number_format(collect($dailyRevenue)->sum('revenue'), 0, ',', '.') }}</td>
                    <td class="text-center">{{ collect($dailyRevenue)->sum('orders') }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Revenue 6 Bulan Terakhir -->
    <div class="section">
        <div class="section-title">Revenue 6 Bulan Terakhir</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Bulan</th>
                    <th class="text-right">Revenue</th>
                    <th class="text-center">Jumlah Pesanan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($monthlyRevenue as $month)
                <tr>
                    <td>{{ $month['month'] }}</td>
                    <td class="text-right revenue-highlight">{{ 'Rp '.number_format($month['revenue'], 0, ',', '.') }}</td>
                    <td class="text-center">{{ $month['orders'] }}</td>
                </tr>
                @endforeach
                <tr style="background: #f9f9f9; font-weight: 600;">
                    <td>Total</td>
                    <td class="text-right revenue-highlight">{{ 'Rp '.number_format(collect($monthlyRevenue)->sum('revenue'), 0, ',', '.') }}</td>
                    <td class="text-center">{{ collect($monthlyRevenue)->sum('orders') }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Top 10 Produk Terlaris -->
    @if($topProducts->count() > 0)
    <div class="section">
        <div class="section-title">Top 10 Produk Terlaris (Berdasarkan Revenue)</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Produk</th>
                    <th class="text-center">Terjual</th>
                    <th class="text-right">Total Revenue</th>
                </tr>
            </thead>
            <tbody>
                @foreach($topProducts as $index => $product)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $product->product_name }}</td>
                    <td class="text-center">{{ $product->total_sold }}</td>
                    <td class="text-right revenue-highlight">{{ 'Rp '.number_format($product->total_revenue, 0, ',', '.') }}</td>
                </tr>
                @endforeach
                <tr style="background: #f9f9f9; font-weight: 600;">
                    <td colspan="2">Total</td>
                    <td class="text-center">{{ $topProducts->sum('total_sold') }}</td>
                    <td class="text-right revenue-highlight">{{ 'Rp '.number_format($topProducts->sum('total_revenue'), 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    @endif

    <!-- Statistik Produk -->
    <div class="section">
        <div class="section-title">Statistik Produk</div>
        <div class="two-column">
            <div class="summary-box">
                <h3>Status Produk</h3>
                <div class="summary-item">
                    <span>Total Produk</span>
                    <span>{{ $stats['products'] }}</span>
                </div>
                <div class="summary-item">
                    <span>Produk Aktif</span>
                    <span>{{ $stats['active_products'] }}</span>
                </div>
                <div class="summary-item">
                    <span>Stok Rendah (< 10)</span>
                    <span style="color: #d97706;">{{ $stats['low_stock_products'] }}</span>
                </div>
                <div class="summary-item">
                    <span>Stok Habis</span>
                    <span style="color: #dc2626;">{{ $stats['out_of_stock'] }}</span>
                </div>
            </div>
        </div>
    </div>

    <div style="margin-top: 40px; padding-top: 20px; border-top: 2px solid #dfdfdf; text-align: center; font-size: 10px; color: #6f6f6f;">
        <p>Laporan ini dibuat secara otomatis oleh sistem gear-in</p>
        <p>© {{ date('Y') }} gear-in · All rights reserved</p>
    </div>
</body>
</html>

