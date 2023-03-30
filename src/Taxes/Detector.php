<?php

namespace App\Taxes;

class Detector
{
    public function __construct(private readonly float $seuil)
    {
    }

    public function detect(float $amount): bool
    {
        return $amount > $this->seuil;
    }
}
