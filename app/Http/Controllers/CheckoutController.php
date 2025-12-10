<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\Wallpaper;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function show(Request $request, Wallpaper $wallpaper): RedirectResponse|View
    {
        abort_if($wallpaper->isFree(), Response::HTTP_BAD_REQUEST, 'Wallpaper is free.');

        $user = $request->user();

        abort_unless($user, Response::HTTP_FORBIDDEN);

        $alreadyOwned = Purchase::where('user_id', (string) $user->getKey())
            ->where('wallpaper_id', (string) $wallpaper->getKey())
            ->where('status', 'paid')
            ->exists();

        if ($alreadyOwned) {
            return redirect()->route('gallery')->with('status', 'You already own this wallpaper.');
        }

        return view('pages.checkout', [
            'wallpaper' => $wallpaper,
        ]);
    }

    public function store(Request $request, Wallpaper $wallpaper): RedirectResponse
    {
        abort_if($wallpaper->isFree(), Response::HTTP_BAD_REQUEST, 'Wallpaper is free.');

        $user = $request->user();

        abort_unless($user, Response::HTTP_FORBIDDEN);

        $validator = validator($request->all(), [
            'cardholder_name' => ['required', 'string', 'max:255'],
            'card_number' => ['required', 'digits:16'],
            'postal_code' => ['required', 'string', 'max:20'],
            'card_expiry' => ['required', 'regex:/^\d{2}\/\d{2}$/'],
            'card_pin' => ['required', 'digits:4'],
        ]);

        $validator->after(function ($validator) {
            $expiry = $validator->getData()['card_expiry'] ?? '';

            if (! preg_match('/^(\d{2})\/(\d{2})$/', $expiry, $matches)) {
                return;
            }

            [$full, $month, $year] = $matches;

            $month = (int) $month;
            $year = 2000 + (int) $year;

            if ($month < 1 || $month > 12) {
                $validator->errors()->add('card_expiry', 'Card expiry month is invalid.');
                return;
            }

            $now = Carbon::now();
            $expiryDate = Carbon::create($year, $month, 1)->endOfMonth();

            if ($expiryDate->lt($now->startOfDay())) {
                $validator->errors()->add('card_expiry', 'Card is expired.');
            }
        });

        $validated = $validator->validate();

        $alreadyOwned = Purchase::where('user_id', (string) $user->getKey())
            ->where('wallpaper_id', (string) $wallpaper->getKey())
            ->where('status', 'paid')
            ->exists();

        if ($alreadyOwned) {
            return redirect()->route('gallery')->with('status', 'You already own this wallpaper.');
        }

        Purchase::updateOrCreate(
            [
                'user_id' => (string) $user->getKey(),
                'wallpaper_id' => (string) $wallpaper->getKey(),
            ],
            [
                'status' => 'paid',
            ],
        );

        return redirect()->route('checkout.success', ['highlight' => (string) $wallpaper->getKey()]);
    }
}

