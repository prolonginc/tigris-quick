<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Will-Call Order</title>
    <style>
        body {
            font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial;
            background-color: #f9fafb;
            margin: 0;
            padding: 0;
        }
        .card {
            background-color: #ffffff;
            border-radius: 0.75rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            max-width: 600px;
            margin: 2rem auto;
            overflow: hidden;
            border: 1px solid #e5e7eb;
        }
        .header {
            background-color: #1d4ed8;
            color: #ffffff;
            padding: 1rem 1.5rem;
            font-weight: 600;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .section { padding: 1.5rem; }
        .title { font-size: 1.25rem; font-weight: 700; color: #111827; }
        .subtitle { color: #6b7280; margin-top: 0.25rem; font-size: 0.875rem; }
        .box {
            background-color: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            padding: 1rem;
            margin-top: 1rem;
        }
        .label { font-weight: 600; color: #111827; font-size: 0.95rem; }
        .item-row {
            display: flex;
            justify-content: space-between;
            border-top: 1px solid #e5e7eb;
            padding: 0.75rem 0;
        }
        .item-row:first-child { border-top: none; }
        .item-name { color: #111827; font-weight: 500; }
        .item-desc { color: #6b7280; font-size: 0.875rem; }
        .qty { font-weight: 500; color: #111827; }
    </style>
</head>
<body>
    <div class="card">
        <div class="header">
            <span>Tigris Auto Glass</span>
            <span>{{ $order->order_number }} &bull; {{ $order->pickup_time }}</span>
        </div>

        <div class="section">
            <h1 class="title">NEW WILL-CALL ORDER — Prepare for Pickup</h1>
            <p class="subtitle">Order placed via wholesale portal.</p>

            <div class="box">
                <p class="label">Customer</p>
                <p class="mt-1 font-medium text-gray-900">{{ $customer->name ?? 'Customer' }}</p>
                <p class="text-gray-600 text-sm">{{ $customer->email ?? '' }}</p>
            </div>

            <div class="box">
                <p class="label">Pickup</p>
                <p><strong>Location:</strong> {{ $order->pickup_info }}</p>
                <p><strong>Time:</strong> {{ $order->pickup_time }}</p>
            </div>

            <div class="box">
                <p class="label">Items to Prepare</p>
                @foreach($order->items as $item)
                    <div class="item-row">
                        <div>
                            <p class="item-name">{{ $item->product->name ?? 'Item' }}</p>
                            <p class="item-desc">{{ $item->product->description ?? '' }}</p>
                        </div>
                        <div class="qty">Qty {{ $item->quantity }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</body>
</html>
