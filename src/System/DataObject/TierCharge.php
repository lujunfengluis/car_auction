<?php
namespace App\System\DataObject;

use Symfony\Component\Validator\Constraints as Assert;

class TierCharge
{
    public function __construct(
        #[Assert\GreaterThanOrEqual(0)]
        private ?float $min,

        #[Assert\GreaterThan(0)]
        private ?float $max,

        #[Assert\NotBlank]
        #[Assert\GreaterThan(0)]
        private float $fee,
    ) {
    }

    public function match(float $basePrice): bool
    {
        return (is_null($this->min) || $basePrice >= $this->min) 
            && (is_null($this->max) || $basePrice < $this->max);
    }

    public function getFee(): float
    {
        return $this->fee;
    }
}