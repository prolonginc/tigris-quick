<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Will-Call Order</title>
    <style>
        /* Tailwind base styles for email */
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
            color: #6b7280;
            margin-top: 0.25rem;
            font-size: 0.875rem;
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
        .item-header {
            display: flex;
            align-items: baseline;
            gap: 0.5rem;
        }
        .item-name {
            color: #111827;
            font-weight: 700;
            font-size: 1rem;
        }
        .item-sep {
            color: #9ca3af;
            font-weight: 400;
        }
        .item-qty {
            color: #111827;
            font-weight: 700;
            font-size: 1rem;
        }
        .item-sku {
            font-size: 0.875rem;
            margin-top: 0.125rem;
        }
        .item-sku-label {
            color: #374151;
            font-weight: 600;
        }
        .item-sku-value {
            color: #1d4ed8;
            font-weight: 600;
        }
        .item-desc {
            color: #6b7280;
            font-size: 0.875rem;
        }
    </style>
</head>
<body>
    <div class="card">
        <!-- Header -->
        <div class="header">
            <span>Tigris Auto Glass</span>
            <span>{{ $order->order_number }} &bull; {{ $order->pickup_time }}</span>
        </div>

        <!-- Body -->
        <div class="section">
            <h1 class="title">NEW WILL-CALL ORDER — Prepare for Pickup</h1>
            <p class="subtitle">Order placed via wholesale portal.</p>

            <!-- Customer Info -->
            <div class="box">
                <p class="label">Customer</p>
                <p style="margin-top: 0.25rem; font-weight: 700; color: #111827; font-size: 1.125rem;">{{ $customer->name }} — {{ $customer->business_name ?? '' }}</p>
                <p style="color: #4b5563; font-size: 0.875rem;">{{ $customer->phone_number ?? '' }} &bull; {{ $customer->email }}</p>
            </div>

            <!-- Items -->
            <div class="box">
                <p class="label">Items to Prepare</p>

                @foreach($order->items as $item)
                <div class="item-row">
                    <div>
                        <p class="item-header">
                            <span class="item-name">{{ $item->product->name }}</span>
                            <span class="item-sep">|</span>
                            <span class="item-qty">({{ $item->quantity }})</span>
                        </p>
                        @if($item->product->sku)
                            <p class="item-sku"><span class="item-sku-label">SKU:</span> <span class="item-sku-value">{{ $item->product->sku }}</span></p>
                        @endif
                        <p class="item-desc">{{ $item->product->description }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</body>
</html>
