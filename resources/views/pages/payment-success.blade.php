<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Successful</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f4f6f8;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 700px;
            margin: 80px auto;
            padding: 20px;
        }

        .success-card {
            background: #ffffff;
            border-radius: 14px;
            padding: 40px 30px;
            text-align: center;
            box-shadow: 0 15px 40px rgba(0,0,0,0.08);
        }

        .success-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
            background: #22c55e;
            color: #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
        }

        h1 {
            font-size: 26px;
            margin-bottom: 10px;
            color: #111827;
        }

        p {
            font-size: 15px;
            color: #6b7280;
            margin-bottom: 30px;
        }

        .order-details {
            text-align: left;
            background: #f9fafb;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 30px;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
            font-size: 14px;
        }

        .detail-row strong {
            color: #374151;
        }

        .detail-row span {
            color: #111827;
            font-weight: 500;
        }

        .detail-row.total {
            font-size: 16px;
            font-weight: 600;
            border-top: 1px solid #e5e7eb;
            padding-top: 12px;
        }

        .actions {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn {
            padding: 12px 22px;
            border-radius: 10px;
            font-size: 15px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .btn-primary {
            background: #4f46e5;
            color: #fff;
        }

        .btn-primary:hover {
            background: #4338ca;
        }

        .btn-outline {
            border: 1px solid #d1d5db;
            color: #374151;
        }

        .btn-outline:hover {
            background: #f3f4f6;
        }

        .secure-note {
            margin-top: 30px;
            font-size: 13px;
            color: #6b7280;
        }

        @media (max-width: 600px) {
            .container {
                margin-top: 40px;
            }
        }

        .language-selector {
            position: absolute;
            top: 15px;
            right: 15px;
            z-index: 1000;
        }

        .language-selector select {
            padding: 8px 12px;
            border-radius: 8px;
            border: 1.5px solid #4f46e5;
            font-size: 14px;
            font-weight: 500;
            background-color: white;
            color: #4f46e5;
            cursor: pointer;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        .language-selector select:hover,
        .language-selector select:focus {
            border-color: #6366f1;
            box-shadow: 0 0 8px rgba(99, 102, 241, 0.5);
            outline: none;
        }
    </style>
</head>
<body>

<div class="container">

    <!-- Language dropdown placed here -->
    <div class="language-selector">
        <form method="GET" id="language-form">
            <select name="lang" onChange="document.getElementById('language-form').submit()">
                <option value="en" {{ app()->getLocale() == 'en' ? 'selected' : '' }}>English</option>
                <option value="bn" {{ app()->getLocale() == 'bn' ? 'selected' : '' }}>Bengali</option>
                <option value="hi" {{ app()->getLocale() == 'hi' ? 'selected' : '' }}>Hindi</option>
            </select>
        </form>
    </div>
    
    <div class="success-card">

        <div class="success-icon">✓</div>

        <h1>Payment Successful</h1>
        <p>Thank you for your purchase! Your payment has been processed successfully.</p>

        <!-- Order Details -->
        <div class="order-details">
            <div class="detail-row">
                <strong>Order ID</strong>
                <span>ORD-123456</span>
            </div>

            <div class="detail-row">
                <strong>Payment Method</strong>
                <span>{{ ucfirst($order->payment_method ?? 'Stripe') }}</span>
            </div>

            <div class="detail-row">
                <strong>Email</strong>
                <span>customer@example.com</span>
            </div>

            <div class="detail-row total">
                <strong>Total Paid</strong>
                <span>$55.20</span>
            </div>
        </div>

        <!-- Actions -->
        <div class="actions">
            <a href="{{ route('checkout.index') }}" class="btn btn-outline">
                Continue Shopping
            </a>
        </div>

        <p class="secure-note">
            🔒 This transaction was processed securely. A confirmation email has been sent to you.
        </p>

    </div>
</div>

</body>
</html>
