<?php

namespace App\Stripe;

use App\Entity\Purchase;

class StripeService
{
    public function __construct(private readonly string $secretKey, private readonly string $publicKey)
    {
    }

    public function getPublicKey()
    {
        return $this->publicKey;
    }

    public function getPaymentIntent(Purchase $purchase)
    {
        \Stripe\Stripe::setApiKey($this->secretKey);

        return \Stripe\PaymentIntent::create(
            [
                'amount' => $purchase->getTotal(),
                'currency' => 'eur'
            ]
        );
    }
}
