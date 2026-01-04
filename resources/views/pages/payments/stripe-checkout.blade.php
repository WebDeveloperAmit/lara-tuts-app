@extends('pages.layout.layout')

@section('content')

<div class="container mt-5" style="max-width:420px;">
    <h3 class="mb-3">Pay ₹{{ $order->total_amount }}</h3>
    <p class="text-muted">Secure payment powered by Stripe</p>

    <form id="payment-form">
        <div id="payment-element"></div>

        <button id="payBtn" class="btn btn-primary w-100 mt-3">
            Pay ₹{{ $order->total_amount }}
        </button>

        <div id="loader" style="display:none">Processing...</div>
        <div id="error" class="text-danger mt-2"></div>
    </form>

</div>

<script src="https://js.stripe.com/v3/"></script>

<script>
    const stripe = Stripe("{{ config('services.stripe.key') }}");

    const elements = stripe.elements({
        clientSecret: "{{ $intent->client_secret }}"
    });

    const paymentElement = elements.create("payment");
    paymentElement.mount("#payment-element");

    document.getElementById("payment-form").addEventListener("submit", async (e) => {
        e.preventDefault();

        document.getElementById("payBtn").disabled = true;
        document.getElementById("loader").style.display = "block";

        const { error } = await stripe.confirmPayment({
            elements,
            confirmParams: {
                return_url: "{{ route('checkout.stripe.processing', ['locale' => app()->getLocale(), 'uuid' => $order->uuid]) }}"
            }
        });

        if (error) {
            document.getElementById("error").textContent = error.message;
            document.getElementById("loader").style.display = "none";
            document.getElementById("payBtn").disabled = false;
        }
    });

</script>

@endsection
