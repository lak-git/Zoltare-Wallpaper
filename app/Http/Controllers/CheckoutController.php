<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\Wallpaper;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Stripe\StripeClient;

class CheckoutController extends Controller
{
    public function __construct(private readonly StripeClient $stripeClient)
    {
    }

    public function __invoke(Request $request, Wallpaper $wallpaper): RedirectResponse
    {
        abort_if(blank(config('services.stripe.secret')), Response::HTTP_SERVICE_UNAVAILABLE, 'Stripe is not configured.');

        abort_if($wallpaper->isFree(), Response::HTTP_BAD_REQUEST, 'Wallpaper is free.');

        $user = $request->user();

        abort_unless($user, Response::HTTP_FORBIDDEN);

        if (Purchase::where('user_id', (string) $user->getKey())
            ->where('wallpaper_id', (string) $wallpaper->getKey())
            ->where('status', 'paid')
            ->exists()
        ) {
            return redirect()->route('gallery')->with('status', 'You already own this wallpaper.');
        }

        $successUrl = config('services.stripe.success_url') ?? route('checkout.success', [], true);
        $cancelUrl = config('services.stripe.cancel_url') ?? route('checkout.cancel', [], true);

        $session = $this->stripeClient->checkout->sessions->create([
            'mode' => 'payment',
            'success_url' => $successUrl.'?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => $cancelUrl,
            'customer_email' => $user->email,
            'metadata' => [
                'user_id' => (string) $user->getKey(),
                'wallpaper_id' => (string) $wallpaper->getKey(),
            ],
            'line_items' => [[
                'price_data' => [
                    'currency' => config('services.stripe.currency', 'usd'),
                    'product_data' => [
                        'name' => $wallpaper->title,
                        'description' => $wallpaper->categoryLabel(),
                    ],
                    'unit_amount' => (int) round($wallpaper->price * 100),
                ],
                'quantity' => 1,
            ]],
        ]);

        Purchase::updateOrCreate(
            [
                'user_id' => (string) $user->getKey(),
                'wallpaper_id' => (string) $wallpaper->getKey(),
            ],
            [
                'stripe_session_id' => $session->id,
                'status' => 'pending',
            ],
        );

        return redirect()->away($session->url);
    }
}

