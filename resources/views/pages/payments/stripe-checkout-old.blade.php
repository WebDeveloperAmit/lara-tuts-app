@extends('pages.layout.layout')

@section('content')
<div class="container mt-5">
    <h3>Pay ₹{{ $order->total_amount }}</h3>

    <form id="payment-form">
        <div id="card-element"></div>
        <button id="payBtn" class="btn btn-primary mt-3">
            Pay Now
        </button>
    </form>
</div>

<script src="https://js.stripe.com/v3/"></script>

<script>
    const stripe = Stripe("{{ config('services.stripe.key') }}");
    const elements = stripe.elements();
    const card = elements.create('card');
    card.mount('#card-element');

    document.getElementById('payment-form').addEventListener('submit', async (e) => {
        e.preventDefault();

        const { error, paymentIntent } = await stripe.confirmCardPayment(
            "{{ $intent->client_secret }}",
            {
                payment_method: {
                    card: card
                }
            }
        );

        if (error) {
            window.location.href = "{{ route('checkout.failure', ['locale' => app()->getLocale(), 'uuid' => $order->uuid]) }}";
        } else {
            // ⚠️ DO NOT mark success here
            window.location.href = "{{ route('checkout.stripe.payment.processing') }}";
        }
    });
</script>
@endsection
