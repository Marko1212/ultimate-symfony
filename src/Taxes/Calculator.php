<?php

namespace App\Taxes;

use Psr\Log\LoggerInterface;

class Calculator
{
    public function __construct(private readonly LoggerInterface $logger, private readonly float $tva)
    {
    }
    public function calcul(float $prix): float
    {
        $this->logger->info("Un calcul a lieu : $prix");
        return $prix * (20 / 100);
    }
}
