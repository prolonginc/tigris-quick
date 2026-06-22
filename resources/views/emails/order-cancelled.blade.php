<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Will-Call Order Cancelled</title>
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
            background-color: #b91c1c;
            color: #ffffff;
            padding: 1rem 1.5rem;
            font-weight: 600;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .section { padding: 1.5rem; }
        .title { font-size: 1.25rem; font-weight: 700; color: #111827; }
        .subtitle { color: #374151; margin-top: 0.25rem; font-size: 0.95rem; }
        .box {
            background-color: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            padding: 1rem;
            margin-top: 1rem;
        }
        .label { font-weight: 600; color: #111827; font-size: 0.95rem; margin-bottom: 0.5rem; }
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
        .footer { font-size: 0.875rem; color: #6b7280; margin-top: 1.5rem; }
    </style>
</head>
<body>
    <div class="card">
        <div class="header">
            <span>Tigris Auto Glass</span>
            <span>Order #: {{ $order->order_number }}</span>
        </div>

        <div class="section">
            @if($audience === 'admin')
                <h1 class="title">Will-Call Order Cancelled</h1>
                <p class="subtitle">An order has been cancelled by the customer.</p>

                <div class="box">
                    <p class="label">Customer</p>
                    <p>{{ $customer->name ?? 'Customer' }}</p>
                    <p class="item-desc">{{ $customer->email ?? '' }}</p>
                </div>
            @else
                <h1 class="title">Your Order Was Cancelled</h1>
                <p class="subtitle">Hi {{ $customer->name ?? 'there' }}, your order <strong>{{ $order->order_number }}</strong> with <strong>Tigris Auto Glass</strong> has been cancelled.</p>
            @endif

            <div class="box">
                <p class="label">Order Details</p>
                <p><strong>Pickup Location:</strong> {{ $order->pickup_info }}</p>
                <p><strong>Pickup Time:</strong> {{ $order->pickup_time }}</p>
            </div>

            <div class="box">
                <p class="label">Cancelled Items</p>
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

            <p class="footer">
                @if($audience === 'admin')
                    No pickup is required for this order.
                @else
                    If this was a mistake, please place a new order or contact us.
                @endif
            </p>
        </div>
    </div>
</body>
</html>
