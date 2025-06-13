<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Supply Order Receipt</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.5;
            color: #333;
        }
        .container {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #ddd;
        }
        .logo {
            max-width: 150px;
            margin-bottom: 10px;
        }
        h1 {
            font-size: 24px;
            font-weight: bold;
            color: #222;
            margin: 0;
        }
        .receipt-id {
            font-size: 14px;
            color: #666;
            margin-top: 5px;
        }
        .info-section {
            margin-bottom: 30px;
        }
        .info-row {
            display: flex;
            margin-bottom: 5px;
        }
        .info-label {
            font-weight: bold;
            width: 150px;
        }
        .info-value {
            flex: 1;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        th {
            background-color: #f5f5f5;
            text-align: left;
            font-weight: bold;
            padding: 8px;
            border-bottom: 2px solid #ddd;
        }
        td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }
        .total-row {
            font-weight: bold;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            font-size: 10px;
            color: #666;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Supply Order Receipt</h1>
            <div class="receipt-id">Receipt #SUPPLY-{{ $order->id }}</div>
            <div class="receipt-date">Date: {{ $date }}</div>
        </div>
        
        <div class="info-section">
            <div class="info-row">
                <span class="info-label">Order ID:</span>
                <span class="info-value">{{ $order->id }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Order Date:</span>
                <span class="info-value">{{ $order->created_at->format('d/m/Y H:i') }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Received Date:</span>
                <span class="info-value">{{ now()->format('d/m/Y H:i') }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Registered By:</span>
                <span class="info-value">{{ $order->registeredBy->name }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Received By:</span>
                <span class="info-value">{{ auth()->user()->name }}</span>
            </div>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th>Product ID</th>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $order->product->id }}</td>
                    <td>{{ $order->product->name }}</td>
                    <td>{{ $order->quantity }}</td>
                    <td>€{{ number_format($order->product->price, 2) }}</td>
                    <td>€{{ number_format($order->product->price * $order->quantity, 2) }}</td>
                </tr>
                <tr class="total-row">
                    <td colspan="4" align="right">Total:</td>
                    <td>€{{ number_format($order->product->price * $order->quantity, 2) }}</td>
                </tr>
            </tbody>
        </table>
        
        <div class="footer">
            <p>This is an automatically generated supply receipt for internal records.</p>
            <p>Grocery Club - {{ now()->format('Y') }}</p>
        </div>
    </div>
</body>
</html>
