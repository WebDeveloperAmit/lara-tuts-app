@extends('pages.layout.layout')

@section('content')

<div class="container mt-5" style="max-width:420px;">
    <h3 class="mb-3">Pay ₹{{ $order->total_amount }}</h3>
    <p class="text-muted">Secure payment powered by Stripe</p>

    <form id="payment-form">
        <div id="payment-element" class="mb-3"></div>
    </form>

    <div id="loader" style="display:none;">
        <p>Processing payment…</p>
    </div>
</div>

<script src="https://js.stripe.com/v3/"></script>

<script>
    
    const stripe = Stripe("{{ config('services.stripe.key') }}");

    const elements = stripe.elements({
        clientSecret: "{{ $intent->client_secret }}"
    });

    const paymentElement = elements.create("payment", {
        layout: "tabs"
    });
    paymentElement.mount("#payment-element");

    // Auto-confirm (Razorpay-like UX)
    document.addEventListener("DOMContentLoaded", async () => {
        const { error } = await stripe.confirmPayment({
            elements,
            confirmParams: {
                return_url: "{{ route('checkout.stripe.payment.processing') }}"
            }
        });

        if (error) {
            window.location.href = "{{ route('checkout.failure', ['locale' => app()->getLocale(), 'uuid' => $order->uuid]) }}";
        }
    });

</script>

@endsection
