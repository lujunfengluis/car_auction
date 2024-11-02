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
        $price = round($request->price, 2);
        $feeItems = $this->feeTypes->listByVehicleType($request->vehicleType);
        $fees = [];

        foreach ($feeItems as $item) {
            $fees[$item->getName()] = $item->calculateFee($price);
        }

        $total = $this->formatFee($price + array_sum(array_values($fees)));
        $formattedFees = array_map(function ($fee) {
            return $this->formatFee($fee);
        }, $fees);

        return [
            'fees' => $formattedFees,
            'total' => $total
        ];
    }

    private function formatFee(float $fee): string
    {
        return '$'.number_format($fee, 2, '.', ',');
    }
}