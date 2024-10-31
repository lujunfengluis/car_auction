<?php
namespace App\System\Factory;

use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\System\DataObject\{FeeType, TierCharge};
use Exception;

class FeeTypeFactory
{
    public function __construct(
        private ValidatorInterface $validator
    ) {
    }
    
    public function makeList(array $types): array
    {
        return array_map(function($t) {
            return $this->makeType($t);
        }, $types);
    }

    /**
     * @param array $type
     * @return FeeType
     * @throws Exception
     */
    private function makeType(array $type): FeeType
    {
        if (isset($type['tier_charges']) && is_array($type['tier_charges'])) {
            $tierCharges = $this->makeTierCharges($type['tier_charges']);
        }
        
        $type = new FeeType(
            $type['name'],
            $type['vehicle_type'],
            $type['percentage'] ?? null,
            $type['min'] ?? null,
            $type['max'] ?? null,
            $type['fixed'] ?? null,
            $tierCharges ?? null
        );

        $errors = $this->validator->validate($type);

        if (count($errors) > 0) {
            throw new Exception(sprintf('Invalid source data: %s', (string) $errors), 502);
        }

        return $type;
    }

    private function makeTierCharges(array $tiers): array
    {
        return array_map(function($t) {
            return $this->makeTierCharge($t);
        }, $tiers);
    }

    /**
     * @param array $tier
     * @return TierCharge
     * @throws Exception
     */
    private function makeTierCharge(array $tier): TierCharge
    {
        $tierCharge = new TierCharge(
            $tier['min'],
            $tier['max'],
            $tier['fee']
        );

        $errors = $this->validator->validate($tierCharge);

        if (count($errors) > 0) {
            throw new Exception(sprintf('Invalid source data: %s', (string) $errors), 502);
        }

        return $tierCharge;
    }
}