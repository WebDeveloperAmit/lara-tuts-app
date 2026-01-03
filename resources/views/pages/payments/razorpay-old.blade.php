<button id="pay-now">Pay Now</button>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

<script>
    var options = {
        key: "{{ config('services.razorpay.key') }}",
        order_id: "{{ $payment->gateway_order_id }}",
        amount: "{{ $order->total_amount * 100 }}",
        currency: "INR",

        handler: function (response) {
            fetch("{{ route('razorpay.verify') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify(response)
            })
            .then(res => res.json())
            .then(data => {
                window.location.href = data.redirect_url;
            });
        }
    };

    new Razorpay(options).open();

</script>
