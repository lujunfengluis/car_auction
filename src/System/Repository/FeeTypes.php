<?php
namespace App\System\Repository;

use App\Model\FeeScheduleInterface;
use App\System\Factory\FeeTypeFactory;

class FeeTypes
{
    public function __construct(
        private FeeScheduleInterface $feeSchedule,
        private FeeTypeFactory $factory
    ) {
    }

    public function listByVehicleType(string $vehicleType): array
    {
        $types = array_filter($this->feeSchedule->list(), fn($t) => $t['vehicle_type'] == $vehicleType);

        return $this->factory->makeList($types);
    }
}