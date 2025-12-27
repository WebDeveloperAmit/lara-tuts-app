<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

    <style>
        .logout_div {
            margin-bottom: 30px;
        }
        .logout_btn {
            background-color: #ef4444;
            color: white;
            padding: 8px 12px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: background-color 0.3s ease;
        }
        body {
            font-family: 'Poppins', sans-serif;
            background: #f4f6f8;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 1100px;
            margin: 40px auto;
            padding: 20px;
        }

        .checkout-wrapper {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 25px;
        }

        .card {
            background: #fff;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.06);
        }

        h2 {
            margin-bottom: 18px;
            font-size: 22px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            font-size: 14px;
            margin-bottom: 6px;
            color: #555;
        }

        input, textarea {
            width: 100%;
            padding: 11px 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
        }

        input:focus, textarea:focus {
            outline: none;
            border-color: #4f46e5;
            box-shadow: 0 0 0 3px rgba(79,70,229,0.12);
        }

        textarea {
            resize: none;
        }

        .row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        /* Payment Methods */
        .payment-method {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border: 1px solid #ddd;
            padding: 14px 16px;
            border-radius: 10px;
            margin-bottom: 12px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .payment-method:hover {
            border-color: #4f46e5;
            background: #f5f5ff;
        }

        .payment-method.active {
            border-color: #4f46e5;
            background: #f5f5ff;
        }

        .payment-left {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .payment-icon {
            font-size: 20px;
        }

        .payment-method input {
            accent-color: #4f46e5;
            transform: scale(1.1);
        }

        /* Order Summary */
        .summary-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
            font-size: 14px;
        }

        .summary-item small {
            display: block;
            color: #777;
            font-size: 12px;
        }

        .summary-total {
            font-weight: 600;
            font-size: 18px;
            background: #f9fafb;
            padding: 14px;
            border-radius: 8px;
            margin-top: 10px;
        }

        .secure-note {
            font-size: 13px;
            color: #666;
            margin-bottom: 15px;
        }

        /* Button */
        .checkout-btn {
            width: 100%;
            background: #4f46e5;
            color: #fff;
            border: none;
            padding: 15px;
            font-size: 16px;
            border-radius: 10px;
            cursor: pointer;
            margin-top: 20px;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .checkout-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 10px 25px rgba(79,70,229,0.3);
            background: #4338ca;
        }

        @media (max-width: 900px) {
            .checkout-wrapper {
                grid-template-columns: 1fr;
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

    <div class="logout_div">
        <a href="{{ route('logout') }}" class="logout_btn">Logout</a>
    </div>

    <!-- Language dropdown placed here -->
    <div class="language-selector">
        <select onchange="changeLanguage(this.value)">
            <option value="en" {{ app()->getLocale() == 'en' ? 'selected' : '' }}>English</option>
            <option value="bn" {{ app()->getLocale() == 'bn' ? 'selected' : '' }}>Bengali</option>
            <option value="hi" {{ app()->getLocale() == 'hi' ? 'selected' : '' }}>Hindi</option>
        </select>
    </div>

    <form action="{{ route('checkout.process') }}" method="POST">
        @csrf

        <div class="checkout-wrapper">

            <!-- LEFT -->
            <div class="card">
                <h2>Billing Details</h2>

                <div class="row">
                    <div class="form-group">
                        <label>First Name</label>
                        <input type="text" name="first_name" placeholder="John" required>
                    </div>
                    <div class="form-group">
                        <label>Last Name</label>
                        <input type="text" name="last_name" placeholder="Doe" required>
                    </div>
                </div>

                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" placeholder="you@example.com" required>
                </div>

                <div class="form-group">
                    <label>Phone Number</label>
                    <input type="text" name="phone" placeholder="+1 234 567 890" required>
                </div>

                <div class="form-group">
                    <label>Address</label>
                    <textarea name="address" rows="3" placeholder="Street, City, Country" required></textarea>
                </div>

                <h2>Payment Method</h2>

                <label class="payment-method">
                    <div class="payment-left">
                        <span class="payment-icon">💳</span>
                        <div>
                            <strong>Stripe</strong>
                            <small>Credit / Debit Card</small>
                        </div>
                    </div>
                    <input type="radio" name="payment_method" value="stripe" required>
                </label>

                <label class="payment-method">
                    <div class="payment-left">
                        <span class="payment-icon">🅿️</span>
                        <div>
                            <strong>PayPal</strong>
                            <small>Pay using PayPal</small>
                        </div>
                    </div>
                    <input type="radio" name="payment_method" value="paypal">
                </label>

                <label class="payment-method">
                    <div class="payment-left">
                        <span class="payment-icon">⚡</span>
                        <div>
                            <strong>RazorPay</strong>
                            <small>UPI / Cards</small>
                        </div>
                    </div>
                    <input type="radio" name="payment_method" value="razorpay">
                </label>

                <label class="payment-method">
                    <div class="payment-left">
                        <span class="payment-icon">📦</span>
                        <div>
                            <strong>Cash on Delivery</strong>
                            <small>Pay on delivery</small>
                        </div>
                    </div>
                    <input type="radio" name="payment_method" value="cod">
                </label>
            </div>

            <!-- RIGHT -->
            <div class="card">
                <h2>Order Summary</h2>
                <p class="secure-note">🔒 Secure & Encrypted Checkout</p>

                <div class="summary-item">
                    <div>
                        <strong>Product Name</strong>
                        <small>Quantity: 1</small>
                    </div>
                    <span>$50.00</span>
                </div>

                <div class="summary-item">
                    <span>Shipping</span>
                    <span>$5.00</span>
                </div>

                {{-- <div class="summary-item summary-total">
                    <span>Total</span>
                    <span>$55.00</span>
                </div>

                <button type="submit" class="checkout-btn">
                    Pay $55.00 Securely
                </button> --}}

                <div class="form-group">
                    <label>Total Amount</label>
                    <input
                        type="text"
                        name="total_amount"
                        id="totalAmount"
                        value="55.00"
                        readonly
                        style="
                            font-size:18px;
                            font-weight:600;
                            background:#f9fafb;
                            border:1px solid #e5e7eb;
                        "
                    >
                </div>

                <button type="submit" class="checkout-btn">
                    Place Order
                </button>

            </div>

        </div>
    </form>
</div>

<script>
    document.querySelectorAll('.payment-method').forEach(method => {
        method.addEventListener('click', () => {
            document.querySelectorAll('.payment-method')
                .forEach(pm => pm.classList.remove('active'));

            method.classList.add('active');
            method.querySelector('input').checked = true;
        });
    });

    function changeLanguage(locale) {
        window.location.href = `/checkout/${locale}`;
    }
</script>

</body>
</html>
