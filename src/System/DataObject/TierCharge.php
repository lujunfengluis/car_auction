<?php
namespace App\System\DataObject;

use Symfony\Component\Validator\Constraints as Assert;

class TierCharge
{
    public function __construct(
        #[Assert\AtLeastOneOf([
            new Assert\Blank,
            new Assert\GreaterThanOrEqual(0)
        ])]
        private ?float $min,

        #[Assert\AtLeastOneOf([
            new Assert\Blank,
            new Assert\GreaterThanOrEqual(0)
        ])]
        private ?float $max,

        #[Assert\NotBlank]
        #[Assert\GreaterThanOrEqual(0)]
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