<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Will-Call Order Received</title>
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
        .section {
            padding: 1.5rem;
        }
        .title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #111827;
        }
        .subtitle {
            color: #374151;
            margin-top: 0.25rem;
            font-size: 0.95rem;
        }
        .box {
            background-color: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            padding: 1rem;
            margin-top: 1rem;
        }
        .label {
            font-weight: 600;
            color: #111827;
            font-size: 0.95rem;
            margin-bottom: 0.5rem;
        }
        .text-gray {
            color: #6b7280;
            font-size: 0.875rem;
        }
        .item-row {
            display: flex;
            justify-content: space-between;
            border-top: 1px solid #e5e7eb;
            padding: 0.75rem 0;
        }
        .item-row:first-child {
            border-top: none;
        }
        .item-name {
            color: #111827;
            font-weight: 500;
        }
        .item-sku {
            color: #1d4ed8;
            font-weight: 600;
            font-size: 0.875rem;
        }
        .item-desc {
            color: #6b7280;
            font-size: 0.875rem;
        }
        .qty {
            font-weight: 500;
            color: #111827;
        }
        .btn {
            display: inline-block;
            background-color: #1d4ed8;
            color: #ffffff;
            font-weight: 500;
            padding: 0.625rem 1.25rem;
            border-radius: 0.375rem;
            text-decoration: none;
            margin-top: 1rem;
        }
        .footer {
            font-size: 0.875rem;
            color: #6b7280;
            margin-top: 1.5rem;
        }
    </style>
</head>
<body>
    <div class="card">
        <!-- Header -->
        <div class="header">
            <span>Tigris Auto Glass</span>
            <span>Order #: {{ $order->order_number }}</span>
        </div>

        <!-- Body -->
        <div class="section">
            <h1 class="title">Will-Call Order Received</h1>
            <p class="subtitle">Hi {{ $customer->name }}, thanks for placing your order with <strong>Tigris Auto Glass.</strong></p>

            <!-- Order Details -->
            <div class="box">
                <p class="label">Order Details</p>
                <p><strong>Pickup Location:</strong> {{ $order->pickup_info }}</p>
                <p><strong>Pickup Time:</strong> {{ $order->pickup_time }}</p>
            </div>

            <!-- Items Ordered -->
            <div class="box">
                <p class="label">Items Ordered</p>

                @foreach($order->items as $item)
                <div class="item-row">
                    <div>
                        <p class="item-name">{{ $item->product->name }}</p>
                        @if($item->product->sku)
                            <p class="item-sku">SKU: {{ $item->product->sku }}</p>
                        @endif
                        <p class="item-desc">{{ $item->product->description }}</p>
                    </div>
                    <div class="qty">Qty {{ $item->quantity }}</div>
                </div>
                @endforeach
            </div>

            <!-- Footer -->
            <p class="footer">Thank you for choosing Tigris Auto Glass.</p>
        </div>
    </div>
</body>
</html>
