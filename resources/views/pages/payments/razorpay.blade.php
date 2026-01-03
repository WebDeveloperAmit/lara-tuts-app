<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

<script>
    
    document.addEventListener('DOMContentLoaded', function () {

        var options = {
            key: "{{ config('services.razorpay.key') }}",
            order_id: "{{ $payment->gateway_order_id }}",
            amount: "{{ $order->total_amount * 100 }}",
            currency: "INR",

            name: "{{ config('app.name') }}",
            description: "Order #{{ $order->order_number }}",

            prefill: {
                name: "{{ $order->first_name }} {{ $order->last_name }}",
                email: "{{ $order->email }}",
                contact: "{{ $order->phone }}"
            },

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
                })
                .catch(() => {
                    window.location.href = "{{ route('checkout.failure', ['locale' => app()->getLocale(), 'uuid' => $order->uuid]) }}";
                });
            },

            modal: {
                ondismiss: function () {
                    // User closed Razorpay popup
                    window.location.href = "{{ route('checkout.failure', ['locale' => app()->getLocale(), 'uuid' => $order->uuid]) }}";
                }
            },

            theme: {
                color: "#0d6efd"
            }
        };

        var rzp = new Razorpay(options);
        rzp.open(); // AUTO OPEN – PROFESSIONAL FLOW
    });

</script>
