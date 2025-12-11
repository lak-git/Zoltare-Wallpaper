<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

// Legacy placeholder retained for backward compatibility; no longer processes Stripe events.
class StripeWebhookController extends Controller
{
    public function __invoke(): Response
    {
        return response(['error' => 'Stripe webhook disabled'], Response::HTTP_GONE);
    }
}

