<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Return Cancelled</title>
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
                <h1 class="title">Order Return Cancelled</h1>
                <p class="subtitle">A previously processed return has been cancelled by the customer.</p>

                <div class="box">
                    <p class="label">Customer</p>
                    <p>{{ $customer->name ?? 'Customer' }}</p>
                    <p class="item-desc">{{ $customer->email ?? '' }}</p>
                </div>
            @else
                <h1 class="title">Your Return Was Cancelled</h1>
                <p class="subtitle">Hi {{ $customer->name ?? 'there' }}, the return on order <strong>{{ $order->order_number }}</strong> has been cancelled and these items are part of your order again.</p>
            @endif

            <div class="box">
                <p class="label">Items Restored</p>
                @foreach($restoredItems as $item)
                    <div class="item-row">
                        <div>
                            <p class="item-name">{{ $item['name'] }}</p>
                            <p class="item-desc">{{ $item['description'] }}</p>
                        </div>
                        <div class="qty">Qty {{ $item['quantity'] }}</div>
                    </div>
                @endforeach
            </div>

            <p class="footer">
                @if($audience === 'admin')
                    Please update inventory and records accordingly.
                @else
                    Thank you for choosing Tigris Auto Glass.
                @endif
            </p>
        </div>
    </div>
</body>
</html>
