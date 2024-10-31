<?php
namespace App\System\Service;

use App\System\Dto\PriceRequestDto;
use App\System\Repository\FeeTypes;

class Pricing
{
    public function __construct(
        private FeeTypes $feeTypes
    ) {
    }

    public function setFees(PriceRequestDto $request): array
    {
        $price = $request->price;
        $feeItems = $this->feeTypes->listByVehicleType($request->vehicleType);
        $fees = [
            'base' => $price
        ];

        foreach ($feeItems as $item) {
            $fees[$item->getName()] = $this->formatFee($item->calculateFee($price));
        }

        return $fees;
    }

    private function formatFee(float $fee): string
    {
        return '$'.number_format($fee, 2, '.', ',');
    }
}