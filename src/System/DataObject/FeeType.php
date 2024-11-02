<?php
namespace App\System\DataObject;

use Symfony\Component\Validator\Constraints as Assert;

class FeeType
{
    public function __construct(
        #[Assert\NotBlank]
        private ?string $name,

        #[Assert\NotBlank]
        #[Assert\Choice(['common', 'luxury'])]
        private ?string $vehicleType,

        #[Assert\Range(
            min: 0,
            max: 100,
        )]
        private ?float $percentage,

        #[Assert\GreaterThan(0)]
        private ?float $min,

        #[Assert\GreaterThan(0)]
        private ?float $max,

        #[Assert\GreaterThan(0)]
        private ?float $fixed,

        #[Assert\All([
            new Assert\Type(TierCharge::class)
        ])]
        private ?array $tierCharges,
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function calculateFee(float $basePrice): float
    {
        $fee = 0;

        if ($this->fixed) {
            $fee = $this->fixed;
        } elseif ($this->percentage) {
            $fee = $basePrice*$this->percentage/100;

            if ($this->min && $fee < $this->min) {
                $fee = $this->min;
            }

            if ($this->max && $fee > $this->max) {
                $fee = $this->max;
            }
        } elseif ($this->tierCharges) {
            $matchedTier = $this->findMatchedTier($this->tierCharges, $basePrice);
            
            if ($matchedTier) {
                $fee = $matchedTier->getFee();
            }
        }

        return round($fee, 2);
    }

    private function findMatchedTier(array $tiers, float $basePrice): ?TierCharge
    {
        $filtered = array_filter($tiers, fn($t) => $t->match($basePrice));

        return $filtered ? reset($filtered) : null;
    }
}