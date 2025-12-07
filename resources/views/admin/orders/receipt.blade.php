<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Seller Receipt - {{ $order->code }}</title>
    <style>
        @media print {
            body { margin: 0; padding: 20px; }
            .no-print { display: none !important; }
            @page { margin: 1cm; }
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 40px 20px;
            background: white;
            color: #1f1f1f;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #1f1f1f;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            font-size: 28px;
            font-weight: 600;
            margin: 0 0 10px 0;
            letter-spacing: -0.5px;
        }
        .header p {
            font-size: 14px;
            color: #6f6f6f;
            margin: 5px 0;
        }
        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-top: 5px;
        }
        .badge.pending { background: #fef3c7; color: #92400e; }
        .badge.paid { background: #d1fae5; color: #065f46; }
        .badge.shipped { background: #dbeafe; color: #1e40af; }
        .badge.completed { background: #d1fae5; color: #065f46; }
        .badge.cancelled { background: #fee2e2; color: #991b1b; }
        .order-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 30px;
        }
        .section {
            margin-bottom: 25px;
        }
        .section-title {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: #6f6f6f;
            margin-bottom: 10px;
            font-weight: 500;
        }
        .section-content {
            font-size: 14px;
            line-height: 1.6;
            color: #1f1f1f;
        }
        .section-content p {
            margin: 5px 0;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .items-table thead {
            border-bottom: 2px solid #1f1f1f;
        }
        .items-table th {
            text-align: left;
            padding: 12px 0;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            font-weight: 600;
        }
        .items-table td {
            padding: 12px 0;
            border-bottom: 1px solid #dfdfdf;
            font-size: 14px;
        }
        .items-table tr:last-child td {
            border-bottom: none;
        }
        .text-right {
            text-align: right;
        }
        .totals {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 2px solid #1f1f1f;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            font-size: 14px;
        }
        .total-row.final {
            font-size: 18px;
            font-weight: 600;
            padding-top: 15px;
            margin-top: 10px;
            border-top: 1px solid #dfdfdf;
        }
        .timeline {
            margin-top: 20px;
        }
        .timeline-item {
            padding: 10px 0;
            border-bottom: 1px solid #dfdfdf;
        }
        .timeline-item:last-child {
            border-bottom: none;
        }
        .timeline-item p {
            margin: 3px 0;
            font-size: 13px;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #dfdfdf;
            text-align: center;
            font-size: 12px;
            color: #6f6f6f;
        }
        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 12px 24px;
            background: #1f1f1f;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            z-index: 1000;
        }
        .print-button:hover {
            background: #121212;
        }
    </style>
</head>
<body>
    <button onclick="window.print()" class="print-button no-print">Print Receipt</button>

    <div class="header">
        <h1>gear-in</h1>
        <p>Seller Receipt / Invoice</p>
        <p style="font-size: 16px; font-weight: 600; margin-top: 10px;">{{ $order->code }}</p>
        <span class="badge {{ $order->status }}">{{ ucfirst($order->status) }}</span>
    </div>

    <div class="order-info">
        <div class="section">
            <div class="section-title">Order Information</div>
            <div class="section-content">
                <p><strong>Order Date:</strong> {{ $order->created_at->format('d M Y, H:i') }}</p>
                <p><strong>Status:</strong> {{ ucfirst($order->status) }}</p>
                <p><strong>Payment Method:</strong> {{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</p>
                <p><strong>Payment Status:</strong> {{ ucfirst($order->payment_status) }}</p>
                <p><strong>Shipping Method:</strong> {{ ucfirst(str_replace('_', ' ', $order->shipping_method)) }}</p>
                @if($order->placed_at)
                    <p><strong>Placed At:</strong> {{ $order->placed_at->format('d M Y, H:i') }}</p>
                @endif
            </div>
        </div>

        <div class="section">
            <div class="section-title">Customer Information</div>
            <div class="section-content">
                <p><strong>{{ $order->customer_name }}</strong></p>
                <p>{{ $order->customer_email }}</p>
                <p>{{ $order->customer_phone }}</p>
                @if($order->user)
                    <p style="margin-top: 10px; font-size: 12px; color: #6f6f6f;">User ID: {{ $order->user_id }}</p>
                @endif
            </div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Shipping Address</div>
        <div class="section-content">
            <p>{{ $order->address_line1 }}</p>
            @if($order->address_line2)
                <p>{{ $order->address_line2 }}</p>
            @endif
            <p>{{ $order->city }}, {{ $order->postal_code }}</p>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Order Items</div>
        <table class="items-table">
            <thead>
                <tr>
                    <th>Item</th>
                    <th class="text-right">Quantity</th>
                    <th class="text-right">Unit Price</th>
                    <th class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->items as $item)
                    <tr>
                        <td>{{ $item->product_name }}</td>
                        <td class="text-right">{{ $item->quantity }}</td>
                        <td class="text-right">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                        <td class="text-right"><strong>Rp {{ number_format($item->line_total, 0, ',', '.') }}</strong></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="totals">
        <div class="total-row">
            <span>Subtotal</span>
            <span>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
        </div>
        <div class="total-row">
            <span>Shipping Fee</span>
            <span>Rp {{ number_format($order->shipping_fee, 0, ',', '.') }}</span>
        </div>
        <div class="total-row final">
            <span>Total</span>
            <span>Rp {{ number_format($order->total, 0, ',', '.') }}</span>
        </div>
    </div>

    @if($order->notes)
    <div class="section">
        <div class="section-title">Customer Notes</div>
        <div class="section-content">
            <p>{{ $order->notes }}</p>
        </div>
    </div>
    @endif

    @if($order->statusHistories && $order->statusHistories->count() > 0)
    <div class="section">
        <div class="section-title">Order Timeline</div>
        <div class="timeline">
            @foreach ($order->statusHistories as $history)
                <div class="timeline-item">
                    <p><strong>{{ ucfirst($history->status) }}</strong></p>
                    <p style="color: #6f6f6f;">{{ $history->created_at->format('d M Y, H:i') }}</p>
                    @if($history->user)
                        <p style="color: #6f6f6f;">by {{ $history->user->name }}</p>
                    @endif
                    @if($history->note)
                        <p style="color: #6f6f6f; font-style: italic;">{{ $history->note }}</p>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
    @endif

    <div class="footer">
        <p>Seller Copy - gear-in</p>
        <p>This is a computer-generated receipt for internal records.</p>
    </div>
</body>
</html>

