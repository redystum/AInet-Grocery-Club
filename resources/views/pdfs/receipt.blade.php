<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Receipt</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .receipt {
            max-width: 800px;
            margin: 0 auto;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
        }
        .order-details {
            margin-bottom: 30px;
        }
        .order-details table {
            width: 100%;
            border-collapse: collapse;
        }
        .order-details th, .order-details td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .items {
            margin-bottom: 30px;
        }
        .items table {
            width: 100%;
            border-collapse: collapse;
        }
        .items th, .items td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .total-row {
            font-weight: bold;
            background-color: #f8f8f8;
        }
        .footer {
            text-align: center;
            font-size: 12px;
            color: #777;
            margin-top: 50px;
            border-top: 1px solid #ddd;
            padding-top: 20px;
        }
    </style>
</head>
<body>
    <div class="receipt">
        <div class="header">
            <h1>Grocery Club</h1>
            <h2>Order Receipt</h2>
        </div>

        <div class="order-details">
            <h3>Order Information</h3>
            <table>
                <tr>
                    <th>Order ID:</th>
                    <td>#{{ $order->id }}</td>
                </tr>
                <tr>
                    <th>Order Date:</th>
                    <td>{{ $order->created_at->format('F j, Y') }}</td>
                </tr>
                <tr>
                    <th>Order Status:</th>
                    <td>{{ ucfirst($order->status) }}</td>
                </tr>
                <tr>
                    <th>Customer:</th>
                    <td>{{ $order->user->name }}</td>
                </tr>
                <tr>
                    <th>Email:</th>
                    <td>{{ $order->user->email }}</td>
                </tr>
                <tr>
                    <th>NIF:</th>
                    <td>{{ $order->nif }}</td>
                </tr>
                <tr>
                    <th>Delivery Address:</th>
                    <td>{{ $order->delivery_address }}</td>
                </tr>
            </table>
        </div>

        <div class="items">
            <h3>Order Items</h3>
            <table>
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Discount</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                    <tr>
                        <td>{{ $item->product->name }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>€{{ number_format($item->unit_price, 2) }}</td>
                        <td>€{{ number_format($item->discount, 2) }}</td>
                        <td>€{{ number_format($item->subtotal, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="totals">
            <table>
                <tr>
                    <th>Subtotal:</th>
                    <td>€{{ number_format(($order->total - $order->shipping_cost), 2) }}</td>
                </tr>
                <tr>
                    <th>Shipping Cost:</th>
                    <td>€{{ number_format($order->shipping_cost, 2) }}</td>
                </tr>
                @if($total_discount > 0)
                <tr>
                    <th>Total Discount:</th>
                    <td>-€{{ number_format($total_discount, 2) }}</td>
                </tr>
                @endif
                <tr class="total-row">
                    <th>Total:</th>
                    <td>€{{ number_format($order->total / 100, 2) }}</td>
                </tr>
            </table>
        </div>

        <div class="footer">
            <p>Thank you for shopping with Grocery Club!</p>
            <p>This receipt was generated on {{ now()->format('F j, Y \a\t g:i A') }}</p>
            <p>For any questions, please contact our support at support@groceryclub.com</p>
        </div>
    </div>
</body>
</html>

