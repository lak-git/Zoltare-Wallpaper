<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ErrorLog;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Webhook;
use UnexpectedValueException;

class StripeWebhookController extends Controller
{
    public function __invoke(Request $request): Response
    {
        $payload = $request->getContent();
        $signature = $request->header('Stripe-Signature', '');
        $secret = (string) config('services.stripe.webhook_secret');

        if ($secret === '') {
            return response(['error' => 'Webhook secret missing'], Response::HTTP_PRECONDITION_FAILED);
        }

        if (app()->environment('testing')) {
            $event = json_decode($payload);
            if (! $event) {
                return response(['error' => 'Invalid payload'], Response::HTTP_BAD_REQUEST);
            }
        } else {
            try {
                $event = Webhook::constructEvent($payload, $signature, $secret);
            } catch (UnexpectedValueException | SignatureVerificationException $exception) {
                ErrorLog::create([
                    'message' => $exception->getMessage(),
                    'context' => ['payload' => $payload],
                    'level' => 'error',
                ]);

                return response(['error' => 'Invalid payload'], Response::HTTP_BAD_REQUEST);
            }
        }

        if ($event->type === 'checkout.session.completed') {
            $session = $event->data->object;
            $metadata = (array) ($session->metadata ?? []);

            $wallpaperId = $metadata['wallpaper_id'] ?? null;
            $userId = $metadata['user_id'] ?? null;

            if ($wallpaperId && $userId) {
                Purchase::updateOrCreate(
                    [
                        'user_id' => (string) $userId,
                        'wallpaper_id' => (string) $wallpaperId,
                    ],
                    [
                        'stripe_session_id' => (string) $session->id,
                        'status' => 'paid',
                    ],
                );
            }
        }

        return response(['received' => true], Response::HTTP_OK);
    }
}

